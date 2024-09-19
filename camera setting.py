import cv2
import threading
import pytesseract
from picamera import PiCamera
from time import sleep
import datetime
import RPi.GPIO as GPIO
import re
import mysql.connector

# 設 GPIO 模式為BCM
GPIO.setmode(GPIO.BCM)

# 設 GPIO 引腳號
ledr = 17 #9、11
ledg = 18 #12、14

# 設 GPIO 引腳為輸出
GPIO.setup(ledr, GPIO.OUT)
GPIO.setup(ledg, GPIO.OUT)

# 初始化鏡頭
camera = PiCamera()

# 設置鏡頭解析度和對焦
camera.resolution = (640, 480)
camera.start_preview()
# 等待鏡頭對焦穩定
sleep(2)  

# 建立與資料庫的連線
conn = mysql.connector.connect(
    host='192.168.63.55',   # 電腦ip
    user='raspberry',   # 資料庫權限名稱
    password='',
    database='car'
)
cursor = conn.cursor(buffered=True)
# 設定 pytesseract 的 OCR 路徑
pytesseract.pytesseract.tesseract_cmd = r'/usr/bin/tesseract'

def preprocess_image(image):
    # 圖像轉為灰度圖像
    gray_img = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

    # 高斯模糊去除噪點
    blurred_img = cv2.GaussianBlur(gray_img, (5, 5), 0)

    # 二值化處理
    _, threshold_img = cv2.threshold(blurred_img, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)

    # 圖像平滑處理技術
    smoothed_img = cv2.medianBlur(threshold_img, 3)

    return smoothed_img

def recognize_license_plate(image):
    # 進行影像前處理
    preprocessed_img = preprocess_image(image)

    # 使用 pytesseract 進行 OCR 識別
    plate_number = pytesseract.image_to_string(preprocessed_img, config='--psm 7')
    # 過濾特殊符號和小寫字母
    plate_number = re.sub(r'[^A-Z0-9]', '', plate_number)
    # 判斷車牌號碼長度是否在指定範圍內
    if len(plate_number) >= 6 and len(plate_number) <= 7:
        return plate_number
    else:
        return ""

def find_license_plate(image):
    # 圖像轉為灰度圖像
    gray_img = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

    # 使用Canny邊緣檢測找到圖像邊緣
    edges = cv2.Canny(gray_img, 100, 200)

    # 進行輪廓檢測
    _, contours, _ = cv2.findContours(edges.copy(), cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

    # 找到最大的輪廓
    max_contour = max(contours, key=cv2.contourArea)

    # 獲取輪廓的外接矩形
    x, y, w, h = cv2.boundingRect(max_contour)

    # 在圖像上畫出輪廓的外接矩形
    cv2.rectangle(image, (x, y), (x + w, y + h), (0, 255, 0), 2)

    # 裁剪出車牌區域
    plate_img = image[y:y + h, x:x + w]

    return plate_img

# 控制 LED 亮、滅
GPIO.output(ledr, GPIO.LOW)
GPIO.output(ledg, GPIO.HIGH)

while True:
    camera.capture('car_plate.jpg')
    # 讀取圖像
    image = cv2.imread('car_plate.jpg')

    # 抓取車牌邊框
    plate_image = find_license_plate(image)

    # 進行車牌識別
    result = recognize_license_plate(image)

    # 列印識別結果
    print("車牌號碼:", result)

    # 查詢 userinfor 資料表中的 car
    select_query = "SELECT car FROM userinfor"
    cursor.execute(select_query)
    user_cars = cursor.fetchall()
    for user_car in user_cars:
        if result == user_car[0] or result == "":
            # 將結果插入資料庫
            insert_query = "INSERT INTO stop (STOPCAR) VALUES (%s)"
            cursor.execute(insert_query, (result,))
            conn.commit()
            update_query = "UPDATE stop SET STOPCAR = %s WHERE PLACE = 'A車位'"
            cursor.execute(update_query, (result,))
            conn.commit()
            delete_query = "DELETE FROM stop WHERE PLACE = '0'"
            cursor.execute(delete_query)
            conn.commit() 
            if result == user_car[0]:
                #日期(年、月、日、時、分)
                current_time = datetime.datetime.now()
                formatted_time = current_time.strftime("%Y-%m-%d %H:%M")
                date_query = "INSERT INTO stop (DATE) VALUES (%s)"
                cursor.execute(date_query, (formatted_time,))
                conn.commit()
                update_query = "UPDATE stop SET DATE = %s WHERE PLACE = 'A車位'"
                cursor.execute(update_query, (formatted_time,))
                conn.commit()
                delete_query = "DELETE FROM stop WHERE PLACE = '0'"
                cursor.execute(delete_query)
                conn.commit()
            # 查詢 stop 資料表中的 STOPCAR
            stopcar_query = "SELECT STOPCAR FROM stop"
            cursor.execute(stopcar_query)
            stopcar = cursor.fetchone()[0]
            # 查詢 reserve 資料表中的 RESERVECAR
            reserve_query = "SELECT RESERVECAR FROM reserve"
            cursor.execute(reserve_query)
            reserve_car = cursor.fetchone()[0]
            # 查詢 reserve 資料表中的 Count
            reservecount = "SELECT Count FROM reserve"
            cursor.execute(reservecount)
            reserve_count = cursor.fetchone()[0]
            # 查詢 reserve 資料表中的 pay
            reservepay = "SELECT pay FROM reserve"
            cursor.execute(reservepay)
            reserve_pay = cursor.fetchone()[0]
            # 查詢 reserve 資料表中的 same
            reservesame = "SELECT same FROM reserve"
            cursor.execute(reservesame)
            reserve_same = cursor.fetchone()[0]
            
            if reserve_count != 0:
                # 計算預約需付多少
                reservetotal = 30 * reserve_count
                update_reservepay = "UPDATE reserve SET pay = %s"
                cursor.execute(update_reservepay, (reservetotal,))
                conn.commit()
            # 控制 LED 亮、滅
            if reserve_count != 0 and reserve_car != "":
                GPIO.output(ledr, GPIO.HIGH)
                GPIO.output(ledg, GPIO.LOW)
                # 將 reserve 表中的 same 修改為1(red)
                reserve_same = "UPDATE reserve SET same = '1'"
                cursor.execute(reserve_same)
                conn.commit()

            rtc = reserve_count * 10
            #預約計時
            for reserve_total_count in range(reserve_count * 10):
                sleep(1)
                # 讀取圖像
                image = cv2.imread('car_plate.jpg')
                # 抓取車牌邊框
                plate_image = find_license_plate(image)
                # 進行車牌識別
                result = recognize_license_plate(image)
                # 列印識別結果
                print("車牌號碼:", result)
                rtc -= 1
                if  rtc < 1:
                    recount = "UPDATE reserve SET Count = '0'"
                    cursor.execute(recount)
                    conn.commit()
                    break
            if result == reserve_car or reserve_car == "" or reserve_count == 0:
                GPIO.output(ledr, GPIO.LOW)
                GPIO.output(ledg, GPIO.HIGH)
                # 將 reserve 表中的 same 修改為0(green)
                reserve_same = "UPDATE reserve SET same = '0'"
                cursor.execute(reserve_same)
                conn.commit()
                
            if stopcar == user_car[0]:
                # 更新 stop 表中的 Time
                stoptime_update = "UPDATE stop SET Time = Time + 1"
                cursor.execute(stoptime_update)
                conn.commit()
                # 更新 stop 表中的 StopTime
                FinalStopTime_update = "UPDATE stop SET StopTime = StopTime + 1"
                cursor.execute(FinalStopTime_update)
                conn.commit()
                sleep(5)
            elif stopcar == "":
                # 將 stop 表中的 Time 修改為預設值
                stopretime = "UPDATE stop SET Time = '0'"
                cursor.execute(stopretime)
                conn.commit()
            # 查詢 stop 資料表中的 Time
            stoptime_query = "SELECT Time FROM stop"
            cursor.execute(stoptime_query)
            stop_time = cursor.fetchone()[0]
            if result == user_car[0] and stop_time == 0:
                #日期(年、月、日、時、分)
                current_time = datetime.datetime.now()
                formatted_time = current_time.strftime("%Y-%m-%d %H:%M")
                date_query = "INSERT INTO stop (DATE) VALUES (%s)"
                cursor.execute(date_query, (formatted_time,))
                conn.commit()
                update_query = "UPDATE stop SET DATE = %s WHERE PLACE = 'A車位'"
                cursor.execute(update_query, (formatted_time,))
                conn.commit()
                delete_query = "DELETE FROM stop WHERE PLACE = '0'"
                cursor.execute(delete_query)
                conn.commit()

 # 關閉與資料庫的連線
cursor.close()
conn.close()