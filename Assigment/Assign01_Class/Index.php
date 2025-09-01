
<?php
/*Trang quản lý danh sách thương hiệu (Brand List) có chức năng:
    Hiển thị bảng dữ liệu
    Thêm logo
    Cập nhật brand
    Xoá brand
    search 
*/
/********************************************
 * [PHẦN 1] – Kiểm tra đăng nhập
 ********************************************/
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit();
}

/********************************************
 * [PHẦN 2] – Kết nối CSDL & truy vấn ban đầu
 ********************************************/
include_once("PhoneBrandDB.php");

/********************************************
 * [PHẦN 3] – Xử lý tìm kiếm (nếu có)
 ********************************************/
$searchKeyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

if ($searchKeyword != "") {
    $stmt = $conn->prepare("SELECT * FROM PhoneBrand WHERE Name LIKE ? OR Country LIKE ?");
    $likeKeyword = "%$searchKeyword%";
    $stmt->bind_param("ss", $likeKeyword, $likeKeyword);
    $stmt->execute();
    $PhoneBrand = $stmt->get_result();
} else {
    $PhoneBrand = $conn->query("SELECT * FROM PhoneBrand");
    if (!$PhoneBrand) {
        die("Query failed: " . $conn->error);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>BRAND LIST</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container mt-3">
        <h2>BRAND LIST</h2>

        <!-- [PHẦN 5] – FORM TÌM KIẾM -->
        <form class="row g-3 mb-3" method="get" action="">
            <div class="col-auto">
                <input type="text" class="form-control" name="keyword" placeholder="Enter brand info" value="<?= htmlspecialchars($searchKeyword) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success mb-3 btn-sm" name="btn-search">Search</button>
                <a href="index.php" class="btn btn-secondary mb-3 btn-sm">Reset</a>
            </div>
        </form>
        <!-- [PHẦN 6] – BẢNG HIỂN THỊ DANH SÁCH BRAND -->
        <table class="table table-bordered">
            <a href="./Add.php" class="btn btn-primary btn-sm">Add New</a>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Logo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($PhoneBrand->num_rows > 0): ?>
                    <?php foreach ($PhoneBrand as $brand): ?>
                        <tr>
                            <td><?= $brand["PBID"] ?></td>
                            <td><?= $brand["Name"] ?></td>
                            <td><?= $brand["Country"] ?></td>
                            <!-- [PHẦN 7] – Hiển thị ảnh logo -->
                            <td>
                                <?php 
                                    $logo = !empty($brand['Logo']) && file_exists("uploads/" . $brand['Logo']) 
                                        ? $brand['Logo'] 
                                        : 'no-logo.png'; 
                                ?>
                                <img src="uploads/<?= $logo ?>" width="100" height="" alt="Logo">
                            </td>
                            <!-- [PHẦN 8] – Các nút Update / Delete -->
                            <td>
                                <div class="d-flex gap-2">
                                    <!--Detail-->
                                    <a href="./Detail.php?PBID=<?= $brand['PBID'] ?>" class="btn btn-info btn-sm">Detail</a>
                                    <!--Update-->
                                    <form action="Update.php" method="get">
                                        <input type="hidden" name="PBID" value="<?= $brand['PBID'] ?>">
                                        <input type="submit" value="Update" class="btn btn-warning btn-sm">
                                    </form>
                                    <!--Delete-->
                                    <form action="Delete.php" method="post" onsubmit="return confirm('Are you sure?');">
                                        <input type="hidden" name="PBID" value="<?= $brand['PBID'] ?>">
                                        <input type="submit" value="Delete" class="btn btn-danger btn-sm">
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                   <!-- [PHẦN 9] – Thông báo khi không có dữ liệu -->
                    <tr><td colspan="5" class="text-center">No brand found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
