<?php
/********************************************
 * [PHẦN 1] – Kết nối database
 ********************************************/
include_once("PhoneBrandDB.php");

/********************************************
 * [PHẦN 2] – Lấy PBID từ URL
 ********************************************/
$PBID = isset($_GET['PBID']) ? intval($_GET['PBID']) : 0;//Dùng $_GET['PBID'] để lấy và truy vấn brand
if ($PBID <= 0) {
    die("Invalid Brand ID.");
}

/********************************************
 * [PHẦN 3] – Truy vấn lấy chi tiết Brand
 ********************************************/
$stmt = $conn->prepare("SELECT * FROM PhoneBrand WHERE PBID = ?");
$stmt->bind_param("i", $PBID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Brand not found.");
}
$PhoneBrand = $result->fetch_assoc();

/********************************************
 * [PHẦN 4] – Xử lý logo
 ********************************************/
$logo = (!empty($PhoneBrand['Logo']) && file_exists("uploads/" . $PhoneBrand['Logo'])) 
            ? $PhoneBrand['Logo'] 
            : "no-logo.png";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>DETAILS BRAND</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container mt-3">
        <h2>DETAILS BRAND</h2>
        <!-- [PHẦN 5] – FORM details -->
        <table class="table table-bordered align-middle">
            <tr>
                <td style="width: 40%; text-align: center;">
                    <img src="uploads/<?= htmlspecialchars($logo) ?>" width="300" alt="Logo">
                </td>
                <td style="width:40% ; vertical-align: middle; padding-left: 30px;">
                    <div class="mb-3">
                        <p class="fw-bold mb-1">Name</p>
                        <p><?= htmlspecialchars($PhoneBrand["Name"]) ?></p>
                    </div>
                    <div>
                        <p class="fw-bold mb-1">Country</p>
                        <p><?= htmlspecialchars($PhoneBrand["Country"]) ?></p>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="text-center">
                        <a href="./index.php" class="btn btn-warning me-2">Back to Index</a>
                        <a href="uploads/<?= htmlspecialchars($logo) ?>" class="btn btn-success" download>Download Logo</a>
                    </div>
                </td>
            </tr>

        </table>

    </div>
</body>

</html>
