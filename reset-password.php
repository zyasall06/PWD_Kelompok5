<?php
session_start();
$error   = '';
$success = '';
$step    = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (($_POST['step'] ?? '') == '1') {
        $email = trim($_POST['email'] ?? '');
        if (empty($email)) {
            $error = 'Email harus diisi.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Format email tidak valid.';
        } else {
            $_SESSION['reset_email'] = $email;
            $step    = 2;
            $success = 'Email terverifikasi. Silakan buat password baru.';
        }

    } elseif (($_POST['step'] ?? '') == '2') {
        $new_pw     = trim($_POST['new_password']     ?? '');
        $confirm_pw = trim($_POST['confirm_password'] ?? '');
        $step       = 2;
        if (empty($new_pw) || empty($confirm_pw)) {
            $error = 'Semua field harus diisi.';
        } elseif ($new_pw !== $confirm_pw) {
            $error = 'Password tidak cocok.';
        } elseif (strlen($new_pw) < 6) {
            $error = 'Password minimal 6 karakter.';
        } else {
            $success = 'Password berhasil direset. Mengalihkan ke halaman login…';
            unset($_SESSION['reset_email']);
            echo '<script>setTimeout(()=>{ window.location.href="index.php"; }, 2200);</script>';
        }
    }

} elseif (isset($_SESSION['reset_email'])) {
    $step = 2;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reset Password – YOUTHEVER 2026</title>
  <link rel="stylesheet" href="css/style.css"/>
  <style>
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
      max-width: 400px;
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
      font-size: 1.7rem;
      color: #1a1a1a;
      margin: 0 0 6px;
    }
    .auth-card .sub {
      text-align: center;
      font-size: .85rem;
      color: #999;
      margin-bottom: 28px;
      line-height: 1.5;
    }

    /* Step indicator */
    .step-indicator {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0;
      margin-bottom: 28px;
    }
    .step-dot {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: .78rem;
      font-weight: 700;
      border: 2px solid #ddd;
      color: #bbb;
      background: #fff;
      position: relative;
      z-index: 1;
    }
    .step-dot.active  { border-color: #5d3f5d; background: #5d3f5d; color: #fff; }
    .step-dot.done    { border-color: #27ae60; background: #27ae60; color: #fff; }
    .step-line {
      flex: 1;
      height: 2px;
      background: #e8e8e8;
      max-width: 60px;
    }
    .step-line.done { background: #27ae60; }
    .step-label-row {
      display: flex;
      justify-content: space-between;
      margin: -6px 0 20px;
      padding: 0 2px;
    }
    .step-label-row span {
      font-size: .7rem;
      color: #bbb;
      text-align: center;
      width: 32px;
    }
    .step-label-row span.active { color: #5d3f5d; font-weight: 700; }

    /* Alerts */
    .auth-alert {
      padding: 10px 14px;
      border-radius: 8px;
      font-size: .85rem;
      margin-bottom: 18px;
      display: flex;
      align-items: flex-start;
      gap: 8px;
      line-height: 1.5;
    }
    .auth-alert.error   { background: #fff0f0; border: 1px solid #fcc; color: #c0392b; }
    .auth-alert.success { background: #f0fff4; border: 1px solid #b2f2c8; color: #27ae60; }

    /* Fields */
    .auth-field { margin-bottom: 16px; }
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
      margin-top: 4px;
      letter-spacing: .03em;
      transition: background .2s;
    }
    .btn-auth-submit:hover { background: #7a4d7a; }

    /* Back link */
    .auth-footer-link {
      text-align: center;
      margin-top: 20px;
      font-size: .83rem;
      color: #aaa;
    }
    .auth-footer-link a { color: #5d3f5d; font-weight: 600; text-decoration: none; }
    .auth-footer-link a:hover { text-decoration: underline; }

    /* Redirecting state */
    .redirect-anim {
      text-align: center;
      padding: 20px 0 8px;
      color: #888;
      font-size: .88rem;
    }
    .redirect-anim .check { font-size: 2.8rem; display: block; margin-bottom: 10px; }
  </style>
</head>
<body>

<div class="auth-card">

  <div class="brand">YOUTHEVER 2026</div>
  <h1>Reset Password</h1>
  <p class="sub">
    <?php if ($step == 1): ?>
      Masukkan email akun Anda untuk memulai proses reset.
    <?php else: ?>
      Buat password baru untuk akun Anda.
    <?php endif; ?>
  </p>

  <!-- Step indicator -->
  <div class="step-indicator">
    <div class="step-dot <?php echo $step >= 1 ? ($step > 1 ? 'done' : 'active') : ''; ?>">
      <?php echo $step > 1 ? '✓' : '1'; ?>
    </div>
    <div class="step-line <?php echo $step > 1 ? 'done' : ''; ?>"></div>
    <div class="step-dot <?php echo $step == 2 ? 'active' : ''; ?>">2</div>
  </div>
  <div class="step-label-row">
    <span class="<?php echo $step == 1 ? 'active' : ''; ?>">Email</span>
    <span></span>
    <span class="<?php echo $step == 2 ? 'active' : ''; ?>">Password</span>
  </div>

  <!-- Alerts -->
  <?php if ($error): ?>
    <div class="auth-alert error">⚠️ <?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <?php if ($success && $step == 1): ?>
    <div class="auth-alert success">✅ <?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>

  <!-- ── Step 1: Verifikasi Email ──────────────────── -->
  <?php if ($step == 1): ?>
    <form method="POST" action="reset-password.php">
      <input type="hidden" name="step" value="1"/>
      <div class="auth-field">
        <label for="email">Email Address</label>
        <input id="email" name="email" type="email" placeholder="nama@email.com"
          value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required/>
      </div>
      <button type="submit" class="btn-auth-submit">Verifikasi Email →</button>
    </form>
  <?php endif; ?>

  <!-- ── Step 2: Buat Password Baru ───────────────── -->
  <?php if ($step == 2 && !($success && strpos($success,'berhasil direset') !== false)): ?>
    <form method="POST" action="reset-password.php">
      <input type="hidden" name="step" value="2"/>
      <div class="auth-field">
        <label for="new_password">Password Baru</label>
        <input id="new_password" name="new_password" type="password"
          placeholder="Min. 6 karakter" required/>
      </div>
      <div class="auth-field">
        <label for="confirm_password">Konfirmasi Password</label>
        <input id="confirm_password" name="confirm_password" type="password"
          placeholder="Ulangi password baru" required/>
      </div>
      <button type="submit" class="btn-auth-submit">Reset Password</button>
    </form>
  <?php endif; ?>

  <!-- ── Success state ─────────────────────────────── -->
  <?php if ($success && strpos($success,'berhasil direset') !== false): ?>
    <div class="redirect-anim">
      <span class="check">✅</span>
      <?php echo htmlspecialchars($success); ?>
    </div>
  <?php endif; ?>

  <div class="auth-footer-link">
    <a href="index.php">← Kembali ke Login</a>
  </div>

</div>

</body>
</html>
