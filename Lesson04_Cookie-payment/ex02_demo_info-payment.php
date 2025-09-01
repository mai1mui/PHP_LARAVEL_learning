<?php
    if($_SERVER ["REQUEST_METHOD"] == "POST"){
        $email = $_POST["email"];
        $username = $_POST["username"];
        $amount = $_POST["amount"];
        session_start();
        $_SESSION["email"] = $email;
        $_SESSION["username"] = $username;
        $_SESSION["amount"] = $amount;
        header(header: "location: demo_payment.php");
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhập Thông Tin Thanh Toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="mb-4">Nhập Thông Tin Thanh Toán</h2>
    <form method="post" class="card p-4 shadow">
        <div class="mb-3">
            <label class="form-label">Email:</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Username:</label>
            <input type="text" name="username" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Số Tiền:</label>
            <input type="number" name="amount" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Tiếp Tục</button>
    </form>

</body>
</html>