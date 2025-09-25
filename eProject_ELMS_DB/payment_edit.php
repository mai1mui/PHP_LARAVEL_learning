<?php
// payment_edit.php — Edit Payment (dùng chung style_edit.css)

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

function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

// Lấy id
$pid = trim($_GET['id'] ?? '');
if ($pid === '') { http_response_code(400); die("Missing id"); }

// Enum hợp lệ theo schema
$STATUSES = ['Paid','Processing','Not Confirmed'];

$success = $error = "";

/* ====== Tải bản ghi hiện tại ====== */
$cur = $conn->prepare(
  "SELECT p.PaymentID, p.AccountID, p.CourseID, p.Amount, p.PayDate, p.PStatus, p.TransactionRef,
          a.AName, c.CName
   FROM payment p
   LEFT JOIN accounts a ON a.AccountID=p.AccountID
   LEFT JOIN courses  c ON c.CourseID =p.CourseID
   WHERE p.PaymentID=?"
);
$cur->bind_param("s", $pid);
$cur->execute();
$payment = $cur->get_result()->fetch_assoc();
if (!$payment) { http_response_code(404); die("Payment not found"); }

// Chuẩn bị giá trị date (yyyy-mm-dd) để show input[type=date]
$curDate = $payment['PayDate'] ? date('Y-m-d', strtotime($payment['PayDate'])) : '';

/* ====== Cập nhật (POST) ====== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $Amount   = trim($_POST['Amount'] ?? '');
  $PayDate  = trim($_POST['PayDate'] ?? '');     // yyyy-mm-dd hoặc rỗng để giữ nguyên
  $PStatus  = trim($_POST['PStatus'] ?? '');
  $TransRef = trim($_POST['TransactionRef'] ?? '');

  // Chuẩn hóa “not confirmed”
  if (strcasecmp($PStatus, 'not confirmed') === 0) $PStatus = 'Not Confirmed';

  // Validate
  if ($Amount === '' || $PStatus === '') {
    $error = "Please fill all required fields (*).";
  } elseif (!is_numeric($Amount) || (float)$Amount < 0) {
    $error = "Amount must be a non-negative number.";
  } elseif (!in_array($PStatus, $STATUSES, true)) {
    $error = "Invalid status value.";
  } else {
    // Gộp ngày với phần giờ cũ (nếu có) — hoặc dùng giờ hiện tại
    if ($PayDate !== '') {
      $existingTime = $payment['PayDate'] ? date('H:i:s', strtotime($payment['PayDate'])) : date('H:i:s');
      $PayDateDT = date('Y-m-d H:i:s', strtotime($PayDate.' '.$existingTime));
    } else {
      // giữ nguyên
      $PayDateDT = $payment['PayDate'];
    }

    $upd = $conn->prepare(
      "UPDATE payment 
         SET Amount=?, PayDate=?, PStatus=?, TransactionRef=?
       WHERE PaymentID=?"
    );
    $amountF = (float)$Amount;
    $upd->bind_param("dssss", $amountF, $PayDateDT, $PStatus, $TransRef, $pid);
    $upd->execute();

    $success = "Payment updated successfully.";

    // refresh dữ liệu đang hiển thị
    $payment['Amount']        = $amountF;
    $payment['PayDate']       = $PayDateDT;
    $payment['PStatus']       = $PStatus;
    $payment['TransactionRef']= $TransRef;
    $curDate = $PayDateDT ? date('Y-m-d', strtotime($PayDateDT)) : '';
  }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Payment</title>
  <!-- Dùng stylesheet chung cho trang “edit” -->
  <link rel="stylesheet" href="style_edit.css">
</head>
<body class="bg-darkblue flex items-center justify-center min-h-screen p-4">
  <div class="bg-cardblue text-textlight rounded-xl shadow-2xl p-8 w-full max-w-2xl">
    <h2 class="text-2xl font-bold mb-6 text-center">Edit Payment</h2>

    <?php if (!empty($success)): ?>
      <div class="alert-success"><?= h($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="alert-error"><?= h($error) ?></div>
    <?php endif; ?>

    <form class="space-y-6" method="post" action="">
      <!-- Payment ID -->
      <div>
        <label class="form-label">PaymentID</label>
        <input type="text" value="<?= h($payment['PaymentID']) ?>" readonly
               class="input readonly">
      </div>

      <!-- Learner (readonly theo mẫu) -->
      <div>
        <label class="form-label">Learner</label>
        <input type="text"
               value="<?= h(($payment['AName'] ?: $payment['AccountID']).' ('.$payment['AccountID'].')') ?>"
               class="input readonly" readonly>
      </div>

      <!-- Course (readonly theo mẫu) -->
      <div>
        <label class="form-label">Course</label>
        <input type="text"
               value="<?= h(($payment['CName'] ?: $payment['CourseID']).' ('.$payment['CourseID'].')') ?>"
               class="input readonly" readonly>
      </div>

      <!-- Amount (*) -->
      <div>
        <label class="form-label">Amount <span class="req">*</span></label>
        <input type="number" step="0.01" min="0" name="Amount" required
               value="<?= h($payment['Amount']) ?>"
               class="input">
      </div>

      <!-- Payment Date -->
      <div>
        <label class="form-label">Payment Date</label>
        <input type="date" name="PayDate" value="<?= h($curDate) ?>" class="input">
        <p class="hint">Để trống để giữ nguyên thời điểm cũ.</p>
      </div>

      <!-- Status (*) -->
      <div>
        <label class="form-label">Status <span class="req">*</span></label>
        <select name="PStatus" required class="input">
          <?php foreach ($STATUSES as $s): ?>
            <option value="<?= h($s) ?>" <?= (strcasecmp($payment['PStatus'],$s)===0 ? 'selected' : '') ?>>
              <?= h($s) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Transaction Ref -->
      <div>
        <label class="form-label">Transaction Ref</label>
        <input type="text" name="TransactionRef"
               value="<?= h($payment['TransactionRef']) ?>" class="input">
      </div>

      <!-- Buttons -->
      <div class="actions-end">
        <a href="payment.php" class="btn-secondary">Cancel</a>
        <button type="submit" class="btn-primary">Save</button>
      </div>
    </form>
  </div>
</body>
</html>
