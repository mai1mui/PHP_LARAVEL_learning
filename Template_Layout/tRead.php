
<?php
//1.controller
include_once './tController.php';
$action=new TimetableController();
//2.read data
$fields=$action->read();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Action List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
</head>
<body>
    <div class="container mt-3">
        <h2>Action List</h2>
        <div style="display: flex;">
            <a href="tCreate.php" class="btn-add">Add New Action</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>TIME</th>
                    <th>ACTION</th>
                    <th>NOTE</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fields as $data) : ?>
                    <tr>
                        <td><?= $data['stt'] ?></td>
                        <td><?= $data['thoigian'] ?></td>
                        <td><?= $data['hoatdong'] ?></td>
                        <td><?= $data['note'] ?></td>
                        <td>
                            <div style="display: flex;">
                                <!-- Form update -->
                                <form action="tUpdate.php" method="get" style="margin-right: 5px;">
                                    <input type="hidden" name="txtStt" value="<?= $data['stt'] ?>">
                                    <input type="submit" value="Update" class="btn-update">
                                </form>
                                <!-- Form delete -->
                                <form action="tDelete.php" method="get" onsubmit="return confirm('Are you sure?');">
                                    <input type="hidden" name="txtStt" value="<?= $data['stt'] ?>">
                                    <input type="submit" value="Delete" class="btn-delete">
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
