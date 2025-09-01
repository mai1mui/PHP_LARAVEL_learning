
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "film";

// Kết nối MySQL với database 'film'
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối đến database '$dbname' thất bại: " . $conn->connect_error);
}

//lay data movies
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM movies WHERE id = $id";
    $result = $conn->query($sql);
    $movies = $result->fetch_assoc();
}

//xu ly khi user nhap data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $director = $_POST["director"];
    $year = $_POST["year"];

    $sql = "update movies set name ='$name', director='$director',year='$year' where id=$id";
    if ($conn->query(query: $sql) == TRUE) {
        //return list when update done
        header(header: "location:read.php");
        exit();
    } else {
        echo"Error!" . $conn->error;
    }
}
// Đóng kết nối
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Update Movie</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-3">
            <h2>Update Movie</h2>
            <form action="" method="POST">
                <input type="hidden" name="id" value="<?=$movies['id']?>">
                <div class="mb-3">
                    <label>Name:</label>
                    <input type="text" name="name" class="form-control" value="<?=$movies['name']?>" required>
                </div>
                <div class="mb-3">
                    <label>Director:</label>
                    <input type="text" name="director" class="form-control" value="<?=$movies['director']?>" required>
                </div>
                <div class="mb-3">
                    <label>Year:</label>
                    <input type="number" name="year" class="form-control" value="<?=$movies['year']?>" required>
                </div>
                <button type="submit" class="btn btn-success">Update</button>
                <a href="read.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </body>
</html>
