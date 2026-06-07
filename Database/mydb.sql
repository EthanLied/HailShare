-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2026 at 02:12 PM
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
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rating_id` int(11) NOT NULL,
  `ride_id` int(11) NOT NULL,
  `rater_user_id` int(11) NOT NULL,
  `rated_user_id` int(11) NOT NULL,
  `rating_score` tinyint(4) NOT NULL CHECK (`rating_score` between 1 and 5),
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Table structure for table `rides`
--

CREATE TABLE `rides` (
  `ride_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pickup_location` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pickup_lat` decimal(10,7) NOT NULL,
  `pickup_long` decimal(10,7) NOT NULL,
  `dropoff_location` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dropoff_lat` decimal(10,7) NOT NULL,
  `dropoff_long` decimal(10,7) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `pickup_time` datetime NOT NULL,
  `available_seats` int(11) NOT NULL,
  `status` enum('active','ongoing','completed','closed') NOT NULL DEFAULT 'active',
  `completed_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rides`
--

INSERT INTO `rides` (`ride_id`, `user_id`, `pickup_location`, `pickup_lat`, `pickup_long`, `dropoff_location`, `dropoff_lat`, `dropoff_long`, `price`, `pickup_time`, `available_seats`, `status`, `completed_at`, `created_at`, `updated_at`) VALUES
(4, 12, 'KLCC, Jalan Ampang, Kampung Cendana, Kuala Lumpur, 50088, Malaysia', 3.1592469, 101.7133662, 'IOI Puchong Jaya, Damansara–Puchong Expressway, Bandar Puchong Jaya, Subang Jaya City Council, 47170, Malaysia', 3.0481365, 101.6210724, 14.00, '2026-08-20 13:00:00', 3, 'active', '2026-05-26 00:00:00', '2026-05-22 14:05:59', '2026-06-07 20:10:09'),
(5, 12, 'KL Sentral, Jalan Stesen Sentral, Seputeh, Kuala Lumpur, 50470, Malaysia', 3.1341106, 101.6865153, 'sSijangkang Dalam Rural Clinic, Jalan Sri Tanjung, Teluk Panglima Garang, Malaysia', 2.9326590, 101.4348653, 13.00, '2026-09-09 13:00:00', 3, 'active', '2026-06-05 00:00:00', '2026-05-22 14:06:18', '2026-06-07 19:46:26'),
(6, 15, 'Sunway Lagoon, Sunway City, Subang Jaya City Council, 46150, Malaysia', 3.0706506, 101.6107862, 'Kajang, Jalan Bukit, Sungai Chua, Kajang, 43000, Malaysia', 2.9826748, 101.7905301, 11.00, '2026-07-06 21:00:00', 5, 'closed', '2026-07-07 00:00:00', '2026-06-07 19:52:43', '2026-06-07 19:52:56'),
(7, 15, 'KLCC, Jalan Ampang, Kampung Cendana, Kuala Lumpur, 50088, Malaysia', 3.1591628, 101.7133606, 'Taman Universiti, Iskandar Puteri, Malaysia', 1.5376929, 103.6286268, 14.00, '2026-07-05 14:00:00', 5, 'active', '2026-07-06 00:00:00', '2026-06-07 19:55:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ride_chat_messages`
--

CREATE TABLE `ride_chat_messages` (
  `message_id` int(11) NOT NULL,
  `ride_chat_id` int(11) NOT NULL,
  `sender_user_id` int(11) NOT NULL,
  `message_content` text NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ride_chat_messages`
--

INSERT INTO `ride_chat_messages` (`message_id`, `ride_chat_id`, `sender_user_id`, `message_content`, `sent_at`) VALUES
(14, 8, 12, 'Raining now though.', '2026-05-22 14:07:42'),
(15, 9, 15, 'Hello\n', '2026-06-07 19:52:16'),
(16, 8, 12, 'Okay!', '2026-06-07 20:00:14');

-- --------------------------------------------------------

--
-- Table structure for table `ride_chat_rooms`
--

CREATE TABLE `ride_chat_rooms` (
  `ride_chat_id` int(11) NOT NULL,
  `ride_id` int(11) NOT NULL,
  `guest_user_id` int(11) NOT NULL,
  `status` enum('waiting','active','closed','timeout') NOT NULL DEFAULT 'waiting',
  `created_at` datetime DEFAULT current_timestamp(),
  `closed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ride_chat_rooms`
--

INSERT INTO `ride_chat_rooms` (`ride_chat_id`, `ride_id`, `guest_user_id`, `status`, `created_at`, `closed_at`) VALUES
(8, 4, 12, 'active', '2026-05-22 14:05:59', NULL),
(9, 5, 12, 'active', '2026-05-22 14:06:18', NULL),
(10, 6, 15, 'closed', '2026-06-07 19:52:43', NULL),
(11, 7, 15, 'active', '2026-06-07 19:55:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ride_participants`
--

CREATE TABLE `ride_participants` (
  `participant_id` int(11) NOT NULL,
  `ride_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('active','left','completed') NOT NULL DEFAULT 'active',
  `joined_at` datetime DEFAULT current_timestamp(),
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ride_participants`
--

INSERT INTO `ride_participants` (`participant_id`, `ride_id`, `user_id`, `status`, `joined_at`, `completed_at`) VALUES
(2, 4, 12, 'active', '2026-05-22 14:05:59', NULL),
(3, 5, 12, 'active', '2026-05-22 14:06:18', NULL),
(4, 5, 15, 'active', '2026-06-07 19:51:22', NULL),
(5, 6, 15, 'completed', '2026-06-07 19:52:43', NULL),
(6, 7, 15, 'active', '2026-06-07 19:55:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `support_chat_messages`
--

CREATE TABLE `support_chat_messages` (
  `message_id` int(11) NOT NULL,
  `support_chat_id` int(11) NOT NULL,
  `sender_user_id` int(11) NOT NULL,
  `message_content` text NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `support_chat_messages`
--

INSERT INTO `support_chat_messages` (`message_id`, `support_chat_id`, `sender_user_id`, `message_content`, `sent_at`) VALUES
(8, 3, 12, 'Hello!', '2026-06-07 20:00:05'),
(9, 3, 13, 'hi', '2026-06-07 20:11:28'),
(10, 3, 13, 'hi', '2026-06-07 20:11:32'),
(11, 3, 13, 'hello', '2026-06-07 20:11:43');

-- --------------------------------------------------------

--
-- Table structure for table `support_chat_rooms`
--

CREATE TABLE `support_chat_rooms` (
  `support_chat_id` int(11) NOT NULL,
  `customer_user_id` int(11) NOT NULL,
  `staff_user_id` int(11) DEFAULT NULL,
  `status` enum('waiting','active','closed','timeout') NOT NULL DEFAULT 'waiting',
  `started_at` datetime DEFAULT current_timestamp(),
  `issue_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `additional_notes` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ended_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `support_chat_rooms`
--

INSERT INTO `support_chat_rooms` (`support_chat_id`, `customer_user_id`, `staff_user_id`, `status`, `started_at`, `issue_type`, `additional_notes`, `ended_at`) VALUES
(2, 12, 13, 'closed', '2026-05-22 14:06:51', 'Others', 'Rude ride insulted me', '2026-06-07 19:56:02'),
(3, 12, 13, 'active', '2026-05-22 14:07:01', 'Ride Issue', 'Someone complained about me', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `first_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `last_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `phone_number` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password_hash` varchar(3000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `security_question` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `security_question_answer` varchar(3000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `account_status` enum('active','suspended','banned','deactivated') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `role_id`, `first_name`, `last_name`, `email`, `phone_number`, `password_hash`, `date_of_birth`, `security_question`, `security_question_answer`, `account_status`, `created_at`, `updated_at`) VALUES
(12, 1, 'Customer', 'Customer', 'customer@mail.com', '012345678', '$2y$10$Vi1XGJ3wtjJJPoSe5kmtb.xNwlU.DHRm06XWCktJf6EAzX57FlB/K', '1977-01-01', 'What is your pet\'s name?', '1', 'active', '2026-05-22 14:04:46', NULL),
(13, 2, 'Staff', 'Staff', 'staff@mail.com', '0123456789', '$2y$10$nWwTr7N/eSR3ZGsfVR0CZOTDfjS.wx25IF/25hvKY33cHTJsovBJC', '2006-02-01', 'What is your pet\'s name?', '1', 'active', '2026-05-22 14:09:45', '2026-05-22 14:09:54'),
(14, 3, 'Admin', 'Admin', 'admin@mail.com', '01234567899', '$2y$10$BJOD9Ursm25D72jozRM1b.bsGAv75yaIF2MsNIkeWINdj5NaxLC5q', '1990-01-13', 'What is your pet&#039;s name?', '1', 'active', '2026-06-07 19:41:06', '2026-06-07 20:00:50'),
(15, 1, 'Customer', 'Customer', 'customer2@mail.com', '012345678999', '$2y$10$cDkkn86nCDpW0aDupOyU1e9.KNjh.vtl/Kqia0c/mPwqaUaJ07y.C', '1998-11-13', 'What is your school name?', '1', 'active', '2026-06-07 19:41:49', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD UNIQUE KEY `ride_id` (`ride_id`,`rater_user_id`,`rated_user_id`),
  ADD KEY `rater_user_id` (`rater_user_id`),
  ADD KEY `rated_user_id` (`rated_user_id`);

--
-- Indexes for table `rides`
--
ALTER TABLE `rides`
  ADD PRIMARY KEY (`ride_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `ride_chat_messages`
--
ALTER TABLE `ride_chat_messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `ride_chat_id` (`ride_chat_id`),
  ADD KEY `sender_user_id` (`sender_user_id`);

--
-- Indexes for table `ride_chat_rooms`
--
ALTER TABLE `ride_chat_rooms`
  ADD PRIMARY KEY (`ride_chat_id`),
  ADD KEY `ride_id` (`ride_id`),
  ADD KEY `guest_user_id` (`guest_user_id`);

--
-- Indexes for table `ride_participants`
--
ALTER TABLE `ride_participants`
  ADD PRIMARY KEY (`participant_id`),
  ADD UNIQUE KEY `ride_id` (`ride_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `support_chat_messages`
--
ALTER TABLE `support_chat_messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `support_chat_id` (`support_chat_id`),
  ADD KEY `sender_user_id` (`sender_user_id`);

--
-- Indexes for table `support_chat_rooms`
--
ALTER TABLE `support_chat_rooms`
  ADD PRIMARY KEY (`support_chat_id`),
  ADD KEY `customer_user_id` (`customer_user_id`),
  ADD KEY `staff_user_id` (`staff_user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_number` (`phone_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rides`
--
ALTER TABLE `rides`
  MODIFY `ride_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ride_chat_messages`
--
ALTER TABLE `ride_chat_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `ride_chat_rooms`
--
ALTER TABLE `ride_chat_rooms`
  MODIFY `ride_chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ride_participants`
--
ALTER TABLE `ride_participants`
  MODIFY `participant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `support_chat_messages`
--
ALTER TABLE `support_chat_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `support_chat_rooms`
--
ALTER TABLE `support_chat_rooms`
  MODIFY `support_chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`ride_id`) REFERENCES `rides` (`ride_id`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`rater_user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `ratings_ibfk_3` FOREIGN KEY (`rated_user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `rides`
--
ALTER TABLE `rides`
  ADD CONSTRAINT `rides_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `ride_chat_messages`
--
ALTER TABLE `ride_chat_messages`
  ADD CONSTRAINT `ride_chat_messages_ibfk_1` FOREIGN KEY (`ride_chat_id`) REFERENCES `ride_chat_rooms` (`ride_chat_id`),
  ADD CONSTRAINT `ride_chat_messages_ibfk_2` FOREIGN KEY (`sender_user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `ride_chat_rooms`
--
ALTER TABLE `ride_chat_rooms`
  ADD CONSTRAINT `ride_chat_rooms_ibfk_1` FOREIGN KEY (`ride_id`) REFERENCES `rides` (`ride_id`),
  ADD CONSTRAINT `ride_chat_rooms_ibfk_2` FOREIGN KEY (`guest_user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `ride_participants`
--
ALTER TABLE `ride_participants`
  ADD CONSTRAINT `ride_participants_ibfk_1` FOREIGN KEY (`ride_id`) REFERENCES `rides` (`ride_id`),
  ADD CONSTRAINT `ride_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `support_chat_messages`
--
ALTER TABLE `support_chat_messages`
  ADD CONSTRAINT `support_chat_messages_ibfk_1` FOREIGN KEY (`support_chat_id`) REFERENCES `support_chat_rooms` (`support_chat_id`),
  ADD CONSTRAINT `support_chat_messages_ibfk_2` FOREIGN KEY (`sender_user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `support_chat_rooms`
--
ALTER TABLE `support_chat_rooms`
  ADD CONSTRAINT `support_chat_rooms_ibfk_1` FOREIGN KEY (`customer_user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `support_chat_rooms_ibfk_2` FOREIGN KEY (`staff_user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
