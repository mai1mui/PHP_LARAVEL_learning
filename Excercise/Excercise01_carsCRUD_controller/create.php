
<?php
#1.ket noi controller
include './CarController.php';
$carbrand = new CarController();
#2.check form submit co thanh cong khong?
if (isset($_POST['btnOK'])):
    $carbrand->create();
endif;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add new</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <div class="create-container">
            <h1>Add new car information</h1>
            <form class="form-group" method="POST">
                <div>
                    <label for="id">ID:</label>
                    <input type="text" id="id" name="txtID" placeholder="enter ID">
                </div>
                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="txtName" placeholder="enter name car">
                </div>
                <div>
                    <label for="brand">Brand:</label>
                    <input type="text" id="brand" name="txtBrand" placeholder="enter brand">
                </div>
                <div>
                    <label for="model">Model:</label>
                    <input type="text" id="model" name="txtModel" placeholder="enter model">
                </div>
                <div>
                    <label for="year">Year:</label>
                    <input type="text" id="year" name="txtYear" placeholder="enter year of manufacture">
                </div>
                <div>
                    <label for="price">Price:</label>
                    <input type="text" id="price" name="txtPrice" placeholder="enter price">
                </div>
                <div>
                    <button class="btn-create" type="submit" name="btnOK">Add New</button>
                </div>
            </form>
        </div>
    </body>
</html>
