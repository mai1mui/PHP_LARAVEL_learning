<?php
// edit_lesson.php  (Lesson Edit – dùng style_edit.css)

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

$lessonId = trim($_GET['id'] ?? '');
if ($lessonId === '') { http_response_code(400); die("Missing id"); }

// Lấy danh sách Course cho dropdown
$courses = [];
$qc = $conn->prepare("SELECT CourseID, CName FROM courses ORDER BY CName ASC");
$qc->execute();
$rc = $qc->get_result();
while ($row = $rc->fetch_assoc()) $courses[] = $row;

// Enum theo schema
$lessonTypes = ['Video','Quiz','Assignment'];
$statuses    = ['Paid','Processing','Not Confirmed'];

$success = $error = "";

/* ====== Cập nhật (POST) ====== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $CourseID   = trim($_POST['CourseID'] ?? '');
  $LName      = trim($_POST['LName'] ?? '');
  $Content    = trim($_POST['Content'] ?? '');
  $LessonType = trim($_POST['LessonType'] ?? '');
  $Ordinal    = ($_POST['Ordinal'] ?? '') !== '' ? (int)$_POST['Ordinal'] : null;
  $LStatus    = trim($_POST['LStatus'] ?? '');

  // Validate
  if ($CourseID === '' || $LName === '' || $LessonType === '' || $LStatus === '') {
    $error = "Please fill all required fields (*).";
  } elseif (!in_array($LessonType, $lessonTypes, true)) {
    $error = "Invalid lesson type.";
  } elseif (!in_array($LStatus, $statuses, true)) {
    $error = "Invalid status.";
  } else {
    // Kiểm tra CourseID tồn tại
    $chk = $conn->prepare("SELECT 1 FROM courses WHERE CourseID=? LIMIT 1");
    $chk->bind_param("s", $CourseID);
    $chk->execute();
    if (!$chk->get_result()->fetch_row()) {
      $error = "CourseID not found.";
    } else {
      // Update
      $sql = "UPDATE lessons 
              SET CourseID=?, LName=?, Content=?, LessonType=?, Ordinal=?, LStatus=?
              WHERE LessonID=?";
      $st  = $conn->prepare($sql);
      if ($Ordinal === null) {
        $null = null;
        $st->bind_param("ssssiss", $CourseID, $LName, $Content, $LessonType, $null, $LStatus, $lessonId);
      } else {
        $st->bind_param("ssssiss", $CourseID, $LName, $Content, $LessonType, $Ordinal, $LStatus, $lessonId);
      }
      $st->execute();
      $success = "Lesson updated successfully.";
    }
  }
}

/* ====== Load lại dữ liệu hiện tại ====== */
$q = $conn->prepare("SELECT LessonID, CourseID, LName, Content, LessonType, Ordinal, CreatedAt, LStatus 
                     FROM lessons WHERE LessonID=?");
$q->bind_param("s", $lessonId);
$q->execute();
$lesson = $q->get_result()->fetch_assoc();
if (!$lesson) { http_response_code(404); die("Lesson not found"); }

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Lesson</title>

  <!-- CSS dùng chung cho trang EDIT (đã tách) -->
  <link rel="stylesheet" href="style_edit.css">

  <!-- (Tuỳ chọn) CDN Tailwind nếu bạn vẫn muốn dùng utility song song với CSS riêng -->
  <script>
    tailwind = { theme: { extend: {
      colors: { darkblue:'#111827', cardblue:'#1f2937', primary:'#3b82f6', primaryhover:'#2563eb', textlight:'#f9fafb' }
    }}}
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="edit-body">
  <div class="card" style="max-width: 768px;">
    <h2 class="title">Edit Lesson</h2>

    <?php if ($success): ?>
      <div class="alert alert--success"><?= h($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert--error"><?= h($error) ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <!-- Lesson ID -->
      <div class="form-group">
        <label class="form-label">Lesson ID</label>
        <input type="text" value="<?= h($lesson['LessonID']) ?>" class="form-control form-control--readonly" readonly>
      </div>

      <!-- Course (*) -->
      <div class="form-group">
        <label class="form-label">Course <span class="req">*</span></label>
        <select name="CourseID" class="form-control" required>
          <option value="">-- Select course --</option>
          <?php foreach ($courses as $c): ?>
            <option value="<?= h($c['CourseID']) ?>" <?= $c['CourseID']===$lesson['CourseID']?'selected':'' ?>>
              <?= h($c['CName']) ?> (<?= h($c['CourseID']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Lesson Name (*) -->
      <div class="form-group">
        <label class="form-label">Lesson Name <span class="req">*</span></label>
        <input type="text" name="LName" value="<?= h($lesson['LName']) ?>" class="form-control" required>
      </div>

      <!-- Content -->
      <div class="form-group">
        <label class="form-label">Content (link/file)</label>
        <input type="text" name="Content" value="<?= h($lesson['Content']) ?>" class="form-control" placeholder="e.g., link:intro01.pdf">
      </div>

      <!-- Lesson Type (*) -->
      <div class="form-group">
        <label class="form-label">Lesson Type <span class="req">*</span></label>
        <select name="LessonType" class="form-control" required>
          <option value="">-- Select type --</option>
          <?php foreach ($lessonTypes as $t): ?>
            <option value="<?= $t ?>" <?= ($lesson['LessonType']===$t?'selected':'') ?>><?= $t ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Ordinal -->
      <div class="form-group">
        <label class="form-label">Ordinal</label>
        <input type="number" name="Ordinal" min="0" value="<?= h((string)$lesson['Ordinal']) ?>" class="form-control" placeholder="Enter order (1, 2, 3...)">
        <p class="form-hint">Để trống nếu không sắp thứ tự.</p>
      </div>

      <!-- Status (*) -->
      <div class="form-group">
        <label class="form-label">Status <span class="req">*</span></label>
        <select name="LStatus" class="form-control" required>
          <?php foreach ($statuses as $s): ?>
            <option value="<?= $s ?>" <?= ($lesson['LStatus']===$s?'selected':'') ?>><?= $s ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Actions -->
      <div class="actions">
        <a href="lesson.php" class="btn btn--ghost">Cancel</a>
        <button type="submit" class="btn btn--primary">Update</button>
      </div>
    </form>
  </div>
</body>
</html>
