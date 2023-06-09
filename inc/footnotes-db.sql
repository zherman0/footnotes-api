-- Server version: 10.5.19-MariaDB
-- PHP Version: 7.4.33

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
CREATE DATABASE IF NOT EXISTS `footnotes-db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `footnotes-db`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hikingLocations`
--

INSERT INTO `hikingLocations` (`locationId`, `name`, `description`, `directions`, `last_updated`, `status`) VALUES
(1, 'Black Hills', 'Located in South Dakota, the Black Hills hike goes up to Mt Rushmore', 'Take an interstate to South Dakota', '2022-10-12 03:10:12', 'enabled'),
(2, 'Delicate Arch', 'Located in Arches national park, delicate arch is quite delicate.', 'Drive to Utah', '2022-10-12 03:11:37', 'enabled'),
(3, 'Adams Canyon', 'Adams Canyon in Utah has a waterfall at the end of it', 'Drive to Layton, Utah', '2022-10-12 03:12:32', 'enabled'),
(4, 'Empire Slot', 'Slot canyon in Utah', 'Central Utah', '2023-06-08 18:25:32', 'enabled');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hikingLog`
--

INSERT INTO `hikingLog` (`hikeId`, `userId`, `locationId`, `hikeDate`, `description`) VALUES
(1, 2, 2, '2023-06-07 06:00:00', 'Nice Day for a walk '),
(2, 1, 1, '2023-06-05 06:00:00', 'Sun and wind'),
(3, 2, 1, '2023-06-09 06:00:00', 'Another fun day out'),
(4, 3, 3, '2023-06-10 06:00:00', 'Very wet'),
(5, 3, 4, '2023-06-11 06:00:00', 'Very tight with lots of standing water');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `fullname`, `username`, `email`, `status`) VALUES
(1, 'Test Hiker', 'hiker1', 'hiker1@example.com', 'enabled'),
(2, 'Test Hiker2', 'hiker2', 'hiker2@example.com', 'enabled'),
(3, 'Jay Smith', 'jsmith', 'jsmith@example.com', 'enabled');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hikingLocations`
--
ALTER TABLE `hikingLocations`
  ADD PRIMARY KEY (`locationId`);

--
-- Indexes for table `hikingLog`
--
ALTER TABLE `hikingLog`
  ADD PRIMARY KEY (`hikeId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hikingLocations`
--
ALTER TABLE `hikingLocations`
  MODIFY `locationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hikingLog`
--
ALTER TABLE `hikingLog`
  MODIFY `hikeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;