<?php
$serverName = "localhost";
$username = "root";
$password = "";
$dbName = "patientdb";
//tao connect
$conn = new mysqli($serverName, $username, $password, $dbName);
// if ($conn->connect_error) {
//     //Sử dụng hàm die cho phép chương trình dừng lại tại
//     //thời điểm gặp lỗi và không thực hiện đoạn code phía dưới
//     die("Connect failed " . $conn->connect_error);

// }
// echo "Connect thanh công mỹ mãn";
?>