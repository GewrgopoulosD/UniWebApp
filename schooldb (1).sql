-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Εξυπηρετητής: 127.0.0.1
-- Χρόνος δημιουργίας: 27 Δεκ 2025 στις 18:22:08
-- Έκδοση διακομιστή: 10.4.32-MariaDB
-- Έκδοση PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `schooldb`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `assignments`
--

CREATE TABLE `assignments` (
  `assignment_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `assignment_title` varchar(255) NOT NULL,
  `assignment_description` text DEFAULT NULL,
  `deadline` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `title_course` varchar(255) NOT NULL,
  `professor_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `courses`
--

INSERT INTO `courses` (`course_id`, `title_course`, `professor_id`, `created_at`) VALUES
(1, 'PHP-Programming', 24, '2025-12-21 17:52:33'),
(5, 'Java-Programming', 24, '2025-12-22 12:40:37'),
(11, 'JavaScript-Programming', 24, '2025-12-22 13:22:11'),
(13, 'test', 24, '2025-12-24 20:38:15'),
(14, 'test2', 24, '2025-12-24 20:38:21'),
(15, 'test3', 24, '2025-12-24 20:38:34'),
(16, 'sth', 24, '2025-12-24 20:42:13');

--
-- Δείκτες `courses`
--
DELIMITER $$
CREATE TRIGGER `check_professor_before_insert` BEFORE INSERT ON `courses` FOR EACH ROW BEGIN
    DECLARE userRole INT;

    SELECT role_id
    INTO userRole
    FROM users
    WHERE user_id = NEW.professor_id;

    IF userRole != 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Only teachers can upload courses!';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `submissions`
--

CREATE TABLE `submissions` (
  `submission_id` int(11) NOT NULL,
  `assignments_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `submitted_at` datetime DEFAULT current_timestamp(),
  `grade` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `topics`
--

CREATE TABLE `topics` (
  `topic_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `topic_title` varchar(255) NOT NULL,
  `resource_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(25) NOT NULL,
  `spCode` varchar(8) NOT NULL,
  `signup` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role_id`, `spCode`, `signup`) VALUES
(24, 'Teacher1', 'teacher1@test.gr', '$2y$10$ZtLTXrk2zBVhFEfyUKAxI.8rsdsydHoJxI2QuAxPNMGiWaJqg/MpW', 0, 'PROF2025', '2025-11-24 09:29:37'),
(25, 'Student1', 'student1@test.gr', '$2y$10$xKQpPRbJkl7mcky/6wkTeeBQwGh.jAsqQytIem/areONGsvRJVmn6', 1, 'STUD2025', '2025-11-24 09:30:53'),
(27, 'Teacher2', 'teacher2@test.gr', '$2y$10$lrdtn9WnMprxgn0RAj4j/eCQSJtBgo4d.rzrDbMlFOeoNgV3K8HFy', 0, 'PROF2025', '2025-12-11 13:45:47'),
(28, 'Student2', 'student2@test.gr', '$2y$10$YwcAu/A5uWrZs.nnaik5Vec7PuLQTNhOwEHQt4S1apqQj80rhWh4e', 1, 'STUD2025', '2025-12-16 20:24:58'),
(30, 'Student3', 'student3@test.gr', '$2y$10$oqQcK.ZR7DTDMprBbx3c/ewr6Ecxnb.c4b4V0MgSbBO7Aaku/uU2i', 1, 'STUD2025', '2025-12-19 14:57:16');

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Ευρετήρια για πίνακα `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD KEY `professor_id` (`professor_id`);

--
-- Ευρετήρια για πίνακα `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`submission_id`),
  ADD KEY `assignments_id` (`assignments_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Ευρετήρια για πίνακα `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`topic_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Ευρετήρια για πίνακα `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `assignments`
--
ALTER TABLE `assignments`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT για πίνακα `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT για πίνακα `submissions`
--
ALTER TABLE `submissions`
  MODIFY `submission_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT για πίνακα `topics`
--
ALTER TABLE `topics`
  MODIFY `topic_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT για πίνακα `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Περιορισμοί για άχρηστους πίνακες
--

--
-- Περιορισμοί για πίνακα `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Περιορισμοί για πίνακα `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`professor_id`) REFERENCES `users` (`user_id`);

--
-- Περιορισμοί για πίνακα `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`assignments_id`) REFERENCES `assignments` (`assignment_id`),
  ADD CONSTRAINT `submissions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Περιορισμοί για πίνακα `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
