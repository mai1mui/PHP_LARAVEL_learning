<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Detail</title>
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
                        graytext: '#9ca3af',
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

    <div class="bg-cardblue text-textlight rounded-xl shadow-2xl p-8 w-full max-w-2xl relative">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Feedback details</h2>
            <button onclick="window.location.href='feedback.php'" class="px-4 py-2 text-sm font-medium text-textlight bg-gray-600 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>Come back
            </button>
        </div>
        
        <!-- Feedback Details Section -->
        <div class="space-y-4 mb-8">
            <div>
                <span class="block text-sm font-medium text-gray-400">FeedbackID</span>
                <p class="text-lg font-semibold">FE01</p>
            </div>
            <div>
                <span class="block text-sm font-medium text-gray-400">User</span>
                <p class="text-lg font-semibold">Guest01</p>
            </div>
            <div>
                <span class="block text-sm font-medium text-gray-400">Rate</span>
                <div class="flex items-center space-x-1 mt-1">
                    <!-- Rating stars, dynamic based on data -->
                    <i class="fa-solid fa-star text-yellow-400"></i>
                    <i class="fa-solid fa-star text-yellow-400"></i>
                    <i class="fa-solid fa-star text-yellow-400"></i>
                    <i class="fa-solid fa-star text-yellow-400"></i>
                    <i class="fa-solid fa-star text-yellow-400"></i>
                </div>
            </div>
            <div>
                <span class="block text-sm font-medium text-gray-400">Content</span>
                <p class="mt-1 bg-gray-700 p-4 rounded-lg">Very good and easy to understand, but needs more real life examples.</p>
            </div>
            <div>
                <span class="block text-sm font-medium text-gray-400">Response date</span>
                <p class="text-lg font-semibold">05/08/2025</p>
            </div>
            <div>
                <span class="block text-sm font-medium text-gray-400">Status</span>
                <p class="mt-1 text-yellow-400 font-medium">Waiting</p>
            </div>
        </div>

        <!-- Admin Actions Section -->
        <div class="border-t border-gray-700 pt-6">
            <h3 class="text-xl font-semibold mb-4">Admin Action</h3>
            <div class="mb-4">
                <label for="admin-reply" class="block text-sm font-medium text-gray-400 mb-1">Reply to feedback</label>
                <textarea id="admin-reply" name="admin-reply" rows="4" placeholder="Enter your answer here..." class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200"></textarea>
            </div>
            <div class="flex justify-end space-x-4">
                <button onclick="window.location.href='feedback.php'" class="px-4 py-2 text-sm font-medium text-textlight bg-green-600 rounded-lg hover:bg-green-700 transition-colors duration-200">
                    <i class="fa-solid fa-paper-plane mr-2"></i>Send reply
                </button>
                <button class="px-4 py-2 text-sm font-medium text-textlight bg-red-600 rounded-lg hover:bg-red-700 transition-colors duration-200">
                    <i class="fa-solid fa-trash-can mr-2"></i>Delete feedback
                </button>
            </div>
        </div>
    </div>

</body>
</html>
