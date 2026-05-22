# PRD — Youthreverfest2026.com

# 1. Project Overview
**Youthreverfest2026.com** adalah portal resmi event festival skala besar yang berfungsi sebagai pusat informasi, interaksi, dan pembelian tiket untuk pengunjung Gen Z. Website dirancang mobile-first dengan pengalaman yang cepat, visual, dan interaktif agar pengguna mudah mengakses line up, jadwal panggung, peta area event, serta melakukan pembelian tiket secara seamless.

Selain sebagai pusat informasi event, website juga menyediakan sistem akun pengguna, dashboard profile, riwayat pembelian tiket, informasi tempat duduk, dan download e-ticket secara digital.

## Goals
- Menjadi pusat informasi resmi event.
- Meningkatkan konversi pembelian tiket online.
- Memberikan pengalaman browsing yang ringan dan responsif di semua device.
- Mempermudah pengguna mengelola tiket melalui dashboard pribadi.
- Menyediakan sistem ticketing digital yang praktis dan modern.

## Success Metrics
- Tingkat pembelian tiket meningkat.
- Bounce rate rendah pada halaman utama.
- Mayoritas akses mobile tetap stabil dan cepat.
- Pengunjung aktif menggunakan fitur schedule/event reminder.
- Pengguna aktif login dan mengakses dashboard ticketing.

---

# 2. User Personas

## 1. Festival Visitor (Gen Z)
- Umur 17–25 tahun.
- Aktif menggunakan smartphone dan media sosial.
- Ingin akses cepat ke line up, jadwal tampil, dan pembelian tiket.
- Membutuhkan e-ticket yang mudah diakses dan di-download.
- Lebih suka UI visual, ringan, dan mudah dibagikan.

## 2. Event Organizer/Admin
- Mengelola konten event, jadwal, banner, line up, dan pengumuman.
- Mengelola data pembelian tiket dan seat pengguna.
- Membutuhkan dashboard cepat dan mudah digunakan.
- Memerlukan pengelolaan data tiket dan pengunjung secara real-time.

## 3. Media Partner/Sponsor
- Mengakses informasi event, logo sponsor, press release, dan kontak kerja sama.
- Membutuhkan informasi resmi yang selalu update.

---

# 3. Core Features

## 1. Interactive Event Homepage
Pengguna langsung melihat highlight event, countdown, line up utama, CTA pembelian tiket, dan pengumuman terbaru tanpa harus banyak klik.

---

## 2. User Authentication & Account System
Pengguna dapat membuat akun, login, logout, dan mengelola profile pribadi untuk mengakses fitur pembelian tiket dan dashboard event.

Fitur:
- Register akun
- Login & logout
- Edit profile
- Forgot password

---

## 3. Ticket Purchasing System
Pengguna dapat memilih kategori tiket, mengisi form pembelian tiket, memilih tempat duduk (seat), melakukan checkout, menerima e-ticket, dan melihat status pembayaran secara real-time.

Fitur:
- Form data pembeli
- Pemilihan jumlah tiket
- Pemilihan seat
- Upload bukti pembayaran / payment gateway
- Generate QR Ticket

---

## 4. User Dashboard Profile

Setelah login, pengguna memiliki dashboard pribadi untuk melihat:
- Profile pengguna
- Riwayat pembelian tiket
- Status pembayaran
- Informasi seat/tempat duduk
- Download e-ticket
- Jadwal event yang disimpan

---

## 5. Dynamic Line Up & Stage Schedule
Pengguna dapat melihat jadwal artis berdasarkan hari atau stage, lalu menyimpan jadwal favorit ke personal schedule/device calendar.

Setiap line up memiliki:

- Foto artist
- Nama artist
- Jadwal tampil
- Stage performance


## 6. Interactive Event Map
Pengguna dapat melihat area panggung, tenant, toilet, gate masuk, dan area penting melalui peta interaktif yang mobile-friendly.

---

## 7. Announcement & News
Admin dapat mempublikasikan update event, perubahan jadwal, atau informasi penting yang langsung tampil di homepage.



## 8. Contact & External Links
Pengguna dapat menghubungi panitia, melihat media sosial resmi, sponsor, dan partner event dengan mudah.


## 9. Admin Dashboard
Admin dapat mengelola:
- Tiket event
- Jadwal stage
- Artist & line up
- Foto line up
- Banner homepage
- Artikel & pengumuman
- Data pembeli tiket
- Seat management
- Statistik pengunjung

Dashboard dirancang ringan dan cepat untuk pengelolaan event real-time.

# 4. Tech Stack
## Backend
- Laravel 12
- REST API / Web Route Laravel
- Authentication: Laravel Breeze / Laravel Sanctum

## Frontend
- Blade Template Engine
- Tailwind CSS 4
- Alpine.js
- Vite

## Database
- MySQL

## Dashboard Recommendation
### Filament PHP v3 (Recommended)

Alasan:
- Performa cepat
- CRUD otomatis
- UI modern
- Integrasi Laravel native
- Cocok untuk admin event management
- Mudah mengelola ticketing dan seat management

## Alternatif
- Laravel Nova
- Backpack for Laravel

## Deployment
- Nginx + Laravel Forge/VPS
- Cloudflare CDN
- Redis Cache (optional)


# 5. Data Models & Relations
## Users
Fields:

- id
- name
- email
- password
- phone
- profile_photo
- role

Relations:
- User hasMany Orders


## Events
Fields:

- id
- title
- description
- location
- start_date
- end_date
- banner

Relations:
- Event hasMany Stages
- Event hasMany Announcements
- Event hasMany Tickets


## Artists / Lineups
Fields:
- id
- name
- image
- bio
- performance_time

Relations:
- Artist belongsToMany Stages


## Stages
Fields:
- id
- event_id
- name
- schedule_time

Relations:
- Stage belongsTo Event
- Stage belongsToMany Artists


## Tickets
Fields:

- id
- event_id
- category
- price
- stock

Relations:
- Ticket belongsTo Event
- Ticket hasMany Orders


## Seats
Fields:

- id
- ticket_id
- seat_number
- status

Relations:
- Seat belongsTo Ticket


## Orders
Fields:

- id
- user_id
- ticket_id
- seat_id
- quantity
- payment_status
- qr_code
- ticket_file

Relations:
- Order belongsTo User
- Order belongsTo Ticket
- Order belongsTo Seat

---

## Announcements
Fields:

- id
- event_id
- title
- content
- published_at

Relations:
- Announcement belongsTo Event


# 6. User Flows

## A. Registrasi & Login
Homepage → Register → Verifikasi akun → Login → Masuk ke Dashboard Profile

## B. Pembelian Tiket
Homepage → Pilih Tiket → Isi Form Pembelian → Pilih Seat → Checkout → Pembayaran → E-ticket dikirim → Tiket muncul di Dashboard

## C. Download Tiket
Dashboard → My Tickets → Lihat Detail Ticket → Lihat Seat → Download E-ticket / QR Ticket

## D. Melihat Jadwal Line Up
Homepage → Schedule Page → Filter Hari/Stage → Pilih Artist → Simpan ke Calendar pribadi

## E. Melihat Informasi Event
Homepage → Announcement/News → Baca Detail → Klik Map/Kontak/Sosial Media

# 7. Out of Scope
- Live streaming event
- Marketplace merchandise
- Sistem refund otomatis
- Fitur chat antar pengguna
- Multi-event management complex system
- Mobile native app (Android/iOS)
- Gamification & NFT integration
