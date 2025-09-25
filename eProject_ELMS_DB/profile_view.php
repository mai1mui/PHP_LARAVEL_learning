<?php
// profile_view.php — Hiển thị hồ sơ người dùng đang đăng nhập

session_start();
if (!isset($_SESSION["AccountID"])) {
  header("Location: login.php");
  exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Kết nối DB
$servername = "127.0.0.1";
$username   = "root";
$password   = "";
$dbname     = "elmsdb";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset('utf8mb4');

function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

$accountId = $_SESSION["AccountID"];

// Lấy thông tin user theo AccountID trong phiên đăng nhập
// Giả định bảng `accounts` có các cột: AccountID, AName, Email, ARole, AStatus, CreatedAt (đồng bộ với các file trước bạn dùng)
$stmt = $conn->prepare("
  SELECT AccountID, AName, Email, ARole, 
         COALESCE(AStatus,'Active') AS AStatus, 
         CreatedAt
  FROM accounts
  WHERE AccountID = ?
  LIMIT 1
");
$stmt->bind_param("s", $accountId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
  // Nếu không tìm thấy (tài khoản đã bị xoá/chưa tồn tại), buộc đăng nhập lại
  session_destroy();
  header("Location: login.php");
  exit;
}

// Chuẩn hoá dữ liệu hiển thị
$createdAt = $user['CreatedAt'] ? date('d/m/Y', strtotime($user['CreatedAt'])) : '';
$roleLabel = $user['ARole'] ?: '';
$statusLbl = $user['AStatus'] ?: 'Active';

// Tạo avatar (demo từ pravatar theo AccountID)
$avatarUrl = "https://i.pravatar.cc/150?u=".rawurlencode($user['AccountID']);

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Inter','sans-serif'] },
          colors: {
            darkblue: '#111827',
            cardblue: '#1f2937',
            primary: '#3b82f6',
            primaryhover: '#2563eb',
            textlight: '#f9fafb',
          }
        }
      }
    }
  </script>
</head>
<body class="bg-darkblue text-white min-h-screen flex items-center justify-center p-6">
  <div class="bg-gray-800 rounded-xl shadow-xl p-8 w-full max-w-3xl">
    <!-- Avatar + Name -->
    <div class="flex flex-col items-center text-center mb-6">
      <img src="<?= h($avatarUrl) ?>"
           alt="User Avatar"
           class="w-32 h-32 rounded-full border-4 border-primary shadow-lg mb-4">
      <h2 class="text-2xl font-bold"><?= h($user['AName'] ?: $user['AccountID']) ?></h2>
      <p class="text-gray-400"><?= h($roleLabel) ?></p>
    </div>

    <!-- Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
      <div class="bg-cardblue p-4 rounded-lg shadow">
        <p class="text-gray-400 text-sm">UserID</p>
        <p class="text-lg font-semibold"><?= h($user['AccountID']) ?></p>
      </div>

      <div class="bg-cardblue p-4 rounded-lg shadow">
        <p class="text-gray-400 text-sm">Email</p>
        <p class="text-lg font-semibold"><?= h($user['Email']) ?></p>
      </div>

      <div class="bg-cardblue p-4 rounded-lg shadow">
        <p class="text-gray-400 text-sm">Role</p>
        <p class="text-lg font-semibold"><?= h($roleLabel) ?></p>
      </div>

      <div class="bg-cardblue p-4 rounded-lg shadow">
        <p class="text-gray-400 text-sm">Status</p>
        <?php
          $statusColor = 'text-green-400';
          if (strcasecmp($statusLbl,'inactive')===0) $statusColor = 'text-red-400';
          elseif (strcasecmp($statusLbl,'pending')===0) $statusColor = 'text-yellow-400';
        ?>
        <p class="text-lg font-semibold <?= $statusColor ?>"><?= h($statusLbl) ?></p>
      </div>

      <div class="bg-cardblue p-4 rounded-lg shadow md:col-span-2">
        <p class="text-gray-400 text-sm">Date Created</p>
        <p class="text-lg font-semibold"><?= h($createdAt) ?></p>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-center space-x-4">
      <a href="profile_edit.php?id=<?= h($user['AccountID']) ?>"
         class="px-6 py-2 bg-primary text-textlight rounded-lg font-semibold hover:bg-primaryhover transition-colors duration-200 shadow-lg flex items-center space-x-2">
        <i class="fas fa-edit"></i>
        <span>Edit Profile</span>
      </a>
      <a href="dashboard.php"
         class="px-6 py-2 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-500 transition-colors duration-200 shadow-lg flex items-center space-x-2">
        <span>Back</span>
      </a>
    </div>
  </div>
</body>
</html>
