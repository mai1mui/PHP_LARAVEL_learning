<?php
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $dbname     = "customerdb";
//--------------
    //tao conect
    $conn = new mysqli($servername, $username, $password);
    //check ket noi
    if($conn->connect_error):
        die("error!".$conn->connect_error);
    endif;
    //create database
    $sql="create database if not exists customerdb";
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
$sql="create table if not exists customers(
    CCode varchar(50) primary key,
    CName varchar(50) not null,
    CPhone varchar(50) not null,
    CEmail varchar(50) not null
)";
if ($conn->query($sql) === TRUE) {
        echo "";
    } else {
        die("Error!" . $conn->error);
    }
?>