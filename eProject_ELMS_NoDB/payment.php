<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Payment Management</title>
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
            <h2 class="text-2xl font-bold text-center mb-6">Payment Management</h2>
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
                        <option value="">-- Filter by Learner --</option>
                        <option value="web">Nguyen Van A</option>
                        <option value="design">Nguyen Van B</option>
                        <option value="data">Nguyen Van C</option>
                    </select>
                    <select class="px-4 py-2 bg-gray-700 text-gray-400 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
                        <option value="">-- Filter by Course --</option>
                        <option value="web">Demo 01</option>
                        <option value="design">Demo 02</option>
                        <option value="data">Demo 03</option>
                    </select>
                    <select class="px-4 py-2 bg-gray-700 text-gray-400 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
                        <option value="">-- Filter by Status --</option>
                        <option value="published">Paid</option>
                        <option value="draft">Processing</option>
                        <option value="draft">Not confirmed</option>
                    </select>

                </div>
            </div>
            <div class="flex items-center justify-between mb-4">
                <!-- Nút Add new payment -->
                <button  onclick="window.location.href='add_payment.php'" class="px-6 py-2 bg-primary text-textlight rounded-lg font-semibold hover:bg-primaryhover transition-colors duration-200 shadow-lg flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Add new payment</span>
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
                            <th class="py-3 px-4 text-left rounded-tl-lg w-28">PaymentID</th>
                            <th class="py-3 px-4 text-left w-28">Learner</th>
                            <th class="py-3 px-4 text-left w-28">Course</th>
                            <th class="py-3 px-4 text-left w-28">Amount</th>
                            <th class="py-3 px-4 text-left w-28">Payment date</th>
                            <th class="py-3 px-4 text-left w-28">Status</th>
                            <th class="py-3 px-4 text-left w-28">Transaction Ref</th>
                            <th class="py-3 px-4 text-left rounded-tr-lg w-28">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Hàng dữ liệu mẫu 1 -->
                        <tr class="border-b border-gray-700 hover:bg-gray-800 transition-colors duration-200">
                            <td class="py-3 px-4">#123456</td>
                            <td class="py-3 px-4">Nguyễn Văn A</td>
                            <td class="py-3 px-4">Demo 01</td>
                            <td class="py-3 px-4">500,000đ</td>
                            <td class="py-3 px-4">10/10/2023</td>
                            <td class="py-3 px-4">
                                <span class="px-4 py-3 text-green-400">Paid</span>
                            </td>
                            <td class="py-3 px-4">10102023ACB</td>
                            <td class="px-4 py-2 space-x-2">
                                <button onclick="window.location.href='edit_payment.php'" class="px-3 py-1 bg-green-500 text-white hover:bg-yellow-600 rounded">Edit</button>
                                <button class="px-3 py-1 bg-red-600 text-white hover:bg-red-700 rounded">Delete</button>
                            </td>
                        </tr>
                        <!-- Hàng dữ liệu mẫu 2 -->
                        <tr class="border-b border-gray-700 hover:bg-gray-800 transition-colors duration-200">
                            <td class="py-3 px-4">#135666</td>
                            <td class="py-3 px-4">Nguyễn Văn B</td>
                            <td class="py-3 px-4">Demo 02</td>
                            <td class="py-3 px-4">500,000đ</td>
                            <td class="py-3 px-4">10/10/2023</td>
                            <td class="py-3 px-4">
                                <span class="px-4 py-3 text-yellow-400">Processing</span>
                            </td>
                            <td class="py-3 px-4">10102023VIB</td>
                            <td class="px-4 py-2 space-x-2">
                                <button onclick="window.location.href='edit_payment.php'" class="px-3 py-1 bg-green-500 text-white hover:bg-yellow-600 rounded">Edit</button>
                                <button class="px-3 py-1 bg-red-600 text-white hover:bg-red-700 rounded">Delete</button>
                            </td>
                        </tr>
                        <!-- Hàng dữ liệu mẫu 3 -->
                        <tr class="border-b border-gray-700 hover:bg-gray-800 transition-colors duration-200">
                            <td class="py-3 px-4">#132678</td>
                            <td class="py-3 px-4">Nguyễn Văn C</td>
                            <td class="py-3 px-4">Demo 03</td>
                            <td class="py-3 px-4">500,000đ</td>
                            <td class="py-3 px-4">10/10/2023</td>
                            <td class="py-3 px-4">
                                <span class="px-4 py-3 text-red-400">Not confirmed</span>
                            </td>
                            <td class="py-3 px-4">10102023VPB</td>
                            <td class="px-4 py-2 space-x-2">
                                <button onclick="window.location.href='edit_payment.php'" class="px-3 py-1 bg-green-500 text-white hover:bg-yellow-600 rounded">Edit</button>
                                <button class="px-3 py-1 bg-red-600 text-white hover:bg-red-700 rounded">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </body>
</html>
