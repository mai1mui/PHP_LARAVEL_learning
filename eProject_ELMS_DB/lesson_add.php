<?php
// add_lesson.php (lesson_add)
session_start();
// Chỉ cho Admin/Instructor
if (!isset($_SESSION["AccountID"]) || !in_array(strtolower($_SESSION["ARole"] ?? ''), ['admin','instructor'])) {
  header("Location: login.php"); exit;
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

/** Sinh LessonID kế tiếp: L001, L002... (ưu tiên format bắt đầu bằng 'L' + số) */
function genNextLessonId(mysqli $conn): string {
  $rs = $conn->query("SELECT LessonID FROM lessons WHERE LessonID REGEXP '^L[0-9]{2,}$' ORDER BY LENGTH(LessonID) DESC, LessonID DESC LIMIT 1");
  if ($row = $rs->fetch_assoc()) {
    $num = (int)preg_replace('/\D/', '', $row['LessonID']);
    return sprintf('L%03d', $num + 1);
  }
  return 'L001';
}

/* ======== Nạp dữ liệu dropdown ======== */
// Courses
$courses = [];
$cs = $conn->prepare("SELECT CourseID, CName FROM courses ORDER BY CName ASC");
$cs->execute();
$cr = $cs->get_result();
while ($row = $cr->fetch_assoc()) $courses[] = $row;

// LessonType & Status theo schema
$lessonTypes = ['Video','Quiz','Assignment'];
$statuses    = ['Paid','Processing','Not Confirmed'];

$success = $error = '';
$posted = [
  'CourseID'   => '',
  'LName'      => '',
  'Content'    => '',
  'LessonType' => '',
  'Ordinal'    => '',
  'LStatus'    => '',
];

/* ======== Xử lý POST (Save) ======== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $CourseID   = trim($_POST['CourseID'] ?? '');
  $LName      = trim($_POST['LName'] ?? '');
  $Content    = trim($_POST['Content'] ?? '');
  $LessonType = trim($_POST['LessonType'] ?? '');
  $Ordinal    = trim($_POST['Ordinal'] ?? '');
  $LStatus    = trim($_POST['LStatus'] ?? '');

  $posted = compact('CourseID','LName','Content','LessonType','Ordinal','LStatus');

  // Chuẩn hoá status (phòng trường hợp người dùng gõ tự do)
  if (strcasecmp($LStatus, 'not confirmed') === 0) $LStatus = 'Not Confirmed';

  // Validate
  if ($CourseID === '' || $LName === '' || $LessonType === '' || $LStatus === '') {
    $error = "Please fill all required fields (*).";
  } elseif (!in_array($LessonType, $lessonTypes, true)) {
    $error = "Invalid lesson type.";
  } elseif (!in_array($LStatus, $statuses, true)) {
    $error = "Invalid status value.";
  } elseif ($Ordinal !== '' && !preg_match('/^\d+$/', $Ordinal)) {
    $error = "Ordinal must be a non-negative integer.";
  } else {
    // Kiểm tra Course tồn tại
    $chk = $conn->prepare("SELECT 1 FROM courses WHERE CourseID=? LIMIT 1");
    $chk->bind_param("s", $CourseID);
    $chk->execute();
    if (!(bool)$chk->get_result()->fetch_row()) {
      $error = "Course not found.";
    } else {
      // Insert
      $LessonID = genNextLessonId($conn);
      $OrdinalInt = ($Ordinal === '') ? null : (int)$Ordinal;

      $sql = "INSERT INTO lessons (LessonID, CourseID, LName, Content, LessonType, Ordinal, LStatus)
              VALUES (?,?,?,?,?,?,?)";
      $ins = $conn->prepare($sql);
      // bind null cho Ordinal nếu rỗng
      if ($OrdinalInt === null) {
        $null = null;
        $ins->bind_param("sssssis", $LessonID, $CourseID, $LName, $Content, $LessonType, $null, $LStatus);
      } else {
        $ins->bind_param("sssssis", $LessonID, $CourseID, $LName, $Content, $LessonType, $OrdinalInt, $LStatus);
      }
      $ins->execute();

      // Ghi activity log (nếu có bảng)
      try {
        $meta = json_encode(['lesson_id'=>$LessonID,'course_id'=>$CourseID], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        $alog = $conn->prepare("INSERT INTO activity_logs (AccountID, action, meta, ip, agent) VALUES (?,?,?,?,?)");
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        $act = 'lesson.create';
        $alog->bind_param("sssss", $_SESSION["AccountID"], $act, $meta, $ip, $agent);
        $alog->execute();
      } catch(Throwable $e) {}

      header("Location: lesson.php?created={$LessonID}");
      exit;
    }
  }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Add New Lesson</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <!-- CSS chung cho trang Add -->
  <link rel="stylesheet" href="style_add.css">
  <!-- Tailwind (nếu bạn vẫn cần các utility, có thể bỏ nếu chỉ dùng CSS tách) -->
  <script>
    tailwind = {
      theme: {
        extend: {
          colors: {
            darkblue:'#111827', cardblue:'#1f2937',
            primary:'#3b82f6', primaryhover:'#2563eb', textlight:'#f9fafb',
          }
        }
      }
    }
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="add-body">
  <div class="card" style="max-width: 768px;">
    <h2 class="title">Add New Lesson</h2>

    <?php if ($success): ?>
      <div class="alert alert--success"><?= h($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert--error"><?= h($error) ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <!-- Course (*) -->
      <div class="form-group">
        <label class="form-label">Course <span class="req">*</span></label>
        <select name="CourseID" class="form-control" required>
          <option value="">-- Select course --</option>
          <?php foreach ($courses as $c): ?>
            <option value="<?= h($c['CourseID']) ?>" <?= ($posted['CourseID']===$c['CourseID']?'selected':'') ?>>
              <?= h($c['CName']) ?> (<?= h($c['CourseID']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Lesson Name (*) -->
      <div class="form-group">
        <label class="form-label">Lesson Name <span class="req">*</span></label>
        <input type="text" name="LName" value="<?= h($posted['LName']) ?>" class="form-control" placeholder="Enter lesson name" required>
      </div>

      <!-- Content (link/file) -->
      <div class="form-group">
        <label class="form-label">Content (link/file)</label>
        <input type="text" name="Content" value="<?= h($posted['Content']) ?>" class="form-control" placeholder="e.g., link:intro01.pdf">
      </div>

      <!-- Lesson Type (*) -->
      <div class="form-group">
        <label class="form-label">Lesson Type <span class="req">*</span></label>
        <select name="LessonType" class="form-control" required>
          <option value="">-- Select type --</option>
          <?php foreach ($lessonTypes as $t): ?>
            <option value="<?= $t ?>" <?= ($posted['LessonType']===$t?'selected':'') ?>><?= $t ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Ordinal (optional) -->
      <div class="form-group">
        <label class="form-label">Ordinal</label>
        <input type="number" name="Ordinal" value="<?= h($posted['Ordinal']) ?>" class="form-control" placeholder="Enter order (1, 2, 3...)">
      </div>

      <!-- Status (*) -->
      <div class="form-group">
        <label class="form-label">Status <span class="req">*</span></label>
        <select name="LStatus" class="form-control" required>
          <?php foreach ($statuses as $s): ?>
            <option value="<?= $s ?>" <?= ($posted['LStatus']===$s?'selected':'') ?>><?= $s ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Actions -->
      <div class="actions">
        <a href="lesson.php" class="btn btn--ghost">Cancel</a>
        <button type="submit" class="btn btn--primary">Save</button>
      </div>
    </form>
  </div>
</body>
</html>
