<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Course</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
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
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-darkblue flex items-center justify-center min-h-screen p-4">
  <div class="bg-cardblue text-textlight rounded-xl shadow-2xl p-8 w-full max-w-2xl relative">
    <h2 class="text-2xl font-bold mb-6 text-center">Edit Course</h2>

    <form id="edit-course-form" class="space-y-6">

      <!-- Course ID -->
      <div>
        <label for="course-id" class="block text-sm font-medium mb-1">CourseID</label>
        <input type="text" id="course-id" value="C01"
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary" readonly>
      </div>

      <!-- Course Name -->
      <div>
        <label for="course-name" class="block text-sm font-medium mb-1">Course Name</label>
        <input type="text" id="course-name" value="Demo 01" required
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary">
      </div>

      <!-- Description -->
      <div>
        <label for="description" class="block text-sm font-medium mb-1">Description</label>
        <textarea id="description" rows="3" required
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary">DesDemo 01</textarea>
      </div>

      <!-- Creator -->
      <div>
        <label for="creator" class="block text-sm font-medium mb-1">Creator</label>
        <input type="text" id="creator" value="Admin 01"
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary" readonly>
      </div>

      <!-- Date Created -->
      <div>
        <label for="date-created" class="block text-sm font-medium mb-1">Date Created</label>
        <input type="date" id="date-created" value="2025-01-01" readonly
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary">
      </div>

      <!-- Start Date -->
      <div>
        <label for="start-date" class="block text-sm font-medium mb-1">Start Date</label>
        <input type="date" id="start-date" value="2025-01-08" required
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary">
      </div>

      <!-- Status -->
      <div>
        <label for="status" class="block text-sm font-medium mb-1">Status</label>
        <select id="status"
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary" required>
          <option value="open" selected>Open</option>
          <option value="closed">Closed</option>
        </select>
      </div>

      <!-- Certificate -->
      <div>
        <label for="certificate" class="block text-sm font-medium mb-1">Certificate</label>
        <select id="certificate"
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary" required>
          <option value="yes" selected>Yes</option>
          <option value="no">No</option>
        </select>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end space-x-4">
        <button type="button" onclick="window.location.href='course.php'"
          class="px-6 py-2 border border-gray-600 text-gray-400 rounded-lg font-semibold hover:bg-gray-700">
          Cancel
        </button>
        <button type="button" onclick="window.location.href='course.php'"
          class="px-6 py-2 bg-primary text-textlight rounded-lg font-semibold hover:bg-primaryhover shadow-lg">
          Save
        </button>
      </div>
    </form>
  </div>
</body>
</html>
