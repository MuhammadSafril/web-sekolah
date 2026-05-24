<?php
// ============================================
// FILE: pages/login.php
// Halaman Login Admin/Guru
// ============================================
session_start();
require_once '../includes/koneksi.php';

// Jika sudah login, redirect ke admin
if (isset($_SESSION['user_id'])) {
    header("Location: admin.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ⚠️ SENGAJA RENTAN SQL INJECTION - untuk tujuan pembelajaran keamanan data
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = MD5('$password')";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['username']  = $user['username'];
        $_SESSION['nama']      = $user['nama_lengkap'];
        $_SESSION['role']      = $user['role'];

        header("Location: admin.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}

$page_title = 'Login';
$base_url = '../';
include '../includes/header.php';
?>

<div class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <div class="icon">🔑</div>
            <h2>Login Portal</h2>
            <p>Masukkan akun Anda untuk melanjutkan</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">❌ <?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username" required autocomplete="username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-hijau btn-block">Masuk →</button>
        </form>

      
    </div>
</div>

<?php include '../includes/footer.php'; ?>
