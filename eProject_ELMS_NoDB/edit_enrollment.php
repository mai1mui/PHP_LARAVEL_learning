<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit enrollment</title>
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
    <body class="bg-gray-900 text-gray-200 flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-xl shadow-2xl p-8 w-full max-w-2xl">
            <h2 class="text-2xl font-bold mb-6 text-center">Edit Enrollment</h2>
            <form class="space-y-6">
                <!-- Enrollment ID (read only) -->
                <div>
                    <label for="enroll-id" class="block text-sm font-medium mb-1">Enrollment ID</label>
                    <input type="text" id="enroll-id" value="#ENR001" readonly
                           class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-400 cursor-not-allowed">
                </div>

                <!-- Learner -->
                <div>
                    <label for="learner" class="block text-sm font-medium mb-1">Learner</label>
                    <input type="text" id="learner" value="Nguyễn Văn A"
                           class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <!-- Course -->
                <div>
                    <label for="course" class="block text-sm font-medium mb-1">Course</label>
                    <input type="text" id="course" value="Demo 01"
                           class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <!-- Registration Date -->
                <div>
                    <label for="reg-date" class="block text-sm font-medium mb-1">Registration Date</label>
                    <input type="date" id="reg-date" value="2025-10-15"
                           class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium mb-1">Status</label>
                    <select id="status" 
                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg 
                            focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        <option selected>Processing</option>
                        <option>Paid</option>
                        <option>Not confirmed</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-4 pt-4">
                    <button type="button" onclick="window.location.href='enrollment.php'"
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
