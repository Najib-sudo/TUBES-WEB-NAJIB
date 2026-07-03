-- Database SQL for Sistem Pencatatan Setoran Hafalan Al-Qur'an
-- Target Database: db_setoran_hafalan

CREATE DATABASE IF NOT EXISTS db_setoran_hafalan;
USE db_setoran_hafalan;

-- 1. Table users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('ustad', 'mahasiswa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Table mahasiswa
CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    prodi VARCHAR(100) NOT NULL,
    angkatan INT NOT NULL,
    id_user INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Table surat
CREATE TABLE IF NOT EXISTS surat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_surat VARCHAR(100) NOT NULL,
    jumlah_ayat INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Table setoran
CREATE TABLE IF NOT EXISTS setoran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_mahasiswa INT NOT NULL,
    id_surat INT NOT NULL,
    ayat_awal INT NOT NULL,
    ayat_akhir INT NOT NULL,
    tanggal DATE NOT NULL,
    status ENUM('Lulus', 'Mengulang', 'Belum Dinilai') NOT NULL DEFAULT 'Belum Dinilai',
    catatan TEXT NULL,
    id_ustad INT DEFAULT NULL,
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id) ON DELETE CASCADE,
    FOREIGN KEY (id_surat) REFERENCES surat(id) ON DELETE CASCADE,
    FOREIGN KEY (id_ustad) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Data Users
-- Ustad password  : ustad123
-- Mahasiswa password: mhs123
INSERT INTO users (id, nama, username, password, role) VALUES
(1, 'Ustad Ahmad Fauzi, M.Ag', 'ustad', '$2y$12$9PDWf5VNZNDt0Xw7su4tI.GuIul4sXk/g6bz.nkR0vMFUQOHyiVQK', 'ustad'),
(2, 'Muhammad Najib', 'najib', '$2y$12$WF/RYkttHNhi/.MSk6HFf.6oVvLHo4/up6a6oDriSSeuqQIkoBXL.', 'mahasiswa'),
(3, 'Ahmad Shiddiq', 'shiddiq', '$2y$12$WF/RYkttHNhi/.MSk6HFf.6oVvLHo4/up6a6oDriSSeuqQIkoBXL.', 'mahasiswa')
ON DUPLICATE KEY UPDATE id=id;

-- Seed Data Mahasiswa
INSERT INTO mahasiswa (id, nim, nama, prodi, angkatan, id_user) VALUES
(1, '123210001', 'Muhammad Najib', 'Informatika', 2023, 2),
(2, '123210002', 'Ahmad Shiddiq', 'Sistem Informasi', 2023, 3)
ON DUPLICATE KEY UPDATE id=id;

-- Seed Data Surat (Juz 30)
INSERT INTO surat (id, nama_surat, jumlah_ayat) VALUES
(1, 'An-Naba\'', 40),
(2, 'An-Nazi\'at', 46),
(3, '\'Abasa', 42),
(4, 'At-Takwir', 29),
(5, 'Al-Infitar', 19),
(6, 'Al-Mutaffifin', 36),
(7, 'Al-Inshiqaq', 25),
(8, 'Al-Buruj', 22),
(9, 'At-Tariq', 17),
(10, 'Al-A\'la', 19),
(11, 'Al-Ghashiyah', 26),
(12, 'Al-Fajr', 30),
(13, 'Al-Balad', 20),
(14, 'Ash-Shams', 15),
(15, 'Al-Lail', 21),
(16, 'Ad-Duha', 11),
(17, 'Ash-Sharh', 8),
(18, 'At-Tin', 8),
(19, 'Al-\'Alaq', 19),
(20, 'Al-Qadr', 5),
(21, 'Al-Bayyinah', 8),
(22, 'Al-Zalzalah', 8),
(23, 'Al-\'Adiyat', 11),
(24, 'Al-Qari\'ah', 11),
(25, 'At-Takathur', 8),
(26, 'Al-\'Asr', 3),
(27, 'Al-Humazah', 9),
(28, 'Al-Fil', 5),
(29, 'Quraish', 4),
(30, 'Al-Ma\'un', 7),
(31, 'Al-Kautsar', 3),
(32, 'Al-Kafirun', 6),
(33, 'An-Nasr', 3),
(34, 'Al-Lahab', 5),
(35, 'Al-Ikhlas', 4),
(36, 'Al-Falaq', 5),
(37, 'An-Nas', 6)
ON DUPLICATE KEY UPDATE id=id;

-- Seed Data Setoran Hafalan
INSERT INTO setoran (id, id_mahasiswa, id_surat, ayat_awal, ayat_akhir, tanggal, status, catatan, id_ustad) VALUES
(1, 1, 37, 1, 6, '2026-07-01', 'Lulus', 'Hafalan sangat lancar, tajwid bagus.', 1),
(2, 1, 36, 1, 5, '2026-07-02', 'Lulus', 'Makhraj huruf sudah baik, pertahankan.', 1),
(3, 2, 37, 1, 6, '2026-07-02', 'Mengulang', 'Perbaiki pelafalan ayat 3-4.', 1),
(4, 2, 37, 1, 6, '2026-07-03', 'Lulus', 'Sudah lancar sekarang.', 1)
ON DUPLICATE KEY UPDATE id=id;
