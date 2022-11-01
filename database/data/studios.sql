-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 01, 2022 at 01:38 PM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sju`
--

-- --------------------------------------------------------

--
-- Table structure for table `studios`
--

DROP TABLE IF EXISTS `studios`;
CREATE TABLE IF NOT EXISTS `studios` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `file` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `studios`
--

INSERT INTO `studios` (`id`, `file`, `link`, `type`, `created_at`, `updated_at`) VALUES
(2, '1222.jpeg', NULL, 'photo', NULL, NULL),
(6, NULL, 'https://www.youtube.com/embed/i6_wQhT2sIE', 'video', NULL, NULL),
(7, 'ksa2022.jpeg', NULL, 'photo', NULL, NULL),
(8, NULL, 'https://www.youtube.com/embed/tJbNOBEUiM4', 'video', NULL, NULL),
(9, 'WhatsApp_Image_2022-04-02_at_12_40_49_PM.jpeg', NULL, 'photo', NULL, NULL),
(10, 'WhatsApp_Image_2022-04-02_at_12_40_22_PM.jpeg', NULL, 'photo', NULL, NULL),
(14, 'AAEE5E89-F395-4A47-BD6F-D83296F04A50.jpeg', NULL, 'photo', NULL, NULL),
(20, NULL, 'https://youtube.com/embed/vqB834pQ2aM', 'video', NULL, NULL),
(19, NULL, 'https://youtube.com/embed/OfelrYb8H6c', 'video', NULL, NULL),
(16, NULL, 'https://www.youtube.com/embed/4pK-npQiW4I', 'video', NULL, NULL),
(25, NULL, 'https://drive.google.com/file/d/1_ohAZFnCG91U_9fc2tAc1BsxQ9PPZy5M/view?usp=drivesdk', 'video', NULL, NULL),
(24, 'WhatsApp_Image_2022-09-22_at_2_47_50_PM.jpeg', NULL, 'photo', NULL, NULL),
(23, '2EC2DF17-9FD2-4F63-8647-A17E74594431.jpeg', NULL, 'photo', NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
