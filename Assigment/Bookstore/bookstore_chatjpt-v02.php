<?php
$servername = "localhost";
$username = "root"; // Đổi nếu cần
$password = ""; // Đổi nếu cần
$dbname = "bookstore";

// Kết nối MySQL
$conn = new mysqli($servername, $username, $password);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Tạo database nếu chưa tồn tại
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

// Tạo bảng books nếu chưa tồn tại
$conn->query("CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    published_year INT NOT NULL
)");

// Xử lý thêm sách
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $year = $_POST['published_year'];

    $stmt = $conn->prepare("INSERT INTO books (title, author, price, published_year) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssdi", $title, $author, $price, $year);
    $stmt->execute();
    $stmt->close();
}

// Xử lý cập nhật sách
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $year = $_POST['published_year'];

    $stmt = $conn->prepare("UPDATE books SET title=?, author=?, price=?, published_year=? WHERE id=?");
    $stmt->bind_param("ssdii", $title, $author, $price, $year, $id);
    $stmt->execute();
    $stmt->close();
}

// Xử lý xóa sách
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM books WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Lấy danh sách sách
$sql = "SELECT * FROM books";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Bookstore</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container {width: 100%;
                background: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            }
        .container input {
                width: 50%;
                padding: 8px;
                border: 1px solid #ccc;
                border-radius: 5px;
            }
        .container button {
                background-color: #28a745;
                color: white;
                padding: 10px 10px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                width: 100px;
            }
        .container button:hover {
                background-color: #218838;
            }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        form { display: inline; }
    </style>
</head>
<body>

<h2>📚 Add new books</h2>
<form method="post" class="container">
    <table>
        <tr>
            <td>Title:</td>
            <td><input type="text" name="title" required autofocus placeholder="Enter tittle"></td>
        </tr>
        <tr>
            <td>Author:</td>
            <td><input type="text" name="author" required placeholder="Enter author"></td>
        </tr>
        <tr>
            <td>Price:</td>
            <td><input type="number" name="price" step="0.01" required placeholder="Enter price"></td>
        </tr>
        <tr>
            <td>Year:</td>
            <td><input type="number" name="published_year" required placeholder="Enter price"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <button type="submit" name="add">Add Book</button>
            </td>
        </tr>
    </table>
</form>

<h2>📖 Books List</h2>
<table>
    <tr>
        <th>Book ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Price</th>
        <th>Year</th>
        <th>Action</th>
    </tr>
    <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['author']) ?></td>
            <td><?= number_format($row['price'], 2) ?> $</td>
            <td><?= $row['published_year'] ?></td>
            <td>
                <!-- Form cập nhật -->
                <form method="post">
<!--                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" required>
                    <input type="text" name="author" value="<?= htmlspecialchars($row['author']) ?>" required>
                    <input type="number" name="price" value="<?= $row['price'] ?>" step="0.01" required>
                    <input type="number" name="published_year" value="<?= $row['published_year'] ?>" required>-->
                    <input type="submit" name="update" value="Update">
                </form>
                <!-- Form xóa -->
                <form method="post" onsubmit="return confirm('Are you sure?');">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="submit" name="delete" value="Delete">
                </form>
            </td>
        </tr>
    <?php } ?>
</table>

</body>
</html>

<?php $conn->close(); ?>
