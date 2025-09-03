<?php

//tao lop
class TimetableController {

    //1.
    private $server = "localhost";
    private $account = "root";
    private $password = "";
    private $database = "timetable";

    //2.ham __construct()
    function __construct() {//khai bao ham
        $this->conn = new mysqli(
                $this->server,
                $this->account,
                $this->password,
                $this->database
        );
        //check error ket noi
        if ($this->conn->connect_error):
            trigger_error("Error: " . $this->conn->connect_error);
        endif;
    }

    //read() methods
    function read() {//khai bao ham
        $query = "select * from timetable";
        $rs = $this->conn->query($query);//thực thi truy vấn sql bằng cách gọi hàm query() từ đối tượng kết nối $this->conn
                                          //kết quả được lưu trong biến $rs (result set)
        //check error
        if($rs && $rs->num_rows >0)://$rs: k phải 0-tức là thành công
                                        //$rs->num_rows >0: có ít nhất 1 dòng kết quả trong bảng
            $data=[];//tạo 1 mảng rống để lưu trữ các bản ghi đọc được từ kết quả truy vấn
            //tạo 1 vòng lặp để hiển thị kết quả
            while($row=$rs->fetch_array())://mỗi dòng dữ liệu được lấy ra từ phương thức fetch_aray() và ghi vào biến $row
                $data[]=$row;//thêm từng dòng vào mảng $data
            endwhile;
            return $data;//sau khi đọc xong dữ liệu, trả về toàn bộ mảng $data được ghi
            else://ngược lại k có bản ghi nào, thông báo và trả về mảng rống
                echo 'Not found';
                return [];
        endif;
    }
    //create() methods
    //update() methods
    //delete() methods
}

?>