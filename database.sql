-- ============================================
-- DATABASE: db_sekolah
-- Jalankan file ini di phpMyAdmin Laragon
-- ============================================

CREATE DATABASE IF NOT EXISTS db_sekolah;
USE db_sekolah;

-- Tabel pengguna (admin & guru)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin', 'guru') DEFAULT 'guru',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel siswa
CREATE TABLE IF NOT EXISTS siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nis VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    kelas VARCHAR(10) NOT NULL,
    alamat TEXT,
    no_telp VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel mata pelajaran
CREATE TABLE IF NOT EXISTS mata_pelajaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_mapel VARCHAR(10) NOT NULL UNIQUE,
    nama_mapel VARCHAR(100) NOT NULL,
    guru_pengampu VARCHAR(100)
);

-- Tabel nilai
CREATE TABLE IF NOT EXISTS nilai (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT NOT NULL,
    mapel_id INT NOT NULL,
    nilai_uts DECIMAL(5,2) DEFAULT 0,
    nilai_uas DECIMAL(5,2) DEFAULT 0,
    nilai_tugas DECIMAL(5,2) DEFAULT 0,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    FOREIGN KEY (mapel_id) REFERENCES mata_pelajaran(id) ON DELETE CASCADE
);

-- Tabel berita/pengumuman
CREATE TABLE IF NOT EXISTS berita (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    isi TEXT NOT NULL,
    penulis VARCHAR(100),
    tanggal DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- DATA AWAL (SEED)
-- ============================================

-- Admin default: username=admin, password=admin123
INSERT INTO users (username, password, nama_lengkap, role) VALUES
('admin', MD5('admin123'), 'Administrator', 'admin'),
('guru1', MD5('guru123'), 'Budi Santoso', 'guru');

-- Data siswa contoh
INSERT INTO siswa (nis, nama, kelas, alamat, no_telp) VALUES
('2024001', 'Ahmad Fauzi', 'X-IPA-1', 'Jl. Merdeka No.1 Kendari', '081234567890'),
('2024002', 'Siti Rahayu', 'X-IPA-1', 'Jl. Sudirman No.5 Kendari', '081234567891'),
('2024003', 'Budi Prasetyo', 'X-IPS-2', 'Jl. Veteran No.12 Kendari', '081234567892'),
('2024004', 'Dewi Lestari', 'XI-IPA-1', 'Jl. Diponegoro No.8 Kendari', '081234567893'),
('2024005', 'Rizki Ramadan', 'XI-IPS-1', 'Jl. Hasanuddin No.3 Kendari', '081234567894');

-- Data mata pelajaran
INSERT INTO mata_pelajaran (kode_mapel, nama_mapel, guru_pengampu) VALUES
('MTK', 'Matematika', 'Budi Santoso'),
('BIN', 'Bahasa Indonesia', 'Sari Dewi'),
('FIS', 'Fisika', 'Ahmad Yusuf'),
('KIM', 'Kimia', 'Rina Wulandari'),
('BIG', 'Bahasa Inggris', 'Doni Pratama');

-- Data nilai contoh
INSERT INTO nilai (siswa_id, mapel_id, nilai_uts, nilai_uas, nilai_tugas) VALUES
(1, 1, 80, 85, 90),
(1, 2, 75, 80, 85),
(2, 1, 90, 88, 92),
(2, 2, 82, 79, 88),
(3, 3, 70, 75, 80);

-- Data berita
INSERT INTO berita (judul, isi, penulis, tanggal) VALUES
('Selamat Datang di Website SMAN 1 Kendari', 
 'Kami dengan bangga mempersembahkan website resmi SMAN 1 Kendari. Website ini merupakan portal informasi untuk seluruh siswa, guru, dan orang tua.', 
 'Admin', '2026-01-15'),
('Pengumuman Ujian Tengah Semester', 
 'Ujian Tengah Semester (UTS) akan dilaksanakan pada tanggal 15-20 Maret 2024. Seluruh siswa wajib hadir tepat waktu. Jadwal lengkap dapat diambil di bagian tata usaha.', 
 'Admin', '2026-02-01'),
('Kegiatan Ekstrakurikuler Semester Genap', 
 'Pendaftaran ekstrakurikuler semester genap telah dibuka. Tersedia pilihan: Pramuka, PMR, OSIS, Basket, Futsal, dan Seni Tari. Segera daftarkan diri kalian!', 
 'Guru BK', '2026-02-10');
