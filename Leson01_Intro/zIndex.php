<?php 
    include './zDatabase.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>Item List</title>
    </head>
    <body>
        <table class="table table-bordered table-hover"> 
            <caption><h2>Item List</h2></caption>
            <tr> 
                <th>Code</th>
                <th>Name</th>
                <th>Price</th>
                <th>Function</th>
            </tr>
            <tr> 
                <td><?= $code ?></td>
                <td><?= $name ?></td>
                <td><?= $price ?></td>
                <td> 
                    <a href ="">Update</a>
                    <a href ="" onclick="return confirm('Are you sure to delete this record?')">Delete</a>
                </td>
            </tr>
        </table>
    </body>
</html>

