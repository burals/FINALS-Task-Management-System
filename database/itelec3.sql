-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2024 at 01:14 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `itelec3-v2`
--

-- --------------------------------------------------------

--
-- Table structure for table `email_config`
--

CREATE TABLE `email_config` (
  `id` int(145) NOT NULL,
  `email` varchar(145) DEFAULT NULL,
  `password` varchar(145) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_config`
--

INSERT INTO `email_config` (`id`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'wrenchnerbangit@gmail.com', 'zapq uiqd mdjn axss', '2024-11-17 13:05:12', NULL),
(1, 'wrenchnerbangit@gmail.com', 'zapq uiqd mdjn axss', '2024-11-17 13:05:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(14) NOT NULL,
  `user_id` int(14) NOT NULL,
  `activity` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `activity`, `created_at`) VALUES
(195, 57, 'Has successfully signed in.', '2024-12-06 12:53:11'),
(196, 57, 'Has successfully signed in.', '2024-12-06 12:54:34'),
(197, 57, 'Has successfully signed in.', '2024-12-06 12:55:17'),
(198, 57, 'Has successfully signed in.', '2024-12-06 12:56:16'),
(199, 57, 'Has successfully signed in.', '2024-12-06 12:58:17'),
(200, 57, 'Has successfully signed in.', '2024-12-06 13:02:29'),
(201, 57, 'Has successfully signed in.', '2024-12-06 13:03:15'),
(202, 57, 'Has successfully signed in.', '2024-12-06 13:03:46'),
(203, 57, 'Has successfully signed in.', '2024-12-06 13:04:05'),
(204, 57, 'Has successfully signed in.', '2024-12-06 13:05:10'),
(205, 57, 'Has successfully signed in.', '2024-12-06 13:06:03'),
(206, 57, 'Has successfully signed in.', '2024-12-06 13:06:50'),
(207, 57, 'Has successfully signed in.', '2024-12-06 13:07:17'),
(208, 57, 'Has successfully signed in.', '2024-12-06 13:10:23'),
(209, 57, 'Has successfully signed in.', '2024-12-06 13:10:39'),
(210, 57, 'Has successfully signed in.', '2024-12-06 13:10:44'),
(211, 51, 'Has successfully signed in.', '2024-12-06 13:10:50'),
(212, 57, 'Has successfully signed in.', '2024-12-06 13:10:57'),
(213, 57, 'Has successfully signed in.', '2024-12-06 13:16:14'),
(214, 57, 'Has successfully signed in.', '2024-12-06 13:17:41'),
(215, 57, 'Has successfully signed in.', '2024-12-06 13:18:20'),
(216, 57, 'Has successfully signed in.', '2024-12-06 13:20:37'),
(217, 57, 'Has successfully signed in.', '2024-12-06 13:23:17'),
(218, 51, 'Has successfully signed in.', '2024-12-06 13:23:29'),
(219, 57, 'Has successfully signed in.', '2024-12-06 13:23:37'),
(220, 52, 'Has successfully signed in.', '2024-12-06 13:25:03'),
(221, 52, 'Has successfully signed in.', '2024-12-06 13:25:28'),
(222, 52, 'Has successfully signed in.', '2024-12-06 13:27:49'),
(223, 57, 'Has successfully signed in.', '2024-12-06 13:27:54'),
(224, 51, 'Has successfully signed in.', '2024-12-06 13:28:00'),
(225, 52, 'Has successfully signed in.', '2024-12-06 13:28:07'),
(226, 57, 'Has successfully signed in.', '2024-12-06 13:32:23'),
(227, 57, 'Has successfully signed in.', '2024-12-06 13:32:52'),
(228, 51, 'Has successfully signed in.', '2024-12-06 13:34:48'),
(229, 52, 'Has successfully signed in.', '2024-12-06 13:35:05'),
(230, 57, 'Has successfully signed in.', '2024-12-06 13:38:19'),
(231, 52, 'Has successfully signed in.', '2024-12-06 13:38:26'),
(232, 52, 'Has successfully signed in.', '2024-12-06 13:43:07'),
(233, 52, 'Has successfully signed in.', '2024-12-06 13:43:50'),
(234, 52, 'Has successfully signed in.', '2024-12-06 13:44:14'),
(235, 52, 'Has successfully signed in.', '2024-12-06 13:44:28'),
(236, 52, 'Has successfully signed in.', '2024-12-06 13:45:31'),
(237, 52, 'Has successfully signed in.', '2024-12-06 13:45:55'),
(238, 52, 'Has successfully signed in.', '2024-12-06 13:46:07'),
(239, 52, 'Has successfully signed in.', '2024-12-06 13:46:23'),
(240, 52, 'Has successfully signed in.', '2024-12-06 13:48:17'),
(241, 57, 'Has successfully signed in.', '2024-12-06 13:50:02'),
(242, 51, 'Has successfully signed in.', '2024-12-06 13:56:20'),
(243, 51, 'Has successfully signed in.', '2024-12-06 13:56:27'),
(244, 57, 'Has successfully signed in.', '2024-12-06 13:56:34'),
(245, 52, 'Has successfully signed in.', '2024-12-06 13:56:41'),
(246, 51, 'Has successfully signed in.', '2024-12-08 12:50:18'),
(247, 52, 'Has successfully signed in.', '2024-12-08 12:50:25'),
(248, 51, 'Has successfully signed in.', '2024-12-08 13:37:09'),
(249, 52, 'Has successfully signed in.', '2024-12-08 13:50:00'),
(250, 51, 'Has successfully signed in.', '2024-12-08 13:50:46'),
(251, 52, 'Has successfully signed in.', '2024-12-08 13:50:52'),
(252, 52, 'Has successfully signed in.', '2024-12-08 13:51:30'),
(253, 52, 'Has successfully signed in.', '2024-12-08 13:52:32'),
(254, 52, 'Has successfully signed in.', '2024-12-08 13:52:37'),
(255, 52, 'Has successfully signed in.', '2024-12-08 13:53:05'),
(256, 52, 'Has successfully signed in.', '2024-12-08 13:53:14'),
(257, 51, 'Has successfully signed in.', '2024-12-08 14:04:33'),
(258, 52, 'Has successfully signed in.', '2024-12-08 14:04:42'),
(259, 57, 'Has successfully signed in.', '2024-12-15 15:51:05'),
(260, 57, 'Has successfully signed in.', '2024-12-15 15:52:43'),
(261, 51, 'Has successfully signed in.', '2024-12-15 15:57:40'),
(262, 57, 'Has successfully signed in.', '2024-12-19 06:46:09'),
(263, 51, 'Has successfully signed in.', '2024-12-19 06:46:19'),
(264, 57, 'Has successfully signed in.', '2024-12-19 06:48:57'),
(265, 51, 'Has successfully signed in.', '2024-12-19 06:49:02'),
(266, 57, 'Has successfully signed in.', '2024-12-19 07:24:31'),
(267, 51, 'Has successfully signed in.', '2024-12-19 07:24:42'),
(268, 57, 'Has successfully signed in.', '2024-12-19 07:50:15'),
(269, 57, 'Has successfully signed in.', '2024-12-19 07:57:33'),
(270, 57, 'Has successfully signed in.', '2024-12-19 08:09:33'),
(271, 57, 'Has successfully signed in.', '2024-12-19 08:09:48'),
(272, 57, 'Has successfully signed in.', '2024-12-19 08:10:23'),
(273, 57, 'Has successfully signed in.', '2024-12-19 08:18:27'),
(274, 57, 'Has successfully signed in.', '2024-12-19 08:19:51'),
(275, 57, 'Has successfully signed in.', '2024-12-19 08:19:57'),
(276, 57, 'Has successfully signed in.', '2024-12-19 08:20:46'),
(277, 57, 'Has successfully signed in.', '2024-12-19 08:21:02'),
(278, 57, 'Has successfully signed in.', '2024-12-19 09:23:36'),
(279, 57, 'Has successfully signed in.', '2024-12-19 09:24:21'),
(280, 57, 'Has successfully signed in.', '2024-12-19 09:26:23'),
(281, 57, 'Has successfully signed in.', '2024-12-19 09:26:26'),
(282, 57, 'Has successfully signed in.', '2024-12-19 09:26:49'),
(283, 57, 'Has successfully signed in.', '2024-12-19 09:27:19'),
(284, 57, 'Has successfully signed in.', '2024-12-19 09:27:47'),
(285, 57, 'Has successfully signed in.', '2024-12-19 09:27:52'),
(286, 57, 'Has successfully signed in.', '2024-12-19 09:28:28'),
(287, 52, 'Has successfully signed in.', '2024-12-19 09:50:18'),
(288, 51, 'Has successfully signed in.', '2024-12-19 09:50:27'),
(289, 57, 'Has successfully signed in.', '2024-12-19 09:50:43'),
(290, 57, 'Has successfully signed in.', '2024-12-19 09:51:34'),
(291, 57, 'Has successfully signed in.', '2024-12-19 09:52:31'),
(292, 57, 'Has successfully signed in.', '2024-12-19 10:03:15'),
(293, 57, 'Has successfully signed in.', '2024-12-19 10:03:21'),
(294, 51, 'Has successfully signed in.', '2024-12-19 10:09:47'),
(295, 52, 'Has successfully signed in.', '2024-12-19 10:09:54'),
(296, 57, 'Has successfully signed in.', '2024-12-19 10:10:33'),
(297, 57, 'Has successfully signed in.', '2024-12-19 10:13:19'),
(298, 57, 'Has successfully signed in.', '2024-12-19 10:27:35'),
(299, 57, 'Has successfully signed in.', '2024-12-19 11:21:45'),
(300, 51, 'Has successfully signed in.', '2024-12-19 11:41:46'),
(301, 57, 'Has successfully signed in.', '2024-12-19 11:42:57'),
(302, 57, 'Has successfully signed in.', '2024-12-19 11:44:11'),
(303, 51, 'Has successfully signed in.', '2024-12-19 11:45:40'),
(304, 57, 'Has successfully signed in.', '2024-12-19 11:49:43'),
(305, 57, 'Has successfully signed in.', '2024-12-19 12:04:24'),
(306, 57, 'Has successfully signed in.', '2024-12-19 12:05:35'),
(307, 53, 'Has successfully signed in.', '2024-12-19 12:12:02'),
(308, 53, 'Has successfully signed in.', '2024-12-19 12:12:26'),
(309, 53, 'Has successfully signed in.', '2024-12-19 12:13:01');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `task_id`, `content`, `created_at`) VALUES
(3, 57, 22, 'network files is ....', '2024-12-19 11:57:23');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `due_date` date NOT NULL,
  `assigned_employee` int(11) DEFAULT NULL,
  `status` enum('pending','in progress','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `has_documents` tinyint(1) DEFAULT 0,
  `has_reports` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `due_date`, `assigned_employee`, `status`, `created_at`, `updated_at`, `has_documents`, `has_reports`) VALUES
(18, '1', '2', '2024-12-12', 53, 'in progress', '2024-11-24 18:51:19', '2024-12-19 12:12:16', 0, 0),
(22, '123', '2321', '2025-12-12', NULL, 'completed', '2024-12-08 13:41:21', '2024-12-19 11:57:23', 0, 0),
(23, '123', '321', '2024-12-17', 57, 'pending', '2024-12-15 15:57:59', '2024-12-19 11:44:26', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `task_assignments`
--

CREATE TABLE `task_assignments` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_assignments`
--

INSERT INTO `task_assignments` (`id`, `task_id`, `employee_id`) VALUES
(20, 18, 51),
(21, 18, 52),
(25, 22, 57),
(26, 23, 57);

-- --------------------------------------------------------

--
-- Table structure for table `task_documents`
--

CREATE TABLE `task_documents` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_documents`
--

INSERT INTO `task_documents` (`id`, `task_id`, `user_id`, `file_path`, `uploaded_at`) VALUES
(4, 22, 57, '../uploads/57/documents/FINALS_NET.doc', '2024-12-19 11:57:23');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `reset_token` varchar(400) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `status` enum('not_active','active') NOT NULL DEFAULT 'active',
  `tokencode` varchar(400) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','users','chairperson') NOT NULL DEFAULT 'users',
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `fullname`, `email`, `password`, `reset_token`, `token_expiry`, `status`, `tokencode`, `created_at`, `role`, `profile_picture`) VALUES
(51, 'DEAN Seth', 'sthlgns@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, NULL, 'active', NULL, '2024-11-24 10:49:30', 'admin', NULL),
(52, 'TEACHER Ken', 'jthrlgns@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, NULL, 'active', NULL, '2024-11-24 12:21:31', 'chairperson', NULL),
(53, 'TEACHER James', 'brighthing2003@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, NULL, 'active', NULL, '2024-11-24 18:35:52', 'users', NULL),
(57, 'wrench', 'kairuschan@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, NULL, 'active', NULL, '2024-12-06 12:53:04', 'users', 'profile.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_employee` (`assigned_employee`);

--
-- Indexes for table `task_assignments`
--
ALTER TABLE `task_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `task_assignments_ibfk_1` (`task_id`);

--
-- Indexes for table `task_documents`
--
ALTER TABLE `task_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=310;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `task_assignments`
--
ALTER TABLE `task_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `task_documents`
--
ALTER TABLE `task_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`assigned_employee`) REFERENCES `user` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `task_assignments`
--
ALTER TABLE `task_assignments`
  ADD CONSTRAINT `task_assignments_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_assignments_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `task_documents`
--
ALTER TABLE `task_documents`
  ADD CONSTRAINT `task_documents_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_documents_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
