
<?php
/*
$servername = "sqlXXX.epizy.com"; // thay XXX bằng số server trong info
$username   = "epiz_xxxxxx";      // username do InfinityFree cấp
$password   = "your_password";    // password bạn đặt
$dbname     = "epiz_xxxxxx_dbname"; // database name
*/
$servername = "sql208.epizy.com"; // phải đúng theo MySQL Hostname
$username   = "if0_39872450";     // user được cấp
$password   = "127897tT";         // password khi tạo DB
$dbname     = "if0_39872450_Demo"; // tên DB chính xác

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully!";
?>
