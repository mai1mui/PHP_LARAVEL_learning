<?php
// exam_edit.php
$examID = $_GET['id'] ?? "EX01"; // demo
// Dữ liệu mẫu, thực tế sẽ lấy từ DB
$exam = [
  "course_id" => "Demo01",
  "document" => "Final exam",
  "description" => "finalexam.pdf",
  "start_time" => "2025-09-15T00:00",
  "duration" => "24 hours",
  "creator" => "Admin"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Exam</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen">
  <div class="bg-gray-800 p-8 rounded-2xl shadow-lg w-full max-w-2xl">
    <h2 class="text-2xl font-bold mb-6 text-center">Edit Exam</h2>

    <form action="update_exam.php" method="POST" class="space-y-4">
      <input type="hidden" name="exam_id" value="<?php echo $examID; ?>">

      <!-- CourseID -->
      <div>
        <label class="block mb-1">Course ID</label>
        <input type="text" name="course_id" value="<?php echo $exam['course_id']; ?>"
               class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500" readonly="">
      </div>

      <!-- Document -->
      <div>
        <label class="block mb-1">Document</label>
        <input type="text" name="document" value="<?php echo $exam['document']; ?>"
               class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500">
      </div>

      <!-- Description -->
      <div>
        <label class="block mb-1">Description</label>
        <input type="text" name="description" value="<?php echo $exam['description']; ?>"
               class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500">
      </div>

      <!-- Start time -->
      <div>
        <label class="block mb-1">Start Time</label>
        <input type="datetime-local" name="start_time" value="<?php echo $exam['start_time']; ?>"
               class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500">
      </div>

      <!-- Test Duration -->
      <div>
        <label class="block mb-1">Test Duration</label>
        <input type="text" name="duration" value="<?php echo $exam['duration']; ?>"
               class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500">
      </div>

      <!-- Creator -->
      <div>
        <label class="block mb-1">Creator</label>
        <input type="text" name="creator" value="<?php echo $exam['creator']; ?>"
               class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500" readonly="">
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
