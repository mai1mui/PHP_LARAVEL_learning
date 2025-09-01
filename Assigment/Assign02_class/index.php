<?php
#1. Access controller
include_once './PatientController.php';
$patient = new PatientController();

#2. Read data
$fields = $patient->read();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eHospital</title>
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
        .btn-add {
            margin-bottom: 20px;
            background-color: #28a745;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-add:hover {
            background-color: #218838;
        }
        .table th {
            background-color: #33CC66;
            color: white;
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .btn-modify, .btn-delete {
            padding: 5px 10px;
            border: none;
            border-radius: 10px;
            color: white;
        }
        .btn-modify {
            background-color: #007bff;
        }
        .btn-modify:hover {
            background-color: #0056b3;
        }
        .btn-delete {
            background-color: #dc3545;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container mt-3">
        <h2>Patient List</h2>
        <div style="display: flex;">
            <a href="create.php" class="btn-add">Add New</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>PatientID</th>
                    <th>PatientName</th>
                    <th>Country</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fields as $data) : ?>
                    <tr>
                        <td><?= $data['PatientID'] ?></td>
                        <td><?= $data['PatientName'] ?></td>
                        <td><?= $data['Country'] ?></td>
                        <td><?= $data['Email'] ?></td>
                        <td>
                            <div style="display: flex;">
                                <!-- Form update -->
                                <form action="update.php" method="get" style="margin-right: 5px;">
                                    <input type="hidden" name="txtID" value="<?= $data['PatientID'] ?>">
                                    <input type="submit" value="Modify" class="btn-modify">
                                </form>
                                <!-- Form delete -->
                                <form action="delete.php" method="get" onsubmit="return confirm('Are you sure?');">
                                    <input type="hidden" name="txtID" value="<?= $data['PatientID'] ?>">
                                    <input type="submit" value="Delete" class="btn-delete">
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
