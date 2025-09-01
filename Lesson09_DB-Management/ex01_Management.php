<!DOCTYPE html>
<?php
    #1. Start session
    session_start();
    #2. Check session
    #3. Database connect
    include_once '../session08_Layouts/zDBConnect.php';
    
    #4. Execute query
    $query = "show tables";
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
        
    <div class="notification-center">
            <?php
                if(isset($_GET['msgOK'])):
                    echo '<div class="alert alert-success">'. $_GET['msgOK']. '</div>';
                endif;
                if(isset($_GET['msgErr'])):
                    echo '<div class="alert alert-danger">'. $_GET['msgErr']. '</div>';
                endif;
            ?>
        </div>
        
        <h1>Database Management System</h1>
        <span style="color: darkgray; font-style: italic; font-size: 18pt">
            List of Tables
        </span>
        <p>
        <a href="./zCreate.php">Create new table</a>
        <p>
        <?php
            if($count == 0):
                echo 'Record not found';
            else:
        ?>
            <table class="table table-hove table-bordered">
                <tr>
                    <th>No</th>
                    <th>Table Name</th>
                    <th>Function</th>
                </tr>
                <?php
                    $i = 0;
                    while($data = mysqli_fetch_array($rs)):
                        $i++;
                ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $data[0] ?></td>
                        <td>
                            <a href="./zDetails.php?code=<?= $data[0] ?>">Details</a> | 
                            <a href="./zDrop.php?code=<?= $data[0] ?>" 
                               onclick="return confirm('Are you sure to delete <?= $data[0] ?>?')">
                               Drop</a>
                        </td>
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