-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 30, 2025 at 09:34 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

CREATE DATABASE IF NOT EXISTS `fashionhub-old` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `fashionhub-old`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fashionhub-old`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('billing','shipping') NOT NULL,
  `name` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `type`, `name`, `street`, `city`, `state`, `zip`, `country`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 6, 'billing', 'Sayam Khan', 'Khema', 'Timergara', 'Kpk', '25000', 'IN', 0, '2025-08-21 10:06:54', '2025-08-21 10:06:54'),
(2, 11, 'billing', 'Sayam Khan', 'Khema', 'Timergara', 'Kpk', '25000', 'PK', 0, '2025-08-29 03:53:52', '2025-08-29 04:52:03'),
(3, 11, 'shipping', 'Sayam Khan', 'Khema', 'Timergara', 'Kpk', '25000', 'PK', 0, '2025-08-29 03:54:13', '2025-08-29 04:52:09');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `size` varchar(10) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `size`, `color`, `created_at`, `updated_at`) VALUES
(44, 9, 2, 1, NULL, NULL, '2025-08-24 10:53:22', '2025-08-24 10:53:22'),
(45, 9, 3, 1, NULL, NULL, '2025-08-24 10:53:22', '2025-08-24 10:53:22'),
(46, 9, 3, 1, NULL, NULL, '2025-08-24 10:53:22', '2025-08-24 10:53:22'),
(47, 9, 3, 1, NULL, NULL, '2025-08-24 10:53:22', '2025-08-24 10:53:22'),
(48, 9, 3, 1, NULL, NULL, '2025-08-24 10:53:22', '2025-08-24 10:53:22'),
(49, 9, 5, 1, NULL, NULL, '2025-08-24 10:53:22', '2025-08-24 10:53:22'),
(50, 9, 5, 1, NULL, NULL, '2025-08-24 10:53:22', '2025-08-24 10:53:22'),
(51, 9, 5, 1, NULL, NULL, '2025-08-24 10:53:22', '2025-08-24 10:53:22'),
(52, 9, 5, 1, NULL, NULL, '2025-08-24 10:53:22', '2025-08-24 10:53:22'),
(53, 9, 6, 1, NULL, NULL, '2025-08-24 10:53:22', '2025-08-24 10:53:22'),
(54, 9, 7, 1, NULL, NULL, '2025-08-24 10:53:22', '2025-08-24 10:53:22'),
(55, 9, 8, 1, NULL, NULL, '2025-08-24 10:53:22', '2025-08-24 10:53:22'),
(56, 9, 9, 1, NULL, NULL, '2025-08-24 10:53:22', '2025-08-24 10:53:22'),
(57, 9, 9, 1, NULL, NULL, '2025-08-24 10:53:22', '2025-08-24 10:53:22'),
(58, 9, 10, 1, NULL, NULL, '2025-08-24 10:53:22', '2025-08-24 10:53:22'),
(59, 9, 10, 1, NULL, NULL, '2025-08-24 10:53:22', '2025-08-24 10:53:22'),
(60, 9, 10, 1, NULL, NULL, '2025-08-24 10:53:22', '2025-08-24 10:53:22'),
(61, 9, 11, 1, NULL, NULL, '2025-08-24 10:53:22', '2025-08-24 10:53:22'),
(62, 9, 12, 1, NULL, NULL, '2025-08-24 10:53:22', '2025-08-24 10:53:22'),
(63, 9, 5, 1, NULL, NULL, '2025-08-24 10:53:29', '2025-08-24 10:53:29'),
(123, 11, 5, 1, NULL, NULL, '2025-08-27 22:28:53', '2025-08-27 22:28:53'),
(125, 10, 1, 1, NULL, NULL, '2025-08-29 15:14:07', '2025-08-29 15:14:07'),
(126, 10, 5, 1, NULL, NULL, '2025-08-29 15:14:08', '2025-08-29 15:14:08');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_number` varchar(20) NOT NULL,
  `order_date` datetime NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `shipping_address` text NOT NULL,
  `billing_address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `order_date`, `status`, `total_amount`, `payment_method`, `payment_status`, `shipping_address`, `billing_address`, `created_at`, `updated_at`) VALUES
(1, 11, 'ORD-20250827-68AE301', '2025-08-27 03:07:20', 'pending', 84.99, 'Cash on Delivery', 'pending', '{\"name\":\"Muhammad Saad\",\"email\":\"mhsaad23305@gmail.com\",\"address\":\"msaad23305@gmail.com\",\"address2\":\"\",\"city\":\"Timergara\",\"state\":\"Kpk\",\"zip\":\"25000\",\"country\":\"US\",\"phone\":\"+09429842565\"}', '{\"name\":\"Muhammad Saad\",\"email\":\"mhsaad23305@gmail.com\",\"address\":\"msaad23305@gmail.com\",\"address2\":\"\",\"city\":\"Timergara\",\"state\":\"Kpk\",\"zip\":\"25000\",\"country\":\"US\",\"phone\":\"+09429842565\"}', '2025-08-26 22:07:20', '2025-08-26 22:07:20'),
(2, 11, 'ORD-20250827-68AE37C', '2025-08-27 03:40:15', 'pending', 104.99, 'Cash on Delivery', 'pending', '{\"name\":\"Muhammad Saad\",\"email\":\"mhsaad23305@gmail.com\",\"address\":\"msaad23305@gmail.com\",\"address2\":\"\",\"city\":\"Timergara\",\"state\":\"Kpk\",\"zip\":\"25000\",\"country\":\"US\",\"phone\":\"+09429842565\"}', '{\"name\":\"Muhammad Saad\",\"email\":\"mhsaad23305@gmail.com\",\"address\":\"msaad23305@gmail.com\",\"address2\":\"\",\"city\":\"Timergara\",\"state\":\"Kpk\",\"zip\":\"25000\",\"country\":\"US\",\"phone\":\"+09429842565\"}', '2025-08-26 22:40:15', '2025-08-26 22:40:15'),
(3, 11, 'ORD-20250827-68AF77F', '2025-08-28 02:26:19', 'pending', 64.99, 'Cash on Delivery', 'pending', '{\"name\":\"Muhammad Saad\",\"email\":\"mhsaad23305@gmail.com\",\"address\":\"msaad23305@gmail.com\",\"address2\":\"\",\"city\":\"Timergara\",\"state\":\"Kpk\",\"zip\":\"25000\",\"country\":\"US\",\"phone\":\"+09429842565\"}', '{\"name\":\"Muhammad Saad\",\"email\":\"mhsaad23305@gmail.com\",\"address\":\"msaad23305@gmail.com\",\"address2\":\"\",\"city\":\"Timergara\",\"state\":\"Kpk\",\"zip\":\"25000\",\"country\":\"US\",\"phone\":\"+09429842565\"}', '2025-08-27 21:26:19', '2025-08-27 21:26:19'),
(4, 11, 'ORD-20250827-68AF791', '2025-08-28 02:31:10', 'pending', 64.99, 'Cash on Delivery', 'pending', '{\"name\":\"Muhammad Saad\",\"email\":\"mhsaad23305@gmail.com\",\"address\":\"msaad23305@gmail.com\",\"address2\":\"\",\"city\":\"Timergara\",\"state\":\"Kpk\",\"zip\":\"25000\",\"country\":\"US\",\"phone\":\"+09429842565\"}', '{\"name\":\"Muhammad Saad\",\"email\":\"mhsaad23305@gmail.com\",\"address\":\"msaad23305@gmail.com\",\"address2\":\"\",\"city\":\"Timergara\",\"state\":\"Kpk\",\"zip\":\"25000\",\"country\":\"US\",\"phone\":\"+09429842565\"}', '2025-08-27 21:31:10', '2025-08-27 21:31:10'),
(5, 11, 'ORD-20250828-68AF86B', '2025-08-28 03:29:10', 'pending', 164.98, 'Cash on Delivery', 'pending', '{\"name\":\"Muhammad Saad\",\"email\":\"mhsaad23305@gmail.com\",\"address\":\"msaad23305@gmail.com\",\"address2\":\"\",\"city\":\"Timergara\",\"state\":\"Kpk\",\"zip\":\"25000\",\"country\":\"US\",\"phone\":\"+09429842565\"}', '{\"name\":\"Muhammad Saad\",\"email\":\"mhsaad23305@gmail.com\",\"address\":\"msaad23305@gmail.com\",\"address2\":\"\",\"city\":\"Timergara\",\"state\":\"Kpk\",\"zip\":\"25000\",\"country\":\"US\",\"phone\":\"+09429842565\"}', '2025-08-27 22:29:10', '2025-08-27 22:29:10'),
(6, 10, 'ORD-20250829-68B1C2C', '2025-08-29 20:09:56', 'pending', 134.99, 'Cash on Delivery', 'pending', '{\"name\":\"Muhammad Saad\",\"email\":\"mhsaad23305@gmail.com\",\"address\":\"msaad23305@gmail.com\",\"address2\":\"\",\"city\":\"Timergara\",\"state\":\"Kpk\",\"zip\":\"25000\",\"country\":\"US\",\"phone\":\"+09429842565\"}', '{\"name\":\"Muhammad Saad\",\"email\":\"mhsaad23305@gmail.com\",\"address\":\"msaad23305@gmail.com\",\"address2\":\"\",\"city\":\"Timergara\",\"state\":\"Kpk\",\"zip\":\"25000\",\"country\":\"US\",\"phone\":\"+09429842565\"}', '2025-08-29 15:09:56', '2025-08-29 15:09:56');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `size` varchar(10) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`, `size`, `color`, `image`, `created_at`) VALUES
(1, 1, 10, 'Wool Blend Sweater', 1, 79.99, 'N/A', 'N/A', 'https://images.unsplash.com/photo-1527719327859-c6ce80353573?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '2025-08-26 22:07:20'),
(2, 2, 3, 'Classic Denim Jacket', 1, 99.99, 'N/A', 'N/A', 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '2025-08-26 22:40:15'),
(3, 3, 1, 'Premium Cotton Shirt', 1, 59.99, 'N/A', 'N/A', 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '2025-08-27 21:26:19'),
(4, 4, 1, 'Premium Cotton Shirt', 1, 59.99, 'N/A', 'N/A', 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '2025-08-27 21:31:10'),
(5, 5, 1, 'Premium Cotton Shirt', 1, 59.99, 'N/A', 'N/A', 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '2025-08-27 22:29:10'),
(6, 5, 3, 'Classic Denim Jacket', 1, 99.99, 'N/A', 'N/A', 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '2025-08-27 22:29:10'),
(7, 6, 6, 'Premium Wool Coat', 1, 129.99, 'N/A', 'N/A', 'https://images.unsplash.com/photo-1520367445093-50dc08a59d9d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '2025-08-29 15:09:56');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `old_price` decimal(10,2) DEFAULT NULL,
  `image` varchar(500) NOT NULL,
  `colors` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`colors`)),
  `sizes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`sizes`)),
  `rating` decimal(2,1) NOT NULL,
  `reviews` int(11) NOT NULL,
  `badge` varchar(20) DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `new_arrival` tinyint(1) NOT NULL DEFAULT 0,
  `sku` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`features`)),
  `color_codes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`color_codes`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `category`, `price`, `old_price`, `image`, `colors`, `sizes`, `rating`, `reviews`, `badge`, `featured`, `new_arrival`, `sku`, `description`, `features`, `color_codes`, `created_at`, `updated_at`) VALUES
(1, 'Premium Cotton Shirt', 'shirts', 59.99, 74.99, 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '[\"black\", \"blue\", \"white\"]', '[\"XS\", \"S\", \"M\", \"L\", \"XL\"]', 4.5, 24, 'sale', 1, 0, 'FH-001', 'This premium cotton shirt is crafted from 100% organic cotton for maximum comfort and breathability. The tailored fit provides a modern silhouette while allowing freedom of movement. Perfect for both casual and business casual occasions.', '[\"100% Organic Cotton\", \"Button-down collar\", \"Single chest pocket\", \"Tailored fit\", \"Machine washable\"]', '{\"black\": \"#3a3a3a\", \"blue\": \"#5a8ac1\", \"white\": \"#e6e6e6\"}', '2025-08-22 11:37:03', '2025-08-22 11:37:03'),
(2, 'Slim Fit Jeans', 'jeans', 79.99, 89.99, 'https://images.unsplash.com/photo-1542272604-787c3835535d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '[\"black\", \"blue\"]', '[\"28\", \"30\", \"32\", \"34\", \"36\"]', 4.0, 18, 'new', 0, 1, 'FH-002', 'These slim fit jeans are designed for a modern, tailored look. Made from premium denim with just the right amount of stretch for comfort. The dark wash makes them versatile enough for both casual and dressier occasions.', '[\"98% Cotton, 2% Elastane\", \"Slim fit through hip and thigh\", \"Zip fly with button closure\", \"Five-pocket styling\", \"Machine wash cold\"]', '{\"black\": \"#3a3a3a\", \"blue\": \"#5a8ac1\"}', '2025-08-22 11:37:03', '2025-08-22 11:37:03'),
(3, 'Classic Denim Jacket', 'jackets', 99.99, NULL, 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '[\"blue\", \"black\"]', '[\"S\", \"M\", \"L\", \"XL\"]', 5.0, 32, NULL, 1, 0, 'FH-003', 'This timeless denim jacket is a wardrobe essential. Made from durable 12-ounce denim, it features a classic fit that layers easily over your favorite tops. The jacket has a button-front closure, chest pockets, and adjustable waist tabs for a custom fit.', '[\"100% Cotton denim\", \"Classic fit\", \"Button-front closure\", \"Chest pockets with flap\", \"Adjustable waist tabs\"]', '{\"blue\": \"#5a8ac1\", \"black\": \"#3a3a3a\"}', '2025-08-22 11:37:03', '2025-08-22 11:37:03'),
(4, 'Casual Summer Dress', 'dresses', 69.99, 79.99, 'https://images.unsplash.com/photo-1529374255404-311a2a4f1fd9?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '[\"beige\", \"white\", \"black\"]', '[\"XS\", \"S\", \"M\"]', 4.0, 21, 'sale', 0, 0, 'FH-004', 'This breezy summer dress is perfect for warm weather. Made from lightweight linen blend fabric that drapes beautifully and keeps you cool. The wrap-style design with tie waist creates a flattering silhouette for all body types.', '[\"65% Linen, 35% Cotton\", \"Wrap-style with tie waist\", \"V-neckline\", \"Short sleeves\", \"Machine wash gentle\"]', '{\"beige\": \"#d4a762\", \"white\": \"#e6e6e6\", \"black\": \"#3a3a3a\"}', '2025-08-22 11:37:03', '2025-08-22 11:37:03'),
(5, 'Leather Crossbody Bag', 'accessories', 89.99, NULL, 'https://images.unsplash.com/photo-1543076447-215ad9ba6923?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '[\"black\", \"beige\"]', '[\"One Size\"]', 4.5, 27, NULL, 1, 0, 'FH-005', 'This stylish crossbody bag is crafted from genuine leather that develops a beautiful patina over time. The compact design fits all your essentials while keeping your hands free. Features multiple compartments for organization and an adjustable strap for comfort.', '[\"Genuine leather\", \"Adjustable crossbody strap\", \"Main zip compartment\", \"Interior slip pocket\", \"Exterior back zip pocket\"]', '{\"black\": \"#3a3a3a\", \"beige\": \"#d4a762\"}', '2025-08-22 11:37:03', '2025-08-22 11:37:03'),
(6, 'Premium Wool Coat', 'jackets', 129.99, NULL, 'https://images.unsplash.com/photo-1520367445093-50dc08a59d9d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '[\"black\", \"beige\"]', '[\"S\", \"M\", \"L\"]', 4.0, 15, 'new', 0, 1, 'FH-006', 'This premium wool coat is perfect for transitional weather. Made from a wool blend that provides warmth without bulk. The tailored silhouette and notched lapel create a polished look that works from office to evening.', '[\"70% Wool, 30% Polyester\", \"Notched lapel\", \"Single-breasted button front\", \"Flap pockets\", \"Lined interior\"]', '{\"black\": \"#3a3a3a\", \"beige\": \"#d4a762\"}', '2025-08-22 11:37:03', '2025-08-22 11:37:03'),
(7, 'Linen Button-Up Shirt', 'shirts', 49.99, 59.99, 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '[\"white\", \"blue\"]', '[\"S\", \"M\", \"L\", \"XL\"]', 4.5, 19, 'sale', 1, 0, 'FH-007', 'This lightweight linen shirt is perfect for warm weather. The breathable fabric and relaxed fit keep you cool and comfortable all day long. The button-up design makes it versatile enough for both casual and dressier occasions.', '[\"100% Linen\", \"Button-up front\", \"Chest pocket\", \"Relaxed fit\", \"Machine washable\"]', '{\"white\": \"#e6e6e6\", \"blue\": \"#5a8ac1\"}', '2025-08-22 11:37:03', '2025-08-22 11:37:03'),
(8, 'Cashmere Scarf', 'accessories', 59.99, NULL, 'https://images.unsplash.com/photo-1595341595379-cf0ff4911a1e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '[\"red\", \"black\", \"beige\"]', '[\"One Size\"]', 5.0, 12, NULL, 0, 0, 'FH-008', 'This luxurious cashmere scarf is the perfect accessory for cooler weather. The ultra-soft cashmere provides warmth without bulk, and the generous size allows for versatile styling. A timeless piece that will last for years.', '[\"100% Cashmere\", \"Generous size: 70\\\" x 12\\\"\", \"Fringed ends\", \"Ultra-soft hand feel\", \"Dry clean recommended\"]', '{\"red\": \"#ff0000\", \"black\": \"#3a3a3a\", \"beige\": \"#d4a762\"}', '2025-08-22 11:37:03', '2025-08-22 11:37:03'),
(9, 'Classic White Sneakers', 'shoes', 89.99, NULL, 'https://images.unsplash.com/photo-1600269452121-4f2416e55c28?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '[\"white\", \"black\"]', '[\"US 7\", \"US 8\", \"US 9\", \"US 10\", \"US 11\"]', 4.8, 42, 'best-seller', 1, 0, 'FH-009', 'These classic white sneakers are a wardrobe staple. Made from premium leather with a comfortable cushioned insole, they\'re perfect for all-day wear. The timeless design pairs well with any outfit.', '[\"Premium leather upper\", \"Cushioned insole for comfort\", \"Rubber outsole for traction\", \"Lace-up closure\", \"Machine washable\"]', '{\"white\": \"#ffffff\", \"black\": \"#3a3a3a\"}', '2025-08-22 11:37:03', '2025-08-22 11:37:03'),
(10, 'Wool Blend Sweater', 'sweaters', 79.99, 99.99, 'https://images.unsplash.com/photo-1527719327859-c6ce80353573?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '[\"navy\", \"gray\", \"burgundy\"]', '[\"S\", \"M\", \"L\", \"XL\"]', 4.3, 28, 'sale', 1, 0, 'FH-010', 'This wool blend sweater offers warmth and style for the cooler months. The relaxed fit and ribbed cuffs provide both comfort and a polished look. Perfect for layering or wearing on its own.', '[\"70% Wool, 30% Acrylic\", \"Ribbed cuffs and hem\", \"Relaxed fit\", \"Crew neckline\", \"Machine wash cold\"]', '{\"navy\": \"#001f3f\", \"gray\": \"#aaaaaa\", \"burgundy\": \"#800020\"}', '2025-08-22 11:37:03', '2025-08-22 11:37:03'),
(11, 'Silk Blouse', 'shirts', 89.99, NULL, 'https://images.unsplash.com/photo-1581044777550-4cfa60707c03?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '[\"ivory\", \"black\", \"sage\"]', '[\"XS\", \"S\", \"M\", \"L\"]', 4.6, 35, NULL, 0, 1, 'FH-011', 'This elegant silk blouse features a delicate drape and subtle sheen that elevates any outfit. The button-front design and pointed collar create a sophisticated look perfect for work or special occasions.', '[\"100% Silk\", \"Button-front closure\", \"Pointed collar\", \"Long sleeves with button cuffs\", \"Dry clean only\"]', '{\"ivory\": \"#fffff0\", \"black\": \"#3a3a3a\", \"sage\": \"#b2ac88\"}', '2025-08-22 11:37:03', '2025-08-22 11:37:03'),
(12, 'Leather Belt', 'accessories', 49.99, NULL, 'https://images.unsplash.com/photo-1595341595379-cf0ff4911a1e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '[\"brown\", \"black\"]', '[\"S (30\\\")\", \"M (32\\\")\", \"L (34\\\")\", \"XL (36\\\")\"]', 4.2, 19, NULL, 0, 0, 'FH-012', 'This genuine leather belt features a classic design with a polished buckle. The high-quality leather develops a beautiful patina over time, making it a durable and stylish accessory.', '[\"Genuine leather\", \"Polished metal buckle\", \"Adjustable fit\", \"Reversible (black/brown option)\", \"Width: 1.5 inches\"]', '{\"brown\": \"#964B00\", \"black\": \"#3a3a3a\"}', '2025-08-22 11:37:03', '2025-08-22 11:37:03');

-- --------------------------------------------------------

--
-- Table structure for table `remember_tokens`
--

CREATE TABLE `remember_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `remember_tokens`
--

INSERT INTO `remember_tokens` (`id`, `user_id`, `token`, `expires_at`, `created_at`) VALUES
(1, 3, '141997d713e9b21c2ab5fe928d5de195540e6f3e39ec963d51aac5c0d8181b72', '2025-09-17 12:46:14', '2025-08-18 10:46:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `users` ADD COLUMN `role` ENUM('user', 'admin') DEFAULT 'user' AFTER `password`;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Saad khan', 'saad123@gmail.com', '$2y$10$jRcJMTv3aAr1DxEvu09ubuah2ZNPK9gF6xAR7SVvJzVWE1y4i0lbG', 'user', '2025-08-18 10:08:28', '2025-08-18 10:08:28'),
(2, 'Saad khan', 'saad13@gmail.com', '$2y$10$uklSOh9W.hrS/JqmAPdiSezaGvqCwdzhLqZJ4O5IV/AzqA1/lkT.S', 'user', '2025-08-18 10:19:40', '2025-08-18 10:19:40'),
(3, 'Saad khan', 'saad3@gmail.com', '$2y$10$ANqakRMgpV5XCjNKTIrKv.pni1OxczmTIIWYudvASTCKTkgV46P8y', 'user', '2025-08-18 10:30:00', '2025-08-18 10:30:00'),
(4, 'Saad khan', 'saad6@gmail.com', '$2y$10$7gsQVgSf8saZqSwtUkmqpeQShdhMTb.4XCa.8R4YUKfPctm2it2aK', 'user', '2025-08-18 11:38:43', '2025-08-18 11:38:43'),
(5, 'Garry Kasparov', 'garrykasparov@gmail.com', '$2y$10$Y5V/kJJHq/s0UxR33Ncu5OharbDvoJ73KNn9u814B6UGc6wTL1eLm', 'user', '2025-08-19 15:23:46', '2025-08-19 15:23:46'),
(6, 'Muhammd Saad', 'msaad23305@gmail.com', '$2y$10$4b25seyyQPNz8ByCDvukjep9fRnF9s7HjW/Kia.5bX6baIYtfObz.', 'user', '2025-08-20 18:56:19', '2025-08-20 18:56:19'),
(7, 'Mh Saad', 'mhsaad23305@gmail.com', '$2y$10$uMLASAbXPcEx9/C.07B5MO2d9TG2MmHMrmSA6MluwNSki/i6.ZxHu', 'user', '2025-08-21 11:02:30', '2025-08-21 11:02:30'),
(9, 'Sohail jan', 'sohail@gmail.com', '$2y$10$gDJM08q29ujTbeQJjxm2fOHC3vVQCf1LXTi9G8/WHhUxtoEzYyG6W', 'user', '2025-08-22 13:42:36', '2025-08-22 13:42:36'),
(10, 'izhar Khan', 'izhar@gmail.com', '$2y$10$XfoGxrRQcMXFTa1h6FSzvuUEWHpCdL/8Vw9pdPm.QGhnkxt3HTDja', 'user', '2025-08-22 15:28:37', '2025-08-29 13:03:44'),
(11, 'Richard Son', 'richard@gmail.com', '$2y$10$Z0vsvwHdF.0Rqc6poD9dMuamofIRx10nUzl9mtXGd87xDUW9Ia0XC', 'user', '2025-08-24 10:41:50', '2025-08-29 04:52:53'),
(12, 'Admin User', 'admin@fashionhub.com', '$2y$10$8K1p/5w6BkQyLx8FcQ8UeO8K1p/5w6BkQyLx8FcQ8UeO8K1p/5w6BkQyLx8FcQ8Ue', 'admin', '2025-08-30 19:32:49', '2025-08-30 19:32:49');

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_sessions`
--

INSERT INTO `user_sessions` (`id`, `user_id`, `session_id`, `ip_address`, `user_agent`, `created_at`, `expires_at`) VALUES
(1, 2, 'blq78rfr1k34llb5g60q0kfreb', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-18 10:19:52', '2025-09-17 12:19:52'),
(2, 2, 'blq78rfr1k34llb5g60q0kfreb', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-18 10:19:59', '2025-09-17 12:19:59'),
(3, 2, 'blq78rfr1k34llb5g60q0kfreb', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', '2025-08-18 10:21:53', '2025-09-17 12:21:53'),
(4, 3, 'chfdu9pbrabjq9rcaaj0a4d4bp', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Mobile Safari/537.36 Edg/139.0.0.0', '2025-08-18 10:38:36', '2025-09-17 12:38:36');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `added_at`) VALUES
(20, 9, 10, '2025-08-23 10:36:01'),
(22, 9, 11, '2025-08-23 11:23:48'),
(25, 9, 1, '2025-08-23 11:28:03'),
(26, 9, 7, '2025-08-23 11:28:05'),
(28, 9, 3, '2025-08-23 12:09:51'),
(29, 9, 5, '2025-08-24 09:24:05'),
(30, 9, 9, '2025-08-24 10:28:48'),
(37, 10, 1, '2025-08-25 23:34:07'),
(38, 10, 3, '2025-08-25 23:34:08'),
(39, 10, 5, '2025-08-25 23:34:08'),
(41, 11, 10, '2025-08-26 10:48:31'),
(43, 11, 3, '2025-08-27 22:22:10'),
(46, 11, 5, '2025-08-27 22:28:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart_item` (`user_id`,`product_id`,`size`,`color`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`);

--
-- Indexes for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_wishlist` (`user_id`,`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD CONSTRAINT `password_reset_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD CONSTRAINT `remember_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `user_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
