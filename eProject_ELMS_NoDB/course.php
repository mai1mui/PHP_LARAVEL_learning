<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Course Management</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Thêm icon -->
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
        </style>
    </head>
    <body class="bg-darkblue flex items-center justify-center min-h-screen p-4">

        <div class="bg-cardblue text-textlight rounded-xl shadow-2xl p-8 w-full max-w-8xl relative">
            <h2 class="text-2xl font-bold mb-6 text-center">Course Management</h2>
            <!-- Search box -->
            <div class="flex justify-center mb-6 relative">
                <input type="text" placeholder=""
                       class="w-52 px-4 py-2 pl-10 bg-gray-700 text-gray-200 border border-gray-600 
                       rounded-lg focus:outline-none focus:ring-2 focus:ring-primary transition-colors duration-200">
                <i class="fas fa-search absolute left-1/2 transform -translate-x-24 top-2.5 text-gray-400"></i>
            </div>

            <!-- Filter section -->
            <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
                <div class="flex flex-col md:flex-row items-center justify-center mx-auto space-y-4 md:space-y-0 md:space-x-4 w-full md:w-auto">
                    <select class="px-4 py-2 bg-gray-700 text-gray-400 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
                        <option value="">-- Filter by Course --</option>
                        <option value="admin1">Demo 01</option>
                        <option value="admin2">Demo 02</option>
                        <option value="admin2">Demo 03</option>
                    </select>
                    <select class="px-4 py-2 bg-gray-700 text-gray-400 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
                        <option value="">-- Filter by Creator --</option>
                        <option value="admin1">Admin 01</option>
                        <option value="admin2">Admin 02</option>
                        <option value="admin2">Instructor 01</option>
                        <option value="admin2">Instructor 02</option>
                    </select>
                    <select class="px-4 py-2 bg-gray-700 text-gray-400 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
                        <option value="">-- Filter by Status --</option>
                        <option value="open">Open</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>


            </div>
            <div class="flex items-center justify-between mb-4">
                <!-- Nút Add new payment -->
                <button onclick="window.location.href='add_course.php'" class="px-6 py-2 bg-primary text-textlight rounded-lg font-semibold hover:bg-primaryhover transition-colors duration-200 shadow-lg flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Add new course</span>
                </button>

                <!-- Back to Dashboard -->
                <a href="dashboard.php" class="px-6 py-2 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-500 transition-colors duration-200 shadow-lg flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Dashboard</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-700 rounded-lg">
                            <th class="px-4 py-2 font-medium">CourseID</th>
                            <th class="px-4 py-2 font-medium">Course Name</th>
                            <th class="px-4 py-2 font-medium">Description</th>
                            <th class="px-4 py-2 font-medium">Start date</th>
                            <th class="px-4 py-2 font-medium">Creator</th>

                            <th class="px-4 py-2 font-medium">Status</th>
                            <th class="px-4 py-2 font-medium">Certificate</th>
                            <th class="px-4 py-2 font-medium">Date created</th>
                            <th class="px-4 py-2 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dòng dữ liệu mẫu -->
                        <tr class="border-b border-gray-600">
                            <td class="px-4 py-3">C01</td>
                            <td class="px-4 py-3">Demo 01</td>
                            <td class="px-4 py-3">DesDemo 01</td>
                            <td class="px-4 py-3">08/01/2025</td>
                            <td class="px-4 py-3">Admin 01</td>
                           
                            <td class="px-4 py-3 text-green-400">Open</td>
                            <td class="px-4 py-3">Yes</td>
                             <td class="px-4 py-3">01/01/2025</td>
                            <td class="px-4 py-2 space-x-2">
                                <button onclick="window.location.href='edit_course.php'" class="px-3 py-1 bg-green-500 text-white hover:bg-yellow-600 rounded">Edit</button>
                                <button class="px-3 py-1 bg-red-600 text-white hover:bg-red-700 rounded">Delete</button>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-600">
                            <td class="px-4 py-3">C02</td>
                            <td class="px-4 py-3">Demo 02</td>
                            <td class="px-4 py-3">DesDemo 02</td>
                            <td class="px-4 py-3">18/01/2025</td>
                            <td class="px-4 py-3">Admin 02</td>
                            
                            <td class="px-4 py-3 text-red-400">Closed</td>
                            <td class="px-4 py-3">Yes</td>
                            <td class="px-4 py-3">01/01/2025</td>
                            <td class="px-4 py-2 space-x-2">
                                <button onclick="window.location.href='edit_course.php'" class="px-3 py-1 bg-green-500 text-white hover:bg-yellow-600 rounded">Edit</button>
                                <button class="px-3 py-1 bg-red-600 text-white hover:bg-red-700 rounded">Delete</button>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-600">
                            <td class="px-4 py-3">C03</td>
                            <td class="px-4 py-3">Demo 03</td>
                            <td class="px-4 py-3">DesDemo 03</td>
                            <td class="px-4 py-3">28/01/2025</td>
                            <td class="px-4 py-3">Instructor 01</td>
                            
                            <td class="px-4 py-3 text-green-400">Open</td>
                            <td class="px-4 py-3">Yes</td>
                            <td class="px-4 py-3">01/01/2025</td>
                            <td class="px-4 py-2 space-x-2">
                                <button onclick="window.location.href='edit_course.php'" class="px-3 py-1 bg-green-500 text-white hover:bg-yellow-600 rounded">Edit</button>
                                <button class="px-3 py-1 bg-red-600 text-white hover:bg-red-700 rounded">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
