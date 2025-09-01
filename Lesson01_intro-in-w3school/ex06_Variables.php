//1. Tạo (Khai báo) Biến PHP<br>
    //syntax: $ten_bien <br>
        <!DOCTYPE html>
        <html>
        <body>
        <?php
        $x = 5;
        $y = "John";
        echo $x;
        echo "<br>";
        echo $y;
        echo "<br>";
        ?>
        </body>
        </html>
        //Lưu ý: Khi bạn gán giá trị văn bản cho một biến, hãy đặt giá trị trong dấu ngoặc kép.<br>
//2.Đặt tên biến<br>
    /*Quy tắc cho biến PHP:<br>
    -bắt đầu bằng $dấu, theo sau là tên của biến<br>
    -bắt đầu bằng một chữ cái hoặc ký tự gạch dưới<br>
    -không bắt đầu bằng số<br>
    -chỉ chứa các ký tự chữ và số và dấu gạch dưới (Az, 0-9 và _)<br>
    -phân biệt chữ hoa chữ thường ( $agevà $AGElà hai biến khác nhau)*/<br>
//3.Biến đầu ra<br>
    //Câu lệnh PHP echo thường được sử dụng để xuất dữ liệu ra màn hình.<br>
        <!DOCTYPE html>
        <html>
        <body>
        <?php
        $txt = "W3Schools.com";
        echo "I love $txt!";
        echo "<br>";
        ?>
        </body>
        </html>
//4.PHP hỗ trợ các kiểu dữ liệu sau:<br>
    /*
    -String<br>
    -Integer<br>
    -Float (số dấu phẩy động - còn gọi là double)<br>
    -Boolean<br>
    -Array<br>
    -Object<br>
    -NULL<br>
    -Resource (Tài nguyên)*/<br>
//5.Variables Scope (Phạm vi biến)<br> 
    //Biến có phạm vi toàn cục
        <!DOCTYPE html>
        <html>
        <body>
        <?php
        $q = 5; // global scope
        function myTest() {
          // using x inside this function will generate an error
          echo "<p>Variable q inside function is: $q</p>";//Warning: Undefined variable $q in C:\PHPL\www\Variables.php on line 62
        } 
        myTest();
        echo "<p>Variable q outside function is: $q</p>";
        ?>
        </body>
        </html>
    //Một biến được khai báo trong một hàm có PHẠM VI LOCAL và chỉ có thể được truy cập trong hàm đó
        <!DOCTYPE html>
        <html>
        <body>
        <?php
        function myTest1() {
          $w = 5; // local scope
          echo "<p>Variable x inside function is: $w</p>";
        } 
        myTest1();
        // using x outside the function will generate an error
        echo "<p>Variable x outside function is: $w</p>";
        ?>
        </body>
        </html>
    //globalkhóa được sử dụng để truy cập biến toàn cục từ bên trong một hàm.
        <!DOCTYPE html>
        <html>
        <body>
        <?php
        $a = 5;
        $s = 10;
        function myTest3() {
          global $a, $s;
          $s = $a + $s;
        } 
        myTest3();  // run function
        echo $s; // output the new value for variable $s
        ?>
        </body>
        </html>
        