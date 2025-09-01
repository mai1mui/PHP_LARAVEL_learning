<?php
include_once 'CustomerDB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST"):
    $CCode = $_POST["CCode"];
    $CName = $_POST["CName"];
    $CPhone = $_POST["CPhone"];
    $CEmail = $_POST["CEmail"];

// Kiểm tra xem kết nối có thành công chưa
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO customers(CCode, CName, CPhone, CEmail) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $CCode, $CName, $CPhone, $CEmail);
    $stmt->execute();
    $stmt->close();
    header("Location: Index.php");
    exit; // Dừng lại để tránh lỗi khi chuyển trang
endif;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Customer Register List</title>
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
                border-radius: 10px; 
                padding: 10px;
                margin: 20px;
                background-color: #007bff;
                color: white;
                border: none;
            }
            .btn-regis:hover {
                background-color: #0056b3;
            }

        </style>
    </head>
    <body>
        <div class="container mt-3">
            <h2>Customer Register Form</h2>
            <form method="POST">
                <table class="table-control">
                    <tr>
                        <td><label for="CCode">Code: </label></td>
                        <td><input type="text" class="form-control" id="CCode" name="CCode" required></td>
                    </tr>
                    <tr>
                        <td><label for="CName">Name: </label></td>
                        <td><input type="text" class="form-control" id="CName" name="CName" required></td>
                    </tr>
                    <tr>
                        <td><label for="CPhone">Phone: </label></td>
                        <td><input type="text" class="form-control" id="CPhone" name="CPhone" required></td>
                    </tr>
                    <tr>
                        <td><label for="CEmail">Email: </label></td>
                        <td><input type="email" class="form-control" id="CEmail" name="CEmail" required></td>
                    </tr>

                </table>
                <input type="submit" value="Register" class="btn-regis" href="Index.php">
            </form>
        </div>
    </body>
</html>
