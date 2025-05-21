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

-- Listage de la structure de table regardsguerre. artist
CREATE TABLE IF NOT EXISTS `artist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `artist_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `artist_firstname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `artist_birth_date` date DEFAULT NULL,
  `artist_death_date` date DEFAULT NULL,
  `artist_job` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `artist_bio` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_anonymized` tinyint(1) NOT NULL,
  `anonymize_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1599687989D9B62` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.artist : ~19 rows (environ)
REPLACE INTO `artist` (`id`, `artist_name`, `artist_firstname`, `artist_birth_date`, `artist_death_date`, `artist_job`, `artist_bio`, `slug`, `is_anonymized`, `anonymize_at`) VALUES
	(4, 'Moribon', 'Jean', '1925-09-13', '2018-11-03', 'Photographe', 'Photographe suisse humaniste, reconnu pour ses reportages poignants sur les conflits et les crises sociales, notamment la guerre d\'Algérie.', '4-jean-moribon', 0, NULL),
	(5, 'Khaman', 'Mohammed', '1930-03-14', '1991-05-04', 'Peintre', 'Peintre algérien majeur, connu pour sa contribution à l\'art contemporain algérien et son engagement dans la représentation de la décolonisation et de l\'identité post-coloniale. ', '5-mohammed-khaman', 0, NULL),
	(6, 'Amine', 'Yasmina', '1975-05-20', NULL, 'Réalisatrice', 'Réalisatrice franco-algérienne, connue pour son exploration de la guerre d\'Algérie et de ses mémoires à travers des documentaires.', '6-1975-05-20-yasmina-amine', 0, NULL),
	(20, 'Takeda', 'Hiroshi', '1957-06-03', NULL, 'Calligraphe', 'Né à Kyoto, il a grandi dans une famille d’artisans spécialisés dans la fabrication de kimonos traditionnels. Passionné par l’histoire et l’esthétique zen, il a voyagé à travers le Japon pour étudier l’art de la calligraphie et la peinture sur soie.', '20-hiroshi-takeda', 0, NULL),
	(22, 'Nemura', 'Kévin', '1975-12-08', NULL, 'Sculteur', 'Issu d’une famille de pêcheurs d’Hokkaido, il a toujours été fasciné par la transformation des matières naturelles. Il a étudié l’art du bois et du métal à Osaka avant de se spécialiser dans la sculpture monumentale.', '22-kevin-nemura', 0, NULL),
	(23, 'Shimizu', 'Miyako', '1921-07-15', '2015-03-02', 'Peintre', 'Née avant la Seconde Guerre mondiale, elle a traversé les bouleversements du XXe siècle, ce qui a profondément marqué son œuvre. Fervente pacifiste, elle s’est engagée dans des associations pour la mémoire des bombardements d’Hiroshima et Nagasaki.', '23-miyako-shimizu', 0, NULL),
	(24, 'Ivanenko', 'Oleh', '1965-11-25', NULL, 'Graveur', 'Né à Kharkiv, il a grandi en plein cœur de l’effondrement de l’URSS, ce qui a influencé son regard critique sur l’histoire et l’identité ukrainienne.', '24-oleh-ivanenko', 0, NULL),
	(25, 'Svitlana', 'Drach', '1992-04-30', NULL, 'Poétesse, Performeuse', 'Originaire de Lviv, elle s’est fait connaître pour ses performances engagées lors des manifestations de l’Euromaidan en 2014.', '25-drach-svitlana', 0, NULL),
	(26, 'Mazepa', 'Yuriy', '1978-09-07', '2022-03-15', 'Photographe', 'Ancien journaliste, il s’est spécialisé dans la photographie de conflit. Il a couvert la guerre du Donbass et a perdu la vie en documentant l’invasion de 2022.', '26-yuriy-mazepa', 0, NULL),
	(27, 'Melnyk', 'Anastasiya', '1986-01-18', NULL, 'Céramiste', 'Après avoir fui le Donetsk en 2014, elle s’est installée à Kyiv, où elle a développé un art inspiré par la fragilité et la reconstruction.', '27-anastasiya-melnyk', 0, NULL),
	(28, 'DeShawn', 'Carter', '1990-05-14', NULL, 'Graffeur', 'Né à Chicago, il a été confronté dès son enfance aux violences policières et aux inégalités raciales.', '28-carter-deshawn', 0, NULL),
	(29, 'Johnson', 'Amara', '1982-02-27', NULL, 'Danseuse, Chorégraphe', 'Originaire de la Nouvelle-Orléans, elle a étudié la danse classique avant de se tourner vers une expression plus libre inspirée du hip-hop et de la danse africaine.', '29-amara-johnson', 0, NULL),
	(30, 'Rivers', 'Malcom', '1974-08-02', NULL, 'Sculteur', 'Né à Atlanta, il a grandi dans une famille impliquée dans les mouvements pour les droits civiques. Très jeune, il a été sensibilisé aux luttes pour l’égalité et la reconnaissance des cultures afro-américaines.', '30-malcom-rivers', 0, NULL),
	(31, 'Brooks', 'Nathaniel', '1892-03-29', '1892-10-07', 'Illustrateur, Peintre', 'Issu d’une famille de fermiers du Kentucky, il a d’abord été illustrateur de presse avant de se consacrer à la peinture après la guerre de Sécession.', '31-nathaniel-brooks', 0, NULL),
	(32, 'Doyle', 'Kate', '1838-11-10', '1919-06-22', 'Photographe', 'Fille d’un imprimeur de Boston, elle a été l’une des rares femmes photographes à couvrir les conséquences de la guerre.', '32-kate-doyle', 0, NULL),
	(33, 'Wainwright', 'Jebediah', '1829-09-05', '1888-01-14', 'Graveur, Caricaturiste', 'Ancien soldat confédéré ayant changé de camp après la bataille de Gettysburg, il est devenu célèbre pour ses gravures satiriques dénonçant l’absurdité de la guerre.', '33-jebediah-wainwright', 0, NULL),
	(34, 'Monroe', 'Ezekiel', '1842-07-20', '1864-12-02', 'Musicien', 'Né dans une famille d’esclaves affranchis en Virginie, il a appris le violon en cachette avant de s’engager comme musicien dans l’armée de l’Union.', '34-ezekiel-monroe', 0, NULL),
	(38, 'Garrigue', 'Anna', '1931-03-14', '1966-01-06', 'Poétesse', 'Née en Algérie, elle était une militante et poétesse engagée dans la lutte pour l’indépendance de l’Algérie. Emprisonnée pendant la guerre, elle a écrit des poèmes marqués par la douleur de l\'exil et le combat pour la liberté.', '38-anna-garrigue', 0, '2025-04-26 00:00:00'),
	(41, 'Dupignon', 'Robert', '2021-02-10', NULL, 'Sculteur', '', '41-robert-dupignon', 1, NULL);

-- Listage de la structure de table regardsguerre. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Listage des données de la table regardsguerre.doctrine_migration_versions : ~1 rows (environ)
REPLACE INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20250212135443', '2025-02-12 13:57:46', 962);

-- Listage de la structure de table regardsguerre. exhibition
CREATE TABLE IF NOT EXISTS `exhibition` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `title_exhibit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `main_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `main_image_alt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_war_begin` date NOT NULL,
  `date_war_end` date DEFAULT NULL,
  `date_exhibit` date NOT NULL COMMENT '(DC2Type:date_immutable)',
  `hour_begin` time NOT NULL,
  `hour_end` time NOT NULL,
  `description_exhibit` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hook_exhibit` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle_exhibit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock_max` int NOT NULL,
  `stock_alert` int NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B8353389989D9B62` (`slug`),
  KEY `IDX_B8353389A76ED395` (`user_id`),
  CONSTRAINT `FK_B8353389A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.exhibition : ~5 rows (environ)
REPLACE INTO `exhibition` (`id`, `user_id`, `title_exhibit`, `main_image`, `main_image_alt`, `date_war_begin`, `date_war_end`, `date_exhibit`, `hour_begin`, `hour_end`, `description_exhibit`, `hook_exhibit`, `subtitle_exhibit`, `stock_max`, `stock_alert`, `slug`) VALUES
	(1, 17, 'Les camps d\'Algérie', '/images/events/20250509/00_main_image.webp', 'Photo du déplacement entre les camps pendant la guerre d\'Algérie.', '1954-11-01', '1962-03-19', '2025-05-25', '09:00:00', '16:00:00', 'Ces camps devenus des symboles de l\'exil, \r\nde la souffrance et du déracinement, où les conditions de vie des populations déplacées étaient marquées par l\'humiliation et l\'abandon.', 'Sujet sensible encore aujourd’hui, ils font désormais partie d\'une mémoire collective partagée entre les Algériens et les Français.', 'Lieux de souffrance et de résistance', 150, 10, '09052025-les-camps-d-algerie'),
	(3, 17, 'Les femmes palestiniennes et leur engagement', '/images/events/20250902/00_main_image.webp', 'Troupes de femmes militaires palestiniennes', '1987-12-09', '1993-09-13', '2025-11-15', '09:00:00', '16:00:00', 'Les femmes palestiniennes jouent un rôle central dans ce conflit, que ce soit comme mères, \r\nmilitantes, journalistes, soignantes ou résistantes. \r\nBeaucoup deviennent des symboles de résilience.', 'Les femmes palestiniennes, actrices essentielles du conflit, s\'engagent activement à travers divers rôles, devenant des symboles de résilience face aux souffrances de leur peuple.', 'Mères, militantes, symboles de courage : les femmes palestiniennes face à l\'adversité', 150, 10, '15112025-les-femmes-palestiniennes-et-leur-engagement'),
	(4, 20, 'L\'incident de Kyujo', '/images/events/20250812/00_main_image.webp', 'Photo d\'un accord passé dans le bureau principal', '1945-08-14', '1945-08-15', '2025-08-12', '09:00:00', '16:00:00', 'Des officiers de l’armée impériale japonaise ont tenté un coup d’État pour empêcher d’annoncer la reddition du Japon à la fin de la Seconde Guerre mondiale. \r\nLeur tentative a échoué, et le matin du 15 août 1945 le conflit était officiellement fini.\r\n', 'Des officiers japonais ont tenté un coup d\'État raté pour empêcher la reddition du Japon, mais l\'annonce de la capitulation a été diffusée le 15 août 1945, mettant fin à la Seconde Guerre mondiale.', 'Le coup d\'État avorté de l\'armée impériale', 150, 10, '12082025-l-incident-de-kyujo'),
	(23, 34, 'L’exode afro-américaine', '/images/events/20250913/00_main_image.webp', 'Photo d\'une famille fuyant la ségrégation', '1916-01-01', '1920-12-31', '2025-09-13', '09:00:00', '16:00:00', 'Cette migration a transformé le paysage démographique et culturel des États-Unis, contribuant à l\'essor des communautés afro-américaines urbaines et influençant profondément la musique, la littérature et la politique américaines.', 'Conflit déterminant dans l\'histoire des États-Unis, aboutissant à l\'abolition de l\'esclavage et à la préservation de l\'unité nationale.', 'Conflit pour la liberté', 150, 10, '13092025-l-exode-afro-americaine'),
	(25, 34, 'L\'Ukraine en résistance', '/images/events/20250603/00_main_image.webp', 'Photo de l\'oeuvre de Bansky sur un mur en Ukraine ou une petite fille marche sur des chars tenant fièrement son drapeau', '2022-02-24', NULL, '2025-06-03', '09:00:00', '16:00:00', 'La guerre russo-ukrainienne a commencé en 2014 et s\'est transformée en une invasion totale. \r\nCe conflit a provoqué des milliers de victimes et une crise géopolitique majeure en Europe.', 'L\'invasion russe a déclenché un conflit majeur en Europe, bouleversant l\'équilibre géopolitique et provoquant une crise humanitaire de grande ampleur.', 'Chronique d\'une guerre : l\'Ukraine au cœur d\'une crise européenne', 150, 10, '03062025-l-Ukraine-en-resistance');

-- Listage de la structure de table regardsguerre. invoice
CREATE TABLE IF NOT EXISTS `invoice` (
  `id` int NOT NULL AUTO_INCREMENT,
  `number_invoice` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_firstname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_total` decimal(15,2) DEFAULT NULL,
  `date_invoice` date NOT NULL,
  `invoice_details` json DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_90651744E7F723D1` (`number_invoice`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.invoice : ~6 rows (environ)
REPLACE INTO `invoice` (`id`, `number_invoice`, `customer_name`, `customer_firstname`, `customer_email`, `order_total`, `date_invoice`, `invoice_details`, `slug`) VALUES
	(78, '20250521-221', 'Norbert', 'Bertno', 'cramoisi@gmail.com', 1052.00, '2025-05-21', '[{"quantity": 66, "ticketTitle": "Adulte", "standardPrice": "10.00", "exhibitionTitle": "Les camps d\'Algérie"}, {"quantity": 49, "ticketTitle": "Enfant", "standardPrice": "8.00", "exhibitionTitle": "Les camps d\'Algérie"}, {"quantity": 28, "ticketTitle": "Enfant -6ans", "standardPrice": "0.00", "exhibitionTitle": "Les camps d\'Algérie"}]', '31-Norbert-Bertno'),
	(79, '20250521-222', 'Norbert', 'Bertno', 'cramoisi@gmail.com', 1008.00, '2025-05-21', '[{"quantity": 68, "ticketTitle": "Adulte", "standardPrice": "10.00", "exhibitionTitle": "L\'incident de Kyujo"}, {"quantity": 41, "ticketTitle": "Enfant", "standardPrice": "8.00", "exhibitionTitle": "L\'incident de Kyujo"}, {"quantity": 41, "ticketTitle": "Enfant -6ans", "standardPrice": "0.00", "exhibitionTitle": "L\'incident de Kyujo"}]', '31-Norbert-Bertno'),
	(80, '20250521-223', 'Lily', 'Renau', 'lily@gmail.com', 30.00, '2025-05-21', '[{"quantity": 3, "ticketTitle": "Adulte", "standardPrice": "10.00", "exhibitionTitle": "L\'Ukraine en résistance"}, {"quantity": 1, "ticketTitle": "Enfant -6ans", "standardPrice": "0.00", "exhibitionTitle": "L\'Ukraine en résistance"}]', '29-Lily-Renau'),
	(81, '20250521-224', 'Lily', 'Renau', 'lily@gmail.com', 64.00, '2025-05-21', '[{"quantity": 4, "ticketTitle": "Adulte", "standardPrice": "10.00", "exhibitionTitle": "Les femmes palestiniennes et leur engagement"}, {"quantity": 3, "ticketTitle": "Enfant", "standardPrice": "8.00", "exhibitionTitle": "Les femmes palestiniennes et leur engagement"}, {"quantity": 3, "ticketTitle": "Enfant -6ans", "standardPrice": "0.00", "exhibitionTitle": "Les femmes palestiniennes et leur engagement"}]', '29-Lily-Renau'),
	(82, '20250521-225', 'Julien', 'Vautier', 'lily@gmail.com', 56.00, '2025-05-21', '[{"quantity": 4, "ticketTitle": "Adulte", "standardPrice": "10.00", "exhibitionTitle": "L’exode afro-américaine"}, {"quantity": 2, "ticketTitle": "Enfant", "standardPrice": "8.00", "exhibitionTitle": "L’exode afro-américaine"}, {"quantity": 2, "ticketTitle": "Enfant -6ans", "standardPrice": "0.00", "exhibitionTitle": "L’exode afro-américaine"}]', '29-Julien-Vautier'),
	(83, '20250521-226', 'Lily', 'fff', 'cramoisi@gmail.com', 30.00, '2025-05-21', '[{"quantity": 3, "ticketTitle": "Adulte", "standardPrice": "10.00", "exhibitionTitle": "L\'Ukraine en résistance"}]', '31-Lily-fff');

-- Listage de la structure de table regardsguerre. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `customer_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_firstname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_date_creation` date NOT NULL,
  `order_status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_invoice` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_total` decimal(17,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F5299398E7F723D1` (`number_invoice`),
  KEY `IDX_F5299398A76ED395` (`user_id`),
  CONSTRAINT `FK_F5299398A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=227 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.order : ~6 rows (environ)
REPLACE INTO `order` (`id`, `user_id`, `customer_name`, `customer_firstname`, `order_date_creation`, `order_status`, `customer_email`, `number_invoice`, `order_total`) VALUES
	(221, 31, 'Norbert', 'Bertno', '2025-05-21', 'Envoyé', 'cramoisi@gmail.com', '20250521-221', 1052.00),
	(222, 31, 'Norbert', 'Bertno', '2025-05-21', 'Envoyé', 'cramoisi@gmail.com', '20250521-222', 1008.00),
	(223, 29, 'Lily', 'Renau', '2025-05-21', 'Envoyé', 'lily@gmail.com', '20250521-223', 30.00),
	(224, 29, 'Lily', 'Renau', '2025-05-21', 'Envoyé', 'lily@gmail.com', '20250521-224', 64.00),
	(225, 29, 'Julien', 'Vautier', '2025-05-21', 'Envoyé', 'lily@gmail.com', '20250521-225', 56.00),
	(226, 31, 'Lily', 'fff', '2025-05-21', 'Envoyé', 'cramoisi@gmail.com', '20250521-226', 30.00);

-- Listage de la structure de table regardsguerre. order_detail
CREATE TABLE IF NOT EXISTS `order_detail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order__id` int DEFAULT NULL,
  `exhibition_id` int DEFAULT NULL,
  `ticket_id` int DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_ED896F462A7D4494` (`exhibition_id`),
  KEY `IDX_ED896F46700047D2` (`ticket_id`),
  KEY `IDX_ED896F46251A8A50` (`order__id`) USING BTREE,
  CONSTRAINT `FK_ED896F46251A8A50` FOREIGN KEY (`order__id`) REFERENCES `order` (`id`),
  CONSTRAINT `FK_ED896F462A7D4494` FOREIGN KEY (`exhibition_id`) REFERENCES `exhibition` (`id`),
  CONSTRAINT `FK_ED896F46700047D2` FOREIGN KEY (`ticket_id`) REFERENCES `ticket` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=354 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.order_detail : ~15 rows (environ)
REPLACE INTO `order_detail` (`id`, `order__id`, `exhibition_id`, `ticket_id`, `unit_price`, `quantity`) VALUES
	(339, 221, 1, 1, 10.00, 66),
	(340, 221, 1, 2, 8.00, 49),
	(341, 221, 1, 3, 0.00, 28),
	(342, 222, 4, 1, 10.00, 68),
	(343, 222, 4, 2, 8.00, 41),
	(344, 222, 4, 3, 0.00, 41),
	(345, 223, 25, 1, 10.00, 3),
	(346, 223, 25, 3, 0.00, 1),
	(347, 224, 3, 1, 10.00, 4),
	(348, 224, 3, 2, 8.00, 3),
	(349, 224, 3, 3, 0.00, 3),
	(350, 225, 23, 1, 10.00, 4),
	(351, 225, 23, 2, 8.00, 2),
	(352, 225, 23, 3, 0.00, 2),
	(353, 226, 25, 1, 10.00, 3);

-- Listage de la structure de table regardsguerre. reset_password_request
CREATE TABLE IF NOT EXISTS `reset_password_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `selector` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_7CE748AA76ED395` (`user_id`),
  CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.reset_password_request : ~10 rows (environ)
REPLACE INTO `reset_password_request` (`id`, `user_id`, `selector`, `hashed_token`, `requested_at`, `expires_at`) VALUES
	(16, 34, 'Ms0VZGx5nkwYgG0jn9IF', 'aEGRL4IBILsX71KYESsoXJJeoMcYjIIyBxhzGSM+GyM=', '2025-03-14 09:48:53', '2025-03-14 10:03:53'),
	(17, 34, 'fvhrkwVoEtmqFDZbb8kE', 'PTwfgy5RiXpY/rkXlrnR9aYJdpuEZ35Y4LxR+USWXDw=', '2025-03-14 09:54:17', '2025-03-14 10:09:17'),
	(18, 20, 'CyqQWoaDHnUKWe8wxhG2', '+enNbB/ArTpQQEUiDEjzCnGpB5aeWp3E5hlu0Ki1Gvc=', '2025-03-14 10:08:03', '2025-03-14 10:23:03'),
	(19, 34, 'rY55bOTvTn4LtJQkbIlm', 'ifmVPjlNSBLM0EDEWs7sxsbJ8g4P3HpsGneE9pzv6gI=', '2025-03-14 12:03:16', '2025-03-14 12:18:16'),
	(20, 34, 'fn7UBgO8TUEs65QVIFsM', 'pimBKzhGdWR5fPc/eUL++yJAQ/9aUvMQhBAH6pKR1O0=', '2025-03-14 12:30:57', '2025-03-14 12:45:57'),
	(21, 34, 'ACbRhrsFdvSubPLG2PTZ', '0/5rIoAg/JHfgl0FUGiAMzgEr3q9xWmwS3mWK5AYAK4=', '2025-03-14 12:36:31', '2025-03-14 12:51:31'),
	(22, 20, 'ZtgCjRXNtoFmPKfTc3oI', 'qHpnZXbGRAMDP8eGXUyN/hC3sKDceCF4BtI/uQROpCg=', '2025-03-14 12:39:32', '2025-03-14 12:54:32'),
	(23, 26, '6UBlHugrRYIBORLIaIc6', 'UnAF3pUT39RsnSv3T3p6thmdLCXi4+XxTF0WplU1Soo=', '2025-03-14 12:42:22', '2025-03-14 12:57:22'),
	(24, 30, 'Q5kBiP6B5nggzxPytsGp', 'Sst0nuOjOiqMvv3RLu8rDPC3ztzr1QAnYtHCHWZ1x/c=', '2025-03-14 12:43:53', '2025-03-14 12:58:53'),
	(25, 17, 'aVyGuiXCV0m8lJrcfQod', 'LgVaPE60PfI0KkHeBSii8UUTOCFKEuDNhV4DTIlNMCI=', '2025-03-14 12:46:18', '2025-03-14 13:01:18');

-- Listage de la structure de table regardsguerre. room
CREATE TABLE IF NOT EXISTS `room` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title_room` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.room : ~4 rows (environ)
REPLACE INTO `room` (`id`, `title_room`) VALUES
	(1, 'Elise'),
	(2, 'Joséphine'),
	(4, 'Sabandra'),
	(5, 'Nicolas');

-- Listage de la structure de table regardsguerre. show
CREATE TABLE IF NOT EXISTS `show` (
  `id` int NOT NULL AUTO_INCREMENT,
  `room_id` int DEFAULT NULL,
  `exhibition_id` int DEFAULT NULL,
  `artist_id` int DEFAULT NULL,
  `artist_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `artist_photo_alt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `artist_text_art` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_320ED90154177093` (`room_id`),
  KEY `IDX_320ED9012A7D4494` (`exhibition_id`),
  KEY `IDX_320ED901B7970CF8` (`artist_id`),
  CONSTRAINT `FK_320ED9012A7D4494` FOREIGN KEY (`exhibition_id`) REFERENCES `exhibition` (`id`),
  CONSTRAINT `FK_320ED90154177093` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`),
  CONSTRAINT `FK_320ED901B7970CF8` FOREIGN KEY (`artist_id`) REFERENCES `artist` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.show : ~4 rows (environ)
REPLACE INTO `show` (`id`, `room_id`, `exhibition_id`, `artist_id`, `artist_photo`, `artist_photo_alt`, `artist_text_art`) VALUES
	(3, 2, 1, 6, '/images/events/20250509/Amine_Yasmina.webp', 'Affiche du court métrage "Les guerrières d\'Algérie"', 'Elle se concentre particulièrement sur les récits souvent oubliés des harkis, comme dans son documentaire "La fin des Harkis", qui donne une voix aux témoins de cette histoire silencieuse. Elle utilise le cinéma pour questionner la mémoire collective, le traumatisme de l\'exil et la réconciliation entre les différentes communautés liées au conflit.'),
	(4, 4, 1, 4, '/images/events/20250509/Moribon_Jean.webp', 'Jeune femme prise en photo', 'Son travail se distingue par une approche profondément humaniste, où il capte les souffrances et les émotions des civils dans des situations de guerre, notamment pendant la guerre d\'Algérie. Ses photographies vont au-delà de l’image de la violence, en mettant l\'accent sur la dignité et la résilience des personnes confrontées à des conditions extrêmes, offrant ainsi un témoignage puissant de leur réalité.'),
	(22, 5, 1, 5, '/images/events/20250509/Khaman_Mohammed.webp', 'Oeuvre peinturale abstraite', 'Influencé par le cubisme et le surréalisme, il abordait les souffrances de la guerre d\'Algérie et l\'impact de l\'exil, utilisant des formes géométriques et des couleurs puissantes pour symboliser la fracture et la reconstruction de l\'Algérie  tout en cherchant à réconcilier les mémoires et à reconstruire visuellement l’âme du pays.'),
	(53, 1, 1, 38, '/images/events/20250509/Garrigue_Anna.webp', 'Image des poèmes de l\'artiste', 'Son travail poétique est profondément marqué par son engagement politique et son amour pour l’Algérie, qu’elle décrit à travers une écriture intense et émotive. Ses poèmes, souvent chargés de nostalgie et de résistance, abordent des thèmes de lutte, de mémoire et de réconciliation, avec une voix féminine forte et poignante.');

-- Listage de la structure de table regardsguerre. ticket
CREATE TABLE IF NOT EXISTS `ticket` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title_ticket` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_ticket` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_ticket_alt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_97A0ADA3989D9B62` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.ticket : ~3 rows (environ)
REPLACE INTO `ticket` (`id`, `title_ticket`, `image_ticket`, `image_ticket_alt`, `slug`) VALUES
	(1, 'Adulte', '/images/tickets/ticket_adult.webp', 'Image du ticket adulte', 'adulte'),
	(2, 'Enfant', '/images/tickets/ticket_enfant.webp', 'Image du ticket enfant', 'enfant'),
	(3, 'Enfant -6ans', '/images/tickets/ticket_J_enfant.webp', 'Image du ticket jeune enfant', 'enfant-6ans');

-- Listage de la structure de table regardsguerre. ticket_pricing
CREATE TABLE IF NOT EXISTS `ticket_pricing` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ticket_id` int NOT NULL,
  `exhibition_id` int NOT NULL,
  `standard_price` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E93DF561700047D2` (`ticket_id`),
  KEY `IDX_E93DF5612A7D4494` (`exhibition_id`),
  CONSTRAINT `FK_E93DF5612A7D4494` FOREIGN KEY (`exhibition_id`) REFERENCES `exhibition` (`id`),
  CONSTRAINT `FK_E93DF561700047D2` FOREIGN KEY (`ticket_id`) REFERENCES `ticket` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.ticket_pricing : ~15 rows (environ)
REPLACE INTO `ticket_pricing` (`id`, `ticket_id`, `exhibition_id`, `standard_price`) VALUES
	(1, 1, 1, 10.00),
	(2, 2, 1, 8.00),
	(3, 3, 1, 0.00),
	(4, 1, 25, 10.00),
	(5, 2, 25, 8.00),
	(6, 3, 25, 0.00),
	(7, 1, 3, 10.00),
	(8, 2, 3, 8.00),
	(9, 3, 3, 0.00),
	(10, 1, 4, 10.00),
	(11, 2, 4, 8.00),
	(12, 3, 4, 0.00),
	(13, 1, 23, 10.00),
	(14, 2, 23, 8.00),
	(15, 3, 23, 0.00);

-- Listage de la structure de table regardsguerre. type
CREATE TABLE IF NOT EXISTS `type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ticket_id` int NOT NULL,
  `title_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8CDE5729700047D2` (`ticket_id`),
  CONSTRAINT `FK_8CDE5729700047D2` FOREIGN KEY (`ticket_id`) REFERENCES `ticket` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.type : ~3 rows (environ)
REPLACE INTO `type` (`id`, `ticket_id`, `title_type`) VALUES
	(1, 2, 'Ticket'),
	(2, 1, 'Ticket'),
	(3, 3, 'Ticket');

-- Listage de la structure de table regardsguerre. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_firstname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_USER_EMAIL` (`user_email`),
  UNIQUE KEY `UNIQ_8D93D649989D9B62` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.user : ~10 rows (environ)
REPLACE INTO `user` (`id`, `user_email`, `user_name`, `user_firstname`, `password`, `roles`, `slug`) VALUES
	(17, 'l.renau@regardsguerre.fr', NULL, NULL, '$2y$13$1/2dfTzLs1bO7LiabfFzOebgy8aghEfdHxG4xOIpZnkABzdS/s7Su', '["ROLE_ADMIN"]', '17-laury-renau'),
	(19, 'm.murmann@regardsguerre.fr', NULL, NULL, '$2y$13$4TvTKYXjIjtU3fNipC5SO.Xrb7bFH.k7e/4w/4afTKXXLbO5WMDZq', '["ROLE_ADMIN"]', '19-micka-murrmann'),
	(20, 'y.ruffo@regardsguerre.fr', NULL, NULL, '$2y$13$MzopDX0AJsIOKRzQ5Fomze1Xkpp7jzgnBdrbStTVQ8ig9sTn634JG', '["ROLE_ADMIN"]', '20-yofer-ruffo'),
	(26, 'r.root@regardsguerre.fr', NULL, NULL, '$2y$13$jlt8xiVmva0v/lKHHGw64eRcC4JWflNi9A0l/KcmWbL1t2pquIXae', '["ROLE_ROOT"]', '26-r-root'),
	(29, 'lily@gmail.com', NULL, NULL, '$2y$13$mtcVAofqugOSCWycO9obzuQt789V.JE6Q15Fja8lMQdLyUQ7MW4Eq', '["ROLE_USER"]', '29-lily'),
	(30, 'maxOly@gmail.com', 'Maxine', 'Olympe', '$2y$13$/ACNqc7g7xa6XQMp5T6RJ.JRVO4CkIDMoK.LJFjvzpNeRFwrrbvn2', '["ROLE_USER"]', '30-maxOly'),
	(31, 'cramoisi@gmail.com', NULL, NULL, '$2y$13$fcu18YDp6kYuhETi1H27i.6rVgfxNOVqmRo676vwxJxLu92Y3Rqaq', '["ROLE_USER"]', '31-cramoisi'),
	(34, 'a.dupont@regardsguerre.fr', NULL, NULL, '$2y$13$nsmVPEUMPNESCxz5C1GdAOLGeRL5QJ.S0ago9qbls/VdThj.6CMrK', '["ROLE_ADMIN"]', '34-artus-dupont'),
	(42, 'lisouu@gmail.com', NULL, NULL, '$2y$13$ivWfE9ZRUHDG8t.QVVq9ouMg6hDN0GIojMKd.xv2/rRJtNQkcvKNO', '["ROLE_USER"]', '42-lisouuu'),
	(50, 'marouan@gmail.com', 'Lou', 'Foque', '$2y$13$v1Krtvy015VD9IR0BKJ1NuS/s/i5hof3EVXzbfLXQzecnZ1mMjTTK', '["ROLE_USER"]', '80-foque-lou');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
