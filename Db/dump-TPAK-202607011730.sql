-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: TPAK
-- ------------------------------------------------------
-- Server version	8.0.46-0ubuntu0.24.04.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `agences`
--

DROP TABLE IF EXISTS `agences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agences` (
  `idagences` int NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idagences`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agences`
--

LOCK TABLES `agences` WRITE;
/*!40000 ALTER TABLE `agences` DISABLE KEYS */;
INSERT INTO `agences` VALUES (0,'Limoges centre','Limoges 87100'),(1,'Marseille nord','Marseille 13004'),(2,'St Nazaire Vieux Port','Saint Naraire 44380');
/*!40000 ALTER TABLE `agences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inscription`
--

DROP TABLE IF EXISTS `inscription`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inscription` (
  `idinscription` int NOT NULL AUTO_INCREMENT,
  `idusers` int DEFAULT NULL,
  `idposts` int DEFAULT NULL,
  PRIMARY KEY (`idinscription`),
  KEY `inscription_users_FK` (`idusers`),
  KEY `inscription_posts_FK` (`idposts`),
  CONSTRAINT `inscription_posts_FK` FOREIGN KEY (`idposts`) REFERENCES `posts` (`idposts`),
  CONSTRAINT `inscription_users_FK` FOREIGN KEY (`idusers`) REFERENCES `users` (`idusers`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inscription`
--

LOCK TABLES `inscription` WRITE;
/*!40000 ALTER TABLE `inscription` DISABLE KEYS */;
INSERT INTO `inscription` VALUES (1,874440,1);
/*!40000 ALTER TABLE `inscription` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `idposts` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `departure` int NOT NULL,
  `arrival` int NOT NULL,
  `travel_date` datetime DEFAULT NULL,
  `seats` int DEFAULT '1',
  `idusers` int DEFAULT NULL,
  `arrival_date` datetime DEFAULT NULL,
  PRIMARY KEY (`idposts`),
  KEY `posts_users` (`idusers`),
  KEY `posts_departure_agence` (`departure`),
  KEY `posts_arrival_agence` (`arrival`),
  CONSTRAINT `fk_posts_arrival` FOREIGN KEY (`arrival`) REFERENCES `agences` (`idagences`),
  CONSTRAINT `fk_posts_departure` FOREIGN KEY (`departure`) REFERENCES `agences` (`idagences`),
  CONSTRAINT `posts_users` FOREIGN KEY (`idusers`) REFERENCES `users` (`idusers`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,'Marseille Limoges',1,0,'2026-07-01 15:29:00',3,647223,'2026-07-02 10:08:00'),(2,'Limoges St Nazaire',0,2,'2026-07-02 15:29:00',4,647223,'2026-07-03 10:08:00'),(3,'Grand trajet marseille st nazaire',1,2,'2026-07-01 11:49:00',3,874440,'2026-07-03 11:50:00');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role` (
  `idrole` int NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `level` int DEFAULT NULL,
  PRIMARY KEY (`idrole`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'Admin',10),(2,'Utilisateur',1);
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `idusers` int NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `mail` varchar(45) DEFAULT NULL,
  `phone` int DEFAULT NULL,
  `idrole` int DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`idusers`),
  KEY `users_role` (`idrole`),
  CONSTRAINT `users_role` FOREIGN KEY (`idrole`) REFERENCES `role` (`idrole`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (647223,'Root','root.admin@gmail.com',0,1,'$2y$10$4VEpMWzfwZpkA5AHwDlA5.vfYwsuh3G4F5nIWB1tZ5Qql31AeJBem'),(865497,'Test1','test1@gmail.com',12345678,2,'$2y$10$PpZ.iaDAh1yYPQXFRcf1xubEkziwjEYBXBCVmO/ZfElTfD.KL1DEG'),(874440,'PAQUET Corentin','propcorentin87@gmail.com',760112473,2,'$2y$10$bhlZx6I4R.ODlTIPJO6/uOXo0U8XisnbyGpKG.31gLX9MT3v7wlWC');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'TPAK'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-01 17:30:26
