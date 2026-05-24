<?php
// ============================================
// FILE: pages/nilai.php
// Halaman Nilai Siswa
// ============================================
session_start();
require_once '../includes/koneksi.php';

$page_title = 'Nilai Siswa';
$base_url = '../';

// Ambil semua siswa untuk dropdown
$all_siswa = mysqli_query($conn, "SELECT id, nis, nama, kelas FROM siswa ORDER BY nama");

$nilai_list = null;
$siswa_dipilih = null;

// ⚠️ SENGAJA RENTAN - ID tidak divalidasi tipe datanya
if (isset($_GET['siswa_id']) && $_GET['siswa_id'] !== '') {
    $sid = $_GET['siswa_id'];
    $siswa_dipilih = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa WHERE id = $sid"));

    $nilai_list = mysqli_query($conn, "
        SELECT n.*, mp.nama_mapel, mp.kode_mapel,
               ((n.nilai_uts * 0.3) + (n.nilai_uas * 0.5) + (n.nilai_tugas * 0.2)) AS nilai_akhir
        FROM nilai n
        JOIN mata_pelajaran mp ON n.mapel_id = mp.id
        WHERE n.siswa_id = $sid
    ");
}

include '../includes/header.php';
?>

<h2 class="section-title">📊 Nilai Siswa</h2>

<div style="background:#fff; border-radius:10px; padding:24px; box-shadow:0 2px 16px rgba(0,0,0,0.08); margin-bottom:24px;">
    <form method="GET" action="" style="display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap;">
        <div style="flex:1; min-width:200px;">
            <label style="display:block; font-size:0.88rem; font-weight:600; color:#666; margin-bottom:6px;">Pilih Siswa:</label>
            <select name="siswa_id" style="width:100%; padding:10px 14px; border:2px solid #dde; border-radius:7px; font-size:0.95rem; background:#fafafa;">
                <option value="">-- Pilih Siswa --</option>
                <?php while ($s = mysqli_fetch_assoc($all_siswa)): ?>
                    <option value="<?= $s['id'] ?>" <?= (isset($_GET['siswa_id']) && $_GET['siswa_id'] == $s['id']) ? 'selected' : '' ?>>
                        <?= $s['nis'] ?> - <?= htmlspecialchars($s['nama']) ?> (<?= $s['kelas'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-hijau">Lihat Nilai</button>
    </form>
</div>

<?php if ($siswa_dipilih): ?>
    <div style="background:#fff; border-radius:10px; padding:20px; box-shadow:0 2px 16px rgba(0,0,0,0.08); margin-bottom:24px; border-left:5px solid var(--emas);">
        <h3 style="color:var(--hijau); margin-bottom:8px;">📋 <?= htmlspecialchars($siswa_dipilih['nama']) ?></h3>
        <p style="font-size:0.9rem; color:#666;">
            NIS: <strong><?= $siswa_dipilih['nis'] ?></strong> &nbsp;|&nbsp;
            Kelas: <strong><?= $siswa_dipilih['kelas'] ?></strong>
        </p>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Mata Pelajaran</th>
                    <th>Nilai UTS (30%)</th>
                    <th>Nilai UAS (50%)</th>
                    <th>Nilai Tugas (20%)</th>
                    <th>Nilai Akhir</th>
                    <th>Predikat</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $ada = false;
            while ($n = mysqli_fetch_assoc($nilai_list)):
                $ada = true;
                $na = $n['nilai_akhir'];
                if ($na >= 90) { $predikat = 'A'; $badge = 'badge-hijau'; }
                elseif ($na >= 80) { $predikat = 'B'; $badge = 'badge-emas'; }
                elseif ($na >= 70) { $predikat = 'C'; $badge = 'badge-merah'; }
                else { $predikat = 'D'; $badge = 'badge-merah'; }
            ?>
                <tr>
                    <td><strong><?= $n['kode_mapel'] ?></strong> - <?= htmlspecialchars($n['nama_mapel']) ?></td>
                    <td><?= $n['nilai_uts'] ?></td>
                    <td><?= $n['nilai_uas'] ?></td>
                    <td><?= $n['nilai_tugas'] ?></td>
                    <td><strong><?= number_format($na, 1) ?></strong></td>
                    <td><span class="badge <?= $badge ?>"><?= $predikat ?></span></td>
                </tr>
            <?php endwhile; ?>

            <?php if (!$ada): ?>
                <tr><td colspan="6" style="text-align:center; padding:30px; color:#999;">Belum ada data nilai untuk siswa ini.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php elseif (isset($_GET['siswa_id'])): ?>
    <div class="alert alert-error">Data siswa tidak ditemukan.</div>
<?php else: ?>
    <div class="alert alert-info">👆 Pilih siswa terlebih dahulu untuk melihat nilai.</div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
