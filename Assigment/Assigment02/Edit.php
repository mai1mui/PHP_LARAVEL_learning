
<?php
include_once("PatientDB.php");
$sql = "SELECT * FROM patient";
$patient = $conn->query($sql);
//doc data tu bang
if(isset($_GET['PatientID'])):
    $PatientID=$_GET['PatientID'];
    $sql="select * from patient where PatientID='$PatientID'";
    $result=$conn->query($sql);
    $patient=$result->fetch_assoc();
endif;

//xu ly khi user nhap date
if($_SERVER["REQUEST_METHOD"]=="POST"):
    $PatientID=$_POST["PatientID"];
    $PatientName=$_POST["PatientName"];
    $Country=$_POST["Country"];
    $Email=$_POST["Email"];
    $sql="update patient set PatientID='$PatientID', PatientName='$PatientName', Country='$Country', Email='$Email' where PatientID='$PatientID'";
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
        <title>Edit Patient</title>
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
                margin: 20px;
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
            <h2>Edit Patient</h2>
            <form method="POST">
                <table class="table-control">
                    <tr>
                        <td><label for="PatientID">PatientID: </label></td>
                        <td><input type="text" class="form-control" id="PatientID" name="PatientID" required value="<?= $patient['PatientID'] ?? '' ?>"</td>
                    </tr>
                    <tr>
                        <td><label for="PatientName">PatientName: </label></td>
                        <td><input type="text" class="form-control" id="PatientName" name="PatientName" required value="<?= $patient['PatientName'] ?? '' ?>"</td>
                    </tr>
                    <tr>
                        <td><label for="Country">Country: </label></td>
                        <td><input type="text" class="form-control" id="Country" name="Country" required value="<?= $patient['Country'] ?? '' ?>"</td>
                    </tr>
                    <tr>
                        <td><label for="Email">Email: </label></td>
                        <td><input type="text" class="form-control" id="Email" name="Email" required value="<?= $patient['Email'] ?? '' ?>"</td>
                    </tr>
                </table>
                
                <tr>
                    <input type="submit" value="Save" class="btn-save" href="Index.php">
                </tr>
            </form>
        </div>
    </body>
</html>
