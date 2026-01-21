-- --------------------------------------------------------
-- Table structure for table `emails`
-- --------------------------------------------------------

CREATE TABLE `emails` (
  `sn` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`sn`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
