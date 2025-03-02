-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2025 at 11:58 AM
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
-- Database: `roommate_matching`
--

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` int(11) NOT NULL,
  `user1_id` int(11) DEFAULT NULL,
  `user2_id` int(11) DEFAULT NULL,
  `match_score` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preferences`
--

CREATE TABLE `preferences` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cooking_habits` varchar(255) DEFAULT NULL,
  `professional_status` enum('Employed','Student','Freelancer') DEFAULT NULL,
  `can stay with pets` enum('Yes','No') DEFAULT NULL,
  `prefered pets` varchar(255) DEFAULT NULL,
  `budget` decimal(10,2) DEFAULT NULL,
  `personality` enum('Extrovert','Introvert') DEFAULT NULL,
  `share_cost` enum('Yes','No') DEFAULT NULL,
  `share_chores` enum('Yes','No') DEFAULT NULL,
  `deal_breakers` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `preferences`
--

INSERT INTO `preferences` (`id`, `user_id`, `cooking_habits`, `professional_status`, `can stay with pets`, `prefered pets`, `budget`, `personality`, `share_cost`, `share_chores`, `deal_breakers`) VALUES
(1, 23, 'experimental', 'Student', 'Yes', 'dogs, cats, parrots', 10000.00, 'Extrovert', '', 'Yes', 'untidy, too many friends coming over, no boundaries');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `preferences` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `age`, `gender`, `preferences`, `created_at`, `profile_picture`) VALUES
(2, 'kiptoo faith', 'faith@gmail.com', '$2y$10$ld1yp9HPFRh9xmbalwXDxOvYuZh4iv3aGiF.SDzNqCb7uCZ5YgLoe', 26, 'Female', 'good and well neat', '2025-02-08 08:04:52', 'profile_2.jpeg'),
(13, 'Alice Chebet', 'alice@example.com', '$2y$10$luv1cJELb9dU2apxUdEnquJFmmENDWESu2YO3YllyIHQVLMSZHqfC', 25, 'Female', 'hiking,movies,reading', '2025-02-08 09:56:45', 'profile_13.jpeg'),
(14, 'Bob Smith', 'bob@example.com', '$2y$10$3JV07As3gIblVFh3DaWdaO3r/bzitiOFS0IVWNuCKnXVtlGniwZPS', 27, 'Male', 'hiking, cooking, cycling', '2025-02-08 09:57:49', 'profile_14.jpeg'),
(15, 'Charlie Kamau', 'charlie@example.com', '$2y$10$Ol/Eb1lSJTU0DjGZqLeXZu2tyx3V1LZiQDWhW4JYnFyNdXbpv/.oe', 24, 'Male', 'gaming, tech. reading', '2025-02-08 09:59:01', 'profile_15.jpeg'),
(16, 'Diana Moraa', 'moraa@example.com', '$2y$10$DzM4RePYZWPeSeQVH0sxpesMY8zbgt0KDPsVxKBRApWggkmGfNeRi', 26, 'Female', 'dancing, art, music', '2025-02-08 09:59:51', 'profile_16.jpeg'),
(17, 'Ethan Odhiambo', 'ethan@example.com', '$2y$10$ZJy0xcwSdW8IlXbtWWUhiOQCm07Fgr3zqzuipcudXxvgz.JISUJAe', 19, 'Male', 'movies, music,football,arsenal', '2025-02-08 10:00:46', 'profile_17.jpeg'),
(18, 'Fiona Wangari', 'fiona@example.com', '$2y$10$bVGjjt1YZP95kROwTKszHOHXAhMjbauKLWsmbk5KDG4IjHwQEnWYW', 23, 'Female', 'painting, photoghraphy,reading', '2025-02-08 10:02:01', 'profile_18.jpeg'),
(19, 'George Olesayun', 'george@gmail.com', '$2y$10$amC4F9y3l8D2087YguoWB.VXSPAIShgOC6ncN7fvcBUKyF.VFOb0C', 28, 'Male', 'cycling, swimming, tech', '2025-02-08 10:02:59', 'profile_19.jpeg'),
(20, 'Hannah Swalimu', 'hannah@example.com', '$2y$10$K18xzW/Itxegiu0iYFPzLOYPbWn1h6bD7K5vCMyUEPohic.aH0Doq', 26, 'Female', 'football,cooking,hiking', '2025-02-08 10:03:51', 'profile_20.jpeg'),
(21, 'Ian Wafula', 'ian@example.com', '$2y$10$m/bfQMjVBVjq9VkGH97cmOvyWdDt0ARAypNWiA8gtatFZDub8QVrW', 22, 'Male', 'movies, tech, chess, cooking', '2025-02-08 10:04:42', 'profile_21.jpeg'),
(22, 'Julia Chepkemoi', 'julia@example.com', '$2y$10$hwZ7ZdImDth2HI1PglVEh.1ABjqQyPjAguAQX2hPGPxG2fVVgcUgS', 30, 'Female', 'yoga, meditation, running', '2025-02-08 10:05:34', 'profile_22.jpeg'),
(23, 'Mary Cheptoo', 'mary@example.com', '$2y$10$wnp6ebZswMFWjV8M1O7clO5QDZzqSUy5hy/.ryIp/HLnIyBrfNkz.', 24, 'Male', 'lady, student, 24yrs', '2025-02-08 14:42:17', 'profile_23.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `user_images`
--

CREATE TABLE `user_images` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_images`
--

INSERT INTO `user_images` (`id`, `user_id`, `image_path`, `uploaded_at`) VALUES
(1, 23, 'f3.jpeg', '2025-03-02 09:51:08'),
(2, 23, 'f2.jpeg', '2025-03-02 09:51:29'),
(3, 23, 'f1.jpeg', '2025-03-02 09:51:38'),
(4, 23, 'f1.jpeg', '2025-03-02 09:52:18'),
(5, 23, 'f1.jpeg', '2025-03-02 09:57:54'),
(6, 23, 'f1.jpeg', '2025-03-02 10:02:04'),
(7, 23, 'f1.jpeg', '2025-03-02 10:11:14'),
(8, 23, 'f1.jpeg', '2025-03-02 10:14:24'),
(9, 23, 'f1.jpeg', '2025-03-02 10:14:44'),
(10, 2, 'm2.jpeg', '2025-03-02 10:44:05'),
(11, 2, 'm2.jpeg', '2025-03-02 10:44:47'),
(12, 2, 'm3.jpeg', '2025-03-02 10:46:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user1_id` (`user1_id`),
  ADD KEY `user2_id` (`user2_id`);

--
-- Indexes for table `preferences`
--
ALTER TABLE `preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_token` (`session_token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_images`
--
ALTER TABLE `user_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `preferences`
--
ALTER TABLE `preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user_images`
--
ALTER TABLE `user_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matches_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `preferences`
--
ALTER TABLE `preferences`
  ADD CONSTRAINT `preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_images`
--
ALTER TABLE `user_images`
  ADD CONSTRAINT `user_images_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
