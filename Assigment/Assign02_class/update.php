<?php
include_once './PatientController.php';
$patient = new PatientController();

$data = []; // Khởi tạo để tránh lỗi nếu không có txtID

// Lấy dữ liệu để hiển thị form
if (isset($_GET['txtID'])):
    $id = $_GET['txtID'];
    $data = $patient->filter($id);
endif;

// Nếu form được submit → gọi hàm update
if(isset($_POST['btnUpdate'])):
    $patient->update();
endif;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Update Patient</title>
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
        .btn-save {
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .btn-save:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container mt-3">
    <h2>Update Patient</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="PatientID" class="form-label">PatientID:</label>
            <input type="text" class="form-control" id="PatientID" name="txtID" readonly value="<?= $data['PatientID'] ?? '' ?>">
        </div>
        <div class="mb-3">
            <label for="PatientName" class="form-label">PatientName:</label>
            <input type="text" class="form-control" id="PatientName" name="txtName" required value="<?= $data['PatientName'] ?? '' ?>">
        </div>
        <div class="mb-3">
            <label for="Country" class="form-label">Country:</label>
            <input type="text" class="form-control" id="Country" name="txtCountry" required value="<?= $data['Country'] ?? '' ?>">
        </div>
        <div class="mb-3">
            <label for="Email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="Email" name="txtMail" required value="<?= $data['Email'] ?? '' ?>">
        </div>
        <input type="submit" value="Save" class="btn-save" name="btnUpdate">
    </form>
</div>
</body>
</html>
