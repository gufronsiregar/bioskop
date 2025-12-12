-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Des 2025 pada 08.14
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `antrian_bioskop`
--

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `antrean`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `antrean` (
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `antrian`
--

CREATE TABLE `antrian` (
  `id` int(11) NOT NULL,
  `nomor` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT '-',
  `status` enum('waiting','serving','done','skip') DEFAULT 'waiting',
  `dipanggil` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `counters`
--

CREATE TABLE `counters` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `counters`
--

INSERT INTO `counters` (`id`, `name`, `created_at`) VALUES
(1, 'Loket 1', '2025-10-15 12:34:09'),
(2, 'Loket 2', '2025-10-15 12:34:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `films`
--

CREATE TABLE `films` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `release_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `films`
--

INSERT INTO `films` (`id`, `title`, `genre`, `duration`, `release_date`) VALUES
(28, 'AGAK LAEN: MENYALA PANTIKU!', NULL, 119, NULL),
(29, 'ZOOTOPIA 2', NULL, 108, NULL),
(30, 'FIVE NIGHTS AT FREDDY\'S 2', NULL, 104, NULL),
(31, 'JUJUTSU KAISEN: SHIBUYA INCIDENT X THE CULLING GAME (EXECUTION)', NULL, 88, NULL),
(32, 'SCARLET', NULL, 111, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `loket_status`
--

CREATE TABLE `loket_status` (
  `id` int(11) NOT NULL,
  `current_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `loket_status`
--

INSERT INTO `loket_status` (`id`, `current_number`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tickets_new`
--

CREATE TABLE `tickets_new` (
  `id` int(11) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `film_id` int(11) DEFAULT NULL,
  `payment_method` varchar(20) DEFAULT 'Tunai',
  `card_number` varchar(30) DEFAULT NULL,
  `no_antrian` int(11) NOT NULL,
  `status` enum('waiting','serving','skipped','done') DEFAULT 'waiting',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `served_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tickets_new`
--

INSERT INTO `tickets_new` (`id`, `code`, `name`, `film_id`, `payment_method`, `card_number`, `no_antrian`, `status`, `created_at`, `served_at`) VALUES
(1, 'B-001', 'Nabila', 28, 'Tunai', '', 0, 'done', '2025-12-10 15:36:38', '2025-12-10 15:38:07'),
(2, 'B-002', 'Nabila', 28, 'Tunai', '', 0, 'done', '2025-12-10 15:36:59', '2025-12-10 15:38:30'),
(3, 'B-003', 'Nabila', 28, 'Tunai', '', 0, 'done', '2025-12-10 15:37:09', '2025-12-10 15:41:16'),
(4, 'B-004', 'Nabila', 28, 'Tunai', '', 0, 'done', '2025-12-10 15:37:17', '2025-12-10 15:41:14'),
(5, 'B-005', 'Nabila', 28, 'Tunai', '', 0, 'done', '2025-12-10 15:37:25', '2025-12-10 15:41:45'),
(6, 'B-006', 'Nabila', 28, 'Tunai', '', 0, 'done', '2025-12-10 15:37:34', '2025-12-10 15:41:13'),
(7, 'B-007', 'Nabila', 28, 'Tunai', '', 0, 'done', '2025-12-10 15:37:44', '2025-12-10 15:38:37'),
(8, 'B-008', 'Nabila', 28, 'Tunai', '', 0, 'done', '2025-12-10 15:37:51', '2025-12-10 15:41:22'),
(9, 'B-009', 'Nabila', 28, 'Tunai', '', 0, 'done', '2025-12-10 15:37:56', '2025-12-10 15:38:23'),
(10, 'B-010', 'naldi', 31, 'Tunai', '', 0, 'skipped', '2025-12-11 01:44:03', '2025-12-11 02:13:56'),
(11, 'B-011', 'naldi', 31, 'Tunai', '', 0, 'done', '2025-12-11 01:44:14', '2025-12-11 02:14:07'),
(12, 'B-012', 'naldi', 31, 'Tunai', '', 0, 'done', '2025-12-11 01:44:25', '2025-12-11 02:14:12'),
(13, 'B-013', 'naldi', 31, 'Tunai', '', 0, 'skipped', '2025-12-11 01:44:32', '2025-12-11 02:14:22'),
(14, 'B-014', 'naldi', 31, 'Tunai', '', 0, 'waiting', '2025-12-11 01:44:42', NULL),
(15, 'B-015', 'naldi', 31, 'Tunai', '', 0, 'waiting', '2025-12-11 01:44:49', NULL),
(16, 'B-016', 'naldi', 31, 'Tunai', '', 0, 'waiting', '2025-12-11 01:45:01', NULL),
(17, 'B-017', 'naldi', 31, 'Tunai', '', 0, 'waiting', '2025-12-11 01:45:21', NULL),
(18, 'B-018', 'naldi', 31, 'Tunai', '', 0, 'done', '2025-12-11 01:45:29', '2025-12-11 02:14:16'),
(19, 'B-019', 'naldi', 31, 'Tunai', '', 0, 'done', '2025-12-11 01:45:39', '2025-12-11 02:14:19'),
(20, 'B-020', 'lesty', 28, 'Tunai', '', 0, 'waiting', '2025-12-11 02:13:28', NULL),
(21, 'B-021', 'Nabila', 28, 'Tunai', '', 0, 'waiting', '2025-12-11 02:15:26', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','petugas') NOT NULL,
  `name` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `name`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'admin', 'Administrator'),
(2, 'petugas', '570c396b3fc856eceb8aa7357f32af1a', 'petugas', 'Petugas Loket');

-- --------------------------------------------------------

--
-- Struktur untuk view `antrean`
--
DROP TABLE IF EXISTS `antrean`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `antrean`  AS SELECT `tickets_new`.`id` AS `id`, `tickets_new`.`code` AS `nomor_antrean`, `tickets_new`.`created_at` AS `waktu_daftar`, CASE WHEN `tickets_new`.`status` = 'done' THEN 'selesai' WHEN `tickets_new`.`status` = 'serving' THEN 'dipanggil' ELSE 'menunggu' END AS `status`, `tickets_new`.`counter_id` AS `loket_id` FROM `tickets_new` ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `antrian`
--
ALTER TABLE `antrian`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `counters`
--
ALTER TABLE `counters`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `films`
--
ALTER TABLE `films`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `loket_status`
--
ALTER TABLE `loket_status`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tickets_new`
--
ALTER TABLE `tickets_new`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `antrian`
--
ALTER TABLE `antrian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `counters`
--
ALTER TABLE `counters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `films`
--
ALTER TABLE `films`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `tickets_new`
--
ALTER TABLE `tickets_new`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
