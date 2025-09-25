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

/** Tạo CourseID tiếp theo kiểu CRS001, CRS002... */
function genNextCourseId(mysqli $conn): string {
  $sql = "SELECT CourseID FROM courses WHERE CourseID REGEXP '^CRS[0-9]{3,}$' 
          ORDER BY LENGTH(CourseID) DESC, CourseID DESC LIMIT 1";
  $rs  = $conn->query($sql);
  if ($row = $rs->fetch_assoc()) {
    $cur = $row['CourseID'];
    $num = (int)preg_replace('/\D/', '', $cur);
    return sprintf('CRS%03d', $num + 1);
  }
  return 'CRS001';
}

$success = $error = "";

/* ========= Handle POST (Save) ========= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $CName        = trim($_POST['CName'] ?? '');
  $CDescription = trim($_POST['CDescription'] ?? '');
  $StartDate    = trim($_POST['StartDate'] ?? '');   // yyyy-mm-dd
  $CStatus      = trim($_POST['CStatus'] ?? '');     // Active | Inactive
  $CreatorID    = $_SESSION['AccountID'];            // người tạo là user hiện tại

  // Validate cơ bản
  if ($CName === '' || $CDescription === '' || $StartDate === '' || $CStatus === '') {
    $error = "Please fill all required fields (*).";
  } elseif (!in_array($CStatus, ['Active','Inactive'], true)) {
    $error = "Invalid status value.";
  } else {
    // Chuẩn hoá StartDate -> date hợp lệ
    $ts = strtotime($StartDate);
    if ($ts === false) {
      $error = "Invalid Start Date.";
    } else {
      $CourseID = genNextCourseId($conn);

      // INSERT
      $ins = $conn->prepare("INSERT INTO courses (CourseID, CName, CDescription, StartDate, CreatorID, CStatus) 
                             VALUES (?,?,?,?,?,?)");
      $ins->bind_param("ssssss", $CourseID, $CName, $CDescription, $StartDate, $CreatorID, $CStatus);
      $ins->execute();

      header("Location: course.php?created={$CourseID}");
      exit;
    }
  }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add New Course</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <!-- CSS chung cho trang add -->
  <link rel="stylesheet" href="style_add.css">
</head>
<body class="add-body">
  <div class="card">
    <h2 class="title">Add New Course</h2>

    <?php if (!empty($success)): ?>
      <div class="alert alert--success"><?= $success ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="alert alert--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <!-- Course Name (*) -->
      <div class="form-group">
        <label class="form-label">Course Name <span class="req">*</span></label>
        <input type="text" name="CName" required class="form-control" placeholder="e.g. Web Development 101">
      </div>

      <!-- Description (*) -->
      <div class="form-group">
        <label class="form-label">Description <span class="req">*</span></label>
        <textarea name="CDescription" rows="3" required class="form-control" placeholder="Short description of the course"></textarea>
      </div>

      <!-- Start Date (*) -->
      <div class="form-group">
        <label class="form-label">Start Date <span class="req">*</span></label>
        <input type="date" name="StartDate" required class="form-control">
      </div>

      <!-- Status (*) -->
      <div class="form-group">
        <label class="form-label">Status <span class="req">*</span></label>
        <select name="CStatus" required class="form-control">
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
        </select>
      </div>

      <!-- Buttons -->
      <div class="actions">
        <a href="course.php" class="btn btn--ghost">Cancel</a>
        <button type="submit" class="btn btn--primary">Save</button>
      </div>
    </form>
  </div>
</body>
</html>
