<!DOCTYPE html>
<?php
    #1. Start session
    session_start();
    
    #2. Check session
    if(!isset($_SESSION['login'])):
        header('location: ex00_Login.php?msgErr=First login then using other option!');
    endif;
    
    #3. Database connect
    include_once './zDBConnect.php';
    
    #4. Execute query
    $query  = "select * from item";
    $rs     = mysqli_query($conn, $query);
    $count  = mysqli_num_rows($rs);   
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <?php include './layout/Head.php'; ?>
        <title>CRUD [Create - Read - Update - Delete]</title>
            <style>
                .notification-center{
                        border: thin solid green;
                        height: 150px;
                        padding: 10px
                    }
            </style>
    </head>
    <?php include './layout/Main.php';  ?>
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
        <div style="text-align: right; padding: 0px 20px">
            Welcome 
            <span style="color: blue; font-weight: bold">
                <?= $_SESSION['login'] ?>
            </span> | 
            <a href="./zLogout.php">Logout</a>
        </div>
        <h1>Item List</h1>
        <a href="./zCreate.php">Create new Item</a>
        <p>
        <?php
            if($count == 0):
                echo 'Record not found';
            else:
        ?>
            <table class="table table-hove table-bordered">
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Function</th>
                </tr>
                <?php
                    while($data = mysqli_fetch_array($rs)):
                ?>
                    <tr>
                        <td><?= $data[0] ?></td>
                        <td><?= $data[1] ?></td>
                        <td><?= $data[2] ?></td>
                        <td>
                            <a href="./zUpdate.php?code=<?= $data[0] ?>">Update</a> | 
                            <a href="./zDelete.php?code=<?= $data[0] ?>" 
                               onclick="return confirm('Are you sure to delete <?= $data[0] ?>?')">
                               Delete</a>
                        </td>
                    </tr>
                <?php        
                    endwhile;
                ?>
            </table>
        <?php        
            endif;
        ?>
        <?php include './layout/Foot.php'; ?>
    </body>
</html>
