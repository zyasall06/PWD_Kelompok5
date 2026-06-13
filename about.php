<?php
session_start();
$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About Us – YOUTHEVER 2026</title>
  <link rel="stylesheet" href="css/style.css"/>
  <link rel="stylesheet" href="css/about.css"/>
</head>
<body>

<!-- ── Announcement Ticker ─────────────────────────── -->
<div style="position:fixed;top:0;left:0;right:0;z-index:200;background:linear-gradient(90deg,#5d3f5d,#3a1a3a);border-bottom:1px solid rgba(197,160,89,.3);padding:7px 0;overflow:hidden;white-space:nowrap;"><div style="display:inline-flex;align-items:center;animation:tickerScroll 28s linear infinite;"><span style="padding:0 40px;color:#fff;font-size:.78rem;">🔴 &nbsp; Lineup Final YOUTHEVER 2026 Telah Dikonfirmasi!</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#d4af37;font-size:.78rem;">🎟️ &nbsp; Early Bird Regular Pass HABIS — Tiket normal masih tersedia terbatas.</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#fff;font-size:.78rem;">📅 &nbsp; Festival berlangsung 24–26 Oktober 2026 di Neon District, Jakarta.</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#d4af37;font-size:.78rem;">⭐ &nbsp; Area VIP Lounge kini dibuka pendaftaran!</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#fff;font-size:.78rem;">🔴 &nbsp; Lineup Final YOUTHEVER 2026 Telah Dikonfirmasi!</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#d4af37;font-size:.78rem;">🎟️ &nbsp; Early Bird Regular Pass HABIS — Tiket normal masih tersedia terbatas.</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#fff;font-size:.78rem;">📅 &nbsp; Festival berlangsung 24–26 Oktober 2026 di Neon District, Jakarta.</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#d4af37;font-size:.78rem;">⭐ &nbsp; Area VIP Lounge kini dibuka pendaftaran!</span></div><style>@keyframes tickerScroll{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}</style></div>

<!-- ── NAV ──────────────────────────────────────── -->
<nav style="top:33px;">
  <div class="nav-left">
    <a href="index.php" class="logo">YOUTHEVER 2026</a>
  </div>
  <button class="nav-toggle" aria-label="Toggle navigation">&#9776;</button>
  <div class="nav-center">
    <a href="index.php">Home</a>
    <a href="about.php" class="active">About Us</a>
    <a href="lineup.php">Line Up</a>
    <a href="event-map.php">Venue</a>
    <a href="rundown.php">Rundown</a>
    <a href="announcements.php">Berita</a>
    <a href="faq.php">FAQ</a>
  </div>
  <div class="nav-right">
    <?php if ($loggedIn): ?>
      <a href="profile.php">Dashboard</a>
    <?php else: ?>
      <a href="index.php">Login</a>
    <?php endif; ?>
  </div>
</nav>

<!-- ── HERO ─────────────────────────────────────── -->
<div class="about-hero">
  <span class="ah-eyebrow">Our Story</span>

  <h1>ABOUT <span>US</span></h1>

  <div class="banner">THE NOISE AWAKENS</div>

  <div class="about-scroll-hint">
    <span>scroll</span>
    <div class="arr"></div>
  </div>
</div>

<!-- ── BODY ─────────────────────────────────────── -->
<div class="about-body">

  <!-- More than a concert -->
  <div class="about-block">
    <span class="section-tag">01 &nbsp; Who We Are</span>
    <h2 class="section-title">More than a <span>concert</span></h2>
    <p class="content-text">The Noise Awakens is a live music concert event built for those who believe music is more than just sound &mdash; it is a force. We bring together passionate artists, dedicated crews, and an unforgettable audience to create an atmosphere that resonates long after the last note fades. From the first beat to the final encore, every moment is crafted to move you.</p>
    <div class="about-quote">
      <p>"From the first beat to the final encore, every moment is crafted to move you."</p>
    </div>
  </div>

  <hr class="about-divider"/>

  <!-- Our Mission -->
  <div class="about-block">
    <span class="section-tag">02 &nbsp; Mission</span>
    <h2 class="section-title">Our <span>Mission</span></h2>
    <p class="content-text">We believe live music has the power to connect people across all walks of life. Our mission is to create a space where artists can express themselves freely and audiences can lose themselves completely &mdash; a shared moment of raw, unfiltered energy that reminds us all why music matters.</p>
  </div>

</div>

<!-- ── FOOTER ────────────────────────────────────── -->
<footer>
  <div class="footer-grid">
    <div class="footer-col">
      <h3>YOUTHEVER</h3>
      <p>Festival Experience 2026</p>
    </div>
    <div class="footer-col">
      <p>
        <strong>PARTNERSHIP &amp; SPONSORSHIP</strong><br/>partnership@youthreverfest.com
      </p>
      <p><strong>MEDIA &amp; PRESS</strong><br/>media@youthreverfest.com</p>
    </div>
    <div class="footer-col">
      <p>
        <strong>CONTACT</strong><br/>&#9993; media@youthreverfest.com<br/>Youmin +62 812-3456-7890
      </p>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; 2026 YOUTHREVERFEST ALL RIGHTS RESERVED.</p>
    <p>🔒 EVENT ADMIN PORTAL</p>
  </div>
</footer>

<script src="js/about.js"></script>
</body>
</html>
