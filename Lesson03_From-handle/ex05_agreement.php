    <!-- Tổng quan chức năng
    Hiển thị form đăng ký với checkbox "agreement" (thỏa thuận).
    Khi nhấn "register", kiểm tra nếu checkbox được chọn:
        Nếu chưa chọn → Hiển thị thông báo yêu cầu tick vào checkbox.
        Nếu đã chọn → Hiển thị alert "giỏi" bằng JavaScript.-->

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>register from</title>
        <style>
    body {
        font-family: Arial, sans-serif;
        text-align: center;
    }
    h1, h3 {
        color: red;
    }
    form {
        display: inline-block;
        text-align: left;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
        </style>

    </head>
    <body>
        <!--đoạn code php-->
        <?php
            #1. Xử lý khi nút submit chưa bấm
            if(!isset($_POST['btn'])):
        ?>
        <!--đoạn code php-->
        
            <!--đoạn code html-->
            <h1>Register from</h1>
            <form method="post">
                <input type="checkbox" name='check'/> agreement (thỏa thuận)
                <hr>
                <input type="submit" name='btn' value='register'/> agreement
            </form>
            <!--đoạn code html-->
            
        <!--đoạn code php-->
        <?php
            #2. Xử lý khi submit đã bấm
            else:
                #2.1 xử lý khi checkbox chưa chọn
                if(!isset($_POST['check'])):
        ?>
        <!--đoạn code php-->
        
            <!--đoạn code html-->
            <h3>tick vào check box, làm ơn !</h3>
            <a href="./ex05_agreement.php">back</a> <!--Hiển thị nút "back" (<a>), quay lại trang form (ex05_agreement.php).-->
            <!--đoạn code html-->
            
        <!--đoạn code php-->
        <?php
                #2.2 xử lý khi checkbox đã chọn
                else:
                    echo '<script>alert("giỏi")</script>';
                endif;//end 2.1
            endif;
        ?>
        <!--đoạn code php-->
    </body>
</html>
