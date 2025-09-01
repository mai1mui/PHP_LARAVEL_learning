<?php
/*Cookie thường được sử dụng để nhận dạng người dùng.
 Cookie là một tệp nhỏ mà máy chủ nhúng vào máy tính của người dùng.
 Mỗi lần cùng một máy tính yêu cầu một trang bằng trình duyệt, nó cũng sẽ gửi cookie.
 Với PHP, bạn có thể tạo và truy xuất giá trị cookie.*/
$cookie_user ='username';
$cookie_visits ='visit_count';
$default_user ='Guest';
if(isset($_COOKIE[$cookie_user])) {
    $username = $_COOKIE[$cookie_user];
}
else {
    $username = $default_user;
    //lưu tên mặc định cookie với 3600s
        //Syntax: setcookie(name, value, expire(hết hạn), path(đường dẫn), domain(tên miền), secure(bảo mật), httponly);
        //Lưu ý: Hàm setcookie()phải xuất hiện TRƯỚC thẻ <html>
    setcookie(name: $cookie_user, value: $username, expires_or_options: time() + 3600, path: "/");
}
//kiểm tra số lượng truy cập
if(isset($_COOKIE[$cookie_visits])) {
    $visit_count = $_COOKIE[$cookie_visits] + 1;
}
else {
    $visit_count =1;
}
//cập nhật số lần truy cập
setcookie(name: $cookie_visits, value: $visit_count, expires_or_options: time() + 3600, path: "/");

//reset cookie-Để xóa cookie, hãy sử dụng setcookie()hàm có ngày hết hạn trong quá khứ
if(isset($_GET["reset"])) {
    setcookie(name: $cookie_user, value: "", expires_or_options: time() - 3600, path: "/");
    setcookie(name: $cookie_visits, value: "", expires_or_options: time() - 3600, path: "/");
    header(header: "Location: ".$_SERVER['PHP_SELF']);
}
echo 'chào bạn '.$username.". Bạn đã tuy cập lần thứ ".$visit_count;
echo '<br>';
echo "<a href ='?reset=true'>reset cookie</a>";
?>
