<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Course Detail • Demo</title>
        <style>
            :root {
                --bg:#0b1220;
                --bg2:#0f172a;
                --text:#e8eefc;
                --muted:#9db0d1;
                --primary:#5b8cfe;
                --primary-600:#3d73ff;
                --shadow:0 20px 40px rgba(0,0,0,.35);
            }
            body {
                margin:0;
                font-family: Inter, sans-serif;
                background: linear-gradient(160deg, var(--bg), var(--bg2));
                color: var(--text);
                min-height:100vh;
                display:flex;
                flex-direction:column;
            }
            header {
                background:#101b35;
                padding:16px 22px;
                border-bottom:1px solid #1f2b50;
            }
            header h1 {
                margin:0;
                font-size:1.2rem;
            }
            main {
                flex:1;
                max-width:900px;
                margin:30px auto;
                padding:20px;
                background:rgba(15,25,50,0.9);
                border-radius:14px;
                border:1px solid #25335f;
                box-shadow: var(--shadow);
            }
            h2 {
                margin-top:0;
            }
            .progress {
                height:12px;
                background:#1c294d;
                border-radius:6px;
                overflow:hidden;
                margin:16px 0;
            }
            .progress span {
                display:block;
                height:100%;
                background:var(--primary);
            }
            .meta {
                margin:16px 0;
                color:var(--muted);
                font-weight:600;
            }
            .btn {
                display: inline-block;
                background: linear-gradient(180deg, #5b8cfe, #3d73ff);
                color: #fff;
                padding: 10px 18px;
                border-radius: 10px;
                text-decoration: none;
                font-weight: 600;
                margin: 10px 10px 0 0;
                transition: background 0.2s;
            }
            .btn:hover {
                background: linear-gradient(180deg, #6a9cff, #4c82ff);
            }

            .link {
                display: inline-block;
                color: #9db0d1;
                margin-top: 15px;
                text-decoration: none;
                font-size: 0.95rem;
            }
            .link:hover {
                color: #fff;
                text-decoration: underline;
            }

            /* Tabs */
            .tabs {
                display:flex;
                border-bottom:1px solid #2a3b63;
                margin-top:20px;
            }
            .tab {
                padding:12px 20px;
                cursor:pointer;
                font-weight:600;
                color:var(--muted);
                border-bottom:3px solid transparent;
                transition:all .2s;
            }
            .tab.active {
                color:var(--text);
                border-color:var(--primary);
            }
            .tab:hover {
                color:#fff;
            }
            .tab-content {
                padding:20px 0;
                min-height:150px;
            }
        </style>
    </head>
    <body>
        <header>
            <h1>Course Detail (Demo)</h1>
        </header>

        <main>
            <h2>Web Development Basics</h2>
            <p>Learn HTML, CSS, JS from scratch and build your first website.</p>
            <div class="tabs">
                <div class="tab active" data-tab="overview">Overview</div>
                <div class="tab" data-tab="syllabus">Syllabus</div>
                <div class="tab" data-tab="instructor">Instructor</div>
                <div class="tab" data-tab="resources">Resources</div>
            </div>
            <div class="tab-content" id="tabContent"></div>

            <a class="btn" href="course_view.php">Register to Courses</a>
            <a class="link" href="course_view.php">Back to Courses</a>
        </main>

        <script>
            const tabContent = document.getElementById("tabContent");
            const tabs = document.querySelectorAll(".tab");

            function loadTab(name) {
                tabs.forEach(t => t.classList.remove("active"));
                document.querySelector(`.tab[data-tab="${name}"]`).classList.add("active");

                if (name === "overview") {
                    tabContent.innerHTML = `<p>This course introduces you to the building blocks of the web: 
              <b>HTML</b> for structure, <b>CSS</b> for styling, and <b>JavaScript</b> for interactivity. 
              By the end, you'll create a simple but complete website.</p>`;
                }
                if (name === "syllabus") {
                    tabContent.innerHTML = `<ul>
                <li>Week 1: HTML Basics</li>
                <li>Week 2: CSS Fundamentals</li>
                <li>Week 3: JavaScript Introduction</li>
                <li>Week 4: Mini Project - Personal Website</li>
              </ul>`;
                }
                if (name === "instructor") {
                    tabContent.innerHTML = `<p><b>Instructor:</b> Jane Smith<br>
              <b>Email:</b> jane.smith@example.com<br>
              <b>Experience:</b> 8 years in front-end development.</p>`;
                }
                if (name === "resources") {
                    tabContent.innerHTML = `<ul>
                <li><a href="#">Download Starter Template</a></li>
                <li><a href="#">HTML & CSS Cheat Sheet</a></li>
                <li><a href="#">JavaScript Practice Exercises</a></li>
              </ul>`;
                }
            }

            tabs.forEach(tab => {
                tab.addEventListener("click", () => loadTab(tab.dataset.tab));
            });

            // load default
            loadTab("overview");
        </script>
    </body>
</html>
