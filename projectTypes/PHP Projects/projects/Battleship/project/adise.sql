-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Φιλοξενητής: 127.0.0.1:3306
-- Χρόνος δημιουργίας: 14 Ιαν 2020 στις 19:34:16
-- Έκδοση διακομιστή: 5.7.26
-- Έκδοση PHP: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `adise`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `games`
--

DROP TABLE IF EXISTS `games`;
CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameName` tinytext NOT NULL,
  `gameAdminId` int(11) NOT NULL,
  `invitedPlayersIds` text NOT NULL,
  `gameStarted` tinyint(1) NOT NULL,
  `timeStarted` date NOT NULL,
  `boardColumns` int(11) NOT NULL,
  `boardRows` int(11) NOT NULL,
  `boat2` int(11) NOT NULL,
  `boat3` int(11) NOT NULL,
  `boat4` int(11) NOT NULL,
  `boat5` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=146 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userName` tinytext NOT NULL,
  `pass` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Άδειασμα δεδομένων του πίνακα `users`
--

INSERT INTO `users` (`id`, `userName`, `pass`) VALUES
(1, 'a', '$2y$10$2/JNSZTxThfiVD0Y8BcN..3Wo1vd5ZXEYcuOM6dEfRyZqepsEJbh6'),
(2, 'b', '$2y$10$3Iv8yjzCrg7kHvVJbhARfenXiRw0hix8hBma2lsnzNy96PqTXmNdi'),
(3, 'c', '$2y$10$R9KQ3bIBo3oEYbDIjJcruuyRsoU7G2nVUcrxOJkRCV9NLH3tWu8He');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
