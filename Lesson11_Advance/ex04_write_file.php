<?php
$file = fopen("./file/txt.txt", "w");
/*Mở file example.txt với chế độ ghi.
 * Nếu file không tồn tại, PHP sẽ tạo file mới.
 * Chế độ "w" có thể thay đổi, ví dụ "a" (append) để thêm nội dung vào cuối file mà không ghi đè.*/

if ($file) {
    fwrite($file, "Hello, this is a test!");  // Ghi dữ liệu vào file
    fclose($file);  // Đóng file sau khi ghi xong
    echo "Dữ liệu đã được ghi vào file.";
} else {
    echo "Không thể mở file.";
}
?>
