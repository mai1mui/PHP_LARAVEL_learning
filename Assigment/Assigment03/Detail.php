<?php
include_once("connectDB.php");

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Invalid ID";
    exit();
}

$query = "SELECT * FROM PhoneBrand WHERE PBID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Brand not found.";
    exit();
}

$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>DETAILS BRAND</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-3">
        <h2>DETAILS BRAND</h2>
        <table class="table">
            <tr>
                <td style="width: 200px">
                    <img src="images/<?= htmlspecialchars($data['Logo']) ?>" alt="Logo" width="100%">
                </td>
                <td>
                    <h3>Name: <?= htmlspecialchars($data['Name']) ?></h3>
                    <p>Country: <?= htmlspecialchars($data['Country']) ?></p>
                </td>
            </tr>
            <tr>
                <td><a href="index.php" class="btn btn-primary">Back to Index</a></td>
                <td><a href="images/<?= urlencode($data['Logo']) ?>" download class="btn btn-primary">Download Logo</a></td>
            </tr>
        </table>
    </div>
</body>

</html>
