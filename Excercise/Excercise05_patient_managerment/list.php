<?php
include_once("connectdb.php");
$sql = "SELECT * FROM patienttb";
$patients = $conn->query($sql);

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
    <a class="btn btn-primary" href="create.php">Create a new patient</a>
        <h2>USER LIST</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($patients->num_rows > 0) {
                    foreach ($patients as $pa) { ?>
                        <tr>
                            <td><?php echo $pa["id"]?></td>
                            <td><?php echo $pa["name"]?></td>
                            <td><?php echo $pa["age"]?></td>
                            <td><?php echo $pa["email"]?></td>
                            <td><a class="btn btn-danger" onclick="return confirm('Are you sure to delete')"
                             href="delete.php?id=<?php echo $pa["id"]?>">Delete</a>
                             <a class="btn btn-warning"
                             href="edit.php?id=<?php echo $pa["id"]?>">Edit</a>
                            </td>
                        </tr>
                    <?php }
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>