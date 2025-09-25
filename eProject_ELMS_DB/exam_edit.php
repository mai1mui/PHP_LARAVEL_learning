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

/* ===================== Helpers ===================== */
function minutesToHuman(?int $min): string {
  if ($min === null || $min <= 0) return '';
  $h = intdiv($min, 60);
  $m = $min % 60;
  return $h > 0 ? sprintf('%d:%02d', $h, $m) : (string)$m; // mặc định HH:MM (vd 1:30) hoặc chỉ phút
}

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

/* ===================== Lấy id & dữ liệu hiện tại ===================== */
$examId = trim($_GET['id'] ?? '');
if ($examId === '') { http_response_code(400); die("Missing id"); }

$sql = "SELECT e.ExamID, e.CourseID, e.DocType, e.Description, e.StartTime, e.DurationMinutes,
               e.CreatorID, e.CreatedAt, a.AName AS CreatorName
        FROM exams e
        LEFT JOIN accounts a ON a.AccountID = e.CreatorID
        WHERE e.ExamID = ?";
$st = $conn->prepare($sql);
$st->bind_param("s", $examId);
$st->execute();
$exam = $st->get_result()->fetch_assoc();
if (!$exam) { http_response_code(404); die("Exam not found"); }

/* ===================== Dữ liệu cho dropdown ===================== */
// Courses
$courses = [];
$cs = $conn->prepare("SELECT CourseID, CName FROM courses ORDER BY CName ASC");
$cs->execute();
$cr = $cs->get_result();
while ($row = $cr->fetch_assoc()) { $courses[] = $row; }

// Doc types (distinct)
$docTypes = [];
try {
  $dt = $conn->query("SELECT DISTINCT DocType FROM exams WHERE DocType IS NOT NULL AND DocType<>'' ORDER BY DocType ASC");
  while ($row = $dt->fetch_assoc()) { $docTypes[] = $row['DocType']; }
} catch (\Throwable $e) { /* ignore */ }
if (empty($docTypes)) $docTypes = ['Exercises','Quiz','Essays','Exam'];

$success = $error = "";

/* ===================== Cập nhật (POST) ===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $CourseID    = trim($_POST['CourseID'] ?? '');
  $DocType     = trim($_POST['DocType'] ?? '');
  $Description = trim($_POST['Description'] ?? '');
  $StartTime   = trim($_POST['StartTime'] ?? '');      // datetime-local
  $DurationRaw = trim($_POST['Duration'] ?? '');

  if ($CourseID === '' || $DocType === '' || $Description === '' || $StartTime === '' || $DurationRaw === '') {
    $error = "Please fill all required fields (*).";
  } else {
    // Course tồn tại?
    $chkC = $conn->prepare("SELECT 1 FROM courses WHERE CourseID=? LIMIT 1");
    $chkC->bind_param("s", $CourseID);
    $chkC->execute();
    $okC = (bool)$chkC->get_result()->fetch_row();

    if (!$okC) {
      $error = "Course not found.";
    } else {
      $ts = strtotime($StartTime);
      if ($ts === false) {
        $error = "Invalid Start Time.";
      } else {
        $StartTimeSql    = date('Y-m-d H:i:s', $ts);
        $DurationMinutes = parseDurationToMinutes($DurationRaw);
        if ($DurationMinutes === null || $DurationMinutes <= 0) {
          $error = "Invalid Test Duration. Use formats like: 90, 90m, 2h, 1h30m, 1:30";
        } else {
          $upd = $conn->prepare("UPDATE exams
                                 SET CourseID=?, DocType=?, Description=?, StartTime=?, DurationMinutes=?
                                 WHERE ExamID=?");
          $upd->bind_param("ssssis", $CourseID, $DocType, $Description, $StartTimeSql, $DurationMinutes, $examId);
          $upd->execute();

          $success = "Exam updated successfully.";
          // refresh local
          $exam['CourseID']        = $CourseID;
          $exam['DocType']         = $DocType;
          $exam['Description']     = $Description;
          $exam['StartTime']       = $StartTimeSql;
          $exam['DurationMinutes'] = $DurationMinutes;

          // Nếu muốn redirect:
          // header("Location: exam.php?updated={$examId}");
          // exit;
        }
      }
    }
  }
}

$conn->close();

// Chuẩn hoá hiển thị
$startTimeLocal = $exam['StartTime'] ? date('Y-m-d\TH:i', strtotime($exam['StartTime'])) : '';
$createdDate    = $exam['CreatedAt'] ? date('Y-m-d', strtotime($exam['CreatedAt'])) : '';
$durationText   = minutesToHuman((int)($exam['DurationMinutes'] ?? 0));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Edit Exam</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="style_edit.css">
</head>
<body class="edit-body">
  <div class="card">
    <h2 class="title">Edit Exam</h2>

    <?php if (!empty($success)): ?>
      <div class="alert alert--success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="alert alert--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <!-- ExamID (readonly) -->
      <div class="form-group">
        <label class="form-label">ExamID</label>
        <input type="text" class="form-control form-control--readonly" value="<?= htmlspecialchars($exam['ExamID']) ?>" readonly>
      </div>

      <!-- Course (*) -->
      <div class="form-group">
        <label class="form-label">Course <span class="req">*</span></label>
        <select name="CourseID" required class="form-control">
          <option value="">-- Select course --</option>
          <?php foreach ($courses as $c): ?>
            <option value="<?= htmlspecialchars($c['CourseID']) ?>"
              <?= ($c['CourseID'] === $exam['CourseID'] ? 'selected' : '') ?>>
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
            <option value="<?= htmlspecialchars($d) ?>"
              <?= (strcasecmp($exam['DocType'], $d)===0 ? 'selected' : '') ?>>
              <?= htmlspecialchars($d) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Description (*) -->
      <div class="form-group">
        <label class="form-label">Description <span class="req">*</span></label>
        <input type="text" name="Description" class="form-control"
               value="<?= htmlspecialchars($exam['Description']) ?>" required>
      </div>

      <!-- Start Time (*) -->
      <div class="form-group">
        <label class="form-label">Start Time <span class="req">*</span></label>
        <input type="datetime-local" name="StartTime" class="form-control"
               value="<?= htmlspecialchars($startTimeLocal) ?>" required>
      </div>

      <!-- Test Duration (*) -->
      <div class="form-group">
        <label class="form-label">Test Duration <span class="req">*</span></label>
        <input type="text" name="Duration" class="form-control"
               placeholder="90 | 90m | 2h | 1h30m | 1:30"
               value="<?= htmlspecialchars($durationText) ?>" required>
        <div class="hint">Bạn có thể nhập phút (90), hoặc định dạng 2h / 1h30m / 1:30.</div>
      </div>

      <!-- Creator (readonly) -->
      <div class="form-group">
        <label class="form-label">Creator</label>
        <input type="text" class="form-control form-control--readonly"
               value="<?= htmlspecialchars($exam['CreatorName'] ?: $exam['CreatorID']) ?>" readonly>
      </div>

      <!-- Date created (readonly) -->
      <div class="form-group">
        <label class="form-label">Date created</label>
        <input type="date" class="form-control form-control--readonly"
               value="<?= htmlspecialchars($createdDate) ?>" readonly>
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
