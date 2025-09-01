<?php
#1.ket noi controller
include './FilmController.php';
$film=new FilmController();
#2.check form dalete
if(isset($_GET['txtId'])):
    $id=$_GET['txtId'];
    $film->delete($id);
endif;
#3.quay ve trang index.php
header("Location:index.php");
exit();
?>