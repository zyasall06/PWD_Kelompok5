<?php
session_start();
$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// Simulasi data pengumuman
$announcements = [
    [
        'id' => 1,
        'title' => 'Lineup Final Telah Diumumkan!',
        'content' => 'Semua artis yang akan tampil di YOUTHEVER 2026 telah dikonfirmasi. Segera pesan tiketmu sekarang sebelum terlambat.',
        'date' => '2 Juni 2026',
        'type' => 'penting',
        'image' => '🎤'
    ],
    [
        'id' => 2,
        'title' => 'Perubahan Jadwal Stage A',
        'content' => 'Jadwal stage A untuk hari kedua telah diubah. Beberapa artis akan tampil lebih awal dari rencana semula untuk mengakomodasi acara lain.',
        'date' => '1 Juni 2026',
        'type' => 'update',
        'image' => '📅'
    ],
    [
        'id' => 3,
        'title' => 'Early Bird Tiket Regular Pass Habis!',
        'content' => 'Early bird untuk kategori Regular Pass telah habis terjual dalam 48 jam. Tiket regular pass dengan harga normal masih tersedia.',
        'date' => '31 Mei 2026',
        'type' => 'info',
        'image' => '🎟️'
    ],
    [
        'id' => 4,
        'title' => 'Fasilitas Area VIP Dibuka Pendaftaran',
        'content' => 'Area VIP lounge sekarang tersedia dengan fasilitas lengkap: AC, WiFi gratis, catering, dan meet & greet dengan artis pilihan.',
        'date' => '28 Mei 2026',
        'type' => 'penting',
        'image' => '⭐'
    ],
    [
        'id' => 5,
        'title' => 'Sponsor Terbaru Bergabung dengan YOUTHEVER 2026',
        'content' => 'Kami bangga mengumumkan sponsor terbaru yang bergabung dalam festival ini. Sponsors ini akan memberikan experience eksklusif untuk pengunjung.',
        'date' => '25 Mei 2026',
        'type' => 'info',
        'image' => '🤝'
    ],
];
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pengumuman & Berita - YOUTHEVER 2026</title>
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
        <a href="announcements.php" style="border-bottom: 2px solid #d4af37; color: #d4af37;">Berita</a>
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

    <main class="announcements-page">
      <div class="announcements-container">
        
        <section class="page-header">
          <h1>📢 Pengumuman & Berita</h1>
          <p>Tetap update dengan semua informasi terbaru tentang YOUTHEVER 2026</p>
        </section>

        <!-- Filter -->
        <div class="filter-section">
          <button class="filter-btn active" onclick="filterAnnouncements('all')">Semua</button>
          <button class="filter-btn" onclick="filterAnnouncements('penting')">Penting</button>
          <button class="filter-btn" onclick="filterAnnouncements('update')">Update</button>
          <button class="filter-btn" onclick="filterAnnouncements('info')">Info</button>
        </div>

        <!-- Announcements List -->
        <div class="announcements-list">
          <?php foreach ($announcements as $announcement): ?>
            <article class="announcement-card" data-type="<?php echo htmlspecialchars($announcement['type']); ?>">
              <div class="announcement-header">
                <div class="announcement-icon"><?php echo $announcement['image']; ?></div>
                <div class="announcement-meta">
                  <h3><?php echo htmlspecialchars($announcement['title']); ?></h3>
                  <div class="announcement-info">
                    <span class="date">📅 <?php echo htmlspecialchars($announcement['date']); ?></span>
                    <span class="type-badge <?php echo htmlspecialchars($announcement['type']); ?>">
                      <?php 
                        $typeLabel = [
                          'penting' => 'Penting',
                          'update' => 'Update',
                          'info' => 'Info'
                        ];
                        echo $typeLabel[$announcement['type']] ?? 'Info';
                      ?>
                    </span>
                  </div>
                </div>
              </div>
              <div class="announcement-content">
                <p><?php echo htmlspecialchars($announcement['content']); ?></p>
              </div>
              <div class="announcement-footer">
                <button class="btn-read-more">Baca Selengkapnya →</button>
              </div>
            </article>
          <?php endforeach; ?>
        </div>

        <!-- Empty State (untuk filter tertentu) -->
        <div id="empty-state" style="display: none; text-align: center; padding: 60px 20px;">
          <p style="color: #888; font-size: 1.1rem;">Tidak ada pengumuman untuk kategori ini.</p>
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
      function filterAnnouncements(type) {
        const cards = document.querySelectorAll('.announcement-card');
        const buttons = document.querySelectorAll('.filter-btn');
        let visibleCount = 0;

        // Update button active state
        buttons.forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');

        // Filter cards
        cards.forEach(card => {
          if (type === 'all' || card.getAttribute('data-type') === type) {
            card.style.display = 'block';
            visibleCount++;
          } else {
            card.style.display = 'none';
          }
        });

        // Show/hide empty state
        document.getElementById('empty-state').style.display = 
          visibleCount === 0 ? 'block' : 'none';
      }
    </script>

  </body>
</html>
