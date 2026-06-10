<?php
session_start();
$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name             = trim($_POST['name']             ?? '');
    $email            = trim($_POST['email']            ?? '');
    $password         = trim($_POST['password']         ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Semua field harus diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif ($password !== $confirm_password) {
        $error = 'Password tidak cocok.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {
        $success = 'Akun berhasil dibuat. Silakan login.';
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up – YOUTHEVER 2026</title>
  <link rel="stylesheet" href="css/style.css"/>
  <style>
    /* ── Full-page centered layout ──────────────────── */
    html, body {
      height: 100%;
      margin: 0;
    }
    body {
      background:
        linear-gradient(rgba(0,0,0,.6), rgba(0,0,0,.6)),
        url("image/Background.jpg");
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 40px 16px;
      box-sizing: border-box;
    }

    /* ── Card ───────────────────────────────────────── */
    .auth-card {
      background: #fff;
      border-radius: 16px;
      width: 100%;
      max-width: 420px;
      padding: 40px 36px;
      color: #333;
      box-shadow: 0 24px 60px rgba(0,0,0,.5);
    }

    .auth-card .brand {
      text-align: center;
      font-size: .72rem;
      letter-spacing: .18em;
      text-transform: uppercase;
      color: #c5a059;
      margin-bottom: 6px;
      font-weight: 700;
    }
    .auth-card h1 {
      text-align: center;
      font-size: 1.8rem;
      color: #1a1a1a;
      margin: 0 0 6px;
    }
    .auth-card .sub {
      text-align: center;
      font-size: .85rem;
      color: #888;
      margin-bottom: 22px;
    }
    .auth-card .sub a { color: #5d3f5d; font-weight: 600; text-decoration: none; }
    .auth-card .sub a:hover { text-decoration: underline; }

    /* Social buttons */
    .social-row {
      display: flex;
      gap: 10px;
      margin-bottom: 16px;
    }
    .btn-social {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 10px 12px;
      border-radius: 8px;
      border: 1px solid #ddd;
      font-size: .85rem;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      transition: opacity .2s;
    }
    .btn-social:hover { opacity: .85; }
    .btn-social.facebook { background: #1877f2; border-color: #1877f2; color: #fff; }
    .btn-social.google   { background: #db4437; border-color: #db4437; color: #fff; }

    /* Divider */
    .auth-divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 14px 0;
      color: #bbb;
      font-size: .82rem;
    }
    .auth-divider::before,
    .auth-divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: #e8e8e8;
    }

    /* Alerts */
    .auth-alert {
      padding: 10px 14px;
      border-radius: 8px;
      font-size: .85rem;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .auth-alert.error   { background: #fff0f0; border: 1px solid #fcc; color: #c0392b; }
    .auth-alert.success { background: #f0fff4; border: 1px solid #b2f2c8; color: #27ae60; }

    /* Form fields */
    .auth-field {
      margin-bottom: 14px;
    }
    .auth-field label {
      display: block;
      font-size: .8rem;
      font-weight: 600;
      color: #555;
      margin-bottom: 5px;
    }
    .auth-field input {
      width: 100%;
      padding: 11px 14px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: .92rem;
      color: #333;
      background: #fafafa;
      transition: border-color .2s, box-shadow .2s;
      box-sizing: border-box;
    }
    .auth-field input:focus {
      outline: none;
      border-color: #c5a059;
      box-shadow: 0 0 0 3px rgba(197,160,89,.15);
      background: #fff;
    }

    /* Submit */
    .btn-auth-submit {
      width: 100%;
      padding: 12px;
      background: #5d3f5d;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      margin-top: 6px;
      letter-spacing: .03em;
      transition: background .2s;
    }
    .btn-auth-submit:hover { background: #7a4d7a; }

    /* Success state CTA */
    .btn-auth-outline {
      display: block;
      text-align: center;
      margin-top: 14px;
      padding: 11px;
      border: 2px solid #5d3f5d;
      border-radius: 8px;
      color: #5d3f5d;
      font-weight: 700;
      text-decoration: none;
      font-size: .92rem;
      transition: all .2s;
    }
    .btn-auth-outline:hover { background: #5d3f5d; color: #fff; }

    .auth-footer-link {
      text-align: center;
      margin-top: 18px;
      font-size: .83rem;
      color: #aaa;
    }
    .auth-footer-link a { color: #5d3f5d; font-weight: 600; text-decoration: none; }
    .auth-footer-link a:hover { text-decoration: underline; }
  </style>
</head>
<body>

<div class="auth-card">

  <div class="brand">YOUTHEVER 2026</div>
  <h1>Sign Up</h1>
  <p class="sub">Sudah punya akun? <a href="index.php">Sign in di sini</a></p>

  <?php if ($error): ?>
    <div class="auth-alert error">⚠️ <?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <?php if ($success): ?>
    <div class="auth-alert success">✅ <?php echo htmlspecialchars($success); ?></div>
    <a href="index.php" class="btn-auth-outline">← Kembali ke Login</a>

  <?php else: ?>

    <!-- Social login -->
    <div class="social-row">
      <a href="https://www.facebook.com/login.php" class="btn-social facebook" target="_blank" rel="noopener">
        📘 Facebook
      </a>
      <a href="https://accounts.google.com/" class="btn-social google" target="_blank" rel="noopener">
        🔎 Google
      </a>
    </div>

    <div class="auth-divider">atau</div>

    <form method="POST" action="register.php">
      <div class="auth-field">
        <label for="name">Nama Lengkap</label>
        <input id="name" name="name" type="text" placeholder="Masukkan nama lengkap"
          value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required/>
      </div>
      <div class="auth-field">
        <label for="email">Email Address</label>
        <input id="email" name="email" type="email" placeholder="nama@email.com"
          value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required/>
      </div>
      <div class="auth-field">
        <label for="password">Password</label>
        <input id="password" name="password" type="password" placeholder="Min. 6 karakter" required/>
      </div>
      <div class="auth-field">
        <label for="confirm_password">Konfirmasi Password</label>
        <input id="confirm_password" name="confirm_password" type="password" placeholder="Ulangi password" required/>
      </div>
      <button type="submit" class="btn-auth-submit">Buat Akun</button>
    </form>

  <?php endif; ?>

  <div class="auth-footer-link">
    <a href="index.php">← Kembali ke halaman utama</a>
  </div>

</div>

</body>
</html>
