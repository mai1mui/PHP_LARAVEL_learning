<?php
#1.ket noi controller
include './PhoneBrandController.php';
if($_SERVER['REQUEST_METHOD']=='POST'):
    $keyword=$_POST['txtSearch'];
    $fields=$phonebrand->search($keyword);
endif;
?>