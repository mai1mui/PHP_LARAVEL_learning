<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Courses List</title>
<style>
  :root{
    --bg:#0b1220; --bg2:#0f172a; --text:#e8eefc; --muted:#9db0d1;
    --primary:#5b8cfe; --primary-600:#3d73ff; --ring:#83a2ff;
    --ok:#21c38b; --danger:#ff6b6b; --shadow:0 20px 40px rgba(0,0,0,.35);
  }
  *{box-sizing:border-box}
  body{
    margin:0;font-family:Inter,system-ui,Segoe UI,Roboto,Arial,sans-serif;
    color:var(--text);
    background:linear-gradient(160deg,var(--bg),var(--bg2));
    min-height:100vh;display:flex;flex-direction:column;
  }
  header{
    display:flex;align-items:center;gap:16px;padding:16px 22px;
    background:#101b35;border-bottom:1px solid #1f2b50;
  }
  .logo{width:32px;height:32px;border-radius:8px;
    background:conic-gradient(from 210deg,#6dd0ff,#6e8dff,#a98bff,#6dd0ff)}
  header h1{font-size:1.1rem;margin:0}
  nav{margin-left:auto;display:flex;gap:14px}
  nav a{color:var(--muted);text-decoration:none;font-weight:600}
  nav a:hover{color:var(--text)}
  /* full-width main so grid stretches edge-to-edge */
  main{flex:1;width:100%;margin:0 auto;padding:22px 28px;max-width:none;min-height:calc(100vh - 72px)}
  .controls{display:flex;gap:12px;margin-bottom:18px;flex-wrap:wrap;align-items:center}
  .input,.select{
    border-radius:10px;border:1px solid #2a3860;background:#0b1533;
    color:var(--text);padding:10px 12px;font-size:1rem;
  }

  /* Grid: 6 columns on large screens.
     Use media queries to make responsive:
     - >=1200px: 6 columns
     - 992 - 1199px: 4 columns
     - 768 - 991px: 3 columns
     - 480 - 767px: 2 columns
     - <480px: 1 column
  */
  .grid{
    display:grid;
    grid-template-columns:repeat(6, 1fr);
    gap:18px;
  }

  @media (max-width:1199px){ .grid{ grid-template-columns: repeat(4, 1fr); } }
  @media (max-width:991px){  .grid{ grid-template-columns: repeat(3, 1fr); } }
  @media (max-width:767px){  .grid{ grid-template-columns: repeat(2, 1fr); } }
  @media (max-width:479px){  .grid{ grid-template-columns: 1fr; } }

  .card{
    background:linear-gradient(180deg,#0e1731ee,#0a122bfa);
    border:1px solid #25335f;border-radius:14px;box-shadow:var(--shadow);
    padding:16px;display:flex;flex-direction:column;gap:10px;min-height:150px;
    transition:transform .18s ease, box-shadow .18s ease;
  }
  .card:hover{ transform: translateY(-6px); box-shadow: 0 30px 50px rgba(0,0,0,.5); }
  .card h3{margin:0;font-size:1rem;line-height:1.2}
  .card p{margin:0;color:var(--muted);font-size:.9rem;flex:1}
  .progress{height:8px;background:#1c294d;border-radius:6px;overflow:hidden;margin-top:8px}
  .progress span{display:block;height:100%;background:var(--primary)}
  .meta{display:flex;justify-content:space-between;align-items:center;gap:8px;margin-top:8px}
  .pct{font-weight:700;color:var(--muted);font-size:.9rem}
  .btn{
    border:0;border-radius:10px;padding:8px 12px;font-weight:700;cursor:pointer;text-align:center;
    background:linear-gradient(180deg,var(--primary),var(--primary-600));color:#fff;border:1px solid rgba(255,255,255,.04)
  }
  .btn:active{transform:scale(.99)}
  /* Stretch grid to full width container — remove max-width so cards use available space */
  .container-full { width:100%; }
  /* optional: make cards same height for visual grid alignment */
  .card .bottom { display:flex; align-items:center; justify-content:space-between; gap:8px; margin-top:8px; }
</style>
</head>
<body>
  <header>
    <div class="logo"></div>
    <h1>Courses List</h1>
  </header>

  <main>
    <div class="controls">
      <input class="input" id="search" placeholder="Search courses..." />
      <select class="select" id="filter">
        <option value="all">All courses</option>
        <option value="enrolled">Free course</option>
        <option value="completed">Certificate course</option>
      </select>
    </div>

    <div class="container-full">
      <div class="grid" id="courseGrid"></div>
    </div>
  </main>

<script>
  const courses = [
    {id:1,title:"Web Development Basics",desc:"Learn HTML, CSS, JS from scratch.",progress:70,status:"enrolled"},
    {id:2,title:"Advanced PHP & Laravel",desc:"Deep dive into backend dev.",progress:20,status:"enrolled"},
    {id:3,title:"Data Science Intro",desc:"Python, Pandas, Visualization.",progress:100,status:"completed"},
    {id:4,title:"UI/UX Design",desc:"Principles of user-centered design.",progress:0,status:"all"},
    {id:5,title:"Database Fundamentals",desc:"SQL, normalization, queries.",progress:45,status:"enrolled"},
    {id:6,title:"Version Control with Git",desc:"Git basics, branching, merging.",progress:80,status:"enrolled"},
    {id:7,title:"Mobile App Intro",desc:"Build simple apps with Flutter.",progress:10,status:"all"},
    {id:8,title:"Algorithms 101",desc:"Sorting, searching, complexity.",progress:30,status:"enrolled"},
    {id:9,title:"Cloud Basics",desc:"Intro to AWS/GCP concepts.",progress:5,status:"all"},
    {id:10,title:"Testing & QA",desc:"Unit tests, integration tests.",progress:60,status:"enrolled"},
    {id:11,title:"Security Essentials",desc:"OWASP, common vulnerabilities.",progress:0,status:"all"},
    {id:12,title:"DevOps Fundamentals",desc:"CI/CD, containers, infra as code.",progress:50,status:"enrolled"}
  ];

  const grid = document.getElementById('courseGrid');
  const searchEl = document.getElementById('search');
  const filterEl = document.getElementById('filter');

  function render(){
    const term = (searchEl.value || "").toLowerCase();
    const filter = filterEl.value;
    grid.innerHTML = "";
    const filtered = courses.filter(c=>{
      const matchesTerm = c.title.toLowerCase().includes(term) || c.desc.toLowerCase().includes(term);
      const matchesFilter = (filter==="all") || (filter==="enrolled" && c.status==="enrolled") || (filter==="completed" && c.status==="completed");
      return matchesTerm && matchesFilter;
    });

    // Ensure grid displays even if fewer than 6 items; it will center automatically
    filtered.forEach(c=>{
      const div = document.createElement('div');
      div.className = "card";
      div.innerHTML = `
        <div>
          <h3>${escapeHtml(c.title)}</h3>
          <p>${escapeHtml(c.desc)}</p>
        </div>
        <div>
          <div class="bottom">
            <button class="btn" onclick="viewCourse(${c.id})">View</button>
          </div>
        </div>
      `;
      grid.appendChild(div);
    });
  }

  function viewCourse(id){
    // điều hướng sang course_detail.html
    window.location.href = "course_detail.html?id=" + encodeURIComponent(id);
  }

  // simple html escape
  function escapeHtml(text){
    return String(text)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  searchEl.addEventListener('input',render);
  filterEl.addEventListener('change',render);

  render();
</script>
</body>
</html>
