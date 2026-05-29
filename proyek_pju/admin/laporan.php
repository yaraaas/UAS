<?php
// File ini disertakan dalam dashboard.php, 
// sehingga session_start() dan koneksi database sudah ditangani di file master tersebut.
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="60"> <title>Daftar Laporan | Admin Struktur Cahaya</title>
    <style>
        :root { --gold: #D4AF37; --bg: #050505; }
        body { background: var(--bg); color: #fff; font-family: 'Inter', sans-serif; padding: 20px; }
        .card { background: rgba(255,255,255,0.03); padding: 30px; border-radius: 20px; border: 1px solid #333; }
        
        .report-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .report-table th { color: var(--gold); text-align: left; padding: 15px; border-bottom: 2px solid #333; }
        .report-table td { padding: 15px; border-bottom: 1px solid #222; }
        
        .status-badge { padding: 5px 12px; border-radius: 10px; font-size: 0.75rem; font-weight: bold; color: #000; text-transform: uppercase; }
        .btn-detail { color: var(--gold); text-decoration: none; font-weight: bold; font-size: 0.85rem; }
        .wa-link { text-decoration: none; font-size: 1.2rem; }
    </style>
</head>
<body>

<div class="card">
    <h3 style="color: var(--gold); margin-bottom: 20px;">Daftar Laporan Masuk</h3>
    <div style="overflow-x: auto;">
        <table class="report-table">
            <thead>
                <tr>
                    <th>Info Pelapor</th>
                    <th>Lokasi</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Kontak</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $query = mysqli_query($conn, "SELECT * FROM laporan ORDER BY created_at DESC");
            if (mysqli_num_rows($query) > 0) {
                while($d = mysqli_fetch_assoc($query)){ 
                    // Logika warna status
                    $status = strtolower($d['status'] ?? 'pending');
                    $bg_color = ($status == 'selesai') ? '#98FB98' : (($status == 'dalam_proses') ? '#FFD700' : '#FF6B6B');
            ?>
                <tr>
                    <td>
                        <div style="font-weight:bold;"><?= htmlspecialchars($d['nama_pelapor'] ?? 'Anonim') ?></div>
                        <div style="font-size: 0.7rem; color: #888;"><?= htmlspecialchars($d['no_telp'] ?? '-') ?></div>
                    </td>
                    <td>
                        <div style="color: var(--gold); font-weight:bold;"><?= htmlspecialchars($d['judul'] ?? 'Tanpa Judul') ?></div>
                        <div style="font-size: 0.75rem; color: #aaa;"><?= htmlspecialchars($d['lokasi_gps'] ?? '-') ?></div>
                    </td>
                    <td style="text-align: center;">
                        <span class="status-badge" style="background: <?= $bg_color ?>;">
                            <?= strtoupper(str_replace('_', ' ', $d['status'])) ?>
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <a href="https://wa.me/<?= preg_replace('/^0/', '62', $d['no_telp']) ?>" target="_blank" class="wa-link">💬</a>
                    </td>
                    <td style="text-align: center;">
                        <a href="?page=detail&id=<?= $d['laporan_id'] ?>" class="btn-detail">DETAIL</a>
                    </td>
                </tr>
            <?php 
                } 
            } else {
                echo "<tr><td colspan='5' style='text-align:center; padding:20px; color:#666;'>Belum ada data laporan yang masuk.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
