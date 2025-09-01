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

// Lấy thông tin bệnh nhân theo ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM patients WHERE id = $id";
    $result = $conn->query($sql);
    $patient = $result->fetch_assoc();
}

// Xử lý khi người dùng cập nhật dữ liệu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $age = $_POST["age"];
    $email = $_POST["email"];

    $sql = "UPDATE patients SET name='$name', age=$age, email='$email' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        // Quay lại danh sách sau khi cập nhật thành công
        header("Location: list.php");
        exit();
    } else {
        echo "Lỗi cập nhật: " . $conn->error;
    }
}

// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Patient</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-3">
        <h2>Edit Patient</h2>
        <form action="edit.php" method="post">
            <input type="hidden" name="id" value="<?= $patient['id'] ?>">
            <div class="mb-3">
                <label>Name:</label>
                <input type="text" name="name" class="form-control" value="<?= $patient['name'] ?>" required>
            </div>
            <div class="mb-3">
                <label>Age:</label>
                <input type="number" name="age" class="form-control" value="<?= $patient['age'] ?>" required>
            </div>
            <div class="mb-3">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" value="<?= $patient['email'] ?>" required readonly>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="list.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
