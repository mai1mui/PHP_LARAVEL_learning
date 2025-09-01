<?php
#xử lý xoá brand trong hệ thống
$serverName = "localhost";//server MySQL đang chạy trên máy cục bộ.
$username = "root";
$password = "";
$dbName = "branddb";
//-----------
    //connect data
    $conn = new mysqli($serverName, $username, $password, $dbName);
    //Kiểm tra xem kết nối có thành công không. Nếu thất bại, dừng chương trình và in lỗi.
    if ($conn->connect_error):
        die("ket noi den database $dbName that bai" . $conn->connect_error);
    endif;
//-----------
//xu ly delete information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete']))://Kiểm tra nếu phương thức là POST và có nút delete được gửi (tránh truy cập trực tiếp).
    //phần xử lý xóa dữ liệu từ CSDL (MySQL)
    $id = $_POST['id'];// Lấy giá trị id được gửi từ form (method POST)
                       //Đây là ID của brand cần xóa,
    $stmt = $conn->prepare("delete from brands where id=?");//Dùng prepared statement để tạo câu lệnh SQL với tham số (?) thay vì gắn trực tiếp.
                                                            //Mục tiêu là để tránh SQL injection (một kiểu tấn công nguy hiểm).
                                                            //nghĩa là:“Xoá dòng nào trong bảng brands mà id bằng với giá trị truyền vào”
    $stmt->bind_param("i", $id);//"i" là kiểu dữ liệu int cho $id.
                                //Nếu là chuỗi thì dùng "s", nếu có nhiều biến thì "is" (tức là một số + một chuỗi)...
    $stmt->execute();//Thực thi câu lệnh SQL sau khi đã gắn đủ giá trị vào.
    $stmt->close();//Đóng statement để giải phóng tài nguyên sau khi thực hiện xong.
endif;

//close connect and come back management.php
$conn->close();
header("location:Management.php");
exit();//Dùng exit() để chắc chắn dừng kịch bản tại đây
?>