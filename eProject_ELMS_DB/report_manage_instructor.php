<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Instructor Reports</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.tailwindcss.min.css">
  <style>
      .course-table {
  border-collapse: collapse;
  width: 100%;
  font-size: 14px;
}

.course-table thead {
  background-color: #1f2937; /* xám đậm */
}

.course-table th, 
.course-table td {
  padding: 12px 16px;
  text-align: left;
}

.course-table tbody tr:nth-child(odd) {
  background-color: #2d3748; /* sọc nền xen kẽ */
}

.course-table tbody tr:hover {
  background-color: #374151; /* hover highlight */
}

.course-table th {
  font-weight: 600;
  color: #f9fafb;
  border-bottom: 2px solid #4b5563;
}

.course-table td {
  border-bottom: 1px solid #374151;
}

  </style>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen p-6">
  <div class="max-w-7xl mx-auto space-y-6">

    <!-- Header + Filter -->
    <div class="flex justify-between items-center">
      <h1 class="text-3xl font-bold">Instructor Dashboard Reports</h1>
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
        <h2 class="text-lg font-semibold">📚 Courses currently being taught</h2>
        <p class="text-2xl font-bold">4</p>
      </div>
      <div class="bg-gray-800 rounded-lg p-4 text-center shadow">
        <h2 class="text-lg font-semibold">👥 Total number of students</h2>
        <p class="text-2xl font-bold">120</p>
      </div>
      <div class="bg-gray-800 rounded-lg p-4 text-center shadow">
        <h2 class="text-lg font-semibold">✅ Completion rate</h2>
        <p class="text-2xl font-bold">78%</p>
      </div>
      <div class="bg-gray-800 rounded-lg p-4 text-center shadow">
        <h2 class="text-lg font-semibold">💰 Monthly Revenue</h2>
        <p class="text-2xl font-bold">15,000,000₫</p>
      </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-gray-800 p-4 rounded-lg shadow">
        <h3 class="mb-2 font-semibold">Bar chart: Students by course</h3>
        <canvas id="studentsChart"></canvas>
      </div>
      <div class="bg-gray-800 p-4 rounded-lg shadow">
        <h3 class="mb-2 font-semibold">Line chart: Monthly Revenue</h3>
        <canvas id="revenueChart"></canvas>
      </div>
      <div class="bg-gray-800 p-4 rounded-lg shadow">
        <h3 class="mb-2 font-semibold">Pie chart: Level of participation</h3>
        <canvas id="engagementChart"></canvas>
      </div>
    </div>

    <div class="bg-gray-800 p-4 rounded-lg shadow">
  <h3 class="mb-4 font-semibold text-white">Course List</h3>
  <table id="instructorTable" class="course-table w-full text-white">
    <thead>
      <tr>
        <th>Course</th>
        <th>Progress</th>
      </tr>
    </thead>
    <tbody>
      <tr><td>HTML Basics</td><td>100%</td></tr>
      <tr><td>CSS Advanced</td><td>85%</td></tr>
      <tr><td>JavaScript Intro</td><td>60%</td></tr>
      <tr><td>React Fundamentals</td><td>40%</td></tr>
      <tr><td>PHP & MySQL</td><td>75%</td></tr>
    </tbody>
  </table>
</div>


  <!-- ChartJS setup -->
  <script>
    // Bar chart - học viên theo khóa học
    new Chart(document.getElementById('studentsChart'), {
      type: 'bar',
      data: {
        labels: ['HTML', 'CSS', 'JS', 'React', 'PHP'],
        datasets: [{label: 'Học viên', data: [30, 25, 20, 15, 30], backgroundColor:'rgba(54,162,235,0.7)'}]
      }
    });

    // Line chart - doanh thu theo tháng
    new Chart(document.getElementById('revenueChart'), {
      type: 'line',
      data: {
        labels: ['Th1','Th2','Th3','Th4','Th5','Th6'],
        datasets: [{label:'Doanh thu (triệu)', data:[10,12,15,13,17,15], borderColor:'rgb(75,192,192)', fill:false}]
      }
    });

  </script>
</body>
</html>
