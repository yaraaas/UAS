<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor PJU | Struktur Cahaya</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;800&display=swap" rel="stylesheet">
    <style>
        :root { 
            --accent: #FFD700; 
            --bg: #0a0a0a; 
            --glass: rgba(255, 255, 255, 0.03); 
        }
        
        body { 
            background: var(--bg); color: #fff; font-family: 'Inter', sans-serif; 
            padding: 40px 20px; display: flex; justify-content: center; 
            background-image: radial-gradient(circle at 50% 50%, #1a1600 0%, #0a0a0a 70%);
        }
        
        .container { 
            width: 100%; max-width: 500px; background: var(--glass); 
            backdrop-filter: blur(20px); padding: 40px; border-radius: 30px; 
            border: 1px solid rgba(255, 215, 0, 0.2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .back-btn {
            color: #777; text-decoration: none; font-size: 0.7rem; 
            text-transform: uppercase; letter-spacing: 2px; 
            margin-bottom: 25px; display: inline-block; transition: 0.3s;
        }
        .back-btn:hover { color: #fff; }

        .title-group {
            font-family: 'Times New Roman', Times, serif;
            font-size: 2.2rem;
            font-weight: bold; 
            letter-spacing: 2px; 
            line-height: 1.1;
            margin-bottom: 40px;
        }
        
        label { display: block; margin: 15px 0 8px; color: #aaa; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; }
        
        input, textarea, select { 
            width: 100%; padding: 15px; background: #1a1a1a; 
            border: 1px solid rgba(255, 215, 0, 0.3); border-radius: 15px; 
            color: #fff; box-sizing: border-box; transition: 0.4s;
        }
        
        /* Dropdown Gold Styling */
        select option { background: #1a1a1a; color: #fff; padding: 10px; }
        select option:checked, select option:hover {
            background-color: var(--accent) !important;
            color: #000 !important;
        }
        
        input:focus, textarea:focus, select:focus { outline: none; border-color: var(--accent); background: rgba(255, 215, 0, 0.05); }
        
        .gps-info { 
            padding: 15px; background: rgba(255, 215, 0, 0.1); 
            border-radius: 15px; border-left: 3px solid var(--accent); 
            color: var(--accent); font-size: 0.85rem; margin-bottom: 20px;
        }
        
        button { 
            width: 100%; padding: 18px; margin-top: 30px; 
            background: var(--accent); color: #000; border: none; 
            border-radius: 15px; font-weight: 800; cursor: pointer; 
            transition: 0.4s; text-transform: uppercase; letter-spacing: 1px;
        }
        
        button:hover { background: #fff; transform: translateY(-5px); box-shadow: 0 10px 30px rgba(255, 215, 0, 0.4); }
    </style>
</head>
<body>

<div class="container">
    <a href="index.php" class="back-btn">&larr; Kembali ke Beranda</a>

    <div style="font-size: 0.65rem; color: var(--accent); letter-spacing: 3px; margin-bottom: 10px; text-transform: uppercase; font-weight: 600;">
        Formulir Pelaporan
    </div>

    <div class="title-group">
        <span style="color: #ffffff;">Laporkan</span><br>
        <span style="color: #777777;">Lampu Mati</span>
    </div>

    <form action="proses_lapor.php" method="POST" enctype="multipart/form-data">
        <label>Nama Pelapor</label>
        <input type="text" name="nama_pelapor" placeholder="Nama lengkap Anda" required>

        <label>Nomor Telepon</label>
        <input type="tel" name="no_telp" placeholder="08xxxxxxxxxx" required>

        <label>Judul Laporan</label>
        <input type="text" name="judul" placeholder="Contoh: Lampu Padam di Depan Ruko" required>

        <label>Tingkat Prioritas</label>
        <select name="prioritas">
            <option value="Rendah">Rendah</option>
            <option value="Sedang">Sedang</option>
            <option value="Tinggi">Tinggi</option>
        </select>

        <label>Deskripsi Masalah</label>
        <textarea name="deskripsi" rows="3" placeholder="Jelaskan detail kerusakan..." required></textarea>

        <label>Lokasi GPS (Otomatis)</label>
        <div class="gps-info">
            <span id="status-gps">Mencari lokasi...</span>
            <input type="hidden" name="lokasi_gps" id="lokasi_input">
        </div>

        <label>Foto Lampu</label>
        <input type="file" name="foto" accept="image/*" required>

        <button type="submit" name="kirim">Kirim Laporan</button>
    </form>
</div>

<script>
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const coord = pos.coords.latitude + "," + pos.coords.longitude;
                document.getElementById("lokasi_input").value = coord;
                document.getElementById("status-gps").innerHTML = "📍 Lokasi terdeteksi: " + coord;
            },
            () => { document.getElementById("status-gps").innerHTML = "Gagal akses GPS."; }
        );
    }
</script>

</body>
</html>