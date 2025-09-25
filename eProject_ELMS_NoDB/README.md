ELearning Management System (ELMS) — Bản demo không dùng DB

Bản demo giao diện ELMS chạy thuần PHP (render file tĩnh), HTML, CSS, JavaScript.
Không cần MySQL/DB — dữ liệu mẫu được nạp từ mảng JS hoặc JSON tĩnh.

🎯 Mục tiêu
Minh hoạ luồng màn hình & UI/UX: Trang chủ, danh sách khoá học, modal đăng nhập/đăng ký, và một số trang quản trị mẫu.
Dùng CSS tách file (homepage.css, style_index.css) + hiệu ứng/animation nhẹ.
Nút Login/Register ở navbar; đăng nhập demo chuyển hướng theo role.

✨ Tính năng (demo)
Trang homepage.php: hero + card tính năng, modal login, hiệu ứng reveal on scroll.
Trang index.php: danh sách khoá học (grid), search + filter (client-side).
Modal Login/Register (demo): kiểm tra định dạng, redirect theo role:
admin / instructor → dashboard.php
learner → dashboard_learner.php
Không gọi DB: dữ liệu COURSES lấy từ app.js (mảng tĩnh) hoặc từ file JSON tĩnh (tuỳ chọn).

⚙️ Yêu cầu
PHP 8+ (chỉ để serve file .php và chạy <?=date()?> – không cần DB).
Trình duyệt hiện đại (Chrome/Edge/Firefox/Safari).

🧑‍🎨 CSS & Animation
homepage.css: nền gradient, blob animation, modal, reveal on scroll (IntersectionObserver).
style_index.css: lớp tiện ích dùng chung cho bảng, button, form (các trang quản trị demo).

🧭 Điều hướng chính
/homepage.php – Trang chủ (login/register ở navbar).
/index.php – Danh sách khoá học (search/filter).
/dashboard.php và /dashboard_learner.php – trang đích sau khi “đăng nhập” demo.

🧱 Giới hạn bản demo
Không có DB, session, phân quyền thật, upload file thật, thanh toán thật…
Các trang quản trị là khung UI để bạn gắn API/DB sau này.
🛣 Nâng cấp lên bản có backend (gợi ý)
Thêm config.php (thông số DB), login.php (xác thực thật), và API trả JSON cho courses/lessons/payments…
Thay demo redirect bằng fetch('login.php', {method:'POST'}) + $_SESSION.
Thay COURSES tĩnh bằng API /courses_api.php.

📄 License
MIT (hoặc chọn license khác tuỳ bạn).
