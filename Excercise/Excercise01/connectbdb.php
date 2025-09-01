<?php
/*
Giải thích:
Bước 1: Kết nối đến MySQL server mà chưa chỉ định database.
Bước 2: Gửi câu lệnh SQL để tạo database nếu chưa tồn tại.
Bước 3: Đóng kết nối cũ, rồi kết nối lại với database đã tạo. */
    $serverName = "localhost";//server MySQL đang chạy trên máy cục bộ.
    $username   = "root";
    $password   = "";
    $dbName     = "branddb";
//--------------
    //Tạo một kết nối mới đến MySQL server nhưng chưa chỉ định database.
    $conn = new mysqli($serverName, $username, $password);
    //Kiểm tra xem kết nối có thành công không. Nếu thất bại, dừng chương trình và in lỗi.
    if($conn->connect_error):
        die("ket noi that bai!".$conn->connect_error);
    endif;
    //Tạo câu lệnh SQL để tạo database nếu nó chưa tồn tại.
    $sql="create database if not exists branddb";

    //Đóng kết nối cũ để chuẩn bị kết nối lại, lần này có chỉ định rõ database branddb.
    $conn->close();
//---------------
    //Tạo lại kết nối, lần này đã chỉ định database.
    $conn=new mysqli($serverName, $username, $password,$dbName);
    //Kiểm tra xem kết nối có thành công không. Nếu thất bại, dừng chương trình và in lỗi.
    if($conn->connect_error):
        die("ket noi den database $dbName that bai!<br>".$conn->connect_error);
    endif;
    echo"";
//---------------
    //create table
    $sql="create table if not exists brands(
        id int auto_increment primary key,
        name varchar(50),
        country varchar(250)
    )";
    //Kiểm tra xem tại database có thành công không. Nếu thất bại thì in lỗi.
    if ($conn->query($sql) === true):
            echo "";
        else:
            echo "Error! " . $conn->error . "<br>";
        endif;
//---------------
//add cot logo vao bang
$sql = "SHOW COLUMNS FROM brands LIKE 'logo'";//check cot logo da co chua
$result = $conn->query($sql);
if ($result->num_rows == 0) {//if result=0 thi create cot moi
    $sql = "ALTER TABLE brands ADD COLUMN logo VARCHAR(250)";
    if ($conn->query($sql) === true):
        echo "Đã thêm cột logo vào bảng <br>";
    else:
        echo "Error! " . $conn->error . "<br>";
    endif;
} else {
    echo "<br>";
}

?>
