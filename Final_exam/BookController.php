<?php
#tao lop
class BookController{
    #1.ket noi database
    private $server = "localhost";
    private $account = "root";
    private $password = "";
    private $database = "LibDB";
    private $conn;
    #2.khai bao ham __construct()
    public function __construct() {
        $this->conn=new mysqli($this->server, $this->account, $this->password, $this->database);
        #thong bao khi ket noi loi
        if($this->conn->connect_error):
        trigger_error("Connection failed:".$this->conn->connect_error);
        endif;
    }
    #3.read() methods
    public function read(){
        #tao truy van sql
        $query="select * from tbBook";
        #dung phuong thuc query() thuc thi cau lenh sql. ket qua tra ve la true hay false thi gan gia tri vao bien rs
        $rs=$this->conn->query($query);
        #check bien rs=true va so dong >0
        if($rs && $rs->num_rows>0):
            #khai bao mang ten data
            $data=[];
            while($row = $rs->fetch_array(MYSQLI_ASSOC)):
                $data[]=$row;
            endwhile;
            return $data;
        else:
            echo "Record not found";
            
        endif;
    }
    #4.create() methods
    public function create() {
        if(isset($_POST['txtTitle'])):
            $title=$_POST['txtTitle'];
            $author=$_POST['txtAuthor'];
            $publisher=$_POST['txtPublisher'];
            $page=$_POST['txtPage'];
            #tao truy van sql de them thong tin user nhap vao database
                $query="insert into tbBook (Title, Author,Publisher,Page) values ('$title','$author','$publisher','$page')";
            #dung methods query() thuc thi cau lenh sql. ket qua true or false thi luu vao bien rs
            $rs=$this->conn->query($query);
            #kiem tra lenh sql thanh cong hay khong
            if($rs):
            header("Location:index.php");
            exit();
            else:
                echo "Nothing to save";
            endif;
        endif;
    }
    #5.update() methods
    #6.filter() methods
    #7.delete() methods
    public function delete($id){
        #tao truy van sql
            $query="delete from tbBook where id='$id'";
            #dung methods query() thuc thi cau lenh sql. ket qua true or false thi luu vao bien rs
            $rs=$this->conn->query($query);
            #kiem tra lenh sql thanh cong hay khong
            if($rs):
            header("Location:index.php");
            exit();
            else:
                echo "Nothing to save";
            endif;
    }
    #8.search() methods
    #9.visitor() methods
    #10.download() methods
    #11.close database
    function __destruct() {
        $this->conn->close();
    }
}
?>