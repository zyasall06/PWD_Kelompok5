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
  <style>
    /* ── About page ─────────────────────────────────── */
    .about-page { background: #000; color: #fff; min-height: 100vh; }

    /* Hero header */
    .about-hero {
      position: relative;
      background: linear-gradient(180deg, #0d0d0d 0%, #000 100%);
      border-bottom: 1px solid #1e1e1e;
      padding: 70px 20px 60px;
      text-align: center;
      overflow: hidden;
    }
    /* Subtle decorative lines */
    .about-hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background:
        repeating-linear-gradient(
          90deg, transparent, transparent 119px,
          rgba(197,160,89,.04) 120px
        );
      pointer-events: none;
    }
    .about-hero .ah-label {
      display: inline-block;
      border: 1px solid #c5a059;
      color: #c5a059;
      font-size: .72rem;
      letter-spacing: .18em;
      text-transform: uppercase;
      padding: 5px 16px;
      border-radius: 20px;
      margin-bottom: 22px;
    }
    .about-hero h1 {
      font-size: 3.2rem;
      color: #fff;
      letter-spacing: .06em;
      line-height: 1.1;
      margin-bottom: 10px;
    }
    .about-hero h1 span { color: #d4af37; }
    .about-hero .ah-sub {
      display: inline-block;
      background: #5d3f5d;
      color: #fff;
      font-size: 1.1rem;
      font-weight: 700;
      padding: 10px 28px;
      border-radius: 6px;
      letter-spacing: .04em;
      margin: 14px 0 22px;
    }
    .about-hero p {
      color: #666;
      font-size: .95rem;
      max-width: 520px;
      margin: 0 auto;
      line-height: 1.7;
    }

    /* Stats strip */
    .about-stats {
      display: flex;
      justify-content: center;
      gap: 0;
      border-top: 1px solid #1a1a1a;
      border-bottom: 1px solid #1a1a1a;
      background: #080808;
    }
    .about-stat {
      flex: 1;
      max-width: 200px;
      text-align: center;
      padding: 28px 16px;
      border-right: 1px solid #1a1a1a;
    }
    .about-stat:last-child { border-right: none; }
    .about-stat .stat-num {
      font-size: 2rem;
      font-weight: 700;
      color: #d4af37;
      line-height: 1;
      margin-bottom: 6px;
    }
    .about-stat .stat-lbl { font-size: .76rem; color: #555; letter-spacing: .08em; text-transform: uppercase; }

    /* Content body */
    .about-body {
      max-width: 860px;
      margin: 0 auto;
      padding: 60px 24px 80px;
    }

    /* Section block */
    .about-block { margin-bottom: 52px; }
    .about-block .block-tag {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: .72rem;
      color: #c5a059;
      letter-spacing: .12em;
      text-transform: uppercase;
      margin-bottom: 10px;
    }
    .about-block .block-tag::before {
      content: '';
      display: inline-block;
      width: 24px;
      height: 1px;
      background: #c5a059;
    }
    .about-block h2 {
      font-size: 1.7rem;
      color: #fff;
      margin-bottom: 16px;
      line-height: 1.2;
    }
    .about-block h2 span { color: #d4af37; }
    .about-block p {
      color: #999;
      line-height: 1.85;
      font-size: .96rem;
    }

    /* Divider */
    .about-divider {
      border: none;
      border-top: 1px solid #1a1a1a;
      margin: 0 0 52px;
    }

    /* Values grid */
    .values-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 16px;
      margin-top: 24px;
    }
    .value-card {
      background: #111;
      border: 1px solid #1e1e1e;
      border-top: 3px solid #c5a059;
      border-radius: 10px;
      padding: 22px 18px;
    }
    .value-card .v-icon { font-size: 1.8rem; margin-bottom: 10px; }
    .value-card h3 { color: #d4af37; font-size: 1rem; margin-bottom: 8px; }
    .value-card p  { color: #666; font-size: .84rem; line-height: 1.6; }

    /* Team / contact strip */
    .about-contact-strip {
      background: #0d0d0d;
      border: 1px solid #1e1e1e;
      border-radius: 12px;
      padding: 32px;
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 20px;
      text-align: center;
    }
    .contact-item .c-icon { font-size: 1.6rem; margin-bottom: 8px; }
    .contact-item h4     { color: #d4af37; font-size: .88rem; margin-bottom: 5px; text-transform: uppercase; letter-spacing: .06em; }
    .contact-item p      { color: #666; font-size: .83rem; line-height: 1.6; }

    /* Responsive */
    @media (max-width: 640px) {
      .about-hero h1 { font-size: 2.2rem; }
      .about-stats    { flex-wrap: wrap; }
      .about-stat     { min-width: 50%; border-bottom: 1px solid #1a1a1a; }
      .about-contact-strip { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body class="about-page">

<!-- ── Navigation ──────────────────────────────────── -->
<nav>
  <div class="nav-left">
    <a href="index.php" class="logo">YOUTHEVER 2026</a>
  </div>
  <button class="nav-toggle" aria-label="Toggle navigation">☰</button>
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
    <a href="tickets.php" class="buy-btn">Buy Ticket</a>
  </div>
</nav>

<!-- ── Hero Header ──────────────────────────────────── -->
<div class="about-hero">
  <span class="ah-label">✦ Our Story</span>
  <h1>ABOUT <span>US</span></h1>
  <div class="ah-sub">THE NOISE AWAKENS</div>
  <p>Sebuah perayaan musik, seni, dan energi kolektif yang dirancang untuk menggerakkan jiwa.</p>
</div>

<!-- ── Stats Strip ──────────────────────────────────── -->
<div class="about-stats">
  <div class="about-stat">
    <div class="stat-num">3</div>
    <div class="stat-lbl">Hari Festival</div>
  </div>
  <div class="about-stat">
    <div class="stat-num">10+</div>
    <div class="stat-lbl">Artis Tampil</div>
  </div>
  <div class="about-stat">
    <div class="stat-num">15K</div>
    <div class="stat-lbl">Kapasitas Penonton</div>
  </div>
  <div class="about-stat">
    <div class="stat-num">2026</div>
    <div class="stat-lbl">Edisi Perdana</div>
  </div>
</div>

<!-- ── Body Content ─────────────────────────────────── -->
<div class="about-body">

  <!-- More than a concert -->
  <div class="about-block">
    <span class="block-tag">01 · Who We Are</span>
    <h2>More Than a <span>Concert</span></h2>
    <p>
      YOUTHEVER 2026 adalah festival musik live yang dibangun untuk mereka yang percaya bahwa musik bukan sekadar suara — melainkan sebuah kekuatan. Kami menyatukan artis-artis bersemangat, kru berdedikasi, dan penonton yang luar biasa untuk menciptakan atmosfer yang terus bergetar jauh setelah nada terakhir mereda. Dari ketukan pertama hingga encore terakhir, setiap momen dirancang untuk menggerakkanmu.
    </p>
  </div>

  <hr class="about-divider"/>

  <!-- Mission -->
  <div class="about-block">
    <span class="block-tag">02 · Mission</span>
    <h2>Misi <span>Kami</span></h2>
    <p>
      Kami percaya bahwa musik live memiliki kekuatan untuk menghubungkan semua orang dari berbagai lapisan kehidupan. Misi kami adalah menciptakan ruang di mana artis dapat berekspresi secara bebas dan penonton dapat larut sepenuhnya — sebuah momen bersama dari energi yang mentah dan tulus, yang mengingatkan kita semua mengapa musik begitu penting.
    </p>
  </div>

  <hr class="about-divider"/>

  <!-- Values -->
  <div class="about-block">
    <span class="block-tag">03 · Values</span>
    <h2>Nilai yang Kami <span>Pegang</span></h2>
    <div class="values-grid">
      <div class="value-card">
        <div class="v-icon">🎵</div>
        <h3>Autentisitas</h3>
        <p>Setiap penampilan adalah ekspresi nyata, bukan sekadar pertunjukan.</p>
      </div>
      <div class="value-card">
        <div class="v-icon">🤝</div>
        <h3>Komunitas</h3>
        <p>Festival ini milik kamu — penggemar, artis, dan semua yang hadir.</p>
      </div>
      <div class="value-card">
        <div class="v-icon">🌟</div>
        <h3>Keberanian</h3>
        <p>Kami mendukung artis baru dan nama besar dengan semangat yang sama.</p>
      </div>
      <div class="value-card">
        <div class="v-icon">♻️</div>
        <h3>Keberlanjutan</h3>
        <p>Event ramah lingkungan dengan komitmen terhadap masa depan yang lebih baik.</p>
      </div>
    </div>
  </div>

  <hr class="about-divider"/>

  <!-- Contact -->
  <div class="about-block">
    <span class="block-tag">04 · Contact</span>
    <h2>Hubungi <span>Kami</span></h2>
    <div class="about-contact-strip">
      <div class="contact-item">
        <div class="c-icon">🤝</div>
        <h4>Partnership & Sponsorship</h4>
        <p>partnership@youthreverfest.com</p>
      </div>
      <div class="contact-item">
        <div class="c-icon">📰</div>
        <h4>Media & Press</h4>
        <p>media@youthreverfest.com</p>
      </div>
      <div class="contact-item">
        <div class="c-icon">📞</div>
        <h4>General Contact</h4>
        <p>🎧 Youmin<br>+62 812-3456-7890</p>
      </div>
    </div>
  </div>

</div><!-- /about-body -->

<!-- ── Footer ───────────────────────────────────────── -->
<footer>
  <div class="footer-grid">
    <div class="footer-col">
      <h3>YOUTHEVER</h3>
      <p>Festival Experience 2026</p>
    </div>
    <div class="footer-col">
      <p><strong>PARTNERSHIP &amp; SPONSORSHIP</strong><br>partnership@youthreverfest.com</p>
      <p><strong>MEDIA &amp; PRESS</strong><br>media@youthreverfest.com</p>
    </div>
    <div class="footer-col">
      <p><strong>CONTACT</strong><br>✉ media@youthreverfest.com<br>🎧 Youmin +62 812-3456-7890</p>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2026 YOUTHREVERFEST ALL RIGHTS RESERVED.</p>
    <p>🔒 EVENT ADMIN PORTAL</p>
  </div>
</footer>

<script>
(function(){
  var btn = document.querySelector('.nav-toggle');
  var nav = document.querySelector('nav');
  if (!btn||!nav) return;
  btn.addEventListener('click', function(){ nav.classList.toggle('nav-open'); });
})();
</script>
</body>
</html>
