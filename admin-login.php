<?php
session_start();

// Redirect jika sudah login admin
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin-profile.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $access_code = isset($_POST['access_code']) ? trim($_POST['access_code']) : '';
    $password    = isset($_POST['password'])    ? trim($_POST['password'])    : '';

    // ── Kode akses & password admin ──────────────────────
    // Ganti nilai ini sesuai kebutuhan (simpan di .env di produksi)
    define('ADMIN_ACCESS_CODE', 'kelompokwebdinamis');
    define('ADMIN_PASSWORD',    'mice4a');
    // ─────────────────────────────────────────────────────

    if ($access_code === ADMIN_ACCESS_CODE && $password === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name']      = 'Administrator';
        $_SESSION['admin_email']     = 'admin@youthreverfest.com';
        $_SESSION['admin_role']      = 'Super Admin';
        header('Location: admin-profile.php');
        exit;
    } else {
        // Pesan error tidak spesifik (keamanan)
        $error = 'Kode akses atau password tidak valid.';
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Access – YOUTHEVER 2026</title>
  <link rel="stylesheet" href="css/style.css"/>
  <style>
    body { background: #000; display: flex; align-items: center; justify-content: center; min-height: 100vh; }

    .admin-login-wrap {
      width: 100%;
      max-width: 420px;
      padding: 20px;
    }

    .admin-login-card {
      background: #111;
      border: 2px solid #c5a059;
      border-radius: 16px;
      padding: 40px 36px;
    }

    .admin-login-badge {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-bottom: 28px;
    }
    .admin-login-badge .shield {
      font-size: 2.4rem;
    }
    .admin-login-badge h1 {
      font-size: 1.4rem;
      color: #d4af37;
      letter-spacing: .06em;
      margin: 0;
    }
    .admin-login-badge p {
      font-size: .78rem;
      color: #666;
      margin: 0;
    }

    .admin-login-divider {
      border: none;
      border-top: 1px solid #2a2a2a;
      margin: 0 0 28px;
    }

    .admin-field {
      margin-bottom: 20px;
    }
    .admin-field label {
      display: block;
      color: #888;
      font-size: .82rem;
      margin-bottom: 7px;
      letter-spacing: .04em;
      text-transform: uppercase;
    }
    .admin-field-inner {
      position: relative;
    }
    .admin-field-inner .field-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 1rem;
      pointer-events: none;
    }
    .admin-field input {
      width: 100%;
      padding: 12px 14px 12px 42px;
      background: #1a1a1a;
      border: 1px solid #333;
      border-radius: 8px;
      color: #fff;
      font-size: .95rem;
      transition: border-color .2s;
      box-sizing: border-box;
    }
    .admin-field input:focus {
      outline: none;
      border-color: #c5a059;
    }
    .admin-field input::placeholder { color: #444; }

    .toggle-pw {
      position: absolute;
      right: 13px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #666;
      cursor: pointer;
      font-size: .9rem;
      padding: 0;
    }
    .toggle-pw:hover { color: #d4af37; }

    .admin-error {
      background: rgba(231,76,60,.12);
      border: 1px solid #e74c3c;
      border-radius: 8px;
      color: #e74c3c;
      padding: 11px 14px;
      font-size: .85rem;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .admin-submit {
      width: 100%;
      padding: 13px;
      background: linear-gradient(135deg, #c5a059, #d4af37);
      border: none;
      border-radius: 8px;
      color: #000;
      font-weight: 700;
      font-size: 1rem;
      cursor: pointer;
      letter-spacing: .04em;
      transition: opacity .2s;
    }
    .admin-submit:hover { opacity: .88; }

    .admin-back {
      display: block;
      text-align: center;
      margin-top: 18px;
      color: #555;
      text-decoration: none;
      font-size: .83rem;
      transition: color .2s;
    }
    .admin-back:hover { color: #d4af37; }

    .admin-hint {
      margin-top: 28px;
      padding: 14px;
      background: #0d0d0d;
      border: 1px solid #1e1e1e;
      border-radius: 8px;
      font-size: .78rem;
      color: #555;
      text-align: center;
      line-height: 1.6;
    }
    .admin-hint strong { color: #666; }
  </style>
</head>
<body>
<div class="admin-login-wrap">
  <div class="admin-login-card">

    <div class="admin-login-badge">
      <span class="shield">🛡️</span>
      <div>
        <h1>ADMIN ACCESS</h1>
        <p>YOUTHEVER 2026 · Restricted Area</p>
      </div>
    </div>

    <hr class="admin-login-divider"/>

    <?php if ($error): ?>
      <div class="admin-error">⚠️ <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="admin-login.php" autocomplete="off">

      <div class="admin-field">
        <label>Kode Akses Admin</label>
        <div class="admin-field-inner">
          <span class="field-icon">🔑</span>
          <input
            type="password"
            id="access_code"
            name="access_code"
            placeholder="Masukkan kode akses"
            required
            autocomplete="off"
          />
          <button type="button" class="toggle-pw" onclick="toggleField('access_code', this)" aria-label="Tampilkan/sembunyikan kode">👁</button>
        </div>
      </div>

      <div class="admin-field">
        <label>Password Admin</label>
        <div class="admin-field-inner">
          <span class="field-icon">🔒</span>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="Masukkan password"
            required
            autocomplete="off"
          />
          <button type="button" class="toggle-pw" onclick="toggleField('password', this)" aria-label="Tampilkan/sembunyikan password">👁</button>
        </div>
      </div>

      <button type="submit" class="admin-submit">🚀 Masuk ke Admin Panel</button>
    </form>

    <a href="index.php" class="admin-back">← Kembali ke halaman utama</a>

    <div class="admin-hint">
      <strong>Hanya untuk administrator resmi.</strong><br>
      Akses tidak sah akan dicatat dan dilaporkan.
    </div>

  </div>
</div>

<script>
function toggleField(id, btn) {
  const input = document.getElementById(id);
  if (input.type === 'password') {
    input.type = 'text';
    btn.textContent = '🙈';
  } else {
    input.type = 'password';
    btn.textContent = '👁';
  }
}
</script>
</body>
</html>
