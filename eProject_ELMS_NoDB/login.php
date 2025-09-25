<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = $_POST["userid"];
    $password = $_POST["password"];

    // DEMO: Kiểm tra tài khoản cứng
    if ($userid === "admin" && $password === "admin@123") {
        $_SESSION["userid"] = $userid;
        header("Location: dashboard.php");
        exit;
    } elseif ($userid === "user" && $password === "1234") {
        $_SESSION["userid"] = $userid;
        header("Location: home.php");
        exit;
    } else {
        $error = "❌ Sai UserID hoặc Password!";
    }
}
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

    <form method="post" class="space-y-5">
      <!-- UserID -->
      <div>
        <label for="userid" class="block text-sm font-medium mb-1">UserID</label>
        <input type="text" id="userid" name="userid" required
               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg 
                      focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-sm font-medium mb-1">Password</label>
        <input type="password" id="password" name="password" required
               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg 
                      focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
      </div>

      <!-- Submit button -->
      <button type="submit"
              class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-500 rounded-lg font-semibold transition">
        Login
      </button>
    </form>

    <!-- Hiển thị lỗi -->
    <?php if (isset($error)): ?>
      <p class="text-red-400 text-center mt-4 font-medium"><?php echo $error; ?></p>
    <?php endif; ?>
  </div>
</body>
</html>
