<?php
session_start();

/* ===== DEBUG =====
 * Bật = true để in thông tin chẩn đoán ngay dưới form (không redirect).
 * Khi xong, đổi lại thành false.
 */


// Kết nối database
$servername = "127.0.0.1";
$username   = "root";
$password   = "";
$dbname     = "elmsdb";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset('utf8mb4');

$error = "";
$debug_msgs = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Lấy AccountID từ input (form của bạn đang ghi label Email, nhưng name có thể là email)
    $accountInput = trim($_POST["accountid"] ?? $_POST["email"] ?? "");
    $pass         = (string)($_POST["password"] ?? "");

    $debug_msgs[] = "accountInput = '{$accountInput}'";
    $debug_msgs[] = "password_length = " . strlen($pass);

    // Tìm user theo AccountID (không dùng Email)
    $sql  = "SELECT AccountID, AName, ARole, Pass FROM Accounts WHERE TRIM(AccountID) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $accountInput);
    $stmt->execute();
    $stmt->store_result();

    $debug_msgs[] = "rows_found = " . $stmt->num_rows;

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($accountID, $name, $role, $storedPass);
        $stmt->fetch();

        $ok = false;

        // Xác định storedPass là hash hay plain
        $info = password_get_info($storedPass);
        $isHashed = !empty($info['algo']);
        $debug_msgs[] = "isHashed = " . ($isHashed ? "true" : "false");

        if ($isHashed) {
            $ok = password_verify($pass, $storedPass);
            $debug_msgs[] = "verify(hashed) = " . ($ok ? "true" : "false");

            if ($ok && password_needs_rehash($storedPass, PASSWORD_DEFAULT)) {
                $newHash = password_hash($pass, PASSWORD_DEFAULT);
                $up = $conn->prepare("UPDATE Accounts SET Pass = ? WHERE AccountID = ?");
                $up->bind_param("ss", $newHash, $accountID);
                $up->execute();
                $up->close();
                $debug_msgs[] = "password_rehashed = true";
            }
        } else {
            // DB lưu plain → so sánh trực tiếp + nâng cấp
            $ok = hash_equals($storedPass, $pass);
            $debug_msgs[] = "compare(plain) = " . ($ok ? "true" : "false");

            if ($ok) {
                $newHash = password_hash($pass, PASSWORD_DEFAULT);
                $up = $conn->prepare("UPDATE Accounts SET Pass = ? WHERE AccountID = ?");
                $up->bind_param("ss", $newHash, $accountID);
                $up->execute();
                $up->close();
                $debug_msgs[] = "upgraded_to_hash = true";
            }
        }

        if ($ok) {
            // Nếu DEBUG=false thì redirect, còn DEBUG=true thì chỉ thông báo
            session_regenerate_id(true);
            $_SESSION["AccountID"] = $accountID;
            $_SESSION["AName"]     = $name;
            $_SESSION["ARole"]     = $role;

            if (!$DEBUG) {
                if (strcasecmp($role, "Admin") === 0) {
                    header("Location: dashboard.php");
                } else {
                    header("Location: index_learner.php");
                }
                exit;
            } else {
                $debug_msgs[] = "LOGIN_OK role={$role}";
            }
        } else {
            $error = "❌ Sai AccountID hoặc Password!";
        }
    } else {
        $error = "❌ Sai AccountID hoặc Password!";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - E-learning</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-200 flex items-center justify-center min-h-screen">
  <div class="bg-gray-800 shadow-2xl rounded-2xl p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

    <form method="post" class="space-y-5" autocomplete="off">
      <!-- Đăng nhập bằng AccountID (nhập ADM001) -->
      <div>
        <label for="accountid" class="block text-sm font-medium mb-1">AccountID</label>
        <input type="text" id="accountid" name="accountid" required
               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg 
               focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
               placeholder="VD: ADM001">
      </div>

      <div>
        <label for="password" class="block text-sm font-medium mb-1">Password</label>
        <input type="password" id="password" name="password" required
               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg 
               focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
               placeholder="VD: 123456">
      </div>

      <button type="submit"
              class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-500 rounded-lg font-semibold transition">
        Login
      </button>
    </form>

    <?php if (!empty($error)): ?>
      <p class="text-red-400 text-center mt-4 font-medium"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

   
  </div>
</body>
</html>
