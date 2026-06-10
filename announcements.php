<?php
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
  <style>
    /* ══════════════════════════════════════════════
       ANNOUNCEMENTS PAGE
    ══════════════════════════════════════════════ */
    *, *::before, *::after { box-sizing: border-box; }
    body { background:#000; color:#fff; font-family:Arial,sans-serif; margin:0; overflow-x:hidden; }

    /* ── NAV ─────────────────────────────────────── */
    nav {
      position:fixed; top:0; left:0; right:0; z-index:100;
      background:rgba(0,0,0,.82);
      backdrop-filter:blur(14px); -webkit-backdrop-filter:blur(14px);
      border-bottom:1px solid rgba(197,160,89,.22);
    }

    /* ── HERO ────────────────────────────────────── */
    .ann-hero {
      position:relative; min-height:62vh;
      display:flex; flex-direction:column;
      align-items:center; justify-content:flex-end;
      text-align:center; padding:130px 24px 56px;
      overflow:hidden;
    }
    .ann-hero::before {
      content:''; position:absolute; inset:0;
      background:
        linear-gradient(180deg,
          rgba(0,0,0,.2) 0%,
          rgba(0,0,0,.15) 35%,
          rgba(0,0,0,.92) 100%),
        url("image/back 8.jpg") center/cover no-repeat;
      z-index:0;
    }
    .ann-hero::after {
      content:''; position:absolute; inset:0;
      background:radial-gradient(ellipse at center, transparent 45%, rgba(0,0,0,.5) 100%);
      z-index:0; pointer-events:none;
    }
    .ann-hero > * { position:relative; z-index:1; }

    .ann-eyebrow {
      display:inline-flex; align-items:center; gap:8px;
      border:1px solid rgba(197,160,89,.45);
      background:rgba(197,160,89,.08);
      color:#c5a059; font-size:.7rem;
      letter-spacing:.22em; text-transform:uppercase;
      padding:6px 18px; border-radius:40px;
      margin-bottom:20px; backdrop-filter:blur(6px);
    }
    .ann-eyebrow::before, .ann-eyebrow::after { content:'✦'; font-size:.55rem; }

    .ann-hero h1 {
      font-size:clamp(2.6rem,8.5vw,6.5rem);
      font-weight:900; line-height:.95;
      letter-spacing:-.02em; color:#fff;
      margin-bottom:14px;
      text-shadow:0 4px 40px rgba(0,0,0,.6);
    }
    .ann-hero h1 span {
      color:#d4af37;
      -webkit-text-stroke:1px rgba(197,160,89,.3);
      filter:drop-shadow(0 0 26px rgba(197,160,89,.45));
    }
    .ann-hero .ann-sub {
      color:rgba(255,255,255,.55); font-size:.88rem;
      max-width:460px; line-height:1.6;
    }

    /* scroll hint */
    .ann-scroll {
      position:absolute; bottom:22px; left:50%;
      transform:translateX(-50%);
      display:flex; flex-direction:column; align-items:center; gap:5px;
      color:rgba(255,255,255,.28); font-size:.66rem;
      letter-spacing:.12em; text-transform:uppercase;
      animation:ash 2s ease-in-out infinite;
    }
    .ann-scroll .aa { width:17px; height:17px;
      border-right:1.5px solid rgba(255,255,255,.28);
      border-bottom:1.5px solid rgba(255,255,255,.28);
      transform:rotate(45deg); }
    @keyframes ash {
      0%,100%{ opacity:.35; transform:translateX(-50%) translateY(0); }
      50%    { opacity:.9;  transform:translateX(-50%) translateY(7px); }
    }

    /* ── BODY ────────────────────────────────────── */
    .ann-body { background:#000; padding:0 0 100px; }

    /* ── FILTER BAR ──────────────────────────────── */
    .ann-filter-wrap {
      position:sticky; top:62px; z-index:90;
      background:rgba(0,0,0,.92);
      backdrop-filter:blur(12px); -webkit-backdrop-filter:blur(12px);
      border-bottom:1px solid #1a1a1a;
      padding:14px 24px;
      display:flex; justify-content:center; gap:10px; flex-wrap:wrap;
    }
    .filter-btn {
      padding:9px 22px; border-radius:40px;
      border:1.5px solid #2a2a2a; background:transparent;
      color:#888; font-size:.82rem; font-weight:700;
      letter-spacing:.06em; text-transform:uppercase;
      cursor:pointer; transition:all .2s;
    }
    .filter-btn:hover { border-color:#c5a059; color:#d4af37; }
    .filter-btn.active { background:#5d3f5d; border-color:#5d3f5d; color:#fff; }

    /* ── GRID ────────────────────────────────────── */
    .ann-grid-wrap {
      max-width:1100px; margin:0 auto;
      padding:48px 24px 0;
    }

    /* featured first card = full width */
    .ann-grid {
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:20px;
    }
    .ann-grid .ann-card:first-child {
      grid-column:1/-1;
      display:grid; grid-template-columns:1fr 1fr;
      gap:0; align-items:stretch;
    }

    /* card */
    .ann-card {
      background:#0d0d0d;
      border:1px solid #1a1a1a;
      border-radius:14px; overflow:hidden;
      transition:transform .25s, border-color .25s, box-shadow .25s;
      display:flex; flex-direction:column;
    }
    .ann-card:hover {
      transform:translateY(-5px);
      border-color:rgba(197,160,89,.35);
      box-shadow:0 18px 48px rgba(0,0,0,.5);
    }

    /* featured icon panel */
    .ann-card:first-child .ann-icon-panel {
      background:linear-gradient(135deg,#1a0d1a,#0d0d0d);
      display:flex; align-items:center; justify-content:center;
      font-size:5rem; padding:40px;
      border-right:1px solid #1a1a1a;
    }
    .ann-card:not(:first-child) .ann-icon-panel { display:none; }

    /* card inner */
    .ann-card-inner { padding:24px; display:flex; flex-direction:column; flex:1; }

    /* type badge */
    .ann-type {
      display:inline-flex; align-items:center; gap:6px;
      font-size:.7rem; font-weight:800;
      letter-spacing:.08em; text-transform:uppercase;
      padding:4px 12px; border-radius:20px;
      margin-bottom:14px; width:fit-content;
    }
    .ann-type.penting { background:rgba(231,76,60,.15);  color:#e74c3c; border:1px solid rgba(231,76,60,.3); }
    .ann-type.update  { background:rgba(243,156,18,.15); color:#f39c12; border:1px solid rgba(243,156,18,.3); }
    .ann-type.info    { background:rgba(39,174,96,.15);  color:#27ae60; border:1px solid rgba(39,174,96,.3); }

    /* icon for smaller cards */
    .ann-card-emoji { font-size:2rem; margin-bottom:12px; display:block; }
    .ann-card:first-child .ann-card-emoji { display:none; }

    .ann-card-inner h3 {
      color:#fff; font-size:1rem; font-weight:700;
      margin-bottom:10px; line-height:1.35;
    }
    .ann-card:first-child .ann-card-inner h3 { font-size:1.4rem; }

    .ann-card-inner .ann-date { color:#555; font-size:.76rem; margin-bottom:12px; }

    .ann-card-inner p {
      color:#777; font-size:.86rem; line-height:1.65;
      flex:1; margin-bottom:18px;
    }

    .ann-card:first-child .ann-card-inner p { font-size:.95rem; color:#888; }

    /* read more */
    .btn-read-more {
      display:inline-flex; align-items:center; gap:6px;
      background:transparent; border:1px solid #2a2a2a;
      color:#888; padding:8px 16px; border-radius:8px;
      font-size:.8rem; cursor:pointer;
      transition:all .2s; text-decoration:none; width:fit-content;
    }
    .btn-read-more:hover { border-color:#d4af37; color:#d4af37; }

    /* empty state */
    .ann-empty {
      display:none; grid-column:1/-1;
      text-align:center; padding:60px 20px;
      color:#444; font-size:.9rem;
    }
    .ann-empty.show { display:block; }

    /* reveal anim */
    .ann-card {
      opacity:0; transform:translateY(20px);
      transition:opacity .5s ease, transform .5s ease,
                 border-color .25s, box-shadow .25s, transform .25s;
    }
    .ann-card.visible { opacity:1; transform:translateY(0); }
    .ann-card.visible:hover { transform:translateY(-5px); }

    /* ── FOOTER ──────────────────────────────────── */
    footer { background:#080808; border-top:1px solid #1a1a1a; padding:40px 50px; margin-top:0; }
    .footer-col h3 { color:#d4af37; margin-bottom:10px; }
    .footer-col p  { color:#666; font-size:.88rem; line-height:1.7; }

    /* ── RESPONSIVE ──────────────────────────────── */
    @media (max-width:768px) {
      .ann-grid { grid-template-columns:1fr; }
      .ann-grid .ann-card:first-child { grid-column:1; grid-template-columns:1fr; }
      .ann-card:first-child .ann-icon-panel { display:none; }
      .ann-card:first-child .ann-card-emoji { display:block; }
    }
  </style>
</head>
<body>

<!-- ── NAV ──────────────────────────────────────── -->
<nav>
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
    <a href="tickets.php" class="buy-btn">Buy Ticket</a>
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

<script>
/* ── Filter ──────────────────────────────────────── */
function filterAnn(type, btn) {
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  const cards = document.querySelectorAll('.ann-card');
  let visible = 0;
  cards.forEach(card => {
    const match = type === 'all' || card.dataset.type === type;
    card.style.display = match ? '' : 'none';
    if (match) visible++;
  });
  document.getElementById('annEmpty').classList.toggle('show', visible === 0);
}

/* ── Scroll reveal ───────────────────────────────── */
const io = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
}, { threshold: 0.1 });
document.querySelectorAll('.ann-card').forEach((c, i) => {
  c.style.transitionDelay = (i * 0.08) + 's';
  io.observe(c);
});

/* ── Nav toggle ──────────────────────────────────── */
(function(){
  var btn = document.querySelector('.nav-toggle');
  var nav = document.querySelector('nav');
  if (!btn||!nav) return;
  btn.addEventListener('click', function(){ nav.classList.toggle('nav-open'); });
})();
</script>
</body>
</html>
