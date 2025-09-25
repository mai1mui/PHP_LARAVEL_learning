<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Report Management - Admin</title>
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
      <h1 class="text-3xl font-bold">Admin Dashboard Reports</h1>
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
        <h2 class="text-lg font-semibold">👥 User</h2>
        <p class="text-2xl font-bold">1,245</p>
      </div>
      <div class="bg-gray-800 rounded-lg p-4 text-center shadow">
        <h2 class="text-lg font-semibold">📚 Course</h2>
        <p class="text-2xl font-bold">32</p>
      </div>
      <div class="bg-gray-800 rounded-lg p-4 text-center shadow">
        <h2 class="text-lg font-semibold">💰 Revenue (month)</h2>
        <p class="text-2xl font-bold">120M VND</p>
      </div>
      <div class="bg-gray-800 rounded-lg p-4 text-center shadow">
        <h2 class="text-lg font-semibold">⏳ Complete</h2>
        <p class="text-2xl font-bold">76%</p>
      </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-gray-800 p-4 rounded-lg shadow">
        <h3 class="mb-2 font-semibold">Bar chart: Students registered / course</h3>
        <canvas id="barChart"></canvas>
      </div>
      <div class="bg-gray-800 p-4 rounded-lg shadow">
        <h3 class="mb-2 font-semibold">Line chart: Visits</h3>
        <canvas id="lineChart"></canvas>
      </div>
      <div class="bg-gray-800 p-4 rounded-lg shadow">
        <h3 class="mb-2 font-semibold">Pie chart: User distribution by role</h3>
        <canvas id="pieChart"></canvas>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-gray-800 p-4 rounded-lg shadow">
      <h3 class="mb-4 font-semibold">New user list</h3>
      <table id="reportTable" class="display w-full text-white">
        <thead>
          <tr>
            <th>Name</th>
            <th>Role</th>
            <th>Registration date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>Nguyen Van A</td><td>Learner</td><td>2025-09-01</td><td>Active</td></tr>
          <tr><td>Tran Thi B</td><td>Instructor</td><td>2025-09-02</td><td>Active</td></tr>
          <tr><td>Le Van C</td><td>Learner</td><td>2025-09-03</td><td>Inactive</td></tr>
          <tr><td>Pham D</td><td>Learner</td><td>2025-09-05</td><td>Active</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ChartJS setup -->
  <script>
    new Chart(document.getElementById('barChart'), {
      type: 'bar',
      data: {
        labels: ['Course 1','Course 2','Course 3','Course 4'],
        datasets: [{label:'Học viên', data:[50,80,30,70], backgroundColor:'rgba(54,162,235,0.7)'}]
      }
    });

    new Chart(document.getElementById('lineChart'), {
      type: 'line',
      data: {
        labels: ['T2','T3','T4','T5','T6','T7','CN'],
        datasets: [{label:'Lượt truy cập', data:[100,150,120,180,200,170,220], borderColor:'rgb(75,192,192)', fill:false}]
      }
    });

    new Chart(document.getElementById('pieChart'), {
      type: 'pie',
      data: {
        labels:['Admin','Instructor','Learner','Parent','Guest'],
        datasets:[{data:[5,15,60,10,10], backgroundColor:['#f87171','#60a5fa','#34d399','#fbbf24','#a78bfa']}]
      }
    });

  </script>
</body>
</html>
