<?php
session_start();

 //Kiểm tra quyền Admin
if (!isset($_SESSION["AccountID"]) || $_SESSION["ARole"] !== "Admin") {
    header("Location: login.php");
    exit;
}

// Kết nối database
$servername = "127.0.0.1";
$username = "root";   // Thay đổi theo MySQL user
$password = "";       // Thay đổi theo MySQL password
$dbname = "elmsdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Tổng số người dùng
$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM Accounts")->fetch_assoc()['total'];

// Tổng số khóa học đang active
$activeCourses = $conn->query("SELECT COUNT(*) AS total FROM Courses WHERE CStatus='Active'")->fetch_assoc()['total'];

// Tổng số assignment
$totalAssignments = $conn->query("SELECT COUNT(*) AS total FROM Lessons WHERE LessonType='Assignment'")->fetch_assoc()['total'];

// Doanh thu tháng (Payment) - giả sử lấy tổng Payment trong tháng hiện tại
$monthStart = date('Y-m-01');
$monthEnd = date('Y-m-t');
$stmt = $conn->prepare("SELECT SUM(Amount) AS revenue FROM Payment WHERE PStatus='Paid' AND PayDate BETWEEN ? AND ?");
$stmt->bind_param("ss", $monthStart, $monthEnd);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$monthlyRevenue = $result['revenue'] ? $result['revenue'] : 0;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e293b, #0a192f);
            color: #f1f5f9;
        }
        nav {
            width: 300px;
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(10px);
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding-top: 50px;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 15px rgba(0,0,0,0.5);
            z-index: 40;
        }
        nav a {
            color: #e2e8f0;
            padding: 0px 20px;
            text-decoration: none;
            display: block;
            border-radius: 8px;
            margin: 0px 5px;
            transition: all 0.3s ease;
            font-weight: 1em;
        }
        nav a:hover {
            background: linear-gradient(90deg, #3b82f6, #6366f1);
            color: #fff;
            transform: translateX(8px);
            box-shadow: 0 0 12px rgba(59,130,246,0.5);
        }
    </style>
</head>
<body>
    <?php include "sidebar.php"; ?>

    <header class="fixed top-0 left-[300px] right-0 h-16 bg-gray-900 flex items-center justify-between px-6 shadow-md z-50">
        <div class="flex items-center space-x-2">
            <span class="text-3xl font-bold text-blue-300"><h1>Welcome back 👋</h1></span>
        </div>

        <div class="flex items-center space-x-10">
            <div class="relative cursor-pointer">
                <i class="fas fa-bell text-gray-300 text-xl"></i>
                <span class="absolute -top-1 -right-2 bg-red-600 text-white text-xs px-1.5 py-0.5 rounded-full">3</span>
            </div>

            <div class="relative">
                <div id="avatarBtn" class="flex items-center space-x-3 cursor-pointer">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center font-bold text-white">
                        <?php echo strtoupper(substr($_SESSION["AName"], 0, 1)); ?>
                    </div>
                    <span class="text-gray-200 font-medium"><?php echo $_SESSION["AName"]; ?></span>
                    <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                </div>

                <div id="dropdownMenu"
                     class="hidden absolute right-0 mt-2 w-68 bg-gray-800 rounded-lg shadow-lg border border-gray-700 z-50">
                    <a href="profile_view.php" class="block px-4 py-2 text-gray-200 hover:bg-gray-700">👤 Profile</a>
                    <a href="settings.php" class="block px-4 py-2 text-gray-200 hover:bg-gray-700">⚙️ Settings</a>
                    <a href="logout.php" class="block px-4 py-2 text-red-400 hover:bg-gray-700">🚪 Logout</a>
                </div>
            </div>
        </div>
    </header>

    <main class="ml-[350px] mt-16 p-8">
        <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            <div class="bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-blue-500/30 transition">
                <h3 class="text-gray-400 text-sm mb-2">Total Users</h3>
                <p class="text-2xl font-bold text-blue-400"><?php echo $totalUsers; ?></p>
            </div>

            <div class="bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-green-500/30 transition">
                <h3 class="text-gray-400 text-sm mb-2">Active Courses</h3>
                <p class="text-2xl font-bold text-green-400"><?php echo $activeCourses; ?></p>
            </div>

            <div class="bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-yellow-500/30 transition">
                <h3 class="text-gray-400 text-sm mb-2">Assignments</h3>
                <p class="text-2xl font-bold text-yellow-400"><?php echo $totalAssignments; ?></p>
            </div>

            <div class="bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-purple-500/30 transition">
                <h3 class="text-gray-400 text-sm mb-2">Monthly Revenue</h3>
                <p class="text-2xl font-bold text-purple-400">$<?php echo number_format($monthlyRevenue, 2); ?></p>
            </div>
        </section>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <script>
        const avatarBtn = document.getElementById("avatarBtn");
        const dropdownMenu = document.getElementById("dropdownMenu");

        avatarBtn.addEventListener("click", () => {
            dropdownMenu.classList.toggle("hidden");
        });

        window.addEventListener("click", function (e) {
            if (!avatarBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.add("hidden");
            }
        });
    </script>
</body>
</html>
