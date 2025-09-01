
<?php
include_once("PatientDB.php");
$sql = "SELECT * FROM patient";
$customers = $conn->query($sql);
$count = $customers->num_rows;//count() chỉ dùng cho mảng, còn $customers là object → nên dùng $customers->num_rows
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eHopital</title>
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
        }
        .table th {
            background-color: #33CC66;
            color: white;
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .btn-modify {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 10px 
        }
        .btn-modify:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
    <div class="container mt-3">
        <h2>Patient list</h2>
        <div style="display: flex;">
            <a href="Add.php" class="btn-add">Add New</a>
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
                <?php
                if ($count > 0) {
                    foreach ($customers as $row) {
                        ?>
                        <tr>
                            <td><?php echo $row['PatientID']; ?></td>
                            <td><?php echo $row['PatientName']; ?></td>
                            <td><?php echo $row['Country']; ?></td>
                            <td><?php echo $row['Email']; ?></td>
                            <td>
                                <div style="display: flex;">
                                   <div style="flex: 1; padding: 5px;">
                                       <!-- Form update -->
                                       <form action="Edit.php" method="get">
                                           <input type="hidden" name="PatientID" value="<?= $row['PatientID'] ?>">
                                           <input type="submit" name="modify" value="Modify" class="btn-modify">
                                       </form>
                                   </div>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='5'>No data!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
