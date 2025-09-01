<?php
#1.ket noi controller
include './BookController.php';
$book=new BookController();
#2.check form delete
if(isset($_GET['txtId'])):
    $id=$_GET['txtId'];
    $book->delete($id);
endif;
#3.quay ve trang index.php
header("Location:index.php");
exit();
?>
