<?php
// 1. Pastikan file koneksi dipanggil dengan jalur yang benar
require_once '../config/koneksi.php';

// 2. Cek apakah koneksi database berhasil
if (!$conn) {
    die("Koneksi ke database gagal. Periksa file koneksi Anda.");
}

// 3. Ambil ID dari URL dengan keamanan (casting ke integer)
$id = (int)($_GET['id'] ?? 0); 

// 4. Ambil data laporan dari database
$query = mysqli_query($conn, "SELECT * FROM laporan WHERE laporan_id = '$id'");
$data = mysqli_fetch_assoc($query);

// 5. Jika data tidak ditemukan
if (!$data) {
    echo "<div class='card' style='padding:20px; color:#fff;'>Laporan tidak ditemukan atau sudah dihapus.</div>";
    exit();
}
?>

<div class="card" style="background: rgba(255,255,255,0.03); padding: 30px; border-radius: 20px; border: 1px solid #333; color: #fff;">
    <h2 style="color: var(--gold); margin-bottom: 20px;">Detail Laporan #<?= $id ?></h2>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div>
            <p style="color:#888; font-size: 0.8rem; margin:0;">Status Laporan</p>
            <strong style="color: var(--gold); font-size: 1.1rem; text-transform: uppercase;">
                <?= htmlspecialchars($data['status']) ?>
            </strong>
        </div>
        <div>
            <p style="color:#888; font-size: 0.8rem; margin:0;">Prioritas</p>
            <strong style="color: #fff; font-size: 1.1rem;">
                <?= htmlspecialchars($data['prioritas'] ?? 'Normal') ?>
            </strong>
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
        <p style="color:#888; font-size: 0.8rem; margin-bottom: 5px;">Foto Bukti</p>
        <img src="../uploads/<?= htmlspecialchars($data['foto_bukti']) ?>" 
             style="width:100%; max-height: 300px; object-fit: cover; border-radius:10px; border:1px solid #333; margin-bottom: 20px;">
    <?php endif; ?>
    
    <hr style="border: 0; border-top: 1px solid #333; margin: 20px 0;">
    
    <form action="proses_tugaskan.php" method="POST">
        <input type="hidden" name="laporan_id" value="<?= $id ?>">
        
        <label style="display:block; color:#aaa; margin-bottom:10px;">Tugaskan ke Teknisi:</label>
        <select name="teknisi_id" required style="width: 100%; padding: 12px; border-radius: 8px; background:#111; color:#fff; border: 1px solid var(--gold); margin-bottom: 15px;">
            <option value="">-- Pilih Teknisi --</option>
            <?php
            $tek = mysqli_query($conn, "SELECT user_id, username FROM users WHERE role = 'teknisi'");
            while($t = mysqli_fetch_assoc($tek)){
                echo "<option value='".$t['user_id']."'>".$t['username']."</option>";
            }
            ?>
        </select>
        
        <button type="submit" style="background:var(--gold); color:#000; border:none; padding:15px; width:100%; border-radius:8px; font-weight:bold; cursor:pointer; text-transform: uppercase;">
            Kirim Tugas
        </button>
    </form>
</div>