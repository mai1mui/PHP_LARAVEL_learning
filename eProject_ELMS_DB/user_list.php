<?php
session_start();

// Kiểm tra quyền Admin (không phân biệt hoa-thường)
if (!isset($_SESSION["AccountID"]) || strcasecmp($_SESSION["ARole"] ?? '', "Admin") !== 0) {
    header("Location: login.php");
    exit;
}

// Kết nối database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elmsdb";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset('utf8mb4');

// ------ Hàm render 1 dòng ------
function render_row($r) {
    $created = !empty($r['CreatedAt']) ? date('d/m/Y', strtotime($r['CreatedAt'])) : '';
    $statusBadge = ($r['AStatus'] === 'Active')
        ? '<span class="px-4 py-1 text-green-400 rounded-full bg-gray-900">Active</span>'
        : '<span class="px-4 py-1 text-red-400 rounded-full bg-gray-900">Inactive</span>';

    echo '<tr class="border-b border-gray-700 hover:bg-gray-800 transition-colors duration-200">
        <td class="py-3 px-4">'.htmlspecialchars($r['AccountID']).'</td>
        <td class="py-3 px-4">'.htmlspecialchars($r['AName']).'</td>
        <td class="py-3 px-4">'.htmlspecialchars($r['Email'] ?? '').'</td>
        <td class="py-3 px-4">********</td>
        <td class="py-3 px-4">'.htmlspecialchars($r['ARole']).'</td>
        <td class="py-3 px-4">'.$statusBadge.'</td>
        <td class="py-3 px-4">'.$created.'</td>
        <td class="px-4 py-2 space-x-2">
            <button onclick="window.location.href=\'edit_user.php?id='.rawurlencode($r['AccountID']).'\'" class="px-3 py-1 bg-green-500 text-white hover:bg-yellow-600 rounded">Edit</button>
            <button onclick="if(confirm(\'Are you sure?\')) window.location.href=\'delete_user.php?id='.rawurlencode($r['AccountID']).'\'" class="px-3 py-1 bg-red-600 text-white hover:bg-red-700 rounded">Delete</button>
        </td>
    </tr>';
}

// ------ Xử lý AJAX: lọc + search (prepared statements) ------
if (isset($_GET['ajax'])) {
    $role   = trim($_GET['role']   ?? '');
    $status = trim($_GET['status'] ?? '');
    $search = trim($_GET['search'] ?? '');

    $sql = "SELECT AccountID, AName, Email, ARole, AStatus, CreatedAt FROM Accounts WHERE 1";
    $types = '';
    $params = [];

    if ($role !== '')   { $sql .= " AND ARole = ?";           $types .= 's'; $params[] = $role; }
    if ($status !== '') { $sql .= " AND AStatus = ?";         $types .= 's'; $params[] = $status; }
    if ($search !== '') { $sql .= " AND (AName LIKE ? OR Email LIKE ?)"; $types .= 'ss'; $like = "%$search%"; $params[] = $like; $params[] = $like; }

    $sql .= " ORDER BY CreatedAt DESC";

    $stmt = $conn->prepare($sql);
    if ($types !== '') { $stmt->bind_param($types, ...$params); }
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) render_row($row);
    } else {
        echo '<tr><td colspan="8" class="py-4 text-center">No accounts found.</td></tr>';
    }
    exit; // chỉ trả bảng cho AJAX
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Account Management</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
  <style>
    body{font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;background:#111827;color:#fff;}
  </style>
</head>
<body class="bg-darkblue text-white min-h-screen flex items-center justify-center p-6">
  <div class="bg-gray-800 rounded-xl shadow-xl p-6 w-full max-w-7xl">
    <h2 class="text-2xl font-bold text-center mb-6">Account Management</h2>

    <!-- Search -->
    <div class="flex justify-center mb-6 relative">
      <input id="search" type="text" placeholder="Search name or email"
             class="w-60 px-4 py-2 pl-10 bg-gray-700 text-gray-200 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary transition-colors duration-200">
      <i class="fas fa-search absolute left-1/2 -translate-x-24 top-2.5 text-gray-400"></i>
    </div>

    <!-- Filter -->
    <div class="flex flex-col md:flex-row items-center justify-center mb-6 space-y-4 md:space-y-0 md:space-x-4">
      <select id="filterRole" class="px-4 py-2 bg-gray-700 text-gray-200 rounded-lg">
        <option value="">-- Filter by Role --</option>
        <option value="Learner">Learner</option>
        <option value="Instructor">Instructor</option>
        <option value="Admin">Admin</option>
      </select>
      <select id="filterStatus" class="px-4 py-2 bg-gray-700 text-gray-200 rounded-lg">
        <option value="">-- Filter by Status --</option>
        <option value="Active">Active</option>
        <option value="Inactive">Inactive</option>
      </select>
    </div>

    <div class="flex items-center justify-between mb-4">
      <button onclick="window.location.href='add_user.php'"
              class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-500 transition shadow-lg flex items-center space-x-2">
        <i class="fas fa-plus"></i><span>Add new account</span>
      </button>
      <a href="dashboard.php"
         class="px-6 py-2 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-500 transition shadow-lg flex items-center space-x-2">
         <i class="fas fa-arrow-left"></i><span>Back to Dashboard</span>
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="table-auto w-full text-left border-collapse">
        <thead class="bg-gray-700 text-gray-300">
          <tr>
            <th class="py-3 px-4 rounded-tl-lg">AccountID</th>
            <th class="py-3 px-4">Account name</th>
            <th class="py-3 px-4">Email</th>
            <th class="py-3 px-4">Password</th>
            <th class="py-3 px-4">Role</th>
            <th class="py-3 px-4">Status</th>
            <th class="py-3 px-4">Date created</th>
            <th class="py-3 px-4 rounded-tr-lg">Action</th>
          </tr>
        </thead>
        <tbody id="accountTable">
        <?php
          // Load mặc định (cùng logic như AJAX)
          $stmt = $conn->prepare("SELECT AccountID,AName,Email,ARole,AStatus,CreatedAt FROM Accounts ORDER BY CreatedAt DESC");
          $stmt->execute();
          $res = $stmt->get_result();
          if ($res->num_rows > 0) {
              while ($row = $res->fetch_assoc()) render_row($row);
          } else {
              echo '<tr><td colspan="8" class="py-4 text-center">No accounts found.</td></tr>';
          }
        ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    $(function () {
      const ajaxUrl = location.pathname; // gọi chính file này
      let timer;

      function filterAccounts() {
        const role   = $('#filterRole').val();
        const status = $('#filterStatus').val();
        const search = $('#search').val();

        $.get(ajaxUrl, { ajax: 1, role, status, search })
          .done(html => $('#accountTable').html(html))
          .fail(() => alert('Load failed'));
      }

      $('#filterRole, #filterStatus').on('change', filterAccounts);

      $('#search').on('input', function(){
        clearTimeout(timer);
        timer = setTimeout(filterAccounts, 300); // debounce 300ms
      });
    });
  </script>
</body>
</html>
<?php $conn->close(); ?>
