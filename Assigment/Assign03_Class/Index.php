
<?php
#1.ket noi controller
include './PhoneBrandController.php';
$phonebrand = new PhonebrandController(); //tao doi tuong phonebrand tu lop controller de goi cac phuong thuc(ham) trong lop do: read(), create(), update(), delete()
$visitor= new PhonebrandController();
$count=$visitor ->visitor();
#2.doc du lieu
if($_SERVER['REQUEST_METHOD']=='POST' && !empty($_POST['txtSearch'])):
    $keyword=$_POST['txtSearch'];
    $fields=$phonebrand->search($keyword);
    else:
        $fields = $phonebrand->read(); //goi ham read()tu doi tuong phonebrand sau do gan gia tri vao bien fields
endif;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Phone Brand List</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f6f8;
                margin: 0;
                padding: 20px;
            }

            .index-container {
                max-width: 1000px;
                margin: auto;
                background-color: #ffffff;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }

            h1 {
                text-align: center;
                color: #333;
                margin-bottom: 20px;
            }

            form {
                display: flex;
                justify-content: center;
                gap: 10px;
                margin-bottom: 20px;
            }

            input[type="text"] {
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 6px;
                width: 300px;
            }

            .btn-search, .btn-reset, .btn-add {
                padding: 10px 15px;
                border: none;
                border-radius: 6px;
                background-color: #007bff;
                color: white;
                cursor: pointer;
                text-decoration: none;
                font-weight: bold;
            }

            .btn-reset {
                background-color: #6c757d;
            }

            .btn-add {
                background-color: #28a745;
                margin-left: 10px;
            }

            .btn-search:hover, .btn-reset:hover, .btn-add:hover {
                opacity: 0.9;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            table thead {
                background-color: #007bff;
                color: white;
            }

            table th, table td {
                padding: 12px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            img {
                border-radius: 5px;
                border: 1px solid #ccc;
            }

            .btn-detail, .btn-update, .btn-delete {
                padding: 6px 10px;
                border-radius: 4px;
                color: white;
                text-decoration: none;
                margin-right: 5px;
                font-size: 14px;
            }

            .btn-detail {
                background-color: #17a2b8;
            }

            .btn-update {
                background-color: #ffc107;
                color: black;
            }

            .btn-delete {
                background-color: #dc3545;
            }

            .btn-detail:hover, .btn-update:hover, .btn-delete:hover {
                opacity: 0.85;
            }

            div > .visitor {
                margin-top: 15px;
                text-align: right;
                font-size: 14px;
                color: #555;
            }
        </style>

    </head>
    <body>
        <div class="index-container">
            <h1>PHONE BRAND LIST</h1>
            <form action="Index.php" method="POST">
                <input type="text" name="txtSearch" placeholder="What are you looking for?">
                <button type="submit" class="btn-search">Search</button>
                <a href="Index.php" type="reset" class="btn-reset">Reset</a>  
            </form>
            <a href="Add.php" class="btn-add">Add New</a>
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Country</th>
                        <th>Logo</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fields as $data): ?>
                        <tr>
                            <td><?= $data['PBID'] ?></td>
                            <td><?= $data['Name'] ?></td>
                            <td><?= $data['Country'] ?></td>
                            <td>
                                <?php if (!empty($data['Logo'])): ?>
                                    <img src="uploads/<?= htmlspecialchars($data['Logo']) ?>" alt="Logo" style="width: 100px; height: 100px;">
                                <?php else: ?>
                                    No logo
                                <?php endif; ?>
                            </td>

                            <td>
                                <div>
                                    <a class="btn-detail" href="Detail.php?txtCode=<?= $data['PBID'] ?>">Detail</a>
                                    <a class="btn-update" href="Update.php?txtCode=<?= $data['PBID'] ?>">Update</a>
                                    <a class="btn-delete" href="Delete.php?txtCode=<?= $data['PBID'] ?>" onclick="return confirm('Are you sure to delete?')">Delete</a>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="visitor">Visitor: <?=$count?></div>
        </div>
    </body>
</html>
