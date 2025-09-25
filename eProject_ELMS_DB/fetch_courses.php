<?php
// fetch_courses.php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);

$servername = "127.0.0.1";
$username   = "root";
$password   = "";
$dbname     = "elmsdb";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset('utf8mb4');

$search  = trim($_GET['search']  ?? '');
$creator = trim($_GET['creator'] ?? '');
$status  = trim($_GET['status']  ?? ''); // 'Active' | 'Inactive' | ''

$sql = "SELECT c.CourseID,
               c.CName,
               c.CDescription,
               c.StartDate,
               c.CreatedAt,
               c.CStatus,
               c.CreatorID,
               a.AName AS CreatorName
        FROM Courses c
        LEFT JOIN Accounts a ON a.AccountID = c.CreatorID
        WHERE 1";
$types  = '';
$params = [];

// Search theo tên/mô tả
if ($search !== '') {
    $sql .= " AND (c.CName LIKE ? OR c.CDescription LIKE ?)";
    $like   = "%{$search}%";
    $types .= 'ss';
    $params[] = $like;
    $params[] = $like;
}

// Filter creator
if ($creator !== '') {
    $sql .= " AND c.CreatorID = ?";
    $types  .= 's';
    $params[] = $creator;
}

// Filter status (DB đang lưu 'Active' / 'Inactive')
if ($status !== '') {
    $sql   .= " AND c.CStatus = ?";
    $types .= 's';
    $params[] = $status; // truyền đúng 'Active' hoặc 'Inactive'
}

$sql .= " ORDER BY c.CreatedAt DESC";

$stmt = $conn->prepare($sql);
if ($types !== '') {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo '<tr><td colspan="8" class="py-4 text-center">No courses found.</td></tr>';
    exit;
}

while ($r = $res->fetch_assoc()) {
    $start   = !empty($r['StartDate']) ? date('d/m/Y', strtotime($r['StartDate'])) : '';
    $created = !empty($r['CreatedAt']) ? date('d/m/Y', strtotime($r['CreatedAt'])) : '';
    $creatorLabel = $r['CreatorName'] ?: $r['CreatorID'];

    $statusBadge = (strcasecmp((string)$r['CStatus'], 'Active') === 0)
        ? '<span class="px-3 py-1 text-green-400 rounded-full bg-gray-900">Active</span>'
        : '<span class="px-3 py-1 text-red-400 rounded-full bg-gray-900">Inactive</span>';

    echo '<tr class="border-b border-gray-700 hover:bg-gray-800 transition-colors duration-200">';
    echo '  <td class="px-4 py-2">'.htmlspecialchars($r['CourseID']).'</td>';
    echo '  <td class="px-4 py-2">'.htmlspecialchars($r['CName']).'</td>';
    echo '  <td class="px-4 py-2">'.htmlspecialchars($r['CDescription']).'</td>';
    echo '  <td class="px-4 py-2">'.$start.'</td>';
    echo '  <td class="px-4 py-2">'.htmlspecialchars($creatorLabel).'</td>';
    echo '  <td class="px-4 py-2">'.$created.'</td>';
    echo '  <td class="px-4 py-2">'.$statusBadge.'</td>';
    echo '  <td class="px-4 py-2 space-x-2">
              <a href="edit_course.php?id='.rawurlencode($r['CourseID']).'" class="px-3 py-1 bg-green-600 hover:bg-green-500 rounded text-white">Edit</a>
              <a href="delete_course.php?id='.rawurlencode($r['CourseID']).'" class="px-3 py-1 bg-red-600 hover:bg-red-500 rounded text-white" onclick="return confirm(\'Are you sure?\')">Delete</a>
            </td>';
    echo '</tr>';
}

$stmt->close();
$conn->close();
