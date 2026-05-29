<?php
session_start();

// Proteksi Halaman Admin: Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once '../config/koneksi.php';
// Tentukan halaman yang aktif
$page = $_GET['page'] ?? 'home';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Struktur Cahaya</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --gold: #D4AF37; --bg: #050505; --side: #0A0A0A; }
        body { margin: 0; display: flex; font-family: 'Times New Roman', serif; background: var(--bg); color: white; }
        
        /* Sidebar */
        .sidebar { width: 250px; height: 100vh; background: var(--side); border-right: 1px solid rgba(212, 175, 55, 0.2); padding: 0; position: fixed; display: flex; flex-direction: column; }
        .sidebar-header { padding: 30px 25px; border-bottom: 1px solid rgba(212, 175, 55, 0.1); margin-bottom: 10px; }
        .sidebar-header h2 { margin: 0; font-size: 1.1rem; letter-spacing: 3px; font-family: 'Playfair Display', serif; }
        
        .sidebar a { display: block; color: #aaa; padding: 18px 25px; text-decoration: none; border-bottom: 1px solid rgba(255, 255, 255, 0.02); font-size: 0.8rem; transition: 0.3s; letter-spacing: 1px; text-transform: uppercase; font-family: 'Inter', sans-serif; }
        .sidebar a:last-child { border-bottom: none; }
        .sidebar a:hover { color: var(--gold); background: rgba(212, 175, 55, 0.05); padding-left: 30px; }
        .sidebar a.active { color: var(--gold); border-left: 3px solid var(--gold); background: rgba(212, 175, 55, 0.03); }
        
        /* Main Content */
        .main { margin-left: 260px; padding: 20px; width: 100%; }
        .card { background: var(--side); padding: 20px; border-radius: 15px; border: 1px solid #333; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <h2 style="color:var(--gold)">MENU ADMIN</h2>
    </div>
    <a href="?page=home" class="<?= $page == 'home' ? 'active' : '' ?>">Dashboard Utama</a>
    <a href="?page=laporan" class="<?= $page == 'laporan' ? 'active' : '' ?>">Daftar Laporan</a>
    <a href="?page=grafik" class="<?= $page == 'grafik' ? 'active' : '' ?>">Analisis Grafik</a>
    <a href="../logout.php" style="border-top: 1px solid rgba(212, 175, 55, 0.1); color: #ff6b6b; font-weight: bold;">🚪 Logout</a>
</div>

<div class="main">
    <?php
    // Routing konten yang disesuaikan
    if ($page == 'home') include 'home.php';
    elseif ($page == 'laporan') include 'laporan.php'; // Nama file diperbaiki di sini
    elseif ($page == 'grafik') include 'grafik.php';
    elseif ($page == 'detail') include 'detail.php';
    else include 'home.php';
    ?>
</div>

</body>
</html>
