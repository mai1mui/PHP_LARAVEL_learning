<?php
session_start();
    if(isset($_SESSION["email"]) == null ||
       isset($_SESSION["username"]) == null ||
       isset($_SESSION["amount"]) == null){
        header(header: "location: demo_info-payment.php");
       };
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Nhận Thanh Toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2 class="mb-4">Xác Nhận Thông Tin Thanh Toán</h2>
    <div class="card p-4 shadow">
        <p><strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>
        <p><strong>Tên Người Dùng:</strong> <?php echo $_SESSION['username']; ?></p>
        <p><strong>Số Tiền:</strong> $<?php echo $_SESSION['amount']; ?></p>

        <form action="confirm.php" method="post">
            <button type="submit" class="btn btn-success">Xác Nhận Thanh Toán</button>
        </form>
        <a href="demo_info-payment.php" class="btn btn-warning mt-3">Quay lại chỉnh sửa</a>
    </div>

</body>
</html>