<?php
session_start();
$error = '';
$showHome = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($email === 'shizlafasia@gmail.com' && $password === 'eventku') {
        $showHome = true;
        $_SESSION['logged_in'] = true;
    } else {
        $error = 'Email atau password salah. Coba lagi.';
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $showHome = true;
}
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>YOUTHEVER 2026</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
      /* ── Sign In page ───────────────────────────────── */
      #login-page {
        background:
          linear-gradient(rgba(0,0,0,.6), rgba(0,0,0,.6)),
          url("image/Background.jpg");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 16px;
        box-sizing: border-box;
      }

      .auth-card {
        background: #fff;
        border-radius: 16px;
        width: 100%;
        max-width: 420px;
        padding: 40px 36px;
        color: #333;
        box-shadow: 0 24px 60px rgba(0,0,0,.5);
      }
      .auth-card .brand {
        text-align: center;
        font-size: .72rem;
        letter-spacing: .18em;
        text-transform: uppercase;
        color: #c5a059;
        margin-bottom: 6px;
        font-weight: 700;
      }
      .auth-card h1 {
        text-align: center;
        font-size: 1.8rem;
        color: #1a1a1a;
        margin: 0 0 6px;
      }
      .auth-card .sub {
        text-align: center;
        font-size: .85rem;
        color: #888;
        margin-bottom: 22px;
      }
      .auth-card .sub a { color: #5d3f5d; font-weight: 600; text-decoration: none; }
      .auth-card .sub a:hover { text-decoration: underline; }

      .social-row {
        display: flex;
        gap: 10px;
        margin-bottom: 16px;
      }
      .social-row .btn-social {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #ddd;
        font-size: .85rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: opacity .2s;
      }
      .social-row .btn-social:hover { opacity: .85; }
      .social-row .btn-social.facebook { background:#1877f2; border-color:#1877f2; color:#fff; }
      .social-row .btn-social.google   { background:#db4437; border-color:#db4437; color:#fff; }

      .auth-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 14px 0;
        color: #bbb;
        font-size: .82rem;
      }
      .auth-divider::before,
      .auth-divider::after { content:''; flex:1; height:1px; background:#e8e8e8; }

      .auth-alert {
        padding: 10px 14px;
        border-radius: 8px;
        font-size: .85rem;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
      }
      .auth-alert.error { background:#fff0f0; border:1px solid #fcc; color:#c0392b; }

      .auth-field { margin-bottom: 14px; }
      .auth-field label {
        display: block;
        font-size: .8rem;
        font-weight: 600;
        color: #555;
        margin-bottom: 5px;
      }
      .auth-field input {
        width: 100%;
        padding: 11px 14px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: .92rem;
        color: #333;
        background: #fafafa;
        transition: border-color .2s, box-shadow .2s;
        box-sizing: border-box;
      }
      .auth-field input:focus {
        outline: none;
        border-color: #c5a059;
        box-shadow: 0 0 0 3px rgba(197,160,89,.15);
        background: #fff;
      }

      .auth-forgot {
        text-align: right;
        font-size: .8rem;
        margin: -6px 0 14px;
      }
      .auth-forgot a { color: #888; text-decoration: none; }
      .auth-forgot a:hover { color: #5d3f5d; text-decoration: underline; }

      .btn-auth-submit {
        width: 100%;
        padding: 12px;
        background: #5d3f5d;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        margin-top: 4px;
        letter-spacing: .03em;
        transition: background .2s;
      }
      .btn-auth-submit:hover { background: #7a4d7a; }

      .auth-footer-link {
        text-align: center;
        margin-top: 18px;
        font-size: .83rem;
        color: #aaa;
      }
      .auth-footer-link a { color: #5d3f5d; font-weight: 600; text-decoration: none; }
      .auth-footer-link a:hover { text-decoration: underline; }
    </style>
  </head>
  <body>

    <section id="login-page" style="<?php echo $showHome ? 'display: none;' : ''; ?>">
      <div class="auth-card">
        <div class="brand">YOUTHEVER 2026</div>
        <h1>Sign In</h1>
        <p class="sub">Don't have an account? <a href="register.php">Sign up now</a></p>

        <!-- Social login -->
        <div class="social-row">
          <a href="https://www.facebook.com/login.php" class="btn-social facebook" target="_blank" rel="noopener">
            📘 Facebook
          </a>
          <a href="https://accounts.google.com/" class="btn-social google" target="_blank" rel="noopener">
            🔎 Google
          </a>
        </div>

        <div class="auth-divider">or</div>

        <?php if ($error): ?>
          <div class="auth-alert error">⚠️ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" action="index.php">
          <div class="auth-field">
            <label for="email">Email Address</label>
            <input
              id="email"
              name="email"
              type="email"
              placeholder="nama@email.com"
              value="<?php echo htmlspecialchars(isset($_POST['email']) ? $_POST['email'] : 'shizlafasia@gmail.com'); ?>"
              required
            />
          </div>
          <div class="auth-field">
            <label for="password">Password</label>
            <input
              id="password"
              name="password"
              type="password"
              placeholder="Password"
              value="<?php echo htmlspecialchars(isset($_POST['password']) ? $_POST['password'] : 'eventku'); ?>"
              required
            />
          </div>
          <p class="auth-forgot"><a href="reset-password.php">Forgot your password?</a></p>
          <button type="submit" class="btn-auth-submit">Sign In</button>
        </form>

        <div class="auth-footer-link">
          <a href="register.php">Belum punya akun? Sign up →</a>
        </div>
      </div>
    </section>

    <main id="home-page" style="<?php echo $showHome ? 'display: block;' : 'display: none;'; ?>">
      <style>
        /* ══════════════════════════════════════════════
           HOME PAGE STYLES
        ══════════════════════════════════════════════ */

        #home-page { background: #000; color: #fff; overflow-x: hidden; }

        /* ── NAV ──────────────────────────────────── */
        #home-page nav {
          position: fixed;
          top: 0; left: 0; right: 0;
          z-index: 100;
          background: rgba(0,0,0,.85);
          backdrop-filter: blur(12px);
          -webkit-backdrop-filter: blur(12px);
          border-bottom: 1px solid rgba(197,160,89,.25);
          transition: background .3s;
        }

        /* ── HERO ─────────────────────────────────── */
        .hero {
          position: relative;
          min-height: 100vh;
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          text-align: center;
          padding: 120px 24px 80px;
          overflow: hidden;
        }

        /* Background image */
        .hero::before {
          content: '';
          position: absolute;
          inset: 0;
          background:
            linear-gradient(180deg,
              rgba(0,0,0,.45) 0%,
              rgba(0,0,0,.3)  40%,
              rgba(0,0,0,.75) 100%),
            url("image/Background.jpg") center/cover no-repeat;
          z-index: 0;
        }

        /* Grain overlay */
        .hero::after {
          content: '';
          position: absolute;
          inset: 0;
          background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
          background-size: 200px;
          z-index: 0;
          pointer-events: none;
        }

        .hero > * { position: relative; z-index: 1; }

        /* Tagline pill */
        .hero .tagline {
          display: inline-flex;
          align-items: center;
          gap: 8px;
          border: 1px solid rgba(197,160,89,.5);
          background: rgba(197,160,89,.08);
          color: #c5a059;
          font-size: .72rem;
          letter-spacing: .22em;
          text-transform: uppercase;
          padding: 7px 18px;
          border-radius: 40px;
          margin-bottom: 28px;
          backdrop-filter: blur(4px);
        }
        .hero .tagline::before { content: '✦'; font-size: .6rem; }
        .hero .tagline::after  { content: '✦'; font-size: .6rem; }

        /* Main title */
        #home-page h1 {
          font-size: clamp(3.2rem, 10vw, 8rem);
          font-weight: 900;
          line-height: 1;
          letter-spacing: -.02em;
          color: #fff;
          margin: 0 0 24px;
          text-shadow: 0 4px 40px rgba(0,0,0,.6);
        }

        /* Glowing gold accent on second line */
        #home-page h1 br + * ,
        .hero-title-gold {
          color: #d4af37;
          -webkit-text-stroke: 1px rgba(197,160,89,.4);
          filter: drop-shadow(0 0 30px rgba(197,160,89,.35));
        }

        .hero p {
          font-size: clamp(.9rem, 2vw, 1.05rem);
          color: rgba(255,255,255,.7);
          max-width: 560px;
          margin: 0 auto 36px;
          line-height: 1.7;
        }

        /* CTA buttons */
        .cta-buttons {
          display: flex;
          gap: 14px;
          justify-content: center;
          flex-wrap: wrap;
          margin-bottom: 48px;
        }
        .cta-buttons .btn {
          padding: 14px 32px;
          font-size: .9rem;
          font-weight: 700;
          letter-spacing: .1em;
          border-radius: 6px;
          text-decoration: none;
          transition: all .25s;
          text-transform: uppercase;
        }
        .cta-buttons .btn:first-child {
          background: linear-gradient(135deg, #c5a059, #d4af37);
          color: #000;
          border: none;
          box-shadow: 0 0 30px rgba(197,160,89,.35);
        }
        .cta-buttons .btn:first-child:hover {
          transform: translateY(-2px);
          box-shadow: 0 0 50px rgba(197,160,89,.55);
        }
        .cta-buttons .btn:last-child {
          background: transparent;
          color: #fff;
          border: 1.5px solid rgba(255,255,255,.35);
        }
        .cta-buttons .btn:last-child:hover {
          border-color: #d4af37;
          color: #d4af37;
          transform: translateY(-2px);
        }

        /* Meta info strip */
        .meta-info {
          display: inline-flex;
          gap: 28px;
          background: rgba(255,255,255,.06);
          border: 1px solid rgba(255,255,255,.1);
          backdrop-filter: blur(8px);
          padding: 12px 28px;
          border-radius: 40px;
          font-size: .82rem;
          color: rgba(255,255,255,.65);
          letter-spacing: .04em;
          flex-wrap: wrap;
          justify-content: center;
        }

        /* Scroll indicator */
        .scroll-hint {
          position: absolute;
          bottom: 32px;
          left: 50%;
          transform: translateX(-50%);
          display: flex;
          flex-direction: column;
          align-items: center;
          gap: 6px;
          color: rgba(255,255,255,.3);
          font-size: .7rem;
          letter-spacing: .12em;
          text-transform: uppercase;
          animation: fadeFloat 2s ease-in-out infinite;
          z-index: 2;
        }
        .scroll-hint .arrow {
          width: 20px; height: 20px;
          border-right: 1.5px solid rgba(255,255,255,.3);
          border-bottom: 1.5px solid rgba(255,255,255,.3);
          transform: rotate(45deg);
        }
        @keyframes fadeFloat {
          0%,100% { opacity:.4; transform: translateX(-50%) translateY(0); }
          50%      { opacity:.9; transform: translateX(-50%) translateY(6px); }
        }

        /* ── STATS STRIP ──────────────────────────── */
        .home-stats {
          display: flex;
          justify-content: center;
          background: #0a0a0a;
          border-top: 1px solid #1a1a1a;
          border-bottom: 1px solid #1a1a1a;
        }
        .home-stat {
          flex: 1;
          max-width: 200px;
          text-align: center;
          padding: 28px 16px;
          border-right: 1px solid #1a1a1a;
          transition: background .2s;
        }
        .home-stat:last-child { border-right: none; }
        .home-stat:hover { background: #111; }
        .home-stat .sn { font-size: 2.2rem; font-weight: 800; color: #d4af37; line-height: 1; margin-bottom: 6px; }
        .home-stat .sl { font-size: .7rem; color: #555; letter-spacing: .1em; text-transform: uppercase; }

        /* ── NEWS SECTION ─────────────────────────── */
        .home-news-section {
          background: #000;
          padding: 80px 0;
        }

        .home-news-section .announcements-container {
          max-width: 960px;
          margin: 0 auto;
          padding: 0 24px;
        }

        /* Section heading */
        .home-news-section .page-header {
          text-align: center;
          margin-bottom: 36px;
        }
        .home-news-section .page-header h2 {
          font-size: 2rem;
          color: #fff;
          margin-bottom: 8px;
          position: relative;
          display: inline-block;
        }
        .home-news-section .page-header h2::after {
          content: '';
          display: block;
          width: 48px;
          height: 3px;
          background: linear-gradient(90deg, #c5a059, #d4af37);
          border-radius: 2px;
          margin: 10px auto 0;
        }
        .home-news-section .page-header p { color: #666; font-size: .9rem; }

        /* Filter pills */
        .home-news-section .filter-section {
          display: flex;
          justify-content: center;
          gap: 10px;
          flex-wrap: wrap;
          margin-bottom: 36px;
        }
        .home-news-section .filter-btn {
          padding: 8px 20px;
          border-radius: 40px;
          border: 1px solid #2a2a2a;
          background: transparent;
          color: #888;
          font-size: .82rem;
          text-decoration: none;
          transition: all .2s;
        }
        .home-news-section .filter-btn:hover,
        .home-news-section .filter-btn.active {
          background: #d4af37;
          border-color: #d4af37;
          color: #000;
          font-weight: 700;
        }

        /* Announcement cards */
        .home-news-section .announcements-list {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(420px, 1fr));
          gap: 20px;
        }
        .home-news-section .announcement-card {
          background: #0d0d0d;
          border: 1px solid #1a1a1a;
          border-radius: 12px;
          padding: 24px;
          transition: transform .2s, border-color .2s, box-shadow .2s;
        }
        .home-news-section .announcement-card:hover {
          transform: translateY(-4px);
          border-color: rgba(197,160,89,.4);
          box-shadow: 0 12px 40px rgba(0,0,0,.4);
        }
        .home-news-section .announcement-header {
          display: flex;
          gap: 16px;
          margin-bottom: 14px;
          align-items: flex-start;
        }
        .home-news-section .announcement-icon {
          background: rgba(197,160,89,.12);
          border: 1px solid rgba(197,160,89,.25);
          color: #d4af37;
          font-size: .68rem;
          font-weight: 800;
          letter-spacing: .08em;
          padding: 8px 10px;
          border-radius: 8px;
          min-width: 44px;
          text-align: center;
          line-height: 1.3;
        }
        .home-news-section .announcement-meta h3 {
          color: #fff;
          font-size: 1rem;
          margin-bottom: 8px;
          line-height: 1.3;
        }
        .home-news-section .announcement-info {
          display: flex;
          gap: 10px;
          align-items: center;
          flex-wrap: wrap;
        }
        .home-news-section .date { color: #555; font-size: .78rem; }
        .home-news-section .type-badge {
          padding: 3px 10px;
          border-radius: 20px;
          font-size: .72rem;
          font-weight: 700;
        }
        .home-news-section .type-badge.penting { background: rgba(231,76,60,.15); color: #e74c3c; border: 1px solid rgba(231,76,60,.3); }
        .home-news-section .type-badge.update  { background: rgba(243,156,18,.15); color: #f39c12; border: 1px solid rgba(243,156,18,.3); }
        .home-news-section .type-badge.info    { background: rgba(39,174,96,.15);  color: #27ae60; border: 1px solid rgba(39,174,96,.3); }
        .home-news-section .announcement-content p {
          color: #777;
          font-size: .86rem;
          line-height: 1.65;
          margin-bottom: 16px;
        }
        .home-news-section .announcement-footer { text-align: right; }
        .home-news-section .btn-read-more {
          background: transparent;
          border: 1px solid #2a2a2a;
          color: #888;
          padding: 7px 16px;
          border-radius: 6px;
          font-size: .8rem;
          text-decoration: none;
          transition: all .2s;
        }
        .home-news-section .btn-read-more:hover {
          border-color: #d4af37;
          color: #d4af37;
        }

        /* ── FOOTER ───────────────────────────────── */
        #home-page footer {
          background: #080808;
          border-top: 1px solid #1a1a1a;
          margin-top: 0;
        }

        /* ── RESPONSIVE ───────────────────────────── */
        @media (max-width: 768px) {
          .home-stats { flex-wrap: wrap; }
          .home-stat  { min-width: 50%; border-bottom: 1px solid #1a1a1a; }
          .home-news-section .announcements-list { grid-template-columns: 1fr; }
          .meta-info { gap: 14px; padding: 10px 18px; }
        }
        @media (max-width: 480px) {
          .cta-buttons .btn { padding: 12px 22px; font-size: .82rem; }
        }
      </style>

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
          <?php if ($showHome): ?>
            <a href="profile.php">Dashboard</a>
          <?php endif; ?>
          <a href="tickets.php" class="buy-btn">Buy Ticket</a>
        </div>
      </nav>

      <!-- ── HERO ──────────────────────────────────── -->
      <header class="hero">
        <p class="tagline">Global Music Experience</p>
        <h1>THE NOISE<br/><span class="hero-title-gold">AWAKENS</span></h1>
        <p>
          Experience three days of unrelenting sound, immersive art, and
          collective energy.<br />Secure your access now before the grid locks
          down.
        </p>
        <div class="cta-buttons">
          <a href="tickets.php" class="btn">BUY TICKETS</a>
          <a href="lineup.php" class="btn">VIEW LINEUP</a>
        </div>
        <div class="meta-info">
          <span>📅 OCT 24-26, 2026</span>
          <span>📍 NEON DISTRICT, ID</span>
        </div>

        <!-- Scroll hint -->
        <div class="scroll-hint">
          <span>scroll</span>
          <div class="arrow"></div>
        </div>
      </header>

      <!-- ── STATS STRIP ────────────────────────────── -->
      <div class="home-stats">
        <div class="home-stat">
          <div class="sn">3</div>
          <div class="sl">Hari Festival</div>
        </div>
        <div class="home-stat">
          <div class="sn">10+</div>
          <div class="sl">Artis Tampil</div>
        </div>
        <div class="home-stat">
          <div class="sn">15K</div>
          <div class="sl">Kapasitas</div>
        </div>
        <div class="home-stat">
          <div class="sn">2</div>
          <div class="sl">Stage Utama</div>
        </div>
        <div class="home-stat">
          <div class="sn">2026</div>
          <div class="sl">Edisi Perdana</div>
        </div>
      </div>

      <!-- ── NEWS SECTION ───────────────────────────── -->
      <section class="home-news-section">
        <div class="announcements-container">
          <div class="page-header">
            <h2>Pengumuman & Berita</h2>
            <p>Tetap update dengan semua informasi terbaru tentang YOUTHEVER 2026</p>
          </div>

          <div class="filter-section">
            <a href="announcements.php" class="filter-btn active">Semua</a>
            <a href="announcements.php" class="filter-btn">Penting</a>
            <a href="announcements.php" class="filter-btn">Update</a>
            <a href="announcements.php" class="filter-btn">Info</a>
          </div>

          <div class="announcements-list">
            <article class="announcement-card">
              <div class="announcement-header">
                <div class="announcement-icon">INFO</div>
                <div class="announcement-meta">
                  <h3>Lineup Final Telah Diumumkan!</h3>
                  <div class="announcement-info">
                    <span class="date">2 Juni 2026</span>
                    <span class="type-badge penting">Penting</span>
                  </div>
                </div>
              </div>
              <div class="announcement-content">
                <p>Semua artis utama dan special performance untuk YOUTHEVER 2026 sudah dikonfirmasi. Pengunjung disarankan mengecek halaman Line Up dan Rundown secara berkala karena beberapa slot penampilan akan memiliki sesi kolaborasi khusus yang hanya berlangsung satu kali selama festival.</p>
              </div>
              <div class="announcement-footer">
                <a href="announcements.php" class="btn-read-more">Baca Selengkapnya</a>
              </div>
            </article>

            <article class="announcement-card">
              <div class="announcement-header">
                <div class="announcement-icon">TIME</div>
                <div class="announcement-meta">
                  <h3>Penyesuaian Jadwal Stage A</h3>
                  <div class="announcement-info">
                    <span class="date">1 Juni 2026</span>
                    <span class="type-badge update">Update</span>
                  </div>
                </div>
              </div>
              <div class="announcement-content">
                <p>Jadwal Stage A untuk hari kedua mengalami penyesuaian kecil. Beberapa penampil akan naik panggung lebih awal agar alur perpindahan antar stage tetap nyaman dan tidak bertabrakan dengan sesi penutupan malam.</p>
              </div>
              <div class="announcement-footer">
                <a href="announcements.php" class="btn-read-more">Baca Selengkapnya</a>
              </div>
            </article>

            <article class="announcement-card">
              <div class="announcement-header">
                <div class="announcement-icon">TIX</div>
                <div class="announcement-meta">
                  <h3>Early Bird Tiket Regular Pass Habis!</h3>
                  <div class="announcement-info">
                    <span class="date">31 Mei 2026</span>
                    <span class="type-badge info">Info</span>
                  </div>
                </div>
              </div>
              <div class="announcement-content">
                <p>Tiket Early Bird kategori Regular Pass telah habis terjual. Tiket Regular dengan harga normal masih tersedia dalam jumlah terbatas, termasuk akses area umum, fasilitas tenant, dan seluruh panggung utama.</p>
              </div>
              <div class="announcement-footer">
                <a href="announcements.php" class="btn-read-more">Baca Selengkapnya</a>
              </div>
            </article>

            <article class="announcement-card">
              <div class="announcement-header">
                <div class="announcement-icon">GATE</div>
                <div class="announcement-meta">
                  <h3>Informasi Akses Venue dan Check-In</h3>
                  <div class="announcement-info">
                    <span class="date">28 Mei 2026</span>
                    <span class="type-badge penting">Penting</span>
                  </div>
                </div>
              </div>
              <div class="announcement-content">
                <p>Gate festival akan dibuka lebih awal untuk mengurangi antrean pada jam ramai. Pastikan QR tiket sudah siap sebelum memasuki area venue, bawa identitas yang sesuai, dan ikuti arahan kru di setiap titik pemeriksaan.</p>
              </div>
              <div class="announcement-footer">
                <a href="announcements.php" class="btn-read-more">Baca Selengkapnya</a>
              </div>
            </article>
          </div>
        </div>
      </section>

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
    </main>

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
  </body>
</html>
