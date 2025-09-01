<?php 
    //1. check submit 
       if(isset($_POST['btn'])):
           echo '<script>alert("Tested")</script>';
       endif;
    
    //2 check from
       if($_SERVER['REQUEST_METHOD'] == 'POST'):
           echo '<script>alert("Tested")</script>';
       endif;
  /*giải thích chi tiết: 
    //1. Kiểm tra khi form được submit 
       if(isset($_POST['btn'])):--$_POST: Là một mảng superglobal chứa dữ liệu được gửi từ form bằng phương thức POST
                                  isset($_POST['btn']):Kiểm tra xem có tồn tại key "btn" trong $_POST không.
                                  "btn" là name của button trong form.
           echo '<script>alert("Tested")</script>';--Nếu isset($_POST['btn']) là TRUE, xuất mã JavaScript để hiển thị alert "Tested" trên trình duyệt
       endif;
    
    //2. Kiểm tra phương thức gửi dữ liệu
       if($_SERVER['REQUEST_METHOD'] == 'POST'):--$_SERVER['REQUEST_METHOD']:Lấy phương thức HTTP của request (ví dụ: GET, POST).                                       
           echo '<script>alert("Tested")</script>';--Nếu điều kiện đúng, hiển thị alert "Tested"
       endif;
     * Lưu ý: Không phụ thuộc vào name của button, chỉ cần form gửi POST thì alert sẽ hiển thị
    
  */
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Post</title>
    </head>
    <body>
        <form method="post">
            Click to test:
            <input type="submit" value="click_test" name="btn"/>
        </form>
    </body>
</html>
