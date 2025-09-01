<!DOCTYPE html>
<?php
    #1. Start session
    #2. Check session
    #3. Database connect
    include_once './zDBConnect.php';
    
    #4. Check form is submitted
    if(isset($_POST['btnOK'])):
        #4.1. Get form data
        $code   = $_POST['txtCode'];
        $name   = $_POST['txtName'];
        $price   = $_POST['txtPrice'];
        
        #4.2 Execute query
        $query  = "insert into item values('{$code}', '{$name}', {$price})";
        $rs     = mysqli_query($conn, $query);
        if(!$rs):
            header('location: Ex01_Read.php?msgErr=Nothing to save');
        else:
            header('location: Ex01_Read.php?msgOK=save to database successfully');
        endif;
    endif;//$_POST['btnOK']
    #5. Close connection
    mysqli_close($conn);
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <title></title>
    </head>
    <body class="container">
        <h1>Insert Item</h1>
        <form method="post">
            <table class="table table-borderedless">
                <tr>
                    <td align="right">Code: </td>
                    <td>
                        <input name="txtCode" placeholder="Enter code" autofocus>
                    </td>
                </tr>
                <tr>
                    <td align="right">Name: </td>
                    <td>
                        <input name="txtName" placeholder="Enter name">
                    </td>
                </tr>
                <tr>
                    <td align="right">Price: </td>
                    <td>
                        <input name="txtPrice" placeholder="Enter Price">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit" name="btnOK" value="Insert" class="btn btn-primary">
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>
