<?php
/*Bài tập yêu cầu bạn làm việc với MySQL và PHP để:
    -Tạo database tên PhoneBrandDB
    -Tạo bảng PhoneBrand với các trường như mô tả
    -Chèn ít nhất 2 bản ghi vào bảng đó*/
    $servername = "localhost";//server MySQL đang chạy trên máy cục bộ.
    $username   = "root";
    $password   = "";
    $dbname     = "PhoneBrandDB";
//--------------
    //Tạo một kết nối mới đến MySQL server nhưng chưa chỉ định database.
    $conn = new mysqli($servername, $username, $password);
    //Kiểm tra xem kết nối có thành công không. Nếu thất bại, dừng chương trình và in lỗi.
    if($conn->connect_error):
        die("Error!".$conn->connect_error);
    endif;
    //Tạo câu lệnh SQL để tạo database nếu nó chưa tồn tại.
    $sql="create database if not exists PhoneBrandDB";
    //Kiểm tra xem tạo database có thành công không. Nếu thất bại thì in lỗi.
    if ($conn->query($sql) === TRUE) {
        echo "";
    } else {
        die("Error!" . $conn->error);
    }
    //Đóng kết nối cũ để chuẩn bị kết nối lại, lần này có chỉ định rõ database
    $conn->close();
//---------------
    //Tạo lại kết nối, lần này đã chỉ định database.
    $conn=new mysqli($servername, $username, $password,$dbname);
    //Kiểm tra xem kết nối có thành công không. Nếu thất bại, dừng chương trình và in lỗi.
    if($conn->connect_error):
        die("Connection to database $dbname that post!<br>".$conn->connect_error);
    endif;
    echo"";
//---------------
    //create table
    $sql="create table if not exists PhoneBrand(
        PBID int auto_increment primary key,
        Name varchar(50) not null,
        Country varchar(250) not null,
        Logo varchar(200) not null
    )";
    //Kiểm tra xem tạo database có thành công không. Nếu thất bại thì in lỗi.
    if ($conn->query($sql) === true):
            echo "";
        else:
            echo "Error! " . $conn->error . "<br>";
        endif;
//---------------
    $result = $conn->query("SELECT COUNT(*) AS count FROM PhoneBrand");
    $row = $result->fetch_assoc();
    $count = $row['count'];

    if ($count == 0):
        $conn->query("insert into PhoneBrand(Name, Country, Logo)
                    values
                    ('Samsung','South Korea','png'),
                    ('Apple','USA','png')
        ");
    endif;
//---------------
    //create table user login
    $sql="create table if not exists Users(
        UID int auto_increment primary key,
        Username varchar(50) not null,
        Password varchar(50) not null
    )";
    //Kiểm tra xem tạo database có thành công không. Nếu thất bại thì in lỗi.
    if ($conn->query($sql) === true):
            echo "";
        else:
            echo "Error! " . $conn->error . "<br>";
        endif;
?>