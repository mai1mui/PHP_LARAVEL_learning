<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Course</title>
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
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center">

  <div class="bg-gray-800 p-6 rounded-2xl shadow-lg w-full max-w-2xl">
    <h2 class="text-2xl font-bold mb-6 text-center">Add New Course</h2>
    
    <form action="course_addnew.php" method="POST" class="space-y-4">
      
      <!-- Course Name -->
      <div>
        <label class="block mb-1 font-medium">Course Name</label>
        <input type="text" name="course_name" required 
          class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
      </div>

      <!-- Description -->
      <div>
        <label class="block mb-1 font-medium">Description</label>
        <textarea name="description" rows="3" required
          class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
      </div>

      <!-- Start Date -->
      <div>
        <label class="block mb-1 font-medium">Start Date</label>
        <input type="date" name="start_date" required 
          class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
      </div>

      <!-- Status -->
      <div>
        <label class="block mb-1 font-medium">Status</label>
        <select name="status" required
          class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
          <option value="Open">Open</option>
          <option value="Closed">Closed</option>
        </select>
      </div>

      <!-- Certificate -->
      <div>
        <label class="block mb-1 font-medium">Certificate</label>
        <select name="certificate" required
          class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:outline-none">
          <option value="Yes">Yes</option>
          <option value="No">No</option>
        </select>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end space-x-4 pt-4">
        <button type="button" onclick="window.location.href='course.php'"
          class="px-6 py-2 rounded-lg border border-gray-600 text-gray-300 font-semibold hover:bg-gray-700 transition">
          Cancel
        </button>
        <button type="submit"onclick="window.location.href='course.php'"
          class="px-6 py-2 rounded-lg bg-primary text-textlight font-semibold hover:bg-primaryhover shadow-md transition">
          Save
        </button>
      </div>

    </form>
  </div>

</body>
</html>
