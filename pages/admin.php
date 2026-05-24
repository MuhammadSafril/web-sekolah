<?php
// ============================================
// FILE: pages/admin.php
// Halaman Panel Admin - hanya untuk yang login
// ============================================
session_start();
require_once '../includes/koneksi.php';

// Cek apakah sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=admin");
    exit;
}

$page_title = 'Panel Admin';
$base_url = '../';
$menu = isset($_GET['menu']) ? $_GET['menu'] : 'dashboard';
$pesan = '';

// ==========================================
// PROSES TAMBAH SISWA
// ==========================================
if ($menu === 'siswa' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi'])) {

    if ($_POST['aksi'] === 'tambah') {
        $nis    = $_POST['nis'];
        $nama   = $_POST['nama'];
        $kelas  = $_POST['kelas'];
        $alamat = $_POST['alamat'];
        $telp   = $_POST['no_telp'];

        // ⚠️ SENGAJA - tidak menggunakan prepared statement
        $sql = "INSERT INTO siswa (nis, nama, kelas, alamat, no_telp) VALUES ('$nis', '$nama', '$kelas', '$alamat', '$telp')";
        if (mysqli_query($conn, $sql)) {
            $pesan = '<div class="alert alert-sukses">✅ Siswa berhasil ditambahkan!</div>';
        } else {
            $pesan = '<div class="alert alert-error">❌ Gagal: ' . mysqli_error($conn) . '</div>';
        }
    }

    if ($_POST['aksi'] === 'hapus') {
        $id = $_POST['id'];
        if (mysqli_query($conn, "DELETE FROM siswa WHERE id = $id")) {
            $pesan = '<div class="alert alert-sukses">✅ Siswa berhasil dihapus.</div>';
        }
    }
}

// ==========================================
// PROSES TAMBAH BERITA
// ==========================================
if ($menu === 'berita' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi'])) {
    if ($_POST['aksi'] === 'tambah') {
        $judul    = $_POST['judul'];
        $isi      = $_POST['isi'];
        $tanggal  = $_POST['tanggal'];
        $penulis  = $_SESSION['nama'];

        $sql = "INSERT INTO berita (judul, isi, penulis, tanggal) VALUES ('$judul', '$isi', '$penulis', '$tanggal')";
        if (mysqli_query($conn, $sql)) {
            $pesan = '<div class="alert alert-sukses">✅ Berita berhasil ditambahkan!</div>';
        }
    }

    if ($_POST['aksi'] === 'hapus') {
        $id = $_POST['id'];
        mysqli_query($conn, "DELETE FROM berita WHERE id = $id");
        $pesan = '<div class="alert alert-sukses">✅ Berita berhasil dihapus.</div>';
    }
}

// Ambil data sesuai menu
$siswa_list  = mysqli_query($conn, "SELECT * FROM siswa ORDER BY kelas, nama");
$berita_list = mysqli_query($conn, "SELECT * FROM berita ORDER BY tanggal DESC");
$user_list   = mysqli_query($conn, "SELECT * FROM users ORDER BY role, nama_lengkap");

include '../includes/header.php';
?>

<div class="admin-grid">

    <!-- SIDEBAR -->
    <div class="admin-sidebar">
        <h3>⚙ Panel Admin</h3>
        <a href="admin.php?menu=dashboard"  class="<?= $menu==='dashboard' ? 'active' : '' ?>">📊 Dashboard</a>
        <a href="admin.php?menu=siswa"      class="<?= $menu==='siswa'     ? 'active' : '' ?>">👨‍🎓 Kelola Siswa</a>
        <a href="admin.php?menu=berita"     class="<?= $menu==='berita'    ? 'active' : '' ?>">📰 Kelola Berita</a>
        <a href="admin.php?menu=pengguna"   class="<?= $menu==='pengguna'  ? 'active' : '' ?>">👥 Pengguna</a>
        <a href="logout.php" style="color:#e53e3e;">🚪 Keluar</a>
    </div>

    <!-- MAIN AREA -->
    <div class="admin-main">
        <p style="font-size:0.85rem; color:#999; margin-bottom:16px;">
            Login sebagai: <strong><?= $_SESSION['nama'] ?></strong>
            <span class="badge <?= $_SESSION['role']==='admin' ? 'badge-hijau' : 'badge-emas' ?>"><?= $_SESSION['role'] ?></span>
        </p>

        <?= $pesan ?>

        <?php if ($menu === 'dashboard'): ?>
        <!-- ========== DASHBOARD ========== -->
        <h2 style="font-family:'Playfair Display',serif; color:var(--hijau); margin-bottom:20px;">Dashboard</h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:16px;">
            <?php
            $stats = [
                ['label'=>'Siswa', 'icon'=>'👨‍🎓', 'q'=>'SELECT COUNT(*) FROM siswa'],
                ['label'=>'Berita', 'icon'=>'📰', 'q'=>'SELECT COUNT(*) FROM berita'],
                ['label'=>'Mapel', 'icon'=>'📚', 'q'=>'SELECT COUNT(*) FROM mata_pelajaran'],
                ['label'=>'User', 'icon'=>'👥', 'q'=>'SELECT COUNT(*) FROM users'],
            ];
            foreach ($stats as $s):
                $n = mysqli_fetch_row(mysqli_query($conn, $s['q']))[0];
            ?>
            <div class="stat-card">
                <div class="stat-num"><?= $n ?></div>
                <div class="stat-label"><?= $s['icon'] ?> <?= $s['label'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>


        <?php elseif ($menu === 'siswa'): ?>
        <!-- ========== KELOLA SISWA ========== -->
        <h2 style="font-family:'Playfair Display',serif; color:var(--hijau); margin-bottom:20px;">Kelola Siswa</h2>

        <!-- Form Tambah Siswa -->
        <details style="margin-bottom:24px; background:#f8f9fa; border-radius:8px; padding:16px;">
            <summary style="cursor:pointer; font-weight:700; color:var(--hijau);">➕ Tambah Siswa Baru</summary>
            <form method="POST" action="" style="margin-top:16px; display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <input type="hidden" name="aksi" value="tambah">
                <div class="form-group">
                    <label>NIS</label>
                    <input type="text" name="nis" placeholder="Contoh: 2024006" required>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" placeholder="Nama siswa" required>
                </div>
                <div class="form-group">
                    <label>Kelas</label>
                    <input type="text" name="kelas" placeholder="Contoh: X-IPA-1" required>
                </div>
                <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="text" name="no_telp" placeholder="08xxxx">
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label>Alamat</label>
                    <textarea name="alamat" rows="2" placeholder="Alamat lengkap"></textarea>
                </div>
                <div style="grid-column:1/-1;">
                    <button type="submit" class="btn btn-hijau">Simpan Siswa</button>
                </div>
            </form>
        </details>

        <!-- Tabel Siswa -->
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr><th>NIS</th><th>Nama</th><th>Kelas</th><th>No. Telp</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                <?php while ($s = mysqli_fetch_assoc($siswa_list)): ?>
                    <tr>
                        <td><?= $s['nis'] ?></td>
                        <td><?= htmlspecialchars($s['nama']) ?></td>
                        <td><?= $s['kelas'] ?></td>
                        <td><?= $s['no_telp'] ?></td>
                        <td>
                            <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Yakin hapus siswa ini?')">
                                <input type="hidden" name="aksi" value="hapus">
                                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                <button type="submit" class="btn btn-merah btn-sm">🗑 Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <?php elseif ($menu === 'berita'): ?>
        <!-- ========== KELOLA BERITA ========== -->
        <h2 style="font-family:'Playfair Display',serif; color:var(--hijau); margin-bottom:20px;">Kelola Berita</h2>

        <details style="margin-bottom:24px; background:#f8f9fa; border-radius:8px; padding:16px;">
            <summary style="cursor:pointer; font-weight:700; color:var(--hijau);">➕ Tambah Berita Baru</summary>
            <form method="POST" action="" style="margin-top:16px;">
                <input type="hidden" name="aksi" value="tambah">
                <div class="form-group">
                    <label>Judul Berita</label>
                    <input type="text" name="judul" placeholder="Judul berita..." required>
                </div>
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group">
                    <label>Isi Berita</label>
                    <textarea name="isi" rows="5" placeholder="Tulis isi berita di sini..." required></textarea>
                </div>
                <button type="submit" class="btn btn-hijau">Publish Berita</button>
            </form>
        </details>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr><th>Tanggal</th><th>Judul</th><th>Penulis</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                <?php while ($b = mysqli_fetch_assoc($berita_list)): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($b['tanggal'])) ?></td>
                        <td><?= htmlspecialchars($b['judul']) ?></td>
                        <td><?= $b['penulis'] ?></td>
                        <td>
                            <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Yakin hapus berita ini?')">
                                <input type="hidden" name="aksi" value="hapus">
                                <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                <button type="submit" class="btn btn-merah btn-sm">🗑 Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <?php elseif ($menu === 'pengguna'): ?>
        <!-- ========== PENGGUNA ========== -->
        <h2 style="font-family:'Playfair Display',serif; color:var(--hijau); margin-bottom:20px;">Daftar Pengguna</h2>


        <div class="table-wrapper">
            <table>
                <thead>
                    <tr><th>Username</th><th>Nama Lengkap</th><th>Role</th><th>Password Hash</th></tr>
                </thead>
                <tbody>
                <?php while ($u = mysqli_fetch_assoc($user_list)): ?>
                    <tr>
                        <td><code><?= $u['username'] ?></code></td>
                        <td><?= htmlspecialchars($u['nama_lengkap']) ?></td>
                        <td><span class="badge <?= $u['role']==='admin' ? 'badge-hijau' : 'badge-emas' ?>"><?= $u['role'] ?></span></td>
                        <td><code style="font-size:0.75rem; color:#e53e3e;"><?= $u['password'] ?></code></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

    </div><!-- end admin-main -->
</div><!-- end admin-grid -->

<?php include '../includes/footer.php'; ?>
