<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "film";

// Kết nối MySQL (chưa chọn database)
$conn = new mysqli($servername, $username, $password);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Error! " . $conn->connect_error);
}

// Tạo database 'film' nếu chưa tồn tại
$sql = "CREATE DATABASE IF NOT EXISTS film";
if ($conn->query($sql) === TRUE) {
    echo "Database '$dbname' đã được tạo hoặc đã tồn tại.<br>";
} else {
    die("Lỗi khi tạo database: " . $conn->error);
}

// Đóng tạm kết nối
$conn->close();

// Kết nối lại với database 'film'
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối mới
if ($conn->connect_error) {
    die("Kết nối đến database '$dbname' thất bại: " . $conn->connect_error);
}

echo "Kết nối thành công đến database '$dbname'.<br>";

?>
