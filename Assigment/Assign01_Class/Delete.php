<?php
// Kiểm tra đăng nhập
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit();
}

// Kết nối MySQL + chọn DB luôn
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "PhoneBrandDB";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Xử lý xoá brand
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['PBID'])) {
    $PBID = $_POST['PBID'];

    $stmt = $conn->prepare("DELETE FROM PhoneBrand WHERE PBID = ?");
    $stmt->bind_param("i", $PBID);
    $stmt->execute();
    $stmt->close();
}

// Đóng kết nối và quay lại trang danh sách
$conn->close();
header("Location: Index.php");
exit();
?>
