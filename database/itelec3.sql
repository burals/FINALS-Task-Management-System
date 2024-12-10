-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 10, 2024 at 07:06 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `itelec3`
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
(259, 51, 'Has successfully signed in.', '2024-12-08 14:09:28'),
(260, 52, 'Has successfully signed in.', '2024-12-08 14:09:34'),
(261, 53, 'Has successfully signed in.', '2024-12-08 14:09:50'),
(262, 51, 'Has successfully signed in.', '2024-12-08 14:15:33'),
(263, 52, 'Has successfully signed in.', '2024-12-08 14:21:41'),
(264, 51, 'Has successfully signed in.', '2024-12-08 14:21:50'),
(265, 51, 'Has successfully signed in.', '2024-12-08 14:23:44'),
(266, 52, 'Has successfully signed in.', '2024-12-08 14:25:29'),
(267, 51, 'Has successfully signed in.', '2024-12-08 14:28:32'),
(268, 51, 'Has successfully signed in.', '2024-12-08 14:37:43'),
(269, 51, 'Has successfully signed in.', '2024-12-08 14:38:42'),
(270, 52, 'Has successfully signed in.', '2024-12-08 14:38:54'),
(271, 51, 'Has successfully signed in.', '2024-12-08 14:39:02'),
(272, 51, 'Has successfully signed in.', '2024-12-08 14:40:42'),
(273, 53, 'Has successfully signed in.', '2024-12-08 14:41:30'),
(274, 51, 'Has successfully signed in.', '2024-12-09 11:42:57'),
(275, 51, 'Has successfully signed in.', '2024-12-09 11:43:44'),
(276, 51, 'Has successfully signed in.', '2024-12-09 11:46:28'),
(277, 51, 'Has successfully signed in.', '2024-12-09 11:48:52'),
(278, 51, 'Has successfully signed in.', '2024-12-09 11:56:47'),
(279, 51, 'Has successfully signed in.', '2024-12-09 11:57:27'),
(280, 52, 'Has successfully signed in.', '2024-12-09 11:57:37'),
(281, 52, 'Has successfully signed in.', '2024-12-09 11:57:49'),
(282, 53, 'Has successfully signed in.', '2024-12-09 11:57:56'),
(283, 52, 'Has successfully signed in.', '2024-12-09 11:58:06'),
(284, 51, 'Has successfully signed in.', '2024-12-09 11:58:12'),
(285, 52, 'Has successfully signed in.', '2024-12-09 12:00:28'),
(286, 51, 'Has successfully signed in.', '2024-12-09 12:00:34'),
(287, 52, 'Has successfully signed in.', '2024-12-09 12:33:08'),
(288, 51, 'Has successfully signed in.', '2024-12-09 12:52:41'),
(289, 51, 'Has successfully signed in.', '2024-12-10 08:09:51'),
(290, 53, 'Has successfully signed in.', '2024-12-10 09:58:55'),
(291, 51, 'Has successfully signed in.', '2024-12-10 10:01:36'),
(292, 53, 'Has successfully signed in.', '2024-12-10 10:01:44'),
(293, 53, 'Has successfully signed in.', '2024-12-10 10:01:58'),
(294, 51, 'Has successfully signed in.', '2024-12-10 10:02:08'),
(295, 52, 'Has successfully signed in.', '2024-12-10 10:04:18'),
(296, 51, 'Has successfully signed in.', '2024-12-10 10:04:25'),
(297, 52, 'Has successfully signed in.', '2024-12-10 10:04:54'),
(298, 51, 'Has successfully signed in.', '2024-12-10 10:05:03'),
(299, 52, 'Has successfully signed in.', '2024-12-10 10:18:33'),
(300, 51, 'Has successfully signed in.', '2024-12-10 10:24:55'),
(301, 52, 'Has successfully signed in.', '2024-12-10 10:27:00'),
(302, 52, 'Has successfully signed in.', '2024-12-10 10:29:22'),
(303, 51, 'Has successfully signed in.', '2024-12-10 10:35:37'),
(304, 52, 'Has successfully signed in.', '2024-12-10 10:35:48'),
(305, 51, 'Has successfully signed in.', '2024-12-10 10:39:58'),
(306, 52, 'Has successfully signed in.', '2024-12-10 10:40:03'),
(307, 51, 'Has successfully signed in.', '2024-12-10 10:40:55'),
(308, 52, 'Has successfully signed in.', '2024-12-10 10:41:02'),
(309, 51, 'Has successfully signed in.', '2024-12-10 10:41:13'),
(310, 52, 'Has successfully signed in.', '2024-12-10 10:42:43'),
(311, 51, 'Has successfully signed in.', '2024-12-10 10:43:03'),
(312, 53, 'Has successfully signed in.', '2024-12-10 10:52:02'),
(313, 51, 'Has successfully signed in.', '2024-12-10 10:53:31'),
(314, 52, 'Has successfully signed in.', '2024-12-10 10:54:54'),
(315, 51, 'Has successfully signed in.', '2024-12-10 11:02:46'),
(316, 53, 'Has successfully signed in.', '2024-12-10 14:38:13'),
(317, 51, 'Has successfully signed in.', '2024-12-10 14:38:34'),
(318, 53, 'Has successfully signed in.', '2024-12-10 14:38:39'),
(319, 51, 'Has successfully signed in.', '2024-12-10 14:39:41'),
(320, 53, 'Has successfully signed in.', '2024-12-10 14:39:50'),
(321, 51, 'Has successfully signed in.', '2024-12-10 14:41:26'),
(322, 51, 'Has successfully signed in.', '2024-12-10 16:32:30'),
(323, 51, 'Has successfully signed in.', '2024-12-10 16:45:22'),
(324, 51, 'Has successfully signed in.', '2024-12-10 17:37:45'),
(325, 51, 'Has successfully signed in.', '2024-12-10 17:39:32');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `employee_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `title`, `description`, `task_id`, `employee_id`, `created_at`) VALUES
(38, 'hi', 'test', 23, 51, '2024-12-10 17:54:13');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `due_date`, `assigned_employee`, `status`, `created_at`, `updated_at`) VALUES
(23, 'test', '123', '2024-12-12', NULL, 'in progress', '2024-12-10 10:25:06', '2024-12-10 17:30:45');

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
(37, 23, 52);

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
  `role` enum('admin','users','chairperson') NOT NULL DEFAULT 'users'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `fullname`, `email`, `password`, `reset_token`, `token_expiry`, `status`, `tokencode`, `created_at`, `role`) VALUES
(51, 'DEAN Seth', 'sthlgns@gmail.com', '202cb962ac59075b964b07152d234b70', '2dd70d2c0beb0cf59e52f1a76b37aaf6d9b4220a5326f9481b708a6e8d82cbd9', '2024-12-10 12:21:07', 'active', NULL, '2024-11-24 10:49:30', 'admin'),
(52, 'TEACHER Ken', 'jthrlgns@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, NULL, 'active', NULL, '2024-11-24 12:21:31', 'chairperson'),
(53, 'TEACHER James', 'brighthing2003@gmail.com', '202cb962ac59075b964b07152d234b70', 'e189b02696a7fa55294cc54039cc086777656216e2c0e092fd0f9db9385d9aee', '2024-12-10 12:23:49', 'active', NULL, '2024-11-24 18:35:52', 'users'),
(57, 'Wrenchner', 'kairuschan@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, NULL, 'active', NULL, '2024-12-06 12:53:04', 'users');

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
  ADD KEY `reports_ibfk_2` (`employee_id`);

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
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=326;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `task_assignments`
--
ALTER TABLE `task_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

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
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`),
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
