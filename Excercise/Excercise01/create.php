<?php 
//form thêm brand mới
include_once 'connectbdb.php';//Nạp file chứa kết nối database $conn.
if($_SERVER["REQUEST_METHOD"] == "POST")://Kiểm tra nếu form được gửi bằng phương thức POST.
    $name =$_POST["name"];//Lấy dữ liệu từ input của người dùng:
    $country =$_POST["country"];//Lấy dữ liệu từ input của người dùng:
    $sql = "insert into brands(name,country) values (?,?)";
    $stmt = $conn->prepare($sql);//Sử dụng prepared statement để tránh lỗi SQL injection.
    $stmt->bind_param("ss",$name,$country);//"ss": 2 tham số dạng string.
                                       //Gửi dữ liệu vào brands(name, country).
    $stmt->execute();
    $stmt->close();
    header("location: Management.php");//Sau khi thêm xong → chuyển hướng về trang quản lý brand.
endif;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <title></title>
    </head>
    <body>
       <div class="container mt-3">
            <h2>New Brand Form</h2>
            <form method="POST">
                <div class="mb-3 mt-3">
                    <label for="name">Name: </label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3 mt-3">
                    <label for="country">Country: </label>
                    <input type="text" class="form-control" id="country" name="country" required>
                </div>
                <input type="submit" value="Add new" class="btn btn-primary btn-sm">
            </form>
        </div>
    </body>
</html>
