<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // TODO: kiểm tra email trong DB
    // Ví dụ demo
    $demoEmail = "nva@example.com";

    if ($email === $demoEmail) {
        // TODO: tạo token reset password (vd: md5 + random_bytes)
        $token = bin2hex(random_bytes(16));

        // TODO: lưu token vào DB kèm expiry time

        // TODO: gửi email thật cho user (dùng PHPMailer)
        $resetLink = "http://localhost/reset_password.php?token=" . $token;

        echo "✅ Reset link has been sent to your email: <br>";
        echo "<a href='$resetLink'>$resetLink</a>";
    } else {
        echo "❌ Email not found in system.";
    }
}
?>
