<?php
session_start();
include '../config/koneksi.php';
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
        .sidebar { width: 250px; height: 100vh; background: var(--side); border-right: 1px solid var(--gold); padding: 20px; position: fixed; }
        .sidebar a { display: block; color: #fff; padding: 15px; text-decoration: none; border-bottom: 1px solid #222; }
        .sidebar a:hover { color: var(--gold); }
        
        /* Main Content */
        .main { margin-left: 260px; padding: 20px; width: 100%; }
        .card { background: var(--side); padding: 20px; border-radius: 15px; border: 1px solid #333; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2 style="color:var(--gold)">MENU ADMIN</h2>
    <a href="?page=home">Dashboard Utama</a>
    <a href="?page=laporan">Daftar Laporan</a> <a href="?page=grafik">Analisis Grafik</a>
    <a href="../logout.php">Logout</a>
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