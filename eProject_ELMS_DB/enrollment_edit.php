<?php
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

$enrId = trim($_GET['id'] ?? '');
if ($enrId === '') { http_response_code(400); die("Missing id"); }

// Lấy danh sách Learner cho dropdown
$learners = [];
$ls = $conn->prepare("SELECT AccountID, AName FROM accounts WHERE ARole='Learner' ORDER BY AName ASC");
$ls->execute();
$lres = $ls->get_result();
while ($row = $lres->fetch_assoc()) $learners[] = $row;

// Lấy danh sách Course cho dropdown
$courses = [];
$cs = $conn->prepare("SELECT CourseID, CName FROM courses ORDER BY CName ASC");
$cs->execute();
$cres = $cs->get_result();
while ($row = $cres->fetch_assoc()) $courses[] = $row;

// Enum status theo schema
$statuses = ['Paid','Processing','Not Confirmed'];

$success = $error = "";

/* ====== Load bản ghi hiện tại ====== */
$curStmt = $conn->prepare("SELECT EnrollmentID, AccountID, CourseID, EnrollDate, EStatus FROM enrollments WHERE EnrollmentID=?");
$curStmt->bind_param("s", $enrId);
$curStmt->execute();
$current = $curStmt->get_result()->fetch_assoc();
if (!$current) { http_response_code(404); die("Enrollment not found"); }

// Tách ngày để hiển thị input[type=date]
$curDate = $current['EnrollDate'] ? date('Y-m-d', strtotime($current['EnrollDate'])) : '';

/* ====== Cập nhật (POST) ====== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $AccountID = trim($_POST['AccountID'] ?? '');
  $CourseID  = trim($_POST['CourseID']  ?? '');
  $dateStr   = trim($_POST['EnrollDate'] ?? ''); // yyyy-mm-dd
  $EStatus   = trim($_POST['EStatus'] ?? '');

  if (strcasecmp($EStatus, 'not confirmed') === 0) $EStatus = 'Not Confirmed';

  if ($AccountID === '' || $CourseID === '' || $EStatus === '') {
    $error = "Please fill all required fields (*).";
  } elseif (!in_array($EStatus, $statuses, true)) {
    $error = "Invalid status value.";
  } else {
    // Kiểm tra Learner tồn tại & đúng role
    $chk1 = $conn->prepare("SELECT 1 FROM accounts WHERE AccountID=? AND ARole='Learner' LIMIT 1");
    $chk1->bind_param("s", $AccountID);
    $chk1->execute();
    $okL = (bool)$chk1->get_result()->fetch_row();

    // Kiểm tra Course tồn tại
    $chk2 = $conn->prepare("SELECT 1 FROM courses WHERE CourseID=? LIMIT 1");
    $chk2->bind_param("s", $CourseID);
    $chk2->execute();
    $okC = (bool)$chk2->get_result()->fetch_row();

    if (!$okL)       $error = "Learner not found (or not a Learner).";
    elseif (!$okC)   $error = "Course not found.";
    else {
      // Gộp ngày với phần giờ cũ (nếu có), hoặc dùng giờ hiện tại
      if ($dateStr !== '') {
        $existingTime = $current['EnrollDate'] ? date('H:i:s', strtotime($current['EnrollDate'])) : date('H:i:s');
        $EnrollDate = date('Y-m-d H:i:s', strtotime($dateStr . ' ' . $existingTime));
      } else {
        $EnrollDate = $current['EnrollDate']; // giữ nguyên
      }

      $upd = $conn->prepare("UPDATE enrollments SET AccountID=?, CourseID=?, EnrollDate=?, EStatus=? WHERE EnrollmentID=?");
      $upd->bind_param("sssss", $AccountID, $CourseID, $EnrollDate, $EStatus, $enrId);
      $upd->execute();

      $success = "Enrollment updated successfully.";
      // refresh dữ liệu hiện tại để hiển thị lại
      $current['AccountID'] = $AccountID;
      $current['CourseID']  = $CourseID;
      $current['EnrollDate']= $EnrollDate;
      $current['EStatus']   = $EStatus;
      $curDate = $EnrollDate ? date('Y-m-d', strtotime($EnrollDate)) : '';
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
  <title>Edit enrollment</title>
  <link rel="stylesheet" href="style_edit.css">
</head>
<body class="edit-body">
  <div class="card">
    <h2 class="title">Edit Enrollment</h2>

    <?php if (!empty($success)): ?>
      <div class="alert alert--success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="alert alert--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <!-- Enrollment ID (read only) -->
      <div class="form-group">
        <label class="form-label">Enrollment ID</label>
        <input type="text" value="<?= htmlspecialchars($current['EnrollmentID']) ?>" readonly class="form-control form-control--readonly">
      </div>

      <!-- Learner (*) -->
      <div class="form-group">
        <label class="form-label">Learner <span class="req">*</span></label>
        <select name="AccountID" required class="form-control">
          <option value="">-- Select learner --</option>
          <?php foreach ($learners as $l): ?>
            <option value="<?= htmlspecialchars($l['AccountID']) ?>" <?= ($l['AccountID'] === $current['AccountID']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($l['AName']) ?> (<?= htmlspecialchars($l['AccountID']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Course (*) -->
      <div class="form-group">
        <label class="form-label">Course <span class="req">*</span></label>
        <select name="CourseID" required class="form-control">
          <option value="">-- Select course --</option>
          <?php foreach ($courses as $c): ?>
            <option value="<?= htmlspecialchars($c['CourseID']) ?>" <?= ($c['CourseID'] === $current['CourseID']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($c['CName']) ?> (<?= htmlspecialchars($c['CourseID']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Registration Date -->
      <div class="form-group">
        <label class="form-label">Registration Date</label>
        <input type="date" name="EnrollDate" value="<?= htmlspecialchars($curDate) ?>" class="form-control">
        <div class="hint">Để trống để giữ nguyên thời điểm cũ.</div>
      </div>

      <!-- Status (*) -->
      <div class="form-group">
        <label class="form-label">Status <span class="req">*</span></label>
        <select name="EStatus" required class="form-control">
          <?php foreach ($statuses as $s): ?>
            <option value="<?= $s ?>" <?= (strcasecmp($current['EStatus'], $s)===0 ? 'selected' : '') ?>><?= $s ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Buttons -->
      <div class="actions">
        <a href="enrollment.php" class="btn btn--ghost">Cancel</a>
        <button type="submit" class="btn btn--primary">Save</button>
      </div>
    </form>
  </div>
</body>
</html>
