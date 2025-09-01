<?php
    #1. đọc data từ from
    $name = $_GET['txtName'];
    $pass = $_GET['txtPassword'];

    #2. kiểm tra tính hợp lệ
    if(($name !== 'admin') || ($pass != '123')):
        header('location: ex02_login.php?msgErr=invalid user or pass');
    else:
        echo 'welcome to page';
    endif;