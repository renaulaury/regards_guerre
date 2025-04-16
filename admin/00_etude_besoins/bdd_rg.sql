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
  `artist_birth_date` date NOT NULL,
  `artist_death_date` date DEFAULT NULL,
  `artist_job` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `artist_bio` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1599687989D9B62` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.artist : ~19 rows (environ)
REPLACE INTO `artist` (`id`, `artist_name`, `artist_firstname`, `artist_birth_date`, `artist_death_date`, `artist_job`, `artist_bio`, `slug`) VALUES
	(3, 'Garrigue', 'Anna', '1931-03-14', '1966-01-06', 'Poétesse', 'Née en Algérie, Anna Garrigue était une militante et poétesse engagée dans la lutte pour l’indépendance de l’Algérie. Emprisonnée pendant la guerre, elle a écrit des poèmes marqués par la douleur de l\'exil et le combat pour la liberté.', '14031931-anna-garrigue'),
	(4, 'Moribon', 'Jean', '1925-09-13', '2018-11-03', 'Photographe', 'Jean Moribon était un photographe suisse humaniste, reconnu pour ses reportages poignants sur les conflits et les crises sociales, notamment la guerre d\'Algérie.', '13091925-jean-moribon'),
	(5, 'Khaman', 'Mohammed', '1930-03-14', '1991-05-04', 'Peintre', 'Mohamed Khadda était un peintre algérien majeur, connu pour sa contribution à l\'art contemporain algérien et son engagement dans la représentation de la décolonisation et de l\'identité post-coloniale. ', '14031930-mohammed-khaman'),
	(6, 'Amine', 'Yasmina', '1975-05-20', NULL, 'Réalisatrice', 'Yasmina Amine est une réalisatrice franco-algérienne, connue pour son exploration de la guerre d\'Algérie et de ses mémoires à travers des documentaires.', '20051975yasmina-amine'),
	(20, 'Takeda', 'Hiroshi', '1957-06-03', NULL, 'Calligraphe', 'Né à Kyoto, Hiroshi Takeda a grandi dans une famille d’artisans spécialisés dans la fabrication de kimonos traditionnels. Passionné par l’histoire et l’esthétique zen, il a voyagé à travers le Japon pour étudier l’art de la calligraphie et la peinture sur soie.', '03061957-hiroshi-takeda'),
	(21, 'Morita', 'Aïko', '1983-09-12', NULL, 'Photographe, Vidéaste', 'Diplômée d’une école d’art de Tokyo, Aiko Morita a rapidement trouvé sa place dans le monde du photojournalisme avant de se tourner vers une approche plus contemplative de l’image.', '12091983-aiko-morita'),
	(22, 'Nemura', 'Kévin', '1975-12-08', NULL, 'Sculteur', 'Issu d’une famille de pêcheurs d’Hokkaido, Kenji Nakamura a toujours été fasciné par la transformation des matières naturelles. Il a étudié l’art du bois et du métal à Osaka avant de se spécialiser dans la sculpture monumentale.', '08121975-kevin-nemura'),
	(23, 'Shimizu', 'Miyako', '1921-07-15', '2015-03-02', 'Peintre', 'Née avant la Seconde Guerre mondiale, Miyako Shimizu a traversé les bouleversements du XXe siècle, ce qui a profondément marqué son œuvre. Fervente pacifiste, elle s’est engagée dans des associations pour la mémoire des bombardements d’Hiroshima et Nagasaki.', '15071921-miyako-shimizu'),
	(24, 'Ivanenko', 'Oleh', '1965-11-25', NULL, 'Graveur', 'Né à Kharkiv, Oleh Ivanenko a grandi en plein cœur de l’effondrement de l’URSS, ce qui a influencé son regard critique sur l’histoire et l’identité ukrainienne.', '25111965-oleh-ivanenko'),
	(25, 'Svitlana', 'Drach', '1992-04-30', NULL, 'Poétesse, Performeuse', 'Originaire de Lviv, Svitlana Drach s’est fait connaître pour ses performances engagées lors des manifestations de l’Euromaidan en 2014.', '30041992-drach-svitlana'),
	(26, 'Mazepa', 'Yuriy', '1978-09-07', '2022-03-15', 'Photographe', 'Ancien journaliste, Yuriy Mazepa s’est spécialisé dans la photographie de conflit. Il a couvert la guerre du Donbass et a perdu la vie en documentant l’invasion de 2022.', '07091978-yuriy-mazepa'),
	(27, 'Melnyk', 'Anastasiya', '1986-01-18', NULL, 'Céramiste', 'Après avoir fui le Donetsk en 2014, Anastasiya Melnyk s’est installée à Kyiv, où elle a développé un art inspiré par la fragilité et la reconstruction.', '18011986-anastasiya-melnyk'),
	(28, 'DeShawn', 'Carter', '1990-05-14', NULL, 'Graffeur', 'Né à Chicago, DeShawn Carter a été confronté dès son enfance aux violences policières et aux inégalités raciales.', '14051990-carter-deshawn'),
	(29, 'Johnson', 'Amara', '1982-02-27', NULL, 'Danseuse, Chorégraphe', 'Originaire de la Nouvelle-Orléans, Amara Johnson a étudié la danse classique avant de se tourner vers une expression plus libre inspirée du hip-hop et de la danse africaine.', '27021982-amara-johnson'),
	(30, 'Rivers', 'Malcom', '1974-08-02', NULL, 'Sculteur', 'Né à Atlanta, Malcolm Rivers a grandi dans une famille impliquée dans les mouvements pour les droits civiques. Très jeune, il a été sensibilisé aux luttes pour l’égalité et la reconnaissance des cultures afro-américaines.', '02081974-malcom-rivers'),
	(31, 'Brooks', 'Nathaniel', '1892-03-29', '1892-10-07', 'Illustrateur, Peintre', 'Issu d’une famille de fermiers du Kentucky, Nathaniel Brooks a d’abord été illustrateur de presse avant de se consacrer à la peinture après la guerre de Sécession.', '29031892-nathaniel-brooks'),
	(32, 'Doyle', 'Kate', '1838-11-10', '1919-06-22', 'Photographe', 'Fille d’un imprimeur de Boston, Catherine Doyle a été l’une des rares femmes photographes à couvrir les conséquences de la guerre.', '10111838-kate-doyle'),
	(33, 'Wainwright', 'Jebediah', '1829-09-05', '1888-01-14', 'Graveur, Caricaturiste', 'Ancien soldat confédéré ayant changé de camp après la bataille de Gettysburg, Jeb Wainwright est devenu célèbre pour ses gravures satiriques dénonçant l’absurdité de la guerre.', '05091829-jebediah-wainwright'),
	(34, 'Monroe', 'Ezekiel', '1842-07-20', '1864-12-02', 'Musicien', 'Né dans une famille d’esclaves affranchis en Virginie, Ezekiel Monroe a appris le violon en cachette avant de s’engager comme musicien dans l’armée de l’Union.', '20071864-ezekiel-monroe');

-- Listage de la structure de table regardsguerre. comment
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `exhibition_id` int DEFAULT NULL,
  `comment_text` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_comment_creation` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526CA76ED395` (`user_id`),
  KEY `IDX_9474526C2A7D4494` (`exhibition_id`),
  CONSTRAINT `FK_9474526C2A7D4494` FOREIGN KEY (`exhibition_id`) REFERENCES `exhibition` (`id`),
  CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.comment : ~0 rows (environ)

-- Listage de la structure de table regardsguerre. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Listage des données de la table regardsguerre.doctrine_migration_versions : ~0 rows (environ)
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
  `date_exhibit` date NOT NULL,
  `hour_begin` time NOT NULL,
  `hour_end` time NOT NULL,
  `description_exhibit` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hook_exhibit` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle_exhibit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock_max` int NOT NULL,
  `stock_alert` int NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B8353389989D9B62` (`slug`),
  KEY `IDX_B8353389A76ED395` (`user_id`),
  CONSTRAINT `FK_B8353389A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.exhibition : ~5 rows (environ)
REPLACE INTO `exhibition` (`id`, `user_id`, `title_exhibit`, `main_image`, `main_image_alt`, `date_war_begin`, `date_war_end`, `date_exhibit`, `hour_begin`, `hour_end`, `description_exhibit`, `hook_exhibit`, `subtitle_exhibit`, `stock_max`, `stock_alert`, `slug`) VALUES
	(1, 17, 'Les camps d\'Algérie', '/images/events/20250509/00_main_image.webp', 'Photo du déplacement entre les camps pendant la guerre d\'Algérie.', '1954-11-01', '1962-03-19', '2025-05-09', '09:00:00', '16:00:00', 'Les camps d\'Algérie, créés pendant la guerre d\'indépendance, sont devenus des symboles de l\'exil, de la souffrance et du déracinement, où les conditions de vie des populations déplacées étaient marquées par l\'humiliation et l\'abandon.', 'Sujet sensible encore aujourd’hui, ils font désormais partie d\'une mémoire collective partagée entre les Algériens et les Français.', 'Lieux de souffrance et de résistance', 150, 10, '09052025-les-camps-d-algerie'),
	(3, 17, 'Les femmes palestiniennes et leur engagement', '/images/events/20250902/00_main_image.webp', 'Troupes de femmes militaires palestiniennes', '1987-12-09', '1993-09-13', '2025-11-15', '09:00:00', '16:00:00', 'Les femmes palestiniennes jouent un rôle central dans ce conflit, que ce soit comme mères, militantes, journalistes, soignantes ou résistantes. elles s’engagent activement, que ce soit à travers des mouvements de résistance, des actions humanitaires ou des témoignages dénonçant les souffrances du peuple palestinien. Beaucoup deviennent des symboles de résilience.', 'Les femmes palestiniennes, actrices essentielles du conflit, s\'engagent activement à travers divers rôles, devenant des symboles de résilience face aux souffrances de leur peuple.', 'Mères, militantes, symboles de courage : les femmes palestiniennes face à l\'adversité', 150, 10, '15112025-les-femmes-palestiniennes-et-leur-engagement'),
	(4, 20, 'L\'incident de Kyujo', '/images/events/20250812/00_main_image.webp', 'Photo d\'un accord passé dans le bureau principal', '1945-08-14', '1945-08-15', '2025-08-12', '09:00:00', '16:00:00', 'Lorsque des officiers de l’armée impériale japonaise ont tenté un coup d’État pour empêcher l’empereur Hirohito d’annoncer la reddition du Japon à la fin de la Seconde Guerre mondiale. Leur tentative a échoué, et le message de capitulation a été diffusé le matin du 15 août 1945, mettant officiellement fin au conflit.', 'Des officiers japonais ont tenté un coup d\'État raté pour empêcher la reddition du Japon, mais l\'annonce de la capitulation a été diffusée le 15 août 1945, mettant fin à la Seconde Guerre mondiale.', 'Le coup d\'État avorté de l\'armée impériale', 150, 10, '12082025-l-incident-de-kyujo'),
	(23, 34, 'L’exode afro-américaine', '/images/events/20250913/00_main_image.webp', 'Photo d\'une famille fuyant la ségrégation', '1916-01-01', '1920-12-31', '2025-09-13', '09:00:00', '16:00:00', 'Cette migration a transformé le paysage démographique et culturel des États-Unis, contribuant à l\'essor des communautés afro-américaines urbaines et influençant profondément la musique, la littérature et la politique américaines.', 'Conflit déterminant dans l\'histoire des États-Unis, aboutissant à l\'abolition de l\'esclavage et à la préservation de l\'unité nationale.', 'Conflit pour la liberté', 150, 10, '13092025-l-exode-afro-americaine'),
	(25, 34, 'L\'Ukraine en résistance', '/images/events/20250603/00_main_image.webp', 'Photo de l\'oeuvre de Bansky sur un mur en Ukraine ou une petite fille marche sur des chars tenant fièrement son drapeau', '2022-02-24', NULL, '2025-06-03', '09:00:00', '16:00:00', 'La guerre russo-ukrainienne a commencé en 2014 avec l\'annexion de la Crimée et s\'est transformée en une invasion totale de l\'Ukraine par la Russie le 24 février 2022. Ce conflit a provoqué des milliers de victimes, des déplacements massifs et une crise géopolitique majeure en Europe.', 'L\'invasion russe a déclenché un conflit majeur en Europe, bouleversant l\'équilibre géopolitique et provoquant une crise humanitaire de grande ampleur.', 'Chronique d\'une guerre : l\'Ukraine au cœur d\'une crise européenne', 150, 10, '03062025-l-Ukraine-en-resistance');

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
  `order_date_creation` date NOT NULL,
  `order_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F5299398A76ED395` (`user_id`),
  CONSTRAINT `FK_F5299398A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.order : ~5 rows (environ)
REPLACE INTO `order` (`id`, `user_id`, `order_date_creation`, `order_status`) VALUES
	(47, 30, '2025-02-28', 'Envoyé'),
	(49, 21, '2025-03-01', 'Envoyé'),
	(50, 17, '2025-03-01', 'Envoyé'),
	(51, 31, '2025-03-02', 'Envoyé'),
	(52, 31, '2025-03-02', 'Envoyé'),
	(53, 31, '2025-04-14', 'Envoyé');

-- Listage de la structure de table regardsguerre. order_detail
CREATE TABLE IF NOT EXISTS `order_detail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order__id` int DEFAULT NULL,
  `exhibition_id` int DEFAULT NULL,
  `ticket_id` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_ED896F46251A8A50` (`order__id`),
  KEY `IDX_ED896F462A7D4494` (`exhibition_id`),
  KEY `IDX_ED896F46700047D2` (`ticket_id`),
  CONSTRAINT `FK_ED896F46251A8A50` FOREIGN KEY (`order__id`) REFERENCES `order` (`id`),
  CONSTRAINT `FK_ED896F462A7D4494` FOREIGN KEY (`exhibition_id`) REFERENCES `exhibition` (`id`),
  CONSTRAINT `FK_ED896F46700047D2` FOREIGN KEY (`ticket_id`) REFERENCES `ticket` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.order_detail : ~10 rows (environ)
REPLACE INTO `order_detail` (`id`, `order__id`, `exhibition_id`, `ticket_id`, `unit_price`, `quantity`) VALUES
	(58, 47, 1, 1, 10.00, 2),
	(59, 47, 1, 3, 0.00, 1),
	(60, 47, 1, 2, 8.00, 1),
	(63, 49, 1, 1, 10.00, 2),
	(64, 49, 1, 3, 0.00, 1),
	(65, 50, 1, 1, 10.00, 2),
	(66, 51, 1, 1, 10.00, 2),
	(67, 51, 1, 3, 0.00, 1),
	(68, 52, 1, 1, 10.00, 2),
	(70, 53, 1, 1, 10.00, 2);

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
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.show : ~5 rows (environ)
REPLACE INTO `show` (`id`, `room_id`, `exhibition_id`, `artist_id`, `artist_photo`, `artist_photo_alt`, `artist_text_art`) VALUES
	(3, 2, 1, 6, 'images/events/20250509/Amine_Yasmina.webp', 'Affiche du court métrage "Les guerrières d\'Algérie"', 'Elle se concentre particulièrement sur les récits souvent oubliés des harkis, comme dans son documentaire "La fin des Harkis", qui donne une voix aux témoins de cette histoire silencieuse. Elle utilise le cinéma pour questionner la mémoire collective, le traumatisme de l\'exil et la réconciliation entre les différentes communautés liées au conflit.'),
	(4, 4, 1, 4, 'images/events/20250509/Moribon_Jean.webp', 'Photo prise par l\'artiste Moribon Jean', 'Son travail se distingue par une approche profondément humaniste, où il capte les souffrances et les émotions des civils dans des situations de guerre, notamment pendant la guerre d\'Algérie. Ses photographies vont au-delà de l’image de la violence, en mettant l\'accent sur la dignité et la résilience des personnes confrontées à des conditions extrêmes, offrant ainsi un témoignage puissant de leur réalité.'),
	(22, 5, 1, 5, 'images/events/20250509/Khaman_Mohammed.webp', 'Oeuvre de Mohammed Khamman', 'Influencé par le cubisme et le surréalisme, il abordait les souffrances de la guerre d\'Algérie et l\'impact de l\'exil, utilisant des formes géométriques et des couleurs puissantes pour symboliser la fracture et la reconstruction de l\'Algérie  tout en cherchant à réconcilier les mémoires et à reconstruire visuellement l’âme du pays.'),
	(23, 1, 1, 3, 'images/events/20250509/Garrigue_Anna.webp', 'Photo d\'art de l\'artite Anne Garrigue', 'Le travail poétique d\'Anna Garrigue est profondément marqué par son engagement politique et son amour pour l’Algérie, qu’elle décrit à travers une écriture intense et émotive. Ses poèmes, souvent chargés de nostalgie et de résistance, abordent des thèmes de lutte, de mémoire et de réconciliation, avec une voix féminine forte et poignante.'),
	(25, 4, 25, 31, 'images/events/20250603/Brooks_Nathaniel.webp', 'gg', 'ggg');

-- Listage de la structure de table regardsguerre. ticket
CREATE TABLE IF NOT EXISTS `ticket` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title_ticket` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_ticket` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_ticket_alt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.type : ~2 rows (environ)
REPLACE INTO `type` (`id`, `ticket_id`, `title_type`) VALUES
	(1, 2, 'Ticket'),
	(2, 1, 'Ticket');

-- Listage de la structure de table regardsguerre. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_firstname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_nickname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason_nickname` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_USER_EMAIL` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table regardsguerre.user : ~13 rows (environ)
REPLACE INTO `user` (`id`, `user_name`, `user_firstname`, `user_nickname`, `reason_nickname`, `user_email`, `password`, `roles`, `slug`) VALUES
	(17, '', '', 'lily', NULL, 'l.renau@regardsguerre.fr', '$2y$13$1/2dfTzLs1bO7LiabfFzOebgy8aghEfdHxG4xOIpZnkABzdS/s7Su', '["ROLE_ADMIN"]', 'utilisateur17'),
	(19, NULL, NULL, 'micka', NULL, 'm.murmann@regardsguerre.fr', '$2y$13$4TvTKYXjIjtU3fNipC5SO.Xrb7bFH.k7e/4w/4afTKXXLbO5WMDZq', '["ROLE_ADMIN"]', 'utilisateur19'),
	(20, NULL, NULL, 'Yopepito', NULL, 'y.ruffo@regardsguerre.fr', '$2y$13$MzopDX0AJsIOKRzQ5Fomze1Xkpp7jzgnBdrbStTVQ8ig9sTn634JG', '["ROLE_ADMIN"]', 'utilisateur20'),
	(21, NULL, NULL, 'Utilisateur21', NULL, 'utilisateur21@supprime.fr', '', '["ROLE_DELETE"]', 'utilisateur21'),
	(26, NULL, NULL, 'root', NULL, 'r.root@regardsguerre.fr', '$2y$13$jlt8xiVmva0v/lKHHGw64eRcC4JWflNi9A0l/KcmWbL1t2pquIXae', '["ROLE_ROOT"]', 'utilisateur26'),
	(29, NULL, NULL, 'lilyDu67', NULL, 'lily@gmail.com', '$2y$13$mtcVAofqugOSCWycO9obzuQt789V.JE6Q15Fja8lMQdLyUQ7MW4Eq', '["ROLE_USER"]', 'utilisateur29'),
	(30, NULL, NULL, 'MaxOu', NULL, 'maxLaMenace@gmail.com', '$2y$13$/ACNqc7g7xa6XQMp5T6RJ.JRVO4CkIDMoK.LJFjvzpNeRFwrrbvn2', '["ROLE_USER"]', 'utilisateur30'),
	(31, 'Cra', 'Moisi', 'cramoisi', NULL, 'cramoisi@gmail.com', '$2y$13$fcu18YDp6kYuhETi1H27i.6rVgfxNOVqmRo676vwxJxLu92Y3Rqaq', '["ROLE_USER"]', 'utilisateur31'),
	(34, NULL, NULL, 'artus', NULL, 'a.dupont@regardsguerre.fr', '$2y$13$nsmVPEUMPNESCxz5C1GdAOLGeRL5QJ.S0ago9qbls/VdThj.6CMrK', '["ROLE_ADMIN"]', 'utilisateur34'),
	(35, NULL, NULL, 'damidoux', NULL, 'damidoux@gmail.com', '$2y$13$YFmulZbmiaOmjWWfyWprO.3UURraNKC7yAOi88rDBcZU.f9WzpIA2', '["ROLE_USER"]', 'utilisateur35'),
	(42, NULL, NULL, 'Lisouu', NULL, 'lisouu@gmail.com', '$2y$13$ivWfE9ZRUHDG8t.QVVq9ouMg6hDN0GIojMKd.xv2/rRJtNQkcvKNO', '["ROLE_USER"]', 'utilisateur42'),
	(47, NULL, NULL, NULL, NULL, 'c.karapuvic@regardsguerre.fr', '$2y$13$qGxY9IhzQ65/.feUVaB6n.0ne0rAX4ska6.UL8QFVV7EAkEqVHs6a', '["ROLE_USER"]', 'utilisateur47');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
