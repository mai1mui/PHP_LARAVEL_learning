<?php
#1.ket noi controller
include './CarController.php';
$carbrand=new CarController();
$data = []; // tranh loi neu khong duoc gan txtID
#2.lay du lieu de hien thi tren form detail
if(isset($_GET['txtID'])):
    $ID=$_GET['txtID'];
    $data=$carbrand->filter($ID);
endif;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Detail</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <div class="create-container">
            <h1>Car Information</h1>
            <form class="form-group">
                <div>
                    <label for="id">ID:</label>
                    <input type="text" id="id" name="txtID" value="<?=$data['ID'] ?? ''?>" readonly>
                </div>
                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="txtName" value="<?=$data['Name'] ?? ''?>" readonly>
                </div>
                <div>
                    <label for="brand">Brand:</label>
                    <input type="text" id="brand" name="txtBrand" value="<?=$data['Brand'] ?? ''?>" readonly>
                </div>
                <div>
                    <label for="model">Model:</label>
                    <input type="text" id="model" name="txtModel" value="<?=$data['Model'] ?? ''?>" readonly>
                </div>
                <div>
                    <label for="year">Year:</label>
                    <input type="text" id="year" name="txtYear" value="<?=$data['Year'] ?? ''?>" readonly>
                </div>
                <div>
                    <label for="price">Price:</label>
                    <input type="text" id="price" name="txtPrice" value="<?=$data['Price'] ?? ''?>" readonly>
                </div>
                <div>
                    <a href="download.php?txtID=<?=$data['ID'] ?? ''?>" class="btn-download">Download</a>
                    <a href="index.php">Back to home page</a>
                </div>
            </form>
        </div>
    </body>
</html>
