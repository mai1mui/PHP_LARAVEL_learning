<?php
// profile_edit.php — Sửa thông tin hồ sơ người dùng đang đăng nhập (kết nối DB)

session_start();
if (!isset($_SESSION["AccountID"])) {
  header("Location: login.php");
  exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// ===== DB =====
$servername = "127.0.0.1";
$username   = "root";
$password   = "";
$dbname     = "elmsdb";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset('utf8mb4');

function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

$accountId = $_SESSION["AccountID"];
$success = $error = "";

// ===== Lấy thông tin user hiện tại =====
// Giả định bảng `accounts` có các cột: AccountID, AName, Email, ARole, AStatus, CreatedAt, Avatar (Avatar có thể không tồn tại)
$stmt = $conn->prepare("
  SELECT AccountID, AName, Email, ARole, COALESCE(AStatus,'Active') AS AStatus, CreatedAt
       , /* Nếu cột Avatar không tồn tại, COALESCE(NULL,'') sẽ luôn rỗng */
         (SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME='accounts' AND COLUMN_NAME='Avatar') AS AvatarColumnExists
  FROM accounts
  WHERE AccountID = ?
  LIMIT 1
");
$stmt->bind_param("s", $accountId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) { session_destroy(); header("Location: login.php"); exit; }

// Thử lấy Avatar (nếu có cột)
$avatarPath = '';
try {
  $a = $conn->prepare("SELECT Avatar FROM accounts WHERE AccountID=? LIMIT 1");
  $a->bind_param("s", $accountId);
  $a->execute();
  $rowA = $a->get_result()->fetch_assoc();
  if ($rowA && isset($rowA['Avatar'])) $avatarPath = (string)$rowA['Avatar'];
} catch (Throwable $e) {
  // Không có cột Avatar -> dùng ảnh mặc định theo AccountID
}
$avatarUrl = $avatarPath !== '' ? $avatarPath : ("https://i.pravatar.cc/150?u=".rawurlencode($user['AccountID']));

// ===== Handle POST (update email + avatar) =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $newEmail = trim($_POST['email'] ?? '');

  if ($newEmail === '' || !filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
    $error = "Email không hợp lệ.";
  } else {
    // Xử lý upload avatar (nếu có)
    $uploadedAvatarPath = null;
    if (!empty($_FILES['avatar']['name']) && is_uploaded_file($_FILES['avatar']['tmp_name'])) {
      $accept = ['image/jpeg','image/png','image/gif','image/webp'];
      $fType = mime_content_type($_FILES['avatar']['tmp_name']);
      if (!in_array($fType, $accept, true)) {
        $error = "Chỉ chấp nhận ảnh JPG/PNG/GIF/WEBP.";
      } else {
        // Tạo thư mục nếu chưa có
        $dir = __DIR__ . '/uploads/avatars';
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }

        // Tên file an toàn
        $ext  = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $file = $accountId . '_' . date('Ymd_His') . '.' . preg_replace('/[^a-zA-Z0-9]/','', $ext);
        $dest = $dir . '/' . $file;
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dest)) {
          // Đường dẫn public (tương đối)
          $uploadedAvatarPath = 'uploads/avatars/' . $file;
        } else {
          $error = "Upload ảnh thất bại.";
        }
      }
    }

    if ($error === '') {
      // Cập nhật DB
      try {
        if ($uploadedAvatarPath !== null) {
          // Thử update có cột Avatar
          $upd = $conn->prepare("UPDATE accounts SET Email=?, Avatar=? WHERE AccountID=?");
          $upd->bind_param("sss", $newEmail, $uploadedAvatarPath, $accountId);
          $upd->execute();
          $avatarUrl = $uploadedAvatarPath;
        } else {
          // Không đổi avatar -> có thể chỉ update Email
          // (Ưu tiên thử update Avatar=NULL nếu có cột, nhưng để an toàn ta chỉ update Email)
          $upd = $conn->prepare("UPDATE accounts SET Email=? WHERE AccountID=?");
          $upd->bind_param("ss", $newEmail, $accountId);
          $upd->execute();
        }
        $success = "Cập nhật hồ sơ thành công.";
        // refresh dữ liệu hiện tại
        $user['Email'] = $newEmail;
      } catch (mysqli_sql_exception $e) {
        // Trường hợp bảng không có cột Avatar -> fallback chỉ update Email
        if ($uploadedAvatarPath !== null) {
          try {
            $upd = $conn->prepare("UPDATE accounts SET Email=? WHERE AccountID=?");
            $upd->bind_param("ss", $newEmail, $accountId);
            $upd->execute();
            $success = "Cập nhật email thành công (cột Avatar không tồn tại).";
            $user['Email'] = $newEmail;
          } catch (Throwable $e2) {
            $error = "Không thể cập nhật hồ sơ. Vui lòng thử lại.";
          }
        } else {
          $error = "Không thể cập nhật hồ sơ. Vui lòng thử lại.";
        }
      }
    }
  }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Profile</title>
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
  <script>
    function previewAvatar(event) {
      const file = event.target.files?.[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = function () {
        const output = document.getElementById('avatarPreview');
        output.src = reader.result;
      }
      reader.readAsDataURL(file);
    }
  </script>
</head>
<body class="bg-darkblue text-white min-h-screen flex items-center justify-center p-6">
  <div class="bg-gray-800 rounded-xl shadow-xl p-8 w-full max-w-2xl">
    <h2 class="text-2xl font-bold text-center mb-6">Edit Profile</h2>

    <?php if (!empty($success)): ?>
      <div class="mb-4 p-3 rounded bg-green-600/20 border border-green-600 text-green-200">
        <?= h($success) ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="mb-4 p-3 rounded bg-red-600/20 border border-red-600 text-red-200">
        <?= h($error) ?>
      </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
      <!-- Avatar -->
      <div class="flex flex-col items-center">
        <img id="avatarPreview"
             src="<?= h($avatarUrl) ?>"
             alt="Avatar"
             class="w-32 h-32 rounded-full border-4 border-primary shadow-lg mb-4 object-cover">
        <input type="file" name="avatar" accept="image/*"
               onchange="previewAvatar(event)"
               class="block w-full text-sm text-gray-400
                      file:mr-4 file:py-2 file:px-4
                      file:rounded-full file:border-0
                      file:text-sm file:font-semibold
                      file:bg-primary file:text-white
                      hover:file:bg-primaryhover">
      </div>

      <!-- Full Name (readonly) -->
      <div>
        <label class="block text-sm text-gray-400 mb-1">Full Name</label>
        <input type="text" value="<?= h($user['AName'] ?: $user['AccountID']) ?>" readonly
               class="w-full px-4 py-2 bg-gray-700 text-gray-300 rounded-lg border border-gray-600 focus:outline-none">
      </div>

      <!-- Email -->
      <div>
        <label class="block text-sm text-gray-400 mb-1">Email</label>
        <input type="email" name="email" value="<?= h($user['Email']) ?>"
               class="w-full px-4 py-2 bg-gray-700 text-gray-200 rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-primary" required>
      </div>

      <!-- Reset Password -->
      <div>
        <label class="block text-sm text-gray-400 mb-1">Password</label>
        <a href="reset_password.php"
           class="inline-block px-4 py-2 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-500 transition-colors duration-200">
          <i class="fas fa-key"></i> Reset Password
        </a>
      </div>

      <!-- Actions -->
      <div class="flex justify-center space-x-4 pt-4">
        <button type="submit"
                class="px-6 py-2 bg-primary text-white rounded-lg font-semibold hover:bg-primaryhover transition-colors duration-200 shadow-lg flex items-center space-x-2">
          <i class="fas fa-save"></i><span>Save Changes</span>
        </button>
        <a href="profile_view.php"
           class="px-6 py-2 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-500 transition-colors duration-200 shadow-lg flex items-center space-x-2">
          <span>Cancel</span>
        </a>
      </div>
    </form>
  </div>
</body>
</html>
