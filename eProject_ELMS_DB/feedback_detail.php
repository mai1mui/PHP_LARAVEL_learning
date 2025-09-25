<?php
// feedback_detail.php
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

/* ===================== Helpers ===================== */
function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

/* Kiểm tra cột Rating có tồn tại không (schema của bạn không bắt buộc có) */
$hasRating = false;
try {
  $chk = $conn->prepare("SELECT 1 FROM information_schema.columns WHERE table_schema=? AND table_name='feedback' AND column_name='Rating' LIMIT 1");
  $chk->bind_param("s", $dbname);
  $chk->execute();
  $hasRating = (bool)$chk->get_result()->fetch_row();
} catch (\Throwable $e) {
  $hasRating = false;
}

/* ===================== Lấy ID feedback ===================== */
$fid = trim($_GET['id'] ?? '');
if ($fid === '') { http_response_code(400); die("Missing id"); }

/* ===================== Xử lý POST (reply / delete) ===================== */
$flash_success = $flash_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  if ($action === 'reply') {
    $reply = trim($_POST['reply'] ?? '');
    if ($reply === '') {
      $flash_error = "Reply content cannot be empty.";
    } else {
      // Cập nhật trạng thái -> Processed
      $up = $conn->prepare("UPDATE feedback SET FStatus='Processed' WHERE FeedbackID=?");
      $up->bind_param("s", $fid);
      $up->execute();

      // Ghi log reply vào activity_logs (nếu có bảng này — script DB của bạn có)
      try {
        $meta = json_encode(['feedback_id'=>$fid, 'reply'=>$reply], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $ins = $conn->prepare("INSERT INTO activity_logs (AccountID, action, meta, ip, agent) VALUES (?,?,?,?,?)");
        $ip    = $_SERVER['REMOTE_ADDR']    ?? null;
        $agent = $_SERVER['HTTP_USER_AGENT']?? null;
        $actionName = 'feedback.reply';
        $ins->bind_param("sssss", $_SESSION["AccountID"], $actionName, $meta, $ip, $agent);
        $ins->execute();
      } catch (\Throwable $e) {
        // nếu table không có, bỏ qua
      }

      $flash_success = "Reply sent and feedback marked as Processed.";
      // Bạn có thể redirect nếu muốn:
      // header("Location: feedback_detail.php?id=".$fid."&ok=1"); exit;
    }
  } elseif ($action === 'delete') {
    // Xoá feedback
    $del = $conn->prepare("DELETE FROM feedback WHERE FeedbackID=?");
    $del->bind_param("s", $fid);
    $del->execute();
    header("Location: feedback.php?deleted={$fid}");
    exit;
  }
}

/* ===================== Lấy chi tiết feedback ===================== */
$sel = $conn->prepare(
  "SELECT f.FeedbackID, f.AccountID, f.Content, f.CreatedAt, f.FStatus".
  ($hasRating ? ", f.Rating " : " ").
  " , a.AName
   FROM feedback f
   LEFT JOIN accounts a ON a.AccountID = f.AccountID
   WHERE f.FeedbackID = ?"
);
$sel->bind_param("s", $fid);
$sel->execute();
$fb = $sel->get_result()->fetch_assoc();
if (!$fb) { http_response_code(404); die("Feedback not found"); }

$conn->close();

/* ===================== Chuẩn hoá hiển thị ===================== */
$created = $fb['CreatedAt'] ? date('d/m/Y', strtotime($fb['CreatedAt'])) : '';
$starsHtml = 'N/A';
if ($hasRating) {
  $n = max(0, min(5, (int)($fb['Rating'] ?? 0)));
  $starsHtml = '';
  for ($i=1; $i<=5; $i++) {
    $starsHtml .= '<span class="star'.($i <= $n ? ' star--on' : '').'">★</span>';
  }
}
$statusBadge = (strcasecmp($fb['FStatus'],'Processed')===0)
  ? '<span class="badge badge--green">Processed</span>'
  : '<span class="badge badge--yellow">Waiting</span>';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Feedback Detail</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <!-- CSS tách riêng cho trang detail -->
  <link rel="stylesheet" href="feedback_detail.css">
</head>
<body class="fd-body">
  <div class="card">
    <div class="card__header">
      <h2 class="title">Feedback details</h2>
      <a href="feedback.php" class="btn btn--ghost">
        ← Back
      </a>
    </div>

    <?php if (!empty($flash_success)): ?>
      <div class="alert alert--success"><?= h($flash_success) ?></div>
    <?php endif; ?>
    <?php if (!empty($flash_error)): ?>
      <div class="alert alert--error"><?= h($flash_error) ?></div>
    <?php endif; ?>

    <div class="grid">
      <div class="field">
        <div class="label">FeedbackID</div>
        <div class="value"><?= h($fb['FeedbackID']) ?></div>
      </div>

      <div class="field">
        <div class="label">User</div>
        <div class="value"><?= h($fb['AName'] ?: $fb['AccountID']) ?></div>
      </div>

      <div class="field">
        <div class="label">Rate</div>
        <div class="value stars"><?= $starsHtml ?></div>
      </div>

      <div class="field field--full">
        <div class="label">Content</div>
        <div class="bubble"><?= nl2br(h($fb['Content'])) ?></div>
      </div>

      <div class="field">
        <div class="label">Response date</div>
        <div class="value"><?= h($created) ?></div>
      </div>

      <div class="field">
        <div class="label">Status</div>
        <div class="value"><?= $statusBadge ?></div>
      </div>
    </div>

    <form method="post" class="reply">
      <input type="hidden" name="action" value="reply">
      <label class="label" for="reply">Reply to feedback</label>
      <textarea id="reply" name="reply" rows="5" placeholder="Enter your answer here..." class="textarea"></textarea>
      <div class="actions">
        <button type="submit" class="btn btn--primary">Send reply</button>
        <button type="submit" name="action" value="delete" class="btn btn--danger"
                onclick="return confirm('Delete this feedback?');">Delete feedback</button>
      </div>
    </form>
  </div>
</body>
</html>
