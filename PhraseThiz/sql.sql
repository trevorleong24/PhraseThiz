-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 14, 2024 at 08:16 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phrasethiz`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

DROP TABLE IF EXISTS `administrator`;
CREATE TABLE IF NOT EXISTS `administrator` (
  `admin_id` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`admin_id`, `username`, `password`, `email`) VALUES
('A01', 'admin', 'Admin123', 'A01@mail.apu.edu.my');

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

DROP TABLE IF EXISTS `answer`;
CREATE TABLE IF NOT EXISTS `answer` (
  `answer_id` varchar(50) NOT NULL,
  `answer_text` varchar(1000) NOT NULL,
  `is_correct` tinyint NOT NULL,
  `question_id` varchar(50) NOT NULL,
  PRIMARY KEY (`answer_id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`answer_id`, `answer_text`, `is_correct`, `question_id`) VALUES
('A01', 'She doesn’t work here anymore.', 1, 'Q01'),
('A02', 'goes', 1, 'Q02'),
('A03', 'I had seen the movie before.', 1, 'Q03'),
('A04', 'in', 1, 'Q04'),
('A05', 'She don’t know him.', 1, 'Q05'),
('A06', 'an', 1, 'Q06'),
('A07', 'will have completed', 1, 'Q07'),
('A08', 'The children ate the cake.', 1, 'Q08'),
('A09', 'saw', 1, 'Q09'),
('A10', 'until', 1, 'Q10'),
('A11', 'Cheerful', 1, 'Q11'),
('A12', 'Hardworking', 1, 'Q12'),
('A13', 'Expand', 1, 'Q13'),
('A14', 'Delicate', 1, 'Q14'),
('A15', 'gift', 1, 'Q15'),
('A16', '1', 1, 'Q16');

-- --------------------------------------------------------

--
-- Table structure for table `instructor`
--

DROP TABLE IF EXISTS `instructor`;
CREATE TABLE IF NOT EXISTS `instructor` (
  `instructor_id` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `institution` varchar(255) NOT NULL,
  `experience` varchar(255) NOT NULL,
  `certificate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `admin_id` varchar(50) NOT NULL,
  PRIMARY KEY (`instructor_id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `instructor`
--

INSERT INTO `instructor` (`instructor_id`, `username`, `password`, `email`, `dob`, `institution`, `experience`, `certificate`, `admin_id`) VALUES
('I01', 'imran', 'Apu123', 'I01@mail.apu.edu.my', '2024-12-09', 'APU', '10 years', 'English', 'A01'),
('I02', 'ariq', '$2y$10$U912WZ2N0yJqBpjYzGjSPeVxGCIQAeTW2Waja5t.FaITdLjNEilze', 'aqilaizat@gmail.com', '2024-03-07', 'APU', '8 years', 'Computer Science', 'A01');

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
CREATE TABLE IF NOT EXISTS `question` (
  `question_id` varchar(50) NOT NULL,
  `question_text` varchar(1000) NOT NULL,
  `Option 1` varchar(255) NOT NULL,
  `Option 2` varchar(255) NOT NULL,
  `Option 3` varchar(255) NOT NULL,
  `Option 4` varchar(255) NOT NULL,
  `quiz_id` varchar(50) NOT NULL,
  PRIMARY KEY (`question_id`),
  KEY `quiz_id` (`quiz_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`question_id`, `question_text`, `Option 1`, `Option 2`, `Option 3`, `Option 4`, `quiz_id`) VALUES
('Q01', 'Choose the correct sentence:', 'She don’t work here anymore.', 'She doesn’t work here anymore.', 'She doesn’t works here anymore.', 'She do not work here anymore.', 'QZ01'),
('Q02', '\"He ___ to the gym every day.\"', 'go', 'goes', 'going', 'gone', 'QZ01'),
('Q03', 'Which sentence uses the past perfect tense correctly?', 'I had saw the movie before.', 'I saw the movie before.', 'I had seen the movie before.', 'I seen the movie before.', 'QZ01'),
('Q04', 'They are interested ___ learning new skills.', 'of', 'on', 'in', 'for', 'QZ01'),
('Q05', 'Identify the incorrect sentence:', 'She don’t know him.', 'She doesn’t know him.', 'She knew him.', 'She hasn’t met him before.', 'QZ01'),
('Q06', 'He bought ___ orange from the market.', 'a', 'an', 'the', 'no article', 'QZ01'),
('Q07', 'By this time next year, she ___ her degree.', 'completes', 'completed', 'will have completed', 'completing', 'QZ01'),
('Q08', 'Identify the sentence in the active voice:', 'The cake was eaten by the children.', 'The children ate the cake.', 'The cake is being eaten.', 'The cake will be eaten.', 'QZ01'),
('Q09', 'I ___ him yesterday at the mall.', 'seen', 'see', 'saw', 'seeing', 'QZ01'),
('Q10', 'I will stay here ___ it starts raining.', 'if', 'because', 'unless', 'until', 'QZ01'),
('Q11', 'What is the synonym of “happy”?', 'Sad', 'Cheerful', 'Angry', 'Anxious', 'QZ02'),
('Q12', 'Which word is closest in meaning to “diligent”?', 'Lazy', 'Hardworking', 'Careless', 'Reckless', 'QZ02'),
('Q13', 'What is the opposite of “diminish”?', 'Decrease', 'Expand', 'Shrink', 'Reduce', 'QZ02'),
('Q14', 'What is the meaning of the word “fragile”?', 'Strong', 'Delicate', 'Sturdy', 'Resilient', 'QZ02'),
('Q15', 'She has a ___ for learning languages.', 'gift', 'burden', 'flaw', 'doubt', 'QZ02'),
('Q16', 'Hi', '1', '2', '3', '4', 'QZ01');

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

DROP TABLE IF EXISTS `quiz`;
CREATE TABLE IF NOT EXISTS `quiz` (
  `quiz_id` varchar(50) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `instructor_id` varchar(50) NOT NULL,
  PRIMARY KEY (`quiz_id`),
  KEY `instructor_id` (`instructor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`quiz_id`, `title`, `description`, `instructor_id`) VALUES
('QZ01', 'Grammar Quiz', 'Test your grammar skills with our interactive quiz! This grammar-focused test includes multiple-choice and fill-in-the-blank questions to help you improve your accuracy. Unlike full language assessments, this quiz is specifically designed to target grammar proficiency, so there won’t be any certificates provided.', 'I01'),
('QZ02', 'Vocabulary Quiz', 'Test your vocabulary skills with our interactive quiz! This vocabulary-focused test includes multiple-choice and fill-in-the-blank questions to help you expand and improve your word knowledge. Unlike full language assessments, this quiz is specifically designed to target vocabulary proficiency, so there won’t be any certificates provided.', 'I01');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `student_id` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `admin_id` varchar(50) NOT NULL,
  PRIMARY KEY (`student_id`) USING BTREE,
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `username`, `password`, `email`, `dob`, `admin_id`) VALUES
('S01', 'taro', '$2y$10$YUPcedvqkvS6vtYz8Np8RuDiVH3VyFWipYQx5GTslC3MyWIBlYFoK', 'aqilaizat67169@gmail.com', '2024-02-08', 'A01');

-- --------------------------------------------------------

--
-- Table structure for table `studentquiz`
--

DROP TABLE IF EXISTS `studentquiz`;
CREATE TABLE IF NOT EXISTS `studentquiz` (
  `student_quiz_id` varchar(50) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `quiz_id` varchar(50) NOT NULL,
  `date_taken` date NOT NULL,
  `score` int NOT NULL,
  PRIMARY KEY (`student_quiz_id`),
  KEY `student_id` (`student_id`),
  KEY `quiz_id` (`quiz_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `studentquiz`
--

INSERT INTO `studentquiz` (`student_quiz_id`, `student_id`, `quiz_id`, `date_taken`, `score`) VALUES
('SQ01', 'S01', 'QZ01', '2024-12-14', 36),
('SQ02', 'S01', 'QZ01', '2024-12-14', 9),
('SQ03', 'S01', 'QZ01', '2024-12-14', 45),
('SQ04', 'S01', 'QZ02', '2024-12-14', 40),
('SQ05', 'S01', 'QZ02', '2024-12-14', 100),
('SQ06', 'S01', 'QZ01', '2024-12-14', 45),
('SQ07', 'S01', 'QZ01', '2024-12-14', 91),
('SQ08', 'S01', 'QZ01', '2024-12-14', 55),
('SQ09', 'S01', 'QZ01', '2024-12-14', 9),
('SQ10', 'S01', 'QZ02', '2024-12-14', 20),
('SQ11', 'S01', 'QZ02', '2024-12-14', 0),
('SQ12', 'S01', 'QZ02', '2024-12-14', 80),
('SQ13', 'S01', 'QZ01', '2024-12-14', 82),
('SQ14', 'S01', 'QZ02', '2024-12-14', 20);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `instructor`
--
ALTER TABLE `instructor`
  ADD CONSTRAINT `instructor_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `administrator` (`admin_id`) ON DELETE CASCADE;

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`quiz_id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz`
--
ALTER TABLE `quiz`
  ADD CONSTRAINT `quiz_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructor` (`instructor_id`) ON DELETE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `administrator` (`admin_id`) ON DELETE CASCADE;

--
-- Constraints for table `studentquiz`
--
ALTER TABLE `studentquiz`
  ADD CONSTRAINT `studentquiz_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `studentquiz_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`quiz_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
