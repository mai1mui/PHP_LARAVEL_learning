<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = $_POST["userid"];
    $current = $_POST["current_password"];
    $new = $_POST["new_password"];
    $confirm = $_POST["confirm_password"];

    if ($new !== $confirm) {
        die("❌ New password and confirm password do not match.");
    }

    // TODO: Kiểm tra mật khẩu cũ trong DB
    // TODO: Hash mật khẩu mới rồi update vào DB
    // password_hash($new, PASSWORD_DEFAULT);

    echo "✅ Password updated successfully for user: " . htmlspecialchars($userid);
}
?>
