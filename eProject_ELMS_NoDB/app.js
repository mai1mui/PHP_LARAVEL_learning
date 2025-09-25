// ===== Courses dataset (could be fetched from API later) =====
const COURSES = [
  { id: 'web101', title: 'Web Development Basics', desc: 'Learn HTML, CSS, JS from scratch.', cat: 'web', href: '#web' },
  { id: 'php201', title: 'Advanced PHP & Laravel', desc: 'Deep dive into backend dev.', cat: 'backend', href: '#php' },
  { id: 'ds101', title: 'Data Science Intro', desc: 'Python, Pandas, Visualization.', cat: 'data', href: '#data' },
  { id: 'ux101', title: 'UI/UX Design', desc: 'Principles of user-centered design.', cat: 'design', href: '#uiux' },
  { id: 'db101', title: 'Database Fundamentals', desc: 'SQL, normalization, queries.', cat: 'db', href: '#db' },
  { id: 'git101', title: 'Version Control with Git', desc: 'Branching, merging, workflow.', cat: 'git', href: '#git' },
  { id: 'mob101', title: 'Mobile App Intro', desc: 'Flutter basics.', cat: 'mobile', href: '#mobile' },
  { id: 'algo101', title: 'Algorithms 101', desc: 'Sorting, searching, complexity.', cat: 'algo', href: '#algo' },
  { id: 'cloud101', title: 'Cloud Basics', desc: 'AWS/GCP basics.', cat: 'cloud', href: '#cloud' },
  { id: 'test101', title: 'Testing & QA', desc: 'Unit tests, integration tests.', cat: 'testing', href: '#testing' },
  { id: 'sec101', title: 'Security Essentials', desc: 'OWASP, common vulns.', cat: 'security', href: '#security' },
  { id: 'devops101', title: 'DevOps Fundamentals', desc: 'CI/CD, containers.', cat: 'devops', href: '#devops' },
];

// ===== Render grid =====
const grid = document.getElementById('coursesGrid');
const searchInput = document.getElementById('searchInput');
const categorySelect = document.getElementById('categorySelect');

function courseCard(c){
  return `
    <article class="card" data-id="${c.id}">
      <div>
        <span class="badge">${c.cat}</span>
        <h3>${c.title}</h3>
        <small>${c.desc}</small>
      </div>
      <div class="actions">
        <button class="btn ghost" data-view="${c.id}"><i class="fa-regular fa-eye"></i> View</button>
        <button class="btn primary" data-enroll="${c.id}"><i class="fa-solid fa-book-open"></i> Enroll</button>
      </div>
    </article>
  `;
}

function render(list){
  grid.innerHTML = list.map(courseCard).join('');
}

function filterCourses(){
  const q = searchInput.value.trim().toLowerCase();
  const cat = categorySelect.value;
  const list = COURSES.filter(c => {
    const okCat = (cat === 'all') || (c.cat === cat);
    const okQ = c.title.toLowerCase().includes(q) || c.desc.toLowerCase().includes(q);
    return okCat && okQ;
  });
  render(list);
}

render(COURSES);
searchInput.addEventListener('input', filterCourses);
categorySelect.addEventListener('change', filterCourses);

// ===== Modals (Login / Signup / Course detail) =====
const modalLogin = document.getElementById('modalLogin');
const modalSignup = document.getElementById('modalSignup');
const modalCourse = document.getElementById('modalCourse');
const btnLogin = document.getElementById('btnLogin');
const btnSignup = document.getElementById('btnSignup');
const loginForm = document.getElementById('loginForm');
const signupForm = document.getElementById('signupForm');

function openModal(el){ el.classList.remove('hidden'); }
function closeModal(el){ el.classList.add('hidden'); }
document.querySelectorAll('[data-close]').forEach(btn => btn.addEventListener('click', e => {
  closeModal(btn.closest('.modal'));
}));

btnLogin.addEventListener('click', () => openModal(modalLogin));
btnSignup.addEventListener('click', () => openModal(modalSignup));

// Demo "auth" using localStorage to keep it functional for UI
loginForm.addEventListener('submit', (e) => {
  e.preventDefault();
  const form = new FormData(loginForm);
  const email = form.get('email');
  localStorage.setItem('elms_user', email);
  alert('Đăng nhập thành công: ' + email);
  closeModal(modalLogin);
});

signupForm.addEventListener('submit', (e) => {
  e.preventDefault();
  const form = new FormData(signupForm);
  const email = form.get('email');
  localStorage.setItem('elms_user', email);
  alert('Tạo tài khoản thành công cho: ' + form.get('name'));
  closeModal(modalSignup);
});

// Course detail modal
grid.addEventListener('click', (e) => {
  const viewId = e.target.closest('[data-view]')?.getAttribute('data-view');
  const enrollId = e.target.closest('[data-enroll]')?.getAttribute('data-enroll');
  if (viewId){
    const c = COURSES.find(x => x.id === viewId);
    if(!c) return;
    document.getElementById('courseTitle').textContent = c.title;
    document.getElementById('courseDesc').textContent = c.desc;
    document.getElementById('btnGoCourse').setAttribute('href', c.href);
    openModal(modalCourse);
  }
  if (enrollId){
    const user = localStorage.getItem('elms_user');
    if (!user){
      openModal(modalLogin);
      return;
    }
    alert('Đăng ký khóa học thành công: ' + enrollId);
  }
});

// Keyboard escape to close modals
window.addEventListener('keydown', (e) => {
  if (e.key === 'Escape'){
    [modalLogin, modalSignup, modalCourse].forEach(closeModal);
  }
});
