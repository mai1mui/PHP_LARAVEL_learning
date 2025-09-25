<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
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
<body class="bg-darkblue text-white min-h-screen flex items-center justify-center p-6">
    <div class="bg-gray-800 rounded-xl shadow-xl p-8 w-full max-w-3xl">
        <!-- Avatar + Name -->
        <div class="flex flex-col items-center text-center mb-6">
            <img src="https://i.pravatar.cc/150?u=U01" 
                 alt="User Avatar" 
                 class="w-32 h-32 rounded-full border-4 border-primary shadow-lg mb-4">
            <h2 class="text-2xl font-bold">Nguyen Van A</h2>
            <p class="text-gray-400">Learner</p>
        </div>

        <!-- Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-cardblue p-4 rounded-lg shadow">
                <p class="text-gray-400 text-sm">UserID</p>
                <p class="text-lg font-semibold">U01</p>
            </div>
            <div class="bg-cardblue p-4 rounded-lg shadow">
                <p class="text-gray-400 text-sm">Email</p>
                <p class="text-lg font-semibold">nva@example.com</p>
            </div>
            <div class="bg-cardblue p-4 rounded-lg shadow">
                <p class="text-gray-400 text-sm">Role</p>
                <p class="text-lg font-semibold">Learner</p>
            </div>
            <div class="bg-cardblue p-4 rounded-lg shadow">
                <p class="text-gray-400 text-sm">Status</p>
                <p class="text-lg font-semibold text-green-400">Active</p>
            </div>
            <div class="bg-cardblue p-4 rounded-lg shadow md:col-span-2">
                <p class="text-gray-400 text-sm">Date Created</p>
                <p class="text-lg font-semibold">05/09/2025</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-center space-x-4">
            <a href="edit_user.php?id=U01" 
               class="px-6 py-2 bg-primary text-textlight rounded-lg font-semibold hover:bg-primaryhover transition-colors duration-200 shadow-lg flex items-center space-x-2">
                <i class="fas fa-edit"></i>
                <span>Edit Profile</span>
            </a>
            <a href="user_list.php" 
               class="px-6 py-2 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-500 transition-colors duration-200 shadow-lg flex items-center space-x-2">
                <span>Back</span>
            </a>
        </div>
    </div>
</body>
</html>
