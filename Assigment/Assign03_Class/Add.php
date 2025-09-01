
<?php
#1.ket noi controller
include './PhoneBrandController.php';
$phonebrand = new PhonebrandController();
#2.check form submit co thanh cong hay khong?
if (isset($_POST['btnOK'])):
    $phonebrand->create();
endif;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Phone Brand</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f2f2f2;
                margin: 0;
                padding: 0;
            }

            .add-container {
                max-width: 500px;
                margin: 60px auto;
                background-color: #ffffff;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }

            .form-group h1 {
                text-align: center;
                color: #333;
                margin-bottom: 30px;
            }

            .form-group div {
                margin-bottom: 20px;
            }

            label {
                display: block;
                margin-bottom: 8px;
                color: #555;
                font-weight: bold;
            }

            .form-input {
                width: 100%;
                padding: 12px;
                border: 1px solid #ccc;
                border-radius: 6px;
                box-sizing: border-box;
            }

            .btn-add {
                width: 100%;
                padding: 12px;
                background-color: #28a745;
                color: white;
                border: none;
                border-radius: 6px;
                font-size: 16px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .btn-add:hover {
                background-color: #218838;
            }
        </style>

    </head>
    <body>
        <div class="add-container">
            <form class="form-group" action="Add.php" method="post" enctype="multipart/form-data">
                <h1>ADD PHONE BRAND</h1>
                <div>
                    <label for="name">Name</label>
                    <input class="form-input" type="text" name="txtName" placeholder="enter name" required>
                </div>
                <div>
                    <label for="country">Country</label>
                    <input class="form-input" type="text" name="txtCountry" placeholder="enter country" required>
                </div>
                <div>
                    <label for="logo">Logo</label>
                    <input class="form-input" type="file" name="txtLogo">
                </div>
                <button type="submit" class="btn-add" name="btnOK" onclick="return confirm('Are you sure?')">Add New</button>
            </form>
        </div>
    </body>
</html>
