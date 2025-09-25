
    
<?php
// payment.php — Payment Management (uses shared style_index.css)

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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['ajax'] ?? '') === 'delete') {
  $id = trim($_POST['id'] ?? '');
  if ($id === '') {
    http_response_code(400);
    echo json_encode(['ok'=>false,'msg'=>'Missing id']); exit;
  }
  $del = $conn->prepare("DELETE FROM payment WHERE PaymentID=?");
  $del->bind_param("s", $id);
  $del->execute();
  echo json_encode(['ok'=>true]); exit;
}

/* ===================== AJAX: LIST PAYMENTS ===================== */
if (isset($_GET['ajax'])) {
  $search  = trim($_GET['search']  ?? '');
  $learner = trim($_GET['learner'] ?? ''); // AccountID
  $course  = trim($_GET['course']  ?? ''); // CourseID
  $status  = trim($_GET['status']  ?? ''); // Paid|Processing|Not Confirmed|''

  $sql = "SELECT p.PaymentID, p.AccountID, p.CourseID, p.Amount, p.PayDate, p.PStatus, p.TransactionRef,
                 a.AName AS LearnerName,
                 c.CName AS CourseName
          FROM payment p
          LEFT JOIN accounts a ON a.AccountID = p.AccountID
          LEFT JOIN courses  c ON c.CourseID  = p.CourseID
          WHERE 1";
  $types = '';
  $params = [];

  if ($search !== '') {
    $like = "%{$search}%";
    $sql .= " AND (p.PaymentID LIKE ?
                   OR a.AName LIKE ?
                   OR c.CName LIKE ?
                   OR p.TransactionRef LIKE ?)";
    $types .= 'ssss';
    array_push($params, $like, $like, $like, $like);
  }
  if ($learner !== '') { $sql .= " AND p.AccountID = ?"; $types.='s'; $params[]=$learner; }
  if ($course  !== '') { $sql .= " AND p.CourseID  = ?"; $types.='s'; $params[]=$course;  }
  if ($status  !== '') { $sql .= " AND p.PStatus   = ?"; $types.='s'; $params[]=$status;  }

  $sql .= " ORDER BY p.PayDate DESC";
  $stmt = $conn->prepare($sql);
  if ($types !== '') { $stmt->bind_param($types, ...$params); }
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows === 0) {
    echo '<tr><td colspan="8" class="py-4 text-center">No payments found.</td></tr>'; exit;
  }

  while ($r = $res->fetch_assoc()) {
    $date   = $r['PayDate'] ? date('d/m/Y H:i', strtotime($r['PayDate'])) : '';
    $amount = is_null($r['Amount']) ? '' : number_format((float)$r['Amount'], 2);

    $st = (string)$r['PStatus'];
    if (strcasecmp($st,'Paid')===0) {
      $badge = '<span class="px-3 py-1 text-green-400 rounded-full bg-gray-900">Paid</span>';
    } elseif (strcasecmp($st,'Processing')===0) {
      $badge = '<span class="px-3 py-1 text-yellow-400 rounded-full bg-gray-900">Processing</span>';
    } else {
      $badge = '<span class="px-3 py-1 text-red-400 rounded-full bg-gray-900">Not Confirmed</span>';
    }

    echo '<tr class="border-b border-gray-700 hover:bg-gray-800 transition-colors duration-200">';
    echo '  <td class="py-3 px-4">'.h($r['PaymentID']).'</td>';
    echo '  <td class="py-3 px-4">'.h($r['LearnerName'] ?: $r['AccountID']).'</td>';
    echo '  <td class="py-3 px-4">'.h($r['CourseName'] ?: $r['CourseID']).'</td>';
    echo '  <td class="py-3 px-4">'.h($amount).'</td>';
    echo '  <td class="py-3 px-4">'.$date.'</td>';
    echo '  <td class="py-3 px-4">'.$badge.'</td>';
    echo '  <td class="py-3 px-4">'.h($r['TransactionRef']).'</td>';
    echo '  <td class="px-4 py-2 space-x-2">
              <a href="edit_payment.php?id='.rawurlencode($r['PaymentID']).'" class="px-3 py-1 bg-green-600 hover:bg-green-500 rounded text-white">Edit</a>
              <button class="px-3 py-1 bg-red-600 hover:bg-red-500 rounded text-white btn-del" data-id="'.h($r['PaymentID']).'">Delete</button>
            </td>';
    echo '</tr>';
  }
  exit;
}

/* ===================== DATA FOR FILTERS ===================== */
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

// Status list (schema)
$statuses = ['Paid','Processing','Not Confirmed'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Management</title>

  <!-- Shared index styles -->
  <link rel="stylesheet" href="style_index.css">
  <!-- Icons + jQuery -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
</head>
<body class="bg-darkblue text-textlight p-6 min-h-screen">
  <div class="max-w-7xl mx-auto bg-cardblue rounded-xl shadow-2xl p-6">
    <h2 class="text-2xl font-bold text-center mb-6">Payment Management</h2>

    <!-- Search -->
    <div class="flex justify-center mb-6 relative">
      <input id="search" type="text" placeholder="Search PaymentID / learner / course / ref"
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
      <button onclick="location.href='add_payment.php'"
              class="px-6 py-2 bg-primary text-textlight rounded-lg font-semibold hover:bg-primaryhover transition-colors duration-200 shadow-lg flex items-center space-x-2">
        <i class="fas fa-plus"></i><span>Add new payment</span>
      </button>

      <a href="dashboard.php"
         class="px-6 py-2 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-500 transition-colors duration-200 shadow-lg flex items-center space-x-2">
        <i class="fas fa-arrow-left"></i><span>Back to Dashboard</span>
      </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="table-auto w-full text-left border-collapse">
        <thead class="bg-gray-700 text-gray-300">
          <tr>
            <th class="py-3 px-4 text-left rounded-tl-lg">PaymentID</th>
            <th class="py-3 px-4 text-left">Learner</th>
            <th class="py-3 px-4 text-left">Course</th>
            <th class="py-3 px-4 text-left">Amount</th>
            <th class="py-3 px-4 text-left">Payment date</th>
            <th class="py-3 px-4 text-left">Status</th>
            <th class="py-3 px-4 text-left">Transaction Ref</th>
            <th class="py-3 px-4 text-left rounded-tr-lg">Action</th>
          </tr>
        </thead>
        <tbody id="payment_body" class="text-gray-300 text-sm font-light"><!-- via AJAX --></tbody>
      </table>
    </div>
  </div>

  <script>
    $(function () {
      const ajaxUrl = location.pathname; // call this same file
      let timer;

      function loadPayments() {
        const search  = $('#search').val();
        const learner = $('#filter_learner').val();
        const course  = $('#filter_course').val();
        const status  = $('#filter_status').val();

        $.get(ajaxUrl, { ajax: 1, search, learner, course, status })
          .done(html => $('#payment_body').html(html))
          .fail(() => $('#payment_body').html('<tr><td colspan="8" class="py-4 text-center">Load failed.</td></tr>'));
      }

      // Delete
      $('#payment_body').on('click', '.btn-del', function () {
        const id = $(this).data('id');
        if (!id) return;
        if (!confirm('Are you sure to delete this payment?')) return;

        $.post(ajaxUrl, { ajax: 'delete', id })
          .done(res => {
            try {
              const data = JSON.parse(res);
              if (data.ok) loadPayments();
              else alert(data.msg || 'Delete failed');
            } catch {
              loadPayments();
            }
          })
          .fail(() => alert('Delete failed'));
      });

      // Initial + events
      loadPayments();
      $('#filter_learner,#filter_course,#filter_status').on('change', loadPayments);
      $('#search').on('input', function(){ clearTimeout(timer); timer = setTimeout(loadPayments, 300); });
    });
  </script>
</body>
</html>
<?php $conn->close(); ?>
