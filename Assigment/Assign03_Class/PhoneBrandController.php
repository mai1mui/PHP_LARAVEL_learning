<?php

class PhonebrandController {
    #1. ket noi database

    private $server = 'localhost';
    private $account = 'root';
    private $password = '';
    private $database = 'phonebranddb';
    private $conn;
    #2.ham __construct()

    function __construct() {//khai bao ham
        $this->conn = new mysqli(
                $this->server,
                $this->account,
                $this->password,
                $this->database
        );
        #check error ket noi
        if ($this->conn->connect_error):
            trigger_error("Error: " . $this->conn->connect_error);
        endif;
    }

    #3.read() methods

    public function read() {//khai bao ham
        $query = "select * from phonebrand";
        $rs = $this->conn->query($query); //thực thi truy vấn sql bằng cách gọi hàm query() từ đối tượng kết nối $this->conn
                                          //kết quả được lưu trong biến $rs (result set)
        #check error
        if ($rs && $rs->num_rows > 0)://$rs: k phải 0-tức là thành công
                                      //$rs->num_rows >0: có ít nhất 1 dòng kết quả trong bảng
            $data = []; //tạo 1 mảng rống để lưu trữ các bản ghi đọc được từ kết quả truy vấn
            #tạo 1 vòng lặp để hiển thị kết quả
            while ($row = $rs->fetch_array())://mỗi dòng dữ liệu được lấy ra từ phương thức fetch_aray() và ghi vào biến $row
                $data[] = $row; //thêm từng dòng vào mảng $data
            endwhile;
            return $data; //sau khi đọc xong dữ liệu, trả về toàn bộ mảng $data được ghi
        else://ngược lại k có bản ghi nào, thông báo và trả về mảng rống
            echo 'Not found';
            return [];
        endif;
    }

    #4.create() methods
    public function create() {
        # Kiểm tra các field bắt buộc
        if (isset($_POST['txtName']) && isset($_POST['txtCountry'])) {
            $name = $_POST['txtName'];
            $country = $_POST['txtCountry'];
            $logo = '';

            # Xử lý upload file logo
            if (isset($_FILES['txtLogo']) && $_FILES['txtLogo']['error'] === 0) {
                $logo = basename($_FILES['txtLogo']['name']);
                move_uploaded_file($_FILES['txtLogo']['tmp_name'], 'uploads/' . $logo);
            }

            # Chèn dữ liệu vào CSDL
            $query = "INSERT INTO phonebrand (Name, Country, Logo) VALUES ('$name', '$country', '$logo')";
            #thuc hien truy van du lieu bang phuong thuc query sau do gan gia tri vao bien rs
            $rs = $this->conn->query($query);
            #check bien rs=true thi chuyen sang Index.php, cofn false thi bao loi
            if ($rs) {
                header("Location: Index.php");
                exit();
            } else {
                echo 'Nothing to save!';
            }
        } else {
            echo 'Missing required fields!';
        }
    }

    #5.update() methods
    public function update() {
        // 1. Lấy dữ liệu từ form
        $code = $_POST['txtCode'];
        $name = $_POST['txtName'];
        $country = $_POST['txtCountry'];

        // 2. Xử lý upload logo (nếu có)
        if (isset($_FILES['txtLogo']) && $_FILES['txtLogo']['error'] === 0) {
            $logoName = basename($_FILES['txtLogo']['name']);
            $uploadPath = 'uploads/' . $logoName;
            move_uploaded_file($_FILES['txtLogo']['tmp_name'], $uploadPath);
        } else {
            // Nếu không chọn logo mới, giữ logo cũ
            $logoName = $_POST['oldLogo'];
        }

        // 3. Cập nhật dữ liệu vào database
        $sql = "UPDATE phonebrand SET Name=?, Country=?, Logo=? WHERE PBID=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$name, $country, $logoName, $code]);

        // 4. Chuyển hướng về danh sách
        header('Location: Index.php');
        exit();
    }

    #6.delete() methods

    public function delete($code) {
        $query = "delete from phonebrand where PBID='$code'";
        #thuc hien truy van sql bang phuong thuc query, sau do gan ket qua vao bien rs
        $rs = $this->conn->query($query);
        #check bien rs-true thi quay ve trang Index.php, con false thi bao loi
        if ($rs):
            header("location:Index.php");
            exit();
        else:
            echo'delete failed!';
        endif;
    }
    #7.filter() methods
    public function filter($code) {
        #truy van sql
        $query="select * from phonebrand where PBID='$code'";
        #thuc hien truy van bang phuong thuc query sau do gan ket qua vao bien rs
        $rs=$this->conn->query($query);
        #check err: rs=true hoac num_rows >0: co it nhat 1 dong trong bang
        if($rs && $rs->num_rows>0):
            $data=$rs->fetch_assoc();
            return $data;
            else:
                echo 'record not found';
        endif;
    }
    #8.search() methods
    public function search($keyword) {
        #truy van su dung LIKE de tim kiem
        $query="select * from phonebrand where Name LIKE '%$keyword%' or Country LIKE '%$keyword%'";
        #thuc thi truy van bang phuong thuc query(), sau do gan ket qua vao bien rs
        $rs=$this->conn->query($query);
        #check err: rs=true va num_rows>0 thi tra ket qua, false thi bao loi
        if($rs && $rs->num_rows>0):
            $result = []; // khai báo mảng kết quả
            while($row=$rs->fetch_assoc()):
                $result[]=$row;
            endwhile;
            return $result;
            else:
                echo 'not found!';
        endif;
    }
    #9.visitor() methods
    private $file='counter.txt';
    #dem so luot truy cap
    public function visitor(){
        if(!file_exists($this->file)):
        file_put_contents($this->file,"0");
        endif;
        $count=(int) file_get_contents($this->file);
        $count++;
        file_put_contents($this->file, $count);
        return $count;
    }
    #lay so luot truy cap hien tai
    public function getVisitor() {
        if(!file_exists($this->file)):
            return 0;
        endif;
        return (int) file_get_contents($this->file);
    }
    #10. dong ket noi database
    function __destruct() {
        $this->conn->close();
    }
}

?>