<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../config/koneksi.php';
$id = $_GET['id'];

mysqli_query($conn, "UPDATE laporan SET status = 'selesai' WHERE laporan_id = '$id'");
echo "<script>alert('Lampu berhasil diperbaiki!'); window.location='tracking.php';</script>";
?>