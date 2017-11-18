-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2017 at 01:27 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eventjini`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `account_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `available_balance` float NOT NULL DEFAULT '0',
  `last_activity` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `type` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `user_id`, `available_balance`, `last_activity`, `status`, `type`) VALUES
(1, 1, 950, '2017-11-18 11:53:55', '1', 1),
(2, 2, 30, '2017-11-18 11:53:54', '1', 1),
(4, 6, 10, '2017-11-18 11:45:34', '1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reference`
--

CREATE TABLE `reference` (
  `ref_id` bigint(20) NOT NULL,
  `from_account_id` bigint(20) NOT NULL,
  `to_account_id` bigint(20) NOT NULL,
  `txt_type_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `note` text,
  `ref_date` datetime NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=>Pending | 1=>Success | 2=>Fake'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reference`
--

INSERT INTO `reference` (`ref_id`, `from_account_id`, `to_account_id`, `txt_type_id`, `amount`, `note`, `ref_date`, `status`) VALUES
(1, 1, 2, 2, 10, 'IMPS', '2017-11-18 16:20:44', 1),
(2, 1, 2, 1, 10, 'NEFT', '2017-11-18 16:21:19', 1),
(3, 1, 2, 2, 30, 'IMPS', '2017-11-18 16:35:12', 1),
(4, 2, 1, 2, 10, 'IMPS', '2017-11-18 16:35:29', 1),
(5, 1, 4, 2, 20, 'To Sambath', '2017-11-18 16:55:44', 1),
(6, 4, 2, 2, 10, 'IMPS to Sundar from sambath', '2017-11-18 17:15:34', 1),
(7, 2, 1, 2, 10, 'Send to bala', '2017-11-18 17:23:54', 1);

--
-- Triggers `reference`
--
DELIMITER $$
CREATE TRIGGER `debit_balance` AFTER INSERT ON `reference` FOR EACH ROW BEGIN
  
  DECLARE a_amount FLOAT;
  SET a_amount = (SELECT available_balance FROM account WHERE account_id = new.from_account_id); 
  IF (a_amount >= NEW.amount) THEN
  
   UPDATE account SET available_balance = available_balance-new.amount , last_activity = NOW() WHERE account_id = new.from_account_id;

  ELSE
  
   INSERT INTO reference_error_log SELECT * FROM reference where from_account_id = new.from_account_id;

  END IF;

 END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `reference_error_log`
--

CREATE TABLE `reference_error_log` (
  `ref_id` bigint(20) NOT NULL,
  `from_account_id` bigint(20) NOT NULL,
  `to_account_id` bigint(20) NOT NULL,
  `txt_type_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `note` text,
  `ref_date` datetime NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=>Pending | 1=>Success | 2=>Fake'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `reference_error_log`
--
DELIMITER $$
CREATE TRIGGER `clear_debit_reference_error` AFTER DELETE ON `reference_error_log` FOR EACH ROW BEGIN
  
  DELETE FROM reference where from_account_id = OLD.from_account_id; 

 END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `txn_id` bigint(20) NOT NULL,
  `ref_id` bigint(20) NOT NULL,
  `txn_amount` float NOT NULL,
  `current_balance` float NOT NULL,
  `txn_type` varchar(20) NOT NULL COMMENT 'Debit | Credit',
  `account_id` bigint(20) NOT NULL,
  `txn_type_id` tinyint(4) NOT NULL,
  `txn_note` text,
  `txn_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`txn_id`, `ref_id`, `txn_amount`, `current_balance`, `txn_type`, `account_id`, `txn_type_id`, `txn_note`, `txn_date`) VALUES
(1, 1, 10, 990, 'Debit', 1, 2, 'IMPS', '2017-11-18 16:20:44'),
(2, 1, 10, 10, 'Credit', 2, 2, 'IMPS', '2017-11-18 16:20:44'),
(3, 3, 30, 950, 'Debit', 1, 2, 'IMPS', '2017-11-18 16:35:12'),
(4, 3, 30, 40, 'Credit', 2, 2, 'IMPS', '2017-11-18 16:35:12'),
(5, 4, 10, 30, 'Debit', 2, 2, 'IMPS', '2017-11-18 16:35:29'),
(6, 4, 10, 960, 'Credit', 1, 2, 'IMPS', '2017-11-18 16:35:29'),
(7, 5, 20, 940, 'Debit', 1, 2, 'To Sambath', '2017-11-18 16:55:44'),
(8, 5, 20, 20, 'Credit', 4, 2, 'To Sambath', '2017-11-18 16:55:44'),
(9, 6, 10, 10, 'Debit', 4, 2, 'IMPS to Sundar from sambath', '2017-11-18 17:15:34'),
(10, 6, 10, 40, 'Credit', 2, 2, 'IMPS to Sundar from sambath', '2017-11-18 17:15:34'),
(11, 7, 10, 30, 'Debit', 2, 2, 'Send to bala', '2017-11-18 17:23:55'),
(12, 7, 10, 950, 'Credit', 1, 2, 'Send to bala', '2017-11-18 17:23:55');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_type`
--

CREATE TABLE `transaction_type` (
  `txt_type_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `status` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction_type`
--

INSERT INTO `transaction_type` (`txt_type_id`, `type`, `status`) VALUES
(1, 'NEFT', '1'),
(2, 'IMPS', '1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `username` varchar(250) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(350) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1' COMMENT '{0=>inactive,1=>active}',
  `user_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=>customer | 2=>Checking',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `username`, `phone`, `email`, `password`, `dob`, `address`, `status`, `user_type`, `created_date`) VALUES
(1, 'Bala', 'Sundar', 'bala', NULL, '32deva@gmail.com', 'c975edb70f08229bbeb298dede828331', NULL, 'chennai', 1, 1, '2017-11-15 12:03:01'),
(2, 'Sundar', 'B', 'sundar', NULL, 'balasundaram@kutung.in', '345e2cdf665a448de8d1cdaedd4f21fe', NULL, 'chennai', 1, 1, '2017-11-16 06:29:39'),
(3, 'Admin', 'Sundar', 'admin', NULL, 'admin@gmail.com', '0192023a7bbd73250516f069df18b500', NULL, 'chennai', 1, 2, '2017-11-18 07:30:54'),
(6, NULL, NULL, 'sambath', NULL, 'sambath@gmail.com', 'ae39ece1a32e55e60c6c183058d3bdb5', NULL, 'chennai', 1, 1, '2017-11-18 11:24:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `account_id_2` (`account_id`);

--
-- Indexes for table `reference`
--
ALTER TABLE `reference`
  ADD PRIMARY KEY (`ref_id`);

--
-- Indexes for table `reference_error_log`
--
ALTER TABLE `reference_error_log`
  ADD PRIMARY KEY (`ref_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`txn_id`),
  ADD KEY `txn_id` (`txn_id`);

--
-- Indexes for table `transaction_type`
--
ALTER TABLE `transaction_type`
  ADD PRIMARY KEY (`txt_type_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `first_name` (`first_name`),
  ADD KEY `last_name` (`last_name`),
  ADD KEY `username` (`username`),
  ADD KEY `phone` (`phone`),
  ADD KEY `email` (`email`),
  ADD KEY `address` (`address`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `reference`
--
ALTER TABLE `reference`
  MODIFY `ref_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `reference_error_log`
--
ALTER TABLE `reference_error_log`
  MODIFY `ref_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `txn_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `transaction_type`
--
ALTER TABLE `transaction_type`
  MODIFY `txt_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
