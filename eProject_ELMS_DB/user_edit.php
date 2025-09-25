<?php
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION["AccountID"]) || $_SESSION["ARole"] !== "Admin") {
    header("Location: login.php");
    exit;
}

// Kết nối DB
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elmsdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy AccountID từ GET
if (!isset($_GET["id"])) {
    header("Location: user_list.php");
    exit;
}

$accountID = $_GET["id"];

// Lấy dữ liệu account hiện tại
$stmt = $conn->prepare("SELECT * FROM Accounts WHERE AccountID=?");
$stmt->bind_param("s", $accountID);
$stmt->execute();
$result = $stmt->get_result();
$demoUser = $result->fetch_assoc();
$stmt->close();

// Xử lý submit form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $role = $_POST["role"];
    $status = $_POST["status"];

    // Cập nhật DB
    $stmt = $conn->prepare("UPDATE Accounts SET AName=?, Email=?, ARole=?, AStatus=? WHERE AccountID=?");
    $stmt->bind_param("sssss", $username, $email, $role, $status, $accountID);

    if ($stmt->execute()) {
        // Load lại dữ liệu mới
        $demoUser["AName"] = $username;
        $demoUser["Email"] = $email;
        $demoUser["ARole"] = $role;
        $demoUser["AStatus"] = $status;
        header("Location: user_list.php");
    } else {
        $error = "❌ Lỗi cập nhật: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Edit Account</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
    theme: {
        extend: {
            colors: {
                darkblue: '#111827',
                cardblue: '#1f2937',
                primary: '#3b82f6',
                primaryhover: '#2563eb',
                textlight: '#f9fafb',
            }
        }
    }
}
</script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-6">
<div class="bg-gray-800 rounded-xl shadow-xl p-8 w-full max-w-2xl">
    <h2 class="text-2xl font-bold mb-6 text-center">Edit Account</h2>

    <?php if(isset($error)) echo '<p class="text-red-400 mb-4">'.$error.'</p>'; ?>
    <?php if(isset($success)) echo '<p class="text-green-400 mb-4">'.$success.'</p>'; ?>

    <form action="" method="POST" class="space-y-4">
        <!-- Hidden AccountID -->
        <input type="hidden" name="accountid" value="<?php echo $demoUser["AccountID"]; ?>">

        <!-- Username -->
        <div>
            <label class="block mb-1">Account name</label>
            <input type="text" name="username" value="<?php echo $demoUser["AName"]; ?>" required
                   class="w-full px-4 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
        </div>

        <!-- Email -->
        <div>
            <label class="block mb-1">Email</label>
            <input type="email" name="email" value="<?php echo $demoUser["Email"]; ?>" required
                   class="w-full px-4 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
        </div>

        <!-- Role -->
        <div>
            <label class="block mb-1">Role</label>
            <select name="role" class="w-full px-4 py-2 bg-gray-700 rounded-lg">
                <option <?php if($demoUser["ARole"]=="Learner") echo "selected"; ?>>Learner</option>
                <option <?php if($demoUser["ARole"]=="Instructor") echo "selected"; ?>>Instructor</option>
                <option <?php if($demoUser["ARole"]=="Admin") echo "selected"; ?>>Admin</option>
            </select>
        </div>

        <!-- Status -->
        <div>
            <label class="block mb-1">Status</label>
            <select name="status" class="w-full px-4 py-2 bg-gray-700 rounded-lg">
                <option <?php if($demoUser["AStatus"]=="Active") echo "selected"; ?>>Active</option>
                <option <?php if($demoUser["AStatus"]=="Inactive") echo "selected"; ?>>Inactive</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-4 pt-4">
            <button type="button" onclick="window.location.href = 'user_list.php'"
                    class="px-6 py-2 rounded-lg border border-gray-600 text-gray-300 font-semibold hover:bg-gray-700 transition">
                Cancel
            </button>
            <button type="submit"
                    class="px-6 py-2 rounded-lg bg-primary text-textlight font-semibold hover:bg-primaryhover shadow-md transition">
                Save
            </button>
        </div>
    </form>
</div>
</body>
</html>
