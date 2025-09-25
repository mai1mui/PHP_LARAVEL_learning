// ===== State =====
let COURSES = [];

// ===== Elements =====
const grid = document.getElementById('coursesGrid');
const searchInput = document.getElementById('searchInput');
const categorySelect = document.getElementById('categorySelect');

// Các modal/nút có thể không tồn tại -> chống null để JS không vỡ
const modalLogin  = document.getElementById('modalLogin')  || null;
const modalSignup = document.getElementById('modalSignup') || null;
const modalCourse = document.getElementById('modalCourse') || null;

const btnLogin  = document.getElementById('btnLogin')  || null;
const btnSignup = document.getElementById('btnSignup') || null;

const loginForm  = document.getElementById('loginForm')  || null;
const signupForm = document.getElementById('signupForm') || null;

// ===== UI helpers =====
function courseCard(c){
  return `
    <article class="card" data-id="${escapeHtml(c.id)}">
      <div>
        <span class="badge">${escapeHtml(c.cat)}</span>
        <h3>${escapeHtml(c.title)}</h3>
        <small>${escapeHtml(c.desc)}</small>
      </div>
      <div class="actions">
        <button class="btn ghost" data-view="${escapeHtml(c.id)}">
          <i class="fa-regular fa-eye"></i> View
        </button>
        <button class="btn primary" data-enroll="${escapeHtml(c.id)}">
          <i class="fa-solid fa-book-open"></i> Enroll
        </button>
      </div>
    </article>
  `;
}

function render(list){
  if (!grid) return;
  grid.innerHTML = list.map(courseCard).join('');
}

function filterCourses(){
  const q = (searchInput?.value || '').trim().toLowerCase();
  const cat = categorySelect?.value || 'all';
  const list = COURSES.filter(c => {
    const okCat = (cat === 'all') || (c.cat === cat);
    const okQ = c.title.toLowerCase().includes(q) || c.desc.toLowerCase().includes(q);
    return okCat && okQ;
  });
  render(list);
}

function populateCategories(){
  if (!categorySelect) return;
  // Lấy danh mục từ dữ liệu
  const cats = Array.from(new Set(COURSES.map(c => c.cat))).sort();
  categorySelect.innerHTML = `<option value="all">All courses</option>` +
    cats.map(c => `<option value="${escapeHtml(c)}">${escapeHtml(c)}</option>`).join('');
}

// Đơn giản hoá escape
function escapeHtml(t){
  return String(t)
    .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
}

// ===== Load from API =====
async function loadCourses(){
  try {
    const res = await fetch('courses_api.php', { cache: 'no-store' });
    const json = await res.json();
    if (!json?.ok) throw new Error(json?.error || 'Load failed');
    COURSES = Array.isArray(json.data) ? json.data : [];
    populateCategories();
    render(COURSES);
  } catch (err) {
    console.error(err);
    if (grid) grid.innerHTML = `<div class="error">Cannot load courses.</div>`;
  }
}

// ===== Listeners =====
searchInput && searchInput.addEventListener('input', filterCourses);
categorySelect && categorySelect.addEventListener('change', filterCourses);

// Close buttons for any modal present
document.querySelectorAll('[data-close]').forEach(btn => {
  btn.addEventListener('click', () => {
    const m = btn.closest('.modal');
    m && m.classList.add('hidden');
  });
});

// Optional: các modal login/signup chỉ gắn event nếu tồn tại
btnLogin  && btnLogin.addEventListener('click', () => modalLogin && modalLogin.classList.remove('hidden'));
btnSignup && btnSignup.addEventListener('click', () => modalSignup && modalSignup.classList.remove('hidden'));

// Demo auth (nếu form tồn tại)
loginForm && loginForm.addEventListener('submit', (e) => {
  e.preventDefault();
  const form = new FormData(loginForm);
  const email = form.get('email');
  localStorage.setItem('elms_user', email);
  alert('Login successful: ' + email);
  modalLogin && modalLogin.classList.add('hidden');
});

signupForm && signupForm.addEventListener('submit', (e) => {
  e.preventDefault();
  const form = new FormData(signupForm);
  const email = form.get('email');
  localStorage.setItem('elms_user', email);
  alert('Account created: ' + (form.get('name') || email));
  modalSignup && modalSignup.classList.add('hidden');
});

// Course detail modal (nếu có)
grid && grid.addEventListener('click', (e) => {
  const target = e.target;
  const viewBtn   = target.closest?.('[data-view]');
  const enrollBtn = target.closest?.('[data-enroll]');

  if (viewBtn){
    const id = viewBtn.getAttribute('data-view');
    const c = COURSES.find(x => x.id === id);
    if (!c || !modalCourse) return;
    document.getElementById('courseTitle')?.replaceChildren(document.createTextNode(c.title));
    document.getElementById('courseDesc')?.replaceChildren(document.createTextNode(c.desc));
    const go = document.getElementById('btnGoCourse');
    go && go.setAttribute('href', c.href || '#');
    modalCourse.classList.remove('hidden');
  }

  if (enrollBtn){
    const id = enrollBtn.getAttribute('data-enroll');
    const user = localStorage.getItem('elms_user');
    if (!user){
      modalLogin && modalLogin.classList.remove('hidden');
      return;
    }
    alert('Enrolled: ' + id);
  }
});

// ESC to close whatever modal exists
window.addEventListener('keydown', (e) => {
  if (e.key === 'Escape'){
    [modalLogin, modalSignup, modalCourse].forEach(m => m && m.classList.add('hidden'));
  }
});

// Init
loadCourses();
