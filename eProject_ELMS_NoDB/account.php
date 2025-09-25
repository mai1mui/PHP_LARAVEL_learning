<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Accounts Demo</title>
        <link rel="stylesheet" href=""> <!-- Nạp CSS -->
        <style>
            .btn-add {
                display: inline-block;
                margin: 10px 0;
                padding: 8px 12px;
                background: #2ecc71;
                color: white;
                text-decoration: none;
                border-radius: 5px;
            }
            .btn-add:hover {
                background: #27ae60;
            }
        </style>
    </head>
    <body>
        <?php include "sidebar.php"; ?>
        <h1>Account List (Demo)</h1>

        <!-- Nút thêm mới -->
        <a href="add_user.php" class="btn-add">+ Add New Account</a>

        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>UserID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Password</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u["UserID"]); ?></td>
                        <td><?php echo htmlspecialchars($u["UName"]); ?></td>
                        <td><?php echo htmlspecialchars($u["Email"]); ?></td>
                        <td><?php echo htmlspecialchars($u["Pass"]); ?></td>
                        <td><?php echo htmlspecialchars($u["URole"]); ?></td>
                        <td>
                            <?php if ($u["UStatus"] == 1): ?>
                                <span class="status-active">Active</span>
                            <?php else: ?>
                                <span class="status-inactive">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $u['UserID']; ?>">Edit</a> | 
                            <a href="delete_user.php?id=<?php echo $u['UserID']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No users found.</td>
                </tr>
            <?php endif; ?>
        </table>
        <a class="logout" href="logout.php">Logout</a>
    </body>
</html>
