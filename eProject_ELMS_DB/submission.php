<?php
// submission.php — Submission Management (dùng CSS chung style_index.css)

session_start();
// Chỉ cho Admin/Instructor
if (!isset($_SESSION["AccountID"]) || !in_array(strtolower($_SESSION["ARole"] ?? ''), ['admin','instructor'])) {
  header("Location: login.php"); exit;
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

/* ===================== AJAX: DELETE ===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['ajax'] ?? '') === 'delete') {
  $id = trim($_POST['id'] ?? '');
  if ($id === '') { http_response_code(400); echo json_encode(['ok'=>false,'msg'=>'Missing id']); exit; }

  $del = $conn->prepare("DELETE FROM submissions WHERE SubID=?");
  $del->bind_param("s", $id);
  $del->execute();
  echo json_encode(['ok'=>true]); exit;
}

/* ===================== AJAX: LIST SUBMISSIONS ===================== */
if (isset($_GET['ajax'])) {
  $search  = trim($_GET['search']  ?? ''); // SubID / Learner / Course / Answer
  $learner = trim($_GET['learner'] ?? ''); // AccountID
  $course  = trim($_GET['course']  ?? ''); // CourseID
  $status  = trim($_GET['status']  ?? ''); // Submitted | Late | Not Submit | ''

  $sql = "SELECT s.SubID, s.AccountID, s.CourseID, s.Answer, s.Mark, s.Feedback, s.SDate, s.SStatus,
                 a.AName AS LearnerName, c.CName AS CourseName
          FROM submissions s
          LEFT JOIN accounts a ON a.AccountID = s.AccountID
          LEFT JOIN courses  c ON c.CourseID  = s.CourseID
          WHERE 1";
  $types = '';
  $params = [];

  if ($search !== '') {
    $like = "%{$search}%";
    $sql .= " AND (s.SubID LIKE ? OR a.AName LIKE ? OR c.CName LIKE ? OR s.Answer LIKE ?)";
    $types .= 'ssss';
    array_push($params, $like, $like, $like, $like);
  }
  if ($learner !== '') { $sql .= " AND s.AccountID = ?"; $types.='s'; $params[]=$learner; }
  if ($course  !== '') { $sql .= " AND s.CourseID  = ?"; $types.='s'; $params[]=$course;  }
  if ($status  !== '') { $sql .= " AND s.SStatus   = ?"; $types.='s'; $params[]=$status;  }

  $sql .= " ORDER BY s.SDate DESC, s.SubID DESC";
  $stmt = $conn->prepare($sql);
  if ($types !== '') { $stmt->bind_param($types, ...$params); }
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows === 0) {
    echo '<tr><td colspan="9" class="py-4 text-center">No submissions found.</td></tr>'; exit;
  }

  while ($r = $res->fetch_assoc()) {
    $date = $r['SDate'] ? date('Y-m-d H:i', strtotime($r['SDate'])) : '';

    // Badge theo status
    $st = (string)$r['SStatus'];
    if (strcasecmp($st,'Submitted')===0) {
      $badge = '<span class="px-3 py-1 text-green-400 rounded-full bg-gray-900">Submitted</span>';
    } elseif (strcasecmp($st,'Late')===0) {
      $badge = '<span class="px-3 py-1 text-yellow-400 rounded-full bg-gray-900">Late</span>';
    } else {
      $badge = '<span class="px-3 py-1 text-red-400 rounded-full bg-gray-900">Not Submit</span>';
    }

    echo '<tr class="border-b border-gray-700 hover:bg-gray-800 transition-colors duration-200">';
    echo '  <td class="px-4 py-2">'.h($r['SubID']).'</td>';
    echo '  <td class="px-4 py-2">'.h($r['LearnerName'] ?: $r['AccountID']).'</td>';
    echo '  <td class="px-4 py-2">'.h($r['CourseName']  ?: $r['CourseID']).'</td>';
    echo '  <td class="px-4 py-2 break-all">'.h($r['Answer']).'</td>';
    echo '  <td class="px-4 py-2 text-center">'.h(rtrim(rtrim((string)$r['Mark'],'0'),'.')).'</td>';
    echo '  <td class="px-4 py-2 break-all">'.h($r['Feedback']).'</td>';
    echo '  <td class="px-4 py-2">'.h($date).'</td>';
    echo '  <td class="px-4 py-2">'.$badge.'</td>';
    echo '  <td class="px-4 py-2 text-center space-x-2">
              <a href="edit_submission.php?id='.rawurlencode($r['SubID']).'" class="px-3 py-1 bg-green-600 hover:bg-green-500 rounded text-white">Edit</a>
              <button class="px-3 py-1 bg-red-600 hover:bg-red-500 rounded text-white btn-del" data-id="'.h($r['SubID']).'">Delete</button>
            </td>';
    echo '</tr>';
  }
  exit;
}

/* ===================== DATA FOR FILTERS ===================== */
// Learners
$ls = $conn->prepare("SELECT AccountID, AName FROM accounts WHERE ARole='Learner' ORDER BY AName ASC");
$ls->execute(); $lr = $ls->get_result();
$learners = $lr->fetch_all(MYSQLI_ASSOC);

// Courses
$cs = $conn->prepare("SELECT CourseID, CName FROM courses ORDER BY CName ASC");
$cs->execute(); $cr = $cs->get_result();
$courses = $cr->fetch_all(MYSQLI_ASSOC);

// Status options (schema)
$statuses = ['Submitted','Late','Not Submit'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Submission Management</title>

  <!-- CSS chung đã tách -->
  <link rel="stylesheet" href="style_index.css">
  <!-- Icons + jQuery -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
</head>
<body class="bg-darkblue text-white min-h-screen flex items-center justify-center p-6">
  <div class="bg-cardblue text-white rounded-xl shadow-xl p-6 w-full max-w-7xl">
    <h2 class="text-2xl font-bold text-center mb-6">Submission Management</h2>

    <!-- Search -->
    <div class="flex justify-center mb-6 relative">
      <input type="text" id="search" placeholder="Search SubID / learner / course / answer"
             class="w-80 px-4 py-2 pl-10 bg-gray-700 text-gray-200 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary transition-colors duration-200">
      <i class="fas fa-search absolute left-1/2 -translate-x-36 top-2.5 text-gray-400"></i>
    </div>

    <!-- Filters -->
    <div class="flex flex-col md:flex-row items-center justify-center mb-6 space-y-4 md:space-y-0 md:space-x-4">
      <select id="filter_learner" class="px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
        <option value="">-- Filter by Learner --</option>
        <?php foreach ($learners as $l): ?>
          <option value="<?= h($l['AccountID']) ?>"><?= h($l['AName']) ?></option>
        <?php endforeach; ?>
      </select>

      <select id="filter_course" class="px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
        <option value="">-- Filter by Course --</option>
        <?php foreach ($courses as $c): ?>
          <option value="<?= h($c['CourseID']) ?>"><?= h($c['CName']) ?></option>
        <?php endforeach; ?>
      </select>

      <select id="filter_status" class="px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
        <option value="">-- Filter by Status --</option>
        <?php foreach ($statuses as $s): ?>
          <option value="<?= h($s) ?>"><?= h($s) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-between mb-4">
      <a href="add_submission.php"
         class="px-6 py-2 bg-primary text-textlight rounded-lg font-semibold hover:bg-primaryhover transition-colors duration-200 shadow-lg flex items-center space-x-2">
        <i class="fas fa-plus"></i><span>Add new submission</span>
      </a>
      <a href="dashboard.php"
         class="px-6 py-2 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-500 transition-colors duration-200 shadow-lg flex items-center space-x-2">
        <i class="fas fa-arrow-left"></i><span>Back to Dashboard</span>
      </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="table-auto w-full text-left">
        <thead>
          <tr class="bg-gray-700 rounded-lg">
            <th class="px-4 py-2">SubmissionID</th>
            <th class="px-4 py-2">Learner</th>
            <th class="px-4 py-2">Course</th>
            <th class="px-4 py-2">Answer</th>
            <th class="px-4 py-2 text-center">Mark</th>
            <th class="px-4 py-2">Feedback</th>
            <th class="px-4 py-2">Date Submitted</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2 text-center">Actions</th>
          </tr>
        </thead>
        <tbody id="submission_body"><!-- via AJAX --></tbody>
      </table>
    </div>
  </div>

  <script>
    $(function(){
      const ajaxUrl = location.pathname; // gọi chính file này
      let timer;

      function loadSubmissions(){
        const search  = $('#search').val();
        const learner = $('#filter_learner').val();
        const course  = $('#filter_course').val();
        const status  = $('#filter_status').val();

        $.get(ajaxUrl, { ajax: 1, search, learner, course, status })
          .done(html => $('#submission_body').html(html))
          .fail(() => $('#submission_body').html('<tr><td colspan="9" class="py-4 text-center">Load failed.</td></tr>'));
      }

      // DELETE handler
      $('#submission_body').on('click', '.btn-del', function(){
        const id = $(this).data('id');
        if (!id) return;
        if (!confirm('Are you sure to delete this submission?')) return;

        $.post(ajaxUrl, { ajax: 'delete', id })
          .done(res => {
            try {
              const data = JSON.parse(res);
              if (data.ok) loadSubmissions();
              else alert(data.msg || 'Delete failed');
            } catch {
              loadSubmissions();
            }
          })
          .fail(() => alert('Delete failed'));
      });

      // initial & events
      loadSubmissions();
      $('#filter_learner,#filter_course,#filter_status').on('change', loadSubmissions);
      $('#search').on('input', function(){ clearTimeout(timer); timer = setTimeout(loadSubmissions, 300); });
    });
  </script>
</body>
</html>
