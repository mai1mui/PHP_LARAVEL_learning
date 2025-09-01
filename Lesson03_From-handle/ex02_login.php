<?php
   /*giải thích:
    <?php --Dùng để mở và đóng khối mã PHP trong tệp HTML hoặc PHP
        if(isset($_GET['msgErr'])):--$_GET: Là một mảng superglobal trong PHP, chứa các dữ liệu được gửi đến trang bằng phương thức GET.
                                     $_GET['msgErr']: Lấy giá trị của tham số msgErr từ URL.
                                     isset($_GET['msgErr']): Kiểm tra xem msgErr có tồn tại trong $_GET không.-> result: true / false
                                        
            echo $_GET['msgErr'];--Khi điều kiện isset($_GET['msgErr']) là TRUE, PHP sẽ in ra giá trị của msgErr.
        endif;--Thay vì dùng dấu {} để đóng khối if, PHP cung cấp cú pháp endif;.
                Cách này hữu ích khi viết PHP trong HTML, giúp mã nguồn dễ đọc hơn.
    ?>
   */ 
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <style>
            .notification{
                height: 150px;
                padding: 10px;
            }
        </style>
    </head>
    <body>
        <div class="notification">
            <?php
                if(isset($_GET['msgErr'])):
                    echo $_GET['msgErr'];
                endif;
            ?>
        </div>
            
        <h1>Login</h1>
        <form action="./zLogin.php" method="get">
            <table>
                <tr>
                    <td>Login name: </td>
                    <td>
                        <input name="txtName" placeholder="enter name" autofocus>
                    </td>
                </tr>
                <tr>
                    <td>Password: </td>
                    <td>
                        <input type='password' name="txtPassword" placeholder="enter password">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type='submit' value="login"/></td>
                </tr>
            </table>
        </form>
    </body>
</html>
