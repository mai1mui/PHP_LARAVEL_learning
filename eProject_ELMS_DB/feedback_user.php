<?php
// feedback_user.php
session_start();

// Yêu cầu đăng nhập (mọi vai trò đều có thể gửi phản hồi)
if (!isset($_SESSION["AccountID"])) {
  header("Location: login.php");
  exit;
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

/** Sinh FeedbackID dạng FE001, FE002... */
function genNextFeedbackId(mysqli $conn): string {
  $sql = "SELECT FeedbackID FROM feedback
          WHERE FeedbackID REGEXP '^FE[0-9]{3,}$'
          ORDER BY LENGTH(FeedbackID) DESC, FeedbackID DESC
          LIMIT 1";
  $rs = $conn->query($sql);
  if ($row = $rs->fetch_assoc()) {
    $cur = $row['FeedbackID'];
    $num = (int)preg_replace('/\D/', '', $cur);
    return sprintf('FE%03d', $num + 1);
  }
  return 'FE001';
}

/* Kiểm tra cột Rating có tồn tại không (schema gốc không bắt buộc có) */
$hasRating = false;
try {
  $chk = $conn->prepare("SELECT 1 FROM information_schema.columns
                         WHERE table_schema=? AND table_name='feedback' AND column_name='Rating' LIMIT 1");
  $chk->bind_param("s", $dbname);
  $chk->execute();
  $hasRating = (bool)$chk->get_result()->fetch_row();
} catch (\Throwable $e) {
  $hasRating = false;
}

/* ===================== Nạp danh sách khoá học ===================== */
$courses = [];
$cs = $conn->prepare("SELECT CourseID, CName FROM courses ORDER BY CName ASC");
$cs->execute();
$cr = $cs->get_result();
while ($row = $cr->fetch_assoc()) { $courses[] = $row; }

/* ===================== Xử lý gửi form ===================== */
$success = $error = '';
$posted = ['course'=>'', 'rating'=>'0', 'feedback'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $courseId = trim($_POST['course']   ?? '');
  $rating   = (int)($_POST['rating']  ?? 0);
  $content  = trim($_POST['feedback'] ?? '');

  // Lưu lại để hiển thị lại form nếu lỗi
  $posted = ['course'=>$courseId, 'rating'=>(string)$rating, 'feedback'=>$content];

  // Validate
  if ($courseId === '' || $content === '') {
    $error = "Please select a course and enter your feedback.";
  } else {
    // Course tồn tại?
    $chkC = $conn->prepare("SELECT 1 FROM courses WHERE CourseID=? LIMIT 1");
    $chkC->bind_param("s", $courseId);
    $chkC->execute();
    $okC = (bool)$chkC->get_result()->fetch_row();

    if (!$okC) {
      $error = "Selected course not found.";
    } else {
      if ($hasRating) {
        if ($rating < 1 || $rating > 5) {
          $error = "Please select a rating from 1 to 5 stars.";
        }
      } else {
        // nếu không có cột rating, bỏ qua giá trị rating người dùng chọn
        $rating = 0;
      }

      if ($error === '') {
        $fid = genNextFeedbackId($conn);
        $accountId = $_SESSION["AccountID"];

        // Vì bảng feedback không có CourseID trong schema gốc, ta gắn CourseID vào nội dung để bảo lưu ngữ cảnh.
        $contentToSave = "[Course: {$courseId}] ".$content;

        if ($hasRating) {
          // Cột Rating tồn tại
          $ins = $conn->prepare(
            "INSERT INTO feedback (FeedbackID, AccountID, Content, FStatus, CreatedAt, Rating)
             VALUES (?,?,?,?,NOW(),?)"
          );
          $status = 'Waiting';
          $ins->bind_param("ssssi", $fid, $accountId, $contentToSave, $status, $rating);
        } else {
          // Không có cột Rating
          $ins = $conn->prepare(
            "INSERT INTO feedback (FeedbackID, AccountID, Content, FStatus, CreatedAt)
             VALUES (?,?,?,?,NOW())"
          );
          $status = 'Waiting';
          $ins->bind_param("ssss", $fid, $accountId, $contentToSave, $status);
        }
        $ins->execute();

        // Ghi activity log (nếu có bảng)
        try {
          $meta = json_encode(['feedback_id'=>$fid, 'course_id'=>$courseId, 'rating'=>$rating], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
          $alog = $conn->prepare("INSERT INTO activity_logs (AccountID, action, meta, ip, agent) VALUES (?,?,?,?,?)");
          $ip    = $_SERVER['REMOTE_ADDR']     ?? null;
          $agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
          $actionName = 'feedback.create';
          $alog->bind_param("sssss", $accountId, $actionName, $meta, $ip, $agent);
          $alog->execute();
        } catch (\Throwable $e) {
          // ignore nếu bảng không tồn tại
        }

        $success = "Thank you! Your feedback has been submitted.";
        // reset form
        $posted = ['course'=>'', 'rating'=>'0', 'feedback'=>''];
      }
    }
  }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Feedback & Reports</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <!-- Dùng lại style_add.css (form add) cho giao diện form gọn gàng -->
  <link rel="stylesheet" href="feedback_user.css">
  <style>
    /* Tăng thêm style nhỏ cho sao đánh giá */
    .stars { display:flex; gap:8px; justify-content:center; margin-top:6px; user-select:none; }
    .star {
      cursor:pointer; font-size:22px; line-height:1; color:#4b5563; transition:color .2s ease-in-out;
    }
    .star.selected { color:#f59e0b; }
  </style>
</head>
<body class="add-body">
  <div class="card" style="max-width:720px;">
    <h2 class="title">Feedback & Reports</h2>

    <?php if ($success): ?>
      <div class="alert alert--success"><?= h($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert--error"><?= h($error) ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <!-- Course -->
      <div class="form-group">
        <label class="form-label" for="course">Course <span class="req">*</span></label>
        <select id="course" name="course" class="form-control" required>
          <option value="">-- Select course --</option>
          <?php foreach ($courses as $c): ?>
            <option value="<?= h($c['CourseID']) ?>" <?= ($posted['course']===$c['CourseID']?'selected':'') ?>>
              <?= h($c['CName']) ?> (<?= h($c['CourseID']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Rating -->
      <div class="form-group">
        <label class="form-label">Rating<?= $hasRating ? '': ' (optional)' ?></label>
        <input type="hidden" id="rating" name="rating" value="<?= h($posted['rating']) ?>">
        <div class="stars" id="rating-star-group" aria-label="rating">
          <span class="star" data-value="1">★</span>
          <span class="star" data-value="2">★</span>
          <span class="star" data-value="3">★</span>
          <span class="star" data-value="4">★</span>
          <span class="star" data-value="5">★</span>
        </div>
        <div class="hint" style="text-align:center;margin-top:6px;">Click to rate from 1–5 stars.</div>
      </div>

      <!-- Feedback -->
      <div class="form-group">
        <label class="form-label" for="feedback">Feedback <span class="req">*</span></label>
        <textarea id="feedback" name="feedback" rows="5" class="form-control" placeholder="Write your feedback here..." required><?= h($posted['feedback']) ?></textarea>
      </div>

      <div class="actions">
        <a class="btn btn--ghost" href="dashboard.php">Cancel</a>
        <button type="submit" class="btn btn--primary">Send feedback</button>
      </div>
    </form>
  </div>

  <script>
    (function(){
      const stars = document.querySelectorAll('#rating-star-group .star');
      const ratingInput = document.getElementById('rating');
      const init = parseInt(ratingInput.value || '0', 10);

      function paint(n){
        stars.forEach(st => {
          const v = parseInt(st.getAttribute('data-value'), 10);
          if (v <= n) st.classList.add('selected'); else st.classList.remove('selected');
        });
      }
      paint(isNaN(init)?0:init);

      stars.forEach(st => {
        st.addEventListener('click', () => {
          const n = parseInt(st.getAttribute('data-value'), 10);
          ratingInput.value = n;
          paint(n);
        });
        st.addEventListener('mouseover', () => {
          const n = parseInt(st.getAttribute('data-value'), 10);
          paint(n);
        });
        st.addEventListener('mouseout', () => {
          const n = parseInt(ratingInput.value || '0', 10);
          paint(isNaN(n)?0:n);
        });
      });
    })();
  </script>
</body>
</html>
