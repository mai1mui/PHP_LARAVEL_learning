<?php
#1.ket noi controller
include './FilmController.php';
$film=new FilmController();
#2.check form submit co thanh cong hay khong
if(isset($_POST['btnOK'])):
    $film->create();
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
        <style>
            body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #e0f7fa, #fffde7);
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .create-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.3s ease-in-out;
            max-width: 450px;
            width: 100%;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            color: #00796b;
            text-align: center;
            margin-bottom: 25px;
            font-weight: 700;
        }

        form div {
            margin-bottom: 15px;
        }

        label {
            font-weight: 500;
            margin-bottom: 5px;
            display: block;
            color: #333;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            transition: border 0.3s;
        }

        input[type="text"]:focus {
            border-color: #00796b;
            outline: none;
        }

        .btn-create {
            background: #00796b;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 12px;
            width: 100%;
            font-weight: bold;
            transition: background 0.3s, transform 0.2s;
        }

        .btn-create:hover {
            background: #004d40;
            transform: scale(1.03);
        }
        </style>
    </head>
    <body>
        <div class="create-container">
            <h1>Add new movie information</h1>
            <form class="form-group" method="POST">
                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="txtName" placeholder="enter name movie" required>
                </div>
                <div>
                    <label for="director">Director:</label>
                    <input type="text" id="brand" name="txtDirector" placeholder="enter director" required>
                </div>
                <div>
                    <label for="year">Year:</label>
                    <input type="text" id="year" name="txtYear" placeholder="enter year" required>
                </div>
                <div>
                    <button class="btn-create" type="submit" name="btnOK">Add New</button>
                </div>
            </form>
        </div>
    </body>
</html>
