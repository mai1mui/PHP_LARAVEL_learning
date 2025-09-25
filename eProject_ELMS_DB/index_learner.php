<?php
// ==== Simple visitor counter ====
$counterFile = __DIR__ . '/visits.txt';
if (!file_exists($counterFile)) {
    // initialize
    file_put_contents($counterFile, "0");
}
$visits = (int) trim(file_get_contents($counterFile));
$visits++;
file_put_contents($counterFile, (string)$visits);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ELMS • Courses</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="index.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
  <!-- Decorative blobs -->
  <div class="bg-blob blob-1" aria-hidden="true"></div>
  <div class="bg-blob blob-2" aria-hidden="true"></div>
  <div class="bg-blob blob-3" aria-hidden="true"></div>

  <!-- NAVBAR -->
  <header class="nav">
    <a class="brand" href="#">
      <span class="logo-dot"></span>
      <span class="brand-text">ELMS</span>
    </a>
    <div class="nav-right">
      <a href="profile_view_learner.php" class="block px-4 py-2 text-gray-200 hover:bg-gray-700">👤 Profile</a>
    </div>
  </header>

  <!-- HERO / SEARCH -->
  <section class="hero">
    <h1 class="hero-title">Courses List</h1>
    <div class="filters">
      <div class="search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input id="searchInput" type="text" placeholder="Search courses..." />
      </div>
      <div class="select-wrap">
        <select id="categorySelect">
          <option value="all">All courses</option>
          <option value="web">Web</option>
          <option value="backend">Back-end</option>
          <option value="data">Data</option>
          <option value="design">UI/UX</option>
          <option value="db">Database</option>
          <option value="devops">DevOps</option>
          <option value="mobile">Mobile</option>
          <option value="algo">Algorithms</option>
          <option value="testing">Testing</option>
          <option value="security">Security</option>
          <option value="cloud">Cloud</option>
          <option value="git">Git</option>
        </select>
      </div>
    </div>
  </section>

  <!-- COURSES GRID -->
  <main class="container">
    <div id="coursesGrid" class="grid"></div>
  </main>

  <!-- FOOTER -->
  <footer class="site-footer">
    <div class="footer-columns">
      <div class="footer-col">
        <h4>Top Tutorials</h4>
        <ul>
          <li><a href="#">HTML Tutorial</a></li>
          <li><a href="#">CSS Tutorial</a></li>
          <li><a href="https://www.w3schools.com/js/default.asp">JavaScript Tutorial</a></li>
          <li><a href="#">PHP Tutorial</a></li>
          <li><a href="#">Python Tutorial</a></li>
          <li><a href="#">Bootstrap Tutorial</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Top References</h4>
        <ul>
          <li><a href="#">HTML Reference</a></li>
          <li><a href="#">CSS Reference</a></li>
          <li><a href="#">JavaScript Reference</a></li>
          <li><a href="#">SQL Reference</a></li>
          <li><a href="#">Python Reference</a></li>
          <li><a href="#">PHP Reference</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Top Examples</h4>
        <ul>
          <li><a href="#">HTML Examples</a></li>
          <li><a href="#">CSS Examples</a></li>
          <li><a href="#">JS Examples</a></li>
          <li><a href="#">SQL Examples</a></li>
          <li><a href="#">Python Examples</a></li>
          <li><a href="#">PHP Examples</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Get Certified</h4>
        <ul>
          <li><a href="#">HTML Certificate</a></li>
          <li><a href="#">CSS Certificate</a></li>
          <li><a href="#">JavaScript Certificate</a></li>
          <li><a href="#">SQL Certificate</a></li>
          <li><a href="#">Python Certificate</a></li>
          <li><a href="#">PHP Certificate</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="contact">
        <p><i class="fa-solid fa-envelope"></i> support@elms.example</p>
        <p><i class="fa-solid fa-phone"></i> +84 912 345 678</p>
      </div>
      <div class="social">
        <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
        <a href="#" aria-label="GitHub"><i class="fa-brands fa-github"></i></a>
      </div>
      <div class="counter">
        <i class="fa-solid fa-eye"></i> Lượt truy cập: <strong><?php echo number_format($visits); ?></strong>
      </div>
    </div>
    <p class="copyright">© <?php echo date('Y'); ?> ELMS. All rights reserved.</p>
  </footer>


  <!-- COURSE DETAIL MODAL -->
  <div id="modalCourse" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="courseTitle">
    <div class="modal-card">
      <h3 id="courseTitle"></h3>
      <p id="courseDesc" class="mt-2"></p>
      <div class="modal-actions">
        <button type="button" class="btn ghost" data-close>Close</button>
        <a id="btnGoCourse" class="btn primary" href="#" target="_blank" rel="noopener">Go to course</a>
      </div>
    </div>
  </div>

  <script src="app.js"></script>
</body>
</html>
