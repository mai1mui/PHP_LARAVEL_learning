ELearning Management System (ELMS)

Nền tảng E-Learning đơn giản với phân quyền Admin / Instructor / Learner, quản lý khóa học, bài học, thanh toán, bài nộp, điểm số và hồ sơ người dùng. Giao diện sử dụng Tailwind/utility styles và tách CSS dùng chung (style_index.css, homepage.css).

✨ Tính năng
🔐 Đăng nhập, phân quyền: Admin/Instructor → dashboard.php, Learner → dashboard_learner.php
📚 Quản lý Courses (thêm/sửa/xóa, tìm kiếm, lọc)
🧩 Quản lý Lessons (AJAX search + filter; add/edit tách file)
💳 Quản lý Payments (AJAX search + filter; add/edit tách file)
📝 Quản lý Submissions (AJAX search + filter; add/edit tách file)
🧮 Quản lý Results (AJAX search + filter; add/edit tách file)
👤 Profile view / edit (đọc thông tin từ DB, đổi email/avatar demo)
🌐 Homepage hiện đại: homepage.php + homepage.css
🧾 API nhỏ: courses_api.php trả danh sách khóa học (JSON) để index/public page load động
🧰 Mã PHP MySQLi chuẩn bị sẵn prepared statement, escape output, tách CSS

🛠 Công nghệ
PHP 8.x (MySQLi)
MySQL/MariaDB
HTML5/CSS3, utility CSS (Tailwind qua CDN hoặc styles tách file)
jQuery cho AJAX nhỏ gọn (trang quản trị)
Fetch API (homepage/index)

⚙️ Yêu cầu
PHP ≥ 8.0
MySQL/MariaDB
Composer (tuỳ chọn nếu bạn muốn thêm lib)
Web server (Apache/Nginx) hoặc PHP built-in server

