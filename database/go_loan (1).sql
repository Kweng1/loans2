-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2024 at 02:12 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `go_loan`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`, `username`) VALUES
(1, 'admin123@gmail.com', '$2y$10$DgyjPsqj9S5SQm8KwhzcserAkr38faM1g4zrmJWB3dcECHa/zpqSW', 'admin1');

-- --------------------------------------------------------

--
-- Table structure for table `billing`
--

CREATE TABLE `billing` (
  `billing_id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `billing_amount` decimal(10,2) NOT NULL,
  `due_date` date NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `date_generated` date NOT NULL,
  `interest` decimal(10,2) NOT NULL DEFAULT 0.03,
  `penalty` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `loan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `loan_amount` decimal(10,2) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `payable_months` int(11) NOT NULL CHECK (`payable_months` between 3 and 32)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`loan_id`, `user_id`, `loan_amount`, `transaction_id`, `status`, `note`, `date`, `payable_months`) VALUES
(3, 4, 1000.00, 654919, 'Pending', 'sadsda', '2024-05-18', 3),
(11, 2, 5000.00, 794046, 'pending', '134', '2024-05-25', 5),
(13, 2, 5000.00, 183910, 'pending', 'note', '2024-05-25', 3),
(14, 1, 5000.00, 605259, 'pending', '23123', '2024-05-28', 4);

-- --------------------------------------------------------

--
-- Table structure for table `loan_approve`
--

CREATE TABLE `loan_approve` (
  `approve_id` int(11) NOT NULL,
  `user_id` int(255) NOT NULL,
  `loan_amount` int(255) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `savingstransactions`
--

CREATE TABLE `savingstransactions` (
  `transaction_id` char(36) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `transaction_type` enum('Deposit','Withdrawal') DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL CHECK (`amount` between 100 and 1000),
  `status` enum('Pending','Failed','Rejected','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `current_amount` decimal(10,2) DEFAULT 100000.00,
  `money_back_earned` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `savingstransactions`
--

INSERT INTO `savingstransactions` (`transaction_id`, `user_id`, `transaction_type`, `amount`, `status`, `created_at`, `current_amount`, `money_back_earned`) VALUES
('115253', 3, 'Deposit', 1000.00, 'Completed', '2024-05-18 03:44:24', 1600.00, 0.00),
('201634', 3, 'Deposit', 500.00, 'Completed', '2024-05-18 03:48:48', 2100.00, 0.00),
('343255', 3, 'Deposit', 1000.00, 'Completed', '2024-05-17 18:32:19', 2100.00, 0.00),
('429695', 2, 'Deposit', 1000.00, 'Completed', '2024-05-15 07:03:09', 1000.00, 0.00),
('565342', 3, 'Deposit', 1000.00, 'Completed', '2024-05-17 18:32:11', 1100.00, 0.00),
('574147', 3, 'Withdrawal', 1000.00, 'Completed', '2024-05-18 03:50:31', 1100.00, 0.00),
('597011', 3, 'Deposit', 1000.00, 'Completed', '2024-05-15 00:33:43', 1000.00, 0.00),
('619366', 3, 'Withdrawal', 500.00, 'Completed', '2024-05-17 18:42:11', 1600.00, 0.00),
('695667', 3, 'Withdrawal', 1000.00, 'Completed', '2024-05-17 18:31:22', 100.00, 0.00),
('701700', 3, 'Withdrawal', 1000.00, 'Completed', '2024-05-17 19:00:46', 600.00, 0.00),
('721697', 3, 'Deposit', 100.00, 'Completed', '2024-05-15 00:33:53', 1100.00, 0.00),
('821483', 3, 'Withdrawal', 500.00, 'Completed', '2024-05-18 03:50:59', 600.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `trash`
--

CREATE TABLE `trash` (
  `id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `loan_amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deletion_date` timestamp GENERATED ALWAYS AS (`deleted_at` + interval 30 day) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trash`
--

INSERT INTO `trash` (`id`, `loan_id`, `loan_amount`, `date`, `deleted_at`) VALUES
(1, 6, 8000.00, '2024-05-17', '2024-05-17 15:00:22'),
(2, 7, 5000.00, '2024-05-17', '2024-05-17 15:33:11'),
(3, 9, 5000.00, '2024-05-17', '2024-05-17 15:33:59'),
(4, 10, 1000.00, '2024-05-25', '2024-05-25 12:39:19'),
(5, 9, 100.00, '2024-05-25', '2024-05-25 12:39:26'),
(6, 8, 1000.00, '2024-05-25', '2024-05-25 12:39:28'),
(7, 6, 1000.00, '2024-05-24', '2024-05-25 12:39:33'),
(8, 5, 1000.00, '2024-05-24', '2024-05-25 12:39:38');

-- --------------------------------------------------------

--
-- Table structure for table `user_delete`
--

CREATE TABLE `user_delete` (
  `user_id` int(11) NOT NULL,
  `plan` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_tbl`
--

CREATE TABLE `user_tbl` (
  `user_id` int(11) NOT NULL,
  `plan` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `bankname` varchar(255) DEFAULT NULL,
  `bankAccount` varchar(20) DEFAULT NULL,
  `cardHolder` varchar(255) DEFAULT NULL,
  `tin` varchar(20) DEFAULT NULL,
  `companyName` varchar(255) DEFAULT NULL,
  `companyAddress` varchar(255) DEFAULT NULL,
  `companyPhoneNumber` varchar(20) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `monthly_earnings` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_tbl`
--

INSERT INTO `user_tbl` (`user_id`, `plan`, `name`, `password`, `address`, `gender`, `birthdate`, `email`, `contact`, `bankname`, `bankAccount`, `cardHolder`, `tin`, `companyName`, `companyAddress`, `companyPhoneNumber`, `position`, `monthly_earnings`, `status`) VALUES
(1, 'basic', 'laroa', '$2y$10$DgyjPsqj9S5SQm8KwhzcserAkr38faM1g4zrmJWB3dcECHa/zpqSW', 'cuanos', 'male', '2002-04-18', 'rklaroa@gmail.com', '00932932193', 'laroew4r32', '234243131', '021391283123', '110043', 'org.ph', 'cuanos', '0932423412', 'rwer', 100000.00, 'approved'),
(2, 'premium', 'ryan', '$2y$10$jfVBV5VEB74I3EqNin5.5.XsC6deH3IKjif8KdEoHCZyDOsntFczy', 'cuanos', 'male', '2002-04-18', 'rkteloy@gmail.com', '09994714498', 'laroa.ph', '20002', '100004', '2002212', 'org.ph', 'laroa.ph', '09994714498', 'manager', 10000.00, 'approved'),
(3, 'premium', 'ejay', '$2y$10$yrYDt/fEtIRp3lyOZlH9GOzKrmPC/flMIQ5s3Y6dX.x5fJ/0efc62', 'adasdad', 'male', '2000-10-10', 'ejay@gmail.com', '213312412525', 'DEF BANK', '231251521521', 'ejayquines', '12231312312312', 'ABC CORP', 'asdadasda', '12332151231', 'manager', 5000.00, 'approved'),
(4, 'basic', 'james', '$2y$10$7/d/.Rib08wq7N1P5gzai.nE53mOLhQDfH80/LjYkWSivLDRfDF06', 'dasda', 'male', '2000-10-10', 'james@gmail.com', '4325235234', 'asdasda', '12312312', 'james', '12321312', 'adsdasdas', 'sadasdasd', '21312312', 'employee', 5000.00, 'approved'),
(5, 'basic', 'Peter Jake ', '$2y$10$bmoNrRMM7cRS05DZE6Vg8uLUMKK56l2/DD1E2ypSSVnkcnnbeIOlq', 'cuanos', 'male', '2003-04-18', 'jake123@gmail.com', '0934832842', 'erewr', '234234234', 'peter jake123', '213123', 'org.php', 'cuanos', '324234324', 'ffsdfsdf', 0.00, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawalrequests`
--

CREATE TABLE `withdrawalrequests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL CHECK (`amount` between 500 and 5000),
  `status` enum('Pending','Failed','Rejected','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `withdrawalrequests`
--

INSERT INTO `withdrawalrequests` (`request_id`, `user_id`, `amount`, `status`, `created_at`) VALUES
(1, 3, 1000.00, '', '2024-05-17 18:23:02'),
(2, 3, 500.00, '', '2024-05-17 18:34:42'),
(3, 3, 500.00, 'Pending', '2024-05-17 18:42:11'),
(4, 3, 500.00, 'Pending', '2024-05-17 18:42:16'),
(5, 3, 1000.00, 'Rejected', '2024-05-17 19:00:21'),
(6, 3, 1000.00, '', '2024-05-18 03:49:03'),
(7, 3, 1000.00, 'Pending', '2024-05-18 03:50:31'),
(8, 3, 500.00, '', '2024-05-18 03:50:42'),
(9, 3, 500.00, 'Rejected', '2024-05-18 03:50:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`billing_id`),
  ADD KEY `loan_id` (`loan_id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `loan_approve`
--
ALTER TABLE `loan_approve`
  ADD PRIMARY KEY (`approve_id`);

--
-- Indexes for table `savingstransactions`
--
ALTER TABLE `savingstransactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `trash`
--
ALTER TABLE `trash`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_delete`
--
ALTER TABLE `user_delete`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_tbl`
--
ALTER TABLE `user_tbl`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `withdrawalrequests`
--
ALTER TABLE `withdrawalrequests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `billing`
--
ALTER TABLE `billing`
  MODIFY `billing_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `loan_approve`
--
ALTER TABLE `loan_approve`
  MODIFY `approve_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trash`
--
ALTER TABLE `trash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_tbl`
--
ALTER TABLE `user_tbl`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `withdrawalrequests`
--
ALTER TABLE `withdrawalrequests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `billing`
--
ALTER TABLE `billing`
  ADD CONSTRAINT `billing_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`loan_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `savingstransactions`
--
ALTER TABLE `savingstransactions`
  ADD CONSTRAINT `savingstransactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_tbl` (`user_id`);

--
-- Constraints for table `withdrawalrequests`
--
ALTER TABLE `withdrawalrequests`
  ADD CONSTRAINT `withdrawalrequests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_tbl` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
