<?php
//upload và cập nhật logo cho từng thương hiệu (brand)
    $serverName = "localhost";//server MySQL đang chạy trên máy cục bộ.
    $username   = "root";
    $password   = "";
    $dbName     = "branddb";
//-----------
    //connect data
    $conn = new mysqli($serverName, $username, $password, $dbName);
    //Kiểm tra xem kết nối có thành công không. Nếu thất bại, dừng chương trình và in lỗi.
    if ($conn->connect_error):
        die("ket noi den database $dbName that bai" . $conn->connect_error);
    endif;
//-----------
// Xử lý khi submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Kiểm tra ảnh được chọn
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {//Kiểm tra xem người dùng có chọn file ảnh hợp lệ hay không.
        //Lấy thông tin về tệp ảnh
        $fileTmp = $_FILES['logo']['tmp_name'];// đường dẫn tệp tạm thời mà PHP lưu ảnh trên máy chủ trước khi bạn di chuyển nó đến thư mục mong muốn.
        $fileName = $_FILES['logo']['name'];//Tên gốc của tệp người dùng tải lên
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));// đuôi file
        //Kiểm tra định dạng ảnh hợp lệ
        $allowedExt = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($fileExt, $allowedExt)) {
            //Tạo thư mục upload
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);//Kiểm tra thư mục uploads: Nếu thư mục uploads/ chưa tồn tại, bạn sẽ tạo nó với quyền truy cập đầy đủ (0777).
                                              //Thư mục này sẽ lưu ảnh được tải lên từ người dùng.
            }
            //Tạo tên tệp mới và di chuyển ảnh
            $newFileName = uniqid() . '.' . $fileExt;//Để tránh trùng lặp tên tệp, bạn sử dụng uniqid() để tạo tên tệp ngẫu nhiên, thêm phần mở rộng (jpg, png, v.v.)
            $uploadFilePath = $uploadDir . $newFileName;
            if (move_uploaded_file($fileTmp, $uploadFilePath)) {//Dùng hàm move_uploaded_file() để di chuyển ảnh từ thư mục tạm thời vào thư mục uploads/. Nếu thành công, bạn sẽ có đường dẫn đầy đủ của ảnh.
                $message = "Tải ảnh thành công!";
                $uploadedImage = $uploadFilePath;
                // Cập nhật DB với tên tệp ảnh mới
                $id = $_POST['id'];
                $sqlUpdate = "UPDATE brands SET logo='$newFileName' WHERE id=$id";
                if ($conn->query($sqlUpdate)) {
                    header("Location: Management.php"); // quay lại trang danh sách
                    exit();
                } else {
                    $message = "Lỗi khi cập nhật cơ sở dữ liệu.";
                }
            } else {
                $message = "Lỗi khi lưu ảnh.";
            }
        } else {
            $message = "Chỉ cho phép định dạng ảnh JPG, JPEG, PNG, GIF.";
        }
    } else {
        $message = "Vui lòng chọn ảnh.";
    }
}

//Hiển thị dữ liệu
if(isset($_GET['id'])):
    $id=$_GET['id'];//Khi người dùng nhấn vào nút "Add logo", id của thương hiệu sẽ được truyền qua URL (logo.php?id=...)
                    //Bạn sử dụng $_GET['id'] để lấy ID này và truy vấn thông tin thương hiệu từ CSDL.
    $sql="select * from brands where id=$id";
    $result=$conn->query($sql);
    $brands=$result->fetch_assoc();
endif;

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <title>Add Logo Form</title>
    </head>
    <body>
       <div class="container mt-3">
            <h2>Add Logo Form</h2>
            <form action="logo.php" mathod="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $brands['id'] ?>">
                <div class="mb-3 mt-3">
                    <label for="name">Name: </label>
                    <input type="text" class="form-control" name="name" value="<?= $brands['name']?>">
                </div>
                <div class="mb-3 mt-3">
                    <label for="logo">Logo: </label>
                    <input type="file" class="form-control" name="logo" accept="image/*" onchange="previewImage(event)">
                </div>
                <!-- Hiển thị ảnh preview -->
                <div class="file-upload">
                    
                    <label for="logo">
                        <img id="preview" src="<?php echo isset($uploadedImage) ? $uploadedImage : ''; ?>" alt="Ảnh logo">
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
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
