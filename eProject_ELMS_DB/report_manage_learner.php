<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Learner Reports</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.tailwindcss.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen p-6">
  <div class="max-w-7xl mx-auto space-y-6">

    <!-- Header + Filter -->
    <div class="flex justify-between items-center">
      <h1 class="text-3xl font-bold">Learner Dashboard Reports</h1>
      <select class="px-3 py-2 bg-gray-800 border border-gray-700 rounded">
        <option>Today</option>
        <option>This week</option>
        <option>This month</option>
        <option>Custom</option>
      </select>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div class="bg-gray-800 rounded-lg p-4 text-center shadow">
        <h2 class="text-lg font-semibold">📚 Registered Courses</h2>
        <p class="text-2xl font-bold">5</p>
      </div>
      <div class="bg-gray-800 rounded-lg p-4 text-center shadow">
        <h2 class="text-lg font-semibold">⏳ Progress TB</h2>
        <p class="text-2xl font-bold">72%</p>
      </div>
      <div class="bg-gray-800 rounded-lg p-4 text-center shadow">
        <h2 class="text-lg font-semibold">✅ Course completed</h2>
        <p class="text-2xl font-bold">3</p>
      </div>
      <div class="bg-gray-800 rounded-lg p-4 text-center shadow">
        <h2 class="text-lg font-semibold">⭐ Average score</h2>
        <p class="text-2xl font-bold">8.5</p>
      </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-gray-800 p-4 rounded-lg shadow">
        <h3 class="mb-2 font-semibold">Line chart: Learning progress over time</h3>
        <canvas id="progressChart"></canvas>
      </div>
      <div class="bg-gray-800 p-4 rounded-lg shadow">
        <h3 class="mb-2 font-semibold">Bar chart: Subject Score</h3>
        <canvas id="scoreChart"></canvas>
      </div>
      <div class="bg-gray-800 p-4 rounded-lg shadow">
        <h3 class="mb-2 font-semibold">Pie chart: Distribution of study time</h3>
        <canvas id="timeChart"></canvas>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-gray-800 p-4 rounded-lg shadow">
      <h3 class="mb-4 font-semibold">List of registered courses</h3>
      <table id="learnerTable" class="display w-full text-white">
        <thead>
          <tr>
            <th>Course</th>
            <th>Progress</th>
            <th>Point</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>HTML Basics</td><td>100%</td><td>9.0</td><td>Complete</td></tr>
          <tr><td>CSS Advanced</td><td>85%</td><td>8.0</td><td>Studying</td></tr>
          <tr><td>JavaScript Intro</td><td>60%</td><td>7.5</td><td>Studying</td></tr>
          <tr><td>React Fundamentals</td><td>40%</td><td>-</td><td>Unfinished</td></tr>
          <tr><td>PHP & MySQL</td><td>70%</td><td>8.5</td><td>Studying</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ChartJS setup -->
  <script>
    // Tiến độ học tập theo thời gian
    new Chart(document.getElementById('progressChart'), {
      type: 'line',
      data: {
        labels: ['Tuần 1','Tuần 2','Tuần 3','Tuần 4','Tuần 5'],
        datasets: [{label:'% Hoàn thành', data:[20,40,55,70,72], borderColor:'rgb(75,192,192)', fill:false}]
      }
    });

    // Điểm theo từng môn
    new Chart(document.getElementById('scoreChart'), {
      type: 'bar',
      data: {
        labels: ['HTML','CSS','JS','React','PHP'],
        datasets: [{label:'Điểm', data:[9,8,7.5,0,8.5], backgroundColor:'rgba(54,162,235,0.7)'}]
      }
    });

    // Phân bố thời gian học
    new Chart(document.getElementById('timeChart'), {
      type: 'pie',
      data: {
        labels:['Morning','Afternoon','Evening'],
        datasets:[{data:[20,35,45], backgroundColor:['#f87171','#60a5fa','#34d399']}]
      }
    });

  </script>
</body>
</html>
