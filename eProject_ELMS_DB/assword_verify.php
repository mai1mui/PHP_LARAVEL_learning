<?php
$servername = "localhost";
$username = "root";  // thay đổi theo user MySQL
$password = "";      // thay đổi theo password MySQL
$dbname = "elmsdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy tất cả account
$sql = "SELECT AccountID, Pass FROM Accounts";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $accountID = $row['AccountID'];
        $plainPass = $row['Pass'];

        // Hash mật khẩu
        $hashedPass = password_hash($plainPass, PASSWORD_DEFAULT);

        // Cập nhật password
        $update = $conn->prepare("UPDATE Accounts SET Pass = ? WHERE AccountID = ?");
        $update->bind_param("ss", $hashedPass, $accountID);
        $update->execute();
    }
    echo "✅ All passwords hashed successfully!";
} else {
    echo "No accounts found.";
}

$conn->close();
?>
