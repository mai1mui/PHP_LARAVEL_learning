<?php
$servername = "localhost";
$username = "root"; // Thay bằng username MySQL của bạn
$password = ""; // Thay bằng password MySQL của bạn (để trống nếu dùng XAMPP)
$dbname = "bookstore";

// 🔹 1️⃣ Kết nối MySQL
$conn = new mysqli($servername, $username, $password);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// 🔹 2️⃣ Tạo database nếu chưa tồn tại
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
$conn->query($sql);

// Chọn database `bookstore`
$conn->select_db($dbname);

// 🔹 3️⃣ Tạo bảng `books` nếu chưa tồn tại
$sql = "CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    published_year INT NOT NULL
)";
$conn->query($sql);

// 🔹 4️⃣ Thêm dữ liệu (INSERT)
$sql = "INSERT INTO books (title, author, price, published_year) 
        VALUES ('The Great Gatsby', 'F. Scott Fitzgerald', 10.99, 1925)";
$conn->query($sql);

// 🔹 5️⃣ Hiển thị danh sách sách (SELECT)
$sql = "SELECT * FROM books";
$result = $conn->query($sql);
echo "<h3>Book List:</h3>";
while ($row = $result->fetch_assoc()) {
    echo "ID: {$row['id']} - Title: {$row['title']} - Author: {$row['author']} - Price: {$row['price']}<br>";
}

// 🔹 6️⃣ Cập nhật giá sách có id = 1 (UPDATE)
$sql = "UPDATE books SET price = 12.99 WHERE id = 1";
$conn->query($sql);

// 🔹 7️⃣ Xóa sách có id = 1 (DELETE)
$sql = "DELETE FROM books WHERE id = 1";
$conn->query($sql);

// 🔹 Đóng kết nối
$conn->close();
?>
