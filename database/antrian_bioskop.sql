-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2025 at 07:29 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
-- Table structure for table `antrean`
--

CREATE TABLE `antrean` (
  `id` int(11) NOT NULL,
  `film` varchar(100) NOT NULL,
  `waktu` datetime NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `loket` varchar(50) DEFAULT 'Loket 1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `antrean`
--

INSERT INTO `antrean` (`id`, `film`, `waktu`, `nama`, `loket`) VALUES
(1, 'Venom: The Last Dance', '2025-10-20 12:14:26', 'Budi Santoso', 'Loket 1'),
(2, 'Inside Out 2', '2025-10-20 12:14:26', 'Siti Rahma', 'Loket 2'),
(3, 'Deadpool & Wolverine', '2025-10-20 12:14:26', 'Andi Wijaya', 'Loket 1'),
(4, 'Agak Laen', '2025-10-20 12:14:26', 'Nia Rahma', 'Loket 2');

-- --------------------------------------------------------

--
-- Table structure for table `counters`
--

CREATE TABLE `counters` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `counters`
--

INSERT INTO `counters` (`id`, `name`, `created_at`) VALUES
(1, 'Loket 1', '2025-10-15 12:34:09'),
(2, 'Loket 2', '2025-10-15 12:34:09');

-- --------------------------------------------------------

--
-- Table structure for table `films`
--

CREATE TABLE `films` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `release_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `films`
--

INSERT INTO `films` (`id`, `title`, `genre`, `duration`, `release_date`) VALUES
(1, 'Avatar 2', 'Action', 180, '2023-12-16'),
(3, 'Fast X', 'Action', 145, '2023-05-19'),
(4, 'Avengers: Endgame', NULL, NULL, NULL),
(5, 'Spiderman: No Way Home', NULL, NULL, NULL),
(6, 'Frozen II', NULL, NULL, NULL),
(12, 'Inside Out 2', NULL, NULL, NULL),
(13, 'Venom 3', NULL, NULL, NULL),
(14, 'Moana 2', NULL, NULL, NULL),
(15, 'Deadpool & Wolverine', NULL, NULL, NULL),
(16, 'Asab Neraka', NULL, 120, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(150) DEFAULT 'Pengunjung',
  `film_id` int(11) DEFAULT NULL,
  `status` enum('waiting','serving','done','skipped') DEFAULT 'waiting',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `served_at` timestamp NULL DEFAULT NULL,
  `counter_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `code`, `name`, `film_id`, `status`, `created_at`, `served_at`, `counter_id`) VALUES
(1, 'B-001', 'Nabila', NULL, 'done', '2025-10-15 12:35:10', '2025-10-15 12:58:18', 1),
(2, 'B-002', 'Nia', NULL, 'done', '2025-10-15 12:45:37', '2025-10-15 12:58:22', 2),
(3, 'B-003', 'Pengunjung', NULL, 'done', '2025-10-15 12:57:10', '2025-10-15 13:08:39', 1),
(4, 'B-004', 'nadia', NULL, 'done', '2025-10-15 13:07:54', '2025-10-15 13:08:43', 2),
(5, 'B-005', 'Pengunjung', NULL, 'done', '2025-10-15 13:09:18', '2025-10-15 13:42:48', 1),
(6, 'B-006', 'Pengunjung', NULL, 'done', '2025-10-15 13:10:14', '2025-10-15 13:42:55', 2),
(7, 'B-007', 'Nia', 1, 'done', '2025-10-16 04:08:47', '2025-10-16 04:17:30', 1),
(8, 'B-008', 'naldi', 5, 'done', '2025-10-16 04:11:38', '2025-10-16 04:18:10', 2),
(9, 'B-009', 'Nadia', 14, 'done', '2025-10-16 06:51:43', '2025-10-16 06:52:38', 1),
(10, 'B-010', 'acd', 1, 'done', '2025-10-16 07:12:47', '2025-10-16 07:13:13', 1),
(11, 'B-011', 'ata', 1, 'done', '2025-10-16 07:13:01', '2025-10-16 07:15:32', 2),
(12, 'B-012', 'naldi', 13, 'done', '2025-10-18 14:57:18', '2025-10-18 14:57:44', 2),
(13, 'B-013', 'Pengunjung', 1, 'done', '2025-10-20 04:51:17', '2025-10-20 04:52:23', 1),
(14, 'B-014', 'Nadia', 4, 'done', '2025-10-20 05:12:07', '2025-10-20 05:12:31', 1),
(15, 'B-015', 'Nia', 15, 'done', '2025-10-20 05:15:16', '2025-10-20 05:15:36', 2),
(16, 'B-016', 'Nabila', 12, 'serving', '2025-10-20 05:19:01', '2025-10-20 05:19:29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','petugas') NOT NULL,
  `name` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `name`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'admin', 'Administrator'),
(2, 'petugas', '570c396b3fc856eceb8aa7357f32af1a', 'petugas', 'Petugas Loket');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `antrean`
--
ALTER TABLE `antrean`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `counters`
--
ALTER TABLE `counters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `films`
--
ALTER TABLE `films`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `counter_id` (`counter_id`),
  ADD KEY `fk_film` (`film_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `antrean`
--
ALTER TABLE `antrean`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `counters`
--
ALTER TABLE `counters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `films`
--
ALTER TABLE `films`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `fk_film` FOREIGN KEY (`film_id`) REFERENCES `films` (`id`),
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`counter_id`) REFERENCES `counters` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
