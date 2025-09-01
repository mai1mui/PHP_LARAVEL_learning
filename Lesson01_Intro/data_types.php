//1.từ khóa (ví dụ if: , else, while, echo, v.v.), lớp, hàm và hàm do người dùng định nghĩa không phân biệt chữ hoa chữ thường.<br>
    <!DOCTYPE html>
    <html>
    <body>

    <?php
    ECHO "Hello World!<br>";
    echo "Hello World!<br>";
    EcHo "Hello World!<br>";
    ?> 

    </body>
    </html>
//2.Lưu ý: Tuy nhiên, tất cả tên biến đều phân biệt chữ hoa chữ thường<br>
    <!DOCTYPE html>
    <html>
    <body>

    <?php
    $color = "red";
    echo "My car is " . $color . "<br>";
    echo "My house is " . $COLOR . "<br>";//Warning: Undefined variable $COLOR in C:\PHPL\www\data_types.php on line 23
    echo "My boat is " . $coLOR . "<br>";//Warning: Undefined variable $COLOR in C:\PHPL\www\data_types.php on line 23
    ?> 

    </body>
    </html>
