<?php
include_once("connectdb.php");
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = $_POST["name"];
    $age = $_POST["age"];
    $email = $_POST["email"];
    $sql = "INSERT INTO patienttb(name,age,email) VALUES(?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis",$name,$age,$email);
    $stmt->execute();
    $stmt->close();
    header("Location: list.php");
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="container mt-3">
        <a class="btn btn-primary" href="list.php">Back to list</a>
        <h2>Create form</h2>
        <form method="POST">
            <div class="mb-3 mt-3">
                <label for="email">Email:</label>
                <input type="email" class="form-control" 
                     placeholder="Enter email" name="email">
            </div>
            <div class="mb-3 mt-3">
                <label for="name">Name:</label>
                <input type="text" class="form-control" 
                     placeholder="Enter name" name="name">
            </div>
            <div class="mb-3 mt-3">
                <label for="age">Age:</label>
                <input type="number" class="form-control" 
                     placeholder="Enter age" name="age">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

</body>

</html>