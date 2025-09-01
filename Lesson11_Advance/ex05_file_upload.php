<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kiểm tra xem có file được upload không
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $fileTmp = $_FILES['avatar']['tmp_name'];
        $fileName = $_FILES['avatar']['name'];
        $fileSize = $_FILES['avatar']['size'];//kích thước
        $fileType = $_FILES['avatar']['type'];//kiểu file
        
        // Lấy phần mở rộng của file
        $fileExt = pathinfo($Path, PATHINFO_EXTENSION);
        $fileExt = strtolower($fileNameParts['extension']);
        
        // Kiểm tra định dạng file (chỉ cho phép ảnh)
        $allowedExt = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($fileExtension, $allowedExt)) {
            // Đặt đường dẫn lưu file
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $newFileName = uniqid() . '.' . $fileExt;
            $uploadFilePath = $uploadDir . $newFileName;
            
            // Di chuyển file từ tạm thời vào thư mục đích
            if (move_uploaded_file($fileTmp, $uploadFilePath)) {
                $message = "Tải ảnh đại diện thành công!";
                $uploadedImage = $uploadFilePath; // Lưu đường dẫn ảnh đã tải lên
            } else {
                $message = "Lỗi khi tải ảnh lên.";
            }
        } else {
            $message = "Vui lòng chọn tệp ảnh hợp lệ.";
        }
    } else {
        $message = "Vui lòng chọn ảnh để tải lên.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Đăng Nhập</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .login-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .login-container h2 {
            text-align: center;
        }
        .file-upload {
            display: block;
            margin: 10px auto;
            border-radius: 50%;
            overflow: hidden;
            width: 80px;
            height: 80px;
            background-color: #f0f0f0;
        }
        .file-upload input {
            display: none;
        }
        .file-upload img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .file-upload label {
            display: block;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }
        .message {
            color: #ff0000;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Đăng Nhập</h2>

        <!-- Hiển thị thông báo -->
        <?php if (isset($message)) : ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST" enctype="multipart/form-data">
            <!-- Logo Upload -->
            <div class="file-upload">
                <input type="file" id="avatar" name="avatar" accept="image/*" onchange="previewImage(event)">
                <label for="avatar">
                    <img id="preview" src="<?php echo isset($uploadedImage) ? $uploadedImage : ''; ?>" alt="Ảnh đại diện">
                </label>
            </div>
            
            <!-- Username -->
            <div class="form-group">
                <input type="text" name="username" placeholder="Tên đăng nhập" required>
            </div>
            
            <!-- Password -->
            <div class="form-group">
                <input type="password" name="password" placeholder="Mật khẩu" required>
            </div>

            <!-- Submit -->
            <div class="form-group">
                <input type="submit" value="Đăng Nhập">
            </div>
        </form>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
