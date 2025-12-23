-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2025 at 08:03 PM
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
-- Database: `hms`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `checkin_date` date NOT NULL,
  `checkout_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','checked_in','checked_out','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `customer_id`, `room_id`, `checkin_date`, `checkout_date`, `total_price`, `status`, `created_at`) VALUES
(1, 1, 5, '2025-12-12', '2025-12-13', 0.00, 'pending', '2025-12-12 02:44:24'),
(2, 2, 4, '2025-12-12', '2025-12-13', 0.00, 'pending', '2025-12-12 02:44:43'),
(3, 3, 4, '2025-12-12', '2025-12-13', 0.00, 'pending', '2025-12-12 02:47:25'),
(4, 4, 6, '2025-12-13', '2025-12-16', 0.00, 'pending', '2025-12-12 02:49:27'),
(51, 64, 1, '2025-12-27', '2025-12-29', 0.00, 'pending', '2025-12-13 08:31:57'),
(52, 65, 4, '2025-12-25', '2025-12-18', 0.00, 'pending', '2025-12-13 08:34:52'),
(60, 73, 3, '2025-12-15', '2025-12-18', 0.00, 'pending', '2025-12-13 19:00:58'),
(61, 74, 3, '2025-12-15', '2025-12-18', 0.00, 'pending', '2025-12-13 19:01:17'),
(62, 75, 3, '2025-12-15', '2025-12-18', 0.00, 'pending', '2025-12-13 19:02:41'),
(63, 76, 5, '2025-12-18', '2026-01-02', 0.00, 'pending', '2025-12-13 19:03:26'),
(64, 77, 1, '2025-12-16', '2025-12-19', 0.00, 'pending', '2025-12-13 19:05:16'),
(65, 78, 1, '2025-12-15', '2025-12-17', 0.00, 'pending', '2025-12-14 03:45:04'),
(66, 79, 1, '2025-12-24', '2025-12-26', 0.00, 'pending', '2025-12-15 13:07:01');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `fname` varchar(50) DEFAULT NULL,
  `lname` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `id_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `fname`, `lname`, `phone`, `email`, `address`, `id_number`, `created_at`) VALUES
(1, 'hello', 'hi', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-12 02:44:24'),
(2, 'hello', 'hi', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-12 02:44:43'),
(3, 'sssss', 'sssss', '111', 'as@gmail', 'hi', '1111', '2025-12-12 02:47:25'),
(4, 'aaa', 'aaaa', '111', 'hellohi@gmail', 'hi', '1111', '2025-12-12 02:49:27'),
(5, 'hello', 'sssss', '1231231', 'rashik.pasa456@gmail.com', 'hi', '1111', '2025-12-13 07:06:06'),
(6, 'hello', 'sssss', '1231231', 'rashik.pasa456@gmail.com', 'hi', '1111', '2025-12-13 07:11:32'),
(7, 'hello', 'sssss', '1231231', 'rashik.pasa456@gmail.com', 'hi', '1111', '2025-12-13 07:11:33'),
(8, 'hello', 'sssss', '1231231', 'rashik.pasa456@gmail.com', 'hi', '1111', '2025-12-13 07:11:34'),
(9, 'hello', 'sssss', '1231231', 'rashik.pasa456@gmail.com', 'hi', '1111', '2025-12-13 07:11:34'),
(10, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 07:11:52'),
(11, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 07:13:31'),
(12, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 07:49:08'),
(13, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 07:51:00'),
(14, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 07:51:12'),
(15, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 07:52:14'),
(16, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 07:54:21'),
(17, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 07:57:13'),
(18, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:01:43'),
(19, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:19:10'),
(20, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:19:11'),
(21, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:19:11'),
(22, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:19:11'),
(23, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:19:11'),
(24, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:19:12'),
(25, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:19:26'),
(26, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:20:12'),
(27, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:20:37'),
(28, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:20:37'),
(29, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:20:37'),
(30, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:21:14'),
(31, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:21:38'),
(32, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:21:38'),
(33, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:21:38'),
(34, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:21:38'),
(35, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:21:39'),
(36, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:21:39'),
(37, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:21:39'),
(38, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:24:43'),
(39, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:26:35'),
(40, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:27:03'),
(41, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:27:36'),
(42, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:27:36'),
(43, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:27:36'),
(44, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:27:37'),
(45, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:27:37'),
(46, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:28:20'),
(47, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:28:20'),
(48, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:28:21'),
(49, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:28:21'),
(50, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:28:47'),
(51, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:28:48'),
(52, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:28:48'),
(53, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:28:48'),
(54, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:28:49'),
(55, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:28:49'),
(56, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:28:58'),
(57, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:29:09'),
(58, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:29:10'),
(59, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:29:10'),
(60, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:29:18'),
(61, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:29:25'),
(62, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:29:26'),
(63, 'hello', 'sssss', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:29:26'),
(64, 'aaa', 'aaaa', '1231231', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:31:56'),
(65, 'hello', 'sssss', '1231231', 'rashik.pasa456@gmail.com', 'Tyanglaphant', '1111', '2025-12-13 08:34:52'),
(66, 'hello', 'hi', '111', 'ram@prasad', 'Tyanglaphant', '2131231', '2025-12-13 08:39:01'),
(67, 'hello', 'hi', '1231231', 'marashikho123@gmail.com', 'hi', '2131231', '2025-12-13 18:49:31'),
(68, 'hello', 'hi', '1231231', 'marashikho123@gmail.com', 'hi', '2131231', '2025-12-13 18:56:19'),
(69, 'hello', 'hi', '1231231', 'marashikho123@gmail.com', 'hi', '2131231', '2025-12-13 18:56:35'),
(70, 'hello', 'hi', '1231231', 'marashikho123@gmail.com', 'hi', '2131231', '2025-12-13 18:56:36'),
(71, 'hello', 'hi', '1231231', 'marashikho123@gmail.com', 'hi', '2131231', '2025-12-13 18:56:43'),
(72, 'hello', 'hi', '1231231', 'marashikho123@gmail.com', 'hi', '2131231', '2025-12-13 18:56:55'),
(73, 'hello', 'hi', '1231231', 'marashikho123@gmail.com', 'hi', '2131231', '2025-12-13 19:00:58'),
(74, 'hello', 'hi', '1231231', 'marashikho123@gmail.com', 'hi', '2131231', '2025-12-13 19:01:17'),
(75, 'hello', 'hi', '1231231', 'marashikho123@gmail.com', 'hi', '2131231', '2025-12-13 19:02:41'),
(76, 'aaa', 'sssss', '1231231', 'marashikho123@gmail.com', 'Tyanglaphant', '2131231', '2025-12-13 19:03:26'),
(77, 'aaa', 'sssss', '1231231', 'rashik.pasa456@gmail.com', 'Tyanglaphant', '1111', '2025-12-13 19:05:16'),
(78, 'aaa', 'sssss', '1231231', 'marashikho123@gmail.com', 'hi', '2131231', '2025-12-14 03:45:04'),
(79, 'hello', 'hi', '111', 'ram@prasad', 'Tyanglaphant', '1111', '2025-12-15 13:07:01');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` enum('cash','card','online') NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('pending','resolved') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `subject`, `message`, `status`, `created_at`) VALUES
(1, 5, 'dustbin', 'no dustbin', 'pending', '2025-12-14 14:46:17');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 5, 3, 'decent place', '2025-12-14 14:44:07'),
(2, 5, 3, 'decent place', '2025-12-14 14:44:11');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_number` varchar(20) NOT NULL,
  `type_id` int(11) NOT NULL,
  `availability` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `type_id`, `availability`, `status`, `created_at`) VALUES
(1, '11', 3, 1, 1, '2025-12-09 08:18:08'),
(3, '99', 1, 0, 0, '2025-12-12 01:51:54'),
(4, '123', 2, 0, 0, '2025-12-12 02:37:00'),
(5, '111', 2, 0, 0, '2025-12-12 02:37:04'),
(6, '222', 3, 0, 0, '2025-12-12 02:37:10'),
(8, '666', 5, 0, 0, '2025-12-18 14:03:47');

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `max_persons` int(11) DEFAULT 2,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`id`, `name`, `description`, `price`, `max_persons`, `created_at`) VALUES
(1, 'Single', NULL, 2000.00, 2, '2025-12-09 08:17:51'),
(2, 'Double', NULL, 3500.00, 2, '2025-12-09 08:17:51'),
(3, 'Suite', NULL, 5000.00, 2, '2025-12-09 08:17:51'),
(4, 'Deluxe', NULL, 7000.00, 2, '2025-12-09 08:17:51'),
(5, 'Family', NULL, 6000.00, 2, '2025-12-09 08:17:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer','receptionist') DEFAULT 'receptionist',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `full_name`, `phone`, `password`, `role`, `created_at`) VALUES
(2, NULL, 'rashik', NULL, NULL, NULL, 'hello1212', 'admin', '2025-12-09 08:28:04'),
(3, 'recep', 'john', NULL, NULL, NULL, 'recep1212', 'receptionist', '2025-12-14 02:45:33'),
(4, NULL, 'hola', NULL, NULL, NULL, '$2y$10$nadn02h7FmCwc6GZ1Yiyb.gJ86pY1.aR7IdgaJ4.oKJBcE6QgQrJe', 'receptionist', '2025-12-14 02:57:36'),
(5, 'harry', 'harryop', NULL, NULL, NULL, 'customer1212', 'customer', '2025-12-14 13:43:06'),
(6, NULL, 'ethan', NULL, NULL, NULL, '$2y$10$54ZNiHUUpYaut.yYjo35FuXVHt7CgCcPYJAdb9t46sLv4XhTgfU22', 'receptionist', '2025-12-18 14:10:31'),
(7, NULL, 'rammm', 'as@gmail.com', 'ramprasad', '56465464645', 'ram111222', 'customer', '2025-12-21 06:34:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_number` (`room_number`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `room_types` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
