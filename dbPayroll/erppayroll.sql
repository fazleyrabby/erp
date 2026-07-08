-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2022 at 09:27 AM
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
-- Database: `erppayroll`
--

-- --------------------------------------------------------

--
-- Table structure for table `bonus_lists`
--

CREATE TABLE `bonus_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bonus_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `applicable_from` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `last_updated_by` bigint(20) DEFAULT NULL,
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bonus_lists`
--

INSERT INTO `bonus_lists` (`id`, `bonus_name`, `month_year`, `note`, `applicable_from`, `created_by`, `last_updated_by`, `deleted`, `deleted_by`, `deleted_date`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Festival Bonus', 'November-2021', 'Write Note', '2021-11-02', 1, 1, 'No', NULL, NULL, 'Active', '2021-11-21 07:26:03', '2021-11-21 09:28:10'),
(3, 'Extra Bonus', 'December-2021', 'Extra Bonus', '2021-11-01', 1, 1, 'No', NULL, '2021-11-21 14:52:24', 'Active', '2021-11-21 07:27:29', '2021-11-21 09:28:32'),
(4, 'Yearly Bonus', 'December-2021', 'Test', '2021-12-10', 21, NULL, 'No', NULL, NULL, 'Active', '2021-12-13 15:15:56', '2021-12-13 15:15:56');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `image`, `status`, `deleted`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted_by`, `deleted_date`, `created_at`, `updated_at`) VALUES
(1, 'uvex', '1648106117brand.jpg', 'Active', 'No', 21, '2022-03-24 13:15:17', NULL, NULL, NULL, NULL, '2022-03-24 18:15:17', '2022-03-24 18:15:17'),
(2, 'Taifun', 'no_image.png', 'Active', 'No', 2, '2022-03-28 12:34:55', 2, '2022-03-29 18:29:42', NULL, NULL, '2022-03-28 17:34:55', '2022-03-29 23:29:42'),
(3, 'China', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:15:59', NULL, NULL, NULL, NULL, '2022-03-29 23:15:59', '2022-03-29 23:15:59'),
(4, 'Zoom', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:16:15', NULL, NULL, NULL, NULL, '2022-03-29 23:16:15', '2022-03-29 23:16:15'),
(5, 'Deshi', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:16:36', NULL, NULL, NULL, NULL, '2022-03-29 23:16:36', '2022-03-29 23:16:36'),
(6, 'Alfa Gold', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:16:59', NULL, NULL, NULL, NULL, '2022-03-29 23:16:59', '2022-03-29 23:16:59'),
(7, 'Solex', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:17:36', NULL, NULL, NULL, NULL, '2022-03-29 23:17:36', '2022-03-29 23:17:36'),
(8, 'Solex Super', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:17:50', NULL, NULL, NULL, NULL, '2022-03-29 23:17:50', '2022-03-29 23:17:50'),
(9, 'Swan', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:18:03', NULL, NULL, NULL, NULL, '2022-03-29 23:18:03', '2022-03-29 23:18:03'),
(10, 'Udyogi', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:18:29', NULL, NULL, NULL, NULL, '2022-03-29 23:18:29', '2022-03-29 23:18:29'),
(11, 'Padma', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:18:43', NULL, NULL, NULL, NULL, '2022-03-29 23:18:43', '2022-03-29 23:18:43'),
(12, 'Bangla', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:19:04', NULL, NULL, NULL, NULL, '2022-03-29 23:19:04', '2022-03-29 23:19:04'),
(13, 'STS', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:19:14', NULL, NULL, NULL, NULL, '2022-03-29 23:19:14', '2022-03-29 23:19:14'),
(14, 'V-Gaurd', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:19:31', NULL, NULL, NULL, NULL, '2022-03-29 23:19:31', '2022-03-29 23:19:31'),
(15, 'Lion', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:19:42', NULL, NULL, NULL, NULL, '2022-03-29 23:19:42', '2022-03-29 23:19:42'),
(16, 'Top-Safety', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:20:00', NULL, NULL, NULL, NULL, '2022-03-29 23:20:00', '2022-03-29 23:20:00'),
(17, 'India', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:20:16', NULL, NULL, NULL, NULL, '2022-03-29 23:20:16', '2022-03-29 23:20:16'),
(18, 'C-Tec', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:20:36', NULL, NULL, NULL, NULL, '2022-03-29 23:20:36', '2022-03-29 23:20:36'),
(19, 'Garrett', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:21:08', NULL, NULL, NULL, NULL, '2022-03-29 23:21:08', '2022-03-29 23:21:08'),
(20, 'RFL', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:21:25', NULL, NULL, NULL, NULL, '2022-03-29 23:21:25', '2022-03-29 23:21:25'),
(21, 'LE', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:21:32', NULL, NULL, NULL, NULL, '2022-03-29 23:21:32', '2022-03-29 23:21:32'),
(22, 'Taizin', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:21:44', NULL, NULL, NULL, NULL, '2022-03-29 23:21:44', '2022-03-29 23:21:44'),
(23, 'Ship', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:21:59', NULL, NULL, NULL, NULL, '2022-03-29 23:21:59', '2022-03-29 23:21:59'),
(24, 'HMBR', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:22:13', NULL, NULL, NULL, NULL, '2022-03-29 23:22:13', '2022-03-29 23:22:13'),
(25, 'Hina', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:22:23', NULL, NULL, NULL, NULL, '2022-03-29 23:22:23', '2022-03-29 23:22:23'),
(26, 'Malaysia', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:22:34', NULL, NULL, NULL, NULL, '2022-03-29 23:22:34', '2022-03-29 23:22:34'),
(27, 'Singapore', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:22:49', NULL, NULL, NULL, NULL, '2022-03-29 23:22:49', '2022-03-29 23:22:49'),
(28, 'Techno', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:23:04', 2, '2022-03-29 21:42:28', NULL, NULL, '2022-03-29 23:23:04', '2022-03-30 02:42:28'),
(29, 'OBI', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:23:12', NULL, NULL, NULL, NULL, '2022-03-29 23:23:12', '2022-03-29 23:23:12'),
(30, 'ASHA', 'no_image.png', 'Active', 'No', 2, '2022-03-29 18:23:21', NULL, NULL, NULL, NULL, '2022-03-29 23:23:21', '2022-03-29 23:23:21'),
(31, 'YAMATO', '1648629800yamato.jpg', 'Active', 'No', 2, '2022-03-29 18:23:38', 3, '2022-03-30 14:43:20', NULL, NULL, '2022-03-29 23:23:38', '2022-03-30 19:43:20'),
(32, 'Test Brand', 'no_image.png', 'Active', 'No', 1, '2022-04-02 20:18:22', NULL, NULL, NULL, NULL, '2022-04-03 01:18:22', '2022-04-03 01:18:22'),
(33, 'BODA', 'no_image.png', 'Active', 'No', 4, '2022-04-06 15:27:10', NULL, NULL, NULL, NULL, '2022-04-06 20:27:10', '2022-04-06 20:27:10'),
(34, 'BOSCH', 'no_image.png', 'Active', 'No', 4, '2022-04-06 16:06:31', NULL, NULL, NULL, NULL, '2022-04-06 21:06:31', '2022-04-06 21:06:31'),
(35, 'DENING', 'no_image.png', 'Active', 'No', 4, '2022-04-07 10:59:59', NULL, NULL, NULL, NULL, '2022-04-07 15:59:59', '2022-04-07 15:59:59'),
(36, 'TIEDAO', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:00:16', NULL, NULL, NULL, NULL, '2022-04-07 16:00:16', '2022-04-07 16:00:16'),
(37, 'HW', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:00:29', NULL, NULL, NULL, NULL, '2022-04-07 16:00:29', '2022-04-07 16:00:29'),
(38, 'BOSS', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:01:00', NULL, NULL, NULL, NULL, '2022-04-07 16:01:00', '2022-04-07 16:01:00'),
(39, 'CROWN', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:01:17', NULL, NULL, NULL, NULL, '2022-04-07 16:01:17', '2022-04-07 16:01:17'),
(40, 'DEWALT', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:02:14', NULL, NULL, NULL, NULL, '2022-04-07 16:02:14', '2022-04-07 16:02:14'),
(41, 'FUJI', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:03:08', NULL, NULL, NULL, NULL, '2022-04-07 16:03:08', '2022-04-07 16:03:08'),
(42, 'KAWASAKI', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:03:55', NULL, NULL, NULL, NULL, '2022-04-07 16:03:55', '2022-04-07 16:03:55'),
(43, 'HUIHANG', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:04:12', NULL, NULL, NULL, NULL, '2022-04-07 16:04:12', '2022-04-07 16:04:12'),
(44, 'WORKX', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:04:35', NULL, NULL, NULL, NULL, '2022-04-07 16:04:35', '2022-04-07 16:04:35'),
(45, 'YUN NA', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:04:58', NULL, NULL, NULL, NULL, '2022-04-07 16:04:58', '2022-04-07 16:04:58'),
(46, 'GREEN POWER', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:05:18', NULL, NULL, NULL, NULL, '2022-04-07 16:05:18', '2022-04-07 16:05:18'),
(47, 'KING POWER', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:05:43', NULL, NULL, NULL, NULL, '2022-04-07 16:05:43', '2022-04-07 16:05:43'),
(48, 'SUPER POWER', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:06:07', NULL, NULL, NULL, NULL, '2022-04-07 16:06:07', '2022-04-07 16:06:07'),
(49, 'KEN', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:06:20', NULL, NULL, NULL, NULL, '2022-04-07 16:06:20', '2022-04-07 16:06:20'),
(50, 'TIMEBRO', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:06:46', NULL, NULL, NULL, NULL, '2022-04-07 16:06:46', '2022-04-07 16:06:46'),
(51, 'MAKITA', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:07:06', NULL, NULL, NULL, NULL, '2022-04-07 16:07:06', '2022-04-07 16:07:06'),
(52, 'STEINEL', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:07:39', NULL, NULL, NULL, NULL, '2022-04-07 16:07:39', '2022-04-07 16:07:39'),
(53, 'LACELA', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:08:08', NULL, NULL, NULL, NULL, '2022-04-07 16:08:08', '2022-04-07 16:08:08'),
(54, 'SKILL', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:08:17', NULL, NULL, NULL, NULL, '2022-04-07 16:08:17', '2022-04-07 16:08:17'),
(55, 'SENKEN-Deleted-55', 'no_image.png', 'Active', 'Yes', 4, '2022-04-07 11:08:28', NULL, NULL, 4, '2022-04-09 15:29:49', '2022-04-07 16:08:28', '2022-04-09 20:29:49'),
(56, 'KINGSTAR', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:09:28', NULL, NULL, NULL, NULL, '2022-04-07 16:09:28', '2022-04-07 16:09:28'),
(57, 'TEW', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:09:45', NULL, NULL, NULL, NULL, '2022-04-07 16:09:45', '2022-04-07 16:09:45'),
(58, 'MAXPRO', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:09:56', NULL, NULL, NULL, NULL, '2022-04-07 16:09:56', '2022-04-07 16:09:56'),
(59, 'ARGER', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:10:12', NULL, NULL, NULL, NULL, '2022-04-07 16:10:12', '2022-04-07 16:10:12'),
(60, 'SPEED', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:10:28', NULL, NULL, NULL, NULL, '2022-04-07 16:10:28', '2022-04-07 16:10:28'),
(61, 'UNISON', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:10:42', NULL, NULL, NULL, NULL, '2022-04-07 16:10:42', '2022-04-07 16:10:42'),
(62, 'KONSUN', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:11:01', NULL, NULL, NULL, NULL, '2022-04-07 16:11:01', '2022-04-07 16:11:01'),
(63, 'BORAY', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:11:12', NULL, NULL, NULL, NULL, '2022-04-07 16:11:12', '2022-04-07 16:11:12'),
(64, 'RIVCEN', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:12:46', NULL, NULL, NULL, NULL, '2022-04-07 16:12:46', '2022-04-07 16:12:46'),
(65, 'TAHER', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:13:09', NULL, NULL, NULL, NULL, '2022-04-07 16:13:09', '2022-04-07 16:13:09'),
(66, 'SURUCHI', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:13:29', NULL, NULL, NULL, NULL, '2022-04-07 16:13:29', '2022-04-07 16:13:29'),
(67, 'RICHU', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:14:45', NULL, NULL, NULL, NULL, '2022-04-07 16:14:45', '2022-04-07 16:14:45'),
(68, 'MUREX', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:14:54', NULL, NULL, NULL, NULL, '2022-04-07 16:14:54', '2022-04-07 16:14:54'),
(69, 'WELDRO', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:15:04', NULL, NULL, NULL, NULL, '2022-04-07 16:15:04', '2022-04-07 16:15:04'),
(70, 'WIM', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:15:47', NULL, NULL, NULL, NULL, '2022-04-07 16:15:47', '2022-04-07 16:15:47'),
(71, 'CHINA / LIBERTY / GOLDEN DOVE-Deleted-71', 'no_image.png', 'Active', 'Yes', 4, '2022-04-07 11:16:08', NULL, NULL, 4, '2022-04-10 15:51:07', '2022-04-07 16:16:08', '2022-04-10 20:51:07'),
(72, 'DESHI / TAHER-Deleted-72', 'no_image.png', 'Active', 'Yes', 4, '2022-04-07 11:16:32', NULL, NULL, 4, '2022-04-07 11:17:12', '2022-04-07 16:16:32', '2022-04-07 16:17:12'),
(73, 'TANAKA', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:17:20', NULL, NULL, NULL, NULL, '2022-04-07 16:17:20', '2022-04-07 16:17:20'),
(74, 'VICTOR', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:17:39', NULL, NULL, NULL, NULL, '2022-04-07 16:17:39', '2022-04-07 16:17:39'),
(75, 'BRIDGEHTONE', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:21:00', NULL, NULL, NULL, NULL, '2022-04-07 16:21:00', '2022-04-07 16:21:00'),
(76, 'GASBAR', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:22:02', NULL, NULL, NULL, NULL, '2022-04-07 16:22:02', '2022-04-07 16:22:02'),
(77, 'KINOSAKI', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:22:15', NULL, NULL, NULL, NULL, '2022-04-07 16:22:15', '2022-04-07 16:22:15'),
(78, 'JAFREE', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:22:55', NULL, NULL, NULL, NULL, '2022-04-07 16:22:55', '2022-04-07 16:22:55'),
(79, 'AKHTER', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:23:04', NULL, NULL, NULL, NULL, '2022-04-07 16:23:04', '2022-04-07 16:23:04'),
(80, 'REDANT', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:23:48', NULL, NULL, NULL, NULL, '2022-04-07 16:23:48', '2022-04-07 16:23:48'),
(81, 'HILIGHT', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:23:56', NULL, NULL, NULL, NULL, '2022-04-07 16:23:56', '2022-04-07 16:23:56'),
(82, 'D&L', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:24:13', NULL, NULL, NULL, NULL, '2022-04-07 16:24:13', '2022-04-07 16:24:13'),
(83, 'MORRIS', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:24:32', NULL, NULL, NULL, NULL, '2022-04-07 16:24:32', '2022-04-07 16:24:32'),
(84, 'NOVENA', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:26:28', NULL, NULL, NULL, NULL, '2022-04-07 16:26:28', '2022-04-07 16:26:28'),
(85, 'MIYAKO', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:26:54', NULL, NULL, NULL, NULL, '2022-04-07 16:26:54', '2022-04-07 16:26:54'),
(86, 'CAMRY', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:27:02', NULL, NULL, NULL, NULL, '2022-04-07 16:27:02', '2022-04-07 16:27:02'),
(87, 'SUMO', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:29:11', NULL, NULL, NULL, NULL, '2022-04-07 16:29:11', '2022-04-07 16:29:11'),
(88, 'VIETNAM', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:29:44', NULL, NULL, NULL, NULL, '2022-04-07 16:29:44', '2022-04-07 16:29:44'),
(89, 'SPS', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:29:56', NULL, NULL, NULL, NULL, '2022-04-07 16:29:56', '2022-04-07 16:29:56'),
(90, 'MEMORY', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:30:01', NULL, NULL, NULL, NULL, '2022-04-07 16:30:01', '2022-04-07 16:30:01'),
(91, 'DONGIL', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:30:34', NULL, NULL, NULL, NULL, '2022-04-07 16:30:34', '2022-04-07 16:30:34'),
(92, 'CAMRY SP', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:30:42', NULL, NULL, NULL, NULL, '2022-04-07 16:30:42', '2022-04-07 16:30:42'),
(93, 'MEGA', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:31:01', NULL, NULL, NULL, NULL, '2022-04-07 16:31:01', '2022-04-07 16:31:01'),
(94, 'SEIKO', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:31:11', NULL, NULL, NULL, NULL, '2022-04-07 16:31:11', '2022-04-07 16:31:11'),
(95, 'GOLDTECH', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:31:20', NULL, NULL, NULL, NULL, '2022-04-07 16:31:20', '2022-04-07 16:31:20'),
(96, 'TANITA', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:31:32', NULL, NULL, NULL, NULL, '2022-04-07 16:31:32', '2022-04-07 16:31:32'),
(97, 'GREENONE', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:35:37', NULL, NULL, NULL, NULL, '2022-04-07 16:35:37', '2022-04-07 16:35:37'),
(98, 'GRAMEEN STAR', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:35:48', NULL, NULL, NULL, NULL, '2022-04-07 16:35:48', '2022-04-07 16:35:48'),
(99, 'SATWIK', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:35:56', NULL, NULL, NULL, NULL, '2022-04-07 16:35:56', '2022-04-07 16:35:56'),
(100, 'ELEPHANT', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:36:05', NULL, NULL, NULL, NULL, '2022-04-07 16:36:05', '2022-04-07 16:36:05'),
(101, 'MARINO', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:36:15', NULL, NULL, NULL, NULL, '2022-04-07 16:36:15', '2022-04-07 16:36:15'),
(102, 'MEGA KING', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:36:24', NULL, NULL, NULL, NULL, '2022-04-07 16:36:24', '2022-04-07 16:36:24'),
(103, '3 STAR', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:36:32', NULL, NULL, NULL, NULL, '2022-04-07 16:36:32', '2022-04-07 16:36:32'),
(104, 'FIVESTAR', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:37:59', NULL, NULL, NULL, NULL, '2022-04-07 16:37:59', '2022-04-07 16:37:59'),
(105, 'MCI', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:38:08', NULL, NULL, NULL, NULL, '2022-04-07 16:38:08', '2022-04-07 16:38:08'),
(106, 'XPART', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:38:16', NULL, NULL, NULL, NULL, '2022-04-07 16:38:16', '2022-04-07 16:38:16'),
(107, 'SAIGON', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:38:25', NULL, NULL, NULL, NULL, '2022-04-07 16:38:25', '2022-04-07 16:38:25'),
(108, 'BLUE / MEGA', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:38:38', NULL, NULL, NULL, NULL, '2022-04-07 16:38:38', '2022-04-07 16:38:38'),
(109, 'THANGLONG', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:38:56', NULL, NULL, NULL, NULL, '2022-04-07 16:38:56', '2022-04-07 16:38:56'),
(110, 'LADDERTECH', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:40:14', NULL, NULL, NULL, NULL, '2022-04-07 16:40:14', '2022-04-07 16:40:14'),
(111, 'EVERBEST', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:40:24', NULL, NULL, NULL, NULL, '2022-04-07 16:40:24', '2022-04-07 16:40:24'),
(112, 'CHANGFA', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:41:05', NULL, NULL, NULL, NULL, '2022-04-07 16:41:05', '2022-04-07 16:41:05'),
(113, 'FT TOOLS', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:41:12', NULL, NULL, NULL, NULL, '2022-04-07 16:41:12', '2022-04-07 16:41:12'),
(114, 'MIYAKI', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:41:21', NULL, NULL, NULL, NULL, '2022-04-07 16:41:21', '2022-04-07 16:41:21'),
(115, 'SPERO', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:41:32', NULL, NULL, NULL, NULL, '2022-04-07 16:41:32', '2022-04-07 16:41:32'),
(116, 'BIR', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:41:41', NULL, NULL, NULL, NULL, '2022-04-07 16:41:41', '2022-04-07 16:41:41'),
(117, 'SDI', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:41:57', NULL, NULL, NULL, NULL, '2022-04-07 16:41:57', '2022-04-07 16:41:57'),
(118, 'CMART', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:42:11', NULL, NULL, NULL, NULL, '2022-04-07 16:42:11', '2022-04-07 16:42:11'),
(119, 'REED', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:42:20', NULL, NULL, NULL, NULL, '2022-04-07 16:42:20', '2022-04-07 16:42:20'),
(120, 'KHAKEE', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:42:33', NULL, NULL, NULL, NULL, '2022-04-07 16:42:33', '2022-04-07 16:42:33'),
(121, 'CARBIDE', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:42:54', NULL, NULL, NULL, NULL, '2022-04-07 16:42:54', '2022-04-07 16:42:54'),
(122, 'SUPO', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:43:08', NULL, NULL, NULL, NULL, '2022-04-07 16:43:08', '2022-04-07 16:43:08'),
(123, 'CHIPPING', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:43:17', NULL, NULL, NULL, NULL, '2022-04-07 16:43:17', '2022-04-07 16:43:17'),
(124, 'CLAW', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:43:25', NULL, NULL, NULL, NULL, '2022-04-07 16:43:25', '2022-04-07 16:43:25'),
(125, 'HIBO', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:43:45', NULL, NULL, NULL, NULL, '2022-04-07 16:43:45', '2022-04-07 16:43:45'),
(126, 'SUNMOON', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:43:50', NULL, NULL, NULL, NULL, '2022-04-07 16:43:50', '2022-04-07 16:43:50'),
(127, 'FUJIMOTO', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:44:10', NULL, NULL, NULL, NULL, '2022-04-07 16:44:10', '2022-04-07 16:44:10'),
(128, 'FIXIT', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:44:18', NULL, NULL, NULL, NULL, '2022-04-07 16:44:18', '2022-04-07 16:44:18'),
(129, 'FG', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:44:24', NULL, NULL, NULL, NULL, '2022-04-07 16:44:24', '2022-04-07 16:44:24'),
(130, 'DIAMOND', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:44:33', NULL, NULL, NULL, NULL, '2022-04-07 16:44:33', '2022-04-07 16:44:33'),
(131, 'RAZORLINE', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:45:03', NULL, NULL, NULL, NULL, '2022-04-07 16:45:03', '2022-04-07 16:45:03'),
(132, 'MASTER FORCE', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:45:10', NULL, NULL, NULL, NULL, '2022-04-07 16:45:10', '2022-04-07 16:45:10'),
(133, 'SUPER', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:45:26', NULL, NULL, NULL, NULL, '2022-04-07 16:45:26', '2022-04-07 16:45:26'),
(134, 'PARMATEX', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:46:05', NULL, NULL, NULL, NULL, '2022-04-07 16:46:05', '2022-04-07 16:46:05'),
(135, 'XTRASEAL', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:46:10', NULL, NULL, NULL, NULL, '2022-04-07 16:46:10', '2022-04-07 16:46:10'),
(136, 'COBALT', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:46:34', NULL, NULL, NULL, NULL, '2022-04-07 16:46:34', '2022-04-07 16:46:34'),
(137, 'MORSE', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:46:43', NULL, NULL, NULL, NULL, '2022-04-07 16:46:43', '2022-04-07 16:46:43'),
(138, 'NORMAL', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:46:51', NULL, NULL, NULL, NULL, '2022-04-07 16:46:51', '2022-04-07 16:46:51'),
(139, 'SILVER', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:46:55', NULL, NULL, NULL, NULL, '2022-04-07 16:46:55', '2022-04-07 16:46:55'),
(140, 'PERFECT', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:47:05', NULL, NULL, NULL, NULL, '2022-04-07 16:47:05', '2022-04-07 16:47:05'),
(141, 'SPIRIT', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:47:18', NULL, NULL, NULL, NULL, '2022-04-07 16:47:18', '2022-04-07 16:47:18'),
(142, 'DERBY', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:47:27', NULL, NULL, NULL, NULL, '2022-04-07 16:47:27', '2022-04-07 16:47:27'),
(143, 'DUWELL', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:47:34', NULL, NULL, NULL, NULL, '2022-04-07 16:47:34', '2022-04-07 16:47:34'),
(144, 'PEDROLO', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:47:58', NULL, NULL, NULL, NULL, '2022-04-07 16:47:58', '2022-04-07 16:47:58'),
(145, 'WHITEBOX', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:48:32', NULL, NULL, NULL, NULL, '2022-04-07 16:48:32', '2022-04-07 16:48:32'),
(146, 'BLUE', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:48:45', NULL, NULL, NULL, NULL, '2022-04-07 16:48:45', '2022-04-07 16:48:45'),
(147, 'V-TECH', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:48:57', NULL, NULL, NULL, NULL, '2022-04-07 16:48:57', '2022-04-07 16:48:57'),
(148, 'JETECH', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:49:20', NULL, NULL, NULL, NULL, '2022-04-07 16:49:20', '2022-04-07 16:49:20'),
(149, 'NEWHOLY', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:49:31', NULL, NULL, NULL, NULL, '2022-04-07 16:49:31', '2022-04-07 16:49:31'),
(150, 'BRITOOL', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:49:41', NULL, NULL, NULL, NULL, '2022-04-07 16:49:41', '2022-04-07 16:49:41'),
(151, 'TUV', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:49:51', NULL, NULL, NULL, NULL, '2022-04-07 16:49:51', '2022-04-07 16:49:51'),
(152, 'TMT', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:50:01', NULL, NULL, NULL, NULL, '2022-04-07 16:50:01', '2022-04-07 16:50:01'),
(153, 'FUKONG', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:50:13', NULL, NULL, NULL, NULL, '2022-04-07 16:50:13', '2022-04-07 16:50:13'),
(154, 'PILOT', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:50:20', NULL, NULL, NULL, NULL, '2022-04-07 16:50:20', '2022-04-07 16:50:20'),
(155, 'RBS', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:50:32', NULL, NULL, NULL, NULL, '2022-04-07 16:50:32', '2022-04-07 16:50:32'),
(156, 'BILLIONAIRE', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:50:36', NULL, NULL, NULL, NULL, '2022-04-07 16:50:36', '2022-04-07 16:50:36'),
(157, 'STANLEY', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:50:47', NULL, NULL, NULL, NULL, '2022-04-07 16:50:47', '2022-04-07 16:50:47'),
(158, 'PHILLIPS', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:50:59', NULL, NULL, NULL, NULL, '2022-04-07 16:50:59', '2022-04-07 16:50:59'),
(159, 'HUANG', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:51:07', NULL, NULL, NULL, NULL, '2022-04-07 16:51:07', '2022-04-07 16:51:07'),
(160, 'TAIWAN', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:51:16', NULL, NULL, NULL, NULL, '2022-04-07 16:51:16', '2022-04-07 16:51:16'),
(161, 'DALI', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:51:29', NULL, NULL, NULL, NULL, '2022-04-07 16:51:29', '2022-04-07 16:51:29'),
(162, 'DALI / RING', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:51:50', NULL, NULL, NULL, NULL, '2022-04-07 16:51:50', '2022-04-07 16:51:50'),
(163, 'RUBICON', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:52:01', NULL, NULL, NULL, NULL, '2022-04-07 16:52:01', '2022-04-07 16:52:01'),
(164, 'TAJIMA', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:52:17', NULL, NULL, NULL, NULL, '2022-04-07 16:52:17', '2022-04-07 16:52:17'),
(165, 'CHAMPION', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:52:37', NULL, NULL, NULL, NULL, '2022-04-07 16:52:37', '2022-04-07 16:52:37'),
(166, 'LINE COLOUR', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:52:43', NULL, NULL, NULL, NULL, '2022-04-07 16:52:43', '2022-04-07 16:52:43'),
(167, 'CROSSPOINT', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:52:49', NULL, NULL, NULL, NULL, '2022-04-07 16:52:49', '2022-04-07 16:52:49'),
(168, 'DOUBLE SPIN', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:52:55', NULL, NULL, NULL, NULL, '2022-04-07 16:52:55', '2022-04-07 16:52:55'),
(169, 'LANCER', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:53:03', NULL, NULL, NULL, NULL, '2022-04-07 16:53:03', '2022-04-07 16:53:03'),
(170, 'XITELI', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:53:11', NULL, NULL, NULL, NULL, '2022-04-07 16:53:11', '2022-04-07 16:53:11'),
(171, 'BLACK', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:53:40', NULL, NULL, NULL, NULL, '2022-04-07 16:53:40', '2022-04-07 16:53:40'),
(172, 'MTV', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:53:46', NULL, NULL, NULL, NULL, '2022-04-07 16:53:46', '2022-04-07 16:53:46'),
(173, 'EDESSO', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:54:04', NULL, NULL, NULL, NULL, '2022-04-07 16:54:04', '2022-04-07 16:54:04'),
(174, 'MOSAY', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:54:14', NULL, NULL, NULL, NULL, '2022-04-07 16:54:14', '2022-04-07 16:54:14'),
(175, 'TOOLMAX', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:54:20', NULL, NULL, NULL, NULL, '2022-04-07 16:54:20', '2022-04-07 16:54:20'),
(176, 'ADVANCE', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:54:27', NULL, NULL, NULL, NULL, '2022-04-07 16:54:27', '2022-04-07 16:54:27'),
(177, 'TJ', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:54:33', NULL, NULL, NULL, NULL, '2022-04-07 16:54:33', '2022-04-07 16:54:33'),
(178, 'KRC', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:54:45', NULL, NULL, NULL, NULL, '2022-04-07 16:54:45', '2022-04-07 16:54:45'),
(179, 'GOLD ELEPHANT', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:54:59', NULL, NULL, NULL, NULL, '2022-04-07 16:54:59', '2022-04-07 16:54:59'),
(180, 'DAHUA', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:55:09', NULL, NULL, NULL, NULL, '2022-04-07 16:55:09', '2022-04-07 16:55:09'),
(181, 'SUNBIRD', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:55:16', NULL, NULL, NULL, NULL, '2022-04-07 16:55:16', '2022-04-07 16:55:16'),
(182, 'YONGTAI', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:55:29', NULL, NULL, NULL, NULL, '2022-04-07 16:55:29', '2022-04-07 16:55:29'),
(183, 'GOLDLION', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:55:33', NULL, NULL, NULL, NULL, '2022-04-07 16:55:33', '2022-04-07 16:55:33'),
(184, 'SUPERPOWER', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:55:58', NULL, NULL, NULL, NULL, '2022-04-07 16:55:58', '2022-04-07 16:55:58'),
(185, 'IPRIX', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:56:23', NULL, NULL, NULL, NULL, '2022-04-07 16:56:23', '2022-04-07 16:56:23'),
(186, 'RC', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:56:44', NULL, NULL, NULL, NULL, '2022-04-07 16:56:44', '2022-04-07 16:56:44'),
(187, 'D HORSE', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:57:04', NULL, NULL, NULL, NULL, '2022-04-07 16:57:04', '2022-04-07 16:57:04'),
(188, 'NOKIA SHAFIQUE', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:57:52', NULL, NULL, NULL, NULL, '2022-04-07 16:57:52', '2022-04-07 16:57:52'),
(189, 'FINE MOULD', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:57:56', NULL, NULL, NULL, NULL, '2022-04-07 16:57:56', '2022-04-07 16:57:56'),
(190, 'PEGASUS', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:58:16', NULL, NULL, NULL, NULL, '2022-04-07 16:58:16', '2022-04-07 16:58:16'),
(191, 'YELLOW', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:58:28', NULL, NULL, NULL, NULL, '2022-04-07 16:58:28', '2022-04-07 16:58:28'),
(192, 'HILTI', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:58:37', NULL, NULL, NULL, NULL, '2022-04-07 16:58:37', '2022-04-07 16:58:37'),
(193, 'HSS', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:58:45', NULL, NULL, NULL, NULL, '2022-04-07 16:58:45', '2022-04-07 16:58:45'),
(194, 'JK', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:58:54', NULL, NULL, NULL, NULL, '2022-04-07 16:58:54', '2022-04-07 16:58:54'),
(195, 'MASONARY', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:59:00', NULL, NULL, NULL, NULL, '2022-04-07 16:59:00', '2022-04-07 16:59:00'),
(196, 'SS', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:59:11', NULL, NULL, NULL, NULL, '2022-04-07 16:59:11', '2022-04-07 16:59:11'),
(197, 'GASTECH', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:59:27', NULL, NULL, NULL, NULL, '2022-04-07 16:59:27', '2022-04-07 16:59:27'),
(198, 'TEKNO', 'no_image.png', 'Active', 'No', 4, '2022-04-07 11:59:58', NULL, NULL, NULL, NULL, '2022-04-07 16:59:58', '2022-04-07 16:59:58'),
(199, 'OSAKA', 'no_image.png', 'Active', 'No', 4, '2022-04-07 12:00:48', NULL, NULL, NULL, NULL, '2022-04-07 17:00:48', '2022-04-07 17:00:48'),
(200, 'SUNLON', 'no_image.png', 'Active', 'No', 4, '2022-04-07 12:00:53', NULL, NULL, NULL, NULL, '2022-04-07 17:00:53', '2022-04-07 17:00:53'),
(201, 'MASTER', 'no_image.png', 'Active', 'No', 4, '2022-04-07 12:01:03', NULL, NULL, NULL, NULL, '2022-04-07 17:01:03', '2022-04-07 17:01:03'),
(202, 'PRETECH', 'no_image.png', 'Active', 'No', 4, '2022-04-07 12:01:23', NULL, NULL, NULL, NULL, '2022-04-07 17:01:23', '2022-04-07 17:01:23'),
(203, 'CAMEL', 'no_image.png', 'Active', 'No', 4, '2022-04-07 12:01:38', NULL, NULL, NULL, NULL, '2022-04-07 17:01:38', '2022-04-07 17:01:38'),
(204, 'MARKSMAN', 'no_image.png', 'Active', 'No', 4, '2022-04-07 12:01:44', NULL, NULL, NULL, NULL, '2022-04-07 17:01:44', '2022-04-07 17:01:44'),
(205, 'FIRST', 'no_image.png', 'Active', 'No', 4, '2022-04-07 12:01:49', NULL, NULL, NULL, NULL, '2022-04-07 17:01:49', '2022-04-07 17:01:49'),
(206, 'MS', 'no_image.png', 'Active', 'No', 4, '2022-04-07 12:02:15', NULL, NULL, NULL, NULL, '2022-04-07 17:02:15', '2022-04-07 17:02:15'),
(207, 'Sencan-Deleted-207', 'no_image.png', 'Active', 'Yes', 4, '2022-04-09 15:29:59', NULL, NULL, 4, '2022-04-09 15:30:34', '2022-04-09 20:29:59', '2022-04-09 20:30:34'),
(208, 'SENCAN', 'no_image.png', 'Active', 'No', 4, '2022-04-09 15:30:41', NULL, NULL, NULL, NULL, '2022-04-09 20:30:41', '2022-04-09 20:30:41'),
(209, 'LIBERTY', 'no_image.png', 'Active', 'No', 4, '2022-04-10 15:51:30', NULL, NULL, NULL, NULL, '2022-04-10 20:51:30', '2022-04-10 20:51:30'),
(210, 'GOLDEN DOVE', 'no_image.png', 'Active', 'No', 4, '2022-04-10 15:51:39', NULL, NULL, NULL, NULL, '2022-04-10 20:51:39', '2022-04-10 20:51:39'),
(211, 'KAMRY', 'no_image.png', 'Active', 'No', 4, '2022-04-11 15:41:43', NULL, NULL, NULL, NULL, '2022-04-11 20:41:43', '2022-04-11 20:41:43'),
(212, 'DC', 'no_image.png', 'Active', 'No', 4, '2022-04-12 12:40:43', NULL, NULL, NULL, NULL, '2022-04-12 17:40:43', '2022-04-12 17:40:43'),
(213, 'GRAMEEN ROCKET', 'no_image.png', 'Active', 'No', 4, '2022-04-12 12:43:02', NULL, NULL, NULL, NULL, '2022-04-12 17:43:02', '2022-04-12 17:43:02'),
(214, 'SAFEMATE', 'no_image.png', 'Active', 'No', 4, '2022-04-12 15:16:17', NULL, NULL, NULL, NULL, '2022-04-12 20:16:17', '2022-04-12 20:16:17'),
(215, 'HUNTER', 'no_image.png', 'Active', 'No', 4, '2022-04-12 15:16:47', NULL, NULL, NULL, NULL, '2022-04-12 20:16:47', '2022-04-12 20:16:47'),
(216, 'JP', 'no_image.png', 'Active', 'No', 4, '2022-04-12 15:20:58', NULL, NULL, NULL, NULL, '2022-04-12 20:20:58', '2022-04-12 20:20:58'),
(217, 'TOP-Deleted-217', 'no_image.png', 'Active', 'Yes', 4, '2022-04-12 15:29:21', NULL, NULL, 4, '2022-04-12 15:29:33', '2022-04-12 20:29:21', '2022-04-12 20:29:33'),
(218, 'FIXMAN', 'no_image.png', 'Active', 'No', 4, '2022-04-14 10:35:56', NULL, NULL, NULL, NULL, '2022-04-14 15:35:56', '2022-04-14 15:35:56'),
(219, 'JAPAN', 'no_image.png', 'Active', 'No', 4, '2022-04-18 15:52:43', NULL, NULL, NULL, NULL, '2022-04-18 20:52:43', '2022-04-18 20:52:43'),
(220, 'GREEN', 'no_image.png', 'Active', 'No', 4, '2022-04-23 11:03:44', NULL, NULL, NULL, NULL, '2022-04-23 16:03:44', '2022-04-23 16:03:44'),
(221, 'SME', 'no_image.png', 'Active', 'No', 4, '2022-04-23 11:16:04', NULL, NULL, NULL, NULL, '2022-04-23 16:16:04', '2022-04-23 16:16:04'),
(222, 'KING', 'no_image.png', 'Active', 'No', 4, '2022-04-24 10:20:23', NULL, NULL, NULL, NULL, '2022-04-24 15:20:23', '2022-04-24 15:20:23'),
(223, 'Nhon Hoa', 'no_image.png', 'Active', 'No', 4, '2022-05-12 11:22:44', NULL, NULL, NULL, NULL, '2022-05-12 16:22:44', '2022-05-12 16:22:44'),
(224, 'Super Scanner', 'no_image.png', 'Active', 'No', 4, '2022-05-12 12:08:23', NULL, NULL, NULL, NULL, '2022-05-12 17:08:23', '2022-05-12 17:08:23'),
(225, 'Solo', 'no_image.png', 'Active', 'No', 4, '2022-05-12 12:13:59', NULL, NULL, NULL, NULL, '2022-05-12 17:13:59', '2022-05-12 17:13:59'),
(226, 'Wiser', 'no_image.png', 'Active', 'No', 4, '2022-05-14 10:33:10', NULL, NULL, NULL, NULL, '2022-05-14 15:33:10', '2022-05-14 15:33:10'),
(227, 'Dunsk', 'no_image.png', 'Active', 'No', 4, '2022-05-14 10:33:20', NULL, NULL, NULL, NULL, '2022-05-14 15:33:20', '2022-05-14 15:33:20'),
(228, 'Sds', 'no_image.png', 'Active', 'No', 4, '2022-05-14 10:33:38', NULL, NULL, NULL, NULL, '2022-05-14 15:33:38', '2022-05-14 15:33:38'),
(229, 'Fangdr', 'no_image.png', 'Active', 'No', 4, '2022-05-14 10:34:07', NULL, NULL, NULL, NULL, '2022-05-14 15:34:07', '2022-05-14 15:34:07'),
(230, 'Nokia', 'no_image.png', 'Active', 'No', 4, '2022-05-14 10:34:19', NULL, NULL, NULL, NULL, '2022-05-14 15:34:19', '2022-05-14 15:34:19'),
(231, 'Swag', 'no_image.png', 'Active', 'No', 4, '2022-05-15 10:20:23', NULL, NULL, NULL, NULL, '2022-05-15 15:20:23', '2022-05-15 15:20:23'),
(232, 'Venus', 'no_image.png', 'Active', 'No', 4, '2022-05-15 10:27:47', NULL, NULL, NULL, NULL, '2022-05-15 15:27:47', '2022-05-15 15:27:47'),
(233, 'Raider Max', 'no_image.png', 'Active', 'No', 4, '2022-05-15 10:28:01', NULL, NULL, NULL, NULL, '2022-05-15 15:28:01', '2022-05-15 15:28:01'),
(234, 'Crownman', 'no_image.png', 'Active', 'No', 4, '2022-05-15 10:40:00', NULL, NULL, NULL, NULL, '2022-05-15 15:40:00', '2022-05-15 15:40:00'),
(235, 'SRC', 'no_image.png', 'Active', 'No', 4, '2022-05-16 15:23:01', NULL, NULL, NULL, NULL, '2022-05-16 20:23:01', '2022-05-16 20:23:01'),
(236, 'Aviation', 'no_image.png', 'Active', 'No', 4, '2022-05-16 15:23:49', NULL, NULL, NULL, NULL, '2022-05-16 20:23:49', '2022-05-16 20:23:49'),
(237, 'Five Star', 'no_image.png', 'Active', 'No', 4, '2022-05-18 15:22:55', NULL, NULL, NULL, NULL, '2022-05-18 20:22:55', '2022-05-18 20:22:55'),
(238, 'Sboky', 'no_image.png', 'Active', 'No', 4, '2022-05-18 15:36:06', NULL, NULL, NULL, NULL, '2022-05-18 20:36:06', '2022-05-18 20:36:06'),
(239, 'Jyot', 'no_image.png', 'Active', 'No', 4, '2022-05-22 11:28:17', NULL, NULL, NULL, NULL, '2022-05-22 16:28:17', '2022-05-22 16:28:17'),
(240, 'Jianghua', 'no_image.png', 'Active', 'No', 4, '2022-05-22 15:08:11', NULL, NULL, NULL, NULL, '2022-05-22 20:08:11', '2022-05-22 20:08:11'),
(241, 'Fuxiang', 'no_image.png', 'Active', 'No', 4, '2022-05-24 15:11:34', NULL, NULL, NULL, NULL, '2022-05-24 20:11:34', '2022-05-24 20:11:34'),
(242, 'Wellborn', 'no_image.png', 'Active', 'No', 4, '2022-05-24 15:18:42', NULL, NULL, NULL, NULL, '2022-05-24 20:18:42', '2022-05-24 20:18:42'),
(243, 'German', 'no_image.png', 'Active', 'No', 4, '2022-05-24 15:19:10', NULL, NULL, NULL, NULL, '2022-05-24 20:19:10', '2022-05-24 20:19:10'),
(244, 'Aiwa', 'no_image.png', 'Active', 'No', 4, '2022-05-24 15:37:04', NULL, NULL, NULL, NULL, '2022-05-24 20:37:04', '2022-05-24 20:37:04'),
(245, 'Gs King Tool', 'no_image.png', 'Active', 'No', 4, '2022-05-24 15:37:17', NULL, NULL, NULL, NULL, '2022-05-24 20:37:17', '2022-05-24 20:37:17'),
(246, 'Fukung', 'no_image.png', 'Active', 'No', 4, '2022-05-24 15:37:35', NULL, NULL, NULL, NULL, '2022-05-24 20:37:35', '2022-05-24 20:37:35'),
(247, 'Tow Axe', 'no_image.png', 'Active', 'No', 4, '2022-05-24 15:40:43', NULL, NULL, NULL, NULL, '2022-05-24 20:40:43', '2022-05-24 20:40:43'),
(248, 'Zhudeuji', 'no_image.png', 'Active', 'No', 4, '2022-06-01 11:17:02', 4, '2022-06-01 11:17:11', NULL, NULL, '2022-06-01 16:17:02', '2022-06-01 16:17:11'),
(249, 'Shiqiang', 'no_image.png', 'Active', 'No', 4, '2022-06-01 12:27:55', NULL, NULL, NULL, NULL, '2022-06-01 17:27:55', '2022-06-01 17:27:55'),
(250, 'Atlas', 'no_image.png', 'Active', 'No', 4, '2022-06-01 12:28:14', NULL, NULL, NULL, NULL, '2022-06-01 17:28:14', '2022-06-01 17:28:14'),
(251, 'Doublespin', 'no_image.png', 'Active', 'No', 4, '2022-06-01 12:28:30', NULL, NULL, NULL, NULL, '2022-06-01 17:28:30', '2022-06-01 17:28:30'),
(252, 'Black Bcar', 'no_image.png', 'Active', 'No', 4, '2022-06-01 12:30:48', NULL, NULL, NULL, NULL, '2022-06-01 17:30:48', '2022-06-01 17:30:48'),
(253, 'EHBL', 'no_image.png', 'Active', 'No', 4, '2022-06-01 12:35:31', NULL, NULL, NULL, NULL, '2022-06-01 17:35:31', '2022-06-01 17:35:31'),
(254, 'Unique', 'no_image.png', 'Active', 'No', 4, '2022-06-01 15:41:58', NULL, NULL, NULL, NULL, '2022-06-01 20:41:58', '2022-06-01 20:41:58'),
(255, 'Sambo', 'no_image.png', 'Active', 'No', 4, '2022-06-01 15:51:04', NULL, NULL, NULL, NULL, '2022-06-01 20:51:04', '2022-06-01 20:51:04'),
(256, 'PENGGONG', 'no_image.png', 'Active', 'No', 4, '2022-06-02 10:55:19', NULL, NULL, NULL, NULL, '2022-06-02 15:55:19', '2022-06-02 15:55:19'),
(257, 'SK', 'no_image.png', 'Active', 'No', 4, '2022-06-02 10:56:42', NULL, NULL, NULL, NULL, '2022-06-02 15:56:42', '2022-06-02 15:56:42'),
(258, 'Dayton', 'no_image.png', 'Active', 'No', 4, '2022-06-02 11:02:40', NULL, NULL, NULL, NULL, '2022-06-02 16:02:40', '2022-06-02 16:02:40'),
(259, 'Oraska', 'no_image.png', 'Active', 'No', 4, '2022-06-11 11:14:17', NULL, NULL, NULL, NULL, '2022-06-11 16:14:17', '2022-06-11 16:14:17'),
(260, 'UK', 'no_image.png', 'Active', 'No', 4, '2022-06-11 15:04:22', NULL, NULL, NULL, NULL, '2022-06-11 20:04:22', '2022-06-11 20:04:22'),
(261, '3M', 'no_image.png', 'Active', 'No', 4, '2022-06-11 15:06:50', NULL, NULL, NULL, NULL, '2022-06-11 20:06:50', '2022-06-11 20:06:50'),
(262, 'Froklip', 'no_image.png', 'Active', 'No', 4, '2022-06-11 15:54:05', NULL, NULL, NULL, NULL, '2022-06-11 20:54:05', '2022-06-11 20:54:05'),
(263, 'STH', 'no_image.png', 'Active', 'No', 4, '2022-06-11 16:44:33', NULL, NULL, NULL, NULL, '2022-06-11 21:44:33', '2022-06-11 21:44:33'),
(264, 'Sun', 'no_image.png', 'Active', 'No', 4, '2022-06-12 13:53:03', NULL, NULL, NULL, NULL, '2022-06-12 18:53:03', '2022-06-12 18:53:03'),
(265, 'Cystel', 'no_image.png', 'Active', 'No', 4, '2022-06-12 13:55:23', NULL, NULL, NULL, NULL, '2022-06-12 18:55:23', '2022-06-12 18:55:23'),
(266, 'Comandar', 'no_image.png', 'Active', 'No', 4, '2022-06-12 13:55:42', NULL, NULL, NULL, NULL, '2022-06-12 18:55:42', '2022-06-12 18:55:42'),
(267, 'Handcare', 'no_image.png', 'Active', 'No', 4, '2022-06-12 13:56:38', NULL, NULL, NULL, NULL, '2022-06-12 18:56:38', '2022-06-12 18:56:38'),
(268, 'Extrem', 'no_image.png', 'Active', 'No', 4, '2022-06-12 17:11:25', NULL, NULL, NULL, NULL, '2022-06-12 22:11:25', '2022-06-12 22:11:25');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`, `status`, `deleted`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted_by`, `deleted_date`, `created_at`, `updated_at`) VALUES
(1, 'Safety Items', '1648103950Occupational_Safety_Equipment.jpg', 'Active', 'No', 21, '2022-03-24 12:39:10', 21, '2022-03-24 12:45:40', NULL, NULL, '2022-03-24 17:39:10', '2022-03-24 17:45:40'),
(2, 'Marin Life Saving Equipments', '164810408727Lifesaving-Equipment-150730112011_result.jpg', 'Active', 'No', 21, '2022-03-24 12:41:27', NULL, NULL, NULL, NULL, '2022-03-24 17:41:27', '2022-03-24 17:41:27'),
(3, 'Fire Control System', '1648104165Industrial-Fire-Fighting-Valves-1-400x356.jpg', 'Active', 'No', 21, '2022-03-24 12:42:45', NULL, NULL, NULL, NULL, '2022-03-24 17:42:45', '2022-03-24 17:42:45'),
(4, 'Fire Fighting Equipments', '1648104230fire-equipment-1518757681-3652635.jpg', 'Active', 'No', 21, '2022-03-24 12:43:50', NULL, NULL, NULL, NULL, '2022-03-24 17:43:50', '2022-03-24 17:43:50'),
(5, 'Hardware Items', '1648104317new-safety-hardware-pic-1.jpg', 'Active', 'No', 21, '2022-03-24 12:45:17', NULL, NULL, NULL, NULL, '2022-03-24 17:45:17', '2022-03-24 17:45:17'),
(6, 'Valves', '1648557087non-rising-stem-gate-valve-500x500.jpg', 'Active', 'No', 2, '2022-03-29 18:31:27', NULL, NULL, NULL, NULL, '2022-03-29 23:31:27', '2022-03-29 23:31:27'),
(7, 'Test Category', 'no_image.png', 'Active', 'No', 1, '2022-04-02 20:18:10', NULL, NULL, NULL, NULL, '2022-04-03 01:18:10', '2022-04-03 01:18:10'),
(8, 'Power Tools', 'no_image.png', 'Active', 'No', 4, '2022-04-06 15:26:50', NULL, NULL, NULL, NULL, '2022-04-06 20:26:50', '2022-04-06 20:26:50'),
(9, 'BOSCH-Deleted-9', 'no_image.png', 'Active', 'Yes', 4, '2022-04-06 16:05:47', NULL, NULL, 4, '2022-04-06 16:06:06', '2022-04-06 21:05:47', '2022-04-06 21:06:06'),
(10, 'Welding Equipments', '1649304083welding-equipments-500x500.jpg', 'Active', 'No', 4, '2022-04-07 10:01:23', 4, '2022-04-07 10:04:28', NULL, NULL, '2022-04-07 15:01:23', '2022-04-07 15:04:28'),
(11, 'Welghing Scales', 'no_image.png', 'Active', 'No', 4, '2022-04-07 10:05:18', NULL, NULL, NULL, NULL, '2022-04-07 15:05:18', '2022-04-07 15:05:18'),
(12, 'Ladders', 'no_image.png', 'Active', 'No', 4, '2022-04-07 10:06:22', NULL, NULL, NULL, NULL, '2022-04-07 15:06:22', '2022-04-07 15:06:22'),
(13, 'Jubilee Clamp', 'no_image.png', 'Active', 'No', 4, '2022-04-07 10:06:54', NULL, NULL, NULL, NULL, '2022-04-07 15:06:54', '2022-04-07 15:06:54'),
(14, 'Hand Tools', 'no_image.png', 'Active', 'No', 4, '2022-04-07 10:07:16', NULL, NULL, NULL, NULL, '2022-04-07 15:07:16', '2022-04-07 15:07:16'),
(15, 'Screwdrivers', 'no_image.png', 'Active', 'No', 4, '2022-04-07 10:08:17', NULL, NULL, NULL, NULL, '2022-04-07 15:08:17', '2022-04-07 15:08:17'),
(16, 'Cutting & Grinding Discs', 'no_image.png', 'Active', 'No', 4, '2022-04-07 10:09:38', NULL, NULL, NULL, NULL, '2022-04-07 15:09:38', '2022-04-07 15:09:38'),
(17, 'Drill Bits', 'no_image.png', 'Active', 'No', 4, '2022-04-07 10:10:13', NULL, NULL, NULL, NULL, '2022-04-07 15:10:13', '2022-04-07 15:10:13'),
(18, 'Tapes', 'no_image.png', 'Active', 'No', 4, '2022-04-07 10:10:53', NULL, NULL, NULL, NULL, '2022-04-07 15:10:53', '2022-04-07 15:10:53');

-- --------------------------------------------------------

--
-- Table structure for table `company_settings`
--

CREATE TABLE `company_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month_year` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_header` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_footer` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `watermark` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manage_stock_to_sale` enum('Yes','No') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Yes',
  `barcode_exists` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_settings`
--

INSERT INTO `company_settings` (`id`, `logo`, `name`, `email`, `phone`, `currency`, `address`, `website`, `month_year`, `report_header`, `report_footer`, `watermark`, `manage_stock_to_sale`, `barcode_exists`, `deleted`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted_by`, `deleted_date`, `created_at`, `updated_at`) VALUES
(1, '1657784202Empira_Compressed-350x350.jpg', 'Example Company', 'example@gmail.com', '0181808080', 'Taka', 'GEC Circle, CTG', 'www.example.com', 'F-Y', 'company_report_header', 'company_report_footer', NULL, 'No', 'No', NULL, NULL, NULL, 1, '2022-07-14 13:36:42', NULL, NULL, '2022-07-05 09:52:26', '2022-07-14 07:36:42');

-- --------------------------------------------------------

--
-- Table structure for table `daily_reports`
--

CREATE TABLE `daily_reports` (
  `id` bigint(20) NOT NULL,
  `date` date DEFAULT NULL,
  `previous_closing` bigint(20) DEFAULT NULL,
  `today_closing` bigint(20) DEFAULT NULL COMMENT 'today balance',
  `opening_balance` bigint(20) DEFAULT NULL COMMENT '(previous day balance + today balance) = opening_balance',
  `created_at` timestamp(6) NULL DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `deleted` enum('Yes','No') NOT NULL DEFAULT 'No',
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` date DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `updated_at` varchar(255) DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `daily_reports`
--

INSERT INTO `daily_reports` (`id`, `date`, `previous_closing`, `today_closing`, `opening_balance`, `created_at`, `created_by`, `deleted`, `deleted_by`, `deleted_date`, `status`, `updated_at`, `updated_by`) VALUES
(1, '2022-06-20', 0, 84110, 84110, '2022-06-28 16:59:38.000000', 4, 'No', NULL, NULL, 'Active', '2022-06-28 11:59:38', NULL),
(2, '2022-06-21', 84110, 170340, 254450, '2022-06-28 17:01:55.000000', 4, 'No', NULL, NULL, 'Active', '2022-06-28 12:01:55', NULL),
(3, '2022-06-22', 254450, 150930, 405380, '2022-06-28 17:02:49.000000', 4, 'No', NULL, NULL, 'Active', '2022-06-28 12:02:49', NULL),
(4, '2022-06-23', 405380, -139300, 266080, '2022-06-28 17:03:54.000000', 4, 'No', NULL, NULL, 'Active', '2022-06-28 12:03:54', NULL),
(5, '2022-06-25', 266080, 40500, 306580, '2022-06-28 17:04:04.000000', 4, 'No', NULL, NULL, 'Active', '2022-06-28 12:04:04', NULL),
(6, '2022-06-26', 306580, -364000, -57420, '2022-06-28 17:05:24.000000', 4, 'No', NULL, NULL, 'Active', '2022-06-28 12:05:24', NULL),
(7, '2022-06-27', -57420, 53200, -4220, '2022-06-28 17:05:37.000000', 4, 'No', NULL, NULL, 'Active', '2022-06-28 12:05:37', NULL),
(8, '2022-06-28', -4220, 0, -4220, '2022-06-29 15:51:39.000000', 4, 'No', NULL, NULL, 'Active', '2022-06-29 10:51:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `damage_products`
--

CREATE TABLE `damage_products` (
  `id` bigint(20) NOT NULL,
  `products_id` bigint(20) NOT NULL,
  `warehouse_id` bigint(20) NOT NULL,
  `damage_quantity` int(11) NOT NULL,
  `remarks` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `damage_date` date NOT NULL,
  `damage_order_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `damage_products`
--

INSERT INTO `damage_products` (`id`, `products_id`, `warehouse_id`, `damage_quantity`, `remarks`, `damage_date`, `damage_order_no`, `created_by`, `deleted_date`, `deleted_by`, `deleted`, `created_at`, `updated_at`, `created_date`) VALUES
(1, 120, 2, 3, 'This is test discount', '2022-06-18', '000001', 1, '2022-06-19 20:33:25', 1, 'Yes', '2022-06-19 02:27:16', '2022-06-20 01:33:25', '2022-06-18 21:27:16');

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visiblity` enum('Seen','Unseen') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seen_by` bigint(20) DEFAULT NULL,
  `reply_message_subject` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reply_message` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reply_message_date` datetime DEFAULT NULL,
  `replied_by` bigint(20) DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `importance` enum('VeryImportant','Important','LessImportant') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emi_sales`
--

CREATE TABLE `emi_sales` (
  `id` int(11) NOT NULL,
  `sale_id` bigint(20) DEFAULT NULL,
  `total_price` bigint(20) DEFAULT NULL,
  `dues_amount` bigint(20) DEFAULT NULL,
  `no_of_tenure` bigint(20) DEFAULT NULL,
  `start_date` varchar(255) DEFAULT NULL,
  `per_tenur_amount` decimal(18,2) DEFAULT NULL,
  `serial` bigint(20) DEFAULT NULL,
  `tenure_payment_date` varchar(255) DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `adjust_amount` varchar(255) DEFAULT NULL,
  `is_paid` enum('Yes','No','Adjusted') NOT NULL DEFAULT 'No',
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `deleted` enum('Yes','No') NOT NULL DEFAULT 'No',
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  `updated_at` varchar(255) DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) NOT NULL,
  `expense_date` date DEFAULT NULL,
  `expense_cause` text NOT NULL,
  `salary_month` varchar(20) DEFAULT NULL,
  `expense_type_id` bigint(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `tbl_user_id` bigint(20) NOT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `deleted` enum('Yes','No') NOT NULL DEFAULT 'No',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `expense_date`, `expense_cause`, `salary_month`, `expense_type_id`, `amount`, `tbl_user_id`, `created_by`, `created_date`, `deleted_by`, `deleted_date`, `updated_by`, `updated_date`, `status`, `deleted`, `created_at`, `updated_at`) VALUES
(1, '2022-06-29', 'Total Expense 11/06/22 From 27/06/22', NULL, 2, '41700.00', 4, 4, '2022-06-29 10:50:45', NULL, NULL, NULL, NULL, 'Active', 'No', '2022-06-29 15:50:45', '2022-06-29 15:50:45');

-- --------------------------------------------------------

--
-- Table structure for table `expense_types`
--

CREATE TABLE `expense_types` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `deleted` enum('Yes','No') NOT NULL DEFAULT 'No',
  `created_by` bigint(20) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `expense_types`
--

INSERT INTO `expense_types` (`id`, `name`, `status`, `deleted`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted_by`, `deleted_date`, `created_at`, `updated_at`) VALUES
(1, 'Nasta', 'Active', 'No', 4, '2022-06-16 12:52:20', NULL, NULL, NULL, NULL, '2022-06-16 17:52:20', '2022-06-16 17:52:20'),
(2, 'Transport', 'Active', 'No', 4, '2022-06-16 12:53:24', NULL, NULL, NULL, NULL, '2022-06-16 17:53:24', '2022-06-16 17:53:24'),
(3, 'Nazrul makam', 'Active', 'No', 4, '2022-06-16 12:53:54', NULL, NULL, NULL, NULL, '2022-06-16 17:53:54', '2022-06-16 17:53:54'),
(4, 'Salary', 'Active', 'No', 4, '2022-06-16 12:54:23', NULL, NULL, NULL, NULL, '2022-06-16 17:54:23', '2022-06-16 17:54:23'),
(5, 'Printed & Photocopy', 'Active', 'No', 4, '2022-06-19 15:18:28', NULL, NULL, NULL, NULL, '2022-06-19 20:18:28', '2022-06-19 20:18:28');

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `facility_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_id` bigint(20) DEFAULT NULL,
  `amount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lower_limit` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upper_limit` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `last_updated_by` bigint(20) DEFAULT NULL,
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `facility_name`, `group_id`, `amount`, `location`, `lower_limit`, `upper_limit`, `created_by`, `last_updated_by`, `deleted`, `deleted_by`, `deleted_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Alitech facility', 24, '500', 'Chittagong Metro', '100.00', '2000.00', 1, 1, 'No', NULL, NULL, 'Active', '2022-07-05 13:24:53', '2022-07-07 05:25:32'),
(2, 'Alitech facility2', 24, '400', 'Others', '50.00', '500.00', 1, 1, 'No', NULL, NULL, 'Active', '2022-07-05 13:25:45', '2022-07-07 05:36:45'),
(3, 'Alitech facility3', 24, '450', 'Chittagong Metro', '150.00', '4500.00', 1, 1, 'No', NULL, NULL, 'Active', '2022-07-05 13:27:24', '2022-07-05 13:28:21'),
(4, 'Alitech facility4', 24, '5000', 'Others', '2500.00', '15000.00', 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-07 05:28:54', '2022-07-07 05:28:54'),
(5, 'House Rent', 24, '00', 'Chittagong Metro', '5000.00', '25000.00', 1, 1, 'No', NULL, NULL, 'Active', '2022-07-14 06:26:53', '2022-07-14 06:45:45'),
(6, 'medical', 24, '00', 'Chittagong Metro', '0.00', '0.00', 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-14 06:46:44', '2022-07-14 06:46:44'),
(7, 'providentFund', 24, '00', 'Chittagong Metro', '0.00', '0.00', 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-14 06:47:05', '2022-07-14 06:47:05'),
(8, 'Company Contribution', 24, '00', 'Chittagong Metro', '0.00', '0.00', 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-14 06:47:37', '2022-07-14 06:47:37'),
(9, 'Alitech facility 45', 24, '1250', 'Chittagong Metro', '550.00', '2500.00', 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-19 06:24:01', '2022-07-19 06:24:01'),
(10, 'House Rent', 24, '25%', 'Chittagong Metro', '15%', '39%', 1, 1, 'No', NULL, NULL, 'Active', '2022-07-19 06:30:20', '2022-07-19 07:04:09'),
(11, 'medical', 24, '1200', 'Chittagong Metro', '550.00', '2500.00', 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-19 06:31:24', '2022-07-19 06:31:24');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `final_salary_sheets`
--

CREATE TABLE `final_salary_sheets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `month_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `saved_sheet_id` bigint(20) DEFAULT NULL,
  `employee_id` bigint(20) DEFAULT NULL,
  `joining_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sheet_id` bigint(20) DEFAULT NULL,
  `account_no` bigint(20) DEFAULT NULL,
  `consulate` decimal(12,2) DEFAULT NULL,
  `basic` decimal(12,2) DEFAULT NULL,
  `house_rent` decimal(12,2) DEFAULT NULL,
  `medical_allowence` decimal(12,2) DEFAULT NULL,
  `company_contribution` decimal(12,2) DEFAULT NULL,
  `laundry` decimal(12,2) DEFAULT NULL,
  `phone_bill` decimal(12,2) DEFAULT NULL,
  `ta_da` decimal(12,2) DEFAULT NULL,
  `provident_fund` decimal(12,2) DEFAULT NULL,
  `company_provident_fund` decimal(12,2) DEFAULT NULL,
  `adjustment` decimal(12,2) DEFAULT NULL,
  `step_amount` decimal(12,2) DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `due` decimal(12,2) DEFAULT NULL,
  `deduct_provident_fund` decimal(12,2) DEFAULT NULL,
  `loan_installment` decimal(12,2) DEFAULT NULL,
  `net_total` decimal(12,2) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `last_updated_by` bigint(20) DEFAULT NULL,
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `final_salary_sheets`
--

INSERT INTO `final_salary_sheets` (`id`, `month_year`, `saved_sheet_id`, `employee_id`, `joining_date`, `sheet_id`, `account_no`, `consulate`, `basic`, `house_rent`, `medical_allowence`, `company_contribution`, `laundry`, `phone_bill`, `ta_da`, `provident_fund`, `company_provident_fund`, `adjustment`, `step_amount`, `total`, `due`, `deduct_provident_fund`, `loan_installment`, `net_total`, `created_by`, `last_updated_by`, `deleted`, `deleted_by`, `deleted_date`, `status`, `created_at`, `updated_at`) VALUES
(5, 'July-2022', 3, 5, '2020-01-01', 7, 98498987987, '0.00', '20000.00', '0.00', '0.00', '0.00', '1200.00', '3000.00', '5000.00', '0.00', '0.00', '0.00', '20000.00', '29200.00', '0.00', '0.00', '0.00', '29200.00', 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-14 08:02:13', '2022-07-14 08:02:13'),
(6, 'July-2022', 3, 6, '2020-01-01', 7, 9874878787, '0.00', '20000.00', '0.00', '0.00', '0.00', '1200.00', '3500.00', '2000.00', '0.00', '0.00', '0.00', '20000.00', '26700.00', '0.00', '0.00', '0.00', '26700.00', 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-14 08:02:13', '2022-07-14 08:02:13'),
(7, 'July-2022', 3, 3, '2021-09-04', 7, 4984987477, '0.00', '20000.00', '0.00', '0.00', '0.00', '200.00', '500.00', '1200.00', '0.00', '0.00', '0.00', '20000.00', '21900.00', '0.00', '0.00', '0.00', '21900.00', 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-14 08:02:13', '2022-07-14 08:02:13'),
(8, 'July-2022', 3, 4, '2021-09-25', 7, 9898498484, '0.00', '20000.00', '0.00', '0.00', '0.00', '250.00', '450.00', '1200.00', '0.00', '0.00', '360.00', '20000.00', '22260.00', '0.00', '0.00', '0.00', '22260.00', 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-14 08:02:13', '2022-07-14 08:02:13'),
(9, 'August-2022', 4, 5, '2020-01-01', 7, 98498987987, '0.00', '20000.00', '0.00', '0.00', '0.00', '1200.00', '3000.00', '5000.00', '0.00', '0.00', '0.00', '20000.00', '29200.00', '0.00', '0.00', '0.00', '29200.00', 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-16 06:05:27', '2022-07-16 06:05:27'),
(10, 'August-2022', 4, 6, '2020-01-01', 7, 9874878787, '0.00', '20000.00', '0.00', '0.00', '0.00', '1200.00', '3500.00', '2000.00', '0.00', '0.00', '0.00', '20000.00', '26700.00', '0.00', '0.00', '0.00', '26700.00', 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-16 06:05:27', '2022-07-16 06:05:27'),
(11, 'August-2022', 4, 3, '2021-09-04', 7, 4984987477, '0.00', '20000.00', '0.00', '0.00', '0.00', '200.00', '500.00', '1200.00', '0.00', '0.00', '0.00', '20000.00', '21900.00', '0.00', '0.00', '3351.00', '18549.00', 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-16 06:05:27', '2022-07-16 06:05:27'),
(12, 'August-2022', 4, 4, '2021-09-25', 7, 9898498484, '0.00', '20000.00', '0.00', '0.00', '0.00', '250.00', '450.00', '1200.00', '0.00', '0.00', '0.00', '20000.00', '21900.00', '0.00', '0.00', '3025.00', '18875.00', 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-16 06:05:27', '2022-07-16 06:05:27');

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type_id` bigint(20) NOT NULL,
  `deleted` enum('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'No',
  `created_by` bigint(20) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `status` enum('Active','Inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `grade_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `steps_list` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_list` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `last_updated_by` bigint(20) DEFAULT NULL,
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT 'No',
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `grade_name`, `note`, `steps_list`, `employee_list`, `created_by`, `last_updated_by`, `deleted`, `deleted_by`, `deleted_date`, `status`, `created_at`, `updated_at`) VALUES
(27, 'Grade A', 'Most Senior Employees.', NULL, '8', 1, NULL, 'No', NULL, NULL, 'Active', '2021-10-23 07:44:24', '2021-10-23 07:44:24'),
(28, 'Grade B', 'Seniors', NULL, '8', 1, NULL, 'No', NULL, NULL, 'Active', '2021-10-23 07:49:59', '2021-10-23 07:49:59'),
(29, 'Grade C', 'Managers', NULL, '8', 1, NULL, 'No', NULL, NULL, 'Active', '2021-10-23 07:50:44', '2021-10-23 07:50:44'),
(30, 'Grade D', NULL, NULL, '8', 1, NULL, 'No', NULL, NULL, 'Active', '2021-10-23 07:51:04', '2021-10-23 07:51:04'),
(31, 'Grade 9', 'Officer Grade', NULL, '8', 1, NULL, 'No', NULL, NULL, 'Active', '2021-11-13 12:00:29', '2021-11-13 12:00:29'),
(32, 'Grade 8', 'Exp. Officer Grade', NULL, '8', 1, NULL, 'No', NULL, NULL, 'Active', '2021-11-13 12:00:50', '2021-11-13 12:00:50'),
(33, 'Grade 100', 'Test Here', NULL, '8', 21, 1, 'No', NULL, NULL, 'Active', '2021-11-21 14:34:30', '2022-07-05 12:54:30'),
(34, 'Ann Stewartdeleted34', 'Eveniet facilis vol', NULL, '8', 21, 21, 'Yes', 21, '2021-11-22 13:05:51', 'Inactive', '2021-11-22 07:05:32', '2021-11-22 07:05:51'),
(35, 'Cody Key', 'Voluptatem sunt ab', NULL, '8', 21, 1, 'No', NULL, NULL, 'Inactive', '2021-11-23 09:17:52', '2022-07-05 13:04:54'),
(36, 'Marcia Kidddeleted36', 'Voluptatem dolore au', NULL, '8', 21, NULL, 'Yes', 1, '2022-07-05 18:54:56', 'Inactive', '2021-11-23 09:18:05', '2022-07-05 12:54:56'),
(37, 'Alitech Grade', NULL, NULL, '8', 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-05 12:46:15', '2022-07-05 12:46:15');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` bigint(20) DEFAULT NULL,
  `users` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `last_updated_by` bigint(20) DEFAULT NULL,
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `group_name`, `note`, `priority`, `users`, `created_by`, `last_updated_by`, `deleted`, `deleted_by`, `deleted_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Group 1', 'test note', NULL, NULL, 1, 1, 'No', NULL, NULL, 'Active', '2021-10-14 06:43:23', '2022-07-05 13:09:58'),
(2, 'Group 2', 'test note 2222', NULL, NULL, 1, 1, 'No', 1, '2021-10-14 13:02:58', 'Active', '2021-10-14 06:44:53', '2021-10-14 07:04:16'),
(3, 'Group 3', 'text note3', NULL, NULL, 1, 1, 'No', 1, '2021-10-17 16:03:33', 'Active', '2021-10-14 07:21:02', '2021-10-31 09:09:28'),
(21, 'Group 4', NULL, NULL, NULL, 1, NULL, 'No', NULL, NULL, 'Active', '2021-10-23 09:01:24', '2021-10-23 09:01:24'),
(22, 'Common Group', NULL, NULL, NULL, 1, NULL, 'No', NULL, NULL, 'Active', '2021-10-23 13:11:39', '2021-10-23 13:11:39'),
(23, 'High Official Group', NULL, NULL, NULL, 1, NULL, 'No', NULL, NULL, 'Active', '2021-11-13 12:04:05', '2021-11-13 12:04:05'),
(24, 'Alitech Group 1', NULL, NULL, NULL, 1, 1, 'No', NULL, NULL, 'Active', '2022-07-05 12:53:26', '2022-07-05 12:53:47');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2021_04_07_171724_create_company_settings_table', 1),
(3, '2022_03_09_092440_create_permission_tables', 1),
(4, '2022_05_15_153129_create_users_table', 1),
(5, '2022_05_15_161424_create_sessions_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`, `deleted`, `status`) VALUES
(1, 'App\\Models\\User', 1, 'No', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `monthly_amounts`
--

CREATE TABLE `monthly_amounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `facility_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('Add','Deduct') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cause` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `last_updated_by` bigint(20) DEFAULT NULL,
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `monthly_amounts`
--

INSERT INTO `monthly_amounts` (`id`, `user_id`, `facility_name`, `amount`, `type`, `month_year`, `cause`, `created_by`, `last_updated_by`, `deleted`, `deleted_by`, `deleted_date`, `status`, `created_at`, `updated_at`) VALUES
(4, 4, 'Adjustment', '360', 'Add', 'July-2022', NULL, 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-14 07:19:28', '2022-07-14 07:19:28');

-- --------------------------------------------------------

--
-- Table structure for table `our_teams`
--

CREATE TABLE `our_teams` (
  `id` int(10) NOT NULL,
  `priority` bigint(20) DEFAULT NULL,
  `member_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `member_desingnation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_number` bigint(20) DEFAULT NULL,
  `address` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member_education` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_links` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_note` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `job_left_date` date DEFAULT NULL,
  `current_grade` int(11) NOT NULL,
  `current_step` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `sheet_id` bigint(20) DEFAULT NULL,
  `job_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary_type` enum('consulate','scale') COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_no` bigint(20) DEFAULT NULL,
  `is_employee` enum('Yes','No') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `laundry` decimal(10,2) DEFAULT NULL,
  `phone_bill` decimal(10,2) DEFAULT NULL,
  `ta_da` decimal(10,2) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `last_updated_by` bigint(20) DEFAULT NULL,
  `deleted` enum('YES','NO') COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` date DEFAULT NULL,
  `referred_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `our_teams`
--

INSERT INTO `our_teams` (`id`, `priority`, `member_name`, `member_desingnation`, `mobile_number`, `address`, `member_education`, `description`, `member_image`, `social_links`, `short_note`, `joining_date`, `job_left_date`, `current_grade`, `current_step`, `group_id`, `sheet_id`, `job_location`, `salary_type`, `account_no`, `is_employee`, `amount`, `laundry`, `phone_bill`, `ta_da`, `created_by`, `last_updated_by`, `deleted`, `deleted_by`, `deleted_date`, `referred_by`, `status`, `salary`, `created_at`, `updated_at`) VALUES
(3, 3, 'Hamidul Islam', 'Junior Backend Developer', 1822004343, 'bohoddarhat, chittagong', 'BSC in CSE', NULL, '1657195082121112814_135897874920702_5139953946439744884_n.jpg', NULL, NULL, '2021-09-04', NULL, 37, 1, 24, 7, 'GEC Circle, CTG', 'scale', 4984987477, 'No', NULL, '200.00', '500.00', '1200.00', 1, 1, 'NO', NULL, NULL, NULL, 'Active', '20000.00', '2022-07-07 11:24:38', '2022-07-07 11:59:28'),
(4, 4, 'Farhan Rahman', 'Junior Backend developer', 1887922063, 'kolonalhat, ctg', NULL, NULL, '16571950971507480_1375439846070084_978421211_o.jpg', NULL, NULL, '2021-09-25', NULL, 37, 1, 24, 7, 'GEC Circle, CTG', 'scale', 9898498484, 'Yes', NULL, '250.00', '450.00', '1200.00', 1, 1, 'NO', NULL, NULL, NULL, 'Active', '20000.00', '2022-07-07 11:41:32', '2022-07-07 11:58:17'),
(5, 1, 'Md Shoaib', 'Senior Programmer', 1823835334, 'Foyslake, Chittagong', 'BSC in CSE', NULL, '16577227723.jpg', NULL, NULL, '2020-01-01', NULL, 37, 1, 24, 7, 'GEC Circle, CTG', 'scale', 98498987987, 'Yes', NULL, '1200.00', '3000.00', '5000.00', 1, 1, 'NO', NULL, NULL, NULL, 'Active', '20000.00', '2022-07-13 13:34:35', '2022-07-13 14:32:52'),
(6, 2, 'Soumen Chakraborty', 'Senior Programmer', 1863982233, 'Foyslake, Chittagong', 'BSC in CSE', NULL, '16577227874.jpg', NULL, NULL, '2020-01-01', NULL, 37, 1, 24, 7, 'GEC Circle, CTG', 'scale', 9874878787, 'Yes', NULL, '1200.00', '3500.00', '2000.00', 1, 1, 'NO', NULL, NULL, 'Shoaibul Islam', 'Active', '20000.00', '2022-07-13 13:37:49', '2022-07-13 14:33:07');

-- --------------------------------------------------------

--
-- Table structure for table `parties`
--

CREATE TABLE `parties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alternate_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_limit` decimal(10,2) NOT NULL,
  `party_type` enum('Supplier','Customer','Walkin_Customer','Both') COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_name` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `current_due` decimal(10,2) DEFAULT 0.00,
  `opening_due` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parties`
--

INSERT INTO `parties` (`id`, `name`, `code`, `address`, `contact`, `alternate_contact`, `credit_limit`, `party_type`, `contact_person`, `email`, `country_name`, `district`, `customer_type`, `status`, `deleted`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted_by`, `deleted_date`, `created_at`, `updated_at`, `current_due`, `opening_due`) VALUES
(1, 'AliTech Supplier', '000001', 'Ctg ,Ali Tech Office', '01823835334', NULL, '500000.00', 'Supplier', 'Shoaib', 'alitech@gmail.com', 'Bangladesh', 'Chittagong', 'Cash', 'Active', 'No', 1, '2022-03-27', NULL, NULL, NULL, NULL, '2022-03-28 02:45:39', '2022-06-23 21:14:21', '7500.00', '0.00'),
(2, 'AliTech Customer', '000001', 'Ctg,Ali Tech Offcie', '01819066599', NULL, '500000.00', 'Both', 'Shoaib', 'aliteh@gmail.com', 'Bangladesh', 'Chittagong', 'Cash', 'Active', 'No', 1, '2022-03-27', 1, '2022-06-22 21:47:46', NULL, NULL, '2022-03-28 02:46:34', '2022-06-23 22:19:25', '-5000.00', '-6000.00'),
(3, 'Hakimi Corporation', '000002', 'Nawabpur Road Dhaka', '01711034559', '0', '0.00', 'Supplier', 'Hatim Hossain', 'hakimi_hatim@hotmail.com', 'Bangladesh', 'Dhaka', 'Regular', 'Active', 'No', 4, '2022-06-16', NULL, NULL, NULL, NULL, '2022-06-16 16:21:32', '2022-06-16 16:21:32', '0.00', '0.00'),
(4, 'Khaja Enterprise', '000003', 'Nawabpur Road', '01912072342', '0', '0.00', 'Supplier', 'MD Rayhan', 'Khaja@gmail.com', 'Bangladesh', 'Dhaka', 'Regular', 'Active', 'No', 4, '2022-06-16', NULL, NULL, NULL, NULL, '2022-06-16 16:25:39', '2022-06-23 16:27:50', '-90000.00', '-90000.00'),
(5, 'Critical Stop', '000004', 'Kaptan Bazar', '01309654122', '0', '0.00', 'Supplier', 'Sofi', 'info@criticalstopbd.com', 'Bangladesh', 'Dhaka', 'Cash', 'Active', 'No', 4, '2022-06-16', NULL, NULL, NULL, NULL, '2022-06-16 16:27:49', '2022-06-16 16:27:49', '0.00', '0.00'),
(6, 'Al Hatim Refrigeration', '000005', 'Jubilee Road', '01715611766', '0', '0.00', 'Both', 'Hatim', 'alhatim_re@hotmail.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 4, '2022-06-16', 4, '2022-06-23 12:46:28', NULL, NULL, '2022-06-16 16:30:01', '2022-06-23 17:46:28', '-36750.00', '-36750.00'),
(7, 'RFL Company', '000006', 'Sitakundo', '01841661915', '0', '0.00', 'Supplier', 'Abdullah', 'RFL.bd@gmail.com', 'Bangladesh', 'Chittagong', 'Cash', 'Active', 'No', 4, '2022-06-16', NULL, NULL, NULL, NULL, '2022-06-16 16:32:03', '2022-06-16 16:32:03', '0.00', '0.00'),
(8, 'KS International', '000007', 'Nawabpur', '01711568198', '0', '0.00', 'Supplier', 'Biplob', 'info@ksinternationalbd.com', 'Bangladesh', 'Dhaka', 'Regular', 'Active', 'No', 4, '2022-06-16', 4, '2022-06-16 11:34:02', NULL, NULL, '2022-06-16 16:33:50', '2022-06-23 16:37:57', '-133000.00', '-133000.00'),
(9, 'Bismillah Marin Store', '000008', 'Batali Road', '01847245039', '0', '0.00', 'Both', 'Uzzol', 'info@bismillahmarin.biz', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 4, '2022-06-16', 4, '2022-06-23 12:46:07', NULL, NULL, '2022-06-16 16:35:31', '2022-06-28 16:30:17', '-6240.00', '0.00'),
(10, 'Lucky Enterprise', '000009', 'Batali Road', '01813314714', '0', '0.00', 'Both', 'Shamsul', 'Lucky@gmail.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 4, '2022-06-16', 4, '2022-06-23 12:45:34', NULL, NULL, '2022-06-16 16:36:57', '2022-06-27 17:56:10', '-5400.00', '0.00'),
(11, 'Iqbal & Brothers', '000010', 'Jubilee Road', '01811669406', '0', '0.00', 'Both', 'Iqbal', 'Iqbal@gmail.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 4, '2022-06-16', 4, '2022-06-23 12:45:46', NULL, NULL, '2022-06-16 16:39:18', '2022-06-23 17:45:46', '0.00', '0.00'),
(12, 'Oraska Ind Pvt Ltd', '000011', 'Dhaka', '01678443317', '0', '0.00', 'Supplier', 'Limon', 'limonoraska@gmail.com', 'Bangladesh', 'Dhaka', 'Regular', 'Active', 'No', 4, '2022-06-16', NULL, NULL, NULL, NULL, '2022-06-16 16:43:44', '2022-06-16 16:43:44', '0.00', '0.00'),
(13, 'Delowar Life Jacket', '000012', 'Dhaka', '01979471780', '0', '0.00', 'Supplier', 'Delowar', 'Delowar@gmail.com', 'Bangladesh', 'Dhaka', 'Regular', 'Active', 'No', 4, '2022-06-16', NULL, NULL, NULL, NULL, '2022-06-16 16:46:15', '2022-06-16 16:46:15', '0.00', '0.00'),
(14, 'Shahalam Enterprise', '000013', 'Dhaka', '01913456629', '0', '0.00', 'Supplier', 'Shahalam', 'Sahalam@gmail.com', 'Bangladesh', 'Dhaka', 'Regular', 'Active', 'No', 4, '2022-06-16', NULL, NULL, NULL, NULL, '2022-06-16 16:48:11', '2022-06-29 16:45:00', '-202990.00', '-137890.00'),
(15, 'Omkar Brass Ind', '000014', 'India', '00919426571732', '0', '0.00', 'Supplier', 'Hasmuk Bhai', 'Omkar@gmail.com', 'India', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-16', NULL, NULL, NULL, NULL, '2022-06-16 17:03:00', '2022-06-23 18:18:07', '-1690400.00', '-1690400.00'),
(16, 'Mamtech Associates', '000015', 'CTG', '01321149523', '0', '0.00', 'Supplier', 'Shahin', 'mamtechassociates@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-16', NULL, NULL, NULL, NULL, '2022-06-16 22:50:26', '2022-06-16 22:50:26', '0.00', '0.00'),
(17, 'Ladder tech Pvt Ltd', '000016', 'CTG', '01766271477', '0', '0.00', 'Supplier', 'Rupom', 'laddertech.bd@hotmail.com', 'Bangladesh', 'Dhaka', 'Regular', 'Active', 'No', 4, '2022-06-16', NULL, NULL, NULL, NULL, '2022-06-16 22:52:07', '2022-06-16 22:52:07', '0.00', '0.00'),
(18, 'Bangladesh Suppliers', '000017', 'CTG', '01711815343', '0', '0.00', 'Both', '0', 'Suppliersbd@yahoo.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 4, '2022-06-16', 4, '2022-06-23 12:44:39', NULL, NULL, '2022-06-16 22:53:27', '2022-06-23 17:44:39', '0.00', '0.00'),
(19, 'Khaj Boilar & marine Store', '000018', 'CTG', '01815501598', '0', '0.00', 'Both', '0', 'khajaboilar@gmail.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 4, '2022-06-16', 4, '2022-06-23 12:44:30', NULL, NULL, '2022-06-16 22:55:37', '2022-06-23 17:44:30', '0.00', '0.00'),
(20, 'Hakimi Traders', '000019', 'CTG', '01912053309', '0', '0.00', 'Both', 'mustu', 'mustu_32@yahoo.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 4, '2022-06-16', 4, '2022-06-23 12:44:16', NULL, NULL, '2022-06-16 22:58:38', '2022-06-27 15:29:33', '-30150.00', '-10050.00'),
(21, 'Afsar Traders', '000020', 'CTG', '01711398005', '0', '0.00', 'Supplier', '0', 'afsar@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-16', NULL, NULL, NULL, NULL, '2022-06-16 23:01:51', '2022-06-16 23:01:51', '0.00', '0.00'),
(22, 'Jubilee Trade Center-Deleted-22', '000002', 'CTG', '01919617834', '0', '0.00', 'Customer', '0', 'jubileetrade@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'Yes', 4, '2022-06-16', NULL, NULL, 4, '2022-06-16 18:04:47', '2022-06-16 23:03:32', '2022-06-16 23:04:47', '0.00', '0.00'),
(31, 'Soaib', '000001', NULL, '01823835334', '01823835334', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 1, '2022-06-19', NULL, NULL, NULL, NULL, '2022-06-19 18:59:35', '2022-06-20 03:14:01', '-500.00', '0.00'),
(32, 'Shoaib Ali Tech-Deleted-32', '000003', 'ctg', '01823835334', NULL, '0.00', 'Customer', 'shoaib', 'shoaibcse@yahoo.com', 'Bangladesh', 'Chittagong', 'Cash', 'Active', 'Yes', 1, '2022-06-19', NULL, NULL, 1, '2022-06-19 14:35:37', '2022-06-19 19:05:51', '2022-06-19 19:35:37', '0.00', '0.00'),
(33, 'Abul Khair Tobaco Company', '000004', 'CTG', '01912643852', '0', '200000.00', 'Customer', 'Junayed', 'Abul@gmail.com', 'Bangladesh', 'Chittagong', 'Customer', 'Active', 'No', 4, '2022-06-19', 4, '2022-06-23 12:15:30', NULL, NULL, '2022-06-19 19:59:43', '2022-06-23 17:15:53', '59200.00', '0.00'),
(34, 'Jubilee Trading Company', '000021', 'Ctg', '01818970790', '0', '0.00', 'Both', 'Rubel', 'jubliee@gmail.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 4, '2022-06-19', 4, '2022-06-23 12:44:01', NULL, NULL, '2022-06-19 20:07:53', '2022-06-23 17:44:01', '-4840.00', '-4590.00'),
(35, 'Shuruchi Tools Center', '000022', 'CTG', '01762686331', '0', '0.00', 'Supplier', 'mridul', 'marketing@shuruchigroup.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-19', NULL, NULL, NULL, NULL, '2022-06-19 20:09:36', '2022-06-19 20:09:36', '0.00', '0.00'),
(36, 'ABC Traders', '000023', 'CTG', '01973105100', '0', '0.00', 'Both', 'mridul', 'info@abctraders.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 4, '2022-06-19', 4, '2022-06-23 12:40:08', NULL, NULL, '2022-06-19 20:10:47', '2022-06-23 17:40:08', '-19584.00', '-19584.00'),
(37, 'Jamuna Hardware Mart', '000024', 'CTG', '01610198994', '0', '0.00', 'Both', 'mridul', 'jumuna@gmail.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 4, '2022-06-19', 4, '2022-06-23 12:43:49', NULL, NULL, '2022-06-19 20:11:55', '2022-06-23 17:43:49', '0.00', '0.00'),
(38, 'Fire Solution-Deleted-38', '000025', 'CTG', '01610342533', '0', '0.00', 'Supplier', 'riton', 'ritoninctg@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'Yes', 4, '2022-06-19', NULL, NULL, 4, '2022-06-27 10:41:17', '2022-06-19 20:13:39', '2022-06-27 15:41:17', '0.00', '0.00'),
(39, 'Al Hatim Refrigeration-Deleted-39', '000026', 'CTG', '01711354315', '0', '0.00', 'Supplier', 'riton', 'alhatim_ref@hotmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'Yes', 4, '2022-06-19', NULL, NULL, 4, '2022-06-23 11:04:49', '2022-06-19 20:15:26', '2022-06-23 16:04:49', '0.00', '0.00'),
(40, 'Karnafully Traders', '000002', 'CTG', '01624025032', '01624025032', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-20', NULL, NULL, NULL, NULL, '2022-06-20 16:24:39', '2022-06-20 16:24:39', '0.00', '0.00'),
(41, 'SK Hardware', '000003', 'CTG', '01757791333', '01757791333', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-20', NULL, NULL, NULL, NULL, '2022-06-20 16:51:35', '2022-06-20 16:51:35', '0.00', '0.00'),
(42, 'Debashis Chowdhury', '000004', 'CTG', '01985632442', '01985632442', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-20', NULL, NULL, NULL, NULL, '2022-06-20 16:59:02', '2022-06-20 16:59:02', '0.00', '0.00'),
(43, 'Hazi Moinuddin', '000005', 'CTG', '01675258456', '01675258456', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-20', NULL, NULL, NULL, NULL, '2022-06-20 17:22:08', '2022-06-20 17:22:08', '0.00', '0.00'),
(44, 'Ali Hasan', '000005', 'CTG', '01834069352', '0', '0.00', 'Customer', 'Ali', 'Ali@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-20', NULL, NULL, NULL, NULL, '2022-06-20 17:27:51', '2022-06-22 15:14:49', '0.00', '0.00'),
(45, 'Unknown', '000006', 'CTG', '01675423651', '01675423651', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-20', NULL, NULL, NULL, NULL, '2022-06-20 17:31:20', '2022-06-20 17:31:20', '0.00', '0.00'),
(46, 'Water & Life', '000007', 'CTG', '01757796325', '01757796325', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-20', NULL, NULL, NULL, NULL, '2022-06-20 17:33:42', '2022-06-20 17:33:42', '0.00', '0.00'),
(47, 'Unknown', '000008', 'CTG', '01678563215', '01678563215', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-20', NULL, NULL, NULL, NULL, '2022-06-20 17:38:34', '2022-06-20 17:38:34', '0.00', '0.00'),
(48, 'Fotikchori Vhumi Office', '000009', 'CTG', '01678569325', '01678569325', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-20', NULL, NULL, NULL, NULL, '2022-06-20 17:40:11', '2022-06-20 17:40:11', '0.00', '0.00'),
(49, 'Md Shahed', '000010', 'CTG', '01756321547', '01756321547', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-20', NULL, NULL, NULL, NULL, '2022-06-20 17:41:42', '2022-06-20 17:41:42', '0.00', '0.00'),
(50, 'Anand', '000011', 'CTG', '01752236548', '01752236548', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-20', NULL, NULL, NULL, NULL, '2022-06-20 17:44:42', '2022-06-20 17:44:42', '0.00', '0.00'),
(51, 'Ilias', '000012', 'CTG', '01757785632', '01757785632', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-20', NULL, NULL, NULL, NULL, '2022-06-20 17:46:33', '2022-06-20 17:46:33', '0.00', '0.00'),
(52, 'Unknown', '000013', 'CTG', '01678532145', '01678532145', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-20', NULL, NULL, NULL, NULL, '2022-06-20 17:48:57', '2022-06-20 17:48:57', '0.00', '0.00'),
(53, 'Babar Chowdhury', '000014', 'CTG', '01678562114', '01678562114', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-20', NULL, NULL, NULL, NULL, '2022-06-20 17:50:26', '2022-06-20 17:50:26', '0.00', '0.00'),
(54, 'Jahanara Trading', '000015', 'CTG', '01685324454', '01685324454', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-20', NULL, NULL, NULL, NULL, '2022-06-20 17:52:03', '2022-06-20 17:52:03', '0.00', '0.00'),
(55, 'The Light', '000016', 'CTG', '01819832912', '01819832912', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-20', NULL, NULL, NULL, NULL, '2022-06-20 17:54:40', '2022-06-20 17:54:40', '0.00', '0.00'),
(56, 'Zainee Ent', '000027', 'CTG', '01971701761', '0', '200000.00', 'Both', 'Taher Bhai', 'Zainee@gmail.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 4, '2022-06-20', 4, '2022-06-26 10:57:29', NULL, NULL, '2022-06-20 18:01:51', '2022-06-27 16:04:33', '258540.00', '241740.00'),
(57, 'Babji Ent', '000006', 'CTG', '01401038200', '0', '200000.00', 'Both', 'Hujafar', 'Babji@gmail.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 2, '2022-06-21', 4, '2022-06-26 10:48:06', NULL, NULL, '2022-06-21 16:38:59', '2022-06-26 21:00:19', '300600.00', '0.00'),
(58, 'Olympia Complex', '000028', 'Dhaka', '01817519937', '0', '0.00', 'Supplier', 'Jahangir', 'Olympia@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 2, '2022-06-21', NULL, NULL, NULL, NULL, '2022-06-21 16:44:46', '2022-06-21 16:46:03', '0.00', '0.00'),
(59, 'MCC 1', '000029', 'CTG', '01757791332', '0', '0.00', 'Both', 'Mridul', 'MCC@gmail.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 2, '2022-06-21', 4, '2022-06-23 12:43:21', NULL, NULL, '2022-06-21 16:48:02', '2022-06-28 16:24:23', '-22800.00', '0.00'),
(60, 'Harbour Associate', '000017', 'CTG', '01756548524', '01756548524', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 2, '2022-06-21', NULL, NULL, NULL, NULL, '2022-06-21 17:04:05', '2022-06-21 17:04:05', '0.00', '0.00'),
(61, 'Seema Oxygen', '000018', 'CTG', '01679733824', '01679733824', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 2, '2022-06-21', NULL, NULL, NULL, NULL, '2022-06-21 17:21:42', '2022-06-21 17:21:42', '0.00', '0.00'),
(62, 'Highway Sweets', '000019', 'CTG', '01818478240', '01818478240', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 2, '2022-06-21', NULL, NULL, NULL, NULL, '2022-06-21 17:42:52', '2022-06-22 15:20:04', '0.00', '0.00'),
(63, 'Liton Refilling', '000030', 'CTG', '01610342533', '0', '0.00', 'Both', 'Riton', 'Riton@gmail.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 2, '2022-06-21', 4, '2022-06-27 10:42:00', NULL, NULL, '2022-06-21 17:48:45', '2022-06-27 15:42:56', '-6835.00', '-4495.00'),
(64, 'Dohs Achol', '000020', 'CTG', '01675632215', '01675632215', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 2, '2022-06-21', NULL, NULL, NULL, NULL, '2022-06-21 18:00:23', '2022-06-21 18:00:23', '0.00', '0.00'),
(65, 'Zainee Ent-Deleted-65', '000007', 'CTG', '01971701761', '0', '0.00', 'Customer', 'Taher Bhai', 'Zainee@gmai.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'Yes', 4, '2022-06-21', NULL, NULL, 4, '2022-06-23 12:45:01', '2022-06-21 19:46:57', '2022-06-23 17:45:01', '0.00', '0.00'),
(79, 'Saif Ent', '000031', 'ctg', '01902505171', '0', '0.00', 'Supplier', 'Saif', 'Saif188@gmail.com', 'Bangladesh', 'Chittagong', 'Supplier', 'Active', 'No', 4, '2022-06-21', 4, '2022-06-25 12:28:55', NULL, NULL, '2022-06-21 20:00:27', '2022-06-25 17:28:55', '0.00', '0.00'),
(84, 'Mridul', '000008', 'ctg', '01757791336', '0', '0.00', 'Customer', 'mridul', 'Mridul60@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-21', NULL, NULL, NULL, NULL, '2022-06-21 20:05:29', '2022-06-21 20:05:29', '0.00', '0.00'),
(88, 'SASM', '000021', 'ctg', '01712401521', '01712401521', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 2, '2022-06-21', NULL, NULL, NULL, NULL, '2022-06-21 20:21:39', '2022-06-21 20:21:39', '0.00', '0.00'),
(89, 'unkown', '000022', 'ctg', '01712000103', '01712000103', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 2, '2022-06-21', NULL, NULL, NULL, NULL, '2022-06-21 20:50:28', '2022-06-21 20:50:28', '0.00', '0.00'),
(90, 'unknown', '000023', 'ctg', '01012000101', '01012000101', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 2, '2022-06-21', NULL, NULL, NULL, NULL, '2022-06-21 20:56:28', '2022-06-21 20:56:28', '9000.00', '0.00'),
(91, 'DR oxy', '000009', 'dhaka', '01676335660', '0', '100000.00', 'Customer', 'nahed', 'dr@yahoo.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 2, '2022-06-21', 4, '2022-06-22 12:09:00', NULL, NULL, '2022-06-21 21:02:02', '2022-06-22 17:10:30', '0.00', '0.00'),
(92, 'Midas Safety', '000010', 'CTG', '01831867786', '0', '0.00', 'Customer', 'aminul bhai', 'midas@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-21', NULL, NULL, NULL, NULL, '2022-06-21 21:41:41', '2022-06-21 21:41:41', '0.00', '0.00'),
(94, 'Amir Ent', '000032', 'ctg', '01882783905', '0', '0.00', 'Both', 'Amir bhai', 'Amir@gmail.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 4, '2022-06-21', 4, '2022-06-23 12:43:06', NULL, NULL, '2022-06-21 21:43:44', '2022-06-23 17:43:06', '0.00', '0.00'),
(96, 'Fiber Plastic', '000024', 'ctg', '01752599654', '01752599654', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-21', NULL, NULL, NULL, NULL, '2022-06-21 21:48:44', '2022-06-21 21:48:44', '0.00', '0.00'),
(97, 'Brac Net Ltd', '000025', 'CTG', '01753654852', '01753654852', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-21', NULL, NULL, NULL, NULL, '2022-06-21 21:57:54', '2022-06-21 21:57:54', '0.00', '0.00'),
(98, 'Rinku', '000026', 'CTG', '01711163196', '01711163196', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-21', NULL, NULL, NULL, NULL, '2022-06-21 22:02:57', '2022-06-21 22:02:57', '0.00', '0.00'),
(99, 'Gilani Oxygen', '000027', 'CTG', '01754452254', '01754452254', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-21', NULL, NULL, NULL, NULL, '2022-06-21 22:07:25', '2022-06-21 22:07:25', '0.00', '0.00'),
(100, 'Unknown', '000028', 'CTG', '01756556458', '01756556458', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-22', NULL, NULL, NULL, NULL, '2022-06-22 15:12:52', '2022-06-22 15:12:53', '0.00', '0.00'),
(101, 'Highway Sweets-Deleted-101', '000011', 'CTG', '01818478240', '0', '0.00', 'Customer', 'sweets', 'HIgh@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'Yes', 4, '2022-06-22', NULL, NULL, 4, '2022-06-22 10:18:18', '2022-06-22 15:17:49', '2022-06-22 15:18:18', '0.00', '0.00'),
(102, 'Talukdar Trade Int', '000029', 'Dhaka', '01712511954', '01712511954', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-22', NULL, NULL, NULL, NULL, '2022-06-22 15:27:49', '2022-06-22 15:35:29', '-1000.00', '0.00'),
(103, 'Talukdar Trade Int', '000012', 'Dhaka', '01712511954', '0', '0.00', 'Customer', 'Talukdar', 'Talukdar@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-22', 4, '2022-06-22 10:40:58', NULL, NULL, '2022-06-22 15:28:59', '2022-06-22 15:59:32', '0.00', '0.00'),
(104, 'Unknown', '000030', 'CTG', '01757786921', '01757786921', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-22', NULL, NULL, NULL, NULL, '2022-06-22 15:58:01', '2022-06-22 15:58:01', '0.00', '0.00'),
(105, 'Jannat Trading', '000031', 'CTG', '01756952445', '01756952445', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-22', NULL, NULL, NULL, NULL, '2022-06-22 16:11:51', '2022-06-22 16:11:51', '0.00', '0.00'),
(106, 'MR Belayet', '000032', 'ctg', '01756325481', '01756325481', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-22', NULL, NULL, NULL, NULL, '2022-06-22 16:18:49', '2022-06-22 16:51:07', '-27000.00', '0.00'),
(107, 'Antora', '000013', 'CTG', '01819818598', '0', '0.00', 'Customer', 'Siddiq', 'Antora@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-22', NULL, NULL, NULL, NULL, '2022-06-22 16:43:17', '2022-06-22 16:51:43', '-1200.00', '0.00'),
(108, 'Dr Oxygen-Deleted-108', '000033', 'Dhaka', '01676335660', '0', '0.00', 'Supplier', 'dr', 'Oxygen@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'Yes', 4, '2022-06-22', NULL, NULL, 4, '2022-06-22 12:11:10', '2022-06-22 16:59:14', '2022-06-22 17:11:10', '0.00', '0.00'),
(109, 'P2P', '000033', 'CTG', '01753265483', '01753265483', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-22', NULL, NULL, NULL, NULL, '2022-06-22 17:23:25', '2022-06-22 17:23:25', '0.00', '0.00'),
(110, 'Arif', '000034', 'CTG', '01757791331', '01757791331', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-22', NULL, NULL, NULL, NULL, '2022-06-22 17:45:21', '2022-06-22 17:45:21', '0.00', '0.00'),
(111, 'Abbasi Marine', '000034', 'CTG', '01711845653', '0', '0.00', 'Both', 'Mohish bhai', 'Abbasi@gmail.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 4, '2022-06-22', 4, '2022-06-23 12:42:55', NULL, NULL, '2022-06-22 17:48:27', '2022-06-23 17:42:55', '-6000.00', '0.00'),
(112, 'utpal', '000035', 'CTG', '01812545875', '0', '0.00', 'Supplier', 'utpal', 'utpal@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-22', NULL, NULL, NULL, NULL, '2022-06-22 17:59:40', '2022-06-22 20:01:00', '0.00', '0.00'),
(113, 'PHP', '000035', 'CTG', '01757739654', '01757739654', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-22', NULL, NULL, NULL, NULL, '2022-06-22 18:05:40', '2022-06-22 18:05:40', '0.00', '0.00'),
(114, 'Kawser Bhai', '000036', 'India', '00918291553444', '0', '0.00', 'Supplier', 'kawser', 'Kawser@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-23', NULL, NULL, NULL, NULL, '2022-06-23 15:44:56', '2022-06-23 15:48:14', '0.00', '0.00'),
(115, 'Genarel Hardware', '000037', 'CTG', '01819630364', '0', '0.00', 'Supplier', 'Unknown', 'Genarel@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-23', NULL, NULL, NULL, NULL, '2022-06-23 16:03:06', '2022-06-23 16:04:00', '-29280.00', '-29280.00'),
(118, 'Nowshin Fire Foe Ent', '000038', 'CTG', '01677195500', '0', '0.00', 'Supplier', 'sukkur', 'noeshin@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-23', NULL, NULL, NULL, NULL, '2022-06-23 16:25:27', '2022-06-23 16:27:13', '-8450.00', '-8450.00'),
(119, 'Rezaul Karim (CNF)', '000039', 'CTG', '01675826920', '0', '0.00', 'Supplier', 'Rezaul', 'rezaul@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-23', NULL, NULL, NULL, NULL, '2022-06-23 16:39:24', '2022-06-23 16:39:46', '-421600.00', '-421600.00'),
(120, 'Nahar Trading', '000040', 'CTG', '01646088667', '0', '0.00', 'Both', 'Riyad', 'Nahar@gmail.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 4, '2022-06-23', 4, '2022-06-23 12:48:49', NULL, NULL, '2022-06-23 17:48:36', '2022-06-27 17:12:09', '-33440.00', '-34000.00'),
(121, 'DH Trading', '000041', 'CTG', '01972308515', '0', '0.00', 'Both', 'Dillu Uncle', 'DH@gmail.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 4, '2022-06-23', 4, '2022-06-23 12:50:25', NULL, NULL, '2022-06-23 17:50:16', '2022-06-23 17:50:25', '0.00', '0.00'),
(122, 'Sky Automachine', '000042', 'CTG', '01828718212', '0', '0.00', 'Both', 'Malek bhai', 'auto@gmail.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 4, '2022-06-23', 4, '2022-06-23 13:06:58', NULL, NULL, '2022-06-23 18:06:50', '2022-06-23 18:11:13', '0.00', '0.00'),
(123, 'Unknown', '000036', 'CTG', '01757736517', '01757736517', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-25', NULL, NULL, NULL, NULL, '2022-06-25 16:36:13', '2022-06-25 16:36:13', '0.00', '0.00'),
(124, 'Sutian', '000037', 'CTG', '01756652484', '01756652484', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-25', NULL, NULL, NULL, NULL, '2022-06-25 16:38:09', '2022-06-25 16:38:09', '0.00', '0.00'),
(125, 'MSA Service', '000038', 'CTG', '01758853212', '01758853212', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-25', NULL, NULL, NULL, NULL, '2022-06-25 16:42:51', '2022-06-25 16:42:51', '0.00', '0.00'),
(126, 'SMB Ent', '000043', 'CTG', '01715502052', '0', '200000.00', 'Both', 'mudar Bhai', 'Smb@gmail.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 4, '2022-06-25', 4, '2022-06-26 16:27:21', NULL, NULL, '2022-06-25 16:48:07', '2022-06-27 16:02:20', '-8280.00', '-82580.00'),
(127, 'RK Ent', '000039', 'CTG', '01757791335', '01757791335', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-25', NULL, NULL, NULL, NULL, '2022-06-25 16:51:30', '2022-06-25 16:51:30', '0.00', '0.00'),
(128, 'Unknown', '000040', 'CTG', '01756521458', '01756521458', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-25', NULL, NULL, NULL, NULL, '2022-06-25 16:53:28', '2022-06-25 16:53:28', '0.00', '0.00'),
(129, 'Unknown', '000041', 'CTG', '01758523647', '01758523647', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-25', NULL, NULL, NULL, NULL, '2022-06-25 17:42:23', '2022-06-25 17:42:23', '0.00', '0.00'),
(130, 'Borhany', '000044', 'India', '00919830130077', '0', '0.00', 'Supplier', 'Borhan', 'Borhany@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-26', NULL, NULL, NULL, NULL, '2022-06-26 16:22:04', '2022-06-26 16:24:28', '0.00', '0.00'),
(131, 'Comilla Traders', '000045', 'CTG', '01712900431', '0', '200000.00', 'Both', 'Mustu bhai', 'Comilla@gmail.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 4, '2022-06-26', 4, '2022-06-26 12:21:28', NULL, NULL, '2022-06-26 17:12:40', '2022-06-27 16:53:44', '24000.00', '0.00'),
(134, 'Kaizar Hardware', '000046', 'CTG', '01711354206', '0', '200000.00', 'Both', 'Abbas', 'Kaizar@gmail.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 4, '2022-06-27', 4, '2022-06-27 12:03:12', NULL, NULL, '2022-06-27 15:36:01', '2022-06-27 17:03:12', '-3200.00', '-3200.00'),
(135, 'Mr Ent', '000047', 'CTG', '01712123993', '0', '200000.00', 'Customer', 'Ekram Bhai', 'Mr@gmail.com', 'Bangladesh', 'Chittagong', 'Customer', 'Active', 'No', 4, '2022-06-27', 4, '2022-06-27 11:14:38', NULL, NULL, '2022-06-27 15:45:19', '2022-06-27 16:14:38', '-6000.00', '6000.00'),
(136, 'Defend Omor Faruk', '000048', 'CTG', '01836400999', '0', '200000.00', 'Customer', 'Faruk', 'Defend@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-27', NULL, NULL, NULL, NULL, '2022-06-27 15:51:10', '2022-06-27 15:51:26', '28720.00', '28720.00'),
(137, 'Brother Oxygen', '000049', 'CTG', '01818816769', '0', '0.00', 'Customer', 'Shahid Shab', 'Brother@gmail.com', 'Bangladesh', 'Chittagong', 'Customer', 'Active', 'No', 4, '2022-06-27', 4, '2022-06-27 11:13:44', NULL, NULL, '2022-06-27 16:09:23', '2022-06-27 16:13:44', '86250.00', '86250.00'),
(138, 'Bhai Bhai Traders', '000050', 'CTG', '01819611317', NULL, '0.00', 'Customer', 'Nijam', 'BHai@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-27', NULL, NULL, NULL, NULL, '2022-06-27 16:13:14', '2022-06-27 16:13:14', '0.00', '0.00'),
(139, 'Najim Ent', '000051', 'CTG', '01711883479', '0', '0.00', 'Customer', 'najim', 'Najim@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-27', NULL, NULL, NULL, NULL, '2022-06-27 16:15:46', '2022-06-27 16:15:46', '0.00', '0.00'),
(140, 'Kabir Oxygen', '000052', 'CTG', '01829663567', '0', '1000000.00', 'Customer', 'Najim', 'kabir@gmail.com', 'Bangladesh', 'Chittagong', 'Customer', 'Active', 'No', 4, '2022-06-27', 4, '2022-06-28 11:13:14', NULL, NULL, '2022-06-27 16:16:29', '2022-06-28 16:14:30', '664000.00', '585500.00'),
(141, 'Taj Ent', '000053', 'CTG', '01919324334', '0', '0.00', 'Customer', 'Kutbi bhai', 'taj@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-27', NULL, NULL, NULL, NULL, '2022-06-27 16:17:07', '2022-06-27 16:21:42', '112800.00', '112800.00'),
(142, 'Union Oxygen', '000054', 'CTG', '01617727100', '0', '0.00', 'Customer', 'kochi bhai', 'union@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-27', NULL, NULL, NULL, NULL, '2022-06-27 16:22:04', '2022-06-27 16:23:49', '105900.00', '105900.00'),
(143, 'KR Oxygen', '000055', 'CTG', '01708432550', '0', '0.00', 'Customer', 'Yusuf Bhai', 'Kr@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-27', NULL, NULL, NULL, NULL, '2022-06-27 16:24:56', '2022-06-27 16:25:06', '39000.00', '39000.00'),
(144, 'A K Oxegen', '000056', 'Dhaka', '01712539520', '0', '0.00', 'Customer', 'Subroto', 'ak@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-27', NULL, NULL, NULL, NULL, '2022-06-27 16:28:10', '2022-06-27 16:28:10', '0.00', '0.00'),
(145, 'Rifat Oxygen', '000057', 'Dhaka', '01715002114', '0', '0.00', 'Customer', 'mujib Bhai', 'Rifat@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-27', NULL, NULL, NULL, NULL, '2022-06-27 16:28:53', '2022-06-27 16:28:53', '0.00', '0.00'),
(150, 'Helal', '000058', 'Dhaka', '01730587435', '0', '0.00', 'Customer', 'Helal', 'helal@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-27', NULL, NULL, NULL, NULL, '2022-06-27 16:30:47', '2022-06-27 16:30:47', '0.00', '0.00'),
(151, 'Nur Nobi Ent', '000059', 'CTG', '01711163465', '0', '0.00', 'Customer', 'nobi bHai', 'nur@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-27', NULL, NULL, NULL, NULL, '2022-06-27 16:31:28', '2022-06-27 16:31:28', '0.00', '0.00'),
(152, 'Chistia Oxygen', '000060', 'Ctg', '01998370400', '0', '0.00', 'Customer', 'Rajib bhai', 'chistia@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-27', NULL, NULL, NULL, NULL, '2022-06-27 16:32:31', '2022-06-27 16:32:31', '0.00', '0.00'),
(153, 'Shagir Ent', '000047', 'ctg', '01716392383', '0', '100000.00', 'Both', 'Shagir', 'Shagir@gmail.com', 'Bangladesh', 'Chittagong', 'Both', 'Active', 'No', 4, '2022-06-27', 4, '2022-06-27 12:04:01', NULL, NULL, '2022-06-27 17:03:03', '2022-06-27 17:05:10', '2800.00', '0.00'),
(154, 'BESTEC BD Ltd', '000042', 'Epz', '01757756325', '01757756325', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-27', NULL, NULL, NULL, NULL, '2022-06-27 17:36:21', '2022-06-27 17:36:22', '0.00', '0.00'),
(155, 'Comvalley Gloval Ltd', '000043', 'ctg', '01752564252', '01752564252', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-27', NULL, NULL, NULL, NULL, '2022-06-27 17:38:10', '2022-06-27 17:38:10', '0.00', '0.00'),
(156, 'Unknown', '000044', 'ctg', '01757791336', '01757791336', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-27', NULL, NULL, NULL, NULL, '2022-06-27 18:00:26', '2022-06-27 18:00:26', '0.00', '0.00'),
(157, 'Compliance BD Ltd', '000061', 'CTG', '01777705383', '0', '0.00', 'Customer', 'Kawser', 'Comp@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-28', NULL, NULL, NULL, NULL, '2022-06-28 15:51:40', '2022-06-28 15:56:05', '616960.00', '616960.00'),
(158, 'Gulf Trade Center', '000062', 'CTG', '01819873844', '0', '0.00', 'Customer', 'Choton Bhai', 'Gulf@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-28', NULL, NULL, NULL, NULL, '2022-06-28 15:58:56', '2022-06-28 15:58:56', '0.00', '0.00'),
(159, 'Abul Khair Group', '000063', 'CTG', '01777777981', '0', '500000.00', 'Customer', 'Faisle Karim', 'Abull@gmail.com', 'Bangladesh', 'Chittagong', 'Customer', 'Active', 'No', 4, '2022-06-28', 4, '2022-06-29 11:40:08', NULL, NULL, '2022-06-28 16:18:37', '2022-06-29 16:40:44', '104000.00', '0.00'),
(160, 'unknown', '000045', 'ctg', '01758563215', '01758563215', '0.00', 'Walkin_Customer', NULL, NULL, NULL, NULL, NULL, 'Active', 'No', 4, '2022-06-29', NULL, NULL, NULL, NULL, '2022-06-29 16:29:22', '2022-06-29 16:29:22', '0.00', '0.00'),
(161, 'Jasbir Traders', '000048', 'Dhaka', '01728989493', '0', '500000.00', 'Supplier', 'jasbir', 'Jasbirtradersbd@gmail.com', 'Bangladesh', 'Chittagong', 'Regular', 'Active', 'No', 4, '2022-06-29', NULL, NULL, NULL, NULL, '2022-06-29 16:49:46', '2022-06-29 16:51:36', '0.00', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_vouchers`
--

CREATE TABLE `payment_vouchers` (
  `id` bigint(20) NOT NULL,
  `party_id` bigint(20) NOT NULL,
  `purchase_id` bigint(20) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `entryBy` bigint(20) NOT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `chequeNo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paymentDate` date DEFAULT NULL,
  `chequeIssueDate` date DEFAULT NULL,
  `accountNo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Active',
  `type` enum('Payment Received','Payment','Payable','Party Payable','Payment Adjustment','Adjustment','Discount') COLLATE utf8_unicode_ci NOT NULL,
  `remarks` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `tbl_bankInfoId` bigint(20) DEFAULT NULL,
  `deletedBy` int(11) DEFAULT NULL,
  `deleted` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `deletedDate` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `lastUpdatedBy` bigint(20) DEFAULT NULL,
  `voucherType` enum('Local Purchase','Foreign Purchase','WalkinSale','PartySale','FS','PurchaseReturn','SalesReturn','discount','Repair','voucherEntry','Expense') COLLATE utf8_unicode_ci NOT NULL,
  `sales_id` bigint(20) DEFAULT NULL,
  `purchase_return_id` bigint(20) DEFAULT NULL,
  `sales_return_id` bigint(20) DEFAULT NULL,
  `expense_id` bigint(20) DEFAULT NULL,
  `tbl_repairing_center_id` bigint(20) DEFAULT NULL,
  `customerType` enum('WalkingCustomer','Party') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Party',
  `voucherNo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `chequeBank` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dbInsertDate` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_vouchers`
--

INSERT INTO `payment_vouchers` (`id`, `party_id`, `purchase_id`, `amount`, `entryBy`, `discount`, `payment_method`, `chequeNo`, `paymentDate`, `chequeIssueDate`, `accountNo`, `status`, `type`, `remarks`, `tbl_bankInfoId`, `deletedBy`, `deleted`, `deletedDate`, `created_at`, `updated_at`, `lastUpdatedBy`, `voucherType`, `sales_id`, `purchase_return_id`, `sales_return_id`, `expense_id`, `tbl_repairing_center_id`, `customerType`, `voucherNo`, `chequeBank`, `dbInsertDate`) VALUES
(1, 40, NULL, '3000.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000002 payment: 3000', NULL, NULL, 'No', NULL, '2022-06-20 16:24:39', '2022-06-20 16:24:39', NULL, 'PartySale', 4, NULL, NULL, NULL, NULL, 'Party', '000001', NULL, NULL),
(2, 40, NULL, '3000.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000002 payment: 3000', NULL, NULL, 'No', NULL, '2022-06-20 16:24:39', '2022-06-20 16:24:39', NULL, 'PartySale', 4, NULL, NULL, NULL, NULL, 'Party', '000002', NULL, NULL),
(3, 41, NULL, '7200.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000003 payment: 7200', NULL, NULL, 'No', NULL, '2022-06-20 16:51:35', '2022-06-20 16:51:35', NULL, 'PartySale', 5, NULL, NULL, NULL, NULL, 'Party', '000003', NULL, NULL),
(4, 41, NULL, '7200.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000003 payment: 7200', NULL, NULL, 'No', NULL, '2022-06-20 16:51:35', '2022-06-20 16:51:35', NULL, 'PartySale', 5, NULL, NULL, NULL, NULL, 'Party', '000004', NULL, NULL),
(5, 42, NULL, '3500.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000004 payment: 3500', NULL, NULL, 'No', NULL, '2022-06-20 16:59:02', '2022-06-20 16:59:02', NULL, 'PartySale', 6, NULL, NULL, NULL, NULL, 'Party', '000005', NULL, NULL),
(6, 42, NULL, '3500.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000004 payment: 3500', NULL, NULL, 'No', NULL, '2022-06-20 16:59:02', '2022-06-20 16:59:02', NULL, 'PartySale', 6, NULL, NULL, NULL, NULL, 'Party', '000006', NULL, NULL),
(7, 43, NULL, '3560.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000005 payment: 3560', NULL, NULL, 'No', NULL, '2022-06-20 17:22:08', '2022-06-20 17:22:08', NULL, 'PartySale', 7, NULL, NULL, NULL, NULL, 'Party', '000007', NULL, NULL),
(8, 43, NULL, '3560.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000005 payment: 3560', NULL, NULL, 'No', NULL, '2022-06-20 17:22:08', '2022-06-20 17:22:08', NULL, 'PartySale', 7, NULL, NULL, NULL, NULL, 'Party', '000008', NULL, NULL),
(9, 45, NULL, '750.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000006 payment: 750', NULL, NULL, 'No', NULL, '2022-06-20 17:31:20', '2022-06-20 17:31:20', NULL, 'PartySale', 8, NULL, NULL, NULL, NULL, 'Party', '000009', NULL, NULL),
(10, 45, NULL, '750.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000006 payment: 750', NULL, NULL, 'No', NULL, '2022-06-20 17:31:20', '2022-06-20 17:31:20', NULL, 'PartySale', 8, NULL, NULL, NULL, NULL, 'Party', '000010', NULL, NULL),
(11, 46, NULL, '17500.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000007 payment: 17500', NULL, NULL, 'No', NULL, '2022-06-20 17:33:42', '2022-06-20 17:33:42', NULL, 'PartySale', 9, NULL, NULL, NULL, NULL, 'Party', '000011', NULL, NULL),
(12, 46, NULL, '17500.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000007 payment: 17500', NULL, NULL, 'No', NULL, '2022-06-20 17:33:42', '2022-06-20 17:33:42', NULL, 'PartySale', 9, NULL, NULL, NULL, NULL, 'Party', '000012', NULL, NULL),
(13, 47, NULL, '2500.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000008 payment: 2500', NULL, NULL, 'No', NULL, '2022-06-20 17:38:34', '2022-06-20 17:38:34', NULL, 'PartySale', 10, NULL, NULL, NULL, NULL, 'Party', '000013', NULL, NULL),
(14, 47, NULL, '2500.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000008 payment: 2500', NULL, NULL, 'No', NULL, '2022-06-20 17:38:34', '2022-06-20 17:38:34', NULL, 'PartySale', 10, NULL, NULL, NULL, NULL, 'Party', '000014', NULL, NULL),
(15, 48, NULL, '7500.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000009 payment: 7500', NULL, NULL, 'No', NULL, '2022-06-20 17:40:11', '2022-06-20 17:40:11', NULL, 'PartySale', 11, NULL, NULL, NULL, NULL, 'Party', '000015', NULL, NULL),
(16, 48, NULL, '7500.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000009 payment: 7500', NULL, NULL, 'No', NULL, '2022-06-20 17:40:11', '2022-06-20 17:40:11', NULL, 'PartySale', 11, NULL, NULL, NULL, NULL, 'Party', '000016', NULL, NULL),
(17, 49, NULL, '1300.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000010 payment: 1300', NULL, NULL, 'No', NULL, '2022-06-20 17:41:42', '2022-06-20 17:41:42', NULL, 'PartySale', 12, NULL, NULL, NULL, NULL, 'Party', '000017', NULL, NULL),
(18, 49, NULL, '1300.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000010 payment: 1300', NULL, NULL, 'No', NULL, '2022-06-20 17:41:42', '2022-06-20 17:41:42', NULL, 'PartySale', 12, NULL, NULL, NULL, NULL, 'Party', '000018', NULL, NULL),
(19, 50, NULL, '3800.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000011 payment: 3800', NULL, NULL, 'No', NULL, '2022-06-20 17:44:42', '2022-06-20 17:44:42', NULL, 'PartySale', 13, NULL, NULL, NULL, NULL, 'Party', '000019', NULL, NULL),
(20, 50, NULL, '3800.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000011 payment: 3800', NULL, NULL, 'No', NULL, '2022-06-20 17:44:42', '2022-06-20 17:44:42', NULL, 'PartySale', 13, NULL, NULL, NULL, NULL, 'Party', '000020', NULL, NULL),
(21, 51, NULL, '1000.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000012 payment: 1000', NULL, NULL, 'No', NULL, '2022-06-20 17:46:33', '2022-06-20 17:46:33', NULL, 'PartySale', 14, NULL, NULL, NULL, NULL, 'Party', '000021', NULL, NULL),
(22, 51, NULL, '1000.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000012 payment: 1000', NULL, NULL, 'No', NULL, '2022-06-20 17:46:33', '2022-06-20 17:46:33', NULL, 'PartySale', 14, NULL, NULL, NULL, NULL, 'Party', '000022', NULL, NULL),
(23, 52, NULL, '900.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000013 payment: 900', NULL, NULL, 'No', NULL, '2022-06-20 17:48:57', '2022-06-20 17:48:57', NULL, 'PartySale', 15, NULL, NULL, NULL, NULL, 'Party', '000023', NULL, NULL),
(24, 52, NULL, '900.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000013 payment: 900', NULL, NULL, 'No', NULL, '2022-06-20 17:48:57', '2022-06-20 17:48:57', NULL, 'PartySale', 15, NULL, NULL, NULL, NULL, 'Party', '000024', NULL, NULL),
(25, 53, NULL, '15600.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000014 payment: 15600', NULL, NULL, 'No', NULL, '2022-06-20 17:50:26', '2022-06-20 17:50:26', NULL, 'PartySale', 16, NULL, NULL, NULL, NULL, 'Party', '000025', NULL, NULL),
(26, 53, NULL, '15600.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000014 payment: 15600', NULL, NULL, 'No', NULL, '2022-06-20 17:50:26', '2022-06-20 17:50:26', NULL, 'PartySale', 16, NULL, NULL, NULL, NULL, 'Party', '000026', NULL, NULL),
(27, 54, NULL, '6000.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000015 payment: 6000', NULL, NULL, 'No', NULL, '2022-06-20 17:52:03', '2022-06-20 17:52:03', NULL, 'PartySale', 17, NULL, NULL, NULL, NULL, 'Party', '000027', NULL, NULL),
(28, 54, NULL, '6000.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000015 payment: 6000', NULL, NULL, 'No', NULL, '2022-06-20 17:52:03', '2022-06-20 17:52:03', NULL, 'PartySale', 17, NULL, NULL, NULL, NULL, 'Party', '000028', NULL, NULL),
(29, 55, NULL, '10000.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000016 payment: 10000', NULL, NULL, 'No', NULL, '2022-06-20 17:54:40', '2022-06-20 17:54:40', NULL, 'PartySale', 18, NULL, NULL, NULL, NULL, 'Party', '000029', NULL, NULL),
(30, 55, NULL, '10000.00', 4, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000016 payment: 10000', NULL, NULL, 'No', NULL, '2022-06-20 17:54:40', '2022-06-20 17:54:40', NULL, 'PartySale', 18, NULL, NULL, NULL, NULL, 'Party', '000030', NULL, NULL),
(31, 2, NULL, '500.00', 1, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000003 payment: 500', NULL, 1, 'Yes', '2022-06-20 20:18:28', '2022-06-21 01:18:11', '2022-06-21 01:18:28', NULL, 'PartySale', 19, NULL, NULL, NULL, NULL, 'Party', '000031', NULL, NULL),
(32, 2, NULL, '500.00', 1, NULL, 'Cash', NULL, '2022-06-20', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000004 payment: 500', NULL, 1, 'Yes', '2022-06-20 20:22:14', '2022-06-21 01:22:01', '2022-06-21 01:22:14', NULL, 'PartySale', 20, NULL, NULL, NULL, NULL, 'Party', '000032', NULL, NULL),
(33, 58, 3, '44000.00', 2, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000003 payment: 44000', NULL, NULL, 'No', NULL, '2022-06-21 16:46:03', '2022-06-21 16:46:03', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000033', NULL, NULL),
(34, 58, 3, '44000.00', 2, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Payment', 'Payment for purchase code: 000003 payment: 44000', NULL, NULL, 'No', NULL, '2022-06-21 16:46:03', '2022-06-21 16:46:03', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000034', NULL, NULL),
(35, 60, NULL, '2680.00', 2, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000017 payment: 2680', NULL, NULL, 'No', NULL, '2022-06-21 17:04:05', '2022-06-21 17:04:05', NULL, 'PartySale', 21, NULL, NULL, NULL, NULL, 'Party', '000035', NULL, NULL),
(36, 60, NULL, '2680.00', 2, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000017 payment: 2680', NULL, NULL, 'No', NULL, '2022-06-21 17:04:05', '2022-06-21 17:04:05', NULL, 'PartySale', 21, NULL, NULL, NULL, NULL, 'Party', '000036', NULL, NULL),
(37, 61, NULL, '165000.00', 2, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000018 payment: 165000', NULL, NULL, 'No', NULL, '2022-06-21 17:21:42', '2022-06-21 17:21:42', NULL, 'PartySale', 22, NULL, NULL, NULL, NULL, 'Party', '000037', NULL, NULL),
(38, 61, NULL, '165000.00', 2, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000018 payment: 165000', NULL, NULL, 'No', NULL, '2022-06-21 17:21:42', '2022-06-21 17:21:42', NULL, 'PartySale', 22, NULL, NULL, NULL, NULL, 'Party', '000038', NULL, NULL),
(39, 8, 4, '88000.00', 2, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000004 payment: 88000', NULL, NULL, 'No', NULL, '2022-06-21 17:38:31', '2022-06-21 17:38:31', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000039', NULL, NULL),
(40, 8, 4, '88000.00', 2, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Payment', 'Payment for purchase code: 000004 payment: 88000', NULL, NULL, 'No', NULL, '2022-06-21 17:38:31', '2022-06-21 17:38:31', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000040', NULL, NULL),
(41, 62, NULL, '37000.00', 2, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000019 payment: 37000', NULL, NULL, 'No', NULL, '2022-06-21 17:42:52', '2022-06-21 17:42:52', NULL, 'PartySale', 23, NULL, NULL, NULL, NULL, 'Party', '000041', NULL, NULL),
(42, 62, NULL, '37000.00', 2, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000019 payment: 37000', NULL, NULL, 'No', NULL, '2022-06-21 17:42:52', '2022-06-21 17:42:52', NULL, 'PartySale', 23, NULL, NULL, NULL, NULL, 'Party', '000042', NULL, NULL),
(43, 63, 5, '300.00', 2, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000005 payment: 300', NULL, NULL, 'No', NULL, '2022-06-21 17:58:56', '2022-06-21 17:58:56', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000043', NULL, NULL),
(44, 63, 5, '300.00', 2, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Payment', 'Payment for purchase code: 000005 payment: 300', NULL, NULL, 'No', NULL, '2022-06-21 17:58:56', '2022-06-21 17:58:56', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000044', NULL, NULL),
(45, 64, NULL, '900.00', 2, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000020 payment: 900', NULL, NULL, 'No', NULL, '2022-06-21 18:00:23', '2022-06-21 18:00:23', NULL, 'PartySale', 24, NULL, NULL, NULL, NULL, 'Party', '000045', NULL, NULL),
(46, 64, NULL, '900.00', 2, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000020 payment: 900', NULL, NULL, 'No', NULL, '2022-06-21 18:00:23', '2022-06-21 18:00:23', NULL, 'PartySale', 24, NULL, NULL, NULL, NULL, 'Party', '000046', NULL, NULL),
(47, 88, NULL, '9500.00', 2, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000021 payment: 9500', NULL, NULL, 'No', NULL, '2022-06-21 20:21:39', '2022-06-21 20:21:39', NULL, 'PartySale', 25, NULL, NULL, NULL, NULL, 'Party', '000047', NULL, NULL),
(48, 88, NULL, '9500.00', 2, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000021 payment: 9500', NULL, NULL, 'No', NULL, '2022-06-21 20:21:39', '2022-06-21 20:21:39', NULL, 'PartySale', 25, NULL, NULL, NULL, NULL, 'Party', '000048', NULL, NULL),
(49, 59, 6, '0.00', 2, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000006 payment: 0', NULL, NULL, 'No', NULL, '2022-06-21 20:46:16', '2022-06-21 20:46:16', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000049', NULL, NULL),
(50, 90, NULL, '9000.00', 2, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000023 payment: 9000', NULL, NULL, 'No', NULL, '2022-06-21 20:56:28', '2022-06-21 20:56:28', NULL, 'PartySale', 27, NULL, NULL, NULL, NULL, 'Party', '000050', NULL, NULL),
(51, 91, NULL, '43500.00', 2, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000005 payment: 43500', NULL, NULL, 'No', NULL, '2022-06-21 21:09:43', '2022-06-21 21:09:43', NULL, 'PartySale', 28, NULL, NULL, NULL, NULL, 'Party', '000051', NULL, NULL),
(52, 91, NULL, '43500.00', 2, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000005 payment: 43500', NULL, NULL, 'No', NULL, '2022-06-21 21:09:43', '2022-06-21 21:09:43', NULL, 'PartySale', 28, NULL, NULL, NULL, NULL, 'Party', '000052', NULL, NULL),
(53, 94, 7, '10400.00', 4, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000007 payment: 10400', NULL, NULL, 'No', NULL, '2022-06-21 21:45:01', '2022-06-21 21:45:01', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000053', NULL, NULL),
(54, 94, 7, '10400.00', 4, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Payment', 'Payment for purchase code: 000007 payment: 10400', NULL, NULL, 'No', NULL, '2022-06-21 21:45:01', '2022-06-21 21:45:01', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000054', NULL, NULL),
(55, 96, NULL, '12790.00', 4, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000024 payment: 12790', NULL, NULL, 'No', NULL, '2022-06-21 21:48:44', '2022-06-21 21:48:44', NULL, 'PartySale', 29, NULL, NULL, NULL, NULL, 'Party', '000055', NULL, NULL),
(56, 96, NULL, '12790.00', 4, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000024 payment: 12790', NULL, NULL, 'No', NULL, '2022-06-21 21:48:44', '2022-06-21 21:48:44', NULL, 'PartySale', 29, NULL, NULL, NULL, NULL, 'Party', '000056', NULL, NULL),
(57, 34, 8, '250.00', 4, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000008 payment: 250', NULL, NULL, 'No', NULL, '2022-06-21 21:52:42', '2022-06-21 21:52:42', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000057', NULL, NULL),
(58, 97, NULL, '1070.00', 4, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000025 payment: 1070', NULL, NULL, 'No', NULL, '2022-06-21 21:57:54', '2022-06-21 21:57:54', NULL, 'PartySale', 30, NULL, NULL, NULL, NULL, 'Party', '000058', NULL, NULL),
(59, 97, NULL, '1070.00', 4, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000025 payment: 1070', NULL, NULL, 'No', NULL, '2022-06-21 21:57:54', '2022-06-21 21:57:54', NULL, 'PartySale', 30, NULL, NULL, NULL, NULL, 'Party', '000059', NULL, NULL),
(60, 98, NULL, '17400.00', 4, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000026 payment: 17400', NULL, NULL, 'No', NULL, '2022-06-21 22:02:57', '2022-06-21 22:02:57', NULL, 'PartySale', 31, NULL, NULL, NULL, NULL, 'Party', '000060', NULL, NULL),
(61, 98, NULL, '17400.00', 4, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000026 payment: 17400', NULL, NULL, 'No', NULL, '2022-06-21 22:02:57', '2022-06-21 22:02:57', NULL, 'PartySale', 31, NULL, NULL, NULL, NULL, 'Party', '000061', NULL, NULL),
(62, 99, NULL, '23200.00', 4, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000027 payment: 23200', NULL, NULL, 'No', NULL, '2022-06-21 22:07:25', '2022-06-21 22:07:25', NULL, 'PartySale', 32, NULL, NULL, NULL, NULL, 'Party', '000062', NULL, NULL),
(63, 99, NULL, '23200.00', 4, NULL, 'Cash', NULL, '2022-06-21', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000027 payment: 23200', NULL, NULL, 'No', NULL, '2022-06-21 22:07:25', '2022-06-21 22:07:25', NULL, 'PartySale', 32, NULL, NULL, NULL, NULL, 'Party', '000063', NULL, NULL),
(64, 100, NULL, '2300.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000028 payment: 2300', NULL, NULL, 'No', NULL, '2022-06-22 15:12:53', '2022-06-22 15:12:53', NULL, 'PartySale', 33, NULL, NULL, NULL, NULL, 'Party', '000064', NULL, NULL),
(65, 100, NULL, '2300.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000028 payment: 2300', NULL, NULL, 'No', NULL, '2022-06-22 15:12:53', '2022-06-22 15:12:53', NULL, 'PartySale', 33, NULL, NULL, NULL, NULL, 'Party', '000065', NULL, NULL),
(66, 44, NULL, '4500.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000006 payment: 4500', NULL, NULL, 'No', NULL, '2022-06-22 15:14:49', '2022-06-22 15:14:49', NULL, 'PartySale', 34, NULL, NULL, NULL, NULL, 'Party', '000066', NULL, NULL),
(67, 44, NULL, '4500.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000006 payment: 4500', NULL, NULL, 'No', NULL, '2022-06-22 15:14:49', '2022-06-22 15:14:49', NULL, 'PartySale', 34, NULL, NULL, NULL, NULL, 'Party', '000067', NULL, NULL),
(68, 62, NULL, '60000.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000029 payment: 60000', NULL, NULL, 'No', NULL, '2022-06-22 15:20:04', '2022-06-22 15:20:04', NULL, 'PartySale', 35, NULL, NULL, NULL, NULL, 'Party', '000068', NULL, NULL),
(69, 62, NULL, '60000.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000029 payment: 60000', NULL, NULL, 'No', NULL, '2022-06-22 15:20:04', '2022-06-22 15:20:04', NULL, 'PartySale', 35, NULL, NULL, NULL, NULL, 'Party', '000069', NULL, NULL),
(70, 102, NULL, '49000.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000030 payment: 49000', NULL, NULL, 'No', NULL, '2022-06-22 15:27:49', '2022-06-22 15:27:49', NULL, 'PartySale', 36, NULL, NULL, NULL, NULL, 'Party', '000070', NULL, NULL),
(71, 103, NULL, '49000.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-22 15:29:56', '2022-06-22 15:29:56', NULL, 'PartySale', NULL, NULL, NULL, NULL, NULL, 'Party', '000071', NULL, NULL),
(72, 102, NULL, '50000.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Voucher Entry for Sale Return', NULL, NULL, 'No', NULL, '2022-06-22 15:35:29', '2022-06-22 15:35:29', NULL, 'SalesReturn', NULL, NULL, 7, NULL, NULL, 'Party', '072', NULL, NULL),
(73, 103, NULL, '0.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-22 15:37:17', '2022-06-22 15:37:17', NULL, 'PartySale', NULL, NULL, NULL, NULL, NULL, 'Party', '000073', NULL, NULL),
(74, 103, NULL, '49000.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000007 payment: 49000', NULL, NULL, 'No', NULL, '2022-06-22 15:38:12', '2022-06-22 15:38:12', NULL, 'PartySale', 37, NULL, NULL, NULL, NULL, 'Party', '000074', NULL, NULL),
(75, 63, 9, '100.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000009 payment: 100', NULL, NULL, 'No', NULL, '2022-06-22 15:54:36', '2022-06-22 15:54:36', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000073', NULL, NULL),
(76, 63, 10, '200.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000010 payment: 200', NULL, NULL, 'No', NULL, '2022-06-22 15:56:46', '2022-06-22 15:56:46', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000073', NULL, NULL),
(77, 104, NULL, '900.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000031 payment: 900', NULL, NULL, 'No', NULL, '2022-06-22 15:58:01', '2022-06-22 15:58:01', NULL, 'PartySale', 38, NULL, NULL, NULL, NULL, 'Party', '000075', NULL, NULL),
(78, 104, NULL, '900.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000031 payment: 900', NULL, NULL, 'No', NULL, '2022-06-22 15:58:01', '2022-06-22 15:58:01', NULL, 'PartySale', 38, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(79, 103, NULL, '49000.00', 4, NULL, 'IFIC BANK', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment Received', 'Voucher Entry for Payment Received [Paid]', NULL, NULL, 'No', NULL, '2022-06-22 15:59:32', '2022-06-22 15:59:32', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000073', NULL, NULL),
(80, 105, NULL, '3000.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000032 payment: 3000', NULL, NULL, 'No', NULL, '2022-06-22 16:11:51', '2022-06-22 16:11:51', NULL, 'PartySale', 39, NULL, NULL, NULL, NULL, 'Party', '000077', NULL, NULL),
(81, 105, NULL, '3000.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000032 payment: 3000', NULL, NULL, 'No', NULL, '2022-06-22 16:11:51', '2022-06-22 16:11:51', NULL, 'PartySale', 39, NULL, NULL, NULL, NULL, 'Party', '000078', NULL, NULL),
(82, 106, NULL, '1500.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000033 payment: 1500', NULL, NULL, 'No', NULL, '2022-06-22 16:18:49', '2022-06-22 16:18:49', NULL, 'PartySale', 40, NULL, NULL, NULL, NULL, 'Party', '000079', NULL, NULL),
(83, 106, NULL, '1500.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000033 payment: 1500', NULL, NULL, 'No', NULL, '2022-06-22 16:18:49', '2022-06-22 16:18:49', NULL, 'PartySale', 40, NULL, NULL, NULL, NULL, 'Party', '000080', NULL, NULL),
(84, 106, NULL, '32000.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000034 payment: 32000', NULL, NULL, 'No', NULL, '2022-06-22 16:25:47', '2022-06-22 16:25:47', NULL, 'PartySale', 41, NULL, NULL, NULL, NULL, 'Party', '000081', NULL, NULL),
(85, 106, NULL, '32000.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000034 payment: 32000', NULL, NULL, 'No', NULL, '2022-06-22 16:25:47', '2022-06-22 16:25:47', NULL, 'PartySale', 41, NULL, NULL, NULL, NULL, 'Party', '000082', NULL, NULL),
(86, 63, 11, '600.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000011 payment: 600', NULL, NULL, 'No', NULL, '2022-06-22 16:27:43', '2022-06-22 16:27:43', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000073', NULL, NULL),
(87, 106, NULL, '2030.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000035 payment: 2030', NULL, NULL, 'No', NULL, '2022-06-22 16:32:03', '2022-06-22 16:32:03', NULL, 'PartySale', 42, NULL, NULL, NULL, NULL, 'Party', '000083', NULL, NULL),
(88, 106, NULL, '2030.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000035 payment: 2030', NULL, NULL, 'No', NULL, '2022-06-22 16:32:03', '2022-06-22 16:32:03', NULL, 'PartySale', 42, NULL, NULL, NULL, NULL, 'Party', '000084', NULL, NULL),
(89, 106, NULL, '520.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000036 payment: 520', NULL, NULL, 'No', NULL, '2022-06-22 16:39:56', '2022-06-22 16:39:56', NULL, 'PartySale', 43, NULL, NULL, NULL, NULL, 'Party', '000085', NULL, NULL),
(90, 106, NULL, '520.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000036 payment: 520', NULL, NULL, 'No', NULL, '2022-06-22 16:39:56', '2022-06-22 16:39:56', NULL, 'PartySale', 43, NULL, NULL, NULL, NULL, 'Party', '000086', NULL, NULL),
(91, 107, NULL, '1200.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000008 payment: 1200', NULL, NULL, 'No', NULL, '2022-06-22 16:44:11', '2022-06-22 16:44:11', NULL, 'PartySale', 44, NULL, NULL, NULL, NULL, 'Party', '000087', NULL, NULL),
(92, 107, NULL, '1200.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000008 payment: 1200', NULL, NULL, 'No', NULL, '2022-06-22 16:44:11', '2022-06-22 16:44:11', NULL, 'PartySale', 44, NULL, NULL, NULL, NULL, 'Party', '000088', NULL, NULL),
(93, 106, NULL, '520.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000037 payment: 520', NULL, NULL, 'No', NULL, '2022-06-22 16:45:40', '2022-06-22 16:45:40', NULL, 'PartySale', 45, NULL, NULL, NULL, NULL, 'Party', '000089', NULL, NULL),
(94, 106, NULL, '520.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000037 payment: 520', NULL, NULL, 'No', NULL, '2022-06-22 16:45:40', '2022-06-22 16:45:40', NULL, 'PartySale', 45, NULL, NULL, NULL, NULL, 'Party', '000090', NULL, NULL),
(95, 106, NULL, '24000.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000038 payment: 24000', NULL, NULL, 'No', NULL, '2022-06-22 16:49:44', '2022-06-22 16:49:44', NULL, 'PartySale', 46, NULL, NULL, NULL, NULL, 'Party', '000091', NULL, NULL),
(96, 106, NULL, '24000.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000038 payment: 24000', NULL, NULL, 'No', NULL, '2022-06-22 16:49:44', '2022-06-22 16:49:44', NULL, 'PartySale', 46, NULL, NULL, NULL, NULL, 'Party', '000092', NULL, NULL),
(97, 106, NULL, '27000.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Voucher Entry for Sale Return', NULL, NULL, 'No', NULL, '2022-06-22 16:51:07', '2022-06-22 16:51:07', NULL, 'SalesReturn', NULL, NULL, 8, NULL, NULL, 'Party', '073', NULL, NULL),
(98, 107, NULL, '1200.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Voucher Entry for Sale Return', NULL, NULL, 'No', NULL, '2022-06-22 16:51:43', '2022-06-22 16:51:43', NULL, 'SalesReturn', NULL, NULL, 10, NULL, NULL, 'Party', '074', NULL, NULL),
(99, 91, NULL, '44000.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Voucher Entry for Sale Return', NULL, NULL, 'No', NULL, '2022-06-22 16:52:40', '2022-06-22 16:52:40', NULL, 'SalesReturn', NULL, NULL, 11, NULL, NULL, 'Party', '075', NULL, NULL),
(100, 108, NULL, '435000.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment', 'Voucher Entry for Payment [Paid]', NULL, 4, 'Yes', '2022-06-22 12:01:26', '2022-06-22 16:59:48', '2022-06-22 17:01:26', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(101, 91, NULL, '43500.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000009 payment: 43500', NULL, NULL, 'No', NULL, '2022-06-22 17:00:24', '2022-06-22 17:00:24', NULL, 'PartySale', 47, NULL, NULL, NULL, NULL, 'Party', '000093', NULL, NULL),
(102, 91, NULL, '44000.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000010 payment: 44000', NULL, NULL, 'No', NULL, '2022-06-22 17:09:55', '2022-06-22 17:09:55', NULL, 'PartySale', 48, NULL, NULL, NULL, NULL, 'Party', '000094', NULL, NULL),
(103, 91, NULL, '43500.00', 4, NULL, 'EFT', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment Received', 'Voucher Entry for Payment Received [Paid]', NULL, NULL, 'No', NULL, '2022-06-22 17:10:30', '2022-06-22 17:10:30', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(104, 63, 12, '810.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000012 payment: 810', NULL, NULL, 'No', NULL, '2022-06-22 17:15:00', '2022-06-22 17:15:00', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(105, 63, 13, '630.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000013 payment: 630', NULL, NULL, 'No', NULL, '2022-06-22 17:19:23', '2022-06-22 17:19:23', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(106, 109, NULL, '3760.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000039 payment: 3760', NULL, NULL, 'No', NULL, '2022-06-22 17:23:25', '2022-06-22 17:23:25', NULL, 'PartySale', 49, NULL, NULL, NULL, NULL, 'Party', '000095', NULL, NULL),
(107, 109, NULL, '3760.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000039 payment: 3760', NULL, NULL, 'No', NULL, '2022-06-22 17:23:25', '2022-06-22 17:23:25', NULL, 'PartySale', 49, NULL, NULL, NULL, NULL, 'Party', '000096', NULL, NULL),
(108, 110, NULL, '6500.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000040 payment: 6500', NULL, NULL, 'No', NULL, '2022-06-22 17:45:21', '2022-06-22 17:45:21', NULL, 'PartySale', 50, NULL, NULL, NULL, NULL, 'Party', '000097', NULL, NULL),
(109, 110, NULL, '6500.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000040 payment: 6500', NULL, NULL, 'No', NULL, '2022-06-22 17:45:21', '2022-06-22 17:45:21', NULL, 'PartySale', 50, NULL, NULL, NULL, NULL, 'Party', '000098', NULL, NULL),
(110, 111, 14, '6000.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000014 payment: 6000', NULL, NULL, 'No', NULL, '2022-06-22 17:51:21', '2022-06-22 17:51:21', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(111, 59, 15, '7600.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000015 payment: 7600', NULL, NULL, 'No', NULL, '2022-06-22 17:52:12', '2022-06-22 17:52:12', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(112, 56, 16, '6000.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000016 payment: 6000', NULL, NULL, 'No', NULL, '2022-06-22 17:57:32', '2022-06-22 17:57:32', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(113, 112, 17, '9000.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000017 payment: 9000', NULL, NULL, 'No', NULL, '2022-06-22 18:01:17', '2022-06-22 18:01:17', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(114, 113, NULL, '26700.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000041 payment: 26700', NULL, NULL, 'No', NULL, '2022-06-22 18:05:40', '2022-06-22 18:05:40', NULL, 'PartySale', 51, NULL, NULL, NULL, NULL, 'Party', '000099', NULL, NULL),
(115, 113, NULL, '26700.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000041 payment: 26700', NULL, NULL, 'No', NULL, '2022-06-22 18:05:40', '2022-06-22 18:05:40', NULL, 'PartySale', 51, NULL, NULL, NULL, NULL, 'Party', '000100', NULL, NULL),
(116, 112, NULL, '9000.00', 4, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Parchase return entry for return # 000002', NULL, NULL, 'No', NULL, '2022-06-22 20:01:00', '2022-06-22 20:01:00', NULL, 'PurchaseReturn', NULL, 2, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(117, 2, 18, '2500.00', 1, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000018 payment: 2500', NULL, NULL, 'Yes', NULL, '2022-06-23 03:23:46', '2022-06-23 03:23:46', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(118, 2, 18, '500.00', 1, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment', 'Payment for purchase code: 000018 payment: 2500', NULL, NULL, 'Yes', NULL, '2022-06-23 03:23:46', '2022-06-23 03:23:46', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(119, 1, 19, '2500.00', 1, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000019 payment: 2500', NULL, 1, 'Yes', '2022-06-22 22:42:16', '2022-06-23 03:32:43', '2022-06-23 03:42:16', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(120, 1, 19, '500.00', 1, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment', 'Payment for purchase code: 000019 payment: 2500', NULL, 1, 'Yes', '2022-06-22 22:42:16', '2022-06-23 03:32:43', '2022-06-23 03:42:16', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(121, 1, 20, '5000.00', 1, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000020 payment: 5000', NULL, NULL, 'No', NULL, '2022-06-23 03:47:19', '2022-06-23 03:47:19', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(122, 1, 20, '2000.00', 1, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment', 'Payment for purchase code: 000020 payment: 5000', NULL, NULL, 'No', NULL, '2022-06-23 03:47:19', '2022-06-23 03:47:19', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(123, 1, 21, '2500.00', 1, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000021 payment: 2500', NULL, NULL, 'No', NULL, '2022-06-23 03:49:14', '2022-06-23 03:49:14', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(124, 1, 21, '1500.00', 1, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment', 'Payment for purchase code: 000021 payment: 2500', NULL, NULL, 'No', NULL, '2022-06-23 03:49:14', '2022-06-23 03:49:14', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(125, 1, 22, '2500.00', 1, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000022 payment: 2500', NULL, NULL, 'No', NULL, '2022-06-23 03:51:42', '2022-06-23 03:51:42', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(126, 1, 22, '15000.00', 1, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment', 'Payment for purchase code: 000022 payment: 2500', NULL, NULL, 'No', NULL, '2022-06-23 03:51:42', '2022-06-23 03:51:42', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(127, 2, 23, '2500.00', 1, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000023 payment: 2500', NULL, NULL, 'No', NULL, '2022-06-23 03:56:21', '2022-06-23 03:56:21', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(128, 2, 23, '1500.00', 1, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment', 'Payment for purchase code: 000023 payment: 2500', NULL, NULL, 'No', NULL, '2022-06-23 03:56:21', '2022-06-23 03:56:21', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(129, 2, NULL, '2500.00', 1, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000011 payment: 2500', NULL, NULL, 'No', NULL, '2022-06-23 04:10:30', '2022-06-23 04:10:30', NULL, 'PartySale', 52, NULL, NULL, NULL, NULL, 'Party', '000101', NULL, NULL),
(130, 2, NULL, '1500.00', 1, NULL, 'Cash', NULL, '2022-06-22', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000011 payment: 2500', NULL, NULL, 'No', NULL, '2022-06-23 04:10:30', '2022-06-23 04:10:30', NULL, 'PartySale', 52, NULL, NULL, NULL, NULL, 'Party', '000102', NULL, NULL),
(131, 114, 24, '137000.00', 4, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000024 payment: 137000', NULL, NULL, 'No', NULL, '2022-06-23 15:48:14', '2022-06-23 15:48:14', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(132, 114, 24, '137000.00', 4, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payment', 'Payment for purchase code: 000024 payment: 137000', NULL, NULL, 'No', NULL, '2022-06-23 15:48:14', '2022-06-23 15:48:14', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(133, 14, 25, '3900.00', 4, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000025 payment: 3900', NULL, NULL, 'No', NULL, '2022-06-23 15:57:54', '2022-06-23 15:57:54', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(134, 14, NULL, '137890.00', 4, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-23 15:58:43', '2022-06-23 15:58:43', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(135, 36, NULL, '19584.00', 4, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-23 16:00:25', '2022-06-23 16:00:25', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(136, 115, NULL, '29280.00', 4, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-23 16:04:00', '2022-06-23 16:04:00', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(137, 6, NULL, '36750.00', 4, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-23 16:05:18', '2022-06-23 16:05:18', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(138, 34, NULL, '4590.00', 4, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-23 16:06:15', '2022-06-23 16:06:15', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(139, 118, NULL, '8450.00', 4, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-23 16:27:13', '2022-06-23 16:27:13', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(140, 4, NULL, '90000.00', 4, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-23 16:27:50', '2022-06-23 16:27:50', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(141, 8, NULL, '133000.00', 4, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-23 16:37:57', '2022-06-23 16:37:57', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(142, 119, NULL, '421600.00', 4, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-23 16:39:46', '2022-06-23 16:39:46', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(143, 33, NULL, '59200.00', 4, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000012 payment: 59200', NULL, NULL, 'No', NULL, '2022-06-23 17:15:53', '2022-06-23 17:15:53', NULL, 'PartySale', 53, NULL, NULL, NULL, NULL, 'Party', '000103', NULL, NULL),
(144, 14, 26, '22000.00', 4, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000026 payment: 22000', NULL, NULL, 'No', NULL, '2022-06-23 18:05:45', '2022-06-23 18:05:45', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(145, 122, 27, '3300.00', 4, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000027 payment: 3300', NULL, NULL, 'No', NULL, '2022-06-23 18:11:13', '2022-06-23 18:11:13', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(146, 122, 27, '3300.00', 4, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payment', 'Payment for purchase code: 000027 payment: 3300', NULL, NULL, 'No', NULL, '2022-06-23 18:11:13', '2022-06-23 18:11:13', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(147, 14, 28, '19200.00', 4, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000028 payment: 19200', NULL, NULL, 'No', NULL, '2022-06-23 18:14:06', '2022-06-23 18:14:06', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(148, 15, NULL, '1690400.00', 4, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-23 18:18:07', '2022-06-23 18:18:07', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(149, 1, 29, '2500.00', 1, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000029 payment: 2500', NULL, NULL, 'No', NULL, '2022-06-23 21:14:21', '2022-06-23 21:14:21', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(150, 1, 29, '1500.00', 1, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payment', 'Payment for purchase code: 000029 payment: 2500', NULL, NULL, 'No', NULL, '2022-06-23 21:14:21', '2022-06-23 21:14:21', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(151, 2, 30, '2500.00', 1, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000030 payment: 2500', NULL, NULL, 'No', NULL, '2022-06-23 21:16:35', '2022-06-23 21:16:35', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(152, 2, 30, '1500.00', 1, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payment', 'Payment for purchase code: 000030 payment: 2500', NULL, NULL, 'No', NULL, '2022-06-23 21:16:35', '2022-06-23 21:16:35', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(153, 2, NULL, '6000.00', 1, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-23 21:24:02', '2022-06-23 21:24:02', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(154, 2, NULL, '2000.00', 1, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000013 payment: 2000', NULL, NULL, 'No', NULL, '2022-06-23 21:42:57', '2022-06-23 21:42:57', NULL, 'PartySale', 54, NULL, NULL, NULL, NULL, 'Party', '000104', NULL, NULL),
(155, 2, NULL, '2000.00', 1, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000013 payment: 2000', NULL, NULL, 'No', NULL, '2022-06-23 21:42:57', '2022-06-23 21:42:57', NULL, 'PartySale', 54, NULL, NULL, NULL, NULL, 'Party', '000105', NULL, NULL),
(156, 2, NULL, '2000.00', 1, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payment Received', 'Voucher Entry for Payment Received [This is Test]', NULL, NULL, 'No', NULL, '2022-06-23 21:47:19', '2022-06-23 21:47:19', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(157, 2, NULL, '5000.00', 1, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payment', 'Voucher Entry for Payment [This is Test]', NULL, NULL, 'No', NULL, '2022-06-23 21:48:20', '2022-06-23 21:48:20', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(158, 2, NULL, '1500.00', 1, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Discount', 'Voucher Entry for Discount [This is Test]', NULL, NULL, 'No', NULL, '2022-06-23 21:49:31', '2022-06-23 21:49:31', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(159, 2, NULL, '-1500.00', 1, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Discount', 'Voucher Entry for Discount [This is Test]', NULL, NULL, 'No', NULL, '2022-06-23 21:50:54', '2022-06-23 21:50:54', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(160, 2, 31, '1000.00', 1, NULL, 'Cash', NULL, '2022-06-23', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000031 payment: 1000', NULL, NULL, 'No', NULL, '2022-06-23 22:19:25', '2022-06-23 22:19:25', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(161, 123, NULL, '600.00', 4, NULL, 'Cash', NULL, '2022-06-25', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000042 payment: 600', NULL, NULL, 'No', NULL, '2022-06-25 16:36:13', '2022-06-25 16:36:13', NULL, 'PartySale', 55, NULL, NULL, NULL, NULL, 'Party', '000106', NULL, NULL),
(162, 123, NULL, '600.00', 4, NULL, 'Cash', NULL, '2022-06-25', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000042 payment: 600', NULL, NULL, 'No', NULL, '2022-06-25 16:36:13', '2022-06-25 16:36:13', NULL, 'PartySale', 55, NULL, NULL, NULL, NULL, 'Party', '000107', NULL, NULL),
(163, 124, NULL, '6900.00', 4, NULL, 'Cash', NULL, '2022-06-25', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000043 payment: 6900', NULL, NULL, 'No', NULL, '2022-06-25 16:38:09', '2022-06-25 16:38:09', NULL, 'PartySale', 56, NULL, NULL, NULL, NULL, 'Party', '000108', NULL, NULL),
(164, 124, NULL, '6900.00', 4, NULL, 'Cash', NULL, '2022-06-25', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000043 payment: 6900', NULL, NULL, 'No', NULL, '2022-06-25 16:38:09', '2022-06-25 16:38:09', NULL, 'PartySale', 56, NULL, NULL, NULL, NULL, 'Party', '000109', NULL, NULL);
INSERT INTO `payment_vouchers` (`id`, `party_id`, `purchase_id`, `amount`, `entryBy`, `discount`, `payment_method`, `chequeNo`, `paymentDate`, `chequeIssueDate`, `accountNo`, `status`, `type`, `remarks`, `tbl_bankInfoId`, `deletedBy`, `deleted`, `deletedDate`, `created_at`, `updated_at`, `lastUpdatedBy`, `voucherType`, `sales_id`, `purchase_return_id`, `sales_return_id`, `expense_id`, `tbl_repairing_center_id`, `customerType`, `voucherNo`, `chequeBank`, `dbInsertDate`) VALUES
(165, 125, NULL, '24000.00', 4, NULL, 'Cash', NULL, '2022-06-25', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000044 payment: 24000', NULL, NULL, 'No', NULL, '2022-06-25 16:42:51', '2022-06-25 16:42:51', NULL, 'PartySale', 57, NULL, NULL, NULL, NULL, 'Party', '000110', NULL, NULL),
(166, 125, NULL, '24000.00', 4, NULL, 'Cash', NULL, '2022-06-25', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000044 payment: 24000', NULL, NULL, 'No', NULL, '2022-06-25 16:42:51', '2022-06-25 16:42:51', NULL, 'PartySale', 57, NULL, NULL, NULL, NULL, 'Party', '000111', NULL, NULL),
(167, 127, NULL, '2100.00', 4, NULL, 'Cash', NULL, '2022-06-25', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000045 payment: 2100', NULL, NULL, 'No', NULL, '2022-06-25 16:51:30', '2022-06-25 16:51:30', NULL, 'PartySale', 58, NULL, NULL, NULL, NULL, 'Party', '000112', NULL, NULL),
(168, 127, NULL, '2100.00', 4, NULL, 'Cash', NULL, '2022-06-25', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000045 payment: 2100', NULL, NULL, 'No', NULL, '2022-06-25 16:51:30', '2022-06-25 16:51:30', NULL, 'PartySale', 58, NULL, NULL, NULL, NULL, 'Party', '000113', NULL, NULL),
(169, 128, NULL, '2000.00', 4, NULL, 'Cash', NULL, '2022-06-25', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000046 payment: 2000', NULL, NULL, 'No', NULL, '2022-06-25 16:53:28', '2022-06-25 16:53:28', NULL, 'PartySale', 59, NULL, NULL, NULL, NULL, 'Party', '000114', NULL, NULL),
(170, 128, NULL, '2000.00', 4, NULL, 'Cash', NULL, '2022-06-25', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000046 payment: 2000', NULL, NULL, 'No', NULL, '2022-06-25 16:53:28', '2022-06-25 16:53:28', NULL, 'PartySale', 59, NULL, NULL, NULL, NULL, 'Party', '000115', NULL, NULL),
(171, 129, NULL, '4900.00', 4, NULL, 'Cash', NULL, '2022-06-25', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000047 payment: 4900', NULL, NULL, 'No', NULL, '2022-06-25 17:42:23', '2022-06-25 17:42:23', NULL, 'PartySale', 60, NULL, NULL, NULL, NULL, 'Party', '000116', NULL, NULL),
(172, 129, NULL, '4900.00', 4, NULL, 'Cash', NULL, '2022-06-25', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000047 payment: 4900', NULL, NULL, 'No', NULL, '2022-06-25 17:42:23', '2022-06-25 17:42:23', NULL, 'PartySale', 60, NULL, NULL, NULL, NULL, 'Party', '000117', NULL, NULL),
(173, 57, NULL, '150300.00', 4, NULL, 'Cash', NULL, '2022-06-26', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000001 payment: 150300', NULL, NULL, 'No', NULL, '2022-06-26 15:48:34', '2022-06-26 15:48:34', NULL, 'PartySale', 1, NULL, NULL, NULL, NULL, 'Party', '000118', NULL, NULL),
(174, 56, NULL, '10000.00', 4, NULL, 'Cash', NULL, '2022-06-26', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000002 payment: 10000', NULL, NULL, 'No', NULL, '2022-06-26 15:57:45', '2022-06-26 15:57:45', NULL, 'PartySale', 2, NULL, NULL, NULL, NULL, 'Party', '000119', NULL, NULL),
(175, 130, 32, '364000.00', 4, NULL, 'Cash', NULL, '2022-06-26', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000032 payment: 364000', NULL, NULL, 'No', NULL, '2022-06-26 16:24:28', '2022-06-26 16:24:28', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(176, 130, 32, '364000.00', 4, NULL, 'Cash', NULL, '2022-06-26', NULL, NULL, 'Active', 'Payment', 'Payment for purchase code: 000032 payment: 364000', NULL, NULL, 'No', NULL, '2022-06-26 16:24:28', '2022-06-26 16:24:28', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(177, 131, NULL, '12000.00', 4, NULL, 'Cash', NULL, '2022-06-26', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000003 payment: 12000', NULL, NULL, 'No', NULL, '2022-06-26 17:21:47', '2022-06-26 17:21:47', NULL, 'PartySale', 3, NULL, NULL, NULL, NULL, 'Party', '000120', NULL, NULL),
(178, 56, NULL, '10000.00', 1, NULL, 'Cash', NULL, '2022-06-26', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for FS code: 000001 payment: 10000', NULL, NULL, 'No', NULL, '2022-06-26 18:12:01', '2022-06-26 18:12:01', NULL, 'FS', 61, NULL, NULL, NULL, NULL, 'Party', '000121', NULL, NULL),
(179, 57, NULL, '150300.00', 4, NULL, 'Cash', NULL, '2022-06-26', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for FS code: 000002 payment: 150300', NULL, NULL, 'No', NULL, '2022-06-26 21:00:19', '2022-06-26 21:00:19', NULL, 'FS', 62, NULL, NULL, NULL, NULL, 'Party', '000122', NULL, NULL),
(180, 57, NULL, '150300.00', 4, NULL, 'Cash', NULL, '2022-06-26', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for FS code: 000002 payment: 150300', NULL, NULL, 'Yes', NULL, '2022-06-26 21:00:19', '2022-06-26 21:00:19', NULL, 'FS', 62, NULL, NULL, NULL, NULL, 'Party', '000123', NULL, NULL),
(181, 56, NULL, '2800.00', 4, NULL, 'Cash', NULL, '2022-06-26', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000003 payment: 2800', NULL, NULL, 'No', NULL, '2022-06-26 21:04:32', '2022-06-26 21:04:32', NULL, 'PartySale', 63, NULL, NULL, NULL, NULL, 'Party', '000124', NULL, NULL),
(182, 126, NULL, '74300.00', 4, NULL, 'Cash', NULL, '2022-06-26', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000004 payment: 74300', NULL, NULL, 'No', NULL, '2022-06-26 21:29:13', '2022-06-26 21:29:13', NULL, 'PartySale', 64, NULL, NULL, NULL, NULL, 'Party', '000125', NULL, NULL),
(183, 20, NULL, '10050.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-27 15:27:41', '2022-06-27 15:27:41', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(184, 20, NULL, '0.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-27 15:28:49', '2022-06-27 15:28:49', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(185, 20, NULL, '10050.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-27 15:29:33', '2022-06-27 15:29:33', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(186, 134, NULL, '3200.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-27 15:39:24', '2022-06-27 15:39:24', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(187, 63, NULL, '4495.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-27 15:42:56', '2022-06-27 15:42:56', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(188, 135, NULL, '6000.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-27 15:46:23', '2022-06-27 15:46:23', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(189, 120, NULL, '34000.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-27 15:46:56', '2022-06-27 15:46:56', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(190, 136, NULL, '28720.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Party Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-27 15:51:26', '2022-06-27 15:51:26', NULL, 'PartySale', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(191, 126, NULL, '82580.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-27 15:53:02', '2022-06-27 15:53:02', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(192, 135, NULL, '-6000.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Payment Received', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-27 16:00:22', '2022-06-27 16:00:22', NULL, 'PartySale', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(193, 126, NULL, '-82580.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Payment', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-27 16:00:57', '2022-06-27 16:00:57', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(194, 126, NULL, '-82580.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Payment', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-27 16:02:20', '2022-06-27 16:02:20', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(195, 56, NULL, '241740.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-27 16:04:29', '2022-06-27 16:04:29', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(196, 56, NULL, '241740.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Payment', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-27 16:04:33', '2022-06-27 16:04:33', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(197, 137, NULL, '86250.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Party Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-27 16:09:42', '2022-06-27 16:09:42', NULL, 'PartySale', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(198, 141, NULL, '112800.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Party Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-27 16:19:11', '2022-06-27 16:19:11', NULL, 'PartySale', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(199, 141, NULL, '112800.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Party Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-27 16:21:42', '2022-06-27 16:21:42', NULL, 'PartySale', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(200, 142, NULL, '105900.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Party Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-27 16:23:49', '2022-06-27 16:23:49', NULL, 'PartySale', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(201, 143, NULL, '39000.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Party Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-27 16:25:06', '2022-06-27 16:25:06', NULL, 'PartySale', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(202, 140, NULL, '585500.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Party Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-27 16:26:51', '2022-06-27 16:26:51', NULL, 'PartySale', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(203, 131, NULL, '12000.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for FS code: 000005 payment: 12000', NULL, NULL, 'No', NULL, '2022-06-27 16:53:44', '2022-06-27 16:53:44', NULL, 'FS', 65, NULL, NULL, NULL, NULL, 'Party', '000126', NULL, NULL),
(204, 131, NULL, '12000.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for FS code: 000005 payment: 12000', NULL, NULL, 'Yes', NULL, '2022-06-27 16:53:44', '2022-06-27 16:53:44', NULL, 'FS', 65, NULL, NULL, NULL, NULL, 'Party', '000127', NULL, NULL),
(205, 153, NULL, '2800.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000006 payment: 2800', NULL, NULL, 'No', NULL, '2022-06-27 17:05:10', '2022-06-27 17:05:10', NULL, 'PartySale', 66, NULL, NULL, NULL, NULL, 'Party', '000128', NULL, NULL),
(206, 120, NULL, '560.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000007 payment: 560', NULL, NULL, 'No', NULL, '2022-06-27 17:12:09', '2022-06-27 17:12:09', NULL, 'PartySale', 67, NULL, NULL, NULL, NULL, 'Party', '000129', NULL, NULL),
(207, 10, 33, '6600.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000033 payment: 6600', NULL, NULL, 'No', NULL, '2022-06-27 17:26:00', '2022-06-27 17:26:00', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(208, 154, NULL, '7200.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000048 payment: 7200', NULL, NULL, 'No', NULL, '2022-06-27 17:36:22', '2022-06-27 17:36:22', NULL, 'PartySale', 68, NULL, NULL, NULL, NULL, 'Party', '000130', NULL, NULL),
(209, 154, NULL, '7200.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000048 payment: 7200', NULL, NULL, 'No', NULL, '2022-06-27 17:36:22', '2022-06-27 17:36:22', NULL, 'PartySale', 68, NULL, NULL, NULL, NULL, 'Party', '000131', NULL, NULL),
(210, 155, NULL, '29000.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000049 payment: 29000', NULL, NULL, 'No', NULL, '2022-06-27 17:38:10', '2022-06-27 17:38:10', NULL, 'PartySale', 69, NULL, NULL, NULL, NULL, 'Party', '000132', NULL, NULL),
(211, 155, NULL, '29000.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000049 payment: 29000', NULL, NULL, 'No', NULL, '2022-06-27 17:38:10', '2022-06-27 17:38:10', NULL, 'PartySale', 69, NULL, NULL, NULL, NULL, 'Party', '000133', NULL, NULL),
(212, 10, NULL, '1200.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000008 payment: 1200', NULL, NULL, 'No', NULL, '2022-06-27 17:56:10', '2022-06-27 17:56:10', NULL, 'PartySale', 70, NULL, NULL, NULL, NULL, 'Party', '000134', NULL, NULL),
(213, 156, NULL, '23000.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000050 payment: 23000', NULL, NULL, 'No', NULL, '2022-06-27 18:00:26', '2022-06-27 18:00:26', NULL, 'PartySale', 71, NULL, NULL, NULL, NULL, 'Party', '000135', NULL, NULL),
(214, 156, NULL, '23000.00', 4, NULL, 'Cash', NULL, '2022-06-27', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000050 payment: 23000', NULL, NULL, 'No', NULL, '2022-06-27 18:00:26', '2022-06-27 18:00:26', NULL, 'PartySale', 71, NULL, NULL, NULL, NULL, 'Party', '000136', NULL, NULL),
(215, 157, NULL, '616960.00', 4, NULL, 'Cash', NULL, '2022-06-28', NULL, NULL, 'Active', 'Party Payable', 'Update opening due', NULL, NULL, 'No', NULL, '2022-06-28 15:56:05', '2022-06-28 15:56:05', NULL, 'PartySale', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(216, 140, NULL, '78500.00', 4, NULL, 'Cash', NULL, '2022-06-28', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000014 payment: 78500', NULL, NULL, 'No', NULL, '2022-06-28 16:14:30', '2022-06-28 16:14:30', NULL, 'PartySale', 72, NULL, NULL, NULL, NULL, 'Party', '000137', NULL, NULL),
(217, 59, 34, '15200.00', 4, NULL, 'Cash', NULL, '2022-06-28', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000034 payment: 15200', NULL, NULL, 'No', NULL, '2022-06-28 16:24:23', '2022-06-28 16:24:23', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(218, 9, 35, '6240.00', 4, NULL, 'Cash', NULL, '2022-06-28', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000035 payment: 6240', NULL, NULL, 'No', NULL, '2022-06-28 16:30:17', '2022-06-28 16:30:17', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(219, 1, NULL, '41700.00', 4, NULL, 'Cash', NULL, '2022-06-29', NULL, NULL, 'Active', 'Payment', '[Voucher Entry for Expense Cause] Total Expense 11/06/22 From 27/06/22', NULL, NULL, 'No', NULL, '2022-06-29 15:50:45', '2022-06-29 15:50:45', NULL, 'Expense', NULL, NULL, NULL, 1, NULL, 'Party', '000076', NULL, NULL),
(220, 160, NULL, '18000.00', 4, NULL, 'Cash', NULL, '2022-06-29', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000051 payment: 18000', NULL, NULL, 'No', NULL, '2022-06-29 16:29:22', '2022-06-29 16:29:22', NULL, 'PartySale', 73, NULL, NULL, NULL, NULL, 'Party', '000138', NULL, NULL),
(221, 160, NULL, '18000.00', 4, NULL, 'Cash', NULL, '2022-06-29', NULL, NULL, 'Active', 'Payment Received', 'Party payment for purchase code: 000051 payment: 18000', NULL, NULL, 'No', NULL, '2022-06-29 16:29:22', '2022-06-29 16:29:22', NULL, 'PartySale', 73, NULL, NULL, NULL, NULL, 'Party', '000139', NULL, NULL),
(222, 159, NULL, '104000.00', 4, NULL, 'Cash', NULL, '2022-06-29', NULL, NULL, 'Active', 'Party Payable', 'Party Payable for Sale code: 000020 payment: 104000', NULL, NULL, 'No', NULL, '2022-06-29 16:40:44', '2022-06-29 16:40:44', NULL, 'PartySale', 74, NULL, NULL, NULL, NULL, 'Party', '000140', NULL, NULL),
(223, 14, 36, '20000.00', 4, NULL, 'Cash', NULL, '2022-06-29', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000036 payment: 20000', NULL, NULL, 'No', NULL, '2022-06-29 16:45:00', '2022-06-29 16:45:00', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(224, 161, 37, '16500.00', 4, NULL, 'Cash', NULL, '2022-06-29', NULL, NULL, 'Active', 'Payable', 'payable for purchase code: 000037 payment: 16500', NULL, NULL, 'No', NULL, '2022-06-29 16:51:36', '2022-06-29 16:51:36', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL),
(225, 161, 37, '16500.00', 4, NULL, 'Cash', NULL, '2022-06-29', NULL, NULL, 'Active', 'Payment', 'Payment for purchase code: 000037 payment: 16500', NULL, NULL, 'No', NULL, '2022-06-29 16:51:36', '2022-06-29 16:51:36', NULL, 'Local Purchase', NULL, NULL, NULL, NULL, NULL, 'Party', '000076', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_monthly_amounts`
--

CREATE TABLE `payroll_monthly_amounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `facility_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('Add','Deduct') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cause` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `last_updated_by` bigint(20) DEFAULT NULL,
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_settings`
--

CREATE TABLE `payroll_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `absent` int(11) DEFAULT NULL,
  `deduct_amount_for_absent` int(11) DEFAULT NULL,
  `activation` enum('On','Off') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('Consecutive','AnyInMonth') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `last_updated_by` bigint(20) DEFAULT NULL,
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payroll_settings`
--

INSERT INTO `payroll_settings` (`id`, `absent`, `deduct_amount_for_absent`, `activation`, `type`, `created_by`, `last_updated_by`, `deleted`, `deleted_by`, `deleted_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 'On', 'Consecutive', 1, 1, 'No', NULL, NULL, 'Active', NULL, '2021-12-01 02:44:03');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `updated_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `group_name`, `created_at`, `updated_at`, `deleted`, `status`, `updated_by`, `deleted_by`) VALUES
(1, 'dashboard.view', 'web', NULL, '2022-07-05 09:52:25', '2022-07-05 09:52:25', 'No', 'Active', NULL, NULL),
(2, 'user.create', 'web', NULL, '2022-07-05 09:52:25', '2022-07-05 09:52:25', 'No', 'Active', NULL, NULL),
(3, 'user.view', 'web', NULL, '2022-07-05 09:52:25', '2022-07-05 09:52:25', 'No', 'Active', NULL, NULL),
(4, 'user.edit', 'web', NULL, '2022-07-05 09:52:25', '2022-07-05 09:52:25', 'No', 'Active', NULL, NULL),
(5, 'user.store', 'web', NULL, '2022-07-05 09:52:25', '2022-07-05 09:52:25', 'No', 'Active', NULL, NULL),
(6, 'user.delete', 'web', NULL, '2022-07-05 09:52:25', '2022-07-05 09:52:25', 'No', 'Active', NULL, NULL),
(7, 'user.changePassword', 'web', NULL, '2022-07-05 09:52:25', '2022-07-05 09:52:25', 'No', 'Active', NULL, NULL),
(8, 'role.create', 'web', NULL, '2022-07-05 09:52:25', '2022-07-05 09:52:25', 'No', 'Active', NULL, NULL),
(9, 'role.view', 'web', NULL, '2022-07-05 09:52:25', '2022-07-05 09:52:25', 'No', 'Active', NULL, NULL),
(10, 'role.edit', 'web', NULL, '2022-07-05 09:52:25', '2022-07-05 09:52:25', 'No', 'Active', NULL, NULL),
(11, 'role.store', 'web', NULL, '2022-07-05 09:52:25', '2022-07-05 09:52:25', 'No', 'Active', NULL, NULL),
(12, 'role.delete', 'web', NULL, '2022-07-05 09:52:25', '2022-07-05 09:52:25', 'No', 'Active', NULL, NULL),
(13, 'permission.create', 'web', NULL, '2022-07-05 09:52:25', '2022-07-05 09:52:25', 'No', 'Active', NULL, NULL),
(14, 'permission.view', 'web', NULL, '2022-07-05 09:52:25', '2022-07-05 09:52:25', 'No', 'Active', NULL, NULL),
(15, 'permission.edit', 'web', NULL, '2022-07-05 09:52:25', '2022-07-05 09:52:25', 'No', 'Active', NULL, NULL),
(16, 'permission.update', 'web', NULL, '2022-07-05 09:52:25', '2022-07-05 09:52:25', 'No', 'Active', NULL, NULL),
(17, 'permission.store', 'web', NULL, '2022-07-05 09:52:26', '2022-07-05 09:52:26', 'No', 'Active', NULL, NULL),
(18, 'permission.delete', 'web', NULL, '2022-07-05 09:52:26', '2022-07-05 09:52:26', 'No', 'Active', NULL, NULL),
(19, 'permissionToRole.create', 'web', NULL, '2022-07-05 09:52:26', '2022-07-05 09:52:26', 'No', 'Active', NULL, NULL),
(20, 'permissionToRole.view', 'web', NULL, '2022-07-05 09:52:26', '2022-07-05 09:52:26', 'No', 'Active', NULL, NULL),
(21, 'permissionToRole.store', 'web', NULL, '2022-07-05 09:52:26', '2022-07-05 09:52:26', 'No', 'Active', NULL, NULL),
(22, 'permissionToRole.delete', 'web', NULL, '2022-07-05 09:52:26', '2022-07-05 09:52:26', 'No', 'Active', NULL, NULL),
(23, 'companySetting.view', 'web', NULL, '2022-07-05 09:52:26', '2022-07-05 09:52:26', 'No', 'Active', NULL, NULL),
(24, 'companySetting.edit', 'web', NULL, '2022-07-05 09:52:26', '2022-07-05 09:52:26', 'No', 'Active', NULL, NULL),
(25, 'companySetting.update', 'web', NULL, '2022-07-05 09:52:26', '2022-07-05 09:52:26', 'No', 'Active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barcode_no` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barcode` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint(20) NOT NULL,
  `brand_id` bigint(20) NOT NULL,
  `unit_id` bigint(20) NOT NULL,
  `opening_stock` int(11) NOT NULL,
  `remainder_quantity` int(11) NOT NULL DEFAULT 0,
  `purchase_price` decimal(12,2) NOT NULL COMMENT 'min price',
  `sale_price` decimal(12,2) NOT NULL COMMENT 'max price',
  `discount` decimal(10,2) DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `current_stock` int(11) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `purchase_quantity` int(11) NOT NULL DEFAULT 0,
  `sale_quantity` int(11) NOT NULL DEFAULT 0,
  `total_purchase_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_sale_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `remaining_price` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `deleted`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', 'No', 'Active', '2022-07-05 09:52:25', '2022-07-05 09:52:25'),
(2, 'Admin', 'web', 'No', 'Active', '2022-07-05 09:52:25', '2022-07-05 09:52:25'),
(3, 'Manager', 'web', 'No', 'Active', '2022-07-05 09:52:25', '2022-07-05 09:52:25'),
(4, 'Support Engineer', 'web', 'No', 'Active', '2022-07-05 09:52:25', '2022-07-05 09:52:25'),
(5, 'Sales Man', 'web', 'No', 'Active', '2022-07-05 09:52:25', '2022-07-05 09:52:25');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`, `deleted`, `status`) VALUES
(1, 1, 'No', 'Active'),
(2, 1, 'No', 'Active'),
(3, 1, 'No', 'Active'),
(4, 1, 'No', 'Active'),
(5, 1, 'No', 'Active'),
(6, 1, 'No', 'Active'),
(7, 1, 'No', 'Active'),
(8, 1, 'No', 'Active'),
(9, 1, 'No', 'Active'),
(10, 1, 'No', 'Active'),
(11, 1, 'No', 'Active'),
(12, 1, 'No', 'Active'),
(13, 1, 'No', 'Active'),
(14, 1, 'No', 'Active'),
(15, 1, 'No', 'Active'),
(16, 1, 'No', 'Active'),
(17, 1, 'No', 'Active'),
(18, 1, 'No', 'Active'),
(19, 1, 'No', 'Active'),
(20, 1, 'No', 'Active'),
(21, 1, 'No', 'Active'),
(22, 1, 'No', 'Active'),
(23, 1, 'No', 'Active'),
(24, 1, 'No', 'Active'),
(25, 1, 'No', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `salary_instructions`
--

CREATE TABLE `salary_instructions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `month_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sheet_id` bigint(20) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mother_account_no` bigint(20) DEFAULT NULL,
  `footer_instruction` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `letter_body` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `last_updated_by` bigint(20) DEFAULT NULL,
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_instructions`
--

INSERT INTO `salary_instructions` (`id`, `month_year`, `sheet_id`, `total_amount`, `bank_name`, `branch_name`, `mother_account_no`, `footer_instruction`, `letter_body`, `created_by`, `last_updated_by`, `deleted`, `deleted_by`, `deleted_date`, `status`, `created_at`, `updated_at`) VALUES
(23, 'December-2021', 3, NULL, 'ISlami Bank', 'Wasa Branch', 568923, '<p>Copy the data from the Salary Calculation sheet each month and paste it into the respective month sheet. Copy from cell B6 to C33.</p>\r\n\r\n<p>At the end of the year, your Yearly Salary Report is ready.</p>\r\n\r\n<p>Let us discuss the template in detail.</p>\r\n\r\n<h2>Content of Simple Salary Sheet Excel Template</h2>\r\n\r\n<p>This template consists of 15 sheets: Salary Calculation Sheet, Salary Slip Printing Sheet, 12 monthly sheets to record salary data, and Yearly Salary Report Sheet.</p>\r\n\r\n<h3>Salary Calculation Sheet</h3>\r\n\r\n<p>First of all, In cell D4 and D5 insert your company name and company address. This name and address will reflect in the salary slips of employees.</p>\r\n\r\n<p><img alt=\"Simple Salary Sheet\" src=\"http://msofficegeek.com/wp-content/uploads/2021/04/Employer-Details.jpg\" style=\"height:44px; width:650px\" /></p>\r\n\r\n<p>Select the month and year from the drop-down menu.</p>', '<h2>What is a Simple Salary Sheet?</h2>\r\n\r\n<p>A simple salary sheet is a document that records the monthly salary data of employees. Like a detailed salary sheet, this sheet does not record each allowance or deduction separately but includes the whole figure.&nbsp;In other words, it reports the total amount of all allowances as well as deductions.</p>\r\n\r\n<h2>Formula to Calculate Salary</h2>\r\n\r\n<p>The salary payable is the amount that remains after adding allowances and subtracting deductions from the basic salary. If an employee has opted for a salary advance it will be added to the deduction amount and then subtracted from the gross salary.</p>\r\n\r\n<blockquote>\r\n<p><em><strong>Gross Salary = Basic + Allowances</strong></em></p>\r\n</blockquote>\r\n\r\n<p>Where: Allowances include HRA, conveyance, medial, etc.</p>\r\n\r\n<blockquote>\r\n<p><em><strong>Net Salary = Gross Salary &ndash; Deductions</strong></em></p>\r\n</blockquote>\r\n\r\n<p>Where: Deductions include professional tax, other federal or state taxes, salary advances, etc.</p>\r\n\r\n<h2>How To Easily Manage Salary Using A Spreadsheet Template?</h2>\r\n\r\n<p>The salary preparation cannot easier than this. Just 1 step process.</p>\r\n\r\n<p>Insert the respective salary amounts against each employee and you are ready to print the salary slip.</p>\r\n.\r\n.\r\n.\r\n.\r\n.\r\nThe salary payable is the amount that remains after adding allowances and subtracting deductions from the\r\nbasic salary. If an employee has opted for a salary advance it will be added to the deduction amount and then\r\nsubtracted from the gross salary.\r\nLike a detailed salary\r\nsheet, this sheet does not record each allowance or deduction separately but includes the whole figure. In other\r\nwords, it reports the total amount of all allowances as well as deductions.', 1, 1, 'No', NULL, NULL, 'Active', '2021-11-20 06:29:10', '2021-11-20 06:34:33'),
(24, 'November-2021', 3, NULL, 'HSBC Bank', 'O.R.Nizam Road', 526398, '<h2>What is Lorem Ipsum?</h2>\r\n\r\n<p><strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<h2>Why do we use it?</h2>\r\n\r\n<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using &#39;Content here, content here&#39;, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for &#39;lorem ipsum&#39; will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h2>Where does it come from?</h2>\r\n\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.</p>\r\n\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>', '<h2>Why do we use it?</h2>\r\n\r\n<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using &#39;Content here, content here&#39;, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for &#39;lorem ipsum&#39; will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h2>Where does it come from?</h2>\r\n\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.</p>\r\n\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>\r\n\r\n<h2>Where can I get some?</h2>\r\n\r\n<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don&#39;t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn&#39;t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>', 1, NULL, 'No', NULL, NULL, 'Active', '2021-11-20 09:34:47', '2021-11-20 09:34:47'),
(25, 'November-2021', 1, NULL, 'Prime Bank', 'O.R. Nizam Road', 587463, '<p><span dir=\"ltr\">Cost to company refers to the total amount of expenses an employer spends on an employee, in terms of employment. In the broader term, from the employer&rsquo;s perspective, it is a total of all</span><br />\r\n<span dir=\"ltr\">expenses starting from the cost of recruitment, to retainment bonus, to retirement payments (if any) that the employer bears on his account. However, as the above-mentioned specific payments</span><br />\r\n<span dir=\"ltr\">occur only once concerning an employee, CTC in the normal parlance is considered to measure the yearly expenditure that a company spends on its employee. Therefore, it is a sum of all monetary</span><br />\r\n<span dir=\"ltr\">benefits and non-monetary benefits extended to the employee.</span><br />\r\n<span dir=\"ltr\">That is to say, CTC includes Gross salary (both fixed and variable- inclusive of basic pay, allowances, incentives, bonus, commission etc.), contributions to statutory funds, and other non-monetary</span><br />\r\n<span dir=\"ltr\">benefits in the form of perquisites.</span><br />\r\n<span dir=\"ltr\">Example: Employee&rsquo;s salary is Rs.30,000 per month. The employee is provided pick and drop cab facility and also food at the office for which the employee is not charged. However, the employer</span><br />\r\n<span dir=\"ltr\">spends an amount of Rs.2000 and Rs.1000, respectively, on account of each employee. In this case, the employee&rsquo;s salary remains Rs.30,000, but the CTC is Rs.33,000 per month or Rs.3,96,000 per</span><br />\r\n<span dir=\"ltr\">annum.</span><br />\r\n<span dir=\"ltr\">Calculation of CTC</span><br />\r\n<br />\r\n<span dir=\"ltr\">CTC is the total of emoluments in terms of benefits that are direct in nature paid monthly, and the sum paid every year &amp; indirect (a sum that the employer pays on behalf of the employee). It also</span><br />\r\n<span dir=\"ltr\">includes contributions towards social security benefits and saving schemes of employees.</span><br />\r\n<span dir=\"ltr\">Gross Salary vs CTC</span><br />\r\n<span dir=\"ltr\">Gross salary refers to all money an employee earns in employment with an entity/company. However, this is the amount before any tax and other deductions. And the aggregate compensation, in</span><br />\r\n<span dir=\"ltr\">whole, would be the Cost to Company.</span><br />\r\n<span dir=\"ltr\">Nett Salary vs CTC</span><br />\r\n<span dir=\"ltr\">Gross salary is the amount an employee earns before any tax or other deductions. Nett Salary or Take-home salary or salary in-hand is the amount that the employee receives after such deductions</span><br />\r\n<span dir=\"ltr\">are made. Common deductions being a contribution to provident fund, tax deducted at source and professional tax. Therefore, CTC is the aggregate compensation of which the Net salary is a part.</span><br />\r\n<span dir=\"ltr\">Components of CTC</span><br />\r\n<span dir=\"ltr\">CTC includes monetary as well as non-monetary benefits extended to the employee. These include:</span><br />\r\n<span dir=\"ltr\">Basic Pay</span><br />\r\n<span dir=\"ltr\">Dearness Allowance (DA)</span><br />\r\n<span dir=\"ltr\">Incentives, bonus, commission</span><br />\r\n<span dir=\"ltr\">House Rent Allowance</span><br />\r\n<span dir=\"ltr\">Daily allowance</span><br />\r\n<span dir=\"ltr\">Contributions to Employee Provident Fund.</span></p>\r\n\r\n<p><span dir=\"ltr\">Children Education Allowance</span><br />\r\n<span dir=\"ltr\">Transport allowance</span><br />\r\n<span dir=\"ltr\">Medical allowance</span><br />\r\n<span dir=\"ltr\">City compensatory allowance</span><br />\r\n<span dir=\"ltr\">Leave Travel Allowance or Concession</span><br />\r\n<span dir=\"ltr\">Contributions to Employee State Insurance</span><br />\r\n<span dir=\"ltr\">Medical reimbursements</span><br />\r\n<span dir=\"ltr\">Telephone / Mobile Phone Allowance</span><br />\r\n<span dir=\"ltr\">Special Allowance</span><br />\r\n<span dir=\"ltr\">Contributions to Gratuity fund</span><br />\r\n<span dir=\"ltr\">Rent Free Accommodation</span><br />\r\n<span dir=\"ltr\">Fringe benefits such as interest-free or concessional loan, free or concessional food, use of movable assets etc.</span><br />\r\n<span dir=\"ltr\">Sweat equity shares allotted or transferred</span><br />\r\n<span dir=\"ltr\">Insurance premium met by the employer</span><br />\r\n<span dir=\"ltr\">Amount of any contribution to an approved superannuation fund</span><br />\r\n<span dir=\"ltr\">Concession in rent</span><br />\r\n<span dir=\"ltr\">Any other payment, emolument or benefit not covered above.</span><br />\r\n<span dir=\"ltr\">Current CTC vs Expected CTC</span></p>\r\n\r\n<p><span dir=\"ltr\">These terms generally come into the picture when an employee working in an organization is looking for a change in employment for better prospects. Current CTC is what an employee is currently</span><br />\r\n<span dir=\"ltr\">earning in the organization he/she is associated with. Expected CTC is the amount, including the level of pay that employee is expecting, based on the level of experience gained over the work</span><br />\r\n<span dir=\"ltr\">tenure. The rate of this increase or Expected CTC, depends on various factors including the industry standards, domain knowledge and expertise of the individual.</span><br />\r\n<span dir=\"ltr\">Breakup of CTC</span><br />\r\n<span dir=\"ltr\">CTC includes several components. They can be classified into three broad categories.</span><br />\r\n<span dir=\"ltr\">Direct monetary benefits paid to the employee every month and forming part of their take-home pay such as basic pay, dearness allowance, incentives, bonus, commission, house rent</span><br />\r\n<span dir=\"ltr\">allowance, transport allowance etc.</span><br />\r\n<span dir=\"ltr\">Indirect benefits are generally non-monetary in nature. These are the benefits that the employee enjoys without paying for them. This cost is borne by the employer. Indirect Benefits include</span><br />\r\n<span dir=\"ltr\">rent-free accommodation, fringe benefits such as an interest-free loan or concessional loan, free or concessional food, use of movable assets of the company, etc.</span><br />\r\n<span dir=\"ltr\">Contributions towards social security benefits and saving schemes of employees that employers contribute, such as Employer Contribution towards PF, ESI, etc.</span><br />\r\n<span dir=\"ltr\">Optimal CTC</span><br />\r\n<span dir=\"ltr\">Considering the vast avenues of industry and markets that businesses operate in, there is no one blanket structure which can accommodate all industry needs. However, considering the widespread</span><br />\r\n<span dir=\"ltr\">majority of small and medium enterprises in our country, we have developed a tool to smoothly calculate a CTC structure which employers can easily use and modify to suit specific business</span><br />\r\n<span dir=\"ltr\">requirements. Refer our calculator </span><span dir=\"ltr\">Salary Structure Optimizer </span><span dir=\"ltr\"> at no cost to find a CTC structure that best suits your requirement.</span></p>', '<p><strong>Employee name</strong></p>\r\n\r\n<p>Salary Sheet Name Column Displays the employee&#39;s name as per company records. Ensure that this matches the name as appearing in the employee&#39;s bank account to avoid discrepancies during salary payments.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Working days</strong></p>\r\n\r\n<p>Specify the total number of working days of the organisation for the given payment cycle.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Loss of Pay (LOP)</strong></p>\r\n\r\n<p>Loss of pay is when the employee is not present at work, and the employee salary is not paid for the days of absence</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Salary days</strong></p>\r\n\r\n<p>The total number of working days considered for payment of salary to the employee. Derive this column value by reducing the number of days of Loss of Pay from the organisation&#39;s Working days.</p>\r\n\r\n<p><strong>Employee Earnings</strong></p>\r\n\r\n<p><strong>Basic pay </strong></p>\r\n\r\n<p>Basic pay is the minimum sum of earnings that an employee stands to receive in terms of employment. The term &#39;wages&#39; is also used interchangeably with basic pay, meaning a fixed, regular payment for work or services.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Dearness Allowance</strong></p>\r\n\r\n<p>This allowance is calculated as a percentage of basic salary to mitigate the impact of inflation.</p>\r\n\r\n<p><strong>House Rent Allowance </strong></p>\r\n\r\n<p>HRA is a special allowance specifically granted to an employee by his employer towards rent for the employee&#39;s residence. For further information on conditions attached and level of exemptions, refer our article <a href=\"https://officeanywhere.io/payroll/hra-calculator\" target=\"_blank\">HRA (House Rent Allowance).</a></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Transport allowance</strong></p>\r\n\r\n<p>Transport allowance granted to an employee to meet the expenditure to commute between the place of residence and the place of duty. Refer <a href=\"https://officeanywhere.io/payroll/salary-optimisation-tool#opt\" target=\"_blank\"> Salary Optimizer calculation.</a></p>\r\n\r\n<p><strong>Other allowances</strong></p>\r\n\r\n<p>Any other allowance, other than specified above that forms a part of employee salary.</p>\r\n\r\n<p><strong>Arrears</strong></p>\r\n\r\n<p>Arrears or past dues received on account of salary by the employee.</p>\r\n\r\n<p><strong>Total Earnings</strong></p>\r\n\r\n<p>Sum of all earnings of the employee in the payment cycle before considering contributions and deductions.</p>\r\n\r\n<p><strong>Employee Deductions</strong></p>\r\n\r\n<p><strong>Contribution to Employee Provident Fund</strong></p>\r\n\r\n<p>An Employee Provident Fund is a retirement benefits scheme meant for salaried employees. A portion of the salary every month is contributed to the fund. For further information, refer our article <a href=\"https://officeanywhere.io/payroll/epf-calculator\" target=\"_blank\">EPF (Employee Provident Fund)</a></p>\r\n\r\n<p><strong>Contribution to Employee State Insurance</strong></p>\r\n\r\n<p>Employees&#39; State Insurance is a self financial contributory fund scheme intended to provide Social protection towards workers and their dependencies. For further information, refer our article <a href=\"https://officeanywhere.io/payroll/esi-calculator\" target=\"_blank\">ESI (Employees&#39; State Insurance)</a></p>\r\n\r\n<p>Similarly, contribution to the below (if any):</p>\r\n\r\n<ul>\r\n	<li><strong>National Pension Scheme</strong></li>\r\n	<li><strong>Voluntary Provident Fund</strong></li>\r\n</ul>\r\n\r\n<p><strong>Advance recovery</strong></p>\r\n\r\n<p>Deduction of monthly instalment against salary advances extended by the employer</p>\r\n\r\n<p><strong>TDS</strong></p>\r\n\r\n<p>TDS or Tax Deducted at Source is the amount deducted from an individual&#39;s income by the employer on behalf of the employee and deposited to the Income Tax department.</p>\r\n\r\n<p>For further information, refer our article TDS calculation on salary ( <a href=\"https://officeanywhere.io/payroll/esi-calculator\" target=\"_blank\"> provide </a> a link to the specific subheading in our &#39;Income Tax Calculator&#39; page). However, the link should open in a new tab)</p>\r\n\r\n<p><strong>Professional Tax</strong></p>\r\n\r\n<p>All persons earning by way of employment are subject to pay Professional Tax if charged by their respective state. For further information, refer our article <a href=\"https://officeanywhere.io/payroll/esi-calculator\" target=\"_blank\">Professional Tax </a></p>\r\n\r\n<p><strong>Total Deductions</strong></p>\r\n\r\n<p>Total of all contributions and deductions of the employee in the payment cycle.</p>\r\n\r\n<p><strong>Net Amount</strong></p>\r\n\r\n<p>Total Earnings minus Total Deductions of each Employee is the Net amount or Net Salary for the payment cycle.</p>\r\n\r\n<p><strong>Advantages of Salary Sheet</strong></p>\r\n\r\n<ul>\r\n	<li>It is a detailed list of a set of information of all employees during a payment cycle.</li>\r\n	<li>At the first instance, a salary sheet gives the management a view of the number of employees on the Payroll and details of the number of worker-days contributed.</li>\r\n	<li>It displays the net amount of salary payable (after considering LOPs and deductions), which is the actual outlay in terms of funds, against the workforce&#39;s total salary packages.</li>\r\n	<li>Using salary Sheet, you can ascertain contributions to Statutory payments, both from the employers and employees.</li>\r\n	<li>It gives a summary of recoveries of Advances (if any) from employees. This intern reduces the total liability during the payment cycle.</li>\r\n	<li>The sooner the Salary Sheet is drawn, the better is the management to take care of its working capital requirements.</li>\r\n	<li>Preparing Salary Sheet also ensures the statutory payments to social securities and direct taxes to governments are made in time to avoid late fees and penalties.</li>\r\n</ul>', 1, NULL, 'No', NULL, NULL, 'Active', '2021-11-20 12:09:19', '2021-11-20 12:09:19'),
(26, NULL, 1, NULL, 'Ezekiel Hebert', 'Brittany Macdonald', 125654696, 'Architecto aute proi', 'Adipisicing aut veli', 21, NULL, 'No', NULL, NULL, 'Active', '2021-11-22 07:08:08', '2021-11-22 07:08:08'),
(27, 'December-2021', 1, NULL, 'Prime Bnk', 'Or NIZAM Branch', 1245745120, 'Test Desc', 'TESTETSET', 21, NULL, 'No', NULL, NULL, 'Active', '2021-12-13 15:54:28', '2021-12-13 15:54:28'),
(28, 'July-2022', 7, NULL, 'Prime Bank', 'Gec, Chittagong', 9489444, '<p>Please transfer this amounts to this several accounts as early as possible.</p>', '<p>The Manager</p>\r\n\r\n<p>Prime Bank Limited</p>\r\n\r\n<p>O.R.Nizam Road Branch,Chittagong</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Subject:Request to transfer of amount fromour STD Accounts to Several savings accounts.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Dear sir,</p>\r\n\r\n<p>With reference to the above mentioned subject , we are requesting you to transfer the amounts to our STD account number 46565654 to saving accounts listed below.</p>', 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-14 09:22:14', '2022-07-14 09:22:14'),
(29, 'August-2022', 7, NULL, 'DBBL', 'Gec Branch,chittagong', 4844146516515, NULL, NULL, 1, 1, 'Yes', NULL, NULL, 'Inactive', '2022-07-16 07:25:41', '2022-07-16 11:09:12');

-- --------------------------------------------------------

--
-- Table structure for table `salary_loans`
--

CREATE TABLE `salary_loans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `amount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenure` int(10) DEFAULT NULL,
  `percent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `installment` decimal(10,2) DEFAULT NULL,
  `applicable_from` date DEFAULT NULL,
  `adjustment` decimal(8,2) DEFAULT NULL,
  `cause` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `last_updated_by` bigint(20) DEFAULT NULL,
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_loans`
--

INSERT INTO `salary_loans` (`id`, `user_id`, `amount`, `month_year`, `tenure`, `percent`, `installment`, `applicable_from`, `adjustment`, `cause`, `created_by`, `last_updated_by`, `deleted`, `deleted_by`, `deleted_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, '15000', 'July-2022', 5, '2', '3025.00', '2022-07-16', '500.00', NULL, 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-14 07:37:36', '2022-07-14 07:37:36'),
(2, 3, '10000', 'July-2022', 3, '2', '3351.00', '2022-07-16', '500.00', NULL, 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-14 07:51:34', '2022-07-14 07:51:34');

-- --------------------------------------------------------

--
-- Table structure for table `salary_loan_details`
--

CREATE TABLE `salary_loan_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `loan_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `month_year` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `installment` bigint(30) DEFAULT NULL,
  `adjust_amount` bigint(20) DEFAULT NULL,
  `loan_status` enum('Pending','Reject','Paid') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `last_updated_by` bigint(20) DEFAULT NULL,
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_loan_details`
--

INSERT INTO `salary_loan_details` (`id`, `loan_id`, `user_id`, `month_year`, `installment`, `adjust_amount`, `loan_status`, `created_by`, `last_updated_by`, `deleted`, `deleted_by`, `deleted_date`, `created_at`, `updated_at`) VALUES
(460, 62, 13, 'November-2021', 8459, NULL, 'Paid', 1, NULL, 'Yes', NULL, NULL, '2021-11-18 12:56:20', '2021-11-20 12:04:56'),
(461, 62, 13, 'December-2021', 8459, NULL, 'Paid', 1, NULL, 'Yes', NULL, NULL, '2021-11-18 12:56:20', '2021-12-13 15:08:05'),
(462, 62, 13, 'January-2022', 8459, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-18 12:56:20', '2021-11-18 12:56:20'),
(463, 62, 13, 'February-2022', 8459, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-18 12:56:20', '2021-11-18 12:56:20'),
(464, 62, 13, 'March-2022', 8459, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-18 12:56:20', '2021-11-18 12:56:20'),
(465, 62, 13, 'April-2022', 8459, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-18 12:56:20', '2021-11-18 12:56:20'),
(466, 63, 15, 'November-2021', 8626, NULL, 'Paid', 1, NULL, 'Yes', NULL, NULL, '2021-11-18 12:57:05', '2021-11-20 12:04:56'),
(467, 63, 15, 'December-2021', 8626, NULL, 'Paid', 1, NULL, 'Yes', NULL, NULL, '2021-11-18 12:57:05', '2021-12-13 15:08:05'),
(468, 63, 15, 'January-2022', 8626, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-18 12:57:05', '2021-11-18 12:57:05'),
(469, 63, 15, 'February-2022', 8626, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-18 12:57:05', '2021-11-18 12:57:05'),
(470, 63, 15, 'March-2022', 8626, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-18 12:57:05', '2021-11-18 12:57:05'),
(471, 63, 15, 'April-2022', 8626, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-18 12:57:05', '2021-11-18 12:57:05'),
(472, 63, 15, 'May-2022', 8626, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-18 12:57:05', '2021-11-18 12:57:05'),
(473, 63, 15, 'June-2022', 8626, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-18 12:57:05', '2021-11-18 12:57:05'),
(474, 63, 15, 'July-2022', 8626, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-18 12:57:05', '2021-11-18 12:57:05'),
(475, 63, 15, 'August-2022', 8626, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-18 12:57:05', '2021-11-18 12:57:05'),
(476, 63, 15, 'September-2022', 8626, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-18 12:57:05', '2021-11-18 12:57:05'),
(477, 63, 15, 'October-2022', 8626, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-18 12:57:05', '2021-11-18 12:57:05'),
(478, 64, 27, 'December-2021', 7268, NULL, 'Paid', 1, NULL, 'Yes', NULL, NULL, '2021-11-20 06:18:09', '2021-11-20 06:20:45'),
(479, 64, 27, 'January-2022', 7268, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-20 06:18:09', '2021-11-20 06:18:09'),
(480, 64, 27, 'February-2022', 7268, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-20 06:18:09', '2021-11-20 06:18:09'),
(481, 64, 27, 'March-2022', 7268, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-20 06:18:09', '2021-11-20 06:18:09'),
(482, 64, 27, 'April-2022', 7268, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-20 06:18:10', '2021-11-20 06:18:10'),
(483, 64, 27, 'May-2022', 7268, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-20 06:18:10', '2021-11-20 06:18:10'),
(484, 64, 27, 'June-2022', 7268, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-20 06:18:10', '2021-11-20 06:18:10'),
(485, 65, 28, 'December-2021', 8100, NULL, 'Paid', 1, NULL, 'Yes', NULL, NULL, '2021-11-20 06:18:48', '2021-11-20 06:20:45'),
(486, 65, 28, 'January-2022', 8100, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-20 06:18:48', '2021-11-20 06:18:48'),
(487, 65, 28, 'February-2022', 8100, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-20 06:18:48', '2021-11-20 06:18:48'),
(488, 65, 28, 'March-2022', 8100, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-20 06:18:48', '2021-11-20 06:18:48'),
(489, 65, 28, 'April-2022', 8100, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-20 06:18:48', '2021-11-20 06:18:48'),
(490, 66, 12, 'November-2021', 46751, NULL, 'Paid', 1, NULL, 'Yes', NULL, NULL, '2021-11-20 11:17:09', '2021-11-20 12:04:56'),
(491, 66, 12, 'December-2021', 46751, NULL, 'Paid', 1, NULL, 'Yes', NULL, NULL, '2021-11-20 11:17:09', '2021-12-13 15:08:05'),
(492, 66, 12, 'January-2022', 46751, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-20 11:17:09', '2021-11-20 11:17:09'),
(493, 66, 12, 'February-2022', 46751, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-20 11:17:09', '2021-11-20 11:17:09'),
(494, 66, 12, 'March-2022', 46751, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-20 11:17:09', '2021-11-20 11:17:09'),
(495, 66, 12, 'April-2022', 46751, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-20 11:17:09', '2021-11-20 11:17:09'),
(496, 66, 12, 'May-2022', 46751, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-20 11:17:09', '2021-11-20 11:17:09'),
(497, 66, 12, 'June-2022', 46751, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-20 11:17:09', '2021-11-20 11:17:09'),
(498, 66, 12, 'July-2022', 46751, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-20 11:17:09', '2021-11-20 11:17:09'),
(499, 66, 12, 'August-2022', 46751, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-20 11:17:09', '2021-11-20 11:17:09'),
(500, 66, 12, 'September-2022', 46751, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-20 11:17:09', '2021-11-20 11:17:09'),
(501, 66, 12, 'October-2022', 46751, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2021-11-20 11:17:09', '2021-11-20 11:17:09'),
(502, 1, 4, 'July-2022', 3025, NULL, 'Paid', 1, NULL, 'Yes', NULL, NULL, '2022-07-14 07:37:36', '2022-07-14 08:01:37'),
(503, 1, 4, 'August-2022', 3025, NULL, 'Paid', 1, NULL, 'Yes', NULL, NULL, '2022-07-14 07:37:36', '2022-07-16 06:05:27'),
(504, 1, 4, 'September-2022', 3025, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2022-07-14 07:37:36', '2022-07-14 07:37:36'),
(505, 1, 4, 'October-2022', 3025, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2022-07-14 07:37:36', '2022-07-14 07:37:36'),
(506, 1, 4, 'November-2022', 3025, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2022-07-14 07:37:36', '2022-07-14 07:37:36'),
(507, 2, 3, 'July-2022', 3351, NULL, 'Paid', 1, NULL, 'Yes', NULL, NULL, '2022-07-14 07:51:34', '2022-07-14 08:01:37'),
(508, 2, 3, 'August-2022', 3351, NULL, 'Paid', 1, NULL, 'Yes', NULL, NULL, '2022-07-14 07:51:34', '2022-07-16 06:05:27'),
(509, 2, 3, 'September-2022', 3351, NULL, 'Pending', 1, NULL, 'No', NULL, NULL, '2022-07-14 07:51:34', '2022-07-14 07:51:34');

-- --------------------------------------------------------

--
-- Table structure for table `salary_sheets`
--

CREATE TABLE `salary_sheets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sheet_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `last_updated_by` bigint(20) DEFAULT NULL,
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_sheets`
--

INSERT INTO `salary_sheets` (`id`, `sheet_name`, `created_by`, `last_updated_by`, `deleted`, `deleted_by`, `deleted_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Sheet A', 1, 21, 'No', NULL, NULL, 'Active', NULL, '2021-11-21 15:24:04'),
(3, 'Sheet B', 1, 1, 'No', NULL, NULL, 'Active', '2021-11-01 09:32:24', '2021-11-01 10:21:52'),
(5, 'Neve holiukyufjrtdyherstfgerfewdeleted5', 21, 21, 'Yes', 21, '2021-11-22 13:06:24', 'Inactive', '2021-11-22 07:06:09', '2021-11-22 07:06:24'),
(6, 'Salary 5/7/2022', 4, NULL, 'No', NULL, NULL, 'Active', '2022-06-16 17:51:09', '2022-06-16 17:51:09'),
(7, 'Alitech Salary Sheet', 1, 1, 'No', NULL, NULL, 'Active', '2022-07-05 13:38:38', '2022-07-14 07:00:52');

-- --------------------------------------------------------

--
-- Table structure for table `saved_salary_sheets`
--

CREATE TABLE `saved_salary_sheets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `month_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sheet_id` bigint(20) DEFAULT NULL,
  `company_payable_net_salary` decimal(8,2) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `last_updated_by` bigint(20) DEFAULT NULL,
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `saved_salary_sheets`
--

INSERT INTO `saved_salary_sheets` (`id`, `month_year`, `sheet_id`, `company_payable_net_salary`, `created_by`, `last_updated_by`, `deleted`, `deleted_by`, `deleted_date`, `status`, `created_at`, `updated_at`) VALUES
(3, 'July-2022', 7, NULL, 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-14 08:02:13', '2022-07-14 08:02:13'),
(4, 'August-2022', 7, NULL, 1, NULL, 'No', NULL, NULL, 'Active', '2022-07-16 06:05:27', '2022-07-16 06:05:27');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Ry48xZ9FEygiWahbwQrr2bYkgp8pqQDVt2PIcNoC', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:102.0) Gecko/20100101 Firefox/102.0', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiSEN6M2JwajNQeXV3Tk5oNnN3Q2dFVVBoMmc2NnhvVDlXVExKeVZCUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wYXlyb2xsL2FsbEZhY2lsaXR5Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEwJGREclllZENVaWNJaS9YYjNKTUVoR2VFaFBpeE5nbTZEZi5rMzI1a2xkQ01vNndiMUNZeVVPIjtzOjIxOiJwYXNzd29yZF9oYXNoX3NhbmN0dW0iO3M6NjA6IiQyeSQxMCRkRHJZZWRDVWljSWkvWGIzSk1FaEdlRWhQaXhOZ202RGYuazMyNWtsZENNbzZ3YjFDWXlVTyI7czoxNToiY29tcGFueVNldHRpbmdzIjthOjE6e2k6MDtPOjI1OiJBcHBcTW9kZWxzXENvbXBhbnlTZXR0aW5nIjoyNzp7czo4OiIAKgB0YWJsZSI7czoxNjoiY29tcGFueV9zZXR0aW5ncyI7czoxMzoiACoAY29ubmVjdGlvbiI7czo1OiJteXNxbCI7czoxMzoiACoAcHJpbWFyeUtleSI7czoyOiJpZCI7czoxMDoiACoAa2V5VHlwZSI7czozOiJpbnQiO3M6MTI6ImluY3JlbWVudGluZyI7YjoxO3M6NzoiACoAd2l0aCI7YTowOnt9czoxMjoiACoAd2l0aENvdW50IjthOjA6e31zOjEwOiIAKgBwZXJQYWdlIjtpOjE1O3M6NjoiZXhpc3RzIjtiOjE7czoxODoid2FzUmVjZW50bHlDcmVhdGVkIjtiOjA7czoxMzoiACoAYXR0cmlidXRlcyI7YToyMzp7czoyOiJpZCI7aToxO3M6NDoibG9nbyI7czozOToiMTY1Nzc4NDIwMkVtcGlyYV9Db21wcmVzc2VkLTM1MHgzNTAuanBnIjtzOjQ6Im5hbWUiO3M6MTU6IkV4YW1wbGUgQ29tcGFueSI7czo1OiJlbWFpbCI7czoxNzoiZXhhbXBsZUBnbWFpbC5jb20iO3M6NToicGhvbmUiO3M6MTA6IjAxODE4MDgwODAiO3M6ODoiY3VycmVuY3kiO3M6NDoiVGFrYSI7czo3OiJhZGRyZXNzIjtzOjE1OiJHRUMgQ2lyY2xlLCBDVEciO3M6Nzoid2Vic2l0ZSI7czoxNToid3d3LmV4YW1wbGUuY29tIjtzOjEwOiJtb250aF95ZWFyIjtzOjM6IkYtWSI7czoxMzoicmVwb3J0X2hlYWRlciI7czoyMToiY29tcGFueV9yZXBvcnRfaGVhZGVyIjtzOjEzOiJyZXBvcnRfZm9vdGVyIjtzOjIxOiJjb21wYW55X3JlcG9ydF9mb290ZXIiO3M6OToid2F0ZXJtYXJrIjtOO3M6MjA6Im1hbmFnZV9zdG9ja190b19zYWxlIjtzOjI6Ik5vIjtzOjE0OiJiYXJjb2RlX2V4aXN0cyI7czoyOiJObyI7czo3OiJkZWxldGVkIjtOO3M6MTA6ImNyZWF0ZWRfYnkiO047czoxMjoiY3JlYXRlZF9kYXRlIjtOO3M6MTA6InVwZGF0ZWRfYnkiO2k6MTtzOjEyOiJ1cGRhdGVkX2RhdGUiO3M6MTk6IjIwMjItMDctMTQgMTM6MzY6NDIiO3M6MTA6ImRlbGV0ZWRfYnkiO047czoxMjoiZGVsZXRlZF9kYXRlIjtOO3M6MTA6ImNyZWF0ZWRfYXQiO3M6MTk6IjIwMjItMDctMDUgMTU6NTI6MjYiO3M6MTA6InVwZGF0ZWRfYXQiO3M6MTk6IjIwMjItMDctMTQgMTM6MzY6NDIiO31zOjExOiIAKgBvcmlnaW5hbCI7YToyMzp7czoyOiJpZCI7aToxO3M6NDoibG9nbyI7czozOToiMTY1Nzc4NDIwMkVtcGlyYV9Db21wcmVzc2VkLTM1MHgzNTAuanBnIjtzOjQ6Im5hbWUiO3M6MTU6IkV4YW1wbGUgQ29tcGFueSI7czo1OiJlbWFpbCI7czoxNzoiZXhhbXBsZUBnbWFpbC5jb20iO3M6NToicGhvbmUiO3M6MTA6IjAxODE4MDgwODAiO3M6ODoiY3VycmVuY3kiO3M6NDoiVGFrYSI7czo3OiJhZGRyZXNzIjtzOjE1OiJHRUMgQ2lyY2xlLCBDVEciO3M6Nzoid2Vic2l0ZSI7czoxNToid3d3LmV4YW1wbGUuY29tIjtzOjEwOiJtb250aF95ZWFyIjtzOjM6IkYtWSI7czoxMzoicmVwb3J0X2hlYWRlciI7czoyMToiY29tcGFueV9yZXBvcnRfaGVhZGVyIjtzOjEzOiJyZXBvcnRfZm9vdGVyIjtzOjIxOiJjb21wYW55X3JlcG9ydF9mb290ZXIiO3M6OToid2F0ZXJtYXJrIjtOO3M6MjA6Im1hbmFnZV9zdG9ja190b19zYWxlIjtzOjI6Ik5vIjtzOjE0OiJiYXJjb2RlX2V4aXN0cyI7czoyOiJObyI7czo3OiJkZWxldGVkIjtOO3M6MTA6ImNyZWF0ZWRfYnkiO047czoxMjoiY3JlYXRlZF9kYXRlIjtOO3M6MTA6InVwZGF0ZWRfYnkiO2k6MTtzOjEyOiJ1cGRhdGVkX2RhdGUiO3M6MTk6IjIwMjItMDctMTQgMTM6MzY6NDIiO3M6MTA6ImRlbGV0ZWRfYnkiO047czoxMjoiZGVsZXRlZF9kYXRlIjtOO3M6MTA6ImNyZWF0ZWRfYXQiO3M6MTk6IjIwMjItMDctMDUgMTU6NTI6MjYiO3M6MTA6InVwZGF0ZWRfYXQiO3M6MTk6IjIwMjItMDctMTQgMTM6MzY6NDIiO31zOjEwOiIAKgBjaGFuZ2VzIjthOjA6e31zOjg6IgAqAGNhc3RzIjthOjA6e31zOjE3OiIAKgBjbGFzc0Nhc3RDYWNoZSI7YTowOnt9czo4OiIAKgBkYXRlcyI7YTowOnt9czoxMzoiACoAZGF0ZUZvcm1hdCI7TjtzOjEwOiIAKgBhcHBlbmRzIjthOjA6e31zOjE5OiIAKgBkaXNwYXRjaGVzRXZlbnRzIjthOjA6e31zOjE0OiIAKgBvYnNlcnZhYmxlcyI7YTowOnt9czoxMjoiACoAcmVsYXRpb25zIjthOjA6e31zOjEwOiIAKgB0b3VjaGVzIjthOjA6e31zOjEwOiJ0aW1lc3RhbXBzIjtiOjE7czo5OiIAKgBoaWRkZW4iO2E6MDp7fXM6MTA6IgAqAHZpc2libGUiO2E6MDp7fXM6MTE6IgAqAGZpbGxhYmxlIjthOjA6e31zOjEwOiIAKgBndWFyZGVkIjthOjE6e2k6MDtzOjE6IioiO319fX0=', 1658215602);

-- --------------------------------------------------------

--
-- Table structure for table `steps`
--

CREATE TABLE `steps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `step_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sequence` bigint(20) NOT NULL,
  `salary_amount` double(10,2) DEFAULT NULL,
  `grade_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `last_updated_by` bigint(20) DEFAULT NULL,
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `steps`
--

INSERT INTO `steps` (`id`, `step_name`, `sequence`, `salary_amount`, `grade_id`, `note`, `priority`, `created_by`, `last_updated_by`, `deleted`, `deleted_by`, `deleted_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Alitech Step 1', 84178, 20000.00, '37', NULL, NULL, 1, 1, 'No', NULL, NULL, 'Active', '2022-07-05 12:50:15', '2022-07-05 13:06:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usertype_id` int(11) DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `designation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `deleted` enum('Yes','No') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
  `created_by` bigint(20) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_date` datetime DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `usertype_id`, `image`, `designation`, `department`, `mobile_no`, `address`, `status`, `deleted`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted_by`, `deleted_date`, `remember_token`, `current_team_id`, `profile_photo_path`, `signature`, `created_at`, `updated_at`, `role`) VALUES
(1, 'Super Admin', 'super.admin@gmail.com', NULL, '$2y$10$dDrYedCUicIi/Xb3JMEhGeEhPixNgm6Df.k325kldCMo6wb1CYyUO', NULL, '16577198371507480_1375439846070084_978421211_o.jpg', NULL, NULL, '01887922063', NULL, 'Active', 'No', NULL, NULL, 1, '2022-07-13 19:44:12', NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-05 09:52:26', '2022-07-13 13:44:12', 'Super Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company_settings`
--
ALTER TABLE `company_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `final_salary_sheets`
--
ALTER TABLE `final_salary_sheets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `monthly_amounts`
--
ALTER TABLE `monthly_amounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `our_teams`
--
ALTER TABLE `our_teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `salary_instructions`
--
ALTER TABLE `salary_instructions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salary_loans`
--
ALTER TABLE `salary_loans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salary_loan_details`
--
ALTER TABLE `salary_loan_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salary_sheets`
--
ALTER TABLE `salary_sheets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saved_salary_sheets`
--
ALTER TABLE `saved_salary_sheets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `steps`
--
ALTER TABLE `steps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company_settings`
--
ALTER TABLE `company_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `final_salary_sheets`
--
ALTER TABLE `final_salary_sheets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `monthly_amounts`
--
ALTER TABLE `monthly_amounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `our_teams`
--
ALTER TABLE `our_teams`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `salary_instructions`
--
ALTER TABLE `salary_instructions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `salary_loans`
--
ALTER TABLE `salary_loans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `salary_loan_details`
--
ALTER TABLE `salary_loan_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=510;

--
-- AUTO_INCREMENT for table `salary_sheets`
--
ALTER TABLE `salary_sheets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `saved_salary_sheets`
--
ALTER TABLE `saved_salary_sheets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `steps`
--
ALTER TABLE `steps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
