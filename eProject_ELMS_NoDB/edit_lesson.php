<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Lesson</title>
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
</head>
<body class="bg-darkblue min-h-screen flex items-center justify-center p-6">
  <div class="bg-cardblue w-full max-w-3xl rounded-2xl shadow-lg p-8">
    <h2 class="text-2xl font-bold text-textlight text-center mb-6">Edit Lesson</h2>

    <form class="space-y-6">
      <!-- Lesson ID -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Lesson ID</label>
        <input type="text" value="L01" readonly
          class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 text-gray-400 cursor-not-allowed">
      </div>

      <!-- Course -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Course</label>
        <select class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-textlight focus:ring-2 focus:ring-primary">
          <option>Demo 01</option>
          <option>Demo 02</option>
        </select>
      </div>

      <!-- Lesson Name -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Lesson Name</label>
        <input type="text" value="Lesson01: Intro 01"
          class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-textlight focus:ring-2 focus:ring-primary">
      </div>

      <!-- Content -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Content (link/file)</label>
        <input type="text" value="link:intro01.pdf"
          class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-textlight focus:ring-2 focus:ring-primary">
      </div>

      <!-- Lesson Type -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Lesson Type</label>
        <select class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-textlight focus:ring-2 focus:ring-primary">
          <option selected>Video</option>
          <option>Document</option>
          <option>Audio</option>
        </select>
      </div>

      <!-- Ordinal -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Ordinal</label>
        <input type="number" value="1"
          class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-textlight focus:ring-2 focus:ring-primary">
      </div>

      <!-- Status -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
        <select class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-600 text-textlight focus:ring-2 focus:ring-primary">
          <option selected>Approved</option>
          <option>Pending approval</option>
        </select>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end space-x-4 pt-4">
        <button type="button" onclick="window.location.href='lesson.php'"
          class="px-6 py-2 rounded-lg border border-gray-600 text-gray-300 font-semibold hover:bg-gray-700 transition">
          Cancel
        </button>
        <button type="submit"
          class="px-6 py-2 rounded-lg bg-primary text-textlight font-semibold hover:bg-primaryhover shadow-md transition">
          Update
        </button>
      </div>
    </form>
  </div>
</body>
</html>
