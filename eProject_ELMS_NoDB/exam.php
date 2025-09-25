<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Exam Management</title>
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
    <body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-6">
        <div class="bg-gray-800 rounded-xl shadow-xl p-6 w-full max-w-8xl">
            <h2 class="text-2xl font-bold text-center mb-6">Exam Management</h2>
            <!-- Search box -->
            <div class="flex justify-center mb-6 relative">
                <input type="text" placeholder="Search"
                       class="w-52 px-4 py-2 pl-10 bg-gray-700 text-gray-200 border border-gray-600 
                       rounded-lg focus:outline-none focus:ring-2 focus:ring-primary transition-colors duration-200">
                <i class="fas fa-search absolute left-1/2 transform -translate-x-24 top-2.5 text-gray-400"></i>
            </div>

            <!-- bộ lọc -->
            <div class="flex flex-col md:flex-row items-center justify-between mb-6 space-y-4 md:space-y-0">

                <!-- Các bộ lọc -->
                <div class="flex flex-col md:flex-row items-center justify-center mx-auto space-y-4 md:space-y-0 md:space-x-4 w-full md:w-auto">
                    
                    <select class="px-4 py-2 bg-gray-700 text-gray-400 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
                        <option value="">-- Filter by Course --</option>
                        <option value="web">Demo 01</option>
                        <option value="design">Demo 02</option>
                        <option value="data">Demo 03</option>
                    </select>
                    <select class="px-4 py-2 bg-gray-700 text-gray-400 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
                        <option value="">-- Filter by Document--</option>
                        <option value="web">Exercises</option>
                        <option value="design">Quiz</option>
                        <option value="data">Essays</option>
                        <option value="design">Exam</option>
                    </select>
                    <select class="px-4 py-2 bg-gray-700 text-gray-400 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
                        <option value="">-- Filter by Creator--</option>
                        <option value="web">Admin</option>
                        <option value="design">Intructor</option>
                    </select>
                    

                </div>
            </div>
            <div class="flex items-center justify-between mb-4">
                <!-- Nút Add new payment -->
                <button onclick="window.location.href='add_exam.php'" class="px-6 py-2 bg-primary text-textlight rounded-lg font-semibold hover:bg-primaryhover transition-colors duration-200 shadow-lg flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Add new exam</span>
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
                            <th class="px-4 py-2">ExamID</th>
                            <th class="px-4 py-2">CourseID</th>
                            <th class="px-4 py-2">Document</th>
                            <th class="px-4 py-2">Description</th>
                            <th class="px-4 py-2">Start time</th>
                            <th class="px-4 py-2">Test duration</th>
                            <th class="px-4 py-2">Creator</th>
                            <th class="px-4 py-2">Date created</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:bg-gray-700">
                            <td class="px-4 py-2">EX01</td>
                            <td class="px-4 py-2">Demo01</td>
                            <td class="px-4 py-2">Final exam</td>
                            <td class="px-4 py-2">finalexam.pdf</td>
                            <td class="px-4 py-2">00:00 15/09/2025</td>
                            <td class="px-4 py-2">24 hours</td>
                            <td class="px-4 py-2">Admin</td>
                            <td class="px-4 py-2">05/09/2025</td>
                            <td class="px-4 py-2 space-x-2">
                                <button class="px-3 py-1 bg-green-500 text-white hover:bg-yellow-600 rounded">Edit</button>
                                <button class="px-3 py-1 bg-red-600 text-white hover:bg-red-700 rounded">Delete</button>
                            </td>
                        </tr>
                        <!-- Thêm các dòng khác ở đây -->
                        <tr class="hover:bg-gray-700">
                            <td class="px-4 py-2">EX02</td>
                            <td class="px-4 py-2">Demo01</td>
                            <td class="px-4 py-2">Exercises</td>
                            <td class="px-4 py-2">exercises.pdf</td>
                            <td class="px-4 py-2">00:00 15/08/2025</td>
                            <td class="px-4 py-2">24 hours</td>
                            <td class="px-4 py-2">Instructor</td>
                            <td class="px-4 py-2">05/08/2025</td>
                            <td class="px-4 py-2 space-x-2">
                                <button onclick="window.location.href='edit_exam.php'" class="px-3 py-1 bg-green-500 text-white hover:bg-yellow-600 rounded">Edit</button>
                                <button class="px-3 py-1 bg-red-600 text-white hover:bg-red-700 rounded">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
