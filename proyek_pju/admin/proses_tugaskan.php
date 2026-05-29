<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: dashboard.php");
    exit();
}

include '../config/koneksi.php';

$laporan_id = mysqli_real_escape_string($conn, $_POST['laporan_id']);
$teknisi_id = mysqli_real_escape_string($conn, $_POST['teknisi_id']);

// Query tanpa tgl_tugas
$sql = "INSERT INTO penugasan (laporan_id, teknisi_id) VALUES ('$laporan_id', '$teknisi_id')";

if(mysqli_query($conn, $sql)){
    // Update status ke 'dalam_proses' (Sesuai dengan enum di tabel Anda)
    mysqli_query($conn, "UPDATE laporan SET status = 'dalam_proses' WHERE laporan_id = '$laporan_id'");
    
    echo "<script>
            alert('Teknisi berhasil ditugaskan!'); 
            window.location.href='dashboard.php?page=laporan';
          </script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>