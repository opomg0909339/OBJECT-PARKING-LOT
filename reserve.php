<?php
session_start();

$err = ""; // 用于存储错误或成功消息

try {
    $pdo = new PDO("mysql:host=localhost;dbname=car;", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $err) {
    die("資料庫無法連接");
}

$user = $_SESSION['account'];
$car = $_SESSION['car'];
$place = $_SESSION['place'];

if (isset($_POST['go'])&&$_POST["stoptime"]!=null) {
    $count=$_POST['stoptime']/10;
    //計算計費次數
    date_default_timezone_set('Asia/Taipei');
    $time=time();
    $time=date("Y/m/d",$time);
    $stmt=$pdo->prepare('update reserve set user=?,stoptime=?,locationtime=?,RESERVECAR=?,count=?,same=? where ID=?');
    $stmt->execute(array($user,$time,$_POST['stoptime'],$car,$count,1,1));
    $stmtt=$pdo->prepare('insert into stop(DATE,LOCATION,STOPCAR,PLACE,StopTime,Stoppay) values(?,?,?,?,?,?)');
    $stmtt->execute(array($time,$place,$car,"A車位",$count,0));

    // 判断是否插入成功，然后设置相应的提示信息
    if ($stmt->rowCount() > 0) {
        $err = "預約成功！";
    } else {
        $err = "預約失敗，請重試。";
    }
}

date_default_timezone_set('Asia/Taipei');
$time = time();
?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <title>預約車位</title>
</head>

<body>
    <div class="vh-100 d-flex justify-content-center align-items-center">
        <div class="container">
            <form style="width:33%;margin:0 auto;padding-top:50px;">
			<?php
                // 根据预订结果显示提示框
                if (isset($_POST['go'])) {
                    echo '<div class="alert border border-2 border-primary alert-primary alert-dismissible fade show" role="alert">' . $err . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                }
                ?>
            </form>
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="border border-3 border-primary"></div>
                    <div class="card bg-white shadow-lg">
                        <div class="card-body p-5">
                            <form class="mb-3 mt-md-4" method="post" action="">
                                <h2 class="fw-bold mb-2 text-uppercase ">預約車位</h2>
                                <p class=" mb-5">請輸入抵達時間和停車時間</p>
                                <div class="mb-3">
                                    <label for="time" class="form-label">抵達時間</label>
                                    <input type="time" class="form-control" id="locationtime" name="time" required>
                                </div>
                                <div class="mb-3">
                                    <label for="newcard" class="form-label">停車時間</label>
                                    <input type="text" class="form-control" id="stoptime" name="stoptime" required>
                                </div>
                                <p>　</p>
                                <div class="d-grid">
                                    <button class="btn btn-outline-dark" type="submit" name="go">預約</button>
                                </div>
                            </form>
                            <form style="width:33%;margin:0 auto;padding-top:10px;" method="post" action="loginindex.php">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-outline-dark" name="backforget">返回</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</body>

</html>
