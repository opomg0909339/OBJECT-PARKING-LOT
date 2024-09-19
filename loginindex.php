<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location:loginmap.php");
    exit();
}
    try {
        $pdo=new PDO("mysql:host=localhost;dbname=car;","root","");
    } catch (PDOException $err) {
        die("資料庫無法連接");
    }
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <title>登入識車辨位</title>
</head>

<body>
    <?php
            $err='';
            if ($_SERVER["REQUEST_METHOD"]=="POST") {
                $stmt=$pdo->prepare('select * from userinfor where account=? and password=?');
                $stmt->execute(array($_POST["account"],$_POST["password"]));
                $rows=$stmt->fetchAll();
                if (count($rows)>0) {
                    $_SESSION["user"]=$rows[0]["ID"];
                    $_SESSION["account"]=$rows[0]["account"];
                    $_SESSION["car"]=$rows[0]["car"];
                    header("Location:loginmap.php");
                    exit();
                } else {
                    $err="帳號/密碼錯誤";
                }
            }
        ?>

    <div class="vh-100 d-flex justify-content-center align-items-center">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="border border-3 border-primary"></div>
                    <div class="card bg-white shadow-lg">
                        <div class="card-body p-5">
                            <form class="mb-3 mt-md-4" action="loginindex.php" action="loginmap.php" method="post">
                                <h2 class="fw-bold mb-2 text-uppercase ">識車辨位</h2>
                                <p class=" mb-5">請輸入帳號和密碼登入系統</p>
                                <?php echo "<p style='color:#f00'>$err </p>";?>
                                <div class="mb-3">
                                    <label for="account" class="form-label">帳號</label>
                                    <input type="Text" class="form-control" id="account" name="account" placeholder="name@example.com">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label ">密碼</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="*******">
                                </div>
                                <p class="small"><a class="text-primary" href="forget.php">忘記密碼?</a></p>
                                <div class="d-grid">
                                    <button class="btn btn-outline-dark" type="submit">登入</button>
                                </div>
                            </form>
                            <div>
                                <p class="mb-0  text-center">沒有帳號? <a href="add.php" class="text-primary fw-bold">註冊</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</body></html>
