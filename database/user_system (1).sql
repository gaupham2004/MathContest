-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 28, 2026 at 02:02 AM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `user_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `official_questions`
--

CREATE TABLE `official_questions` (
  `id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `question_test` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_option` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `official_results`
--

CREATE TABLE `official_results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `total_questions` int(11) NOT NULL,
  `date_taken` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `official_results`
--

INSERT INTO `official_results` (`id`, `user_id`, `test_id`, `score`, `total_questions`, `date_taken`) VALUES
(8, 13, 1, 1, 2, '2026-02-27 20:56:42'),
(9, 13, 2, 2, 2, '2026-02-27 20:56:42');

-- --------------------------------------------------------

--
-- Table structure for table `official_tests`
--

CREATE TABLE `official_tests` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `grade` varchar(50) NOT NULL,
  `time_limit` int(11) NOT NULL,
  `num_questions` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `official_tests`
--

INSERT INTO `official_tests` (`id`, `name`, `category`, `grade`, `time_limit`, `num_questions`) VALUES
(1, 'General Mathematics Quiz', 'Primary', 'Grade 3', 30, 2),
(2, 'Algebra Fundamentals', 'Junior', 'Grade 8', 45, 2),
(3, 'Advanced Statistics', 'Senior', 'Grade 12', 60, 1),
(4, 'General Mathematics Quiz', 'Primary', 'Grade 3', 30, 2),
(5, 'Algebra Fundamentals', 'Junior', 'Grade 8', 45, 2),
(6, 'Advanced Statistics', 'Senior', 'Grade 12', 60, 1);

-- --------------------------------------------------------

--
-- Table structure for table `paypal_payments`
--

CREATE TABLE `paypal_payments` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `paypal_order_id` varchar(100) DEFAULT NULL,
  `payer_email` varchar(255) DEFAULT NULL,
  `payer_id` varchar(100) DEFAULT NULL,
  `captured_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `paypal_payments`
--

INSERT INTO `paypal_payments` (`id`, `transaction_id`, `paypal_order_id`, `payer_email`, `payer_id`, `captured_at`) VALUES
(1, 4, '5W68128615780744D', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-19 19:36:39'),
(2, 5, '7SN12437AN7847059', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-19 19:58:45'),
(3, 6, '3DT12193C8809911N', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-20 17:06:53'),
(4, 7, '9UG02646BP094960E', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-22 13:47:16'),
(5, 8, '0VE53095JG197963K', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-22 19:48:22'),
(6, 9, '5T246640F58166213', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-23 13:25:40'),
(7, 10, '52S24086TP0273826', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-23 14:01:36'),
(8, 11, '3TV605792J156042G', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-23 14:05:48'),
(9, 12, '5XT17564NA085092V', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-23 17:07:23'),
(10, 13, '1HK32080NC1720059', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-24 14:20:39'),
(11, 14, '9VK41035HC454063H', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-24 14:24:27'),
(12, 15, '466524542G708154Y', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-24 14:29:39'),
(13, 16, '85L23461LL524961A', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-24 14:34:44'),
(14, 17, '4FJ52932JC715771X', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-24 14:39:10'),
(15, 18, '0LJ503786G175150K', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-26 21:02:45'),
(16, 19, '4HE33656LJ715111K', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-26 22:02:56'),
(17, 20, '92T257261C194162W', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-26 22:25:09'),
(18, 21, '47491007TT579841D', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-26 22:31:07'),
(19, 22, '44T363517X6048605', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-26 22:41:24'),
(20, 23, '97D00043HW5965122', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-26 22:47:51'),
(21, 24, '40L01601NN6390205', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-26 22:48:35'),
(22, 25, '79M3192262525401J', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-26 22:52:16'),
(23, 26, '9FT81813AP224613W', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-27 15:06:22'),
(24, 27, '71N91733LU370273C', 'sb-hnlki43850467@personal.example.com', 'KYHXVDYH2RCBY', '2025-06-27 17:05:17');

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `author` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `replied_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `replies`
--

INSERT INTO `replies` (`id`, `topic_id`, `author`, `message`, `replied_at`) VALUES
(1, 1, 'MathWhiz', 'Make a study schedule and stick to it.', '2025-06-25 21:27:58'),
(2, 1, 'PrepNinja', 'Practice past papers under timed conditions!', '2025-06-25 21:27:58'),
(3, 2, 'ProblemSolver', 'Try the Chicken McNugget problem — it’s fun!', '2025-06-25 21:27:58'),
(4, 2, 'AlgebraKing', 'I love the one where you prove 1=2 (and it’s wrong).', '2025-06-25 21:27:58'),
(5, 3, 'Vuong', 'Where do we submit questions about login issues?', '2025-06-25 21:27:58'),
(6, 3, 'HelperBot', 'Send a message to admin@mathema.com', '2025-06-25 21:27:58'),
(7, 4, 'Student101', 'I recommend Khan Academy’s Grade 9 modules.', '2025-06-25 21:27:58'),
(8, 4, 'TutorJane', 'Don’t forget IXL — super helpful.', '2025-06-25 21:27:58'),
(9, 5, 'GameOn', 'Prodigy is awesome for younger students.', '2025-06-25 21:27:58'),
(10, 5, 'BrainFun', 'Math games boost retention for sure.', '2025-06-25 21:27:58'),
(11, 6, 'Speedy', 'Skip the hard ones and come back later.', '2025-06-25 21:27:58'),
(12, 6, 'FocusGuy', 'Mark the time every 10 minutes to stay on pace.', '2025-06-25 21:27:58'),
(13, 7, 'ZenMode', 'Breathing techniques help me a lot.', '2025-06-25 21:27:58'),
(14, 7, 'CoachTom', 'Practice builds confidence.', '2025-06-25 21:27:58'),
(15, 8, 'ContestMom', 'Snacks, pencils, ID, and water bottle.', '2025-06-25 21:27:58'),
(16, 8, 'PackMaster', 'Bring a watch and backup calculator.', '2025-06-25 21:27:58'),
(17, 9, 'MathOops', 'I once subtracted instead of adding in a geometry question.', '2025-06-25 21:27:58'),
(18, 9, 'LaughWithMe', 'I wrote 3.14 for every answer. It was a π test.', '2025-06-25 21:27:58'),
(19, 10, 'GrindMaster', 'Daily drills helped a lot.', '2025-06-25 21:27:58'),
(20, 10, 'Motivator', 'Celebrate small wins to stay motivated.', '2025-06-25 21:27:58'),
(21, 2, 'Guest', 'hi', '2025-06-27 09:32:29'),
(24, 3, 'Vuong', 'hi there', '2025-06-27 09:44:42'),
(25, 1, 'Vuong Pham', 'hi', '2025-06-27 13:09:29');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `filename` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `title`, `description`, `filename`, `category`, `status`, `uploaded_at`) VALUES
(1, 'Geometry Cheat Sheet', 'A quick guide to shapes and formulas.', 'geometry_guide.pdf', 'Primary', 'approved', '2026-02-28 01:46:07'),
(2, 'Algebra Workbook', 'Practice problems for Junior students.', 'algebra_basics.pdf', 'Junior', 'pending', '2026-02-28 01:46:07'),
(3, 'Mathematics Handbook', 'Comprehensive guide to basic arithmetic and logic.', 'math_basics.pdf', 'Primary', 'approved', '2026-02-28 01:54:29'),
(4, 'Algebra Study Guide', 'Essential formulas and practice problems for Junior level.', 'algebra_guide.pdf', 'Junior', 'approved', '2026-02-28 01:54:29'),
(5, 'Advanced Calculus Notes', 'Pre-read material for Senior level mathematics.', 'calculus_intro.pdf', 'Senior', 'pending', '2026-02-28 01:54:29');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `category` varchar(20) DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `email`, `password`, `category`, `transaction_id`) VALUES
(3, 'test1@gmail.com', '$2y$10$PDYU1fddjjQOYNDc.osajOLwVWeUFtDz5QctXYQ5HWK0ydm56Z0AO', 'open', 11),
(4, 'team1@gmail.com', '$2y$10$PSKfacXxbbLVE9iw3cHtw.J5R6LGJr6.Wg27qAxsCFDcZAMzZiTuO', '', 14),
(6, 'team2@gmail.com', '$2y$10$BqfQ0qSAXx9MTxnCniEwheu7mxLe.ObtU7uvMpw04SvbIo0ZEEmt6', 'open', 16),
(7, 'vuongmon2004@gmail.com', '$2y$10$LI7PVL82tN6.KH4EZqHhteXGntaVquZZpQuL8utPmHgrtaClbq.E6', 'open', 25);

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `member_name` varchar(100) NOT NULL,
  `birthday` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `team_id`, `member_name`, `birthday`, `age`) VALUES
(9, 3, 'member 1', '2000-05-05', 25),
(10, 3, 'member 2', '2018-05-05', 7),
(11, 3, 'member 3', '1999-05-05', 26),
(12, 3, 'member 4', '2018-05-05', 7),
(13, 6, 'member b1', '2000-04-04', 25),
(14, 6, 'member b2', '2000-04-04', 25),
(15, 6, 'member b3', '2000-05-05', 25),
(16, 6, 'member b4', '2000-05-05', 25),
(17, 7, 'member d1', '2000-05-05', 25),
(18, 7, 'member d2', '2000-02-02', 25),
(19, 7, 'member d3', '2000-02-02', 25),
(20, 7, 'member d4', '2000-02-02', 25);

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(100) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`id`, `title`, `author`, `category`, `created_at`, `description`) VALUES
(1, 'How to Prepare for the Mathema Contest?', 'AnnaM', 'Contest Preparation', '2025-06-24 20:49:50', NULL),
(2, 'Share Your Favorite Math Problem', 'MathFan2025', 'Math Tips & Tricks', '2025-06-24 20:49:50', NULL),
(3, 'General Questions & Technical Issues', 'Vuong', 'Technical Issues', '2025-06-24 20:49:50', NULL),
(4, 'Best Resources for Grade 9 Math', 'StudyBuddy', 'Contest Preparation', '2025-06-24 20:49:50', NULL),
(5, 'Is Math More Fun with Games?', 'PuzzleMaster', 'General Discussion', '2025-06-24 20:49:50', NULL),
(6, 'Tips for Finishing on Time', 'QuickSolver', 'Contest Preparation', '2025-06-24 20:49:50', NULL),
(7, 'How to Overcome Test Anxiety?', 'CalmThinker', 'General Discussion', '2025-06-24 20:49:50', NULL),
(8, 'What to Bring on Contest Day?', 'PrepQueen', 'Contest Preparation', '2025-06-24 20:49:50', NULL),
(9, 'Share Funny Math Fails', 'LOL_Math', 'General Discussion', '2025-06-24 20:49:50', NULL),
(10, 'How I Improved from 60% to 90%', 'LevelUpPro', 'Math Tips & Tricks', '2025-06-24 20:49:50', NULL),
(11, 'Best Time Management Tips for Mathema Contest', 'Vuong', 'General Discussion', '2025-06-27 10:03:20', 'I\'m often running out of time during math contests. What strategies do you use to manage time better during the Mathema Contest?\r\nPlease share your techniques!'),
(12, 'What to Expect in the Junior Round', 'Vuong', 'General Discussion', '2025-06-27 10:43:36', 'Has anyone taken the junior round before? What type of questions come up the most?'),
(13, 'Tips for Solving Logic Puzzles Fast', 'Vuong', 'General Discussion', '2025-06-27 11:11:58', 'I\'ve found that timing yourself while solving logic puzzles helps build speed. Does anyone else use this strategy or have better tips?'),
(17, 'Why I Love Math Puzzles', 'Vuong', 'General Discussion', '2025-06-27 11:37:41', 'I’ve been solving math puzzles since I was 10. They’re not just fun, but they really train your brain. What are your favorite ones?'),
(18, 'hello everyone', 'Vuong Pham', 'General Discussion', '2025-06-27 13:09:57', 'hello');

-- --------------------------------------------------------

--
-- Table structure for table `training_questions`
--

CREATE TABLE `training_questions` (
  `id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL,
  `question_test` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_option` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `training_questions`
--

INSERT INTO `training_questions` (`id`, `training_id`, `question_test`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`) VALUES
(1, 1, 'How many sides does an octagon have?', '6', '7', '8', '10', 'C'),
(2, 1, 'What is 100 divided by 4?', '20', '25', '40', '50', 'B');

-- --------------------------------------------------------

--
-- Table structure for table `training_tests`
--

CREATE TABLE `training_tests` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `num_questions` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `training_tests`
--

INSERT INTO `training_tests` (`id`, `name`, `category`, `num_questions`) VALUES
(1, 'General Math Practice', 'Open', 2);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `amount`, `status`, `created_at`) VALUES
(1, '5.00', 'COMPLETED', '2025-06-19 16:31:22'),
(2, '5.00', 'COMPLETED', '2025-06-19 19:32:32'),
(3, '5.00', 'COMPLETED', '2025-06-19 19:34:48'),
(4, '5.00', 'COMPLETED', '2025-06-19 19:36:43'),
(5, '5.00', 'COMPLETED', '2025-06-19 19:59:07'),
(6, '5.00', 'COMPLETED', '2025-06-20 17:08:22'),
(7, '5.00', 'COMPLETED', '2025-06-22 13:47:39'),
(8, '5.00', 'COMPLETED', '2025-06-22 19:50:20'),
(9, '5.00', 'COMPLETED', '2025-06-23 13:30:38'),
(10, '5.00', 'COMPLETED', '2025-06-23 14:01:54'),
(11, '20.00', 'COMPLETED', '2025-06-23 14:05:50'),
(12, '5.00', 'COMPLETED', '2025-06-23 17:08:21'),
(13, '5.00', 'COMPLETED', '2025-06-24 14:22:58'),
(14, '20.00', 'COMPLETED', '2025-06-24 14:24:34'),
(15, '20.00', 'COMPLETED', '2025-06-24 14:29:47'),
(16, '20.00', 'COMPLETED', '2025-06-24 14:34:52'),
(17, '5.00', 'COMPLETED', '2025-06-24 14:39:17'),
(18, '5.00', 'COMPLETED', '2025-06-26 21:04:32'),
(19, '5.00', 'COMPLETED', '2025-06-26 22:03:23'),
(20, '5.00', 'COMPLETED', '2025-06-26 22:25:29'),
(21, '5.00', 'COMPLETED', '2025-06-26 22:31:14'),
(22, '5.00', 'COMPLETED', '2025-06-26 22:41:31'),
(23, '5.00', 'COMPLETED', '2025-06-26 22:47:58'),
(24, '5.00', 'COMPLETED', '2025-06-26 22:48:42'),
(25, '20.00', 'COMPLETED', '2025-06-26 22:52:24'),
(26, '5.00', 'COMPLETED', '2025-06-27 15:10:11'),
(27, '5.00', 'COMPLETED', '2025-06-27 17:05:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `category` varchar(20) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user',
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `category`, `birthday`, `age`, `transaction_id`, `email`, `role`, `phone`) VALUES
(13, 'Vuong', '$2y$10$.pUC3eson.z1QQYx3pIJlukg2zO/5QSnKN3PvUjdHlqvTUKo5m6u.', 'primary', '2018-05-05', 7, 13, 'gaupham2004@gmail.com', 'user', NULL),
(15, 'Hoang', '$2y$10$WZvcSagDR8c.eJyEtthXCu.ydSYIUN28k/y3V4hL.mIYl7SanWvX2', 'open', '2000-05-05', 25, NULL, 'cpham1@confederationcollege.ca', 'user', NULL),
(16, 'Vun', '$2y$10$ZKij7NWW3L79/JhSxLTEMuPLr9l.EFWGltH1utCyu5UVclQjHYsu.', 'open', '2000-05-05', 25, 26, 'vuongmon2004@gmail.com', 'user', NULL),
(17, 'Vuong Pham', '$2y$10$WZsNmlLAeFKnzG.UMM9wtuGv7Ulc8BOolDvdSPI3PYqyzvzB29QZ6', 'senior', '2004-02-02', 21, 27, 'phamcaovun2004@gmail.com', 'user', NULL);

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `set_age_before_update` BEFORE UPDATE ON `users` FOR EACH ROW BEGIN
  SET NEW.age = TIMESTAMPDIFF(YEAR, NEW.birthday, CURDATE());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `education` varchar(255) DEFAULT NULL,
  `interests` text,
  `goals` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `category` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `education`, `interests`, `goals`, `created_at`, `category`) VALUES
(1, 13, 'Middle School', 'math', 'Software Engineer', '2025-06-24 20:42:07', 'primary'),
(2, 17, 'College / University', 'Math', 'Software Engineer', '2025-06-27 13:07:28', 'senior');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `official_questions`
--
ALTER TABLE `official_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_id` (`test_id`);

--
-- Indexes for table `official_results`
--
ALTER TABLE `official_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `test_id` (`test_id`);

--
-- Indexes for table `official_tests`
--
ALTER TABLE `official_tests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paypal_payments`
--
ALTER TABLE `paypal_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_transaction_id` (`transaction_id`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topic_id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`email`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `training_questions`
--
ALTER TABLE `training_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `training_id` (`training_id`);

--
-- Indexes for table `training_tests`
--
ALTER TABLE `training_tests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `official_questions`
--
ALTER TABLE `official_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `official_results`
--
ALTER TABLE `official_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `official_tests`
--
ALTER TABLE `official_tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `paypal_payments`
--
ALTER TABLE `paypal_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `training_questions`
--
ALTER TABLE `training_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `training_tests`
--
ALTER TABLE `training_tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `official_questions`
--
ALTER TABLE `official_questions`
  ADD CONSTRAINT `official_questions_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `official_tests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `official_results`
--
ALTER TABLE `official_results`
  ADD CONSTRAINT `official_results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `official_results_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `official_tests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `paypal_payments`
--
ALTER TABLE `paypal_payments`
  ADD CONSTRAINT `fk_transaction_id` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `paypal_payments_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`);

--
-- Constraints for table `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `team_members`
--
ALTER TABLE `team_members`
  ADD CONSTRAINT `team_members_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `training_questions`
--
ALTER TABLE `training_questions`
  ADD CONSTRAINT `training_questions_ibfk_1` FOREIGN KEY (`training_id`) REFERENCES `training_tests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
