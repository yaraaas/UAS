-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2026 at 10:33 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_pju`
--

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `laporan_id` int(11) NOT NULL,
  `nama_pelapor` varchar(255) NOT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `lokasi_gps` varchar(100) NOT NULL,
  `alamat_lengkap` text DEFAULT NULL,
  `foto_bukti` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('pending','diterima','dalam_proses','selesai') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`laporan_id`, `nama_pelapor`, `no_telp`, `judul`, `user_id`, `lokasi_gps`, `alamat_lengkap`, `foto_bukti`, `keterangan`, `status`, `created_at`) VALUES
(9, '', NULL, '', NULL, '-6.879807,108.776764', '', 'PJU_1780039106.webp', NULL, 'selesai', '2026-05-29 07:18:26'),
(10, '', NULL, '', NULL, '-6.7101,108.5303', '', 'PJU_1780039895.png', NULL, 'pending', '2026-05-29 07:31:35'),
(11, '', NULL, '', NULL, '-26.998746,30.805504', '', 'PJU_1780040432.png', NULL, 'dalam_proses', '2026-05-29 07:40:32');

-- --------------------------------------------------------

--
-- Table structure for table `log_status`
--

CREATE TABLE `log_status` (
  `log_id` int(11) NOT NULL,
  `laporan_id` int(11) DEFAULT NULL,
  `status_sebelumnya` varchar(20) DEFAULT NULL,
  `status_sesudahnya` varchar(20) DEFAULT NULL,
  `diubah_oleh` int(11) DEFAULT NULL,
  `waktu_ubah` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penugasan`
--

CREATE TABLE `penugasan` (
  `penugasan_id` int(11) NOT NULL,
  `laporan_id` int(11) DEFAULT NULL,
  `teknisi_id` int(11) DEFAULT NULL,
  `catatan_perbaikan` text DEFAULT NULL,
  `waktu_penugasan` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penugasan`
--

INSERT INTO `penugasan` (`penugasan_id`, `laporan_id`, `teknisi_id`, `catatan_perbaikan`, `waktu_penugasan`) VALUES
(4, 9, 2, NULL, '2026-05-29 07:19:08'),
(5, 11, 3, NULL, '2026-05-29 08:30:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `role` enum('admin','teknisi','masyarakat') DEFAULT 'masyarakat',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `nama_lengkap`, `role`, `created_at`) VALUES
(1, 'admin1', 'admin123', 'Administrator Utama', 'admin', '2026-05-29 03:01:56'),
(2, 'teknisi1', 'teknisi123', 'Budi Teknisi', 'teknisi', '2026-05-29 03:01:56'),
(3, 'teknisi2', 'teknisi123', 'Siti Teknisi', 'teknisi', '2026-05-29 03:01:56'),
(4, 'masyarakat1', 'user123', 'Andi Warga', 'masyarakat', '2026-05-29 03:01:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`laporan_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `log_status`
--
ALTER TABLE `log_status`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `laporan_id` (`laporan_id`),
  ADD KEY `diubah_oleh` (`diubah_oleh`);

--
-- Indexes for table `penugasan`
--
ALTER TABLE `penugasan`
  ADD PRIMARY KEY (`penugasan_id`),
  ADD KEY `laporan_id` (`laporan_id`),
  ADD KEY `teknisi_id` (`teknisi_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `laporan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `log_status`
--
ALTER TABLE `log_status`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penugasan`
--
ALTER TABLE `penugasan`
  MODIFY `penugasan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `laporan`
--
ALTER TABLE `laporan`
  ADD CONSTRAINT `laporan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `log_status`
--
ALTER TABLE `log_status`
  ADD CONSTRAINT `log_status_ibfk_1` FOREIGN KEY (`laporan_id`) REFERENCES `laporan` (`laporan_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `log_status_ibfk_2` FOREIGN KEY (`diubah_oleh`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `penugasan`
--
ALTER TABLE `penugasan`
  ADD CONSTRAINT `penugasan_ibfk_1` FOREIGN KEY (`laporan_id`) REFERENCES `laporan` (`laporan_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penugasan_ibfk_2` FOREIGN KEY (`teknisi_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
