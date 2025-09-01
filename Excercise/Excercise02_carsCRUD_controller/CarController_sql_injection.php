<?php

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
    if (isset($_POST['txtID']) && isset($_POST['txtName'])) {
        $stmt = $this->conn->prepare("INSERT INTO carbrand (ID, Name, Brand, Model, Year, Price) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $_POST['txtID'], $_POST['txtName'], $_POST['txtBrand'], $_POST['txtModel'], $_POST['txtYear'], $_POST['txtPrice']);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Nothing to save";
        }
    }
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
    $stmt = $this->conn->prepare("SELECT * FROM carbrand WHERE ID = ?");
    $stmt->bind_param("s", $ID); // dùng "s" vì ID là chuỗi
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_array(MYSQLI_ASSOC);
    } else {
        echo "Record not found";
    }
}


    #7.delete() methods

   public function delete($ID) {
    $stmt = $this->conn->prepare("DELETE FROM carbrand WHERE ID = ?");
    $stmt->bind_param("s", $ID);
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo 'Delete failed';
    }
}


    #8.search() methods

    public function search($keyword) {
        $keyword = "%{$keyword}%"; // thêm wildcard
        $stmt = $this->conn->prepare("SELECT * FROM carbrand WHERE Name LIKE ? OR Brand LIKE ? OR Model LIKE ? OR Year LIKE ? OR Price LIKE ?");
        $stmt->bind_param("sssss", $keyword, $keyword, $keyword, $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
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
    #11.close database: tiet kiem bo nho

    function __destruct() {
        $this->conn->close();
    }
}

?>