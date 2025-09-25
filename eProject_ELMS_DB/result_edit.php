<?php
// result_edit.php — Edit a Result (uses shared style_edit.css)

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

$resultId = isset($_GET['id']) ? trim($_GET['id']) : '';
if ($resultId === '' || !ctype_digit($resultId)) { http_response_code(400); die("Missing or invalid id"); }

$success = $error = "";

/* ========= Load dropdown data ========= */
// Learners
$learners = [];
$q1 = $conn->prepare("SELECT AccountID, AName FROM accounts WHERE ARole='Learner' ORDER BY AName ASC");
$q1->execute(); $r1 = $q1->get_result();
while ($row = $r1->fetch_assoc()) $learners[] = $row;

// Courses
$courses = [];
$q2 = $conn->prepare("SELECT CourseID, CName FROM courses ORDER BY CName ASC");
$q2->execute(); $r2 = $q2->get_result();
while ($row = $r2->fetch_assoc()) $courses[] = $row;

// Status enum (per schema)
$statuses = ['Passed', 'Failed', 'Pending'];

/* ========= Load current record ========= */
$stmt = $conn->prepare("SELECT ResultID, AccountID, CourseID, Content, Mark, RStatus FROM results WHERE ResultID=?");
$stmt->bind_param("i", $resultId);
$stmt->execute();
$current = $stmt->get_result()->fetch_assoc();
if (!$current) { http_response_code(404); die("Result not found"); }

/* ========= Handle POST (Update) ========= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $AccountID = trim($_POST['AccountID'] ?? '');
  $CourseID  = trim($_POST['CourseID'] ?? '');
  $Content   = trim($_POST['Content'] ?? '');
  $MarkStr   = trim($_POST['Mark'] ?? '');
  $RStatus   = trim($_POST['RStatus'] ?? '');

  // Normalize & validate
  if ($AccountID === '' || $CourseID === '' || $RStatus === '') {
    $error = "Please fill all required fields (*).";
  } elseif (!in_array($RStatus, $statuses, true)) {
    $error = "Invalid status value.";
  } else {
    // Validate learner
    $chkL = $conn->prepare("SELECT 1 FROM accounts WHERE AccountID=? AND ARole='Learner' LIMIT 1");
    $chkL->bind_param("s", $AccountID);
    $chkL->execute();
    $okL = (bool)$chkL->get_result()->fetch_row();

    // Validate course
    $chkC = $conn->prepare("SELECT 1 FROM courses WHERE CourseID=? LIMIT 1");
    $chkC->bind_param("s", $CourseID);
    $chkC->execute();
    $okC = (bool)$chkC->get_result()->fetch_row();

    // Validate mark (nullable, decimal)
    $Mark = null;
    if ($MarkStr !== '') {
      if (!is_numeric($MarkStr)) {
        $error = "Score must be a number.";
      } else {
        $Mark = (float)$MarkStr;
      }
    }

    if (empty($error) && !$okL)       $error = "Learner not found (or not a Learner).";
    if (empty($error) && !$okC)       $error = "Course not found.";

    if (empty($error)) {
      $sql = "UPDATE results SET AccountID=?, CourseID=?, Content=?, Mark=?, RStatus=? WHERE ResultID=?";
      $upd = $conn->prepare($sql);
      // bind: s s s d s i  (Mark can be NULL -> use null handling)
      if ($Mark === null) {
        // When binding NULL with "d" MySQLi sets 0. Use set_null workaround:
        $upd->bind_param("sssisi", $AccountID, $CourseID, $Content, $Mark /* null for now */, $RStatus, $resultId);
        // Explicitly set NULL for the 4th parameter
        $upd->send_long_data(3, null);
      } else {
        $upd->bind_param("sssisi", $AccountID, $CourseID, $Content, $Mark, $RStatus, $resultId);
      }
      $upd->execute();

      $success = "Result updated successfully.";
      // refresh current for display
      $current['AccountID'] = $AccountID;
      $current['CourseID']  = $CourseID;
      $current['Content']   = $Content;
      $current['Mark']      = $MarkStr;
      $current['RStatus']   = $RStatus;
    }
  }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Result</title>

  <!-- Tailwind (utility classes) + shared edit-layout CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="style_edit.css">
</head>
<body class="bg-darkblue text-textlight flex items-center justify-center min-h-screen p-6">
  <div class="bg-cardblue w-full max-w-2xl rounded-2xl shadow-2xl p-8">
    <h2 class="text-2xl font-bold text-center mb-6">Edit Result</h2>

    <?php if (!empty($success)): ?>
      <div class="mb-4 p-3 rounded bg-green-600/20 border border-green-600 text-green-200"><?= h($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="mb-4 p-3 rounded bg-red-600/20 border border-red-600 text-red-200"><?= h($error) ?></div>
    <?php endif; ?>

    <form class="space-y-6" method="post" action="">
      <!-- ResultID (readonly) -->
      <div>
        <label class="block text-sm font-medium mb-1">ResultID</label>
        <input type="text" value="<?= h($current['ResultID']) ?>" readonly
               class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 text-gray-400 cursor-not-allowed">
      </div>

      <!-- Learner (*) -->
      <div>
        <label class="block text-sm font-medium mb-1">Learner <span class="text-red-400">*</span></label>
        <select name="AccountID" required
                class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 focus:ring-2 focus:ring-primary">
          <option value="">-- Select learner --</option>
          <?php foreach ($learners as $l): ?>
            <option value="<?= h($l['AccountID']) ?>" <?= ($l['AccountID']===$current['AccountID']?'selected':'') ?>>
              <?= h($l['AName']) ?> (<?= h($l['AccountID']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Course (*) -->
      <div>
        <label class="block text-sm font-medium mb-1">Course <span class="text-red-400">*</span></label>
        <select name="CourseID" required
                class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 focus:ring-2 focus:ring-primary">
          <option value="">-- Select course --</option>
          <?php foreach ($courses as $c): ?>
            <option value="<?= h($c['CourseID']) ?>" <?= ($c['CourseID']===$current['CourseID']?'selected':'') ?>>
              <?= h($c['CName']) ?> (<?= h($c['CourseID']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Exam name (Content) -->
      <div>
        <label class="block text-sm font-medium mb-1">Exam</label>
        <input type="text" name="Content" value="<?= h($current['Content']) ?>"
               class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 focus:ring-2 focus:ring-primary"
               placeholder="e.g., Midterm, Final Exam, Quiz">
      </div>

      <!-- Score (Mark, optional) -->
      <div>
        <label class="block text-sm font-medium mb-1">Score</label>
        <input type="number" step="0.01" name="Mark" value="<?= h((string)$current['Mark']) ?>"
               class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 focus:ring-2 focus:ring-primary"
               placeholder="e.g., 85 or 85.5">
        <p class="text-xs text-gray-400 mt-1">Leave empty if ungraded.</p>
      </div>

      <!-- Status (*) -->
      <div>
        <label class="block text-sm font-medium mb-1">Status <span class="text-red-400">*</span></label>
        <select name="RStatus" required
                class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 focus:ring-2 focus:ring-primary">
          <?php foreach ($statuses as $s): ?>
            <option value="<?= h($s) ?>" <?= (strcasecmp($current['RStatus'],$s)===0?'selected':'') ?>><?= h($s) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end space-x-4">
        <a href="result.php"
           class="px-6 py-2 rounded-lg border border-gray-600 text-gray-300 font-semibold hover:bg-gray-700 transition">
          Cancel
        </a>
        <button type="submit"
                class="px-6 py-2 rounded-lg bg-primary text-textlight font-semibold hover:bg-primaryhover shadow-md transition">
          Save changes
        </button>
      </div>
    </form>
  </div>
</body>
</html>
