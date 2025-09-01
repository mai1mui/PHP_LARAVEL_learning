<?php
#1.ket noi contoller
include './CarController.php';
#2.doc du lieu
if (isset($_GET['txtID'])) {
    $car = new CarController();
    $car->downloadPDF($_GET['txtID']);
} else {
    echo "Download failed";
}

?>
