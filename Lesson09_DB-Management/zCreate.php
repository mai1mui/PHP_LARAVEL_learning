<!DOCTYPE html>
<?php
    # 1. Start session
    # 2. Check session
    # 3. Database connect
    include_once '../session08_Layouts/zDBConnect.php';
    
    # 4. Check form is submitted?
    if(isset($_POST['btnOK'])):
        # 5. Get data element
        $table = $_POST['txtTable'];
        
        $field1 = $_POST['txtField1'];
        $field2 = $_POST['txtField2'];
        $field3 = $_POST['txtField3'];
        
        $type1 = $_POST['txtType1'];
        $type2 = $_POST['txtType2'];
        $type3 = $_POST['txtType3'];
        
        # 6. Execute Query
        $query = "CREATE TABLE $table"
                . "(ID int AUTO_INCREMENT PRIMARY KEY, $field1 $type1, $field2 $type2, $field3 $type3)";
        $rs = mysqli_query($conn, $query);
        
        if(!$rs):
            header('location: ex01_Management.php?msgErr=Nothing to create');
        else:
            header('location: ex01_Management.php?msgOK=save to database successfully');
        endif;
    endif;  //$_POST['btnOK']
    # 7. Close connection
    mysqli_close($conn);
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"/>
        <title>Management</title>
    </head>
    <body class="container">
        <h1>Database Management System</h1>
        <span style="color:darkgray; font-size: 18pt; font-style: italic">
            Create Table Form
        </span>
        <p>
        <div align="right"><a href="./Ex01_Management.php">Back to Table List</a></div>
        
        <form method="post">
            <table class="table table-bordered">
                <tr>
                    <td align="right">Table name: </td>
                    <td>
                        <input name="txtTable" autofocus required>
                    </td>
                </tr>
            </table>
            <h3>Create three fields for table</h3>
            <table class="table table-bordered">
                <tr>
                    <th>No</th>
                    <th>Fields name</th>
                    <th>Data type</th>
                </tr>
                <tr>
                    <td>1</td>
                    <td>
                        <input name="txtField1"  required>
                    </td>
                    <td>
                        <input name="txtType1" required>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>
                        <input name="txtField2" required>
                    </td>
                    <td>
                        <input name="txtType2" required>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>
                        <input name="txtField3" required>
                    </td>
                    <td>
                        <input name="txtType3" required>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" align="center">
                        <input  class="btn btn-outline-primary"
                            type="submit" name="btnOK" value="Create Table">
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>