<?php
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $dbname     = "PatientDB";
//--------------
    //tao conect
    $conn = new mysqli($servername, $username, $password);
    //check ket noi
    if($conn->connect_error):
        die("error!".$conn->connect_error);
    endif;
    //create database
    $sql="create database if not exists PatientDB";
    if ($conn->query($sql) === TRUE) {
        echo "";
    } else {
        die("Error!" . $conn->error);
    }
    //close conect
    $conn->close();
//---------------
//open connect
$conn=new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error):
    die("error!<br>".$conn->connect_error);
endif;
echo"";

//create table
$sql="create table if not exists patient(
    PatientID varchar(50) primary key,
    PatientName varchar(50) not null,
    Country varchar(50) not null,
    Email varchar(50)
)";
if ($conn->query($sql) === TRUE) {
        echo "";
    } else {
        die("Error!" . $conn->error);
    }
?>