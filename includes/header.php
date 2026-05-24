<?php
// ============================================
// FILE: includes/header.php
// ============================================
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - SMAN 1 Kendari' : 'SMAN 1 Kendari' ?></title>
    <link rel="stylesheet" href="<?= $base_url ?? '' ?>css/style.css">
</head>
<body>

<header class="site-header">
    <div class="header-inner">
        <div class="logo-area">
            <div class="logo-icon">🏫</div>
            <div>
                <div class="logo-title">SMAN 1 KENDARI</div>
                <div class="logo-sub">Sekolah Menengah Atas Negeri</div>
            </div>
        </div>
        <nav class="main-nav">
            <a href="<?= $base_url ?? '' ?>index.php">Beranda</a>
            <a href="<?= $base_url ?? '' ?>pages/berita.php">Berita</a>
            <a href="<?= $base_url ?? '' ?>pages/siswa.php">Data Siswa</a>
            <a href="<?= $base_url ?? '' ?>pages/nilai.php">Nilai</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= $base_url ?? '' ?>pages/admin.php" class="nav-admin">⚙ Admin</a>
                <a href="<?= $base_url ?? '' ?>pages/logout.php" class="nav-logout">Keluar</a>
            <?php else: ?>
                <a href="<?= $base_url ?? '' ?>pages/login.php" class="nav-login">🔑 Login</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="main-content">
