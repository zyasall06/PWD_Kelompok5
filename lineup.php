<?php
session_start();
$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Artist Lineup – YOUTHEVER 2026</title>
  <link rel="stylesheet" href="css/style.css"/>
  <style>
    /* ══════════════════════════════════════════════
       LINEUP PAGE
    ══════════════════════════════════════════════ */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: #000; color: #fff; font-family: Arial, sans-serif; overflow-x: hidden; }

    /* ── NAV fixed frosted ───────────────────────── */
    nav {
      position: fixed;
      top: 0; left: 0; right: 0; z-index: 100;
      background: rgba(0,0,0,.82);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border-bottom: 1px solid rgba(197,160,89,.22);
    }

    /* ── HERO ────────────────────────────────────── */
    .lineup-hero {
      position: relative;
      min-height: 72vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-end;
      text-align: center;
      padding: 120px 24px 64px;
      overflow: hidden;
    }
    .lineup-hero::before {
      content: '';
      position: absolute; inset: 0;
      background:
        linear-gradient(180deg,
          rgba(0,0,0,.25) 0%,
          rgba(0,0,0,.15) 35%,
          rgba(0,0,0,.88) 100%),
        url("image/back 5.jpg") center/cover no-repeat;
      z-index: 0;
    }
    /* side vignette */
    .lineup-hero::after {
      content: '';
      position: absolute; inset: 0;
      background: radial-gradient(ellipse at center, transparent 45%, rgba(0,0,0,.5) 100%);
      z-index: 0; pointer-events: none;
    }
    .lineup-hero > * { position: relative; z-index: 1; }

    /* eyebrow */
    .lh-eyebrow {
      display: inline-flex; align-items: center; gap: 8px;
      border: 1px solid rgba(197,160,89,.45);
      background: rgba(197,160,89,.08);
      color: #c5a059; font-size: .7rem;
      letter-spacing: .22em; text-transform: uppercase;
      padding: 6px 18px; border-radius: 40px;
      margin-bottom: 20px;
      backdrop-filter: blur(6px);
    }
    .lh-eyebrow::before, .lh-eyebrow::after { content: '✦'; font-size: .55rem; }

    /* heading */
    .lineup-hero h1 {
      font-size: clamp(3rem, 10vw, 7.5rem);
      font-weight: 900;
      line-height: .95;
      letter-spacing: -.02em;
      color: #fff;
      margin-bottom: 16px;
      text-shadow: 0 4px 40px rgba(0,0,0,.6);
    }
    .lineup-hero h1 span {
      color: #d4af37;
      -webkit-text-stroke: 1px rgba(197,160,89,.3);
      filter: drop-shadow(0 0 28px rgba(197,160,89,.45));
    }

    /* sub text */
    .lineup-hero .lh-sub {
      color: rgba(255,255,255,.6);
      font-size: clamp(.85rem, 1.8vw, 1rem);
      max-width: 500px;
      line-height: 1.65;
      margin-bottom: 36px;
    }

    /* scroll hint */
    .lineup-scroll {
      position: absolute; bottom: 24px; left: 50%;
      transform: translateX(-50%);
      display: flex; flex-direction: column; align-items: center; gap: 5px;
      color: rgba(255,255,255,.28); font-size: .66rem;
      letter-spacing: .12em; text-transform: uppercase;
      animation: sh 2s ease-in-out infinite;
    }
    .lineup-scroll .sarr {
      width: 17px; height: 17px;
      border-right: 1.5px solid rgba(255,255,255,.28);
      border-bottom: 1.5px solid rgba(255,255,255,.28);
      transform: rotate(45deg);
    }
    @keyframes sh {
      0%,100%{ opacity:.35; transform:translateX(-50%) translateY(0); }
      50%    { opacity:.85; transform:translateX(-50%) translateY(7px); }
    }

    /* ── MAIN SECTION ────────────────────────────── */
    .lineup-section {
      background: #000;
      padding: 0 0 100px;
    }

    /* ── FILTER BAR ──────────────────────────────── */
    .filter-wrap {
      position: sticky;
      top: 62px; z-index: 90;
      background: rgba(0,0,0,.9);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-bottom: 1px solid #1a1a1a;
      padding: 16px 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      flex-wrap: wrap;
    }
    .filter-btns { display: flex; gap: 10px; flex-wrap: wrap; justify-content: center; }
    .filter-btn {
      padding: 9px 24px;
      border-radius: 40px;
      border: 1.5px solid #2a2a2a;
      background: transparent;
      color: #888; font-size: .82rem; font-weight: 700;
      letter-spacing: .08em; text-transform: uppercase;
      cursor: pointer; transition: all .22s;
    }
    .filter-btn:hover { border-color: #c5a059; color: #d4af37; }
    .filter-btn.active {
      background: #5d3f5d;
      border-color: #5d3f5d; color: #fff;
    }

    .filter-count { display: none; }

    /* ── ARTIST GRID ─────────────────────────────── */
    .artist-grid-wrap { max-width: 1200px; margin: 0 auto; padding: 48px 24px 0; }

    .artist-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
      gap: 24px;
    }

    /* card */
    .artist-card {
      background: #0d0d0d;
      border: 1px solid #1a1a1a;
      border-radius: 14px;
      overflow: hidden;
      transition: transform .25s, box-shadow .25s, border-color .25s;
      cursor: default;
    }
    .artist-card:hover {
      transform: translateY(-6px);
      border-color: rgba(197,160,89,.4);
      box-shadow: 0 20px 50px rgba(0,0,0,.55), 0 0 0 1px rgba(197,160,89,.15);
    }

    /* image container */
    .artist-card .img-wrap {
      position: relative;
      overflow: hidden;
      aspect-ratio: 3/4;
      background: #111;
    }
    .artist-card img {
      width: 100%; height: 100%;
      object-fit: cover;
      display: block;
      transition: transform .4s ease;
    }
    .artist-card:hover img { transform: scale(1.06); }

    /* day badge */
    .day-badge {
      position: absolute; top: 12px; left: 12px;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: .68rem; font-weight: 800;
      letter-spacing: .1em; text-transform: uppercase;
      backdrop-filter: blur(6px);
    }
    .artist-card.day1 .day-badge { background: rgba(52,152,219,.25); border: 1px solid rgba(52,152,219,.45); color: #74c0f5; }
    .artist-card.day2 .day-badge { background: rgba(155,89,182,.25); border: 1px solid rgba(155,89,182,.45); color: #c39bd3; }

    /* overlay on hover */
    .artist-card .img-wrap::after {
      content: '';
      position: absolute; inset: 0;
      background: linear-gradient(180deg, transparent 40%, rgba(0,0,0,.7) 100%);
    }

    /* card body */
    .artist-card .card-body {
      padding: 18px 18px 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
    }
    .card-info h3 {
      font-size: .95rem; font-weight: 800;
      letter-spacing: .05em; color: #fff;
      margin-bottom: 4px;
    }
    .card-info p {
      font-size: .72rem; color: #c5a059;
      letter-spacing: .1em; text-transform: uppercase;
    }

    /* add button */
    .add-btn {
      width: 36px; height: 36px;
      border-radius: 50%;
      background: transparent;
      border: 1.5px solid #333;
      color: #888; font-size: 1.2rem;
      cursor: pointer; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
      transition: all .2s;
      line-height: 1;
    }
    .add-btn:hover, .add-btn.added {
      background: linear-gradient(135deg, #c5a059, #d4af37);
      border-color: transparent; color: #000; font-weight: 700;
    }
    .add-btn.added::before { content: '✓'; font-size: .9rem; }

    /* hidden state */
    .artist-card.hidden { display: none; }

    /* empty state */
    .no-results {
      display: none;
      grid-column: 1/-1;
      text-align: center;
      padding: 60px 20px;
      color: #444; font-size: .9rem;
    }
    .no-results.show { display: block; }

    /* ── FOOTER ──────────────────────────────────── */
    footer {
      background: #080808;
      border-top: 1px solid #1a1a1a;
      padding: 40px 50px;
      margin-top: 0;
    }
    .footer-col h3 { color: #d4af37; margin-bottom: 10px; }
    .footer-col p  { color: #666; font-size: .88rem; line-height: 1.7; }

    /* ── RESPONSIVE ──────────────────────────────── */
    @media (max-width: 768px) {
      .artist-grid { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
    }
    @media (max-width: 480px) {
      .artist-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
      .lineup-hero  { min-height: 60vh; }
    }
  </style>
</head>
<body>

<!-- ── Announcement Ticker ─────────────────────────── -->
<div style="position:fixed;top:0;left:0;right:0;z-index:200;background:linear-gradient(90deg,#5d3f5d,#3a1a3a);border-bottom:1px solid rgba(197,160,89,.3);padding:7px 0;overflow:hidden;white-space:nowrap;"><div style="display:inline-flex;align-items:center;animation:tickerScroll 28s linear infinite;"><span style="padding:0 40px;color:#fff;font-size:.78rem;">🔴 &nbsp; Lineup Final YOUTHEVER 2026 Telah Dikonfirmasi!</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#d4af37;font-size:.78rem;">🎟️ &nbsp; Early Bird Regular Pass HABIS — Tiket normal masih tersedia terbatas.</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#fff;font-size:.78rem;">📅 &nbsp; Festival berlangsung 24–26 Oktober 2026 di Neon District, Jakarta.</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#d4af37;font-size:.78rem;">⭐ &nbsp; Area VIP Lounge kini dibuka pendaftaran!</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#fff;font-size:.78rem;">🔴 &nbsp; Lineup Final YOUTHEVER 2026 Telah Dikonfirmasi!</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#d4af37;font-size:.78rem;">🎟️ &nbsp; Early Bird Regular Pass HABIS — Tiket normal masih tersedia terbatas.</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#fff;font-size:.78rem;">📅 &nbsp; Festival berlangsung 24–26 Oktober 2026 di Neon District, Jakarta.</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#d4af37;font-size:.78rem;">⭐ &nbsp; Area VIP Lounge kini dibuka pendaftaran!</span></div><style>@keyframes tickerScroll{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}</style></div>

<!-- ── NAV ──────────────────────────────────────── -->
<nav style="top:33px;">
  <div class="nav-left">
    <a href="index.php" class="logo">YOUTHEVER 2026</a>
  </div>
  <button class="nav-toggle" aria-label="Toggle navigation">☰</button>
  <div class="nav-center">
    <a href="index.php">Home</a>
    <a href="about.php">About Us</a>
    <a href="lineup.php" class="active">Line Up</a>
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
<div class="lineup-hero">
  <span class="lh-eyebrow">YOUTHEVER 2026</span>
  <h1>ARTIST <span>LINEUP</span></h1>
  <p class="lh-sub">Filter by day and click artists to add them to your personalized festival schedule.</p>
  <div class="lineup-scroll">
    <span>scroll</span>
    <div class="sarr"></div>
  </div>
</div>

<!-- ── LINEUP SECTION ────────────────────────────── -->
<section class="lineup-section">

  <!-- Filter bar -->
  <div class="filter-wrap">
    <div class="filter-btns">
      <button class="filter-btn active" type="button" onclick="setFilter('all', this)">ALL</button>
      <button class="filter-btn" type="button" onclick="setFilter('day1', this)">DAY 1</button>
      <button class="filter-btn" type="button" onclick="setFilter('day2', this)">DAY 2</button>
    </div>
    <span class="filter-count" id="filterCount"></span>
  </div>

  <!-- Grid -->
  <div class="artist-grid-wrap">
    <div class="artist-grid" id="artistGrid">

      <div class="artist-card day2">
        <div class="img-wrap">
          <img src="image/kunto aji.jpg" alt="Kunto Aji" loading="lazy"/>
          <span class="day-badge">Day 2</span>
        </div>
        <div class="card-body">
          <div class="card-info">
            <h3>KUNTO AJI</h3>
            <p>INDONESIA POP</p>
          </div>
          <button class="add-btn" onclick="toggleAdd(this)" aria-label="Add to schedule">+</button>
        </div>
      </div>

      <div class="artist-card day2">
        <div class="img-wrap">
          <img src="image/hindia.jpeg" alt="Hindia" loading="lazy"/>
          <span class="day-badge">Day 2</span>
        </div>
        <div class="card-body">
          <div class="card-info">
            <h3>HINDIA</h3>
            <p>INDIE ROCK</p>
          </div>
          <button class="add-btn" onclick="toggleAdd(this)" aria-label="Add to schedule">+</button>
        </div>
      </div>

      <div class="artist-card day1">
        <div class="img-wrap">
          <img src="image/vancouver sleep clinic.jpg" alt="Vancouver Sleep Clinic" loading="lazy"/>
          <span class="day-badge">Day 1</span>
        </div>
        <div class="card-body">
          <div class="card-info">
            <h3>VANCOUVER SLEEP CLINIC</h3>
            <p>INDIE POP</p>
          </div>
          <button class="add-btn" onclick="toggleAdd(this)" aria-label="Add to schedule">+</button>
        </div>
      </div>

      <div class="artist-card day1">
        <div class="img-wrap">
          <img src="image/nadin.jpg" alt="Nadin Amizah" loading="lazy"/>
          <span class="day-badge">Day 1</span>
        </div>
        <div class="card-body">
          <div class="card-info">
            <h3>NADIN AMIZAH</h3>
            <p>INDIE POP</p>
          </div>
          <button class="add-btn" onclick="toggleAdd(this)" aria-label="Add to schedule">+</button>
        </div>
      </div>

      <div class="artist-card day1">
        <div class="img-wrap">
          <img src="image/joji.jpg" alt="Joji" loading="lazy"/>
          <span class="day-badge">Day 1</span>
        </div>
        <div class="card-body">
          <div class="card-info">
            <h3>JOJI</h3>
            <p>ALTERNATIVE R&amp;B</p>
          </div>
          <button class="add-btn" onclick="toggleAdd(this)" aria-label="Add to schedule">+</button>
        </div>
      </div>

      <div class="artist-card day1">
        <div class="img-wrap">
          <img src="image/sleeping at last.jpg" alt="Sleeping At Last" loading="lazy"/>
          <span class="day-badge">Day 1</span>
        </div>
        <div class="card-body">
          <div class="card-info">
            <h3>SLEEPING AT LAST</h3>
            <p>INDIE POP</p>
          </div>
          <button class="add-btn" onclick="toggleAdd(this)" aria-label="Add to schedule">+</button>
        </div>
      </div>

      <div class="artist-card day2">
        <div class="img-wrap">
          <img src="image/sal priadi.jpg" alt="Sal Priadi" loading="lazy"/>
          <span class="day-badge">Day 2</span>
        </div>
        <div class="card-body">
          <div class="card-info">
            <h3>SAL PRIADI</h3>
            <p>ART POP</p>
          </div>
          <button class="add-btn" onclick="toggleAdd(this)" aria-label="Add to schedule">+</button>
        </div>
      </div>

      <div class="artist-card day2">
        <div class="img-wrap">
          <img src="image/reality club.jpg" alt="Reality Club" loading="lazy"/>
          <span class="day-badge">Day 2</span>
        </div>
        <div class="card-body">
          <div class="card-info">
            <h3>REALITY CLUB</h3>
            <p>INDIE ROCK</p>
          </div>
          <button class="add-btn" onclick="toggleAdd(this)" aria-label="Add to schedule">+</button>
        </div>
      </div>

      <div class="artist-card day2">
        <div class="img-wrap">
          <img src="image/cortis.jpg" alt="Cortis" loading="lazy"/>
          <span class="day-badge">Day 2</span>
        </div>
        <div class="card-body">
          <div class="card-info">
            <h3>CORTIS</h3>
            <p>K POP</p>
          </div>
          <button class="add-btn" onclick="toggleAdd(this)" aria-label="Add to schedule">+</button>
        </div>
      </div>

      <div class="no-results" id="noResults">
        Tidak ada artis untuk filter ini.
      </div>

    </div><!-- /artist-grid -->
  </div>

</section>

<!-- ── FOOTER ────────────────────────────────────── -->
<footer>
  <div class="footer-grid">
    <div class="footer-col">
      <h3>YOUTHEVER</h3>
      <p>Festival Experience 2026</p>
    </div>
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
function setFilter(day, btn) {
  const cards = document.querySelectorAll('.artist-card');
  let visible = 0;

  cards.forEach(card => {
    if (day === 'all' || card.classList.contains(day)) {
      card.classList.remove('hidden');
      visible++;
    } else {
      card.classList.add('hidden');
    }
  });

  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  const countEl = document.getElementById('filterCount');
  countEl.textContent = visible + ' artis';

  const noRes = document.getElementById('noResults');
  noRes.classList.toggle('show', visible === 0);
}

/* ── Add / remove from schedule ─────────────────── */
function toggleAdd(btn) {
  const isAdded = btn.classList.toggle('added');
  if (!isAdded) {
    btn.textContent = '+';
    btn.style.background = '';
    btn.style.borderColor = '';
    btn.style.color = '';
  }
}

/* ── Init count ──────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  const total = document.querySelectorAll('.artist-card').length;
  document.getElementById('filterCount').textContent = total + ' artis';
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
