<?php
session_start();
// Chỉ cho Admin/Instructor
if (!isset($_SESSION["AccountID"]) || !in_array(strtolower($_SESSION["ARole"] ?? ''), ['admin', 'instructor'])) {
    header("Location: login.php");
    exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Kết nối DB
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "elmsdb";
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset('utf8mb4');

/** Tạo EnrollmentID tiếp theo kiểu ENR001, ENR002... */
function genNextEnrollmentId(mysqli $conn): string {
    $sql = "SELECT EnrollmentID FROM enrollments WHERE EnrollmentID REGEXP '^ENR[0-9]{3,}$' ORDER BY LENGTH(EnrollmentID) DESC, EnrollmentID DESC LIMIT 1";
    $rs = $conn->query($sql);
    if ($row = $rs->fetch_assoc()) {
        $cur = $row['EnrollmentID'];        // ENR00x
        $num = (int) preg_replace('/\D/', '', $cur);
        return sprintf('ENR%03d', $num + 1);
    }
    return 'ENR001';
}

$success = $error = "";

/* ========= Nạp dữ liệu dropdown ========= */
// Learners
$learners = [];
$ls = $conn->prepare("SELECT AccountID, AName FROM accounts WHERE ARole='Learner' ORDER BY AName ASC");
$ls->execute();
$lr = $ls->get_result();
while ($row = $lr->fetch_assoc()) $learners[] = $row;

// Courses
$courses = [];
$cs = $conn->prepare("SELECT CourseID, CName FROM courses ORDER BY CName ASC");
$cs->execute();
$cr = $cs->get_result();
while ($row = $cr->fetch_assoc()) $courses[] = $row;

// Enum status theo schema
$statuses = ['Processing', 'Paid', 'Not Confirmed'];

/* ========= Handle POST (Save) ========= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $AccountID = trim($_POST['AccountID'] ?? '');
    $CourseID  = trim($_POST['CourseID']  ?? '');
    $dateStr   = trim($_POST['EnrollDate'] ?? ''); // yyyy-mm-dd
    $EStatus   = trim($_POST['EStatus'] ?? '');

    if (strcasecmp($EStatus, 'not confirmed') === 0) $EStatus = 'Not Confirmed';

    if ($AccountID === '' || $CourseID === '' || $EStatus === '') {
        $error = "Please fill all required fields (*).";
    } elseif (!in_array($EStatus, ['Paid','Processing','Not Confirmed'], true)) {
        $error = "Invalid status value.";
    } else {
        $chk1 = $conn->prepare("SELECT 1 FROM accounts WHERE AccountID=? AND ARole='Learner' LIMIT 1");
        $chk1->bind_param("s", $AccountID);
        $chk1->execute();
        $okL = (bool)$chk1->get_result()->fetch_row();

        $chk2 = $conn->prepare("SELECT 1 FROM courses WHERE CourseID=? LIMIT 1");
        $chk2->bind_param("s", $CourseID);
        $chk2->execute();
        $okC = (bool)$chk2->get_result()->fetch_row();

        if (!$okL)       $error = "Learner not found (or not a Learner).";
        elseif (!$okC)   $error = "Course not found.";
        else {
            $EnrollDate = ($dateStr !== '')
                ? date('Y-m-d H:i:s', strtotime($dateStr.' '.date('H:i:s')))
                : date('Y-m-d H:i:s');

            $EnrollmentID = genNextEnrollmentId($conn);

            $ins = $conn->prepare("INSERT INTO enrollments (EnrollmentID, AccountID, CourseID, EnrollDate, EStatus) VALUES (?,?,?,?,?)");
            $ins->bind_param("sssss", $EnrollmentID, $AccountID, $CourseID, $EnrollDate, $EStatus);
            $ins->execute();

            header("Location: enrollment.php?created={$EnrollmentID}");
            exit;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add new enrollment</title>
  <link rel="stylesheet" href="style_add.css">
</head>
<body class="add-body">
  <div class="card">
    <h2 class="title">Add New Enrollment</h2>

    <?php if (!empty($success)): ?>
      <div class="alert alert--success"><?= $success ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="alert alert--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <!-- Learner (*) -->
      <div class="form-group">
        <label class="form-label">Learner <span class="req">*</span></label>
        <select name="AccountID" required class="form-control">
          <option value="">-- Select learner --</option>
          <?php foreach ($learners as $l): ?>
            <option value="<?= htmlspecialchars($l['AccountID']) ?>">
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
            <option value="<?= htmlspecialchars($c['CourseID']) ?>">
              <?= htmlspecialchars($c['CName']) ?> (<?= htmlspecialchars($c['CourseID']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Registration Date (optional) -->
      <div class="form-group">
        <label class="form-label">Registration Date</label>
        <input type="date" name="EnrollDate" class="form-control">
        <div class="hint">Để trống để dùng thời điểm hiện tại.</div>
      </div>

      <!-- Status (*) -->
      <div class="form-group">
        <label class="form-label">Status <span class="req">*</span></label>
        <select name="EStatus" required class="form-control">
          <?php foreach ($statuses as $s): ?>
            <option value="<?= $s ?>"><?= $s ?></option>
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
