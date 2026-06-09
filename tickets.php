<?php
session_start();
$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// Simulasi data tiket
$ticketCategories = [
    [
        'id' => 1,
        'name' => 'Regular Pass',
        'price' => 300000,
        'description' => 'Akses ke area umum festival',
        'features' => ['Area umum', 'Main stage', 'Side stage'],
        'stock' => 150
    ],
    [
        'id' => 2,
        'name' => 'VIP Pass',
        'price' => 750000,
        'description' => 'Akses VIP dengan fasilitas eksklusif',
        'features' => ['Area VIP', 'Seating nyaman', 'Meet & greet', 'Free merchandise'],
        'stock' => 50
    ],
    [
        'id' => 3,
        'name' => 'Premium Pass',
        'price' => 1200000,
        'description' => 'Pengalaman premium dengan akses penuh',
        'features' => ['Premium lounge', 'Parking gratis', 'Catering gratis', 'VIP merchandise'],
        'stock' => 25
    ]
];

$step = isset($_GET['step']) ? intval($_GET['step']) : 1;
$selectedTicket = isset($_GET['ticket']) ? intval($_GET['ticket']) : null;
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Beli Tiket - YOUTHEVER 2026</title>
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
        <?php if ($loggedIn): ?>
          <a href="profile.php">Dashboard</a>
        <?php else: ?>
          <a href="index.php">Login</a>
        <?php endif; ?>
        <a href="tickets.php" class="buy-btn">Buy Ticket</a>
      </div>
    </nav>

    <main class="tickets-page">
      <div class="tickets-container">
        
        <!-- Progress Bar -->
        <div class="progress-bar">
          <div class="progress-step <?php echo $step >= 1 ? 'active' : ''; ?>">
            <span>1</span> <span class="step-label">Pilih Tiket</span>
          </div>
          <div class="progress-line <?php echo $step >= 2 ? 'active' : ''; ?>"></div>
          <div class="progress-step <?php echo $step >= 2 ? 'active' : ''; ?>">
            <span>2</span> <span class="step-label">Form Data</span>
          </div>
          <div class="progress-line <?php echo $step >= 3 ? 'active' : ''; ?>"></div>
          <div class="progress-step <?php echo $step >= 3 ? 'active' : ''; ?>">
            <span>3</span> <span class="step-label">Pilih Seat</span>
          </div>
          <div class="progress-line <?php echo $step >= 4 ? 'active' : ''; ?>"></div>
          <div class="progress-step <?php echo $step >= 4 ? 'active' : ''; ?>">
            <span>4</span> <span class="step-label">Checkout</span>
          </div>
        </div>

        <!-- STEP 1: Pilih Tiket -->
        <?php if ($step == 1): ?>
          <section class="step-content">
            <h2>Pilih Kategori Tiket</h2>
            <p style="color: #888; margin-bottom: 30px;">Pilih kategori tiket yang sesuai dengan kebutuhan Anda</p>
            
            <div class="ticket-options">
              <?php foreach ($ticketCategories as $ticket): ?>
                <div class="ticket-option">
                  <div class="ticket-info">
                    <h3><?php echo htmlspecialchars($ticket['name']); ?></h3>
                    <p class="ticket-description"><?php echo htmlspecialchars($ticket['description']); ?></p>
                    <ul class="ticket-features">
                      <?php foreach ($ticket['features'] as $feature): ?>
                        <li>✓ <?php echo htmlspecialchars($feature); ?></li>
                      <?php endforeach; ?>
                    </ul>
                    <p class="ticket-stock">Stok tersedia: <?php echo $ticket['stock']; ?> tiket</p>
                  </div>
                  <div class="ticket-price">
                    <div class="price">Rp<?php echo number_format($ticket['price'], 0, ',', '.'); ?></div>
                    <a href="?step=2&ticket=<?php echo $ticket['id']; ?>" class="btn-select">Pilih</a>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </section>
        <?php endif; ?>

        <!-- STEP 2: Form Data -->
        <?php if ($step == 2 && $selectedTicket): ?>
          <section class="step-content">
            <h2>Isi Data Pembeli</h2>
            <form class="purchase-form">
              <div class="form-row">
                <div class="form-group">
                  <label>Nama Lengkap</label>
                  <input type="text" placeholder="Nama Lengkap" required />
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input type="email" placeholder="email@example.com" required />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label>Nomor Telepon</label>
                  <input type="tel" placeholder="+62 8xx-xxxx-xxxx" required />
                </div>
                <div class="form-group">
                  <label>Jumlah Tiket</label>
                  <input type="number" min="1" max="5" value="1" required />
                </div>
              </div>
              <div class="form-group">
                <label>Catatan (opsional)</label>
                <textarea placeholder="Catatan tambahan..."></textarea>
              </div>
              <div class="form-actions">
                <a href="?step=1" class="btn-back">← Kembali</a>
                <a href="?step=3&ticket=<?php echo $selectedTicket; ?>" class="btn-next">Lanjutkan →</a>
              </div>
            </form>
          </section>
        <?php endif; ?>

        <!-- STEP 3: Pilih Seat -->
        <?php if ($step == 3 && $selectedTicket): ?>
          <section class="step-content">
            <h2>Pilih Tempat Duduk</h2>
            <div class="seat-selection">
              <div class="seat-legend">
                <span><span class="seat-available"></span> Tersedia</span>
                <span><span class="seat-booked"></span> Terpesan</span>
                <span><span class="seat-selected"></span> Dipilih</span>
              </div>
              
              <div class="seating-chart">
                <?php for ($row = 1; $row <= 8; $row++): ?>
                  <div class="seat-row">
                    <div class="row-label">Baris <?php echo $row; ?></div>
                    <?php for ($col = 1; $col <= 12; $col++): ?>
                      <?php 
                        $isBooked = rand(0, 1) === 1; // Simulasi kursi yang terpesan
                        $seatId = chr(64 + $row) . $col;
                      ?>
                      <button 
                        type="button" 
                        class="seat <?php echo $isBooked ? 'booked' : 'available'; ?>" 
                        data-seat="<?php echo $seatId; ?>"
                        <?php echo $isBooked ? 'disabled' : ''; ?>
                      >
                        <?php echo $col; ?>
                      </button>
                    <?php endfor; ?>
                  </div>
                <?php endfor; ?>
              </div>
            </div>
            <div class="form-actions">
              <a href="?step=2&ticket=<?php echo $selectedTicket; ?>" class="btn-back">← Kembali</a>
              <a href="?step=4&ticket=<?php echo $selectedTicket; ?>" class="btn-next">Lanjutkan →</a>
            </div>
          </section>
        <?php endif; ?>

        <!-- STEP 4: Checkout -->
        <?php if ($step == 4 && $selectedTicket): ?>
          <section class="step-content">
            <h2>Review & Checkout</h2>
            <div class="checkout-summary">
              <div class="summary-item">
                <label>Kategori Tiket</label>
                <p><?php echo htmlspecialchars($ticketCategories[$selectedTicket - 1]['name']); ?></p>
              </div>
              <div class="summary-item">
                <label>Harga Per Tiket</label>
                <p>Rp<?php echo number_format($ticketCategories[$selectedTicket - 1]['price'], 0, ',', '.'); ?></p>
              </div>
              <div class="summary-item">
                <label>Jumlah Tiket</label>
                <p>1</p>
              </div>
              <div class="summary-total">
                <label>Total Pembayaran</label>
                <p>Rp<?php echo number_format($ticketCategories[$selectedTicket - 1]['price'], 0, ',', '.'); ?></p>
              </div>
            </div>

            <div class="payment-methods">
              <h3>Metode Pembayaran</h3>
              <label class="payment-option">
                <input type="radio" name="payment" checked />
                <span>💳 Transfer Bank</span>
              </label>
              <label class="payment-option">
                <input type="radio" name="payment" />
                <span>📱 E-Wallet</span>
              </label>
              <label class="payment-option">
                <input type="radio" name="payment" />
                <span>🏪 Convenience Store</span>
              </label>
            </div>

            <div class="form-actions">
              <a href="?step=3&ticket=<?php echo $selectedTicket; ?>" class="btn-back">← Kembali</a>
              <button type="button" class="btn-checkout" onclick="alert('Proses pembayaran dimulai...'); location.href='index.php';">Lanjut ke Pembayaran</button>
            </div>
          </section>
        <?php endif; ?>

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
      // Seat selection logic
      document.querySelectorAll('.seat.available').forEach(seat => {
        seat.addEventListener('click', function() {
          this.classList.toggle('selected');
        });
      });
    </script>

  </body>
</html>
