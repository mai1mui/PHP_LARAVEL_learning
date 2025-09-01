
<?php
//chỉnh sửa thông tin trong database
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
//doc data tu bang brands
if(isset($_GET['id'])):
    $id=$_GET['id'];
    $sql="select * from brands where id=$id";
    $result=$conn->query($sql);
    $brands=$result->fetch_assoc();
endif;

//xu ly khi user update
if($_SERVER["REQUEST_METHOD"]=="POST")://Khi người dùng nhấn nút Save để cập nhật thông tin, biểu mẫu sẽ gửi dữ liệu qua phương thức POST.
    $id=$_POST["id"];
    $name=$_POST["name"];
    $country=$_POST["country"];
    $sql="update brands set name='$name', country='$country' where id=$id";
    if($conn->query($sql)==true):
        // Nếu cập nhật thành công, chuyển hướng về trang Management
        header("location:Management.php");
        exit();
    else:
        echo"error!".$conn->error;//Nếu có lỗi, thông báo lỗi sẽ được in ra.
    endif;
endif;

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Information</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-container {
            max-width: 400px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Update Information</h2>
    <form method="POST" id="editForm">
        <input type="hidden" name="id" value="<?=$brands['id']?>">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?=$brands['name']?>" required>
        </div>
        <div class="form-group">
            <label for="country">Country:</label>
            <input type="text" id="country" name="country" value="<?=$brands['country']?>" required>
        </div>
        <div>
            <button type="submit" class="btn btn-success">Save</button>
            <a href="Management.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>