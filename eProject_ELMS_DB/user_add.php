<?php
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION["AccountID"]) || $_SESSION["ARole"] !== "Admin") {
    header("Location: login.php");
    exit;
}

// Kết nối DB
$servername = "localhost";
$username = "root"; // MySQL user
$password = "";     // MySQL password
$dbname = "elmsdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Xử lý submit form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $role = $_POST["role"];
    $status = $_POST["status"];

    // Kiểm tra trùng password
    if ($password !== $confirm_password) {
        $error = "❌ Password và Confirm Password không khớp!";
    } else {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Tạo AccountID theo vai trò: L001, I001, A001, tăng dần
        $prefix = substr($role, 0, 1); // L, I, A
        $sql = "SELECT AccountID FROM Accounts WHERE ARole='$role' ORDER BY AccountID DESC LIMIT 1";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastID = intval(substr($row["AccountID"], 1));
            $newID = $prefix . str_pad($lastID + 1, 3, "0", STR_PAD_LEFT);
        } else {
            $newID ="D". $prefix ;
        }

        // Insert vào DB
        $stmt = $conn->prepare("INSERT INTO Accounts (AccountID, AName, Email, Pass, ARole, AStatus, CreatedAt) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssss", $newID, $username, $email, $hashedPassword, $role, $status);

        if ($stmt->execute()) {
            header("Location: user_list.php");
            
        } else {
            $error = "❌ Lỗi thêm account: " . $stmt->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <title>Add New Account</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-6">
        <div class="bg-gray-800 rounded-xl shadow-xl p-8 w-full max-w-2xl">
            <h2 class="text-2xl font-bold mb-6 text-center">Add New Account</h2>

            <?php if (isset($error)) echo '<p class="text-red-400 mb-4">' . $error . '</p>'; ?>
            <form action="" method="POST" class="space-y-4">
                <div>
                    <label class="block mb-1">Account name</label>
                    <input type="text" name="username" required
                           class="w-full px-4 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block mb-1">Email</label>
                    <input type="email" name="email" required
                           class="w-full px-4 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block mb-1">Password</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block mb-1">Confirm Password</label>
                    <input type="password" name="confirm_password" required
                           class="w-full px-4 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block mb-1">Role</label>
                    <select name="role" class="w-full px-4 py-2 bg-gray-700 rounded-lg">
                        <option value="Learner">Learner</option>
                        <option value="Instructor">Instructor</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2 bg-gray-700 rounded-lg">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-4 pt-4">
                    <button type="button" onclick="window.location.href = 'user_list.php'"
                            class="px-6 py-2 rounded-lg border border-gray-600 text-gray-300 font-semibold hover:bg-gray-700 transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-500 shadow-md transition">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </body>
</html>
