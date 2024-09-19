<?php
session_start();

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
    if(isset($_POST['re'])){
      if($_POST["address"]==null|$_POST['password']==null)
      {
        $err="請輸入正確值";
      }
    if ($_SERVER["REQUEST_METHOD"]=="POST")
    {
      $stmt=$pdo->prepare('select * from userinfor where account=?');
      $stmt->execute(array($_POST["address"]));
      $rows=$stmt->fetchAll();
    if (isset($_POST["address"]) && isset($_POST["password"])) {
    // 檢查是否提交了 "address" 和 "password" 項目

    $address = $_POST["address"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare('SELECT * FROM userinfor WHERE account = ?');
    $stmt->execute([$address]);
    $row = $stmt->fetch();

    if ($row) {
        // 找到匹配的帳號，執行更新操作
        $stmt = $pdo->prepare('UPDATE userinfor SET password = ? WHERE account = ?');
        $stmt->execute([$password, $address]);

        $err = '<div class="alert border border-2 border-success alert-success alert-dismissible fade show" role="alert">更改成功
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    } else {
        // 找不到匹配的帳號
        $err = '<div class="alert border border-2 border-danger alert-danger alert-dismissible fade show" role="alert">查無此帳號
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
} else {
    // 如果未提交 "address" 和 "password"
    $err = '<div class="alert border border-2 border-warning alert-warning alert-dismissible fade show" role="alert">請輸入正確值
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'; // 或者您可以設置一個相應的錯誤消息
}
  }
}
  ?>
    <div class="vh-100 d-flex justify-content-center align-items-center">
        <div class="container">
            <form style="width:33%;margin:0 auto;padding-top:50px;">
    <?php 
    if(isset($_POST['re'])){
        echo $err;
    }
    ?>
</form>
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="border border-3 border-primary"></div>
                    <div class="card bg-white shadow-lg">
                        <div class="card-body p-5">
                            <form class="mb-3 mt-md-4" action="forget.php" method="post">
                                <h2 class="fw-bold mb-2 text-uppercase ">忘記密碼</h2>
                                <p class=" mb-5">請輸入帳號和密碼重建帳號</p>
                                <div class="mb-3">
                                    <label for="addressnew" class="form-label">帳號</label>
                                    <input type="Text" class="form-control" id="address" name="address" placeholder="name@example.com" required>
                                </div>
                                <div class="mb-3">
                                    <label for="passwordnew" class="form-label ">密碼</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="*******" required>
                                </div>
                                <p>　</p>
                                <div class="d-grid">
                                    <button class="btn btn-outline-dark" type="submit" name="re">重建帳號</button>
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
