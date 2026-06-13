<?php
session_start();
$error    = '';
$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// Handle login POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');
    if ($email === 'shizlafasia@gmail.com' && $password === 'kelompokwebdinamis') {
        $_SESSION['logged_in'] = true;
        $loggedIn = true;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Email atau password salah. Coba lagi.';
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Home always visible — no login required
$showHome = true;
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>YOUTHEVER 2026</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/index.css" />
  <body>

    <section id="login-page" style="display:none;">
      <!-- Login form dipindah ke modal — section ini tidak digunakan lagi -->
    </section>

    <main id="home-page">

      <!-- ── ANNOUNCEMENT TICKER ────────────────────── -->
      <div class="ann-ticker" style="
        position:fixed; top:0; left:0; right:0; z-index:200;
        background:linear-gradient(90deg,#5d3f5d,#3a1a3a);
        border-bottom:1px solid rgba(197,160,89,.3);
        padding:7px 0; overflow:hidden; white-space:nowrap;
      ">
        <div class="ann-ticker-track" style="
          display:inline-flex; align-items:center;
          animation:tickerScroll 28s linear infinite;
        ">
          <span style="padding:0 40px; color:#fff; font-size:.78rem; letter-spacing:.04em;">
            🔴 &nbsp; Lineup Final YOUTHEVER 2026 Telah Dikonfirmasi! Cek halaman Line Up sekarang.
          </span>
          <span style="color:rgba(197,160,89,.4);">✦</span>
          <span style="padding:0 40px; color:#d4af37; font-size:.78rem; letter-spacing:.04em;">
            🎟️ &nbsp; Early Bird Regular Pass HABIS — Tiket normal masih tersedia terbatas.
          </span>
          <span style="color:rgba(197,160,89,.4);">✦</span>
          <span style="padding:0 40px; color:#fff; font-size:.78rem; letter-spacing:.04em;">
            📅 &nbsp; Festival berlangsung 24–26 Oktober 2026 di Neon District, Jakarta.
          </span>
          <span style="color:rgba(197,160,89,.4);">✦</span>
          <span style="padding:0 40px; color:#d4af37; font-size:.78rem; letter-spacing:.04em;">
            ⭐ &nbsp; Area VIP Lounge kini dibuka pendaftaran — akses eksklusif & meet &amp; greet!
          </span>
          <span style="color:rgba(197,160,89,.4);">✦</span>
          <!-- duplicate for seamless loop -->
          <span style="padding:0 40px; color:#fff; font-size:.78rem; letter-spacing:.04em;">
            🔴 &nbsp; Lineup Final YOUTHEVER 2026 Telah Dikonfirmasi! Cek halaman Line Up sekarang.
          </span>
          <span style="color:rgba(197,160,89,.4);">✦</span>
          <span style="padding:0 40px; color:#d4af37; font-size:.78rem; letter-spacing:.04em;">
            🎟️ &nbsp; Early Bird Regular Pass HABIS — Tiket normal masih tersedia terbatas.
          </span>
          <span style="color:rgba(197,160,89,.4);">✦</span>
          <span style="padding:0 40px; color:#fff; font-size:.78rem; letter-spacing:.04em;">
            📅 &nbsp; Festival berlangsung 24–26 Oktober 2026 di Neon District, Jakarta.
          </span>
          <span style="color:rgba(197,160,89,.4);">✦</span>
          <span style="padding:0 40px; color:#d4af37; font-size:.78rem; letter-spacing:.04em;">
            ⭐ &nbsp; Area VIP Lounge kini dibuka pendaftaran — akses eksklusif & meet &amp; greet!
          </span>
        </div>
        <style>
          @keyframes tickerScroll {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
          }
          .ann-ticker-track:hover { animation-play-state: paused; }
        </style>
      </div>

      <nav style="top:33px;">
        <div class="nav-left">
          <a href="index.php" class="logo">YOUTHEVER 2026</a>
        </div>
        <button class="nav-toggle" aria-label="Toggle navigation">☰</button>
        <div class="nav-center">
          <a href="index.php">Home</a>
          <a href="about.php">About Us</a>
          <a href="lineup.php">Line Up</a>
          <a href="event-map.php">Venue</a>
          <a href="rundown.php">Rundown</a>
          <a href="announcements.php">Berita</a>
          <a href="faq.php">FAQ</a>
        </div>
        <div class="nav-right">
          <?php if ($loggedIn): ?>
            <a href="profile.php">Dashboard</a>
            <a href="index.php?logout=1" style="color:#ff6b6b;">Logout</a>
          <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php" class="buy-btn">Register</a>
          <?php endif; ?>
        </div>
      </nav>

      <!-- ── HERO ──────────────────────────────────── -->
      <header class="hero" style="min-height:100vh; padding-top:170px; padding-bottom:60px; justify-content:center;">
        <p class="tagline">Global Music Experience</p>
        <h1>THE NOISE<br/><span class="hero-title-gold">AWAKENS</span></h1>
        <p>
          Experience three days of unrelenting sound, immersive art, and
          collective energy.<br />Secure your access now before the grid locks
          down.
        </p>
        <div class="cta-buttons" style="margin-top:32px; margin-bottom:24px;">
          <a href="tickets.php" class="btn">BUY TICKETS</a>
          <a href="lineup.php"  class="btn">VIEW LINEUP</a>
        </div>
        <div class="meta-info">
          <span>📅 OCT 24-26, 2026</span>
          <span>📍 NEON DISTRICT, ID</span>
        </div>

        <!-- Scroll hint -->
        <div class="scroll-hint">
          <span>scroll</span>
          <div class="arrow"></div>
        </div>
      </header>

      <!-- ── STATS STRIP ────────────────────────────── -->
      <div class="home-stats">
        <div class="home-stat"><div class="sn">3</div><div class="sl">Hari Festival</div></div>
        <div class="home-stat"><div class="sn">10+</div><div class="sl">Artis Tampil</div></div>
        <div class="home-stat"><div class="sn">15K</div><div class="sl">Kapasitas</div></div>
        <div class="home-stat"><div class="sn">2</div><div class="sl">Stage Utama</div></div>
        <div class="home-stat"><div class="sn">2026</div><div class="sl">Edisi Perdana</div></div>
      </div>

      <!-- ── RUNDOWN PREVIEW ───────────────────────── -->
      <section style="background:#000; padding:70px 24px 80px;">
        <div style="max-width:760px; margin:0 auto;">

          <!-- Section header -->
          <div style="text-align:center; margin-bottom:40px;">
            <span style="display:inline-flex;align-items:center;gap:8px;
              border:1px solid rgba(197,160,89,.45); background:rgba(197,160,89,.08);
              color:#c5a059; font-size:.7rem; letter-spacing:.2em; text-transform:uppercase;
              padding:6px 18px; border-radius:40px; margin-bottom:16px;">
              ✦ Festival Schedule ✦
            </span>
            <h2 style="color:#fff; font-size:clamp(1.4rem,3.5vw,2rem); margin-bottom:8px;">
              Festival <span style="color:#d4af37;">Rundown</span>
            </h2>
            <p style="color:#666; font-size:.88rem;">Jadwal penampilan dua hari penuh — klik untuk lihat lengkap</p>
          </div>

          <!-- Day tabs -->
          <div style="display:flex; background:#111; border:1px solid #222;
            border-radius:10px; overflow:hidden; margin-bottom:24px;">
            <button id="rdTabDay1"
              onclick="rdShowDay(1)"
              style="flex:1; padding:13px; background:#5d3f5d; color:#fff;
                border:none; font-weight:700; font-size:.85rem;
                letter-spacing:.08em; text-transform:uppercase; cursor:pointer;
                font-family:'Montserrat Alternates',Arial,sans-serif;">
              🌅 Day 1
            </button>
            <button id="rdTabDay2"
              onclick="rdShowDay(2)"
              style="flex:1; padding:13px; background:transparent; color:#555;
                border:none; font-weight:700; font-size:.85rem;
                letter-spacing:.08em; text-transform:uppercase; cursor:pointer;
                font-family:'Montserrat Alternates',Arial,sans-serif;">
              🌆 Day 2
            </button>
          </div>

          <!-- Timeline Day 1 -->
          <div id="rdDay1" style="position:relative;">
            <!-- vertical line -->
            <div style="position:absolute;left:20px;top:0;bottom:0;width:2px;
              background:linear-gradient(180deg,#5d3f5d,#1a001a);"></div>
            <?php
            $rdData = [
              1 => [
                ['15.00 – 16.30','FESTIVAL OPENING & CREATIVE MARKET','Main Area',false],
                ['16.30 – 18.30','DREAMY INDIE POP SESSION','Sleeping At Last, Vancouver Sleep Clinic, Nadin Amizah',true],
                ['19.00 – 20.30','ALTERNATIVE R&B & EMOTIONAL STAGE','JOJI',true],
                ['21.00 – 22.30','CLOSING REFLECTION SHOW','Ambient visual show, lantern moment',false,true],
              ],
              2 => [
                ['15.00 – 16.00','COMMUNITY & INTERACTIVE ACTIVITIES','Random play dance, merch market',false],
                ['16.00 – 18.00','ART POP & INDONESIAN INDIE SESSION','Sal Priadi, Kunto Aji',true],
                ['18.30 – 20.00','INDIE ROCK STAGE','Reality Club, Hindia',true],
                ['20.15 – 21.00','K-POP CLOSING PERFORMANCE','Cortis, final fireworks & crowd farewell',false,true],
              ],
            ];
            foreach ($rdData[1] as $item):
              $badge = '';
              if (isset($item[4]) && $item[4]) {
                $badge = '<span style="font-size:.7rem;font-weight:700;padding:4px 12px;border-radius:20px;background:rgba(93,63,93,.25);border:1px solid rgba(93,63,93,.5);color:#c39bd3;white-space:nowrap;">IN SCHEDULE</span>';
              } elseif ($item[3]) {
                $badge = '<span style="font-size:.7rem;font-weight:700;padding:4px 12px;border-radius:20px;background:rgba(243,156,18,.12);border:1px solid rgba(243,156,18,.3);color:#f39c12;white-space:nowrap;">REMIND ME</span>';
              }
            ?>
            <div style="display:flex;align-items:flex-start;gap:16px;
              padding:16px 16px 16px 0; margin-left:10px;
              border-bottom:1px solid #111;">
              <!-- dot -->
              <div style="width:22px;height:22px;border-radius:50%;background:#5d3f5d;
                border:2px solid #000;flex-shrink:0;margin-top:2px;
                box-shadow:0 0 0 3px rgba(93,63,93,.35);"></div>
              <div style="flex:1;">
                <div style="color:#d4af37;font-weight:700;font-size:.8rem;margin-bottom:3px;
                  font-family:'Montserrat Alternates',Arial,sans-serif;">
                  <?php echo htmlspecialchars($item[0]); ?>
                </div>
                <div style="color:#fff;font-weight:700;font-size:.9rem;margin-bottom:3px;">
                  <?php echo htmlspecialchars($item[1]); ?>
                </div>
                <div style="color:#666;font-size:.8rem;"><?php echo htmlspecialchars($item[2]); ?></div>
              </div>
              <?php if ($badge): ?><div style="padding-top:2px;"><?php echo $badge; ?></div><?php endif; ?>
            </div>
            <?php endforeach; ?>
          </div>

          <!-- Timeline Day 2 -->
          <div id="rdDay2" style="position:relative; display:none;">
            <div style="position:absolute;left:20px;top:0;bottom:0;width:2px;
              background:linear-gradient(180deg,#5d3f5d,#1a001a);"></div>
            <?php foreach ($rdData[2] as $item):
              $badge = '';
              if (isset($item[4]) && $item[4]) {
                $badge = '<span style="font-size:.7rem;font-weight:700;padding:4px 12px;border-radius:20px;background:rgba(93,63,93,.25);border:1px solid rgba(93,63,93,.5);color:#c39bd3;white-space:nowrap;">IN SCHEDULE</span>';
              } elseif ($item[3]) {
                $badge = '<span style="font-size:.7rem;font-weight:700;padding:4px 12px;border-radius:20px;background:rgba(243,156,18,.12);border:1px solid rgba(243,156,18,.3);color:#f39c12;white-space:nowrap;">REMIND ME</span>';
              }
            ?>
            <div style="display:flex;align-items:flex-start;gap:16px;
              padding:16px 16px 16px 0; margin-left:10px;
              border-bottom:1px solid #111;">
              <div style="width:22px;height:22px;border-radius:50%;background:#5d3f5d;
                border:2px solid #000;flex-shrink:0;margin-top:2px;
                box-shadow:0 0 0 3px rgba(93,63,93,.35);"></div>
              <div style="flex:1;">
                <div style="color:#d4af37;font-weight:700;font-size:.8rem;margin-bottom:3px;
                  font-family:'Montserrat Alternates',Arial,sans-serif;">
                  <?php echo htmlspecialchars($item[0]); ?>
                </div>
                <div style="color:#fff;font-weight:700;font-size:.9rem;margin-bottom:3px;">
                  <?php echo htmlspecialchars($item[1]); ?>
                </div>
                <div style="color:#666;font-size:.8rem;"><?php echo htmlspecialchars($item[2]); ?></div>
              </div>
              <?php if ($badge): ?><div style="padding-top:2px;"><?php echo $badge; ?></div><?php endif; ?>
            </div>
            <?php endforeach; ?>
          </div>

          <!-- CTA -->
          <div style="text-align:center; margin-top:32px;">
            <a href="rundown.php"
              style="display:inline-flex;align-items:center;gap:8px;
                background:transparent; border:1.5px solid #5d3f5d;
                color:#c39bd3; padding:11px 28px; border-radius:8px;
                font-size:.85rem; font-weight:700; text-decoration:none;
                letter-spacing:.06em; transition:all .2s;
                font-family:'Montserrat Alternates',Arial,sans-serif;"
              onmouseover="this.style.background='#5d3f5d';this.style.color='#fff';"
              onmouseout="this.style.background='transparent';this.style.color='#c39bd3';">
              📅 Lihat Rundown Lengkap →
            </a>
          </div>

        </div>
      </section>

      <footer>
        <div class="footer-grid">
          <div class="footer-col">
            <h3>YOUTHEVER</h3>
            <p>Festival Experience 2026</p>
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

    <script src="js/index.js"></script>
  </body>
</html>
