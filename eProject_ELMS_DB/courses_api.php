<?php
// courses_api.php
header('Content-Type: application/json; charset=utf-8');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$servername = "127.0.0.1";
$username   = "root";
$password   = "";
$dbname     = "elmsdb";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset('utf8mb4');

    /* 
      Giả định bảng courses có tối thiểu:
        - CourseID (PK)
        - CName (tên)
      Tuỳ schema của bạn, nếu có Description/Category/Link thì dùng trực tiếp.
      Ở đây mình suy luận:
        - Description (nullable)
        - Category (nullable)
      href sẽ trỏ tới trang chi tiết.
    */
    $sql = "
      SELECT 
        CourseID                              AS id,
        CName                                 AS title,
        COALESCE(Description, '')             AS `desc`,
        COALESCE(Category, 'general')         AS cat,
        CONCAT('course_detail.php?id=', CourseID) AS href
      FROM courses
      ORDER BY CName ASC
    ";
    $res = $conn->query($sql);

    $rows = [];
    while ($r = $res->fetch_assoc()) {
        // Ép kiểu an toàn
        $rows[] = [
            'id'    => (string)$r['id'],
            'title' => (string)$r['title'],
            'desc'  => (string)$r['desc'],
            'cat'   => (string)$r['cat'],
            'href'  => (string)$r['href'],
        ];
    }

    echo json_encode(['ok'=>true, 'data'=>$rows], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok'=>false, 'error'=>'Server error', 'detail'=>$e->getMessage()]);
} finally {
    if (isset($conn) && $conn instanceof mysqli) $conn->close();
}
