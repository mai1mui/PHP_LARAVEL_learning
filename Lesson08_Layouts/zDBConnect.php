<?php
    #1. Database Information
    $server     = "localhost"; //default 3306
    $account    = "root";
    $password   = "";
    $database   = "stronghold";
    
    #2. Database connect
    $conn = mysqli_connect($server, $account, $password, $database);
    
    #3. Test connection
//    if(!$conn):
//        die('Connection fails!');
//    else:
//       echo 'Congratulation.';
//    endif;
//    