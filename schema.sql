-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 23, 2026 at 04:54 PM
-- Server version: 10.5.29-MariaDB
-- PHP Version: 8.4.16

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
(1, 'Node-Namche-01', 1, 'active', '2026-01-21 22:39:28'),
(2, 'Node-Lukla-01', 2, 'active', '2026-01-21 23:05:28'),
(3, 'kathmandu-01', 16, 'active', '2026-01-22 11:13:01'),
(4, 'Node-Dingboche-01', 4, 'active', '2026-01-21 22:38:12'),
(5, 'Node-GorakShep-01', 5, 'inactive', '2026-01-21 08:11:08'),
(6, 'Node-Phakding-01', 6, 'active', '2026-01-21 23:04:22'),
(7, 'Node-Khumjung-01', 7, 'maintenance', '2026-01-20 10:11:08'),
(8, 'Node-Pangboche-01', 8, 'active', '2026-01-21 09:56:08'),
(9, 'Node-Pheriche-01', 9, 'active', '2026-01-23 09:19:58'),
(10, 'Node-Lobuche-01', 10, 'active', '2026-01-21 09:51:08');

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `sn` int(11) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emails`
--

INSERT INTO `emails` (`sn`, `email`) VALUES
(1, 'kandelze123@gmail.com'),
(4, 'sakchyambastakoti36@gmail.com'),
(3, 'sakshyamupadhyaya81@uniglobecollege.edu.np'),
(2, 'zenithkandel0@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `fcm_tokens`
--

CREATE TABLE `fcm_tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(512) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fcm_tokens`
--

INSERT INTO `fcm_tokens` (`id`, `token`, `user_agent`, `user_id`, `active`, `created_at`, `updated_at`) VALUES
(1, 'ftzgT5ggFGYjnz0Gv1Bkl2:APA91bHfJQ2MUo6bFqlSYj_vHnH6fZ-01vM-WKZCqtd-wedn1Wp-6t_RPuwWvfwPX3WAfHd8FXjLuhUx1G2nwGJzBW3cDLlxUc2fCEHa2PI91YtR7qFwWMA', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 1, '2026-01-21 21:22:36', '2026-01-21 23:24:24'),
(2, 'ebJLG78MKmCgO1LEdK5GWV:APA91bHe7QZ_uHTy3Wn534Hgw4atAjO-OI5hLzkyTTQyPnx0gB2nWlLknuE2l_ma89rLsXuLQOQzH3Zi4XV8Pkc_ihyTM1gLQqUVZgA0o4Ha8ZGfuZCXXNY', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', NULL, 1, '2026-01-21 21:39:35', '2026-01-21 21:40:20'),
(3, 'ebJLG78MKmCgO1LEdK5GWV:APA91bH2QtXlK9j3fUn4iyrYFZ9fI_6x3Dv3Dx4vsu2j1pCPhHDUEwlKi4U1AUB3ILOaHPenqUxHqcIHkSKQGjdXfMXwkd2S2mlDD8SrOca8oG3WLOszbiY', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', NULL, 1, '2026-01-21 21:40:49', '2026-01-21 21:56:42'),
(4, 'ebJLG78MKmCgO1LEdK5GWV:APA91bGhvJ3ZHn6j-q1a9iEL9qQhOFqKeMv7KK5D3K_rf7kQJz7OEIZh_9VOfSq-fwvQVifSGArIzRncEBDjfaqwU7cIuc4HMYGfCafoS1rmz9Hdc2UDPV0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', NULL, 1, '2026-01-21 21:56:52', '2026-01-22 10:15:45'),
(5, 'd1sU8W4-Buzh3uYp2U2Vp0:APA91bGaoz4noghm3ajrGf2sJfEaCxwidFGD8EKwsR3KIZvlYUJxPeqVzqvCaGNrZ2uK9l6_0_C3FkdjZxcH6ts41uxMsURdiV2UJ5652JRtQltW9xWsRDY', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, 1, '2026-01-21 23:04:18', '2026-01-21 23:08:29'),
(6, 'fU_zj_MujsDCIlxEg1-e9X:APA91bFy5rqCVUNmTFZ8jlTanIGcn3ZSiugifg0ec8VAvIPF7FbucO9xpAZvGPXT1jRGT6-XOOyYksS0puOwF91FUyBgXoylOB60zQufVLdRTxLwtqV-20k', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', NULL, 1, '2026-01-22 06:05:20', '2026-01-22 08:19:14');

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
(1, 'Nepal Army Helicopter Unit', '+977-1-4412345', '30-45 mins', 'available', 'Kathmandu', '[9,11,13,14]'),
(2, 'Khumbu Ground Search Team', '+977-38-540116', '2-4 hours', 'available', 'Namche Bazaar', '[13,10,9]'),
(3, 'Himalayan Rescue Association', '+977-1-4440292', '1-2 hours', 'available', 'Pheriche', '[13,10,9]'),
(4, 'Everest ER Clinic', '+977-38-540071', '30 mins', 'available', 'Lukla', '[4,2,1,0,3,10,12,13]'),
(5, 'Local Sherpa Rescue', '+977-9841234567', '1 hour', 'dispatched', 'Tengboche', '[3,0,1]'),
(6, 'Emergency Food Supply', '+977-9851234567', '6-8 hours', 'available', 'Namche Bazaar', '[9]'),
(7, 'Mountain Medicine Center', '+977-1-4411890', '3-4 hours', 'available', 'Kathmandu', '[14,11,9]'),
(8, 'Red Cross Nepal - Supplies', '+977-1-4270650', '4-6 hours', 'available', 'Kathmandu', '[8,9,12,14,3]');

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
(1, 'location', '{\n  \"1\": \"Namche Bazaar\",\n  \"2\": \"Lukla Village\",\n  \"3\": \"Tengboche\",\n  \"4\": \"Dingboche\",\n  \"5\": \"Gorak Shep\",\n  \"6\": \"Phakding\",\n  \"7\": \"Khumjung\",\n  \"8\": \"Pangboche\",\n  \"9\": \"Pheriche\",\n  \"10\": \"Lobuche\",\n  \"11\": \"Syangboche\",\n  \"12\": \"Thame\",\n  \"13\": \"Gokyo\",\n  \"14\": \"Machermo\",\n  \"15\": \"Chhukung\",\n  \"16\": \"Kathmandu\"\n}', 'Maps location codes to village names in Solukhumbu region', '2026-01-22 08:34:37'),
(2, 'message', '{\n  \"0\": \"Emergency - Critical Situation\",\n  \"1\": \"Medical Emergency\",\n  \"2\": \"Medicine Shortage\",\n  \"3\": \"Evacuation Needed\",\n  \"4\": \"Status OK - Situation Normal\",\n  \"5\": \"Injury Reported\",\n  \"6\": \"Food Shortage\",\n  \"7\": \"Water Shortage\",\n  \"8\": \"Weather Alert\",\n  \"9\": \"Lost Person\",\n  \"10\": \"Animal Attack\",\n  \"11\": \"Landslide\",\n  \"12\": \"Snow Storm\",\n  \"13\": \"Equipment Failure\",\n  \"14\": \"Other Emergency\"\n}\n', 'Maps message codes to emergency types', '2026-01-22 07:18:34');

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
(76, 3, -58, 7, '2026-01-22 08:33:17', 'active'),
(77, 3, -56, 7, '2026-01-22 08:33:48', 'active'),
(78, 3, -65, 1, '2026-01-22 08:34:39', 'active'),
(79, 3, -60, 5, '2026-01-22 08:35:21', 'active'),
(80, 3, -55, 0, '2026-01-22 08:48:45', 'active'),
(81, 3, -55, 1, '2026-01-22 08:49:20', 'active'),
(82, 3, -56, 1, '2026-01-22 08:57:30', 'active'),
(83, 3, -57, 1, '2026-01-22 08:59:41', 'active'),
(84, 3, -58, 7, '2026-01-22 08:59:47', 'active'),
(85, 3, -58, 11, '2026-01-22 08:59:54', 'active'),
(86, 3, -70, 6, '2026-01-22 09:56:25', 'active'),
(87, 3, -67, 7, '2026-01-22 10:15:07', 'active'),
(88, 3, -67, 14, '2026-01-22 10:15:17', 'active'),
(89, 3, -68, 13, '2026-01-22 10:15:50', 'active'),
(90, 3, -67, 1, '2026-01-22 10:16:00', 'active'),
(91, 3, -73, 7, '2026-01-22 10:24:02', 'active'),
(92, 3, -72, 7, '2026-01-22 10:24:17', 'active'),
(93, 3, -66, 10, '2026-01-22 10:24:56', 'active'),
(94, 3, -65, 7, '2026-01-22 10:28:01', 'active'),
(95, 3, -59, 5, '2026-01-22 10:28:59', 'active'),
(96, 3, -60, 0, '2026-01-22 10:29:19', 'active'),
(97, 3, -60, 5, '2026-01-22 10:29:38', 'active'),
(98, 3, -65, 7, '2026-01-22 10:30:22', 'active'),
(99, 3, -56, 5, '2026-01-22 10:39:10', 'active'),
(100, 3, -64, 4, '2026-01-22 10:41:09', 'active'),
(101, 3, -57, 8, '2026-01-22 10:54:29', 'active'),
(102, 3, -57, 4, '2026-01-22 10:54:52', 'active'),
(103, 3, -56, 7, '2026-01-22 10:55:20', 'active'),
(104, 3, -62, 1, '2026-01-22 11:11:21', 'active'),
(105, 3, -61, 1, '2026-01-22 11:11:53', 'active'),
(106, 3, -62, 1, '2026-01-22 11:11:59', 'active'),
(107, 3, -60, 1, '2026-01-22 11:12:06', 'active'),
(108, 3, -61, 1, '2026-01-22 11:12:13', 'active'),
(109, 3, -59, 1, '2026-01-22 11:12:19', 'active'),
(110, 3, -66, 9, '2026-01-22 11:13:01', 'resolved'),
(111, 9, -12, 2, '2026-01-23 09:19:58', 'resolved');

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
(1, 'Admin User', 'admin@lifeline.np', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-01-23 07:39:57', '2026-01-21 10:11:08'),
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
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`sn`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `fcm_tokens`
--
ALTER TABLE `fcm_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `idx_active` (`active`),
  ADD KEY `idx_user_id` (`user_id`);

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
  MODIFY `DID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fcm_tokens`
--
ALTER TABLE `fcm_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `MID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

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
