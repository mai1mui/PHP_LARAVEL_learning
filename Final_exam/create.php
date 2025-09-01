<?php
#1.ket noi controller
include './BookController.php';
$book=new BookController();
#2.check form submit co thanh cong hay khong
if(isset($_POST['btnOK'])):
    $book->create();
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
            background-color: #f8f9fa;
            padding: 30px;
            font-family: Arial, sans-serif;
        }

        .create-container {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
        }

        .form-group div {
            margin-bottom: 15px;
        }

        label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 6px;
        }

        .btn-create {
            background-color: #007bff;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            display: block;
            width: 100%;
            font-weight: bold;
        }

        .btn-create:hover {
            background-color: #0056b3;
        }
    </style>
</head>

    <body>
        <div class="create-container">
            <h1>Add new book information</h1>
            <form class="form-group" method="POST">
                <div>
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="txtTitle" placeholder="enter title" required>
                </div>
                <div>
                    <label for="author">Author:</label>
                    <input type="text" id="author" name="txtAuthor" placeholder="enter author" required>
                </div>
                <div>
                    <label for="publisher">Publisher:</label>
                    <input type="text" id="publisher" name="txtPublisher" placeholder="enter publisher" required>
                </div>
                <div>
                    <label for="page">Page:</label>
                    <input type="number" id="page" name="txtPage" min="1" placeholder="enter number of pages " required>
                </div>
                <div>
                    <button class="btn-create" type="submit" name="btnOK">Add New</button>
                </div>
            </form>
        </div>
    </body>
</html>
