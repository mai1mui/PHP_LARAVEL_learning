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

/*
  Giả định bảng `exams` có cấu trúc (bạn điều chỉnh tên cột nếu khác):
  - ExamID (varchar PK), CourseID (varchar FK -> courses), DocType (varchar)  // Exercises | Quiz | Exam | Essays ...
  - Description (text), StartTime (datetime), DurationMinutes (int)
  - CreatorID (varchar FK -> accounts), CreatedAt (datetime DEFAULT CURRENT_TIMESTAMP)
*/

/* ===================== AJAX: DELETE ===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['ajax'] ?? '') === 'delete') {
  $id = trim($_POST['id'] ?? '');
  if ($id === '') {
    http_response_code(400);
    echo json_encode(['ok'=>false, 'msg'=>'Missing id']); exit;
  }
  $del = $conn->prepare("DELETE FROM exams WHERE ExamID = ?");
  $del->bind_param("s", $id);
  $del->execute();
  echo json_encode(['ok'=>true]); exit;
}

/* ===================== AJAX: LIST ===================== */
if (isset($_GET['ajax'])) {
  $search  = trim($_GET['search']  ?? '');
  $course  = trim($_GET['course']  ?? ''); // CourseID
  $doc     = trim($_GET['doc']     ?? ''); // DocType
  $creator = trim($_GET['creator'] ?? ''); // CreatorID

  $sql = "SELECT e.ExamID, e.CourseID, c.CName,
                 e.DocType, e.Description, e.StartTime, e.DurationMinutes,
                 e.CreatorID, a.AName AS CreatorName, e.CreatedAt
          FROM exams e
          LEFT JOIN courses  c ON c.CourseID  = e.CourseID
          LEFT JOIN accounts a ON a.AccountID = e.CreatorID
          WHERE 1";
  $types = '';
  $params = [];

  if ($search !== '') {
    $sql .= " AND (e.ExamID LIKE ? OR e.Description LIKE ? OR e.DocType LIKE ? OR c.CName LIKE ?)";
    $like = "%{$search}%";
    $types .= 'ssss'; array_push($params, $like, $like, $like, $like);
  }
  if ($course !== '') { $sql .= " AND e.CourseID = ?"; $types.='s'; $params[]=$course; }
  if ($doc !== '')    { $sql .= " AND e.DocType  = ?"; $types.='s'; $params[]=$doc; }
  if ($creator !== ''){ $sql .= " AND e.CreatorID= ?"; $types.='s'; $params[]=$creator; }

  $sql .= " ORDER BY e.StartTime DESC, e.CreatedAt DESC";

  $stmt = $conn->prepare($sql);
  if ($types !== '') { $stmt->bind_param($types, ...$params); }
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows === 0) {
    echo '<tr><td colspan="9" class="py-4 text-center">No exams found.</td></tr>'; exit;
  }

  while ($r = $res->fetch_assoc()) {
    $start = $r['StartTime'] ? date('H:i d/m/Y', strtotime($r['StartTime'])) : '';
    $created = $r['CreatedAt'] ? date('d/m/Y', strtotime($r['CreatedAt'])) : '';
    // Hiển thị thời lượng: X giờ Y phút nếu muốn
    $durM = (int)($r['DurationMinutes'] ?? 0);
    $durText = $durM>0 ? ( ($durM>=60 ? floor($durM/60).'h '.($durM%60).'m' : $durM.'m') ) : '';

    echo '<tr>';
    echo '  <td>'.htmlspecialchars($r['ExamID']).'</td>';
    echo '  <td>'.htmlspecialchars($r['CourseID']).'</td>';
    echo '  <td>'.htmlspecialchars($r['DocType']).'</td>';
    echo '  <td>'.htmlspecialchars($r['Description']).'</td>';
    echo '  <td>'.$start.'</td>';
    echo '  <td>'.$durText.'</td>';
    echo '  <td>'.htmlspecialchars($r['CreatorName'] ?: $r['CreatorID']).'</td>';
    echo '  <td>'.$created.'</td>';
    echo '  <td class="actions">
              <a href="edit_exam.php?id='.rawurlencode($r['ExamID']).'" class="btn btn--primary">Edit</a>
              <button class="btn btn--ghost btn-del" data-id="'.htmlspecialchars($r['ExamID']).'">Delete</button>
            </td>';
    echo '</tr>';
  }
  exit;
}

/* ===================== DATA FOR FILTERS ===================== */
// Courses
$courses = [];
$cs = $conn->prepare("SELECT CourseID, CName FROM courses ORDER BY CName ASC");
$cs->execute();
$cr = $cs->get_result();
while ($row = $cr->fetch_assoc()) { $courses[] = $row; }

// Creators (Admin/Instructor)
$creators = [];
$qq = $conn->prepare("SELECT AccountID, AName FROM accounts WHERE ARole IN('Admin','Instructor') ORDER BY AName ASC");
$qq->execute();
$qres = $qq->get_result();
while ($row = $qres->fetch_assoc()) { $creators[] = $row; }

// Doc types (distinct)
$doctypes = [];
try {
  $dt = $conn->query("SELECT DISTINCT DocType FROM exams WHERE DocType IS NOT NULL AND DocType<>'' ORDER BY DocType ASC");
  while ($row = $dt->fetch_assoc()) { $doctypes[] = $row['DocType']; }
} catch (\Throwable $e) {
  // nếu bảng/field khác, có thể để fallback cứng
  $doctypes = ['Exercises','Quiz','Exam','Essays'];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Exam Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  <!-- CSS chung cho layout index -->
  <link rel="stylesheet" href="style_index.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
</head>
<body>
  <div class="container-wide">
    <h1 class="page-title">Exam Management</h1>

    <!-- Controls -->
    <div class="control-row">
      <!-- Search -->
      <div class="control-wrap">
        <i class="fa-solid fa-search search-icon"></i>
        <input id="search" class="input input--search" placeholder="Search ExamID / description / doc / course">
      </div>

      <!-- Filter by Course -->
      <select id="filter_course" class="select">
        <option value="">-- Filter by Course --</option>
        <?php foreach ($courses as $c): ?>
          <option value="<?= htmlspecialchars($c['CourseID']) ?>"><?= htmlspecialchars($c['CName']) ?></option>
        <?php endforeach; ?>
      </select>

      <!-- Filter by Document -->
      <select id="filter_doc" class="select">
        <option value="">-- Filter by Document --</option>
        <?php foreach ($doctypes as $d): ?>
          <option value="<?= htmlspecialchars($d) ?>"><?= htmlspecialchars($d) ?></option>
        <?php endforeach; ?>
      </select>

      <!-- Filter by Creator -->
      <select id="filter_creator" class="select">
        <option value="">-- Filter by Creator --</option>
        <?php foreach ($creators as $u): ?>
          <option value="<?= htmlspecialchars($u['AccountID']) ?>"><?= htmlspecialchars($u['AName']) ?></option>
        <?php endforeach; ?>
      </select>

      <!-- Actions (right) -->
      <div style="margin-left:auto; display:flex; gap:.5rem;">
        <a href="add_exam.php" class="btn btn--primary"><i class="fa fa-plus"></i> Add new exam</a>
        <a href="dashboard.php" class="btn btn--ghost"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
      </div>
    </div>

    <!-- Table -->
    <div class="card table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>ExamID</th>
            <th>CourseID</th>
            <th>Document</th>
            <th>Description</th>
            <th>Start time</th>
            <th>Test duration</th>
            <th>Creator</th>
            <th>Date created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="exam_body">
          <!-- via AJAX -->
        </tbody>
      </table>
    </div>
  </div>

  <script>
    $(function(){
      const ajaxUrl = location.pathname; // gọi chính file này
      let timer;

      function loadExams(){
        const search  = $('#search').val();
        const course  = $('#filter_course').val();
        const doc     = $('#filter_doc').val();
        const creator = $('#filter_creator').val();

        $.get(ajaxUrl, { ajax: 1, search, course, doc, creator })
          .done(html => $('#exam_body').html(html))
          .fail(()=> $('#exam_body').html('<tr><td colspan="9" class="text-center">Load failed.</td></tr>'));
      }

      // delete
      $('#exam_body').on('click', '.btn-del', function(){
        const id = $(this).data('id');
        if (!id) return;
        if (!confirm('Are you sure to delete this exam?')) return;

        $.post(ajaxUrl, { ajax: 'delete', id })
          .done(res => {
            try{
              const data = JSON.parse(res);
              if (data.ok) loadExams();
              else alert(data.msg || 'Delete failed');
            }catch(_e){
              loadExams();
            }
          })
          .fail(()=> alert('Delete failed'));
      });

      // events
      loadExams();
      $('#filter_course,#filter_doc,#filter_creator').on('change', loadExams);
      $('#search').on('input', function(){ clearTimeout(timer); timer=setTimeout(loadExams, 300); });
    });
  </script>
</body>
</html>
<?php $conn->close(); ?>
