<?php 
    $cars = [
        ["Toyota", "Camry", "25000"],
        ["Honda", "Civic", "22000"],
        ["Ford", "Focus", "20000"]
    ];
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
            <caption><h2>Car List</h2></caption>
            <tr> 
                <th>Brand</th>
                <th>Model</th>
                <th>Price</th>
                <th>Function</th>
            </tr>
            <?php 
            foreach ($cars as $key => $value) { // Bỏ dấu ";" và dùng "{"
                echo '<tr>';
                foreach ($value as $data) { // Bỏ dấu ";" và dùng "{"
                    echo '<td>'.$data.'</td>';
                }
                echo '<td><a href="#">Update</a> | <a href="#">Delete</a></td>';
                echo '</tr>';
            }
            ?>
        </table>
    </body>
</html>
