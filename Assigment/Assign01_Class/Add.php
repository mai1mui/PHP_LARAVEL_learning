<?php
//form thêm brand mới
// [PHẦN 1] - Kết nối DB và khai báo biến
include_once 'PhoneBrandDB.php';//Nạp file chứa kết nối database $conn.
$message = '';//Biến $message dùng để lưu thông báo lỗi hoặc trạng thái xử lý.
// [PHẦN 2] - Kiểm tra nếu người dùng submit form
if ($_SERVER["REQUEST_METHOD"] == "POST") {//Kiểm tra nếu form được gửi đi bằng phương thức POST.
    $Name = $_POST["Name"];
    $Country = $_POST["Country"];
    
    // [PHẦN 3] - Kiểm tra xem có chọn ảnh hợp lệ không
    if (isset($_FILES['Logo']) && $_FILES['Logo']['error'] == 0) {
        //Lấy thông tin về tệp ảnh
        $fileTmp = $_FILES['Logo']['tmp_name'];//Đường dẫn file tạm thời được tạo khi upload ảnh.
                                               //$fileName = $_FILES['Logo']['name'];/Tên gốc của file được upload.
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));//Lấy phần mở rộng file và chuyển thành chữ thường.
        
        //Kiểm tra định dạng ảnh hợp lệ-Tránh người dùng upload file nguy hiểm
        $allowedExt = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($fileExt, $allowedExt)) {
             // [PHẦN 4] - Tạo thư mục uploads và lưu ảnh
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);//0777:cấp quyền ghi/đọc/thi hành cho tất cả.
            }
            
            // Tạo tên tệp mới và di chuyển ảnh
            $newFileName = uniqid() . '.' . $fileExt;// Tạo tên file mới duy nhất bằng uniqid() để tránh trùng tên.
            $uploadFilePath = $uploadDir . $newFileName;//Gộp đường dẫn thư mục và tên file thành đường dẫn cuối cùng để lưu ảnh.
            if (move_uploaded_file($fileTmp, $uploadFilePath)) {// Di chuyển ảnh từ thư mục tạm sang thư mục uploads/.
                $uploadedImage = $uploadFilePath;//Lưu lại đường dẫn ảnh vừa upload để hiển thị ở phần preview.
                
                // [PHẦN 5] - Thêm brand vào DB
                $sql = "INSERT INTO PhoneBrand(Name, Country, Logo) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);//prepared statement để tránh SQL Injection.
                $stmt->bind_param("sss", $Name, $Country, $newFileName);//"sss": nghĩa là 3 biến kiểu string.
                $stmt->execute();
                $stmt->close();

                // Sau khi thêm xong → chuyển hướng về trang quản lý brand.
                header("Location: Index.php");
                exit();
            // [PHẦN 6] - Báo lỗi khi update ảnh
            } else {
//                $message = "Lỗi khi lưu ảnh.";/Lưu thất bại
            }
        } else {
            $message = "Chỉ cho phép định dạng ảnh JPG, JPEG, PNG, GIF.";//File sai định dạng
        }
    } else {
        $message = "Vui lòng chọn ảnh.";//Không chọn file
    }
}
?>
<!-- [PHẦN 7] - Giao diện Form HTML -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <title>Add Brand Form</title>
    </head>
    <body>
       <div class="container mt-3">
            <h2>Add Brand Form</h2>
            <form method="POST" enctype="multipart/form-data"><!--Gửi dữ liệu qua POST và cho phép upload file (enctype bắt buộc khi có input type file).-->
                <div class="mb-3 mt-3">
                    <label for="Name">Brand Name: </label>
                    <input type="text" class="form-control" id="Name" name="Name" required>
                </div>
                <div class="mb-3 mt-3">
                    <label for="Country">Manufacture(Country): </label>
                    <input type="text" class="form-control" id="Country" name="Country" required>
                </div>
                <div class="mb-3 mt-3">
                    <label for="Logo">Logo: </label>
                    <input type="file" class="form-control" name="Logo" accept="image/*" required>
                </div>
                <!-- Hiển thị ảnh preview -->
                <div class="file-upload">
                    <label for="Logo">
                        <img id="preview" src="<?php echo isset($uploadedImage) ? $uploadedImage : ''; ?>" alt="Ảnh logo" style="max-width: 200px;">
                        <!--Nếu biến $uploadedImage tồn tại → hiện ảnh đã upload.
                            Nếu không thì để trống.-->
                    </label>
                </div>
                <input type="submit" value="Add new" class="btn btn-primary btn-sm">
            </form>
            <?php if ($message): ?>
                <div class="alert alert-warning mt-3"><?php echo $message; ?></div>
            <?php endif; ?>
        </div>
    </body>
</html>
