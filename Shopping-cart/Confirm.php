
<?php
    session_start();
    if(isset($_SESSION["name"]) == null ||
       isset($_SESSION["email"]) == null ||
       isset($_SESSION["address"]) == null ||
       isset($_SESSION["phone"]) == null){
        header(header: "location: Success.php");
       }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin: 50px;
        }
        h1, h2, h3 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        .container input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .table-info {
            margin: auto;
        }
        .table-info input {
            width: 50px;
        }
        button {
            background: #5cb85c;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
        }
        button:hover {
            background: #4cae4c;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .total {
            margin-top: 20px;
            font-weight: bold;
        }
        .total input {
            width: 200px
        }
    </style>   
</head>
<body>

<div class="container">
    <h1>Payment Confirmation</h1>
    <div class="container">
        <form class="form-group" action="" method="post">
            <p><strong>Name:</strong><?php echo $_SESSION ["name"]; ?></p>
            <p><strong>Email:</strong><?php echo $_SESSION ["email"]; ?></p>
            <p><strong>Address:</strong><?php echo $_SESSION ["address"]; ?></p>
            <p><strong>Phone:</strong><?php echo $_SESSION ["phone"]; ?></p>
            <p><strong>Notes:</strong><?php echo $_SESSION ["notes"]; ?></p>
            <p><strong>Total:</strong><?php echo $_SESSION ["total"]; ?></p>
        </form>
            <th class="button-group">
                <td>
                    <form action="Success.php">
                        <button>Payment</button>
                    </form>
                </td>
                <td>
                    <form action="shopping_cart.php">
                        <button>Return</button>
                    </form>
                </td>
            </th> 
        
    </div>
</div>

</body>
</html>