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

// Tạo bảng 'movies' nếu chưa tồn tại
$sql_create_table = "CREATE TABLE IF NOT EXISTS movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    director VARCHAR(100) NOT NULL,
    year INT NOT NULL
)";
$conn->query($sql_create_table); // Thực thi truy vấn

// Lấy dữ liệu từ bảng 'movies'
$movies = [];
$result = $conn->query("SELECT * FROM movies");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $movies[] = $row;
    }
}
//nhan data tu create.php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = $_POST["name"] ?? "N/A";
    $director = $_POST["director"] ?? "N/A";
    $year = $_POST["year"] ?? "N/A";
    
    if(!empty($name)&&!empty($director)&&!empty($year)){
        $sql = "INSERT INTO movies (name,director,year) VALUES ('$name','$director','$year')";
        $conn->query(query:$sql);
    }
}
//truy van danh sach movies
$sql = "SELECT * FROM movies";
$result = $conn->query(query:$sql);
//chuyen data
$movies = [];
if($result && $result->num_rows > 0){
    while ($row = $result->fetch_assoc()){
        $movies[] = $row;
    }
}
// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container mt-3">
        <h2>Movie List</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Director</th>
                    <th>Year</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($movies) > 0) {
                    foreach ($movies as $row) {
                        ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['director']; ?></td>
                            <td><?php echo $row['year']; ?></td>
                            <td>
                                <div style="display: flex;">
                                   <div style="flex: 1; padding: 5px;">
                                       <!-- Form update -->
                                       <form action="update.php" method="get">
                                           <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                           <input type="submit" value="Update" class="btn btn-warning btn-sm">
                                       </form>
                                   </div>
                                   <div style="flex: 1; padding: 5px;">
                                        <!-- Form delete -->
                                        <form action="delete.php" method="post" onsubmit="return confirm('Are you sure?');">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <input type="submit" name="delete" value="Delete" class="btn btn-danger btn-sm">
                                        </form>
                                   </div>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='5'>No data!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
