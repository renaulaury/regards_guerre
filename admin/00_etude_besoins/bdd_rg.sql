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


-- Listage de la structure de la base pour regardsguerre
CREATE DATABASE IF NOT EXISTS `regardsguerre` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `regardsguerre`;

-- Listage de la structure de table regardsguerre. artiste
CREATE TABLE IF NOT EXISTS `artiste` (
  `id` int NOT NULL AUTO_INCREMENT,
  `artist_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `artist_firstname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `artist_birth_date` date NOT NULL,
  `artist_death_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.artiste : ~4 rows (environ)
REPLACE INTO `artiste` (`id`, `artist_name`, `artist_firstname`, `artist_birth_date`, `artist_death_date`) VALUES
	(1, 'Garrigue', 'Anna', '1931-03-14', '1966-01-06'),
	(2, 'Moribon', 'Jean', '1925-09-13', '2018-11-03'),
	(3, 'Khaman', 'Mohammed', '1930-03-14', '1991-05-04'),
	(4, 'Amine', 'Yasmina', '1975-05-20', '2025-02-06');

-- Listage de la structure de table regardsguerre. comment
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `comment_text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_comment_creation` datetime NOT NULL,
  `exhibition_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526CA76ED395` (`user_id`),
  KEY `IDX_9474526C2A7D4494` (`exhibition_id`),
  CONSTRAINT `FK_9474526C2A7D4494` FOREIGN KEY (`exhibition_id`) REFERENCES `exhibition` (`id`),
  CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.comment : ~0 rows (environ)

-- Listage de la structure de table regardsguerre. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Listage des données de la table regardsguerre.doctrine_migration_versions : ~1 rows (environ)
REPLACE INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20250205123741', '2025-02-06 07:54:31', 557);

-- Listage de la structure de table regardsguerre. exhibition
CREATE TABLE IF NOT EXISTS `exhibition` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `title_exhibit` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `main_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_war_begin` date NOT NULL,
  `date_war_end` date NOT NULL,
  `date_exhibit` date NOT NULL,
  `hour_begin` time NOT NULL,
  `hour_end` time NOT NULL,
  `description_exhibit` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B8353389A76ED395` (`user_id`),
  CONSTRAINT `FK_B8353389A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.exhibition : ~4 rows (environ)
REPLACE INTO `exhibition` (`id`, `user_id`, `title_exhibit`, `main_image`, `date_war_begin`, `date_war_end`, `date_exhibit`, `hour_begin`, `hour_end`, `description_exhibit`) VALUES
	(1, 2, 'Les camps d\'Algérie', 'public/events/20250509_algerie/00_main_image.webp', '1954-11-01', '1962-03-19', '2025-05-09', '09:00:00', '16:00:00', 'Les camps d\'Algérie, créés pendant la guerre d\'indépendance, sont devenus des symboles de l\'exil, de la souffrance et du déracinement, où les conditions de vie des populations déplacées étaient marquées par l\'humiliation et l\'abandon.'),
	(2, 2, 'L\'invasion russe de l\'Ukraine', 'f', '2022-02-24', '2025-02-06', '2025-06-03', '09:00:00', '16:00:00', 'La guerre russo-ukrainienne a commencé en 2014 avec l\'annexion de la Crimée et s\'est transformée en une invasion totale de l\'Ukraine par la Russie le 24 février 2022. Ce conflit a provoqué des milliers de victimes, des déplacements massifs et une crise géopolitique majeure en Europe.'),
	(3, 2, 'Les femmes palestiniennes et leur engagement', 'f', '1987-12-09', '1993-09-13', '2025-11-15', '09:00:00', '16:00:00', 'Les femmes palestiniennes jouent un rôle central dans ce conflit, que ce soit comme mères, militantes, journalistes, soignantes ou résistantes. elles s’engagent activement, que ce soit à travers des mouvements de résistance, des actions humanitaires ou des témoignages dénonçant les souffrances du peuple palestinien. Beaucoup deviennent des symboles de résilience.'),
	(4, 2, 'L\'incident de Kyujo', 'f', '1945-08-14', '1945-08-15', '2025-01-12', '09:00:00', '16:00:00', 'Llorsque des officiers de l’armée impériale japonaise ont tenté un coup d’État pour empêcher l’empereur Hirohito d’annoncer la reddition du Japon à la fin de la Seconde Guerre mondiale. Leur tentative a échoué, et le message de capitulation a été diffusé le matin du 15 août 1945, mettant officiellement fin au conflit.');

-- Listage de la structure de table regardsguerre. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.messenger_messages : ~0 rows (environ)

-- Listage de la structure de table regardsguerre. order
CREATE TABLE IF NOT EXISTS `order` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `order_date_creation` date NOT NULL,
  `order_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F5299398A76ED395` (`user_id`),
  CONSTRAINT `FK_F5299398A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.order : ~0 rows (environ)

-- Listage de la structure de table regardsguerre. order_detail
CREATE TABLE IF NOT EXISTS `order_detail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order__id` int DEFAULT NULL,
  `exhibition_id` int DEFAULT NULL,
  `ticket_id` int DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_ED896F46251A8A50` (`order__id`),
  KEY `IDX_ED896F462A7D4494` (`exhibition_id`),
  KEY `IDX_ED896F46700047D2` (`ticket_id`),
  CONSTRAINT `FK_ED896F46251A8A50` FOREIGN KEY (`order__id`) REFERENCES `order` (`id`),
  CONSTRAINT `FK_ED896F462A7D4494` FOREIGN KEY (`exhibition_id`) REFERENCES `exhibition` (`id`),
  CONSTRAINT `FK_ED896F46700047D2` FOREIGN KEY (`ticket_id`) REFERENCES `ticket` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.order_detail : ~0 rows (environ)

-- Listage de la structure de table regardsguerre. room
CREATE TABLE IF NOT EXISTS `room` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title_room` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.room : ~5 rows (environ)
REPLACE INTO `room` (`id`, `title_room`) VALUES
	(1, 'Nicolas'),
	(2, 'Joséphine'),
	(3, 'Elise'),
	(4, 'Sabandra'),
	(5, 'Luciano');

-- Listage de la structure de table regardsguerre. show
CREATE TABLE IF NOT EXISTS `show` (
  `id` int NOT NULL AUTO_INCREMENT,
  `room_id` int DEFAULT NULL,
  `artiste_id` int DEFAULT NULL,
  `exhibition_id` int DEFAULT NULL,
  `artist_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `artist_job` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `artist_bio` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `artist_text_art` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_320ED90154177093` (`room_id`),
  KEY `IDX_320ED90121D25844` (`artiste_id`),
  KEY `IDX_320ED9012A7D4494` (`exhibition_id`),
  CONSTRAINT `FK_320ED90121D25844` FOREIGN KEY (`artiste_id`) REFERENCES `artiste` (`id`),
  CONSTRAINT `FK_320ED9012A7D4494` FOREIGN KEY (`exhibition_id`) REFERENCES `exhibition` (`id`),
  CONSTRAINT `FK_320ED90154177093` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.show : ~4 rows (environ)
REPLACE INTO `show` (`id`, `room_id`, `artiste_id`, `exhibition_id`, `artist_photo`, `artist_job`, `artist_bio`, `artist_text_art`) VALUES
	(1, 1, 1, 1, 'public/events/20250509_algerie/01_artiste1.webp', 'poétesse', 'Née en Algérie, Anna Garrigue était une militante et poétesse engagée dans la lutte pour l’indépendance de l’Algérie. Emprisonnée pendant la guerre, elle a écrit des poèmes marqués par la douleur de l\'exil et le combat pour la liberté.', '\nLe travail poétique d\'Anna Garrigue est profondément marqué par son engagement politique et son amour pour l’Algérie, qu’elle décrit à travers une écriture intense et émotive. Ses poèmes, souvent chargés de nostalgie et de résistance, abordent des thèmes de lutte, de mémoire et de réconciliation, avec une voix féminine forte et poignante.'),
	(2, 4, 2, 1, 'public/events/20250509_algerie/02_artiste2.webp', 'photographe', 'Jean Moribon était un photographe suisse humaniste, reconnu pour ses reportages poignants sur les conflits et les crises sociales, notamment la guerre d\'Algérie.', '\nSon travail se distingue par une approche profondément humaniste, où il capte les souffrances et les émotions des civils dans des situations de guerre, notamment pendant la guerre d\'Algérie. Ses photographies vont au-delà de l’image de la violence, en mettant l\'accent sur la dignité et la résilience des personnes confrontées à des conditions extrêmes, offrant ainsi un témoignage puissant de leur réalité.'),
	(3, 5, 3, 1, 'public/events/20250509_algerie/03_artiste3.webp', 'peintre', 'Mohamed Khadda était un peintre algérien majeur, connu pour sa contribution à l\'art contemporain algérien et son engagement dans la représentation de la décolonisation et de l\'identité post-coloniale. ', 'Influencé par le cubisme et le surréalisme, il abordait les souffrances de la guerre d\'Algérie et l\'impact de l\'exil, utilisant des formes géométriques et des couleurs puissantes pour symboliser la fracture et la reconstruction de l\'Algérie  tout en cherchant à réconcilier les mémoires et à reconstruire visuellement l’âme du pays.'),
	(4, 2, 4, 1, 'public/events/20250509_algerie/04_artiste4.webp', 'réalisatrice', 'Yasmina Amine est une réalisatrice franco-algérienne, connue pour son exploration de la guerre d\'Algérie et de ses mémoires à travers des documentaires.', 'Elle se concentre particulièrement sur les récits souvent oubliés des harkis, comme dans son documentaire "La fin des Harkis", qui donne une voix aux témoins de cette histoire silencieuse. Elle utilise le cinéma pour questionner la mémoire collective, le traumatisme de l\'exil et la réconciliation entre les différentes communautés liées au conflit.');

-- Listage de la structure de table regardsguerre. ticket
CREATE TABLE IF NOT EXISTS `ticket` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title_ticket` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.ticket : ~2 rows (environ)
REPLACE INTO `ticket` (`id`, `title_ticket`) VALUES
	(1, 'Enfant'),
	(2, 'Adulte');

-- Listage de la structure de table regardsguerre. ticket_pricing
CREATE TABLE IF NOT EXISTS `ticket_pricing` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ticket_id` int DEFAULT NULL,
  `exhibition_id` int DEFAULT NULL,
  `standard_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E93DF561700047D2` (`ticket_id`),
  KEY `IDX_E93DF5612A7D4494` (`exhibition_id`),
  CONSTRAINT `FK_E93DF5612A7D4494` FOREIGN KEY (`exhibition_id`) REFERENCES `exhibition` (`id`),
  CONSTRAINT `FK_E93DF561700047D2` FOREIGN KEY (`ticket_id`) REFERENCES `ticket` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.ticket_pricing : ~2 rows (environ)
REPLACE INTO `ticket_pricing` (`id`, `ticket_id`, `exhibition_id`, `standard_price`) VALUES
	(1, 2, 1, 10.00),
	(2, 1, 1, 8.00);

-- Listage de la structure de table regardsguerre. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_nickname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_USER_EMAIL` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.user : ~2 rows (environ)
REPLACE INTO `user` (`id`, `user_email`, `password`, `user_nickname`, `role`) VALUES
	(1, 'root@regardsguerre.fr', '1234', 'root', 'root'),
	(2, 'l.dupont@regardsguerre.fr', '1234', 'ldupont', 'admin');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
