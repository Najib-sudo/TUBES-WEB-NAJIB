# 📖 Sistem Pencatatan Setoran Hafalan Al-Qur'an

Aplikasi web untuk mencatat dan memantau setoran hafalan Al-Qur'an mahasiswa secara digital, dibangun menggunakan PHP native dengan desain premium glassmorphism.

---

## 🚀 Progres 1

### ✅ Fitur yang Sudah Selesai

#### 🔐 Autentikasi
- [x] Halaman Login dengan animasi typing effect
- [x] Session management (login & logout)
- [x] Role-based access: **Ustad** dan **Mahasiswa**

#### 👨‍🏫 Panel Ustad (Admin)
- [x] **Dashboard** — statistik mahasiswa, total setoran, setoran hari ini, dan total surat; daftar mahasiswa teraktif & setoran terbaru
- [x] **Manajemen Mahasiswa** — tambah, edit, hapus data mahasiswa (CRUD)
- [x] **Manajemen Surat** — tambah, edit, hapus daftar surat Al-Qur'an beserta jumlah ayat (CRUD)
- [x] **Catatan Setoran** — catat, edit, hapus setoran hafalan mahasiswa; filter berdasarkan mahasiswa

#### 👨‍🎓 Panel Mahasiswa
- [x] **Dashboard** — ringkasan progress hafalan pribadi
- [x] **Riwayat Setoran** — lihat seluruh riwayat setoran milik sendiri
- [x] **Profil** — ubah nama, username, dan password

#### 🎨 UI/UX
- [x] Desain premium **glassmorphism** dengan palet warna hijau islami
- [x] Sidebar navigasi responsif dengan efek collapse
- [x] Animasi fade-in, counter dashboard, ripple button, dan typing effect
- [x] Notifikasi toast custom (sukses / error / info)
- [x] Tabel dengan fitur pencarian client-side

#### 🐛 Bug Fix
- [x] Memperbaiki modal Tambah Surat & Edit Surat yang tidak muncul di tengah layar
  - Root cause: `body` memiliki `transform` dari animasi `.fade-in` yang membuat `position: fixed` (Bootstrap modal) menjadi relatif ke `body`, bukan viewport
  - Fix: Override CSS `body.fade-in { transform: none !important }` + skip set transform pada `body` di `initFadeIn()`

---

## 🛠️ Teknologi

| Teknologi | Keterangan |
|-----------|-----------|
| PHP 8.x (Native) | Backend & routing |
| MySQL / MariaDB | Database |
| Bootstrap 5.3 | UI framework |
| Bootstrap Icons | Icon library |
| Vanilla JavaScript | Interaktivitas UI |
| CSS Glassmorphism | Custom styling |
| Google Fonts (Outfit) | Tipografi |

---

## 📁 Struktur Direktori

```
TUBES NAJIB/
├── admin/              # View untuk role Ustad
│   ├── dashboard.php
│   ├── mahasiswa.php
│   ├── setoran.php
│   └── surat.php
├── mahasiswa/          # View untuk role Mahasiswa
│   ├── dashboard.php
│   ├── profil.php
│   └── riwayat.php
├── assets/
│   ├── css/style.css   # Custom glassmorphism styles
│   ├── js/main.js      # Vanilla JS utilities
│   └── images/
├── config/
│   └── database.php    # Koneksi PDO
├── controllers/        # Logic CRUD
├── includes/           # Layout (header, navbar, sidebar, footer)
├── models/             # Model database
├── index.php           # Router utama
├── login.php           # Halaman login
├── logout.php
└── database.sql        # Schema + seed data
```

---

## ⚙️ Cara Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/Najib-sudo/TUBES-WEB-NAJIB.git
   ```

2. **Import database**
   - Buka phpMyAdmin atau MySQL client
   - Import file `database.sql`

3. **Konfigurasi database** *(jika perlu)*
   - Edit `config/database.php` sesuaikan `DB_HOST`, `DB_USER`, `DB_PASS`

4. **Jalankan server**
   ```bash
   php -S localhost:3000
   ```

5. **Akses aplikasi** di browser: `http://localhost:3000`

---

## 🔑 Akun Default

| Role | Username | Password |
|------|----------|----------|
| Ustad | `ustad` | `ustad123` |
| Mahasiswa | `najib` | `mhs123` |
| Mahasiswa | `shiddiq` | `mhs123` |

---

## 👨‍💻 Developer

**Muhammad Najib** — Tugas Besar Pemrograman Web
