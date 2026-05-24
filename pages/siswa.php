<?php
// ============================================
// FILE: pages/siswa.php
// Halaman Data Siswa (publik, bisa dilihat semua)
// ============================================
session_start();
require_once '../includes/koneksi.php';

$page_title = 'Data Siswa';
$base_url = '../';

// ⚠️ SENGAJA RENTAN - parameter kelas tidak divalidasi (SQL Injection)
$kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';

if ($kelas) {
    $sql = "SELECT * FROM siswa WHERE kelas = '$kelas' ORDER BY nama ASC";
} else {
    $sql = "SELECT * FROM siswa ORDER BY kelas, nama ASC";
}

$siswa_list = mysqli_query($conn, $sql);

// Ambil daftar kelas unik untuk filter
$kelas_list = mysqli_query($conn, "SELECT DISTINCT kelas FROM siswa ORDER BY kelas");

include '../includes/header.php';
?>

<h2 class="section-title">👨‍🎓 Data Siswa</h2>

<!-- Filter Kelas -->
<div style="margin-bottom:20px; display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
    <span style="font-size:0.9rem; font-weight:600; color:#666;">Filter Kelas:</span>
    <a href="siswa.php" class="btn btn-sm <?= !$kelas ? 'btn-hijau' : '' ?>" style="<?= !$kelas ? '' : 'background:#eee;color:#333;' ?>">Semua</a>
    <?php
    mysqli_data_seek($kelas_list, 0);
    while ($k = mysqli_fetch_assoc($kelas_list)):
    ?>
        <a href="siswa.php?kelas=<?= urlencode($k['kelas']) ?>"
           class="btn btn-sm <?= $kelas === $k['kelas'] ? 'btn-hijau' : '' ?>"
           style="<?= $kelas === $k['kelas'] ? '' : 'background:#eee;color:#333;' ?>">
           <?= $k['kelas'] ?>
        </a>
    <?php endwhile; ?>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Alamat</th>
                <th>No. Telp</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        $ada = false;
        while ($s = mysqli_fetch_assoc($siswa_list)):
            $ada = true;
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><code><?= $s['nis'] ?></code></td>
                <td><strong><?= htmlspecialchars($s['nama']) ?></strong></td>
                <td><span class="badge badge-hijau"><?= $s['kelas'] ?></span></td>
                <td><?= htmlspecialchars($s['alamat']) ?></td>
                <td><?= $s['no_telp'] ?></td>
            </tr>
        <?php endwhile; ?>

        <?php if (!$ada): ?>
            <tr><td colspan="6" style="text-align:center; padding:30px; color:#999;">Tidak ada data siswa.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
