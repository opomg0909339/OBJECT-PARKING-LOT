<html>
    <?php
   session_start();
   if (!isset($_SESSION["user"])) {
       header("Location:loginindex.php");
       exit();
   }
   try {
       $pdo=new PDO("mysql:host=localhost;dbname=car;","root","");
   } catch (PDOException $err) {
       die("資料庫無法連接");
   }

   if (isset($_POST['exit'])) {
    unset($_SESSION["user"]);
    header("Location:loginindex.php");
    exit();
  }
  
  if(isset($_POST["re"])){
$_SESSION["place"]=$_POST["re"];
}
  if (isset($_POST['re'])) {
    header("Location:reserve.php");
    exit();
  }
  
  if (isset($_POST['center'])) {
    header("Location:center.php");
    exit();
  }
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    <title>識車辨位</title>
    <style>
        #map {
            height: 100vh;
            display: flex;
            flex-flow: column;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light border border-2 border-top-0 border-end-0 border-start-0 border-primary navbar-white p-4 shadow rounded">
        <div class="container">
            <a class="navbar-brand">識車辨位</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <form method="post" action="loginmap.php" style="display:inline;">
                            <button type="submit" class="btn" name="homepage">主頁</button>
                        </form>
                    </li>

                    <li class="nav-item">
                        <form method="post" action="center.php" style="display:inline;">
                            <button type="submit" class="btn" name="center">繳費</button>
                        </form>
                    </li>
					
					<li class="nav-item">
                        <form method="post" action="history.php" style="display:inline;">
                            <button type="submit" class="btn" name="history">歷史紀錄</button>
                        </form>
                    </li>
					
					<li class="nav-item">
                        <form method="post" action="recar.php" style="display:inline;">
                            <button type="submit" class="btn" name="recar">變更車牌</button>
                        </form>
                    </li>
					
					<li class="nav-item">
                        <form method="post" action="recard.php" style="display:inline;">
                            <button type="submit" class="btn" name="recard">變更卡號</button>
                        </form>
                    </li>

                    <li class="nav-item">
                        <form method="post" action="forget.php" style="display:inline;">
                            <button type="submit" class="btn" name="repassword">修改密碼</button>
                        </form>
                    </li>
                </ul>
                <form action="loginmap.php" method="post">
                    <button class="btn btn-outline-primary" type="submit" name="exit">登出</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="alert alert-primary text-center" role="alert">
        <?php

$user = $_SESSION['account'];
echo "使用者:".$user;

date_default_timezone_set('Asia/Taipei');
$time=date("Y-m-d H:i",time());
echo '<br>'.$time;//顯示目前時間
$stmt=$pdo->prepare('select * from stop where LOCATION=?');
$stmt->execute(array('710台南市永康區正南一街157-2號'));
$rows=$stmt->fetchAll();
foreach($rows as $r)
if($r['DATE']==$time){
$map1="red";}
else
{
  $map1="green";
}
$stmt=$pdo->prepare('select * from reserve where same=?');
$stmt->execute(array('1'));


?>
    </div>
    <div id="map"></div>
    <script>
        //23.02550590840316, 120.22638243229096

        var map = L.map('map').setView([23.02550590840316, 120.22638243229096], 17);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        var greenIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var redIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41],

        });

        var greyIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-grey.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41],

        });


        //var marker = L.marker([23.025488579043696, 120.22752814973715]).addTo(map);
        //marker.bindPopup("<form method='post' action='loginmap.php' action='pay.php'><b>710台南市永康區中正二街16-100</b><br><a target='_blank' href='https://goo.gl/maps/cjsXp7yTS5Dr5i5y5'>導航至此</a><br><button type='submit' class='btn btn-primary' value='710台南市永康區中正二街16-100' name='re'>預約車位</button><br></form>").openPopup();

        <?php
        date_default_timezone_set('Asia/Taipei');
        $time=date("Y-m-d H:i",time());
        //echo '<br>'.$time;//顯示目前時間
        $map1="";
        $stmt=$pdo->prepare('select * from stop where DATE=?');
        $stmt->execute(array($time));
        $rows=$stmt->fetchAll();
        if(count($rows)>0)
        {
          echo"var marker1 = L.marker([23.025535265964415, 120.22750692834475] , {icon:redIcon}).addTo(map);";
        }
        else
        {
          echo "var marker = L.marker([23.025535265964415, 120.22750692834475] , {icon:greenIcon}).addTo(map);";
        }
        $stmt=$pdo->prepare('select * from reserve where same=?');
        $stmt->execute(array("1"));
        $rows=$stmt->fetchAll();
        if(count($rows)>0)
        {
          echo"var marker1 = L.marker([23.025535265964415, 120.22750692834475] , {icon:redIcon}).addTo(map);";
        }
        ?>
        
        
        
        marker.bindPopup("<form method='post' action='loginmap.php' action='pay.php'><b>710台南市永康區中正二街16-100</b><br><a target='_blank' href='https://maps.app.goo.gl/TMt5hC6LLQASkEAK6'>導航至此</a><br><button type='submit' class='btn btn-primary' value='710台南市永康區正南一街157-2號' name='re'>預約車位</button><br></form>").openPopup(); 
        marker1.openPopup();
        





        map.addControl(searchControl);
        //var marker = L.marker([23.025488579043696, 120.22752814973715]).addTo(map);
        //marker.bindPopup("<form method='post' action='loginmap.php'><b>710台南市永康區中正二街16-100</b><br><a target='_blank' href='https://goo.gl/maps/cjsXp7yTS5Dr5i5y5'>導航至此</a><br><button type='submit' class='btn btn-primary' value='710台南市永康區中正二街16-100' name='re'>預約車位</button><br></form>").openPopup();
        //var marker = L.marker([23.02550590840316, 120.22638243229096]).addTo(map);
        //marker.bindPopup("<form method='post' action='loginmap.php'><b>710台南市永康區正南一街157-2號</b><br><a target='_blank' href='https://goo.gl/maps/WrYjhXzcoU3BL4Ev9'>導航至此</a><br><button type='submit' class='btn btn-primary' value='710台南市永康區正南一街157-2號' name='re'>預約車位</button><br></form>").openPopup();
        //map.addControl(searchControl);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</body></html>
