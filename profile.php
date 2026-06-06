<?php
session_start();
$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

if (!$loggedIn) {
    header('Location: index.php');
    exit;
}

// Simulasi data pengguna
$userData = [
    'name' => 'John Doe',
    'email' => 'shizlafasia@gmail.com',
    'phone' => '+62 812-3456-7890',
    'joined' => '15 Januari 2026'
];

// Simulasi riwayat tiket
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

$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'profile';
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - YOUTHEVER 2026</title>
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <nav>
      <div class="nav-left">
        <div class="logo">YOUTHEVER 2026</div>
      </div>
      <div class="nav-center">
        <a href="index.php">Home</a>
        <a href="lineup.php">Line Up</a>
        <a href="event-map.php">Venue</a>
        <a href="rundown.php">Rundown</a>
        <a href="announcements.php">Berita</a>
        <a href="faq.php">FAQ</a>
      </div>
      <div class="nav-right">
        <a href="profile.php" style="color: #d4af37;">Dashboard</a>
        <a href="tickets.php" class="buy-btn">Buy Ticket</a>
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

          <div class="sidebar-menu">
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
          </div>

          <a href="index.php?logout=true" class="logout-btn">🚪 Logout</a>
        </aside>

        <!-- Main Content -->
        <div class="dashboard-content">
          
          <!-- PROFILE TAB -->
          <?php if ($activeTab === 'profile'): ?>
            <section class="tab-content">
              <h2>Profil Saya</h2>
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
                <button class="btn-save">Simpan Perubahan</button>
              </div>
            </section>
          <?php endif; ?>

          <!-- TICKETS TAB -->
          <?php if ($activeTab === 'tickets'): ?>
            <section class="tab-content">
              <h2>Tiket Saya</h2>
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
                      <div class="qr-placeholder"><?php echo $ticket['qr']; ?></div>
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
              <h2>Jadwal Favorit Saya</h2>
              <p style="color: #888; margin: 20px 0;">Jadwal artis favorit yang Anda simpan akan tampil di sini.</p>
              <div class="schedule-list">
                <div class="schedule-item">
                  <div class="schedule-time">12:00 - 13:00</div>
                  <div class="schedule-artist">
                    <h4>KUNTO AJI</h4>
                    <p>Main Stage - 24 Oktober</p>
                  </div>
                </div>
                <div class="schedule-item">
                  <div class="schedule-time">18:00 - 19:00</div>
                  <div class="schedule-artist">
                    <h4>HINDIA</h4>
                    <p>Side Stage - 25 Oktober</p>
                  </div>
                </div>
              </div>
            </section>
          <?php endif; ?>

          <!-- SETTINGS TAB -->
          <?php if ($activeTab === 'settings'): ?>
            <section class="tab-content">
              <h2>Pengaturan</h2>
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

  </body>
</html>
