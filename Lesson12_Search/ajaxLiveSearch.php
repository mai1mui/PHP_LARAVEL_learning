<?php
    #1. Kết nối CSDL
    include_once '../../session04_CRUD/zDBConnect.php';
    $return = '';
    #2. Thực hiện query
    if(isset($_POST["query"])){
        $search = mysqli_real_escape_string($conn, $_POST["query"]);
        $query = "select * from item where "
                . "Code like '%{$search}%' or "
                . "Name like '%{$search}%' or "
                . "Price like '%{$search}%'";      
    }
    else{
            $query = "select * from item";
    }
    $rs = mysqli_query($conn, $query);
    if(mysqli_num_rows($rs) > 0){
            $return .='
            <table class="table table bordered">
            <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Price</th>
            </tr>';
            while($data = mysqli_fetch_array($rs)){
                    $return .= '
                    <tr>
                    <td>'.$data[0].'</td>
                    <td>'.$data[1].'</td>
                    <td>'.$data[2].'</td>
                    </tr>';
            }
            echo $return;
            echo '</table>';
    }
    else{
            echo 'Record not found!';
    }
    