<?php
session_start();

if (!isset($_SESSION['email']) || !isset($_SESSION['username']) 
    || !isset($_SESSION['amount'])) {
    header("Location: index.php");
    exit();
}
// Xóa session sau khi hoàn tất thanh toán
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán Thành Công</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5 text-center">

    <h2 class="mb-4 text-success">Thanh Toán Thành Công!</h2>
    <p class="fs-5">Cảm ơn bạn đã sử dụng dịch vụ.</p>
    <a href="index.php" class="btn btn-primary">Quay về trang chính</a>

</body>
</html>