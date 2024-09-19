<?php
session_start();
try {
    $pdo = new PDO("mysql:host=localhost;dbname=car;", "root", "");
} catch (PDOException $err) {
    die("資料庫無法連接");
}

$successMessage = ""; // 初始化成功提示消息

if (isset($_POST['pay'])) {
    $stmt = $pdo->prepare('select * from stop where ID=?');
    $stmt->execute(array($_POST['pay']));
    $serid = $stmt->fetchAll();
    foreach ($serid as $s) {
        $nowpay = $s["StopTime"] * 10; //算要扣多少錢
    }

    if (isset($_SESSION['user'])) {
        $userid = $_SESSION['user'];
    } else {
        // 处理 $_SESSION['user'] 未设置的情况
    }

    $err = '';

    if (isset($userid)) {
        // 获取 $userid 对应的 $money
        $stmt = $pdo->prepare('select * from userinfor where ID=?');
        $stmt->execute(array($userid));
        $now = $stmt->fetchAll();
        foreach ($now as $s) {
            $money = $s["money"]; //抓資料庫的目前金額
        }

        if ($money >= $nowpay) {
            $money = $money - $nowpay;
            $stmt = $pdo->prepare('update userinfor set money=? where ID=?');
            $stmt->execute(array($money, $userid));

            $stoppay = 1;
            $stmt = $pdo->prepare('update stop set stoppay=? where ID=?');
            $stmt->execute(array($stoppay, $_POST['pay']));

            $successMessage = "付款成功"; // 設置成功提示消息
        } else {
            $err = "餘額不足";
        }
    } else {
        // 处理 $userid 未设置的情况
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>識車辨位 - 會員中心</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <!-- Bootstrap 5 Icon CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
</head>

<body>
    <?php
    $user = $_SESSION['account'];
    $car = $_SESSION['car'];
    $userid = $_SESSION['user'];
    ?>
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
                <form action="loginmap.php">
                    <button class="btn btn-outline-primary" type="submit" name="exit">登出</button>
                </form>
            </div>
            <script>
                function submitForm(formName) {
                    document.forms[formName].submit();
                }
            </script>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col ps-md-2 pt-2">
                <div class="page-header pt-3">
                    <h2>歷史停車紀錄</h2>
                    <?php
                    // 获取用户信息
                    if (isset($userid)) {
                        $stmtUser = $pdo->prepare('SELECT * FROM userinfor WHERE ID=?');
                        $stmtUser->execute([$userid]);
                        $userInfo = $stmtUser->fetch(PDO::FETCH_ASSOC);

                        // 显示用户信息的警告框
                        echo '<div class="alert alert-primary" role="alert">';
                        echo '用戶ID: ' . $userInfo['account'] . '　';
                        echo '車牌號: ' . $userInfo['car'] . '　';
                        echo '卡號: ' . $userInfo['card'] . '　';
                        echo '金額: ' . $userInfo['money'] . '$';
                        echo '</div>';
                    }

                    // 显示停车记录
                    $stmt = $pdo->prepare('SELECT * FROM stop WHERE STOPCAR=?');
                    $stmt->execute([$car]);
                    $rows = $stmt->fetchAll();

                    echo '<div class="row">';

                    foreach ($rows as $r) {
                        $stop = $r["Stoppay"] == 0 ? "尚未繳費" : "已繳費";
                        $pay = $r["StopTime"] * 10;
                        if($r["Stoppay"]==1 && $r["STOPCAR"]==$car)
                        {

                        echo '<div class="col-md-4">';
                        echo '<div class="card shadow">';
                        echo '<div class="card-body text-start">';
                        echo '<h5 class="card-title">訂單編號 ' . $r["ID"] . '</h5>';
                        echo '<p class="card-text"><i class="bi bi-calendar-check mr-3"></i> 日期 ' . $r["DATE"] . '</p>';
                        echo '<p class="card-text"><i class="bi bi-geo-alt mr-3"></i> 地點 ' . $r["LOCATION"] . '</p>';
                        echo '<p class="card-text"><i class="bi bi-distribute-horizontal mr-3"></i> 車位 ' . $r["PLACE"] . '</p>';
                        echo '<p class="card-text"><i class="bi bi-card-list mr-3"></i> 車牌 ' . $r["STOPCAR"] . '</p>';
                        echo '<p class="card-text"><i class="bi bi-currency-dollar mr-3"></i> 費用 ' . $pay . '</p>';
                        echo '<p class="card-text"><i class="bi bi-cash-coin mr-3"></i> 付款狀態 ' . $stop . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        }
                    }

                    echo '</div>';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>

</html>
