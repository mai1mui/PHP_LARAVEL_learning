<?php
session_start();
// Chỉ cho Admin/Instructor
if (!isset($_SESSION["AccountID"]) || !in_array(strtolower($_SESSION["ARole"] ?? ''), ['admin','instructor'])) {
  header("Location: login.php"); exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/* ===================== Kết nối DB ===================== */
$servername = "127.0.0.1";
$username   = "root";
$password   = "";
$dbname     = "elmsdb";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset('utf8mb4');

/** Tạo ExamID tiếp theo kiểu EXM001, EXM002... */
function genNextExamId(mysqli $conn): string {
  $sql = "SELECT ExamID FROM exams WHERE ExamID REGEXP '^EXM[0-9]{3,}$'
          ORDER BY LENGTH(ExamID) DESC, ExamID DESC LIMIT 1";
  $rs  = $conn->query($sql);
  if ($row = $rs->fetch_assoc()) {
    $cur = $row['ExamID'];
    $num = (int)preg_replace('/\D/', '', $cur);
    return sprintf('EXM%03d', $num + 1);
  }
  return 'EXM001';
}

/** Parse duration string -> minutes (hỗ trợ: 90, 90m, 2h, 1h30m, 1:30, 01:30) */
function parseDurationToMinutes(string $s): ?int {
  $s = trim(strtolower($s));
  if ($s === '') return null;

  // dạng HH:MM
  if (preg_match('/^\s*(\d{1,3}):(\d{1,2})\s*$/', $s, $m)) {
    return (int)$m[1]*60 + (int)$m[2];
  }
  // dạng 1h30m, 2h, 45m
  $h = 0; $m = 0; $matched = false;
  if (preg_match('/(\d+)\s*h/', $s, $mh)) { $h = (int)$mh[1]; $matched = true; }
  if (preg_match('/(\d+)\s*m/', $s, $mm)) { $m = (int)$mm[1]; $matched = true; }
  if ($matched) return $h*60 + $m;

  // chỉ số: mặc định là phút
  if (preg_match('/^\d+$/', $s)) return (int)$s;

  return null;
}

$success = $error = "";

/* ===================== Nạp dữ liệu dropdown ===================== */
// Courses
$courses = [];
$cs = $conn->prepare("SELECT CourseID, CName FROM courses ORDER BY CName ASC");
$cs->execute();
$cr = $cs->get_result();
while ($row = $cr->fetch_assoc()) { $courses[] = $row; }

// Doc types (có thể lấy distinct từ DB; fallback nếu bảng trống)
$docTypes = [];
try {
  $dt = $conn->query("SELECT DISTINCT DocType FROM exams WHERE DocType IS NOT NULL AND DocType<>'' ORDER BY DocType ASC");
  while ($row = $dt->fetch_assoc()) { $docTypes[] = $row['DocType']; }
} catch (\Throwable $e) {
  // ignore
}
if (empty($docTypes)) $docTypes = ['Exercises','Quiz','Essays','Exam'];

/* ===================== Handle POST ===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $CourseID    = trim($_POST['CourseID'] ?? '');
  $DocType     = trim($_POST['DocType'] ?? '');
  $Description = trim($_POST['Description'] ?? '');
  $StartTime   = trim($_POST['StartTime'] ?? '');         // datetime-local (yyyy-mm-ddTHH:MM)
  $DurationRaw = trim($_POST['Duration'] ?? '');          // free text (90, 2h, 1h30m, 1:30)
  $CreatorID   = $_SESSION['AccountID'];

  // Validate
  if ($CourseID === '' || $DocType === '' || $Description === '' || $StartTime === '' || $DurationRaw === '') {
    $error = "Please fill all required fields (*).";
  } else {
    // Kiểm tra Course tồn tại
    $chkC = $conn->prepare("SELECT 1 FROM courses WHERE CourseID=? LIMIT 1");
    $chkC->bind_param("s", $CourseID);
    $chkC->execute();
    $okC = (bool)$chkC->get_result()->fetch_row();

    if (!$okC) {
      $error = "Course not found.";
    } else {
      // Chuẩn hoá StartTime (datetime-local -> 'Y-m-d H:i:s')
      $ts = strtotime($StartTime);
      if ($ts === false) {
        $error = "Invalid Start Time.";
      } else {
        $StartTimeSql = date('Y-m-d H:i:s', $ts);
        $DurationMinutes = parseDurationToMinutes($DurationRaw);

        if ($DurationMinutes === null || $DurationMinutes <= 0) {
          $error = "Invalid Test Duration. Use formats like: 90, 90m, 2h, 1h30m, 1:30";
        } else {
          $ExamID = genNextExamId($conn);

          $ins = $conn->prepare("INSERT INTO exams (ExamID, CourseID, DocType, Description, StartTime, DurationMinutes, CreatorID)
                                 VALUES (?,?,?,?,?,?,?)");
          $ins->bind_param("sssssis", $ExamID, $CourseID, $DocType, $Description, $StartTimeSql, $DurationMinutes, $CreatorID);
          $ins->execute();

          header("Location: exam.php?created={$ExamID}");
          exit;
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
  <title>Add New Exam</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <!-- CSS dùng chung cho trang ADD -->
  <link rel="stylesheet" href="style_add.css">
</head>
<body class="add-body">
  <div class="card">
    <h2 class="title">Add New Exam</h2>

    <?php if (!empty($success)): ?>
      <div class="alert alert--success"><?= $success ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="alert alert--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <!-- Course (*) -->
      <div class="form-group">
        <label class="form-label">Course <span class="req">*</span></label>
        <select name="CourseID" required class="form-control">
          <option value="">-- Select course --</option>
          <?php foreach ($courses as $c): ?>
            <option value="<?= htmlspecialchars($c['CourseID']) ?>">
              <?= htmlspecialchars($c['CName']) ?> (<?= htmlspecialchars($c['CourseID']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Document (*) -->
      <div class="form-group">
        <label class="form-label">Document <span class="req">*</span></label>
        <select name="DocType" required class="form-control">
          <?php foreach ($docTypes as $d): ?>
            <option value="<?= htmlspecialchars($d) ?>"><?= htmlspecialchars($d) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Description (*) -->
      <div class="form-group">
        <label class="form-label">Description <span class="req">*</span></label>
        <input type="text" name="Description" required class="form-control" placeholder="e.g. finalexam.pdf">
      </div>

      <!-- Start Time (*) -->
      <div class="form-group">
        <label class="form-label">Start Time <span class="req">*</span></label>
        <input type="datetime-local" name="StartTime" required class="form-control">
      </div>

      <!-- Test Duration (*) -->
      <div class="form-group">
        <label class="form-label">Test Duration <span class="req">*</span></label>
        <input type="text" name="Duration" required class="form-control" placeholder="90 | 90m | 2h | 1h30m | 1:30">
        <div class="hint">Bạn có thể nhập phút (90), hoặc định dạng 2h / 1h30m / 1:30.</div>
      </div>

      <!-- Actions -->
      <div class="actions">
        <a href="exam.php" class="btn btn--ghost">Cancel</a>
        <button type="submit" class="btn btn--primary">Save</button>
      </div>
    </form>
  </div>
</body>
</html>
