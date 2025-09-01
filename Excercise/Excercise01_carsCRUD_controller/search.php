<?php
#1.ket noi controller
include './CarController.php';
#2.doc du lieu
if($_SERVER['SERVER_METHOD']=="POST"):
    $keyword-$_POST['txtSearch'];
    $fields=$carbrand->search($keyword);
endif;
?>
