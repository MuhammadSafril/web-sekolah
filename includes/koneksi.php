<?php
// ============================================
// FILE: includes/koneksi.php
// Konfigurasi koneksi ke database MySQL
// ============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Default Laragon: root
define('DB_PASS', '');           // Default Laragon: kosong
define('DB_NAME', 'db_sekolah');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("<div style='color:red; padding:20px; font-family:sans-serif;'>
        <h3>❌ Koneksi Database Gagal!</h3>
        <p>Error: " . mysqli_connect_error() . "</p>
        <p>Pastikan Laragon sudah aktif dan database <b>db_sekolah</b> sudah dibuat.</p>
    </div>");
}

mysqli_set_charset($conn, "utf8");
?>
