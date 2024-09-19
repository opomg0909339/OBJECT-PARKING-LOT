<?php
session_start();

if(isset($_POST['backadd']))
{
  header("Location:loginindex.php");
  exit();
}
if (isset($_SESSION["user"])) {
    header("Location:loginindex.php");
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
    <title>註冊</title>
</head>

<body>
        <?php



    if(isset($_POST['new'])){
      if($_POST['account']==null|$_POST['password']==null|$_POST['card']==null|$_POST['car']==null)
      {
        $err="請輸入正確值";
      }
    if ($_SERVER["REQUEST_METHOD"]=="POST")
    {
      $stmt=$pdo->prepare('select * from userinfor where account=? and password=?');
      $stmt->execute(array($_POST["account"],$_POST["password"]));
      $rows=$stmt->fetchAll();
    if (count($rows)>0) 
      {
        $err= "<strong>帳號重複!</strong>請重新註冊.";
      } 
        else 
      {
        $stmt=$pdo->prepare('insert into userinfor(account,password,card,car) values(?,?,?,?)');
        $stmt->execute(array($_POST["account"],$_POST["password"],$_POST["card"],$_POST["car"]));
        $err= ("<strong>註冊成功!</strong>請返回登入.");
      }
  }
}

  ?>
    <div class="vh-100 d-flex justify-content-center align-items-center">
        <div class="container">
		<form style="width:33%;margin:0 auto;padding-top:50px;">
    <?php 
    if(isset($_POST['new'])){
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
                            <form class="mb-3 mt-md-4" action="add.php" method="post">
                                <h2 class="fw-bold mb-2 text-uppercase ">註冊</h2>
                                <p class=" mb-5">註冊新帳號來使用本系統</p>
                                <div class="mb-3">
                                    <label for="addressnew" class="form-label">帳號</label>
                                    <input type="Text" class="form-control" id="account" name="account" placeholder="name@example.com" required>
                                </div>
                                <div class="mb-3">
                                    <label for="passwordnew" class="form-label ">密碼</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="*******" required>
                                </div>
                                <div class="mb-3">
                                    <label for="card" class="form-label ">卡號</label>
                                    <input type="text" class="form-control" id="card" name="card" placeholder="****-****-****-****" required>
                                </div>
                                <div class="mb-3">
                                    <label for="car" class="form-label ">車牌</label>
                                    <input type="text" class="form-control" id="car" name="car" placeholder="AAA-0000" required>
                                </div>
								<h1>　</h1>
                                <div class="d-grid">
                                    <button class="btn btn-outline-dark" type="submit" name="new">註冊</button>
                                </div>
                                <p>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-outline-dark" name="homepage" onclick="location.href='loginmap.php'">返回</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</body></html>
