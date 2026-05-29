<?php
// File ini disertakan dalam dashboard.php
// Ambil ID dari URL dengan keamanan (casting ke integer)
$id = (int)($_GET['id'] ?? 0); 

// Ambil data laporan dari database
$query = mysqli_query($conn, "SELECT * FROM laporan WHERE laporan_id = '$id'");
$data = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan
if (!$data) {
    echo "<div class='card' style='padding:20px; color:#fff;'>Laporan tidak ditemukan atau sudah dihapus.</div>";
    exit();
}
?>

<div class="card" style="background: rgba(255,255,255,0.03); padding: 30px; border-radius: 20px; border: 1px solid #333; color: #fff;">
    <h2 style="color: var(--gold); margin-bottom: 5px;">Detail Laporan #<?= $id ?></h2>
    <h3 style="color: #fff; margin-bottom: 25px; font-weight: 300; letter-spacing: 1px;"><?= htmlspecialchars($data['judul'] ?? 'Tanpa Judul') ?></h3>
    
    <div style="margin-bottom: 25px;">
        <p style="color:#888; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Keterangan Masalah</p>
        <div style="background: rgba(255,255,255,0.02); padding: 15px; border-radius: 12px; border: 1px solid #222; font-size: 0.95rem; line-height: 1.6; color: #ccc;">
            <?= nl2br(htmlspecialchars($data['keterangan'] ?? 'Tidak ada deskripsi detail.')) ?>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
        <div>
            <p style="color:#888; font-size: 0.8rem; margin:0;">Status Laporan</p>
            <strong style="color: var(--gold); font-size: 1.1rem; text-transform: uppercase;">
                <?= htmlspecialchars(str_replace('_', ' ', $data['status'])) ?>
            </strong>
        </div>
        <div>
            <p style="color:#888; font-size: 0.8rem; margin:0;">Prioritas</p>
            <strong style="color: #fff; font-size: 1.1rem;">
                <?= htmlspecialchars($data['prioritas'] ?? 'Normal') ?>
            </strong>
        </div>
    </div>

    <div style="background: rgba(0,0,0,0.3); padding: 15px; border-radius: 10px; margin-bottom: 25px;">
        <p style="color:#888; font-size: 0.8rem; margin:0;">Koordinat Lokasi</p>
        <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($data['lokasi_gps']) ?>" target="_blank" 
           style="color: var(--gold); text-decoration: none; font-weight: bold;">
            📍 <?= htmlspecialchars($data['lokasi_gps']) ?>
        </a>
    </div>

    <?php if(!empty($data['foto_bukti'])): ?>
        <div style="margin-bottom: 25px;">
            <p style="color:#888; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">Foto Bukti Kerusakan</p>
            <a href="../uploads/<?= htmlspecialchars($data['foto_bukti']) ?>" target="_blank" title="Klik untuk memperbesar">
                <img src="../uploads/<?= htmlspecialchars($data['foto_bukti']) ?>" 
                     style="width:100%; max-height: 400px; object-fit: contain; background: #000; border-radius:15px; border:1px solid #333;">
            </a>
        </div>
    <?php endif; ?>
    
    <hr style="border: 0; border-top: 1px solid #333; margin: 20px 0;">
    
    <form action="proses_tugaskan.php" method="POST">
        <input type="hidden" name="laporan_id" value="<?= $id ?>">
        
        <label style="display:block; color:#aaa; margin-bottom:10px;">Tugaskan ke Teknisi:</label>
        <select name="teknisi_id" required style="width: 100%; padding: 12px; border-radius: 8px; background:#111; color:#fff; border: 1px solid var(--gold); margin-bottom: 15px;">
            <option value="">-- Pilih Teknisi --</option>
            <?php
            $tek = mysqli_query($conn, "SELECT user_id, username FROM users WHERE role = 'teknisi'");
            while($t = mysqli_fetch_assoc($tek)) {
                echo "<option value='".$t['user_id']."'>".htmlspecialchars($t['username'])."</option>";
            }
            ?>
        </select>
        
        <button type="submit" style="background:var(--gold); color:#000; border:none; padding:15px; width:100%; border-radius:8px; font-weight:bold; cursor:pointer; text-transform: uppercase;">
            Kirim Tugas
        </button>
    </form>
</div>
