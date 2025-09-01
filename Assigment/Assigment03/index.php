<?php
    include_once 'connectDB.php';

    session_start();
    if (!isset($_SESSION["loggedin"])) {
        header("Location: login.php");
        exit();
    }

    $search = $_GET['search'] ?? '';
    $sql = "SELECT * FROM PhoneBrand WHERE Name LIKE '%$search%' OR Country LIKE '%$search%'";
    $rs = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Brand List</title>
    </head>
    <body>
        <form style='float: right' method="get">
            <input type="text" name="search" placeholder="Enter brand name" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
        </form>
        <p>       
        <h2>Brand List</h2>
        <a href="add.php">Add New</a>
        <p>
        <table border="1" style='margin: auto'>
            <tr><th>Name</th><th>Country</th><th>Logo</th><th>Action</th></tr>
            <?php while($row = $rs->fetch_assoc()): ?>
            <tr>
                <td><?= $row['Name'] ?></td>
                <td><?= $row['Country'] ?></td>
                <td><img src="images/<?= $row['Logo'] ?>" width="100%"></td>
                <td><a href="Detail.php?id=<?= $row['PBID'] ?>">Details</a></td>
                
            </tr>
            <?php endwhile; ?>
        </table>
    </body>
</html>
