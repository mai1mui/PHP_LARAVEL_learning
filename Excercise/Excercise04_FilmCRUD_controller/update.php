<?php
#1.ket noi controller
include './FilmController.php';;
$film=new FilmController();
$data=[];//tranh loi neu khong duoc gan txtId tren url
#2.lay du lieu hien thi tren form update
if(isset($_GET['txtId'])):
    $id=$_GET['txtId'];
    $data=$film->filter($id);
endif;
#3.check form submit thanh cong khong
if(isset($_POST['btnOK'])):
    $film->update();
endif;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <style>
             body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #f0f4c3, #e1f5fe);
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .update-container {
            background: #fff;
            padding: 30px 35px;
            border-radius: 20px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
            max-width: 450px;
            width: 100%;
            animation: slideIn 0.5s ease forwards;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            text-align: center;
            color: #33691e;
            margin-bottom: 28px;
            font-weight: 700;
        }

        form div {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #555;
        }

        input[type="text"] {
            width: 100%;
            padding: 11px 14px;
            font-size: 1rem;
            border: 1.8px solid #ccc;
            border-radius: 12px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus {
            border-color: #33691e;
            outline: none;
            box-shadow: 0 0 8px rgba(51, 105, 30, 0.3);
        }

        .btn-save {
            background-color: #33691e;
            color: white;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            padding: 12px 0;
            width: 100%;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-save:hover {
            background-color: #1b5e20;
            transform: scale(1.05);
        }
        </style>
    </head>
    <body>
        <div class="update-container">
            <h1>Update movie information</h1>
            <form class="form-group" method="POST">
                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="txtName" value="<?=$data['name'] ?? ''?>">
                </div>
                <div>
                    <label for="director">Director:</label>
                    <input type="text" id="director" name="txtDirector" value="<?=$data['director'] ?? ''?>">
                </div>
                <div>
                    <label for="year">Year:</label>
                    <input type="text" id="year" name="txtYear" value="<?=$data['year'] ?? ''?>">
                </div>
                <div>
                    <button class="btn-save" type="submit" name="btnOK">Save</button>
                </div>
            </form>
        </div>
    </body>
</html>
