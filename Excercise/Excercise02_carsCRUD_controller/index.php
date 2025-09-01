
<?php
#1.ket noi controller
include './CarController.php';
$carbrand = new CarController();
$count = $carbrand->visitor();
#2.doc du lieu
if ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['txtSearch'])):
    $keyword = $_POST['txtSearch'];
    $fields = $carbrand->search($keyword);
else:
    $fields = $carbrand->read();
endif;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Index</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <div class="visitor">Visitors:<?= $count ?></div>
        <form action="Index.php" method="POST">
            <input type="text" name="txtSearch" placeholder="What are you looking for?">
            <button type="submit" class="btn-search">Search</button>
            <a href="Index.php" class="btn-reset">Reset</a>  
        </form>
        <div class="index-container">
            <h1>Cars List</h1>
            <a href="create.php">Add New</a>
            <form class="form-group" action="">
                <table class="table-form">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Year</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($fields as $data): ?>
                        <tr>
                            <td><?= $data['ID'] ?></td>
                            <td><?= $data['Name'] ?></td>
                            <td><?= $data['Brand'] ?></td>
                            <td><?= $data['Model'] ?></td>
                            <td><?= $data['Year'] ?></td>
                            <td><?= $data['Price'] ?></td>
                            <td>
                                <a href="detail.php?txtID=<?=$data['ID']?>" class="btn-detail">Detail</a>
                                <a href="update.php?txtID=<?=$data['ID']?>" class="btn-update">Update</a>
                                <a href="delete.php?txtID=<?=$data['ID']?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                <a href="download.php?txtID=<?=$data['ID']?>" class="btn-download">Download</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </form>
        </div>
    </body>
</html>
