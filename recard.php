<?php
session_start();
try {
    $pdo = new PDO("mysql:host=localhost;dbname=car;", "root", "");
} catch (PDOException $err) {
    die("資料庫無法連接");
}

$alertMessage = ""; // 初始化提示資訊

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['re'])) {
    if (empty($_POST["newcard"]) || empty($_POST['card'])) {
        $alertMessage = '<div class="alert border border-2 border-danger alert-danger alert-dismissible fade show" role="alert">請輸入正確值<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    } else {
        $cardToChange = $_POST['card'];
        $newCard = $_POST['newcard'];

        // 查詢用户訊息
        $stmt = $pdo->prepare('SELECT * FROM userinfor WHERE card = ?');
        $stmt->execute([$cardToChange]);
        $rows = $stmt->fetchAll();

        if (count($rows) > 0) {
            // 更新用户訊息
            $stmt = $pdo->prepare('UPDATE userinfor SET card = ? WHERE card = ?');
            $stmt->execute([$newCard, $cardToChange]);
            $alertMessage = '<div class="alert border border-2 border-success alert-success alert-dismissible fade show" role="alert">更改成功<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        } else {
            $alertMessage = '<div class="alert border border-2 border-danger alert-danger alert-dismissible fade show" role="alert">查無此卡號<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }
    }
}
?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <title>變更卡號</title>
</head>

<body>
    <div class="vh-100 d-flex justify-content-center align-items-center">
        <div class="container">
            <form style="width:33%;margin:0 auto;padding-top:50px;">
                <?php echo $alertMessage; ?>
            </form>
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="border border-3 border-primary"></div>
                    <div class="card bg-white shadow-lg">
                        <div class="card-body p-5">
                            <form class="mb-3 mt-md-4" method="post" action="">
                                <h2 class="fw-bold mb-2 text-uppercase ">變更卡號</h2>
                                <p class=" mb-5">請輸入卡號來更換卡號</p>
                                <div class="mb-3">
                                    <label for="card" class="form-label">原卡號</label>
                                    <input type="text" class="form-control" id="card" name="card" placeholder="123456789" required>
                                </div>
                                <div class="mb-3">
                                    <label for="newcard" class="form-label">新卡號</label>
                                    <input type="text" class="form-control" id="newcard" name="newcard" placeholder="987654321" required>
                                </div>
                                <p>　</p>
                                <div class="d-grid">
                                    <button class="btn btn-outline-dark" type="submit" name="re">變更卡號</button>
                                </div>
                            </form>
                            <form style="width:33%;margin:0 auto;padding-top:10px;" method="post" action="loginmap.php">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-outline-dark" name="backforget">返回</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </
