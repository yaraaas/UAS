<?php
// Pastikan $conn sudah terhubung sebelum kode ini

// 1. Data Distribusi Status (Menggunakan satu query GROUP BY agar lebih efisien)
$stats = ['pending' => 0, 'diterima' => 0, 'dalam_proses' => 0, 'selesai' => 0];
$res_status = mysqli_query($conn, "SELECT status, COUNT(*) as jumlah FROM laporan GROUP BY status");
if ($res_status) {
    while ($row = mysqli_fetch_assoc($res_status)) {
        if (array_key_exists($row['status'], $stats)) {
            $stats[$row['status']] = (int)$row['jumlah'];
        }
    }
}

// 2. Data Trend (7 hari terakhir)
$labels_trend = []; $data_trend = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $labels_trend[] = date('d M', strtotime($date));
    $res_t = mysqli_query($conn, "SELECT COUNT(*) as jumlah FROM laporan WHERE DATE(created_at) = '$date'");
    $data_trend[] = (int)mysqli_fetch_assoc($res_t)['jumlah'];
}
?>

<style>
    .summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
    .summary-box { background: rgba(255,255,255,0.02); padding: 20px; border-radius: 15px; border: 1px solid #222; text-align: center; transition: 0.3s; }
    .summary-box:hover { border-color: var(--gold); background: rgba(212, 175, 55, 0.05); transform: translateY(-3px); }
    .summary-box h4 { margin: 0; font-size: 0.7rem; color: #888; text-transform: uppercase; letter-spacing: 1px; }
    .summary-box div { font-size: 1.8rem; font-family: 'Playfair Display', serif; margin-top: 5px; }
    .card-graph { background: rgba(255,255,255,0.03); border-radius: 20px; border: 1px solid #333; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); transition: 0.3s; }
    .card-graph:hover { border-color: rgba(212, 175, 55, 0.3); }
</style>

<div class="summary-grid">
    <div class="summary-box" style="border-bottom: 3px solid #FF5E5E;">
        <h4>Menunggu Antrian</h4>
        <div style="color: #FF5E5E; text-shadow: 0 0 10px rgba(255,94,94,0.3);"><?= $stats['pending'] + $stats['diterima'] ?></div>
    </div>
    <div class="summary-box" style="border-bottom: 3px solid var(--gold);">
        <h4>Dalam Pengerjaan</h4>
        <div style="color: var(--gold); text-shadow: 0 0 10px rgba(212,175,55,0.3);"><?= $stats['dalam_proses'] ?></div>
    </div>
    <div class="summary-box" style="border-bottom: 3px solid #00F2A9;">
        <h4>Telah Teratasi</h4>
        <div style="color: #00F2A9; text-shadow: 0 0 10px rgba(0,242,169,0.3);"><?= $stats['selesai'] ?></div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
    <div class="card-graph">
        <h3 style="color: var(--gold); margin-bottom: 25px; font-size: 1rem; text-transform: uppercase; letter-spacing: 1px; font-family: 'Playfair Display', serif;">Distribusi Status</h3>
        <div style="height: 300px;"><canvas id="chartStatus"></canvas></div>
    </div>
    <div class="card-graph">
        <h3 style="color: var(--gold); margin-bottom: 25px; font-size: 1rem; text-transform: uppercase; letter-spacing: 1px; font-family: 'Playfair Display', serif;">Trend Laporan Mingguan</h3>
        <div style="height: 300px;"><canvas id="chartTrend"></canvas></div>
    </div>
</div>

<script>
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#888';

    // Plugin untuk teks di tengah Doughnut
    const centerTextPlugin = {
        id: 'centerText',
        afterDraw: (chart) => {
            if (chart.config.type === 'doughnut') {
                const { ctx, chartArea: { top, bottom, left, right, width, height } } = chart;
                ctx.save();
                ctx.font = 'bold 2rem Playfair Display';
                ctx.fillStyle = '#D4AF37';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                ctx.fillText(total, left + width / 2, top + height / 2 - 5);
                ctx.font = '0.6rem Inter';
                ctx.fillStyle = '#666';
                ctx.fillText('TOTAL LAPORAN', left + width / 2, top + height / 2 + 25);
                ctx.restore();
            }
        }
    };

    // Chart Status (Donut)
    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        plugins: [centerTextPlugin],
        data: {
            labels: ['Antrian', 'Dalam Proses', 'Selesai'],
            datasets: [{
                data: [<?= $stats['pending'] + $stats['diterima'] ?>, <?= $stats['dalam_proses'] ?>, <?= $stats['selesai'] ?>],
                backgroundColor: ['#FF5E5E', '#D4AF37', '#00F2A9'],
                hoverOffset: 20, borderWidth: 0, borderRadius: 5
            }]
        },
        options: { maintainAspectRatio: false, cutout: '80%', plugins: { legend: { position: 'bottom', labels: { padding: 20, color: '#eee', usePointStyle: true } } } }
    });

    // Chart Trend (Line)
    const ctxTrend = document.getElementById('chartTrend').getContext('2d');
    const trendGradient = ctxTrend.createLinearGradient(0, 0, 0, 300);
    trendGradient.addColorStop(0, 'rgba(212, 175, 55, 0.4)');
    trendGradient.addColorStop(1, 'rgba(212, 175, 55, 0)');

    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: <?= json_encode($labels_trend) ?>,
            datasets: [{
                label: 'Laporan', data: <?= json_encode($data_trend) ?>,
                borderColor: '#D4AF37', backgroundColor: trendGradient, borderWidth: 3, fill: true, tension: 0.4, pointRadius: 4
            }]
        },
        options: { 
            maintainAspectRatio: false, 
            plugins: { legend: { display: false } }, 
            scales: { 
                y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { precision: 0 } }, 
                x: { grid: { display: false } } 
            } 
        }
    });
</script>
