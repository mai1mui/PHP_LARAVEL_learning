<?php
// exam_addnew.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Exam</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen">
  <div class="bg-gray-800 p-8 rounded-2xl shadow-lg w-full max-w-2xl">
    <h2 class="text-2xl font-bold mb-6 text-center">Add New Exam</h2>

    <form action="save_exam.php" method="POST" class="space-y-4">
      <!-- CourseID -->
      <div>
        <label class="block mb-1">Course ID</label>
        <input type="text" name="course_id"
               class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500"
               placeholder="Demo01">
      </div>

      <!-- Document -->
      <label class="block mb-1">Document</label>
      <select class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500">
                        <option value="web">Exercises</option>
                        <option value="design">Quiz</option>
                        <option value="data">Essays</option>
                        <option value="design">Exam</option>
                    </select>

      <!-- Description -->
      <div>
        <label class="block mb-1">Description</label>
        <input type="text" name="description"
               class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500"
               placeholder="finalexam.pdf">
      </div>

      <!-- Start time -->
      <div>
        <label class="block mb-1">Start Time</label>
        <input type="datetime-local" name="start_time"
               class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500">
      </div>

      <!-- Test Duration -->
      <div>
        <label class="block mb-1">Test Duration</label>
        <input type="text" name="duration"
               class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500"
               placeholder="24 hours">
      </div>

      <!-- Buttons -->
      <div class="flex justify-between pt-4">
        <a href="exam.php" class="bg-gray-600 px-4 py-2 rounded-lg hover:bg-gray-500">Cancel</a>
        <button type="submit" class="bg-blue-600 px-4 py-2 rounded-lg hover:bg-blue-500">Save</button>
      </div>
    </form>
  </div>
</body>
</html>
