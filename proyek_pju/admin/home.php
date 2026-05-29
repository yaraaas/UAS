<?php
// Mengambil data statistik untuk dashboard
function get_total_count($conn, $status = null) {
    $sql = "SELECT COUNT(*) as total FROM laporan";
    if ($status) {
        $status = mysqli_real_escape_string($conn, $status);
        $sql .= " WHERE status='$status'";
    }
    $res = mysqli_query($conn, $sql);
    if ($res) {
        $data = mysqli_fetch_assoc($res);
        return (int)$data['total'];
    }
    return 0;
}

$total_laporan = get_total_count($conn);
$pending       = get_total_count($conn, 'pending');
$proses        = get_total_count($conn, 'dalam_proses');
$selesai       = get_total_count($conn, 'selesai');

// Hitung persentase penyelesaian
$rate = $total_laporan > 0 ? round(($selesai / $total_laporan) * 100) : 0;
?>

<style>
    .welcome-header { margin-bottom: 25px; }
    .welcome-header h2 { font-family: 'Playfair Display', serif; color: var(--gold); font-size: 2rem; margin: 0; }
    .welcome-header p { color: #888; margin-top: 5px; line-height: 1.5; font-size: 0.9rem; }

    .stats-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
        gap: 20px; 
        margin-bottom: 30px;
    }
    
    .stat-card { 
        background: rgba(255,255,255,0.03); 
        padding: 25px; 
        border-radius: 15px; 
        border: 1px solid #222; 
        transition: 0.3s;
    }
    
    .stat-card:hover { border-color: var(--gold); transform: translateY(-5px); }
    .stat-card .label { color: #888; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; }
    .stat-card .value { color: #fff; font-size: 2.2rem; font-weight: bold; margin-top: 5px; font-family: 'Playfair Display', serif; }
    .stat-card.highlight .value { color: var(--gold); }

    .recent-section { background: rgba(255,255,255,0.02); padding: 25px; border-radius: 20px; border: 1px solid #222; }
    .recent-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .recent-header h4 { color: var(--gold); margin: 0; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem; }
    .view-all { color: #555; text-decoration: none; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; }
    .view-all:hover { color: var(--gold); }
    
    .recent-item { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        padding: 15px 0; 
        border-bottom: 1px solid #222;
    }
    .recent-item:last-child { border-bottom: none; }
    .recent-info { display: flex; flex-direction: column; gap: 4px; }
    .recent-info h5 { margin: 0; color: #eee; font-size: 0.95rem; line-height: 1.3; }
    .recent-info span { color: #888; font-size: 0.75rem; line-height: 1.3; }
    
    .meta-tag { display: flex; gap: 10px; align-items: center; margin-top: 2px; }
    .tag-id { color: var(--gold); font-weight: bold; font-size: 0.65rem; }
    .tag-time { color: #555; font-size: 0.65rem; }
    
    .status-pill { 
        font-size: 0.65rem; 
        padding: 4px 10px; 
        border-radius: 20px; 
        font-weight: bold; 
        text-transform: uppercase;
    }
    .status-pending { background: rgba(255, 107, 107, 0.1); color: #FF6B6B; border: 1px solid #FF6B6B; }
    .status-proses { background: rgba(212, 175, 55, 0.1); color: var(--gold); border: 1px solid var(--gold); }
    .status-selesai { background: rgba(152, 251, 152, 0.1); color: #98FB98; border: 1px solid #98FB98; }

    .quick-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 30px; }
    .action-btn { 
        background: rgba(255,255,255,0.02); border: 1px solid #333; color: #fff; padding: 15px; 
        border-radius: 12px; text-decoration: none; text-align: center; font-size: 0.75rem;
        transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 10px;
        text-transform: uppercase; letter-spacing: 1px;
    }
    .action-btn:hover { border-color: var(--gold); background: rgba(212, 175, 55, 0.05); color: var(--gold); transform: translateY(-2px); }
</style>
<div class="welcome-header">
    <h2>Panel Kendali Admin</h2>
    <p>Tingkat penyelesaian saat ini: <span style="color: var(--gold); font-weight: bold;"><?= $rate ?>%</span> dari seluruh laporan masyarakat.</p>
</div>

<div class="quick-actions">
    <a href="?page=laporan" class="action-btn">📂 Kelola Laporan</a>
    <a href="?page=grafik" class="action-btn">📊 Analisis Data</a>
</div>

<div class="stats-grid">
    <div class="stat-card highlight">
        <div class="label">Total Laporan</div>
        <div class="value"><?= $total_laporan ?></div>
    </div>
    <div class="stat-card">
        <div class="label">Menunggu (Pending)</div>
        <div class="value"><?= $pending ?></div>
    </div>
    <div class="stat-card">
        <div class="label">Dalam Perbaikan</div>
        <div class="value"><?= $proses ?></div>
    </div>
    <div class="stat-card">
        <div class="label">Selesai</div>
        <div class="value"><?= $selesai ?></div>
    </div>
</div>

<div class="recent-section">
    <div class="recent-header">
        <h4>Laporan Terbaru</h4>
        <a href="?page=laporan" class="view-all">Lihat Semua &rarr;</a>
    </div>
    <?php 
    $recent_query = mysqli_query($conn, "SELECT * FROM laporan ORDER BY created_at DESC LIMIT 5");
    if ($recent_query && mysqli_num_rows($recent_query) > 0):
        while ($r = mysqli_fetch_assoc($recent_query)): 
            $st_class = ($r['status'] == 'selesai') ? 'status-selesai' : (($r['status'] == 'dalam_proses') ? 'status-proses' : 'status-pending'); ?>
            <div class="recent-item">
                <div class="recent-info">
                    <h5><?= htmlspecialchars($r['judul']) ?></h5>
                    <span>Oleh: <?= htmlspecialchars($r['nama_pelapor']) ?></span>
                    <div class="meta-tag">
                        <span class="tag-id">#ID-<?= $r['laporan_id'] ?></span>
                        <span class="tag-time">📅 <?= date('d M Y, H:i', strtotime($r['created_at'])) ?></span>
                    </div>
                </div>
                <span class="status-pill <?= $st_class ?>"><?= str_replace('_', ' ', $r['status']) ?></span>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="color: #555; font-size: 0.8rem; text-align: center; margin: 20px 0;">Belum ada laporan masuk.</p>
    <?php endif; ?>
</div>
