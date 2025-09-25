<?php 
//session_start();
//if (!isset($_SESSION["userid"]) || $_SESSION["userid"] !== "user") {
//    header("Location: login.php");
//    exit;
//}
//
//
//$course = $_GET['course'] ?? "unknown";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lession Index</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #8e44ad; }
        ul { list-style-type: none; padding: 0; }
        li { margin: 8px 0; }
        .btn { 
            display: inline-block; padding: 10px 20px; 
            background: #e67e22; color: #fff; 
            text-decoration: none; border-radius: 6px; 
        }
        .btn:hover { background: #d35400; }
    </style>
</head>
<body>
    <h1>Danh sách bài học - <?php echo strtoupper($course); ?></h1>
    <ul>
        <li><a href="#">Bài học 1: Giới thiệu cơ bản</a></li>
        <li><a href="#">Bài học 2: Thực hành demo</a></li>
    </ul>

    <!-- Nút đăng ký cuối trang -->
    <div style="margin-top: 20px;">
        <a href="register.php?course=<?php echo $course; ?>" class="btn">Đăng ký khóa học này</a>
    </div>

    <p style="margin-top:20px;"><a href="home.php">Quay về trang chủ</a></p>
</body>
</html>
