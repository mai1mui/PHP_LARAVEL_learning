<?php

require_once '../fpdf186/fpdf.php'; // Đường dẫn đến thư viện FPDF
#tao lop CarController

class CarController {
    #1.ket noi database

    private $server = "localhost";
    private $account = "root";
    private $password = "";
    private $database = "carsDB";
    private $conn;

    #2.khai bao ham __constructor()

    public function __construct() {
        $this->conn = new mysqli($this->server, $this->account, $this->password, $this->database);
        #thong bao khi ket noi loi
        if ($this->conn->connect_error):
            trigger_error("Connection failed:" . $this->conn->connect_error);
        endif;
    }

    #3.read()methods

    public function read() {
        #tao truy van sql
        $query = "select * from carbrand";
        #dung methods query() thuc thi cau lenh sql. ket qua true or false thi gan gia tri vao bien rs
        $rs = $this->conn->query($query);
        #check bien rs =true va so dong>0
        if ($rs && $rs->num_rows > 0):
            #khai bao mang ten data
            $data = [];
            while ($row = $rs->fetch_array()):
                $data[] = $row;
            endwhile;
            return $data;
        else:
            echo 'Record not found';
        endif;
    }

    #4.create(0 methods

    public function create() {
        if (isset($_POST['txtID']) && isset($_POST['txtName'])):
            $ID = $_POST['txtID'];
            $Name = $_POST['txtName'];
            $Brand = $_POST['txtBrand'];
            $Model = $_POST['txtModel'];
            $Year = $_POST['txtYear'];
            $Price = $_POST['txtPrice'];
            #tao chuoi truy van sql de them ban ghi moi vao index.php
            $query = "insert into carbrand (ID,Name,Brand,Model,Year,Price) values ('$ID','$Name','$Brand','$Model','$Year','$Price')";
            #dung methods query() thuc thi cau lenh sql. ket qua true or false thi gan gia tri vao bien rs
            $rs = $this->conn->query($query);
            #kiem tra lenh sql chay thanh cong khong?
            if ($rs):
                header("Location:index.php");
                exit();
            else:
                echo "Nothing to save";
            endif;
        endif;
    }

    #5.update() methods

    public function update() {
        if (isset($_POST['txtID'])):
            $ID = $_POST['txtID'];
            $Name = $_POST['txtName'];
            $Brand = $_POST['txtBrand'];
            $Model = $_POST['txtModel'];
            $Year = $_POST['txtYear'];
            $Price = $_POST['txtPrice'];
            #tao chuoi truy van sql de them ban ghi moi vao index.php
            $query = "update carbrand set Name='$Name',Brand='$Brand',Model='$Model',Year='$Year',Price='$Price' where ID='$ID'";
            #dung methods query() thuc thi cau lenh sql. ket qua true or false thi gan gia tri vao bien rs
            $rs = $this->conn->query($query);
            #kiem tra lenh sql chay thanh cong khong?
            if ($rs):
                header("Location:index.php");
                exit();
            else:
                echo "Nothing to save";
            endif;
        endif;
    }

    #6.filter()methods

    public function filter($ID) {
        #tao truy van sql
        $query = "select * from carbrand where ID='$ID'";
        #dung methods query() thuc thi cau lenh sql. ket qua true or false thi gan gia tri vao bien rs
        $rs = $this->conn->query($query);
        #check bien rs =true va so dong>0
        if ($rs && $rs->num_rows > 0):
            $data = $rs->fetch_array(MYSQLI_ASSOC);
            return $data;
        else:
            echo "Record not found";
        endif;
    }

    #7.delete() methods

    public function delete($ID) {
        #tao truy van sql
        $query = "delete from carbrand where ID='$ID'";
        #dung methods query() thuc thi cau lenh sql. ket qua true or false thi gan gia tri vao bien rs
        $rs = $this->conn->query($query);
        #check bien rs =true 
        if ($rs):
            header("Location:index.php");
            exit();
        else:
            echo 'Delete failed';
        endif;
    }

    #8.search() methods

    public function search($keyword) {
        #tao truy van sql dung LIKE de tim kiem
        $query = "select *from carbrand where Name LIKE '%$keyword%' or Brand LIKE '%$keyword%' or Model LIKE '%$keyword%' or Year LIKE '%$keyword%' or Price LIKE '%$keyword%'";
        #dung methods query() thuc thi cau lenh sql. ket qua true or false thi gan gia tri vao bien rs
        $rs = $this->conn->query($query);
        #check bien rs =true va so dong>0
        if ($rs && $rs->num_rows > 0):
            #khai bao mang ten data
            $result = [];
            while ($row = $rs->fetch_assoc()):
                $result[] = $row;
            endwhile;
            return $result;
        else:
            echo 'Record not found';
        endif;
    }

    #9.visitor()methods

    private $file = 'counter.txt';

    #dem so luot truy cap

    public function visitor() {
        if (!file_exists($this->file)):
            file_put_contents($this->file, "0");
        endif;
        $count = (int) file_get_contents($this->file);
        $count++;
        file_put_contents($this->file, $count);
        return $count;
    }

    #lay so luot truy cap hien tai

    public function getVisitor() {
        if (!file_exists($this->file)):
            return 0;
        endif;
        return (int) file_get_contents($this->file);
    }

    #10.download() methods

    public function downloadPDF($ID) {
        $data = $this->filter($ID);
        if (!$data) {
            echo "Không tìm thấy dữ liệu xe.";
            return;
        }

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Tiêu đề
        $pdf->Cell(0, 10, 'Car Information', 0, 1, 'C');
        $pdf->Ln(10);

        // Nội dung chi tiết xe
        $pdf->SetFont('Arial', '', 12);
        foreach ($data as $key => $value) {
            $pdf->Cell(40, 10, ucfirst($key) . ':', 0, 0);
            $pdf->Cell(0, 10, $value, 0, 1);
        }

        // Gửi file PDF ra trình duyệt để tải về
        $pdf->Output('D', 'car_' . $ID . '.pdf');  // 'D' = download, tên file car_ID.pdf
        exit;
    }

    #11.close database: tiet kiem bo nho

    function __destruct() {
        $this->conn->close();
    }
}

?>