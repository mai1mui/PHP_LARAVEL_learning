<?php
// Giả sử đã login -> lấy dữ liệu user (demo cứng)
$user = [
    "id" => "U01",
    "name" => "Nguyen Van A",
    "email" => "nva@example.com",
    "role" => "Learner",
    "status" => "Active",
    "avatar" => "https://i.pravatar.cc/150?u=U01"
];
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Profile</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {sans: ['Inter', 'sans-serif']},
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
        <script>
            // Preview avatar trước khi upload
            function previewAvatar(event) {
                const reader = new FileReader();
                reader.onload = function () {
                    const output = document.getElementById('avatarPreview');
                    output.src = reader.result;
                }
                reader.readAsDataURL(event.target.files[0]);
            }
        </script>
    </head>
    <body class="bg-darkblue text-white min-h-screen flex items-center justify-center p-6">
        <div class="bg-gray-800 rounded-xl shadow-xl p-8 w-full max-w-2xl">
            <h2 class="text-2xl font-bold text-center mb-6">Edit Profile</h2>

            <!-- Form -->
            <form action="profile_update.php" method="POST" enctype="multipart/form-data" class="space-y-6">

                <!-- Avatar -->
                <div class="flex flex-col items-center">
                    <img id="avatarPreview" 
                         src="<?= $user['avatar'] ?>" 
                         alt="Avatar" 
                         class="w-32 h-32 rounded-full border-4 border-primary shadow-lg mb-4">
                    <input type="file" name="avatar" accept="image/*" 
                           onchange="previewAvatar(event)"
                           class="block w-full text-sm text-gray-400 
                           file:mr-4 file:py-2 file:px-4 
                           file:rounded-full file:border-0 
                           file:text-sm file:font-semibold
                           file:bg-primary file:text-white
                           hover:file:bg-primaryhover">
                </div>

                <!-- Name (readonly) -->
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Full Name</label>
                    <input type="text" name="name" value="<?= $user['name'] ?>" readonly
                           class="w-full px-4 py-2 bg-gray-700 text-gray-300 rounded-lg border border-gray-600 focus:outline-none">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Email</label>
                    <input type="email" name="email" value="<?= $user['email'] ?>"
                           class="w-full px-4 py-2 bg-gray-700 text-gray-200 rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-primary">
                </div>

                <!-- Reset Password Link -->
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Password</label>
                    <a href="reset_password.php" 
                       class="inline-block px-4 py-2 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-500 transition-colors duration-200">
                        <i class="fas fa-key"></i> Reset Password
                    </a>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-center space-x-4 pt-4">
                    <button type="submit" 
                            class="px-6 py-2 bg-primary text-white rounded-lg font-semibold hover:bg-primaryhover transition-colors duration-200 shadow-lg flex items-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>Save Changes</span>
                    </button>
                    <a href="profile_view.php" 
                       class="px-6 py-2 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-500 transition-colors duration-200 shadow-lg flex items-center space-x-2">
                        <span>Cancel</span>
                    </a>
                </div>
            </form>
        </div>
    </body>
</html>
