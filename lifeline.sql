-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2026 at 06:28 PM
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
-- Database: `lifeline`
--

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `DID` int(10) NOT NULL,
  `device_name` varchar(100) DEFAULT NULL,
  `LID` int(10) NOT NULL COMMENT 'Location ID mapped from indexes',
  `status` enum('active','inactive','maintenance') DEFAULT 'active',
  `last_ping` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`DID`, `device_name`, `LID`, `status`, `last_ping`) VALUES
(1, 'Node-Namche-01', 1, 'active', '2026-01-21 10:11:08'),
(2, 'Node-Lukla-01', 2, 'active', '2026-01-21 10:06:08'),
(3, 'Node-Tengboche-01', 3, 'active', '2026-01-21 10:01:08'),
(4, 'Node-Dingboche-01', 4, 'active', '2026-01-21 16:15:01'),
(5, 'Node-GorakShep-01', 5, 'inactive', '2026-01-21 08:11:08'),
(6, 'Node-Phakding-01', 6, 'active', '2026-01-21 10:03:08'),
(7, 'Node-Khumjung-01', 7, 'maintenance', '2026-01-20 10:11:08'),
(8, 'Node-Pangboche-01', 8, 'active', '2026-01-21 09:56:08'),
(9, 'Node-Pheriche-01', 9, 'active', '2026-01-21 10:08:08'),
(10, 'Node-Lobuche-01', 10, 'active', '2026-01-21 09:51:08');

-- --------------------------------------------------------

--
-- Table structure for table `helps`
--

CREATE TABLE `helps` (
  `HID` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `eta` varchar(50) DEFAULT NULL COMMENT 'Estimated time of arrival',
  `status` enum('available','dispatched','busy') DEFAULT 'available',
  `location` varchar(200) DEFAULT NULL,
  `for_messages` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `helps`
--

INSERT INTO `helps` (`HID`, `name`, `contact`, `eta`, `status`, `location`, `for_messages`) VALUES
(1, 'Nepal Army Helicopter Unit', '+977-1-4412345', '30-45 mins', 'available', 'Kathmandu', ''),
(2, 'Khumbu Ground Search Team', '+977-38-540116', '2-4 hours', 'available', 'Namche Bazaar', ''),
(3, 'Himalayan Rescue Association', '+977-1-4440292', '1-2 hours', 'available', 'Pheriche', ''),
(4, 'Everest ER Clinic', '+977-38-540071', '30 mins', 'available', 'Lukla', ''),
(5, 'Local Sherpa Rescue', '+977-9841234567', '1 hour', 'dispatched', 'Tengboche', ''),
(6, 'Emergency Food Supply', '+977-9851234567', '6-8 hours', 'available', 'Namche Bazaar', '[9]'),
(7, 'Mountain Medicine Center', '+977-1-4411890', '3-4 hours', 'available', 'Kathmandu', ''),
(8, 'Red Cross Nepal - Supplies', '+977-1-4270650', '4-6 hours', 'available', 'Kathmandu', '');

-- --------------------------------------------------------

--
-- Table structure for table `indexes`
--

CREATE TABLE `indexes` (
  `IID` int(11) NOT NULL,
  `type` enum('location','message','help') NOT NULL,
  `mapping` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'JSON object mapping integer codes to meanings. For help type: maps HID to array of applicable message codes' CHECK (json_valid(`mapping`)),
  `description` text DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `indexes`
--

INSERT INTO `indexes` (`IID`, `type`, `mapping`, `description`, `updated_at`) VALUES
(1, 'location', '{\n  \"1\": \"Namche Bazaar\",\n  \"2\": \"Lukla Village\",\n  \"3\": \"Tengboche\",\n  \"4\": \"Dingboche\",\n  \"5\": \"Gorak Shep\",\n  \"6\": \"Phakding\",\n  \"7\": \"Khumjung\",\n  \"8\": \"Pangboche\",\n  \"9\": \"Pheriche\",\n  \"10\": \"Lobuche\",\n  \"11\": \"Syangboche\",\n  \"12\": \"Thame\",\n  \"13\": \"Gokyo\",\n  \"14\": \"Machermo\",\n  \"15\": \"Chhukung\"\n}', 'Maps location codes to village names in Solukhumbu region', '2026-01-21 10:11:08'),
(2, 'message', '{\n  \"1\": \"Medical Emergency - Altitude Sickness\",\n  \"2\": \"Medical Emergency - Injury\",\n  \"3\": \"Medical Emergency - Illness\",\n  \"4\": \"Search and Rescue Required\",\n  \"5\": \"Avalanche Alert\",\n  \"6\": \"Landslide Warning\",\n  \"7\": \"Fire Emergency\",\n  \"8\": \"Flood Warning\",\n  \"9\": \"Lost/Missing Person\",\n  \"10\": \"Equipment Failure\",\n  \"11\": \"Weather Emergency\",\n  \"12\": \"Infrastructure Damage\",\n  \"13\": \"Food/Water Shortage\",\n  \"14\": \"Communication Lost\",\n  \"15\": \"All Clear - Situation Normal\"\n}', 'Maps message codes to emergency types', '2026-01-21 10:11:08');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `MID` int(10) NOT NULL,
  `DID` int(10) NOT NULL COMMENT 'Device ID that sent the message',
  `RSSI` int(10) DEFAULT NULL COMMENT 'Signal strength indicator',
  `message_code` int(10) NOT NULL COMMENT 'Message code mapped from indexes',
  `timestamp` datetime DEFAULT current_timestamp(),
  `status` text NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`MID`, `DID`, `RSSI`, `message_code`, `timestamp`, `status`) VALUES
(1, 1, -65, 1, '2026-01-21 09:41:08', 'active'),
(2, 2, -72, 15, '2026-01-21 09:11:08', 'active'),
(3, 4, -58, 2, '2026-01-21 10:01:08', 'resolved'),
(4, 6, -80, 6, '2026-01-21 09:26:08', ''),
(5, 8, -68, 11, '2026-01-21 09:51:08', ''),
(6, 9, -55, 3, '2026-01-21 09:56:08', ''),
(7, 3, -75, 15, '2026-01-21 08:11:08', ''),
(8, 10, -82, 14, '2026-01-21 10:06:08', ''),
(9, 4, -65, 3, '2026-01-21 16:15:01', '');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','operator') DEFAULT 'operator',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UID`, `name`, `email`, `password`, `role`, `last_login`, `created_at`) VALUES
(1, 'Admin User', 'admin@lifeline.np', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-01-21 21:48:30', '2026-01-21 10:11:08'),
(2, 'Operator One', 'operator1@lifeline.np', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'operator', NULL, '2026-01-21 10:11:08'),
(3, 'Operator Two', 'operator2@lifeline.np', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'operator', NULL, '2026-01-21 10:11:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`DID`);

--
-- Indexes for table `helps`
--
ALTER TABLE `helps`
  ADD PRIMARY KEY (`HID`);

--
-- Indexes for table `indexes`
--
ALTER TABLE `indexes`
  ADD PRIMARY KEY (`IID`),
  ADD UNIQUE KEY `type_unique` (`type`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`MID`),
  ADD KEY `fk_device` (`DID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `DID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `helps`
--
ALTER TABLE `helps`
  MODIFY `HID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `indexes`
--
ALTER TABLE `indexes`
  MODIFY `IID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `MID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_device` FOREIGN KEY (`DID`) REFERENCES `devices` (`DID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
