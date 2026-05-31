<?php
session_start();
$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Artist Lineup - YOUTHEVER 2026</title>
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <nav>
      <div class="nav-left">
        <div class="logo">YOUTHEVER 2026</div>
      </div>
      <div class="nav-center">
        <a href="index.php">Home</a>
        <a href="lineup.php">Line Up</a>
        <a href="#">Venue</a>
        <a href="rundown.php">Rundown</a>
        <a href="faq.php">FAQ</a>
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

    <section class="lineup-section">
      <h1>ARTIST <span>LINEUP</span></h1>
      <p>Filter by day and click artists to add them to your personalized festival schedule.</p>

      <div class="filter-btns">
        <button class="filter-btn" type="button" onclick="setFilter('all', this)">ALL</button>
        <button class="filter-btn" type="button" onclick="setFilter('day1', this)">DAY 1</button>
        <button class="filter-btn" type="button" onclick="setFilter('day2', this)">DAY 2</button>
      </div>

      <div class="artist-grid">
        <div class="artist-card day2">
          <img src="image/kunto aji.jpg" alt="Kunto Aji"><h3>KUNTO AJI</h3><p>INDONESIA POP</p><button class="add-btn">+</button>
        </div>
        <div class="artist-card day2">
          <img src="image/hindia.jpeg" alt="Hindia"><h3>HINDIA</h3><p>INDIE ROCK</p><button class="add-btn">+</button>
        </div>
        <div class="artist-card day1">
          <img src="image/vancouver sleep clinic.jpg" alt="Vancouver Sleep Clinic"><h3>VANCOUVER SLEEP CLINIC</h3><p>INDIE POP</p><button class="add-btn">+</button>
        </div>
        <div class="artist-card day1">
          <img src="image/nadin.jpg" alt="Nadin Amizah"><h3>NADIN AMIZAH</h3><p>INDIE POP</p><button class="add-btn">+</button>
        </div>
        <div class="artist-card day1">
          <img src="image/joji.jpg" alt="Joji"><h3>JOJI</h3><p>ALTERNATIVE R&B</p><button class="add-btn">+</button>
        </div>
        <div class="artist-card day1">
          <img src="image/sleeping at last.jpg" alt="Sleeping At Last"><h3>SLEEPING AT LAST</h3><p>INDIE POP</p><button class="add-btn">+</button>
        </div>
        <div class="artist-card day2">
          <img src="image/sal priadi.jpg" alt="Sal Priadi"><h3>SAL PRIADI</h3><p>ART POP</p><button class="add-btn">+</button>
        </div>
        <div class="artist-card day2">
          <img src="image/reality club.jpg" alt="Reality Club"><h3>REALITY CLUB</h3><p>INDIE ROCK</p><button class="add-btn">+</button>
        </div>
        <div class="artist-card day2">
          <img src="image/cortis.jpg" alt="Cortis"><h3>CORTIS</h3><p>K POP</p><button class="add-btn">+</button>
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

    <script>
      function filterSelection(c) {
        const cards = document.getElementsByClassName('artist-card');
        if (c === 'all') c = '';
        for (let i = 0; i < cards.length; i++) {
          cards[i].style.display = cards[i].className.indexOf(c) > -1 ? 'block' : 'none';
        }
      }

      function setFilter(c, button) {
        filterSelection(c);
        document.querySelectorAll('.filter-btn').forEach((btn) => btn.classList.remove('active'));
        button.classList.add('active');
      }
    </script>
  </body>
</html>
