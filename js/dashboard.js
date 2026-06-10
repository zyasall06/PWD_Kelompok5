/* ════════════════════════════════════════════════════
   dashboard.js  –  profile.php logic
════════════════════════════════════════════════════ */

/* ── Avatar preview ──────────────────────────────── */
function previewAvatar(event) {
  const file = event.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = function (e) {
    const url = e.target.result;
    ['sidebar-avatar', 'profile-avatar'].forEach(function (id) {
      const el = document.getElementById(id);
      if (!el) return;
      el.style.backgroundImage = "url('" + url + "')";
      el.classList.add('has-photo');
      el.textContent = '';
    });
  };
  reader.readAsDataURL(file);
}

/* ── Admin modal helpers ─────────────────────────── */
function openAdminModal() {
  var m = document.getElementById('adminModal');
  if (m) m.classList.add('open');
}

function closeAdminModal() {
  var m = document.getElementById('adminModal');
  var err = document.getElementById('modalError');
  var code = document.getElementById('modalCode');
  var pass = document.getElementById('modalPass');
  if (m)    m.classList.remove('open');
  if (err)  err.classList.remove('show');
  if (code) code.value = '';
  if (pass) pass.value = '';
}

function submitAdminAccess() {
  var ADMIN_CODE = 'kelompokwebdinamis';
  var ADMIN_PASS = 'mice4a';

  var code    = document.getElementById('modalCode').value.trim();
  var pass    = document.getElementById('modalPass').value.trim();
  var errBox  = document.getElementById('modalError');
  var errText = document.getElementById('modalErrorText');

  if (!code || !pass) {
    errText.textContent = 'Kode akses dan password harus diisi.';
    errBox.classList.add('show');
    return;
  }
  if (code === ADMIN_CODE && pass === ADMIN_PASS) {
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = 'admin-login.php';
    [['access_code', code], ['password', pass]].forEach(function (pair) {
      var inp = document.createElement('input');
      inp.type  = 'hidden';
      inp.name  = pair[0];
      inp.value = pair[1];
      form.appendChild(inp);
    });
    document.body.appendChild(form);
    form.submit();
  } else {
    errText.textContent = 'Kode akses atau password tidak valid.';
    errBox.classList.add('show');
    document.getElementById('modalCode').value = '';
    document.getElementById('modalPass').value = '';
  }
}

function toggleVis(id, btn) {
  var inp = document.getElementById(id);
  if (!inp) return;
  inp.type = (inp.type === 'password') ? 'text' : 'password';
  btn.textContent = (inp.type === 'password') ? '👁' : '🙈';
}

/* ── Nav toggle ──────────────────────────────────── */
(function () {
  var btn = document.querySelector('.nav-toggle');
  var nav = document.querySelector('nav');
  if (!btn || !nav) return;
  btn.addEventListener('click', function () {
    nav.classList.toggle('nav-open');
  });
})();

/* ── Modal init on DOM ready ─────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
  /* Close on overlay click */
  var overlay = document.getElementById('adminModal');
  if (overlay) {
    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) closeAdminModal();
    });
  }

  /* Enter key submits modal */
  ['modalCode', 'modalPass'].forEach(function (id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') submitAdminAccess();
    });
  });
});
