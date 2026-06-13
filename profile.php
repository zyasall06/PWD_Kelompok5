<?php
session_start();
require_once 'config/db.php';

$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// Jika belum login, tampilkan halaman "harus login" bukan redirect paksa
if (!$loggedIn):
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard – YOUTHEVER 2026</title>
  <link rel="stylesheet" href="css/style.css"/>
  <style>
    body {
      background:
        linear-gradient(rgba(0,0,0,.75),rgba(0,0,0,.75)),
        url("image/back 10.jpg") center/cover no-repeat fixed;
      min-height:100vh; display:flex; flex-direction:column;
    }
    nav { position:fixed; top:0; left:0; right:0; z-index:100;
      background:rgba(0,0,0,.82); backdrop-filter:blur(14px); border-bottom:1px solid rgba(197,160,89,.22); }
    .login-gate {
      flex:1; display:flex; align-items:center; justify-content:center;
      padding:100px 24px 60px; text-align:center;
    }
    .login-gate-card {
      background:rgba(15,5,15,.82); border:1px solid rgba(197,160,89,.2);
      backdrop-filter:blur(12px); border-radius:20px;
      padding:52px 44px; max-width:440px; width:100%;
    }
    .gate-icon { font-size:3.5rem; display:block; margin-bottom:18px; }
    .gate-title { color:#d4af37; font-size:1.6rem; font-weight:800; margin-bottom:10px; }
    .gate-sub   { color:#666; font-size:.92rem; line-height:1.6; margin-bottom:32px; }
    .gate-btns  { display:flex; flex-direction:column; gap:12px; }
    .btn-gate-login {
      display:block; background:linear-gradient(135deg,#c5a059,#d4af37);
      color:#000; padding:13px 24px; border-radius:10px;
      font-weight:700; font-size:.95rem; text-decoration:none;
      letter-spacing:.04em; transition:opacity .2s;
    }
    .btn-gate-login:hover { opacity:.88; }
    .btn-gate-register {
      display:block; background:transparent; border:1.5px solid #5d3f5d;
      color:#c39bd3; padding:12px 24px; border-radius:10px;
      font-weight:700; font-size:.95rem; text-decoration:none;
      letter-spacing:.04em; transition:all .2s;
    }
    .btn-gate-register:hover { background:#5d3f5d; color:#fff; }
    .gate-back  { display:block; margin-top:20px; color:#444; font-size:.82rem; text-decoration:none; }
    .gate-back:hover { color:#888; }
  </style>
</head>
<body>
<nav>
  <div class="nav-left"><a href="index.php" class="logo">YOUTHEVER 2026</a></div>
  <button class="nav-toggle">☰</button>
  <div class="nav-center">
    <a href="index.php">Home</a><a href="about.php">About Us</a>
    <a href="lineup.php">Line Up</a><a href="event-map.php">Venue</a>
    <a href="rundown.php">Rundown</a><a href="announcements.php">Berita</a>
    <a href="faq.php">FAQ</a>
  </div>
  <div class="nav-right">
    <a href="login.php">Login</a>
    <a href="register.php" class="buy-btn">Register</a>
  </div>
</nav>

<div class="login-gate">
  <div class="login-gate-card">
    <span class="gate-icon">🔒</span>
    <h1 class="gate-title">Akses Dashboard</h1>
    <p class="gate-sub">
      Anda perlu masuk ke akun untuk melihat dashboard, tiket, dan jadwal festival Anda.
    </p>
    <div class="gate-btns">
      <a href="login.php?redirect=profile.php" class="btn-gate-login">🔑 Sign In ke Akun Saya</a>
      <a href="register.php" class="btn-gate-register">✨ Buat Akun Baru</a>
    </div>
    <a href="index.php" class="gate-back">← Kembali ke Home</a>
  </div>
</div>

<script>
(function(){
  var btn=document.querySelector('.nav-toggle');
  var nav=document.querySelector('nav');
  if(!btn||!nav) return;
  btn.addEventListener('click',function(){ nav.classList.toggle('nav-open'); });
})();
</script>
</body>
</html>
<?php
exit;
endif;

// Ambil data admin dari database
$userData = [
    'name' => 'John Doe',
    'email' => 'shizlafasia@gmail.com',
    'phone' => '+62 812-3456-7890',
    'joined' => '15 Januari 2026',
    'photo' => null
];

$query = "SELECT id, name, email, phone, photo, joined_date FROM admin_users WHERE id = 1 LIMIT 1";
$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userData = [
        'id' => $row['id'],
        'name' => $row['name'],
        'email' => $row['email'],
        'phone' => $row['phone'] ?? '+62 812-3456-7890',
        'joined' => date('d F Y', strtotime($row['joined_date'])),
        'photo' => $row['photo']
    ];
}

// Ambil data tiket dari database
$tickets = [];
$ticket_query = "SELECT ticket_number, event_name, event_date_start, event_date_end, category, price, status, seat, qr_code 
                 FROM tickets WHERE user_id = 1";
$ticket_result = $conn->query($ticket_query);
if ($ticket_result && $ticket_result->num_rows > 0) {
    while ($ticket = $ticket_result->fetch_assoc()) {
        $tickets[] = [
            'id' => $ticket['ticket_number'],
            'event' => $ticket['event_name'],
            'date' => date('d F Y', strtotime($ticket['event_date_start'])) . ' - ' . date('d F Y', strtotime($ticket['event_date_end'])),
            'category' => $ticket['category'],
            'price' => 'Rp ' . number_format($ticket['price'], 0, ',', '.'),
            'status' => $ticket['status'],
            'seat' => $ticket['seat'],
            'qr' => $ticket['qr_code']
        ];
    }
}

// Jika tidak ada tiket dari database, gunakan data simulasi
if (empty($tickets)) {
    $tickets = [
        [
            'id' => 'TKT001',
            'event' => 'YOUTHEVER 2026',
            'date' => '24 - 26 Oktober 2024',
            'category' => 'Regular Pass',
            'price' => 'Rp 300.000',
            'status' => 'Terbayar',
            'seat' => 'A12',
            'qr' => '█████████████'
        ],
        [
            'id' => 'TKT002',
            'event' => 'YOUTHEVER 2026',
            'date' => '24 - 26 Oktober 2024',
            'category' => 'VIP Pass',
            'price' => 'Rp 750.000',
            'status' => 'Terbayar',
            'seat' => 'VIP-05',
            'qr' => '█████████████'
        ]
    ];
}

$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'profile';
$allowedTabs = ['profile', 'tickets', 'schedule', 'settings'];
if (!in_array($activeTab, $allowedTabs, true)) {
    $activeTab = 'profile';
}
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - YOUTHEVER 2026</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
      /* ── Admin Button ───────────────────────────── */
      .btn-admin-access {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        margin-top: 12px;
        padding: 11px 16px;
        background: transparent;
        border: 1px solid #c5a059;
        border-radius: 6px;
        color: #c5a059;
        font-size: .82rem;
        font-weight: 600;
        cursor: pointer;
        letter-spacing: .04em;
        transition: all .2s;
        text-align: center;
        text-decoration: none;
      }
      .btn-admin-access:hover {
        background: rgba(197,160,89,.12);
        color: #d4af37;
      }

      /* ── Modal overlay ──────────────────────────── */
      .admin-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.75);
        z-index: 9000;
        align-items: center;
        justify-content: center;
        padding: 20px;
      }
      .admin-modal-overlay.open { display: flex; }

      .admin-modal {
        background: #111;
        border: 2px solid #c5a059;
        border-radius: 16px;
        padding: 36px 32px;
        width: 100%;
        max-width: 380px;
        position: relative;
      }
      .admin-modal .modal-close {
        position: absolute;
        top: 14px; right: 16px;
        background: none; border: none;
        color: #555; font-size: 1.2rem;
        cursor: pointer; line-height: 1;
      }
      .admin-modal .modal-close:hover { color: #fff; }

      .admin-modal .m-icon  { font-size: 2.4rem; text-align: center; display: block; margin-bottom: 10px; }
      .admin-modal h3 { color: #d4af37; text-align: center; margin-bottom: 6px; font-size: 1.2rem; }
      .admin-modal p  { color: #666; text-align: center; font-size: .83rem; margin-bottom: 24px; line-height: 1.5; }

      .admin-modal .m-field { margin-bottom: 16px; }
      .admin-modal .m-field label {
        display: block; color: #888; font-size: .78rem;
        text-transform: uppercase; letter-spacing: .06em; margin-bottom: 6px;
      }
      .admin-modal .m-field-inner { position: relative; }
      .admin-modal .m-field input {
        width: 100%; padding: 11px 42px 11px 14px;
        background: #1a1a1a; border: 1px solid #2a2a2a;
        border-radius: 8px; color: #fff; font-size: .92rem;
        box-sizing: border-box; transition: border-color .2s;
      }
      .admin-modal .m-field input:focus { outline: none; border-color: #c5a059; }
      .toggle-vis {
        position: absolute; right: 12px; top: 50%;
        transform: translateY(-50%);
        background: none; border: none;
        color: #555; cursor: pointer; font-size: .9rem; padding: 0;
      }
      .toggle-vis:hover { color: #d4af37; }

      .modal-error {
        background: rgba(231,76,60,.12); border: 1px solid rgba(231,76,60,.4);
        border-radius: 8px; color: #e74c3c;
        padding: 9px 13px; font-size: .82rem;
        margin-bottom: 14px; display: none;
        align-items: center; gap: 8px;
      }
      .modal-error.show { display: flex; }

      .btn-modal-submit {
        width: 100%; padding: 12px;
        background: linear-gradient(135deg, #c5a059, #d4af37);
        border: none; border-radius: 8px;
        color: #000; font-weight: 700; font-size: .95rem;
        cursor: pointer; transition: opacity .2s;
      }
      .btn-modal-submit:hover { opacity: .88; }

      .admin-modal .m-note {
        text-align: center; margin-top: 14px;
        font-size: .75rem; color: #333; line-height: 1.5;
      }
    </style>
  </head>
  <body>
    <nav>
      <div class="nav-left">
        <a href="index.php" class="logo">YOUTHEVER 2026</a>
      </div>
      <button class="nav-toggle" aria-label="Toggle navigation">☰</button>
      <div class="nav-center">
        <a href="index.php">Home</a>
        <a href="about.php">About Us</a>
        <a href="lineup.php">Line Up</a>
        <a href="event-map.php">Venue</a>
        <a href="rundown.php">Rundown</a>
        <a href="announcements.php">Berita</a>
        <a href="faq.php">FAQ</a>
      </div>
      <div class="nav-right">
        <a href="profile.php" style="color: #d4af37;">Dashboard</a>
      </div>
    </nav>

    <main class="dashboard-page">
      <div class="dashboard-container">
        
        <!-- Sidebar -->
        <aside class="dashboard-sidebar">
          <div class="user-card">
            <div class="user-avatar" id="sidebar-avatar">👤</div>
            <label for="sidebar-avatar-input" class="avatar-upload-link">Update Foto</label>
            <input type="file" id="sidebar-avatar-input" accept="image/*" hidden onchange="previewAvatar(event)" />
            <h3><?php echo htmlspecialchars($userData['name']); ?></h3>
            <p><?php echo htmlspecialchars($userData['email']); ?></p>
          </div>

          <nav class="sidebar-menu">
            <a href="profile.php?tab=profile" class="menu-item <?php echo $activeTab === 'profile' ? 'active' : ''; ?>">
              📋 Profile
            </a>
            <a href="profile.php?tab=tickets" class="menu-item <?php echo $activeTab === 'tickets' ? 'active' : ''; ?>">
              🎟️ My Tickets
            </a>
            <a href="profile.php?tab=schedule" class="menu-item <?php echo $activeTab === 'schedule' ? 'active' : ''; ?>">
              📅 Schedule
            </a>
            <a href="profile.php?tab=settings" class="menu-item <?php echo $activeTab === 'settings' ? 'active' : ''; ?>">
              ⚙️ Settings
            </a>
          </nav>

          <a href="index.php?logout=true" class="logout-btn">🚪 Logout</a>

          <!-- Admin Access Button -->
          <button class="btn-admin-access" onclick="document.getElementById('adminModal').classList.add('open')">
            🛡️ Apakah Anda Admin?
          </button>
        </aside>

        <!-- Main Content -->
        <div class="dashboard-content">
          
          <!-- PROFILE TAB -->
          <?php if ($activeTab === 'profile'): ?>
            <section class="tab-content">
              <div class="tab-heading">
                <h2>Profil Saya</h2>
                <p>Kelola informasi akun dan foto profil yang digunakan di dashboard.</p>
              </div>
              <div class="profile-form">
                <div class="profile-avatar-card">
                  <div class="profile-avatar-photo" id="profile-avatar">👤</div>
                  <div>
                    <p class="profile-avatar-label">Foto Profil</p>
                    <label for="profile-avatar-input" class="btn-upload">Unggah Foto</label>
                    <input type="file" id="profile-avatar-input" accept="image/*" hidden onchange="previewAvatar(event)" />
                  </div>
                </div>
                <div class="form-group">
                  <label>Nama Lengkap</label>
                  <input type="text" value="<?php echo htmlspecialchars($userData['name']); ?>" readonly />
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input type="email" value="<?php echo htmlspecialchars($userData['email']); ?>" readonly />
                </div>
                <div class="form-group">
                  <label>Nomor Telepon</label>
                  <input type="tel" value="<?php echo htmlspecialchars($userData['phone']); ?>" />
                </div>
                <div class="form-group">
                  <label>Bergabung Sejak</label>
                  <input type="text" value="<?php echo htmlspecialchars($userData['joined']); ?>" readonly />
                </div>
                <button type="button" class="btn-save">Simpan Perubahan</button>
              </div>
            </section>
          <?php endif; ?>

          <!-- TICKETS TAB -->
          <?php if ($activeTab === 'tickets'): ?>
            <section class="tab-content">
              <div class="tab-heading">
                <h2>Tiket Saya</h2>
                <p>Lihat detail tiket, status pembayaran, kursi, dan kode akses event.</p>
              </div>
              <div class="tickets-list">
                <?php foreach ($tickets as $ticket): ?>
                  <div class="ticket-card">
                    <div class="ticket-header">
                      <h3><?php echo htmlspecialchars($ticket['event']); ?></h3>
                      <span class="status-badge <?php echo strtolower(str_replace(' ', '-', $ticket['status'])); ?>">
                        <?php echo htmlspecialchars($ticket['status']); ?>
                      </span>
                    </div>
                    <div class="ticket-details">
                      <div class="detail-item">
                        <label>Nomor Tiket</label>
                        <p><?php echo htmlspecialchars($ticket['id']); ?></p>
                      </div>
                      <div class="detail-item">
                        <label>Tanggal Event</label>
                        <p><?php echo htmlspecialchars($ticket['date']); ?></p>
                      </div>
                      <div class="detail-item">
                        <label>Kategori</label>
                        <p><?php echo htmlspecialchars($ticket['category']); ?></p>
                      </div>
                      <div class="detail-item">
                        <label>Seat</label>
                        <p><?php echo htmlspecialchars($ticket['seat']); ?></p>
                      </div>
                      <div class="detail-item">
                        <label>Harga</label>
                        <p><?php echo htmlspecialchars($ticket['price']); ?></p>
                      </div>
                    </div>
                    <div class="ticket-qr">
                      <p>QR Code Tiket:</p>
                      <div class="qr-placeholder"><?php echo htmlspecialchars($ticket['qr']); ?></div>
                    </div>
                    <div class="ticket-actions">
                      <button class="btn-download">📥 Download Tiket</button>
                      <button class="btn-share">📤 Share Tiket</button>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </section>
          <?php endif; ?>

          <!-- SCHEDULE TAB -->
          <?php if ($activeTab === 'schedule'): ?>
            <section class="tab-content">
              <div class="tab-heading">
                <h2>Jadwal Favorit Saya</h2>
                <p>Jadwal artis favorit yang Anda simpan akan tampil di sini.</p>
              </div>
              <div class="schedule-list">
                <div class="schedule-item">
                  <div class="schedule-time">16.30 - 18.30</div>
                  <div class="schedule-artist">
                    <h4>DREAMY INDIE POP SESSION</h4>
                    <p>Sleeping At Last, Vancouver Sleep Clinic, Nadin Amizah - Day 1</p>
                  </div>
                </div>
                <div class="schedule-item">
                  <div class="schedule-time">18.30 - 20.00</div>
                  <div class="schedule-artist">
                    <h4>INDIE ROCK STAGE</h4>
                    <p>Reality Club, Hindia - Day 2</p>
                  </div>
                </div>
              </div>
            </section>
          <?php endif; ?>

          <!-- SETTINGS TAB -->
          <?php if ($activeTab === 'settings'): ?>
            <section class="tab-content">
              <div class="tab-heading">
                <h2>Pengaturan</h2>
                <p>Atur notifikasi, privasi, dan keamanan akun festival Anda.</p>
              </div>
              <div class="settings-form">
                <div class="setting-group">
                  <h3>Notifikasi</h3>
                  <label class="checkbox-label">
                    <input type="checkbox" checked />
                    Aktifkan notifikasi event reminder
                  </label>
                  <label class="checkbox-label">
                    <input type="checkbox" checked />
                    Aktifkan notifikasi perubahan jadwal
                  </label>
                </div>
                <div class="setting-group">
                  <h3>Privasi</h3>
                  <label class="checkbox-label">
                    <input type="checkbox" />
                    Tampilkan profil saya di direktori peserta
                  </label>
                </div>
                <div class="setting-group">
                  <h3>Keamanan</h3>
                  <button class="btn-change-password">Ubah Password</button>
                </div>
              </div>
            </section>
          <?php endif; ?>

        </div>

      </div>
    </main>

    <footer>
      <div class="footer-grid">
        <div class="footer-col">
          <h3>YOUTHEVER</h3>
          <p>Festival Experience 2026</p>
        </div>
        <div class="footer-col">
          <p>
            <strong>PARTNERSHIP & SPONSORSHIP</strong><br />partnership@youthreverfest.com
          </p>
          <p><strong>MEDIA & PRESS</strong><br />media@youthreverfest.com</p>
        </div>
        <div class="footer-col">
          <p>
            <strong>CONTACT</strong><br />✉ media@youthreverfest.com<br />🎧 Youmin +62 812-3456-7890
          </p>
        </div>
      </div>
      <div class="footer-bottom">
        <p>© 2026 YOUTHREVERFEST ALL RIGHTS RESERVED.</p>
        <p>🔒 EVENT ADMIN PORTAL</p>
      </div>
    </footer>

    <script>
      (function() {
        const savedPhoto = <?php echo json_encode($userData['photo']); ?>;
        const menuLabels = {
          'profile': ['ID', 'Profile'],
          'tickets': ['TK', 'My Tickets'],
          'schedule': ['SC', 'Schedule'],
          'settings': ['ST', 'Settings']
        };

        Object.keys(menuLabels).forEach((tab) => {
          const link = document.querySelector(`.sidebar-menu a[href="profile.php?tab=${tab}"]`);
          if (!link) return;
          link.innerHTML = `<span class="menu-icon">${menuLabels[tab][0]}</span><span>${menuLabels[tab][1]}</span>`;
        });

        const logout = document.querySelector('.logout-btn');
        if (logout) logout.textContent = 'Logout';

        const sidebarAvatar = document.getElementById('sidebar-avatar');
        const profileAvatar = document.getElementById('profile-avatar');
        [sidebarAvatar, profileAvatar].forEach((avatar) => {
          if (!avatar) return;
          if (savedPhoto) {
            avatar.style.backgroundImage = `url('${savedPhoto}')`;
            avatar.classList.add('has-photo');
            avatar.textContent = '';
          } else if (!avatar.textContent.trim()) {
            avatar.textContent = 'U';
          }
        });

        document.querySelectorAll('.btn-download').forEach((button) => {
          button.textContent = 'Download Tiket';
        });
        document.querySelectorAll('.btn-share').forEach((button) => {
          button.textContent = 'Share Tiket';
        });
      })();

      function previewAvatar(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
          const imageUrl = e.target.result;

          const sidebarAvatar = document.getElementById('sidebar-avatar');
          const profileAvatar = document.getElementById('profile-avatar');

          if (sidebarAvatar) {
            sidebarAvatar.style.backgroundImage = `url('${imageUrl}')`;
            sidebarAvatar.classList.add('has-photo');
            sidebarAvatar.textContent = '';
          }

          if (profileAvatar) {
            profileAvatar.style.backgroundImage = `url('${imageUrl}')`;
            profileAvatar.classList.add('has-photo');
            profileAvatar.textContent = '';
          }
        };
        reader.readAsDataURL(file);
      }
    </script>

    <script>
      (function() {
        var btn = document.querySelector('.nav-toggle');
        var nav = document.querySelector('nav');
        if (!btn || !nav) return;
        btn.addEventListener('click', function() {
          nav.classList.toggle('nav-open');
        });
      })();
    </script>

    <!-- ── Admin Access Modal ─────────────────────── -->
    <div class="admin-modal-overlay" id="adminModal" role="dialog" aria-modal="true" aria-label="Admin Access">
      <div class="admin-modal">
        <button class="modal-close" onclick="closeAdminModal()" aria-label="Tutup">✕</button>
        <span class="m-icon">🛡️</span>
        <h3>Admin Access</h3>
        <p>Masukkan kode akses dan password admin untuk melanjutkan ke panel admin.</p>

        <div class="modal-error" id="modalError">⚠️ <span id="modalErrorText"></span></div>

        <div class="m-field">
          <label>Kode Akses Admin</label>
          <div class="m-field-inner">
            <input type="password" id="modalCode" placeholder="Kode akses rahasia" autocomplete="off"/>
            <button type="button" class="toggle-vis" onclick="toggleVis('modalCode',this)">👁</button>
          </div>
        </div>
        <div class="m-field">
          <label>Password Admin</label>
          <div class="m-field-inner">
            <input type="password" id="modalPass" placeholder="Password admin" autocomplete="off"/>
            <button type="button" class="toggle-vis" onclick="toggleVis('modalPass',this)">👁</button>
          </div>
        </div>

        <button class="btn-modal-submit" onclick="submitAdminAccess()">🚀 Masuk ke Admin Panel</button>
        <p class="m-note">Akses tidak sah akan dicatat dan dilaporkan.</p>
      </div>
    </div>

    <script>
      // ── Kode & password harus cocok dengan admin-login.php ──
      const ADMIN_CODE = 'kelompokwebdinamis';
      const ADMIN_PASS = 'mice4a';

      function submitAdminAccess() {
        const code = document.getElementById('modalCode').value.trim();
        const pass = document.getElementById('modalPass').value.trim();
        const errBox  = document.getElementById('modalError');
        const errText = document.getElementById('modalErrorText');

        if (!code || !pass) {
          errText.textContent = 'Kode akses dan password harus diisi.';
          errBox.classList.add('show');
          return;
        }
        if (code === ADMIN_CODE && pass === ADMIN_PASS) {
          // Kirim via form POST ke admin-login.php agar sesi terbentuk di server
          const form = document.createElement('form');
          form.method = 'POST';
          form.action = 'admin-login.php';
          ['access_code','password'].forEach((name, i) => {
            const inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = name;
            inp.value = i === 0 ? code : pass;
            form.appendChild(inp);
          });
          document.body.appendChild(form);
          form.submit();
        } else {
          errText.textContent = 'Kode akses atau password tidak valid.';
          errBox.classList.add('show');
          document.getElementById('modalCode').value = '';
          document.getElementById('modalPass').value = '';
        }
      }

      function closeAdminModal() {
        document.getElementById('adminModal').classList.remove('open');
        document.getElementById('modalError').classList.remove('show');
        document.getElementById('modalCode').value = '';
        document.getElementById('modalPass').value = '';
      }

      function toggleVis(id, btn) {
        const inp = document.getElementById(id);
        inp.type = inp.type === 'password' ? 'text' : 'password';
        btn.textContent = inp.type === 'password' ? '👁' : '🙈';
      }

      // Tutup modal saat klik overlay
      document.getElementById('adminModal').addEventListener('click', function(e) {
        if (e.target === this) closeAdminModal();
      });

      // Enter key submit
      ['modalCode','modalPass'].forEach(id => {
        document.getElementById(id).addEventListener('keydown', e => {
          if (e.key === 'Enter') submitAdminAccess();
        });
      });
    </script>
  </body>
</html>
