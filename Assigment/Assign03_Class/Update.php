
<?php
#1.ket noi controller
include './PhoneBrandController.php';
$phonebrand = new PhonebrandController();
$data = []; // tránh lỗi nếu không được gán
#2.lay du lieu de hien thi tren form update
if (isset($_GET['txtCode'])):
    $code = $_GET['txtCode'];
    $data = $phonebrand->filter($code);
    if(!$data):
        echo 'not found!';
    endif;
endif;
#3.neu form duoc submit thi goi ham update
if (isset($_POST['btnOK'])):
    $phonebrand->update();
endif;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update Phone Brand</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f0f2f5;
                margin: 0;
                padding: 0;
            }

            .update-container {
                max-width: 500px;
                margin: 50px auto;
                padding: 30px;
                background-color: #ffffff;
                border-radius: 10px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .form-group h1 {
                text-align: center;
                color: #333;
                margin-bottom: 20px;
            }

            .form-group div {
                margin-bottom: 15px;
            }

            label {
                display: block;
                margin-bottom: 5px;
                color: #555;
                font-weight: bold;
            }

            .form-input {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 6px;
                box-sizing: border-box;
            }

            .btn-save {
                width: 100%;
                padding: 12px;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 6px;
                font-size: 16px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .btn-save:hover {
                background-color: #0056b3;
            }
        </style>

    </head>
    <body>
        <div class="update-container">
            <form class="form-group" action="" method="post" enctype="multipart/form-data">
                <h1>UPDATE PHONE BRAND</h1>
                <div>
                    <label for="code">Code</label>
                    <input class="form-input" type="text" name="txtCode" value="<?= $data['PBID'] ?? '' ?>" readonly>
                </div>
                <div>
                    <label for="name">Name</label>
                    <input class="form-input" type="text" name="txtName" value="<?= $data['Name'] ?? '' ?>">
                </div>
                <div>
                    <label for="country">Country</label>
                    <input class="form-input" type="text" name="txtCountry" value="<?= $data['Country'] ?? '' ?>">
                </div>
                <div>
                    <label for="logo">Logo</label>
                    <input class="form-input" type="file" name="txtLogo">
                    <input type="hidden" name="oldLogo" value="<?= $data['Logo'] ?? '' ?>">
                </div>
                <button class="btn-save" name="btnOK">Save</button>
            </form>
        </div>
    </body>
</html>
