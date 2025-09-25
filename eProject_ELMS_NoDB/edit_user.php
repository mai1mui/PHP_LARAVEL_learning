<?php
// Lấy ID từ query string
$id = $_GET["id"] ?? "U01";

// TODO: Thay bằng query DB
$demoUser = [
    "userid" => "U01",
    "username" => "Nguyen Van A",
    "email" => "nva@example.com",
    "role" => "Learner",
    "status" => "Active"
];
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
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            .filter-input, .filter-select {
                @apply px-4 py-2 bg-gray-700 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary transition-colors duration-200;
            }
        </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-6">
    <div class="bg-gray-800 rounded-xl shadow-xl p-8 w-full max-w-2xl">
        <h2 class="text-2xl font-bold mb-6 text-center">Edit Account</h2>
        <form action="update_user.php" method="POST" class="space-y-4">
            
            <!-- Hidden UserID -->
            <input type="hidden" name="userid" value="<?php echo $demoUser["userid"]; ?>">

            <!-- Username -->
            <div>
                <label class="block mb-1">Account name</label>
                <input type="text" name="username" value="<?php echo $demoUser["username"]; ?>" required
                       class="w-full px-4 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

            <!-- Email -->
            <div>
                <label class="block mb-1">Email</label>
                <input type="email" name="email" value="<?php echo $demoUser["email"]; ?>" required
                       class="w-full px-4 py-2 bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

            <!-- Role -->
            <div>
                <label class="block mb-1">Role</label>
                <select name="role" class="w-full px-4 py-2 bg-gray-700 rounded-lg">
                    <option <?php if($demoUser["role"]=="Learner") echo "selected"; ?>>Learner</option>
                    <option <?php if($demoUser["role"]=="Instructor") echo "selected"; ?>>Instructor</option>
                    <option <?php if($demoUser["role"]=="Admin") echo "selected"; ?>>Admin</option>
                </select>
            </div>

            <!-- Status -->
            <div>
                <label class="block mb-1">Status</label>
                <select name="status" class="w-full px-4 py-2 bg-gray-700 rounded-lg">
                    <option <?php if($demoUser["status"]=="Active") echo "selected"; ?>>Active</option>
                    <option <?php if($demoUser["status"]=="Inactive") echo "selected"; ?>>Inactive</option>
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
