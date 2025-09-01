<!DOCTYPE html>
<?php
    #1. Start session
        session_start();
    
    #2. Check session
    if(!isset($_SESSION['login'])):
        header('location: Ex00_Login.php?msgErr=First login then using other option!');
    endif;
    
    #3. Database connect
    include_once './zDBConnect.php';
    
    #4. Get code from Ex01_Read.php
    if(!isset($_GET['code'])):
        header('location: ex01_Read.php?msgErr=Nothing to update!');
    endif;
    $code = $_GET['code'];
    
    #5. Execute query to display current record by code
    $query  = "select * from item where code = '{$code}'";
    $rs     = mysqli_query($conn, $query);
    $data   = mysqli_fetch_array($rs); 
///////////////////////////////////////
    #6. Check Form is submitted?
    if(isset($_POST['btnOK'])):
        #7. Get form element value
        $name = $_POST['txtName'];
        $price = $_POST['txtPrice'];
        
        #8. Execute query to UPDATE current record by code
        $query = "update item set name = '{$name}', price = '{$price}' where code = '{$code}'";
        $rs = mysqli_query($conn, $query);
        if(!$rs):
            header('location: ex01_Read.php?msgErr=Nothing to update!');
        else:
            header('location: ex01_Read.php?msgOK=Update successfully!');
        endif;
       
    endif; //$_POST['btnOK']
    #9. Close connection
    mysqli_close($conn);

?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <title>CRUD [Create - Read - Update - Delete]</title>
    </head>
    <body class="container">
        <div style="text-align: right; padding: 0px 20px">
            Welcome 
            <span style="color: blue; font-weight: bold">
                <?= $_SESSION['login'] ?>
            </span> | 
             <a href="./zLogout.php">Logout</a>
        </div>
        <h1>Update Item</h1>
        <form method="post">
            <table class="table table-borderedless">
                <tr>
                    <td align="right">Code: </td>
                    <td>
                        <input name="txtCode" value="<?= $data[0] ?>" readonly>
                    </td>
                </tr>
                <tr>
                    <td align="right">Name: </td>
                    <td>
                        <input name="txtName" value="<?= $data[1] ?>" autofocus>
                    </td>
                </tr>
                <tr>
                    <td align="right">Price: </td>
                    <td>
                        <input name="txtPrice" value="<?= $data[2] ?>">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit" name="btnOK" value="Update" 
                               class="btn btn-primary" 
                               onclick="return confirm('Are you sure to update <?= $data[0] ?>?')">
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>
