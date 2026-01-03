-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 03, 2026 at 04:55 PM
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
-- Database: `dashboarddb`
--

-- --------------------------------------------------------

--
-- Table structure for table `file_uploads`
--

CREATE TABLE `file_uploads` (
  `post_ID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `fileName` varchar(255) NOT NULL,
  `filePath` varchar(255) NOT NULL,
  `post_date` date NOT NULL DEFAULT current_timestamp(),
  `caption` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `passcode` char(255) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp(),
  `userType` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `passcode`, `reg_date`, `userType`) VALUES
(47, 'admin', '$2y$10$SAHWLRazTyD3ocF0iKRKaeEvOl6sxE4nHdFNHJvJ2dTfCd1D3cWpO', '2025-12-23 15:22:01', 'admin'),
(48, 'user', '$2y$10$xsMPSGVdKvW10jDHnvAOquN7kODzNC9SXfEv49R8qC7SQzwHppRhG', '2025-12-23 15:22:42', ''),
(50, 'Gelo', '$2y$10$N/hvpfEM3gy10RLQH/hMXurt7SPcH8nR3vqRdFe/x4x1pyVK61D0S', '2025-12-24 00:50:46', 'admin'),
(51, 'user1', '$2y$10$/ssXSh0as03du3JD1FkU2uKGlIfRdJqxek7VfwDsJcF6H92LkCKOe', '2025-12-28 15:59:56', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `file_uploads`
--
ALTER TABLE `file_uploads`
  ADD PRIMARY KEY (`post_ID`),
  ADD KEY `post_ID` (`post_ID`),
  ADD KEY `post_ID_2` (`post_ID`),
  ADD KEY `post_ID_3` (`post_ID`,`userID`,`fileName`,`filePath`,`post_date`),
  ADD KEY `caption` (`caption`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `userID` (`userID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `file_uploads`
--
ALTER TABLE `file_uploads`
  MODIFY `post_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
