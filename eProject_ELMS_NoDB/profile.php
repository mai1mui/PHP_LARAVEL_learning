<?php
session_start();
// Kiểm tra quyền admin
if (!isset($_SESSION["userid"]) || $_SESSION["userid"] !== "admin") {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Admin Profile</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-900 text-gray-200">
        <!-- Sidebar -->
        <?php include "sidebar.php"; ?>

        <!-- Topbar -->
        <header class="fixed top-0 left-[320px] right-0 h-16 bg-gray-800 flex items-center justify-between px-6 shadow-md z-40">
            <h1 class="text-xl font-semibold text-blue-400">👤 Profile</h1>
            <a href="dashboard.php" class="text-gray-300 hover:text-blue-400">←Come Back</a>
        </header>

    </body>
</html>
