<?php
// lesson.php
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

/* ===================== AJAX: DELETE ===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['ajax'] ?? '') === 'delete') {
  $id = trim($_POST['id'] ?? '');
  if ($id === '') {
    http_response_code(400);
    echo json_encode(['ok'=>false, 'msg'=>'Missing id']);
    exit;
  }
  $del = $conn->prepare("DELETE FROM lessons WHERE LessonID = ?");
  $del->bind_param("s", $id);
  $del->execute();
  echo json_encode(['ok'=>true]);
  exit;
}

/* ===================== AJAX: LIST LESSONS ===================== */
if (isset($_GET['ajax'])) {
  $search   = trim($_GET['search'] ?? '');
  $courseId = trim($_GET['course'] ?? '');
  $type     = trim($_GET['type'] ?? '');
  $status   = trim($_GET['status'] ?? ''); // 'Paid' | 'Processing' | 'Not Confirmed' | ''

  $sql = "SELECT l.LessonID, l.CourseID, l.LName, l.Content, l.LessonType, l.Ordinal,
                 l.CreatedAt, l.LStatus, c.CName
          FROM lessons l
          LEFT JOIN courses c ON c.CourseID = l.CourseID
          WHERE 1";
  $typesStr = '';
  $params   = [];

  if ($search !== '') {
    $sql .= " AND (l.LName LIKE ? OR l.Content LIKE ? OR c.CName LIKE ? OR l.LessonID LIKE ?)";
    $like = "%{$search}%";
    $typesStr .= 'ssss';
    array_push($params, $like, $like, $like, $like);
  }
  if ($courseId !== '') {
    $sql .= " AND l.CourseID = ?";
    $typesStr .= 's'; $params[] = $courseId;
  }
  if ($type !== '') {
    $sql .= " AND l.LessonType = ?";
    $typesStr .= 's'; $params[] = $type;
  }
  if ($status !== '') {
    $sql .= " AND l.LStatus = ?";
    $typesStr .= 's'; $params[] = $status;
  }

  $sql .= " ORDER BY l.CreatedAt DESC, l.Ordinal ASC";

  $stmt = $conn->prepare($sql);
  if ($typesStr !== '') { $stmt->bind_param($typesStr, ...$params); }
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows === 0) {
    echo '<tr><td colspan="9" class="py-4 text-center">No lessons found.</td></tr>';
    exit;
  }

  while ($r = $res->fetch_assoc()) {
    $created = !empty($r['CreatedAt']) ? date('d/m/Y', strtotime($r['CreatedAt'])) : '';

    $st = (string)$r['LStatus'];
    if (strcasecmp($st,'Paid')===0) {
      $badge = '<span class="px-3 py-1 text-green-400 rounded-full bg-gray-900">Paid</span>';
    } elseif (strcasecmp($st,'Processing')===0) {
      $badge = '<span class="px-3 py-1 text-yellow-400 rounded-full bg-gray-900">Processing</span>';
    } else {
      $badge = '<span class="px-3 py-1 text-red-400 rounded-full bg-gray-900">Not Confirmed</span>';
    }

    echo '<tr class="border-b border-gray-700 hover:bg-gray-800 transition-colors duration-200">';
    echo '  <td class="px-4 py-3">'.htmlspecialchars($r['LessonID']).'</td>';
    echo '  <td class="px-4 py-3">'.htmlspecialchars($r['CName'] ?: $r['CourseID']).'</td>';
    echo '  <td class="px-4 py-3">'.htmlspecialchars($r['LName']).'</td>';
    echo '  <td class="px-4 py-3 break-all">'.htmlspecialchars($r['Content']).'</td>';
    echo '  <td class="px-4 py-3">'.htmlspecialchars($r['LessonType']).'</td>';
    echo '  <td class="px-4 py-3">'.htmlspecialchars($r['Ordinal']).'</td>';
    echo '  <td class="px-4 py-3">'.$created.'</td>';
    echo '  <td class="px-4 py-3">'.$badge.'</td>';
    echo '  <td class="px-4 py-2 space-x-2">
              <a href="edit_lesson.php?id='.rawurlencode($r['LessonID']).'" class="px-3 py-1 bg-green-600 hover:bg-green-500 rounded text-white">Edit</a>
              <button class="px-3 py-1 bg-red-600 hover:bg-red-500 rounded text-white btn-del" data-id="'.htmlspecialchars($r['LessonID']).'">Delete</button>
            </td>';
    echo '</tr>';
  }
  exit;
}

/* ===================== DATA FOR FILTERS ===================== */
// Courses
$courses = [];
$courseStmt = $conn->prepare("SELECT CourseID, CName FROM courses ORDER BY CName ASC");
$courseStmt->execute();
$courses = $courseStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Lesson types (distinct)
$types = [];
$typeStmt = $conn->prepare("SELECT DISTINCT LessonType FROM lessons WHERE LessonType IS NOT NULL AND LessonType<>'' ORDER BY LessonType ASC");
$typeStmt->execute();
$r1 = $typeStmt->get_result();
while ($row = $r1->fetch_assoc()) $types[] = $row['LessonType'];

// Statuses (distinct)
$statuses = [];
$statusStmt = $conn->prepare("SELECT DISTINCT LStatus FROM lessons WHERE LStatus IS NOT NULL AND LStatus<>'' ORDER BY FIELD(LStatus,'Paid','Processing','Not Confirmed'), LStatus");
$statusStmt->execute();
$r2 = $statusStmt->get_result();
while ($row = $r2->fetch_assoc()) $statuses[] = $row['LStatus'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Lesson Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <!-- Tailwind CDN -->
  <script>
    tailwind = {
      theme: {
        extend: {
          fontFamily: { sans: ['Inter','ui-sans-serif','system-ui'] },
          colors: {
            darkblue:'#111827', cardblue:'#1f2937',
            primary:'#3b82f6', primaryhover:'#2563eb', textlight:'#f9fafb',
          }
        }
      }
    }
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="style_index.css"><!-- CSS chung đã tách -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
</head>
<body class="bg-darkblue text-textlight p-4 min-h-screen flex items-center justify-center">
  <div class="bg-cardblue rounded-xl shadow-2xl p-8 w-full max-w-7xl">
    <h2 class="text-2xl font-bold mb-6 text-center">Lesson Management</h2>

    <!-- Search -->
    <div class="flex justify-center mb-6 relative">
      <input id="search" type="text" placeholder="Search lesson / course / content..."
             class="w-80 px-4 py-2 pl-10 bg-gray-700 text-gray-200 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary transition-colors duration-200">
      <i class="fas fa-search absolute left-1/2 -translate-x-36 top-2.5 text-gray-400"></i>
    </div>

    <!-- Filters -->
    <div class="flex flex-col md:flex-row items-center justify-center mb-6 space-y-4 md:space-y-0 md:space-x-4">
      <select id="filter_course" class="px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
        <option value="">-- Filter by Course --</option>
        <?php foreach ($courses as $c): ?>
          <option value="<?= htmlspecialchars($c['CourseID']) ?>"><?= htmlspecialchars($c['CName']) ?></option>
        <?php endforeach; ?>
      </select>

      <select id="filter_type" class="px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
        <option value="">-- Filter by Lesson type --</option>
        <?php foreach ($types as $t): ?>
          <option value="<?= htmlspecialchars($t) ?>"><?= htmlspecialchars($t) ?></option>
        <?php endforeach; ?>
      </select>

      <select id="filter_status" class="px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
        <option value="">-- Filter by Status --</option>
        <?php foreach ($statuses as $s): ?>
          <option value="<?= htmlspecialchars($s) ?>"><?= htmlspecialchars($s) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-between mb-4">
      <a href="add_lesson.php"
         class="px-6 py-2 bg-primary text-textlight rounded-lg font-semibold hover:bg-primaryhover transition-colors duration-200 shadow-lg flex items-center space-x-2">
        <i class="fas fa-plus"></i><span>Add new lesson</span>
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
            <th class="px-4 py-2 font-medium">LessonID</th>
            <th class="px-4 py-2 font-medium">Course</th>
            <th class="px-4 py-2 font-medium">Lesson name</th>
            <th class="px-4 py-2 font-medium">Content</th>
            <th class="px-4 py-2 font-medium">Lesson type</th>
            <th class="px-4 py-2 font-medium">Ordinal</th>
            <th class="px-4 py-2 font-medium">Date created</th>
            <th class="px-4 py-2 font-medium">Status</th>
            <th class="px-4 py-2 font-medium">Action</th>
          </tr>
        </thead>
        <tbody id="lesson_body"><!-- via AJAX --></tbody>
      </table>
    </div>
  </div>

  <script>
    $(function(){
      const ajaxUrl = location.pathname; // gọi chính file này
      let timer;

      function loadLessons() {
        const search = $('#search').val();
        const course = $('#filter_course').val();
        const type   = $('#filter_type').val();
        const status = $('#filter_status').val();

        $.get(ajaxUrl, { ajax: 1, search, course, type, status })
          .done(html => $('#lesson_body').html(html))
          .fail(() => $('#lesson_body').html('<tr><td colspan="9" class="py-4 text-center">Load failed.</td></tr>'));
      }

      function bindDelete() {
        $('#lesson_body').on('click', '.btn-del', function(){
          const id = $(this).data('id');
          if (!id) return;
          if (!confirm('Are you sure to delete this lesson?')) return;

          $.post(ajaxUrl, { ajax: 'delete', id })
            .done(res => {
              try {
                const data = JSON.parse(res);
                if (data.ok) loadLessons();
                else alert(data.msg || 'Delete failed');
              } catch {
                loadLessons();
              }
            })
            .fail(() => alert('Delete failed'));
        });
      }

      // initial
      loadLessons();
      bindDelete();

      // events
      $('#filter_course,#filter_type,#filter_status').on('change', loadLessons);
      $('#search').on('input', function(){ clearTimeout(timer); timer = setTimeout(loadLessons, 300); });
    });
  </script>
</body>
</html>
<?php $conn->close(); ?>
