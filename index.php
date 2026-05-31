
<?php
session_start();
$error = '';
$showHome = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($email === 'shizlafasia@gmail.com' && $password === 'eventku') {
        $showHome = true;
        $_SESSION['logged_in'] = true;
    } else {
        $error = 'Email atau password salah. Coba lagi.';
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $showHome = true;
}
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>YOUTHEVER 2026</title>
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>

    <section id="login-page" style="<?php echo $showHome ? 'display: none;' : ''; ?>">
      <div class="login-card">
        <h1>Sign In</h1>
        <p>Don't have an account yet? <a href="#">Sign up now</a></p>
        <div class="social-login">
          <button type="button" class="btn-social">Facebook</button>
          <button type="button" class="btn-social">Google</button>
        </div>
        <div class="divider">or</div>
        <?php if ($error): ?>
          <p style="color: red; margin-bottom: 15px;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post" action="index.php">
          <div class="input-group">
            <label for="email">Email Address</label>
            <input
              id="email"
              name="email"
              type="email"
              placeholder="Email Address"
              value="<?php echo htmlspecialchars(isset($_POST['email']) ? $_POST['email'] : 'shizlafasia@gmail.com'); ?>"
              required
            />
          </div>
          <div class="input-group">
            <label for="password">Password</label>
            <input
              id="password"
              name="password"
              type="password"
              placeholder="Password"
              value="<?php echo htmlspecialchars(isset($_POST['password']) ? $_POST['password'] : 'eventku'); ?>"
              required
            />
          </div>
          <p class="forgot-password"><a href="#">Forgot your password?</a></p>
          <button type="submit" class="btn-signin">Sign In</button>
        </form>
      </div>
    </section>

    <main id="home-page" style="<?php echo $showHome ? 'display: block;' : 'display: none;'; ?>">
      <nav>
        <div class="nav-left">
          <div class="logo">YOUTHEVER 2026</div>
        </div>
        <div class="nav-center">
          <a href="#">About Us</a>
          <a href="lineup.php">Line Up</a>
          <a href="#">Venue</a>
          <a href="rundown.php">Rundown</a>
          <a href="faq.php">FAQ</a>
        </div>
        <div class="nav-right">
          <a href="?logout=true">Logout</a>
          <a href="#" class="buy-btn">Buy Ticket</a>
        </div>
      </nav>

      <header class="hero">
        <p class="tagline">Global Music Experience</p>
        <h1>THE NOISE<br />AWAKENS</h1>
        <p>
          Experience three days of unrelenting sound, immersive art, and
          collective energy.<br />Secure your access now before the grid locks
          down.
        </p>
        <div class="cta-buttons">
          <a href="lineup.php" class="btn">BUY TICKETS</a>
          <a href="lineup.php" class="btn">VIEW LINEUP</a>
        </div>
        <div class="meta-info">
          <p>📅 OCT 24-26, 2024 &nbsp;&nbsp;&nbsp; 📍 NEON DISTRICT, ID</p>
        </div>
      </header>

      <footer>
        <div class="footer-grid">
          <div class="footer-col">
            <h3>SONICFRACTURE</h3>
            <p>General Information</p>
          </div>
          <div class="footer-col">
            <p>
              <strong>PARTNERSHIP & SPONSORSHIP</strong><br />partnership@youthreverfest.com
            </p>
            <p><strong>MEDIA & PRESS</strong><br />media@youthreverfest.com</p>
          </div>
          <div class="footer-col">
            <p>
              <strong>CONTACT</strong><br />✉ media@youthreverfest.com<br />🎧 Youmin +62 812-3456-7890
            </p>
          </div>
        </div>
        <div class="footer-bottom">
          <p>© 2026 YOUTHREVERFEST ALL RIGHTS RESERVED.</p>
          <p>🔒 EVENT ADMIN PORTAL</p>
        </div>
      </footer>
    </main>

  </body>
</html>
