
//1.Hàm PHP strlen()trả về độ dài của một chuỗi.<br>
<!DOCTYPE html>
<html>
    <body>
        <div>echo strlen("Hello world!");<br></div>
        <?php
        echo strlen("Hello world!");
        echo '<br>';
        ?> 
    </body>
</html>

//2.Hàm PHP str_word_count()đếm số từ trong một chuỗi<br>
<!DOCTYPE html>
<html>
    <body>
        <div>echo str_word_count("Hello world!");</div><br>
        <?php
        echo str_word_count("Hello world!");
        echo '<br>';
        ?> 
    </body>
</html>

//3.Hàm này strtoupper()trả về chuỗi ký tự viết hoa:
<!DOCTYPE html>
<html>
    <body>
        <div>$x = "Hello World!";
        echo strtoupper($x);</div>
        <?php
        $x = "Hello World!";
        echo strtoupper($x);
        ?> 
    </body>
</html>

//4.Hàm này strtolower()trả về chuỗi ở dạng chữ thường
//5.Để chèn các ký tự không hợp lệ vào chuỗi, hãy sử dụng ký tự thoát
    Ký tự thoát là dấu gạch chéo \ ngược theo sau là ký tự bạn muốn chèn.
    \'	Single Quote
    \'	Single Quote
    \$	PHP variables
    \n	New Line
    \r	Carriage Return
    \t	Tab
    \f	Form Feed
    \ooo	Octal value
    \xhh	Hex value