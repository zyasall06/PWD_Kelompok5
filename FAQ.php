<?php
session_start();
$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// Baca FAQ dari file JSON (dikelola admin di admin-profile.php)
$faqFile = __DIR__ . '/data/faqs.json';
$faqs = [];
if (file_exists($faqFile)) {
    $all  = json_decode(file_get_contents($faqFile), true) ?: [];
    $faqs = array_values(array_filter($all, fn($f) => $f['active'] ?? true));
}
if (empty($faqs)) {
    $faqs = [
        ['id'=>1,'question'=>'Apa itu YOUTHREVER FEST?',                    'answer'=>'YOUTHREVER FEST adalah festival musik dua hari dengan konsep dreamy, emotional, dan youth-culture experience yang menghadirkan berbagai musisi indie dan alternative.'],
        ['id'=>2,'question'=>'Kapan festival berlangsung?',                 'answer'=>'20–21 September 2026.'],
        ['id'=>3,'question'=>'Dimana venue festival?',                      'answer'=>'Aurora Open Space, Bandung.'],
        ['id'=>4,'question'=>'Apakah festival ini outdoor?',                'answer'=>'Ya, festival menggunakan konsep outdoor open-air venue.'],
        ['id'=>5,'question'=>'Apakah tersedia tenant makanan dan minuman?', 'answer'=>'Tersedia berbagai food & beverage tenant selama festival berlangsung.'],
    ];
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FAQ – YOUTHEVER 2026</title>
  <link rel="stylesheet" href="css/style.css"/>
  <link rel="stylesheet" href="css/faq.css"/>
</head>
<body>

<!-- ── Announcement Ticker ─────────────────────────── -->
<div style="position:fixed;top:0;left:0;right:0;z-index:200;background:linear-gradient(90deg,#5d3f5d,#3a1a3a);border-bottom:1px solid rgba(197,160,89,.3);padding:7px 0;overflow:hidden;white-space:nowrap;"><div style="display:inline-flex;align-items:center;animation:tickerScroll 28s linear infinite;"><span style="padding:0 40px;color:#fff;font-size:.78rem;">🔴 &nbsp; Lineup Final YOUTHEVER 2026 Telah Dikonfirmasi!</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#d4af37;font-size:.78rem;">🎟️ &nbsp; Early Bird Regular Pass HABIS — Tiket normal masih tersedia terbatas.</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#fff;font-size:.78rem;">📅 &nbsp; Festival berlangsung 24–26 Oktober 2026 di Neon District, Jakarta.</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#d4af37;font-size:.78rem;">⭐ &nbsp; Area VIP Lounge kini dibuka pendaftaran!</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#fff;font-size:.78rem;">🔴 &nbsp; Lineup Final YOUTHEVER 2026 Telah Dikonfirmasi!</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#d4af37;font-size:.78rem;">🎟️ &nbsp; Early Bird Regular Pass HABIS — Tiket normal masih tersedia terbatas.</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#fff;font-size:.78rem;">📅 &nbsp; Festival berlangsung 24–26 Oktober 2026 di Neon District, Jakarta.</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#d4af37;font-size:.78rem;">⭐ &nbsp; Area VIP Lounge kini dibuka pendaftaran!</span></div><style>@keyframes tickerScroll{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}</style></div>

<!-- ── NAV ──────────────────────────────────────── -->
<nav style="top:33px;">
  <div class="nav-left"><a href="index.php" class="logo">YOUTHEVER 2026</a></div>
  <button class="nav-toggle" aria-label="Toggle navigation">☰</button>
  <div class="nav-center">
    <a href="index.php">Home</a>
    <a href="about.php">About Us</a>
    <a href="lineup.php">Line Up</a>
    <a href="event-map.php">Venue</a>
    <a href="rundown.php">Rundown</a>
    <a href="announcements.php">Berita</a>
    <a href="faq.php" class="active">FAQ</a>
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
<div class="faq-hero">
  <span class="faq-eyebrow">YOUTHEVER 2026</span>
  <h1>VITAL <span>INTEL</span></h1>
  <p class="fh-sub">FREQUENTLY ASKED QUESTIONS</p>
  <div class="faq-scroll"><span>scroll</span><div class="fa-arr"></div></div>
</div>

<!-- ── BODY ─────────────────────────────────────── -->
<div class="faq-body">
  <div class="faq-list-wrap">

    <div class="faq-section-head">
      <div class="fsh-tag">Pertanyaan Umum</div>
      <p>Klik pertanyaan untuk melihat jawaban</p>
    </div>

    <?php foreach ($faqs as $i => $faq): ?>
    <div class="faq-item" id="faqItem<?php echo $faq['id']; ?>">
      <input type="checkbox" id="faq<?php echo $faq['id']; ?>"/>
      <label for="faq<?php echo $faq['id']; ?>"
             onclick="toggleFaq(<?php echo $faq['id']; ?>)">
        <span class="q-num"><?php printf('%02d', $i+1); ?></span>
        <span class="q-text"><?php echo htmlspecialchars($faq['question']); ?></span>
        <span class="q-icon">+</span>
      </label>
      <div class="faq-answer">
        <p><?php echo htmlspecialchars($faq['answer']); ?></p>
      </div>
    </div>
    <?php endforeach; ?>

    <!-- CTA -->
    <div class="faq-cta">
      <p>Masih ada pertanyaan? Hubungi tim kami langsung.</p>
      <a href="mailto:media@youthreverfest.com">✉ Hubungi Kami</a>
    </div>

  </div>
</div>

<!-- ── FOOTER ────────────────────────────────────── -->
<footer>
  <div class="footer-grid">
    <div class="footer-col"><h3>YOUTHEVER</h3><p>Festival Experience 2026</p></div>
    <div class="footer-col">
      <p><strong>PARTNERSHIP &amp; SPONSORSHIP</strong><br/>partnership@youthreverfest.com</p>
      <p><strong>MEDIA &amp; PRESS</strong><br/>media@youthreverfest.com</p>
    </div>
    <div class="footer-col">
      <p><strong>CONTACT</strong><br/>✉ media@youthreverfest.com<br/>🎧 Youmin +62 812-3456-7890</p>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2026 YOUTHREVERFEST ALL RIGHTS RESERVED.</p>
    <p>🔒 EVENT ADMIN PORTAL</p>
  </div>
</footer>

<script src="js/faq.js"></script>
</body>
</html>
