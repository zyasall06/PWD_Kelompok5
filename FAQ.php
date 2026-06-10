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
  <style>
    /* ══════════════════════════════════════════════
       FAQ PAGE
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
    .faq-hero {
      position:relative; min-height:65vh;
      display:flex; flex-direction:column;
      align-items:center; justify-content:flex-end;
      text-align:center; padding:130px 24px 60px;
      overflow:hidden;
    }
    .faq-hero::before {
      content:''; position:absolute; inset:0;
      background:
        linear-gradient(180deg,
          rgba(0,0,0,.18) 0%,
          rgba(0,0,0,.12) 30%,
          rgba(0,0,0,.93) 100%),
        url("image/back 9.jpg") center/cover no-repeat;
      z-index:0;
    }
    .faq-hero::after {
      content:''; position:absolute; inset:0;
      background:radial-gradient(ellipse at center, transparent 45%, rgba(0,0,0,.52) 100%);
      z-index:0; pointer-events:none;
    }
    .faq-hero > * { position:relative; z-index:1; }

    /* eyebrow */
    .faq-eyebrow {
      display:inline-flex; align-items:center; gap:8px;
      border:1px solid rgba(197,160,89,.45);
      background:rgba(197,160,89,.08);
      color:#c5a059; font-size:.7rem;
      letter-spacing:.22em; text-transform:uppercase;
      padding:6px 18px; border-radius:40px;
      margin-bottom:20px; backdrop-filter:blur(6px);
    }
    .faq-eyebrow::before, .faq-eyebrow::after { content:'✦'; font-size:.55rem; }

    .faq-hero h1 {
      font-size:clamp(2.8rem,9vw,7rem);
      font-weight:900; line-height:.95;
      letter-spacing:-.02em; color:#fff;
      margin-bottom:14px;
      text-shadow:0 4px 40px rgba(0,0,0,.6);
    }
    .faq-hero h1 span {
      color:#d4af37;
      -webkit-text-stroke:1px rgba(197,160,89,.3);
      filter:drop-shadow(0 0 26px rgba(197,160,89,.45));
    }
    .faq-hero .fh-sub {
      color:rgba(255,255,255,.5); font-size:.82rem;
      letter-spacing:.2em; text-transform:uppercase;
    }

    /* scroll hint */
    .faq-scroll {
      position:absolute; bottom:22px; left:50%;
      transform:translateX(-50%);
      display:flex; flex-direction:column; align-items:center; gap:5px;
      color:rgba(255,255,255,.28); font-size:.66rem;
      letter-spacing:.12em; text-transform:uppercase;
      animation:fsh 2s ease-in-out infinite;
    }
    .faq-scroll .fa-arr {
      width:17px; height:17px;
      border-right:1.5px solid rgba(255,255,255,.28);
      border-bottom:1.5px solid rgba(255,255,255,.28);
      transform:rotate(45deg);
    }
    @keyframes fsh {
      0%,100%{ opacity:.35; transform:translateX(-50%) translateY(0); }
      50%    { opacity:.9;  transform:translateX(-50%) translateY(7px); }
    }

    /* ── BODY ────────────────────────────────────── */
    .faq-body { background:#000; padding:0 0 100px; }

    /* ── FAQ LIST ────────────────────────────────── */
    .faq-list-wrap {
      max-width:760px; margin:0 auto;
      padding:56px 24px 0;
    }

    /* section header */
    .faq-section-head {
      text-align:center; margin-bottom:40px;
    }
    .faq-section-head .fsh-tag {
      display:inline-flex; align-items:center; gap:8px;
      color:#c5a059; font-size:.7rem;
      letter-spacing:.18em; text-transform:uppercase; margin-bottom:10px;
    }
    .faq-section-head .fsh-tag::before {
      content:''; display:inline-block;
      width:28px; height:1.5px;
      background:linear-gradient(90deg,#c5a059,transparent);
    }
    .faq-section-head .fsh-tag::after {
      content:''; display:inline-block;
      width:28px; height:1.5px;
      background:linear-gradient(270deg,#c5a059,transparent);
    }
    .faq-section-head p {
      color:#555; font-size:.82rem; letter-spacing:.1em;
    }

    /* accordion item */
    .faq-item {
      border:1px solid #1a1a1a;
      border-radius:12px; overflow:hidden;
      margin-bottom:10px;
      background:#0d0d0d;
      transition:border-color .2s;
    }
    .faq-item:hover { border-color:rgba(93,63,93,.5); }
    .faq-item.open  { border-color:#5d3f5d; }

    /* hide native checkbox */
    .faq-item input[type=checkbox] { display:none; }

    /* label = question row */
    .faq-item label {
      display:flex; align-items:center;
      justify-content:space-between; gap:16px;
      padding:20px 22px;
      cursor:pointer; user-select:none;
      transition:background .15s;
    }
    .faq-item label:hover { background:rgba(93,63,93,.06); }

    .faq-item label .q-num {
      color:#5d3f5d; font-weight:800;
      font-size:.8rem; min-width:28px;
    }
    .faq-item label .q-text {
      flex:1; color:#ccc; font-size:.95rem;
      font-weight:600; line-height:1.4;
      transition:color .2s;
    }
    .faq-item.open label .q-text { color:#fff; }

    /* plus/minus icon */
    .faq-item label .q-icon {
      width:28px; height:28px; border-radius:50%;
      background:#111; border:1px solid #2a2a2a;
      display:flex; align-items:center; justify-content:center;
      font-size:.9rem; color:#888; flex-shrink:0;
      transition:all .2s;
    }
    .faq-item.open label .q-icon {
      background:#5d3f5d; border-color:#5d3f5d; color:#fff;
      transform:rotate(45deg);
    }

    /* answer panel */
    .faq-answer {
      max-height:0; overflow:hidden;
      transition:max-height .35s ease, padding .35s ease;
      padding:0 22px;
    }
    .faq-item.open .faq-answer {
      max-height:400px;
      padding:0 22px 20px;
    }
    .faq-answer p {
      color:#888; font-size:.9rem;
      line-height:1.75; border-top:1px solid #1a1a1a;
      padding-top:16px; margin:0;
    }

    /* reveal anim */
    .faq-item {
      opacity:0; transform:translateY(16px);
      transition:opacity .5s ease, transform .5s ease,
                 border-color .2s;
    }
    .faq-item.visible { opacity:1; transform:translateY(0); }

    /* CTA strip */
    .faq-cta {
      text-align:center; margin-top:48px;
      padding:32px 24px;
      background:#0d0d0d;
      border:1px solid #1a1a1a;
      border-radius:14px;
    }
    .faq-cta p { color:#666; font-size:.88rem; margin-bottom:16px; }
    .faq-cta a {
      display:inline-block;
      background:#5d3f5d; color:#fff;
      padding:11px 28px; border-radius:8px;
      font-weight:700; font-size:.88rem;
      letter-spacing:.04em; text-decoration:none;
      transition:background .2s;
    }
    .faq-cta a:hover { background:#7a4d7a; }

    /* ── FOOTER ──────────────────────────────────── */
    footer { background:#080808; border-top:1px solid #1a1a1a; padding:40px 50px; margin-top:0; }
    .footer-col h3 { color:#d4af37; margin-bottom:10px; }
    .footer-col p  { color:#666; font-size:.88rem; line-height:1.7; }

    @media(max-width:600px){
      .faq-item label { padding:16px; }
      .faq-item label .q-text { font-size:.88rem; }
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
    <a href="announcements.php">Berita</a>
    <a href="faq.php" class="active">FAQ</a>
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

<script>
/* ── Accordion toggle ────────────────────────────── */
function toggleFaq(id) {
  const item = document.getElementById('faqItem' + id);
  const isOpen = item.classList.contains('open');
  // close all
  document.querySelectorAll('.faq-item').forEach(el => {
    el.classList.remove('open');
    el.querySelector('.q-icon').textContent = '+';
  });
  // open clicked if it was closed
  if (!isOpen) {
    item.classList.add('open');
    item.querySelector('.q-icon').textContent = '+';
  }
}

/* ── Scroll reveal ───────────────────────────────── */
const io = new IntersectionObserver(entries => {
  entries.forEach(e => {
    if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); }
  });
}, { threshold: 0.1 });
document.querySelectorAll('.faq-item').forEach((el, i) => {
  el.style.transitionDelay = (i * 0.07) + 's';
  io.observe(el);
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
