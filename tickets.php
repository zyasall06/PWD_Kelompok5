<?php
include 'service/database.php';
session_start();

$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

$ticketCategories = [
    ['id'=>1,'name'=>'Regular Pass', 'price'=>300000, 'description'=>'Akses ke area umum festival',              'features'=>['Area umum','Main stage','Side stage'],                              'stock'=>150],
    ['id'=>2,'name'=>'VIP Pass',     'price'=>750000, 'description'=>'Akses VIP dengan fasilitas eksklusif',      'features'=>['Area VIP','Seating nyaman','Meet & greet','Free merchandise'],      'stock'=>50],
    ['id'=>3,'name'=>'Premium Pass', 'price'=>1200000,'description'=>'Pengalaman premium dengan akses penuh',     'features'=>['Premium lounge','Parking gratis','Catering gratis','VIP merchandise'],'stock'=>25],
];

$step            = isset($_GET['step'])    ? intval($_GET['step'])    : 1;
$selectedTicket = isset($_GET['ticket']) ? intval($_GET['ticket']) : null;
$qty            = isset($_GET['qty'])    ? max(1, min(5, intval($_GET['qty']))) : 1;

// --- PROSES SIMPAN DATABASE SAAT USER SELESAI TRANSAKSI ---
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_ticket'])) {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    
    if (empty($name) || empty($email) || empty($phone) || $quantity <= 0) {
        $error = 'Semua field wajib diisi dengan benar.';
        $step = 2; // Paksa kembali ke step 2 jika ada data kosong
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
        $step = 2;
    } else {
        // Query Simpan Data ke Tabel Tickets Anda
        $sql = "INSERT INTO ticket2 (`nama lengkap`, `email`, `nomor telepon`, `jumlah tiket`) 
                VALUES ('$name', '$email', '$phone', '$quantity')";

        if ($db->query($sql) === TRUE) {
            // Jika sukses disimpan, arahkan ke halaman profil/sukses lewat Javascript bawaan Anda di bawah
            $success = 'Pemesanan berhasil disimpan ke database!';
        } else {
            $error = 'Gagal menyimpan ke database: ' . $db->error;
            $step = 4;
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Beli Tiket – YOUTHEVER 2026</title>
  <link rel="stylesheet" href="css/style.css"/>
  <style>
    /* ── Tickets page background ─────────────────── */
    body {
      background:
        linear-gradient(rgba(0,0,0,.82), rgba(0,0,0,.82)),
        url("image/back 11.jpg") center/cover no-repeat fixed;
      min-height: 100vh;
    }

    /* Nav frosted */
    nav {
      position: fixed !important;
      top: 0; left: 0; right: 0; z-index: 200;
      background: rgba(0,0,0,.82) !important;
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
    }
    .tickets-page { padding-top: 80px; }

    /* Step content glass */
    .step-content {
      background: rgba(20,10,20,.72) !important;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(197,160,89,.15) !important;
      border-radius: 14px;
    }

    /* Progress bar glass */
    .progress-bar {
      background: rgba(20,10,20,.72) !important;
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      border: 1px solid rgba(197,160,89,.15) !important;
    }
    /* ── Seat: all available = green ─────────────────── */
    .seat.available         { background:#2a5a2a; border-color:#51cf66; color:#fff; }
    .seat.available:hover   { background:#3a7a3a; }
    .seat.available.selected{ background:#d4af37; border-color:#d4af37; color:#000; font-weight:700; }

    /* ── Payment section ─────────────────────────────── */
    .payment-section {
      background:#111; border:1px solid #2a2a2a;
      border-radius:12px; overflow:hidden; margin-top:28px;
    }
    .payment-section-header {
      display:flex; align-items:center; justify-content:space-between;
      padding:16px 20px; background:#0d0d0d;
      border-bottom:1px solid #1e1e1e;
    }
    .payment-section-header h3 { color:#d4af37; margin:0; font-size:1rem; }

    /* Countdown */
    .pay-timer {
      display:flex; align-items:center; gap:8px;
      background:#2a0a0a; border:1px solid #e74c3c44;
      border-radius:8px; padding:6px 14px;
    }
    .pay-timer .timer-label { color:#888; font-size:.76rem; }
    .pay-timer .timer-val   { color:#e74c3c; font-weight:700; font-size:1rem; font-variant-numeric:tabular-nums; letter-spacing:.04em; }
    .pay-timer.warn .timer-val { animation: blink .6s step-end infinite; }
    @keyframes blink { 50%{ opacity:.3; } }

    /* Method cards */
    .pay-methods { display:grid; grid-template-columns:1fr 1fr; gap:14px; padding:20px; }
    .pay-method-card {
      border:2px solid #2a2a2a; border-radius:10px;
      padding:18px 16px; cursor:pointer;
      transition:all .2s; background:#0d0d0d;
    }
    .pay-method-card:hover  { border-color:#c5a059; }
    .pay-method-card.chosen { border-color:#d4af37; background:#1a1500; }
    .pay-method-card input  { display:none; }
    .pm-top { display:flex; align-items:center; gap:10px; margin-bottom:10px; }
    .pm-icon { font-size:1.6rem; }
    .pm-name { color:#fff; font-weight:700; font-size:.95rem; }
    .pm-detail { background:#111; border:1px solid #1e1e1e; border-radius:8px; padding:12px; }
    .pm-detail .pm-row { display:flex; justify-content:space-between; align-items:center; padding:5px 0; border-bottom:1px solid #1a1a1a; }
    .pm-detail .pm-row:last-child { border:none; padding-bottom:0; }
    .pm-detail .pm-row label { color:#666; font-size:.76rem; text-transform:uppercase; letter-spacing:.05em; }
    .pm-detail .pm-row strong { color:#d4af37; font-size:.9rem; letter-spacing:.03em; }
    .pm-copy {
      display:flex; align-items:center; gap:6px;
      background:none; border:1px solid #333;
      color:#888; padding:4px 10px; border-radius:5px;
      font-size:.74rem; cursor:pointer; transition:all .15s;
    }
    .pm-copy:hover { border-color:#c5a059; color:#d4af37; }
    .pm-copy.copied { border-color:#27ae60; color:#27ae60; }

    /* Proof upload */
    .pay-proof { padding:0 20px 20px; }
    .pay-proof h4 { color:#d4af37; margin-bottom:12px; font-size:.9rem; }
    .proof-upload-area {
      border:2px dashed #2a2a2a; border-radius:10px;
      padding:28px; text-align:center;
      cursor:pointer; transition:border-color .2s;
      background:#0d0d0d; position:relative;
    }
    .proof-upload-area:hover { border-color:#c5a059; }
    .proof-upload-area.has-file { border-color:#27ae60; border-style:solid; }
    .proof-upload-area input[type=file] {
      position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%;
    }
    .proof-icon   { font-size:2rem; margin-bottom:8px; display:block; }
    .proof-text   { color:#666; font-size:.85rem; }
    .proof-text strong { color:#c5a059; }
    .proof-preview {
      max-width:100%; max-height:200px; border-radius:8px;
      display:none; margin:10px auto 0; object-fit:contain;
    }
    .proof-preview.show { display:block; }
    .proof-filename { color:#27ae60; font-size:.82rem; margin-top:8px; display:none; }
    .proof-filename.show { display:block; }

    /* Submit payment */
    .pay-submit-row { padding:0 20px 20px; }
    .btn-pay-submit {
      width:100%; padding:13px;
      background:linear-gradient(135deg,#c5a059,#d4af37);
      border:none; border-radius:10px; color:#000;
      font-weight:700; font-size:1rem; cursor:pointer;
      letter-spacing:.04em; transition:opacity .2s;
    }
    .btn-pay-submit:hover   { opacity:.88; }
    .btn-pay-submit:disabled{ opacity:.4; cursor:not-allowed; }

    /* Expired overlay */
    .timer-expired {
      display:none; position:fixed; inset:0;
      background:rgba(0,0,0,.88); z-index:9999;
      align-items:center; justify-content:center;
    }
    .timer-expired.show { display:flex; }
    .expired-box {
      background:#111; border:2px solid #e74c3c;
      border-radius:16px; padding:40px 36px;
      text-align:center; max-width:380px;
    }
    .expired-box .ex-icon { font-size:3rem; display:block; margin-bottom:14px; }
    .expired-box h3 { color:#e74c3c; font-size:1.4rem; margin-bottom:10px; }
    .expired-box p  { color:#888; font-size:.88rem; margin-bottom:24px; line-height:1.6; }
    .btn-restart {
      background:#e74c3c; color:#fff; border:none;
      padding:11px 24px; border-radius:8px;
      font-weight:700; cursor:pointer; font-size:.95rem;
    }

    @media(max-width:600px){ .pay-methods{ grid-template-columns:1fr; } }
  </style>
</head>
<body>
<nav>
  <div class="nav-left"><a href="index.php" class="logo">YOUTHEVER 2026</a></div>
  <button class="nav-toggle" aria-label="Toggle navigation">☰</button>
  <div class="nav-center">
    <a href="index.php">Home</a><a href="about.php">About Us</a><a href="lineup.php">Line Up</a>
    <a href="event-map.php">Venue</a><a href="rundown.php">Rundown</a>
    <a href="announcements.php">Berita</a><a href="faq.php">FAQ</a>
  </div>
  <div class="nav-right">
    <?php if ($loggedIn): ?><a href="profile.php">Dashboard</a>
    <?php else: ?><a href="index.php">Login</a><?php endif; ?>
  </div>
</nav>

<main class="tickets-page">
<div class="tickets-container">

  <?php if ($error): ?>
    <p style="color: #ff6b6b; text-align: center; font-weight: bold; margin-bottom: 15px;"><?php echo htmlspecialchars($error); ?></p>
  <?php endif; ?>

  <div class="progress-bar">
    <?php
    $steps = ['Pilih Tiket','Form Data','Pilih Seat','Checkout'];
    foreach ($steps as $i => $label):
      $n = $i + 1;
    ?>
    <div class="progress-step <?php echo $step >= $n ? 'active' : ''; ?>">
      <span><?php echo $n; ?></span>
      <span class="step-label"><?php echo $label; ?></span>
    </div>
    <?php if ($n < 4): ?>
      <div class="progress-line <?php echo $step > $n ? 'active' : ''; ?>"></div>
    <?php endif; endforeach; ?>
  </div>

  <?php if ($step == 1): ?>
  <section class="step-content">
    <h2>Pilih Kategori Tiket</h2>
    <p style="color:#888;margin-bottom:30px;">Pilih kategori tiket yang sesuai dengan kebutuhan Anda</p>
    <div class="ticket-options">
      <?php foreach ($ticketCategories as $ticket): ?>
      <div class="ticket-option">
        <div class="ticket-info">
          <h3><?php echo htmlspecialchars($ticket['name']); ?></h3>
          <p class="ticket-description"><?php echo htmlspecialchars($ticket['description']); ?></p>
          <ul class="ticket-features">
            <?php foreach ($ticket['features'] as $f): ?>
              <li>✓ <?php echo htmlspecialchars($f); ?></li>
            <?php endforeach; ?>
          </ul>
          <p class="ticket-stock">Stok tersedia: <?php echo $ticket['stock']; ?> tiket</p>
        </div>
        <div class="ticket-price">
          <div class="price">Rp<?php echo number_format($ticket['price'],0,',','.'); ?></div>
          <a href="?step=2&ticket=<?php echo $ticket['id']; ?>" class="btn-select">Pilih</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <?php if ($step == 2 && $selectedTicket): ?>
  <section class="step-content">
    <h2>Isi Data Pembeli</h2>
    <div class="purchase-form">
      <div class="form-row">
        <div class="form-group">
          <label>Nama Lengkap</label>
          <input type="text" id="custName" placeholder="Nama Lengkap" value="<?php echo isset($_SESSION['temp_name']) ? htmlspecialchars($_SESSION['temp_name']) : ''; ?>" required/>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" id="custEmail" placeholder="email@example.com" value="<?php echo isset($_SESSION['temp_email']) ? htmlspecialchars($_SESSION['temp_email']) : ''; ?>" required/>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Nomor Telepon</label>
          <input type="tel" id="custPhone" placeholder="+62 8xx-xxxx-xxxx" value="<?php echo isset($_SESSION['temp_phone']) ? htmlspecialchars($_SESSION['temp_phone']) : ''; ?>" required/>
        </div>
        <div class="form-group">
          <label>Jumlah Tiket</label>
          <input type="number" id="qtyInput" min="1" max="5" value="<?php echo $qty; ?>" required/>
        </div>
      </div>
      <div class="form-actions">
        <a href="?step=1" class="btn-back">← Kembali</a>
        <button type="button" class="btn-next" onclick="saveStep2Data()">
          Lanjutkan →
        </button>
      </div>
    </div>
  </section>
  <script>
    function saveStep2Data() {
        const name = document.getElementById('custName').value;
        const email = document.getElementById('custEmail').value;
        const phone = document.getElementById('custPhone').value;
        const qty = document.getElementById('qtyInput').value;

        if(!name || !email || !phone) {
            alert('Harap isi seluruh field data pembeli!');
            return;
        }

        sessionStorage.setItem('t_name', name);
        sessionStorage.setItem('t_email', email);
        sessionStorage.setItem('t_phone', phone);
        
        location.href='?step=3&ticket=<?php echo $selectedTicket; ?>&qty='+qty;
    }
  </script>
  <?php endif; ?>

  <?php if ($step == 3 && $selectedTicket): ?>
  <section class="step-content">
    <h2>Pilih Tempat Duduk</h2>
    <div class="seat-selection">
      <div class="seat-legend">
        <span><span class="seat-available"></span> Tersedia</span>
        <span><span class="seat-booked"></span> Terpesan</span>
        <span><span class="seat-selected"></span> Dipilih</span>
      </div>

      <div style="text-align:center;background:#1a0a0a;border:2px solid #e74c3c44;
        border-radius:8px;padding:10px;color:#e74c3c;font-weight:700;
        font-size:.85rem;letter-spacing:.1em;margin-bottom:20px;">
        🎤 STAGE / PANGGUNG
      </div>

      <div class="seating-chart">
        <?php for ($row = 1; $row <= 8; $row++): ?>
        <div class="seat-row">
          <div class="row-label">Baris <?php echo $row; ?></div>
          <?php for ($col = 1; $col <= 12; $col++):
            $seatId = chr(64 + $row) . $col;
          ?>
          <button type="button"
            class="seat available"
            data-seat="<?php echo $seatId; ?>">
            <?php echo $col; ?>
          </button>
          <?php endfor; ?>
        </div>
        <?php endfor; ?>
      </div>

      <div id="selectedSeatInfo" style="margin-top:14px;padding:12px 16px;
        background:#111;border:1px solid #2a2a2a;border-radius:8px;
        color:#888;font-size:.85rem;display:none;">
        Kursi dipilih: <strong id="selectedSeatList" style="color:#d4af37;"></strong>
      </div>
    </div>
    <div class="form-actions">
      <a href="?step=2&ticket=<?php echo $selectedTicket; ?>&qty=<?php echo $qty; ?>" class="btn-back">← Kembali</a>
      <a href="?step=4&ticket=<?php echo $selectedTicket; ?>&qty=<?php echo $qty; ?>" class="btn-next">Lanjutkan →</a>
    </div>
  </section>
  <?php endif; ?>

  <?php if ($step == 4 && $selectedTicket):
    $tkt   = $ticketCategories[$selectedTicket - 1];
    $total = $tkt['price'] * $qty;
  ?>
  <section class="step-content">
    <h2>Review & Checkout</h2>

    <div class="checkout-summary">
      <div class="summary-item">
        <label>Kategori Tiket</label>
        <p><?php echo htmlspecialchars($tkt['name']); ?></p>
      </div>
      <div class="summary-item">
        <label>Harga Per Tiket</label>
        <p>Rp<?php echo number_format($tkt['price'],0,',','.'); ?></p>
      </div>
      <div class="summary-item">
        <label>Jumlah Tiket</label><p><?php echo $qty; ?></p>
      </div>
      <div class="summary-total">
        <label>Total Pembayaran</label>
        <p>Rp<?php echo number_format($total, 0, ',', '.'); ?></p>
      </div>
    </div>

    <div class="payment-section">

      <div class="payment-section-header">
        <h3>💳 Metode Pembayaran</h3>
        <div class="pay-timer" id="payTimer">
          <span class="timer-label">⏱ Selesaikan dalam</span>
          <span class="timer-val" id="timerDisplay">15:00</span>
        </div>
      </div>

      <div class="pay-methods">

        <label class="pay-method-card chosen" id="card-bank">
          <input type="radio" name="pay_method" value="bank" checked onchange="selectMethod('bank')"/>
          <div class="pm-top">
            <span class="pm-icon">🏦</span>
            <span class="pm-name">Transfer Bank</span>
          </div>
          <div class="pm-detail">
            <div class="pm-row">
              <label>Bank</label>
              <strong>Bank Mandiri</strong>
            </div>
            <div class="pm-row">
              <label>No. Rekening</label>
              <div style="display:flex;align-items:center;gap:8px;">
                <strong id="norek">98230923918</strong>
                <button type="button" class="pm-copy" onclick="copyText('98230923918',this)">📋 Salin</button>
              </div>
            </div>
            <div class="pm-row">
              <label>Atas Nama</label>
              <strong>YOUTHEVER 2026</strong>
            </div>
          </div>
        </label>

        <label class="pay-method-card" id="card-ewallet">
          <input type="radio" name="pay_method" value="ewallet" onchange="selectMethod('ewallet')"/>
          <div class="pm-top">
            <span class="pm-icon">📱</span>
            <span class="pm-name">E-Wallet (GoPay)</span>
          </div>
          <div class="pm-detail">
            <div class="pm-row">
              <label>Platform</label>
              <strong>GoPay</strong>
            </div>
            <div class="pm-row">
              <label>Nomor</label>
              <div style="display:flex;align-items:center;gap:8px;">
                <strong id="gopay">0895339233393</strong>
                <button type="button" class="pm-copy" onclick="copyText('0895339233393',this)">📋 Salin</button>
              </div>
            </div>
            <div class="pm-row">
              <label>Atas Nama</label>
              <strong>YOUTHEVER 2026</strong>
            </div>
          </div>
        </label>

      </div><form method="post" action="tickets.php?step=4&ticket=<?php echo $selectedTicket; ?>&qty=<?php echo $qty; ?>" enctype="multipart/form-data">
        
        <input type="hidden" name="name" id="hiddenName" />
        <input type="hidden" name="email" id="hiddenEmail" />
        <input type="hidden" name="phone" id="hiddenPhone" />
        <input type="hidden" name="quantity" value="<?php echo $qty; ?>" />

        <div class="pay-proof">
          <h4>📎 Upload Bukti Pembayaran</h4>
          <div class="proof-upload-area" id="proofArea">
            <input type="file" id="proofFile" accept="image/*,.pdf" onchange="handleProofUpload(event)"/>
            <span class="proof-icon">🖼️</span>
            <p class="proof-text">
              <strong>Klik atau seret file ke sini</strong><br>
              Mendukung JPG, PNG, PDF · Maks. 5 MB
            </p>
            <img class="proof-preview" id="proofPreview" alt="Bukti pembayaran"/>
          </div>
          <p class="proof-filename" id="proofFilename"></p>
          <p style="color:#555;font-size:.76rem;margin-top:8px;">
            * Upload bukti transfer setelah melakukan pembayaran. Tiket akan diverifikasi dalam 1×24 jam.
          </p>
        </div>

        <div class="pay-submit-row">
          <button type="submit" name="buy_ticket" class="btn-pay-submit" id="btnPaySubmit">
            ✅ Konfirmasi Pembayaran
          </button>
        </div>
      </form>

    </div><div class="form-actions" style="margin-top:20px;">
      <a href="?step=3&ticket=<?php echo $selectedTicket; ?>&qty=<?php echo $qty; ?>" class="btn-back">← Kembali</a>
    </div>
  </section>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
        if(document.getElementById('hiddenName')) {
            document.getElementById('hiddenName').value = sessionStorage.getItem('t_name') || '';
            document.getElementById('hiddenEmail').value = sessionStorage.getItem('t_email') || '';
            document.getElementById('hiddenPhone').value = sessionStorage.getItem('t_phone') || '';
        }
    });
  </script>
  <?php endif; ?>

</div></main>

<div class="timer-expired" id="timerExpired">
  <div class="expired-box">
    <span class="ex-icon">⏰</span>
    <h3>Waktu Habis!</h3>
    <p>Sesi pemesanan Anda telah berakhir (15 menit). Silakan mulai ulang proses pembelian tiket.</p>
    <button class="btn-restart" onclick="location.href='?step=1'">🔄 Mulai Ulang</button>
  </div>
</div>

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
/* ── Seat selection ──────────────────────────────────── */
const selectedSeats = new Set();
document.querySelectorAll('.seat.available').forEach(seat => {
  seat.addEventListener('click', function() {
    const id = this.dataset.seat;
    if (this.classList.contains('selected')) {
      this.classList.remove('selected');
      selectedSeats.delete(id);
    } else {
      this.classList.add('selected');
      selectedSeats.add(id);
    }
    const info = document.getElementById('selectedSeatInfo');
    const list = document.getElementById('selectedSeatList');
    if (info && list) {
      if (selectedSeats.size > 0) {
        info.style.display = 'block';
        list.textContent = [...selectedSeats].join(', ');
      } else {
        info.style.display = 'none';
      }
    }
  });
});

/* ── Payment method toggle ───────────────────────────── */
function selectMethod(method) {
  document.getElementById('card-bank').classList.toggle('chosen', method === 'bank');
  document.getElementById('card-ewallet').classList.toggle('chosen', method === 'ewallet');
}

/* ── Copy to clipboard ───────────────────────────────── */
function copyText(text, btn) {
  navigator.clipboard.writeText(text).then(() => {
    btn.textContent = '✅ Disalin';
    btn.classList.add('copied');
    setTimeout(() => { btn.textContent = '📋 Salin'; btn.classList.remove('copied'); }, 2000);
  }).catch(() => {
    const el = document.createElement('textarea');
    el.value = text; document.body.appendChild(el);
    el.select(); document.execCommand('copy');
    document.body.removeChild(el);
    btn.textContent = '✅ Disalin'; btn.classList.add('copied');
    setTimeout(() => { btn.textContent = '📋 Salin'; btn.classList.remove('copied'); }, 2000);
  });
}

/* ── Proof upload preview ────────────────────────────── */
function handleProofUpload(e) {
  const file = e.target.files[0];
  if (!file) return;

  const maxSize = 5 * 1024 * 1024;
  if (file.size > maxSize) {
    alert('Ukuran file maksimal 5 MB.');
    e.target.value = '';
    return;
  }

  const area     = document.getElementById('proofArea');
  const preview  = document.getElementById('proofPreview');
  const filename = document.getElementById('proofFilename');
  area.classList.add('has-file');
  filename.textContent = '✅ ' + file.name;
  filename.classList.add('show');

  if (file.type.startsWith('image/')) {
    const reader = new FileReader();
    reader.onload = ev => {
      preview.src = ev.target.result;
      preview.classList.add('show');
    };
    reader.readAsDataURL(file);
  } else {
    preview.classList.remove('show');
  }
}

/* ── Countdown timer (15 min) — only on step 4 ───────── */
(function() {
  const timerEl = document.getElementById('timerDisplay');
  const timerWrap = document.getElementById('payTimer');
  const expired   = document.getElementById('timerExpired');
  if (!timerEl) return;

  let remaining = 15 * 60; 
  const tick = setInterval(() => {
    remaining--;
    const m = String(Math.floor(remaining / 60)).padStart(2,'0');
    const s = String(remaining % 60).padStart(2,'0');
    timerEl.textContent = m + ':' + s;
    if (remaining <= 60)  timerWrap.classList.add('warn');
    if (remaining <= 0) {
      clearInterval(tick);
      timerEl.textContent = '00:00';
      expired.classList.add('show');
      const btn = document.getElementById('btnPaySubmit');
      if (btn) btn.disabled = true;
    }
  }, 1000);
})();

// Jalankan alert sukses dari database & pindah halaman otomatis
<?php if ($success): ?>
  alert("✅ <?php echo $success; ?>\n\nPembayaran sedang diverifikasi.");
  window.location.href = 'profile.php?tab=tickets';
<?php endif; ?>
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
