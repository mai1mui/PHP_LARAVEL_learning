<?php

#1.ket noi controller
include './PhoneBrandController.php';
$phonebrand = new PhonebrandController();
#2.check form delete co thanh cong
if (isset($_GET['txtCode'])):
    $code = $_GET['txtCode'];
    $phonebrand->delete($code);
endif;
#3.quay ve Index.php
header("location:Index.php");
exit();
?>