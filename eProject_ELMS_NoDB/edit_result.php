<?php
// edit_result.php
// Giả sử đã có kết nối DB và lấy dữ liệu result theo resultID
$resultID = $_GET['id'] ?? null;

// Demo data giả định
$result = [
    "learner_name" => "Nguyễn Văn A",
    "course" => "Demo 01",
    "exam" => "Midterm",
    "score" => 85,
    "status" => "Passed"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Result</title>
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
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen">
  <div class="bg-gray-800 p-8 rounded-2xl shadow-lg w-full max-w-2xl">
    <h2 class="text-2xl font-bold mb-6 text-center">Edit Result</h2>

    <form action="update_result.php" method="POST" class="space-y-4">
      <input type="hidden" name="resultID" value="<?php echo $resultID; ?>">

      <!-- Learner Name -->
      <div>
        <label class="block mb-1">Learner Name</label>
        <input type="text" name="learner_name" value="<?php echo $result['learner_name']; ?>"
               class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500">
      </div>

      <!-- Course -->
      <div>
        <label class="block mb-1">Course</label>
        <input type="text" name="course" value="<?php echo $result['course']; ?>"
               class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500">
      </div>

      <!-- Exam -->
      <div>
        <label class="block mb-1">Exam</label>
        <input type="text" name="exam" value="<?php echo $result['exam']; ?>"
               class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500">
      </div>

      <!-- Score -->
      <div>
        <label class="block mb-1">Score</label>
        <input type="number" name="score" value="<?php echo $result['score']; ?>"
               class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500">
      </div>

      <!-- Status -->
      <div>
        <label class="block mb-1">Status</label>
        <select name="status" class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500">
          <option value="Passed" <?php if($result['status']=="Passed") echo "selected"; ?>>Passed</option>
          <option value="Failed" <?php if($result['status']=="Failed") echo "selected"; ?>>Failed</option>
          <option value="Pending" <?php if($result['status']=="Pending") echo "selected"; ?>>Pending</option>
        </select>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end space-x-4">
        <button type="button" onclick="window.location.href='result.php'"
          class="px-6 py-2 border border-gray-600 text-gray-400 rounded-lg font-semibold hover:bg-gray-700">
          Cancel
        </button>
        <button type="submit"
          class="px-6 py-2 bg-primary text-textlight rounded-lg font-semibold hover:bg-primaryhover shadow-lg">
          Save changes
        </button>
      </div>
    </form>
  </div>
</body>
</html>
