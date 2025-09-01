
<?php 
  /* 
   1.chạy trang ex01_create.php trước
   2.nhập data rồi bấm nút add new
   * $code = $_GET [txtCode];
   * $name = $_GET [txtName];
   * $price = $_GET [txtPrice];
 */ 
    $code = $_GET['txtCode']?? 'N/A';
    $name = $_GET['txtName']?? 'N/A';
    $price = $_GET['txtPrice']?? 'N/A';
        
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>New Item List</title>
         <style>
             body {
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                width: 50%;
                height: 100vh;
                background-color: #f4f4f4;
                padding: 100px
            }
            .container {width: 100%;
                background: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            }
            table {
                width: 100%;
            }
            td {border: 1px solid black;
                padding: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Item List</h2>
            <form method="GET">
                <table>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Function</th>
                    </tr>
                    <tr>
                        <td><?=$code?></td>
                        <td><?=$name?></td>
                        <td><?=$price?></td>
                        <td>Update|Delete</td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>

