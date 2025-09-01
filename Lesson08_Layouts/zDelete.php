<?php
    #1. Start session
    #2. Check session
    #3. Database connect
    include_once './zDBConnect.php';
    
    #4. Get code from Ex01_Read.php
    if(!isset($_GET['code'])):
        header('location: Ex01_Read.php?msgErr=Nothing to delete!');
    endif;
    $code = $_GET['code'];
    
    #5. Execute query
    $query = "delete from item where code = '{$code}'";
    $rs = mysqli_query($conn, $query);
    if(!$rs):
        header('location: Ex01_Read.php?msgErr=Nothing to delete!');
    else:
        header('location: Ex01_Read.php?msgOK=Delete successfully!');
    endif;
#6. Close connection


    