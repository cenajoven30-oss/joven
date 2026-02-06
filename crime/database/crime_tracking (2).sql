-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2025 at 02:58 AM
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
-- Database: `crime_tracking`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evidence_files`
--

CREATE TABLE `evidence_files` (
  `evidence_id` int(11) NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `evidence` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `in_progress_ref`
--

CREATE TABLE `in_progress_ref` (
  `ref_id` int(11) NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pending_report_refs`
--

CREATE TABLE `pending_report_refs` (
  `ref_id` int(11) NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pending_report_refs`
--

INSERT INTO `pending_report_refs` (`ref_id`, `report_id`, `type`, `description`) VALUES
(1, 2, 'Assault', 'sasas'),
(2, 1, 'Assault', 'sdsd');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `evidence` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `date_reported` datetime DEFAULT current_timestamp(),
  `status` enum('pending','in-progress','resolved') DEFAULT 'pending',
  `reporter` varchar(20) DEFAULT NULL,
  `assigned_date` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`report_id`, `user_id`, `type`, `evidence`, `description`, `location`, `date_reported`, `status`, `reporter`, `assigned_date`) VALUES
(1, 2, 'Assault', 'uploads/boruto.jpg', 'sdsd', 'Malbago', '2025-10-03 00:00:00', 'resolved', 'dsd', '2025-02-01'),
(2, 7, 'Assault', 'uploads/tmp_17594712', 'sasas', 'Kodia', '2025-10-03 00:00:00', 'pending', 'asasa', NULL),
(6, 8, 'Assault', 'uploads/tmp_17595489', 'csasda', 'Kodia', '2025-10-04 00:00:00', 'resolved', 'saa', ''),
(8, 7, 'Vandalism', 'uploads/tmp_17598344', 'sasassas', 'Kodia', '2025-10-07 00:00:00', 'pending', 'sasa', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `resolved_report_refs`
--

CREATE TABLE `resolved_report_refs` (
  `ref_id` int(11) NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `fname` varchar(100) DEFAULT NULL,
  `mname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('citizen','admin','officer') DEFAULT 'citizen',
  `phone` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `fname`, `mname`, `lname`, `email`, `password`, `role`, `phone`) VALUES
(1, '', 'dave', '', '', 'sjdjsd@gmail.com', '$2y$10$GT9AGHolu/XsenVixXYO1uxLswg.UDKbhqh9JNHFnhd/GDLryMpwS', 'citizen', 2147483647),
(2, '', 'evad', '', '', 'dasasa@gmail.com', '$2y$10$rUymplzooUcthLbJbypNn.O9FU8AEBRDKYlc0NEndC6OY9CeDs7Au', 'citizen', 2147483647),
(7, '', 'admin', '', '', 'admin01@gmail.com', '$2y$10$ENFPZ3UQPWsU6Hdnq.7q/OiO9pd2g0UH/THdPV0PToleNdne2XaCS', 'admin', 9),
(8, '', 'punay', '', '', 'adbahjas@gmail.com', '$2y$10$aZh5U0zeCJRG1MKqVOMp.O66rtz9JfTxrEm28J39aCKB36x7hGi2O', 'citizen', 2939283),
(9, '', 'dave', '', '', 'asasasa9tle@gmail.com', '$2y$10$KDWS8LGFzcpfXsrTaQRsye6LF81hTlL04suNqkiA2PG8oqSP/oOde', 'citizen', 2147483647),
(11, '', 'dave', '', '', 'asasssasa9tle@gmail.com', '$2y$10$pYgD8K5y5a2PNTB4FILW0uaSBPigQ8A2IJsU3hfxxWTmIYLTPeCr2', 'citizen', 2147483647),
(12, '', 'dave', '', '', 'asasssasasasa9tle@gmail.com', '$2y$10$VzkEVqpopxcSmQTUMtlYKORY9gVxUOY00fs211h6/pztRTUGn8UQm', 'citizen', 2147483647),
(13, '', 'davex771', '', '', 'sadsds@gmail.com', '$2y$10$vubNJULoMv40Ml3dvhiOk.LWhMKFz7wiKbrcYKaFfD5FBEcN1FENe', 'citizen', 2147483647),
(14, '', 'evadd', '', '', 'esjndjsnd@gmail.com', '$2y$10$i1bnKjM1dHDr1PDVrccyuuEuhAzEg.r2MNcwXAZzKVn2UebpIqwCW', 'citizen', 2147483647),
(15, '', 'asasas', '', '', 'sasasas@gmail.com', '$2y$10$cVMyhSFqOX5TTCYZACPQVe/5YatxscerVyzmqL8imbXoRQVitTugu', 'citizen', 2147483647);

-- --------------------------------------------------------

--
-- Table structure for table `user_reports`
--

CREATE TABLE `user_reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `evidence_files`
--
ALTER TABLE `evidence_files`
  ADD PRIMARY KEY (`evidence_id`),
  ADD KEY `report_id` (`report_id`);

--
-- Indexes for table `in_progress_ref`
--
ALTER TABLE `in_progress_ref`
  ADD PRIMARY KEY (`ref_id`),
  ADD KEY `report_id` (`report_id`);

--
-- Indexes for table `pending_report_refs`
--
ALTER TABLE `pending_report_refs`
  ADD PRIMARY KEY (`ref_id`),
  ADD KEY `report_id` (`report_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `resolved_report_refs`
--
ALTER TABLE `resolved_report_refs`
  ADD PRIMARY KEY (`ref_id`),
  ADD KEY `report_id` (`report_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_reports`
--
ALTER TABLE `user_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `report_id` (`report_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evidence_files`
--
ALTER TABLE `evidence_files`
  MODIFY `evidence_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `in_progress_ref`
--
ALTER TABLE `in_progress_ref`
  MODIFY `ref_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pending_report_refs`
--
ALTER TABLE `pending_report_refs`
  MODIFY `ref_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `resolved_report_refs`
--
ALTER TABLE `resolved_report_refs`
  MODIFY `ref_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_reports`
--
ALTER TABLE `user_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `evidence_files`
--
ALTER TABLE `evidence_files`
  ADD CONSTRAINT `evidence_files_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`report_id`);

--
-- Constraints for table `in_progress_ref`
--
ALTER TABLE `in_progress_ref`
  ADD CONSTRAINT `in_progress_ref_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`report_id`);

--
-- Constraints for table `pending_report_refs`
--
ALTER TABLE `pending_report_refs`
  ADD CONSTRAINT `pending_report_refs_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`report_id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `resolved_report_refs`
--
ALTER TABLE `resolved_report_refs`
  ADD CONSTRAINT `resolved_report_refs_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`report_id`);

--
-- Constraints for table `user_reports`
--
ALTER TABLE `user_reports`
  ADD CONSTRAINT `user_reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_reports_ibfk_2` FOREIGN KEY (`report_id`) REFERENCES `reports` (`report_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
