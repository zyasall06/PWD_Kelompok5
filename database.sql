-- youthreverfest_db database schema
-- Create with: phpMyAdmin -> Import, or run in MySQL CLI

CREATE DATABASE IF NOT EXISTS youthreverfest_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE youthreverfest_db;

-- =============================
-- Admin users (for profile.php + update_admin.php)
-- =============================
CREATE TABLE IF NOT EXISTS admin_users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  phone VARCHAR(40) DEFAULT NULL,
  photo VARCHAR(255) DEFAULT NULL,
  joined_date DATE DEFAULT (CURRENT_DATE),
  access_code VARCHAR(120) DEFAULT NULL,
  password_hash VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_admin_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed default admin user (id=1)
INSERT INTO admin_users (id, name, email, phone, photo, joined_date)
VALUES
  (1, 'John Doe', 'shizlafasia@gmail.com', '+62 812-3456-7890', NULL, '2026-01-15')
ON DUPLICATE KEY UPDATE
  name=VALUES(name), phone=VALUES(phone), photo=VALUES(photo), joined_date=VALUES(joined_date);

-- =============================
-- Tickets master data
-- =============================
CREATE TABLE IF NOT EXISTS ticket_categories (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(80) NOT NULL,
  price INT UNSIGNED NOT NULL,
  description TEXT DEFAULT NULL,
  stock INT UNSIGNED NOT NULL DEFAULT 0,
  features_json JSON DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_ticket_categories_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO ticket_categories (id, name, price, description, stock, features_json)
VALUES
  (1, 'Regular Pass', 300000, 'Akses ke area umum festival', 150,
    JSON_ARRAY('Area umum','Main stage','Side stage')),
  (2, 'VIP Pass', 750000, 'Akses VIP dengan fasilitas eksklusif', 50,
    JSON_ARRAY('Area VIP','Seating nyaman','Meet & greet','Free merchandise')),
  (3, 'Premium Pass', 1200000, 'Pengalaman premium dengan akses penuh', 25,
    JSON_ARRAY('Premium lounge','Parking gratis','Catering gratis','VIP merchandise'))
ON DUPLICATE KEY UPDATE
  name=VALUES(name), price=VALUES(price), description=VALUES(description), stock=VALUES(stock), features_json=VALUES(features_json);

-- =============================
-- Orders / tickets purchased by user
-- =============================
CREATE TABLE IF NOT EXISTS tickets (
  ticket_number VARCHAR(40) NOT NULL,
  user_id INT UNSIGNED NOT NULL DEFAULT 1,
  event_name VARCHAR(120) NOT NULL,
  event_date_start DATE NOT NULL,
  event_date_end DATE NOT NULL,
  category VARCHAR(80) NOT NULL,
  price INT UNSIGNED NOT NULL,
  status VARCHAR(40) NOT NULL DEFAULT 'Pending',
  seat VARCHAR(20) DEFAULT NULL,
  qr_code TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  paid_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (ticket_number),
  KEY idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed example tickets (so profile.php has data)
INSERT INTO tickets (
  ticket_number, user_id, event_name, event_date_start, event_date_end,
  category, price, status, seat, qr_code
) VALUES
  ('TKT001', 1, 'YOUTHEVER 2026', '2024-10-24', '2024-10-26', 'Regular Pass', 300000, 'Terbayar', 'A12', '█████████████'),
  ('TKT002', 1, 'YOUTHEVER 2026', '2024-10-24', '2024-10-26', 'VIP Pass', 750000, 'Terbayar', 'VIP-05', '█████████████')
ON DUPLICATE KEY UPDATE
  event_name=VALUES(event_name), event_date_start=VALUES(event_date_start), event_date_end=VALUES(event_date_end),
  category=VALUES(category), price=VALUES(price), status=VALUES(status), seat=VALUES(seat), qr_code=VALUES(qr_code);

-- =============================
-- Payments proof (optional but useful later)
-- =============================
CREATE TABLE IF NOT EXISTS payment_proofs (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  ticket_number VARCHAR(40) NOT NULL,
  user_id INT UNSIGNED NOT NULL DEFAULT 1,
  pay_method VARCHAR(30) NOT NULL,
  proof_path VARCHAR(255) DEFAULT NULL,
  proof_name VARCHAR(255) DEFAULT NULL,
  status VARCHAR(40) NOT NULL DEFAULT 'uploaded',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_ticket_number (ticket_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

