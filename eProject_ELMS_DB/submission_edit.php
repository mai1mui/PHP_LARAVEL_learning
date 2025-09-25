<?php
// edit_submission.php — Edit Submission (uses shared style_edit.css)

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

$subId = trim($_GET['id'] ?? '');
if ($subId === '') { http_response_code(400); die("Missing id"); }

// Dropdown: Learners
$learners = [];
$ls = $conn->prepare("SELECT AccountID, AName FROM accounts WHERE ARole='Learner' ORDER BY AName ASC");
$ls->execute();
$lres = $ls->get_result();
while ($row = $lres->fetch_assoc()) $learners[] = $row;

// Dropdown: Courses
$courses = [];
$cs = $conn->prepare("SELECT CourseID, CName FROM courses ORDER BY CName ASC");
$cs->execute();
$cres = $cs->get_result();
while ($row = $cres->fetch_assoc()) $courses[] = $row;

// Status options (per schema)
$statuses = ['Submitted','Late','Not Submit'];

$success = $error = "";

/** Load current record */
$curStmt = $conn->prepare("SELECT SubID, AccountID, CourseID, Answer, Mark, Feedback, SDate, SStatus
                           FROM submissions WHERE SubID=?");
$curStmt->bind_param("s", $subId);
$curStmt->execute();
$current = $curStmt->get_result()->fetch_assoc();
if (!$current) { http_response_code(404); die("Submission not found"); }

// date-only for input[type=date]
$curDate = $current['SDate'] ? date('Y-m-d', strtotime($current['SDate'])) : '';

/** Handle POST (Update) */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $AccountID = trim($_POST['AccountID'] ?? '');
  $CourseID  = trim($_POST['CourseID']  ?? '');
  $Answer    = trim($_POST['Answer']    ?? '');
  $Mark      = trim($_POST['Mark']      ?? '');
  $Feedback  = trim($_POST['Feedback']  ?? '');
  $dateStr   = trim($_POST['SDate']     ?? ''); // yyyy-mm-dd
  $SStatus   = trim($_POST['SStatus']   ?? '');

  // Normalize status
  if (strcasecmp($SStatus, 'not submit') === 0 || strcasecmp($SStatus, 'not submit ') === 0) {
    $SStatus = 'Not Submit';
  }

  // Validate
  if ($AccountID === '' || $CourseID === '' || $SStatus === '') {
    $error = "Please fill all required fields (*).";
  } elseif (!in_array($SStatus, $statuses, true)) {
    $error = "Invalid status value.";
  } elseif ($Mark !== '' && !is_numeric($Mark)) {
    $error = "Mark must be a number.";
  } else {
    // Check learner exists & role
    $chk1 = $conn->prepare("SELECT 1 FROM accounts WHERE AccountID=? AND ARole='Learner' LIMIT 1");
    $chk1->bind_param("s", $AccountID);
    $chk1->execute();
    $okL = (bool)$chk1->get_result()->fetch_row();

    // Check course exists
    $chk2 = $conn->prepare("SELECT 1 FROM courses WHERE CourseID=? LIMIT 1");
    $chk2->bind_param("s", $CourseID);
    $chk2->execute();
    $okC = (bool)$chk2->get_result()->fetch_row();

    if (!$okL)       $error = "Learner not found (or not a Learner).";
    elseif (!$okC)   $error = "Course not found.";
    else {
      // Compose datetime for SDate
      if ($dateStr !== '') {
        $existingTime = $current['SDate'] ? date('H:i:s', strtotime($current['SDate'])) : date('H:i:s');
        $SDate = date('Y-m-d H:i:s', strtotime($dateStr . ' ' . $existingTime));
      } else {
        $SDate = $current['SDate']; // keep old
      }

      // Mark may be NULL
      $markVal = ($Mark === '') ? null : (float)$Mark;

      $upd = $conn->prepare("
        UPDATE submissions
           SET AccountID=?, CourseID=?, Answer=?, Mark=?, Feedback=?, SDate=?, SStatus=?
         WHERE SubID=?");
      // use "d" for double when not null, but since we might pass NULL, bind as "s" and cast via set_null if needed
      // Simplest: use "sd" with conditional; mysqli doesn't support nullable numeric binding directly, so pass null via set to null and use "d" when not null; fallback to "s" and let MySQL cast.
      if ($markVal === null) {
        $markParam = null;
        $upd->bind_param("ssssssss",
          $AccountID, $CourseID, $Answer, $markParam, $Feedback, $SDate, $SStatus, $subId
        );
      } else {
        // Still bind as string safely; MySQL will coerce to numeric
        $markParam = (string)$markVal;
        $upd->bind_param("ssssssss",
          $AccountID, $CourseID, $Answer, $markParam, $Feedback, $SDate, $SStatus, $subId
        );
      }
      $upd->execute();

      $success = "Submission updated successfully.";

      // refresh current
      $current['AccountID'] = $AccountID;
      $current['CourseID']  = $CourseID;
      $current['Answer']    = $Answer;
      $current['Mark']      = ($markVal === null ? null : $markVal);
      $current['Feedback']  = $Feedback;
      $current['SDate']     = $SDate;
      $current['SStatus']   = $SStatus;
      $curDate = $SDate ? date('Y-m-d', strtotime($SDate)) : '';
    }
  }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Submission</title>

  <!-- Tailwind config BEFORE CDN -->
  <script>
    tailwind = {
      theme: {
        extend: {
          colors: {
            darkblue: '#111827',
            cardblue: '#1f2937',
            primary: '#3b82f6',
            primaryhover: '#2563eb',
            textlight: '#f9fafb',
          }
        }
      }
    }
  </script>
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Shared edit form styles -->
  <link rel="stylesheet" href="style_edit.css">
</head>
<body class="bg-darkblue text-gray-200 flex items-center justify-center min-h-screen p-4">
  <div class="bg-cardblue rounded-xl shadow-2xl p-8 w-full max-w-2xl">
    <h2 class="text-2xl font-bold mb-6 text-center">Edit Submission</h2>

    <?php if (!empty($success)): ?>
      <div class="mb-4 p-3 rounded bg-green-600/20 border border-green-600 text-green-200">
        <?= h($success) ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="mb-4 p-3 rounded bg-red-600/20 border border-red-600 text-red-200">
        <?= h($error) ?>
      </div>
    <?php endif; ?>

    <form class="space-y-6" method="post" action="">
      <!-- SubID (readonly) -->
      <div>
        <label class="block text-sm font-medium mb-1">SubmissionID</label>
        <input type="text" value="<?= h($current['SubID']) ?>" readonly
               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-400 cursor-not-allowed">
      </div>

      <!-- Learner (*) -->
      <div>
        <label class="block text-sm font-medium mb-1">Learner <span class="text-red-400">*</span></label>
        <select name="AccountID" required
                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
          <option value="">-- Select learner --</option>
          <?php foreach ($learners as $l): ?>
            <option value="<?= h($l['AccountID']) ?>"
              <?= ($l['AccountID'] === $current['AccountID']) ? 'selected' : '' ?>>
              <?= h($l['AName']) ?> (<?= h($l['AccountID']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Course (*) -->
      <div>
        <label class="block text-sm font-medium mb-1">Course <span class="text-red-400">*</span></label>
        <select name="CourseID" required
                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
          <option value="">-- Select course --</option>
          <?php foreach ($courses as $c): ?>
            <option value="<?= h($c['CourseID']) ?>"
              <?= ($c['CourseID'] === $current['CourseID']) ? 'selected' : '' ?>>
              <?= h($c['CName']) ?> (<?= h($c['CourseID']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Answer -->
      <div>
        <label class="block text-sm font-medium mb-1">Answer</label>
        <input type="text" name="Answer" value="<?= h($current['Answer']) ?>"
               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
               placeholder="e.g., answer.pdf or text/url">
      </div>

      <!-- Mark (optional) -->
      <div>
        <label class="block text-sm font-medium mb-1">Mark</label>
        <input type="number" step="0.01" name="Mark"
               value="<?= h($current['Mark'] === null ? '' : rtrim(rtrim((string)$current['Mark'],'0'),'.')) ?>"
               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
               placeholder="Leave empty for NULL">
      </div>

      <!-- Feedback -->
      <div>
        <label class="block text-sm font-medium mb-1">Feedback</label>
        <input type="text" name="Feedback" value="<?= h($current['Feedback']) ?>"
               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
      </div>

      <!-- Date Submitted -->
      <div>
        <label class="block text-sm font-medium mb-1">Date Submitted</label>
        <input type="date" name="SDate" value="<?= h($curDate) ?>"
               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
        <p class="text-xs text-gray-400 mt-1">Leave blank to keep the previous datetime.</p>
      </div>

      <!-- Status (*) -->
      <div>
        <label class="block text-sm font-medium mb-1">Status <span class="text-red-400">*</span></label>
        <select name="SStatus" required
                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
          <?php foreach ($statuses as $s): ?>
            <option value="<?= h($s) ?>" <?= (strcasecmp($current['SStatus'],$s)===0 ? 'selected' : '') ?>><?= h($s) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end space-x-4 pt-2">
        <a href="submission.php"
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
