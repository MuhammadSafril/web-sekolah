<?php
// ============================================
// FILE: pages/berita.php
// Halaman Daftar Berita
// ============================================
session_start();
require_once '../includes/koneksi.php';

$page_title = 'Berita & Pengumuman';
$base_url = '../';

// ⚠️ SENGAJA RENTAN - Tidak ada validasi input pencarian (XSS)
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';

if ($cari) {
    $sql = "SELECT * FROM berita WHERE judul LIKE '%$cari%' OR isi LIKE '%$cari%' ORDER BY tanggal DESC";
} else {
    $sql = "SELECT * FROM berita ORDER BY tanggal DESC";
}

$berita_list = mysqli_query($conn, $sql);

include '../includes/header.php';
?>

<h2 class="section-title">📰 Berita & Pengumuman</h2>

<!-- Form pencarian - sengaja tidak di-sanitasi untuk demo XSS -->
<form method="GET" action="" style="margin-bottom:24px;">
    <div class="search-bar">
        <input type="text" name="cari" value="<?= $cari ?>" placeholder="🔍 Cari berita...">
        <button type="submit" class="btn btn-hijau">Cari</button>
        <?php if ($cari): ?>
            <a href="berita.php" class="btn" style="background:#eee;color:#333;">Reset</a>
        <?php endif; ?>
    </div>
</form>

<?php if ($cari): ?>
    <div class="alert alert-info">Hasil pencarian untuk: <strong><?= $cari ?></strong></div>
<?php endif; ?>

<div class="berita-grid">
<?php
$ada = false;
while ($b = mysqli_fetch_assoc($berita_list)):
    $ada = true;
?>
    <div class="berita-card">
        <div class="tanggal">📅 <?= date('d F Y', strtotime($b['tanggal'])) ?></div>
        <h3><?= $b['judul'] ?></h3>
        <p style="color:#666; font-size:0.82rem; margin-bottom:8px;">✍️ <?= $b['penulis'] ?></p>
        <p><?= nl2br(substr($b['isi'], 0, 200)) ?>...</p>
    </div>
<?php endwhile; ?>

<?php if (!$ada): ?>
    <div style="grid-column:1/-1; text-align:center; padding:40px; color:#999;">
        <p style="font-size:2rem;">📭</p>
        <p>Tidak ada berita ditemukan.</p>
    </div>
<?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
