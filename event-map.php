<?php
session_start();
$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

$locations = [
  ['id'=>1, 'name'=>'Main Stage',           'type'=>'stage',    'icon'=>'🎤','color'=>'#e74c3c','desc'=>'Panggung utama untuk artis headline. Kapasitas 15.000 penonton.','area'=>'North Zone'],
  ['id'=>2, 'name'=>'Side Stage',           'type'=>'stage',    'icon'=>'🎸','color'=>'#e74c3c','desc'=>'Panggung kedua untuk artis supporting. Kapasitas 5.000 penonton.','area'=>'East Zone'],
  ['id'=>3, 'name'=>'Acoustic Stage',       'type'=>'stage',    'icon'=>'🎵','color'=>'#e74c3c','desc'=>'Panggung akustik intimate untuk sesi khusus.','area'=>'South Zone'],
  ['id'=>4, 'name'=>'VIP Lounge',           'type'=>'vip',      'icon'=>'👑','color'=>'#f39c12','desc'=>'Area eksklusif VIP dengan akses bar premium dan lounge nyaman.','area'=>'Center'],
  ['id'=>5, 'name'=>'Premium Viewing',      'type'=>'vip',      'icon'=>'⭐','color'=>'#f39c12','desc'=>'Platform elevated khusus pemegang tiket Premium & VIP.','area'=>'North Zone'],
  ['id'=>6, 'name'=>'Gate A – Main Entrance','type'=>'gate',    'icon'=>'🚪','color'=>'#3498db','desc'=>'Pintu masuk utama dari arah parkir utara. Buka pukul 12.00.','area'=>'North'],
  ['id'=>7, 'name'=>'Gate B – East Entrance','type'=>'gate',    'icon'=>'🚪','color'=>'#3498db','desc'=>'Pintu masuk alternatif sisi timur. Akses khusus VIP & disabilitas.','area'=>'East'],
  ['id'=>8, 'name'=>'Gate C – South Entrance','type'=>'gate',   'icon'=>'🚪','color'=>'#3498db','desc'=>'Pintu masuk selatan dekat parkir bus dan shuttle.','area'=>'South'],
  ['id'=>9, 'name'=>'Food Court A',         'type'=>'food',     'icon'=>'🍔','color'=>'#27ae60','desc'=>'Area kuliner utama dengan 20+ booth makanan & minuman.','area'=>'West Zone'],
  ['id'=>10,'name'=>'Food Court B',         'type'=>'food',     'icon'=>'🍜','color'=>'#27ae60','desc'=>'Food court secondary dengan pilihan street food lokal.','area'=>'East Zone'],
  ['id'=>11,'name'=>'Bar & Beverages',      'type'=>'food',     'icon'=>'🍺','color'=>'#27ae60','desc'=>'Booth minuman beralkohol dan non-alkohol. Wajib 21+.','area'=>'Center'],
  ['id'=>12,'name'=>'Medical Center',       'type'=>'facility', 'icon'=>'🏥','color'=>'#9b59b6','desc'=>'Pusat medis dan first aid. Tersedia dokter & paramedik 24 jam.','area'=>'West Zone'],
  ['id'=>13,'name'=>'Toilet Utama',         'type'=>'facility', 'icon'=>'🚻','color'=>'#9b59b6','desc'=>'Fasilitas toilet pria, wanita, dan disabilitas. 40 bilik tersedia.','area'=>'South Zone'],
  ['id'=>14,'name'=>'Info Booth',           'type'=>'facility', 'icon'=>'ℹ️','color'=>'#9b59b6','desc'=>'Booth informasi, lost & found, dan customer service.','area'=>'Center'],
  ['id'=>15,'name'=>'ATM Center',           'type'=>'facility', 'icon'=>'🏧','color'=>'#9b59b6','desc'=>'Kumpulan ATM multi-bank untuk keperluan tunai.','area'=>'East Zone'],
  ['id'=>16,'name'=>'Merchandise',          'type'=>'merch',    'icon'=>'👕','color'=>'#1abc9c','desc'=>'Official merchandise YOUTHEVER 2026. Limited edition tersedia!','area'=>'Center'],
  ['id'=>17,'name'=>'Photo Booth',          'type'=>'merch',    'icon'=>'📸','color'=>'#1abc9c','desc'=>'Area foto instagramable dengan backdrop festival.','area'=>'West Zone'],
  ['id'=>18,'name'=>'Parkir Utama',         'type'=>'parking',  'icon'=>'🅿️','color'=>'#95a5a6','desc'=>'Area parkir kendaraan roda empat. Kapasitas 3.000 mobil.','area'=>'North'],
  ['id'=>19,'name'=>'Parkir Motor',         'type'=>'parking',  'icon'=>'🏍️','color'=>'#95a5a6','desc'=>'Area parkir khusus sepeda motor. Kapasitas 2.000 motor.','area'=>'South'],
  ['id'=>20,'name'=>'Drop Off / Shuttle',   'type'=>'parking',  'icon'=>'🚌','color'=>'#95a5a6','desc'=>'Area drop off taksi online dan halte shuttle bus.','area'=>'East'],
];

$categories = [
  'all'      => ['label'=>'Semua',       'icon'=>'🗺️','color'=>'#c5a059'],
  'stage'    => ['label'=>'Panggung',    'icon'=>'🎤','color'=>'#e74c3c'],
  'vip'      => ['label'=>'VIP Area',    'icon'=>'👑','color'=>'#f39c12'],
  'gate'     => ['label'=>'Pintu Masuk', 'icon'=>'🚪','color'=>'#3498db'],
  'food'     => ['label'=>'Kuliner',     'icon'=>'🍔','color'=>'#27ae60'],
  'facility' => ['label'=>'Fasilitas',   'icon'=>'🏥','color'=>'#9b59b6'],
  'merch'    => ['label'=>'Merch & Fun', 'icon'=>'👕','color'=>'#1abc9c'],
  'parking'  => ['label'=>'Parkir',      'icon'=>'🅿️','color'=>'#95a5a6'],
];

// Pin coordinates [cx, cy] mapped to SVG viewBox 0 0 900 620
$pinCoords = [
  1  => [240, 150],
  2  => [630, 140],
  3  => [330, 228],
  4  => [450, 320],
  5  => [180, 120],
  6  => [450,  42],
  7  => [862, 310],
  8  => [450, 590],
  9  => [130, 318],
  10 => [768, 318],
  11 => [450, 355],
  12 => [115, 460],
  13 => [720, 460],
  14 => [480, 460],
  15 => [780, 360],
  16 => [635, 305],
  17 => [115, 480],
  18 => [160, 552],
  19 => [740, 552],
  20 => [862, 480],
];
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Venue Map – YOUTHEVER 2026</title>
  <link rel="stylesheet" href="css/style.css"/>
  <style>
    /* ─── Venue Map Page ──────────────────────────────── */
    .vmap-page {
      background:
        linear-gradient(rgba(0,0,0,.72), rgba(0,0,0,.72)),
        url("image/back 4.jpg") center/cover no-repeat fixed;
      min-height:100vh; padding:0 0 80px;
    }

    /* Header */
    .vmap-header { text-align:center; padding:50px 20px 28px; }
    .vmap-header h1 { font-size:2.5rem; color:#d4af37; letter-spacing:.06em; margin-bottom:8px; }
    .vmap-header p  { color:#888; font-size:1rem; }

    /* Filter bar */
    .vmap-filters {
      display:flex; justify-content:center; flex-wrap:wrap;
      gap:10px; padding:0 20px 28px;
    }
    .vmap-filter-btn {
      display:flex; align-items:center; gap:6px;
      padding:9px 18px; border-radius:40px;
      border:2px solid #444; background:transparent;
      color:#ccc; font-size:.85rem; cursor:pointer;
      transition:all .2s; white-space:nowrap;
    }
    .vmap-filter-btn:hover { border-color:#c5a059; color:#fff; }
    .vmap-filter-btn.active { font-weight:700; }

    /* Body grid */
    .vmap-body {
      display:grid;
      grid-template-columns:1fr 340px;
      gap:22px;
      max-width:1280px;
      margin:0 auto;
      padding:0 20px;
    }

    /* Map canvas */
    .vmap-canvas-wrap {
      background:#0d0d0d;
      border:2px solid #2a2a2a;
      border-radius:12px;
      overflow:hidden;
    }
    .vmap-canvas-wrap svg { width:100%; height:auto; display:block; }

    /* Legend bar below map */
    .vmap-legend {
      display:flex; flex-wrap:wrap; gap:8px 16px;
      padding:14px 18px;
      background:#111;
      border-top:1px solid #222;
    }
    .vmap-legend-item { display:flex; align-items:center; gap:6px; font-size:.76rem; color:#888; }
    .vmap-legend-dot  { width:11px; height:11px; border-radius:50%; flex-shrink:0; }

    /* Tooltip */
    .vmap-tooltip {
      position:fixed; background:#1a1a1a; border:1px solid #c5a059;
      border-radius:8px; padding:9px 13px; color:#fff; font-size:.81rem;
      pointer-events:none; z-index:9999; max-width:200px; display:none; line-height:1.4;
    }
    .vmap-tooltip strong { color:#d4af37; display:block; margin-bottom:3px; }

    /* Right panel */
    .vmap-panel { display:flex; flex-direction:column; gap:16px; }

    /* Info card */
    .vmap-info-card {
      background:#1a1a1a; border:2px solid #c5a059;
      border-radius:12px; padding:22px; min-height:190px;
    }
    .info-placeholder {
      display:flex; flex-direction:column; align-items:center;
      justify-content:center; height:150px; color:#444;
      font-size:.88rem; text-align:center; gap:10px;
    }
    .info-placeholder .ph-emoji { font-size:2.4rem; }
    .vmap-info-icon  { font-size:2.6rem; display:block; margin-bottom:6px; }
    .vmap-info-type  {
      display:inline-block; padding:3px 11px; border-radius:20px;
      font-size:.74rem; font-weight:700; margin-bottom:11px;
    }
    .vmap-info-name  { color:#d4af37; font-size:1.2rem; font-weight:700; margin-bottom:6px; }
    .vmap-info-area  { color:#888; font-size:.84rem; margin-bottom:10px; }
    .vmap-info-desc  { color:#ccc; font-size:.88rem; line-height:1.6; }

    /* Location list */
    .vmap-list-wrap { background:#1a1a1a; border:1px solid #2a2a2a; border-radius:12px; overflow:hidden; flex:1; }
    .vmap-list-header {
      padding:12px 16px; background:#222; border-bottom:1px solid #2a2a2a;
      color:#d4af37; font-size:.82rem; font-weight:700;
      letter-spacing:.05em; text-transform:uppercase;
    }
    .vmap-list { max-height:400px; overflow-y:auto; }
    .vmap-list::-webkit-scrollbar { width:4px; }
    .vmap-list::-webkit-scrollbar-track { background:#1a1a1a; }
    .vmap-list::-webkit-scrollbar-thumb { background:#444; border-radius:2px; }

    .vmap-list-item {
      display:flex; align-items:center; gap:12px;
      padding:12px 16px; border-bottom:1px solid #222;
      cursor:pointer; transition:background .15s;
    }
    .vmap-list-item:hover, .vmap-list-item.active { background:#242424; }
    .vmap-list-item.active { border-left:3px solid #d4af37; padding-left:13px; }
    .vmap-list-item.hidden-item { display:none; }
    .vmap-list-icon { font-size:1.35rem; min-width:30px; text-align:center; }
    .vmap-list-text h4 { color:#fff; font-size:.88rem; margin-bottom:2px; }
    .vmap-list-text p  { color:#666; font-size:.76rem; }

    /* Responsive */
    @media (max-width:900px) { .vmap-body { grid-template-columns:1fr; } }
    @media (max-width:600px) {
      .vmap-header h1 { font-size:1.8rem; }
      .vmap-filter-btn { padding:7px 12px; font-size:.78rem; }
    }
  </style>
</head>
<body>

<!-- ── Announcement Ticker ─────────────────────────── -->
<div style="position:fixed;top:0;left:0;right:0;z-index:200;background:linear-gradient(90deg,#5d3f5d,#3a1a3a);border-bottom:1px solid rgba(197,160,89,.3);padding:7px 0;overflow:hidden;white-space:nowrap;"><div style="display:inline-flex;align-items:center;animation:tickerScroll 28s linear infinite;"><span style="padding:0 40px;color:#fff;font-size:.78rem;">🔴 &nbsp; Lineup Final YOUTHEVER 2026 Telah Dikonfirmasi!</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#d4af37;font-size:.78rem;">🎟️ &nbsp; Early Bird Regular Pass HABIS — Tiket normal masih tersedia terbatas.</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#fff;font-size:.78rem;">📅 &nbsp; Festival berlangsung 24–26 Oktober 2026 di Neon District, Jakarta.</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#d4af37;font-size:.78rem;">⭐ &nbsp; Area VIP Lounge kini dibuka pendaftaran!</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#fff;font-size:.78rem;">🔴 &nbsp; Lineup Final YOUTHEVER 2026 Telah Dikonfirmasi!</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#d4af37;font-size:.78rem;">🎟️ &nbsp; Early Bird Regular Pass HABIS — Tiket normal masih tersedia terbatas.</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#fff;font-size:.78rem;">📅 &nbsp; Festival berlangsung 24–26 Oktober 2026 di Neon District, Jakarta.</span><span style="color:rgba(197,160,89,.4);">✦</span><span style="padding:0 40px;color:#d4af37;font-size:.78rem;">⭐ &nbsp; Area VIP Lounge kini dibuka pendaftaran!</span></div><style>@keyframes tickerScroll{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}</style></div>

<!-- ── Navigation ─────────────────────────────────── -->
<nav style="top:33px;">
  <div class="nav-left">
    <a href="index.php" class="logo">YOUTHEVER 2026</a>
  </div>
  <button class="nav-toggle" aria-label="Toggle navigation">☰</button>
  <div class="nav-center">
    <a href="index.php">Home</a>
    <a href="about.php">About Us</a>
    <a href="lineup.php">Line Up</a>
    <a href="event-map.php" class="active">Venue</a>
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

<!-- ── Page ───────────────────────────────────────── -->
<main class="vmap-page">

  <div class="vmap-header">
    <h1>🗺️ Venue Map</h1>
    <p>Jelajahi layout area YOUTHEVER 2026 — klik marker atau pilih kategori</p>
  </div>

  <!-- Filter buttons -->
  <div class="vmap-filters" role="group" aria-label="Filter kategori venue">
    <?php foreach ($categories as $key => $cat): ?>
      <button
        class="vmap-filter-btn <?php echo $key==='all'?'active':''; ?>"
        data-filter="<?php echo $key; ?>"
        style="<?php echo $key==='all'?'background:#c5a059;border-color:#c5a059;color:#000;':''; ?>"
        aria-pressed="<?php echo $key==='all'?'true':'false'; ?>"
      ><?php echo $cat['icon'].' '.$cat['label']; ?></button>
    <?php endforeach; ?>
  </div>

  <!-- Map + Panel -->
  <div class="vmap-body">

    <!-- SVG Venue Map -->
    <div class="vmap-canvas-wrap">
      <svg id="venueMap" viewBox="0 0 900 620" xmlns="http://www.w3.org/2000/svg" aria-label="Peta venue YOUTHEVER 2026">

        <defs>
          <pattern id="gridPat" width="50" height="50" patternUnits="userSpaceOnUse">
            <path d="M50 0L0 0 0 50" fill="none" stroke="#1c1c1c" stroke-width="1"/>
          </pattern>
        </defs>

        <!-- Background -->
        <rect width="900" height="620" fill="#0d0d0d"/>
        <rect width="900" height="620" fill="url(#gridPat)"/>

        <!-- Festival boundary -->
        <rect x="30" y="28" width="840" height="564" rx="14" fill="none" stroke="#2a2a2a" stroke-width="2" stroke-dasharray="8,4"/>

        <!-- Zone: Stage (North) -->
        <rect x="50" y="48" width="800" height="198" rx="10" fill="#160808" stroke="#e74c3c" stroke-width="1" stroke-opacity=".35"/>
        <text x="62" y="68" font-family="Arial" font-size="10" fill="#e74c3c" fill-opacity=".55" letter-spacing="1" text-transform="uppercase">NORTH ZONE · STAGE AREA</text>

        <!-- Zone: Center -->
        <rect x="50" y="260" width="800" height="128" rx="10" fill="#15150a" stroke="#f39c12" stroke-width="1" stroke-opacity=".25"/>
        <text x="62" y="278" font-family="Arial" font-size="10" fill="#f39c12" fill-opacity=".55" letter-spacing="1">CENTER ZONE · VIP &amp; SERVICES</text>

        <!-- Zone: South -->
        <rect x="50" y="402" width="800" height="188" rx="10" fill="#0a0a18" stroke="#3498db" stroke-width="1" stroke-opacity=".25"/>
        <text x="62" y="420" font-family="Arial" font-size="10" fill="#3498db" fill-opacity=".55" letter-spacing="1">SOUTH ZONE · FACILITIES &amp; PARKING</text>

        <!-- Walkways -->
        <rect x="440" y="48" width="20" height="564" fill="#181818"/>
        <rect x="50" y="302" width="800" height="16" fill="#181818"/>

        <!-- ── Stage platforms ── -->
        <rect x="90" y="82" width="288" height="128" rx="8" fill="#200a0a" stroke="#e74c3c" stroke-width="2"/>
        <text x="234" y="138" text-anchor="middle" font-family="Arial" font-size="14" font-weight="bold" fill="#e74c3c">MAIN STAGE</text>
        <text x="234" y="156" text-anchor="middle" font-family="Arial" font-size="10" fill="#666">capacity 15,000</text>

        <rect x="510" y="82" width="230" height="110" rx="8" fill="#160808" stroke="#e74c3c" stroke-width="1.5" stroke-opacity=".7"/>
        <text x="625" y="132" text-anchor="middle" font-family="Arial" font-size="13" font-weight="bold" fill="#e74c3c">SIDE STAGE</text>
        <text x="625" y="150" text-anchor="middle" font-family="Arial" font-size="10" fill="#666">capacity 5,000</text>

        <rect x="260" y="200" width="165" height="50" rx="6" fill="#120505" stroke="#e74c3c" stroke-width="1" stroke-opacity=".5"/>
        <text x="343" y="230" text-anchor="middle" font-family="Arial" font-size="10" fill="#e74c3c">ACOUSTIC STAGE</text>

        <!-- ── VIP box ── -->
        <rect x="348" y="268" width="204" height="102" rx="8" fill="#1a1400" stroke="#f39c12" stroke-width="2"/>
        <text x="450" y="315" text-anchor="middle" font-family="Arial" font-size="13" font-weight="bold" fill="#f39c12">VIP LOUNGE</text>
        <text x="450" y="334" text-anchor="middle" font-family="Arial" font-size="10" fill="#888">exclusive access</text>

        <!-- ── Food Courts ── -->
        <rect x="64" y="270" width="130" height="88" rx="6" fill="#0a180a" stroke="#27ae60" stroke-width="1.5"/>
        <text x="129" y="311" text-anchor="middle" font-family="Arial" font-size="11" font-weight="bold" fill="#27ae60">FOOD</text>
        <text x="129" y="328" text-anchor="middle" font-family="Arial" font-size="10" fill="#27ae60">COURT A</text>

        <rect x="706" y="270" width="130" height="88" rx="6" fill="#0a180a" stroke="#27ae60" stroke-width="1.5"/>
        <text x="771" y="311" text-anchor="middle" font-family="Arial" font-size="11" font-weight="bold" fill="#27ae60">FOOD</text>
        <text x="771" y="328" text-anchor="middle" font-family="Arial" font-size="10" fill="#27ae60">COURT B</text>

        <!-- ── Merch ── -->
        <rect x="570" y="272" width="120" height="60" rx="6" fill="#0a1818" stroke="#1abc9c" stroke-width="1.2"/>
        <text x="630" y="305" text-anchor="middle" font-family="Arial" font-size="10" fill="#1abc9c">MERCH STORE</text>

        <!-- ── Parking boxes ── -->
        <rect x="64" y="508" width="188" height="84" rx="6" fill="#111" stroke="#95a5a6" stroke-width="1.5"/>
        <text x="158" y="549" text-anchor="middle" font-family="Arial" font-size="12" font-weight="bold" fill="#95a5a6">PARKING</text>
        <text x="158" y="568" text-anchor="middle" font-family="Arial" font-size="10" fill="#95a5a6">UTAMA</text>

        <rect x="648" y="508" width="188" height="84" rx="6" fill="#111" stroke="#95a5a6" stroke-width="1.5"/>
        <text x="742" y="549" text-anchor="middle" font-family="Arial" font-size="12" font-weight="bold" fill="#95a5a6">PARKIR</text>
        <text x="742" y="568" text-anchor="middle" font-family="Arial" font-size="10" fill="#95a5a6">MOTOR</text>

        <!-- ── Compass ── -->
        <g transform="translate(844,52)">
          <circle r="20" fill="#111" stroke="#333"/>
          <text text-anchor="middle" y="-6" font-family="Arial" font-size="9" font-weight="bold" fill="#d4af37">N</text>
          <text text-anchor="middle" y="14" font-family="Arial" font-size="7" fill="#555">S</text>
          <text x="9" y="4" font-family="Arial" font-size="7" fill="#555">E</text>
          <text x="-14" y="4" font-family="Arial" font-size="7" fill="#555">W</text>
          <line x1="0" y1="-13" x2="0" y2="13" stroke="#d4af37" stroke-width="1"/>
          <line x1="-13" y1="0" x2="13" y2="0" stroke="#444" stroke-width="1"/>
        </g>

        <!-- Map title -->
        <text x="450" y="20" text-anchor="middle" font-family="Arial" font-size="13" font-weight="bold" fill="#d4af37" letter-spacing="2">YOUTHEVER 2026 – VENUE MAP</text>

        <!-- ── Pins ─────────────────────────────────────── -->
        <?php foreach ($locations as $loc):
          $cx  = $pinCoords[$loc['id']][0];
          $cy  = $pinCoords[$loc['id']][1];
          $col = $loc['color'];
        ?>
        <g class="venue-pin"
           data-id="<?php echo $loc['id']; ?>"
           data-type="<?php echo $loc['type']; ?>"
           data-name="<?php echo htmlspecialchars($loc['name']); ?>"
           data-area="<?php echo htmlspecialchars($loc['area']); ?>"
           data-desc="<?php echo htmlspecialchars($loc['desc']); ?>"
           data-icon="<?php echo $loc['icon']; ?>"
           data-color="<?php echo $col; ?>"
           tabindex="0" role="button"
           aria-label="<?php echo htmlspecialchars($loc['name']); ?>"
           style="cursor:pointer;">
          <!-- outer pulse ring -->
          <circle class="pin-pulse" cx="<?php echo $cx; ?>" cy="<?php echo $cy; ?>" r="20"
            fill="<?php echo $col; ?>" fill-opacity=".18"
            stroke="<?php echo $col; ?>" stroke-width="1.5" stroke-opacity=".5"/>
          <!-- inner dot -->
          <circle cx="<?php echo $cx; ?>" cy="<?php echo $cy; ?>" r="12"
            fill="<?php echo $col; ?>" fill-opacity=".9"/>
          <!-- icon -->
          <text x="<?php echo $cx; ?>" y="<?php echo $cy+4; ?>"
            text-anchor="middle" font-size="10" font-family="Arial" fill="#fff">
            <?php echo $loc['icon']; ?>
          </text>
        </g>
        <?php endforeach; ?>

      </svg>

      <!-- Legend strip -->
      <div class="vmap-legend">
        <?php foreach ($categories as $key => $cat): if ($key==='all') continue; ?>
          <div class="vmap-legend-item">
            <span class="vmap-legend-dot" style="background:<?php echo $cat['color']; ?>"></span>
            <?php echo $cat['icon'].' '.$cat['label']; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div><!-- /vmap-canvas-wrap -->

    <!-- Right panel -->
    <div class="vmap-panel">

      <!-- Info card -->
      <div class="vmap-info-card" id="infoCard">
        <div class="info-placeholder" id="infoPlaceholder">
          <span class="ph-emoji">🗺️</span>
          <p>Klik marker di peta atau pilih lokasi di bawah untuk melihat detail</p>
        </div>
        <div id="infoContent" style="display:none;">
          <span class="vmap-info-icon" id="infoIcon"></span>
          <span class="vmap-info-type" id="infoTypeBadge"></span>
          <div class="vmap-info-name" id="infoName"></div>
          <div class="vmap-info-area">📍 <span id="infoAreaText"></span></div>
          <div class="vmap-info-desc" id="infoDesc"></div>
        </div>
      </div>

      <!-- Location list -->
      <div class="vmap-list-wrap">
        <div class="vmap-list-header">
          📋 Daftar Lokasi &nbsp;<span id="listCount"></span>
        </div>
        <div class="vmap-list" id="locationList">
          <?php foreach ($locations as $loc): ?>
            <div class="vmap-list-item"
                 data-id="<?php echo $loc['id']; ?>"
                 data-type="<?php echo $loc['type']; ?>"
                 tabindex="0" role="button"
                 aria-label="<?php echo htmlspecialchars($loc['name']); ?>">
              <div class="vmap-list-icon"><?php echo $loc['icon']; ?></div>
              <div class="vmap-list-text">
                <h4><?php echo htmlspecialchars($loc['name']); ?></h4>
                <p><?php echo htmlspecialchars($loc['area']); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div><!-- /vmap-panel -->
  </div><!-- /vmap-body -->

</main><!-- /vmap-page -->

<!-- Tooltip -->
<div class="vmap-tooltip" id="vmapTooltip"></div>

<!-- Footer -->
<footer>
  <div class="footer-grid">
    <div class="footer-col"><h3>YOUTHEVER</h3><p>Festival Experience 2026</p></div>
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
const locationData    = <?php echo json_encode(array_values($locations)); ?>;
const categoryColors  = <?php echo json_encode(array_map(fn($c)=>$c['color'], $categories)); ?>;
const categoryLabels  = <?php echo json_encode(array_map(fn($c)=>$c['label'], $categories)); ?>;

let activeFilter = 'all';
let activeId     = null;

const pins       = document.querySelectorAll('.venue-pin');
const listItems  = document.querySelectorAll('.vmap-list-item');
const filterBtns = document.querySelectorAll('.vmap-filter-btn');
const tooltip    = document.getElementById('vmapTooltip');
const listCount  = document.getElementById('listCount');

/* ── Filter ─────────────────────────────────────── */
function applyFilter(filter) {
  activeFilter = filter;
  filterBtns.forEach(btn => {
    const on = btn.dataset.filter === filter;
    btn.classList.toggle('active', on);
    btn.setAttribute('aria-pressed', on ? 'true' : 'false');
    if (on) {
      const col = filter === 'all' ? '#c5a059' : (categoryColors[filter] || '#c5a059');
      btn.style.cssText = `background:${col};border-color:${col};color:#000;font-weight:700;`;
    } else {
      btn.style.cssText = '';
    }
  });

  let vis = 0;
  pins.forEach(p => {
    const show = filter === 'all' || p.dataset.type === filter;
    p.style.opacity = show ? '1' : '0.1';
    p.style.pointerEvents = show ? 'all' : 'none';
  });
  listItems.forEach(item => {
    const show = filter === 'all' || item.dataset.type === filter;
    item.classList.toggle('hidden-item', !show);
    if (show) vis++;
  });
  listCount.textContent = `(${vis})`;

  if (activeId !== null) {
    const pin = document.querySelector(`.venue-pin[data-id="${activeId}"]`);
    if (pin && pin.style.opacity === '0.1') clearSelection();
  }
}

/* ── Select ─────────────────────────────────────── */
function selectLocation(id) {
  activeId = id;
  const loc = locationData.find(l => l.id === id);
  if (!loc) return;

  pins.forEach(p => {
    const ring = p.querySelector('.pin-pulse');
    if (parseInt(p.dataset.id) === id) {
      ring.setAttribute('r', '26');
      ring.setAttribute('stroke-width', '2.5');
    } else {
      ring.setAttribute('r', '20');
      ring.setAttribute('stroke-width', '1.5');
    }
  });

  listItems.forEach(item => item.classList.toggle('active', parseInt(item.dataset.id) === id));
  const activeItem = document.querySelector(`.vmap-list-item[data-id="${id}"]`);
  if (activeItem) activeItem.scrollIntoView({ block:'nearest', behavior:'smooth' });

  // Fill info card
  document.getElementById('infoPlaceholder').style.display = 'none';
  document.getElementById('infoContent').style.display     = 'block';
  document.getElementById('infoIcon').textContent      = loc.icon;
  document.getElementById('infoName').textContent      = loc.name;
  document.getElementById('infoAreaText').textContent  = loc.area;
  document.getElementById('infoDesc').textContent      = loc.desc;

  const col  = categoryColors[loc.type] || '#888';
  const lbl  = categoryLabels[loc.type] || loc.type;
  const badge = document.getElementById('infoTypeBadge');
  badge.textContent    = lbl;
  badge.style.cssText  = `background:${col}30;color:${col};border:1px solid ${col};`;
}

function clearSelection() {
  activeId = null;
  pins.forEach(p => {
    const ring = p.querySelector('.pin-pulse');
    ring.setAttribute('r', '20');
    ring.setAttribute('stroke-width', '1.5');
  });
  listItems.forEach(i => i.classList.remove('active'));
  document.getElementById('infoPlaceholder').style.display = 'flex';
  document.getElementById('infoContent').style.display     = 'none';
}

/* ── Event bindings ─────────────────────────────── */
filterBtns.forEach(btn => btn.addEventListener('click', () => applyFilter(btn.dataset.filter)));

pins.forEach(pin => {
  pin.addEventListener('click', () => {
    const id = parseInt(pin.dataset.id);
    activeId === id ? clearSelection() : selectLocation(id);
  });
  pin.addEventListener('mouseenter', e => {
    tooltip.innerHTML = `<strong>${pin.dataset.name}</strong>${pin.dataset.area}`;
    tooltip.style.display = 'block';
  });
  pin.addEventListener('mousemove', e => {
    tooltip.style.left = (e.clientX + 14) + 'px';
    tooltip.style.top  = (e.clientY - 10) + 'px';
  });
  pin.addEventListener('mouseleave', () => { tooltip.style.display = 'none'; });
  pin.addEventListener('keydown', e => {
    if (e.key==='Enter'||e.key===' ') { e.preventDefault(); pin.click(); }
  });
});

listItems.forEach(item => {
  item.addEventListener('click', () => {
    const id   = parseInt(item.dataset.id);
    const type = item.dataset.type;
    if (activeFilter !== 'all' && activeFilter !== type) applyFilter('all');
    activeId === id ? clearSelection() : selectLocation(id);
  });
  item.addEventListener('keydown', e => {
    if (e.key==='Enter'||e.key===' ') { e.preventDefault(); item.click(); }
  });
});

// init count
applyFilter('all');
</script>

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
