<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Submission Management</title>
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
        <div class="bg-gray-800 text-white rounded-xl shadow-xl p-6 w-full max-w-8xl">
            <h2 class="text-2xl font-bold text-center mb-6">Submission Management</h2>
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
                    <select class="px-4 py-2 bg-gray-700 text-gray-400 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
                        <option value="">-- Filter by SubmissionID --</option>
                        <option value="web">SubmissionID 01</option>
                        <option value="design">SubmissionID 02</option>
                        <option value="data">SubmissionID 03</option>
                    </select>
                    <select class="px-4 py-2 bg-gray-700 text-gray-400 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
                        <option value="">-- Filter by Course --</option>
                        <option value="web">Demo 01</option>
                        <option value="design">Demo 02</option>
                        <option value="data">Demo 03</option>
                    </select>
                    <select class="px-4 py-2 bg-gray-700 text-gray-400 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
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

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full text-left ">
                    <thead>
                        <tr class="bg-gray-700 rounded-lg">
                            <th class="px-4 py-2 w-28">SubmissionID</th>
                            <th class="px-4 py-2 w-40">Learner</th>
                            <th class="px-4 py-2 w-32">Course</th>
                            <th class="px-4 py-2 w-40">Answer</th>
                            <th class="px-4 py-2 w-16 text-center">Mark</th>
                            <th class="px-4 py-2 w-28">Feedback</th>
                            <th class="px-4 py-2 w-32">Date Submitted</th>
                            <th class="px-4 py-2 w-28">Status</th>
                            <th class="px-4 py-2 w-40 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody >
                        <tr class="border-b border-gray-600">
                            <td class="px-4 py-2">SUB01</td>
                            <td class="px-4 py-2 truncate max-w-[160px]">Nguyen Van A</td>
                            <td class="px-4 py-2">Demo 01</td>
                            <td class="px-4 py-2 truncate max-w-[160px]">answerNVA.pdf</td>
                            <td class="px-4 py-2 text-center">100</td>
                            <td class="px-4 py-2 truncate max-w-[200px]">Good</td>
                            <td class="px-4 py-2">2025-09-05</td>
                            <td class="px-4 py-3 text-green-400">Submitted</td>
                            <td class="px-4 py-2 flex space-x-2 justify-center">
                                <button onclick="window.location.href='edit_submission.php'" class="px-3 py-1 bg-green-500 text-white hover:bg-yellow-600 rounded whitespace-nowrap">Edit</button>
                                <button class="px-3 py-1 bg-red-600 text-white hover:bg-red-700 rounded whitespace-nowrap">Delete</button>
                            </td>
                        </tr>
                        <!-- Row 2 -->
                        <tr class="border-b border-gray-600">
                            <td class="px-4 py-2">SUB02</td>
                            <td class="px-4 py-2 truncate max-w-[160px]">Nguyen Van B</td>
                            <td class="px-4 py-2">Demo 02</td>
                            <td class="px-4 py-2 truncate max-w-[160px]">answerNVB.pdf</td>
                            <td class="px-4 py-2 text-center">100</td>
                            <td class="px-4 py-2 truncate max-w-[200px]">Good</td>
                            <td class="px-4 py-2">2025-09-15</td>
                            <td class="px-4 py-3 text-yellow-400">Late</td>
                            <td class="px-4 py-2 flex space-x-2 justify-center">
                                <button onclick="window.location.href='edit_submission.php'" class="px-3 py-1 bg-green-500 text-white hover:bg-yellow-600 rounded whitespace-nowrap">Edit</button>
                                <button class="px-3 py-1 bg-red-600 text-white hover:bg-red-700 rounded whitespace-nowrap">Delete</button>
                            </td>
                        </tr>
                        <!-- Row 3 -->
                        <tr class="border-b border-gray-600">
                            <td class="px-4 py-2">SUB03</td>
                            <td class="px-4 py-2 truncate max-w-[160px]">Nguyen Van C</td>
                            <td class="px-4 py-2">Demo 03</td>
                            <td class="px-4 py-2 truncate max-w-[160px]">Null</td>
                            <td class="px-4 py-2 text-center">100</td>
                            <td class="px-4 py-2 truncate max-w-[200px]">Good</td>
                            <td class="px-4 py-2">2025-09-15</td>
                            <td class="px-4 py-3 text-red-400">Not submit</td>
                            <td class="px-4 py-2 flex space-x-2 justify-center">
                                <button onclick="window.location.href='edit_submission.php'" class="px-3 py-1 bg-green-500 text-white hover:bg-yellow-600 rounded whitespace-nowrap">Edit</button>
                                <button class="px-3 py-1 bg-red-600 text-white hover:bg-red-700 rounded whitespace-nowrap">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </body>
</html>
