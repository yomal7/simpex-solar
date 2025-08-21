-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysolardb.cvggoaa6op0w.eu-north-1.rds.amazonaws.com:3306
-- Generation Time: Apr 29, 2025 at 07:21 AM
-- Server version: 8.0.40
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simpex_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `agreement_equipment`
--

CREATE TABLE `agreement_equipment` (
  `id` int NOT NULL,
  `agreement_id` int NOT NULL,
  `inventory_id` int NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(15,2) DEFAULT NULL,
  `total_price` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `agreement_equipment`
--

INSERT INTO `agreement_equipment` (`id`, `agreement_id`, `inventory_id`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES
(1, 4, 2, 4, 100000.00, 400000.00, '2024-12-24 18:42:34'),
(2, 4, 1, 3, 100000.00, 300000.00, '2024-12-24 18:42:34'),
(3, 4, 3, 2, 500000.00, 1000000.00, '2024-12-24 18:42:35'),
(73, 31, 2, 4, 100000.00, 400000.00, '2024-12-26 15:55:30'),
(74, 31, 1, 3, 100000.00, 300000.00, '2024-12-26 15:55:30'),
(75, 31, 3, 4, 500000.00, 2000000.00, '2024-12-26 15:55:31'),
(88, 36, 1, 3, 100000.00, 300000.00, '2025-01-02 05:21:54'),
(89, 36, 2, 1, 100000.00, 100000.00, '2025-01-02 05:21:54'),
(90, 37, 2, 1, 100000.00, 100000.00, '2025-02-11 13:06:10'),
(91, 37, 3, 2, 500000.00, 1000000.00, '2025-02-11 13:06:10'),
(92, 38, 2, 5, 100000.00, 500000.00, '2025-02-12 03:28:37'),
(93, 38, 3, 4, 500000.00, 2000000.00, '2025-02-12 03:28:37'),
(94, 38, 1, 1, 100000.00, 100000.00, '2025-02-12 03:28:37'),
(95, 39, 2, 1, 100000.00, 100000.00, '2025-02-16 09:23:00'),
(96, 39, 3, 2, 500000.00, 1000000.00, '2025-02-16 09:23:00'),
(97, 40, 1, 2, 100000.00, 200000.00, '2025-02-22 09:11:58'),
(98, 40, 2, 1, 100000.00, 100000.00, '2025-02-22 09:11:58'),
(99, 40, 3, 1, 500000.00, 500000.00, '2025-02-22 09:11:59'),
(100, 41, 1, 3, 100000.00, 300000.00, '2025-02-22 09:12:37'),
(101, 41, 2, 1, 100000.00, 100000.00, '2025-02-22 09:12:37'),
(102, 41, 3, 1, 500000.00, 500000.00, '2025-02-22 09:12:38'),
(103, 42, 1, 3, 100000.00, 300000.00, '2025-02-22 09:23:59'),
(104, 42, 2, 1, 100000.00, 100000.00, '2025-02-22 09:23:59'),
(105, 42, 3, 2, 500000.00, 1000000.00, '2025-02-22 09:24:00'),
(106, 43, 1, 3, 100000.00, 300000.00, '2025-02-22 09:24:06'),
(107, 43, 2, 1, 100000.00, 100000.00, '2025-02-22 09:24:06'),
(108, 43, 3, 2, 500000.00, 1000000.00, '2025-02-22 09:24:06'),
(109, 44, 1, 1, 100000.00, 100000.00, '2025-04-08 15:00:31'),
(110, 44, 2, 1, 100000.00, 100000.00, '2025-04-08 15:00:31'),
(111, 44, 4, 2, 200000.00, 400000.00, '2025-04-08 15:00:31'),
(112, 45, 2, 1, 100000.00, 100000.00, '2025-04-22 04:13:05'),
(113, 45, 3, 2, 500000.00, 1000000.00, '2025-04-22 04:13:05'),
(114, 46, 1, 1, 100000.00, 100000.00, '2025-04-22 09:16:41'),
(115, 46, 2, 1, 100000.00, 100000.00, '2025-04-22 09:16:41'),
(116, 47, 1, 3, 100000.00, 300000.00, '2025-04-24 07:54:25'),
(117, 47, 2, 1, 100000.00, 100000.00, '2025-04-24 07:54:25'),
(118, 47, 3, 1, 500000.00, 500000.00, '2025-04-24 07:54:25'),
(119, 48, 3, 2, 500000.00, 1000000.00, '2025-04-25 08:21:08'),
(120, 49, 1, 2, 100000.00, 200000.00, '2025-04-27 09:51:46'),
(121, 49, 2, 1, 100000.00, 100000.00, '2025-04-27 09:51:46'),
(122, 50, 1, 3, 100.00, 300.00, '2025-04-27 17:37:45'),
(123, 50, 2, 1, 100.00, 100.00, '2025-04-27 17:37:46'),
(124, 50, 3, 1, 500.00, 500.00, '2025-04-27 17:37:46'),
(125, 51, 1, 3, 100.00, 300.00, '2025-04-27 17:40:02'),
(126, 51, 2, 1, 100.00, 100.00, '2025-04-27 17:40:03'),
(127, 51, 3, 1, 500.00, 500.00, '2025-04-27 17:40:03'),
(128, 52, 1, 3, 100.00, 300.00, '2025-04-27 17:41:37'),
(129, 52, 2, 1, 100.00, 100.00, '2025-04-27 17:41:37'),
(130, 52, 3, 1, 500.00, 500.00, '2025-04-27 17:41:38'),
(131, 53, 2, 1, 100000.00, 100000.00, '2025-04-27 23:38:54'),
(132, 53, 3, 2, 500000.00, 1000000.00, '2025-04-27 23:38:54'),
(133, 54, 1, 3, 100000.00, 300000.00, '2025-04-28 04:17:41'),
(134, 54, 2, 1, 100000.00, 100000.00, '2025-04-28 04:17:41'),
(135, 54, 3, 1, 500000.00, 500000.00, '2025-04-28 04:17:41');

-- --------------------------------------------------------

--
-- Table structure for table `approvalschedule`
--

CREATE TABLE `approvalschedule` (
  `schedule_id` int NOT NULL,
  `approval_id` int NOT NULL,
  `proposed_datetime` datetime NOT NULL,
  `proposed_by` enum('operationManager','customer') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `proposed_user_id` int DEFAULT NULL,
  `customer_response` enum('pending','agreed','reschedule_request') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `customer_comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `final_datetime` datetime DEFAULT NULL,
  `status` enum('proposed','agreed','rejected','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'proposed',
  `engineer_id` int DEFAULT NULL,
  `approval_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `employee_id` int NOT NULL,
  `date` date NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`employee_id`, `date`, `time_in`, `time_out`, `created_at`, `updated_at`) VALUES
(4, '2025-04-13', '12:14:00', NULL, '2025-04-13 06:33:21', '2025-04-13 06:44:43'),
(4, '2025-04-21', '21:45:26', NULL, '2025-04-21 16:15:24', '2025-04-21 16:15:24'),
(4, '2025-04-22', '00:18:55', '22:39:36', '2025-04-21 18:48:53', '2025-04-22 17:09:35'),
(5, '2025-03-02', '02:42:00', NULL, '2025-03-05 02:53:11', '2025-03-05 02:53:11'),
(5, '2025-04-21', '22:20:48', NULL, '2025-04-21 16:50:47', '2025-04-21 16:50:47'),
(5, '2025-04-22', '00:18:51', '15:08:27', '2025-04-21 18:48:50', '2025-04-22 09:38:27'),
(6, '2025-04-22', '13:33:57', '22:39:15', '2025-04-22 08:03:58', '2025-04-22 17:09:14'),
(7, '2025-04-22', '13:34:12', '22:39:43', '2025-04-22 08:04:12', '2025-04-22 17:09:42'),
(8, '2025-04-21', '15:47:13', '15:47:17', '2025-04-21 13:47:12', '2025-04-21 13:47:16'),
(8, '2025-04-22', '15:28:41', '22:39:40', '2025-04-22 09:58:41', '2025-04-22 17:09:39'),
(9, '2025-04-13', '06:00:00', NULL, '2025-04-13 07:02:00', '2025-04-13 07:03:21'),
(9, '2025-04-21', '16:38:45', NULL, '2025-04-21 14:38:44', '2025-04-21 14:38:44'),
(9, '2025-04-22', '00:18:27', '00:18:32', '2025-04-21 18:48:25', '2025-04-21 18:48:30'),
(10, '2025-04-21', '14:50:41', '21:32:25', '2025-04-21 14:50:41', '2025-04-21 16:02:24'),
(10, '2025-04-22', '13:34:53', '13:35:04', '2025-04-22 08:04:52', '2025-04-22 08:05:03'),
(11, '2025-04-21', '14:50:59', '22:20:42', '2025-04-21 14:50:59', '2025-04-21 16:50:41'),
(11, '2025-04-22', '15:29:47', '15:29:57', '2025-04-22 09:59:47', '2025-04-22 09:59:57'),
(12, '2025-04-21', '17:37:15', '21:41:29', '2025-04-21 15:37:14', '2025-04-21 16:11:27'),
(12, '2025-04-22', '22:39:19', '22:39:46', '2025-04-22 17:09:18', '2025-04-22 17:09:45'),
(12, '2025-04-27', '07:10:58', NULL, '2025-04-27 01:40:56', '2025-04-27 01:40:56'),
(13, '2025-04-26', '23:43:11', '23:43:19', '2025-04-26 18:13:11', '2025-04-26 18:13:19'),
(13, '2025-04-27', '07:09:13', NULL, '2025-04-27 01:39:11', '2025-04-27 01:39:11'),
(15, '2025-04-27', '07:09:35', NULL, '2025-04-27 01:39:34', '2025-04-27 01:39:34'),
(17, '2025-04-27', '07:09:55', NULL, '2025-04-27 01:39:54', '2025-04-27 01:39:54'),
(18, '2025-04-27', '07:09:20', '07:09:50', '2025-04-27 01:39:19', '2025-04-27 01:39:49'),
(21, '2025-04-27', '07:09:28', NULL, '2025-04-27 01:39:27', '2025-04-27 01:39:27'),
(22, '2025-04-26', '23:43:16', NULL, '2025-04-26 18:13:15', '2025-04-26 18:13:15'),
(23, '2025-04-27', '07:09:41', '07:09:46', '2025-04-27 01:39:40', '2025-04-27 01:39:45');

-- --------------------------------------------------------

--
-- Table structure for table `backup_feedback`
--

CREATE TABLE `backup_feedback` (
  `id` int NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `feedback_type` enum('general','suggestion','complaint','compliment','inquiry') NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `rating` int DEFAULT NULL,
  `status` enum('new','in_progress','resolved','closed') NOT NULL DEFAULT 'new',
  `admin_notes` text,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `backup_feedback`
--

INSERT INTO `backup_feedback` (`id`, `name`, `email`, `phone`, `feedback_type`, `subject`, `message`, `rating`, `status`, `admin_notes`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 'Ajith', 'ajith@gmail.com', '0712323423', 'general', 'hi there', 'adedeffref efnkjf heckjbfr jdjkdbc hddjdk xd edjksebjwe eudwedeu', 3, 'new', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-16 14:40:04', NULL),
(3, 'Ajith', 'habdthilakasiri2002@gmail.com', '0712323423', 'suggestion', 'ane bn', 'ane bn mokakda karanne', 5, 'in_progress', '\n\n[2025-04-19 19:32:47] Email response sent:\nDear Ajith,\r\n\r\nThank you for your feedback regarding \"ane bn\".\r\n\r\n[Your response here]\r\n\r\nBest regards,\r\nThe SimplEx Solar Team', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 19:32:15', '2025-04-19 17:32:47'),
(4, 'kasun', 'kasun@gmail.com', '0712324324', 'general', 'ane bn', 'ewdweded', 3, 'closed', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 19:38:50', '2025-04-20 15:37:46'),
(5, 'xscsd', 'dcsdcs@sdcds.com', '071123224', 'suggestion', 'sdcsdc', 'asdxede', 3, 'new', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 19:43:30', NULL),
(6, 'QDWDED', 'EWDREF@assd.com', '0713244424', 'general', 'eefer', 'ederf', 3, 'new', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 19:45:15', NULL),
(8, 'Ajith', 'habdthilakasiri2002@gmail.com', '0712323423', 'complaint', 'ane bn', 'ewwdedr', 3, 'in_progress', '\n\n[2025-04-22 06:09:32] Email response sent:\nDear Ajith,\r\n\r\n                                    Thank you for your feedback regarding \"ane bn\".\r\n\r\n                                    [Y]cerwrvrtfr\r\n                                    Best regards,\r\n                                    The SimplEx Solar Team', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-22 06:07:51', '2025-04-22 04:09:30'),
(9, 'Binula', 'habdthilakasiri2002@gmail.com', '071123456', 'suggestion', 'edhkw', 'edcaerfe', 4, 'new', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-24 11:43:38', NULL),
(10, 'Ajith', 'kasun@gmail.com', NULL, 'general', 'today', 'hisxidu', 3, 'new', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-29 00:22:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bank_slips`
--

CREATE TABLE `bank_slips` (
  `id` int NOT NULL,
  `payment_id` int DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('pending','approve','reject') DEFAULT 'pending',
  `reject_reason` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bank_slips`
--

INSERT INTO `bank_slips` (`id`, `payment_id`, `image`, `status`, `reject_reason`, `created_at`, `updated_at`) VALUES
(1, 14, 'uploads/bank_slips/67bad551f0416_download.jpeg', 'pending', NULL, '2025-02-23 07:59:14', '2025-02-23 07:59:14'),
(2, 16, 'uploads/bank_slips/67bad6d619c17_download.jpeg', 'pending', NULL, '2025-02-23 08:05:42', '2025-02-23 08:05:42'),
(3, 18, 'uploads/bank_slips/67c014a8755b4_download.jpeg', 'pending', NULL, '2025-02-27 07:30:49', '2025-02-27 07:30:49');

-- --------------------------------------------------------

--
-- Table structure for table `blogcomments`
--

CREATE TABLE `blogcomments` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `post_id` int NOT NULL,
  `body` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `category_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_categories`
--

INSERT INTO `blog_categories` (`category_id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Equipment Section', 'equipment', 'Information about solar equipment and components', '2024-11-24 11:36:52', '2024-11-24 11:36:52'),
(2, 'User Guide Section', 'user-guide', 'Guides and tutorials for system usage', '2024-11-24 11:36:52', '2024-11-24 11:36:52'),
(3, 'Package Selection and Customization', 'package-selection', 'Details about available packages and customization options', '2024-11-24 11:36:52', '2024-11-24 11:36:52'),
(4, 'Installation Process Section', 'installation', 'Information about the installation process', '2024-11-24 11:36:52', '2024-11-24 11:36:52'),
(5, 'Energy Management Section', 'energy-management', 'Tips and guides for energy management', '2024-11-24 11:36:52', '2024-11-24 11:36:52'),
(6, 'Agreement and Documentation Section', 'agreements', 'Information about contracts and documentation', '2024-11-24 11:36:52', '2024-11-24 11:36:52'),
(7, 'Payment and Financing Section', 'payment-financing', 'Details about payment options and financing', '2024-11-24 11:36:52', '2024-11-24 11:36:52'),
(8, 'Customer Support and After-Sales Section', 'customer-support', 'Support information and after-sales services', '2024-11-24 11:36:52', '2024-11-24 11:36:52'),
(9, 'Industry News and Innovations Section', 'industry-news', 'Latest updates and innovations in solar industry', '2024-11-24 11:36:52', '2024-11-24 11:36:52'),
(10, 'Sustainability and Environmental Section', 'sustainability', 'Environmental impact and sustainability information', '2024-11-24 11:36:52', '2024-11-24 11:36:52');

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `post_id` int NOT NULL,
  `user_id` int NOT NULL,
  `category_id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `summary` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `featured_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `views` int NOT NULL DEFAULT '0',
  `status` enum('draft','published') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`post_id`, `user_id`, `category_id`, `title`, `slug`, `summary`, `body`, `featured_image`, `views`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'Growat solar panles new', 'growat-solar-panles-new', 'Growatt Solar Panels: A Comprehensive Guide to Sustainable Energy Solutions new', 'As the global demand for renewable energy surges, Growatt has emerged as a leading provider of solar energy solutions. Known for its high-performance solar panels and inverters, Growatt offers sustainable, efficient, and affordable energy solutions for residential, commercial, and industrial applications. This article explores the features, benefits, and applications of Growatt solar panels, providing an insightful overview for potential customers and eco-conscious enthusiasts.\r\n\r\nAbout Growatt\r\nFounded in 2010, Growatt specializes in the research, development, and manufacturing of solar energy products, including solar panels, inverters, and energy storage systems. The company is recognized worldwide for its innovative technology, high efficiency, and commitment to sustainability.\r\n\r\nGrowatt has a global presence with installations in over 100 countries, supported by a robust network of customer service centers. Its products are widely acclaimed for their reliability and advanced features, making it a popular choice for energy-conscious consumers.\r\n\r\nKey Features of Growatt Solar Panels\r\nHigh Efficiency\r\nGrowatt solar panels are designed with advanced photovoltaic (PV) technology, ensuring high energy conversion efficiency. This means you can generate more power from fewer panels, optimizing space and investment.\r\n\r\nDurability and Longevity\r\nBuilt with premium materials, Growatt solar panels are engineered to withstand harsh weather conditions, including extreme heat, heavy rain, and snow. The panels come with warranties of up to 25 years, ensuring long-term performance and reliability.\r\n\r\nSmart Technology Integration\r\nGrowatt panels are compatible with smart monitoring systems, allowing users to track energy production, consumption, and efficiency in real-time. This integration enhances energy management and ensures optimal performance.\r\n\r\nEnvironmentally Friendly Design\r\nGrowatt solar panels are designed with sustainability in mind, using recyclable materials and energy-efficient manufacturing processes to minimize their environmental footprint.\r\n\r\nVersatile Applications\r\nGrowatt offers solar panels suitable for various applications, from small residential rooftops to large-scale solar farms. The panels are also compatible with Growatt inverters and storage systems, making them ideal for integrated solar solutions.\r\n\r\nBenefits of Growatt Solar Panels\r\nCost Savings: By harnessing solar energy, users can significantly reduce electricity bills and even earn credits through net metering programs.\r\nEnergy Independence: Growatt panels enable households and businesses to generate their own electricity, reducing dependence on traditional power grids.\r\nReduced Carbon Footprint: Using solar energy helps reduce greenhouse gas emissions, contributing to a cleaner, greener planet.\r\nScalability: Whether you start with a single panel or a full solar array, Growatt solutions can be scaled to meet growing energy needs.\r\nApplications of Growatt Solar Panels\r\nResidential Solutions\r\nHomeowners can install Growatt solar panels on rooftops to generate clean energy for daily use. The panels are designed to integrate seamlessly with battery storage systems, providing backup power during outages.\r\n\r\nCommercial and Industrial Use\r\nGrowatt solar panels are a preferred choice for businesses aiming to reduce operational costs and demonstrate environmental responsibility. From warehouses to office buildings, these panels can power diverse facilities efficiently.\r\n\r\nAgricultural Applications\r\nIn rural and agricultural settings, Growatt panels can power irrigation systems, lighting, and equipment, ensuring sustainable and cost-effective energy for farmers.\r\n\r\nOff-Grid Installations\r\nFor remote areas with limited access to electricity, Growatt panels paired with energy storage systems provide a reliable and independent power source.\r\n\r\nWhy Choose Growatt?\r\nReputation for Excellence\r\nGrowatt has received numerous awards and certifications, showcasing its commitment to quality and innovation in renewable energy.\r\n\r\nGlobal Support Network\r\nWith a strong presence in multiple countries, Growatt offers reliable customer support and technical assistance.\r\n\r\nAffordability\r\nGrowatt solar panels provide excellent value for money, making renewable energy accessible to a wider audience.', '674311ada6f38_Growatt-5kw-lithium-ion-solar-kit.jpg', 68, 'published', '2024-11-24 11:44:45', '2025-04-29 03:13:39'),
(4, 2, 6, 'gvgtbvtr', 'gvgtbvtr', 'fervtrgvtyty', 'rtvrvbtr', '675f09185a38d_dn3.png', 0, 'draft', '2024-12-15 16:51:36', '2024-12-15 16:51:36'),
(5, 2, 6, '6t546t56', '6t546t56', 'tgtg45tg', 'rfegrt4t5g', '675f0aae4cc90_dn1.png', 3, 'published', '2024-12-15 16:58:23', '2025-02-04 07:23:33'),
(7, 2, 1, 'frtfgtf', 'frtfgtf', 'edwfwerf', 'erfwerfre', '675f0e11515eb_christmas 3.png', 3, 'published', '2024-12-15 17:12:49', '2025-04-08 10:29:17'),
(8, 2, 1, 'fwerfre', 'fwerfre', 'dfvdv', 'ffcdfv', '675f10dfebaed_freepik__candid-image-photography-natural-textures-highly-r__36415.jpeg', 1, 'draft', '2024-12-15 17:24:48', '2025-04-23 08:19:56'),
(9, 2, 5, 'The titel', 'the-titel', 'yfdsauycfxysdyuc sdhcbsdsc dcsdgicd kdcbsdks hcsjdcskdsa sjdc s sdhisdcse  isidoh cdohsdicds csdsoihcdsdio', 'dcsd ssjkbsdcbdcds djbkcbab.CJBCSD SCJDCSJCKZ csdkcjdcdc bskacdscknlcldn scjcjkcas jskcc scjncdbckjsdc jkbscjksKLB SCJLBLdsc dcsdcnl snajsksds skbsajssd asjxsjsc kaSXBSALBCSNSDHQWIHQE EHEOWSNCDNEI SAndcdcds dcdcdcjkb', '67f4fbc717ef3_solar-hero.png', 1, 'published', '2025-04-08 10:34:47', '2025-04-08 10:34:53');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price_at_time` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `product_id`, `quantity`, `price_at_time`, `created_at`, `updated_at`) VALUES
(12, 19, 9, 1, 40000.00, '2025-04-22 15:12:29', '2025-04-22 15:12:29'),
(25, 44, 6, 2, 945000.00, '2025-04-29 05:08:30', '2025-04-29 05:08:30');

-- --------------------------------------------------------

--
-- Table structure for table `coordinator_signatures`
--

CREATE TABLE `coordinator_signatures` (
  `signature_id` int NOT NULL,
  `coordinator_id` int NOT NULL,
  `signature_image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `coordinator_signatures`
--

INSERT INTO `coordinator_signatures` (`signature_id`, `coordinator_id`, `signature_image`, `created_at`) VALUES
(5, 8, '676afa34617a1_1735064116_signature.png', '2024-12-24 18:15:16');

-- --------------------------------------------------------

--
-- Table structure for table `customerquotation`
--

CREATE TABLE `customerquotation` (
  `quotation_id` int NOT NULL,
  `pre_project_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `package_id` int DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `monthly_consumption` decimal(10,2) NOT NULL,
  `nearest_city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `customizations` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `package_type` enum('premade','custom') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'premade',
  `status` enum('pending','under_review','reviewed','accepted_by_customer','rejected_by_customer','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customerquotation`
--

INSERT INTO `customerquotation` (`quotation_id`, `pre_project_id`, `user_id`, `package_id`, `address`, `monthly_consumption`, `nearest_city`, `customizations`, `created_at`, `package_type`, `status`) VALUES
(3, 1, 4, 7, 'eferfregt', 1000.00, 'shdd', 'customize this package', '2024-12-17 11:39:56', 'premade', 'accepted_by_customer'),
(4, 2, 4, 6, 'sdcsd', 1000.00, 'wdeefer', 'Quotation 1', '2024-12-22 16:58:20', 'premade', 'reviewed'),
(5, 3, 4, 3, 'grtgtrhy', 1000.00, 'matara', 'add another solar, price change', '2025-01-02 05:01:31', 'premade', 'accepted_by_customer'),
(6, 4, 8, 1, 'Pallewaththa, Kamburugamuwa, Matara', -1.00, 'Matara', 'Please consider price diduction', '2025-02-04 07:24:52', 'premade', 'reviewed'),
(7, 5, 19, 1, 'Kamal. Kamburugamuwa ,Matara', 10.00, 'Matara', 'Please re consider the price', '2025-02-04 07:26:57', 'premade', 'accepted_by_customer'),
(8, 6, 20, 3, 'nimal, Kamburugamuwa, Matara', 10.00, 'Matara', 'Please re consider the price', '2025-02-04 07:41:14', 'premade', 'reviewed'),
(9, 7, 21, 1, 'Main road, Kamburugamuwa', 1000.00, 'Matara', 'please consider the price', '2025-02-11 06:30:34', 'premade', 'accepted_by_customer'),
(10, 8, 22, 10, 'Ajith, Kamburugamuwa , Matara', 100.00, 'Matara', 'please change the price', '2025-02-12 03:24:53', 'premade', 'accepted_by_customer'),
(11, 9, 25, 6, 'Thisum, Kamburugamuwa , Matara', 1000.00, 'Matara', 'price should be chaged', '2025-02-22 08:49:58', 'premade', 'accepted_by_customer'),
(12, 10, 19, 3, '67, Kirindiwela', 90.00, 'Kirindiwela', '', '2025-02-27 06:13:10', 'premade', 'accepted_by_customer'),
(16, 11, 19, 6, 'Kamburugamuwa, Matara, Srilanka', 99.00, 'Matara', 'Please reconsider the price', '2025-04-08 14:53:46', 'premade', 'accepted_by_customer'),
(17, 12, 21, 10, 'Kasun, Kamburugamuwa , Matara', 100.00, 'Matara', 'plesase add a differnt price', '2025-04-16 06:08:42', 'premade', 'accepted_by_customer'),
(20, 13, 21, 1, 'Main road, Kamburugamuwa', 100.00, 'Matara', 'This is this months project', '2025-04-19 10:21:12', 'premade', 'accepted_by_customer'),
(21, 14, 21, 3, 'Ajith, Kamburugamuwa , Matara', 100.00, 'Matara', '`10', '2025-04-21 10:58:02', 'premade', 'accepted_by_customer'),
(22, 15, 19, 6, 'No 69', 10.00, 'Kandy', 'hnuynu', '2025-04-22 08:43:00', 'premade', 'accepted_by_customer'),
(23, 16, 21, 10, 'ihh;', 10.00, 'kulk', 'rutiuy', '2025-04-24 07:26:24', 'premade', 'pending'),
(24, 17, 45, 10, 'No 515, Waliweriya Rd, Henegama.', 90.00, 'Waliweriya', 'I want to fit that solar panals to my roof', '2025-04-25 07:37:53', 'premade', 'accepted_by_customer'),
(25, 18, 44, 1, 'njkdcjsnckds', 20.00, 'Kirindiwela', 'jsdsdd', '2025-04-27 05:13:05', 'premade', 'accepted_by_customer'),
(26, 19, 45, 6, 'No . 45 , Kirindiwela Rd. Weliweriya', 100.00, 'Weliweriya', 'I have small roof top', '2025-04-27 08:21:07', 'premade', 'accepted_by_customer'),
(27, 20, 45, 3, 'No . 186 , jangle Rd. Haputale', 2000.00, 'Haputale', '', '2025-04-27 16:44:22', 'premade', 'accepted_by_customer'),
(28, 21, 44, 3, 'Main road, Kamburugamuwa', 90.00, 'Matara', 'This is my first quotation', '2025-04-28 00:11:34', 'premade', 'accepted_by_customer'),
(29, 22, 44, 3, 'Binula, Kamburugamuwa , Matara', 80.00, 'Matara', 'sjdhdsed', '2025-04-28 02:50:23', 'premade', 'accepted_by_customer'),
(30, 23, 44, 3, 'Binula, Kamburugamuwa , Matara', 82.00, 'Matara', 'udhweiewd', '2025-04-28 04:08:41', 'premade', 'accepted_by_customer'),
(31, 24, 44, 1, 'Binula, Kamburugamuwa , Matara', 83.00, 'Matara', 'edwede', '2025-04-28 05:43:40', 'premade', 'accepted_by_customer');

--
-- Triggers `customerquotation`
--
DELIMITER $$
CREATE TRIGGER `trg_customerquotation_before_insert` BEFORE INSERT ON `customerquotation` FOR EACH ROW BEGIN
    DECLARE max_id INT;
    
    -- Find the current maximum pre_project_id
    SELECT COALESCE(MAX(pre_project_id), 10) INTO max_id 
    FROM customerquotation;
    
    -- Set the new pre_project_id to max + 1
    IF NEW.pre_project_id IS NULL OR NEW.pre_project_id = 0 THEN
        SET NEW.pre_project_id = max_id + 1;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `custompackage`
--

CREATE TABLE `custompackage` (
  `custom_package_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `base_package_id` int DEFAULT NULL,
  `customizations` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estimated_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('on-grid','off-grid','hybrid') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documentSubmission`
--

CREATE TABLE `documentSubmission` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `document` varchar(255) NOT NULL,
  `status` enum('pending','accept','reject') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rejection_reason` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `documentSubmission`
--

INSERT INTO `documentSubmission` (`id`, `project_id`, `document`, `status`, `created_at`, `updated_at`, `rejection_reason`) VALUES
(1, 9, '67f8d3c643859_download.jpeg', 'accept', '2025-04-11 08:33:11', '2025-04-11 13:12:06', NULL),
(2, 7, '67f90d46575ea_WhatsApp Image 2025-02-08 at 18.52.39_60ba756a.jpg', 'accept', '2025-04-11 10:56:06', '2025-04-11 13:14:53', NULL),
(3, 5, '67ff49a076e1f_happy new year.jpeg', 'accept', '2025-04-16 06:09:31', '2025-04-16 06:13:47', NULL),
(4, 10, '68071a9fede53_happy new year.jpeg', 'accept', '2025-04-22 04:27:10', '2025-04-22 04:28:29', NULL),
(5, 11, '68075fa3d6c88_create-blog.png', 'accept', '2025-04-22 09:21:38', '2025-04-22 09:24:06', NULL),
(6, 12, '6809ef88287c6_delivery_report_ORD202504225398.pdf', 'accept', '2025-04-24 08:00:08', '2025-04-24 08:01:55', NULL),
(10, 13, '680cc521bfe68_CEB – Ceylon Electricity Board.pdf', 'accept', '2025-04-26 17:02:58', '2025-04-26 11:36:23', NULL),
(11, 14, '680e007b8fec3_Quotation_QT00004.pdf', 'accept', '2025-04-27 10:01:31', '2025-04-27 10:09:18', NULL),
(12, 16, '680f021fe23ba_Quotation_QT00028 (1).pdf', 'accept', '2025-04-28 09:50:48', '2025-04-28 04:25:06', NULL),
(13, 15, '680fc69d817d3_CEB – Ceylon Electricity Board.pdf', 'accept', '2025-04-28 23:49:09', '2025-04-28 18:20:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int NOT NULL,
  `user_id` int NOT NULL,
  `role` enum('technician','deliveryPerson','engineer','clerk') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `user_id`, `role`, `address`, `created_at`, `updated_at`) VALUES
(4, 12, 'technician', '94, Galle Road, Walana, Panadura', '2024-12-16 17:44:00', '2025-04-26 07:19:44'),
(5, 13, 'technician', '267, Temple Road, Padukka', '2024-12-16 17:48:11', '2025-04-27 01:18:19'),
(6, 14, 'deliveryPerson', '', '2024-12-17 06:12:14', '2024-12-17 06:12:14'),
(7, 15, 'engineer', '', '2025-01-02 05:05:07', '2025-01-02 05:05:07'),
(8, 16, 'technician', '', '2025-01-02 05:28:57', '2025-01-02 05:28:57'),
(9, 17, 'engineer', '', '2025-01-17 10:00:59', '2025-01-17 10:00:59'),
(10, 18, 'technician', '', '2025-01-17 10:01:51', '2025-01-17 10:01:51'),
(11, 23, 'technician', '', '2025-02-15 10:21:36', '2025-02-15 10:21:36'),
(12, 24, 'technician', '', '2025-02-15 14:06:36', '2025-02-15 14:06:36'),
(13, 28, 'clerk', '', '2025-02-26 18:41:31', '2025-02-26 18:41:31'),
(14, 34, 'technician', '', '2025-04-22 09:46:51', '2025-04-22 09:46:51'),
(15, 35, 'technician', '', '2025-04-23 08:34:42', '2025-04-23 08:34:42'),
(16, 36, 'technician', '', '2025-04-23 08:40:05', '2025-04-23 08:40:05'),
(17, 37, 'deliveryPerson', '', '2025-04-23 08:50:06', '2025-04-23 08:50:06'),
(18, 38, 'deliveryPerson', '', '2025-04-23 09:07:05', '2025-04-23 09:07:05'),
(20, 41, 'deliveryPerson', 'wkjncfiwue', '2025-04-23 17:01:18', '2025-04-23 17:01:18'),
(21, 42, 'deliveryPerson', 'bjhbuuyu', '2025-04-23 17:33:32', '2025-04-23 20:31:09'),
(22, 43, 'technician', 'dnkj', '2025-04-23 18:56:02', '2025-04-23 18:56:02'),
(23, 46, 'deliveryPerson', '314, Temple Road, Dalugama, Kelaniya', '2025-04-25 22:02:11', '2025-04-25 22:02:11');

-- --------------------------------------------------------

--
-- Table structure for table `engineerapproval`
--

CREATE TABLE `engineerapproval` (
  `approval_id` int NOT NULL,
  `project_id` int NOT NULL,
  `status` enum('pending','scheduled','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `feedback_type` enum('general','suggestion','complaint','compliment','inquiry') NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `rating` int DEFAULT NULL,
  `status` enum('new','in_progress','resolved','closed') NOT NULL DEFAULT 'new',
  `admin_notes` text,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `feedback_type`, `subject`, `message`, `rating`, `status`, `admin_notes`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 'Ajith', 'ajith@gmail.com', 'general', 'hi there', 'adedeffref efnkjf heckjbfr jdjkdbc hddjdk xd edjksebjwe eudwedeu', 3, 'new', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-16 14:40:04', NULL),
(3, 'Ajith', 'habdthilakasiri2002@gmail.com', 'suggestion', 'ane bn', 'ane bn mokakda karanne', 5, 'in_progress', '\n\n[2025-04-19 19:32:47] Email response sent:\nDear Ajith,\r\n\r\nThank you for your feedback regarding \"ane bn\".\r\n\r\n[Your response here]\r\n\r\nBest regards,\r\nThe SimplEx Solar Team', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 19:32:15', '2025-04-19 17:32:47'),
(4, 'kasun', 'kasun@gmail.com', 'general', 'ane bn', 'ewdweded', 3, 'closed', '', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 19:38:50', '2025-04-20 15:37:46'),
(5, 'xscsd', 'dcsdcs@sdcds.com', 'suggestion', 'sdcsdc', 'asdxede', 3, 'new', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 19:43:30', NULL),
(6, 'QDWDED', 'EWDREF@assd.com', 'general', 'eefer', 'ederf', 3, 'new', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 19:45:15', NULL),
(8, 'Ajith', 'habdthilakasiri2002@gmail.com', 'complaint', 'ane bn', 'ewwdedr', 3, 'in_progress', '\n\n[2025-04-22 06:09:32] Email response sent:\nDear Ajith,\r\n\r\n                                    Thank you for your feedback regarding \"ane bn\".\r\n\r\n                                    [Y]cerwrvrtfr\r\n                                    Best regards,\r\n                                    The SimplEx Solar Team', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-22 06:07:51', '2025-04-22 04:09:30'),
(9, 'Binula', 'habdthilakasiri2002@gmail.com', 'suggestion', 'edhkw', 'edcaerfe', 4, 'new', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-24 11:43:38', NULL),
(10, 'Ajith', 'kasun@gmail.com', 'general', 'today', 'hisxidu', 3, 'new', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-29 00:22:58', NULL),
(11, 'Binula DIMANTHA', 'ajith@gmail.com', 'suggestion', 'TODAY PRESENTATION', 'DCFREFERFRE', 3, 'new', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-29 08:58:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `installation`
--

CREATE TABLE `installation` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `schedule_status` enum('pending','approved','requested') DEFAULT 'pending',
  `request_reason` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('initial','active','completed') DEFAULT 'initial',
  `completed_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `installation`
--

INSERT INTO `installation` (`id`, `project_id`, `start_date`, `end_date`, `schedule_status`, `request_reason`, `created_at`, `updated_at`, `status`, `completed_date`) VALUES
(1, 10, '2025-04-25', '2025-04-30', 'approved', NULL, '2025-04-24 10:38:33', '2025-04-25 20:00:37', 'completed', '2025-04-26'),
(2, 12, '2025-04-25', '2025-04-29', 'approved', 'can you give another date to me?', '2025-04-24 17:23:34', '2025-04-28 17:27:23', 'active', '2025-04-28'),
(3, 13, '2025-04-27', '2025-04-29', 'approved', NULL, '2025-04-26 21:23:54', '2025-04-26 21:27:46', 'completed', '2025-04-27'),
(4, 14, '2025-04-27', '2025-04-30', 'pending', NULL, '2025-04-27 10:40:43', '2025-04-28 17:53:37', 'initial', NULL),
(5, 16, '2025-04-28', '2025-04-28', 'approved', NULL, '2025-04-28 04:34:45', '2025-04-29 03:43:07', 'completed', '2025-04-29');

-- --------------------------------------------------------

--
-- Table structure for table `installation_phase`
--

CREATE TABLE `installation_phase` (
  `installation_id` int NOT NULL,
  `project_id` int NOT NULL,
  `engineer_id` int DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `schedule_status` enum('pending','accept','request') DEFAULT NULL,
  `reschedule_request` text,
  `status` enum('initial','active','completed') DEFAULT 'initial',
  `step_1` tinyint(1) DEFAULT '0',
  `step_1_date` datetime DEFAULT NULL,
  `step_2` tinyint(1) DEFAULT '0',
  `step_2_date` datetime DEFAULT NULL,
  `step_3` tinyint(1) DEFAULT '0',
  `step_3_date` datetime DEFAULT NULL,
  `step_4` tinyint(1) DEFAULT '0',
  `step_4_date` datetime DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `installation_phase`
--

INSERT INTO `installation_phase` (`installation_id`, `project_id`, `engineer_id`, `start_date`, `start_time`, `end_date`, `schedule_status`, `reschedule_request`, `status`, `step_1`, `step_1_date`, `step_2`, `step_2_date`, `step_3`, `step_3_date`, `step_4`, `step_4_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 12, NULL, NULL, NULL, NULL, NULL, NULL, 'initial', 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, '2025-04-24 17:20:50', '2025-04-24 17:20:50'),
(2, 13, NULL, NULL, NULL, NULL, NULL, NULL, 'initial', 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, '2025-04-26 21:20:12', '2025-04-26 21:20:12'),
(3, 14, NULL, NULL, NULL, NULL, NULL, NULL, 'initial', 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, '2025-04-27 10:37:00', '2025-04-27 10:37:00'),
(4, 16, NULL, NULL, NULL, NULL, NULL, NULL, 'initial', 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, '2025-04-28 04:32:26', '2025-04-28 04:32:26');

-- --------------------------------------------------------

--
-- Table structure for table `installation_schedule`
--

CREATE TABLE `installation_schedule` (
  `id` int NOT NULL,
  `installation_id` int NOT NULL,
  `start_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_date` date NOT NULL,
  `duration_days` int DEFAULT '1',
  `status` enum('pending','accept','request') DEFAULT 'pending',
  `reschedule_request` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `installation_team`
--

CREATE TABLE `installation_team` (
  `id` int NOT NULL,
  `installation_id` int NOT NULL,
  `employee_id` int NOT NULL,
  `role` enum('technician','engineer') DEFAULT 'technician',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `blog_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `supplier_id` int NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `name`, `price`, `quantity`, `description`, `blog_link`, `supplier_id`, `image`, `created_at`, `deleted_at`) VALUES
(1, 'solar panles', 100.00, 0, 'Grawatt solar panels 400W', 'http://localhost/simplex/blog/showPost/6t546t56', 1, '', '2024-11-26 17:58:59', NULL),
(2, 'inverter', 100000.00, 0, 'Solex inverter 3kw', 'http://localhost/simplex/blog/showPost/6t546t56', 3, '', '2024-11-26 18:04:49', NULL),
(3, 'Battey', 500000.00, 4, 'Genso Battery', 'http://localhost/simplex/blog/showPost/6t546t56', 1, '', '2024-12-24 18:01:50', NULL),
(4, 'solar panel', 200000.00, 5, 'panel', 'http://localhost/simplex/supplierCoordinator/addProduct', 3, '677627bae3561_PesanigeAluthmaThuna.png', '2025-01-02 05:44:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leaverecords`
--

CREATE TABLE `leaverecords` (
  `id` int NOT NULL,
  `leave_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `number_of_days` int DEFAULT NULL,
  `reason` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('Approved','Pending','Not Approved') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `employee_id` int NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leaverecords`
--

INSERT INTO `leaverecords` (`id`, `leave_type`, `start_date`, `end_date`, `number_of_days`, `reason`, `status`, `created_at`, `updated_at`, `employee_id`, `comment`) VALUES
(1, 'Annual Leave', '2024-11-25', '2024-11-28', 4, 'Family Vacation', 'Approved', '2024-11-20 03:30:00', '2024-12-17 12:41:21', 6, NULL),
(2, 'Sick Leave', '2024-11-26', '2024-11-26', 1, 'Fever and Headache', 'Not Approved', '2024-11-24 05:15:00', '2025-02-26 16:41:18', 4, NULL),
(3, 'Casual Leave', '2024-11-30', '2024-12-02', 3, 'Personal Work', 'Not Approved', '2024-11-22 02:45:00', '2024-12-17 12:42:14', 5, NULL),
(4, 'Annual Leave', '2024-11-25', '2024-11-27', 3, 'Family Vacation', 'Approved', '2024-11-23 03:30:00', '2024-12-17 12:42:15', 6, NULL),
(5, 'Sick Leave', '2024-12-19', '2024-12-26', 8, 'fever', 'Approved', '2024-12-18 11:23:51', '2025-01-02 05:25:31', 6, NULL),
(13, 'casualLeave', '2024-12-29', '2024-12-31', 3, 'headache', 'Not Approved', '2024-12-28 18:46:53', '2025-02-26 17:02:27', 6, NULL),
(33, 'Casual Leave', '2025-01-02', '2025-01-04', 3, 'Family Celebration', 'Not Approved', '2025-01-02 05:09:46', '2025-01-17 08:54:35', 6, NULL),
(34, 'Sick Leave', '2025-01-02', '2025-01-02', 1, 'fever', 'Approved', '2025-01-02 05:41:04', '2025-04-23 11:15:07', 6, 'hjjh'),
(35, 'Sick Leave', '2025-01-02', '2025-01-09', 8, 'fever', 'Approved', '2025-01-02 15:14:04', '2025-04-22 17:41:31', 6, NULL),
(36, 'Sick Leave', '2025-01-02', '2025-01-09', 8, 'fever', 'Approved', '2025-01-02 15:14:15', '2025-02-27 09:46:10', 6, NULL),
(37, 'Casual Leave', '2025-01-10', '2025-01-10', 1, 'birthday', 'Pending', '2025-01-02 15:14:37', '2025-01-02 15:14:37', 6, NULL),
(38, 'Other', '2025-01-03', '2025-01-15', 13, 'celebration', 'Pending', '2025-01-02 15:15:01', '2025-01-02 15:15:01', 6, NULL),
(39, 'Annual Leave', '2025-01-16', '2025-01-23', 8, 'annual leave', 'Pending', '2025-01-02 15:15:30', '2025-01-02 15:15:30', 6, NULL),
(40, 'Other', '2025-01-25', '2025-01-25', 1, 'stomachache', 'Pending', '2025-01-02 15:16:22', '2025-01-02 15:16:22', 6, NULL),
(52, 'Sick Leave', '2025-01-02', '2025-01-02', 1, 'nm', 'Approved', '2025-01-04 13:58:06', '2025-02-26 17:23:15', 6, NULL),
(53, 'Sick Leave', '2025-01-04', '2025-01-06', 3, 'zces', 'Pending', '2025-01-04 15:08:10', '2025-01-04 15:08:10', 6, NULL),
(54, 'Sick Leave', '2025-01-04', '2025-01-17', 14, 'nbnb', 'Pending', '2025-01-04 15:25:03', '2025-01-04 15:25:03', 6, NULL),
(55, 'Casual Leave', '2025-01-17', '2025-01-18', 2, 'birthday', 'Pending', '2025-01-17 16:43:44', '2025-01-17 16:43:44', 9, NULL),
(56, 'Annual Leave', '2025-01-18', '2025-01-18', 1, 'csc', 'Pending', '2025-01-17 16:44:37', '2025-01-17 16:44:37', 9, NULL),
(57, 'Casual Leave', '2025-01-18', '2025-01-20', 3, 'ghjg', 'Pending', '2025-01-18 03:36:13', '2025-01-18 03:36:13', 9, NULL),
(58, 'Annual Leave', '2025-01-27', '2025-01-30', 4, 'bh', 'Pending', '2025-01-18 03:36:31', '2025-01-18 03:36:31', 9, NULL),
(59, 'Casual Leave', '2025-01-20', '2025-01-21', 2, 'cf', 'Not Approved', '2025-01-18 03:45:54', '2025-02-27 06:44:33', 9, NULL),
(60, 'Casual Leave', '2025-01-25', '2025-01-29', 5, 'nh', 'Pending', '2025-01-18 03:46:11', '2025-01-18 03:46:11', 9, NULL),
(61, 'Sick Leave', '2025-01-23', '2025-01-24', 2, 'v', 'Pending', '2025-01-22 05:22:26', '2025-01-22 05:22:26', 10, NULL),
(62, 'Sick Leave', '2025-01-23', '2025-01-24', 2, 'v', 'Pending', '2025-01-22 05:22:27', '2025-01-22 05:22:27', 10, NULL),
(63, 'Annual Leave', '2025-01-22', '2025-01-31', 10, 'qsq', 'Pending', '2025-01-22 05:23:45', '2025-01-22 05:23:45', 9, NULL),
(64, 'Sick Leave', '2025-02-04', '2025-02-06', 3, 'dw', 'Approved', '2025-02-04 09:48:22', '2025-04-26 05:59:31', 10, NULL),
(65, 'Sick Leave', '2025-02-26', '2025-02-27', 2, 'dd', 'Pending', '2025-02-25 13:37:58', '2025-02-26 09:19:17', 4, 'i cannot assign you the holiday'),
(66, 'Sick Leave', '2025-04-23', '2025-04-24', 2, 'fever', 'Pending', '2025-04-22 17:24:28', '2025-04-22 17:24:28', 9, NULL),
(67, 'Sick Leave', '2025-04-25', '2025-04-26', 2, 'svs', 'Pending', '2025-04-22 17:33:25', '2025-04-22 17:33:25', 9, NULL),
(68, 'Sick Leave', '2025-04-25', '2025-05-03', 9, 'bb', 'Pending', '2025-04-22 18:43:11', '2025-04-22 18:43:11', 9, NULL),
(69, 'Casual Leave', '2025-04-23', '2025-04-25', 3, '1234', 'Pending', '2025-04-22 18:47:13', '2025-04-22 18:47:13', 9, NULL),
(70, 'Casual Leave', '2025-04-30', '2025-05-01', 2, 'leave', 'Pending', '2025-04-22 18:47:20', '2025-04-22 18:47:20', 9, NULL),
(71, 'Sick Leave', '2025-04-23', '2025-04-24', 2, 'sick', 'Pending', '2025-04-22 20:23:50', '2025-04-22 20:23:50', 4, NULL),
(72, 'Casual Leave', '2025-04-23', '2025-04-24', 2, 'testing leave', 'Pending', '2025-04-22 21:36:33', '2025-04-22 21:36:33', 9, NULL),
(73, 'Sick Leave', '2025-05-01', '2025-05-03', 3, 'suffer with fever', 'Approved', '2025-04-23 11:00:52', '2025-04-26 22:19:56', 6, NULL),
(74, 'Annual Leave', '2025-04-24', '2025-04-25', 2, 'ccc', 'Approved', '2025-04-23 11:24:31', '2025-04-25 04:16:07', 10, 'this leave can be given'),
(75, 'Sick Leave', '2025-04-25', '2025-04-25', 1, 'scs', 'Pending', '2025-04-25 05:50:44', '2025-04-25 05:50:44', 10, NULL),
(76, 'Sick Leave', '2025-04-25', '2025-04-25', 1, 'scs', 'Pending', '2025-04-25 05:50:46', '2025-04-25 05:50:46', 10, NULL),
(77, 'Casual Leave', '2025-04-26', '2025-04-29', 4, 'reason', 'Pending', '2025-04-25 22:38:15', '2025-04-25 22:38:15', 9, NULL),
(78, 'Casual Leave', '2025-04-28', '2025-04-29', 2, 'reason 2', 'Approved', '2025-04-25 22:39:54', '2025-04-28 22:41:05', 9, NULL),
(79, 'Casual Leave', '2025-04-26', '2025-04-30', 5, 'dddddddv', 'Pending', '2025-04-25 22:40:27', '2025-04-25 22:40:27', 9, NULL),
(80, 'Sick Leave', '2025-04-26', '2025-05-01', 6, 'hh', 'Pending', '2025-04-25 22:41:12', '2025-04-25 22:41:12', 9, NULL),
(81, 'Casual Leave', '2025-04-26', '2025-04-26', 1, 'casual leave', 'Not Approved', '2025-04-26 19:16:31', '2025-04-26 20:05:15', 6, NULL),
(82, 'Sick Leave', '2025-04-27', '2025-04-28', 2, 'feel not well', 'Pending', '2025-04-26 19:17:07', '2025-04-26 19:17:07', 6, NULL),
(83, 'Other', '2025-04-29', '2025-04-30', 2, 'industrial occasion', 'Pending', '2025-04-26 19:18:38', '2025-04-26 19:18:38', 6, NULL),
(84, 'Other', '2025-04-30', '2025-05-01', 2, 'general meeting', 'Pending', '2025-04-26 20:28:58', '2025-04-26 20:28:58', 6, NULL),
(85, 'Casual Leave', '2025-04-29', '2025-04-30', 2, 'celebration', 'Pending', '2025-04-29 04:37:25', '2025-04-29 04:39:48', 13, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `sender_id` int NOT NULL,
  `sender_role` varchar(50) NOT NULL,
  `receiver_id` int NOT NULL,
  `receiver_role` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `sender_role`, `receiver_id`, `receiver_role`, `message`, `timestamp`, `is_read`) VALUES
(4, 21, 'customer', 10, 'supplierCoordinator', 'Hi, I\'m testing', '2025-04-25 13:38:48', 1),
(13, 21, 'customer', 8, 'operationsCoordinator', 'i want to know about projects', '2025-04-26 23:04:30', 1),
(14, 44, 'customer', 8, 'operationsCoordinator', 'hi', '2025-04-29 03:16:33', 0),
(15, 44, 'customer', 8, 'operationsCoordinator', 'hi', '2025-04-29 03:16:38', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `order_number` varchar(20) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text NOT NULL,
  `contact_phone` varchar(20) NOT NULL,
  `payment_method` enum('bank_deposit','cash','online') NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deliver_id` int DEFAULT NULL,
  `delivered_at` datetime DEFAULT NULL,
  `delivery_report` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `total_amount`, `shipping_address`, `contact_phone`, `payment_method`, `status`, `created_at`, `updated_at`, `deliver_id`, `delivered_at`, `delivery_report`) VALUES
(1, 19, 'ORD202504218320', 1930000.00, 'No 456, Rathnapura Rd, Kuruwita', '0774549646', 'cash', 'delivered', '2025-04-21 10:16:16', '2025-04-23 09:48:14', 6, '2025-04-23 08:44:47', 'delivery_report_ORD202504218320_1745397886.pdf'),
(2, 19, 'ORD202504217368', 69000.00, 'No 45, 5th Lane, Kadawath', '0776767676', 'bank_deposit', 'pending', '2025-04-21 11:23:39', '2025-04-21 14:46:49', NULL, NULL, NULL),
(3, 19, 'ORD202504219519', 40000.00, 'No 56, Colombo', '0776767676', 'bank_deposit', 'processing', '2025-04-21 16:57:00', '2025-04-22 06:53:38', NULL, NULL, NULL),
(4, 19, 'ORD202504225331', 34500.00, 'Mahena, Mandawala.', '0745856234', 'cash', 'processing', '2025-04-22 00:21:07', '2025-04-22 00:21:16', NULL, NULL, NULL),
(5, 21, 'ORD202504222223', 955000.00, 'ederdr3d', '071hgjguj', 'cash', 'processing', '2025-04-22 04:55:50', '2025-04-22 04:56:08', NULL, NULL, NULL),
(6, 21, 'ORD202504225398', 40000.00, 'ederder', 'eederfdre', 'bank_deposit', 'shipped', '2025-04-22 05:01:06', '2025-04-23 06:15:46', 6, '2025-04-23 06:15:46', 'delivery_report_ORD202504225398_1745388945.pdf'),
(7, 19, 'ORD202504222637', 80000.00, 'eiuiniuj', '0776767676', 'bank_deposit', 'shipped', '2025-04-22 08:17:59', '2025-04-25 04:45:14', 6, '2025-04-25 04:45:14', 'delivery_report_ORD202504222637_1745556315.pdf'),
(8, 19, 'ORD202504229720', 1590000.00, 'iygiuhiupo', 'iyguihui', 'bank_deposit', 'pending', '2025-04-22 15:05:17', '2025-04-22 15:05:17', NULL, NULL, NULL),
(9, 21, 'ORD202504247794', 74500.00, 'no 45, samanala mawatha, nugegoda.', '0774549646', 'bank_deposit', 'shipped', '2025-04-24 18:27:49', '2025-04-24 18:31:17', 17, NULL, NULL),
(11, 45, 'ORD202504274628', 1761750.00, 'galle,matara', '01233444444', 'bank_deposit', 'delivered', '2025-04-27 06:10:38', '2025-04-27 06:50:19', 6, '2025-04-27 06:47:09', 'delivery_report_ORD202504274628_1745736428.pdf'),
(12, 45, 'ORD202504274173', 1288500.00, 'saaaaaaaaaa', '01233444444', 'cash', 'shipped', '2025-04-27 06:58:25', '2025-04-27 07:09:39', 6, '2025-04-27 07:09:39', 'delivery_report_ORD202504274173_1745737779.pdf'),
(13, 44, 'ORD202504281015', 1911750.00, 'eghsjegwhede', '071663778', 'bank_deposit', 'pending', '2025-04-28 06:36:51', '2025-04-28 06:36:51', NULL, NULL, NULL),
(14, 45, 'ORD202504285794', 171750.00, 'ini7', '4874jhbh98', 'bank_deposit', 'shipped', '2025-04-28 07:35:21', '2025-04-28 07:49:20', 6, '2025-04-28 07:49:20', 'delivery_report_ORD202504285794_1745826560.pdf'),
(15, 44, 'ORD202504291536', 1890000.00, 'kirindiwela', '076566577899', 'bank_deposit', 'pending', '2025-04-29 03:01:25', '2025-04-29 03:01:25', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price_at_time` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price_at_time`, `created_at`) VALUES
(1, 1, 6, 2, 945000.00, '2025-04-21 10:16:16'),
(2, 1, 9, 1, 40000.00, '2025-04-21 10:16:16'),
(3, 2, 3, 2, 34500.00, '2025-04-21 11:23:39'),
(4, 3, 9, 1, 40000.00, '2025-04-21 16:57:00'),
(5, 4, 3, 1, 34500.00, '2025-04-22 00:21:07'),
(6, 5, 8, 1, 795000.00, '2025-04-22 04:55:50'),
(7, 5, 9, 4, 40000.00, '2025-04-22 04:55:50'),
(8, 6, 9, 1, 40000.00, '2025-04-22 05:01:06'),
(9, 7, 9, 2, 40000.00, '2025-04-22 08:17:59'),
(10, 8, 8, 2, 795000.00, '2025-04-22 15:05:17'),
(11, 9, 3, 1, 34500.00, '2025-04-24 18:27:49'),
(12, 9, 9, 1, 40000.00, '2025-04-24 18:27:49'),
(13, 11, 5, 1, 171750.00, '2025-04-27 06:10:38'),
(14, 11, 8, 2, 795000.00, '2025-04-27 06:10:39'),
(15, 12, 5, 2, 171750.00, '2025-04-27 06:58:26'),
(16, 12, 6, 1, 945000.00, '2025-04-27 06:58:26'),
(17, 13, 5, 1, 171750.00, '2025-04-28 06:36:51'),
(18, 13, 6, 1, 945000.00, '2025-04-28 06:36:51'),
(19, 13, 8, 1, 795000.00, '2025-04-28 06:36:51'),
(20, 14, 5, 1, 171750.00, '2025-04-28 07:35:21'),
(21, 15, 6, 2, 945000.00, '2025-04-29 03:01:25');

-- --------------------------------------------------------

--
-- Table structure for table `otps`
--

CREATE TABLE `otps` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `expiry_time` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `otps`
--

INSERT INTO `otps` (`id`, `email`, `otp`, `expiry_time`, `created_at`) VALUES
(8, 'pasansanjiwa2023@gmail.com', '201158', '2025-04-07 17:52:16', '2025-04-07 15:37:16'),
(12, 'habdthialaksiri2002@gmail.com', '678670', '2025-04-24 07:07:14', '2025-04-24 04:50:22'),
(13, 'habdthilaksiri2002@gmail.com', '765521', '2025-04-24 07:07:43', '2025-04-24 04:52:43'),
(15, 'peshani@gmail.com', '722633', '2025-04-24 19:30:34', '2025-04-24 13:45:34');

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `package_id` int NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `price` decimal(10,2) NOT NULL,
  `warranty_years` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('on-grid','off-grid','hybrid') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `service_charge` decimal(10,2) DEFAULT '0.00',
  `final_price` decimal(10,2) DEFAULT '0.00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package`
--

INSERT INTO `package` (`package_id`, `title`, `slug`, `description`, `price`, `warranty_years`, `created_at`, `type`, `image`, `service_charge`, `final_price`, `updated_at`, `deleted_at`) VALUES
(1, '10 kW Home Solar Solution', '1kw', 'Our 10 kW solar package is a high-performance system designed for large households seeking energy independence and cost savings. This package includes premium monocrystalline solar panels, a hybrid inverter, and a durable rooftop mounting system to maximize energy generation. With an optional battery backup, it ensures uninterrupted power during outages. Smart monitoring features allow real-time energy tracking, and net metering support enables users to export excess electricity to the grid. Built for durability, this system withstands extreme weather conditions, providing a reliable and sustainable energy solution for years to come.', 1100000.00, 10, '2024-11-26 19:12:56', 'hybrid', 'uploads/packages/package_67461db81e9a8.jpeg', 500000.00, 1600000.00, '2025-02-04 06:41:19', NULL),
(3, '3 kW Home Solar Solution', 'wefwf', 'A budget-friendly solar solution ideal for small homes or apartments, helping to reduce electricity bills while promoting clean energy.', 600300.00, 8, '2024-11-27 15:59:36', 'off-grid', 'uploads/packages/package_674741e8d3814.jpeg', 10000.00, 610300.00, '2025-02-04 06:37:42', NULL),
(4, '3kw', '3kw', 'dwedew', 101000.00, 1, '2024-11-27 16:00:12', 'on-grid', 'uploads/packages/package_6747420ce952b.jpg', 10000.00, 111000.00, '2024-12-06 16:25:02', '2024-11-27 16:36:56'),
(5, 'ewe', 'ewe', 'wewqeq', 100100.00, 7, '2024-11-27 16:25:55', 'hybrid', 'uploads/packages/package_6747481397795.jpg', 10000.00, 110100.00, '2024-12-06 16:25:03', '2024-11-27 16:26:15'),
(6, '5 kW Home Solar Solution', 'sdadescsdfvfs', 'A reliable and cost-effective solar energy solution designed for residential use. This package provides sustainable electricity to power household appliances, reduce dependency on the grid, and lower electricity costs.', 100100.00, 10, '2024-11-28 07:12:02', 'on-grid', 'uploads/packages/package_674817c205d86.jpg', 10000.00, 110100.00, '2025-02-04 06:33:07', NULL),
(7, 'ewdwe', 'ewdwe', 'wedwe', 4100100.00, 1, '2024-11-28 07:32:03', 'on-grid', 'uploads/packages/package_67481c73c643a.jpg', 1.00, 4100101.00, '2025-02-04 06:35:24', '2025-02-04 06:35:24'),
(8, '30kW solar package', '30kw-solar-package', 'Our 30kW solar package is a high-capacity system designed for businesses, large homes, and commercial establishments seeking reliable and cost-effective solar energy. This package includes premium solar panels, an efficient inverter, and a durable mounting system to ensure maximum energy generation. It can significantly reduce electricity bills while promoting sustainability. Customization options are available to meet specific energy needs.', 1101000.00, 10, '2025-01-29 13:08:02', 'on-grid', 'uploads/packages/package_679a283470e82.jpg', 100000.00, 1201000.00, '2025-02-04 06:43:40', NULL),
(9, '50 kW Industrial Solar Solution', '50-kw-industrial-solar-solution', 'Our 50 kW Industrial Solar Package is a premium, high-capacity solar power system designed to meet the energy demands of factories, warehouses, and large commercial establishments. This solution provides efficient, cost-effective, and sustainable energy while significantly reducing electricity expenses. With advanced technology, durable components, and smart monitoring, this package ensures uninterrupted power supply and long-term savings.', 0.00, 20, '2025-02-04 06:52:15', 'on-grid', NULL, 1000000.00, 1000000.00, '2025-02-04 06:53:22', '2025-02-04 06:53:22'),
(10, '50 kW Industrial Solar Package', 'defer', 'Our 50 kW Industrial Solar Package is a premium, high-capacity solar power system designed to meet the energy demands of factories, warehouses, and large commercial establishments. This solution provides efficient, cost-effective, and sustainable energy while significantly reducing electricity expenses. With advanced technology, durable components, and smart monitoring, this package ensures uninterrupted power supply and long-term savings.', 1500000.00, 10, '2025-02-04 06:53:55', 'on-grid', 'uploads/packages/package_67a1b983994df.jpg', 1000000.00, 2500000.00, '2025-02-04 06:56:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `packageequipment`
--

CREATE TABLE `packageequipment` (
  `equipment_id` int NOT NULL,
  `package_id` int DEFAULT NULL,
  `item_id` int NOT NULL,
  `quantity` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packageequipment`
--

INSERT INTO `packageequipment` (`equipment_id`, `package_id`, `item_id`, `quantity`) VALUES
(29, 6, 1, 1),
(30, 6, 2, 1),
(31, 3, 1, 3),
(32, 3, 2, 1),
(33, 3, 3, 1),
(34, 1, 3, 2),
(35, 1, 2, 1),
(36, 8, 1, 10),
(37, 8, 2, 1),
(38, 8, 3, 2),
(45, 10, 2, 5),
(46, 10, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `packagefeature`
--

CREATE TABLE `packagefeature` (
  `feature_id` int NOT NULL,
  `package_id` int DEFAULT NULL,
  `feature_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packagefeature`
--

INSERT INTO `packagefeature` (`feature_id`, `package_id`, `feature_name`, `description`) VALUES
(28, 6, 'High Efficiency', 'Utilizes premium monocrystalline panels for maximum energy conversion.'),
(29, 6, 'Net Metering Support', 'Connects with the grid to export excess energy and earn credits.'),
(30, 6, 'Remote Monitoring', 'Smart app integration for real-time energy tracking.'),
(31, 6, 'Weather Resistance', 'Option to expand the system by adding more panels or battery storage.'),
(32, 3, 'Efficient Energy Production', 'High-performance solar panels for optimal output.'),
(33, 3, 'Grid-Connected System', 'Reduces reliance on utility power.'),
(34, 3, 'Compact Design', 'Suitable for homes with limited roof space.'),
(35, 3, 'Eco-Friendly', 'Helps reduce carbon footprint.'),
(36, 3, 'Battery backup', 'Provide energy even for night'),
(37, 1, 'High Power Output', 'Supports high electricity consumption.'),
(38, 1, 'Battery Backup Option', 'Ensures power during outages.'),
(39, 1, 'Smart Monitoring', 'Real-time tracking via mobile app.'),
(40, 1, 'Real-time tracking via mobile app.', 'Export excess energy to the grid.'),
(41, 1, 'Durable & Reliable', 'Withstands extreme weather conditions.'),
(42, 8, 'High-Efficiency Solar Panels', 'Premium-grade panels ensure optimal power output and long-term performance.'),
(43, 8, 'Advanced Inverter System', 'Converts solar energy into usable electricity with high efficiency and stability.'),
(44, 8, 'Robust Mounting Structure', 'Durable and weather-resistant frames for secure installation'),
(45, 8, 'Battery Backup', 'Store excess energy for use during power outages or peak hours.'),
(46, 8, 'Net Metering Compatibility', 'Sell excess energy back to the grid and reduce electricity costs.'),
(58, 10, 'High Energy Production', 'Supplies power for heavy machinery.'),
(59, 10, 'Grid Integration', 'Exports excess energy to the power grid.'),
(60, 10, 'Low Maintenance', 'Designed for long-term durability.'),
(61, 10, 'Sustainability', 'Reduces carbon footprint significantly.'),
(62, 10, 'Customizable', 'Tailored solutions for specific industrial needs.');

-- --------------------------------------------------------

--
-- Table structure for table `paymentphase`
--

CREATE TABLE `paymentphase` (
  `phase_id` int NOT NULL,
  `project_id` int NOT NULL,
  `phase_name` enum('first_payment','final_payment') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `percentage` decimal(5,2) NOT NULL,
  `status` enum('pending','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `payment_method` enum('bank_deposit','cash','online') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','pending_verification','approved','rejected') NOT NULL DEFAULT 'pending',
  `bank_slip` varchar(255) DEFAULT NULL,
  `rejection_reason` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `payment_method`, `amount`, `status`, `bank_slip`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 1, 'cash', 0.00, 'approved', NULL, NULL, '2025-04-21 10:17:24', '2025-04-23 08:44:48'),
(2, 2, 'bank_deposit', 0.00, 'pending_verification', '1745305781_google-drive.png', NULL, '2025-04-21 11:26:14', '2025-04-22 07:09:42'),
(3, 3, 'bank_deposit', 0.00, 'approved', '1745280620_ER diagram.jpg', 'not clear.\r\nplease upload more clear one', '2025-04-21 22:05:27', '2025-04-22 06:53:38'),
(6, 4, 'cash', 0.00, 'pending', NULL, NULL, '2025-04-22 00:21:16', '2025-04-22 00:21:16'),
(7, 5, 'cash', 0.00, 'pending', NULL, NULL, '2025-04-22 04:56:07', '2025-04-22 04:56:07'),
(8, 6, 'bank_deposit', 0.00, 'approved', '1745298513_bank_slip_order_6.pdf', 'boru slip daanna epa', '2025-04-22 05:02:52', '2025-04-22 06:51:00'),
(9, 7, 'bank_deposit', 0.00, 'approved', '1745310347_L5.E3.drawio (1).pdf', NULL, '2025-04-22 08:22:10', '2025-04-22 08:26:39'),
(10, 8, 'bank_deposit', 0.00, 'pending_verification', '1745334374_Bank_Deposit_Slip (8).pdf', NULL, '2025-04-22 15:06:15', '2025-04-22 15:06:15'),
(11, 9, 'bank_deposit', 0.00, 'approved', '1745519304_bank_slip_order_9.pdf', NULL, '2025-04-24 18:28:24', '2025-04-24 18:31:01'),
(12, 11, 'bank_deposit', 0.00, 'approved', '1745735117_Screenshot 2025-04-27 043553.png', NULL, '2025-04-27 06:25:17', '2025-04-27 06:32:56'),
(13, 12, 'cash', 0.00, 'approved', NULL, NULL, '2025-04-27 06:58:37', '2025-04-27 07:09:40'),
(14, 13, 'bank_deposit', 0.00, 'pending_verification', '1745822248_bank_slip_order_13.pdf', NULL, '2025-04-28 06:37:28', '2025-04-28 06:37:28'),
(15, 14, 'bank_deposit', 0.00, 'approved', '1745826173_Quotation_QT00027.pdf', NULL, '2025-04-28 07:36:15', '2025-04-28 07:43:24'),
(16, 15, 'bank_deposit', 0.00, 'pending_verification', '1745895792_Agreement_AG00054 (1).pdf', NULL, '2025-04-29 03:03:12', '2025-04-29 03:03:12');

-- --------------------------------------------------------

--
-- Table structure for table `payment_log`
--

CREATE TABLE `payment_log` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `project_id` int DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_type` enum('project first payment','project final payment','store payment') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personaldetails`
--

CREATE TABLE `personaldetails` (
  `details_id` int NOT NULL,
  `project_id` int DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `bank_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `account_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `connection_type` enum('ongrid','offgrid','hybrid') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `supply_type` enum('single_phase','three_phase') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `self_submission` tinyint(1) DEFAULT '0',
  `submission_status` enum('pending','submitted','verified') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `submitted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `views` int NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `title`, `slug`, `views`, `image`, `body`, `published`, `created_at`, `updated_at`) VALUES
(1, 1, '5 Habits that can improve your life', '5-habits-that-can-improve-your-life', 0, 'banner.jpg', 'Read every day', 1, '2018-02-03 02:28:02', '2018-02-01 13:44:31'),
(2, 1, 'Second post on LifeBlog', 'second-post-on-lifeblog', 0, 'banner.jpg', 'This is the body of the second post on this site', 1, '2024-11-23 09:33:07', '2024-11-23 09:33:07');

-- --------------------------------------------------------

--
-- Table structure for table `post_topic`
--

CREATE TABLE `post_topic` (
  `id` int NOT NULL,
  `post_id` int NOT NULL,
  `topic_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `post_topic`
--

INSERT INTO `post_topic` (`id`, `post_id`, `topic_id`) VALUES
(1, 1, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `pre_projects`
--

CREATE TABLE `pre_projects` (
  `pre_project_id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `current_phase` enum('quotation','site_visit','agreement') DEFAULT 'quotation',
  `status` enum('active','completed','cancelled') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pre_projects`
--

INSERT INTO `pre_projects` (`pre_project_id`, `customer_id`, `current_phase`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 'agreement', 'completed', '2024-12-17 11:39:56', '2025-01-01 15:19:24'),
(2, 4, 'quotation', 'active', '2024-12-22 16:58:20', '2024-12-22 16:58:20'),
(3, 4, 'agreement', 'completed', '2025-01-02 05:01:31', '2025-01-02 05:24:31'),
(4, 8, 'quotation', 'active', '2025-02-04 07:24:51', '2025-02-04 07:24:51'),
(5, 19, 'agreement', 'completed', '2025-02-04 07:26:57', '2025-02-16 09:23:20'),
(6, 20, 'quotation', 'active', '2025-02-04 07:41:14', '2025-02-04 07:41:14'),
(7, 21, 'agreement', 'completed', '2025-02-11 06:30:34', '2025-02-11 13:06:45'),
(8, 22, 'agreement', 'completed', '2025-02-12 03:24:53', '2025-02-12 03:29:12'),
(9, 25, 'agreement', 'completed', '2025-02-22 08:49:58', '2025-02-22 09:25:12'),
(10, 19, 'site_visit', 'active', '2025-02-27 06:13:10', '2025-02-27 06:15:16'),
(11, 19, 'agreement', 'completed', '2025-02-28 11:25:50', '2025-04-08 15:00:55'),
(12, 21, 'agreement', 'active', '2025-04-16 06:08:41', '2025-04-19 05:53:37'),
(13, 21, 'agreement', 'completed', '2025-04-19 10:21:11', '2025-04-22 04:26:41'),
(14, 21, 'agreement', 'completed', '2025-04-21 10:58:02', '2025-04-24 07:56:18'),
(15, 19, 'agreement', 'completed', '2025-04-22 08:42:59', '2025-04-22 09:18:24'),
(16, 21, 'quotation', 'active', '2025-04-24 07:26:24', '2025-04-24 07:26:24'),
(17, 45, 'agreement', 'completed', '2025-04-25 07:37:53', '2025-04-25 08:23:15'),
(18, 44, 'agreement', 'completed', '2025-04-27 05:13:05', '2025-04-27 23:58:31'),
(19, 45, 'agreement', 'completed', '2025-04-27 08:21:07', '2025-04-27 09:55:18'),
(20, 45, 'site_visit', 'active', '2025-04-27 16:44:21', '2025-04-28 00:01:57'),
(21, 44, 'site_visit', 'active', '2025-04-28 00:11:33', '2025-04-28 00:18:43'),
(22, 44, 'site_visit', 'active', '2025-04-28 02:50:23', '2025-04-28 04:08:12'),
(23, 44, 'agreement', 'completed', '2025-04-28 04:08:41', '2025-04-28 04:20:13'),
(24, 44, 'site_visit', 'active', '2025-04-28 05:43:40', '2025-04-28 05:47:03');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `blog_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `supplier_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `image1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` enum('Solar Panel','Inverters','Components') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Components'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `blog_link`, `supplier_id`, `created_at`, `updated_at`, `deleted_at`, `image1`, `image2`, `image3`, `category`) VALUES
(2, '5KW On Grid Solar PV System', 700000.00, 'Tags: 5kv, on-grid, Solar, Solar Companies in Sri lanka, Solar Electricity in Sri lanka, Solar Energy in Sri lanka, Solar Installation Sri lanka, Solar Power System, Solar System sri lanka', 'https://dinapalagroup.lk/product/5kw-on-grid-solar-pv-system-with-complete-installation-three-phase/', 4, '2024-11-29 00:02:36', '2024-11-29 06:09:49', '2024-11-29 06:09:49', NULL, NULL, NULL, 'Solar Panel'),
(3, 'On Grid Solar PV System', 34500.00, 'Tags: 5kv, on-grid, Solar, Solar Companies in Sri lanka, Solar Electricity in Sri lanka, Solar Energy in Sri lanka, Solar Installation Sri lanka, Solar Power System, Solar System sri lanka', 'https://dinapalagroup.lk/product/5kw-on-grid-solar-pv-system-with-complete-installation-three-phase/', 2, '2024-11-29 00:50:07', '2024-11-29 06:20:07', NULL, NULL, NULL, NULL, 'Inverters'),
(4, 'Rosen 24V 100AH LifePO4 Battery', 369000.00, 'Offers up to 20 times longer cycle life and five times longer float/calendar life than lead acid battery , helping to minimize replacement cost and reduce total cost of ownership.', 'https://cameralktec.com/product/rosen-24v-100ah-lifepo4-battery', 4, '2024-12-02 19:02:52', '2024-12-03 06:47:02', NULL, NULL, NULL, NULL, 'Solar Panel'),
(5, 'SUNONPro 3.5kW Off Grid Hybrid Inverter', 171750.00, 'Inverter Capacity: 3.5kW\r\nWarranty: 12 Months Warranty', 'https://cameralktec.com/product/sunonpro-35kw-off-grid-hybrid-inverter', 3, '2024-12-03 07:21:56', '2024-12-11 14:29:34', NULL, '1733927374_D7dONA8VK0pvqgHDKCZQcoqHpGRD5s5zJWVwofIy.jpg', '1733927374_HBwJLosoFkprAQTCfy3QdhZpOQvWKExrf2zMQfQM.jpg', '1733927374_KinLKYaCbfZzMxmCK4T30VW2iOOXeCqbU6w5JOu6.jpg', 'Inverters'),
(6, 'Growatt Hope 4.8L-C1 Lithium Battery', 945000.00, 'Growatt Hope 4.8L-C1 Lithium Battery is an energy storage unit composed of cells, mechanical parts, battery management system (BMS) as well as power and signal terminals.', 'https://www.cameralk.com/product/growatt-hope-48l-c1-lithium-battery', 4, '2024-12-16 10:10:07', '2024-12-16 10:10:07', NULL, '1734343806_growatt-hope.png', '1734343806_growatt-hope-4.8l-c1-lithium-battery.png', '1734343806_growatt-hope.png', 'Components'),
(7, 'Rosen 51.2V 100Ah LiFePo4 Battery Powerwall', 795000.00, 'Rosen Lithium Ion Powerwall series are widely used in Solar Systems for residential and commercial use. With more than 6000 times deep cycle, up to 90% DOD and phosphate battery Cell, Rosen Powerwall has won global approval!', 'https://www.cameralk.com/product/rosen-51.2v-100ah-lifepo4-battery-powerwall', 2, '2024-12-16 10:15:54', '2024-12-16 10:16:13', '2024-12-16 10:16:13', '1734344153_Rosen.jpg', '1734344153_Rosen.jpg', '1734344153_Rosen.jpg', 'Components'),
(8, 'Rosen 51.2V 100Ah LiFePo4 Battery Powerwall', 795000.00, 'Rosen Lithium Ion Powerwall series are widely used in Solar Systems for residential and commercial use. With more than 6000 times deep cycle, up to 90% DOD and phosphate battery Cell, Rosen Powerwall has won global approval!', 'https://www.cameralk.com/product/rosen-51.2v-100ah-lifepo4-battery-powerwall', 2, '2024-12-16 10:15:58', '2024-12-16 10:15:58', NULL, '1734344157_Rosen.jpg', '1734344157_Rosen.jpg', '1734344157_Rosen.jpg', 'Components'),
(9, 'SAKO 550W high efficiency Solar Panel Mono Half Cut Cell', 40000.00, 'SAKO 550w PV module with 10bb half-cut mono Perc cell technology with multi bus-bar design, improved cells efficiency and get higher output power. The module efficiency up to 21.02%. Such panel can reduce energy loss caused by shading due to new cell string layout and lower cell connection power loss due to half-cell design. .', 'https://www.cameralk.com/product/sako-550w-high-efficiency-solar-panel-mono-per-half-cut-cell', 5, '2024-12-16 10:41:02', '2025-04-27 05:54:22', '2025-04-27 05:54:22', '1745578696_600x400.png', '', '', 'Solar Panel'),
(10, 'testing62', 45598.00, 'gyuguy', 'http://localhost/simpex-solar/supplierCoordinator/addShopProduct', 5, '2025-04-25 11:02:31', '2025-04-25 11:04:28', '2025-04-25 11:04:28', NULL, NULL, NULL, 'Solar Panel'),
(11, 'testing62', 45598.00, 'gyuguy', 'http://localhost/simpex-solar/supplierCoordinator/addShopProduct', 5, '2025-04-25 11:02:34', '2025-04-25 11:04:03', '2025-04-25 11:04:03', NULL, NULL, NULL, 'Solar Panel');

-- --------------------------------------------------------

--
-- Table structure for table `product_features`
--

CREATE TABLE `product_features` (
  `feature_id` int NOT NULL,
  `product_id` int NOT NULL,
  `feature` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_features`
--

INSERT INTO `product_features` (`feature_id`, `product_id`, `feature`, `created_at`, `updated_at`) VALUES
(7, 2, 'dsvs', '2024-11-29 06:09:41', '2024-11-29 06:09:41'),
(8, 2, 'rtty', '2024-11-29 06:09:41', '2024-11-29 06:09:41'),
(9, 3, 'frtg', '2024-11-29 06:20:07', '2024-11-29 06:20:07'),
(10, 3, 'hytg', '2024-11-29 06:20:07', '2024-11-29 06:20:07'),
(11, 3, '', '2024-11-29 06:20:07', '2024-11-29 06:20:07'),
(12, 3, '', '2024-11-29 06:20:07', '2024-11-29 06:20:07'),
(17, 4, 'Normal Battery Voltage(Vdc)  25.6V', '2024-12-03 06:47:03', '2024-12-03 06:47:03'),
(18, 4, 'Weight(NW Kg)  33.0Kg', '2024-12-03 06:47:03', '2024-12-03 06:47:03'),
(25, 5, 'Voltage  230VAC', '2024-12-11 14:29:35', '2024-12-11 14:29:35'),
(26, 5, 'Surge Power  7000VA', '2024-12-11 14:29:35', '2024-12-11 14:29:35'),
(27, 6, 'Compact size and easy installation', '2024-12-16 10:10:07', '2024-12-16 10:10:07'),
(28, 6, 'High energy density and efficiency', '2024-12-16 10:10:08', '2024-12-16 10:10:08'),
(29, 6, 'Excellent safety of LiFePO4 battery', '2024-12-16 10:10:08', '2024-12-16 10:10:08'),
(30, 6, '', '2024-12-16 10:10:08', '2024-12-16 10:10:08'),
(31, 7, 'Attractive Design', '2024-12-16 10:15:55', '2024-12-16 10:15:55'),
(32, 7, 'Wide Application', '2024-12-16 10:15:55', '2024-12-16 10:15:55'),
(33, 7, 'Integrated Function', '2024-12-16 10:15:55', '2024-12-16 10:15:55'),
(34, 7, '', '2024-12-16 10:15:56', '2024-12-16 10:15:56'),
(35, 8, 'Attractive Design', '2024-12-16 10:15:58', '2024-12-16 10:15:58'),
(36, 8, 'Wide Application', '2024-12-16 10:15:59', '2024-12-16 10:15:59'),
(37, 8, 'Integrated Function', '2024-12-16 10:15:59', '2024-12-16 10:15:59'),
(38, 8, '', '2024-12-16 10:15:59', '2024-12-16 10:15:59'),
(43, 9, 'High module efficiency', '2025-04-25 10:58:17', '2025-04-25 10:58:17'),
(44, 9, '10BB Half-Cut Cell Technology', '2025-04-25 10:58:17', '2025-04-25 10:58:17'),
(45, 9, 'Excellent weak light performance', '2025-04-25 10:58:17', '2025-04-25 10:58:17'),
(46, 9, 'Higher Durability against harsh environment', '2025-04-25 10:58:17', '2025-04-25 10:58:17'),
(47, 10, 'yhiuhiu', '2025-04-25 11:02:31', '2025-04-25 11:02:31'),
(48, 10, 'hhiuhh', '2025-04-25 11:02:31', '2025-04-25 11:02:31'),
(49, 10, '', '2025-04-25 11:02:32', '2025-04-25 11:02:32'),
(50, 10, '', '2025-04-25 11:02:32', '2025-04-25 11:02:32'),
(51, 11, 'yhiuhiu', '2025-04-25 11:02:34', '2025-04-25 11:02:34'),
(52, 11, 'hhiuhh', '2025-04-25 11:02:34', '2025-04-25 11:02:34'),
(53, 11, '', '2025-04-25 11:02:34', '2025-04-25 11:02:34'),
(54, 11, '', '2025-04-25 11:02:35', '2025-04-25 11:02:35');

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `project_id` int NOT NULL,
  `pre_project_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `quotation_id` int DEFAULT NULL,
  `package_id` int DEFAULT NULL,
  `custom_package_id` int DEFAULT NULL,
  `status` enum('active','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'active',
  `current_phase` enum('document_submission','first_payment','installation','final_payment','engineer_approval','grid_connection','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'document_submission',
  `estimated_completion_date` date DEFAULT NULL,
  `actual_completion_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `phase_status` enum('pending','in_progress','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `first_payment_amount` decimal(10,2) DEFAULT NULL,
  `final_payment_amount` decimal(10,2) DEFAULT NULL,
  `first_payment_date` date DEFAULT NULL,
  `final_payment_date` date DEFAULT NULL,
  `documents_submitted` json DEFAULT NULL,
  `engineer_approval_date` date DEFAULT NULL,
  `grid_connection_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `project`
--
DELIMITER $$
CREATE TRIGGER `create_payment_phases` AFTER INSERT ON `project` FOR EACH ROW BEGIN
    DECLARE total_project_cost DECIMAL(10,2);
    
    -- Get total project cost (assumed to be stored in the project or agreement)
    SELECT final_price INTO total_project_cost 
    FROM Agreement 
    WHERE project_id = NEW.project_id;
    
    -- Create first payment phase (25%)
    INSERT INTO PaymentPhase (
        project_id, 
        phase_name, 
        total_amount, 
        percentage, 
        status
    ) VALUES (
        NEW.project_id, 
        'first_payment', 
        total_project_cost * 0.25, 
        25.00, 
        'pending'
    );
    
    -- Create final payment phase (75%)
    INSERT INTO PaymentPhase (
        project_id, 
        phase_name, 
        total_amount, 
        percentage, 
        status
    ) VALUES (
        NEW.project_id, 
        'final_payment', 
        total_project_cost * 0.75, 
        75.00, 
        'pending'
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `projectphase`
--

CREATE TABLE `projectphase` (
  `phase_id` int NOT NULL,
  `project_id` int DEFAULT NULL,
  `phase_name` enum('agreement','personal_details','equipment_ordering','installation','testing','completion') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('not_started','pending','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'not_started',
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `project_id` int NOT NULL,
  `pre_project_id` int DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `quotation_id` int DEFAULT NULL,
  `agreement_id` int DEFAULT NULL,
  `package_id` int DEFAULT NULL,
  `custom_package_id` int DEFAULT NULL,
  `status` enum('active','completed','cancelled') DEFAULT NULL,
  `current_phase` enum('document_submission','first_payment','installation','final_payment','engineer_approval','grid_connection','completed') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `equipment_released` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `pre_project_id`, `customer_id`, `quotation_id`, `agreement_id`, `package_id`, `custom_package_id`, `status`, `current_phase`, `created_at`, `updated_at`, `equipment_released`) VALUES
(3, 1, 4, 3, 35, NULL, NULL, 'active', 'document_submission', '2025-01-01 15:19:24', '2025-01-01 15:19:24', 0),
(4, 3, 4, 5, 36, 3, NULL, 'active', 'document_submission', '2025-01-02 05:24:32', '2025-04-23 06:32:25', 0),
(5, 7, 21, 9, 37, NULL, NULL, 'active', 'final_payment', '2025-02-11 13:06:45', '2025-04-21 11:48:35', 1),
(6, 8, 22, 10, 38, NULL, NULL, 'active', 'document_submission', '2025-02-12 03:29:12', '2025-02-12 03:29:12', 0),
(7, 5, 19, 7, 39, NULL, NULL, 'active', 'engineer_approval', '2025-02-16 09:23:21', '2025-04-21 14:18:11', 1),
(8, 9, 25, 11, 43, NULL, NULL, 'active', 'document_submission', '2025-02-22 09:25:12', '2025-02-22 09:25:12', 0),
(9, 11, 19, 16, 44, NULL, NULL, 'active', 'engineer_approval', '2025-04-08 15:00:55', '2025-04-21 14:24:51', 1),
(10, 13, 21, 20, 45, NULL, NULL, 'completed', 'completed', '2025-04-22 04:26:42', '2025-04-28 20:09:11', 1),
(11, 15, 19, 22, 46, NULL, NULL, 'active', 'installation', '2025-04-22 09:18:24', '2025-04-23 16:49:47', 1),
(12, 14, 21, 21, 47, NULL, NULL, 'active', 'installation', '2025-04-24 07:56:19', '2025-04-24 17:20:50', 1),
(13, 17, 45, 24, 48, NULL, NULL, 'active', 'engineer_approval', '2025-04-25 08:23:15', '2025-04-26 21:52:04', 1),
(14, 19, 45, 26, 49, NULL, NULL, 'completed', 'installation', '2025-04-27 09:55:18', '2025-04-28 20:09:12', 1),
(15, 18, 44, 25, 53, NULL, NULL, 'active', 'first_payment', '2025-04-27 23:58:31', '2025-04-28 18:20:06', 0),
(16, 23, 44, 30, 54, NULL, NULL, 'completed', 'completed', '2025-04-28 04:20:13', '2025-04-29 04:02:16', 1);

--
-- Triggers `projects`
--
DELIMITER $$
CREATE TRIGGER `after_equipment_release` AFTER UPDATE ON `projects` FOR EACH ROW BEGIN
    IF NEW.equipment_released = TRUE AND OLD.equipment_released = FALSE THEN
        INSERT INTO installation_phase (project_id, status) 
        VALUES (NEW.project_id, 'initial');
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `project_agreements`
--

CREATE TABLE `project_agreements` (
  `agreement_id` int NOT NULL,
  `review_id` int NOT NULL,
  `quotation_id` int NOT NULL,
  `pre_project_id` int NOT NULL,
  `system_capacity` decimal(10,2) DEFAULT NULL,
  `estimated_generation` decimal(10,2) DEFAULT NULL,
  `base_price` decimal(15,2) DEFAULT NULL,
  `service_charge` decimal(15,2) DEFAULT NULL,
  `total_price` decimal(15,2) DEFAULT NULL,
  `notes` text,
  `revision_request` text,
  `coordinator_signature_id` int DEFAULT NULL,
  `customer_signature` varchar(255) DEFAULT NULL,
  `status` enum('pending','revision_requested','signed','completed','cancelled') DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `project_agreements`
--

INSERT INTO `project_agreements` (`agreement_id`, `review_id`, `quotation_id`, `pre_project_id`, `system_capacity`, `estimated_generation`, `base_price`, `service_charge`, `total_price`, `notes`, `revision_request`, `coordinator_signature_id`, `customer_signature`, `status`, `valid_until`, `created_at`, `updated_at`) VALUES
(4, 6, 3, 1, 1000.00, 1000.00, 1000700.00, 100101.00, 1100801.00, 'test100', 'please revise the agreemnt. we can propbeble go better ', 5, '', 'cancelled', '2025-01-23', '2024-12-24 18:42:34', '2024-12-26 15:55:29'),
(31, 6, 3, 1, 1000.00, 1000.00, 2700000.00, 99999999.99, 99999999.99, 'this is the best deal you can find', 'please do better', 5, NULL, 'cancelled', '2025-01-25', '2024-12-26 15:55:30', '2025-04-27 13:16:18'),
(35, 6, 3, 1, 1000.00, 1000.00, 4200000.00, 19100000.00, 23300000.00, 'this deal is the best', NULL, 5, '67755d003549f_signature.png', 'completed', '2025-01-25', '2024-12-26 16:08:16', '2025-04-27 13:16:18'),
(36, 7, 5, 3, 200.00, 200.00, 400000.00, 1119300.00, 1519300.00, 'emjwhe', NULL, 5, '677623150fdcf_signature.png', 'completed', '2025-02-01', '2025-01-02 05:21:53', '2025-04-27 13:16:18'),
(37, 15, 9, 7, 10.00, 20.00, 1100000.00, 50000000.00, 51100000.00, 'Total Price: Rs. 1,150,000.00,sc 50,000.00, Base Price: Rs. 1,100,000.00', NULL, 5, '67ab4b650071b_image_fx_ (59).jpg', 'completed', '2025-03-13', '2025-02-11 13:06:10', '2025-04-27 13:16:18'),
(38, 16, 10, 8, 10.00, 20.00, 2600000.00, 10000000.00, 12600000.00, 'Please check the agreement\r\n', NULL, 5, '67ac158850a4d_image_fx_ (64).jpg', 'completed', '2025-03-14', '2025-02-12 03:28:37', '2025-04-27 13:16:18'),
(39, 18, 7, 5, 10.00, 20.00, 1100000.00, 99999999.99, 99999999.99, 'This is the final quotation', NULL, 5, '67b1ae891381b_Screenshot 2025-02-14 224655.png', 'completed', '2025-03-18', '2025-02-16 09:22:59', '2025-04-27 13:16:18'),
(40, 19, 11, 9, 1000.00, 1000.00, 500300.00, 10000.00, 510300.00, 'This is the final quotation', NULL, 5, NULL, 'pending', '2025-03-24', '2025-02-22 09:11:58', '2025-02-22 09:11:58'),
(41, 19, 11, 9, 1000.00, 1000.00, 900000.00, 99999999.99, 99999999.99, 'This is the final quotation', 'make again\r\n', 5, NULL, 'cancelled', '2025-03-24', '2025-02-22 09:12:37', '2025-04-27 13:16:18'),
(42, 19, 11, 9, 1000.00, 1000.00, 1400000.00, 99999999.99, 99999999.99, 'This is the final', NULL, 5, NULL, 'pending', '2025-03-24', '2025-02-22 09:23:59', '2025-04-27 13:16:18'),
(43, 19, 11, 9, 1000.00, 1000.00, 1400000.00, 99999999.99, 99999999.99, 'This is the final', NULL, 5, '67b997f90d0f9_International Mother Language Day.png', 'completed', '2025-03-24', '2025-02-22 09:24:06', '2025-04-27 13:16:18'),
(44, 21, 16, 11, 1000.00, 1000.00, 400200.00, 10100.00, 410300.00, 'This is my final price', NULL, 5, '67f53a26cba0f_solar-hero.png', 'completed', '2025-05-08', '2025-04-08 15:00:30', '2025-04-08 15:00:55'),
(45, 24, 20, 13, 100.00, 80.00, 1100000.00, 99999999.99, 99999999.99, 'werder', NULL, 5, '68071a8383cfe_image_fx (8).jpg', 'completed', '2025-05-22', '2025-04-22 04:13:05', '2025-04-27 13:16:18'),
(46, 25, 22, 15, 56.00, 700.00, 200000.00, 10000000.00, 10200000.00, 'rcrfyfu', NULL, 5, '68075ee1578bd_solar-hero.png', 'completed', '2025-05-22', '2025-04-22 09:16:40', '2025-04-27 13:16:18'),
(47, 26, 21, 14, 50.00, 600.00, 900000.00, 10000000.00, 10900000.00, 'yruruyti', NULL, 5, '6809eea2a4e70_output2.png', 'completed', '2025-05-24', '2025-04-24 07:54:24', '2025-04-27 13:16:18'),
(48, 28, 24, 17, 60.00, 55.00, 1000000.00, 99999999.99, 99999999.99, 'these can discuss and change', NULL, 5, '680b46749f3f9_example.png', 'completed', '2025-05-25', '2025-04-25 08:21:08', '2025-04-27 13:16:18'),
(49, 29, 26, 19, 50.00, 1000.00, 300000.00, 30000000.00, 30300000.00, 'last price', NULL, 5, '680dff0649b07_images.png', 'completed', '2025-05-27', '2025-04-27 09:51:46', '2025-04-27 13:16:18'),
(50, 32, 27, 20, 2500.00, 20000.00, 900.00, 10000.00, 10900.00, '', 'revise', 5, NULL, 'cancelled', '2025-05-27', '2025-04-27 17:37:45', '2025-04-27 17:40:01'),
(51, 32, 27, 20, 2500.00, 20000.00, 900.00, 10000.00, 10900.00, '', NULL, 5, NULL, 'pending', '2025-05-27', '2025-04-27 17:40:02', '2025-04-27 17:40:02'),
(52, 32, 27, 20, 2500.00, 20000.00, 900.00, 10000.00, 10900.00, '', NULL, 5, NULL, 'pending', '2025-05-27', '2025-04-27 17:41:37', '2025-04-27 17:41:37'),
(53, 30, 25, 18, 100.00, 10.00, 1100000.00, 600000.00, 1700000.00, 'This is the final quotation', NULL, 5, '', 'pending', '2025-05-27', '2025-04-27 23:38:54', '2025-04-28 00:00:51'),
(54, 35, 30, 23, 5.00, 7300.00, 900000.00, 11000.00, 911000.00, 'shgxusdydd', NULL, 5, '680f01fca3bb7_Infografix.png', 'completed', '2025-05-28', '2025-04-28 04:17:41', '2025-04-28 04:20:13');

-- --------------------------------------------------------

--
-- Table structure for table `project_bankslips`
--

CREATE TABLE `project_bankslips` (
  `id` int NOT NULL,
  `projectpayment_id` int NOT NULL,
  `slip_file` varchar(255) DEFAULT NULL,
  `status` enum('pending','accept','reject') DEFAULT 'pending',
  `reject_reason` text,
  `slip_downloaded` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `project_bankslips`
--

INSERT INTO `project_bankslips` (`id`, `projectpayment_id`, `slip_file`, `status`, `reject_reason`, `slip_downloaded`, `created_at`, `updated_at`) VALUES
(15, 24, 'slip_68030d76015be.png', 'accept', NULL, 1, '2025-04-19 02:40:03', '2025-04-19 02:42:23'),
(16, 26, 'slip_68032c3ff1ee7.pdf', 'accept', NULL, 1, '2025-04-19 04:52:36', '2025-04-19 04:54:02'),
(17, 27, NULL, 'pending', NULL, 1, '2025-04-21 13:18:15', '2025-04-21 13:18:15'),
(18, 31, 'slip_680761a62a530.jpeg', 'accept', NULL, 1, '2025-04-22 09:25:37', '2025-04-22 09:31:50'),
(19, 32, 'slip_6809f10a5779b.pdf', 'accept', NULL, 1, '2025-04-24 08:05:18', '2025-04-24 08:07:25'),
(22, 37, 'final_slip_680d555c31da7.pdf', 'accept', NULL, 1, '2025-04-26 21:49:08', '2025-04-26 21:52:03'),
(23, 38, NULL, 'pending', NULL, 1, '2025-04-27 10:18:32', '2025-04-27 10:18:32'),
(24, 39, 'final_slip_680e114959e76.pdf', 'accept', NULL, 1, '2025-04-27 10:52:40', '2025-04-27 11:14:40'),
(25, 40, 'slip_680f03c3c767e.pdf', 'accept', NULL, 1, '2025-04-28 04:26:46', '2025-04-28 04:29:16');

-- --------------------------------------------------------

--
-- Table structure for table `project_certificates`
--

CREATE TABLE `project_certificates` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `engineer_id` int NOT NULL,
  `installation_certificate_1` varchar(255) DEFAULT NULL,
  `installation_certificate_2` varchar(255) DEFAULT NULL,
  `installation_image_1` varchar(255) DEFAULT NULL,
  `installation_image_2` varchar(255) DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `project_certificates`
--

INSERT INTO `project_certificates` (`id`, `project_id`, `engineer_id`, `installation_certificate_1`, `installation_certificate_2`, `installation_image_1`, `installation_image_2`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 10, 9, 'cert1_680bed7cddc7e_bank_slip_order_9.pdf', 'cert2_680bed7ce0470_delivery_report_ORD202504225398.pdf', 'img1_680bed7ce0d8e_output2.png', 'img2_680bed7ce1845_L5.E3.jpg', '2025-04-25 20:15:55', '2025-04-25 19:19:16', '2025-04-25 20:15:55'),
(2, 13, 9, NULL, NULL, NULL, NULL, NULL, '2025-04-26 22:00:05', '2025-04-26 22:00:05'),
(3, 14, 9, 'cert1_680e1357b35ef_Bank_Deposit_Slip (11).pdf', NULL, 'img1_680e1357b549d_L5.E3.jpg', NULL, '2025-04-27 11:22:00', '2025-04-27 11:17:54', '2025-04-27 11:22:00'),
(4, 16, 7, 'cert1_680f0879268f4_Quotation_QT00028 (1).pdf', NULL, 'img1_680f087926d4d_Infografix.png', NULL, '2025-04-28 04:47:53', '2025-04-28 04:42:23', '2025-04-28 04:47:53');

-- --------------------------------------------------------

--
-- Table structure for table `project_payments`
--

CREATE TABLE `project_payments` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `payment_method` enum('online','bank deposit','cash') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_phase` enum('first_payment','final_payment') NOT NULL,
  `payment_status` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `transaction_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `project_payments`
--

INSERT INTO `project_payments` (`id`, `project_id`, `payment_method`, `amount`, `payment_phase`, `payment_status`, `created_at`, `updated_at`, `transaction_id`) VALUES
(24, 7, 'bank deposit', 200000.00, 'first_payment', 1, '2025-04-19 02:40:02', '2025-04-19 02:42:23', NULL),
(25, 9, 'cash', 300000.00, 'first_payment', 1, '2025-04-19 02:45:30', '2025-04-19 02:47:06', NULL),
(26, 5, 'bank deposit', 12775.00, 'first_payment', 1, '2025-04-19 04:52:36', '2025-04-19 04:54:02', NULL),
(27, 5, 'bank deposit', 38325.00, 'final_payment', 0, '2025-04-21 13:18:15', '2025-04-21 13:18:15', NULL),
(28, 5, 'online', 38325.00, 'final_payment', 1, '2025-04-21 13:25:02', '2025-04-21 13:25:23', 'TRANS_FINAL_680647436ee3b'),
(29, 9, 'cash', 110300.00, 'final_payment', 1, '2025-04-21 14:24:35', '2025-04-21 14:24:51', NULL),
(30, 10, 'cash', 30000.00, 'first_payment', 1, '2025-04-22 04:31:03', '2025-04-25 04:20:39', NULL),
(31, 11, 'bank deposit', 2550.00, 'first_payment', 1, '2025-04-22 09:25:37', '2025-04-22 09:31:51', NULL),
(32, 12, 'bank deposit', 3400.00, 'first_payment', 1, '2025-04-24 08:05:17', '2025-04-24 08:07:25', NULL),
(34, 10, 'cash', 71100.00, 'final_payment', 1, '2025-04-25 04:34:57', '2025-04-25 04:35:51', NULL),
(36, 13, 'cash', 350000.00, 'first_payment', 1, '2025-04-26 21:04:36', '2025-04-26 21:07:06', NULL),
(37, 13, 'bank deposit', 651000.00, 'final_payment', 1, '2025-04-26 21:49:08', '2025-04-26 21:52:03', NULL),
(38, 14, 'cash', 10000.00, 'first_payment', 1, '2025-04-27 10:13:39', '2025-04-27 10:23:21', NULL),
(39, 14, 'bank deposit', 20300.00, 'final_payment', 1, '2025-04-27 10:52:40', '2025-04-27 11:14:40', NULL),
(40, 16, 'bank deposit', 500000.00, 'first_payment', 1, '2025-04-28 04:26:46', '2025-04-28 04:29:16', NULL),
(41, 16, 'cash', 411000.00, 'final_payment', 1, '2025-04-28 04:40:46', '2025-04-28 04:41:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reviewed_quotations`
--

CREATE TABLE `reviewed_quotations` (
  `review_id` int NOT NULL,
  `quotation_id` int DEFAULT NULL,
  `system_capacity` decimal(10,2) DEFAULT NULL,
  `estimated_generation` decimal(10,2) DEFAULT NULL,
  `base_price` decimal(15,2) DEFAULT NULL,
  `service_charge` decimal(15,2) DEFAULT NULL,
  `total_price` decimal(15,2) DEFAULT NULL,
  `notes` text,
  `valid_until` date DEFAULT NULL,
  `status` enum('pending_customer_review','accepted','rejected','expired') DEFAULT 'pending_customer_review',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reviewed_quotations`
--

INSERT INTO `reviewed_quotations` (`review_id`, `quotation_id`, `system_capacity`, `estimated_generation`, `base_price`, `service_charge`, `total_price`, `notes`, `valid_until`, `status`, `created_at`) VALUES
(1, 3, 0.00, 0.00, 200000.00, 99999999.99, 99999999.99, '', '2024-12-27', 'accepted', '2024-12-20 16:46:10'),
(2, 3, 0.00, 0.00, 200000.00, 99999999.99, 99999999.99, '', '2024-12-27', 'accepted', '2024-12-20 16:46:12'),
(3, 3, 10000.00, 1000.00, 400000.00, 100000.00, 500000.00, 'wedeqfref', '2024-12-27', 'accepted', '2024-12-20 16:47:00'),
(4, 3, 10000.00, 1000.00, 400000.00, 100000.00, 500000.00, 'wedeqfref', '2024-12-27', 'accepted', '2024-12-20 16:47:01'),
(5, 3, 10000.00, 1000.00, 400000.00, 100000.00, 500000.00, 'test', '2024-12-27', 'accepted', '2024-12-20 16:48:42'),
(6, 3, 1000.00, 1000.00, 700000.00, 99999999.99, 99999999.99, 'test100', '2024-12-27', 'accepted', '2024-12-20 17:01:55'),
(7, 5, 200.00, 200.00, 0.00, 1119300.00, 1119300.00, 'emjwhe', '2025-01-09', 'accepted', '2025-01-02 05:08:58'),
(8, 8, -1.00, 0.00, 0.00, 99999999.99, 99999999.99, '', '2025-02-11', 'pending_customer_review', '2025-02-04 09:18:23'),
(9, 8, -1.00, 0.00, 0.00, 99999999.99, 99999999.99, '', '2025-02-11', 'pending_customer_review', '2025-02-04 09:19:05'),
(10, 8, 100.00, 100.00, 0.00, 99999999.99, 99999999.99, '', '2025-02-11', 'pending_customer_review', '2025-02-04 09:28:33'),
(11, 8, 0.00, 0.00, 0.00, 99999999.99, 99999999.99, '', '2025-02-11', 'pending_customer_review', '2025-02-04 09:31:51'),
(12, 6, 100.00, 100.00, 0.00, 99999999.99, 99999999.99, 'test this', '2025-02-11', 'pending_customer_review', '2025-02-04 09:41:13'),
(15, 9, 10.00, 20.00, 1100000.00, 50000.00, 1150000.00, 'Total Price: Rs. 1,150,000.00,sc 50,000.00, Base Price: Rs. 1,100,000.00', '2025-02-18', 'accepted', '2025-02-11 08:49:20'),
(16, 10, 10.00, 20.00, 1500000.00, 10000.00, 1510000.00, '', '2025-02-19', 'accepted', '2025-02-12 03:25:44'),
(17, 7, 10.00, 20.00, 1100000.00, 500000.00, 1600000.00, 'This is the final quotation', '2025-02-23', 'accepted', '2025-02-16 08:27:30'),
(18, 7, 10.00, 20.00, 1100000.00, 500000.00, 1600000.00, 'This is the final quotation', '2025-02-23', 'accepted', '2025-02-16 08:27:39'),
(19, 11, 1000.00, 1000.00, 100100.00, 100.00, 100200.00, 'This is the final quotation', '2025-03-01', 'accepted', '2025-02-22 08:54:58'),
(20, 12, 0.00, 0.00, 600300.00, 10000.00, 610300.00, 'this is the final', '2025-03-06', 'accepted', '2025-02-27 06:14:48'),
(21, 16, 1000.00, 1000.00, 100100.00, 10100.00, 110200.00, 'This is my final price', '2025-04-15', 'accepted', '2025-04-08 14:55:30'),
(22, 17, 10000.00, 998.00, 1500000.00, 1000000.00, 2500000.00, 'iwdqeewdwed', '2025-04-26', 'accepted', '2025-04-19 05:02:33'),
(23, 4, 100.00, 10.00, 100100.00, 10000.00, 110100.00, '', '2025-04-26', 'pending_customer_review', '2025-04-19 06:31:19'),
(24, 20, 100.00, 80.00, 1100000.00, 100000.00, 1200000.00, '', '2025-04-26', 'accepted', '2025-04-19 10:49:35'),
(25, 22, 56.00, 700.00, 100100.00, 10000.00, 110100.00, 'rcrfyfu', '2025-04-29', 'accepted', '2025-04-22 08:55:54'),
(26, 21, 50.00, 600.00, 600300.00, 10000.00, 610300.00, 'yruruyti', '2025-05-01', 'accepted', '2025-04-24 07:28:35'),
(27, 24, 50.00, 40.00, 1500000.00, 1500000.00, 3000000.00, 'This is the final note', '2025-05-02', 'accepted', '2025-04-25 07:46:55'),
(28, 24, 60.00, 55.00, 1500000.00, 1000000.00, 2500000.00, '', '2025-05-02', 'accepted', '2025-04-25 07:52:45'),
(29, 26, 50.00, 1000.00, 100100.00, 30000.00, 130100.00, '', '2025-05-04', 'accepted', '2025-04-27 09:27:05'),
(30, 25, 100.00, 10.00, 1100000.00, 600000.00, 1700000.00, 'This is the final quotation', '2025-05-04', 'accepted', '2025-04-27 12:15:40'),
(31, 27, 100.00, 24000.00, 600300.00, 10000.00, 610300.00, '', '2025-05-04', 'accepted', '2025-04-27 17:22:43'),
(32, 27, 2500.00, 20000.00, 600300.00, 10000.00, 610300.00, '', '2025-05-04', 'accepted', '2025-04-27 17:29:32'),
(33, 28, 100.00, 90.00, 600300.00, 10000.00, 610300.00, 'This is my final quotation', '2025-05-05', 'accepted', '2025-04-28 00:12:28'),
(34, 29, 100.00, 10.00, 600300.00, 90000.00, 690300.00, 'this is the final price', '2025-05-05', 'accepted', '2025-04-28 02:54:25'),
(35, 30, 5.00, 7300.00, 600300.00, 11000.00, 611300.00, 'shgxusdydd', '2025-05-05', 'accepted', '2025-04-28 04:09:39'),
(36, 31, 10.00, 20.00, 1100000.00, 500000.00, 1600000.00, 'edderfer', '2025-05-05', 'accepted', '2025-04-28 05:44:43');

-- --------------------------------------------------------

--
-- Table structure for table `reviewed_quotation_equipment`
--

CREATE TABLE `reviewed_quotation_equipment` (
  `id` int NOT NULL,
  `review_id` int NOT NULL,
  `inventory_id` int NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(15,2) DEFAULT NULL,
  `total_price` decimal(15,2) DEFAULT NULL,
  `is_from_package` tinyint DEFAULT '0',
  `modification_type` enum('added','modified','removed') NOT NULL DEFAULT 'added',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reviewed_quotation_equipment`
--

INSERT INTO `reviewed_quotation_equipment` (`id`, `review_id`, `inventory_id`, `quantity`, `unit_price`, `total_price`, `is_from_package`, `modification_type`, `created_at`) VALUES
(1, 6, 2, 4, 100000.00, 400000.00, 1, 'modified', '2024-12-20 17:01:55'),
(2, 6, 1, 3, 100000.00, 300000.00, 0, 'added', '2024-12-20 17:01:55'),
(3, 7, 1, 3, 100000.00, 300000.00, 1, 'modified', '2025-01-02 05:08:59'),
(4, 7, 2, 1, 100000.00, 100000.00, 1, 'modified', '2025-01-02 05:08:59'),
(5, 8, 1, 3, 100000.00, 300000.00, 1, 'modified', '2025-02-04 09:18:23'),
(6, 8, 2, 1, 100000.00, 100000.00, 1, 'modified', '2025-02-04 09:18:24'),
(7, 8, 3, 1, 500000.00, 500000.00, 1, 'modified', '2025-02-04 09:18:24'),
(8, 9, 1, 3, 100000.00, 300000.00, 1, 'modified', '2025-02-04 09:19:05'),
(9, 9, 2, 1, 100000.00, 100000.00, 1, 'modified', '2025-02-04 09:19:06'),
(10, 9, 3, 1, 500000.00, 500000.00, 1, 'modified', '2025-02-04 09:19:06'),
(11, 10, 1, 3, 100000.00, 300000.00, 1, 'modified', '2025-02-04 09:28:33'),
(12, 10, 2, 1, 100000.00, 100000.00, 1, 'modified', '2025-02-04 09:28:33'),
(13, 10, 3, 1, 500000.00, 500000.00, 1, 'modified', '2025-02-04 09:28:33'),
(14, 11, 1, 3, 100000.00, 300000.00, 1, 'modified', '2025-02-04 09:31:51'),
(15, 11, 2, 1, 100000.00, 100000.00, 1, 'modified', '2025-02-04 09:31:51'),
(16, 11, 3, 1, 500000.00, 500000.00, 1, 'modified', '2025-02-04 09:31:52'),
(19, 15, 2, 1, 100000.00, 100000.00, 1, 'modified', '2025-02-11 08:49:21'),
(20, 15, 3, 2, 500000.00, 1000000.00, 1, 'modified', '2025-02-11 08:49:21'),
(21, 16, 2, 5, 100000.00, 500000.00, 1, 'modified', '2025-02-12 03:25:44'),
(22, 16, 3, 2, 500000.00, 1000000.00, 1, 'modified', '2025-02-12 03:25:44'),
(23, 17, 2, 1, 100000.00, 100000.00, 1, 'modified', '2025-02-16 08:27:31'),
(24, 17, 3, 2, 500000.00, 1000000.00, 1, 'modified', '2025-02-16 08:27:31'),
(25, 18, 2, 1, 100000.00, 100000.00, 1, 'modified', '2025-02-16 08:27:39'),
(26, 18, 3, 2, 500000.00, 1000000.00, 1, 'modified', '2025-02-16 08:27:39'),
(27, 19, 1, 1, 100000.00, 100000.00, 1, 'modified', '2025-02-22 08:54:58'),
(28, 19, 2, 1, 100000.00, 100000.00, 1, 'modified', '2025-02-22 08:54:58'),
(29, 20, 1, 3, 100000.00, 300000.00, 1, 'modified', '2025-02-27 06:14:48'),
(30, 20, 2, 1, 100000.00, 100000.00, 1, 'modified', '2025-02-27 06:14:48'),
(31, 20, 3, 1, 500000.00, 500000.00, 1, 'modified', '2025-02-27 06:14:49'),
(32, 21, 1, 1, 100000.00, 100000.00, 1, 'modified', '2025-04-08 14:55:31'),
(33, 21, 2, 1, 100000.00, 100000.00, 1, 'modified', '2025-04-08 14:55:31'),
(34, 22, 2, 5, 100000.00, 500000.00, 1, 'modified', '2025-04-19 05:02:33'),
(35, 22, 3, 2, 500000.00, 1000000.00, 1, 'modified', '2025-04-19 05:02:33'),
(36, 23, 1, 1, 100000.00, 100000.00, 1, 'modified', '2025-04-19 06:31:19'),
(37, 23, 2, 1, 100000.00, 100000.00, 1, 'modified', '2025-04-19 06:31:19'),
(38, 24, 2, 1, 100000.00, 100000.00, 1, 'modified', '2025-04-19 10:49:35'),
(39, 24, 3, 2, 500000.00, 1000000.00, 1, 'modified', '2025-04-19 10:49:35'),
(40, 25, 1, 1, 100000.00, 100000.00, 1, 'modified', '2025-04-22 08:55:55'),
(41, 25, 2, 1, 100000.00, 100000.00, 1, 'modified', '2025-04-22 08:55:55'),
(42, 26, 1, 3, 100000.00, 300000.00, 1, 'modified', '2025-04-24 07:28:35'),
(43, 26, 2, 1, 100000.00, 100000.00, 1, 'modified', '2025-04-24 07:28:35'),
(44, 26, 3, 1, 500000.00, 500000.00, 1, 'modified', '2025-04-24 07:28:35'),
(45, 27, 2, 5, 100000.00, 500000.00, 1, 'modified', '2025-04-25 07:46:55'),
(46, 27, 3, 2, 500000.00, 1000000.00, 1, 'modified', '2025-04-25 07:46:55'),
(47, 28, 2, 5, 100000.00, 500000.00, 1, 'modified', '2025-04-25 07:52:45'),
(48, 28, 3, 2, 500000.00, 1000000.00, 1, 'modified', '2025-04-25 07:52:45'),
(49, 29, 1, 1, 100000.00, 100000.00, 1, 'modified', '2025-04-27 09:27:06'),
(50, 29, 2, 1, 100000.00, 100000.00, 1, 'modified', '2025-04-27 09:27:06'),
(51, 30, 2, 1, 100000.00, 100000.00, 1, 'modified', '2025-04-27 12:15:40'),
(52, 30, 3, 2, 500000.00, 1000000.00, 1, 'modified', '2025-04-27 12:15:40'),
(53, 31, 1, 3, 100.00, 300.00, 1, 'modified', '2025-04-27 17:22:44'),
(54, 31, 2, 1, 100.00, 100.00, 1, 'modified', '2025-04-27 17:22:44'),
(55, 31, 3, 1, 500.00, 500.00, 1, 'modified', '2025-04-27 17:22:44'),
(56, 32, 1, 3, 100.00, 300.00, 1, 'modified', '2025-04-27 17:29:32'),
(57, 32, 2, 1, 100.00, 100.00, 1, 'modified', '2025-04-27 17:29:33'),
(58, 32, 3, 1, 500.00, 500.00, 1, 'modified', '2025-04-27 17:29:33'),
(59, 33, 1, 3, 100.00, 300.00, 1, 'modified', '2025-04-28 00:12:29'),
(60, 33, 2, 1, 100.00, 100.00, 1, 'modified', '2025-04-28 00:12:29'),
(61, 33, 3, 1, 500.00, 500.00, 1, 'modified', '2025-04-28 00:12:29'),
(62, 34, 1, 3, 100.00, 300.00, 1, 'modified', '2025-04-28 02:54:25'),
(63, 34, 2, 1, 100.00, 100.00, 1, 'modified', '2025-04-28 02:54:25'),
(64, 34, 3, 1, 500.00, 500.00, 1, 'modified', '2025-04-28 02:54:25'),
(65, 35, 1, 3, 100.00, 300.00, 1, 'modified', '2025-04-28 04:09:39'),
(66, 35, 2, 1, 100000.00, 100000.00, 1, 'modified', '2025-04-28 04:09:40'),
(67, 35, 3, 1, 500000.00, 500000.00, 1, 'modified', '2025-04-28 04:09:40'),
(68, 36, 2, 1, 100000.00, 100000.00, 1, 'modified', '2025-04-28 05:44:43'),
(69, 36, 3, 2, 500000.00, 1000000.00, 1, 'modified', '2025-04-28 05:44:43');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int NOT NULL,
  `project_id` int NOT NULL,
  `pre_project_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `issue_type` enum('electrical','mechanical','performance','other') NOT NULL,
  `description` text NOT NULL,
  `requested_date` date NOT NULL,
  `completion_date` date DEFAULT NULL,
  `status` enum('pending','accepted','in_progress','completed','rejected') NOT NULL DEFAULT 'pending',
  `comments` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `project_id`, `pre_project_id`, `customer_id`, `issue_type`, `description`, `requested_date`, `completion_date`, `status`, `comments`, `created_at`, `updated_at`) VALUES
(1, 14, 19, 45, 'electrical', 'hgdvghd', '2025-04-28', NULL, 'pending', NULL, '2025-04-28 08:30:52', '2025-04-29 05:09:47');

-- --------------------------------------------------------

--
-- Table structure for table `site_visits`
--

CREATE TABLE `site_visits` (
  `visit_id` int NOT NULL,
  `pre_project_id` int DEFAULT NULL,
  `visit_date` date DEFAULT NULL,
  `visit_time` time DEFAULT NULL,
  `status` enum('pending','scheduled','confirmed','completed','cancelled','reschedule_requested') DEFAULT 'pending',
  `site_notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `reschedule_request` text,
  `reschedule_status` enum('none','requested','rescheduled') DEFAULT 'none'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `site_visits`
--

INSERT INTO `site_visits` (`visit_id`, `pre_project_id`, `visit_date`, `visit_time`, `status`, `site_notes`, `created_at`, `updated_at`, `reschedule_request`, `reschedule_status`) VALUES
(1, 1, '2024-12-25', '14:33:00', 'completed', 'completed site visi succesfully', '2024-12-23 08:58:10', '2024-12-24 06:44:37', NULL, 'rescheduled'),
(2, 3, NULL, NULL, 'completed', NULL, '2025-01-02 05:10:40', '2025-01-02 05:19:37', NULL, 'none'),
(3, 7, '2025-02-23', '09:23:00', 'completed', 'Fine , no chages', '2025-02-11 11:33:16', '2025-02-11 12:49:12', NULL, 'rescheduled'),
(7, 8, '2025-02-20', '11:59:00', 'completed', 'no chages, futhermore add a additional inverter', '2025-02-12 03:26:10', '2025-02-12 03:27:35', NULL, 'none'),
(8, 5, '2025-02-18', '10:41:00', 'completed', 'SITE VISIT COMPLETED', '2025-02-16 08:42:07', '2025-02-16 09:07:46', NULL, 'none'),
(9, 9, '2025-03-25', '09:43:00', 'completed', 'Add another solar panel', '2025-02-22 08:58:46', '2025-02-22 09:08:23', NULL, 'rescheduled'),
(10, 10, NULL, NULL, 'pending', NULL, '2025-02-27 06:15:16', '2025-02-27 06:15:16', NULL, 'none'),
(11, 11, '2025-04-16', '10:32:00', 'completed', 'site visit is completed', '2025-04-08 14:57:51', '2025-04-08 14:59:15', NULL, 'none'),
(12, 12, '2025-04-25', '15:26:00', 'completed', 'site vist cometed hari', '2025-04-19 05:03:43', '2025-04-19 05:53:37', NULL, 'rescheduled'),
(13, 13, '2025-04-23', '09:46:00', 'completed', 'nice', '2025-04-19 10:49:49', '2025-04-19 11:23:26', NULL, 'none'),
(14, 15, '2025-04-24', '08:40:00', 'completed', 'ff', '2025-04-22 08:58:09', '2025-04-22 09:09:28', NULL, 'rescheduled'),
(15, 14, '2025-04-30', '08:18:00', 'completed', 'jytjtkl', '2025-04-24 07:29:10', '2025-04-24 07:48:02', NULL, 'rescheduled'),
(16, 17, '2025-04-28', '09:40:00', 'completed', 'site visit done', '2025-04-25 07:54:08', '2025-04-25 08:14:40', NULL, 'rescheduled'),
(17, 19, '2025-04-27', '10:06:00', 'completed', 'add a new solar panel', '2025-04-27 09:32:54', '2025-04-27 09:41:19', NULL, 'none'),
(18, 18, '2025-04-28', '08:51:00', 'completed', 'this is the final note', '2025-04-27 12:17:28', '2025-04-27 12:20:33', NULL, 'none'),
(19, 20, '2025-04-27', '11:07:00', 'completed', 'Complete site visit', '2025-04-27 17:29:53', '2025-04-27 17:33:53', NULL, 'none'),
(20, 21, NULL, NULL, 'pending', NULL, '2025-04-28 00:18:43', '2025-04-28 00:18:43', NULL, 'none'),
(21, 22, NULL, NULL, 'pending', NULL, '2025-04-28 04:08:12', '2025-04-28 04:08:12', NULL, 'none'),
(22, 23, '2025-04-29', '13:46:00', 'completed', 'wuggkedkwehd edhwejkdh', '2025-04-28 04:11:57', '2025-04-28 04:16:45', NULL, 'none'),
(23, 24, NULL, NULL, 'pending', NULL, '2025-04-28 05:47:03', '2025-04-28 05:47:03', NULL, 'none');

-- --------------------------------------------------------

--
-- Table structure for table `slip_download`
--

CREATE TABLE `slip_download` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `bank_id` int NOT NULL,
  `downloaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `slip_download`
--

INSERT INTO `slip_download` (`id`, `order_id`, `bank_id`, `downloaded_at`) VALUES
(1, 3, 0, '2025-04-21 22:04:29'),
(2, 6, 0, '2025-04-22 05:02:38'),
(3, 7, 1, '2025-04-22 08:19:29'),
(4, 8, 1, '2025-04-22 15:05:46'),
(5, 9, 1, '2025-04-24 18:28:12'),
(6, 11, 1, '2025-04-27 06:21:40'),
(7, 13, 0, '2025-04-28 06:37:01'),
(8, 14, 1, '2025-04-28 07:35:59'),
(9, 15, 0, '2025-04-29 03:01:59');

-- --------------------------------------------------------

--
-- Table structure for table `store_orders`
--

CREATE TABLE `store_orders` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `user_id` int NOT NULL,
  `delivery_option` enum('deliver','pickup') NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `street_address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `address_notes` text,
  `quantity` int NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `delivery_fee` decimal(10,2) DEFAULT '0.00',
  `discount` decimal(10,2) DEFAULT '0.00',
  `status` enum('pending','approved','processing','ready for pickup','out for delivery','delivered','cancelled','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `store_orders`
--

INSERT INTO `store_orders` (`id`, `product_id`, `user_id`, `delivery_option`, `full_name`, `email`, `phone_number`, `street_address`, `city`, `province`, `postal_code`, `address_notes`, `quantity`, `price`, `delivery_fee`, `discount`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, 4, 'deliver', 'Mahinda', 'mahinda@gmail.com', '0775696969', 'No 786, Kadana Rd,', 'Gampaha', 'Western', '78956', '', 1, NULL, 450.00, 0.00, 'rejected', '2025-02-13 06:43:44', '2025-02-15 08:43:03', NULL),
(2, 8, 4, 'pickup', 'user1', 'user1@gmail.com', '0774569231', 'No 12/B, Dhanapala Lane,', 'Biyagama', 'Western', '89021', NULL, 2, 45084322.00, 0.00, 882.00, 'cancelled', '2025-02-13 07:11:34', '2025-02-17 07:32:03', NULL),
(3, 5, 4, 'deliver', 'Saman', 'user1@gmail.com', '0777777777', 'No 786, Kadana Rd,', 'Kandy', 'Central', '89032', NULL, 3, 171753.00, 450.00, 0.00, 'processing', '2025-02-15 08:51:08', '2025-02-17 05:17:42', NULL),
(4, 4, 4, 'deliver', 'user1', 'user1@gmail.com', '0775696969', 'No 67', 'Biyagama', 'Western', '78956', NULL, 2, 369000.00, 750.00, 0.00, 'processing', '2025-02-15 09:10:22', '2025-02-23 08:05:41', NULL),
(5, 9, 4, 'pickup', 'user1', 'user1@gmail.com', '077 4564689', 'No 506/01', 'Maradana', 'Western', '74201', NULL, 2, 40000.00, 0.00, 200.00, 'processing', '2025-02-15 09:12:36', '2025-02-23 07:59:13', NULL),
(6, 5, 4, 'deliver', 'user1', 'user1@gmail.com', '0777777777', 'No 12/B, Dhanapala Lane,', 'Kandy', 'Central', '89021', NULL, 1, 171750.00, 450.00, 0.00, 'processing', '2025-02-25 19:33:53', '2025-02-27 07:30:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `store_payments`
--

CREATE TABLE `store_payments` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `payment_method` enum('online','cash','bank deposit') NOT NULL,
  `payment_status` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `store_payments`
--

INSERT INTO `store_payments` (`id`, `order_id`, `payment_method`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, 3, 'cash', 0, '2025-02-17 05:17:43', '2025-02-17 05:17:43'),
(14, 5, 'bank deposit', 1, '2025-02-23 07:59:13', '2025-04-23 11:47:03'),
(16, 4, 'bank deposit', 0, '2025-02-23 08:05:42', '2025-02-23 08:05:42'),
(18, 6, 'bank deposit', 0, '2025-02-27 07:30:48', '2025-02-27 07:30:48');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contact_number` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `other_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `address`, `email`, `contact_number`, `other_details`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'ComapanyA', '123 Solar St, Colombo', 'john.doe@example.com', '0712345678', 'Preferred supplier for solar panels', '2024-11-25 08:23:19', '2024-11-25 08:23:19', NULL),
(2, 'JComapanyB', '456 Green Ave, Colombo', 'jane.smith@example.com', '0723456789', 'Specializes in inverters and batteries', '2024-11-25 08:23:19', '2025-04-28 04:35:57', NULL),
(3, 'helys', 'address1, road2, city 3', 'helys@gmail.com', '0712362378', 'no details', '2024-11-25 08:54:30', '2024-11-25 08:54:30', NULL),
(4, 'new company', 'new, road, city', 'new@gmail.com', '0712321324', 'no', '2024-11-25 10:05:18', '2024-11-25 10:05:18', NULL),
(5, 'ranil_2', 'cdfcdff', 'supplier@gmail.com', '3242435454', 'rfretrtg', '2024-11-27 07:15:05', '2025-04-29 03:31:18', '2025-04-29 03:31:18'),
(6, 'njnnnn nnsas bb', '4jvn nnsndbb', 'k@gnndshjasbb.h', '0159456655', 'i6fcmsnadbb', '2025-04-25 11:06:46', '2025-04-28 05:17:39', '2025-04-28 05:17:39'),
(7, 'peshff', 'dwddff', 'pesh@nsdffd.vf', '0159456699', 'deffrff', '2025-04-28 05:18:19', '2025-04-28 05:18:50', '2025-04-28 05:18:50'),
(8, 'peee', 'peee', 'pee@jj.kk', '0159456689', 'peee', '2025-04-28 05:33:39', '2025-04-28 05:34:27', '2025-04-28 05:34:27'),
(9, 'pesi', 'pesi', 'pes@fi.lk', '0111111113', 'pesi', '2025-04-28 06:36:53', '2025-04-28 06:37:40', '2025-04-28 06:37:40'),
(10, 'rasa', 'rasa', 'ras@gha.lk', '0745656566', 'rasa', '2025-04-28 06:58:07', '2025-04-28 06:59:14', '2025-04-28 06:59:14'),
(11, 'Hayleys Solar', '400 Deans Road, Colombo 10', 'info@hayleyssolar.com', '0112345678', 'Leading solar solutions provider in Sri Lanka; offers panels, batteries, inverters', '2025-04-29 03:36:54', '2025-04-29 03:36:54', NULL),
(12, 'Gunda Power', '55/3 Nawala Rd, Nugegoda', 'support@gundapower.com', '0112888999', 'Specializes in solar power systems for commercial and residential use', '2025-04-29 03:38:19', '2025-04-29 03:38:19', NULL),
(13, 'SunPower Energy', '23 Temple Rd, Colombo 6', 'sales@sunpowerenergy.lk', '0773123456', 'Offers full-service installation and solar consultations', '2025-04-29 03:42:14', '2025-04-29 03:42:14', NULL),
(14, 'Greenarica Solar', '89 Park Avenue, Kandy', 'hello@greenarica.lk', '0711234567', 'Provides eco-friendly solar solutions with island-wide service', '2025-04-29 03:43:16', '2025-04-29 03:43:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `project_id` int DEFAULT NULL,
  `employee_id` int DEFAULT NULL,
  `status` enum('not_started','in_progress','completed') NOT NULL DEFAULT 'not_started',
  `comment` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `start_date`, `end_date`, `project_id`, `employee_id`, `status`, `comment`, `created_at`, `updated_at`) VALUES
(2, 'Task1', 'Add task', '2025-04-22', '2025-04-25', 4, 8, 'in_progress', NULL, '2025-04-13 02:34:55', '2025-04-25 04:51:41'),
(4, 'task3', 'this is task 3', '2025-04-30', '2025-05-07', 4, 6, 'completed', 'commented', '2025-04-25 03:26:32', '2025-04-27 06:01:43'),
(5, 'task4', 'this is task 4', '2025-04-29', '2025-05-03', 4, 10, 'in_progress', 'ccsc', '2025-04-25 03:30:54', '2025-04-26 11:13:23'),
(6, 'task5', 'this is task 5', '2025-05-02', '2025-05-03', 4, 9, 'not_started', NULL, '2025-04-26 07:36:12', '2025-04-28 18:54:08'),
(7, 'New task', 'Description', '2025-04-27', '2025-04-30', 4, 23, 'in_progress', NULL, '2025-04-26 20:56:50', '2025-04-26 20:56:50'),
(8, 'Roof Inspection', 'View roof that is suitable for solar installation', '2025-04-30', '2025-05-01', 4, 10, 'not_started', NULL, '2025-04-28 19:05:40', '2025-04-28 22:39:19'),
(10, 'Roof Inspection', 'roof inspection', '2025-04-30', '2025-05-06', 4, 13, 'not_started', NULL, '2025-04-29 04:50:19', '2025-04-29 04:51:40');

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`id`, `name`, `slug`) VALUES
(1, 'Inspiration', 'inspiration'),
(2, 'Motivation', 'motivation'),
(3, 'Diary', 'diary');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` enum('customer','admin','chiefCoordinator','operationsCoordinator','hRAdministrator','supplierCoordinator','employee') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `profile_picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `phone`, `role`, `created_at`, `updated_at`, `profile_picture`) VALUES
(1, 'first', 'first@gmail.com', '$2y$10$2.3uy81juXArBe2moaooV.m/Ks1vka/rQzpye4Cw775rROrUGZwtm', NULL, 'customer', '2024-11-21 06:45:27', '2024-11-21 06:45:27', NULL),
(2, 'admin', 'admin@gmail.com', '$2y$10$aNpJXxHKNXXoIlO2WsSOHejhMtKC4OLT1Pf.G.yqPjc/aomJ61Cb6', '0749825681', 'admin', '2024-11-23 07:12:45', '2025-04-26 11:11:49', '680cbf74df2c6_TSHIRT PROJECT (3).png'),
(3, 'manager', 'm@gmail.com', '$2y$10$5.Dy033hIeXxQYhrij4Ii.zn3/mPJfREDNWsj6uHGPbjW34lniJxW', NULL, '', '2024-11-23 07:13:29', '2024-11-23 07:13:29', NULL),
(4, 'Anoja Silva', 'user1@gmail.com', '$2y$10$xqJ5XMWPA51bZDkRX4OvvuacVI9fwdUPxxez1j7JvObg3jEyG10qe', '2342354325', 'customer', '2024-11-23 13:08:38', '2025-04-27 01:22:40', '67762a766da64_PesanigeAluthmaThuna.png'),
(5, 'Sewmini', 'user2@gmail.com', '$2y$10$3xW56eqCz3HvW3jaN2eEBuVQa3RKCmS96/04MozdUvo0qS8B1.m6.', NULL, 'customer', '2024-11-23 15:21:14', '2025-04-27 01:22:40', NULL),
(6, 'Piyasena', 'user3@gmail.com', '$2y$10$MoLTIZiyOGH2YxKUoYMtq.N43/LPNYYOzVH9JoHIn5Y4X9DRPgwz6', NULL, 'customer', '2024-11-23 15:59:59', '2025-04-27 01:22:40', NULL),
(8, 'Chaminda Dissanayake', 'operations.simplex@gmail.com', '$2y$10$wQGNNinmSu0zBJKyqUf/kOYbu0n.UjyPWzPeVH7PJ2lITGD5hNriK', '0775478954', 'operationsCoordinator', '2024-11-26 05:08:44', '2025-04-25 22:30:22', '680c0cea963b8_OPdownload (1).jpeg'),
(9, 'Gayan de Silva', 'hr.simplex@gmail.com', '$2y$10$3AuIxAc0PxbGeKgLm0GF/.MO8ETeru.3xhQn6wgn7oCmHlsjDhGLa', '0777159826', 'hRAdministrator', '2024-11-26 06:25:18', '2025-04-26 07:33:45', '680c09735161e_hrmdoenload.jpeg'),
(10, 'Iresha Hettiarachchi', 'supplier.simplex@gmail.com', '$2y$10$xiNJZrzPUKY91AduR6I3TuUU4Yqc4Xz.tHx8Y8jU33xdMp4lsbxqW', '0723456789', 'supplierCoordinator', '2024-11-26 06:26:08', '2025-04-25 22:35:17', '680c0e2568492_dowSuPOM.jpeg'),
(11, 'Sampath Jayasinghe', 'chief.simplex@gmail.com', '$2y$10$b6ghp5./SZTkT5/eX6jnXO2PxrFBKWZMklqqjVScENKUl98iUAQam', '0777144982', 'chiefCoordinator', '2024-11-26 06:26:36', '2025-04-26 07:44:12', '680c0ebd01803_chimanjpeg.jpeg'),
(12, 'Amila Kumara', 'techie11@gmail.com', '$2y$10$pKEkTots5M4DDT8RtHdBBOXU24ddGoTIJG9N0j5H/IX0Z8Pd8ajUu', '0757789456', 'employee', '2024-12-16 17:44:00', '2025-04-26 07:29:51', NULL),
(13, 'Roshan Gamage', 'techie12@gmail.com', '$2y$10$uhgfJMt52T9HxIJDR34TOe0kE0nbbQhtI17jkIBemAUZie9Vri0rm', '0112549161', 'employee', '2024-12-16 17:48:11', '2025-04-27 01:18:20', '680d85dd0561b_download.jpeg'),
(14, 'Thilini Herath', 'deliveryperson11@gmail.com', '$2y$10$iYHthi44iTABpgfs1gva7OWpmWDLMnwpBzk0umHzGFX0KqT9t2qHi', '0777171771', 'employee', '2024-12-17 06:12:13', '2025-04-25 19:42:51', NULL),
(15, 'Jayashanka', 'testing2@gmail.com', '$2y$10$bLbfqca.CRkBvuAp.t5.JOXeW3uUbbGtm4TBtXgeBiEmyaifRB3Je', '07596232449', 'employee', '2025-01-02 05:05:07', '2025-04-27 01:22:39', NULL),
(16, 'Fernando', 'testing3@gamil.com', '$2y$10$6Z6newMfIvBmQwEJSFmheO5BdoIyT1IlJL3WDRr6fsGoxqruHFhVO', '0774568945', 'employee', '2025-01-02 05:28:57', '2025-04-27 01:22:39', NULL),
(17, 'Nuwan Bandara', 'engineer11@gmail.com', '$2y$10$yCdUiUIMHNF48DQMLBZU8uCndzLeLHhBtTjr/viDVUlyCFjA0orfe', '0777777777', 'employee', '2025-01-17 10:00:59', '2025-04-25 19:42:50', NULL),
(18, 'Fathima Ranasinghe', 'technician11@gmail.com', '$2y$10$SLk9Uk3GS2ZEWH9.Ciyxd.RvuL5zQvFdNvfzvj5VCHE6RK3SZtYoy', '0711111111', 'employee', '2025-01-17 10:01:51', '2025-04-25 19:42:51', NULL),
(19, 'Kamal', 'kamal@gmail.com', '$2y$10$yosuN7FoPNFh2E/STtzRUejUccbw5pGywOjttwQutWbGuJBiS36fy', '0758269874', 'customer', '2025-02-04 07:25:21', '2025-04-22 08:43:00', NULL),
(20, 'Nimal', 'nimal@gmail.com', '$2y$10$fQx.pRXZgUBYOlvPZPQDi.iCKfKOeXn0A7cebe8WmPURqRavKlIH2', NULL, 'customer', '2025-02-04 07:40:09', '2025-02-04 07:40:09', NULL),
(21, 'Kasun', 'kasun@gmail.com', '$2y$10$nFK2WxjfuBzEEH1PeZLudung.dTBmx0f8qwZdSVDxVmKBc6io7JnC', '0774549689', 'customer', '2025-02-11 06:28:29', '2025-04-24 07:26:24', '680375aa50807_20250419_1536_Kasun\'s 35mm Portrait_simple_compose_01js6r53kce94tvfkg0a4fsxw3.png'),
(22, 'ajith', 'ajith@gmail.com', '$2y$10$FQEZZ7FUayksC1CZ.IX5reY6GKyW5twlbjYXdY4/Eo8zOCc5GtmpC', NULL, 'customer', '2025-02-12 03:23:29', '2025-02-12 03:23:29', NULL),
(23, 'Nisansala', 'Nisansalaaa@yahoo.com', '$2y$10$Aplht5QQRTGmH9j5loXVTexfr3lflCOqn3jPVjpFXsgJs3N9uGHgu', '019849852594', 'employee', '2025-02-15 10:21:36', '2025-04-27 01:23:38', NULL),
(24, 'Kasuni', '15ya@gmail.com', '$2y$10$h2LPgReo1NGF0YkAgkuhye7x9nbGsNRff2Boof2ptrbGAMoteFoUG', '649824984', 'employee', '2025-02-15 14:06:36', '2025-04-27 01:22:38', NULL),
(25, 'thisum', 'thisum@gmail.com', '$2y$10$3Or51rVcDtbztFFj1RcktO/1LaYGjGSosOURjraiiDIp2Bi.z3ufa', NULL, 'customer', '2025-02-22 08:46:39', '2025-02-22 08:46:39', NULL),
(26, 'pasan', 'pasansanjiiwa2022@gmail.com', '$2y$10$EZfKpFvR.AXFhctja0DtyOmozX/K/rR6xFlxBUI8GFIkMY37qImoG', NULL, 'customer', '2025-02-25 12:11:33', '2025-02-25 12:11:33', NULL),
(27, 'pasan', 'pasan@gmail.com', '$2y$10$f9ar6YlqhOZSF0Z04tppq.DiYjDWv3wCXsT/cWO4K8Crv1IeOKraS', NULL, 'customer', '2025-02-25 12:12:06', '2025-02-25 12:12:06', NULL),
(28, 'Ridmi Thisera', 'ya@gmail.com', '$2y$10$rGVO3QCqkX4WpoDEQcjl.eUG/sdh7is.L7cdkL.7ZVKf9A6hA8xl6', '0779559525626', 'employee', '2025-02-26 18:41:30', '2025-04-27 01:38:57', NULL),
(29, 'Binula Dimantha', 'habinuladt2002@gmail.com', '$2y$10$isEbC2SUh/NWUH2o.lTGJuVMKLLPcIjQSrYc6fJNYSO/eKUxAWcT2', NULL, 'customer', '2025-03-11 11:56:39', '2025-03-11 11:56:39', NULL),
(33, 'pasan sanjiiwa', 'pasansanjiiwa2023@gmail.com', '$2y$10$mzSjQ4ND5fMkBgmZcAGOLOppo2zZ6a8vmqK0skFqC/89KNvKfWEyq', NULL, 'customer', '2025-04-07 15:38:48', '2025-04-07 15:38:48', NULL),
(34, 'Thisum', 'thisum12@gmail.com', '$2y$10$H8DD5uL.rfbsKlQhdqDTQu1NNulC8lxk9iy9Wl.esBqGmBeTNXQR2', '0771234567', 'employee', '2025-04-22 09:46:50', '2025-04-27 01:22:38', NULL),
(35, 'Silva', 'testing55@gmail.com', '$2y$10$B4HHIMQ9MD816ogFibWcmOqMCZYBp1kzmo5wsUMZu.TGyYfs8qK9m', '1234567', 'employee', '2025-04-23 08:34:42', '2025-04-27 01:22:38', NULL),
(36, 'Anura', 'testing56@gmail.com', '$2y$10$23uEHltzfmDffHOwTuxcN.H9fRV5Vl5T1.3wVKPIPyGIwW8wV8whe', '12345', 'employee', '2025-04-23 08:40:04', '2025-04-27 01:22:37', NULL),
(37, 'Saman', 'testing57@gmail.com', '$2y$10$zEWV9bmA2ugTsUlXCFu5HuQjB/niOo3AJ.ds1InQXe0pj0DhzjXgu', '1234567', 'employee', '2025-04-23 08:50:06', '2025-04-27 01:22:37', NULL),
(38, 'Amal', 'testing59@gmail.com', '$2y$10$.b7QtaDuWA8ikWAozaeUeOVey.uoN66D9e6aMddjFmzrY39iLZZTe', '1234567', 'employee', '2025-04-23 09:07:05', '2025-04-27 01:22:37', NULL),
(41, 'Kamal', 'testing67@gmail.com', '$2y$10$E3qN1jjna3/ujKkrjeRrt.K0WL7ITRQ7UXE/xFhTTtbYA/HuhDHke', '123456489', 'employee', '2025-04-23 17:01:18', '2025-04-27 01:22:36', '68091cdfdeaff_logoany.png'),
(42, 'Saduni', 'testing62@gmail.com', '$2y$10$pLdOHJ3dRG00npbDox2DnOYtW5U5SwHlIBvAZfXNkcJsw4Ww/iUca', '1656498498', 'employee', '2025-04-23 17:33:32', '2025-04-27 01:22:36', '68094e293f010_distributed-database.png'),
(43, 'Supun', 'bhbhb@jnk.ddk', '$2y$10$Lgrmp/34BGPh.2WdQKfBpes4BX8xJvRnpy/CJpyiXHhtMRECS2nja', '45615', 'employee', '2025-04-23 18:56:02', '2025-04-27 01:22:35', ''),
(44, 'Binula Dimantha', 'habdthilakasiri2002@gmail.com', '$2y$10$UuhDLYRdXs54h7jYPFtoq.WXJzrtFpogqoUJraUTjPqP.PRLAcjIe', '0712323423', 'customer', '2025-04-24 04:59:04', '2025-04-28 02:50:23', NULL),
(45, 'Oshada', 'dimuth515@gmail.com', '$2y$10$oLjpSTD5PY332fNatAAKR.f9yTAh9Uud6oob6cDwhj3XCP7u4U5a2', '0771579826', 'customer', '2025-04-25 07:25:51', '2025-04-27 16:44:22', '680de936ae5ca_download.jpeg'),
(46, 'Indika Fernando', 'indika@gmail.com', '$2y$10$pUs/nfSVlvSmqLrvX4..COCnzic1GQb2Cu3P/4sv4rjPP5gumzxrO', '0774896512', 'employee', '2025-04-25 22:02:11', '2025-04-25 22:02:11', '680c066310f6f_download.jpeg'),
(47, 'Dinesh Abeysekera', 'dinesh.abeyail@d', '$2y$10$O2b7CpzClKPTICx6jVtFZeXNGy3QG/W68JAUrshF8WKqYhUPcJs3K', '0723415789', 'employee', '2025-04-26 10:10:43', '2025-04-26 10:10:43', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agreement_equipment`
--
ALTER TABLE `agreement_equipment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agreement_id` (`agreement_id`),
  ADD KEY `inventory_id` (`inventory_id`);

--
-- Indexes for table `approvalschedule`
--
ALTER TABLE `approvalschedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `proposed_user_id` (`proposed_user_id`),
  ADD KEY `engineer_id` (`engineer_id`),
  ADD KEY `idx_schedule_approval` (`approval_id`),
  ADD KEY `idx_schedule_status` (`status`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`employee_id`,`date`);

--
-- Indexes for table `bank_slips`
--
ALTER TABLE `bank_slips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_id` (`payment_id`);

--
-- Indexes for table `blogcomments`
--
ALTER TABLE `blogcomments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`post_id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart_item` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `coordinator_signatures`
--
ALTER TABLE `coordinator_signatures`
  ADD PRIMARY KEY (`signature_id`),
  ADD KEY `coordinator_id` (`coordinator_id`);

--
-- Indexes for table `customerquotation`
--
ALTER TABLE `customerquotation`
  ADD PRIMARY KEY (`quotation_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `pre_project_id` (`pre_project_id`);

--
-- Indexes for table `custompackage`
--
ALTER TABLE `custompackage`
  ADD PRIMARY KEY (`custom_package_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `base_package_id` (`base_package_id`);

--
-- Indexes for table `documentSubmission`
--
ALTER TABLE `documentSubmission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_employee_role` (`role`);

--
-- Indexes for table `engineerapproval`
--
ALTER TABLE `engineerapproval`
  ADD PRIMARY KEY (`approval_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `installation`
--
ALTER TABLE `installation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `installation_phase`
--
ALTER TABLE `installation_phase`
  ADD PRIMARY KEY (`installation_id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `engineer_id` (`engineer_id`);

--
-- Indexes for table `installation_schedule`
--
ALTER TABLE `installation_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `installation_id` (`installation_id`);

--
-- Indexes for table `installation_team`
--
ALTER TABLE `installation_team`
  ADD PRIMARY KEY (`id`),
  ADD KEY `installation_id` (`installation_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_ibfk_1` (`supplier_id`);

--
-- Indexes for table `leaverecords`
--
ALTER TABLE `leaverecords`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_deliver` (`deliver_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `otps`
--
ALTER TABLE `otps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`package_id`),
  ADD UNIQUE KEY `unique_slug` (`slug`),
  ADD KEY `idx_package_title` (`title`);

--
-- Indexes for table `packageequipment`
--
ALTER TABLE `packageequipment`
  ADD PRIMARY KEY (`equipment_id`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `fk_inventory` (`item_id`);

--
-- Indexes for table `packagefeature`
--
ALTER TABLE `packagefeature`
  ADD PRIMARY KEY (`feature_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `paymentphase`
--
ALTER TABLE `paymentphase`
  ADD PRIMARY KEY (`phase_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `payment_log`
--
ALTER TABLE `payment_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `personaldetails`
--
ALTER TABLE `personaldetails`
  ADD PRIMARY KEY (`details_id`),
  ADD UNIQUE KEY `project_id` (`project_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_topic`
--
ALTER TABLE `post_topic`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `topic_id` (`topic_id`);

--
-- Indexes for table `pre_projects`
--
ALTER TABLE `pre_projects`
  ADD PRIMARY KEY (`pre_project_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `product_features`
--
ALTER TABLE `product_features`
  ADD PRIMARY KEY (`feature_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `quotation_id` (`quotation_id`),
  ADD KEY `custom_package_id` (`custom_package_id`),
  ADD KEY `idx_project_status` (`status`),
  ADD KEY `fk_project_pre_project` (`pre_project_id`),
  ADD KEY `fk_project_customer` (`customer_id`);

--
-- Indexes for table `projectphase`
--
ALTER TABLE `projectphase`
  ADD PRIMARY KEY (`phase_id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `idx_phase_status` (`status`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `pre_project_id` (`pre_project_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `quotation_id` (`quotation_id`),
  ADD KEY `agreement_id` (`agreement_id`);

--
-- Indexes for table `project_agreements`
--
ALTER TABLE `project_agreements`
  ADD PRIMARY KEY (`agreement_id`),
  ADD KEY `review_id` (`review_id`),
  ADD KEY `coordinator_signature_id` (`coordinator_signature_id`);

--
-- Indexes for table `project_bankslips`
--
ALTER TABLE `project_bankslips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projectpayment_id` (`projectpayment_id`);

--
-- Indexes for table `project_certificates`
--
ALTER TABLE `project_certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `engineer_id` (`engineer_id`);

--
-- Indexes for table `project_payments`
--
ALTER TABLE `project_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `reviewed_quotations`
--
ALTER TABLE `reviewed_quotations`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `quotation_id` (`quotation_id`);

--
-- Indexes for table `reviewed_quotation_equipment`
--
ALTER TABLE `reviewed_quotation_equipment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `review_id` (`review_id`),
  ADD KEY `inventory_id` (`inventory_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `pre_project_id` (`pre_project_id`);

--
-- Indexes for table `site_visits`
--
ALTER TABLE `site_visits`
  ADD PRIMARY KEY (`visit_id`),
  ADD KEY `pre_project_id` (`pre_project_id`);

--
-- Indexes for table `slip_download`
--
ALTER TABLE `slip_download`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `store_orders`
--
ALTER TABLE `store_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `store_payments`
--
ALTER TABLE `store_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_user_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agreement_equipment`
--
ALTER TABLE `agreement_equipment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `approvalschedule`
--
ALTER TABLE `approvalschedule`
  MODIFY `schedule_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_slips`
--
ALTER TABLE `bank_slips`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `blogcomments`
--
ALTER TABLE `blogcomments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `post_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `coordinator_signatures`
--
ALTER TABLE `coordinator_signatures`
  MODIFY `signature_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customerquotation`
--
ALTER TABLE `customerquotation`
  MODIFY `quotation_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `custompackage`
--
ALTER TABLE `custompackage`
  MODIFY `custom_package_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documentSubmission`
--
ALTER TABLE `documentSubmission`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `engineerapproval`
--
ALTER TABLE `engineerapproval`
  MODIFY `approval_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `installation`
--
ALTER TABLE `installation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `installation_phase`
--
ALTER TABLE `installation_phase`
  MODIFY `installation_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `installation_schedule`
--
ALTER TABLE `installation_schedule`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `installation_team`
--
ALTER TABLE `installation_team`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leaverecords`
--
ALTER TABLE `leaverecords`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `otps`
--
ALTER TABLE `otps`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `package_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `packageequipment`
--
ALTER TABLE `packageequipment`
  MODIFY `equipment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `packagefeature`
--
ALTER TABLE `packagefeature`
  MODIFY `feature_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `paymentphase`
--
ALTER TABLE `paymentphase`
  MODIFY `phase_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `payment_log`
--
ALTER TABLE `payment_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personaldetails`
--
ALTER TABLE `personaldetails`
  MODIFY `details_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `post_topic`
--
ALTER TABLE `post_topic`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pre_projects`
--
ALTER TABLE `pre_projects`
  MODIFY `pre_project_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product_features`
--
ALTER TABLE `product_features`
  MODIFY `feature_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `project_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `projectphase`
--
ALTER TABLE `projectphase`
  MODIFY `phase_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `project_agreements`
--
ALTER TABLE `project_agreements`
  MODIFY `agreement_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `project_bankslips`
--
ALTER TABLE `project_bankslips`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `project_certificates`
--
ALTER TABLE `project_certificates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `project_payments`
--
ALTER TABLE `project_payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `reviewed_quotations`
--
ALTER TABLE `reviewed_quotations`
  MODIFY `review_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `reviewed_quotation_equipment`
--
ALTER TABLE `reviewed_quotation_equipment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `site_visits`
--
ALTER TABLE `site_visits`
  MODIFY `visit_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `slip_download`
--
ALTER TABLE `slip_download`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `store_orders`
--
ALTER TABLE `store_orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `store_payments`
--
ALTER TABLE `store_payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agreement_equipment`
--
ALTER TABLE `agreement_equipment`
  ADD CONSTRAINT `agreement_equipment_ibfk_1` FOREIGN KEY (`agreement_id`) REFERENCES `project_agreements` (`agreement_id`),
  ADD CONSTRAINT `agreement_equipment_ibfk_2` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`);

--
-- Constraints for table `approvalschedule`
--
ALTER TABLE `approvalschedule`
  ADD CONSTRAINT `approvalschedule_ibfk_1` FOREIGN KEY (`approval_id`) REFERENCES `engineerapproval` (`approval_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `approvalschedule_ibfk_2` FOREIGN KEY (`proposed_user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `approvalschedule_ibfk_3` FOREIGN KEY (`engineer_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `bank_slips`
--
ALTER TABLE `bank_slips`
  ADD CONSTRAINT `bank_slips_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `store_payments` (`id`);

--
-- Constraints for table `blogcomments`
--
ALTER TABLE `blogcomments`
  ADD CONSTRAINT `blogcomments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `blogcomments_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `blog_posts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`category_id`);

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `coordinator_signatures`
--
ALTER TABLE `coordinator_signatures`
  ADD CONSTRAINT `coordinator_signatures_ibfk_1` FOREIGN KEY (`coordinator_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `customerquotation`
--
ALTER TABLE `customerquotation`
  ADD CONSTRAINT `customerquotation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `customerquotation_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `package` (`package_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `customerquotation_ibfk_3` FOREIGN KEY (`pre_project_id`) REFERENCES `pre_projects` (`pre_project_id`);

--
-- Constraints for table `custompackage`
--
ALTER TABLE `custompackage`
  ADD CONSTRAINT `custompackage_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `custompackage_ibfk_2` FOREIGN KEY (`base_package_id`) REFERENCES `package` (`package_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `documentSubmission`
--
ALTER TABLE `documentSubmission`
  ADD CONSTRAINT `documentSubmission_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `installation_phase`
--
ALTER TABLE `installation_phase`
  ADD CONSTRAINT `installation_phase_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`),
  ADD CONSTRAINT `installation_phase_ibfk_2` FOREIGN KEY (`engineer_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `installation_schedule`
--
ALTER TABLE `installation_schedule`
  ADD CONSTRAINT `installation_schedule_ibfk_1` FOREIGN KEY (`installation_id`) REFERENCES `installation_phase` (`installation_id`);

--
-- Constraints for table `installation_team`
--
ALTER TABLE `installation_team`
  ADD CONSTRAINT `installation_team_ibfk_1` FOREIGN KEY (`installation_id`) REFERENCES `installation_phase` (`installation_id`),
  ADD CONSTRAINT `installation_team_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_deliver` FOREIGN KEY (`deliver_id`) REFERENCES `employees` (`employee_id`),
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `packageequipment`
--
ALTER TABLE `packageequipment`
  ADD CONSTRAINT `fk_inventory` FOREIGN KEY (`item_id`) REFERENCES `inventory` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_package` FOREIGN KEY (`package_id`) REFERENCES `package` (`package_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `packagefeature`
--
ALTER TABLE `packagefeature`
  ADD CONSTRAINT `packagefeature_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `package` (`package_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `paymentphase`
--
ALTER TABLE `paymentphase`
  ADD CONSTRAINT `paymentphase_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `payment_log`
--
ALTER TABLE `payment_log`
  ADD CONSTRAINT `payment_log_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `payment_log_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`);

--
-- Constraints for table `personaldetails`
--
ALTER TABLE `personaldetails`
  ADD CONSTRAINT `personaldetails_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `post_topic`
--
ALTER TABLE `post_topic`
  ADD CONSTRAINT `post_topic_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_topic_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pre_projects`
--
ALTER TABLE `pre_projects`
  ADD CONSTRAINT `pre_projects_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_features`
--
ALTER TABLE `product_features`
  ADD CONSTRAINT `product_features_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `fk_project_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_project_pre_project` FOREIGN KEY (`pre_project_id`) REFERENCES `pre_projects` (`pre_project_id`),
  ADD CONSTRAINT `project_ibfk_1` FOREIGN KEY (`quotation_id`) REFERENCES `customerquotation` (`quotation_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `project_ibfk_2` FOREIGN KEY (`custom_package_id`) REFERENCES `custompackage` (`custom_package_id`) ON UPDATE CASCADE;

--
-- Constraints for table `projectphase`
--
ALTER TABLE `projectphase`
  ADD CONSTRAINT `projectphase_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`pre_project_id`) REFERENCES `pre_projects` (`pre_project_id`),
  ADD CONSTRAINT `projects_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `projects_ibfk_3` FOREIGN KEY (`quotation_id`) REFERENCES `reviewed_quotations` (`review_id`),
  ADD CONSTRAINT `projects_ibfk_4` FOREIGN KEY (`agreement_id`) REFERENCES `project_agreements` (`agreement_id`);

--
-- Constraints for table `project_agreements`
--
ALTER TABLE `project_agreements`
  ADD CONSTRAINT `project_agreements_ibfk_1` FOREIGN KEY (`review_id`) REFERENCES `reviewed_quotations` (`review_id`),
  ADD CONSTRAINT `project_agreements_ibfk_2` FOREIGN KEY (`coordinator_signature_id`) REFERENCES `coordinator_signatures` (`signature_id`);

--
-- Constraints for table `project_bankslips`
--
ALTER TABLE `project_bankslips`
  ADD CONSTRAINT `project_bankslips_ibfk_1` FOREIGN KEY (`projectpayment_id`) REFERENCES `project_payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_certificates`
--
ALTER TABLE `project_certificates`
  ADD CONSTRAINT `project_certificates_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`),
  ADD CONSTRAINT `project_certificates_ibfk_2` FOREIGN KEY (`engineer_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `project_payments`
--
ALTER TABLE `project_payments`
  ADD CONSTRAINT `project_payments_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviewed_quotations`
--
ALTER TABLE `reviewed_quotations`
  ADD CONSTRAINT `reviewed_quotations_ibfk_1` FOREIGN KEY (`quotation_id`) REFERENCES `customerquotation` (`quotation_id`);

--
-- Constraints for table `reviewed_quotation_equipment`
--
ALTER TABLE `reviewed_quotation_equipment`
  ADD CONSTRAINT `reviewed_quotation_equipment_ibfk_1` FOREIGN KEY (`review_id`) REFERENCES `reviewed_quotations` (`review_id`),
  ADD CONSTRAINT `reviewed_quotation_equipment_ibfk_2` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`);

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`),
  ADD CONSTRAINT `services_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `services_ibfk_3` FOREIGN KEY (`pre_project_id`) REFERENCES `pre_projects` (`pre_project_id`);

--
-- Constraints for table `site_visits`
--
ALTER TABLE `site_visits`
  ADD CONSTRAINT `site_visits_ibfk_1` FOREIGN KEY (`pre_project_id`) REFERENCES `pre_projects` (`pre_project_id`);

--
-- Constraints for table `slip_download`
--
ALTER TABLE `slip_download`
  ADD CONSTRAINT `slip_download_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `store_orders`
--
ALTER TABLE `store_orders`
  ADD CONSTRAINT `store_orders_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `store_orders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `store_payments`
--
ALTER TABLE `store_payments`
  ADD CONSTRAINT `store_payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `store_orders` (`id`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
