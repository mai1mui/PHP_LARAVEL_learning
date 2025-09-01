1.Cú pháp
    switch (expression) {
        case label1:
            //code block
            break;
        case label2:
            //code block;
            break;
        case label3:
            //code block
            break;
        default:
            //code block
    }
    Nó hoạt động như thế này:
    - Biểu thức được đánh giá một lần
    - Giá trị của biểu thức được so sánh với giá trị của từng trường hợp
    - Nếu có sự khớp nhau, khối mã liên quan sẽ được thực thi
    - Từ breakkhóa thoát ra khỏi khối chuyển đổi
    - Khối defaultmã được thực thi nếu không có sự khớp
    <br>
    <?php
    $favcolor = "red";

    switch ($favcolor) {
        case "red":
            echo "Your favorite color is red!";
            break;
        case "blue":
            echo "Your favorite color is blue!";
            break;
        case "green":
            echo "Your favorite color is green!";
            break;
        default:
            echo "Your favorite color is neither red, blue, nor green!";
    }
    ?>

