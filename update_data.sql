-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2022 at 07:31 AM
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
-- Database: `update_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `id` int(11) NOT NULL,
  `pin` varchar(255) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `mobile` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`id`, `pin`, `name`, `mobile`, `email`, `status`) VALUES
(1, '2002', 'Sanjay Prasad', '8643340213', 'sanjay12@gamil.com', 0),
(2, '1234', 'Arjun', '9878786765', 'arjun@gmail.com', 1),
(3, '7541', 'john', '8767654543', 'john@yahoo.com', 0),
(4, '1187', 'prasad', '9876543210', 'jp@gmail.com', 0),
(5, '1713', 'prrasad', '987678986', 'prasad@gmail.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `need_amount`
--

CREATE TABLE `need_amount` (
  `id` int(11) NOT NULL,
  `manager_id` int(11) DEFAULT 0,
  `date` date DEFAULT NULL,
  `amount` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `need_amount`
--

INSERT INTO `need_amount` (`id`, `manager_id`, `date`, `amount`) VALUES
(1, 1, '2022-11-29', '400');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(200) DEFAULT NULL,
  `manager_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `manager_id`, `amount`, `remarks`, `date`) VALUES
(1, 1, 2, 249, 'Hello this is my first transaction', '2022-11-29'),
(2, 2, 1, 600, 'success', '2022-11-29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `balance` int(11) DEFAULT 0,
  `expense` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `manager_id`, `name`, `mobile`, `balance`, `expense`) VALUES
(1, 0, 'sanjay', '56858', 3720, 500),
(2, 1, 'last name', '56858', NULL, 0),
(3, 2, 'prasad', '8778624681', 0, 0),
(4, 2, 'vvb', '33398889', 0, 0),
(5, 2, 'tcg', '99885255', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `need_amount`
--
ALTER TABLE `need_amount`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `need_amount`
--
ALTER TABLE `need_amount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
