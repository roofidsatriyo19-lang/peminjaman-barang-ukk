-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Feb 2026 pada 13.28
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `peminjaman_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `aktivitas` text DEFAULT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `id_user`, `aktivitas`, `waktu`) VALUES
(1, 1, 'Melakukan logout dari sistem', '2026-02-13 08:05:13'),
(2, 3, 'Berhasil login ke dalam sistem', '2026-02-13 08:05:22'),
(3, 3, 'Melakukan logout dari sistem', '2026-02-13 08:05:25'),
(4, 7, 'Berhasil login ke dalam sistem', '2026-02-13 08:05:31'),
(5, 7, 'Melakukan logout dari sistem', '2026-02-13 08:05:39'),
(6, 1, 'Berhasil login ke dalam sistem', '2026-02-13 08:05:45'),
(7, 1, 'Mengubah data barang: Karton Panjang (ID: 14)', '2026-02-13 08:10:57'),
(8, 1, 'Berhasil login ke dalam sistem', '2026-02-13 14:21:38'),
(9, 1, 'Melakukan logout dari sistem', '2026-02-13 14:33:46'),
(10, 6, 'Berhasil login ke dalam sistem', '2026-02-13 14:34:06'),
(11, 6, 'Melakukan booking barang: Karton Panjang (Jumlah: 1)', '2026-02-13 14:34:23'),
(12, 6, 'Melakukan logout dari sistem', '2026-02-13 14:34:26'),
(13, 1, 'Berhasil login ke dalam sistem', '2026-02-13 14:34:31'),
(14, 1, 'Berhasil login ke dalam sistem', '2026-02-14 15:54:15'),
(15, 1, 'Melakukan logout dari sistem', '2026-02-14 15:54:22'),
(16, 3, 'Berhasil login ke dalam sistem', '2026-02-14 15:54:26'),
(17, 3, 'Melakukan logout dari sistem', '2026-02-14 15:54:30'),
(18, 7, 'Berhasil login ke dalam sistem', '2026-02-14 15:54:44'),
(19, 7, 'Melakukan logout dari sistem', '2026-02-14 15:54:53'),
(20, 1, 'Berhasil login ke dalam sistem', '2026-02-14 15:55:23'),
(21, 1, 'Melakukan logout dari sistem', '2026-02-14 15:55:26'),
(22, 7, 'Berhasil login ke dalam sistem', '2026-02-14 15:55:35'),
(23, 7, 'Melakukan logout dari sistem', '2026-02-14 16:44:58'),
(24, 3, 'Berhasil login ke dalam sistem', '2026-02-14 16:45:04'),
(25, 3, 'Menyetujui peminjaman user: eL', '2026-02-14 16:45:15'),
(26, 3, 'Melakukan logout dari sistem', '2026-02-14 16:45:19'),
(27, 7, 'Berhasil login ke dalam sistem', '2026-02-14 16:45:29'),
(28, 7, 'Mengajukan pengembalian: Pensil (Denda terhitung: Rp 10.000)', '2026-02-14 16:45:33'),
(29, 7, 'Mengajukan pengembalian: Pensil (Denda terhitung: Rp 10.000)', '2026-02-14 16:45:36'),
(30, 7, 'Melakukan logout dari sistem', '2026-02-14 16:45:42'),
(31, 3, 'Berhasil login ke dalam sistem', '2026-02-14 16:45:50'),
(32, 3, 'Melakukan logout dari sistem', '2026-02-14 16:46:01'),
(33, 6, 'Berhasil login ke dalam sistem', '2026-02-14 16:46:20'),
(34, 6, 'Melakukan logout dari sistem', '2026-02-14 16:46:48'),
(35, 3, 'Berhasil login ke dalam sistem', '2026-02-14 16:47:05'),
(36, 3, 'Melakukan logout dari sistem', '2026-02-14 16:49:14'),
(37, 1, 'Berhasil login ke dalam sistem', '2026-02-14 16:49:20'),
(38, 1, 'Berhasil login ke dalam sistem', '2026-02-14 16:49:50'),
(39, 1, 'Melakukan logout dari sistem', '2026-02-14 16:58:23'),
(40, 6, 'Berhasil login ke dalam sistem', '2026-02-14 17:05:03'),
(41, 3, 'Berhasil login ke dalam sistem', '2026-02-14 17:05:18'),
(42, 6, 'Berhasil login ke dalam sistem', '2026-02-14 17:05:52'),
(43, 6, 'Mengajukan pengembalian: Karton Panjang (Denda terhitung: Rp 5.000)', '2026-02-14 17:05:55'),
(44, 3, 'Berhasil login ke dalam sistem', '2026-02-14 17:06:03'),
(45, 3, 'Berhasil login ke dalam sistem', '2026-02-15 07:00:56'),
(46, 3, 'Melakukan logout dari sistem', '2026-02-15 07:01:19'),
(47, 10, 'Berhasil login ke dalam sistem', '2026-02-15 07:02:42'),
(48, 10, 'Mengajukan pengembalian: Pulpen (Denda terhitung: Rp 5.000)', '2026-02-15 07:02:49'),
(49, 10, 'Melakukan logout dari sistem', '2026-02-15 07:02:53'),
(50, 3, 'Berhasil login ke dalam sistem', '2026-02-15 07:02:57'),
(51, 3, 'Melakukan logout dari sistem', '2026-02-15 07:22:50'),
(52, 3, 'Berhasil login ke dalam sistem', '2026-02-15 07:22:55'),
(53, 3, 'Melakukan logout dari sistem', '2026-02-15 07:23:02'),
(54, 1, 'Berhasil login ke dalam sistem', '2026-02-15 07:23:07'),
(55, 1, 'Berhasil login ke dalam sistem', '2026-02-15 07:23:29'),
(56, 1, 'Melakukan logout dari sistem', '2026-02-15 07:23:47'),
(57, 7, 'Berhasil login ke dalam sistem', '2026-02-15 07:23:53'),
(58, 7, 'Melakukan booking: Karton Panjang (1 Unit)', '2026-02-15 07:24:03'),
(59, 7, 'Melakukan logout dari sistem', '2026-02-15 07:24:05'),
(60, 3, 'Berhasil login ke dalam sistem', '2026-02-15 07:24:11'),
(61, 3, 'Menyetujui peminjaman user: anotheropit', '2026-02-15 07:24:18'),
(62, 3, 'Melakukan logout dari sistem', '2026-02-15 07:24:24'),
(63, 7, 'Berhasil login ke dalam sistem', '2026-02-15 07:24:32'),
(64, 7, 'Mengajukan pengembalian: Karton Panjang', '2026-02-15 07:24:35'),
(65, 7, 'Melakukan logout dari sistem', '2026-02-15 07:24:39'),
(66, 3, 'Berhasil login ke dalam sistem', '2026-02-15 07:24:44'),
(67, 3, 'Mengonfirmasi pengembalian ID: 30', '2026-02-15 07:24:54'),
(68, 3, 'Melakukan logout dari sistem', '2026-02-15 07:25:17'),
(69, 7, 'Berhasil login ke dalam sistem', '2026-02-15 07:25:24'),
(70, 7, 'Melakukan booking: Lem (1 Unit)', '2026-02-15 07:25:56'),
(71, 7, 'Melakukan logout dari sistem', '2026-02-15 07:25:58'),
(72, 3, 'Berhasil login ke dalam sistem', '2026-02-15 07:26:05'),
(73, 3, 'Menyetujui peminjaman user: anotheropit', '2026-02-15 07:26:13'),
(74, 3, 'Melakukan logout dari sistem', '2026-02-15 07:26:20'),
(75, 7, 'Berhasil login ke dalam sistem', '2026-02-15 07:26:28'),
(76, 7, 'Mengajukan pengembalian: Lem', '2026-02-15 07:26:36'),
(77, 7, 'Melakukan logout dari sistem', '2026-02-15 07:26:38'),
(78, 3, 'Berhasil login ke dalam sistem', '2026-02-15 07:26:43'),
(79, 3, 'Mengonfirmasi pengembalian ID: 31', '2026-02-15 07:26:52'),
(80, 3, 'Melakukan logout dari sistem', '2026-02-15 07:29:03'),
(81, 1, 'Berhasil login ke dalam sistem', '2026-02-15 07:29:08'),
(82, 1, 'Melakukan logout dari sistem', '2026-02-15 07:33:00'),
(83, 3, 'Berhasil login ke dalam sistem', '2026-02-15 07:33:06'),
(84, 3, 'Melakukan logout dari sistem', '2026-02-15 07:33:13'),
(85, 1, 'Berhasil login ke dalam sistem', '2026-02-15 07:33:19'),
(86, 1, 'Berhasil login ke dalam sistem', '2026-02-15 09:43:22'),
(87, 1, 'Melakukan logout dari sistem', '2026-02-15 09:44:35'),
(88, 1, 'Berhasil login ke dalam sistem', '2026-02-15 09:51:41'),
(89, 1, 'Melakukan logout dari sistem', '2026-02-15 10:05:44'),
(90, 1, 'Berhasil login ke dalam sistem', '2026-02-15 10:06:14'),
(91, 1, 'Melakukan logout dari sistem', '2026-02-15 10:06:17'),
(92, 1, 'Berhasil login ke dalam sistem', '2026-02-15 10:06:36'),
(93, 1, 'Melakukan logout dari sistem', '2026-02-15 10:07:05'),
(94, 1, 'Berhasil login ke dalam sistem', '2026-02-15 10:07:18'),
(95, 1, 'Melakukan logout dari sistem', '2026-02-15 10:07:22'),
(96, 1, 'Berhasil login ke dalam sistem', '2026-02-15 10:07:42'),
(97, 1, 'Melakukan logout dari sistem', '2026-02-15 10:07:47'),
(98, 1, 'Berhasil login ke dalam sistem', '2026-02-15 10:08:38'),
(99, 1, 'Melakukan logout dari sistem', '2026-02-15 10:08:44'),
(100, 1, 'Berhasil login ke dalam sistem', '2026-02-15 10:08:57'),
(101, 1, 'Melakukan logout dari sistem', '2026-02-15 10:09:02'),
(102, 1, 'Berhasil login ke dalam sistem', '2026-02-15 10:09:40'),
(103, 1, 'Melakukan logout dari sistem', '2026-02-15 10:21:17'),
(104, 1, 'Berhasil login ke dalam sistem', '2026-02-15 10:21:54'),
(105, 1, 'Berhasil login ke dalam sistem', '2026-02-15 10:22:13'),
(106, 1, 'Melakukan logout dari sistem', '2026-02-15 10:22:15'),
(107, 1, 'Berhasil login ke dalam sistem', '2026-02-15 10:22:41'),
(108, 1, 'Melakukan logout dari sistem', '2026-02-15 10:22:43'),
(109, 1, 'Berhasil login ke dalam sistem', '2026-02-15 10:22:58'),
(110, 1, 'Melakukan logout dari sistem', '2026-02-15 10:23:00'),
(111, 1, 'Berhasil login ke dalam sistem', '2026-02-15 10:24:12'),
(112, 1, 'Melakukan logout dari sistem', '2026-02-15 10:24:57');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_barang`
--

CREATE TABLE `tb_barang` (
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(50) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `id_kategori` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `harga_sewa` decimal(10,0) NOT NULL,
  `status` enum('tersedia','dipelihara','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_barang`
--

INSERT INTO `tb_barang` (`id_barang`, `nama_barang`, `kategori`, `id_kategori`, `stok`, `foto`, `harga_sewa`, `status`) VALUES
(4, 'Proyektor', NULL, 2, 5, NULL, 0, 'tersedia'),
(5, 'Tipe-x', NULL, 1, 4, NULL, 0, 'tersedia'),
(6, 'Pulpen', NULL, 1, 3, NULL, 0, 'tersedia'),
(11, 'Pensil', NULL, 1, 4, NULL, 0, 'tersedia'),
(12, 'Spidol', NULL, 1, 5, NULL, 0, 'tersedia'),
(13, 'Lem', NULL, 4, 5, NULL, 0, 'tersedia'),
(14, 'Karton Panjang', NULL, 1, 4, NULL, 0, 'tersedia');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_denda`
--

CREATE TABLE `tb_denda` (
  `id_denda` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `jumlah_denda` decimal(10,0) NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_kategori`
--

CREATE TABLE `tb_kategori` (
  `id_kategori` int(11) NOT NULL,
  `kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_kategori`
--

INSERT INTO `tb_kategori` (`id_kategori`, `kategori`) VALUES
(1, 'Alat Tulis'),
(2, 'Alat Elektronik'),
(4, 'Lain-lain');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_peminjaman`
--

CREATE TABLE `tb_peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `id_petugas` int(11) DEFAULT NULL,
  `username` varchar(32) DEFAULT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali` date NOT NULL,
  `total_bayar` decimal(10,0) DEFAULT NULL,
  `status_transaksi` enum('booking','dipinjam','selesai','dibatalkan','proses kembali') NOT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_peminjaman`
--

INSERT INTO `tb_peminjaman` (`id_peminjaman`, `id_user`, `id_barang`, `id_petugas`, `username`, `tgl_pinjam`, `tgl_kembali`, `total_bayar`, `status_transaksi`, `jumlah`) VALUES
(6, 7, 4, NULL, 'anotheropit', '2026-02-02', '2026-02-02', 0, 'dibatalkan', 0),
(10, 7, 5, NULL, 'anotheropit', '2026-02-03', '2026-02-03', 0, 'selesai', 0),
(11, 6, 6, NULL, 'eL', '2026-02-03', '2026-02-04', 0, 'selesai', 0),
(12, 7, 11, NULL, 'anotheropit', '2026-02-03', '2026-02-04', 0, 'selesai', 0),
(13, 7, 12, NULL, 'anotheropit', '2026-02-04', '2026-02-05', 0, 'selesai', 1),
(14, 7, 12, NULL, 'anotheropit', '2026-02-04', '2026-02-06', 0, 'selesai', 1),
(19, 1, 4, 1, 'admin', '2026-02-08', '2026-02-09', 0, 'dibatalkan', 1),
(30, 7, 14, 3, 'anotheropit', '2026-02-15', '2026-02-16', 0, 'selesai', 1),
(31, 7, 13, 3, 'anotheropit', '2026-02-15', '2026-02-16', 0, 'selesai', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pengembalian`
--

CREATE TABLE `tb_pengembalian` (
  `id_pengembalian` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `tgl_pengembalian` date NOT NULL,
  `kondisi_alat` varchar(255) NOT NULL,
  `denda` decimal(10,0) NOT NULL,
  `id_petugas` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_pengembalian`
--

INSERT INTO `tb_pengembalian` (`id_pengembalian`, `id_peminjaman`, `tgl_pengembalian`, `kondisi_alat`, `denda`, `id_petugas`, `jumlah`) VALUES
(1, 11, '2026-02-04', 'Baik', 0, 1, 0),
(2, 12, '2026-02-04', 'Baik', 0, 1, 0),
(3, 13, '2026-02-04', 'Baik', 0, 1, 0),
(4, 14, '2026-02-05', 'Baik', 0, 1, 0),
(5, 30, '2026-02-15', 'Baik', 0, 3, 0),
(6, 31, '2026-02-15', 'Baik', 0, 3, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `role` enum('admin','petugas','peminjam') NOT NULL,
  `kontak` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `username`, `password`, `nama_lengkap`, `role`, `kontak`) VALUES
(1, 'admin', '1234', 'admin', 'admin', '12304598'),
(2, 'dodo', '12345', 'dodo', 'peminjam', '67676767'),
(3, 'petugas', '123', 'Petugas Layanan', 'petugas', ''),
(6, 'eL', '1906', 'Levina', 'peminjam', ''),
(7, 'anotheropit', '2007', 'opitoo', 'peminjam', ''),
(10, 'dede', '12345', 'Dede Daryana', 'peminjam', '');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`);

--
-- Indeks untuk tabel `tb_barang`
--
ALTER TABLE `tb_barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `tb_denda`
--
ALTER TABLE `tb_denda`
  ADD PRIMARY KEY (`id_denda`),
  ADD KEY `id_peminjaman` (`id_peminjaman`);

--
-- Indeks untuk tabel `tb_kategori`
--
ALTER TABLE `tb_kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `tb_peminjaman`
--
ALTER TABLE `tb_peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `id_user` (`id_user`,`id_barang`,`id_petugas`),
  ADD KEY `username` (`username`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indeks untuk tabel `tb_pengembalian`
--
ALTER TABLE `tb_pengembalian`
  ADD PRIMARY KEY (`id_pengembalian`),
  ADD KEY `id_peminjaman` (`id_peminjaman`);

--
-- Indeks untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT untuk tabel `tb_barang`
--
ALTER TABLE `tb_barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `tb_denda`
--
ALTER TABLE `tb_denda`
  MODIFY `id_denda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tb_kategori`
--
ALTER TABLE `tb_kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tb_peminjaman`
--
ALTER TABLE `tb_peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `tb_pengembalian`
--
ALTER TABLE `tb_pengembalian`
  MODIFY `id_pengembalian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tb_barang`
--
ALTER TABLE `tb_barang`
  ADD CONSTRAINT `tb_barang_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `tb_kategori` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_denda`
--
ALTER TABLE `tb_denda`
  ADD CONSTRAINT `tb_denda_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `tb_peminjaman` (`id_peminjaman`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_peminjaman`
--
ALTER TABLE `tb_peminjaman`
  ADD CONSTRAINT `tb_peminjaman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_peminjaman_ibfk_2` FOREIGN KEY (`username`) REFERENCES `tb_user` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_peminjaman_ibfk_3` FOREIGN KEY (`id_barang`) REFERENCES `tb_barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_pengembalian`
--
ALTER TABLE `tb_pengembalian`
  ADD CONSTRAINT `tb_pengembalian_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `tb_peminjaman` (`id_peminjaman`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
