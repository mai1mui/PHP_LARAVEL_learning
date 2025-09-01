<?php
#1.ket noi controller
include './FilmController.php';
$film=new FilmController();
#2.doc du lieu
$fields=$film->read();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Index</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <style>
            /* Reset một số mặc định */
body {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(to right, #f8f9fa, #e9ecef);
    color: #333;
    animation: fadeInBody 0.3s ease-in-out;
}

@keyframes fadeInBody {
    from { opacity: 0; }
    to { opacity: 1; }
}

.index-container {
    max-width: 1000px;
    margin: 50px auto;
    background-color: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    animation: slideIn 0.7s ease-out;
}

@keyframes slideIn {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.index-container h1 {
    text-align: center;
    margin-bottom: 30px;
    font-weight: bold;
    color: #0d6efd;
}

form {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

input[type="text"] {
    padding: 10px 15px;
    width: 250px;
    border: 1px solid #ced4da;
    border-radius: 8px;
    transition: all 0.3s ease;
}

input[type="text"]:focus {
    outline: none;
    border-color: #0d6efd;
    box-shadow: 0 0 5px rgba(13, 110, 253, 0.5);
}

.btn-search, .btn-reset, .btn-detail, .btn-update, .btn-delete {
    padding: 8px 15px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
    text-decoration: none;
    color: white;
}

.btn-search {
    background-color: #0d6efd;
}
.btn-reset {
    background-color: #6c757d;
}
.btn-detail {
    background-color: #198754;
}
.btn-update {
    background-color: #ffc107;
    color: #000;
}
.btn-delete {
    background-color: #dc3545;
}

.btn-search:hover, .btn-reset:hover,
.btn-detail:hover, .btn-update:hover, .btn-delete:hover {
    transform: translateY(-2px);
    opacity: 0.9;
}

.table-form {
    width: 100%;
    border-collapse: collapse;
    animation: fadeInTable 1s ease-in-out;
}

@keyframes fadeInTable {
    from { opacity: 0; transform: scale(0.98); }
    to { opacity: 1; transform: scale(1); }
}

.table-form th, .table-form td {
    border: 1px solid #dee2e6;
    padding: 12px;
    text-align: center;
}

.table-form th {
    background-color: #0d6efd;
    color: white;
}

.visitor {
    text-align: right;
    padding: 10px 20px;
    font-style: italic;
    color: #6c757d;
    font-size: 14px;
}

        </style>
    </head>
    <body>
        <div class="visitor">Visitors:</div>
        <form>
            <input type="text" name="txtSearch" placeholder="What are you looking for?">
            <button type="submit" class="btn-search">Search</button>
            <a href="Index.php" class="btn-reset">Reset</a>  
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
                    <?php foreach ($fields as $data):?>

                        <tr>
                            <td><?=$data['id']?></td>
                            <td><?=$data['name']?></td>
                            <td><?=$data['director']?></td>
                            <td><?=$data['year']?></td>
                            <td>
                                <a href="detail.php?txtId=<?=$data['id']?>" class="btn-detail">Detail</a>
                                <a href="update.php?txtId=<?=$data['id']?>" class="btn-update">Update</a>
                                <a href="delete.php?txtId=<?=$data['id']?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </table>
            </form>
        </div>
    </body>
</html>