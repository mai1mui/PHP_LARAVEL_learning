<?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($_POST["username"] == "admin" && $_POST["password"] == "123") {
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
        <title></title>
    </head>
    <body>
        <form method='POST'>
            <table style='margin: auto' border="1" cellpadding="10">
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
                    <td><input type="submit" value='submit'></td>
                </tr>
            </table>
        </form>
    </body>
</html>
