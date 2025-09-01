<?php
/*trang quản lý danh sách thương hiệu (Brand List) có chức năng:
    Hiển thị bảng dữ liệu
    Thêm logo
    Cập nhật brand
    Xoá brand
*/
include_once ("connectbdb.php");//Nạp file chứa kết nối database $conn.
$sql = "select * from brands";//truy vấn toàn bộ thông tin từ bảng brands
$brands = $conn->query($sql);
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
        <a href="./create.php" class="btn btn-primary">Add New</a>
        <h2>BRAND LIST</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Logo</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($brands->num_rows>0):
                        foreach ($brands as $brands):
                ?>
                    <tr>
                        <td><?= $brands["id"]?></td>
                        <td><?= $brands["name"]?></td>
                        <td><?= $brands["country"]?></td>
                        <td><img src="uploads/<?=$brands['logo']?>" width="100" alt="Logo"></td><!--Lấy ảnh logo từ thư mục uploads. Nếu brand chưa có logo → có thể bị lỗi ảnh không tồn tại.-->
                        <td>
                            <div style="display: flex;">
                               <div style="flex: 1; padding: 2px;">
                               <!-- Form update -->
                               <form action="logo.php" method="get">
                                   <input type="hidden" name="id" value="<?= $brands['id'] ?>">
                                   <input type="submit" name="logo" value="Add logo" class="btn btn-info btn-sm">
                               </form>
                               </div>
                               <div style="flex: 1; padding: 2px;">
                               <!-- Form update -->
                               <form action="update.php" method="get">
                                   <input type="hidden" name="id" value="<?= $brands['id'] ?>">
                                   <input type="submit" name="update" value="Update" class="btn btn-warning btn-sm">
                               </form>
                               </div>
                               <div style="flex: 1; padding: 2px;">
                               <!-- Form delete -->
                               <form action="delete.php" method="post" onsubmit="return confirm('Are you sure?');">
                                   <input type="hidden" name="id" value="<?= $brands['id'] ?>">
                                   <input type="submit" name="delete" value="Delete" class="btn btn-danger btn-sm">
                               </form>
                               </div>
                           </div>
                       </td>
                         
                    </tr>
                <?php    
                        endforeach;
                    endif;
                ?>
                
            </tbody>
        </table>
    </div>
</body>

</html>
