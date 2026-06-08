-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2026 at 09:16 AM
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
-- Database: `perpustakaan_sekolah`
--

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `id_anggota` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_telp` varchar(20) NOT NULL,
  `kelas` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anggota`
--

INSERT INTO `anggota` (`id_anggota`, `nama`, `no_telp`, `kelas`) VALUES
(1, 'Nawita ', '6281234567892', '12 IPA 2'),
(2, 'Ihaniie Rustam', '6281234567888', '12 IPA 2'),
(3, 'Livny Putri', '6281234567878', '10 IPS 2'),
(4, 'Gaoniyuu', '6281234567877', '12 IPA 1'),
(5, 'Antonio Niu', '6281234567844', '12 IPA 2'),
(6, 'Joshua', '6281234567892', '12 IPS 2'),
(7, 'Evelyn Putri', '6281234567888', '10 IPS 2');

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` varchar(20) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `genre` varchar(50) NOT NULL,
  `pengarang` varchar(100) NOT NULL,
  `penerbit` varchar(100) NOT NULL,
  `tahun_terbit` varchar(4) NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `judul`, `genre`, `pengarang`, `penerbit`, `tahun_terbit`, `stok`) VALUES
('AB1', 'English Grammar in Use', 'Bahasa', 'Raymond Murphy', 'Cambridge University Press', '2019', 9),
('AE1', 'Pengantar Ekonomi Makro', 'Ekonomi', 'N. Gregory Mankiw', 'Salemba Empat', '2014', 6),
('AI1', 'Clean Code', 'Informatika', 'Robert C. Martin', 'Prentice Hall', '2008', 5),
('AI2', 'Logika Informatika', 'Informatika', 'Suprapto', 'Gava Media', '2017', 8),
('AI3', 'Pemrograman Web dengan PHP', 'Informatika', 'Budi Raharjo', 'Informatika', '2021', 5),
('AS1', 'Fisika Dasar Jilid 1', 'Sains', 'Halliday & Resnick', 'Erlangga', '2010', 4),
('AS2', 'Metodologi Penelitian', 'Sains', 'Sugiyono', 'Alfabeta', '2018', 12),
('FA1', 'The Hunger Games', 'Action', 'Suzanne Collins', 'Scholastic', '2008', 6),
('FD1', 'Pulang', 'Drama', 'Leila S. Chudori', 'KPG', '2012', 3),
('FF1', 'Bumi (Series)', 'Fantasi', 'Tere Liye', 'Gramedia Pustaka Utama', '2014', 7),
('FF2', 'Harry Potter', 'Fantasi', 'J.K. Rowling', 'Bloomsbury', '1997', 10),
('FN1', 'Laskar Pelangi', 'Novel', 'Andrea Hirata', 'Bentang Pustaka', '2005', 4),
('FS1', '5 cm', 'Slice of Life', 'Donny Dhirgantoro', 'Grasindo', '2005', 4),
('UB1', 'Biografi Mohammad Hatta', 'Biografi', 'Deliar Noer', 'LP3ES', '1990', 2),
('UM1', 'Atomic Habits', 'Motivasi', 'James Clear', 'Gramedia Pustaka Utama', '2019', 15),
('UM2', 'Filosofi Teras', 'Motivasi', 'Henry Manampiring', 'Kompas', '2018', 9),
('US1', 'Sapiens', 'Sejarah', 'Yuval Noah Harari', 'KPG', '2011', 5),
('US2', 'A Brief History of Time', 'Sejarah', 'Stephen Hawking', 'Bantam Books', '1988', 4);

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_pinjam` int(11) NOT NULL,
  `id_buku` varchar(20) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali_seharusnya` date NOT NULL,
  `tgl_dikembalikan` date DEFAULT NULL,
  `batas_hari` int(11) NOT NULL DEFAULT 0,
  `denda_per_hari` int(11) NOT NULL DEFAULT 0,
  `denda` int(11) NOT NULL DEFAULT 0,
  `status` enum('pinjam','kembali') NOT NULL DEFAULT 'pinjam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_pinjam`, `id_buku`, `id_anggota`, `tgl_pinjam`, `tgl_kembali_seharusnya`, `tgl_dikembalikan`, `batas_hari`, `denda_per_hari`, `denda`, `status`) VALUES
(1, 'FF2', 1, '2026-05-14', '2026-05-21', '2026-06-01', 0, 0, 65000, 'kembali'),
(2, 'UM2', 3, '2026-06-01', '2026-06-08', '2026-06-07', 0, 0, 5000, 'kembali'),
(3, 'FN1', 2, '2026-05-14', '2026-05-21', NULL, 0, 0, 0, 'pinjam'),
(4, 'FA1', 5, '2026-06-01', '2026-06-08', '2026-06-01', 0, 0, 0, 'kembali'),
(5, 'AE1', 3, '2026-05-04', '2026-05-09', '2026-06-07', 0, 0, 145000, 'kembali'),
(6, 'AB1', 6, '2026-06-03', '2026-06-10', NULL, 0, 0, 0, 'pinjam'),
(7, 'AB1', 5, '2026-06-07', '2026-06-14', '2026-06-07', 0, 0, 0, 'kembali');

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id` int(11) NOT NULL,
  `batas_hari_pinjam` int(11) NOT NULL,
  `denda_per_hari` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengaturan`
--

INSERT INTO `pengaturan` (`id`, `batas_hari_pinjam`, `denda_per_hari`) VALUES
(1, 5, 5000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('admin','siswa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama_lengkap`, `username`, `password`, `level`) VALUES
(1, 'Administrator Perpus', 'admin', '0192023a7bbd73250516f069df18b500', 'admin'),
(2, 'Nawita ', '231011', '827ccb0eea8a706c4c34a16891f84e7b', 'siswa'),
(3, 'Ihaniie Rustam', '231012', '827ccb0eea8a706c4c34a16891f84e7b', 'siswa'),
(4, 'Livny Putri', '231013', '827ccb0eea8a706c4c34a16891f84e7b', 'siswa'),
(5, 'Gaoniyuu', '231014', '827ccb0eea8a706c4c34a16891f84e7b', 'siswa'),
(6, 'Antonio Niu', '231015', '827ccb0eea8a706c4c34a16891f84e7b', 'siswa'),
(7, 'Joshua', '231016', '827ccb0eea8a706c4c34a16891f84e7b', 'siswa'),
(8, 'Evelyn Putri', '231017', '827ccb0eea8a706c4c34a16891f84e7b', 'siswa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id_anggota`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_pinjam`),
  ADD KEY `id_buku` (`id_buku`),
  ADD KEY `id_anggota` (`id_anggota`);

--
-- Indexes for table `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id_anggota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_pinjam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `fk_peminjaman_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_peminjaman_buku` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
