DROP DATABASE IF EXISTS `volscore`;
CREATE DATABASE `volscore` /*!40100 DEFAULT CHARACTER SET utf8mb3 */;
USE `volscore`;
-- MySQL dump 10.13  Distrib 8.0.31, for Win64 (x86_64)
--
-- Host: localhost    Database: volscore
-- ------------------------------------------------------
-- Server version	5.7.11

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `games`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(45) NOT NULL,
  `level` varchar(45) NOT NULL,
  `category` varchar(45) NOT NULL,
  `league` varchar(45) NOT NULL,
  `location` varchar(45) DEFAULT NULL,
  `venue` varchar(45) DEFAULT NULL,
  `moment` datetime DEFAULT NULL,
  `receiving_id` int(11) NOT NULL,
  `visiting_id` int(11) NOT NULL,
  `toss` int(1) NOT NULL DEFAULT 0 COMMENT 'Receiving: 1, Visiting: 2',
  PRIMARY KEY (`id`),
  KEY `fk_games_teams_idx` (`receiving_id`),
  KEY `fk_games_teams1_idx` (`visiting_id`),
  CONSTRAINT `fk_games_teams` FOREIGN KEY (`receiving_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_games_teams1` FOREIGN KEY (`visiting_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `games`
--

/*!40000 ALTER TABLE `games` DISABLE KEYS */;
INSERT INTO `games` VALUES (1,'Championnat','R','M','U17','Froideville','Salle des Platanes','2023-01-20 20:00:00',3,2,0),(2,'Championnat','R','M','U17','Yverdon','Salle des Iles','2023-01-20 20:45:00',6,5,0),(3,'Championnat','R','M','U17','Dorigny','Salle Omnisport','2023-01-20 20:00:00',1,4,0);
/*!40000 ALTER TABLE `games` ENABLE KEYS */;

--
-- Table structure for table `members`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_id` int(11) NOT NULL,
  `role` varchar(45) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `license` int(11) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `libero` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_members_teams1_idx` (`team_id`),
  CONSTRAINT `fk_members_teams1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `members`
--

/*!40000 ALTER TABLE `members` DISABLE KEYS */;
INSERT INTO `members` VALUES (1,1,'C','Gavin','Franks',91453,1,NULL),(2,1,'J','Brody','Workman',11498,14,1),(3,1,'J','Rudyard','Rush',39546,15,NULL),(4,5,'C','Theodore','Whitehead',55183,2,NULL),(5,3,'C','Chaim','Stewart',10529,12,NULL),(6,2,'C','Austin','Riddle',34116,4,NULL),(7,2,'J','Lyle','Eaton',41709,7,1),(8,6,'C','Kuame','Beach',51207,6,NULL),(9,3,'J','Noble','Lamb',44499,11,1),(10,3,'J','Magee','Joyce',85616,4,NULL),(11,6,'J','John','Davidson',84036,14,NULL),(12,6,'J','Thomas','Schroeder',34654,2,NULL),(13,5,'J','Matthew','Parker',84467,3,NULL),(14,5,'J','Noah','Benson',17345,6,NULL),(15,5,'J','Edward','Flowers',83454,1,NULL),(16,3,'J','Lyle','Sellers',50358,14,NULL),(17,4,'C','Amir','Rose',94398,3,NULL),(18,1,'J','Dean','Conrad',48163,6,NULL),(19,3,'J','Honorato','Merrill',22132,2,NULL),(20,3,'J','Cedric','Conner',23052,5,NULL),(21,2,'J','Murphy','Woods',32964,10,NULL),(22,5,'J','Stephen','Mathis',91690,7,NULL),(23,4,'J','Phelan','Emerson',39118,9,NULL),(24,4,'J','Herrod','Harding',32134,8,NULL),(25,4,'J','Nathan','Daugherty',39557,6,1),(26,2,'J','Emerson','Lindsey',98287,14,NULL),(27,4,'J','Ivan','Koch',18875,4,NULL),(28,4,'J','Xander','Gonzales',56924,2,NULL),(29,3,'J','Christopher','Melendez',72345,7,NULL),(30,3,'J','Jerry','Estes',45560,15,NULL),(31,5,'J','Laith','Gould',98255,8,1),(32,2,'J','Kibo','Garner',20039,5,NULL),(33,1,'J','Kato','Holmes',25941,2,NULL),(34,5,'J','Silas','Odonnell',11628,14,NULL),(35,4,'J','Todd','Burt',58404,13,NULL),(36,1,'J','Gareth','Leblanc',26579,3,NULL),(37,4,'J','Lane','Benson',94586,14,NULL),(38,1,'J','Eaton','Mills',48309,4,NULL),(39,6,'J','Boris','Deleon',33613,1,1),(40,1,'J','Dillon','Mccormick',10906,9,NULL),(41,3,'J','Honorato','Guerrero',45989,9,NULL),(42,1,'J','Coby','Bell',46277,5,NULL),(43,5,'J','Kane','Drake',53072,9,NULL),(44,5,'J','Vaughan','Faulkner',27102,13,NULL),(45,2,'J','Erich','Sykes',33688,3,NULL),(46,2,'J','Adam','Davis',15896,11,NULL),(47,3,'J','Byron','Knox',16190,13,NULL),(48,6,'J','Cairo','Briggs',21834,4,NULL),(49,4,'J','Deacon','Aguirre',19076,15,NULL),(50,6,'J','Joseph','Soto',80744,3,NULL),(51,5,'J','Alden','Ingram',27461,11,NULL),(52,4,'J','Lev','Petty',94524,7,NULL),(53,6,'J','Wang','Wiley',94894,7,NULL),(54,5,'J','Oren','Rollins',14421,10,NULL),(55,1,'J','Vaughan','Horton',47511,8,NULL),(56,2,'J','Murphy','Wise',51632,2,NULL),(57,3,'J','Keane','Goodman',97922,3,NULL),(58,4,'J','Hyatt','Allison',47125,12,NULL),(61,3,'J','Ronan','Dale',65142,1,NULL),(63,1,'J','Quamar','Parrish',67220,7,NULL),(64,2,'J','Aristotle','Parks',63888,1,NULL),(65,2,'J','Walker','Hart',48291,6,NULL),(66,2,'J','Carl','Mclaughlin',28182,9,NULL),(67,4,'J','Ulysses','Curry',60183,9,NULL),(68,6,'J','Cyrus','Schwartz',91659,8,NULL),(69,6,'J','Knox','Adams',24085,13,NULL),(71,2,'J','Louis','Bean',75328,12,NULL),(74,3,'J','Cairo','Parrish',63586,10,NULL),(75,4,'J','Raja','Shelton',48129,1,NULL),(77,6,'J','Hayden','Onell',52247,9,NULL),(80,3,'J','Emerson','Terrell',72265,6,NULL),(96,1,'J','Lucius','Munoz',31390,11,NULL),(102,6,'J','Merrill','Herman',48597,10,NULL),(123,1,'J','Adrian','Craft',21742,12,NULL);
/*!40000 ALTER TABLE `members` ENABLE KEYS */;

--
-- Table structure for table `points`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_id` int(11) NOT NULL,
  `set_id` int(11) NOT NULL,
  `position_of_server` int(11) NOT NULL COMMENT 'The position (1-6) of the last server of the team which scored the point.',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_points_teams1_idx` (`team_id`),
  KEY `fk_points_sets1_idx` (`set_id`),
  CONSTRAINT `fk_points_sets1` FOREIGN KEY (`set_id`) REFERENCES `sets` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_points_teams1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'Contains all points scored.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `players`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `players` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `number` int(11) NULL,
  `validated` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_players_game_idx` (`game_id`),
  KEY `fk_players_teams_idx` (`member_id`),
  CONSTRAINT `fk_player_game` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_player_team` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `positions`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `set_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `player_position_1_id` int(11) NOT NULL,
  `player_position_2_id` int(11) NOT NULL,
  `player_position_3_id` int(11) NOT NULL,
  `player_position_4_id` int(11) NOT NULL,
  `player_position_5_id` int(11) NOT NULL,
  `player_position_6_id` int(11) NOT NULL,
  `final`int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_position_sets1_idx` (`set_id`),
  KEY `fk_position_teams_idx` (`team_id`),
  KEY `fk_positions_members1_idx` (`player_position_1_id`),
  KEY `fk_positions_members2_idx` (`player_position_2_id`),
  KEY `fk_positions_members3_idx` (`player_position_3_id`),
  KEY `fk_positions_members4_idx` (`player_position_4_id`),
  KEY `fk_positions_members5_idx` (`player_position_5_id`),
  KEY `fk_positions_members6_idx` (`player_position_6_id`),
  CONSTRAINT `fk_position_sets1` FOREIGN KEY (`set_id`) REFERENCES `sets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_position_teams` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_positions_players1` FOREIGN KEY (`player_position_1_id`) REFERENCES `players` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_positions_players2` FOREIGN KEY (`player_position_2_id`) REFERENCES `players` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_positions_players3` FOREIGN KEY (`player_position_3_id`) REFERENCES `players` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_positions_players4` FOREIGN KEY (`player_position_4_id`) REFERENCES `players` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_positions_players5` FOREIGN KEY (`player_position_5_id`) REFERENCES `players` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_positions_players6` FOREIGN KEY (`player_position_6_id`) REFERENCES `players` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `substitutions`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `substitutions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position_id` int(11) NOT NULL COMMENT 'References the "position sheet" of a specific set',
  `player_position_out` int(11) NOT NULL COMMENT 'The position of the player who exits',
  `player_in_id` int(11) NOT NULL COMMENT 'The player who enters',
  `returned` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Indicates that the starting player has returned (and can no longer leave during this set)',
  PRIMARY KEY (`id`),
  KEY `fk_positions_idx` (`position_id`),
  KEY `fk_player_sub_idx` (`player_in_id`),
  CONSTRAINT `fk_positions` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_player_sub` FOREIGN KEY (`player_in_id`) REFERENCES `members` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sets`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` int(11) DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `game_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sets_games1_idx` (`game_id`),
  CONSTRAINT `fk_sets_games1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `teams`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
INSERT INTO `teams` VALUES (2,'Ecublens'),(3,'Froideville'),(4,'Littoral'),(1,'LUC'),(5,'Lutry'),(6,'Yverdon'),(7,'kill me'),(8, 'suppression test');
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;

--
-- Table structure for table `bookings`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `point_id` int(11) NOT NULL,
  `severity` int(1) NOT NULL DEFAULT 0 COMMENT '0 -> yellow, 1 -> red, 2 -> yellowred, 3 -> yellow and red',
  PRIMARY KEY (`id`),
  KEY `fk_bookings_players_idx` (`player_id`),
  KEY `fk_bookings_points_idx` (`point_id`),
  CONSTRAINT `fk_booking_player` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_booking_point` FOREIGN KEY (`point_id`) REFERENCES `points` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'Contains bookings.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `timeouts`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `timeouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_id` int(11) NOT NULL,
  `set_id` int(11) NOT NULL,
  `point_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_timeouts_teams_idx` (`team_id`),
  KEY `fk_sets_teams_idx` (`set_id`),
  KEY `fk_timeouts_points_idx` (`point_id`),
  CONSTRAINT `fk_timeout_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_timeout_set` FOREIGN KEY (`set_id`) REFERENCES `sets` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_timeout_point` FOREIGN KEY (`point_id`) REFERENCES `points` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;



/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-02-04 12:19:39
