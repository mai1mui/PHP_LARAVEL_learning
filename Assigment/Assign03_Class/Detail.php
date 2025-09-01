
<?php
#1.ket noi controller
include './PhoneBrandController.php';
$phonebrand = new PhonebrandController();
#2.lay du lieu de hien thi tren form detail
if (isset($_GET['txtCode'])):
    $code = $_GET['txtCode'];
    $data = $phonebrand->filter($code);
endif;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Phone Brand Detail</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }

            .detail-container {
                max-width: 600px;
                margin: 50px auto;
                padding: 25px;
                background-color: white;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            h1 {
                text-align: center;
                color: #333;
            }

            .detail-logo {
                text-align: center;
                margin: 20px 0;
            }

            .detail-logo img {
                max-width: 200px;
                height: auto;
                border-radius: 8px;
                border: 1px solid #ccc;
            }

            .detail-info {
                display: flex;
                flex-direction: column;
                gap: 15px;
                margin-bottom: 20px;
            }

            .detail-info div {
                display: flex;
                flex-direction: column;
            }

            strong {
                margin-bottom: 5px;
                color: #555;
            }

            .form-input {
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 6px;
                background-color: #f9f9f9;
                color: #333;
            }

            a {
                display: block;
                text-align: center;
                color: #007bff;
                text-decoration: none;
                margin-top: 20px;
            }

            a:hover {
                text-decoration: underline;
            }
        </style>

    </head>
    <body>
        <div class="detail-container">
            <form action="">
                <h1>PHONE BRAND DETAIL</h1>
                <div class="detail-logo">
                    <img src="uploads/<?= htmlspecialchars($data['Logo']) ?>" alt="Logo">
                </div>
                <div class="detail-info">
                    <div>
                        <strong>Name:</strong>
                        <input class="form-input" type="text" name="txtName" value="<?= $data['Name'] ?? '' ?>" readonly>
                    </div>
                    <div>
                        <strong>Country:</strong>
                        <input class="form-input" type="text" name="txtCountry" value="<?= $data['Country'] ?? '' ?>" readonly>
                    </div>
                </div>
                <a href="Index.php">Back to Index.php page</a>

            </form>
        </div>
    </body>
</html>
