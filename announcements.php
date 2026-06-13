<?php
session_start();
$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

$announcements = [
    ['id'=>1,'title'=>'Lineup Final Telah Diumumkan!'<?php
session_start();
$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

$announcements = [
    ['id'=>1,'title'=>'Lineup Final Telah Diumumkan!',
     'content'=>'Semua artis yang akan tampil di YOUTHEVER 2026 telah dikonfirmasi. Segera pesan tiketmu sekarang sebelum terlambat.',
     'date'=>'2 Juni 2026','type'=>'penting','image'=>'🎤'],
    ['id'=>2,'title'=>'Perubahan Jadwal Stage A',
     'content'=>'Jadwal stage A untuk hari kedua telah diubah. Beberapa artis akan tampil lebih awal dari rencana semula untuk mengakomodasi acara lain.',
     'date'=>'1 Juni 2026','type'=>'update','image'=>'📅'],
    ['id'=>3,'title'=>'Early Bird Tiket Regular Pass Habis!',
     'content'=>'Early bird untuk kategori Regular Pass telah habis terjual dalam 48 jam. Tiket regular pass dengan harga normal masih tersedia.',
     'date'=>'31 Mei 2026','type'=>'info','image'=>'🎟️'],
    ['id'=>4,'title'=>'Fasilitas Area VIP Dibuka Pendaftaran',
     'content'=>'Area VIP lounge sekarang tersedia dengan fasilitas lengkap: AC, WiFi gratis, catering, dan meet & greet dengan artis pilihan.',
     'date'=>'28 Mei 2026','type'=>'penting','image'=>'⭐'],
    ['id'=>5,'title'=>'Sponsor Terbaru Bergabung dengan YOUTHEVER 2026',
     'content'=>'Kami bangga mengumumkan sponsor terbaru yang bergabung dalam festival ini. Sponsors ini akan memberikan experience eksklusif untuk pengunjung.',
     'date'=>'25 Mei 2026','type'=>'info','image'=>'🤝'],
];
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pengumuman & Berita – YOUTHEVER 2026</title>
  <link rel="stylesheet" href="css/style.css"/>
  <link rel="stylesheet" href="css/announcements.css"/>
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
    <a href="announcements.php" class="active">Berita</a>
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
<div class="ann-hero">
  <span class="ann-eyebrow">YOUTHEVER 2026</span>
  <h1>📢 PENGUMUMAN <span>&amp; BERITA</span></h1>
  <p class="ann-sub">Tetap update dengan semua informasi terbaru tentang YOUTHEVER 2026</p>
  <div class="ann-scroll"><span>scroll</span><div class="aa"></div></div>
</div>

<!-- ── BODY ─────────────────────────────────────── -->
<div class="ann-body">

  <!-- Filter bar -->
  <div class="ann-filter-wrap">
    <button class="filter-btn active" onclick="filterAnn('all',this)">Semua</button>
    <button class="filter-btn" onclick="filterAnn('penting',this)">Penting</button>
    <button class="filter-btn" onclick="filterAnn('update',this)">Update</button>
    <button class="filter-btn" onclick="filterAnn('info',this)">Info</button>
  </div>

  <!-- Grid -->
  <div class="ann-grid-wrap">
    <div class="ann-grid" id="annGrid">

      <?php foreach ($announcements as $a):
        $typeLabel = ['penting'=>'Penting','update'=>'Update','info'=>'Info'];
        $typeIcon  = ['penting'=>'🔴','update'=>'🟡','info'=>'🟢'];
      ?>
      <article class="ann-card" data-type="<?php echo htmlspecialchars($a['type']); ?>">

        <!-- featured icon panel (only visible on first card via CSS) -->
        <div class="ann-icon-panel"><?php echo $a['image']; ?></div>

        <div class="ann-card-inner">
          <span class="ann-card-emoji"><?php echo $a['image']; ?></span>
          <span class="ann-type <?php echo htmlspecialchars($a['type']); ?>">
            <?php echo ($typeIcon[$a['type']]??''); ?> <?php echo ($typeLabel[$a['type']]??'Info'); ?>
          </span>
          <h3><?php echo htmlspecialchars($a['title']); ?></h3>
          <span class="ann-date">📅 <?php echo htmlspecialchars($a['date']); ?></span>
          <p><?php echo htmlspecialchars($a['content']); ?></p>
          <button class="btn-read-more">Baca Selengkapnya →</button>
        </div>

      </article>
      <?php endforeach; ?>

      <div class="ann-empty" id="annEmpty">Tidak ada pengumuman untuk kategori ini.</div>

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

<script src="js/announcements.js"></script>
</body>
</html>
,
     'content'=>'Semua artis yang akan tampil di YOUTHEVER 2026 telah dikonfirmasi. Segera pesan tiketmu sekarang sebelum terlambat.',
     'date'=>'2 Juni 2026','type'=>'penting','image'=>'🎤'],
    ['id'=>2,'title'=>'Perubahan Jadwal Stage A',
     'content'=>'Jadwal stage A untuk hari kedua telah diubah. Beberapa artis akan tampil lebih awal dari rencana semula untuk mengakomodasi acara lain.',
     'date'=>'1 Juni 2026','type'=>'update','image'=>'📅'],
    ['id'=>3,'title'=>'Early Bird Tiket Regular Pass Habis!',
     'content'=>'Early bird untuk kategori Regular Pass telah habis terjual dalam 48 jam. Tiket regular pass dengan harga normal masih tersedia.',
     'date'=>'31 Mei 2026','type'=>'info','image'=>'🎟️'],
    ['id'=>4,'title'=>'Fasilitas Area VIP Dibuka Pendaftaran',
     'content'=>'Area VIP lounge sekarang tersedia dengan fasilitas lengkap: AC, WiFi gratis, catering, dan meet & greet dengan artis pilihan.',
     'date'=>'28 Mei 2026','type'=>'penting','image'=>'⭐'],
    ['id'=>5,'title'=>'Sponsor Terbaru Bergabung dengan YOUTHEVER 2026',
     'content'=>'Kami bangga mengumumkan sponsor terbaru yang bergabung dalam festival ini. Sponsors ini akan memberikan experience eksklusif untuk pengunjung.',
     'date'=>'25 Mei 2026','type'=>'info','image'=>'🤝'],
];
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pengumuman & Berita – YOUTHEVER 2026</title>
  <link rel="stylesheet" href="css/style.css"/>
  <link rel="stylesheet" href="css/announcements.css"/>
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
    <a href="announcements.php" class="active">Berita</a>
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
<div class="ann-hero">
  <span class="ann-eyebrow">YOUTHEVER 2026</span>
  <h1>📢 PENGUMUMAN <span>&amp; BERITA</span></h1>
  <p class="ann-sub">Tetap update dengan semua informasi terbaru tentang YOUTHEVER 2026</p>
  <div class="ann-scroll"><span>scroll</span><div class="aa"></div></div>
</div>

<!-- ── BODY ─────────────────────────────────────── -->
<div class="ann-body">

  <!-- Filter bar -->
  <div class="ann-filter-wrap">
    <button class="filter-btn active" onclick="filterAnn('all',this)">Semua</button>
    <button class="filter-btn" onclick="filterAnn('penting',this)">Penting</button>
    <button class="filter-btn" onclick="filterAnn('update',this)">Update</button>
    <button class="filter-btn" onclick="filterAnn('info',this)">Info</button>
  </div>

  <!-- Grid -->
  <div class="ann-grid-wrap">
    <div class="ann-grid" id="annGrid">

      <?php foreach ($announcements as $a):
        $typeLabel = ['penting'=>'Penting','update'=>'Update','info'=>'Info'];
        $typeIcon  = ['penting'=>'🔴','update'=>'🟡','info'=>'🟢'];
      ?>
      <article class="ann-card" data-type="<?php echo htmlspecialchars($a['type']); ?>">

        <!-- featured icon panel (only visible on first card via CSS) -->
        <div class="ann-icon-panel"><?php echo $a['image']; ?></div>

        <div class="ann-card-inner">
          <span class="ann-card-emoji"><?php echo $a['image']; ?></span>
          <span class="ann-type <?php echo htmlspecialchars($a['type']); ?>">
            <?php echo ($typeIcon[$a['type']]??''); ?> <?php echo ($typeLabel[$a['type']]??'Info'); ?>
          </span>
          <h3><?php echo htmlspecialchars($a['title']); ?></h3>
          <span class="ann-date">📅 <?php echo htmlspecialchars($a['date']); ?></span>
          <p><?php echo htmlspecialchars($a['content']); ?></p>
          <button class="btn-read-more">Baca Selengkapnya →</button>
        </div>

      </article>
      <?php endforeach; ?>

      <div class="ann-empty" id="annEmpty">Tidak ada pengumuman untuk kategori ini.</div>

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

<script src="js/announcements.js"></script>
</body>
</html>
