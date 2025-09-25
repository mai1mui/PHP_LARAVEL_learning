<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add New Lesson</title>
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
    <body class="bg-darkblue min-h-screen flex items-center justify-center p-6">
        <div class="bg-cardblue w-full max-w-3xl rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-textlight text-center mb-6">Add New Lesson</h2>

            <form class="space-y-6">
                <!-- Course -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Course</label>
                    <select class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-textlight focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">-- Select Course --</option>
                        <option value="demo01">Demo 01</option>
                        <option value="demo02">Demo 02</option>
                    </select>
                </div>

                <!-- Lesson Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Lesson Name</label>
                    <input type="text" placeholder="Enter lesson name"
                           class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-textlight placeholder-gray-400 focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>

                <!-- Content -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Content (link/file)</label>
                    <input type="text" placeholder="e.g., link:intro01.pdf"
                           class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-textlight placeholder-gray-400 focus:ring-2 focus:ring-primary">
                </div>

                <!-- Lesson Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Lesson Type</label>
                    <select class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-textlight focus:ring-2 focus:ring-primary">
                        <option value="">-- Select Type --</option>
                        <option>Video</option>
                        <option>Document</option>
                        <option>Audio</option>
                    </select>
                </div>

                <!-- Ordinal -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Ordinal</label>
                    <input type="number" placeholder="Enter order (1, 2, 3...)"
                           class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-textlight focus:ring-2 focus:ring-primary">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                    <select class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-textlight focus:ring-2 focus:ring-primary">
                        <option value="pending">Pending approval</option>
                        <option value="approved">Approved</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-4 pt-4">
                    <button type="button" onclick="window.location.href = 'lesson.php'"
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
