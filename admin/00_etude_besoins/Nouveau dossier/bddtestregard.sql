-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour testregards
CREATE DATABASE IF NOT EXISTS `testregards` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `testregards`;

-- Listage de la structure de table testregards. client
CREATE TABLE IF NOT EXISTS `client` (
  `id_client` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_client`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table testregards.client : ~6 rows (environ)
REPLACE INTO `client` (`id_client`, `nom`) VALUES
	(1, 'Marion'),
	(2, 'Jules'),
	(3, 'Robert'),
	(4, 'David'),
	(5, 'Mona'),
	(6, 'anna');

-- Listage de la structure de table testregards. expo
CREATE TABLE IF NOT EXISTS `expo` (
  `id_expo` int NOT NULL AUTO_INCREMENT,
  `expoDate` date NOT NULL,
  `expoTitle` varchar(50) NOT NULL,
  `expoDescription` varchar(255) NOT NULL,
  PRIMARY KEY (`id_expo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table testregards.expo : ~6 rows (environ)
REPLACE INTO `expo` (`id_expo`, `expoDate`, `expoTitle`, `expoDescription`) VALUES
	(1, '2025-02-03', 'Expo 1', 'Expo description 1'),
	(2, '2025-03-03', 'Expo 2', 'Expo description 2'),
	(3, '2025-04-03', 'Expo 3', 'Expo description 3'),
	(4, '2025-05-03', 'Expo 4', 'Expo description 4'),
	(5, '2025-06-03', 'Expo 5', 'Expo description 5'),
	(6, '2025-07-03', 'Expo 6', 'Expo description 6');

-- Listage de la structure de table testregards. order_
CREATE TABLE IF NOT EXISTS `order_` (
  `id_order` int NOT NULL AUTO_INCREMENT,
  `orderDate` date NOT NULL,
  `id_client` int NOT NULL,
  PRIMARY KEY (`id_order`),
  KEY `FK_order__client` (`id_client`),
  CONSTRAINT `FK_order__client` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table testregards.order_ : ~6 rows (environ)
REPLACE INTO `order_` (`id_order`, `orderDate`, `id_client`) VALUES
	(1, '2025-02-03', 6),
	(2, '2025-02-01', 5),
	(3, '2025-01-02', 4),
	(4, '2025-02-03', 2),
	(5, '2025-02-03', 1),
	(6, '2025-02-03', 3);

-- Listage de la structure de table testregards. order_details
CREATE TABLE IF NOT EXISTS `order_details` (
  `id_order` int NOT NULL,
  `id_ticket` int NOT NULL,
  `id_expo` int NOT NULL,
  `quantity` int NOT NULL,
  `unitPrice` decimal(15,0) NOT NULL DEFAULT '0',
  KEY `id_order` (`id_order`),
  KEY `id_ticket` (`id_ticket`),
  KEY `id_expo` (`id_expo`),
  CONSTRAINT `FK_order_details_expo` FOREIGN KEY (`id_expo`) REFERENCES `expo` (`id_expo`),
  CONSTRAINT `FK_order_details_order_` FOREIGN KEY (`id_order`) REFERENCES `order_` (`id_order`),
  CONSTRAINT `FK_order_details_ticket` FOREIGN KEY (`id_ticket`) REFERENCES `ticket` (`id_ticket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table testregards.order_details : ~2 rows (environ)
REPLACE INTO `order_details` (`id_order`, `id_ticket`, `id_expo`, `quantity`, `unitPrice`) VALUES
	(1, 1, 1, 2, 10),
	(2, 2, 2, 4, 5);

-- Listage de la structure de table testregards. ticket
CREATE TABLE IF NOT EXISTS `ticket` (
  `id_ticket` int NOT NULL AUTO_INCREMENT,
  `ticketType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_ticket`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table testregards.ticket : ~2 rows (environ)
REPLACE INTO `ticket` (`id_ticket`, `ticketType`) VALUES
	(1, 'Adulte'),
	(2, 'Enfant');

-- Listage de la structure de table testregards. ticket_pricing
CREATE TABLE IF NOT EXISTS `ticket_pricing` (
  `id_expo` int NOT NULL,
  `id_ticket` int NOT NULL,
  `standardPrice` int NOT NULL,
  KEY `id_ticket` (`id_ticket`),
  KEY `id_expo` (`id_expo`),
  KEY `FK_ticket_pricing_ticket` (`id_ticket`),
  CONSTRAINT `FK_ticket_pricing_expo` FOREIGN KEY (`id_expo`) REFERENCES `expo` (`id_expo`),
  CONSTRAINT `FK_ticket_pricing_ticket` FOREIGN KEY (`id_ticket`) REFERENCES `ticket` (`id_ticket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table testregards.ticket_pricing : ~6 rows (environ)
REPLACE INTO `ticket_pricing` (`id_expo`, `id_ticket`, `standardPrice`) VALUES
	(1, 1, 10),
	(1, 2, 5),
	(2, 1, 15),
	(2, 2, 8),
	(3, 1, 10),
	(3, 2, 5);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
