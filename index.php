<?php
// ============================================
// FILE: index.php
// Halaman Beranda
// ============================================
session_start();
require_once 'includes/koneksi.php';

$page_title = 'Beranda';
$base_url = '';

// Ambil 3 berita terbaru
$berita = mysqli_query($conn, "SELECT * FROM berita ORDER BY tanggal DESC LIMIT 3");

// Hitung statistik
$jml_siswa  = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM siswa"))[0];
$jml_guru   = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM users WHERE role='guru'"))[0];
$jml_mapel  = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM mata_pelajaran"))[0];
$jml_berita = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM berita"))[0];

include 'includes/header.php';
?>

<!-- HERO SECTION -->
<section class="hero">
    <h1>SMAN 1 KENDARI</h1>
    <p>Selamat datang di portal resmi SMA Negeri 1 Kendari. Temukan informasi terkini seputar akademik, kegiatan, dan pengumuman sekolah.</p>
    <br>
    <a href="pages/berita.php" class="btn btn-emas">📰 Lihat Berita Terbaru</a>
    &nbsp;
    <a href="pages/siswa.php" class="btn" style="background:rgba(255,255,255,0.2);color:#fff">👨‍🎓 Data Siswa</a>
</section>

<!-- STATISTIK -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-num"><?= $jml_siswa ?></div>
        <div class="stat-label">👨‍🎓 Total Siswa</div>
    </div>
    <div class="stat-card">
        <div class="stat-num"><?= $jml_guru ?></div>
        <div class="stat-label">👨‍🏫 Guru & Staff</div>
    </div>
    <div class="stat-card">
        <div class="stat-num"><?= $jml_mapel ?></div>
        <div class="stat-label">📚 Mata Pelajaran</div>
    </div>
    <div class="stat-card">
        <div class="stat-num"><?= $jml_berita ?></div>
        <div class="stat-label">📰 Total Berita</div>
    </div>
</div>

<!-- BERITA TERBARU -->
<h2 class="section-title">📰 Berita & Pengumuman Terbaru</h2>
<div class="berita-grid">
<?php while ($b = mysqli_fetch_assoc($berita)): ?>
    <div class="berita-card">
        <div class="tanggal">📅 <?= date('d M Y', strtotime($b['tanggal'])) ?></div>
        <h3><?= htmlspecialchars($b['judul']) ?></h3>
        <p><?= htmlspecialchars(substr($b['isi'], 0, 150)) ?>...</p>
    </div>
<?php endwhile; ?>
</div>

<div style="text-align:center; margin-bottom:32px;">
    <a href="pages/berita.php" class="btn btn-hijau">Lihat Semua Berita →</a>
</div>

<?php include 'includes/footer.php'; ?>
