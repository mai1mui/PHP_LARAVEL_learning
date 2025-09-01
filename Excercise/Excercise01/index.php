<?php
/*hiển thị danh sách các brand dưới dạng menu dropdown.*/
include_once("connectbdb.php");//goi file db
$sql = "SELECT * FROM brands";//truy van toan bo du lieu trong bang
$brands = $conn->query($sql);
$count = mysqli_num_rows($brands)//dem so dong ket qua (so brands trong bang)
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <title>Brand</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-sm bg-info navbar-dark">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                        Brands
                    </a>
                    <div class="dropdown-menu">
                        <?php
                        if ($count > 0)://neu count >0 thi lap qua tung brand
                            while ($data = mysqli_fetch_array($brands))://lay tung dong du lieu
                                echo '<a class="dropdown-item" target="new" href="' . $data[2] . '">' . $data[1] . '</a>';//target="new": moi dong tao 1 the <a>
                            endwhile;
                        endif;
                        ?>
                    </div>
                </li>
            </ul>
        </nav>
        <div class="container">
            <h3>Homepage</h3>
            <p style="color:gray; font-style: italic">Choose the Brand to visit the Brand's site.</p>
        </div>
        <div class="container">
            <a href="./create.php">New Brand</a> |
            <a href="./Management.php">Brand Management</a>
        </div>
    </body>
</html>