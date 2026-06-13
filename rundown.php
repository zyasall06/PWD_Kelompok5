<?php
session_start();
$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$day = isset($_GET['day']) ? intval($_GET['day']) : 1;
if ($day !== 1 && $day !== 2) { $day = 1; }
$data = [
    1 => [
        ['15.00 - 16.30', 'FESTIVAL OPENING & CREATIVE MARKET EXPERIENCE', 'Main Area', false],
        ['16.30 - 18.30', 'DREAMY INDIE POP SESSION', 'Sleeping At Last, Vancouver Sleep Clinic, Nadin Amizah', true],
        ['19.00 - 20.30', 'ALTERNATIVE R&B & EMOTIONAL STAGE', 'JOJI', true],
        ['21.00 - 22.30', 'CLOSING REFLECTION SHOW', 'Ambient visual show, lantern moment, audience sing along', false, true],
    ],
    2 => [
        ['15.00 - 16.00', 'COMMUNITY & INTERACTIVE ACTIVITIES', 'Random play dance, merch market, fan interaction', false],
        ['16.00 - 18.00', 'ART POP & INDONESIAN INDIE SESSION', 'Sal Priadi, Kunto Aji', true],
        ['18.30 - 20.00', 'INDIE ROCK STAGE', 'Reality Club, Hindia', true],
        ['20.15 - 21.00', 'K-POP CLOSING PERFORMANCE', 'Cortis, final fireworks & crowd farewell', false, true],
    ],
];
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Rundown – YOUTHEVER 2026</title>
  <link rel="stylesheet" href="css/style.css"/>
  <style>
    /* ══════════════════════════════════════════════
       RUNDOWN PAGE
    ══════════════════════════════════════════════ */
    *, *::before, *::after { box-sizing: border-box; }
    body { background: #000; color: #fff; font-family: Arial, sans-serif; margin: 0; overflow-x: hidden; }

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
    .rd-hero {
      position: relative;
      min-height: 68vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-end;
      text-align: center;
      padding: 130px 24px 60px;
      overflow: hidden;
    }
    .rd-hero::before {
      content: '';
      position: absolute; inset: 0;
      background:
        linear-gradient(180deg,
          rgba(0,0,0,.2)  0%,
          rgba(0,0,0,.15) 35%,
          rgba(0,0,0,.9)  100%),
        url("image/back 6.jpg") center/cover no-repeat;
      z-index: 0;
    }
    .rd-hero::after {
      content: '';
      position: absolute; inset: 0;
      background: radial-gradient(ellipse at center, transparent 45%, rgba(0,0,0,.55) 100%);
      z-index: 0; pointer-events: none;
    }
    .rd-hero > * { position: relative; z-index: 1; }

    /* eyebrow */
    .rd-eyebrow {
      display: inline-flex; align-items: center; gap: 8px;
      border: 1px solid rgba(197,160,89,.45);
      background: rgba(197,160,89,.08);
      color: #c5a059; font-size: .7rem;
      letter-spacing: .22em; text-transform: uppercase;
      padding: 6px 18px; border-radius: 40px;
      margin-bottom: 20px; backdrop-filter: blur(6px);
    }
    .rd-eyebrow::before, .rd-eyebrow::after { content: '✦'; font-size: .55rem; }

    .rd-hero h1 {
      font-size: clamp(2.8rem, 9vw, 7rem);
      font-weight: 900; line-height: .95;
      letter-spacing: -.02em; color: #fff;
      margin-bottom: 14px;
      text-shadow: 0 4px 40px rgba(0,0,0,.6);
    }
    .rd-hero h1 span {
      color: #d4af37;
      -webkit-text-stroke: 1px rgba(197,160,89,.3);
      filter: drop-shadow(0 0 26px rgba(197,160,89,.45));
    }
    .rd-hero .rd-sub {
      color: rgba(255,255,255,.55);
      font-size: .88rem; letter-spacing: .18em;
      text-transform: uppercase; margin-bottom: 0;
    }

    /* scroll hint */
    .rd-scroll {
      position: absolute; bottom: 22px; left: 50%;
      transform: translateX(-50%);
      display: flex; flex-direction: column; align-items: center; gap: 5px;
      color: rgba(255,255,255,.28); font-size: .66rem;
      letter-spacing: .12em; text-transform: uppercase;
      animation: rsh 2s ease-in-out infinite;
    }
    .rd-scroll .ra {
      width: 17px; height: 17px;
      border-right: 1.5px solid rgba(255,255,255,.28);
      border-bottom: 1.5px solid rgba(255,255,255,.28);
      transform: rotate(45deg);
    }
    @keyframes rsh {
      0%,100%{ opacity:.35; transform:translateX(-50%) translateY(0); }
      50%    { opacity:.9;  transform:translateX(-50%) translateY(7px); }
    }

    /* ── BODY ────────────────────────────────────── */
    .rd-body { background: #000; padding: 0 0 100px; }

    /* ── DAY TABS ─────────────────────────────────── */
    .rd-tabs-wrap {
      position: sticky; top: 62px; z-index: 90;
      background: rgba(0,0,0,.92);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-bottom: 1px solid #1a1a1a;
      padding: 14px 24px;
      display: flex; justify-content: center;
    }
    .rd-tabs {
      display: inline-flex;
      background: #0d0d0d;
      border: 1px solid #222;
      border-radius: 10px;
      overflow: hidden;
    }
    .rd-tabs .tab-link {
      padding: 13px 36px;
      color: #555; font-weight: 700;
      font-size: .85rem; letter-spacing: .1em;
      text-transform: uppercase; text-decoration: none;
      transition: all .2s; border-bottom: none;
    }
    .rd-tabs .tab-link:hover { color: #d4af37; background: #111; }
    .rd-tabs .tab-link.active {
      background: #5d3f5d;
      color: #fff;
    }

    /* day label */
    .rd-day-label {
      text-align: center; padding: 22px 24px 0;
      font-size: .72rem; color: #444;
      letter-spacing: .14em; text-transform: uppercase;
    }
    .rd-day-label strong { color: #d4af37; }

    /* ── TIMELINE CARDS ──────────────────────────── */
    .rd-list {
      max-width: 760px;
      margin: 28px auto 0;
      padding: 0 24px;
      display: flex;
      flex-direction: column;
      gap: 0;
    }

    /* timeline line */
    .rd-list { position: relative; }
    .rd-list::before {
      content: '';
      position: absolute;
      left: 48px; top: 0; bottom: 0;
      width: 2px;
      background: linear-gradient(180deg, #5d3f5d, #2a1a2a 90%);
      z-index: 0;
    }

    /* card */
    .rd-card {
      position: relative;
      display: flex;
      align-items: center;
      gap: 20px;
      padding: 22px 20px 22px 0;
      cursor: pointer;
      transition: all .18s;
      z-index: 1;
    }
    .rd-card::after {
      content: '';
      position: absolute;
      inset: 6px 0;
      background: rgba(93,63,93,.0);
      border-radius: 12px;
      transition: background .2s;
      z-index: -1;
    }
    .rd-card:hover::after { background: rgba(93,63,93,.08); }

    /* timeline dot */
    .rd-dot {
      width: 14px; height: 14px;
      border-radius: 50%;
      background: #5d3f5d;
      border: 2px solid #000;
      flex-shrink: 0;
      margin-left: 42px;
      box-shadow: 0 0 0 3px rgba(93,63,93,.35);
      transition: transform .2s, box-shadow .2s;
    }
    .rd-card:hover .rd-dot {
      transform: scale(1.3);
      box-shadow: 0 0 0 5px rgba(93,63,93,.4);
    }

    /* time */
    .rd-time {
      color: #d4af37; font-weight: 700;
      font-size: .82rem; letter-spacing: .05em;
      min-width: 110px; white-space: nowrap;
    }

    /* details */
    .rd-details { flex: 1; }
    .rd-details h3 {
      color: #fff; font-size: .98rem;
      font-weight: 700; margin-bottom: 4px;
      line-height: 1.3;
    }
    .rd-details p { color: #666; font-size: .82rem; line-height: 1.5; }

    /* status badges */
    .rd-status {
      font-size: .72rem; font-weight: 800;
      white-space: nowrap; padding: 5px 13px;
      border-radius: 20px; letter-spacing: .05em;
      text-transform: uppercase;
    }
    .status-schedule { background: rgba(93,63,93,.25); border: 1px solid rgba(93,63,93,.5); color: #c39bd3; }
    .status-remind   { background: rgba(243,156,18,.12); border: 1px solid rgba(243,156,18,.35); color: #f39c12; }
    .status-add      { background: #111; border: 1px solid #2a2a2a; color: #555; transition: all .2s; }
    .rd-card:hover .status-add { border-color: #5d3f5d; color: #c39bd3; }

    /* ── FOOTER ──────────────────────────────────── */
    footer {
      background: #080808;
      border-top: 1px solid #1a1a1a;
      padding: 40px 50px; margin-top: 0;
    }
    .footer-col h3 { color: #d4af37; margin-bottom: 10px; }
    .footer-col p  { color: #666; font-size: .88rem; line-height: 1.7; }

    /* ── RESPONSIVE ──────────────────────────────── */
    @media (max-width: 600px) {
      .rd-list::before { left: 30px; }
      .rd-dot { margin-left: 24px; }
      .rd-time { min-width: 85px; font-size: .76rem; }
      .rd-details h3 { font-size: .88rem; }
      .rd-status { display: none; }
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
  <button type="button" class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">&#9776;</button>
  <div class="nav-center">
    <a href="index.php">Home</a>
    <a href="about.php">About Us</a>
    <a href="lineup.php">Line Up</a>
    <a href="event-map.php">Venue</a>
    <a href="rundown.php" class="active">Rundown</a>
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
<div class="rd-hero">
  <span class="rd-eyebrow">YOUTHEVER 2026</span>
  <h1>FESTIVAL <span>RUNDOWN</span></h1>
  <p class="rd-sub">YOUTHEVER 2026</p>
  <div class="rd-scroll">
    <span>scroll</span>
    <div class="ra"></div>
  </div>
</div>

<!-- ── BODY ─────────────────────────────────────── -->
<div class="rd-body">

  <!-- Day tabs -->
  <div class="rd-tabs-wrap">
    <div class="rd-tabs">
      <a href="rundown.php?day=1" class="tab-link <?php echo $day===1?'active':''; ?>">DAY 1</a>
      <a href="rundown.php?day=2" class="tab-link <?php echo $day===2?'active':''; ?>">DAY 2</a>
    </div>
  </div>

  <!-- Day label -->
  <p class="rd-day-label">
    Jadwal <strong>Day <?php echo $day; ?></strong> — klik kartu untuk menambahkan ke jadwalmu
  </p>

  <!-- Timeline list -->
  <div class="rd-list">
    <?php foreach ($data[$day] as $item):
      $originalStatus = 'none';
      if (isset($item[4]) && $item[4])   $originalStatus = 'schedule';
      elseif ($item[3])                   $originalStatus = 'remind';
    ?>
    <div class="rd-card"
         data-original-status="<?php echo $originalStatus; ?>"
         data-added="<?php echo $originalStatus==='schedule'?'true':'false'; ?>">

      <div class="rd-dot"></div>
      <div class="rd-time"><?php echo htmlspecialchars($item[0]); ?></div>
      <div class="rd-details">
        <h3><?php echo htmlspecialchars($item[1]); ?></h3>
        <p><?php echo htmlspecialchars($item[2]); ?></p>
      </div>

      <?php if ($originalStatus === 'schedule'): ?>
        <div class="rd-status status-schedule">IN SCHEDULE</div>
      <?php elseif ($originalStatus === 'remind'): ?>
        <div class="rd-status status-remind">REMIND ME</div>
      <?php else: ?>
        <div class="rd-status status-add">+ ADD</div>
      <?php endif; ?>

    </div>
    <?php endforeach; ?>
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
      <p><strong>PARTNERSHIP &amp; SPONSORSHIP</strong><br/>partnership@youthreverfest.com</p>
      <p><strong>MEDIA &amp; PRESS</strong><br/>media@youthreverfest.com</p>
    </div>
    <div class="footer-col">
      <p><strong>CONTACT</strong><br/>&#9993; media@youthreverfest.com<br/>Youmin +62 812-3456-7890</p>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; 2026 YOUTHREVERFEST ALL RIGHTS RESERVED.</p>
    <p>🔒 EVENT ADMIN PORTAL</p>
  </div>
</footer>

<script>
/* Card click toggle */
document.querySelectorAll('.rd-card').forEach(card => {
  card.addEventListener('click', () => {
    const statusEl = card.querySelector('.rd-status');
    const orig  = card.dataset.originalStatus;
    const added = card.dataset.added === 'true';

    if (added) {
      card.dataset.added = 'false';
      if (orig === 'remind') {
        statusEl.textContent = 'REMIND ME';
        statusEl.className = 'rd-status status-remind';
      } else {
        statusEl.textContent = '+ ADD';
        statusEl.className = 'rd-status status-add';
      }
    } else {
      card.dataset.added = 'true';
      statusEl.textContent = 'IN SCHEDULE';
      statusEl.className = 'rd-status status-schedule';
    }
  });
});

/* Nav toggle */
(function(){
  var btn = document.querySelector('.nav-toggle');
  var nav = document.querySelector('nav');
  if (!btn||!nav) return;
  btn.addEventListener('click', function(){
    nav.classList.toggle('nav-open');
    btn.setAttribute('aria-expanded', nav.classList.contains('nav-open'));
  });
})();
</script>
</body>
</html>
