<?php
    # 1. Start session
    # 2. Check session
    # 3. Database connect
    include_once '../session08_Layouts/zDBConnect.php';
  
    # 4. Get Item code
    if(!isset($_GET['code'])):
        echo 'Nothing to drop!';
    endif;
    $table = $_GET['code'];
    
    # 5. Execute query
    $query = "drop table $table";
    $rs = mysqli_query($conn, $query);
    if(!$rs):
        echo 'Nothing to delete';
    else:
        header('location: ex01_Management.php');
    endif;
    
    # 6. close connection
    mysqli_close($conn);
