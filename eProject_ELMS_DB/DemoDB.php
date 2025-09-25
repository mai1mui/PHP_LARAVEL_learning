<?php
// Cấu hình kết nối tới MySQL
$servername = "localhost"; // Thường là localhost nếu dùng XAMPP/WAMP
$username   = "root";      // Tên user MySQL, mặc định là root
$password   = "";          // Mật khẩu MySQL, mặc định rỗng trên XAMPP/WAMP
$dbname     = "elmsdb";    // Tên database vừa tạo/import

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully!";
?>
