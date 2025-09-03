<?php

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
        <div class="visitor">Visitors:</div>
        <form>
            <input type="text" name="txtSearch" placeholder="What are you looking for?">
            <button type="submit" class="btn-search">Search</button>
            <a href="Index.php" type="reset" class="btn-reset">Reset</a>  
        </form>
        <div class="index-container">
            <h1>Movies List</h1>
            <a href="create.php">Add New</a>
            <form class="form-group" action="">
                <table class="table-form">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Director</th>
                        <th>Year</th>
                        <th>Action</th>
                    </tr>

                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <a href="detail.php" class="btn-detail">Detail</a>
                                <a href="update.php" class="btn-update">Update</a>
                                <a href="delete.php" class="btn-delete" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                <a href="download.php" class="btn-download">Download</a>
                            </td>
                        </tr>

                </table>
            </form>
        </div>
    </body>
</html>
