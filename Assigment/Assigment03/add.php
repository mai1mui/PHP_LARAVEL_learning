<?php
    include_once 'connectDB.php';

    session_start();
    if (!isset($_SESSION["loggedin"])) {
        header("Location: login.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $country = $_POST['country'];
        $logo = $_FILES['logo'];

        if ($name && $country && $logo['name']) {
            $stmt = $conn->prepare("INSERT INTO PhoneBrand (Name, Country, Logo) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $country, $filename);
            $stmt->execute();

            header("Location: index.php");
            exit();
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Patient List</title>
    </head>
    <body>
        <h2 style="text-align: center">Patient List</h2>
        <form method="post" enctype="multipart/form-data">
            <table style='margin: auto' cellpadding="10">
                <tr>
                    <td>Logo:</td>
                    <td><input type="text" name="name" required></td>
                </tr>
                <tr>
                    <td>Manufacturer (Country):</td>
                    <td><input type="text" name="country" required></td>
                </tr>
                <tr>
                    <td>Logo:</td>
                    <td><input type="file" name="logo" required></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value='Add New'></td>
                </tr>
            </table>
        </form>
    </body>
</html>
