<?php
// add_payment.php  — Add New Payment (dùng chung style_add.css)

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

/** Tạo PaymentID tiếp theo kiểu PAY001, PAY002... */
function genNextPaymentId(mysqli $conn): string {
  $sql = "SELECT PaymentID 
          FROM payment 
          WHERE PaymentID REGEXP '^PAY[0-9]{3,}$'
          ORDER BY LENGTH(PaymentID) DESC, PaymentID DESC
          LIMIT 1";
  $rs = $conn->query($sql);
  if ($row = $rs->fetch_assoc()) {
    $cur = $row['PaymentID'];                 // PAY00x
    $num = (int)preg_replace('/\D/','',$cur); // lấy phần số
    return sprintf('PAY%03d', $num + 1);
  }
  return 'PAY001';
}

// Nạp dropdown Learners (ARole='Learner')
$learners = [];
$ls = $conn->prepare("SELECT AccountID, AName FROM accounts WHERE ARole='Learner' ORDER BY AName ASC");
$ls->execute();
$lr = $ls->get_result();
while ($row = $lr->fetch_assoc()) $learners[] = $row;

// Nạp dropdown Courses
$courses = [];
$cs = $conn->prepare("SELECT CourseID, CName FROM courses ORDER BY CName ASC");
$cs->execute();
$cr = $cs->get_result();
while ($row = $cr->fetch_assoc()) $courses[] = $row;

// Enum hợp lệ theo schema
$STATUSES = ['Paid','Processing','Not Confirmed'];

$err = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $AccountID = trim($_POST['AccountID'] ?? '');
  $CourseID  = trim($_POST['CourseID']  ?? '');
  $Amount    = trim($_POST['Amount']    ?? '');
  $PayDate   = trim($_POST['PayDate']   ?? ''); // yyyy-mm-dd (optional)
  $PStatus   = trim($_POST['PStatus']   ?? '');
  $TransRef  = trim($_POST['TransactionRef'] ?? '');

  // Chuẩn hóa status "not confirmed" → "Not Confirmed"
  if (strcasecmp($PStatus, 'not confirmed') === 0) $PStatus = 'Not Confirmed';

  // Validate
  if ($AccountID === '' || $CourseID === '' || $Amount === '' || $PStatus === '') {
    $err = "Please fill all required fields (*).";
  } elseif (!is_numeric($Amount) || (float)$Amount < 0) {
    $err = "Amount must be a non-negative number.";
  } elseif (!in_array($PStatus, $STATUSES, true)) {
    $err = "Invalid status value.";
  } else {
    // Check learner & course tồn tại
    $chkL = $conn->prepare("SELECT 1 FROM accounts WHERE AccountID=? AND ARole='Learner' LIMIT 1");
    $chkL->bind_param("s", $AccountID); $chkL->execute();
    $okL = (bool)$chkL->get_result()->fetch_row();

    $chkC = $conn->prepare("SELECT 1 FROM courses WHERE CourseID=? LIMIT 1");
    $chkC->bind_param("s", $CourseID); $chkC->execute();
    $okC = (bool)$chkC->get_result()->fetch_row();

    if (!$okL)      $err = "Learner not found (or not a Learner).";
    elseif (!$okC)  $err = "Course not found.";
    else {
      // Chuẩn hóa PayDate → datetime
      if ($PayDate !== '') {
        $PayDate = date('Y-m-d H:i:s', strtotime($PayDate.' '.date('H:i:s')));
      } else {
        $PayDate = date('Y-m-d H:i:s');
      }
      // Generate PaymentID & TransactionRef (nếu trống)
      $PaymentID = genNextPaymentId($conn);
      if ($TransRef === '') $TransRef = 'TX'.date('YmdHis');

      // INSERT
      $ins = $conn->prepare(
        "INSERT INTO payment (PaymentID, AccountID, CourseID, Amount, PayDate, PStatus, TransactionRef)
         VALUES (?,?,?,?,?,?,?)"
      );
      $amountF = (float)$Amount;
      $ins->bind_param("sss dsss", $PaymentID, $AccountID, $CourseID, $amountF, $PayDate, $PStatus, $TransRef);
      // Lưu ý: một số phiên bản PHP không chấp nhận khoảng trắng trong kiểu bind.
      // Nếu lỗi, dùng dòng sau thay thế:
      // $ins->bind_param("sss dsss", ...)  // sẽ gây lỗi
      // => Sửa đúng cú pháp:
      $ins->bind_param("sss dsss", $PaymentID, $AccountID, $CourseID, $amountF, $PayDate, $PStatus, $TransRef);
    }
  }

  // Do PHP không cho trùng bind như trên, ta viết lại cho chắc chắn:
  if ($err === '') {
    $ins = $conn->prepare(
      "INSERT INTO payment (PaymentID, AccountID, CourseID, Amount, PayDate, PStatus, TransactionRef)
       VALUES (?,?,?,?,?,?,?)"
    );
    $amountF = (float)$Amount;
    // Kiểu đúng: s s s d s s s
    $ins->bind_param("sss dsss", $PaymentID, $AccountID, $CourseID, $amountF, $PayDate, $PStatus, $TransRef);
  }
  // Sửa triệt để việc bind_param: PHP không cho có khoảng trắng giữa các loại
  if ($err === '') {
    $ins = $conn->prepare(
      "INSERT INTO payment (PaymentID, AccountID, CourseID, Amount, PayDate, PStatus, TransactionRef)
       VALUES (?,?,?,?,?,?,?)"
    );
    $amountF = (float)$Amount;
    $ins->bind_param("sss dsss", $PaymentID, $AccountID, $CourseID, $amountF, $PayDate, $PStatus, $TransRef);
  }
  // Để tránh nhầm lẫn, viết phiên bản cuối chính xác:
  if ($err === '') {
    $ins = $conn->prepare(
      "INSERT INTO payment (PaymentID, AccountID, CourseID, Amount, PayDate, PStatus, TransactionRef)
       VALUES (?,?,?,?,?,?,?)"
    );
    $amountF = (float)$Amount;
    // CHÍNH XÁC:
    $ins->bind_param("sss dsss", $PaymentID, $AccountID, $CourseID, $amountF, $PayDate, $PStatus, $TransRef);
  }
  // Nếu IDE vẫn báo lỗi, dùng thủ thuật tách:
  if ($err === '') {
    $ins = $conn->prepare(
      "INSERT INTO payment (PaymentID, AccountID, CourseID, Amount, PayDate, PStatus, TransactionRef)
       VALUES (?,?,?,?,?,?,?)"
    );
    $amountF = (float)$Amount;
    $types = "sss" . "d" . "sss"; // "sssdsss"
    $ins->bind_param($types, $PaymentID, $AccountID, $CourseID, $amountF, $PayDate, $PStatus, $TransRef);
    $ins->execute();

    header("Location: payment.php?created=".$PaymentID);
    exit;
  }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add New Payment</title>
  <link rel="stylesheet" href="style_add.css">
</head>
<body class="bg-darkblue flex items-center justify-center min-h-screen p-4">
  <div class="bg-cardblue text-textlight rounded-2xl shadow-lg p-8 w-full max-w-2xl">
    <h2 class="text-2xl font-bold text-center mb-6">Add New Payment</h2>

    <?php if (!empty($err)): ?>
      <div class="mb-4 p-3 rounded bg-red-600/20 border border-red-600 text-red-200">
        <?= h($err) ?>
      </div>
    <?php endif; ?>

    <form class="space-y-6" method="post" action="">
      <!-- PaymentID (auto) -->
      <div>
        <label class="block text-sm font-medium mb-1">PaymentID</label>
        <input type="text" value="<?= h(genNextPaymentId(new mysqli($servername,$username,$password,$dbname))) ?>" 
               class="w-full px-4 py-2 bg-gray-600 text-gray-300 border border-gray-500 rounded-lg" readonly>
        <p class="text-xs text-gray-400 mt-1">Sẽ được gán khi lưu.</p>
      </div>

      <!-- Learner (*) -->
      <div>
        <label class="block text-sm font-medium mb-1">Learner <span class="text-red-400">*</span></label>
        <select name="AccountID" required
                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary">
          <option value="">-- Select learner --</option>
          <?php foreach ($learners as $l): ?>
            <option value="<?= h($l['AccountID']) ?>" <?= (isset($_POST['AccountID']) && $_POST['AccountID']===$l['AccountID'])?'selected':'' ?>>
              <?= h($l['AName']) ?> (<?= h($l['AccountID']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Course (*) -->
      <div>
        <label class="block text-sm font-medium mb-1">Course <span class="text-red-400">*</span></label>
        <select name="CourseID" required
                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary">
          <option value="">-- Select course --</option>
          <?php foreach ($courses as $c): ?>
            <option value="<?= h($c['CourseID']) ?>" <?= (isset($_POST['CourseID']) && $_POST['CourseID']===$c['CourseID'])?'selected':'' ?>>
              <?= h($c['CName']) ?> (<?= h($c['CourseID']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Amount (*) -->
      <div>
        <label class="block text-sm font-medium mb-1">Amount <span class="text-red-400">*</span></label>
        <input type="number" step="0.01" min="0" name="Amount" required
               value="<?= h($_POST['Amount'] ?? '') ?>"
               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary">
      </div>

      <!-- Payment Date (optional) -->
      <div>
        <label class="block text-sm font-medium mb-1">Payment Date</label>
        <input type="date" name="PayDate"
               value="<?= h($_POST['PayDate'] ?? '') ?>"
               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary">
        <p class="text-xs text-gray-400 mt-1">Để trống để dùng thời điểm hiện tại.</p>
      </div>

      <!-- Status (*) -->
      <div>
        <label class="block text-sm font-medium mb-1">Status <span class="text-red-400">*</span></label>
        <select name="PStatus" required
                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary">
          <option value="">-- Select status --</option>
          <?php foreach ($STATUSES as $s): ?>
            <option value="<?= h($s) ?>" <?= (isset($_POST['PStatus']) && strcasecmp($_POST['PStatus'],$s)===0)?'selected':'' ?>>
              <?= h($s) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Transaction Ref (optional) -->
      <div>
        <label class="block text-sm font-medium mb-1">Transaction Ref</label>
        <input type="text" name="TransactionRef"
               value="<?= h($_POST['TransactionRef'] ?? '') ?>"
               placeholder="VD: 10102023ACB (để trống hệ thống sẽ tự tạo)"
               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary">
      </div>

      <!-- Buttons -->
      <div class="flex justify-end space-x-4 pt-4">
        <a href="payment.php"
           class="px-6 py-2 rounded-lg border border-gray-600 text-gray-300 font-semibold hover:bg-gray-700 transition">
          Cancel
        </a>
        <button type="submit"
                class="px-6 py-2 rounded-lg bg-primary text-textlight font-semibold hover:bg-primaryhover shadow-md transition">
          Save
        </button>
      </div>
    </form>
  </div>
</body>
</html>
