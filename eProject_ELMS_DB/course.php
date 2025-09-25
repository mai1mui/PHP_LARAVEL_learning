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
$username   = "root";
$password   = "";
$dbname     = "elmsdb";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset('utf8mb4');

// Lấy danh sách creator để filter (bảng 'accounts' theo schema bạn đã gửi)
$creators = [];
$q = $conn->prepare("SELECT DISTINCT AccountID, AName FROM accounts WHERE ARole IN ('Admin','Instructor') ORDER BY AName ASC");
$q->execute();
$res = $q->get_result();
while ($row = $res->fetch_assoc()) { $creators[] = $row; }

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Course Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  <!-- CSS & libs -->
  <link rel="stylesheet" href="style_index.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
</head>
<body>
  <div class="container-wide">
    <h1 class="page-title">Course Management</h1>

    <!-- Search -->
    <div class="control-row">
      <div class="control-wrap">
        <i class="fa-solid fa-search search-icon"></i>
        <input id="search" class="input input--search" placeholder="Search course...">
      </div>

      <!-- Filters -->
      <select id="filter_creator" class="select">
        <option value="">-- Filter by Creator --</option>
        <?php foreach ($creators as $c): ?>
          <option value="<?= htmlspecialchars($c['AccountID']) ?>">
            <?= htmlspecialchars($c['AName']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <select id="filter_status" class="select">
        <option value="">-- Filter by Status --</option>
        <option value="Active">Active</option>
        <option value="Inactive">Inactive</option>
      </select>

      <!-- Actions (right) -->
      <div style="margin-left:auto; display:flex; gap:.5rem;">
        <a href="add_course.php" class="btn btn--primary"><i class="fa fa-plus"></i> Add new course</a>
        <a href="dashboard.php" class="btn btn--ghost"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
      </div>
    </div>

    <!-- Table -->
    <div class="card table-wrap">
      <table class="table" id="course_table">
        <thead>
          <tr>
            <th>CourseID</th>
            <th>Course Name</th>
            <th>Description</th>
            <th>Start date</th>
            <th>Creator</th>
            <th>Date created</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="course_body">
          <!-- Content will load via AJAX -->
        </tbody>
      </table>
    </div>
  </div>

  <script>
    function loadCourses() {
      const search  = $("#search").val();
      const creator = $("#filter_creator").val();
      const status  = $("#filter_status").val();

      $.get("fetch_courses.php", { search, creator, status })
        .done(html => $("#course_body").html(html))
        .fail(() => $("#course_body").html('<tr><td colspan="8" class="text-center">Load failed.</td></tr>'));
    }

    $(document).ready(function () {
      loadCourses();
      let t;
      $("#search").on("input", function(){ clearTimeout(t); t=setTimeout(loadCourses, 300); });
      $("#filter_creator, #filter_status").on("change", loadCourses);
    });
  </script>
</body>
</html>
