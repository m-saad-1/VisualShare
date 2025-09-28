-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 30, 2025 at 09:35 PM
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
-- Database: `pinterest_clone`
--

CREATE DATABASE IF NOT EXISTS `pinterest_clone`;

USE `pinterest_clone`;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `upload_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `upload_id`, `created_at`) VALUES
(1, 14, 42, '2025-08-14 19:41:54'),
(3, 14, 32, '2025-08-14 19:41:57'),
(6, 14, 41, '2025-08-14 19:42:03'),
(7, 14, 36, '2025-08-14 19:42:03'),
(9, 14, 33, '2025-08-14 19:53:06'),
(10, 14, 31, '2025-08-14 20:09:46'),
(11, 14, 35, '2025-08-14 20:09:50'),
(13, 9, 32, '2025-08-14 20:25:25'),
(15, 9, 36, '2025-08-15 14:06:46'),
(16, 9, 30, '2025-08-15 14:06:48'),
(17, 9, 24, '2025-08-15 14:06:49'),
(18, 9, 28, '2025-08-15 14:06:50'),
(19, 9, 42, '2025-08-15 14:06:53'),
(24, 11, 31, '2025-08-26 11:33:08'),
(26, 11, 41, '2025-08-26 11:42:37'),
(28, 11, 34, '2025-08-26 11:43:16'),
(29, 11, 39, '2025-08-26 11:43:17'),
(30, 11, 24, '2025-08-26 11:43:18'),
(34, 11, 35, '2025-08-26 12:07:13'),
(36, 11, 40, '2025-08-26 12:07:16'),
(47, 11, 36, '2025-08-26 19:22:24'),
(48, 11, 30, '2025-08-26 19:23:13'),
(53, 11, 42, '2025-08-26 19:35:45'),
(55, 11, 28, '2025-08-26 20:01:40'),
(56, 11, 32, '2025-08-26 20:01:41'),
(58, 11, 43, '2025-08-26 22:19:26'),
(59, 11, 38, '2025-08-26 22:19:28'),
(60, 11, 33, '2025-08-26 22:19:35'),
(61, 11, 29, '2025-08-26 23:41:45'),
(62, 2, 33, '2025-08-26 23:49:48'),
(63, 2, 38, '2025-08-27 18:53:12'),
(65, 10, 24, '2025-08-29 18:19:15'),
(67, 10, 35, '2025-08-29 18:19:18'),
(68, 10, 31, '2025-08-29 18:19:19'),
(69, 10, 28, '2025-08-29 18:19:21'),
(70, 10, 32, '2025-08-29 18:19:22'),
(71, 10, 36, '2025-08-29 18:19:22'),
(72, 10, 41, '2025-08-29 18:19:23'),
(74, 10, 38, '2025-08-29 18:19:25'),
(76, 10, 29, '2025-08-29 18:19:26'),
(87, 10, 34, '2025-08-29 20:34:17'),
(91, 10, 30, '2025-08-29 20:34:35'),
(92, 10, 33, '2025-08-29 21:39:01'),
(93, 10, 39, '2025-08-29 21:39:09'),
(94, 10, 42, '2025-08-29 21:39:10'),
(97, 10, 43, '2025-08-29 22:03:30'),
(98, 10, 40, '2025-08-29 22:03:34');

-- --------------------------------------------------------

--
-- Table structure for table `saved_posts`
--

CREATE TABLE `saved_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `upload_id` int(11) NOT NULL,
  `saved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(64, 'content'),
(43, 'figma'),
(58, 'icon'),
(62, 'logo'),
(41, 'me'),
(42, 'pic'),
(65, 'platform'),
(44, 'thumbanail'),
(59, 'thunder'),
(63, 'visualshare'),
(45, 'webflow');

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `thumbnail_path` varchar(255) DEFAULT '',
  `thumbnail_time` float DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`id`, `user_id`, `filename`, `filepath`, `title`, `description`, `upload_date`, `thumbnail_path`, `thumbnail_time`) VALUES
(24, 10, '68947fab464a6.png', '/home/vol7_1/infinityfree.com/if0_39557605/htdocs/Content-upload/uploads/68947fab464a6.png', 'VisualShare', 'VisualShare, where you can share images· and videos', '2025-08-07 10:27:55', '', 0),
(28, 12, '6894826bec454.jpg', '/home/vol7_1/infinityfree.com/if0_39557605/htdocs/Content-upload/uploads/6894826bec454.jpg', 'Thomas Jefferson hereðŸ˜˜', 'Hi, I am Thomas Jefferson', '2025-08-07 10:39:39', '', 0),
(29, 12, '689482be02e6f.jpg', '/home/vol7_1/infinityfree.com/if0_39557605/htdocs/Content-upload/uploads/689482be02e6f.jpg', 'My Portfolio website', 'This is my Portfolio website, built with CSS, HTML, and JS. Here, I showcase my recent work.', '2025-08-07 10:41:02', '', 0),
(30, 12, '689483314c068.jpg', '/home/vol7_1/infinityfree.com/if0_39557605/htdocs/Content-upload/uploads/689483314c068.jpg', 'Product Design', 'This product poster was designed by me', '2025-08-07 10:42:57', '', 0),
(31, 13, '689483f45dfe1.jpg', '/home/vol7_1/infinityfree.com/if0_39557605/htdocs/Content-upload/uploads/689483f45dfe1.jpg', 'LongCoat', 'Men fashionable long coat', '2025-08-07 10:46:14', '', 0),
(32, 13, '6894846907572.jpg', '/home/vol7_1/infinityfree.com/if0_39557605/htdocs/Content-upload/uploads/6894846907572.jpg', 'Men fashion', 'Men premium  - clothing', '2025-08-07 10:48:09', '', 0),
(33, 13, '6894885ee7129.jpg', '/home/vol7_1/infinityfree.com/if0_39557605/htdocs/Content-upload/uploads/6894885ee7129.jpg', 'Me', 'Natty Chicko', '2025-08-07 11:05:03', '', 0),
(34, 14, '6894892f9efa0.jpg', '/home/vol7_1/infinityfree.com/if0_39557605/htdocs/Content-upload/uploads/6894892f9efa0.jpg', 'BoosterAffix', 'Performance Marketing Platform', '2025-08-07 11:08:31', '', 0),
(35, 14, '68948966a314a.jpg', '/home/vol7_1/infinityfree.com/if0_39557605/htdocs/Content-upload/uploads/68948966a314a.jpg', 'Sales Funnel', 'Sales Increasing Platform', '2025-08-07 11:09:26', '', 0),
(36, 14, '689489b39b2ee.jpg', '/home/vol7_1/infinityfree.com/if0_39557605/htdocs/Content-upload/uploads/689489b39b2ee.jpg', 'Flyer and brochur Design', 'Professional Flyer and Brochur design', '2025-08-07 11:10:43', '', 0),
(38, 14, '68948cb5468cf.png', '/home/vol7_1/infinityfree.com/if0_39557605/htdocs/Content-upload/uploads/68948cb5468cf.png', 'Amazing Graphics Work', 'Prompt will be coming soon', '2025-08-07 11:23:33', '', 0),
(39, 14, '68948ed29119e.jpg', '/home/vol7_1/infinityfree.com/if0_39557605/htdocs/Content-upload/uploads/68948ed29119e.jpg', 'CV | Resume', 'Professional CV | Resume Design', '2025-08-07 11:32:34', '', 0),
(40, 14, '689490a4a5967.jpg', 'uploads/content/content_40_1755168623.jpg', 'Figma to Webflow Thumbnail Design', 'Duzzling Figma to Webflow thumbnail design.', '2025-08-07 11:40:20', 'uploads/content/content_40_1755168623.jpg', 0),
(41, 14, '68949165f0ea0.jpg', 'uploads/content/content_41_1755166069.jpg', 'Thumbnail', 'Frontend Web Development thumbnail', '2025-08-07 11:43:33', '', 0),
(42, 14, '689dc0ac549f8.jpg', 'C:/xampp/htdocs/Content-upload/uploads/689dc0ac549f8.jpg', 'Figma Thumbnail', 'Figma Thumbnail Design', '2025-08-14 10:55:40', '', 0),
(43, 11, '68ae0bf5e257a.png', 'C:/xampp/htdocs/Content-upload/uploads/68ae0bf5e257a.png', 'thunder icon', 'thunder icon', '2025-08-26 19:33:09', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `upload_tags`
--

CREATE TABLE `upload_tags` (
  `upload_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `upload_tags`
--

INSERT INTO `upload_tags` (`upload_id`, `tag_id`) VALUES
(24, 63),
(24, 64),
(24, 65),
(40, 43),
(40, 44),
(40, 45),
(43, 58),
(43, 59),
(43, 62);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `gender` enum('male','female','other') DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `gender`, `profile_pic`, `bio`, `location`) VALUES
(1, 'saad1', 'saad1@gmail.com', '$2y$10$Vt6ETXS87aOn7GL0L1CgdODiFxQJ.bdEicgNtJXzziRpd1TA3.aLm', '2025-07-17 11:20:23', NULL, NULL, NULL, NULL),
(2, 'Saymoo', 'saad2@gmail.com', '$2y$10$DhwTfu6nJdu0vp4aRqkDmetg6vBf.FZr5wOQ.AB5O/JYsDQZ/ZV66', '2025-07-17 11:21:08', NULL, 'assets/profile_pics/profile_2_1754070466.jpeg', NULL, NULL),
(3, 'saad3', 'saad3@gmail.com', '$2y$10$1p2kcjc65t4CMDZHRIS8lu8VHRZsWVLsg7L5wTL8mkACHqdyuIfWC', '2025-07-18 10:35:39', NULL, NULL, NULL, NULL),
(4, 'Izhar Ahmad', 'izhar@gmail.com', '$2y$10$W.S63..IIXMDN.SW/PvFhuH0vLJaTflsgvEesF33BsdWSuucNtW.u', '2025-07-18 10:39:32', NULL, NULL, NULL, NULL),
(5, 'Sohail Khan', 'sohail@gmail.com', '$2y$10$7Qt2nTIoQqJDRK.FoAm9eOiURCfOrvbRMcEeq6YV/13uVD4zdt8y6', '2025-07-18 10:41:02', NULL, NULL, NULL, NULL),
(6, 'Warren Chicko', 'warren@gmail.com', '$2y$10$GhQmrnqGbtVdLQNa4CoLs.hEOk3/SKGyc3Nz7n2VqeUBnd3vtKfvC', '2025-07-31 10:46:51', 'male', 'assets/profile_pics/profile_6_1754522920.jpg', NULL, NULL),
(7, 'Fahad Mustafa', 'fahad@gmail.com', '$2y$10$IPgFTLSsUfhV2SvYt9BXGONKYhiCf7YjUTqlT0zPn6OvCbLt2sglS', '2025-08-04 10:16:28', NULL, 'assets/profile_pics/profile_7_1754302860.png', NULL, NULL),
(8, 'VisualShare1', 'visual@gmail.com', '$2y$10$XLdS3KjG3jvkvFcaPCRsleVq43tVulhmK.2Jd8Ph2eFkKBKEw4MvG', '2025-08-06 11:11:34', NULL, 'assets/profile_pics/profile_8_1754478912.png', NULL, NULL),
(9, 'Rumor Soft', 'rumor@gmail.com', '$2y$10$2N5oWiLNIkwo0E1ZOnBFoeaHkRQuiHh3lI9I3cjp/2sAuxwCMTy.a', '2025-08-06 22:42:10', NULL, 'assets/profile_pics/profile_9_1754520316.jpg', NULL, NULL),
(10, 'VisualShare', 'visualshare@vs.com', '$2y$10$q0UcLIBxgxzQw8gOXUZBC.66jjeV8ZQcqCX0fh9x/fo54aO2NBYl.', '2025-08-07 10:06:23', NULL, 'assets/profile_pics/profile_10_1754562632.png', NULL, NULL),
(11, 'Saymooo', 'saymooo@gmail.com', '$2y$10$9EHlN2qp8DOF1ivZTHVkkOPiGnOHTy1qjLjb1cqBFz9HfJybSdLw2', '2025-08-07 10:32:04', NULL, 'assets/profile_pics/profile_11_1756323928.png', NULL, NULL),
(12, 'Thomas Jefferson', 'thomas@vs.com', '$2y$10$eJlQLBAOB0MnUMbWhbv7MOS53D8bxN.2kmdXDaFyj99EqY/JEuPXa', '2025-08-07 10:38:26', NULL, NULL, NULL, NULL),
(13, 'Natty Chicko', 'natty@vs.com', '$2y$10$TMv5/qLovvuyrw74eWJb7.A8V/WXJsnw44KybocM/ali.YlG2qLMm', '2025-08-07 10:43:58', NULL, 'assets/profile_pics/profile_13_1754564740.jpg', NULL, NULL),
(14, 'Graphics Designer', 'graphics@vs.com', '$2y$10$6OV/YRqD3IbyVzFk799xVeBtQAaw.uuWaLpnTyRpO98dUM/Q28MIy', '2025-08-07 11:07:10', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_favorites`
--

CREATE TABLE `user_favorites` (
  `user_id` int(11) NOT NULL,
  `upload_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_favorites`
--

INSERT INTO `user_favorites` (`user_id`, `upload_id`, `created_at`) VALUES
(2, 33, '2025-08-26 23:49:49'),
(2, 38, '2025-08-26 23:49:48'),
(2, 43, '2025-08-26 23:49:47'),
(10, 33, '2025-08-29 12:50:53'),
(11, 30, '2025-08-27 23:23:03'),
(11, 35, '2025-08-27 23:23:02'),
(11, 39, '2025-08-27 23:23:01'),
(14, 35, '2025-08-13 20:04:24'),
(14, 40, '2025-08-13 20:04:36');

-- --------------------------------------------------------

--
-- Table structure for table `user_follows`
--

CREATE TABLE `user_follows` (
  `id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `following_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `follow_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_follows`
--

INSERT INTO `user_follows` (`id`, `follower_id`, `following_id`, `created_at`, `follow_date`) VALUES
(25, 6, 2, '2025-08-05 00:33:35', '2025-08-04 19:49:11'),
(26, 2, 3, '2025-08-05 15:58:55', '2025-08-05 10:58:55'),
(27, 2, 6, '2025-08-05 16:18:16', '2025-08-05 11:18:16'),
(28, 2, 8, '2025-08-06 17:12:18', '2025-08-06 12:12:18'),
(29, 8, 6, '2025-08-07 02:44:35', '2025-08-06 21:44:35'),
(69, 14, 12, '0000-00-00 00:00:00', '2025-08-29 05:04:20'),
(85, 14, 11, '0000-00-00 00:00:00', '2025-08-29 11:45:22'),
(89, 14, 13, '0000-00-00 00:00:00', '2025-08-29 11:45:50'),
(92, 14, 7, '0000-00-00 00:00:00', '2025-08-29 11:46:09'),
(97, 10, 14, '0000-00-00 00:00:00', '2025-08-29 12:43:44'),
(99, 11, 14, '0000-00-00 00:00:00', '2025-08-29 19:33:45'),
(100, 14, 10, '0000-00-00 00:00:00', '2025-08-29 20:04:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`user_id`,`upload_id`),
  ADD KEY `upload_id` (`upload_id`);

--
-- Indexes for table `saved_posts`
--
ALTER TABLE `saved_posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`upload_id`),
  ADD KEY `upload_id` (`upload_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `upload_tags`
--
ALTER TABLE `upload_tags`
  ADD PRIMARY KEY (`upload_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD PRIMARY KEY (`user_id`,`upload_id`),
  ADD KEY `upload_id` (`upload_id`);

--
-- Indexes for table `user_follows`
--
ALTER TABLE `user_follows`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_follow` (`follower_id`,`following_id`),
  ADD KEY `following_id` (`following_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `saved_posts`
--
ALTER TABLE `saved_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_follows`
--
ALTER TABLE `user_follows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`upload_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `saved_posts`
--
ALTER TABLE `saved_posts`
  ADD CONSTRAINT `saved_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `saved_posts_ibfk_2` FOREIGN KEY (`upload_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `uploads`
--
ALTER TABLE `uploads`
  ADD CONSTRAINT `uploads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `upload_tags`
--
ALTER TABLE `upload_tags`
  ADD CONSTRAINT `upload_tags_ibfk_1` FOREIGN KEY (`upload_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `upload_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD CONSTRAINT `user_favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_favorites_ibfk_2` FOREIGN KEY (`upload_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_follows`
--
ALTER TABLE `user_follows`
  ADD CONSTRAINT `user_follows_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_follows_ibfk_2` FOREIGN KEY (`following_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
