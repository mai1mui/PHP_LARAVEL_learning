<?php
// 1. Access controller
include_once './PatientController.php';
$patient = new PatientController();

// 2. check form delete
if (isset($_GET['txtID'])) {
    $id = $_GET['txtID'];
    $patient->delete($id);
}

// 3. Quay về trang index.php
header("location: index.php");
exit();
?>
