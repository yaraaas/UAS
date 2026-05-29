<?php
session_start();
// Proteksi akses
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../config/koneksi.php';

// Mengambil ID dari sesi user yang login
$id_teknisi = $_SESSION['user_id']; 

// Query mengambil data laporan dan nama teknisi yang bertugas dari tabel users
$query = mysqli_query($conn, "SELECT laporan.*, users.username as nama_teknisi 
        FROM laporan 
        JOIN penugasan ON laporan.laporan_id = penugasan.laporan_id 
        JOIN users ON penugasan.teknisi_id = users.user_id
        WHERE penugasan.teknisi_id = '$id_teknisi' AND laporan.status = 'dalam_proses'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Tugas | Struktur Cahaya</title>
    <style>
        :root { --gold: #D4AF37; --bg: #050505; }
        * { box-sizing: border-box; }
        body { background: var(--bg); color: #fff; font-family: 'Inter', sans-serif; padding: 40px 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        .navbar { text-align: center; font-size: 1.2rem; letter-spacing: 4px; color: var(--gold); margin-bottom: 40px; border-bottom: 1px solid #333; padding-bottom: 20px; }
        
        .task-card { 
            background: rgba(255,255,255,0.05); padding: 25px; border-radius: 15px; 
            border: 1px solid rgba(212,175,55,0.3); margin-bottom: 20px;
        }
        .task-card p { margin-bottom: 10px; font-size: 0.95rem; }
        .task-card strong { color: var(--gold); display: block; margin-bottom: 2px; }
        
        .btn-selesai { 
            display: inline-block; margin-top: 15px; padding: 12px 25px;
            background: var(--gold); color: #000; text-decoration: none; 
            font-weight: 800; border-radius: 10px; font-size: 0.8rem; text-transform: uppercase;
            transition: 0.3s;
        }
        .btn-selesai:hover { background: #fff; }
        .no-data { text-align: center; color: #666; margin-top: 50px; font-style: italic; }
    </style>
</head>
<body>
    <div class="navbar">DAFTAR TUGAS TEKNISI</div>
    
    <div class="container">
        <?php if(mysqli_num_rows($query) > 0) { ?>
            <?php while($row = mysqli_fetch_array($query)) { ?>
            <div class="task-card">
                <p><strong>Teknisi Bertugas:</strong> <?= htmlspecialchars($row['nama_teknisi']) ?></p>
                <p><strong>Lokasi Koordinat:</strong> <?= htmlspecialchars($row['lokasi_gps']) ?></p>
                
                <a href="update_selesai.php?id=<?= $row['laporan_id'] ?>" class="btn-selesai">
                    Tandai Selesai
                </a>
            </div>
            <?php } ?>
        <?php } else { ?>
            <div class="no-data">Tidak ada tugas dalam proses saat ini.</div>
        <?php } ?>
    </div>
</body>
</html>