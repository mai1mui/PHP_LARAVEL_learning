<?php
include_once("PhoneBrandDB.php");

if (isset($_GET['PBID'])) {
    $PBID = $_GET['PBID'];
    $sql = "SELECT * FROM PhoneBrand WHERE PBID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $PBID);
    $stmt->execute();
    $result = $stmt->get_result();
    $PhoneBrand = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $PBID = $_POST["PBID"];
    $Name = $_POST["Name"];
    $Country = $_POST["Country"];

    // Giữ logo cũ mặc định
    $logo = $_POST["OldLogo"] ?? '';

    // Nếu có upload logo mới thì ghi đè
    if (isset($_FILES["Logo"]) && $_FILES["Logo"]["error"] == 0) {
        $target_dir = "uploads/";
        $logo = basename($_FILES["Logo"]["name"]);
        $target_file = $target_dir . $logo;
        move_uploaded_file($_FILES["Logo"]["tmp_name"], $target_file);
    }

    $sql = "UPDATE PhoneBrand SET Name=?, Country=?, Logo=? WHERE PBID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $Name, $Country, $logo, $PBID);

    if ($stmt->execute()) {
        header("Location: Index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Phone Brand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-3">
        <h2>Update Phone Brand</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="PBID" value="<?= $PhoneBrand['PBID'] ?? '' ?>">
            <input type="hidden" name="OldLogo" value="<?= $PhoneBrand['Logo'] ?? '' ?>">

            <div class="mb-3">
                <label for="Name" class="form-label">Name:</label>
                <input type="text" class="form-control" name="Name" id="Name" value="<?= $PhoneBrand['Name'] ?? '' ?>" required>
            </div>

            <div class="mb-3">
                <label for="Country" class="form-label">Country:</label>
                <input type="text" class="form-control" name="Country" id="Country" value="<?= $PhoneBrand['Country'] ?? '' ?>" required>
            </div>

            <div class="mb-3">
                <label for="Logo" class="form-label">Logo:</label>
                <input type="file" class="form-control" name="Logo" id="Logo" accept="image/*">
                <?php if (!empty($PhoneBrand['Logo'])): ?>
                    <img src="uploads/<?= $PhoneBrand['Logo'] ?>" width="100" class="mt-2">
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="Index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
