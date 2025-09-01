<?php
include_once("connectdb.php");
if(isset($_GET["id"])){
    $id_delete = $_GET["id"];
    $sql = "DELETE FROM patienttb WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$id_delete);
    $stmt->execute();
    $stmt->close();
    header("Location: list.php");
}
?>