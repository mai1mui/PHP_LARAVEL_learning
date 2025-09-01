<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($_POST["username"] === "fpt" && $_POST["password"] === "aptech") {
            $_SESSION["loggedin"] = true;
            header("Location: index.php");
            exit();
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Form Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        table {
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        td {
            padding: 12px 20px;
            font-size: 16px;
        }
        input[type="text"],
        input[type="password"] {
            width: 250px;
            padding: 8px 10px;
            border: 1.5px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #4CAF50;
            outline: none;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        /* Căn giữa ô trống ở nút submit */
        td:first-child:last-child {
            width: 120px;
        }
    </style>
</head>
<body>
    <form method='POST'>
        <table border="1" cellpadding="10">
            <tr>
                <td>Username:</td>
                <td><input type="text" name="username" required></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="password" required></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value='Submit'></td>
            </tr>
        </table>
    </form>
</body>
</html>
