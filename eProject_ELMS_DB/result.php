<?php
// result.php — Result Management (uses shared style_index.css)

session_start();
// Only Admin/Instructor
if (!isset($_SESSION["AccountID"]) || !in_array(strtolower($_SESSION["ARole"] ?? ''), ['admin','instructor'])) {
  header("Location: login.php"); exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// DB connect
$servername = "127.0.0.1";
$username   = "root";
$password   = "";
$dbname     = "elmsdb";
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset('utf8mb4');

function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

/* ===================== AJAX: DELETE ===================== */
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['ajax'] ?? '')==='delete') {
  $id = trim($_POST['id'] ?? '');
  if ($id==='') { http_response_code(400); echo json_encode(['ok'=>false,'msg'=>'Missing id']); exit; }

  $stmt = $conn->prepare("DELETE FROM results WHERE ResultID = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  echo json_encode(['ok'=>true]);
  exit;
}

/* ===================== AJAX: LIST RESULTS ===================== */
if (isset($_GET['ajax'])) {
  $search = trim($_GET['search'] ?? ''); // learner/course/exam(Content)
  $course = trim($_GET['course'] ?? ''); // CourseID
  $exam   = trim($_GET['exam']   ?? ''); // matched against Content
  $status = trim($_GET['status'] ?? ''); // Passed | Failed | Pending

  $sql = "SELECT r.ResultID, r.AccountID, r.CourseID, r.Content, r.Mark, r.RStatus,
                 a.AName AS LearnerName, c.CName AS CourseName
          FROM results r
          LEFT JOIN accounts a ON a.AccountID = r.AccountID
          LEFT JOIN courses  c ON c.CourseID  = r.CourseID
          WHERE 1";
  $types = '';
  $params = [];

  if ($search !== '') {
    $like = "%{$search}%";
    $sql  .= " AND (a.AName LIKE ? OR c.CName LIKE ? OR r.Content LIKE ?)";
    $types.= 'sss'; $params[]=$like; $params[]=$like; $params[]=$like;
  }
  if ($course !== '') {
    $sql  .= " AND r.CourseID = ?";
    $types.= 's';  $params[]=$course;
  }
  if ($exam !== '') {
    $like = "%{$exam}%";
    $sql  .= " AND r.Content LIKE ?";
    $types.= 's';  $params[]=$like;
  }
  if ($status !== '') {
    $sql  .= " AND r.RStatus = ?";
    $types.= 's';  $params[]=$status;
  }

  $sql .= " ORDER BY r.ResultID DESC";
  $stmt = $conn->prepare($sql);
  if ($types!=='') $stmt->bind_param($types, ...$params);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows===0) {
    echo '<tr><td colspan="7" class="py-4 text-center">No results found.</td></tr>'; exit;
  }

  while ($r = $res->fetch_assoc()) {
    $st = (string)$r['RStatus'];
    $badge =
      (strcasecmp($st,'Passed')===0)  ? '<span class="px-3 py-1 text-green-400 rounded-full bg-gray-900">Passed</span>' :
      ((strcasecmp($st,'Failed')===0) ? '<span class="px-3 py-1 text-red-400 rounded-full bg-gray-900">Failed</span>' :
                                        '<span class="px-3 py-1 text-yellow-400 rounded-full bg-gray-900">Pending</span>');

    $mark = $r['Mark'];
    if ($mark !== null && $mark !== '') {
      $mark = rtrim(rtrim((string)$mark,'0'),'.'); // neat display (e.g., 85.00 -> 85)
    }

    echo '<tr class="border-b border-gray-700 hover:bg-gray-800 transition-colors duration-200">';
    echo '  <td class="px-4 py-3">'.h($r['ResultID']).'</td>';
    echo '  <td class="px-4 py-3">'.h($r['LearnerName'] ?: $r['AccountID']).'</td>';
    echo '  <td class="px-4 py-3">'.h($r['CourseName']  ?: $r['CourseID']).'</td>';
    echo '  <td class="px-4 py-3">'.h($r['Content'] ?: '').'</td>'; // treat Content as exam name
    echo '  <td class="px-4 py-3">'.h($mark).'</td>';
    echo '  <td class="px-4 py-3">'.$badge.'</td>';
    echo '  <td class="px-4 py-2 space-x-2">
              <a href="edit_result.php?id='.rawurlencode((string)$r['ResultID']).'" class="px-3 py-1 bg-green-600 hover:bg-green-500 rounded text-white">Edit</a>
              <button class="px-3 py-1 bg-red-600 hover:bg-red-500 rounded text-white btn-del" data-id="'.h($r['ResultID']).'">Delete</button>
            </td>';
    echo '</tr>';
  }
  exit;
}

/* ===================== FILTER DATA ===================== */
// Courses
$cq = $conn->prepare("SELECT CourseID, CName FROM courses ORDER BY CName ASC");
$cq->execute(); $cr = $cq->get_result();
$courses = $cr->fetch_all(MYSQLI_ASSOC);

// Distinct exam (from Content)
$eq = $conn->prepare("SELECT DISTINCT Content FROM results WHERE Content IS NOT NULL AND Content<>'' ORDER BY Content ASC");
$eq->execute(); $er = $eq->get_result();
$exams = []; while ($row = $er->fetch_assoc()) $exams[] = $row['Content'];

// Status options
$statuses = ['Passed','Failed','Pending'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Result Management</title>

  <!-- Shared index layout styles -->
  <link rel="stylesheet" href="style_index.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
</head>
<body class="bg-darkblue flex items-center justify-center min-h-screen p-4">

  <div class="bg-cardblue text-textlight rounded-xl shadow-2xl p-8 w-full max-w-7xl relative">
    <h2 class="text-2xl font-bold mb-6 text-center">Result Management</h2>

    <!-- Search -->
    <div class="flex justify-center mb-6 relative">
      <input id="search" type="text" placeholder="Search learner / course / exam"
             class="w-80 px-4 py-2 pl-10 bg-gray-700 text-gray-200 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary transition-colors duration-200">
      <i class="fas fa-search absolute left-1/2 -translate-x-36 top-2.5 text-gray-400"></i>
    </div>

    <!-- Filters -->
    <div class="flex flex-col md:flex-row items-center justify-center mb-6 space-y-4 md:space-y-0 md:space-x-4">
      <select id="filter_course" class="px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
        <option value="">-- Filter by Course --</option>
        <?php foreach ($courses as $c): ?>
          <option value="<?= h($c['CourseID']) ?>"><?= h($c['CName']) ?></option>
        <?php endforeach; ?>
      </select>

      <select id="filter_exam" class="px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
        <option value="">-- Filter by Exam --</option>
        <?php foreach ($exams as $e): ?>
          <option value="<?= h($e) ?>"><?= h($e) ?></option>
        <?php endforeach; ?>
      </select>

      <select id="filter_status" class="px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
        <option value="">-- Filter by Status --</option>
        <?php foreach ($statuses as $s): ?>
          <option value="<?= h($s) ?>"><?= h($s) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="flex items-center justify-between mb-4">
      <a href="add_result.php"
         class="px-6 py-2 bg-primary text-textlight rounded-lg font-semibold hover:bg-primaryhover transition-colors duration-200 shadow-lg flex items-center space-x-2">
        <i class="fas fa-plus"></i><span>Add new result</span>
      </a>

      <a href="dashboard.php"
         class="px-6 py-2 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-500 transition-colors duration-200 shadow-lg flex items-center space-x-2">
        <i class="fas fa-arrow-left"></i><span>Back to Dashboard</span>
      </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead>
          <tr class="bg-gray-700 rounded-lg">
            <th class="px-4 py-2 font-medium">ResultID</th>
            <th class="px-4 py-2 font-medium">Learner name</th>
            <th class="px-4 py-2 font-medium">Course</th>
            <th class="px-4 py-2 font-medium">Exam</th>
            <th class="px-4 py-2 font-medium">Score</th>
            <th class="px-4 py-2 font-medium">Status</th>
            <th class="px-4 py-2 font-medium">Action</th>
          </tr>
        </thead>
        <tbody id="result_body"><!-- via AJAX --></tbody>
      </table>
    </div>
  </div>

  <script>
    $(function(){
      const ajaxUrl = location.pathname; // hit this file
      let timer;

      function loadResults(){
        const search = $('#search').val();
        const course = $('#filter_course').val();
        const exam   = $('#filter_exam').val();
        const status = $('#filter_status').val();

        $.get(ajaxUrl, { ajax: 1, search, course, exam, status })
          .done(html => $('#result_body').html(html))
          .fail(() => $('#result_body').html('<tr><td colspan="7" class="py-4 text-center">Load failed.</td></tr>'));
      }

      // delete handler
      $('#result_body').on('click', '.btn-del', function(){
        const id = $(this).data('id');
        if(!id) return;
        if(!confirm('Are you sure to delete this result?')) return;

        $.post(ajaxUrl, { ajax:'delete', id })
          .done(res=>{
            try{
              const data = JSON.parse(res);
              if(data.ok) loadResults(); else alert(data.msg || 'Delete failed');
            } catch { loadResults(); }
          })
          .fail(()=> alert('Delete failed'));
      });

      // initial & events
      loadResults();
      $('#filter_course,#filter_exam,#filter_status').on('change', loadResults);
      $('#search').on('input', function(){ clearTimeout(timer); timer = setTimeout(loadResults, 300); });
    });
  </script>
</body>
</html>
