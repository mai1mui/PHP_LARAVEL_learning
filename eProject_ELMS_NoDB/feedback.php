<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Feedback Management</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Thêm icon ngôi sao -->
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
            .star-rating .fa-star {
                color: #4b5563; /* Default gray */
            }
            .star-rating .filled {
                color: #f59e0b; /* Filled yellow */
            }
        </style>
    </head>
    <body class="bg-darkblue flex items-center justify-center min-h-screen p-4">

        <div class="bg-cardblue text-textlight rounded-xl shadow-2xl p-8 w-full max-w-8xl relative">
            <h2 class="text-2xl font-bold mb-6 text-center">Feedback Management</h2>
            <!-- Search box -->
            <div class="flex justify-center mb-6 relative">
                <input type="text" placeholder="Search"
                       class="w-52 px-4 py-2 pl-10 bg-gray-700 text-gray-200 border border-gray-600 
                       rounded-lg focus:outline-none focus:ring-2 focus:ring-primary transition-colors duration-200">
                <i class="fas fa-search absolute left-1/2 transform -translate-x-24 top-2.5 text-gray-400"></i>
            </div>
            <!--  bộ lọc -->
            <div class="flex flex-col md:flex-row items-center justify-between mb-6 space-y-4 md:space-y-0">

                <!-- Các bộ lọc -->
                <div class="flex flex-col md:flex-row items-center justify-center mx-auto space-y-4 md:space-y-0 md:space-x-4 w-full md:w-auto">
                    <select class="px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
                        <option value="">-- Filter by User--</option>
                        <option value="web">Learner</option>
                        <option value="design">Guest</option>
                        <option value="data">Instructor</option>
                    </select>
                    <select class="px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
                        <option value="">-- Filter by Rating --</option>
                        <option value="5">&#xf005;&#xf005;&#xf005;&#xf005;&#xf005; (5 Stars)</option>
                        <option value="4">&#xf005;&#xf005;&#xf005;&#xf005;&#xf006; (4 Stars)</option>
                        <option value="3">&#xf005;&#xf005;&#xf005;&#xf006;&#xf006; (3 Stars)</option>
                        <option value="2">&#xf005;&#xf005;&#xf006;&#xf006;&#xf006; (2 Stars)</option>
                        <option value="1">&#xf005;&#xf006;&#xf006;&#xf006;&#xf006; (1 Star)</option>
                    </select>

                    <select class="px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
                        <option value="">-- Filter by Status --</option>
                        <option value="published">Submitted</option>
                        <option value="draft">Late</option>
                        <option value="draft">Not submit</option>
                    </select>

                </div>
            </div>
            <div class="flex items-center justify-between mb-4">

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
                            <th class="px-4 py-2 font-medium">FeedbackID</th>
                            <th class="px-4 py-2 font-medium">User</th>
                            <th class="px-4 py-2 font-medium">Rate</th>
                            <th class="px-4 py-2 font-medium">Content</th>
                            <th class="px-4 py-2 font-medium">Response date</th>
                            <th class="px-4 py-2 font-medium">Status</th>
                            <th class="px-4 py-2 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Example01 -->
                        <tr class="border-b border-gray-600">
                            <td class="px-4 py-3">FE01</td>
                            <td class="px-4 py-3">Learner01</td>
                            <td class="px-4 py-3 star-rating">
                                <i class="fa-solid fa-star filled"></i>
                                <i class="fa-solid fa-star filled"></i>
                                <i class="fa-solid fa-star filled"></i>
                                <i class="fa-solid fa-star filled"></i>
                                <i class="fa-solid fa-star filled"></i>
                            </td>
                            <td class="px-4 py-3 truncate max-w-xs">Very good and easy to understand, but needs more real life examples.</td>
                            <td class="px-4 py-3">05/08/2025</td>
                            <td class="px-4 py-3 text-green-400">Processed</td>
                            <td class="px-4 py-2 flex space-x-2 justify-center">
                                <button onclick="window.location.href='feedback_detail.php'" class="px-3 py-1 bg-blue-600 text-white hover:bg-blue-800 rounded whitespace-nowrap">View</button>
                            </td>
                        </tr>
                        <!-- Example02 -->
                        <tr class="border-b border-gray-600">
                            <td class="px-4 py-3">FE02</td>
                            <td class="px-4 py-3">Guest01</td>
                            <td class="px-4 py-3 star-rating">
                                <i class="fa-solid fa-star filled"></i>
                                <i class="fa-solid fa-star filled"></i>
                                <i class="fa-solid fa-star filled"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </td>
                            <td class="px-4 py-3 truncate max-w-xs">The content is a bit old. Need to update with new software.</td>
                            <td class="px-4 py-3">05/08/2025</td>
                            <td class="px-4 py-3 text-yellow-400">Waiting</td>
                            <td class="px-4 py-2 flex space-x-2 justify-center">
                                <button onclick="window.location.href='feedback_detail.php'" class="px-3 py-1 bg-blue-600 text-white hover:bg-blue-800 rounded whitespace-nowrap">View</button>
                            </td>
                        </tr>
                        <!-- Example03 -->
                        <tr class="border-b border-gray-600">
                            <td class="px-4 py-3">FE03</td>
                            <td class="px-4 py-3">Guest02</td>
                            <td class="px-4 py-3 star-rating">
                                <i class="fa-solid fa-star filled"></i>
                                <i class="fa-solid fa-star filled"></i>
                                <i class="fa-solid fa-star filled"></i>
                                <i class="fa-solid fa-star filled"></i>
                                <i class="fa-solid fa-star"></i>
                            </td>
                            <td class="px-4 py-3 truncate max-w-xs">Need more practical examples to understand the concepts easily.</td>
                            <td class="px-4 py-3">05/08/2025</td>
                            <td class="px-4 py-3 text-green-400">Processed</td>
                            <td class="px-4 py-2 flex space-x-2 justify-center">
                                <button onclick="window.location.href='feedback_detail.php'" class="px-3 py-1 bg-blue-600 text-white hover:bg-blue-800 rounded whitespace-nowrap">View</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </body>
</html>
