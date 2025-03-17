-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 17, 2025 at 06:06 PM
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
  `subject_id` int NOT NULL,
  `total_classes` int NOT NULL,
  `attended_classes` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `subject_id`, `total_classes`, `attended_classes`) VALUES
(27, 10, 3, 4, 4),
(28, 10, 2, 2, 2),
(29, 10, 1, 4, 2),
(30, 11, 1, 4, 3),
(31, 7, 1, 3, 2),
(32, 8, 1, 4, 3),
(33, 8, 3, 2, 1),
(34, 11, 3, 2, 1),
(35, 20, 3, 4, 2),
(36, 30, 2, 5, 2),
(37, 31, 2, 5, 4),
(38, 28, 1, 1, 1),
(39, 30, 1, 2, 2),
(40, 32, 1, 1, 1),
(41, 31, 1, 2, 1),
(42, 28, 2, 4, 1),
(43, 32, 2, 5, 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'pio', 'kirankumar82054@gmail.com', 'Sometimes when I look at the moon and I like to imagine you do too—\r\nA bridge in the sky and there&#039;s an invisible line connecting U and I, across miles. After sunset, can we meet on the moon?', '2024-12-20 19:11:35'),
(2, 'pio', 'kirankumar82054@gmail.com', 'Sometimes when I look at the moon and I like to imagine you do too—\r\nA bridge in the sky and there&#039;s an invisible line connecting U and I, across miles. After sunset, can we meet on the moon?', '2024-12-20 19:11:52'),
(3, 'Sai varun', 'sai143@gmai.com', 'Pora', '2024-12-21 08:29:54');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

DROP TABLE IF EXISTS `leave_requests`;
CREATE TABLE IF NOT EXISTS `leave_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `request_text` text NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `student_id`, `request_text`, `status`, `created_at`, `updated_at`) VALUES
(1, 7, 'Please grant me leave for 5 days', 'accepted', '2024-12-21 18:54:15', '2024-12-22 13:49:55'),
(2, 10, 'please grant me leave for 5 days', 'accepted', '2024-12-22 12:02:45', '2024-12-22 12:03:05'),
(3, 30, 'Hello sir i wanna watch mufasa please', 'accepted', '2024-12-24 16:01:42', '2025-03-17 16:52:11'),
(4, 32, 'hELLOOO', 'rejected', '2025-03-17 16:52:39', '2025-03-17 16:52:53');

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
  `exam_type` enum('Quiz','Midterm','Final','Assignment','Other') NOT NULL DEFAULT 'Other',
  `exam_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `marks`
--

INSERT INTO `marks` (`id`, `user_id`, `subject_id`, `marks_obtained`, `total_marks`, `exam_type`, `exam_date`) VALUES
(7, 2, 3, 1.00, 1.00, 'Quiz', '2024-12-11'),
(8, 3, 3, 1.00, 1.00, 'Quiz', '2024-12-11'),
(9, 4, 3, 1.00, 1.00, 'Midterm', '2024-12-11'),
(10, 5, 3, 1.00, 1.00, 'Midterm', '2024-12-11'),
(11, 6, 3, 1.00, 1.00, 'Final', '2024-12-11'),
(12, 7, 3, 1.00, 1.00, 'Final', '2024-12-11'),
(13, 8, 3, 1.00, 1.00, 'Assignment', '2024-12-11'),
(14, 2, 1, 1.00, 10.00, 'Quiz', '2024-12-05'),
(15, 3, 1, 1.00, 10.00, 'Quiz', '2024-12-05'),
(16, 4, 1, 1.00, 10.00, 'Midterm', '2024-12-05'),
(17, 6, 1, 1.00, 10.00, 'Midterm', '2024-12-05'),
(18, 7, 1, 1.00, 10.00, 'Final', '2024-12-05'),
(19, 8, 1, 1.00, 10.00, 'Final', '2024-12-05'),
(20, 10, 1, 1.00, 10.00, 'Assignment', '2024-12-05'),
(21, 11, 1, 2.00, 10.00, 'Assignment', '2024-12-05'),
(22, 2, 3, 1.00, 11.00, 'Quiz', '2024-12-02'),
(23, 4, 3, 1.00, 11.00, 'Quiz', '2024-12-02'),
(24, 6, 3, 1.00, 11.00, 'Midterm', '2024-12-02'),
(25, 7, 3, 5.00, 11.00, 'Midterm', '2024-12-02'),
(26, 8, 3, 7.00, 11.00, 'Final', '2024-12-02'),
(27, 10, 3, 8.00, 11.00, 'Final', '2024-12-02'),
(28, 11, 3, 11.00, 11.00, 'Assignment', '2024-12-02'),
(29, 20, 3, 5.00, 11.00, 'Assignment', '2024-12-02'),
(30, 28, 2, 15.00, 30.00, 'Midterm', '2024-12-11'),
(31, 30, 2, 23.00, 30.00, 'Final', '2024-12-11'),
(32, 31, 2, 20.00, 30.00, 'Final', '2024-12-11'),
(33, 28, 2, 10.00, 100.00, 'Assignment', '2025-03-13'),
(34, 30, 2, 20.00, 100.00, 'Assignment', '2025-03-13'),
(35, 31, 2, 30.00, 100.00, 'Assignment', '2025-03-13'),
(36, 32, 2, 40.00, 100.00, 'Assignment', '2025-03-13');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE IF NOT EXISTS `subjects` (
  `subject_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `document_links` text,
  `total_classes` int DEFAULT NULL,
  `instructor_id` int DEFAULT NULL,
  PRIMARY KEY (`subject_id`),
  KEY `fk_instructor_id` (`instructor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `name`, `document_links`, `total_classes`, `instructor_id`) VALUES
(1, 'MicroProcessors and Interfaces', 'https://drive.google.com/drive/folders/1_2A7PLUZpA-PgDei3qMi8ly4NAnlO75Y?usp=drive_link', 2, 25),
(2, 'Web Technologies', 'https://drive.google.com/drive/folders/1ZXjFq_1rOdjf8yeHrq71CpgP5OAHGi0T?usp=drive_link', 5, 23),
(3, 'Machine Learning', 'https://drive.google.com/drive/folders/1wxnXbvnxiifenLjpqMFZnWaDn7rA2aDB?usp=drive_link', 0, 27),
(4, 'Statistical and Predictive Analysis', 'https://drive.google.com/drive/folders/1_vjZYNPkAJVR5zwfq_B7Srk2wUbtAJJi?usp=drive_link', 0, 26),
(5, 'Software Engineering', 'https://drive.google.com/drive/folders/1aXIE384cpNgCHmKbZZV5keFyg31QsdJl?usp=drive_link', 0, 24),
(6, 'Effective Technical Communication', 'https://drive.google.com/drive/folders/15CBribzks1OJPEXbluyDor-1PZG8gnTz?usp=drive_link', 0, 22);

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`id`, `day_of_week`, `period_1_subject`, `period_1_start`, `period_1_end`, `period_2_subject`, `period_2_start`, `period_2_end`, `period_3_subject`, `period_3_start`, `period_3_end`, `lunch_start`, `lunch_end`, `period_5_subject`, `period_5_start`, `period_5_end`, `period_6_subject`, `period_6_start`, `period_6_end`, `period_7_subject`, `period_7_start`, `period_7_end`, `created_at`) VALUES
(1, 'Monday', 'DAT', '09:00:00', '10:00:00', 'COUN.HR', '10:00:00', '11:00:00', 'SPA', '11:00:00', '12:00:00', '12:00:00', '12:45:00', 'MPI', '12:45:00', '01:45:00', 'LIBRARY', '01:45:00', '02:45:00', 'LIBRARY', '02:45:00', '03:45:00', '2024-12-20 18:36:43'),
(2, 'Tuesday', 'MPI', '09:00:00', '10:00:00', 'ETC', '10:00:00', '11:00:00', 'SE', '11:00:00', '12:00:00', '12:00:00', '12:45:00', 'WT', '12:45:00', '01:45:00', 'MINI PROJECT', '01:45:00', '02:45:00', 'MINI PROJECT', '02:45:00', '03:45:00', '2024-12-20 18:36:43'),
(3, 'Wednesday', 'WT', '09:00:00', '10:00:00', 'SE', '10:00:00', '11:00:00', 'MPI', '11:00:00', '12:00:00', '12:00:00', '12:45:00', 'SPA', '12:45:00', '01:45:00', 'DAT', '01:45:00', '02:45:00', 'COUN.HR', '02:45:00', '03:45:00', '2024-12-20 18:36:43'),
(4, 'Thursday', 'DAT', '09:00:00', '10:00:00', 'COUN.HR', '10:00:00', '11:00:00', 'SPA', '11:00:00', '12:00:00', '12:00:00', '12:45:00', 'WT LAB', '12:45:00', '01:45:00', 'WT LAB', '01:45:00', '02:45:00', 'WT LAB', '02:45:00', '03:45:00', '2024-12-20 18:36:43'),
(5, 'Friday', 'ETC', '09:00:00', '10:00:00', 'ETC', '10:00:00', '11:00:00', 'WT', '11:00:00', '12:00:00', '12:00:00', '12:45:00', 'SE', '12:45:00', '01:45:00', 'SPORTS', '01:45:00', '02:45:00', 'SPORTS', '02:45:00', '03:45:00', '2024-12-20 18:36:43'),
(6, 'Saturday', 'ADD-ON PROGRAM', '09:00:00', '10:00:00', 'ADD-ON PROGRAM', '10:00:00', '11:00:00', 'ADD-ON PROGRAM', '11:00:00', '12:00:00', '12:00:00', '12:45:00', 'ADD-ON PROGRAM', '12:45:00', '01:45:00', 'ADD-ON PROGRAM', '01:45:00', '02:45:00', 'ADD-ON PROGRAM', '02:45:00', '03:45:00', '2024-12-20 18:36:43'),
(7, 'Sunday', 'HOLIDAY', '09:00:00', '10:00:00', 'HOLIDAY', '10:00:00', '11:00:00', 'HOLIDAY', '11:00:00', '12:00:00', '12:00:00', '12:45:00', 'HOLIDAY', '12:45:00', '01:45:00', 'HOLIDAY', '01:45:00', '02:45:00', 'HOLIDAY', '02:45:00', '03:45:00', '2024-12-20 18:36:43');

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
  `role` enum('Student','Teacher','Admin') NOT NULL DEFAULT 'Student',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `counsellor_id` int DEFAULT NULL,
  `subject_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `email`, `phone`, `gender`, `role`, `created_at`, `updated_at`, `counsellor_id`, `subject_id`) VALUES
(1, 'TheHonouredOne', '$2y$10$nlFb.0.UpnZKy0PI9/FOZO20nXruvUMcF0G7YxWeUgSMlkB/YXnV2', 'pio', 'skorpion.op@gmail.com', '9490468679', 'Female', 'Admin', '2024-12-16 12:15:02', '2024-12-21 06:41:56', NULL, 0),
(22, 'krushna', '$2y$10$RqVb6IR5vxu5PDhuGlGuEulet55kkalRjNDn9gmDavcwpy/dqjPsu', 'Dr. D.Krushna', 'krushna@gmai.com', '1234567891', 'Male', 'Teacher', '2024-12-22 17:12:43', '2024-12-22 17:12:43', NULL, 6),
(23, 'Ram', '$2y$10$WLIJqxAO1sfqCBWEc4JQIu7DYKBMZ8X0BgOmJRcWWPbeeEaisX7hC', 'P. Rama Santosh Naidu', 'ram@gmail.com', '1234567891', 'Male', 'Teacher', '2024-12-22 17:14:08', '2024-12-22 17:14:08', 22, 2),
(24, 'Vamsi', '$2y$10$aqVqWRPlyA8wnR1SgpUjD.n75iJbTm31pXL4fmFzyHAtl7VfKZiiW', 'M. Vamsi Krishna', 'vamsi@gmail.com', '234245251213', 'Male', 'Teacher', '2024-12-22 17:14:45', '2024-12-22 17:14:45', 22, 5),
(25, 'Santosh', '$2y$10$lEmaG3WU8Z3YI6S6kKcJj.tR4Nui3vwcICi.pIogU8Jom2nxkUzxi', 'K. Santosh Jhansi', '11@gmail.com', '1233344256', 'Female', 'Teacher', '2024-12-22 17:15:42', '2024-12-22 17:15:42', 22, 1),
(26, 'srinu', '$2y$10$HZ7y1o.lf9sG1d/u8yjQ6eaWLWi47kPRWcuXDRQ6DCwW0ikFN8pMm', 'M. srinivasrao', '1111@gmail.com', '1234527891', 'Male', 'Teacher', '2024-12-22 17:16:47', '2024-12-22 17:16:47', 22, 4),
(27, 'vara', '$2y$10$SZiqUZAFrdFQHA4rxGXNP.oiaWX//doUdf1CnAWcc3Swb0PBFylbC', 'V. Varalakshmi', 'varalakshmi@gmail.com', '1234567891', 'Female', 'Teacher', '2024-12-22 17:17:29', '2024-12-22 17:17:29', 22, 3),
(28, '23331A0507', '$2y$10$/jsxOZPY4cD3hP7SQsg1ReI6rULCpQ0dm.J2.4YCYZoViNBeHn0oG', 'GOGULA OMKAR VENKAT', 'OMKAR@GMAIL.COM', '7337501892', 'Male', 'Student', '2024-12-22 17:19:39', '2024-12-22 17:19:39', 23, 1),
(30, '22331A05C5', '$2y$10$cvAhFXfdx3Bmnq/vGHli5utyJ6kxVWaOQvSwHFEznpAcb5rnEtNkG', 'PALLE KIRAN KUMAR', 'kirankumar82054@gmail.com', '9490468679', 'Male', 'Student', '2024-12-22 17:20:33', '2024-12-22 17:20:33', 22, 1),
(31, '22331A05C3', '$2y$10$tKqJispyJFF.WopkHo62uexG36vg1ZxrlSpXjythUasnfLJoYBkFm', 'PALA SIDDESWARA REDDY', 'SID@GMAIL.COM', '6281424496', 'Male', 'Student', '2024-12-22 17:21:09', '2024-12-24 16:05:48', 23, 1),
(32, '22331A05C4', '$2y$10$Hu2CHLeshatGRuABG0PAYOYQ/.9YnHq8fExsG7wJenZHQLxt.NqXu', 'Sai varun', 'sai143@gmai.com', '11231324', 'Male', 'Student', '2024-12-24 16:04:27', '2024-12-24 16:04:27', 26, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
