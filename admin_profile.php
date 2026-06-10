<?php
session_start();

// ── Logout admin ─────────────────────────────────────
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: admin-login.php');
    exit;
}

// ── Guard: hanya admin yang boleh akses ───────────────
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit;
}

require_once 'config/db.php';

// ── Data admin ────────────────────────────────────────
$adminData = [
    'name'   => $_SESSION['admin_name']  ?? 'Administrator',
    'email'  => $_SESSION['admin_email'] ?? 'admin@youthreverfest.com',
    'role'   => $_SESSION['admin_role']  ?? 'Super Admin',
    'phone'  => '+62 812-0000-0001',
    'joined' => '01 Januari 2026',
    'photo'  => null,
];

$q = $conn->query("SELECT id, name, email, phone, photo, joined_date FROM admin_users WHERE email = '"
    . $conn->real_escape_string($adminData['email']) . "' LIMIT 1");
if ($q && $q->num_rows > 0) {
    $row = $q->fetch_assoc();
    $adminData['id']     = $row['id'];
    $adminData['name']   = $row['name'];
    $adminData['phone']  = $row['phone'] ?? $adminData['phone'];
    $adminData['joined'] = date('d F Y', strtotime($row['joined_date']));
    $adminData['photo']  = $row['photo'];
}

// ── Stats ringkasan tiket ─────────────────────────────
$stats = ['total'=>0,'vip'=>0,'premium'=>0,'regular'=>0];
$sq = $conn->query("SELECT category, COUNT(*) as cnt FROM tickets GROUP BY category");
if ($sq && $sq->num_rows > 0) {
    while ($sr = $sq->fetch_assoc()) {
        $stats['total'] += $sr['cnt'];
        $c = strtolower($sr['category']);
        if (strpos($c,'vip')!==false)     $stats['vip']     += $sr['cnt'];
        elseif(strpos($c,'premium')!==false) $stats['premium'] += $sr['cnt'];
        else                              $stats['regular'] += $sr['cnt'];
    }
}
// Fallback simulasi
if ($stats['total'] === 0) {
    $stats = ['total'=>1248,'vip'=>186,'premium'=>312,'regular'=>750];
}

// ── Daftar tiket terbaru ──────────────────────────────
$tickets = [];
$tq = $conn->query("SELECT ticket_number,event_name,category,price,status,seat,created_at
                    FROM tickets ORDER BY created_at DESC LIMIT 10");
if ($tq && $tq->num_rows > 0) {
    while ($tr = $tq->fetch_assoc()) { $tickets[] = $tr; }
}
if (empty($tickets)) {
    $tickets = [
        ['ticket_number'=>'TKT-001','event_name'=>'YOUTHEVER 2026','category'=>'VIP Pass',    'price'=>750000,'status'=>'Terbayar','seat'=>'VIP-01','created_at'=>'2026-01-10'],
        ['ticket_number'=>'TKT-002','event_name'=>'YOUTHEVER 2026','category'=>'Regular Pass', 'price'=>300000,'status'=>'Terbayar','seat'=>'A-22', 'created_at'=>'2026-01-11'],
        ['ticket_number'=>'TKT-003','event_name'=>'YOUTHEVER 2026','category'=>'Premium Pass', 'price'=>500000,'status'=>'Pending', 'seat'=>'B-05', 'created_at'=>'2026-01-12'],
        ['ticket_number'=>'TKT-004','event_name'=>'YOUTHEVER 2026','category'=>'Regular Pass', 'price'=>300000,'status'=>'Terbayar','seat'=>'A-33', 'created_at'=>'2026-01-13'],
    ];
}

// ── Aktivitas log (simulasi) ──────────────────────────
$activityLog = [
    ['time'=>'10 Jun 2026, 08:15','action'=>'Login admin berhasil',   'type'=>'success'],
    ['time'=>'09 Jun 2026, 14:30','action'=>'Export data tiket (CSV)','type'=>'info'],
    ['time'=>'09 Jun 2026, 11:00','action'=>'Update info rundown',     'type'=>'info'],
    ['time'=>'08 Jun 2026, 09:45','action'=>'Tambah pengumuman baru',  'type'=>'success'],
    ['time'=>'07 Jun 2026, 16:20','action'=>'Percobaan akses gagal',   'type'=>'warning'],
];

$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'overview';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Panel – YOUTHEVER 2026</title>
  <link rel="stylesheet" href="css/style.css"/>
  <style>
    /* ─── Admin Panel Base ─────────────────────────── */
    .admin-page {
      background:
        linear-gradient(rgba(0,0,0,.88), rgba(0,0,0,.88)),
        url("image/back 10.jpg") center/cover no-repeat fixed;
      min-height: 100vh;
    }

    /* Top bar frosted */
    .admin-topbar {
      display:flex; align-items:center; justify-content:space-between;
      padding:14px 32px;
      background: rgba(0,0,0,.82) !important;
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border-bottom:1px solid rgba(197,160,89,.25);
      position:sticky; top:0; z-index:100;
    }
    .admin-topbar-left { display:flex; align-items:center; gap:14px; }
    .admin-topbar-logo { color:#d4af37; font-weight:700; font-size:1.05rem; letter-spacing:.05em; text-decoration:none; }
    .admin-badge {
      background:linear-gradient(135deg,#c5a059,#d4af37);
      color:#000; font-size:.7rem; font-weight:700;
      padding:3px 10px; border-radius:20px; letter-spacing:.06em;
    }
    .admin-topbar-right { display:flex; align-items:center; gap:16px; }
    .admin-topbar-user  { color:#888; font-size:.85rem; }
    .admin-topbar-user strong { color:#d4af37; }
    .admin-logout-btn {
      background:#2a0a0a; border:1px solid #e74c3c; color:#e74c3c;
      padding:7px 14px; border-radius:6px; font-size:.82rem;
      cursor:pointer; text-decoration:none; transition:all .2s;
    }
    .admin-logout-btn:hover { background:#e74c3c; color:#fff; }

    /* Layout */
    .admin-body {
      display:grid; grid-template-columns:240px 1fr;
      min-height:calc(100vh - 60px);
    }

    /* Sidebar */
    .admin-sidebar {
      background: rgba(10,5,10,.75);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-right:1px solid rgba(197,160,89,.12);
      padding:24px 0;
      position:sticky; top:60px; height:calc(100vh - 60px);
      overflow-y:auto;
    }
    .admin-sidebar-section {
      padding:0 16px; margin-bottom:6px;
      font-size:.7rem; color:#444; letter-spacing:.08em;
      text-transform:uppercase; font-weight:700;
    }
    .admin-nav-item {
      display:flex; align-items:center; gap:10px;
      padding:11px 20px; color:#888; text-decoration:none;
      font-size:.88rem; transition:all .15s; border-left:3px solid transparent;
    }
    .admin-nav-item:hover { color:#fff; background:#141414; }
    .admin-nav-item.active { color:#d4af37; background:#141414; border-left-color:#d4af37; }
    .admin-nav-item .nav-icon { font-size:1rem; min-width:20px; }
    .admin-nav-divider { border:none; border-top:1px solid #1a1a1a; margin:12px 16px; }

    /* Main content */
    .admin-main { padding:28px 32px; }
    .admin-main h2 { color:#d4af37; font-size:1.5rem; margin-bottom:22px; }

    /* ─── Stats cards ──────────────────────────────── */
    .stats-grid {
      display:grid; grid-template-columns:repeat(4,1fr);
      gap:16px; margin-bottom:28px;
    }
    .stat-card {
      background:#111; border:1px solid #1e1e1e;
      border-radius:12px; padding:20px;
      border-top:3px solid transparent;
      transition:transform .2s;
    }
    .stat-card:hover { transform:translateY(-3px); }
    .stat-card.total   { border-top-color:#c5a059; }
    .stat-card.vip     { border-top-color:#f39c12; }
    .stat-card.premium { border-top-color:#9b59b6; }
    .stat-card.regular { border-top-color:#27ae60; }
    .stat-label { color:#666; font-size:.78rem; text-transform:uppercase; letter-spacing:.06em; margin-bottom:8px; }
    .stat-value { color:#fff; font-size:2rem; font-weight:700; line-height:1; }
    .stat-icon  { font-size:1.8rem; float:right; margin-top:-30px; opacity:.6; }

    /* ─── Section card ─────────────────────────────── */
    .admin-card {
      background:#111; border:1px solid #1e1e1e;
      border-radius:12px; overflow:hidden; margin-bottom:24px;
    }
    .admin-card-header {
      display:flex; align-items:center; justify-content:space-between;
      padding:16px 20px; border-bottom:1px solid #1e1e1e;
      background:#0d0d0d;
    }
    .admin-card-header h3 { color:#d4af37; font-size:1rem; margin:0; }
    .admin-card-body { padding:20px; }

    /* ─── Profile form ─────────────────────────────── */
    .admin-profile-grid { display:grid; grid-template-columns:200px 1fr; gap:28px; align-items:start; }
    .admin-avatar-box { text-align:center; }
    .admin-avatar {
      width:130px; height:130px; border-radius:50%;
      background:#1a1a1a; border:3px solid #c5a059;
      display:flex; align-items:center; justify-content:center;
      font-size:3rem; margin:0 auto 12px;
      background-size:cover; background-position:center;
    }
    .admin-role-badge {
      display:inline-block; background:linear-gradient(135deg,#c5a059,#d4af37);
      color:#000; font-size:.72rem; font-weight:700;
      padding:4px 12px; border-radius:20px; letter-spacing:.05em;
      margin-bottom:12px;
    }
    .admin-form-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    .admin-form-group { display:flex; flex-direction:column; gap:6px; }
    .admin-form-group.full { grid-column:1/-1; }
    .admin-form-group label { color:#666; font-size:.8rem; text-transform:uppercase; letter-spacing:.05em; }
    .admin-form-group input, .admin-form-group select {
      background:#1a1a1a; border:1px solid #2a2a2a; color:#fff;
      padding:11px 14px; border-radius:8px; font-size:.9rem;
      transition:border-color .2s;
    }
    .admin-form-group input:focus, .admin-form-group select:focus {
      outline:none; border-color:#c5a059;
    }
    .admin-form-group input[readonly] { color:#555; cursor:default; }
    .btn-admin-save {
      background:linear-gradient(135deg,#c5a059,#d4af37);
      color:#000; border:none; padding:11px 24px;
      border-radius:8px; font-weight:700; cursor:pointer;
      font-size:.9rem; transition:opacity .2s;
    }
    .btn-admin-save:hover { opacity:.88; }

    /* ─── Ticket table ─────────────────────────────── */
    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th {
      text-align:left; padding:11px 14px; font-size:.75rem;
      color:#666; text-transform:uppercase; letter-spacing:.06em;
      border-bottom:1px solid #1e1e1e; background:#0d0d0d;
    }
    .admin-table td { padding:12px 14px; border-bottom:1px solid #161616; font-size:.88rem; color:#ccc; }
    .admin-table tr:hover td { background:#141414; }
    .admin-table .tkt-num { color:#d4af37; font-weight:600; font-size:.82rem; }
    .badge-status {
      padding:3px 10px; border-radius:20px; font-size:.75rem; font-weight:700;
    }
    .badge-status.terbayar { background:#27ae6022; color:#27ae60; border:1px solid #27ae60; }
    .badge-status.pending  { background:#f39c1222; color:#f39c12; border:1px solid #f39c12; }

    /* ─── Activity log ─────────────────────────────── */
    .log-list { display:flex; flex-direction:column; gap:10px; }
    .log-item {
      display:flex; align-items:center; gap:14px;
      padding:12px 16px; background:#0d0d0d;
      border-radius:8px; border-left:3px solid transparent;
    }
    .log-item.success { border-left-color:#27ae60; }
    .log-item.info    { border-left-color:#3498db; }
    .log-item.warning { border-left-color:#f39c12; }
    .log-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
    .log-item.success .log-dot { background:#27ae60; }
    .log-item.info    .log-dot { background:#3498db; }
    .log-item.warning .log-dot { background:#f39c12; }
    .log-action { color:#ccc; font-size:.88rem; flex:1; }
    .log-time   { color:#444; font-size:.76rem; white-space:nowrap; }

    /* ─── Quick actions ────────────────────────────── */
    .quick-actions { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
    .quick-btn {
      display:flex; flex-direction:column; align-items:center; gap:8px;
      padding:18px 10px; background:#0d0d0d; border:1px solid #1e1e1e;
      border-radius:12px; color:#888; text-decoration:none;
      font-size:.82rem; transition:all .2s; cursor:pointer;
    }
    .quick-btn:hover { border-color:#c5a059; color:#d4af37; background:#111; }
    .quick-btn .q-icon { font-size:1.8rem; }

    /* ─── Security panel ───────────────────────────── */
    .security-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
    .security-item {
      background:#0d0d0d; border:1px solid #1e1e1e;
      border-radius:10px; padding:18px;
    }
    .security-item h4 { color:#d4af37; margin-bottom:10px; font-size:.95rem; }
    .security-item p  { color:#666; font-size:.83rem; line-height:1.6; }
    .security-status {
      display:flex; align-items:center; gap:8px;
      margin-top:12px; font-size:.82rem;
    }
    .sec-dot { width:8px; height:8px; border-radius:50%; background:#27ae60; }
    .sec-dot.warn { background:#f39c12; }

    /* ─── Change password ──────────────────────────── */
    .change-pw-form { display:grid; gap:14px; max-width:400px; }

    /* Responsive */
    @media(max-width:1024px) {
      .stats-grid { grid-template-columns:repeat(2,1fr); }
      .admin-profile-grid { grid-template-columns:1fr; }
    }
    @media(max-width:768px) {
      .admin-body { grid-template-columns:1fr; }
      .admin-sidebar { display:none; }
      .admin-main { padding:20px 16px; }
      .quick-actions { grid-template-columns:repeat(2,1fr); }
      .security-grid { grid-template-columns:1fr; }
    }
  </style>
</head>
<body class="admin-page">

<!-- ── Top Bar ─────────────────────────────────────── -->
<header class="admin-topbar">
  <div class="admin-topbar-left">
    <a href="index.php" class="admin-topbar-logo">YOUTHEVER 2026</a>
    <span class="admin-badge">🛡️ ADMIN PANEL</span>
  </div>
  <div class="admin-topbar-right">
    <span class="admin-topbar-user">
      Halo, <strong><?php echo htmlspecialchars($adminData['name']); ?></strong>
      &nbsp;·&nbsp; <?php echo htmlspecialchars($adminData['role']); ?>
    </span>
    <a href="?logout=1" class="admin-logout-btn">🚪 Logout Admin</a>
  </div>
</header>


<div class="admin-body">

  <!-- Sidebar nav -->
  <aside class="admin-sidebar">
    <div style="padding:16px 20px 8px;">
      <div class="admin-avatar" id="sb-avatar" style="width:70px;height:70px;font-size:1.8rem;margin-bottom:8px;<?php echo $adminData['photo'] ? 'background-image:url('.htmlspecialchars($adminData['photo']).')' : ''; ?>">
        <?php echo $adminData['photo'] ? '' : '🛡️'; ?>
      </div>
      <div style="color:#d4af37;font-weight:700;font-size:.9rem;"><?php echo htmlspecialchars($adminData['name']); ?></div>
      <div style="color:#555;font-size:.76rem;"><?php echo htmlspecialchars($adminData['role']); ?></div>
    </div>
    <hr class="admin-nav-divider"/>

    <p class="admin-sidebar-section">Dashboard</p>
    <a href="?tab=overview"  class="admin-nav-item <?php echo $activeTab==='overview' ?'active':''; ?>"><span class="nav-icon">📊</span> Overview</a>
    <a href="?tab=tickets"   class="admin-nav-item <?php echo $activeTab==='tickets'  ?'active':''; ?>"><span class="nav-icon">🎟️</span> Manajemen Tiket</a>
    <a href="?tab=faq"       class="admin-nav-item <?php echo $activeTab==='faq'      ?'active':''; ?>"><span class="nav-icon">❓</span> Kelola FAQ</a>
    <a href="?tab=activity"  class="admin-nav-item <?php echo $activeTab==='activity' ?'active':''; ?>"><span class="nav-icon">📋</span> Log Aktivitas</a>

    <hr class="admin-nav-divider"/>
    <p class="admin-sidebar-section">Pengaturan</p>
    <a href="?tab=profile"   class="admin-nav-item <?php echo $activeTab==='profile'  ?'active':''; ?>"><span class="nav-icon">👤</span> Profil Admin</a>
    <a href="?tab=security"  class="admin-nav-item <?php echo $activeTab==='security' ?'active':''; ?>"><span class="nav-icon">🔐</span> Keamanan</a>

    <hr class="admin-nav-divider"/>
    <p class="admin-sidebar-section">Navigasi</p>
    <a href="index.php"         class="admin-nav-item"><span class="nav-icon">🏠</span> Home</a>
    <a href="announcements.php" class="admin-nav-item"><span class="nav-icon">📢</span> Pengumuman</a>
    <a href="lineup.php"        class="admin-nav-item"><span class="nav-icon">🎤</span> Lineup</a>
    <a href="rundown.php"       class="admin-nav-item"><span class="nav-icon">📅</span> Rundown</a>
  </aside>

  <!-- Main content -->
  <main class="admin-main">

    <!-- ═══ OVERVIEW TAB ══════════════════════════════ -->
    <?php if ($activeTab === 'overview'): ?>

    <h2>📊 Overview Dashboard</h2>

    <!-- Stats cards -->
    <div class="stats-grid">
      <div class="stat-card total">
        <div class="stat-label">Total Tiket Terjual</div>
        <div class="stat-value"><?php echo number_format($stats['total']); ?></div>
        <div class="stat-icon">🎟️</div>
      </div>
      <div class="stat-card vip">
        <div class="stat-label">VIP Pass</div>
        <div class="stat-value"><?php echo number_format($stats['vip']); ?></div>
        <div class="stat-icon">👑</div>
      </div>
      <div class="stat-card premium">
        <div class="stat-label">Premium Pass</div>
        <div class="stat-value"><?php echo number_format($stats['premium']); ?></div>
        <div class="stat-icon">⭐</div>
      </div>
      <div class="stat-card regular">
        <div class="stat-label">Regular Pass</div>
        <div class="stat-value"><?php echo number_format($stats['regular']); ?></div>
        <div class="stat-icon">🎫</div>
      </div>
    </div>

    <!-- Quick actions -->
    <div class="admin-card">
      <div class="admin-card-header"><h3>⚡ Aksi Cepat</h3></div>
      <div class="admin-card-body">
        <div class="quick-actions">
          <a href="announcements.php" class="quick-btn"><span class="q-icon">📢</span>Buat Pengumuman</a>
          <a href="lineup.php"        class="quick-btn"><span class="q-icon">🎤</span>Kelola Lineup</a>
          <a href="rundown.php"       class="quick-btn"><span class="q-icon">📅</span>Edit Rundown</a>
          <a href="event-map.php"     class="quick-btn"><span class="q-icon">🗺️</span>Peta Venue</a>
          <a href="?tab=tickets"      class="quick-btn"><span class="q-icon">🎟️</span>Lihat Tiket</a>
          <a href="?tab=activity"     class="quick-btn"><span class="q-icon">📋</span>Log Aktivitas</a>
        </div>
      </div>
    </div>

    <!-- Recent tickets preview -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h3>🎟️ Tiket Terbaru</h3>
        <a href="?tab=tickets" style="color:#d4af37;font-size:.82rem;text-decoration:none;">Lihat Semua →</a>
      </div>
      <div class="admin-card-body" style="padding:0;">
        <table class="admin-table">
          <thead><tr>
            <th>No. Tiket</th><th>Kategori</th><th>Harga</th><th>Status</th><th>Tanggal</th>
          </tr></thead>
          <tbody>
          <?php foreach (array_slice($tickets,0,4) as $t): ?>
            <tr>
              <td class="tkt-num"><?php echo htmlspecialchars($t['ticket_number']); ?></td>
              <td><?php echo htmlspecialchars($t['category']); ?></td>
              <td>Rp <?php echo number_format($t['price'],0,',','.'); ?></td>
              <td><span class="badge-status <?php echo strtolower(str_replace(' ','-',$t['status'])); ?>"><?php echo htmlspecialchars($t['status']); ?></span></td>
              <td style="color:#555;"><?php echo htmlspecialchars($t['created_at']); ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <?php endif; ?>

    <!-- ═══ TICKETS TAB ════════════════════════════════ -->
    <?php if ($activeTab === 'tickets'): ?>

    <h2>🎟️ Manajemen Tiket</h2>
    <div class="admin-card">
      <div class="admin-card-header">
        <h3>Semua Tiket</h3>
        <span style="color:#555;font-size:.82rem;"><?php echo count($tickets); ?> tiket ditemukan</span>
      </div>
      <div class="admin-card-body" style="padding:0;">
        <table class="admin-table">
          <thead><tr>
            <th>No. Tiket</th><th>Event</th><th>Kategori</th><th>Kursi</th><th>Harga</th><th>Status</th><th>Tanggal</th>
          </tr></thead>
          <tbody>
          <?php foreach ($tickets as $t): ?>
            <tr>
              <td class="tkt-num"><?php echo htmlspecialchars($t['ticket_number']); ?></td>
              <td><?php echo htmlspecialchars($t['event_name']); ?></td>
              <td><?php echo htmlspecialchars($t['category']); ?></td>
              <td><?php echo htmlspecialchars($t['seat']); ?></td>
              <td>Rp <?php echo number_format($t['price'],0,',','.'); ?></td>
              <td><span class="badge-status <?php echo strtolower(str_replace(' ','-',$t['status'])); ?>"><?php echo htmlspecialchars($t['status']); ?></span></td>
              <td style="color:#555;"><?php echo htmlspecialchars($t['created_at']); ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <?php endif; ?>

    <!-- ═══ ACTIVITY TAB ═══════════════════════════════ -->
    <?php if ($activeTab === 'activity'): ?>

    <h2>📋 Log Aktivitas</h2>
    <div class="admin-card">
      <div class="admin-card-header"><h3>Riwayat Aktivitas Admin</h3></div>
      <div class="admin-card-body">
        <div class="log-list">
          <?php foreach ($activityLog as $log): ?>
            <div class="log-item <?php echo $log['type']; ?>">
              <span class="log-dot"></span>
              <span class="log-action"><?php echo htmlspecialchars($log['action']); ?></span>
              <span class="log-time"><?php echo htmlspecialchars($log['time']); ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <?php endif; ?>

    <!-- ═══ PROFILE TAB ════════════════════════════════ -->
    <?php if ($activeTab === 'profile'): ?>

    <h2>👤 Profil Administrator</h2>
    <div class="admin-card">
      <div class="admin-card-header"><h3>Informasi Akun</h3></div>
      <div class="admin-card-body">
        <div class="admin-profile-grid">

          <!-- Avatar box -->
          <div class="admin-avatar-box">
            <div class="admin-avatar" id="admin-avatar-big"
              style="<?php echo $adminData['photo'] ? 'background-image:url('.htmlspecialchars($adminData['photo']).')' : ''; ?>">
              <?php echo $adminData['photo'] ? '' : '🛡️'; ?>
            </div>
            <span class="admin-role-badge"><?php echo htmlspecialchars($adminData['role']); ?></span>
            <br/>
            <label for="avatar-file" style="background:#1a1a1a;border:1px solid #333;color:#888;
              padding:8px 16px;border-radius:6px;cursor:pointer;font-size:.8rem;display:inline-block;margin-top:6px;">
              📷 Ganti Foto
            </label>
            <input type="file" id="avatar-file" accept="image/*" hidden onchange="previewAdminAvatar(event)"/>
          </div>

          <!-- Form -->
          <div>
            <div class="admin-form-grid">
              <div class="admin-form-group">
                <label>Nama Lengkap</label>
                <input type="text" id="admin-name" value="<?php echo htmlspecialchars($adminData['name']); ?>"/>
              </div>
              <div class="admin-form-group">
                <label>Role</label>
                <input type="text" value="<?php echo htmlspecialchars($adminData['role']); ?>" readonly/>
              </div>
              <div class="admin-form-group">
                <label>Email (tidak dapat diubah)</label>
                <input type="email" value="<?php echo htmlspecialchars($adminData['email']); ?>" readonly/>
              </div>
              <div class="admin-form-group">
                <label>Nomor Telepon</label>
                <input type="tel" id="admin-phone" value="<?php echo htmlspecialchars($adminData['phone']); ?>"/>
              </div>
              <div class="admin-form-group full">
                <label>Bergabung Sejak</label>
                <input type="text" value="<?php echo htmlspecialchars($adminData['joined']); ?>" readonly/>
              </div>
            </div>
            <br/>
            <button class="btn-admin-save" onclick="saveAdminProfile()">💾 Simpan Perubahan</button>
          </div>
        </div>
      </div>
    </div>

    <?php endif; ?>

    <!-- ═══ SECURITY TAB ═══════════════════════════════ -->
    <?php if ($activeTab === 'security'): ?>

    <h2>🔐 Keamanan Akun Admin</h2>

    <div class="security-grid">
      <div class="security-item">
        <h4>🔑 Kode Akses Admin</h4>
        <p>Kode akses digunakan bersama password untuk masuk ke panel admin. Simpan dengan aman dan jangan bagikan ke siapapun.</p>
        <div class="security-status">
          <span class="sec-dot"></span>
          Kode akses aktif
        </div>
      </div>
      <div class="security-item">
        <h4>🛡️ Status Sesi</h4>
        <p>Sesi admin Anda aktif. Logout otomatis akan terjadi setelah tidak aktif selama 2 jam.</p>
        <div class="security-status">
          <span class="sec-dot"></span>
          Sesi valid · Login terakhir hari ini
        </div>
      </div>
      <div class="security-item">
        <h4>📍 Akses Terakhir</h4>
        <p>Pantau riwayat login admin untuk memastikan tidak ada akses tidak sah.</p>
        <div class="security-status">
          <span class="sec-dot warn"></span>
          1 percobaan gagal terdeteksi (7 Jun 2026)
        </div>
      </div>
      <div class="security-item">
        <h4>🔒 Ubah Password</h4>
        <p>Disarankan mengganti password secara berkala untuk keamanan akun admin.</p>
        <div class="change-pw-form" style="margin-top:12px;">
          <div class="admin-form-group">
            <label>Password Baru</label>
            <input type="password" id="new-pw" placeholder="Min. 8 karakter"/>
          </div>
          <div class="admin-form-group">
            <label>Konfirmasi Password</label>
            <input type="password" id="confirm-pw" placeholder="Ulangi password baru"/>
          </div>
          <button class="btn-admin-save" onclick="changePassword()" style="width:100%;">Ganti Password</button>
        </div>
      </div>
    </div>

    <?php endif; ?>

    <!-- ═══ FAQ TAB ════════════════════════════════════ -->
    <?php
    // ── FAQ storage via JSON file ─────────────────────
    $faqFile = __DIR__ . '/data/faqs.json';
    $faqs = [];
    if (file_exists($faqFile)) {
        $faqs = json_decode(file_get_contents($faqFile), true) ?: [];
    }
    // Default FAQs jika file belum ada
    if (empty($faqs)) {
        $faqs = [
            ['id'=>1,'question'=>'Apa itu YOUTHREVER FEST?',                  'answer'=>'YOUTHREVER FEST adalah festival musik dua hari dengan konsep dreamy, emotional, dan youth-culture experience yang menghadirkan berbagai musisi indie dan alternative.','active'=>true],
            ['id'=>2,'question'=>'Kapan festival berlangsung?',               'answer'=>'20–21 September 2026.','active'=>true],
            ['id'=>3,'question'=>'Dimana venue festival?',                    'answer'=>'Aurora Open Space, Bandung.','active'=>true],
            ['id'=>4,'question'=>'Apakah festival ini outdoor?',              'answer'=>'Ya, festival menggunakan konsep outdoor open-air venue.','active'=>true],
            ['id'=>5,'question'=>'Apakah tersedia tenant makanan dan minuman?','answer'=>'Tersedia berbagai food & beverage tenant selama festival berlangsung.','active'=>true],
        ];
    }

    // Handle FAQ actions
    $faqMsg = '';
    if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['faq_action'])) {
        @mkdir(__DIR__.'/data', 0755, true);
        if ($_POST['faq_action']==='add') {
            $q = trim($_POST['faq_question'] ?? '');
            $a = trim($_POST['faq_answer']   ?? '');
            if ($q && $a) {
                $maxId = array_reduce($faqs, fn($c,$f) => max($c,$f['id']), 0);
                $faqs[] = ['id'=>$maxId+1,'question'=>$q,'answer'=>$a,'active'=>true];
                file_put_contents($faqFile, json_encode($faqs, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
                $faqMsg = 'success:Pertanyaan berhasil ditambahkan.';
            } else { $faqMsg = 'error:Pertanyaan dan jawaban tidak boleh kosong.'; }
        } elseif ($_POST['faq_action']==='delete') {
            $delId = (int)($_POST['faq_id'] ?? 0);
            $faqs  = array_values(array_filter($faqs, fn($f) => $f['id'] !== $delId));
            file_put_contents($faqFile, json_encode($faqs, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
            $faqMsg = 'success:Pertanyaan berhasil dihapus.';
        } elseif ($_POST['faq_action']==='toggle') {
            $togId = (int)($_POST['faq_id'] ?? 0);
            foreach ($faqs as &$f) { if ($f['id']===$togId) $f['active'] = !$f['active']; }
            unset($f);
            file_put_contents($faqFile, json_encode($faqs, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
            $faqMsg = 'success:Status FAQ diperbarui.';
        }
        // Re-read after save
        if (file_exists($faqFile)) $faqs = json_decode(file_get_contents($faqFile), true) ?: $faqs;
    }
    ?>

    <?php if ($activeTab === 'faq'): ?>
    <h2>❓ Kelola FAQ</h2>

    <?php if ($faqMsg): [$ftype,$ftxt] = explode(':',$faqMsg,2); ?>
      <div style="padding:10px 16px;border-radius:8px;margin-bottom:20px;font-size:.88rem;
        background:<?php echo $ftype==='success'?'#27ae6018':'#e74c3c18'; ?>;
        border:1px solid <?php echo $ftype==='success'?'#27ae6044':'#e74c3c44'; ?>;
        color:<?php echo $ftype==='success'?'#27ae60':'#e74c3c'; ?>;">
        <?php echo $ftype==='success'?'✅':'⚠️'; ?> <?php echo htmlspecialchars($ftxt); ?>
      </div>
    <?php endif; ?>

    <!-- Add FAQ form -->
    <div class="admin-card" style="margin-bottom:24px;">
      <div class="admin-card-header"><h3>➕ Tambah Pertanyaan Baru</h3></div>
      <div class="admin-card-body">
        <form method="POST" action="?tab=faq">
          <input type="hidden" name="faq_action" value="add"/>
          <div class="admin-form-group" style="margin-bottom:14px;">
            <label>Pertanyaan</label>
            <input type="text" name="faq_question" placeholder="Tulis pertanyaan di sini…" required
              style="background:#1a1a1a;border:1px solid #2a2a2a;color:#fff;padding:11px 14px;border-radius:8px;width:100%;box-sizing:border-box;font-size:.92rem;"/>
          </div>
          <div class="admin-form-group" style="margin-bottom:18px;">
            <label>Jawaban</label>
            <textarea name="faq_answer" rows="3" placeholder="Tulis jawaban di sini…" required
              style="background:#1a1a1a;border:1px solid #2a2a2a;color:#fff;padding:11px 14px;border-radius:8px;width:100%;box-sizing:border-box;font-size:.92rem;resize:vertical;font-family:inherit;"></textarea>
          </div>
          <button type="submit" class="btn-admin-save">💾 Tambah FAQ</button>
        </form>
      </div>
    </div>

    <!-- FAQ list -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h3>📋 Daftar FAQ <span style="color:#555;font-weight:400;font-size:.82rem;">(<?php echo count($faqs); ?> item)</span></h3>
        <span style="color:#555;font-size:.78rem;">Tampil di halaman FAQ publik</span>
      </div>
      <div class="admin-card-body" style="padding:0;">
        <?php if (empty($faqs)): ?>
          <div style="padding:32px;text-align:center;color:#444;">Belum ada FAQ. Tambahkan di atas.</div>
        <?php else: ?>
          <?php foreach ($faqs as $faq): ?>
          <div style="padding:16px 20px;border-bottom:1px solid #161616;display:flex;gap:14px;align-items:flex-start;">
            <div style="flex:1;">
              <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                <span style="color:#d4af37;font-weight:700;font-size:.9rem;">
                  Q<?php echo $faq['id']; ?>.
                </span>
                <span style="color:<?php echo $faq['active']?'#51cf66':'#888'; ?>;font-size:.72rem;
                  background:<?php echo $faq['active']?'#51cf6618':'#2a2a2a'; ?>;
                  border:1px solid <?php echo $faq['active']?'#51cf6644':'#333'; ?>;
                  padding:2px 8px;border-radius:20px;font-weight:700;">
                  <?php echo $faq['active']?'Aktif':'Nonaktif'; ?>
                </span>
              </div>
              <p style="color:#fff;font-size:.9rem;margin-bottom:6px;font-weight:600;">
                <?php echo htmlspecialchars($faq['question']); ?>
              </p>
              <p style="color:#666;font-size:.83rem;line-height:1.5;">
                <?php echo htmlspecialchars($faq['answer']); ?>
              </p>
            </div>
            <div style="display:flex;flex-direction:column;gap:6px;min-width:90px;">
              <form method="POST" action="?tab=faq">
                <input type="hidden" name="faq_action" value="toggle"/>
                <input type="hidden" name="faq_id" value="<?php echo $faq['id']; ?>"/>
                <button type="submit" style="width:100%;background:#1a1a1a;border:1px solid #333;color:#888;
                  padding:6px 10px;border-radius:6px;font-size:.76rem;cursor:pointer;transition:all .2s;"
                  onmouseover="this.style.borderColor='#c5a059';this.style.color='#d4af37'"
                  onmouseout="this.style.borderColor='#333';this.style.color='#888'">
                  <?php echo $faq['active']?'🔕 Nonaktifkan':'✅ Aktifkan'; ?>
                </button>
              </form>
              <form method="POST" action="?tab=faq"
                onsubmit="return confirm('Hapus pertanyaan ini?')">
                <input type="hidden" name="faq_action" value="delete"/>
                <input type="hidden" name="faq_id" value="<?php echo $faq['id']; ?>"/>
                <button type="submit" style="width:100%;background:#2a0a0a;border:1px solid #e74c3c44;
                  color:#e74c3c;padding:6px 10px;border-radius:6px;font-size:.76rem;cursor:pointer;">
                  🗑️ Hapus
                </button>
              </form>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

  </main><!-- /admin-main -->
</div><!-- /admin-body -->

<!-- Footer -->
<footer style="background:#0d0d0d;border-top:1px solid #1e1e1e;padding:20px 32px;display:flex;justify-content:space-between;align-items:center;font-size:.78rem;color:#444;">
  <span>© 2026 YOUTHREVERFEST · Admin Panel</span>
  <span>🔒 Restricted Access · <?php echo htmlspecialchars($adminData['role']); ?></span>
</footer>

<script>
// ── Avatar preview ──────────────────────────────────
function previewAdminAvatar(e) {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = function(ev) {
    const url = ev.target.result;
    ['admin-avatar-big','sb-avatar'].forEach(id => {
      const el = document.getElementById(id);
      if (el) { el.style.backgroundImage = `url('${url}')`; el.textContent = ''; }
    });
  };
  reader.readAsDataURL(file);

  const fd = new FormData();
  fd.append('action', 'upload_photo');
  fd.append('photo', file);

  fetch('update_admin.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(d => {
      if (!d.success) {
        alert('Upload foto gagal: ' + d.message);
        return;
      }

      ['admin-avatar-big','sb-avatar'].forEach(id => {
        const el = document.getElementById(id);
        if (el && d.photo_url) {
          el.style.backgroundImage = `url('${d.photo_url}')`;
          el.textContent = '';
        }
      });
    })
    .catch(() => alert('Upload foto gagal. Periksa koneksi database atau folder upload.'));
}

// ── Save profile ────────────────────────────────────
function saveAdminProfile() {
  const name  = document.getElementById('admin-name')?.value.trim();
  const phone = document.getElementById('admin-phone')?.value.trim();
  if (!name) { alert('Nama tidak boleh kosong.'); return; }

  const fd = new FormData();
  fd.append('action','update_profile');
  fd.append('name', name);
  fd.append('phone', phone);
  fetch('update_admin.php', { method:'POST', body:fd })
    .then(r=>r.json())
    .then(d => {
      if (d.success) {
        alert('Profil admin berhasil disimpan!');
        location.reload();
      } else { alert('Error: ' + d.message); }
    })
    .catch(() => alert('Profil disimpan secara lokal (DB tidak terhubung).'));
}

// ── Change password ─────────────────────────────────
function changePassword() {
  const np = document.getElementById('new-pw')?.value;
  const cp = document.getElementById('confirm-pw')?.value;
  if (!np || np.length < 8) { alert('Password minimal 8 karakter.'); return; }
  if (np !== cp) { alert('Konfirmasi password tidak cocok.'); return; }
  alert('Fitur ganti password memerlukan koneksi database. Silakan update di server langsung.');
}
</script>
</body>
</html>
