<?php
/************************************
 * [PHẦN 1] - Kết nối CSDL & khai báo
 ************************************/
include_once 'name_db.php'; // Kết nối tới DB (viết riêng trong file khác)
$message = ''; // Biến để hiển thị thông báo (thành công hoặc lỗi)


/*******************************************
 * [PHẦN 2] - Kiểm tra nếu người dùng Submit form
 *******************************************/
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // [2.1] Lấy dữ liệu từ form
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    
    // [2.2] Kiểm tra dữ liệu (validation đơn giản)
    if (empty($name) || empty($email)) {
        $message = "Vui lòng điền đầy đủ thông tin.";
    } else {
        /***************************************
         * [PHẦN 3] - Xử lý file upload (nếu có)
         ***************************************/
        $uploadFilePath = '';
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
            $fileTmp = $_FILES['avatar']['tmp_name'];
            $fileName = $_FILES['avatar']['name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($fileExt, $allowed)) {
                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                $newFileName = uniqid() . '.' . $fileExt;
                $uploadFilePath = $uploadDir . $newFileName;

                if (!move_uploaded_file($fileTmp, $uploadFilePath)) {
                    $message = "Lỗi khi upload ảnh.";
                }
            } else {
                $message = "Định dạng ảnh không hợp lệ.";
            }
        }

        /****************************************
         * [PHẦN 4] - Thêm dữ liệu vào Database
         ****************************************/
        if ($message === '') {
            $sql = "INSERT INTO users (name, email, avatar) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $newFileName);
            if ($stmt->execute()) {
                /**************************************
                 * [PHẦN 5] - Thành công → chuyển trang
                 **************************************/
                header("Location: success.php");
                exit();
            } else {
                $message = "Lỗi truy vấn DB: " . $conn->error;
            }
            $stmt->close();
        }
    }
}
?>
<!-- Giao diện HTML (kèm form và thông báo)-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Template</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Form Thêm Mới</h2>
        <form method="POST" enctype="multipart/form-data">
            <!-- [PHẦN 6] - Form nhập liệu -->
            <div class="mb-3">
                <label>Họ tên:</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label>Email:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label>Ảnh đại diện:</label>
                <input type="file" class="form-control" name="avatar" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Thêm mới</button>
        </form>

        <!-- [PHẦN 7] - Hiển thị thông báo -->
        <?php if ($message): ?>
            <div class="alert alert-warning mt-3"><?php echo $message; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
