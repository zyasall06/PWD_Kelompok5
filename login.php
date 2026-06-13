<?php
session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: profile.php');
    exit;
}

$error    = '';
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'profile.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');
    $redirect = trim($_POST['redirect'] ?? 'profile.php');

    if ($email === 'shizlafasia@gmail.com' && $password === 'kelompokwebdinamis') {
        $_SESSION['logged_in'] = true;
        header('Location: ' . $redirect);
        exit;
    } else {
        $error = 'Email atau password salah. Coba lagi.';
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login – YOUTHEVER 2026</title>
  <link rel="stylesheet" href="css/style.css"/>
  <style>
    html, body { height:100%; margin:0; }
    body {
      background:
        linear-gradient(rgba(0,0,0,.62), rgba(0,0,0,.62)),
        url("image/Background.jpg") center/cover no-repeat fixed;
      display:flex; align-items:center; justify-content:center;
      min-height:100vh; padding:40px 16px; box-sizing:border-box;
    }
    .auth-card {
      background:#fff; border-radius:16px; width:100%; max-width:420px;
      padding:40px 36px; color:#333; box-shadow:0 24px 60px rgba(0,0,0,.5);
    }
    .auth-card .brand {
      text-align:center; font-size:.72rem; letter-spacing:.18em;
      text-transform:uppercase; color:#c5a059; margin-bottom:6px; font-weight:700;
    }
    .auth-card h1 { text-align:center; font-size:1.8rem; color:#1a1a1a; margin:0 0 6px; }
    .auth-card .sub { text-align:center; font-size:.85rem; color:#888; margin-bottom:22px; }
    .auth-card .sub a { color:#5d3f5d; font-weight:600; text-decoration:none; }
    .auth-card .sub a:hover { text-decoration:underline; }
    .social-row { display:flex; gap:10px; margin-bottom:16px; }
    .social-row .btn-social {
      flex:1; display:flex; align-items:center; justify-content:center;
      gap:8px; padding:10px 12px; border-radius:8px; border:1px solid #ddd;
      font-size:.85rem; font-weight:600; cursor:pointer;
      text-decoration:none; transition:opacity .2s;
    }
    .social-row .btn-social:hover { opacity:.85; }
    .social-row .btn-social.facebook { background:#1877f2; border-color:#1877f2; color:#fff; }
    .social-row .btn-social.google   { background:#db4437; border-color:#db4437; color:#fff; }
    .auth-divider {
      display:flex; align-items:center; gap:12px;
      margin:14px 0; color:#bbb; font-size:.82rem;
    }
    .auth-divider::before, .auth-divider::after { content:''; flex:1; height:1px; background:#e8e8e8; }
    .auth-alert { padding:10px 14px; border-radius:8px; font-size:.85rem;
      margin-bottom:16px; display:flex; align-items:center; gap:8px; }
    .auth-alert.error { background:#fff0f0; border:1px solid #fcc; color:#c0392b; }
    .auth-field { margin-bottom:14px; }
    .auth-field label { display:block; font-size:.8rem; font-weight:600; color:#555; margin-bottom:5px; }
    .auth-field input {
      width:100%; padding:11px 14px; border:1px solid #ddd; border-radius:8px;
      font-size:.92rem; color:#333; background:#fafafa;
      transition:border-color .2s, box-shadow .2s; box-sizing:border-box;
    }
    .auth-field input:focus { outline:none; border-color:#c5a059; box-shadow:0 0 0 3px rgba(197,160,89,.15); background:#fff; }
    .auth-forgot { text-align:right; font-size:.8rem; margin:-6px 0 14px; }
    .auth-forgot a { color:#888; text-decoration:none; }
    .auth-forgot a:hover { color:#5d3f5d; text-decoration:underline; }
    .btn-auth-submit {
      width:100%; padding:12px; background:#5d3f5d; color:#fff;
      border:none; border-radius:8px; font-size:1rem; font-weight:700;
      cursor:pointer; letter-spacing:.03em; transition:background .2s;
    }
    .btn-auth-submit:hover { background:#7a4d7a; }
    .auth-footer-link { text-align:center; margin-top:18px; font-size:.83rem; color:#aaa; }
    .auth-footer-link a { color:#5d3f5d; font-weight:600; text-decoration:none; }
    .auth-footer-link a:hover { text-decoration:underline; }
  </style>
</head>
<body>
<div class="auth-card">
  <div class="brand">YOUTHEVER 2026</div>
  <h1>Sign In</h1>
  <p class="sub">Belum punya akun? <a href="register.php">Sign up</a></p>

  <div class="social-row">
    <a href="https://www.facebook.com/login.php" class="btn-social facebook" target="_blank" rel="noopener">📘 Facebook</a>
    <a href="https://accounts.google.com/" class="btn-social google" target="_blank" rel="noopener">🔎 Google</a>
  </div>
  <div class="auth-divider">atau</div>

  <?php if ($error): ?>
    <div class="auth-alert error">⚠️ <?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="POST" action="login.php">
    <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>"/>
    <div class="auth-field">
      <label for="email">Email Address</label>
      <input id="email" name="email" type="email" placeholder="nama@email.com"
        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required/>
    </div>
    <div class="auth-field">
      <label for="password">Password</label>
      <input id="password" name="password" type="password" placeholder="Password" required/>
    </div>
    <p class="auth-forgot"><a href="reset-password.php">Lupa password?</a></p>
    <button type="submit" class="btn-auth-submit">Sign In</button>
  </form>

  <div class="auth-footer-link">
    <a href="index.php">← Kembali ke Home</a>
  </div>
</div>
</body>
</html>
