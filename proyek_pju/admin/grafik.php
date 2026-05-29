<?php
// Pastikan tidak ada karakter/spasi di atas baris ini
// Query untuk mengambil data dari database
$q_pending = mysqli_query($conn, "SELECT count(*) FROM laporan WHERE status='pending'");
$q_proses  = mysqli_query($conn, "SELECT count(*) FROM laporan WHERE status='dalam_proses'");
$q_selesai = mysqli_query($conn, "SELECT count(*) FROM laporan WHERE status='selesai'");

$pending = mysqli_fetch_array($q_pending)[0] ?? 0;
$proses  = mysqli_fetch_array($q_proses)[0] ?? 0;
$selesai = mysqli_fetch_array($q_selesai)[0] ?? 0;
?>

<div class="card">
    <h3>Statistik Laporan</h3>
    <canvas id="grafikPJU"></canvas>
</div>

<script>
    (function() {
        const ctx = document.getElementById('grafikPJU').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pending', 'Proses', 'Selesai'],
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: [<?= $pending ?>, <?= $proses ?>, <?= $selesai ?>],
                    backgroundColor: ['#D4AF37', '#888', '#444']
                }]
            },
            options: {
                plugins: { legend: { labels: { color: '#fff' } } },
                scales: { 
                    y: { ticks: { color: '#fff' } }, 
                    x: { ticks: { color: '#fff' } } 
                }
            }
        });
    })();
</script>