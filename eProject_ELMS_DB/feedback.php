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

/* ===================== Kiểm tra cột Rating (tuỳ chọn) ===================== */
$hasRating = false;
try {
  $chk = $conn->prepare("SELECT 1 FROM information_schema.columns WHERE table_schema=? AND table_name='feedback' AND column_name='Rating' LIMIT 1");
  $chk->bind_param("s", $dbname);
  $chk->execute();
  $hasRating = (bool)$chk->get_result()->fetch_row();
} catch (\Throwable $e) {
  $hasRating = false; // an toàn
}

/* ===================== AJAX: DELETE ===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['ajax'] ?? '') === 'delete') {
  $id = trim($_POST['id'] ?? '');
  if ($id === '') { http_response_code(400); echo json_encode(['ok'=>false,'msg'=>'Missing id']); exit; }

  $del = $conn->prepare("DELETE FROM feedback WHERE FeedbackID = ?");
  $del->bind_param("s", $id);
  $del->execute();
  echo json_encode(['ok'=>true]); exit;
}

/* ===================== AJAX: LIST FEEDBACK ===================== */
if (isset($_GET['ajax'])) {
  $search = trim($_GET['search'] ?? '');
  $userId = trim($_GET['user']   ?? '');  // AccountID
  $status = trim($_GET['status'] ?? '');  // Processed | Waiting
  $rating = trim($_GET['rating'] ?? '');  // 1..5 (nếu có cột)

  $sql = "SELECT f.FeedbackID, f.AccountID, f.Content, f.CreatedAt, f.FStatus,
                 a.AName ".($hasRating ? ", f.Rating " : " ");
  $sql .= "FROM feedback f
           LEFT JOIN accounts a ON a.AccountID = f.AccountID
           WHERE 1";
  $types = '';
  $params = [];

  if ($search !== '') {
    $sql .= " AND (f.FeedbackID LIKE ? OR f.Content LIKE ? OR a.AName LIKE ?)";
    $like = "%{$search}%";
    $types .= 'sss'; array_push($params, $like, $like, $like);
  }
  if ($userId !== '') { $sql .= " AND f.AccountID = ?"; $types.='s'; $params[]=$userId; }
  if ($status !== '') { $sql .= " AND f.FStatus = ?";   $types.='s'; $params[]=$status; }
  if ($hasRating && $rating !== '') { $sql .= " AND f.Rating = ?"; $types.='i'; $params[]=(int)$rating; }

  $sql .= " ORDER BY f.CreatedAt DESC";

  $stmt = $conn->prepare($sql);
  if ($types !== '') $stmt->bind_param($types, ...$params);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows === 0) {
    echo '<tr><td colspan="7" class="text-center">No feedback found.</td></tr>'; exit;
  }

  while ($r = $res->fetch_assoc()) {
    $date = $r['CreatedAt'] ? date('d/m/Y', strtotime($r['CreatedAt'])) : '';
    // vẽ sao
    $stars = '-';
    if ($hasRating) {
      $n = max(0, min(5, (int)($r['Rating'] ?? 0)));
      $stars = str_repeat('★', $n) . str_repeat('☆', 5-$n);
    }

    echo '<tr>';
    echo '  <td>'.htmlspecialchars($r['FeedbackID']).'</td>';
    echo '  <td>'.htmlspecialchars($r['AName'] ?: $r['AccountID']).'</td>';
    echo '  <td>'.($hasRating ? '<span class="stars">'.$stars.'</span>' : '<span class="muted">N/A</span>').'</td>';
    echo '  <td class="truncate">'.htmlspecialchars($r['Content']).'</td>';
    echo '  <td>'.$date.'</td>';
    echo '  <td>'.(strcasecmp($r['FStatus'],'Processed')===0 ? '<span class="badge badge--green">Processed</span>' : '<span class="badge badge--yellow">Waiting</span>').'</td>';
    echo '  <td class="actions">
              <a class="btn btn--primary" href="feedback_detail.php?id='.rawurlencode($r['FeedbackID']).'">View</a>
              <button class="btn btn--ghost btn-del" data-id="'.htmlspecialchars($r['FeedbackID']).'">Delete</button>
            </td>';
    echo '</tr>';
  }
  exit;
}

/* ===================== DATA FOR FILTERS ===================== */
// Users for dropdown
$users = [];
$us = $conn->prepare("SELECT AccountID, AName FROM accounts ORDER BY AName ASC");
$us->execute();
$ur = $us->get_result();
while ($row = $ur->fetch_assoc()) { $users[] = $row; }

// Distinct ratings (if enabled)
$ratings = [];
if ($hasRating) {
  try {
    $qr = $conn->query("SELECT DISTINCT Rating FROM feedback WHERE Rating IS NOT NULL AND Rating BETWEEN 1 AND 5 ORDER BY Rating DESC");
    while ($row = $qr->fetch_assoc()) $ratings[] = (int)$row['Rating'];
  } catch (\Throwable $e) { $ratings = [5,4,3,2,1]; }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Feedback Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  <link rel="stylesheet" href="style_index.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
</head>
<body>
  <div class="container-wide">
    <h1 class="page-title">Feedback Management</h1>

    <!-- Controls -->
    <div class="control-row">
      <!-- Search -->
      <div class="control-wrap">
        <i class="fa-solid fa-search search-icon"></i>
        <input id="search" class="input input--search" placeholder="Search ID / content / user">
      </div>

      <!-- Filter by User -->
      <select id="filter_user" class="select">
        <option value="">-- Filter by User --</option>
        <?php foreach ($users as $u): ?>
          <option value="<?= htmlspecialchars($u['AccountID']) ?>"><?= htmlspecialchars($u['AName']) ?></option>
        <?php endforeach; ?>
      </select>

      <!-- Filter by Rating (if available) -->
      <?php if ($hasRating): ?>
        <select id="filter_rating" class="select">
          <option value="">-- Filter by Rating --</option>
          <?php foreach ($ratings ?: [5,4,3,2,1] as $rt): ?>
            <option value="<?= $rt ?>"><?= str_repeat('★', $rt).str_repeat('☆', 5-$rt) ?> (<?= $rt ?>)</option>
          <?php endforeach; ?>
        </select>
      <?php endif; ?>

      <!-- Filter by Status -->
      <select id="filter_status" class="select">
        <option value="">-- Filter by Status --</option>
        <option value="Processed">Processed</option>
        <option value="Waiting">Waiting</option>
      </select>

      <div style="margin-left:auto; display:flex; gap:.5rem;">
        <a href="dashboard.php" class="btn btn--ghost"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
      </div>
    </div>

    <!-- Table -->
    <div class="card table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>FeedbackID</th>
            <th>User</th>
            <th>Rate</th>
            <th>Content</th>
            <th>Response date</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="fb_body">
          <!-- via AJAX -->
        </tbody>
      </table>
    </div>
  </div>

  <script>
    $(function(){
      const ajaxUrl = location.pathname; // gọi chính file này
      let timer;

      function loadFeedback(){
        const search = $('#search').val();
        const user   = $('#filter_user').val();
        const status = $('#filter_status').val();
        const rating = $('#filter_rating').length ? $('#filter_rating').val() : '';

        $.get(ajaxUrl, { ajax: 1, search, user, status, rating })
          .done(html => $('#fb_body').html(html))
          .fail(()=> $('#fb_body').html('<tr><td colspan="7" class="text-center">Load failed.</td></tr>'));
      }

      // delete
      $('#fb_body').on('click', '.btn-del', function(){
        const id = $(this).data('id');
        if (!id) return;
        if (!confirm('Are you sure to delete this feedback?')) return;

        $.post(ajaxUrl, { ajax: 'delete', id })
          .done(res=>{
            try { const data = JSON.parse(res); if (data.ok) loadFeedback(); else alert(data.msg || 'Delete failed'); }
            catch{ loadFeedback(); }
          })
          .fail(()=> alert('Delete failed'));
      });

      // events
      loadFeedback();
      $('#filter_user,#filter_status').on('change', loadFeedback);
      $('#filter_rating').on('change', loadFeedback);
      $('#search').on('input', function(){ clearTimeout(timer); timer=setTimeout(loadFeedback, 300); });
    });
  </script>
</body>
</html>
