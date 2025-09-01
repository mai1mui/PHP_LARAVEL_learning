<!DOCTYPE html>
<?php
    #1. Start session
    #2. Check session
    #3. Database connect
    include_once '../session08_Layouts/zDBConnect.php';
    
    #4. Get code from Ex01_Management.php
    if(!isset($_GET['code'])):
        header('location: ex01_Management.php?msgErr=Table is not found!');      
    endif;
    $table = $_GET['code'];
    
    #5. Execute query
    $query = "show columns from $table";
    $rs = mysqli_query($conn, $query);
    $count = mysqli_num_rows($rs);
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <title></title>
    </head>
    <body class="container">
        <h1>Database Management System</h1>
        <span style="color: darkgray; font-style: italic; font-size: 18pt">
            Table <?= $table ?> Details
        </span>
        <p>
            <a href="./Create.php">Create new table</a> | 
            <a href="./Ex01_Management.php">Back to table list</a>
        <p>
        <?php
            if($count == 0):
                echo 'Record not found';
            else:
        ?>
            <table class="table table-hove table-bordered">
                <tr>
                    <th>No</th>
                    <th>Column Name</th>
                    <th>Data type</th>
                    <th>Null</th>
                    <th>Constraint</th>
                </tr>
                <?php
                    $i = 0;
                    while($data = mysqli_fetch_array($rs)):
                        $i++;
                ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $data[0] ?></td>
                        <td><?= $data[1] ?></td>
                        <td><?= $data[2] ?></td>
                        <td><?= $data[3] ?></td>
                    </tr>
                <?php        
                    endwhile;
                ?>
            </table>
        <?php        
            endif;
        ?>
    </body>
</html>