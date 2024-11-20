-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 02, 2024 at 03:27 PM
-- Server version: 8.0.35
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Journey_to_discovery`
--

-- --------------------------------------------------------

--
-- Table structure for table `Comment`
--

CREATE TABLE `Comment` (
  `placeID` int NOT NULL,
  `userID` int NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Comment`
--

INSERT INTO `Comment` (`placeID`, `userID`, `comment`) VALUES
(111, 111, 'Very Lovely!'),
(222, 222, 'Wow, Amazing!'),
(333, 333, 'Breathtaking!!'),
(111, 222, 'Beautiful place.'),
(444, 444, 'Can\'t wait to visit this place.'),
(445, 333, 'Views must be absolutely stunning.'),
(447, 111, 'What a beautiful lake.'),
(446, 222, 'This place look historical and interesting.'),
(448, 222, 'This looks so fun ^^'),
(450, 333, 'So peaceful and refreshing!'),
(449, 222, 'Incredible views, amazing architecture!');

-- --------------------------------------------------------

--
-- Table structure for table `Country`
--

CREATE TABLE `Country` (
  `id` int NOT NULL,
  `country` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Country`
--

INSERT INTO `Country` (`id`, `country`) VALUES
(111, 'Japan'),
(222, 'Saudi Arabia'),
(333, 'France'),
(444, 'USA');

-- --------------------------------------------------------

--
-- Table structure for table `Like`
--

CREATE TABLE `Like` (
  `placeID` int NOT NULL,
  `userID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Like`
--

INSERT INTO `Like` (`placeID`, `userID`) VALUES
(111, 111),
(222, 222),
(333, 333),
(111, 333),
(111, 222),
(444, 222),
(444, 333),
(445, 444),
(447, 444),
(446, 111),
(447, 222),
(448, 111),
(448, 444),
(448, 222),
(450, 111),
(449, 222),
(449, 333);

-- --------------------------------------------------------

--
-- Table structure for table `Place`
--

CREATE TABLE `Place` (
  `id` int NOT NULL,
  `travelID` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `location` text COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `photoFileName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Place`
--

INSERT INTO `Place` (`id`, `travelID`, `name`, `location`, `description`, `photoFileName`) VALUES
(111, 111, 'Fuji Mountain', 'Honshu, Japan', 'A visit to Mount Fuji offers breathtaking views of Japan\'s iconic peak, rising majestically above the clouds. Surrounded by serene lakes and lush forests, it\'s a peaceful retreat for both adventure seekers and nature lovers.', 'images/mountain.jpg'),
(222, 222, 'AlUla', 'AlUla, Saudi Arabia', 'A visit to AlUla unveils ancient sandstone cliffs, Nabatean tombs, and desert oases, blending rich history with breathtaking landscapes.', 'Alula.jpeg'),
(333, 333, 'Eiffel Tower', 'Paris, France', 'A visit to the Eiffel Tower offers stunning panoramic views of Paris from its iconic iron lattice structure. Day or night, it’s a symbol of romance and architectural wonder, drawing visitors from around the world.', 'Eiffel.jpg'),
(444, 383, 'Central Park', 'New York, Manhattan', 'Central Park is a vast, iconic urban park in New York City, with lush landscapes, scenic paths, lakes, and landmarks, offering a peaceful retreat in the heart of Manhattan.', 'images/Park.jpg'),
(445, 383, 'The Emoire State Building', 'New York', 'The Empire State Building is a world-famous, 102-story skyscraper in New York City, renowned for its Art Deco design and breathtaking views from its observation decks.', 'images/Empire.jpeg'),
(446, 384, 'Old Town', 'Nice', 'is a colorful nook of a neighborhood enjoyed by both travelers and locals alike for its narrow shop-lined streets and atmosphere.', 'images/Nice.webp'),
(447, 384, 'Lake Annecy', 'Annecy', 'Lake Annecy is one of the largest lakes in France; and it is one of the most attractive lakes in Europe', 'images/lake.jpeg'),
(448, 385, 'Tokyo Disneyland', 'Tokyo', 'Tokyo Disneyland is a magical theme park in Japan inspired by classic Disney characters and stories, featuring thrilling rides, parades, and immersive attractions.', 'images/Disneyland.png'),
(449, 386, 'Kingdom Tower', 'Riyadh', 'Kingdom Tower, is a striking skyscraper in Riyadh, Saudi Arabia, known for its unique architectural design, sky bridge, and panoramic city views.', 'images/kingdom.jpeg'),
(450, 386, 'The Beach', 'Al-Khobar', 'Al Khobar Beach is a serene coastal destination in Saudi Arabia, featuring sandy shores, clear waters, and scenic views of the Arabian Gulf, ideal for relaxation and family outings', 'images/beach.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `Travel`
--

CREATE TABLE `Travel` (
  `id` int NOT NULL,
  `userID` int NOT NULL,
  `month` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `year` int NOT NULL,
  `countryID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Travel`
--

INSERT INTO `Travel` (`id`, `userID`, `month`, `year`, `countryID`) VALUES
(111, 111, 'January', 2024, 111),
(222, 222, 'November', 2022, 222),
(333, 333, 'May', 2023, 333),
(383, 111, 'June', 2022, 444),
(384, 222, 'October', 2023, 333),
(385, 333, 'May', 2024, 111),
(386, 444, 'January', 2024, 222);

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `id` int NOT NULL,
  `firstName` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `lastName` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `emailAddress` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `photoFileName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`id`, `firstName`, `lastName`, `emailAddress`, `password`, `photoFileName`) VALUES
(111, 'Mohammed', 'Saleh', 'Msaleh@gmail.com', '$2a$12$CiE7W65G3W4I0.fmrHl8qOvv.svJ7Rv/nHPRrvqvjqBMTL.uJCco6', 'User_photo1'),
(222, 'Sarah', 'Ahmad', 'Sahmad@gmail.com', '$2a$12$XoRpRXGl.veRFf8N7YTbWuTUq4qQc3vGQ8ubxEdDZTglH93pMF.RW', 'User_photo2'),
(333, 'Eman', 'Saleh', 'Esaleh@gmail.com', '$2a$12$/KX8jXZtFYvro0ExRaWMi.fHuyTmVhzQGXJoTeaR4H2eWnY2KZHc2', 'User_photo3'),
(444, 'Lama', 'Saad', 'Lsaad@gmail.com', '$2a$12$XjKH8OzHiy./tCglBM/nGe0d.CHXLISgI0h2H2Fi.R8YD0HpC.Hn2', 'defultphoto.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Comment`
--
ALTER TABLE `Comment`
  ADD KEY `placeID` (`placeID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `Country`
--
ALTER TABLE `Country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Like`
--
ALTER TABLE `Like`
  ADD KEY `placeID` (`placeID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `Place`
--
ALTER TABLE `Place`
  ADD PRIMARY KEY (`id`),
  ADD KEY `travelID` (`travelID`);

--
-- Indexes for table `Travel`
--
ALTER TABLE `Travel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userID` (`userID`),
  ADD KEY `countryID` (`countryID`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Country`
--
ALTER TABLE `Country`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=445;

--
-- AUTO_INCREMENT for table `Place`
--
ALTER TABLE `Place`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=451;

--
-- AUTO_INCREMENT for table `Travel`
--
ALTER TABLE `Travel`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=387;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=445;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Comment`
--
ALTER TABLE `Comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`placeID`) REFERENCES `Place` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `User` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `Like`
--
ALTER TABLE `Like`
  ADD CONSTRAINT `like_ibfk_1` FOREIGN KEY (`placeID`) REFERENCES `Place` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `like_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `User` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `Place`
--
ALTER TABLE `Place`
  ADD CONSTRAINT `place_ibfk_1` FOREIGN KEY (`travelID`) REFERENCES `Travel` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `Travel`
--
ALTER TABLE `Travel`
  ADD CONSTRAINT `travel_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `User` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `travel_ibfk_2` FOREIGN KEY (`countryID`) REFERENCES `Country` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
