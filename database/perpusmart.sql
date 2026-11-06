-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2026 at 11:10 AM
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
-- Database: `perpusmart`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode_buku` varchar(20) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `penulis` varchar(150) NOT NULL,
  `penerbit` varchar(150) NOT NULL,
  `tahun_terbit` year(4) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `stok` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  `cover_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id`, `kode_buku`, `judul`, `penulis`, `penerbit`, `tahun_terbit`, `kategori`, `stok`, `deskripsi`, `cover_url`, `created_at`, `updated_at`) VALUES
(1, 'BK-001', 'Clean Code: A Handbook of Agile Software Craftsmanship', 'Robert C. Martin', 'Prentice Hall', '2008', 'Pemrograman', 5, 'Buku klasik tentang cara menulis kode yang bersih, mudah dibaca, dan mudah dipelihara.', NULL, '2026-11-06 10:36:29', '2026-11-06 10:36:29'),
(2, 'BK-002', 'The Pragmatic Programmer: Your Journey to Mastery', 'David Thomas & Andrew Hunt', 'Addison-Wesley', '2019', 'Pemrograman', 2, 'Panduan lengkap untuk menjadi programmer yang pragmatis dan efektif.', NULL, '2026-11-06 10:36:29', '2026-11-06 11:08:18'),
(3, 'BK-003', 'Pemrograman Web dengan PHP dan MySQL', 'Bunafit Nugroho', 'Gava Media', '2020', 'Pemrograman Web', 6, 'Buku panduan praktis membangun aplikasi web dinamis menggunakan PHP dan MySQL.', NULL, '2026-11-06 10:36:29', '2026-11-06 11:08:32'),
(4, 'BK-004', 'Basis Data: Konsep dan Perancangan', 'Fathansyah', 'Informatika Bandung', '2018', 'Basis Data', 4, 'Penjelasan mendalam tentang konsep basis data relasional, normalisasi, dan ERD.', NULL, '2026-11-06 10:36:29', '2026-11-06 10:36:29'),
(5, 'BK-005', 'Algoritma dan Pemrograman dalam Bahasa Pascal dan C', 'Rinaldi Munir', 'Informatika Bandung', '2016', 'Algoritma', 6, 'Buku fundamental tentang algoritma, struktur data, dan implementasinya dalam Pascal dan C.', NULL, '2026-11-06 10:36:29', '2026-11-06 10:36:29'),
(6, 'BK-006', 'Kecerdasan Buatan: Teknik dan Aplikasinya', 'Sri Kusumadewi', 'Graha Ilmu', '2017', 'Kecerdasan Buatan', 1, 'Membahas teknik kecerdasan buatan: logika fuzzy, jaringan saraf tiruan, dan sistem pakar.', NULL, '2026-11-06 10:36:29', '2026-11-06 11:08:41'),
(7, 'BK-007', 'Sistem Operasi: Konsep dan Teknik Implementasi', 'Abraham Silberschatz', 'Erlangga', '2019', 'Sistem Operasi', 5, 'Referensi standar tentang konsep sistem operasi: manajemen proses, memori, dan file.', NULL, '2026-11-06 10:36:29', '2026-11-06 10:36:29'),
(8, 'BK-008', 'Jaringan Komputer dan Internet', 'Forouzan & Mosharraf', 'McGraw-Hill', '2021', 'Jaringan Komputer', 3, 'Buku lengkap tentang protokol jaringan, arsitektur TCP/IP, routing, dan keamanan jaringan.', NULL, '2026-11-06 10:36:29', '2026-11-06 10:36:29'),
(9, 'BK-009', 'Rekayasa Perangkat Lunak: Pendekatan Praktisi', 'Roger S. Pressman', 'Andi Offset', '2015', 'Rekayasa Perangkat Lunak', 4, 'Panduan komprehensif tentang proses rekayasa perangkat lunak dan manajemen proyek IT.', NULL, '2026-11-06 10:36:29', '2026-11-06 10:36:29'),
(10, 'BK-010', 'Pemrograman Berorientasi Objek dengan Java', 'Deitel & Deitel', 'Pearson Education', '2020', 'Pemrograman', 8, 'Buku terlengkap tentang OOP menggunakan Java, mencakup GUI dan penanganan exception.', NULL, '2026-11-06 10:36:29', '2026-11-06 10:36:29');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode_pinjam` varchar(30) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `buku_id` int(10) UNSIGNED NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `batas_kembali` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('dipinjam','dikembalikan','terlambat') NOT NULL DEFAULT 'dipinjam',
  `denda` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `kode_pinjam`, `user_id`, `buku_id`, `tanggal_pinjam`, `batas_kembali`, `tanggal_kembali`, `status`, `denda`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 'PJ-202610-0001', 2, 1, '2026-11-06', '2026-11-13', NULL, 'dipinjam', 0, 'Peminjaman untuk tugas kuliah', '2026-11-06 10:36:29', '2026-11-06 10:36:29'),
(3, 'PJ-202611-7B906E', 3, 2, '2026-11-06', '2026-11-09', NULL, 'dipinjam', 0, '', '2026-11-06 11:08:18', '2026-11-06 11:08:18'),
(4, 'PJ-202611-F1C306', 3, 3, '2026-11-06', '2026-11-13', NULL, 'dipinjam', 0, '', '2026-11-06 11:08:32', '2026-11-06 11:08:32'),
(5, 'PJ-202611-2A16B3', 3, 6, '2026-11-06', '2026-11-13', NULL, 'dipinjam', 0, '', '2026-11-06 11:08:41', '2026-11-06 11:08:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin', 'admin@perpusmart.id', '$2y$12$wmpVNnXImaBQWqG/YW8fVOEKjvkUSIivnIreuBpfj.OLdBM4ogzii', 'admin', '2026-11-06 10:36:29', '2026-11-06 10:36:29'),
(2, 'Budi Santoso', 'budi', 'budi@mahasiswa.id', '$2y$12$H6.n8KtqALM0UY8A6PO47O9QcoZ/W9Ko9tU/KLsFGmNa.vadZTgme', 'user', '2026-11-06 10:36:29', '2026-11-06 10:36:29'),
(3, 'dinda', 'dindaayu', 'ayud2698@gmail.com', '$2y$12$Wfal6l0el9VUpqh7.9va/eJ/G1UbV70hKLxz8bK8e1/lloibRx1Kq', 'user', '2026-11-06 10:46:15', '2026-11-06 10:46:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_buku` (`kode_buku`),
  ADD KEY `idx_judul` (`judul`),
  ADD KEY `idx_penulis` (`penulis`),
  ADD KEY `idx_kategori` (`kategori`),
  ADD KEY `idx_kode` (`kode_buku`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pinjam` (`kode_pinjam`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_buku_id` (`buku_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `fk_peminjaman_buku` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_peminjaman_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
