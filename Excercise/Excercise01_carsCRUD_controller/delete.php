<?php
#1.ket noi controller
include './CarController.php';
$carbrand=new CarController();
#2.check form delete
if(isset($_GET['txtID'])):
    $ID=$_GET['txtID'];
    $carbrand->delete($ID);
endif;

#3.quay ve index khi thanh cong
header("Location:index.php");
exit();
?>