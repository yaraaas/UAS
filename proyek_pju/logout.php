<?php
session_start();

// Menghapus semua variabel sesi yang terdaftar
session_unset();

// Menghancurkan sesi yang sedang aktif
session_destroy();

// Mengarahkan pengguna kembali ke halaman login
header("Location: login.php");
exit();