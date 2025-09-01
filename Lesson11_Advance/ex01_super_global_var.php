<?php
//supper global variables
    // 1. Resource (Tài nguyên đang truy cập)
    echo 'Resource (Tài nguyên đang truy cập)';
    echo '1. Resource: ' . $_SERVER['PHP_SELF'] . "<br>";//Trả về đường dẫn của chính script PHP đang chạy.

    // 2. Server name (Tên máy chủ)(domain hoặc localhost).
    echo '2. Server Name: ' . $_SERVER['SERVER_NAME'] . "<br>";

    // 3. Server name followed by port (Tên máy chủ kèm cổng)(thường là 80 hoặc 443).
    echo '3. Server Name with Port: ' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . "<br>";

    // 4. Browser (Trình duyệt người dùng)và hệ điều hành của người dùng.
    echo '4. Browser: ' . $_SERVER['HTTP_USER_AGENT'] . "<br>";

    // 5. Web page (Trả về URI của trang web mà người dùng đang truy cập).
    echo '5. Web Page: ' . $_SERVER['REQUEST_URI'] . "<br>";

?>
