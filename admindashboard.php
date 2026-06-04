<?php
session_start();
$adminPassword = 'admin123'; // Simulasi password admin

$isAdminLoggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
$adminError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$isAdminLoggedIn) {
    $password = isset($_POST['admin_password']) ? $_POST['admin_password'] : '';
    
    if ($password === $adminPassword) {
        $_SESSION['admin_logged_in'] = true;
        $isAdminLoggedIn = true;
    } else {
        $adminError = 'Password admin salah.';
    }
}

if (isset($_GET['logout'])) {
    unset($_SESSION['admin_logged_in']);
    header('Location: admin-dashboard.php');
    exit;
}

// Simulasi data untuk dashboard
$stats = [
    'total_tickets' => 450,
    'sold_tickets' => 280,
    'revenue' => 150000000,
    'visitors' => 3250
];

$recentOrders = [
    ['id' => 'ORD001', 'customer' => 'John Doe', 'ticket' => 'VIP Pass', 'amount' => 750000, 'status' => 'Terbayar', 'date' => '3 Juni 2026'],
    ['id' => 'ORD002', 'customer' => 'Jane Smith', 'ticket' => 'Regular Pass', 'amount' => 300000, 'status' => 'Terbayar', 'date' => '3 Juni 2026'],
    ['id' => 'ORD003', 'customer' => 'Bob Johnson', 'ticket' => 'Premium Pass', 'amount' => 1200000, 'status' => 'Pending', 'date' => '2 Juni 2026'],
];

$currentTab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - YOUTHEVER 2026</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
      .admin-login-page {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        background: linear-gradient(135deg, #000000 0%, #1a0033 100%);
      }

      .admin-login-card {
        background: #1a1a1a;
        border: 2px solid #c5a059;
        padding: 40px;
        border-radius: 8px;
        width: 100%;
        max-width: 400px;
      }

      .admin-login-card h1 {
        color: #c5a059;
        text-align: center;
        margin-bottom: 10px;
      }

      .admin-login-card p {
        text-align: center;
        color: #888;
        margin-bottom: 30px;
      }

      .admin-dashboard-container {
        display: flex;
        min-height: 100vh;
      }

      .admin-sidebar {
        width: 250px;
        background: #1a1a1a;
        border-right: 2px solid #c5a059;
        padding: 20px;
        position: fixed;
        height: 100vh;
        overflow-y: auto;
      }

      .admin-main {
        margin-left: 250px;
        flex: 1;
        padding: 40px;
      }

      .admin-nav {
        list-style: none;
        margin-top: 30px;
      }

      .admin-nav li {
        margin-bottom: 10px;
      }

      .admin-nav a {
        display: block;
        color: #fff;
        text-decoration: none;
        padding: 12px 15px;
        border-radius: 4px;
        transition: all 0.3s;
        border-left: 3px solid transparent;
      }

      .admin-nav a:hover,
      .admin-nav a.active {
        background: #2a2a2a;
        border-left-color: #d4af37;
        color: #d4af37;
      }

      .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
      }

      .stat-card {
        background: #1a1a1a;
        border: 2px solid #c5a059;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
      }

      .stat-card h3 {
        color: #888;
        font-size: 0.9rem;
        margin-bottom: 10px;
        text-transform: uppercase;
      }

      .stat-card .value {
        color: #d4af37;
        font-size: 2rem;
        font-weight: bold;
      }

      .admin-table {
        width: 100%;
        border-collapse: collapse;
        background: #1a1a1a;
        border: 2px solid #c5a059;
        margin-top: 20px;
      }

      .admin-table thead {
        background: #2a2a2a;
        border-bottom: 2px solid #c5a059;
      }

      .admin-table th,
      .admin-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #333;
      }

      .admin-table th {
        color: #d4af37;
        font-weight: bold;
      }

      .admin-table tr:hover {
        background: #252525;
      }

      .status-badge {
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 0.85rem;
        font-weight: bold;
      }

      .status-badge.terbayar {
        background: #51cf66;
        color: #000;
      }

      .status-badge.pending {
        background: #ffa500;
        color: #000;
      }

      .tab-content {
        display: none;
      }

      .tab-content.active {
        display: block;
      }

      .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
      }

      .admin-header h2 {
        color: #c5a059;
      }

      .btn-action {
        background: #5d3f5d;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
      }

      .btn-action:hover {
        background: #7a4d7a;
      }

      @media (max-width: 768px) {
        .admin-sidebar {
          width: 200px;
        }

        .admin-main {
          margin-left: 200px;
          padding: 20px;
        }

        .stats-grid {
          grid-template-columns: 1fr;
        }
      }
    </style>
  </head>
  <body>

    <!-- Admin Login Page -->
    <?php if (!$isAdminLoggedIn): ?>
      <section class="admin-login-page">
        <div class="admin-login-card">
          <h1>🔐 ADMIN PORTAL</h1>
          <p>Kelola Event YOUTHEVER 2026</p>
          <?php if ($adminError): ?>
            <p style="color: #ff6b6b; margin-bottom: 15px;"><?php echo htmlspecialchars($adminError); ?></p>
          <?php endif; ?>
          <form method="post">
            <div class="input-group">
              <label for="admin_password">Admin Password</label>
              <input
                id="admin_password"
                name="admin_password"
                type="password"
                placeholder="Masukkan password admin"
                required
              />
            </div>
            <button type="submit" class="btn-signin">Login Admin</button>
          </form>
          <p style="margin-top: 20px; font-size: 0.85rem;">
            <a href="index.php" style="color: #d4af37;">← Kembali ke Homepage</a>
          </p>
        </div>
      </section>
    <?php else: ?>
      <!-- Admin Dashboard -->
      <div class="admin-dashboard-container">
        
        <!-- Sidebar -->
        <aside class="admin-sidebar">
          <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="color: #c5a059;">ADMIN</h2>
            <p style="color: #888; font-size: 0.9rem;">YOUTHEVER 2026</p>
          </div>

          <nav class="admin-nav">
            <li><a href="admin-dashboard.php?tab=dashboard" class="<?php echo $currentTab === 'dashboard' ? 'active' : ''; ?>">📊 Dashboard</a></li>
            <li><a href="admin-dashboard.php?tab=tickets" class="<?php echo $currentTab === 'tickets' ? 'active' : ''; ?>">🎟️ Kelola Tiket</a></li>
            <li><a href="admin-dashboard.php?tab=lineup" class="<?php echo $currentTab === 'lineup' ? 'active' : ''; ?>">🎤 Kelola Lineup</a></li>
            <li><a href="admin-dashboard.php?tab=schedule" class="<?php echo $currentTab === 'schedule' ? 'active' : ''; ?>">📅 Kelola Jadwal</a></li>
            <li><a href="admin-dashboard.php?tab=announcements" class="<?php echo $currentTab === 'announcements' ? 'active' : ''; ?>">📢 Pengumuman</a></li>
            <li><a href="admin-dashboard.php?tab=orders" class="<?php echo $currentTab === 'orders' ? 'active' : ''; ?>">📦 Pesanan</a></li>
            <li><a href="admin-dashboard.php?tab=users" class="<?php echo $currentTab === 'users' ? 'active' : ''; ?>">👥 Pengguna</a></li>
            <li style="margin-top: 30px; border-top: 1px solid #333; padding-top: 20px;">
              <a href="admin-dashboard.php?logout=true">🚪 Logout</a>
            </li>
          </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">

          <!-- Dashboard Tab -->
          <div class="tab-content <?php echo $currentTab === 'dashboard' ? 'active' : ''; ?>">
            <div class="admin-header">
              <h2>📊 Dashboard</h2>
            </div>

            <div class="stats-grid">
              <div class="stat-card">
                <h3>Total Tiket</h3>
                <div class="value"><?php echo $stats['total_tickets']; ?></div>
              </div>
              <div class="stat-card">
                <h3>Tiket Terjual</h3>
                <div class="value"><?php echo $stats['sold_tickets']; ?></div>
              </div>
              <div class="stat-card">
                <h3>Total Revenue</h3>
                <div class="value">Rp<?php echo number_format($stats['revenue'] / 1000000, 0) . 'M'; ?></div>
              </div>
              <div class="stat-card">
                <h3>Pengunjung Terdaftar</h3>
                <div class="value"><?php echo $stats['visitors']; ?></div>
              </div>
            </div>

            <h3 style="color: #c5a059; margin-top: 30px;">Pesanan Terbaru</h3>
            <table class="admin-table">
              <thead>
                <tr>
                  <th>ID Pesanan</th>
                  <th>Customer</th>
                  <th>Tipe Tiket</th>
                  <th>Jumlah</th>
                  <th>Status</th>
                  <th>Tanggal</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($recentOrders as $order): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                    <td><?php echo htmlspecialchars($order['customer']); ?></td>
                    <td><?php echo htmlspecialchars($order['ticket']); ?></td>
                    <td>Rp<?php echo number_format($order['amount'], 0, ',', '.'); ?></td>
                    <td><span class="status-badge <?php echo strtolower($order['status']); ?>"><?php echo htmlspecialchars($order['status']); ?></span></td>
                    <td><?php echo htmlspecialchars($order['date']); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- Tickets Management Tab -->
          <div class="tab-content <?php echo $currentTab === 'tickets' ? 'active' : ''; ?>">
            <div class="admin-header">
              <h2>🎟️ Kelola Tiket</h2>
              <button class="btn-action">+ Tambah Tiket Baru</button>
            </div>
            <table class="admin-table">
              <thead>
                <tr>
                  <th>Kategori</th>
                  <th>Harga</th>
                  <th>Total Stok</th>
                  <th>Terjual</th>
                  <th>Sisa</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Regular Pass</td>
                  <td>Rp 300.000</td>
                  <td>200</td>
                  <td>150</td>
                  <td>50</td>
                  <td><button class="btn-action">Edit</button></td>
                </tr>
                <tr>
                  <td>VIP Pass</td>
                  <td>Rp 750.000</td>
                  <td>100</td>
                  <td>80</td>
                  <td>20</td>
                  <td><button class="btn-action">Edit</button></td>
                </tr>
                <tr>
                  <td>Premium Pass</td>
                  <td>Rp 1.200.000</td>
                  <td>50</td>
                  <td>50</td>
                  <td>0</td>
                  <td><button class="btn-action">Edit</button></td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Lineup Management Tab -->
          <div class="tab-content <?php echo $currentTab === 'lineup' ? 'active' : ''; ?>">
            <div class="admin-header">
              <h2>🎤 Kelola Lineup</h2>
              <button class="btn-action">+ Tambah Artist</button>
            </div>
            <p style="color: #888; margin: 20px 0;">Fitur manajemen lineup akan dikembangkan lebih lanjut dengan CRUD lengkap untuk artis, stage, dan jadwal performance.</p>
          </div>

          <!-- Schedule Management Tab -->
          <div class="tab-content <?php echo $currentTab === 'schedule' ? 'active' : ''; ?>">
            <div class="admin-header">
              <h2>📅 Kelola Jadwal</h2>
              <button class="btn-action">+ Tambah Jadwal</button>
            </div>
            <p style="color: #888; margin: 20px 0;">Fitur manajemen jadwal stage dan performance akan dikembangkan lebih lanjut.</p>
          </div>

          <!-- Announcements Management Tab -->
          <div class="tab-content <?php echo $currentTab === 'announcements' ? 'active' : ''; ?>">
            <div class="admin-header">
              <h2>📢 Pengumuman</h2>
              <button class="btn-action">+ Buat Pengumuman</button>
            </div>
            <p style="color: #888; margin: 20px 0;">Fitur manajemen pengumuman akan dikembangkan lebih lanjut.</p>
          </div>

          <!-- Orders Management Tab -->
          <div class="tab-content <?php echo $currentTab === 'orders' ? 'active' : ''; ?>">
            <div class="admin-header">
              <h2>📦 Pesanan</h2>
            </div>
            <table class="admin-table">
              <thead>
                <tr>
                  <th>ID Pesanan</th>
                  <th>Customer</th>
                  <th>Tiket</th>
                  <th>Jumlah</th>
                  <th>Status</th>
                  <th>Tanggal</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($recentOrders as $order): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                    <td><?php echo htmlspecialchars($order['customer']); ?></td>
                    <td><?php echo htmlspecialchars($order['ticket']); ?></td>
                    <td>Rp<?php echo number_format($order['amount'], 0, ',', '.'); ?></td>
                    <td><span class="status-badge <?php echo strtolower($order['status']); ?>"><?php echo htmlspecialchars($order['status']); ?></span></td>
                    <td><?php echo htmlspecialchars($order['date']); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- Users Management Tab -->
          <div class="tab-content <?php echo $currentTab === 'users' ? 'active' : ''; ?>">
            <div class="admin-header">
              <h2>👥 Pengguna</h2>
            </div>
            <p style="color: #888; margin: 20px 0;">Fitur manajemen pengguna akan dikembangkan lebih lanjut dengan daftar semua pengguna terdaftar.</p>
          </div>

        </main>

      </div>
    <?php endif; ?>

  </body>
</html>
