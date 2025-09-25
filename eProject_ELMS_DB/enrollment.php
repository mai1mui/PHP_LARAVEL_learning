<?php
session_start();
// Chỉ cho Admin/Instructor
if (!isset($_SESSION["AccountID"]) || !in_array(strtolower($_SESSION["ARole"] ?? ''), ['admin', 'instructor'])) {
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

/* ===================== AJAX: DELETE ===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['ajax'] ?? '') === 'delete') {
  $id = trim($_POST['id'] ?? '');
  if ($id === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'msg' => 'Missing id']);
    exit;
  }
  // Xoá enrollment
  $del = $conn->prepare("DELETE FROM enrollments WHERE EnrollmentID = ?");
  $del->bind_param("s", $id);
  $del->execute();

  echo json_encode(['ok' => true]);
  exit;
}

/* ===================== AJAX: LIST ENROLLMENTS ===================== */
if (isset($_GET['ajax'])) {
  $search   = trim($_GET['search']  ?? '');
  $learner  = trim($_GET['learner'] ?? ''); // AccountID
  $course   = trim($_GET['course']  ?? ''); // CourseID
  $status   = trim($_GET['status']  ?? ''); // Paid | Processing | Not Confirmed | ''

  $sql = "SELECT e.EnrollmentID, e.AccountID, e.CourseID, e.EnrollDate, e.EStatus,
                 a.AName AS LearnerName, c.CName AS CourseName
          FROM enrollments e
          LEFT JOIN accounts a ON a.AccountID = e.AccountID
          LEFT JOIN courses  c ON c.CourseID  = e.CourseID
          WHERE 1";
  $types = '';
  $params = [];

  if ($search !== '') {
    $sql .= " AND (e.EnrollmentID LIKE ? OR a.AName LIKE ? OR c.CName LIKE ?)";
    $like = "%{$search}%";
    $types .= 'sss';
    $params[] = $like; $params[] = $like; $params[] = $like;
  }
  if ($learner !== '') {
    $sql .= " AND e.AccountID = ?";
    $types .= 's';
    $params[] = $learner;
  }
  if ($course !== '') {
    $sql .= " AND e.CourseID = ?";
    $types .= 's';
    $params[] = $course;
  }
  if ($status !== '') {
    $sql .= " AND e.EStatus = ?";
    $types .= 's';
    $params[] = $status;
  }

  $sql .= " ORDER BY e.EnrollDate DESC";

  $stmt = $conn->prepare($sql);
  if ($types !== '') $stmt->bind_param($types, ...$params);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows === 0) {
    echo '<tr><td colspan="6" class="py-4 text-center">No enrollments found.</td></tr>';
    exit;
  }

  while ($r = $res->fetch_assoc()) {
    $date = !empty($r['EnrollDate']) ? date('d/m/Y H:i', strtotime($r['EnrollDate'])) : '';

    // badge theo status (dùng class từ layout_index.css)
    $st = (string)$r['EStatus'];
    if (strcasecmp($st, 'Paid') === 0) {
      $badge = '<span class="badge badge--green">Paid</span>';
    } elseif (strcasecmp($st, 'Processing') === 0) {
      $badge = '<span class="badge badge--yellow">Processing</span>';
    } else {
      $badge = '<span class="badge badge--red">Not Confirmed</span>';
    }

    echo '<tr>';
    echo '  <td>'.htmlspecialchars($r['EnrollmentID']).'</td>';
    echo '  <td>'.htmlspecialchars($r['LearnerName'] ?: $r['AccountID']).'</td>';
    echo '  <td>'.htmlspecialchars($r['CourseName'] ?: $r['CourseID']).'</td>';
    echo '  <td>'.$date.'</td>';
    echo '  <td>'.$badge.'</td>';
    echo '  <td class="actions">
              <a href="edit_enrollment.php?id='.rawurlencode($r['EnrollmentID']).'" class="btn btn-primary">Edit</a>
              <button class="btn btn-ghost btn-del" data-id="'.htmlspecialchars($r['EnrollmentID']).'">Delete</button>
            </td>';
    echo '</tr>';
  }
  exit;
}

/* ===================== DATA FOR FILTERS ===================== */
// Learners
$ls = $conn->prepare("SELECT AccountID, AName FROM accounts WHERE ARole='Learner' ORDER BY AName ASC");
$ls->execute();
$lr = $ls->get_result();
$learners = $lr->fetch_all(MYSQLI_ASSOC);

// Courses
$cs = $conn->prepare("SELECT CourseID, CName FROM courses ORDER BY CName ASC");
$cs->execute();
$cr = $cs->get_result();
$courses = $cr->fetch_all(MYSQLI_ASSOC);

// Status list theo schema
$statuses = ['Paid','Processing','Not Confirmed'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Enrollment Management</title>

  <!-- Font + jQuery -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

  <!-- CSS chung đã tách -->
  <link rel="stylesheet" href="style_index.css">
</head>
<body>
  <div class="container-wide">
    <h1 class="page-title">Enrollment Management</h1>

    <!-- Controls: Search + Filters -->
    <div class="control-row">
      <div class="control-wrap">
        <i class="fa-solid fa-search search-icon"></i>
        <input id="search" class="input input--search" placeholder="Search EnrollmentID / learner / course">
      </div>

      <select id="filter_learner" class="select">
        <option value="">-- Filter by Learner --</option>
        <?php foreach ($learners as $l): ?>
          <option value="<?= htmlspecialchars($l['AccountID']) ?>"><?= htmlspecialchars($l['AName']) ?></option>
        <?php endforeach; ?>
      </select>

      <select id="filter_course" class="select">
        <option value="">-- Filter by Course --</option>
        <?php foreach ($courses as $c): ?>
          <option value="<?= htmlspecialchars($c['CourseID']) ?>"><?= htmlspecialchars($c['CName']) ?></option>
        <?php endforeach; ?>
      </select>

      <select id="filter_status" class="select">
        <option value="">-- Filter by Status --</option>
        <?php foreach ($statuses as $s): ?>
          <option value="<?= $s ?>"><?= $s ?></option>
        <?php endforeach; ?>
      </select>

      <div style="margin-left:auto; display:flex; gap:.5rem;">
        <a href="add_enrollment.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add new enrollment</a>
        <a href="dashboard.php" class="btn btn-ghost"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
      </div>
    </div>

    <!-- Table -->
    <div class="card table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>EnrollmentID</th>
            <th>Learner</th>
            <th>Course</th>
            <th>Registration date</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="enroll_body">
          <!-- via AJAX -->
        </tbody>
      </table>
    </div>
  </div>

  <script>
    $(function () {
      const ajaxUrl = location.pathname; // gọi chính file này
      let timer;

      function loadEnrollments() {
        const search  = $('#search').val();
        const learner = $('#filter_learner').val();
        const course  = $('#filter_course').val();
        const status  = $('#filter_status').val();

        $.get(ajaxUrl, { ajax: 1, search, learner, course, status })
          .done(html => $('#enroll_body').html(html))
          .fail(() => $('#enroll_body').html('<tr><td colspan="6" class="text-center">Load failed.</td></tr>'));
      }

      $('#enroll_body').on('click', '.btn-del', function () {
        const id = $(this).data('id');
        if (!id) return;
        if (!confirm('Are you sure to delete this enrollment?')) return;

        $.post(ajaxUrl, { ajax: 'delete', id })
          .done(res => {
            try {
              const data = JSON.parse(res);
              if (data.ok) loadEnrollments();
              else alert(data.msg || 'Delete failed');
            } catch {
              loadEnrollments();
            }
          })
          .fail(() => alert('Delete failed'));
      });

      // initial + events
      loadEnrollments();
      $('#filter_learner,#filter_course,#filter_status').on('change', loadEnrollments);
      $('#search').on('input', function(){ clearTimeout(timer); timer = setTimeout(loadEnrollments, 300); });
    });
  </script>
</body>
</html>
<?php $conn->close(); ?>
