<!DOCTYPE html>
<?php
    #1. Start session
    session_start();
    
    #2. Database connect
    include_once './zDBConnect.php';
    
    #3. Check Form is submitted?
    if(isset($_GET['btnOK'])):      
        #3.1. Get Form value
        $name = $_GET['txtName'];
        $pass = $_GET['txtPass'];
        
        #3.2. Execute query
        $query = "select * from user where username = '{$name}' and password = '{$pass}'";
        $rs = mysqli_query($conn, $query);
        
        #3.3. Redirect
        if(mysqli_num_rows($rs) == 0):
            header('location: ex00_Login.php?msgErr=Username or Password is incorrect!');
        else:
            $_SESSION['login'] = $name; //Set username to session
            header('location: ex01_Read.php'); //Redirect to Ex01_Read.php
        endif;
    
    endif; //$_GET['btnOK']
    
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <?php include './layout/Head.php'; ?>
        <title></title>
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
                if(isset($_GET['msgErr'])):
                    echo "<div class='alert alert-danger'>". $_GET['msgErr']. "</div>";
                endif;
            ?>
        </div>
        <h1>Login Form</h1>
        <form method="GET">
            <table class="table table-borderedless">
                <tr>
                    <td align="right">Username: </td>
                    <td>
                        <input name="txtName" placeholder="Enter Username" autofocus>
                    </td>
                </tr>
                <tr>
                    <td align="right">Password: </td>
                    <td>
                        <input type="password" name="txtPass" placeholder="Enter Password">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit" name="btnOK" value="Login" class="btn btn-primary">
                    </td>
                </tr>
            </table>
        </form>
        <?php include './layout/Foot.php'; ?>
    </body>
</html>
