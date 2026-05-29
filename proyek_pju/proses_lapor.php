<?php
// 1. Gunakan file koneksi terpusat agar nama database selalu sinkron
require_once 'config/koneksi.php';

// --- TAMBAHKAN LOGIKA UNTUK MENANGANI PENGIRIMAN LAPORAN (POST) ---
if (isset($_POST['kirim'])) {
    $nama      = mysqli_real_escape_string($conn, $_POST['nama_pelapor']);
    $telp      = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $judul     = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $lokasi    = mysqli_real_escape_string($conn, $_POST['lokasi_gps']);
    
    // Proses Upload Foto
    $nama_file = $_FILES['foto']['name'];
    $tmp_file  = $_FILES['foto']['tmp_name'];
    $ekstensi  = pathinfo($nama_file, PATHINFO_EXTENSION);
    $nama_baru = "PJU_" . time() . "." . $ekstensi; 
    $path      = "uploads/" . $nama_baru;

    if (move_uploaded_file($tmp_file, $path)) {
        // Note: Kolom 'prioritas' tidak ada di database Anda, jadi kita simpan ke keterangan/deskripsi jika perlu
        // Untuk sementara kita biarkan sesuai struktur tabel yang ada di SQL Anda
        $query = "INSERT INTO laporan (nama_pelapor, no_telp, judul, keterangan, lokasi_gps, foto_bukti, status) 
                  VALUES ('$nama', '$telp', '$judul', '$deskripsi', '$lokasi', '$nama_baru', 'pending')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Laporan berhasil dikirim!'); window.location='index.php';</script>";
            exit();
        } else {
            die("Error Database: " . mysqli_error($conn));
        }
    } else {
        die("Gagal mengunggah foto. Pastikan folder 'uploads' ada dan dapat ditulis.");
    }
}

// 2. Mengamankan input ID
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    echo "<div class='card'><p style='color:red;'>ID Laporan tidak valid.</p></div>";
    exit;
}

// 3. Query menggunakan Prepared Statement untuk mencegah SQL Injection
$stmt = mysqli_prepare($conn, "SELECT * FROM laporan WHERE laporan_id = ?");
if (!$stmt) {
    die("<div class='card'><p style='color:red;'>Gagal menyiapkan data: " . mysqli_error($conn) . "</p></div>");
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<div class='card'><p style='color:red;'>Laporan tidak ditemukan.</p></div>";
    exit;
}
?>

<style>
    :root { --gold: #D4AF37; }
    .card { 
        background: rgba(255,255,255,0.03); 
        padding: 30px; 
        border-radius: 20px; 
        border: 1px solid #333; 
        color: #fff;
        font-family: 'Inter', sans-serif;
        max-width: 600px;
        margin: 50px auto;
    }
    label { display: block; color: #aaa; margin-bottom: 10px; font-size: 0.8rem; text-transform: uppercase; }
</style>

<div class="card" style="background: rgba(255,255,255,0.03); padding: 30px; border-radius: 20px; border: 1px solid #333;">
    <h2 style="color: var(--gold); margin-bottom: 20px;">Laporan #<?= $id ?></h2>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div>
            <p style="color:#888; font-size: 0.8rem; margin:0;">Status Laporan</p>
            <strong style="color: var(--gold); font-size: 1.1rem; text-transform: uppercase;"><?= htmlspecialchars(str_replace('_', ' ', $data['status'])) ?></strong>
        </div>
        <div>
            <p style="color:#888; font-size: 0.8rem; margin:0;">Tingkat Prioritas</p>
            <strong style="color: #fff; font-size: 1.1rem;"><?= htmlspecialchars($data['prioritas'] ?? 'Normal') ?></strong>
        </div>
    </div>

    <div style="background: rgba(0,0,0,0.3); padding: 15px; border-radius: 10px; margin-bottom: 20px;">
        <p style="color:#888; font-size: 0.8rem; margin:0;">Koordinat Lokasi</p>
        <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($data['lokasi_gps']) ?>" target="_blank" 
           style="color: var(--gold); text-decoration: none; font-weight: bold;">
            📍 <?= htmlspecialchars($data['lokasi_gps']) ?>
        </a>
    </div>

    <?php if(!empty($data['foto_bukti'])): ?>
        <p style="color:#888; font-size: 0.8rem; margin-bottom: 5px;">Foto Bukti Kerusakan</p>
        <img src="uploads/<?= htmlspecialchars($data['foto_bukti']) ?>" 
             style="width:100%; max-height: 300px; object-fit: cover; border-radius:10px; border:1px solid #333; margin-bottom: 20px;">
    <?php endif; ?>
    
    <hr style="border: 0; border-top: 1px solid #333; margin: 20px 0;">
    
    <form action="proses_tugaskan.php" method="POST">
        <input type="hidden" name="laporan_id" value="<?= $id ?>">
        
        <label style="display:block; color:#aaa; margin-bottom:10px;">Tugaskan ke Teknisi Lapangan:</label>
        <select name="teknisi_id" required style="width: 100%; padding: 12px; border-radius: 8px; background:#111; color:#fff; border: 1px solid var(--gold); margin-bottom: 15px;">
            <option value="">-- Pilih Teknisi Tersedia --</option>
            <?php
            $tek = mysqli_query($conn, "SELECT user_id, username FROM users WHERE role = 'teknisi'");
            if ($tek) {
                while($t = mysqli_fetch_assoc($tek)){
                    echo "<option value='".$t['user_id']."'>".htmlspecialchars($t['username'])."</option>";
                }
            }
            ?>
        </select>
        
        <button type="submit" style="background:var(--gold); color:#000; border:none; padding:15px; width:100%; border-radius:8px; font-weight:bold; cursor:pointer; text-transform: uppercase; letter-spacing: 1px;">
            Kirim Tugas ke Lapangan
        </button>
    </form>
</div>
