<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "film";

// Kết nối MySQL với database 'film'
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối đến database '$dbname' thất bại: " . $conn->connect_error);
}

//xu ly xoa benh nhan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt =$conn->prepare("delete from movies where id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $stmt->close();    
}
// Đóng kết nối va quay lại read.php
$conn->close();
header(header:"location:read.php");
exit();
?>