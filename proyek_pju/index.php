<?php
include 'config/koneksi.php';
$total = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan"))['total'];
$diproses = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as diproses FROM laporan WHERE status='dalam_proses'"))['diproses'];
$selesai = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as selesai FROM laporan WHERE status='selesai'"))['selesai'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struktur Cahaya | Monitoring Premium</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --gold: #D4AF37; --bg: #050505; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: var(--bg); color: #fff; font-family: 'Inter', sans-serif; overflow-x: hidden; }

        /* HERO - RAMAI DENGAN LAYER */
        .hero { height: 90vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;
                background: linear-gradient(rgba(5,5,5,0.7), rgba(5,5,5,0.7)), url('image_02455d.jpg'); background-size: cover; background-position: center; border-bottom: 5px solid var(--gold); }
        h1 { font-family: 'Playfair Display'; font-size: clamp(3rem, 8vw, 6rem); line-height: 1; }
        h1 span { color: var(--gold); }
        .hero-desc { font-size: 1.1rem; color: #aaa; margin: 20px 0; max-width: 500px; }
        .btn-lapor { padding: 18px 45px; border: 1px solid var(--gold); color: var(--gold); text-transform: uppercase; font-weight: 600; letter-spacing: 3px; text-decoration: none; border-radius: 50px; transition: 0.4s; }
        .btn-lapor:hover { background: var(--gold); color: #000; box-shadow: 0 0 30px var(--gold); }

        /* CONTAINER DENGAN JARAK PAS */
        .container { max-width: 1000px; margin: -60px auto 80px auto; padding: 0 20px; }

        /* STATS - TAMPILAN LEBIH RAMAI */
        .stats-wrapper { display: grid; grid-template-columns: repeat(3, 1fr); gap: 25px; }
        .card { background: rgba(255,255,255,0.04); backdrop-filter: blur(10px); padding: 40px 20px; border-radius: 20px; border: 1px solid rgba(212,175,55,0.2); text-align: center; }
        .num { font-size: 3rem; font-family: 'Playfair Display'; color: var(--gold); margin-bottom: 5px; }
        .label { font-size: 0.7rem; letter-spacing: 2px; text-transform: uppercase; color: #aaa; }

        /* SECTION KERJA */
        .grid-info { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center; margin-top: 80px; }
        .workflow { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .step { background: rgba(255,255,255,0.02); padding: 25px; border-left: 3px solid var(--gold); border-radius: 10px; }
        .step-num { font-size: 1.5rem; color: var(--gold); font-family: 'Playfair Display'; }
    </style>
</head>
<body>

    <section class="hero">
        <h1>STRUKTUR <span>CAHAYA</span></h1>
        <p class="hero-desc">Sistem monitoring PJU cerdas: cepat, transparan, dan terukur demi kenyamanan publik.</p>
        <a href="lapor.php" class="btn-lapor">Laporkan Masalah</a>
    </section>

    <div class="container">
        <div class="stats-wrapper">
            <div class="card"><div class="num"><?= $total ?></div><div class="label">Total Laporan</div></div>
            <div class="card"><div class="num"><?= $diproses ?></div><div class="label">Sedang Diproses</div></div>
            <div class="card"><div class="num"><?= $selesai ?></div><div class="label">Selesai Diperbaiki</div></div>
        </div>

        <div class="grid-info">
            <div>
                <h2 style="font-family: 'Playfair Display'; font-size: 2.5rem; margin-bottom: 20px;">Sistem Monitoring Cerdas</h2>
                <p style="color: #aaa; font-size: 0.95rem; margin-bottom: 20px;">Struktur Cahaya mengintegrasikan pelaporan berbasis koordinat GPS dengan dasbor manajemen teknis. Kami memastikan setiap laporan yang masuk diproses dengan standar prioritas tertinggi.</p>
            </div>
            <div class="workflow">
                <div class="step"><div class="step-num">01</div><h3>Lapor</h3><p style="font-size: 0.75rem; color: #888;">Data lokasi GPS otomatis.</p></div>
                <div class="step"><div class="step-num">02</div><h3>Verifikasi</h3><p style="font-size: 0.75rem; color: #888;">Validasi urgensi admin.</p></div>
                <div class="step"><div class="step-num">03</div><h3>Perbaikan</h3><p style="font-size: 0.75rem; color: #888;">Teknisi terjun lokasi.</p></div>
                <div class="step"><div class="step-num">04</div><h3>Selesai</h3><p style="font-size: 0.75rem; color: #888;">Lampu kembali menyala.</p></div>
            </div>
        </div>
    </div>

</body>
</html>