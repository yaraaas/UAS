<?php
include 'config/koneksi.php';

if (isset($_POST['kirim'])) {
    // Tangkap data yang dikirim dari lapor.php (NAMA ATRIBUT HARUS SAMA)
    $nama      = mysqli_real_escape_string($conn, $_POST['nama_pelapor']);
    // Server-side validation for nama_pelapor
    if (empty($nama)) {
        echo "<script>alert('Nama Pelapor tidak boleh kosong!'); window.history.back();</script>";
        exit();
    }

    $telp      = mysqli_real_escape_string($conn, $_POST['no_telp']);
    // Server-side validation for no_telp
    if (empty($telp)) {
        echo "<script>alert('Nomor Telepon tidak boleh kosong!'); window.history.back();</script>";
        exit();
    }

    $judul     = mysqli_real_escape_string($conn, $_POST['judul']);
    $prioritas = mysqli_real_escape_string($conn, $_POST['prioritas']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $lokasi    = mysqli_real_escape_string($conn, $_POST['lokasi_gps']);
    
    // Proses Upload Foto
    $nama_file = $_FILES['foto']['name'];
    $tmp_file  = $_FILES['foto']['tmp_name'];
    $ekstensi  = pathinfo($nama_file, PATHINFO_EXTENSION);
    $nama_baru = "PJU_" . time() . "." . $ekstensi; 
    $path      = "uploads/" . $nama_baru;

    if (move_uploaded_file($tmp_file, $path)) {
        // Query disesuaikan dengan kolom database Anda
        $query = "INSERT INTO laporan (nama_pelapor, no_telp, judul, keterangan, lokasi_gps, foto_bukti, status) 
                  VALUES ('$nama', '$telp', '$judul', '$deskripsi', '$lokasi', '$nama_baru', 'pending')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Laporan berhasil dikirim!'); window.location='index.php';</script>";
        } else {
            echo "Error Database: " . mysqli_error($conn);
        }
    } else {
        echo "Gagal mengunggah foto.";
    }
}
?>