-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2023 at 09:47 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `color_challenge`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `refer_code` text DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `refer_code`, `role`, `status`) VALUES
(1, 'Nanthakumar', 'Nantha34@gmail.com', 'Nantha@543', 'CMDS', 'Super admin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

CREATE TABLE `app_settings` (
  `id` int(11) NOT NULL,
  `link` text DEFAULT NULL,
  `version` int(200) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `app_settings`
--

INSERT INTO `app_settings` (`id`, `link`, `version`, `description`) VALUES
(1, 'https://play.google.com/store/apps/details?id=com.app.abcdapp', 4, '4.0');

-- --------------------------------------------------------

--
-- Table structure for table `challenges`
--

CREATE TABLE `challenges` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `color_id` int(11) DEFAULT NULL,
  `coins` decimal(10,2) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0 COMMENT 'Wait For Result-0 |\r\nYou won -1 |\r\nBetter Luck Next Time -2',
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `challenges`
--

INSERT INTO `challenges` (`id`, `user_id`, `color_id`, `coins`, `status`, `datetime`) VALUES
(1, 2, 1, '5.00', 1, '2023-02-26 00:41:44'),
(2, 1, 1, '5.00', 1, '2023-02-26 16:41:44'),
(3, 1, 2, '5.00', 2, '2023-02-26 14:40:21'),
(4, 3, 2, '5.00', 2, '2023-02-26 14:40:21'),
(5, 3, 3, '500.00', 2, '2023-02-26 14:40:21'),
(6, 1, 6, '100.00', 2, '2023-02-26 15:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `code` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`, `code`) VALUES
(1, 'Red', '#FF0000'),
(2, 'Blue', '#0000FF'),
(3, 'Purple', '#800080'),
(4, 'Magenta', '#FF00FF'),
(5, 'Green', '#008000'),
(6, 'Orange', '#FFA500');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `color_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `color_id`, `date`) VALUES
(1, 1, '2023-02-01'),
(2, 1, '2023-02-26');

-- --------------------------------------------------------

--
-- Table structure for table `rules`
--

CREATE TABLE `rules` (
  `id` int(11) NOT NULL,
  `ruleId` int(11) DEFAULT NULL,
  `sender` text DEFAULT NULL,
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rules`
--

INSERT INTO `rules` (`id`, `ruleId`, `sender`, `message`) VALUES
(1, 31, 'Divakar', 'Helllo Divakar');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `register_points` decimal(10,2) DEFAULT 0.00,
  `withdrawal_status` tinyint(4) DEFAULT 0,
  `challenge_status` tinyint(4) DEFAULT 0,
  `min_withdrawal` text DEFAULT NULL,
  `min_dp_coins` decimal(10,2) DEFAULT 0.00,
  `max_dp_coins` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `register_points`, `withdrawal_status`, `challenge_status`, `min_withdrawal`, `min_dp_coins`, `max_dp_coins`) VALUES
(1, '50.00', 1, 1, '100.00', '5.00', '100.00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` text DEFAULT '',
  `name` varchar(255) DEFAULT NULL,
  `upi` text DEFAULT NULL,
  `earn` decimal(10,2) DEFAULT 0.00,
  `coins` decimal(10,2) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `referred_by` text DEFAULT NULL,
  `refer_code` text DEFAULT NULL,
  `withdrawal_status` tinyint(4) DEFAULT 0,
  `challenge_status` tinyint(4) DEFAULT 0,
  `status` tinyint(4) DEFAULT 1 COMMENT 'Active-1\r\nBlocked -0',
  `joined_date` text DEFAULT NULL,
  `last_updated` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `name`, `upi`, `earn`, `coins`, `balance`, `referred_by`, `refer_code`, `withdrawal_status`, `challenge_status`, `status`, `joined_date`, `last_updated`) VALUES
(1, '', NULL, 'sanju56@oksbi', '0.00', '100.00', '100.00', '', 'CMDS58516', 0, 0, 1, '2023-02-01', '2023-02-01 08:07:09'),
(2, '', NULL, 'scfcecef', '0.00', '200.00', '900.00', '', 'CMDS62743', 0, 0, 1, '2023-02-02', '2023-02-02 10:06:49'),
(3, '', NULL, '', '0.00', '50.00', '0.00', 'CMJD777', 'CMDS22278', 0, 0, 1, '2023-03-03', '2023-03-03 07:43:34'),
(4, 'sanjaysd34@gmail.com', NULL, '', '0.00', '50.00', '0.00', '', 'CMDS38647', 0, 0, 1, '2023-03-03', '2023-03-03 07:46:03');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0 COMMENT 'Pending -0 |\r\ncompleted -1 |\r\nCancelled -2',
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `withdrawals`
--

INSERT INTO `withdrawals` (`id`, `user_id`, `amount`, `status`, `datetime`) VALUES
(1, 1, '100.00', 2, '2023-02-27 14:06:38'),
(2, 2, '900.00', 2, '2023-02-27 16:18:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `challenges`
--
ALTER TABLE `challenges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rules`
--
ALTER TABLE `rules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `app_settings`
--
ALTER TABLE `app_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `challenges`
--
ALTER TABLE `challenges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rules`
--
ALTER TABLE `rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
