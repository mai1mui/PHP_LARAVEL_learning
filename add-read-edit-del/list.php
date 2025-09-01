<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "patientdb";

// Kết nối MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Tạo bảng nếu chưa tồn tại
$sql_create_table = "CREATE TABLE IF NOT EXISTS patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    email VARCHAR(255) NOT NULL
)";
$conn->query($sql_create_table);

// Xử lý khi nhận dữ liệu từ create.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"] ?? "N/A";
    $age = $_POST["age"] ?? "N/A";
    $email = $_POST["email"] ?? "N/A";

    if (!empty($name) && !empty($age) && !empty($email)) {
        $sql = "INSERT INTO patients (name, age, email) VALUES ('$name', $age, '$email')";
        $conn->query($sql);
    }
}

// Truy vấn danh sách bệnh nhân
$sql = "SELECT * FROM patients";
$result = $conn->query($sql);

// Chuyển kết quả thành mảng
$patients = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row;
    }
}

// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>USER LIST</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-3">
        <h2>USER LIST</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($patients) > 0) {
                    foreach ($patients as $pa) {
                ?>
                    <tr>
                        <td><?php echo $pa['id']; ?></td>
                        <td><?php echo $pa['name']; ?></td>
                        <td><?php echo $pa['age']; ?></td>
                        <td><?php echo $pa['email']; ?></td>
                        <td>
                            <!-- Form xóa -->
                            <form action="delete.php" method="post" onsubmit="return confirm('Are you sure?');">
                                <input type="hidden" name="id" value="<?= $pa['id'] ?>">
                                <input type="submit" name="delete" value="Delete" class="btn btn-danger btn-sm">
                            </form>
                            <!-- Button chỉnh sửa -->
                            <form action="edit.php" method="get">
                                <input type="hidden" name="id" value="<?= $pa['id'] ?>">
                                <input type="submit" value="Edit" class="btn btn-warning btn-sm">
                            </form>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='5'>Không có dữ liệu</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
