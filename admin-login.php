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
  <link rel="stylesheet" href="css/admin-login.css"/>
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

<script src="js/admin-login.js"></script>
</body>
</html>
