-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2022 at 07:25 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lab4db`
--

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `msgid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `emailaddress` varchar(100) NOT NULL,
  `message` varchar(100) NOT NULL,
  `timestamp` datetime NOT NULL,
  `sent` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`msgid`, `userid`, `emailaddress`, `message`, `timestamp`, `sent`) VALUES
(72, 12, 'joshua.cohen.133@my.csun.edu', 'This message was sent by server', '2022-12-05 22:30:00', 0),
(73, 12, 'joshua.cohen.133@my.csun.edu', 'This message was sent by server', '2022-12-06 23:00:00', 0),
(74, 14, 'joshua.cohen.133@my.csun.edu', 'Hello, this is server test 2', '2022-12-06 16:30:00', 0),
(75, 14, 'joshua.cohen.133@my.csun.edu', 'Hello, this is server test 2', '2022-12-07 16:30:00', 0),
(76, 15, 'joshua.cohen.133@my.csun.edu', 'Hello, this is server test 4', '2022-12-08 13:00:00', 0),
(77, 15, 'joshua.cohen.133@my.csun.edu', 'Hello, this is server test 4', '2022-12-05 22:30:00', 0),
(79, 18, 'joshua.cohen.133@my.csun.edu', 'Hello, this is server test 6', '2022-12-06 11:00:00', 0),
(80, 18, 'joshua.cohen.133@my.csun.edu', 'Hello, this is server test 7', '2022-12-06 17:00:00', 0),
(81, 18, 'joshua.cohen.133@my.csun.edu', 'Hello, this is test 8', '2022-12-08 13:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(512) NOT NULL,
  `salt` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userid`, `username`, `password`, `salt`) VALUES
(12, 'username1', 'dab7d51ed2a0e5578c0eef7f2499b7066006030cf835bffa7ef057c73310c4c660dd10bf2f80da2a8158ef7a433bdd714b83983fec4eec3be144ff34360c9746', '624727'),
(13, 'username2', '5a1ff38b23911b22e6f0bad46c7f5e4314f6eca14d97e25d3fc2a73b35a08079584b917efc7e4f731618c0d0a5be9a84301f34d25c303dd5065ded430e5a4923', '714322'),
(14, 'username3', '430b735f780379df3e8fb683df5e066f40928b3de4886c1692031144b498d56bd8e004e076e9e55bc3f943bdfd0666ceafa82e7cddf1993cae942a4659ebf755', '580687'),
(15, 'username4', '74e85df8758770f44494405e574ca045cf42cc23972dad495234bab8a77d6c376aed45daec852b4f53d5f6722905214e2c39de81f6718937159a1185933f48f2', '252605'),
(17, 'username5', '8a2a2e9b856753a210c5c4a8307a1f5249aa972217e4612b70e177f51581fe50200644aaf3f3a7ed45f5701372012de5282fef8b7859321c3d14ceef36b858da', '736992'),
(18, 'username6', '08938ca7814bcc8f7e3896b2b1fc928b7059f71a505520346af9c4ce86674aa7dee9d3b8f93ad84745c5598b39e5ac53a93b7c3bd73401ef557c18c11b5790a6', '442140'),
(19, 'username7', '026342362d26e8aaeebde712b4c403d5cc7d2839dc0e4fde257372d0ce8451d6459b77d7788a715c96e3664d9ee4cc6b457e4e81c5f3e756bdd4f0ee4d7a0cab', '189000'),
(20, 'username8', '23119fde20a874d2fa70319b4e1b391738ed105059f766b6a6965df45e9f623c1e3b7a86288e2c79336e0005dea5d7dc46fa1c63ef238e85d27bc7d51d62877b', '702438');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`msgid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `msgid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
