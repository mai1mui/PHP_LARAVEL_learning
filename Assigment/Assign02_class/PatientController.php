<?php

# class PatientController
class PatientController {
    #1. Properties
    private $server = "localhost";
    private $account = "root";
    private $password = "";
    private $database = "PatientDB";
    #2. Constructor
    public function __construct() {
        $this->conn = new mysqli($this->server, $this->account, $this->password, $this->database);
        if ($this->conn->connect_error) {
            trigger_error("Connection failed: " . $this->conn->connect_error);
        }
    }
    #3. read() method
    public function read() {
        $query = "select * from Patient";
        $rs = $this->conn->query($query);

        if ($rs && $rs->num_rows > 0) {
            $data = [];
            while ($row = $rs->fetch_array()) {
                $data[] = $row;
            }
            return $data;
        } else {
            echo 'Record not found';
            return [];
        }
    }
    #4. create() method
    public function create() {//Đây là cách khai báo một phương thức công khai (public method) có tên là create. Phương thức này có thể được gọi từ bên ngoài lớp.
        if (isset($_POST['txtID']) && isset($_POST['txtName'])) {
            $id = $_POST['txtID'];//Gán giá trị người dùng nhập vào trường txtID cho biến $id.
            $name = real_escape_string($_POST['txtName']);//Hàm thêm ký tự gạch chéo (\) trước các ký tự đặc biệt như ', ", \, hoặc NULL để tránh lỗi cú pháp SQL. Kết quả được gán vào $name.
            $country = $this->conn->real_escape_string($_POST['txtCountry']);//Hàm real_escape_string() (thuộc đối tượng $this->conn, tức là kết nối CSDL) thoát các ký tự đặc biệt để chống SQL Injection. Kết quả được gán vào $country.
            $mail = real_escape_string($_POST['txtMail']);//Dữ liệu email cũng được xử lý bằng ham và lưu vào biến $mail.
            
            $query = "insert into Patient (PatientID, PatientName, Country, Email) values ('$id', '$name', '$country', '$mail')";
            //Tạo chuỗi truy vấn SQL để thêm bản ghi mới vào bảng Patient với các giá trị người dùng đã nhập.
            $rs = $this->conn->query($query);//Dùng phương thức query() để thực thi câu SQL. Kết quả trả về (thành công hay thất bại) sẽ lưu trong biến $rs.
            #Kiểm tra nếu lệnh SQL chạy thành công (biến $rs là true) thì thực hiện khối lệnh bên trong.
            if ($rs) {
                header("Location: index.php");
                exit();//Kết thúc việc thực thi đoạn script ngay lập tức để tránh chạy các dòng lệnh tiếp theo.
            } else {//Nếu truy vấn SQL thất bại, hiển thị dòng thông báo "Nothing to save" (Không có gì để lưu).
                echo 'Nothing to save';
            }
        }
    }
    #5. update() method
    public function update() {
        if (isset($_POST['txtID'])) {
            $id = $_POST['txtID'];
            $name = addslashes($_POST['txtName']);
            $country = $this->conn->real_escape_string($_POST['txtCountry']);
            $mail = addslashes($_POST['txtMail']);
            $query = "update Patient set PatientName='$name', Country='$country', Email='$mail' where PatientID='$id'";
            $rs = $this->conn->query($query);
            if ($rs) {
                header("Location: index.php");
                exit();
            } else {
                echo 'Nothing to save';
            }
        }
    }
    #6. delete() method
    public function delete($id) {
        $query = "delete from Patient where PatientID='$id'";
        $rs = $this->conn->query($query);
        if ($rs) {
            header("Location: index.php");
            exit();
        } else {
            echo "Delete failed";
        }
    }

    #7. filter() method
    public function filter($id) {
        $query = "SELECT * FROM Patient WHERE PatientID='$id'";
        $rs = $this->conn->query($query);
        if ($rs && $rs->num_rows > 0) {
            $data = $rs->fetch_array(MYSQLI_ASSOC);
            return $data;
        } else {
            echo "Record not found";
        }
    }
}
?>
