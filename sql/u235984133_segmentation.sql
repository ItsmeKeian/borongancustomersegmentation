-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 25, 2026 at 06:21 PM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u235984133_segmentation`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `acc_id` int(11) NOT NULL,
  `role` enum('Admin','Establishment') NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`acc_id`, `role`, `username`, `password`) VALUES
(5, 'Admin', 'admin', '$2y$10$F2dqNGKiePHKMu6B6RnpquEFltw8iZIi0BrxqpwNNDsif7Mtl4fMC'),
(8, 'Admin', 'keiangacillos', '$2y$10$puM4gwkuxb5XAnoRts.6U.L2sp4x6zERVYzyzi3HYCZZu2NI2sxUW');

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `campaign_sid` int(11) NOT NULL,
  `establishment` varchar(150) NOT NULL,
  `campaign_name` varchar(100) NOT NULL,
  `target_segment` varchar(50) NOT NULL,
  `channel` varchar(50) NOT NULL,
  `message` varchar(150) NOT NULL,
  `schedule_time` datetime NOT NULL,
  `status` varchar(50) NOT NULL,
  `sent_count` int(11) DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`campaign_sid`, `establishment`, `campaign_name`, `target_segment`, `channel`, `message`, `schedule_time`, `status`, `sent_count`, `created_at`) VALUES
(35, 'Tata Tea\'s Coffee Shop', 'TESTING FOR EMAIL', 'Professionals', 'email', 'test', '2025-10-13 16:40:00', 'Sent', 1, '2025-10-13 16:40:53'),
(37, 'Tata Tea\'s Coffee Shop', 'SSSSS', 'Professionals', 'sms', 'SS', '2025-10-13 16:41:00', 'Sent', 1, '2025-10-13 16:41:38'),
(38, 'Watson\'s', 'Flash Sale 50% OFF', 'all', 'email', '50% OFF on your total for every 5 items you purchase in our store!', '2025-10-15 16:06:00', 'Sent', 0, '2025-10-15 16:06:50'),
(52, 'Tata Tea\'s Coffee Shop', 'TESTING FOR SENDING SMS', 'TEST SEGMENT FOR SMS', 'sms', 'TEST', '2025-10-16 13:56:00', 'Sent', 0, '2025-10-16 13:56:32'),
(53, 'Tata Tea\'s Coffee Shop', 'TESTING FOR SENDING SMS', 'Professionals', 'email', 'test', '2025-10-15 14:12:00', 'Sent', 1, '2025-10-16 14:12:34'),
(54, 'Tata Tea\'s Coffee Shop', 'TESTING', 'TEST SEGMENT FOR SMS', 'sms', 'HELLOOO KEIAN THIS IS TESTING ONLY', '2025-10-16 15:18:00', 'Sent', 1, '2025-10-16 15:18:57'),
(55, 'Tata Tea\'s Coffee Shop', 'SENDING TEST CAMPAIGN', 'all', 'sms', 'HELLO, THIS IS ONLY TESTING FOR THE SMS \n\nThank You!', '2025-10-16 15:25:00', 'Sent', 15, '2025-10-16 15:25:36'),
(60, 'Tata Tea\'s Coffee Shop', 'HELLO THIS IS', 'OTHERS', 'sms', 'TEST', '2025-10-16 16:20:00', 'Sent', 6, '2025-10-16 16:20:30'),
(61, 'Tata Tea\'s Coffee Shop', 'HELLO THIS IS ssss', 'OTHERS', 'sms', 'tessssst', '2025-10-16 16:25:00', 'Sent', 6, '2025-10-16 16:21:52'),
(62, 'Tata Tea', 'PROMO COFFEE', 'Professionals', 'email', 'udhudijskjeedhh', '2025-10-30 17:30:00', 'Sent', 3, '2025-10-30 17:29:50'),
(63, 'Tata Tea', 'PROMO COFFEE', 'Professionals', 'email', 'udhudijskjeedhh', '2025-10-30 17:30:00', 'Sent', 3, '2025-10-30 17:30:00'),
(64, 'TATA TEA - Borongan', 'Free Cheese Burger', 'all', 'email', 'We are giving free cheese burgers to the first 5 customers who will visit our Store tomorrow', '2025-12-03 22:59:00', 'Sent', 6, '2025-12-03 22:59:42'),
(65, 'TATA TEA - Borongan', 'Free Cheese Burger', 'all', 'email', 'We are giving free cheese burgers to the first 5 customers who will visit our Store tomorrow', '2025-12-03 22:59:00', 'Sent', 6, '2025-12-03 22:59:59'),
(66, 'TATA TEA - Borongan', 'Free Cheese Burger', 'all', 'email', 'We are giving free cheese burgers to the first 5 customers who will visit our Store tomorrow', '2025-12-03 22:59:00', 'Sent', 6, '2025-12-03 23:00:15'),
(67, 'TATA TEA - Borongan', 'Free Cheese Burger', 'all', 'email', 'We are giving free cheese burgers to the first 5 customers who will visit our Store tomorrow', '2025-12-03 22:59:00', 'Sent', 6, '2025-12-03 23:00:42'),
(71, 'TATA TEA - Borongan', 'Free Cheese Burger', 'PROFESSIONALS', 'email', 'Free burgers', '2025-12-18 15:43:00', 'Sent', 6, '2025-12-18 15:43:10'),
(72, 'TATA TEA - Borongan', 'Free Cheese Burger', 'PROFESSIONALS', 'email', 'Free burgers', '2025-12-18 15:43:00', 'Sent', 6, '2025-12-18 15:43:27'),
(73, 'TATA TEA - Borongan', 'Free Cheese Burger', 'PROFESSIONALS', 'email', 'Free burgers', '2025-12-18 15:43:00', 'Sent', 6, '2025-12-18 15:43:45'),
(74, 'TATA TEA - Borongan', 'Free Cheese Burger', 'PROFESSIONALS', 'email', 'Free burgers', '2025-12-18 15:43:00', 'Sent', 6, '2025-12-18 15:44:01'),
(75, 'TATA TEA - Borongan', 'Free Cheese Burger', 'PROFESSIONALS', 'email', 'Free burgers', '2025-12-18 15:43:00', 'Sent', 6, '2025-12-18 15:44:18'),
(76, 'TATA TEA - Borongan', 'Free Cheese Burger', 'PROFESSIONALS', 'email', 'Free burgers', '2025-12-18 15:43:00', 'Sent', 6, '2025-12-18 15:44:36'),
(77, 'TATA TEA - Borongan', 'Free Cheese Burger', 'PROFESSIONALS', 'email', 'Free burgers', '2025-12-18 15:43:00', 'Sent', 6, '2025-12-18 15:44:53'),
(78, 'Tata Tea\'s Coffee Shop', 'TEST LIVE', 'ADULTS', 'email', 'AMBOTSAIMO ADULT', '2026-01-06 22:46:00', 'sent', 1, '2026-01-06 14:46:35'),
(79, 'TATA TEA - Borongan', 'Free Burger Madness', 'STUDENTS', 'email', 'wfjsfiygdfouqgfuw', '2026-01-07 21:46:00', 'sent', 0, '2026-01-07 13:46:08'),
(80, 'TATA TEA - Borongan', 'Free Burger Madness', 'STUDENTS', 'email', 'wfjsfiygdfouqgfuw', '2026-01-07 21:46:00', 'sent', 0, '2026-01-07 13:46:32'),
(81, 'TATA TEA - Borongan', 'Free Burger Madness', 'STUDENTS', 'email', 'wfjsfiygdfouqgfuw;knk', '2026-01-07 21:46:00', 'sent', 0, '2026-01-07 13:46:54'),
(82, 'TATA TEA - Borongan', 'Free Burger Madness', 'STUDENTS', 'email', 'wfjsfiygdfouqgfuw;knk', '2026-01-07 21:46:00', 'sent', 0, '2026-01-07 13:47:18');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_sid` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `location` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `total_spent` decimal(10,2) NOT NULL,
  `purchase_count` int(11) NOT NULL,
  `segment` varchar(50) NOT NULL,
  `establishment` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `occupation` varchar(100) DEFAULT NULL,
  `estimated_income` decimal(10,2) DEFAULT NULL,
  `education` varchar(100) DEFAULT NULL,
  `is_loyal` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_sid`, `full_name`, `age`, `gender`, `location`, `email`, `phone`, `total_spent`, `purchase_count`, `segment`, `establishment`, `created_at`, `occupation`, `estimated_income`, `education`, `is_loyal`) VALUES
(1, 'Keian', 25, 'Male', 'Amantacop', 'dawnbringerriven02@gmail.com', '211', 531.00, 4, 'Families', 'Tata Tea\'s Coffee Shop', '2025-12-09 03:51:00', 'PROGRAMMER', 20000.00, 'College', 1),
(2, 'Goerge', 24, 'Male', 'Amantacop', 'gacilloskeian02@gmail.com', '211', 1645.00, 16, 'Families', 'Tata Tea\'s Coffee Shop', '2025-11-12 03:52:00', 'PROGRAMMER', 2500.00, 'College', 1),
(3, 'Lira', 24, 'Female', 'Ando', 'Lirra@gmail.com', '2323232', 671.00, 6, 'Families', 'Tata Tea\'s Coffee Shop', '2025-11-13 03:52:00', 'Student', 10000.00, 'High School', 1),
(4, 'NEKNEK', 24, 'Male', 'Ando', 'Lirra@gmail.com', '2323232', 462.00, 1, 'TESTingssss', 'TESTING BUSINESS', '2025-12-09 03:57:00', 'Student', 10000.00, 'High School', 0),
(8, 'Atasha Ofelia', 21, 'Female', 'Benowangan', 'atashaofelia@gmail.com', '09662237309', 104.00, 5, 'Others', 'Tata Tea\'s Coffee Shop', '2025-12-11 15:04:00', 'N/A', 10000.00, 'High School Graduate', 1),
(9, 'Atasha Palada', 22, 'Female', 'Cabong', 'Atashapalada13@gmail.com', '09865215421', 14060.00, 18, 'Select Segment', 'TATA TEA - Borongan', '2025-12-11 07:23:00', 'Student', 0.00, 'College', 1),
(10, 'Glenda Grey', 24, 'Female', 'Cabong', 'gert23@gmail.com', '09567823561', 5628.00, 9, 'Select Segment', 'TATA TEA - Borongan', '2025-12-01 08:27:00', 'Student', 0.00, 'College', 1),
(11, 'Lourdes Alde', 25, 'Female', 'Santa Fe', 'lourdes@gmail.com', '09567823561', 3737.00, 12, 'Select Segment', 'TATA TEA - Borongan', '2025-10-16 11:30:00', 'Student', 2500.00, 'College', 1),
(13, 'Mike Borac', 25, 'Male', 'Surok', 'Mikeborac24@gmail.com', '09685214565', 7352.00, 9, 'Select Segment', 'TATA TEA - Borongan', '2025-10-09 09:14:00', 'Student', 5000.00, 'College', 1),
(14, 'Agatha Fem', 20, 'Female', 'Calico-an', 'agatha19@gmail.com', '09567845687', 2518.00, 5, 'Select Segment', 'TATA TEA - Borongan', '2025-11-14 14:26:00', 'Student', 0.00, 'College', 1),
(20, 'Aljon Alday', 22, 'Male', 'Tabunan', 'aljonalday2003@gmail.com', '09613424549', 25896.00, 32, 'Select Segment', 'TATA TEA - Borongan', '2025-12-12 13:41:37', 'Student', 25000.00, 'College', 1),
(21, 'Mike Salamangca', 29, 'Male', 'Surok', 'mike1@gmail.com', '09863254159', 207.00, 1, 'Select Segment', 'TATA TEA - Borongan', '2025-12-12 17:49:26', 'Engineer', 1000.00, 'College Graduate', 0),
(22, 'Benny Botom', 22, 'Male', 'Bato', 'benny12@gmail.com', '0974682972', 447.00, 1, 'Select Segment', 'TATA TEA - Borongan', '2025-12-12 19:29:00', 'Students', 0.00, 'College', 0),
(23, 'FOR TEST ONLY', 24, 'Male', 'Ando', 'dawnbringerriven02@gmail.com', '2323232', 20.00, 1, 'Families', 'Tata Tea\'s Coffee Shop', '2025-12-13 08:25:00', 'Student', 10000.00, 'College', 0),
(36, 'Miko Bajado', 16, 'Male', 'Balud', 'miko23@gmail.com', '096785643725', 823.00, 2, 'Select Segment', 'TATA TEA - Borongan', '2026-01-07 21:57:00', 'Student', 0.00, 'High School', 0),
(37, 'Winmar Escoto', 45, 'Male', 'Campesao', 'winmarescoto69@gmail.com', '0967854676', 453.00, 2, 'Select Segment', 'TATA TEA - Borongan', '2026-01-07 09:58:00', 'Contractor', 30000.00, 'College Graduate', 0),
(38, 'Rommel Bula', 62, 'Male', 'Santa Fe', 'memel6@gmail.com', '09567854676', 525.00, 1, 'Select Segment', 'TATA TEA - Borongan', '2026-01-07 12:24:00', 'None', 60000.00, 'None', 0),
(39, 'Leoncio Arago Jr', 19, 'Male', 'Bayobay', 'banoy123@gmail.com', '0925675689', 138.00, 1, 'Select Segment', 'TATA TEA - Borongan', '2026-01-07 14:26:00', 'Student', 0.00, 'College', 0),
(40, 'naur test', 23, 'Male', 'Amantacop', 'dawnbringerriven02@gmail.com', '2323232', 0.00, 0, 'Select Segment', 'Tata Tea\'s Coffee Shop', '2026-01-08 06:25:00', 'Student', 10000.00, 'College Graduate', 0),
(41, 'Berto anacio', 21, 'Male', 'Siha', 'bert23@gmail.com', '09267854792', 0.00, 0, 'Select Segment', 'TATA TEA - Borongan', '2026-01-08 10:39:00', 'Student', 0.00, 'College', 0),
(42, 'Shanie Hereras', 25, 'Female', 'Maypangdan', 'shaniehereras', '0926789567', 0.00, 0, 'Select Segment', 'TATA TEA - Borongan', '2026-01-03 10:51:00', 'Student', 5400.00, 'College Graduate', 0);

-- --------------------------------------------------------

--
-- Table structure for table `establishment`
--

CREATE TABLE `establishment` (
  `establishment_sid` int(11) NOT NULL,
  `business_name` varchar(100) NOT NULL,
  `business_type` varchar(100) NOT NULL,
  `owners_name` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` varchar(150) NOT NULL,
  `date_time` datetime DEFAULT current_timestamp(),
  `password` varchar(250) NOT NULL,
  `confirmpassword` varchar(50) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT NULL,
  `last_bot_reply` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `establishment`
--

INSERT INTO `establishment` (`establishment_sid`, `business_name`, `business_type`, `owners_name`, `email`, `contact`, `address`, `date_time`, `password`, `confirmpassword`, `last_login`, `status`, `last_bot_reply`) VALUES
(1, 'Tata Tea\'s Coffee Shop', 'Food & Beverage', 'Keian C. Gacillos', 'dawnbringerriven02@gmail.com', '2122233', 'BORONGAN CITY', '2025-06-13 09:57:00', '$2y$10$dwvZkNQJEz7sGlLP5boQBOSGBsyU8jF/lwyiXNzpk.p9vNtP2z0.u', '', '2026-01-08 06:25:50', 'Active', '2025-11-28 00:34:56'),
(18, 'Tata Tea', 'Coffee & Milktea Shop', 'Aljon', 'aljonalday2003@gmail.com', '09974217525', 'Maydolong Eastern Samar', '2025-10-17 16:51:00', '$2y$10$z/F02BFpQCMCDUSQ5YYpa.eN1i/VL9kdHXM6nSbtBeispn9gM6NyW', '12345678', '2025-12-18 13:21:12', 'Inactive', NULL),
(19, 'Puregold', 'Grocery', 'Roxane S.Concan', 'concanroxane1@gmail.com', '09753019375', 'Borongan city', '2025-10-17 17:17:00', '$2y$10$18s8L9aODk3AkIfKZpbekeC0BcN2BnBSxfOuw0FR2rJPTcOJmpmIO', '$2y$10$18s8L9aODk3AkIfKZpbekeC0BcN2BnBSxfOuw0FR2rJ', '2025-10-17 17:20:44', 'Inactive', NULL),
(20, 'Bubble bee', 'Food & Beverage', 'Mary Ann P. Ambil', 'ambilmeann6@gmail.com', '09517482469', 'Borongan City', '2025-10-17 17:18:00', '$2y$10$8SqJ1xY67uA6sIvejddo8OMr2MQVsS2TKJZ90plTUgrLpbx9N6QYS', '$2y$10$8SqJ1xY67uA6sIvejddo8OMr2MQVsS2TKJZ90plTUgr', '2025-10-17 17:20:28', 'Inactive', NULL),
(23, 'TATA TEA - Borongan', 'Bubble Tea Shop', 'Aljon Alday', 'aldayaljon16@gmail.com', '09974217525', 'Maydolong E. Samar', '2025-12-03 19:46:00', '$2y$10$yW0Y207sTji4.LQQEnf1iOBujT/FhxKQsTkgh3fHEXCG8qBXWkWLy', '', '2026-01-12 12:45:41', 'Active', '2025-12-13 14:29:52'),
(24, 'NEW WORLD CAFE', 'F&B', 'Lirra Calim', 'lirracalim@gmail.com', '09662237309', 'Songco, Borongan City', '2025-12-15 20:52:00', '$2y$10$dR8n0qgk4t526S5tw6kVYutPmfd10O4ade3lqYhD2iatTS4vbl/LS', '$2y$10$dR8n0qgk4t526S5tw6kVYutPmfd10O4ade3lqYhD2ia', '2025-12-15 20:58:48', 'Inactive', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_logins`
--

CREATE TABLE `failed_logins` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `establishment_name` varchar(255) DEFAULT NULL,
  `attempts` int(11) DEFAULT 0,
  `last_attempt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `failed_logins`
--

INSERT INTO `failed_logins` (`id`, `username`, `establishment_name`, `attempts`, `last_attempt`) VALUES
(2, '1@gmail.com', 'Tata Tea\'s Coffee Shop', 5, '2025-12-18 11:22:13'),
(4, 'lira@gmail.com', 'Business Name', 0, '2025-10-15 21:20:47'),
(6, 'Lirra@gmail.com', 'Unknown Establishment', 1, '2025-10-15 16:57:10'),
(7, 'Admin1', 'Unknown Establishment', 2, '2025-10-17 09:03:06'),
(8, 'admin1 123456', 'Unknown Establishment', 1, '2025-10-04 20:17:55'),
(16, 'admin@admin', 'Unknown Establishment', 1, '2025-10-11 21:32:43'),
(17, 'bagaindocgem2@gmail.com', 'Unknown Establishment', 1, '2025-10-11 23:03:53'),
(18, 'Sjddd', 'Unknown Establishment', 1, '2025-10-12 02:55:17'),
(19, 'aa', 'Unknown Establishment', 2, '2025-10-12 20:29:16'),
(21, 'admin@gmail.com', 'Unknown Establishment', 1, '2025-10-13 16:22:16'),
(26, 'concanroxane1@gmail.com', 'Unknown Establishment', 1, '2025-10-17 16:41:32'),
(33, 'lira@gmail.com', 'Unknown Establishment', 3, '2025-10-17 16:41:19'),
(38, 'aljonalday2003@gmail.com', 'Unknown Establishment', 3, '2025-10-17 16:28:18'),
(46, 'admin', 'Unknown Establishment', 4, '2025-12-11 15:51:30'),
(51, 'ellamaeapura7@gmail.com', 'Unknown Establishment', 2, '2025-10-17 17:21:56'),
(53, 'aljonalday2003@gmail.com', 'Tata Tea', 0, '2025-12-18 13:21:12'),
(54, 'asdasd', 'Unknown Establishment', 5, '2025-11-07 12:36:14'),
(64, 'sample@gmail.com', 'Unknown Establishment', 5, '2025-11-07 12:35:31'),
(74, 'qwe', 'Unknown Establishment', 5, '2025-11-07 12:36:18'),
(79, '123123', 'Unknown Establishment', 5, '2025-11-07 12:36:22'),
(84, '123123a', 'Unknown Establishment', 5, '2025-11-07 12:36:24'),
(89, '123123ad', 'Unknown Establishment', 5, '2025-11-07 12:36:27'),
(94, '123123adx', 'Unknown Establishment', 5, '2025-11-07 12:36:31'),
(99, '123123adx1', 'Unknown Establishment', 5, '2025-11-07 12:36:33'),
(104, '123123adx1asad', 'Unknown Establishment', 1, '2025-11-07 12:52:25'),
(110, 'fakeuser', 'Unknown Establishment', 5, '2025-11-07 13:02:33'),
(115, '2@gmail.com', 'Unknown Establishment', 1, '2025-12-17 20:31:41'),
(122, 'aljonalday16@gamil.com', 'Unknown Establishment', 4, '2025-12-05 15:09:59'),
(126, 'aljonalday16@gmail.com', 'Unknown Establishment', 1, '2025-12-17 20:32:35'),
(132, 'aldayaljon16@gmail.com ', 'TATA TEA - Borongan', 0, '2026-01-12 12:45:41'),
(148, '\'', 'Unknown Establishment', 3, '2025-12-17 10:34:45'),
(151, '`', 'Unknown Establishment', 1, '2025-12-17 10:16:53'),
(152, 'admin\' or 1=1--', 'Unknown Establishment', 3, '2025-12-17 10:17:08'),
(158, '\'OR\'1\'=\'1', 'Unknown Establishment', 1, '2025-12-17 12:21:43'),
(170, 'aldayaljon16@gmail.com', 'CAFE BON', 3, '2025-12-18 12:45:19'),
(192, 'dawnbringerriven02@gmail.com', 'Tata Tea\'s Coffee Shop', 0, '2026-01-08 06:25:50'),
(195, '1@gmail.com', 'Unknown Establishment', 2, '2026-01-06 12:15:59');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventory_id` int(11) NOT NULL,
  `establishment` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` enum('Available','Out of Stock') DEFAULT 'Available',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventory_id`, `establishment`, `item_name`, `price`, `quantity`, `status`, `created_at`) VALUES
(4, 'Tata Tea\'s Coffee Shop', 'BREAD', 22.00, 6, 'Available', '2025-12-08 23:09:50'),
(5, 'Tata Tea\'s Coffee Shop', 'Cake', 44.00, 15, 'Available', '2025-12-08 23:09:59'),
(6, 'Tata Tea\'s Coffee Shop', 'Ham', 20.00, 29, 'Available', '2025-12-08 23:30:23'),
(7, 'Tata Tea\'s Coffee Shop', 'Hotdog', 25.00, 0, 'Out of Stock', '2025-12-08 23:30:31'),
(8, 'Tata Tea\'s Coffee Shop', 'French Fries', 180.00, 16, 'Available', '2025-12-09 02:05:13'),
(9, 'TESTING BUSINESS', 'ham', 22.00, 34, 'Available', '2025-12-09 03:57:55'),
(12, 'Tata Tea\'s Coffee Shop', 'ham3', 23.00, 123, 'Available', '2025-12-09 05:16:06'),
(13, 'Tata Tea\'s Coffee Shop', 'ham4', 23.00, 11, 'Available', '2025-12-09 05:16:12'),
(14, 'Tata Tea\'s Coffee Shop', 'ham5', 12.00, 0, 'Available', '2025-12-09 05:16:22'),
(15, 'Tata Tea\'s Coffee Shop', 'bread1', 23.00, 2, 'Available', '2025-12-09 05:16:32'),
(16, 'Tata Tea\'s Coffee Shop', 'bred1', 23.00, 44, 'Available', '2025-12-09 05:16:38'),
(18, 'Tata Tea\'s Coffee Shop', 'NEW ITEM', 22.00, 6, 'Available', '2025-12-09 21:02:39'),
(19, 'TESTING BUSINESS', 'BREAD', 22.00, 34, 'Available', '2025-12-11 19:35:54'),
(21, 'TATA TEA - Borongan', 'Matcha Milk Tea', 69.00, 119, 'Available', '2025-12-11 19:44:32'),
(22, 'TATA TEA - Borongan', 'Classic Creamcheese ', 89.00, 63, 'Available', '2025-12-11 19:45:30'),
(23, 'TATA TEA - Borongan', 'Blueberry Fruit Tea', 89.00, 69, 'Available', '2025-12-11 19:46:45'),
(24, 'TATA TEA - Borongan', 'Brown Sugar Milk', 99.00, 82, 'Available', '2025-12-11 19:47:24'),
(25, 'TATA TEA - Borongan', 'Cookies & Cream Oreo Frappe', 129.00, 38, 'Available', '2025-12-11 19:48:15'),
(26, 'TATA TEA - Borongan', 'Egg Waffle Original', 69.00, 93, 'Available', '2025-12-11 19:48:58'),
(27, 'TATA TEA - Borongan', 'Sour Cream Fries', 59.00, 97, 'Available', '2025-12-11 19:49:59'),
(28, 'TATA TEA - Borongan', 'Classic Fries', 59.00, 78, 'Available', '2025-12-11 19:50:47'),
(29, 'TATA TEA - Borongan', 'Chicken Burger', 139.00, 86, 'Available', '2025-12-11 19:51:31'),
(30, 'TATA TEA - Borongan', 'Beef Burger', 149.00, 39, 'Available', '2025-12-11 19:51:54'),
(31, 'TATA TEA - Borongan', 'Bacon & Beef Burger', 199.00, 139, 'Available', '2025-12-11 19:52:45'),
(35, 'TATA TEA - Borongan', 'Brown Sugar Milk ', 100.00, 88, 'Available', '2025-12-16 12:45:43');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_daily_stock`
--

CREATE TABLE `inventory_daily_stock` (
  `id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `establishment` varchar(255) NOT NULL,
  `stock_date` date NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_daily_stock`
--

INSERT INTO `inventory_daily_stock` (`id`, `inventory_id`, `establishment`, `stock_date`, `quantity`) VALUES
(1, 4, 'Tata Tea\'s Coffee Shop', '2025-12-17', 6),
(2, 5, 'Tata Tea\'s Coffee Shop', '2025-12-17', 15),
(3, 6, 'Tata Tea\'s Coffee Shop', '2025-12-17', 29),
(4, 7, 'Tata Tea\'s Coffee Shop', '2025-12-17', 0),
(5, 8, 'Tata Tea\'s Coffee Shop', '2025-12-17', 16),
(6, 9, 'TESTING BUSINESS', '2025-12-17', 34),
(7, 12, 'Tata Tea\'s Coffee Shop', '2025-12-17', 123),
(8, 13, 'Tata Tea\'s Coffee Shop', '2025-12-17', 11),
(9, 14, 'Tata Tea\'s Coffee Shop', '2025-12-17', 0),
(10, 15, 'Tata Tea\'s Coffee Shop', '2025-12-17', 4),
(11, 16, 'Tata Tea\'s Coffee Shop', '2025-12-17', 44),
(12, 18, 'Tata Tea\'s Coffee Shop', '2025-12-17', 6),
(13, 19, 'TESTING BUSINESS', '2025-12-17', 34),
(14, 21, 'TATA TEA - Borongan', '2025-12-17', 135),
(15, 22, 'TATA TEA - Borongan', '2025-12-17', 63),
(16, 23, 'TATA TEA - Borongan', '2025-12-17', 76),
(17, 24, 'TATA TEA - Borongan', '2025-12-17', 84),
(18, 25, 'TATA TEA - Borongan', '2025-12-17', 45),
(19, 26, 'TATA TEA - Borongan', '2025-12-17', 99),
(20, 27, 'TATA TEA - Borongan', '2025-12-17', 97),
(21, 28, 'TATA TEA - Borongan', '2025-12-17', 81),
(22, 29, 'TATA TEA - Borongan', '2025-12-17', 86),
(23, 30, 'TATA TEA - Borongan', '2025-12-17', 47),
(24, 31, 'TATA TEA - Borongan', '2025-12-17', 95),
(25, 35, 'TATA TEA - Borongan', '2025-12-17', 100),
(32, 4, 'Tata Tea\'s Coffee Shop', '2025-12-18', 6),
(33, 5, 'Tata Tea\'s Coffee Shop', '2025-12-18', 15),
(34, 6, 'Tata Tea\'s Coffee Shop', '2025-12-18', 29),
(35, 7, 'Tata Tea\'s Coffee Shop', '2025-12-18', 0),
(36, 8, 'Tata Tea\'s Coffee Shop', '2025-12-18', 16),
(37, 9, 'TESTING BUSINESS', '2025-12-18', 34),
(38, 12, 'Tata Tea\'s Coffee Shop', '2025-12-18', 123),
(39, 13, 'Tata Tea\'s Coffee Shop', '2025-12-18', 11),
(40, 14, 'Tata Tea\'s Coffee Shop', '2025-12-18', 0),
(41, 15, 'Tata Tea\'s Coffee Shop', '2025-12-18', 4),
(42, 16, 'Tata Tea\'s Coffee Shop', '2025-12-18', 44),
(43, 18, 'Tata Tea\'s Coffee Shop', '2025-12-18', 6),
(44, 19, 'TESTING BUSINESS', '2025-12-18', 34),
(45, 21, 'TATA TEA - Borongan', '2025-12-18', 135),
(46, 22, 'TATA TEA - Borongan', '2025-12-18', 63),
(47, 23, 'TATA TEA - Borongan', '2025-12-18', 76),
(48, 24, 'TATA TEA - Borongan', '2025-12-18', 84),
(49, 25, 'TATA TEA - Borongan', '2025-12-18', 45),
(50, 26, 'TATA TEA - Borongan', '2025-12-18', 99),
(51, 27, 'TATA TEA - Borongan', '2025-12-18', 97),
(52, 28, 'TATA TEA - Borongan', '2025-12-18', 81),
(53, 29, 'TATA TEA - Borongan', '2025-12-18', 86),
(54, 30, 'TATA TEA - Borongan', '2025-12-18', 47),
(55, 31, 'TATA TEA - Borongan', '2025-12-18', 95),
(56, 35, 'TATA TEA - Borongan', '2025-12-18', 100),
(63, 4, 'Tata Tea\'s Coffee Shop', '2025-12-19', 6),
(64, 5, 'Tata Tea\'s Coffee Shop', '2025-12-19', 15),
(65, 6, 'Tata Tea\'s Coffee Shop', '2025-12-19', 29),
(66, 7, 'Tata Tea\'s Coffee Shop', '2025-12-19', 0),
(67, 8, 'Tata Tea\'s Coffee Shop', '2025-12-19', 16),
(68, 9, 'TESTING BUSINESS', '2025-12-19', 34),
(69, 12, 'Tata Tea\'s Coffee Shop', '2025-12-19', 123),
(70, 13, 'Tata Tea\'s Coffee Shop', '2025-12-19', 11),
(71, 14, 'Tata Tea\'s Coffee Shop', '2025-12-19', 0),
(72, 15, 'Tata Tea\'s Coffee Shop', '2025-12-19', 4),
(73, 16, 'Tata Tea\'s Coffee Shop', '2025-12-19', 44),
(74, 18, 'Tata Tea\'s Coffee Shop', '2025-12-19', 6),
(75, 19, 'TESTING BUSINESS', '2025-12-19', 34),
(76, 21, 'TATA TEA - Borongan', '2025-12-19', 135),
(77, 22, 'TATA TEA - Borongan', '2025-12-19', 63),
(78, 23, 'TATA TEA - Borongan', '2025-12-19', 74),
(79, 24, 'TATA TEA - Borongan', '2025-12-19', 82),
(80, 25, 'TATA TEA - Borongan', '2025-12-19', 45),
(81, 26, 'TATA TEA - Borongan', '2025-12-19', 99),
(82, 27, 'TATA TEA - Borongan', '2025-12-19', 97),
(83, 28, 'TATA TEA - Borongan', '2025-12-19', 81),
(84, 29, 'TATA TEA - Borongan', '2025-12-19', 86),
(85, 30, 'TATA TEA - Borongan', '2025-12-19', 46),
(86, 31, 'TATA TEA - Borongan', '2025-12-19', 99),
(87, 35, 'TATA TEA - Borongan', '2025-12-19', 88),
(94, 4, 'Tata Tea\'s Coffee Shop', '2025-12-27', 6),
(95, 5, 'Tata Tea\'s Coffee Shop', '2025-12-27', 15),
(96, 6, 'Tata Tea\'s Coffee Shop', '2025-12-27', 29),
(97, 7, 'Tata Tea\'s Coffee Shop', '2025-12-27', 0),
(98, 8, 'Tata Tea\'s Coffee Shop', '2025-12-27', 16),
(99, 9, 'TESTING BUSINESS', '2025-12-27', 34),
(100, 12, 'Tata Tea\'s Coffee Shop', '2025-12-27', 123),
(101, 13, 'Tata Tea\'s Coffee Shop', '2025-12-27', 11),
(102, 14, 'Tata Tea\'s Coffee Shop', '2025-12-27', 0),
(103, 15, 'Tata Tea\'s Coffee Shop', '2025-12-27', 4),
(104, 16, 'Tata Tea\'s Coffee Shop', '2025-12-27', 44),
(105, 18, 'Tata Tea\'s Coffee Shop', '2025-12-27', 6),
(106, 19, 'TESTING BUSINESS', '2025-12-27', 34),
(107, 21, 'TATA TEA - Borongan', '2025-12-27', 135),
(108, 22, 'TATA TEA - Borongan', '2025-12-27', 63),
(109, 23, 'TATA TEA - Borongan', '2025-12-27', 74),
(110, 24, 'TATA TEA - Borongan', '2025-12-27', 82),
(111, 25, 'TATA TEA - Borongan', '2025-12-27', 45),
(112, 26, 'TATA TEA - Borongan', '2025-12-27', 99),
(113, 27, 'TATA TEA - Borongan', '2025-12-27', 97),
(114, 28, 'TATA TEA - Borongan', '2025-12-27', 81),
(115, 29, 'TATA TEA - Borongan', '2025-12-27', 86),
(116, 30, 'TATA TEA - Borongan', '2025-12-27', 46),
(117, 31, 'TATA TEA - Borongan', '2025-12-27', 99),
(118, 35, 'TATA TEA - Borongan', '2025-12-27', 88),
(125, 4, 'Tata Tea\'s Coffee Shop', '2025-12-28', 6),
(126, 5, 'Tata Tea\'s Coffee Shop', '2025-12-28', 15),
(127, 6, 'Tata Tea\'s Coffee Shop', '2025-12-28', 29),
(128, 7, 'Tata Tea\'s Coffee Shop', '2025-12-28', 0),
(129, 8, 'Tata Tea\'s Coffee Shop', '2025-12-28', 16),
(130, 9, 'TESTING BUSINESS', '2025-12-28', 34),
(131, 12, 'Tata Tea\'s Coffee Shop', '2025-12-28', 123),
(132, 13, 'Tata Tea\'s Coffee Shop', '2025-12-28', 11),
(133, 14, 'Tata Tea\'s Coffee Shop', '2025-12-28', 0),
(134, 15, 'Tata Tea\'s Coffee Shop', '2025-12-28', 4),
(135, 16, 'Tata Tea\'s Coffee Shop', '2025-12-28', 44),
(136, 18, 'Tata Tea\'s Coffee Shop', '2025-12-28', 6),
(137, 19, 'TESTING BUSINESS', '2025-12-28', 34),
(138, 21, 'TATA TEA - Borongan', '2025-12-28', 135),
(139, 22, 'TATA TEA - Borongan', '2025-12-28', 63),
(140, 23, 'TATA TEA - Borongan', '2025-12-28', 74),
(141, 24, 'TATA TEA - Borongan', '2025-12-28', 82),
(142, 25, 'TATA TEA - Borongan', '2025-12-28', 45),
(143, 26, 'TATA TEA - Borongan', '2025-12-28', 99),
(144, 27, 'TATA TEA - Borongan', '2025-12-28', 97),
(145, 28, 'TATA TEA - Borongan', '2025-12-28', 81),
(146, 29, 'TATA TEA - Borongan', '2025-12-28', 86),
(147, 30, 'TATA TEA - Borongan', '2025-12-28', 46),
(148, 31, 'TATA TEA - Borongan', '2025-12-28', 99),
(149, 35, 'TATA TEA - Borongan', '2025-12-28', 88),
(156, 4, 'Tata Tea\'s Coffee Shop', '2025-12-29', 6),
(157, 5, 'Tata Tea\'s Coffee Shop', '2025-12-29', 15),
(158, 6, 'Tata Tea\'s Coffee Shop', '2025-12-29', 29),
(159, 7, 'Tata Tea\'s Coffee Shop', '2025-12-29', 0),
(160, 8, 'Tata Tea\'s Coffee Shop', '2025-12-29', 16),
(161, 9, 'TESTING BUSINESS', '2025-12-29', 34),
(162, 12, 'Tata Tea\'s Coffee Shop', '2025-12-29', 123),
(163, 13, 'Tata Tea\'s Coffee Shop', '2025-12-29', 11),
(164, 14, 'Tata Tea\'s Coffee Shop', '2025-12-29', 0),
(165, 15, 'Tata Tea\'s Coffee Shop', '2025-12-29', 4),
(166, 16, 'Tata Tea\'s Coffee Shop', '2025-12-29', 44),
(167, 18, 'Tata Tea\'s Coffee Shop', '2025-12-29', 6),
(168, 19, 'TESTING BUSINESS', '2025-12-29', 34),
(169, 21, 'TATA TEA - Borongan', '2025-12-29', 135),
(170, 22, 'TATA TEA - Borongan', '2025-12-29', 63),
(171, 23, 'TATA TEA - Borongan', '2025-12-29', 74),
(172, 24, 'TATA TEA - Borongan', '2025-12-29', 82),
(173, 25, 'TATA TEA - Borongan', '2025-12-29', 45),
(174, 26, 'TATA TEA - Borongan', '2025-12-29', 99),
(175, 27, 'TATA TEA - Borongan', '2025-12-29', 97),
(176, 28, 'TATA TEA - Borongan', '2025-12-29', 81),
(177, 29, 'TATA TEA - Borongan', '2025-12-29', 86),
(178, 30, 'TATA TEA - Borongan', '2025-12-29', 46),
(179, 31, 'TATA TEA - Borongan', '2025-12-29', 99),
(180, 35, 'TATA TEA - Borongan', '2025-12-29', 88),
(187, 4, 'Tata Tea\'s Coffee Shop', '2025-12-30', 6),
(188, 5, 'Tata Tea\'s Coffee Shop', '2025-12-30', 15),
(189, 6, 'Tata Tea\'s Coffee Shop', '2025-12-30', 29),
(190, 7, 'Tata Tea\'s Coffee Shop', '2025-12-30', 0),
(191, 8, 'Tata Tea\'s Coffee Shop', '2025-12-30', 16),
(192, 9, 'TESTING BUSINESS', '2025-12-30', 34),
(193, 12, 'Tata Tea\'s Coffee Shop', '2025-12-30', 123),
(194, 13, 'Tata Tea\'s Coffee Shop', '2025-12-30', 11),
(195, 14, 'Tata Tea\'s Coffee Shop', '2025-12-30', 0),
(196, 15, 'Tata Tea\'s Coffee Shop', '2025-12-30', 4),
(197, 16, 'Tata Tea\'s Coffee Shop', '2025-12-30', 44),
(198, 18, 'Tata Tea\'s Coffee Shop', '2025-12-30', 6),
(199, 19, 'TESTING BUSINESS', '2025-12-30', 34),
(200, 21, 'TATA TEA - Borongan', '2025-12-30', 135),
(201, 22, 'TATA TEA - Borongan', '2025-12-30', 63),
(202, 23, 'TATA TEA - Borongan', '2025-12-30', 74),
(203, 24, 'TATA TEA - Borongan', '2025-12-30', 82),
(204, 25, 'TATA TEA - Borongan', '2025-12-30', 45),
(205, 26, 'TATA TEA - Borongan', '2025-12-30', 99),
(206, 27, 'TATA TEA - Borongan', '2025-12-30', 97),
(207, 28, 'TATA TEA - Borongan', '2025-12-30', 81),
(208, 29, 'TATA TEA - Borongan', '2025-12-30', 86),
(209, 30, 'TATA TEA - Borongan', '2025-12-30', 46),
(210, 31, 'TATA TEA - Borongan', '2025-12-30', 99),
(211, 35, 'TATA TEA - Borongan', '2025-12-30', 88),
(218, 4, 'Tata Tea\'s Coffee Shop', '2025-12-31', 6),
(219, 5, 'Tata Tea\'s Coffee Shop', '2025-12-31', 15),
(220, 6, 'Tata Tea\'s Coffee Shop', '2025-12-31', 29),
(221, 7, 'Tata Tea\'s Coffee Shop', '2025-12-31', 0),
(222, 8, 'Tata Tea\'s Coffee Shop', '2025-12-31', 16),
(223, 9, 'TESTING BUSINESS', '2025-12-31', 34),
(224, 12, 'Tata Tea\'s Coffee Shop', '2025-12-31', 123),
(225, 13, 'Tata Tea\'s Coffee Shop', '2025-12-31', 11),
(226, 14, 'Tata Tea\'s Coffee Shop', '2025-12-31', 0),
(227, 15, 'Tata Tea\'s Coffee Shop', '2025-12-31', 4),
(228, 16, 'Tata Tea\'s Coffee Shop', '2025-12-31', 44),
(229, 18, 'Tata Tea\'s Coffee Shop', '2025-12-31', 6),
(230, 19, 'TESTING BUSINESS', '2025-12-31', 34),
(231, 21, 'TATA TEA - Borongan', '2025-12-31', 135),
(232, 22, 'TATA TEA - Borongan', '2025-12-31', 63),
(233, 23, 'TATA TEA - Borongan', '2025-12-31', 74),
(234, 24, 'TATA TEA - Borongan', '2025-12-31', 82),
(235, 25, 'TATA TEA - Borongan', '2025-12-31', 45),
(236, 26, 'TATA TEA - Borongan', '2025-12-31', 99),
(237, 27, 'TATA TEA - Borongan', '2025-12-31', 97),
(238, 28, 'TATA TEA - Borongan', '2025-12-31', 81),
(239, 29, 'TATA TEA - Borongan', '2025-12-31', 86),
(240, 30, 'TATA TEA - Borongan', '2025-12-31', 46),
(241, 31, 'TATA TEA - Borongan', '2025-12-31', 99),
(242, 35, 'TATA TEA - Borongan', '2025-12-31', 88),
(249, 4, 'Tata Tea\'s Coffee Shop', '2026-01-01', 6),
(250, 5, 'Tata Tea\'s Coffee Shop', '2026-01-01', 15),
(251, 6, 'Tata Tea\'s Coffee Shop', '2026-01-01', 29),
(252, 7, 'Tata Tea\'s Coffee Shop', '2026-01-01', 0),
(253, 8, 'Tata Tea\'s Coffee Shop', '2026-01-01', 16),
(254, 9, 'TESTING BUSINESS', '2026-01-01', 34),
(255, 12, 'Tata Tea\'s Coffee Shop', '2026-01-01', 123),
(256, 13, 'Tata Tea\'s Coffee Shop', '2026-01-01', 11),
(257, 14, 'Tata Tea\'s Coffee Shop', '2026-01-01', 0),
(258, 15, 'Tata Tea\'s Coffee Shop', '2026-01-01', 4),
(259, 16, 'Tata Tea\'s Coffee Shop', '2026-01-01', 44),
(260, 18, 'Tata Tea\'s Coffee Shop', '2026-01-01', 6),
(261, 19, 'TESTING BUSINESS', '2026-01-01', 34),
(262, 21, 'TATA TEA - Borongan', '2026-01-01', 135),
(263, 22, 'TATA TEA - Borongan', '2026-01-01', 63),
(264, 23, 'TATA TEA - Borongan', '2026-01-01', 74),
(265, 24, 'TATA TEA - Borongan', '2026-01-01', 82),
(266, 25, 'TATA TEA - Borongan', '2026-01-01', 45),
(267, 26, 'TATA TEA - Borongan', '2026-01-01', 99),
(268, 27, 'TATA TEA - Borongan', '2026-01-01', 97),
(269, 28, 'TATA TEA - Borongan', '2026-01-01', 81),
(270, 29, 'TATA TEA - Borongan', '2026-01-01', 86),
(271, 30, 'TATA TEA - Borongan', '2026-01-01', 46),
(272, 31, 'TATA TEA - Borongan', '2026-01-01', 99),
(273, 35, 'TATA TEA - Borongan', '2026-01-01', 88),
(280, 4, 'Tata Tea\'s Coffee Shop', '2026-01-02', 6),
(281, 5, 'Tata Tea\'s Coffee Shop', '2026-01-02', 15),
(282, 6, 'Tata Tea\'s Coffee Shop', '2026-01-02', 29),
(283, 7, 'Tata Tea\'s Coffee Shop', '2026-01-02', 0),
(284, 8, 'Tata Tea\'s Coffee Shop', '2026-01-02', 16),
(285, 9, 'TESTING BUSINESS', '2026-01-02', 34),
(286, 12, 'Tata Tea\'s Coffee Shop', '2026-01-02', 123),
(287, 13, 'Tata Tea\'s Coffee Shop', '2026-01-02', 11),
(288, 14, 'Tata Tea\'s Coffee Shop', '2026-01-02', 0),
(289, 15, 'Tata Tea\'s Coffee Shop', '2026-01-02', 4),
(290, 16, 'Tata Tea\'s Coffee Shop', '2026-01-02', 44),
(291, 18, 'Tata Tea\'s Coffee Shop', '2026-01-02', 6),
(292, 19, 'TESTING BUSINESS', '2026-01-02', 34),
(293, 21, 'TATA TEA - Borongan', '2026-01-02', 135),
(294, 22, 'TATA TEA - Borongan', '2026-01-02', 63),
(295, 23, 'TATA TEA - Borongan', '2026-01-02', 74),
(296, 24, 'TATA TEA - Borongan', '2026-01-02', 82),
(297, 25, 'TATA TEA - Borongan', '2026-01-02', 45),
(298, 26, 'TATA TEA - Borongan', '2026-01-02', 99),
(299, 27, 'TATA TEA - Borongan', '2026-01-02', 97),
(300, 28, 'TATA TEA - Borongan', '2026-01-02', 81),
(301, 29, 'TATA TEA - Borongan', '2026-01-02', 86),
(302, 30, 'TATA TEA - Borongan', '2026-01-02', 46),
(303, 31, 'TATA TEA - Borongan', '2026-01-02', 99),
(304, 35, 'TATA TEA - Borongan', '2026-01-02', 88),
(311, 4, 'Tata Tea\'s Coffee Shop', '2026-01-03', 6),
(312, 5, 'Tata Tea\'s Coffee Shop', '2026-01-03', 15),
(313, 6, 'Tata Tea\'s Coffee Shop', '2026-01-03', 29),
(314, 7, 'Tata Tea\'s Coffee Shop', '2026-01-03', 0),
(315, 8, 'Tata Tea\'s Coffee Shop', '2026-01-03', 16),
(316, 9, 'TESTING BUSINESS', '2026-01-03', 34),
(317, 12, 'Tata Tea\'s Coffee Shop', '2026-01-03', 123),
(318, 13, 'Tata Tea\'s Coffee Shop', '2026-01-03', 11),
(319, 14, 'Tata Tea\'s Coffee Shop', '2026-01-03', 0),
(320, 15, 'Tata Tea\'s Coffee Shop', '2026-01-03', 4),
(321, 16, 'Tata Tea\'s Coffee Shop', '2026-01-03', 44),
(322, 18, 'Tata Tea\'s Coffee Shop', '2026-01-03', 6),
(323, 19, 'TESTING BUSINESS', '2026-01-03', 34),
(324, 21, 'TATA TEA - Borongan', '2026-01-03', 135),
(325, 22, 'TATA TEA - Borongan', '2026-01-03', 63),
(326, 23, 'TATA TEA - Borongan', '2026-01-03', 74),
(327, 24, 'TATA TEA - Borongan', '2026-01-03', 82),
(328, 25, 'TATA TEA - Borongan', '2026-01-03', 45),
(329, 26, 'TATA TEA - Borongan', '2026-01-03', 99),
(330, 27, 'TATA TEA - Borongan', '2026-01-03', 97),
(331, 28, 'TATA TEA - Borongan', '2026-01-03', 81),
(332, 29, 'TATA TEA - Borongan', '2026-01-03', 86),
(333, 30, 'TATA TEA - Borongan', '2026-01-03', 46),
(334, 31, 'TATA TEA - Borongan', '2026-01-03', 99),
(335, 35, 'TATA TEA - Borongan', '2026-01-03', 88),
(342, 4, 'Tata Tea\'s Coffee Shop', '2026-01-04', 6),
(343, 5, 'Tata Tea\'s Coffee Shop', '2026-01-04', 15),
(344, 6, 'Tata Tea\'s Coffee Shop', '2026-01-04', 29),
(345, 7, 'Tata Tea\'s Coffee Shop', '2026-01-04', 0),
(346, 8, 'Tata Tea\'s Coffee Shop', '2026-01-04', 16),
(347, 9, 'TESTING BUSINESS', '2026-01-04', 34),
(348, 12, 'Tata Tea\'s Coffee Shop', '2026-01-04', 123),
(349, 13, 'Tata Tea\'s Coffee Shop', '2026-01-04', 11),
(350, 14, 'Tata Tea\'s Coffee Shop', '2026-01-04', 0),
(351, 15, 'Tata Tea\'s Coffee Shop', '2026-01-04', 4),
(352, 16, 'Tata Tea\'s Coffee Shop', '2026-01-04', 44),
(353, 18, 'Tata Tea\'s Coffee Shop', '2026-01-04', 6),
(354, 19, 'TESTING BUSINESS', '2026-01-04', 34),
(355, 21, 'TATA TEA - Borongan', '2026-01-04', 135),
(356, 22, 'TATA TEA - Borongan', '2026-01-04', 63),
(357, 23, 'TATA TEA - Borongan', '2026-01-04', 74),
(358, 24, 'TATA TEA - Borongan', '2026-01-04', 82),
(359, 25, 'TATA TEA - Borongan', '2026-01-04', 45),
(360, 26, 'TATA TEA - Borongan', '2026-01-04', 99),
(361, 27, 'TATA TEA - Borongan', '2026-01-04', 97),
(362, 28, 'TATA TEA - Borongan', '2026-01-04', 81),
(363, 29, 'TATA TEA - Borongan', '2026-01-04', 86),
(364, 30, 'TATA TEA - Borongan', '2026-01-04', 46),
(365, 31, 'TATA TEA - Borongan', '2026-01-04', 99),
(366, 35, 'TATA TEA - Borongan', '2026-01-04', 88),
(373, 4, 'Tata Tea\'s Coffee Shop', '2026-01-05', 6),
(374, 5, 'Tata Tea\'s Coffee Shop', '2026-01-05', 15),
(375, 6, 'Tata Tea\'s Coffee Shop', '2026-01-05', 29),
(376, 7, 'Tata Tea\'s Coffee Shop', '2026-01-05', 0),
(377, 8, 'Tata Tea\'s Coffee Shop', '2026-01-05', 16),
(378, 9, 'TESTING BUSINESS', '2026-01-05', 34),
(379, 12, 'Tata Tea\'s Coffee Shop', '2026-01-05', 123),
(380, 13, 'Tata Tea\'s Coffee Shop', '2026-01-05', 11),
(381, 14, 'Tata Tea\'s Coffee Shop', '2026-01-05', 0),
(382, 15, 'Tata Tea\'s Coffee Shop', '2026-01-05', 4),
(383, 16, 'Tata Tea\'s Coffee Shop', '2026-01-05', 44),
(384, 18, 'Tata Tea\'s Coffee Shop', '2026-01-05', 6),
(385, 19, 'TESTING BUSINESS', '2026-01-05', 34),
(386, 21, 'TATA TEA - Borongan', '2026-01-05', 135),
(387, 22, 'TATA TEA - Borongan', '2026-01-05', 63),
(388, 23, 'TATA TEA - Borongan', '2026-01-05', 74),
(389, 24, 'TATA TEA - Borongan', '2026-01-05', 82),
(390, 25, 'TATA TEA - Borongan', '2026-01-05', 45),
(391, 26, 'TATA TEA - Borongan', '2026-01-05', 99),
(392, 27, 'TATA TEA - Borongan', '2026-01-05', 97),
(393, 28, 'TATA TEA - Borongan', '2026-01-05', 81),
(394, 29, 'TATA TEA - Borongan', '2026-01-05', 86),
(395, 30, 'TATA TEA - Borongan', '2026-01-05', 46),
(396, 31, 'TATA TEA - Borongan', '2026-01-05', 99),
(397, 35, 'TATA TEA - Borongan', '2026-01-05', 88),
(404, 4, 'Tata Tea\'s Coffee Shop', '2026-01-06', 6),
(405, 5, 'Tata Tea\'s Coffee Shop', '2026-01-06', 15),
(406, 6, 'Tata Tea\'s Coffee Shop', '2026-01-06', 29),
(407, 7, 'Tata Tea\'s Coffee Shop', '2026-01-06', 0),
(408, 8, 'Tata Tea\'s Coffee Shop', '2026-01-06', 16),
(409, 9, 'TESTING BUSINESS', '2026-01-06', 34),
(410, 12, 'Tata Tea\'s Coffee Shop', '2026-01-06', 123),
(411, 13, 'Tata Tea\'s Coffee Shop', '2026-01-06', 11),
(412, 14, 'Tata Tea\'s Coffee Shop', '2026-01-06', 0),
(413, 15, 'Tata Tea\'s Coffee Shop', '2026-01-06', 4),
(414, 16, 'Tata Tea\'s Coffee Shop', '2026-01-06', 44),
(415, 18, 'Tata Tea\'s Coffee Shop', '2026-01-06', 6),
(416, 19, 'TESTING BUSINESS', '2026-01-06', 34),
(417, 21, 'TATA TEA - Borongan', '2026-01-06', 135),
(418, 22, 'TATA TEA - Borongan', '2026-01-06', 63),
(419, 23, 'TATA TEA - Borongan', '2026-01-06', 74),
(420, 24, 'TATA TEA - Borongan', '2026-01-06', 82),
(421, 25, 'TATA TEA - Borongan', '2026-01-06', 45),
(422, 26, 'TATA TEA - Borongan', '2026-01-06', 99),
(423, 27, 'TATA TEA - Borongan', '2026-01-06', 97),
(424, 28, 'TATA TEA - Borongan', '2026-01-06', 81),
(425, 29, 'TATA TEA - Borongan', '2026-01-06', 86),
(426, 30, 'TATA TEA - Borongan', '2026-01-06', 46),
(427, 31, 'TATA TEA - Borongan', '2026-01-06', 99),
(428, 35, 'TATA TEA - Borongan', '2026-01-06', 88),
(435, 4, 'Tata Tea\'s Coffee Shop', '2026-01-07', 6),
(436, 5, 'Tata Tea\'s Coffee Shop', '2026-01-07', 15),
(437, 6, 'Tata Tea\'s Coffee Shop', '2026-01-07', 29),
(438, 7, 'Tata Tea\'s Coffee Shop', '2026-01-07', 0),
(439, 8, 'Tata Tea\'s Coffee Shop', '2026-01-07', 16),
(440, 9, 'TESTING BUSINESS', '2026-01-07', 34),
(441, 12, 'Tata Tea\'s Coffee Shop', '2026-01-07', 123),
(442, 13, 'Tata Tea\'s Coffee Shop', '2026-01-07', 11),
(443, 14, 'Tata Tea\'s Coffee Shop', '2026-01-07', 0),
(444, 15, 'Tata Tea\'s Coffee Shop', '2026-01-07', 4),
(445, 16, 'Tata Tea\'s Coffee Shop', '2026-01-07', 44),
(446, 18, 'Tata Tea\'s Coffee Shop', '2026-01-07', 6),
(447, 19, 'TESTING BUSINESS', '2026-01-07', 34),
(448, 21, 'TATA TEA - Borongan', '2026-01-07', 135),
(449, 22, 'TATA TEA - Borongan', '2026-01-07', 63),
(450, 23, 'TATA TEA - Borongan', '2026-01-07', 74),
(451, 24, 'TATA TEA - Borongan', '2026-01-07', 82),
(452, 25, 'TATA TEA - Borongan', '2026-01-07', 45),
(453, 26, 'TATA TEA - Borongan', '2026-01-07', 99),
(454, 27, 'TATA TEA - Borongan', '2026-01-07', 97),
(455, 28, 'TATA TEA - Borongan', '2026-01-07', 81),
(456, 29, 'TATA TEA - Borongan', '2026-01-07', 86),
(457, 30, 'TATA TEA - Borongan', '2026-01-07', 46),
(458, 31, 'TATA TEA - Borongan', '2026-01-07', 99),
(459, 35, 'TATA TEA - Borongan', '2026-01-07', 88),
(466, 4, 'Tata Tea\'s Coffee Shop', '2026-01-08', 6),
(467, 5, 'Tata Tea\'s Coffee Shop', '2026-01-08', 15),
(468, 6, 'Tata Tea\'s Coffee Shop', '2026-01-08', 29),
(469, 7, 'Tata Tea\'s Coffee Shop', '2026-01-08', 0),
(470, 8, 'Tata Tea\'s Coffee Shop', '2026-01-08', 16),
(471, 9, 'TESTING BUSINESS', '2026-01-08', 34),
(472, 12, 'Tata Tea\'s Coffee Shop', '2026-01-08', 123),
(473, 13, 'Tata Tea\'s Coffee Shop', '2026-01-08', 11),
(474, 14, 'Tata Tea\'s Coffee Shop', '2026-01-08', 0),
(475, 15, 'Tata Tea\'s Coffee Shop', '2026-01-08', 2),
(476, 16, 'Tata Tea\'s Coffee Shop', '2026-01-08', 44),
(477, 18, 'Tata Tea\'s Coffee Shop', '2026-01-08', 6),
(478, 19, 'TESTING BUSINESS', '2026-01-08', 34),
(479, 21, 'TATA TEA - Borongan', '2026-01-08', 125),
(480, 22, 'TATA TEA - Borongan', '2026-01-08', 63),
(481, 23, 'TATA TEA - Borongan', '2026-01-08', 72),
(482, 24, 'TATA TEA - Borongan', '2026-01-08', 82),
(483, 25, 'TATA TEA - Borongan', '2026-01-08', 41),
(484, 26, 'TATA TEA - Borongan', '2026-01-08', 97),
(485, 27, 'TATA TEA - Borongan', '2026-01-08', 97),
(486, 28, 'TATA TEA - Borongan', '2026-01-08', 80),
(487, 29, 'TATA TEA - Borongan', '2026-01-08', 86),
(488, 30, 'TATA TEA - Borongan', '2026-01-08', 41),
(489, 31, 'TATA TEA - Borongan', '2026-01-08', 94),
(490, 35, 'TATA TEA - Borongan', '2026-01-08', 88),
(497, 4, 'Tata Tea\'s Coffee Shop', '2026-01-09', 6),
(498, 5, 'Tata Tea\'s Coffee Shop', '2026-01-09', 15),
(499, 6, 'Tata Tea\'s Coffee Shop', '2026-01-09', 29),
(500, 7, 'Tata Tea\'s Coffee Shop', '2026-01-09', 0),
(501, 8, 'Tata Tea\'s Coffee Shop', '2026-01-09', 16),
(502, 9, 'TESTING BUSINESS', '2026-01-09', 34),
(503, 12, 'Tata Tea\'s Coffee Shop', '2026-01-09', 123),
(504, 13, 'Tata Tea\'s Coffee Shop', '2026-01-09', 11),
(505, 14, 'Tata Tea\'s Coffee Shop', '2026-01-09', 0),
(506, 15, 'Tata Tea\'s Coffee Shop', '2026-01-09', 2),
(507, 16, 'Tata Tea\'s Coffee Shop', '2026-01-09', 44),
(508, 18, 'Tata Tea\'s Coffee Shop', '2026-01-09', 6),
(509, 19, 'TESTING BUSINESS', '2026-01-09', 34),
(510, 21, 'TATA TEA - Borongan', '2026-01-09', 121),
(511, 22, 'TATA TEA - Borongan', '2026-01-09', 63),
(512, 23, 'TATA TEA - Borongan', '2026-01-09', 69),
(513, 24, 'TATA TEA - Borongan', '2026-01-09', 82),
(514, 25, 'TATA TEA - Borongan', '2026-01-09', 38),
(515, 26, 'TATA TEA - Borongan', '2026-01-09', 95),
(516, 27, 'TATA TEA - Borongan', '2026-01-09', 97),
(517, 28, 'TATA TEA - Borongan', '2026-01-09', 78),
(518, 29, 'TATA TEA - Borongan', '2026-01-09', 86),
(519, 30, 'TATA TEA - Borongan', '2026-01-09', 41),
(520, 31, 'TATA TEA - Borongan', '2026-01-09', 94),
(521, 35, 'TATA TEA - Borongan', '2026-01-09', 88),
(528, 4, 'Tata Tea\'s Coffee Shop', '2026-01-10', 6),
(529, 5, 'Tata Tea\'s Coffee Shop', '2026-01-10', 15),
(530, 6, 'Tata Tea\'s Coffee Shop', '2026-01-10', 29),
(531, 7, 'Tata Tea\'s Coffee Shop', '2026-01-10', 0),
(532, 8, 'Tata Tea\'s Coffee Shop', '2026-01-10', 16),
(533, 9, 'TESTING BUSINESS', '2026-01-10', 34),
(534, 12, 'Tata Tea\'s Coffee Shop', '2026-01-10', 123),
(535, 13, 'Tata Tea\'s Coffee Shop', '2026-01-10', 11),
(536, 14, 'Tata Tea\'s Coffee Shop', '2026-01-10', 0),
(537, 15, 'Tata Tea\'s Coffee Shop', '2026-01-10', 2),
(538, 16, 'Tata Tea\'s Coffee Shop', '2026-01-10', 44),
(539, 18, 'Tata Tea\'s Coffee Shop', '2026-01-10', 6),
(540, 19, 'TESTING BUSINESS', '2026-01-10', 34),
(541, 21, 'TATA TEA - Borongan', '2026-01-10', 121),
(542, 22, 'TATA TEA - Borongan', '2026-01-10', 63),
(543, 23, 'TATA TEA - Borongan', '2026-01-10', 69),
(544, 24, 'TATA TEA - Borongan', '2026-01-10', 82),
(545, 25, 'TATA TEA - Borongan', '2026-01-10', 38),
(546, 26, 'TATA TEA - Borongan', '2026-01-10', 95),
(547, 27, 'TATA TEA - Borongan', '2026-01-10', 97),
(548, 28, 'TATA TEA - Borongan', '2026-01-10', 78),
(549, 29, 'TATA TEA - Borongan', '2026-01-10', 86),
(550, 30, 'TATA TEA - Borongan', '2026-01-10', 41),
(551, 31, 'TATA TEA - Borongan', '2026-01-10', 94),
(552, 35, 'TATA TEA - Borongan', '2026-01-10', 88),
(559, 4, 'Tata Tea\'s Coffee Shop', '2026-01-11', 6),
(560, 5, 'Tata Tea\'s Coffee Shop', '2026-01-11', 15),
(561, 6, 'Tata Tea\'s Coffee Shop', '2026-01-11', 29),
(562, 7, 'Tata Tea\'s Coffee Shop', '2026-01-11', 0),
(563, 8, 'Tata Tea\'s Coffee Shop', '2026-01-11', 16),
(564, 9, 'TESTING BUSINESS', '2026-01-11', 34),
(565, 12, 'Tata Tea\'s Coffee Shop', '2026-01-11', 123),
(566, 13, 'Tata Tea\'s Coffee Shop', '2026-01-11', 11),
(567, 14, 'Tata Tea\'s Coffee Shop', '2026-01-11', 0),
(568, 15, 'Tata Tea\'s Coffee Shop', '2026-01-11', 2),
(569, 16, 'Tata Tea\'s Coffee Shop', '2026-01-11', 44),
(570, 18, 'Tata Tea\'s Coffee Shop', '2026-01-11', 6),
(571, 19, 'TESTING BUSINESS', '2026-01-11', 34),
(572, 21, 'TATA TEA - Borongan', '2026-01-11', 121),
(573, 22, 'TATA TEA - Borongan', '2026-01-11', 63),
(574, 23, 'TATA TEA - Borongan', '2026-01-11', 69),
(575, 24, 'TATA TEA - Borongan', '2026-01-11', 82),
(576, 25, 'TATA TEA - Borongan', '2026-01-11', 38),
(577, 26, 'TATA TEA - Borongan', '2026-01-11', 95),
(578, 27, 'TATA TEA - Borongan', '2026-01-11', 97),
(579, 28, 'TATA TEA - Borongan', '2026-01-11', 78),
(580, 29, 'TATA TEA - Borongan', '2026-01-11', 86),
(581, 30, 'TATA TEA - Borongan', '2026-01-11', 41),
(582, 31, 'TATA TEA - Borongan', '2026-01-11', 94),
(583, 35, 'TATA TEA - Borongan', '2026-01-11', 88),
(590, 4, 'Tata Tea\'s Coffee Shop', '2026-01-12', 6),
(591, 5, 'Tata Tea\'s Coffee Shop', '2026-01-12', 15),
(592, 6, 'Tata Tea\'s Coffee Shop', '2026-01-12', 29),
(593, 7, 'Tata Tea\'s Coffee Shop', '2026-01-12', 0),
(594, 8, 'Tata Tea\'s Coffee Shop', '2026-01-12', 16),
(595, 9, 'TESTING BUSINESS', '2026-01-12', 34),
(596, 12, 'Tata Tea\'s Coffee Shop', '2026-01-12', 123),
(597, 13, 'Tata Tea\'s Coffee Shop', '2026-01-12', 11),
(598, 14, 'Tata Tea\'s Coffee Shop', '2026-01-12', 0),
(599, 15, 'Tata Tea\'s Coffee Shop', '2026-01-12', 2),
(600, 16, 'Tata Tea\'s Coffee Shop', '2026-01-12', 44),
(601, 18, 'Tata Tea\'s Coffee Shop', '2026-01-12', 6),
(602, 19, 'TESTING BUSINESS', '2026-01-12', 34),
(603, 21, 'TATA TEA - Borongan', '2026-01-12', 121),
(604, 22, 'TATA TEA - Borongan', '2026-01-12', 63),
(605, 23, 'TATA TEA - Borongan', '2026-01-12', 69),
(606, 24, 'TATA TEA - Borongan', '2026-01-12', 82),
(607, 25, 'TATA TEA - Borongan', '2026-01-12', 38),
(608, 26, 'TATA TEA - Borongan', '2026-01-12', 93),
(609, 27, 'TATA TEA - Borongan', '2026-01-12', 97),
(610, 28, 'TATA TEA - Borongan', '2026-01-12', 78),
(611, 29, 'TATA TEA - Borongan', '2026-01-12', 86),
(612, 30, 'TATA TEA - Borongan', '2026-01-12', 41),
(613, 31, 'TATA TEA - Borongan', '2026-01-12', 89),
(614, 35, 'TATA TEA - Borongan', '2026-01-12', 88),
(621, 4, 'Tata Tea\'s Coffee Shop', '2026-01-13', 6),
(622, 5, 'Tata Tea\'s Coffee Shop', '2026-01-13', 15),
(623, 6, 'Tata Tea\'s Coffee Shop', '2026-01-13', 29),
(624, 7, 'Tata Tea\'s Coffee Shop', '2026-01-13', 0),
(625, 8, 'Tata Tea\'s Coffee Shop', '2026-01-13', 16),
(626, 9, 'TESTING BUSINESS', '2026-01-13', 34),
(627, 12, 'Tata Tea\'s Coffee Shop', '2026-01-13', 123),
(628, 13, 'Tata Tea\'s Coffee Shop', '2026-01-13', 11),
(629, 14, 'Tata Tea\'s Coffee Shop', '2026-01-13', 0),
(630, 15, 'Tata Tea\'s Coffee Shop', '2026-01-13', 2),
(631, 16, 'Tata Tea\'s Coffee Shop', '2026-01-13', 44),
(632, 18, 'Tata Tea\'s Coffee Shop', '2026-01-13', 6),
(633, 19, 'TESTING BUSINESS', '2026-01-13', 34),
(634, 21, 'TATA TEA - Borongan', '2026-01-13', 119),
(635, 22, 'TATA TEA - Borongan', '2026-01-13', 63),
(636, 23, 'TATA TEA - Borongan', '2026-01-13', 69),
(637, 24, 'TATA TEA - Borongan', '2026-01-13', 82),
(638, 25, 'TATA TEA - Borongan', '2026-01-13', 38),
(639, 26, 'TATA TEA - Borongan', '2026-01-13', 93),
(640, 27, 'TATA TEA - Borongan', '2026-01-13', 97),
(641, 28, 'TATA TEA - Borongan', '2026-01-13', 78),
(642, 29, 'TATA TEA - Borongan', '2026-01-13', 86),
(643, 30, 'TATA TEA - Borongan', '2026-01-13', 39),
(644, 31, 'TATA TEA - Borongan', '2026-01-13', 139),
(645, 35, 'TATA TEA - Borongan', '2026-01-13', 88),
(652, 4, 'Tata Tea\'s Coffee Shop', '2026-01-14', 6),
(653, 5, 'Tata Tea\'s Coffee Shop', '2026-01-14', 15),
(654, 6, 'Tata Tea\'s Coffee Shop', '2026-01-14', 29),
(655, 7, 'Tata Tea\'s Coffee Shop', '2026-01-14', 0),
(656, 8, 'Tata Tea\'s Coffee Shop', '2026-01-14', 16),
(657, 9, 'TESTING BUSINESS', '2026-01-14', 34),
(658, 12, 'Tata Tea\'s Coffee Shop', '2026-01-14', 123),
(659, 13, 'Tata Tea\'s Coffee Shop', '2026-01-14', 11),
(660, 14, 'Tata Tea\'s Coffee Shop', '2026-01-14', 0),
(661, 15, 'Tata Tea\'s Coffee Shop', '2026-01-14', 2),
(662, 16, 'Tata Tea\'s Coffee Shop', '2026-01-14', 44),
(663, 18, 'Tata Tea\'s Coffee Shop', '2026-01-14', 6),
(664, 19, 'TESTING BUSINESS', '2026-01-14', 34),
(665, 21, 'TATA TEA - Borongan', '2026-01-14', 119),
(666, 22, 'TATA TEA - Borongan', '2026-01-14', 63),
(667, 23, 'TATA TEA - Borongan', '2026-01-14', 69),
(668, 24, 'TATA TEA - Borongan', '2026-01-14', 82),
(669, 25, 'TATA TEA - Borongan', '2026-01-14', 38),
(670, 26, 'TATA TEA - Borongan', '2026-01-14', 93),
(671, 27, 'TATA TEA - Borongan', '2026-01-14', 97),
(672, 28, 'TATA TEA - Borongan', '2026-01-14', 78),
(673, 29, 'TATA TEA - Borongan', '2026-01-14', 86),
(674, 30, 'TATA TEA - Borongan', '2026-01-14', 39),
(675, 31, 'TATA TEA - Borongan', '2026-01-14', 139),
(676, 35, 'TATA TEA - Borongan', '2026-01-14', 88),
(683, 4, 'Tata Tea\'s Coffee Shop', '2026-01-15', 6),
(684, 5, 'Tata Tea\'s Coffee Shop', '2026-01-15', 15),
(685, 6, 'Tata Tea\'s Coffee Shop', '2026-01-15', 29),
(686, 7, 'Tata Tea\'s Coffee Shop', '2026-01-15', 0),
(687, 8, 'Tata Tea\'s Coffee Shop', '2026-01-15', 16),
(688, 9, 'TESTING BUSINESS', '2026-01-15', 34),
(689, 12, 'Tata Tea\'s Coffee Shop', '2026-01-15', 123),
(690, 13, 'Tata Tea\'s Coffee Shop', '2026-01-15', 11),
(691, 14, 'Tata Tea\'s Coffee Shop', '2026-01-15', 0),
(692, 15, 'Tata Tea\'s Coffee Shop', '2026-01-15', 2),
(693, 16, 'Tata Tea\'s Coffee Shop', '2026-01-15', 44),
(694, 18, 'Tata Tea\'s Coffee Shop', '2026-01-15', 6),
(695, 19, 'TESTING BUSINESS', '2026-01-15', 34),
(696, 21, 'TATA TEA - Borongan', '2026-01-15', 119),
(697, 22, 'TATA TEA - Borongan', '2026-01-15', 63),
(698, 23, 'TATA TEA - Borongan', '2026-01-15', 69),
(699, 24, 'TATA TEA - Borongan', '2026-01-15', 82),
(700, 25, 'TATA TEA - Borongan', '2026-01-15', 38),
(701, 26, 'TATA TEA - Borongan', '2026-01-15', 93),
(702, 27, 'TATA TEA - Borongan', '2026-01-15', 97),
(703, 28, 'TATA TEA - Borongan', '2026-01-15', 78),
(704, 29, 'TATA TEA - Borongan', '2026-01-15', 86),
(705, 30, 'TATA TEA - Borongan', '2026-01-15', 39),
(706, 31, 'TATA TEA - Borongan', '2026-01-15', 139),
(707, 35, 'TATA TEA - Borongan', '2026-01-15', 88),
(714, 4, 'Tata Tea\'s Coffee Shop', '2026-01-16', 6),
(715, 5, 'Tata Tea\'s Coffee Shop', '2026-01-16', 15),
(716, 6, 'Tata Tea\'s Coffee Shop', '2026-01-16', 29),
(717, 7, 'Tata Tea\'s Coffee Shop', '2026-01-16', 0),
(718, 8, 'Tata Tea\'s Coffee Shop', '2026-01-16', 16),
(719, 9, 'TESTING BUSINESS', '2026-01-16', 34),
(720, 12, 'Tata Tea\'s Coffee Shop', '2026-01-16', 123),
(721, 13, 'Tata Tea\'s Coffee Shop', '2026-01-16', 11),
(722, 14, 'Tata Tea\'s Coffee Shop', '2026-01-16', 0),
(723, 15, 'Tata Tea\'s Coffee Shop', '2026-01-16', 2),
(724, 16, 'Tata Tea\'s Coffee Shop', '2026-01-16', 44),
(725, 18, 'Tata Tea\'s Coffee Shop', '2026-01-16', 6),
(726, 19, 'TESTING BUSINESS', '2026-01-16', 34),
(727, 21, 'TATA TEA - Borongan', '2026-01-16', 119),
(728, 22, 'TATA TEA - Borongan', '2026-01-16', 63),
(729, 23, 'TATA TEA - Borongan', '2026-01-16', 69),
(730, 24, 'TATA TEA - Borongan', '2026-01-16', 82),
(731, 25, 'TATA TEA - Borongan', '2026-01-16', 38),
(732, 26, 'TATA TEA - Borongan', '2026-01-16', 93),
(733, 27, 'TATA TEA - Borongan', '2026-01-16', 97),
(734, 28, 'TATA TEA - Borongan', '2026-01-16', 78),
(735, 29, 'TATA TEA - Borongan', '2026-01-16', 86),
(736, 30, 'TATA TEA - Borongan', '2026-01-16', 39),
(737, 31, 'TATA TEA - Borongan', '2026-01-16', 139),
(738, 35, 'TATA TEA - Borongan', '2026-01-16', 88),
(745, 4, 'Tata Tea\'s Coffee Shop', '2026-01-17', 6),
(746, 5, 'Tata Tea\'s Coffee Shop', '2026-01-17', 15),
(747, 6, 'Tata Tea\'s Coffee Shop', '2026-01-17', 29),
(748, 7, 'Tata Tea\'s Coffee Shop', '2026-01-17', 0),
(749, 8, 'Tata Tea\'s Coffee Shop', '2026-01-17', 16),
(750, 9, 'TESTING BUSINESS', '2026-01-17', 34),
(751, 12, 'Tata Tea\'s Coffee Shop', '2026-01-17', 123),
(752, 13, 'Tata Tea\'s Coffee Shop', '2026-01-17', 11),
(753, 14, 'Tata Tea\'s Coffee Shop', '2026-01-17', 0),
(754, 15, 'Tata Tea\'s Coffee Shop', '2026-01-17', 2),
(755, 16, 'Tata Tea\'s Coffee Shop', '2026-01-17', 44),
(756, 18, 'Tata Tea\'s Coffee Shop', '2026-01-17', 6),
(757, 19, 'TESTING BUSINESS', '2026-01-17', 34),
(758, 21, 'TATA TEA - Borongan', '2026-01-17', 119),
(759, 22, 'TATA TEA - Borongan', '2026-01-17', 63),
(760, 23, 'TATA TEA - Borongan', '2026-01-17', 69),
(761, 24, 'TATA TEA - Borongan', '2026-01-17', 82),
(762, 25, 'TATA TEA - Borongan', '2026-01-17', 38),
(763, 26, 'TATA TEA - Borongan', '2026-01-17', 93),
(764, 27, 'TATA TEA - Borongan', '2026-01-17', 97),
(765, 28, 'TATA TEA - Borongan', '2026-01-17', 78),
(766, 29, 'TATA TEA - Borongan', '2026-01-17', 86),
(767, 30, 'TATA TEA - Borongan', '2026-01-17', 39),
(768, 31, 'TATA TEA - Borongan', '2026-01-17', 139),
(769, 35, 'TATA TEA - Borongan', '2026-01-17', 88),
(776, 4, 'Tata Tea\'s Coffee Shop', '2026-01-18', 6),
(777, 5, 'Tata Tea\'s Coffee Shop', '2026-01-18', 15),
(778, 6, 'Tata Tea\'s Coffee Shop', '2026-01-18', 29),
(779, 7, 'Tata Tea\'s Coffee Shop', '2026-01-18', 0),
(780, 8, 'Tata Tea\'s Coffee Shop', '2026-01-18', 16),
(781, 9, 'TESTING BUSINESS', '2026-01-18', 34),
(782, 12, 'Tata Tea\'s Coffee Shop', '2026-01-18', 123),
(783, 13, 'Tata Tea\'s Coffee Shop', '2026-01-18', 11),
(784, 14, 'Tata Tea\'s Coffee Shop', '2026-01-18', 0),
(785, 15, 'Tata Tea\'s Coffee Shop', '2026-01-18', 2),
(786, 16, 'Tata Tea\'s Coffee Shop', '2026-01-18', 44),
(787, 18, 'Tata Tea\'s Coffee Shop', '2026-01-18', 6),
(788, 19, 'TESTING BUSINESS', '2026-01-18', 34),
(789, 21, 'TATA TEA - Borongan', '2026-01-18', 119),
(790, 22, 'TATA TEA - Borongan', '2026-01-18', 63),
(791, 23, 'TATA TEA - Borongan', '2026-01-18', 69),
(792, 24, 'TATA TEA - Borongan', '2026-01-18', 82),
(793, 25, 'TATA TEA - Borongan', '2026-01-18', 38),
(794, 26, 'TATA TEA - Borongan', '2026-01-18', 93),
(795, 27, 'TATA TEA - Borongan', '2026-01-18', 97),
(796, 28, 'TATA TEA - Borongan', '2026-01-18', 78),
(797, 29, 'TATA TEA - Borongan', '2026-01-18', 86),
(798, 30, 'TATA TEA - Borongan', '2026-01-18', 39),
(799, 31, 'TATA TEA - Borongan', '2026-01-18', 139),
(800, 35, 'TATA TEA - Borongan', '2026-01-18', 88),
(807, 4, 'Tata Tea\'s Coffee Shop', '2026-01-19', 6),
(808, 5, 'Tata Tea\'s Coffee Shop', '2026-01-19', 15),
(809, 6, 'Tata Tea\'s Coffee Shop', '2026-01-19', 29),
(810, 7, 'Tata Tea\'s Coffee Shop', '2026-01-19', 0),
(811, 8, 'Tata Tea\'s Coffee Shop', '2026-01-19', 16),
(812, 9, 'TESTING BUSINESS', '2026-01-19', 34),
(813, 12, 'Tata Tea\'s Coffee Shop', '2026-01-19', 123),
(814, 13, 'Tata Tea\'s Coffee Shop', '2026-01-19', 11),
(815, 14, 'Tata Tea\'s Coffee Shop', '2026-01-19', 0),
(816, 15, 'Tata Tea\'s Coffee Shop', '2026-01-19', 2),
(817, 16, 'Tata Tea\'s Coffee Shop', '2026-01-19', 44),
(818, 18, 'Tata Tea\'s Coffee Shop', '2026-01-19', 6),
(819, 19, 'TESTING BUSINESS', '2026-01-19', 34),
(820, 21, 'TATA TEA - Borongan', '2026-01-19', 119),
(821, 22, 'TATA TEA - Borongan', '2026-01-19', 63),
(822, 23, 'TATA TEA - Borongan', '2026-01-19', 69),
(823, 24, 'TATA TEA - Borongan', '2026-01-19', 82),
(824, 25, 'TATA TEA - Borongan', '2026-01-19', 38),
(825, 26, 'TATA TEA - Borongan', '2026-01-19', 93),
(826, 27, 'TATA TEA - Borongan', '2026-01-19', 97),
(827, 28, 'TATA TEA - Borongan', '2026-01-19', 78),
(828, 29, 'TATA TEA - Borongan', '2026-01-19', 86),
(829, 30, 'TATA TEA - Borongan', '2026-01-19', 39),
(830, 31, 'TATA TEA - Borongan', '2026-01-19', 139),
(831, 35, 'TATA TEA - Borongan', '2026-01-19', 88),
(838, 4, 'Tata Tea\'s Coffee Shop', '2026-01-20', 6),
(839, 5, 'Tata Tea\'s Coffee Shop', '2026-01-20', 15),
(840, 6, 'Tata Tea\'s Coffee Shop', '2026-01-20', 29),
(841, 7, 'Tata Tea\'s Coffee Shop', '2026-01-20', 0),
(842, 8, 'Tata Tea\'s Coffee Shop', '2026-01-20', 16),
(843, 9, 'TESTING BUSINESS', '2026-01-20', 34),
(844, 12, 'Tata Tea\'s Coffee Shop', '2026-01-20', 123),
(845, 13, 'Tata Tea\'s Coffee Shop', '2026-01-20', 11),
(846, 14, 'Tata Tea\'s Coffee Shop', '2026-01-20', 0),
(847, 15, 'Tata Tea\'s Coffee Shop', '2026-01-20', 2),
(848, 16, 'Tata Tea\'s Coffee Shop', '2026-01-20', 44),
(849, 18, 'Tata Tea\'s Coffee Shop', '2026-01-20', 6),
(850, 19, 'TESTING BUSINESS', '2026-01-20', 34),
(851, 21, 'TATA TEA - Borongan', '2026-01-20', 119),
(852, 22, 'TATA TEA - Borongan', '2026-01-20', 63),
(853, 23, 'TATA TEA - Borongan', '2026-01-20', 69),
(854, 24, 'TATA TEA - Borongan', '2026-01-20', 82),
(855, 25, 'TATA TEA - Borongan', '2026-01-20', 38),
(856, 26, 'TATA TEA - Borongan', '2026-01-20', 93),
(857, 27, 'TATA TEA - Borongan', '2026-01-20', 97),
(858, 28, 'TATA TEA - Borongan', '2026-01-20', 78),
(859, 29, 'TATA TEA - Borongan', '2026-01-20', 86),
(860, 30, 'TATA TEA - Borongan', '2026-01-20', 39),
(861, 31, 'TATA TEA - Borongan', '2026-01-20', 139),
(862, 35, 'TATA TEA - Borongan', '2026-01-20', 88),
(869, 4, 'Tata Tea\'s Coffee Shop', '2026-01-21', 6),
(870, 5, 'Tata Tea\'s Coffee Shop', '2026-01-21', 15),
(871, 6, 'Tata Tea\'s Coffee Shop', '2026-01-21', 29),
(872, 7, 'Tata Tea\'s Coffee Shop', '2026-01-21', 0),
(873, 8, 'Tata Tea\'s Coffee Shop', '2026-01-21', 16),
(874, 9, 'TESTING BUSINESS', '2026-01-21', 34),
(875, 12, 'Tata Tea\'s Coffee Shop', '2026-01-21', 123),
(876, 13, 'Tata Tea\'s Coffee Shop', '2026-01-21', 11),
(877, 14, 'Tata Tea\'s Coffee Shop', '2026-01-21', 0),
(878, 15, 'Tata Tea\'s Coffee Shop', '2026-01-21', 2),
(879, 16, 'Tata Tea\'s Coffee Shop', '2026-01-21', 44),
(880, 18, 'Tata Tea\'s Coffee Shop', '2026-01-21', 6),
(881, 19, 'TESTING BUSINESS', '2026-01-21', 34),
(882, 21, 'TATA TEA - Borongan', '2026-01-21', 119),
(883, 22, 'TATA TEA - Borongan', '2026-01-21', 63),
(884, 23, 'TATA TEA - Borongan', '2026-01-21', 69),
(885, 24, 'TATA TEA - Borongan', '2026-01-21', 82),
(886, 25, 'TATA TEA - Borongan', '2026-01-21', 38),
(887, 26, 'TATA TEA - Borongan', '2026-01-21', 93),
(888, 27, 'TATA TEA - Borongan', '2026-01-21', 97),
(889, 28, 'TATA TEA - Borongan', '2026-01-21', 78),
(890, 29, 'TATA TEA - Borongan', '2026-01-21', 86),
(891, 30, 'TATA TEA - Borongan', '2026-01-21', 39),
(892, 31, 'TATA TEA - Borongan', '2026-01-21', 139),
(893, 35, 'TATA TEA - Borongan', '2026-01-21', 88),
(900, 4, 'Tata Tea\'s Coffee Shop', '2026-01-22', 6),
(901, 5, 'Tata Tea\'s Coffee Shop', '2026-01-22', 15),
(902, 6, 'Tata Tea\'s Coffee Shop', '2026-01-22', 29),
(903, 7, 'Tata Tea\'s Coffee Shop', '2026-01-22', 0),
(904, 8, 'Tata Tea\'s Coffee Shop', '2026-01-22', 16),
(905, 9, 'TESTING BUSINESS', '2026-01-22', 34),
(906, 12, 'Tata Tea\'s Coffee Shop', '2026-01-22', 123),
(907, 13, 'Tata Tea\'s Coffee Shop', '2026-01-22', 11),
(908, 14, 'Tata Tea\'s Coffee Shop', '2026-01-22', 0),
(909, 15, 'Tata Tea\'s Coffee Shop', '2026-01-22', 2),
(910, 16, 'Tata Tea\'s Coffee Shop', '2026-01-22', 44),
(911, 18, 'Tata Tea\'s Coffee Shop', '2026-01-22', 6),
(912, 19, 'TESTING BUSINESS', '2026-01-22', 34),
(913, 21, 'TATA TEA - Borongan', '2026-01-22', 119),
(914, 22, 'TATA TEA - Borongan', '2026-01-22', 63),
(915, 23, 'TATA TEA - Borongan', '2026-01-22', 69),
(916, 24, 'TATA TEA - Borongan', '2026-01-22', 82),
(917, 25, 'TATA TEA - Borongan', '2026-01-22', 38),
(918, 26, 'TATA TEA - Borongan', '2026-01-22', 93),
(919, 27, 'TATA TEA - Borongan', '2026-01-22', 97),
(920, 28, 'TATA TEA - Borongan', '2026-01-22', 78),
(921, 29, 'TATA TEA - Borongan', '2026-01-22', 86),
(922, 30, 'TATA TEA - Borongan', '2026-01-22', 39),
(923, 31, 'TATA TEA - Borongan', '2026-01-22', 139),
(924, 35, 'TATA TEA - Borongan', '2026-01-22', 88),
(931, 4, 'Tata Tea\'s Coffee Shop', '2026-01-23', 6),
(932, 5, 'Tata Tea\'s Coffee Shop', '2026-01-23', 15),
(933, 6, 'Tata Tea\'s Coffee Shop', '2026-01-23', 29),
(934, 7, 'Tata Tea\'s Coffee Shop', '2026-01-23', 0),
(935, 8, 'Tata Tea\'s Coffee Shop', '2026-01-23', 16),
(936, 9, 'TESTING BUSINESS', '2026-01-23', 34),
(937, 12, 'Tata Tea\'s Coffee Shop', '2026-01-23', 123),
(938, 13, 'Tata Tea\'s Coffee Shop', '2026-01-23', 11),
(939, 14, 'Tata Tea\'s Coffee Shop', '2026-01-23', 0),
(940, 15, 'Tata Tea\'s Coffee Shop', '2026-01-23', 2),
(941, 16, 'Tata Tea\'s Coffee Shop', '2026-01-23', 44),
(942, 18, 'Tata Tea\'s Coffee Shop', '2026-01-23', 6),
(943, 19, 'TESTING BUSINESS', '2026-01-23', 34),
(944, 21, 'TATA TEA - Borongan', '2026-01-23', 119),
(945, 22, 'TATA TEA - Borongan', '2026-01-23', 63),
(946, 23, 'TATA TEA - Borongan', '2026-01-23', 69),
(947, 24, 'TATA TEA - Borongan', '2026-01-23', 82),
(948, 25, 'TATA TEA - Borongan', '2026-01-23', 38),
(949, 26, 'TATA TEA - Borongan', '2026-01-23', 93),
(950, 27, 'TATA TEA - Borongan', '2026-01-23', 97),
(951, 28, 'TATA TEA - Borongan', '2026-01-23', 78),
(952, 29, 'TATA TEA - Borongan', '2026-01-23', 86),
(953, 30, 'TATA TEA - Borongan', '2026-01-23', 39),
(954, 31, 'TATA TEA - Borongan', '2026-01-23', 139),
(955, 35, 'TATA TEA - Borongan', '2026-01-23', 88),
(956, 4, 'Tata Tea\'s Coffee Shop', '2026-01-24', 6),
(957, 5, 'Tata Tea\'s Coffee Shop', '2026-01-24', 15),
(958, 6, 'Tata Tea\'s Coffee Shop', '2026-01-24', 29),
(959, 7, 'Tata Tea\'s Coffee Shop', '2026-01-24', 0),
(960, 8, 'Tata Tea\'s Coffee Shop', '2026-01-24', 16),
(961, 9, 'TESTING BUSINESS', '2026-01-24', 34),
(962, 12, 'Tata Tea\'s Coffee Shop', '2026-01-24', 123),
(963, 13, 'Tata Tea\'s Coffee Shop', '2026-01-24', 11),
(964, 14, 'Tata Tea\'s Coffee Shop', '2026-01-24', 0),
(965, 15, 'Tata Tea\'s Coffee Shop', '2026-01-24', 2),
(966, 16, 'Tata Tea\'s Coffee Shop', '2026-01-24', 44),
(967, 18, 'Tata Tea\'s Coffee Shop', '2026-01-24', 6),
(968, 19, 'TESTING BUSINESS', '2026-01-24', 34),
(969, 21, 'TATA TEA - Borongan', '2026-01-24', 119),
(970, 22, 'TATA TEA - Borongan', '2026-01-24', 63),
(971, 23, 'TATA TEA - Borongan', '2026-01-24', 69),
(972, 24, 'TATA TEA - Borongan', '2026-01-24', 82),
(973, 25, 'TATA TEA - Borongan', '2026-01-24', 38),
(974, 26, 'TATA TEA - Borongan', '2026-01-24', 93),
(975, 27, 'TATA TEA - Borongan', '2026-01-24', 97),
(976, 28, 'TATA TEA - Borongan', '2026-01-24', 78),
(977, 29, 'TATA TEA - Borongan', '2026-01-24', 86),
(978, 30, 'TATA TEA - Borongan', '2026-01-24', 39),
(979, 31, 'TATA TEA - Borongan', '2026-01-24', 139),
(980, 35, 'TATA TEA - Borongan', '2026-01-24', 88),
(987, 4, 'Tata Tea\'s Coffee Shop', '2026-01-25', 6),
(988, 5, 'Tata Tea\'s Coffee Shop', '2026-01-25', 15),
(989, 6, 'Tata Tea\'s Coffee Shop', '2026-01-25', 29),
(990, 7, 'Tata Tea\'s Coffee Shop', '2026-01-25', 0),
(991, 8, 'Tata Tea\'s Coffee Shop', '2026-01-25', 16),
(992, 9, 'TESTING BUSINESS', '2026-01-25', 34),
(993, 12, 'Tata Tea\'s Coffee Shop', '2026-01-25', 123),
(994, 13, 'Tata Tea\'s Coffee Shop', '2026-01-25', 11),
(995, 14, 'Tata Tea\'s Coffee Shop', '2026-01-25', 0),
(996, 15, 'Tata Tea\'s Coffee Shop', '2026-01-25', 2),
(997, 16, 'Tata Tea\'s Coffee Shop', '2026-01-25', 44),
(998, 18, 'Tata Tea\'s Coffee Shop', '2026-01-25', 6),
(999, 19, 'TESTING BUSINESS', '2026-01-25', 34),
(1000, 21, 'TATA TEA - Borongan', '2026-01-25', 119),
(1001, 22, 'TATA TEA - Borongan', '2026-01-25', 63),
(1002, 23, 'TATA TEA - Borongan', '2026-01-25', 69),
(1003, 24, 'TATA TEA - Borongan', '2026-01-25', 82),
(1004, 25, 'TATA TEA - Borongan', '2026-01-25', 38),
(1005, 26, 'TATA TEA - Borongan', '2026-01-25', 93),
(1006, 27, 'TATA TEA - Borongan', '2026-01-25', 97),
(1007, 28, 'TATA TEA - Borongan', '2026-01-25', 78),
(1008, 29, 'TATA TEA - Borongan', '2026-01-25', 86),
(1009, 30, 'TATA TEA - Borongan', '2026-01-25', 39),
(1010, 31, 'TATA TEA - Borongan', '2026-01-25', 139),
(1011, 35, 'TATA TEA - Borongan', '2026-01-25', 88);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `establishment_id` int(11) NOT NULL,
  `sender_type` enum('user','admin','bot') NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `establishment_id`, `sender_type`, `sender_id`, `message`, `is_read`, `created_at`) VALUES
(1, 22, 'user', 22, 'hii', 1, '2025-11-26 12:53:17'),
(2, 22, 'admin', 5, 'hello', 0, '2025-11-26 12:53:30'),
(3, 22, 'admin', 5, 'anoo', 0, '2025-11-26 12:53:57'),
(4, 22, 'admin', 5, 'ahh', 0, '2025-11-26 12:54:02'),
(5, 22, 'admin', 5, 'hahaha', 0, '2025-11-26 12:54:06'),
(6, 22, 'user', 22, 'okay', 1, '2025-11-26 12:57:48'),
(7, 22, 'admin', 5, 'sige po', 0, '2025-11-26 12:57:57'),
(8, 22, 'admin', 5, 'yowwwn', 0, '2025-11-26 12:58:35'),
(9, 22, 'user', 22, 'copy', 1, '2025-11-26 14:48:15'),
(10, 22, 'user', 22, 'hi', 1, '2025-11-26 15:59:32'),
(11, 22, 'user', 22, 'how to login?', 1, '2025-11-26 15:59:35'),
(12, 22, 'bot', NULL, 'To login: click the top-right dropdown and go to Login.', 1, '2025-11-26 15:59:35'),
(13, 22, 'user', 22, 'okay', 1, '2025-11-26 15:59:39'),
(14, 22, 'user', 22, 'noted', 1, '2025-11-26 15:59:41'),
(15, 22, 'user', 22, 'how about the dashboard?', 1, '2025-11-26 15:59:47'),
(16, 22, 'bot', NULL, 'The dashboard shows your statistics.', 1, '2025-11-26 15:59:47'),
(17, 22, 'user', 22, 'what is campaign?', 1, '2025-11-26 16:00:08'),
(18, 22, 'bot', NULL, 'Use the Campaigns page to send SMS or Email.', 1, '2025-11-26 16:00:08'),
(19, 22, 'user', 22, 'how about campaign?', 1, '2025-11-26 16:00:16'),
(20, 22, 'bot', NULL, 'Use the Campaigns page to send SMS or Email.', 1, '2025-11-26 16:00:16'),
(21, 1, 'user', 1, 'hii', 1, '2025-11-27 16:34:56'),
(22, 1, 'bot', NULL, 'I couldn\'t find a direct answer. Your question has been forwarded to admin.', 1, '2025-11-27 16:34:56'),
(23, 1, 'user', 1, 'what is dashboard?', 1, '2025-11-27 16:35:04'),
(24, 1, 'bot', NULL, 'The dashboard shows your statistics.', 1, '2025-11-27 16:35:04'),
(25, 1, 'admin', 5, 'anoman\'', 0, '2025-12-01 07:29:08'),
(26, 1, 'admin', 5, 'ano tim problema?', 0, '2025-12-01 07:29:11'),
(27, 1, 'admin', 5, 'pag search', 0, '2025-12-01 07:29:15'),
(28, 1, 'user', 1, 'dikam maasaha', 1, '2025-12-01 07:29:49'),
(29, 1, 'admin', 5, 'hoy', 0, '2025-12-02 17:46:12'),
(30, 1, 'user', 1, 'yes', 1, '2025-12-02 17:56:15'),
(31, 23, 'user', 23, 'How to log in', 1, '2025-12-13 06:29:52'),
(32, 23, 'user', 23, 'How to add customer?', 1, '2025-12-13 06:30:43'),
(33, 23, 'admin', 5, 'Just go to the Customer Section, click the add customer, fill up all necessary information once done click Create Customer.', 0, '2025-12-13 06:36:27'),
(34, 23, 'user', 23, 'How about adding purchase?', 1, '2025-12-14 01:20:26'),
(35, 23, 'user', 23, 'How to create a purchase record?', 1, '2025-12-14 01:20:41');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `establishment` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date_created` datetime DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `establishment`, `message`, `date_created`, `is_read`) VALUES
(1, 'Tata tea\'s coffee shop', 'HELLOOOO', '2025-11-17 18:56:33', 1),
(2, 'tawe', 'ta', '2025-11-17 19:19:25', 0),
(3, 'Tata tea\'s coffee shop', 'this is for testing only', '2025-11-17 19:19:49', 1),
(4, 'Tata tea\'s coffee shop', 'New customer joined your segment', '2025-11-17 19:40:33', 1),
(6, 'tata tea\'s coffee shop', 'HIGH-RISK CHURN: Customer bobo has not purchased in 14 days. Last purchase: 2025-09-23 14:50:00.', '2025-11-27 01:37:09', 1),
(7, 'tata tea\'s coffee shop', 'HIGH-RISK CHURN: Customer boboss has not purchased in 14 days. Last purchase: 2025-10-05 10:38:00.', '2025-11-27 01:37:09', 1),
(8, 'tata tea\'s coffee shop', 'HIGH-RISK CHURN: Customer carol has not purchased in 14 days. Last purchase: 2025-01-23 16:29:00.', '2025-11-27 01:37:09', 1),
(9, 'tata tea\'s coffee shop', 'HIGH-RISK CHURN: Customer Christian has not purchased in 14 days. Last purchase: 2025-08-22 15:41:00.', '2025-11-27 01:37:09', 1),
(10, 'tata tea\'s coffee shop', 'HIGH-RISK CHURN: Customer Kyy has not purchased in 14 days. Last purchase: 2025-09-23 14:54:00.', '2025-11-27 01:37:09', 1),
(11, 'tata tea\'s coffee shop', 'HIGH-RISK CHURN: Customer Mariles has not purchased in 14 days. Last purchase: 2025-01-17 15:35:00.', '2025-11-27 01:37:09', 1),
(12, 'tata tea\'s coffee shop', 'HIGH-RISK CHURN: Customer sird has not purchased in 14 days. Last purchase: 2025-06-22 15:44:00.', '2025-11-27 01:37:09', 1),
(13, 'tata tea\'s coffee shop', 'HIGH-RISK CHURN: Customer wwww has not purchased in 14 days. Last purchase: 2025-09-25 10:55:00.', '2025-11-27 01:37:09', 1),
(14, 'tata tea\'s coffee shop', 'HIGH-RISK CHURN: Customer wwww11 has not purchased in 14 days. Last purchase: 2025-10-01 20:59:00.', '2025-11-27 01:37:09', 1),
(15, 'tata tea\'s coffee shop', 'HIGH-RISK CHURN: Customer keian has not purchased in 14 days. Last purchase: 2025-10-14 10:01:00.', '2025-11-27 01:37:09', 1),
(16, 'tata tea\'s coffee shop', 'HIGH-RISK CHURN: Customer jonathan has not purchased in 14 days. Last purchase: 2025-10-16 08:42:00.', '2025-11-27 01:37:09', 1),
(17, 'tata tea\'s coffee shop', 'HIGH-RISK CHURN: Customer Mhirz has not purchased in 14 days. Last purchase: 2025-02-12 15:35:00.', '2025-11-27 01:37:09', 1),
(18, 'tata tea\'s coffee shop', 'HIGH-RISK CHURN: Customer Carol has not purchased in 14 days. Last purchase: 2025-08-22 15:43:00.', '2025-11-27 01:37:09', 1),
(19, 'tata tea\'s coffee shop', 'HIGH-RISK CHURN: Customer Marlonis has not purchased in 14 days. Last purchase: 2025-10-14 10:02:00.', '2025-11-27 01:37:09', 1),
(20, 'tata tea\'s coffee shop', 'HIGH-RISK CHURN: Customer NEW TEST has not purchased in 14 days. Last purchase: 2025-10-01 23:41:00.', '2025-11-27 01:37:09', 1),
(21, 'tata tea\'s coffee shop', 'HIGH-RISK CHURN: Customer TEST has not purchased in 14 days. Last purchase: 2025-10-02 20:28:00.', '2025-11-27 01:37:09', 1),
(22, 'tata tea\'s coffee shop', 'HIGH-RISK CHURN: Customer itsme has not purchased in 14 days. Last purchase: 2025-10-14 10:28:00.', '2025-11-27 01:37:09', 1),
(23, 'tata tea\'s coffee shop', 'PRODUCT RISK: Customer keian has not ordered their favorite drink \"w\" in 10 days. Last order: 2025-10-14 10:01:00.', '2025-11-27 01:37:09', 1),
(24, 'tata tea\'s coffee shop', 'PRODUCT RISK: Customer Christian has not ordered their favorite drink \"aweww\" in 10 days. Last order: 2025-10-01 20:59:00.', '2025-11-27 01:37:09', 1),
(25, 'tata tea\'s coffee shop', 'PRODUCT RISK: Customer Carol has not ordered their favorite drink \"bond paper\" in 10 days. Last order: 2025-08-22 15:43:00.', '2025-11-27 01:37:09', 1),
(26, 'tata tea\'s coffee shop', 'PRODUCT RISK: Customer Mhirz has not ordered their favorite drink \"keyboard\" in 10 days. Last order: 2025-02-12 15:35:00.', '2025-11-27 01:37:09', 1),
(27, 'tata tea\'s coffee shop', 'PRODUCT RISK: Customer Marlonis has not ordered their favorite drink \"1\" in 10 days. Last order: 2025-10-14 10:02:00.', '2025-11-27 01:37:09', 1),
(28, 'tata tea\'s coffee shop', 'PRODUCT RISK: Customer NEW TEST has not ordered their favorite drink \"test\" in 10 days. Last order: 2025-10-01 23:41:00.', '2025-11-27 01:37:09', 1),
(29, 'tata tea\'s coffee shop', 'PRODUCT RISK: Customer TEST has not ordered their favorite drink \"test\" in 10 days. Last order: 2025-10-02 20:28:00.', '2025-11-27 01:37:09', 1),
(30, 'tata tea\'s coffee shop', 'PRODUCT RISK: Customer itsme has not ordered their favorite drink \"medicine\" in 10 days. Last order: 2025-10-14 10:28:00.', '2025-11-27 01:37:09', 1),
(31, 'keian store', 'HIGH-RISK CHURN: Customer 1 has not purchased in 14 days. Last purchase: 0000-00-00 00:00:00.', '2025-11-27 01:37:09', 0),
(32, 'keian store', 'HIGH-RISK CHURN: Customer 11 has not purchased in 14 days. Last purchase: 0000-00-00 00:00:00.', '2025-11-27 01:37:09', 0),
(33, 'keian store', 'HIGH-RISK CHURN: Customer 111 has not purchased in 14 days. Last purchase: 0000-00-00 00:00:00.', '2025-11-27 01:37:09', 0),
(34, 'keian store', 'HIGH-RISK CHURN: Customer 22 has not purchased in 14 days. Last purchase: 0000-00-00 00:00:00.', '2025-11-27 01:37:09', 0),
(35, 'keian store', 'HIGH-RISK CHURN: Customer 33 has not purchased in 14 days. Last purchase: 0000-00-00 00:00:00.', '2025-11-27 01:37:09', 0),
(36, 'keian store', 'HIGH-RISK CHURN: Customer 44 has not purchased in 14 days. Last purchase: 0000-00-00 00:00:00.', '2025-11-27 01:37:09', 0),
(37, 'keian store', 'HIGH-RISK CHURN: Customer keianatics has not purchased in 14 days. Last purchase: 2025-08-01 10:14:00.', '2025-11-27 01:37:09', 0),
(38, 'keian store', 'PRODUCT RISK: Customer keianatics has not ordered their favorite drink \"22\" in 10 days. Last order: 0000-00-00 00:00:00.', '2025-11-27 01:37:09', 0),
(39, 'Watson\'s', 'HIGH-RISK CHURN: Customer Aljon C. has not purchased in 14 days. Last purchase: 2025-10-04 20:18:00.', '2025-11-27 01:37:09', 0),
(40, 'Watson\'s', 'HIGH-RISK CHURN: Customer Lirra has not purchased in 14 days. Last purchase: 2025-10-15 15:59:00.', '2025-11-27 01:37:09', 0),
(41, 'Watson\'s', 'PRODUCT RISK: Customer Aljon C. has not ordered their favorite drink \"Clear men shampoo\" in 10 days. Last order: 2025-10-04 20:18:00.', '2025-11-27 01:37:09', 0),
(42, 'Watson\'s', 'PRODUCT RISK: Customer Lirra has not ordered their favorite drink \"Celeteque Sunscreen \" in 10 days. Last order: 2025-10-15 15:59:00.', '2025-11-27 01:37:09', 0),
(43, 'concanroxane1@gmail.com', 'HIGH-RISK CHURN: Customer Roxane S. Concan has not purchased in 14 days. Last purchase: 2025-10-15 17:00:00.', '2025-11-27 01:37:09', 0),
(44, 'concanroxane1@gmail.com', 'HIGH-RISK CHURN: Customer Iris S. Concan has not purchased in 14 days. Last purchase: 2025-10-15 22:03:00.', '2025-11-27 01:37:09', 0),
(45, 'concanroxane1@gmail.com', 'PRODUCT RISK: Customer Roxane S. Concan has not ordered their favorite drink \"Cream silk Conditioner\" in 10 days. Last order: 2025-10-15 17:00:00.', '2025-11-27 01:37:09', 0),
(46, 'concanroxane1@gmail.com', 'PRODUCT RISK: Customer Iris S. Concan has not ordered their favorite drink \"1 doz safeguard \" in 10 days. Last order: 2025-10-15 22:03:00.', '2025-11-27 01:37:09', 0),
(47, 'Tata Tea', 'HIGH-RISK CHURN: Customer Jane Cris has not purchased in 14 days. Last purchase: 2025-10-17 17:01:00.', '2025-11-27 01:37:09', 0),
(48, 'Tata Tea', 'HIGH-RISK CHURN: Customer Lirra Calim has not purchased in 14 days. Last purchase: 2025-10-17 16:59:00.', '2025-11-27 01:37:09', 0),
(49, 'Tata Tea', 'HIGH-RISK CHURN: Customer Aljon has not purchased in 14 days. Last purchase: 2025-10-17 17:02:00.', '2025-11-27 01:37:09', 0),
(50, 'Tata Tea', 'HIGH-RISK CHURN: Customer Mary ann has not purchased in 14 days. Last purchase: 2025-10-17 17:05:00.', '2025-11-27 01:37:09', 0),
(51, 'Tata Tea', 'HIGH-RISK CHURN: Customer Atasha Ofelia has not purchased in 14 days. Last purchase: 2025-10-30 17:50:00.', '2025-11-27 01:37:09', 0),
(52, 'Tata Tea', 'PRODUCT RISK: Customer Lirra Calim has not ordered their favorite drink \"Dark Mocha Milktea\" in 10 days. Last order: 2025-10-17 16:59:00.', '2025-11-27 01:37:09', 0),
(53, 'Tata Tea', 'PRODUCT RISK: Customer Jane Cris has not ordered their favorite drink \"Matcha Latte\" in 10 days. Last order: 2025-10-17 17:01:00.', '2025-11-27 01:37:09', 0),
(54, 'Tata Tea', 'PRODUCT RISK: Customer Aljon has not ordered their favorite drink \"Dark Brewd Coffee \" in 10 days. Last order: 2025-10-17 17:02:00.', '2025-11-27 01:37:09', 0),
(55, 'Tata Tea', 'PRODUCT RISK: Customer Mary ann has not ordered their favorite drink \"Iced Coffee\" in 10 days. Last order: 2025-10-17 17:05:00.', '2025-11-27 01:37:09', 0),
(56, 'Tata Tea', 'PRODUCT RISK: Customer Atasha Ofelia has not ordered their favorite drink \"cocoa\" in 10 days. Last order: 2025-10-30 17:50:00.', '2025-11-27 01:37:09', 0),
(57, 'tata tea\'s coffee shop', 'HIGH-RISK CHURN: Customer keian has not purchased in 14 days. Last purchase: 2025-10-16 23:46:00.', '2025-11-30 09:00:03', 1),
(58, 'tata tea\'s coffee shop', 'PRODUCT RISK: Customer keian has not ordered their favorite drink \"Pancake\" in 10 days. Last order: 2025-10-16 23:46:00.', '2025-11-30 09:00:03', 1),
(59, 'tata tea\'s coffee shop', 'PRODUCT RISK: Customer Christian has not ordered their favorite \"aweww\" in 10 days. Last order: 2025-10-01 20:59:00.', '2025-12-04 09:00:04', 1),
(60, 'tata tea\'s coffee shop', 'PRODUCT RISK: Customer Carol has not ordered their favorite \"bond paper\" in 10 days. Last order: 2025-08-22 15:43:00.', '2025-12-04 09:00:04', 1),
(61, 'tata tea\'s coffee shop', 'PRODUCT RISK: Customer Marlonis has not ordered their favorite \"1\" in 10 days. Last order: 2025-10-14 10:02:00.', '2025-12-04 09:00:04', 1),
(62, 'tata tea\'s coffee shop', 'PRODUCT RISK: Customer NEW TEST has not ordered their favorite \"test\" in 10 days. Last order: 2025-10-01 23:41:00.', '2025-12-04 09:00:04', 1),
(63, 'tata tea\'s coffee shop', 'PRODUCT RISK: Customer TEST has not ordered their favorite \"test\" in 10 days. Last order: 2025-10-02 20:28:00.', '2025-12-04 09:00:04', 1),
(64, 'keian store', 'PRODUCT RISK: Customer keianatics has not ordered their favorite \"22\" in 10 days. Last order: 0000-00-00 00:00:00.', '2025-12-04 09:00:04', 0),
(65, 'Watson\'s', 'PRODUCT RISK: Customer Aljon C. has not ordered their favorite \"Clear men shampoo\" in 10 days. Last order: 2025-10-04 20:18:00.', '2025-12-04 09:00:04', 0),
(66, 'Watson\'s', 'PRODUCT RISK: Customer Lirra has not ordered their favorite \"Celeteque Sunscreen \" in 10 days. Last order: 2025-10-15 15:59:00.', '2025-12-04 09:00:04', 0),
(67, 'concanroxane1@gmail.com', 'PRODUCT RISK: Customer Roxane S. Concan has not ordered their favorite \"Cream silk Conditioner\" in 10 days. Last order: 2025-10-15 17:00:00.', '2025-12-04 09:00:04', 0),
(68, 'concanroxane1@gmail.com', 'PRODUCT RISK: Customer Iris S. Concan has not ordered their favorite \"1 doz safeguard \" in 10 days. Last order: 2025-10-15 22:03:00.', '2025-12-04 09:00:04', 0),
(69, 'Tata Tea', 'PRODUCT RISK: Customer Lirra Calim has not ordered their favorite \"Dark Mocha Milktea\" in 10 days. Last order: 2025-10-17 16:59:00.', '2025-12-04 09:00:04', 0),
(70, 'Tata Tea', 'PRODUCT RISK: Customer Jane Cris has not ordered their favorite \"Matcha Latte\" in 10 days. Last order: 2025-10-17 17:01:00.', '2025-12-04 09:00:04', 0),
(71, 'Tata Tea', 'PRODUCT RISK: Customer Aljon has not ordered their favorite \"Dark Brewd Coffee \" in 10 days. Last order: 2025-10-17 17:02:00.', '2025-12-04 09:00:04', 0),
(72, 'Tata Tea', 'PRODUCT RISK: Customer Mary ann has not ordered their favorite \"Iced Coffee\" in 10 days. Last order: 2025-10-17 17:05:00.', '2025-12-04 09:00:04', 0),
(73, 'Tata Tea', 'PRODUCT RISK: Customer Atasha Ofelia has not ordered their favorite \"cocoa\" in 10 days. Last order: 2025-10-30 17:50:00.', '2025-12-04 09:00:04', 0),
(74, 'TATA TEA - Borongan', 'HIGH-RISK CHURN: Customer Agatha Fem has not purchased in 14 days. Last purchase: 2025-09-10 23:56:00.', '2025-12-04 09:00:04', 1),
(75, 'TATA TEA - Borongan', 'HIGH-RISK CHURN: Customer Esabel Gert has not purchased in 14 days. Last purchase: 2025-09-25 15:06:00.', '2025-12-04 09:00:04', 1),
(76, 'TATA TEA - Borongan', 'HIGH-RISK CHURN: Customer Mikko Boddo has not purchased in 14 days. Last purchase: 2025-09-19 15:11:00.', '2025-12-04 09:00:04', 0),
(77, 'TATA TEA - Borongan', 'PRODUCT RISK: Customer Lourdes Alde has not ordered their favorite \"Cookies & Cream Milk Tea\" in 10 days. Last order: 2025-11-06 16:25:00.', '2025-12-04 09:00:04', 0),
(78, 'TATA TEA - Borongan', 'PRODUCT RISK: Customer Agatha Fem has not ordered their favorite \"Strawberry Milk Tea\" in 10 days. Last order: 2025-09-10 23:56:00.', '2025-12-04 09:00:04', 0),
(79, 'TATA TEA - Borongan', 'PRODUCT RISK: Customer Esabel Gert has not ordered their favorite \"Egg Waffle Original\" in 10 days. Last order: 2025-09-20 12:10:00.', '2025-12-04 09:00:04', 0),
(80, 'TATA TEA - Borongan', 'PRODUCT RISK: Customer Mikko Boddo has not ordered their favorite \"Chicken Burger\" in 10 days. Last order: 2025-09-19 15:11:00.', '2025-12-04 09:00:04', 0),
(81, 'tata tea\'s coffee shop', 'PRODUCT RISK: Customer Goerge has not ordered their favorite \"banana\" in 10 days. Last order: 2025-11-24 23:43:00.', '2025-12-05 09:00:09', 1),
(83, 'tata tea\'s coffee shop', 'HIGH-RISK CHURN: Customer Lira has not purchased in 14 days. Last purchase: 2025-11-22 16:00:00.', '2025-12-07 09:00:03', 1),
(86, 'tata tea\'s coffee shop', 'PRODUCT RISK: Customer ken has not ordered their favorite \"banana\" in 10 days. Last order: 2025-11-27 23:45:00.', '2025-12-08 09:00:04', 1),
(87, 'tata tea\'s coffee shop', 'PRODUCT RISK: Customer Kyy has not ordered their favorite \"banana\" in 10 days. Last order: 2025-11-27 23:45:00.', '2025-12-08 09:00:04', 1),
(88, 'Tata Tea\'s Coffee Shop', 'HIGH-RISK CHURN: Customer NEKNEK has not purchased in 14 days. Last purchase: 2025-11-27 01:58:00.', '2025-12-11 09:00:03', 1),
(89, 'Tata Tea\'s Coffee Shop', 'HIGH-RISK CHURN: Customer Atasha Ofelia has not purchased in 14 days. Last purchase: 2025-10-01 16:10:00.', '2025-12-12 09:00:04', 0),
(90, 'Tata Tea\'s Coffee Shop', 'HIGH-RISK CHURN: Customer Anbotsaimo11 has not purchased in 14 days. Last purchase: 2025-12-01 01:23:00.', '2025-12-15 09:00:03', 0),
(91, 'Tata Tea\'s Coffee Shop', 'HIGH-RISK CHURN: Customer FOR TEST ONLY has not purchased in 14 days. Last purchase: 2025-12-03 01:17:00.', '2025-12-17 09:00:04', 0),
(92, 'Tata Tea\'s Coffee Shop', 'HIGH-RISK CHURN: Customer Keian has not purchased in 14 days. Last purchase: 2025-12-09 03:56:00.', '2025-12-27 09:00:04', 0),
(93, 'Tata Tea\'s Coffee Shop', 'HIGH-RISK CHURN: Customer Goerge has not purchased in 14 days. Last purchase: 2025-12-09 21:03:00.', '2025-12-27 09:00:04', 0),
(94, 'Tata Tea\'s Coffee Shop', 'HIGH-RISK CHURN: Customer Lira has not purchased in 14 days. Last purchase: 2025-12-08 03:54:00.', '2025-12-27 09:00:04', 0),
(95, 'Tata Tea\'s Coffee Shop', 'HIGH-RISK CHURN: Customer GINA MAE has not purchased in 14 days. Last purchase: 2025-12-06 16:13:00.', '2025-12-27 09:00:04', 0),
(96, 'Tata Tea\'s Coffee Shop', 'HIGH-RISK CHURN: Customer Atasha Ofelia has not purchased in 14 days. Last purchase: 2025-12-14 01:24:00.', '2025-12-28 09:00:04', 0),
(97, 'Tata Tea\'s Coffee Shop', 'HIGH-RISK CHURN: Customer naur has not purchased in 14 days. Last purchase: 2026-01-07 14:45:00.', '2026-01-22 09:00:04', 0);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `reset_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`reset_id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(4, 'dawnbringerriven02@gmail.com', 'cb8c655397b0f24734dfd10bed27c646f9c6700748ab144f750b95bd79c1c341', '2025-09-29 12:26:52', '2025-09-29 03:26:52'),
(5, 'dawnbringerriven02@gmail.com', '93bf40b685562b8058bb3b36cb6b3d02b49a78f8a1c76f996666b45e30790afa', '2025-09-29 12:31:46', '2025-09-29 03:31:46'),
(8, 'gacilloskeian02@gmail.com', '1bb3dd785fd21fd55299f1041791f040f59fbedf93679c6de812504155dbf106', '2025-10-01 17:07:55', '2025-10-01 16:07:55'),
(9, 'dawnbringerriven02@gmail.com', '5e9db6dfebe8e44284eb8e3fe2e77c3ea9682e8460db8123df39b20efe03a692', '2025-10-01 17:10:29', '2025-10-01 16:10:29'),
(12, 'gacilloskeian02@gmail.com', '19775a84c8564c0e2426169c9b571fd6835912f338a1e16d7665f1fd5513c859', '2025-10-05 12:34:27', '2025-10-05 03:34:27'),
(14, 'aljonalday2003@gmail.com', 'b8ae7a05ad1f05a99004db9b8a6b3b36e97be573fc2a4e0c0d1b0c041e3db105', '2025-10-30 17:46:57', '2025-10-30 08:46:57'),
(15, 'aljonalday2003@gmail.com', '97c3cc3273c4dbb1d02ed15e1611c6685f41487f59e106c8a2cf1b58eb167bbd', '2025-10-30 17:49:32', '2025-10-30 08:49:32'),
(16, 'aljonalday2003@gmail.com', '0f049fea997f22a28adc90ffaaf19613a164377c2efeb89c3295fd844ec1d4a6', '2025-10-30 17:53:53', '2025-10-30 08:53:53'),
(17, 'aljonalday2003@gmail.com', '92ae400b3629d608917ae7679997dc7dd533c1b129b67ef23be291ab563d41e0', '2025-10-30 17:53:56', '2025-10-30 08:53:56'),
(18, 'aljonalday2003@gmail.com', '5118bf251d7531f9ac1ef152a87b1ee27c0e0d0114100e87e48d444ef8aa92cc', '2025-12-18 13:40:45', '2025-12-18 04:40:45'),
(20, 'aljonalday2003@gmail.com', '1dadc69dc7ceef1e26c3cf648814c9e9559862d622a73543e9186b8257b6b6d5', '2025-12-18 13:41:44', '2025-12-18 04:41:44'),
(21, 'aljonalday2003@gmail.com', 'b48ab2d51f30443d089e0893adf9e818d8398599947445b4422d1dc23dd47bb2', '2025-12-18 13:43:28', '2025-12-18 04:43:28'),
(25, 'aldayaljon16@gmail.com', '174346a7a0449b051626840263c36c3ad1e764a423ee0555090171bcf1c28ca3', '2025-12-18 14:00:19', '2025-12-18 05:00:19');

-- --------------------------------------------------------

--
-- Table structure for table `purchased`
--

CREATE TABLE `purchased` (
  `purchased_sid` int(11) NOT NULL,
  `customer_sid` int(11) DEFAULT NULL,
  `full_name` varchar(150) NOT NULL,
  `date_purchase` datetime NOT NULL,
  `total` varchar(20) NOT NULL,
  `establishment` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchased`
--

INSERT INTO `purchased` (`purchased_sid`, `customer_sid`, `full_name`, `date_purchase`, `total`, `establishment`) VALUES
(1, 3, 'Lira', '2025-11-18 03:53:00', '104', 'Tata Tea\'s Coffee Shop'),
(4, 3, 'Lira', '2025-12-05 03:54:00', '180', 'Tata Tea\'s Coffee Shop'),
(5, 3, 'Lira', '2025-12-08 03:54:00', '25', 'Tata Tea\'s Coffee Shop'),
(6, 1, 'Keian', '2025-12-09 03:56:00', '339', 'Tata Tea\'s Coffee Shop'),
(8, 1, 'Keian', '2025-12-05 03:56:00', '20', 'Tata Tea\'s Coffee Shop'),
(9, 1, 'Keian', '2025-12-05 03:56:00', '132', 'Tata Tea\'s Coffee Shop'),
(10, 4, 'NEKNEK', '2025-12-09 03:58:00', '462', 'TESTING BUSINESS'),
(11, 2, 'Goerge', '2025-12-09 21:03:00', '382', 'Tata Tea\'s Coffee Shop'),
(12, 5, 'NEKNEK', '2025-11-27 01:58:00', '60', 'Tata Tea\'s Coffee Shop'),
(21, 8, 'Atasha Ofelia', '2025-10-01 16:10:00', '44', 'Tata Tea\'s Coffee Shop'),
(23, 6, 'GINA MAE', '2025-12-06 16:13:00', '44', 'Tata Tea\'s Coffee Shop'),
(24, 9, 'Atasha Palada', '2025-12-03 09:55:00', '347', 'TATA TEA - Borongan'),
(25, 10, 'Glenda Grey', '2025-11-21 10:00:00', '636', 'TATA TEA - Borongan'),
(26, 11, 'Lourdes Alde', '2025-11-20 09:00:00', '276', 'TATA TEA - Borongan'),
(27, 14, 'Agatha Fem', '2025-11-05 12:28:00', '178', 'TATA TEA - Borongan'),
(29, 13, 'Mike Borac', '2025-12-02 22:29:00', '1279', 'TATA TEA - Borongan'),
(30, 10, 'Glenda Grey', '2025-12-01 23:29:00', '1335', 'TATA TEA - Borongan'),
(31, 13, 'Mike Borac', '2025-12-05 10:39:00', '238', 'TATA TEA - Borongan'),
(33, 20, 'Aljon Alday', '2025-12-08 08:04:00', '792', 'TATA TEA - Borongan'),
(34, 9, 'Atasha Palada', '2025-12-09 10:05:00', '414', 'TATA TEA - Borongan'),
(36, 13, 'Mike Borac', '2025-12-12 11:10:00', '414', 'TATA TEA - Borongan'),
(37, 10, 'Glenda Grey', '2025-12-13 07:07:00', '276', 'TATA TEA - Borongan'),
(41, 20, 'Aljon Alday', '2025-11-23 18:23:00', '69', 'TATA TEA - Borongan'),
(42, 9, 'Atasha Palada', '2025-11-22 18:24:00', '59', 'TATA TEA - Borongan'),
(43, 9, 'Atasha Palada', '2025-12-12 18:38:00', '169', 'TATA TEA - Borongan'),
(44, 13, 'Mike Borac', '2025-12-08 18:41:00', '59', 'TATA TEA - Borongan'),
(45, 20, 'Aljon Alday', '2025-12-07 08:15:00', '276', 'TATA TEA - Borongan'),
(46, 9, 'Atasha Palada', '2025-12-08 09:16:00', '745', 'TATA TEA - Borongan'),
(47, 10, 'Glenda Grey', '2025-12-09 09:18:00', '345', 'TATA TEA - Borongan'),
(49, 13, 'Mike Borac', '2025-12-12 11:17:00', '894', 'TATA TEA - Borongan'),
(51, 13, 'Mike Borac', '2025-11-30 09:19:00', '596', 'TATA TEA - Borongan'),
(52, 14, 'Agatha Fem', '2025-12-01 07:19:00', '745', 'TATA TEA - Borongan'),
(53, 10, 'Glenda Grey', '2025-12-02 11:21:00', '138', 'TATA TEA - Borongan'),
(54, 13, 'Mike Borac', '2025-12-03 10:23:00', '505', 'TATA TEA - Borongan'),
(55, 14, 'Agatha Fem', '2025-12-04 08:25:00', '745', 'TATA TEA - Borongan'),
(56, 20, 'Aljon Alday', '2025-12-05 11:22:00', '447', 'TATA TEA - Borongan'),
(57, 10, 'Glenda Grey', '2025-12-06 11:23:00', '138', 'TATA TEA - Borongan'),
(58, 13, 'Mike Borac', '2025-12-07 09:24:00', '745', 'TATA TEA - Borongan'),
(59, 20, 'Aljon Alday', '2025-12-08 10:27:00', '585', 'TATA TEA - Borongan'),
(62, 9, 'Atasha Palada', '2025-12-11 10:24:00', '138', 'TATA TEA - Borongan'),
(63, 10, 'Glenda Grey', '2025-12-12 19:25:00', '345', 'TATA TEA - Borongan'),
(65, 22, 'Benny Botom', '2025-12-12 19:31:00', '447', 'TATA TEA - Borongan'),
(67, 20, 'Aljon Alday', '2025-12-07 08:44:00', '1390', 'TATA TEA - Borongan'),
(68, 20, 'Aljon Alday', '2025-12-10 09:44:00', '745', 'TATA TEA - Borongan'),
(69, 20, 'Aljon Alday', '2025-12-09 08:43:00', '690', 'TATA TEA - Borongan'),
(70, 20, 'Aljon Alday', '2025-12-10 10:47:00', '1043', 'TATA TEA - Borongan'),
(71, 20, 'Aljon Alday', '2025-12-01 09:44:00', '890', 'TATA TEA - Borongan'),
(72, 20, 'Aljon Alday', '2025-12-03 11:47:00', '1419', 'TATA TEA - Borongan'),
(73, 20, 'Aljon Alday', '2025-12-02 09:46:00', '592', 'TATA TEA - Borongan'),
(74, 20, 'Aljon Alday', '2025-10-19 08:47:00', '801', 'TATA TEA - Borongan'),
(75, 20, 'Aljon Alday', '2025-10-27 09:49:00', '903', 'TATA TEA - Borongan'),
(76, 20, 'Aljon Alday', '2025-10-28 10:50:00', '1032', 'TATA TEA - Borongan'),
(77, 20, 'Aljon Alday', '2025-10-29 09:50:00', '792', 'TATA TEA - Borongan'),
(78, 20, 'Aljon Alday', '2025-10-30 09:52:00', '1032', 'TATA TEA - Borongan'),
(79, 20, 'Aljon Alday', '2025-11-23 10:54:00', '801', 'TATA TEA - Borongan'),
(80, 20, 'Aljon Alday', '2025-11-24 10:52:00', '801', 'TATA TEA - Borongan'),
(81, 9, 'Atasha Palada', '2025-10-26 09:53:00', '693', 'TATA TEA - Borongan'),
(82, 9, 'Atasha Palada', '2025-10-27 08:55:00', '483', 'TATA TEA - Borongan'),
(83, 9, 'Atasha Palada', '2025-10-29 10:54:00', '552', 'TATA TEA - Borongan'),
(84, 9, 'Atasha Palada', '2025-11-24 19:52:00', '801', 'TATA TEA - Borongan'),
(85, 9, 'Atasha Palada', '2025-11-26 19:52:00', '610', 'TATA TEA - Borongan'),
(86, 9, 'Atasha Palada', '2025-11-28 11:57:00', '1161', 'TATA TEA - Borongan'),
(87, 9, 'Atasha Palada', '2025-12-01 09:55:00', '621', 'TATA TEA - Borongan'),
(88, 9, 'Atasha Palada', '2025-12-03 09:54:00', '801', 'TATA TEA - Borongan'),
(89, 9, 'Atasha Palada', '2025-10-28 09:58:00', '621', 'TATA TEA - Borongan'),
(90, 9, 'Atasha Palada', '2025-10-30 09:00:00', '531', 'TATA TEA - Borongan'),
(92, 9, 'Atasha Palada', '2025-12-13 08:37:00', '4917', 'TATA TEA - Borongan'),
(93, 10, 'Glenda Grey', '2025-12-13 08:37:00', '2346', 'TATA TEA - Borongan'),
(94, 13, 'Mike Borac', '2025-12-13 08:41:00', '2622', 'TATA TEA - Borongan'),
(95, 21, 'Mike salamangma', '2025-12-13 08:42:00', '207', 'TATA TEA - Borongan'),
(97, 23, 'FOR TEST ONLY', '2025-12-03 01:17:00', '20', 'Tata Tea\'s Coffee Shop'),
(102, 8, 'Atasha Ofelia', '2025-12-01 01:23:00', '12', 'Tata Tea\'s Coffee Shop'),
(103, 19, 'Anbotsaimo11', '2025-12-01 01:23:00', '12', 'Tata Tea\'s Coffee Shop'),
(104, 8, 'Atasha Ofelia', '2025-12-09 01:24:00', '12', 'Tata Tea\'s Coffee Shop'),
(105, 8, 'Atasha Ofelia', '2025-12-14 01:24:00', '24', 'Tata Tea\'s Coffee Shop'),
(106, 8, 'Atasha Ofelia', '2025-12-13 01:24:00', '12', 'Tata Tea\'s Coffee Shop'),
(107, 20, 'Aljon Alday', '2025-12-09 09:28:00', '89', 'TATA TEA - Borongan'),
(108, 20, 'Aljon Alday', '2025-12-15 13:59:00', '98', 'TATA TEA - Borongan'),
(109, 14, 'Agatha Fem', '2025-11-15 14:50:00', '552', 'TATA TEA - Borongan'),
(110, 20, 'Aljon Alday', '2025-12-15 15:08:00', '6624', 'TATA TEA - Borongan'),
(112, 20, 'Aljon Alday', '2025-09-16 14:39:00', '516', 'TATA TEA - Borongan'),
(113, 20, 'Aljon Alday', '2025-12-16 15:27:00', '207', 'TATA TEA - Borongan'),
(114, 20, 'Aljon Alday', '2025-11-18 11:31:00', '1103', 'TATA TEA - Borongan'),
(118, 20, 'Aljon Alday', '2025-12-18 14:50:00', '369', 'TATA TEA - Borongan'),
(119, 20, 'Aljon Alday', '2025-12-18 14:50:00', '369', 'TATA TEA - Borongan'),
(120, 20, 'Aljon Alday', '2025-12-18 14:50:00', '369', 'TATA TEA - Borongan'),
(121, 20, 'Aljon Alday', '2025-12-18 14:50:00', '369', 'TATA TEA - Borongan'),
(123, 28, 'naur', '2026-01-07 14:45:00', '46', 'Tata Tea\'s Coffee Shop'),
(125, 20, 'Aljon Alday', '2025-12-07 09:43:00', '178', 'TATA TEA - Borongan'),
(128, 11, 'Lourdes Alde', '2025-10-25 10:07:00', '138', 'TATA TEA - Borongan'),
(129, 11, 'Lourdes Alde', '2025-11-01 10:09:00', '138', 'TATA TEA - Borongan'),
(131, 14, 'Agatha Fem', '2025-12-25 10:19:00', '298', 'TATA TEA - Borongan'),
(132, 36, 'Miko Bajado', '2026-01-05 11:21:00', '556', 'TATA TEA - Borongan'),
(133, 37, 'Winmar Escoto', '2026-01-06 14:23:00', '197', 'TATA TEA - Borongan'),
(134, 37, 'Winmar Escoto', '2026-01-07 11:48:00', '256', 'TATA TEA - Borongan'),
(135, 38, 'Rommel Bula', '2026-01-08 10:48:00', '525', 'TATA TEA - Borongan'),
(136, 39, 'Leoncio Arago Jr', '2026-01-01 11:49:00', '138', 'TATA TEA - Borongan'),
(137, 36, 'Miko Bajado', '2026-01-08 11:52:00', '267', 'TATA TEA - Borongan'),
(139, 20, 'Aljon Alday', '2026-01-05 10:15:00', '69', 'TATA TEA - Borongan');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `item_id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_items`
--

INSERT INTO `purchase_items` (`item_id`, `purchase_id`, `inventory_id`, `item_name`, `price`, `quantity`, `subtotal`) VALUES
(1, 1, 5, 'Cake', 44.00, 1, 44.00),
(2, 1, 6, 'Ham', 20.00, 3, 60.00),
(3, 2, 5, 'Cake', 44.00, 2, 88.00),
(4, 2, 8, 'French Fries', 180.00, 1, 180.00),
(5, 3, 7, 'Hotdog', 25.00, 2, 50.00),
(6, 4, 8, 'French Fries', 180.00, 1, 180.00),
(7, 5, 7, 'Hotdog', 25.00, 1, 25.00),
(8, 6, 5, 'Cake', 44.00, 1, 44.00),
(9, 6, 6, 'Ham', 20.00, 2, 40.00),
(10, 6, 7, 'Hotdog', 25.00, 3, 75.00),
(11, 6, 8, 'French Fries', 180.00, 1, 180.00),
(12, 7, 6, 'Ham', 20.00, 2, 40.00),
(13, 8, 6, 'Ham', 20.00, 1, 20.00),
(14, 9, 5, 'Cake', 44.00, 3, 132.00),
(15, 10, 9, 'ham', 22.00, 21, 462.00),
(16, 11, 18, 'NEW ITEM', 22.00, 1, 22.00),
(17, 11, 8, 'French Fries', 180.00, 2, 360.00),
(18, 12, 6, 'Ham', 20.00, 3, 60.00),
(19, 13, 5, 'Cake', 44.00, 1, 44.00),
(20, 18, 5, 'Cake', 44.00, 1, 44.00),
(21, 19, 5, 'Cake', 44.00, 1, 44.00),
(22, 19, 6, 'Ham', 20.00, 1, 20.00),
(23, 20, 6, 'Ham', 20.00, 1, 20.00),
(24, 21, 5, 'Cake', 44.00, 1, 44.00),
(25, 22, 5, 'Cake', 44.00, 1, 44.00),
(26, 23, 5, 'Cake', 44.00, 1, 44.00),
(27, 24, 29, 'Chicken Burger', 139.00, 2, 278.00),
(28, 24, 21, 'Matcha Milktea', 69.00, 1, 69.00),
(29, 25, 25, 'Cookies & Cream Oreo Frappe', 129.00, 1, 129.00),
(30, 25, 31, 'Bacon & Beef Burger', 169.00, 3, 507.00),
(31, 26, 26, 'Egg Waffle Original', 69.00, 4, 276.00),
(32, 27, 23, 'Blueberry Fruit Tea', 89.00, 2, 178.00),
(33, 28, 27, 'Sour Cream Fries', 59.00, 5, 295.00),
(34, 29, 30, 'Beef Burger', 149.00, 5, 745.00),
(35, 29, 23, 'Blueberry Fruit Tea', 89.00, 6, 534.00),
(36, 30, 25, 'Cookies & Cream Oreo Frappe', 129.00, 5, 645.00),
(37, 30, 26, 'Egg Waffle Original', 69.00, 10, 690.00),
(38, 31, 30, 'Beef Burger', 149.00, 1, 149.00),
(39, 31, 23, 'Blueberry Fruit Tea', 89.00, 1, 89.00),
(40, 32, 29, 'Chicken Burger', 139.00, 1, 139.00),
(41, 32, 25, 'Cookies & Cream Oreo Frappe', 129.00, 2, 258.00),
(42, 33, 21, 'Matcha Milktea', 69.00, 5, 345.00),
(43, 33, 30, 'Beef Burger', 149.00, 3, 447.00),
(44, 34, 21, 'Matcha Milktea', 69.00, 5, 345.00),
(45, 34, 26, 'Egg Waffle Original', 69.00, 1, 69.00),
(46, 35, 21, 'Matcha Milktea', 69.00, 2, 138.00),
(47, 36, 21, 'Matcha Milktea', 69.00, 6, 414.00),
(48, 37, 21, 'Matcha Milktea', 69.00, 4, 276.00),
(49, 38, 26, 'Egg Waffle Original', 69.00, 1, 69.00),
(50, 39, 26, 'Egg Waffle Original', 69.00, 1, 69.00),
(51, 40, 26, 'Egg Waffle Original', 69.00, 1, 69.00),
(52, 41, 26, 'Egg Waffle Original', 69.00, 1, 69.00),
(53, 42, 27, 'Sour Cream Fries', 59.00, 1, 59.00),
(54, 43, 31, 'Bacon & Beef Burger', 169.00, 1, 169.00),
(55, 44, 28, 'Classic Fries', 59.00, 1, 59.00),
(56, 45, 21, 'Matcha Milktea', 69.00, 4, 276.00),
(57, 46, 30, 'Beef Burger', 149.00, 5, 745.00),
(58, 47, 26, 'Egg Waffle Original', 69.00, 5, 345.00),
(59, 48, 26, 'Egg Waffle Original', 69.00, 3, 207.00),
(60, 49, 30, 'Beef Burger', 149.00, 6, 894.00),
(61, 50, 21, 'Matcha Milktea', 69.00, 4, 276.00),
(62, 51, 30, 'Beef Burger', 149.00, 4, 596.00),
(63, 52, 30, 'Beef Burger', 149.00, 5, 745.00),
(64, 53, 26, 'Egg Waffle Original', 69.00, 2, 138.00),
(65, 54, 21, 'Matcha Milktea', 69.00, 3, 207.00),
(66, 54, 30, 'Beef Burger', 149.00, 2, 298.00),
(67, 55, 30, 'Beef Burger', 149.00, 5, 745.00),
(68, 56, 30, 'Beef Burger', 149.00, 3, 447.00),
(69, 57, 21, 'Matcha Milktea', 69.00, 2, 138.00),
(70, 58, 30, 'Beef Burger', 149.00, 5, 745.00),
(71, 59, 30, 'Beef Burger', 149.00, 3, 447.00),
(72, 59, 21, 'Matcha Milktea', 69.00, 2, 138.00),
(73, 60, 21, 'Matcha Milktea', 69.00, 3, 207.00),
(74, 61, 21, 'Matcha Milktea', 69.00, 4, 276.00),
(75, 62, 21, 'Matcha Milktea', 69.00, 2, 138.00),
(76, 63, 26, 'Egg Waffle Original', 69.00, 5, 345.00),
(77, 64, 21, 'Matcha Milktea', 69.00, 4, 276.00),
(78, 65, 30, 'Beef Burger', 149.00, 3, 447.00),
(79, 66, 30, 'Beef Burger', 149.00, 4, 596.00),
(80, 67, 29, 'Chicken Burger', 139.00, 10, 1390.00),
(81, 68, 30, 'Beef Burger', 149.00, 5, 745.00),
(82, 69, 20, 'Classic Milk Tea', 69.00, 6, 414.00),
(83, 69, 21, 'Matcha Milktea', 69.00, 4, 276.00),
(84, 70, 30, 'Beef Burger', 149.00, 7, 1043.00),
(85, 71, 22, 'Classic Creamcheese ', 89.00, 10, 890.00),
(86, 72, 25, 'Cookies & Cream Oreo Frappe', 129.00, 11, 1419.00),
(87, 73, 21, 'Matcha Milktea', 69.00, 6, 414.00),
(88, 73, 22, 'Classic Creamcheese ', 89.00, 2, 178.00),
(89, 74, 22, 'Classic Creamcheese ', 89.00, 5, 445.00),
(90, 74, 23, 'Blueberry Fruit Tea', 89.00, 4, 356.00),
(91, 75, 25, 'Cookies & Cream Oreo Frappe', 129.00, 7, 903.00),
(92, 76, 25, 'Cookies & Cream Oreo Frappe', 129.00, 8, 1032.00),
(93, 77, 24, 'Brown Sugar Milk', 99.00, 8, 792.00),
(94, 78, 25, 'Cookies & Cream Oreo Frappe', 129.00, 8, 1032.00),
(95, 79, 22, 'Classic Creamcheese ', 89.00, 9, 801.00),
(96, 80, 23, 'Blueberry Fruit Tea', 89.00, 9, 801.00),
(97, 81, 24, 'Brown Sugar Milk', 99.00, 7, 693.00),
(98, 82, 26, 'Egg Waffle Original', 69.00, 7, 483.00),
(99, 83, 26, 'Egg Waffle Original', 69.00, 7, 483.00),
(100, 83, 20, 'Classic Milk Tea', 69.00, 1, 69.00),
(101, 84, 22, 'Classic Creamcheese ', 89.00, 9, 801.00),
(102, 85, 28, 'Classic Fries', 59.00, 8, 472.00),
(103, 85, 20, 'Classic Milk Tea', 69.00, 2, 138.00),
(104, 86, 25, 'Cookies & Cream Oreo Frappe', 129.00, 9, 1161.00),
(105, 87, 20, 'Classic Milk Tea', 69.00, 9, 621.00),
(106, 88, 23, 'Blueberry Fruit Tea', 89.00, 9, 801.00),
(107, 89, 20, 'Classic Milk Tea', 69.00, 9, 621.00),
(108, 90, 28, 'Classic Fries', 59.00, 9, 531.00),
(109, 91, 8, 'French Fries', 180.00, 1, 180.00),
(110, 92, 30, 'Beef Burger', 149.00, 33, 4917.00),
(111, 93, 21, 'Matcha Milktea', 69.00, 34, 2346.00),
(112, 94, 26, 'Egg Waffle Original', 69.00, 38, 2622.00),
(113, 95, 26, 'Egg Waffle Original', 69.00, 3, 207.00),
(114, 96, 7, 'Hotdog', 25.00, 2, 50.00),
(115, 97, 6, 'Ham', 20.00, 1, 20.00),
(116, 98, 7, 'Hotdog', 25.00, 1, 25.00),
(117, 99, 7, 'Hotdog', 25.00, 1, 25.00),
(118, 100, 7, 'Hotdog', 25.00, 1, 25.00),
(119, 101, 15, 'bread1', 23.00, 1, 23.00),
(120, 102, 14, 'ham5', 12.00, 1, 12.00),
(121, 103, 14, 'ham5', 12.00, 1, 12.00),
(122, 104, 14, 'ham5', 12.00, 1, 12.00),
(123, 105, 14, 'ham5', 12.00, 2, 24.00),
(124, 106, 14, 'ham5', 12.00, 1, 12.00),
(125, 107, 22, 'Classic Creamcheese ', 89.00, 1, 89.00),
(126, 108, 34, 'Milk Tea Okinawa', 49.00, 2, 98.00),
(127, 109, 20, 'Classic Milk Tea', 69.00, 4, 276.00),
(128, 109, 21, 'Matcha Milk Tea', 69.00, 4, 276.00),
(129, 110, 20, 'Classic Milk Tea', 69.00, 90, 6210.00),
(130, 110, 21, 'Matcha Milk Tea', 69.00, 6, 414.00),
(131, 111, 25, 'Cookies & Cream Oreo Frappe', 129.00, 3, 387.00),
(132, 111, 21, 'Matcha Milk Tea', 69.00, 1, 69.00),
(133, 112, 21, 'Matcha Milk Tea', 69.00, 1, 69.00),
(134, 112, 30, 'Beef Burger', 149.00, 3, 447.00),
(135, 113, 20, 'Classic Milk Tea', 69.00, 1, 69.00),
(136, 113, 21, 'Matcha Milk Tea', 69.00, 2, 138.00),
(137, 114, 31, 'Bacon & Beef Burger', 169.00, 6, 1014.00),
(138, 114, 23, 'Blueberry Fruit Tea', 89.00, 1, 89.00),
(139, 115, 31, 'Bacon & Beef Burger', 169.00, 5, 845.00),
(140, 115, 23, 'Blueberry Fruit Tea', 89.00, 1, 89.00),
(141, 116, 35, 'Brown Sugar Milk ', 100.00, 2, 200.00),
(142, 116, 31, 'Bacon & Beef Burger', 169.00, 1, 169.00),
(143, 117, 35, 'Brown Sugar Milk ', 100.00, 2, 200.00),
(144, 117, 31, 'Bacon & Beef Burger', 169.00, 1, 169.00),
(145, 118, 35, 'Brown Sugar Milk ', 100.00, 2, 200.00),
(146, 118, 31, 'Bacon & Beef Burger', 169.00, 1, 169.00),
(147, 119, 35, 'Brown Sugar Milk ', 100.00, 2, 200.00),
(148, 119, 31, 'Bacon & Beef Burger', 169.00, 1, 169.00),
(149, 120, 35, 'Brown Sugar Milk ', 100.00, 2, 200.00),
(150, 120, 31, 'Bacon & Beef Burger', 169.00, 1, 169.00),
(151, 121, 35, 'Brown Sugar Milk ', 100.00, 2, 200.00),
(152, 121, 31, 'Bacon & Beef Burger', 169.00, 1, 169.00),
(153, 122, 30, 'Beef Burger', 149.00, 1, 149.00),
(154, 122, 24, 'Brown Sugar Milk', 99.00, 2, 198.00),
(155, 123, 15, 'bread1', 23.00, 2, 46.00),
(156, 124, 31, 'Bacon & Beef Burger', 169.00, 2, 338.00),
(157, 125, 23, 'Blueberry Fruit Tea', 89.00, 2, 178.00),
(158, 126, 25, 'Cookies & Cream Oreo Frappe', 129.00, 2, 258.00),
(159, 126, 21, 'Matcha Milk Tea', 69.00, 4, 276.00),
(160, 127, 30, 'Beef Burger', 149.00, 1, 149.00),
(161, 127, 31, 'Bacon & Beef Burger', 169.00, 3, 507.00),
(162, 128, 26, 'Egg Waffle Original', 69.00, 2, 138.00),
(163, 129, 21, 'Matcha Milk Tea', 69.00, 2, 138.00),
(164, 130, 21, 'Matcha Milk Tea', 69.00, 2, 138.00),
(165, 131, 30, 'Beef Burger', 149.00, 2, 298.00),
(166, 132, 30, 'Beef Burger', 149.00, 2, 298.00),
(167, 132, 25, 'Cookies & Cream Oreo Frappe', 129.00, 2, 258.00),
(168, 133, 28, 'Classic Fries', 59.00, 1, 59.00),
(169, 133, 21, 'Matcha Milk Tea', 69.00, 2, 138.00),
(170, 134, 21, 'Matcha Milk Tea', 69.00, 2, 138.00),
(171, 134, 28, 'Classic Fries', 59.00, 2, 118.00),
(172, 135, 25, 'Cookies & Cream Oreo Frappe', 129.00, 3, 387.00),
(173, 135, 21, 'Matcha Milk Tea', 69.00, 2, 138.00),
(174, 136, 26, 'Egg Waffle Original', 69.00, 2, 138.00),
(175, 137, 23, 'Blueberry Fruit Tea', 89.00, 3, 267.00),
(176, 138, 26, 'Egg Waffle Original', 69.00, 1, 69.00),
(177, 139, 26, 'Egg Waffle Original', 69.00, 1, 69.00),
(178, 140, 30, 'Beef Burger', 149.00, 2, 298.00),
(179, 140, 21, 'Matcha Milk Tea', 69.00, 2, 138.00);

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` int(11) NOT NULL,
  `establishment_id` int(11) NOT NULL,
  `reminder_date` date NOT NULL,
  `message` text NOT NULL,
  `is_notified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reminders`
--

INSERT INTO `reminders` (`id`, `establishment_id`, `reminder_date`, `message`, `is_notified`, `created_at`) VALUES
(1, 1, '2025-12-11', 'haaa', 1, '2025-12-03 20:27:14'),
(2, 1, '2025-12-04', 'hahaha', 1, '2025-12-03 20:27:40'),
(4, 23, '2025-12-04', 'employee birthday', 1, '2025-12-04 06:11:50'),
(5, 23, '2025-12-16', 'Christmas Party!!!', 1, '2025-12-13 03:33:59'),
(6, 23, '2025-12-17', 'live band', 0, '2025-12-18 05:49:50'),
(7, 23, '2026-01-08', 'Defense', 0, '2026-01-07 02:47:16'),
(8, 23, '2026-01-12', 'Final Defense!!!!!', 1, '2026-01-11 11:16:58'),
(9, 23, '2026-01-15', 'Live band', 0, '2026-01-12 05:07:50');

-- --------------------------------------------------------

--
-- Table structure for table `segments`
--

CREATE TABLE `segments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `establishment` varchar(255) DEFAULT NULL,
  `age_min` int(11) DEFAULT NULL,
  `age_max` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `criteria` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `segments`
--

INSERT INTO `segments` (`id`, `name`, `establishment`, `age_min`, `age_max`, `description`, `criteria`, `created_at`) VALUES
(6, 'Families', 'Tata Tea\'s Coffee Shop', 35, 60, 'this is families segment', '', '2025-09-17 09:05:43'),
(10, 'Students', 'Business Name', 12, 25, 'THIS DESCRIPTION SEGMENT', 'THIS IS CRITERIA SEGMENT', '2025-09-18 07:46:23'),
(11, 'PROFESSIONALS', 'Business Name', 25, 35, 'THIS IS PROFESSIONALS SEGMENT', 'THIS CRITERIA SEGMENT', '2025-09-18 07:46:53'),
(13, 'Students', 'Keian store', 10, 25, 'this is students', 'criteria', '2025-09-23 02:15:21'),
(34, 'Loyal Buyers', 'Watson\'s', 21, 70, 'This segment is only for those who often purchased on our store ', 'The costumer(s) that will be added here are the ones who often purchase in our store', '2025-10-15 16:03:15'),
(36, 'Young Adult Students per', 'Tata Tea', 16, 22, 'Students aged 16-22 who are likely to be priced sensitive and frequent visitors ', 'Aged 16-22, residing/studying within Borongan City ', '2025-10-17 17:50:57'),
(37, 'Loyal Customers', 'Tata Tea', 16, 25, 'Frequent buyers', 'residing in Borongan City', '2025-10-30 17:18:42'),
(40, 'TESTingssss', 'TESTING BUSINESS', 1, 22, 'this is testing', '', '2025-11-26 20:19:54'),
(52, 'Morning Snackers', 'TATA TEA - Borongan', 16, 50, 'customer aged 16 - 50', '', '2026-01-12 12:49:57');

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `systemlog_sid` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `establishment_name` varchar(255) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`acc_id`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`campaign_sid`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_sid`);

--
-- Indexes for table `establishment`
--
ALTER TABLE `establishment`
  ADD PRIMARY KEY (`establishment_sid`);

--
-- Indexes for table `failed_logins`
--
ALTER TABLE `failed_logins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_fail` (`username`,`establishment_name`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventory_id`);

--
-- Indexes for table `inventory_daily_stock`
--
ALTER TABLE `inventory_daily_stock`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_stock` (`inventory_id`,`stock_date`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`reset_id`);

--
-- Indexes for table `purchased`
--
ALTER TABLE `purchased`
  ADD PRIMARY KEY (`purchased_sid`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `segments`
--
ALTER TABLE `segments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`systemlog_sid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `acc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `campaign_sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `establishment`
--
ALTER TABLE `establishment`
  MODIFY `establishment_sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `failed_logins`
--
ALTER TABLE `failed_logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `inventory_daily_stock`
--
ALTER TABLE `inventory_daily_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1018;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `reset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `purchased`
--
ALTER TABLE `purchased`
  MODIFY `purchased_sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `segments`
--
ALTER TABLE `segments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `systemlog_sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=330;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory_daily_stock`
--
ALTER TABLE `inventory_daily_stock`
  ADD CONSTRAINT `inventory_daily_stock_ibfk_1` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`inventory_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
