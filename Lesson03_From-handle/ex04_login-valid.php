<?php
    //1. khai báo biến
        $name = $errName = '';  //$name: Lưu tên đăng nhập người dùng nhập vào
                                //$errName: Lưu thông báo lỗi nếu trường tên bị bỏ trống.
        $pass = $errPass = '';  //$pass: Lưu mật khẩu người dùng nhập vào.
                                //$errPass: Lưu thông báo lỗi nếu trường mật khẩu bị bỏ trống.
        
    //2. kiểm tra method post
        if($_SERVER['REQUEST_METHOD'] == 'POST'):
            //Kiểm tra và xử lý tên đăng nhập
            $name = $_POST['txtName'];  //$_POST['txtName']: Lấy dữ liệu từ input txtName.
            if(empty($name)):   //empty($name) kiểm tra nếu người dùng để trống ô nhập:
                $errName = 'cannot blank';    //Nếu trống → Gán errName = 'cannot blank'..
                                            //Nếu có dữ liệu → Giữ nguyên $name.
            endif;
            //Kiểm tra và xử lý mật khẩu
            $pass = $_POST['txtPassword'];  //$_POST['txtPassword']: Lấy dữ liệu từ input txtPassword.
            if(empty($pass)):   //empty($pass) kiểm tra nếu mật khẩu bị bỏ trống:
                $errPass = 'cant blank';    //Nếu trống → Gán $errPass = 'cant blank'.
                                            //Nếu có dữ liệu → Giữ nguyên $pass.
            endif;
        endif; //end post
        
    //3. hiển thị:
        if(isset($_POST['btn'])):
            if((empty($errName)) && empty($errPass)):
                echo '<script>alert("true")</script>';
            endif;
        endif;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>Login</h1>
        <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>"> <!--Dùng $_SERVER['PHP_SELF'] để gửi dữ liệu đến chính trang này.
                                                                                         Dùng htmlspecialchars() để tránh tấn công XSS (Cross-Site Scripting).-->
            <table>
                <tr>
                    <td>Login name: </td>
                    <td>
                        <input name="txtName" placeholder="enter name" autofocus>
                    </td>
                    <td>
                        <span style="color:red"><?= $errName?></span><!--Hiển thị thông báo lỗi nếu tên bị trống.-->
                    </td>
                </tr>
                <tr>
                    <td>Password: </td>
                    <td>
                        <input type='password' name="txtPassword" placeholder="enter password">
                    </td>
                    <td>
                        <span style="color:red"><?= $errPass?></span><!--Hiển thị lỗi nếu mật khẩu trống..-->
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type='submit' name="btn" value="login"/></td>
                </tr>
            </table>
        </form>
    </body>
</html>
