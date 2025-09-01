
<?php
include_once("CustomerDB.php");
$sql = "SELECT * FROM customers";
$customers = $conn->query($sql);
//doc data tu bang
if(isset($_GET['CCode'])):
    $CCode=$_GET['CCode'];
    $sql="select * from customers where CCode='$CCode'";
    $result=$conn->query($sql);
    $customers=$result->fetch_assoc();
endif;

//xu ly khi user nhap date
if($_SERVER["REQUEST_METHOD"]=="POST"):
    $CCode=$_POST["CCode"];
    $CName=$_POST["CName"];
    $CPhone=$_POST["CPhone"];
    $CEmail=$_POST["CEmail"];
    $sql="update customers set CCode='$CCode', CName='$CName', CPhone='$CPhone', CEmail='$CEmail' where CCode='$CCode'";
    if($conn->query($sql)==true):
        //return list when update done
        header("location:Index.php");
        exit();
    else:
        echo"error!".$conn->error;
    endif;
endif;

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <title>Customer Edit Form</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f8f9fa;
                margin: 20px;
            }
            h2 {
                text-align: center;
                color: #343a40;
                margin-bottom: 30px;
            }

            .table th {
                background-color: #343a40;
                color: white;
            }
            .table td, .table th {
                vertical-align: middle;
            }
            .btn-save {
                border-radius: 10px; 
                padding: 10px;
                margin-bottom: 20px;
                background-color: #007bff;
                color: white;
                border: none;
            }
            .btn-save:hover {
                background-color: #0056b3;
            }

        </style>
    </head>
    <body>
       <div class="container mt-3">
            <h2>Customer Edit Form</h2>
            <form method="POST">
                <table class="table-control">
                    <tr>
                        <td><label for="CCode">Code: </label></td>
                        <td><input type="text" class="form-control" id="CCode" name="CCode" required value="<?= $customers['CCode'] ?? '' ?>"</td>
                    </tr>
                    <tr>
                        <td><label for="CName">Name: </label></td>
                        <td><input type="text" class="form-control" id="CName" name="CName" required value="<?= $customers['CName'] ?? '' ?>"</td>
                    </tr>
                    <tr>
                        <td><label for="CPhone">Phone: </label></td>
                        <td><input type="text" class="form-control" id="CPhone" name="CPhone" required value="<?= $customers['CPhone'] ?? '' ?>"</td>
                    </tr>
                    <tr>
                        <td><label for="CEmail">Email: </label></td>
                        <td><input type="text" class="form-control" id="CEmail" name="CEmail" required value="<?= $customers['CEmail'] ?? '' ?>"</td>
                    </tr>
                </table>
                
                <tr>
                    <input type="submit" value="Save" class="btn-save" href="Index.php">
                </tr>
            </form>
        </div>
    </body>
</html>
