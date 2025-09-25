<?php
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION["AccountID"]) || $_SESSION["ARole"] !== "Admin") {
    header("Location: login.php");
    exit;
}

// Kiểm tra tham số GET
if (!isset($_GET["id"])) {
    header("Location: user_list.php");
    exit;
}

$accountID = $_GET["id"];

// Kết nối DB
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elmsdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Không cho xóa chính admin hiện tại
if ($accountID === $_SESSION["AccountID"]) {
    $_SESSION["error"] = "❌ Bạn không thể xóa chính tài khoản của mình!";
    header("Location: user_list.php");
    exit;
}

// Xóa account
$stmt = $conn->prepare("DELETE FROM Accounts WHERE AccountID=?");
$stmt->bind_param("s", $accountID);

if ($stmt->execute()) {
    $_SESSION["success"] = "✅ Account đã được xóa thành công!";
} else {
    $_SESSION["error"] = "❌ Lỗi xóa account: " . $stmt->error;
}

$stmt->close();
$conn->close();

// Quay về danh sách
header("Location: user_list.php");
exit;
?>
