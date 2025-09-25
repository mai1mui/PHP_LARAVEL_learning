<?php
session_start();
// Chỉ cho Admin/Instructor
if (!isset($_SESSION["AccountID"]) || !in_array(strtolower($_SESSION["ARole"] ?? ''), ['admin', 'instructor'])) {
    header("Location: login.php");
    exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Kết nối DB
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "elmsdb";
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset('utf8mb4');

$courseId = trim($_GET['id'] ?? '');
if ($courseId === '') {
    http_response_code(400);
    die("Missing id");
}

$success = $error = "";

/* ====== Lấy dữ liệu khoá học hiện tại ====== */
$sql = "SELECT c.CourseID, c.CName, c.CDescription, c.StartDate, c.CreatorID, c.CreatedAt, c.CStatus,
               a.AName AS CreatorName
        FROM courses c
        LEFT JOIN accounts a ON a.AccountID = c.CreatorID
        WHERE c.CourseID = ?";
$st = $conn->prepare($sql);
$st->bind_param("s", $courseId);
$st->execute();
$course = $st->get_result()->fetch_assoc();
if (!$course) {
    http_response_code(404);
    die("Course not found");
}

/* ====== Cập nhật (POST) ====== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $CName = trim($_POST['CName'] ?? '');
    $CDescription = trim($_POST['CDescription'] ?? '');
    $StartDate = trim($_POST['StartDate'] ?? ''); // yyyy-mm-dd
    $CStatus = trim($_POST['CStatus'] ?? '');   // Active | Inactive
    // Validate
    if ($CName === '' || $CDescription === '' || $StartDate === '' || $CStatus === '') {
        $error = "Please fill all required fields (*).";
    } elseif (!in_array($CStatus, ['Active', 'Inactive'], true)) {
        $error = "Invalid status value.";
    } elseif (strtotime($StartDate) === false) {
        $error = "Invalid Start Date.";
    } else {
        $upd = $conn->prepare("UPDATE courses SET CName=?, CDescription=?, StartDate=?, CStatus=? WHERE CourseID=?");
        $upd->bind_param("sssss", $CName, $CDescription, $StartDate, $CStatus, $courseId);
        $upd->execute();

        header("Location: course.php?updated={$courseId}");
        exit;

        // refresh dữ liệu hiện tại
        $course['CName'] = $CName;
        $course['CDescription'] = $CDescription;
        $course['StartDate'] = $StartDate;
        $course['CStatus'] = $CStatus;
    }
}

$conn->close();

// Chuẩn hoá để hiển thị
$createdDate = $course['CreatedAt'] ? date('Y-m-d', strtotime($course['CreatedAt'])) : '';
$startDate = $course['StartDate'] ? date('Y-m-d', strtotime($course['StartDate'])) : '';
?>
<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8" />
        <title>Edit Course</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link rel="stylesheet" href="style_edit.css">
    </head>
    <body class="edit-body">
        <div class="card">
            <h2 class="title">Edit Course</h2>

            <?php if (!empty($success)): ?>
                <div class="alert alert--success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert--error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post" action="">
                <!-- Course ID (readonly) -->
                <div class="form-group">
                    <label class="form-label">CourseID</label>
                    <input type="text" value="<?= htmlspecialchars($course['CourseID']) ?>" class="form-control form-control--readonly" readonly>
                </div>

                <!-- Course Name (*) -->
                <div class="form-group">
                    <label class="form-label">Course Name <span class="req">*</span></label>
                    <input type="text" name="CName" value="<?= htmlspecialchars($course['CName']) ?>" required class="form-control">
                </div>

                <!-- Description (*) -->
                <div class="form-group">
                    <label class="form-label">Description <span class="req">*</span></label>
                    <textarea name="CDescription" rows="3" required class="form-control"><?= htmlspecialchars($course['CDescription']) ?></textarea>
                </div>

                <!-- Creator (readonly) -->
                <div class="form-group">
                    <label class="form-label">Creator</label>
                    <input type="text" value="<?= htmlspecialchars($course['CreatorName'] ?: $course['CreatorID']) ?>" class="form-control form-control--readonly" readonly>
                </div>

                <!-- Date Created (readonly) -->
                <div class="form-group">
                    <label class="form-label">Date Created</label>
                    <input type="date" value="<?= htmlspecialchars($createdDate) ?>" class="form-control form-control--readonly" readonly>
                </div>

                <!-- Start Date (*) -->
                <div class="form-group">
                    <label class="form-label">Start Date <span class="req">*</span></label>
                    <input type="date" name="StartDate" value="<?= htmlspecialchars($startDate) ?>" required class="form-control">
                </div>

                <!-- Status (*) -->
                <div class="form-group">
                    <label class="form-label">Status <span class="req">*</span></label>
                    <select name="CStatus" required class="form-control">
                        <option value="Active"   <?= (strcasecmp($course['CStatus'], 'Active') === 0 ? 'selected' : '') ?>>Active</option>
                        <option value="Inactive" <?= (strcasecmp($course['CStatus'], 'Inactive') === 0 ? 'selected' : '') ?>>Inactive</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="actions">
                    <a href="course.php" class="btn btn--ghost">Cancel</a>
                    <button type="submit" class="btn btn--primary">Save</button>
                </div>
            </form>
        </div>
    </body>
</html>
