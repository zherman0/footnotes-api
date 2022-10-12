-- phpMyAdmin SQL Dump
-- version 5.2.0-1.fc36
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 12, 2022 at 05:40 PM
-- Server version: 10.5.16-MariaDB
-- PHP Version: 7.4.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `footnotes-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `hikingLocations`
--

CREATE TABLE `hikingLocations` (
  `locationId` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `directions` varchar(1000) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'enabled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hikingLocations`
--

INSERT INTO `hikingLocations` (`locationId`, `name`, `description`, `directions`, `last_updated`, `status`) VALUES
(1, 'Black Hills', 'Located in South Dakota, the Black Hills hike goes up to Mt Rushmore', 'Take an interstate to South Dakota', '2022-10-12 03:10:12', 'enabled'),
(2, 'Delicate Arch', 'Located in Arches national park, delicate arch is quite delicate.', 'Drive to Utah', '2022-10-12 03:11:37', 'enabled'),
(3, 'Adams Canyon', 'Adams Canyon in Utah has a waterfall at the end of it', 'Drive to Layton, Utah', '2022-10-12 03:12:32', 'enabled');

-- --------------------------------------------------------

--
-- Table structure for table `hikingLog`
--

CREATE TABLE `hikingLog` (
  `hikeId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `locationId` int(11) NOT NULL,
  `hikeDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userId` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'enabled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `fullname`, `username`, `email`, `status`) VALUES
(1, 'Test Hiker', 'hiker1', 'hiker1@example.com', 'enabled'),
(2, 'Test Hiker2', 'hiker2', 'hiker2@example.com', 'enabled');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hikingLocations`
--
ALTER TABLE `hikingLocations`
  ADD PRIMARY KEY (`locationId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hikingLocations`
--
ALTER TABLE `hikingLocations`
  MODIFY `locationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
