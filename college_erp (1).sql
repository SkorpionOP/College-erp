-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 19, 2024 at 05:58 PM
-- Server version: 8.3.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `college_erp`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
CREATE TABLE IF NOT EXISTS `attendance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `subject` varchar(255) NOT NULL,
  `total_classes` int NOT NULL,
  `attended_classes` int NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `subject`, `total_classes`, `attended_classes`, `date`) VALUES
(19, 1, 'Mathematics', 30, 25, '2024-12-01'),
(20, 1, 'Physics', 25, 18, '2024-12-01'),
(21, 1, 'Chemistry', 20, 15, '2024-12-01'),
(22, 2, 'Mathematics', 28, 20, '2024-12-01'),
(23, 2, 'Physics', 25, 15, '2024-12-01'),
(24, 2, 'Chemistry', 22, 19, '2024-12-01'),
(25, 3, 'Mathematics', 32, 29, '2024-12-01'),
(26, 3, 'Physics', 30, 28, '2024-12-01'),
(27, 3, 'Chemistry', 25, 23, '2024-12-01');

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

DROP TABLE IF EXISTS `instructors`;
CREATE TABLE IF NOT EXISTS `instructors` (
  `instructor_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `phone_no` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`instructor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`instructor_id`, `name`, `phone_no`, `email`) VALUES
(1, 'Shobha Rani', '9876543210', 'shobha.rani@example.com'),
(2, 'Rama Santhosh', '8765432109', 'rama.santhosh@example.com'),
(3, 'Omkar', '7654321098', 'omkar@example.com'),
(4, 'Vamsi', '6543210987', 'vamsi@example.com'),
(5, 'Krushna', '5432109876', 'krushna@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

DROP TABLE IF EXISTS `marks`;
CREATE TABLE IF NOT EXISTS `marks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `subject` varchar(255) NOT NULL,
  `marks` decimal(5,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE IF NOT EXISTS `subjects` (
  `subject_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `instructor_id` int NOT NULL,
  `document_links` text,
  PRIMARY KEY (`subject_id`),
  KEY `instructor_id` (`instructor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `name`, `instructor_id`, `document_links`) VALUES
(1, 'MicroProcessors and Interfaces', 1, 'http://example.com/documents/microprocessors.pdf'),
(2, 'Web Technologies', 2, 'http://example.com/documents/web_technologies.pdf'),
(3, 'Machine Learning', 2, 'http://example.com/documents/machine_learning.pdf'),
(4, 'Statistical and Predictive Analysis', 3, 'http://example.com/documents/statistics.pdf'),
(5, 'Software Engineering', 4, 'http://example.com/documents/software_engineering.pdf'),
(6, 'Effective Technical Communication', 5, 'http://example.com/documents/communication.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `email`, `phone`, `gender`, `created_at`, `updated_at`) VALUES
(1, 'TheHonouredOne', '$2y$10$nlFb.0.UpnZKy0PI9/FOZO20nXruvUMcF0G7YxWeUgSMlkB/YXnV2', 'pio', 'skorpion.op@gmail.com', '9490468679', 'Female', '2024-12-16 12:15:02', '2024-12-16 12:15:02'),
(2, 'john_doe', '$2y$10$eDz/8JtQL.wz.oXM3NlmYOCR2fhR3YZOLwNei2bd6/RvPbZhPqZIO', 'John Doe', 'john.doe@example.com', '1234567890', 'Male', '2024-12-16 12:19:26', '2024-12-16 12:19:26'),
(3, 'jane_smith', '$2y$10$F9L82TgMm/F4zH/beg8uwuWqfNdiRnp8ql8XnquzZft2NY4HIz6te', 'Jane Smith', 'jane.smith@example.com', '0987654321', 'Female', '2024-12-16 12:19:26', '2024-12-16 12:19:26'),
(4, 'mark_taylor', '$2y$10$wq9U/zRsxxe/8kYbMaX6O.WJFRJm0wdV.ZwvGFq3w.cZXLRPxvFLG', 'Mark Taylor', 'mark.taylor@example.com', '1122334455', 'Male', '2024-12-16 12:19:26', '2024-12-16 12:19:26'),
(5, 'emma_jones', '$2y$10$wEJ6bkNhOCH6jZ9RqNAXWONVtbCEEdz8FidgcIqDdCcgMJIDKM23O', 'Emma Jones', 'emma.jones@example.com', '5566778899', 'Female', '2024-12-16 12:19:26', '2024-12-16 12:19:26'),
(6, 'alex_brown', '$2y$10$EiXTW.Y2LXtLD4NgEB06hOw/Gb/fkiIv2VR.k5XsX9Kt6G91rElCG', 'Alex Brown', 'alex.brown@example.com', '6677889900', 'Other', '2024-12-16 12:19:26', '2024-12-16 12:19:26');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `marks`
--
ALTER TABLE `marks`
  ADD CONSTRAINT `marks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`instructor_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
