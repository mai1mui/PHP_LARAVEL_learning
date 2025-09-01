<?php
#1. Access controller
include_once './PatientController.php';
$patient = new PatientController();

#2. check form submit
if(isset($_POST['btnOK'])):
    $patient->create();
endif;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>New Patient Register</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
            .btn-regis {
                padding: 10px;
                margin: 20px;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 10px
            }
            .btn-regis:hover {
                background-color: #0056b3;
            }

        </style>
    </head>
    <body>
        <div class="container mt-3">
            <h2>New Patient Register</h2>
            <form method="POST">
                <table class="table-control">
                    <tr>
                        <td><label for="PatientID">PatientID: </label></td>
                        <td><input type="text" class="form-control" id="PatientID" name="txtID" autofocus required></td>
                    </tr>
                    <tr>
                        <td><label for="PatientName">PatientName: </label></td>
                        <td><input type="text" class="form-control" id="PatientName" name="txtName" required></td>
                    </tr>
                    <tr>
                        <td><label for="Country">Country: </label></td>
                        <td><input type="text" class="form-control" id="Country" name="txtCountry" required></td>
                    </tr>
                    <tr>
                        <td><label for="Email">Email: </label></td>
                        <td><input type="email" class="form-control" id="Email" name="txtMail" required></td>
                    </tr>

                </table>
                <input type="submit" value="Register" class="btn-regis" name="btnOK" href="index.php">
            </form>
        </div>
    </body>
</html>
