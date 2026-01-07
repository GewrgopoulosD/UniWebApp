-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Εξυπηρετητής: localhost
-- Χρόνος δημιουργίας: 07 Ιαν 2026 στις 12:51:22
-- Έκδοση διακομιστή: 10.4.28-MariaDB
-- Έκδοση PHP: 8.2.4

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

-- -------------------------------------------------------
--
-- Δομή πίνακα για τον πίνακα `assignments`
--

drop database if exists schooldb;
create database schooldb;
use schooldb;


CREATE TABLE `assignments` (
  `assignment_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `assignment_title` varchar(255) NOT NULL,
  `assignment_description` text DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `assignments`
--

INSERT INTO `assignments` (`assignment_id`, `course_id`, `assignment_title`, `assignment_description`, `deadline`, `created_at`) VALUES
(12, 1, 'Σύστημα Διαχείρισης Κρατήσεων για Καταδυτικό Κέντρο', 'Να αναπτυχθεί μια web εφαρμογή με χρήση PHP & MySQL, η οποία επιτρέπει τη διαχείριση κρατήσεων πελατών για ένα καταδυτικό κέντρο.\r\n\r\n-Τεχνολογίες\r\nPHP\r\nMySQL\r\nHTML / CSS\r\n(Προαιρετικά) JavaScript\r\nApache (XAMPP ή WAMP).\r\n\r\n-Περιγραφή Συστήματος\r\nΗ εφαρμογή πρέπει να υποστηρίζει δύο ρόλους:\r\nΧρήστης (Client)\r\nΔιαχειριστής (Admin)', '2026-04-01', '2026-01-02 21:21:32'),
(13, 5, 'Διαχείριση Βιβλιοθήκης (Library Management System)', 'Να αναπτυχθεί μια κονσόλα/desktop εφαρμογή σε Java, η οποία επιτρέπει τη διαχείριση βιβλίων, χρηστών και δανεισμών σε μια βιβλιοθήκη.\r\n\r\n-Τεχνολογίες\r\nJava SE \r\nEclipse ή IntelliJ IDEA\r\n(Προαιρετικά) SQLite ή MySQL για αποθήκευση δεδομένων.\r\n\r\n-Λειτουργίες Χρήστη\r\nΕγγραφή & Σύνδεση\r\nΑναζήτηση βιβλίων (κατά τίτλο ή συγγραφέα)\r\nΔανεισμός βιβλίου\r\nΠροβολή των δανεισμένων βιβλίων του.\r\n\r\n-Λειτουργίες Διαχειριστή\r\nΠροσθήκη / Επεξεργασία / Διαγραφή βιβλίων\r\nΠροβολή όλων των δανεισμών\r\nΈγκριση ή απόρριψη αιτήματος δανεισμού\r\nΔιαχείριση χρηστών (προαιρετικά).', '2026-05-01', '2026-01-02 21:30:41'),
(15, 1, 'test1', 'dasdasd', '2026-01-15', '2026-01-07 12:38:23'),
(16, 1, 'test2', 'rewrew', '2026-01-29', '2026-01-07 12:38:38'),
(17, 1, 'test3', 'asdasda', '2026-01-30', '2026-01-07 12:38:50'),
(18, 1, 'test4', 'dasdasd', '2026-01-17', '2026-01-07 12:38:58');

--
-- Δείκτες `assignments`
--
DELIMITER $$
CREATE TRIGGER `check_teacher_before_insert_assignment` BEFORE INSERT ON `assignments` FOR EACH ROW BEGIN
    DECLARE teacherRole INT;

    
    SELECT role_id
    INTO teacherRole
    FROM users
    WHERE user_id = (SELECT professor_id FROM courses WHERE course_id = NEW.course_id);

   
    IF teacherRole != 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Only teachers can create assignments!';
    END IF;
END
$$
DELIMITER ;

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
(11, 'JavaScript-Programming', 24, '2025-12-22 13:22:11');

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
  `assignment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `submitted_at` datetime DEFAULT current_timestamp(),
  `grade` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `submissions`
--

INSERT INTO `submissions` (`submission_id`, `assignment_id`, `user_id`, `file_path`, `submitted_at`, `grade`) VALUES
(1, 12, 25, NULL, '2026-01-04 14:26:58', 6.90),
(5, 13, 25, '25', '2026-01-05 16:44:47', 4.00);

--
-- Δείκτες `submissions`
--
DELIMITER $$
CREATE TRIGGER `check_student_before_insert_submission` BEFORE INSERT ON `submissions` FOR EACH ROW BEGIN
    DECLARE userRole INT;

    -- Παίρνουμε το role_id του χρήστη που προσπαθεί να υποβάλει
    SELECT role_id
    INTO userRole
    FROM users
    WHERE user_id = NEW.user_id;

    -- Αν δεν είναι φοιτητής (role_id != 1) τότε ακυρώνουμε
    IF userRole != 1 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Only students can submit assignments!';
    END IF;
END
$$
DELIMITER ;

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

--
-- Άδειασμα δεδομένων του πίνακα `topics`
--

INSERT INTO `topics` (`topic_id`, `course_id`, `topic_title`, `resource_url`) VALUES
(1, 1, 'PHP Introduction', 'https://www.w3schools.com/php/php_intro.asp'),
(3, 1, 'PHP Installation', 'https://www.w3schools.com/php/php_install.asp'),
(5, 1, 'PHP Syntax', 'https://www.w3schools.com/php/php_syntax.asp'),
(8, 5, 'Java Introduction', 'https://www.w3schools.com/java/java_intro.asp'),
(9, 11, 'JavaScript Introduction', 'https://www.w3schools.com/js/js_intro.asp');

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
(24, 'Teacher1', 'teacher1@test.gr', '$2y$10$ZtLTXrk2zBVhFEfyUKAxI.8rsdsydHoJxI2QuAxPNMGiWaJqg/MpW', 0, 'PROF2025', '2025-11-24 07:29:37'),
(25, 'Student1', 'student1@test.gr', '$2y$10$xKQpPRbJkl7mcky/6wkTeeBQwGh.jAsqQytIem/areONGsvRJVmn6', 1, 'STUD2025', '2025-11-24 07:30:53'),
(27, 'Teacher2', 'teacher2@test.gr', '$2y$10$lrdtn9WnMprxgn0RAj4j/eCQSJtBgo4d.rzrDbMlFOeoNgV3K8HFy', 0, 'PROF2025', '2025-12-11 11:45:47'),
(28, 'Student2', 'student2@test.gr', '$2y$10$YwcAu/A5uWrZs.nnaik5Vec7PuLQTNhOwEHQt4S1apqQj80rhWh4e', 1, 'STUD2025', '2025-12-16 18:24:58'),
(30, 'Student3', 'student3@test.gr', '$2y$10$oqQcK.ZR7DTDMprBbx3c/ewr6Ecxnb.c4b4V0MgSbBO7Aaku/uU2i', 1, 'STUD2025', '2025-12-19 12:57:16');

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `fk_assignments_course` (`course_id`);

--
-- Ευρετήρια για πίνακα `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Ευρετήρια για πίνακα `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`submission_id`),
  ADD KEY `fk_submission_assignment` (`assignment_id`),
  ADD KEY `fk_submission_user` (`user_id`);

--
-- Ευρετήρια για πίνακα `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`topic_id`),
  ADD KEY `fk_course` (`course_id`);

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
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT για πίνακα `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT για πίνακα `submissions`
--
ALTER TABLE `submissions`
  MODIFY `submission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT για πίνακα `topics`
--
ALTER TABLE `topics`
  MODIFY `topic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT για πίνακα `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Περιορισμοί για άχρηστους πίνακες
--

--
-- Περιορισμοί για πίνακα `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `fk_assignments_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `fk_submission_assignment` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`assignment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_submission_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `fk_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
