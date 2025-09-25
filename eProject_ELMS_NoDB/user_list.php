<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Account Management</title>
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
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            .filter-input, .filter-select {
                @apply px-4 py-2 bg-gray-700 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary transition-colors duration-200;
            }
        </style>
    </head>
    <body class="bg-darkblue text-white min-h-screen flex items-center justify-center p-6">
        <div class="bg-gray-800 rounded-xl shadow-xl p-6 w-full max-w-8xl">
            <h2 class="text-2xl font-bold text-center mb-6">Account Management</h2>
            <!-- Search box -->
            <div class="flex justify-center mb-6 relative">
                <input type="text" placeholder="Search"
                       class="w-52 px-4 py-2 pl-10 bg-gray-700 text-gray-200 border border-gray-600 
                       rounded-lg focus:outline-none focus:ring-2 focus:ring-primary transition-colors duration-200">
                <i class="fas fa-search absolute left-1/2 transform -translate-x-24 top-2.5 text-gray-400"></i>
            </div>
            <div class="flex flex-col md:flex-row items-center justify-between mb-6 space-y-4 md:space-y-0">

                <!-- Các bộ lọc -->
                <div class="flex flex-col md:flex-row items-center justify-center mx-auto space-y-4 md:space-y-0 md:space-x-4 w-full md:w-auto">

                    <!-- Filter by Role -->
                    <select class="px-4 py-2 bg-gray-700 text-gray-400 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
                        <option value="">-- Filter by Role --</option>
                        <option value="web">Learner</option>
                        <option value="design">Instructor</option>
                    </select>

                    <!-- Filter by Status -->
                    <select class="px-4 py-2 bg-gray-700 text-gray-400 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
                        <option value="">-- Filter by Status --</option>
                        <option value="published">Active</option>
                        <option value="draft">Inactive</option>
                    </select>


                </div>
            </div>

            <div class="flex items-center justify-between mb-4">
                <!-- Nút Add new payment -->
                <button onclick="window.location.href = 'add_user.php'" class="px-6 py-2 bg-primary text-textlight rounded-lg font-semibold hover:bg-primaryhover transition-colors duration-200 shadow-lg flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Add new account</span>
                </button>

                <!-- Back to Dashboard -->
                <a href="dashboard.php" class="px-6 py-2 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-500 transition-colors duration-200 shadow-lg flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Dashboard</span>
                </a>
            </div>


            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full text-left border-collapse">
                    <thead class="bg-gray-700 text-gray-300">
                        <tr>
                            <th class="py-3 px-4 text-left rounded-tl-lg w-28">AccountID</th>
                            <th class="py-3 px-4 text-left w-28">Account name</th>
                            <th class="py-3 px-4 text-left w-28">Email</th>
                            <th class="py-3 px-4 text-left w-28">Password</th>
                            <th class="py-3 px-4 text-left w-28">Role</th>
                            <th class="py-3 px-4 text-left w-28">Status</th>
                            <th class="py-3 px-4 text-left w-28">Date created</th>
                            <th class="py-3 px-4 text-left rounded-tr-lg w-28">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Hàng dữ liệu mẫu 1 -->
                        <tr class="border-b border-gray-700 hover:bg-gray-800 transition-colors duration-200">
                            <td class="py-3 px-4">A01</td>
                            <td class="py-3 px-4">Nguyen Van A</td>
                            <td class="py-3 px-4">nva@example.com</td>
                            <td class="py-3 px-4">********</td>
                            <td class="py-3 px-4">Learner</td>
                            <td class="py-3 px-4">
                                <span class="px-4 py-3 text-green-400">Active</span>
                            </td>
                            <td class="py-3 px-4">05/09/2025</td>
                            <td class="px-4 py-2 space-x-2">
                                <button onclick="window.location.href = 'edit_user.php'" class="px-3 py-1 bg-green-500 text-white hover:bg-yellow-600 rounded">Edit</button>
                                <button class="px-3 py-1 bg-red-600 text-white hover:bg-red-700 rounded">Delete</button>
                            </td>
                        </tr>
                        <!-- Hàng dữ liệu mẫu 2 -->
                        <tr class="border-b border-gray-700 hover:bg-gray-800 transition-colors duration-200">
                            <td class="py-3 px-4">A02</td>
                            <td class="py-3 px-4">Tran Van D</td>
                            <td class="py-3 px-4">nvd@example.com</td>
                            <td class="py-3 px-4">********</td>
                            <td class="py-3 px-4">Instructor</td>
                            <td class="py-3 px-4">
                                <span class="px-4 py-3 text-red-400">Inactive</span>
                            </td>
                            <td class="py-3 px-4">05/09/2025</td>
                            <td class="px-4 py-2 space-x-2">
                                <button onclick="window.location.href = 'edit_user.php'" class="px-3 py-1 bg-green-500 text-white hover:bg-yellow-600 rounded">Edit</button>
                                <button class="px-3 py-1 bg-red-600 text-white hover:bg-red-700 rounded">Delete</button>
                            </td>
                        </tr>
                        <!-- Hàng dữ liệu mẫu 3 -->

                    </tbody>
                </table>
            </div>

        </div>
    </body>
</html>
