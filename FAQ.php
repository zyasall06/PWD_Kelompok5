<?php
session_start();
$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>VITAL INTEL - FAQ</title>
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body class="faq-page">
    <nav>
      <div class="nav-left">
        <div class="logo">YOUTHEVER 2026</div>
      </div>
      <div class="nav-center">
        <a href="index.php">Home</a>
        <a href="lineup.php">Line Up</a>
        <a href="#">Venue</a>
        <a href="rundown.php">Rundown</a>
        <a href="faq.php" class="active">FAQ</a>
      </div>
      <div class="nav-right">
        <?php if ($loggedIn): ?>
          <a href="index.php?logout=true">Logout</a>
        <?php else: ?>
          <a href="index.php">Login</a>
        <?php endif; ?>
        <a href="#" class="buy-btn">Buy Ticket</a>
      </div>
    </nav>

    <section class="faq-section">
      <div class="faq-container">
        <h1>VITAL INTEL</h1>
        <p class="subtitle">FREQUENTLY ASKED QUESTIONS</p>

        <div class="faq-list">
          <div class="faq-item">
            <input type="checkbox" id="faq1">
            <label for="faq1">
              <span>Apa itu YOUTHREVER FEST?</span>
              <span class="icon"></span>
            </label>
            <div class="answer">
              <p>YOUTHREVER FEST adalah festival musik dua hari dengan konsep dreamy, emotional, dan youth-culture experience yang menghadirkan berbagai musisi indie dan alternative.</p>
            </div>
          </div>

          <div class="faq-item">
            <input type="checkbox" id="faq2">
            <label for="faq2">
              <span>Kapan festival berlangsung?</span>
              <span class="icon"></span>
            </label>
            <div class="answer">
              <p>20–21 September 2026.</p>
            </div>
          </div>

          <div class="faq-item">
            <input type="checkbox" id="faq3">
            <label for="faq3">
              <span>Dimana venue festival?</span>
              <span class="icon"></span>
            </label>
            <div class="answer">
              <p>Aurora Open Space, Bandung.</p>
            </div>
          </div>

          <div class="faq-item">
            <input type="checkbox" id="faq4">
            <label for="faq4">
              <span>Apakah festival ini outdoor?</span>
              <span class="icon"></span>
            </label>
            <div class="answer">
              <p>Ya, festival menggunakan konsep outdoor open-air venue.</p>
            </div>
          </div>

          <div class="faq-item">
            <input type="checkbox" id="faq5">
            <label for="faq5">
              <span>Apakah tersedia tenant makanan dan minuman?</span>
              <span class="icon"></span>
            </label>
            <div class="answer">
              <p>Tersedia berbagai food & beverage tenant selama festival berlangsung.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

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
  </body>
</html>
