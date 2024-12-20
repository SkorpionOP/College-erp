-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 20, 2024 at 07:29 PM
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
-- Database: `college_erp1`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `action` varchar(255) NOT NULL,
  `timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
CREATE TABLE IF NOT EXISTS `attendance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `subject_id` int NOT NULL,
  `total_classes` int NOT NULL,
  `attended_classes` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `subject_id` int NOT NULL,
  `marks_obtained` decimal(5,2) NOT NULL,
  `total_marks` decimal(5,2) NOT NULL,
  `exam_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Table structure for table `timetable`
--

DROP TABLE IF EXISTS `timetable`;
CREATE TABLE IF NOT EXISTS `timetable` (
  `id` int NOT NULL AUTO_INCREMENT,
  `day_of_week` varchar(10) NOT NULL,
  `period_1_subject` varchar(255) NOT NULL,
  `period_1_start` time NOT NULL DEFAULT '09:00:00',
  `period_1_end` time NOT NULL DEFAULT '10:00:00',
  `period_2_subject` varchar(255) NOT NULL,
  `period_2_start` time NOT NULL DEFAULT '10:00:00',
  `period_2_end` time NOT NULL DEFAULT '11:00:00',
  `period_3_subject` varchar(255) NOT NULL,
  `period_3_start` time NOT NULL DEFAULT '11:00:00',
  `period_3_end` time NOT NULL DEFAULT '12:00:00',
  `lunch_start` time NOT NULL DEFAULT '12:00:00',
  `lunch_end` time NOT NULL DEFAULT '12:45:00',
  `period_5_subject` varchar(255) NOT NULL,
  `period_5_start` time NOT NULL DEFAULT '12:45:00',
  `period_5_end` time NOT NULL DEFAULT '01:45:00',
  `period_6_subject` varchar(255) NOT NULL,
  `period_6_start` time NOT NULL DEFAULT '01:45:00',
  `period_6_end` time NOT NULL DEFAULT '02:45:00',
  `period_7_subject` varchar(255) NOT NULL,
  `period_7_start` time NOT NULL DEFAULT '02:45:00',
  `period_7_end` time NOT NULL DEFAULT '03:45:00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`id`, `day_of_week`, `period_1_subject`, `period_1_start`, `period_1_end`, `period_2_subject`, `period_2_start`, `period_2_end`, `period_3_subject`, `period_3_start`, `period_3_end`, `lunch_start`, `lunch_end`, `period_5_subject`, `period_5_start`, `period_5_end`, `period_6_subject`, `period_6_start`, `period_6_end`, `period_7_subject`, `period_7_start`, `period_7_end`, `created_at`) VALUES
(1, 'Monday', 'DAT', '09:00:00', '10:00:00', 'COUN.HR', '10:00:00', '11:00:00', 'SPA', '11:00:00', '12:00:00', '12:00:00', '12:45:00', 'MPI', '12:45:00', '01:45:00', 'LIBRARY', '01:45:00', '02:45:00', 'LIBRARY', '02:45:00', '03:45:00', '2024-12-20 18:36:43'),
(2, 'Tuesday', 'MPI', '09:00:00', '10:00:00', 'ETC', '10:00:00', '11:00:00', 'SE', '11:00:00', '12:00:00', '12:00:00', '12:45:00', 'WT', '12:45:00', '01:45:00', 'MINI PROJECT', '01:45:00', '02:45:00', 'MINI PROJECT', '02:45:00', '03:45:00', '2024-12-20 18:36:43'),
(3, 'Wednesday', 'WT', '09:00:00', '10:00:00', 'SE', '10:00:00', '11:00:00', 'MPI', '11:00:00', '12:00:00', '12:00:00', '12:45:00', 'SPA', '12:45:00', '01:45:00', 'DAT', '01:45:00', '02:45:00', 'COUN.HR', '02:45:00', '03:45:00', '2024-12-20 18:36:43'),
(4, 'Thursday', 'DAT', '09:00:00', '10:00:00', 'COUN.HR', '10:00:00', '11:00:00', 'SPA', '11:00:00', '12:00:00', '12:00:00', '12:45:00', 'WT LAB', '12:45:00', '01:45:00', 'WT LAB', '01:45:00', '02:45:00', 'WT LAB', '02:45:00', '03:45:00', '2024-12-20 18:36:43'),
(5, 'Friday', 'ETC', '09:00:00', '10:00:00', 'ETC', '10:00:00', '11:00:00', 'WT', '11:00:00', '12:00:00', '12:00:00', '12:45:00', 'SE', '12:45:00', '01:45:00', 'SPORTS', '01:45:00', '02:45:00', 'SPORTS', '02:45:00', '03:45:00', '2024-12-20 18:36:43'),
(6, 'Saturday', 'ADD-ON PROGRAM', '09:00:00', '10:00:00', 'ADD-ON PROGRAM', '10:00:00', '11:00:00', 'ADD-ON PROGRAM', '11:00:00', '12:00:00', '12:00:00', '12:45:00', 'ADD-ON PROGRAM', '12:45:00', '01:45:00', 'ADD-ON PROGRAM', '01:45:00', '02:45:00', 'ADD-ON PROGRAM', '02:45:00', '03:45:00', '2024-12-20 18:36:43');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `email`, `phone`, `gender`, `created_at`, `updated_at`) VALUES
(7, 'TheHonouredOne', '$2y$10$f5JFCCWTvh31iaKRC8032u/X0fYScpmaGdcSxk/mor/Tz71bvSr4y', 'Skorpion', 'kirankumar82054@gmail.com', '9490468679', 'Male', '2024-12-20 19:20:13', '2024-12-20 19:20:13');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `fk_activity_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`);

--
-- Constraints for table `marks`
--
ALTER TABLE `marks`
  ADD CONSTRAINT `marks_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`);

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`instructor_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
