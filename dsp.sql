-- MySQL dump 10.16  Distrib 10.1.45-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: dsp
-- ------------------------------------------------------
-- Server version	10.1.45-MariaDB-1~bionic

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `dsp`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `dsp` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `dsp`;

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `address` (
  `id_address` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` smallint(5) unsigned NOT NULL,
  `address` varchar(255) NOT NULL DEFAULT '',
  `address2` varchar(255) NOT NULL DEFAULT '',
  `zip_code` varchar(10) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `addr_type` varchar(20) NOT NULL DEFAULT '',
  `id_country` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id_address`),
  KEY `address_country` (`id_country`),
  CONSTRAINT `address_country` FOREIGN KEY (`id_country`) REFERENCES `country` (`id_country`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address`
--

LOCK TABLES `address` WRITE;
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
/*!40000 ALTER TABLE `address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country` (
  `id_country` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country`
--

LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paliers_NB`
--

DROP TABLE IF EXISTS `paliers_NB`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paliers_NB` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `palier` smallint(5) unsigned NOT NULL,
  `prix` float NOT NULL,
  `position` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paliers_NB`
--

LOCK TABLES `paliers_NB` WRITE;
/*!40000 ALTER TABLE `paliers_NB` DISABLE KEYS */;
INSERT INTO `paliers_NB` VALUES (1,10,0.1,1),(2,49,0.08,2),(3,199,0.07,3),(4,499,0.06,4),(5,999,0.05,5),(6,3000,0.04,6),(7,5000,0.035,7);
/*!40000 ALTER TABLE `paliers_NB` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paliers_couleur`
--

DROP TABLE IF EXISTS `paliers_couleur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paliers_couleur` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `palier` smallint(5) unsigned NOT NULL,
  `prix` float NOT NULL,
  `position` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paliers_couleur`
--

LOCK TABLES `paliers_couleur` WRITE;
/*!40000 ALTER TABLE `paliers_couleur` DISABLE KEYS */;
INSERT INTO `paliers_couleur` VALUES (1,15,0.7,1),(2,49,0.55,2),(3,99,0.5,3),(4,199,0.45,4),(5,499,0.4,5),(6,1000,0.25,6);
/*!40000 ALTER TABLE `paliers_couleur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id_user` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) NOT NULL DEFAULT '',
  `last_name` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(190) NOT NULL DEFAULT '',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `pseudo` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `user_type` varchar(20) NOT NULL DEFAULT 'user',
  `secure_key` varchar(190) NOT NULL DEFAULT '',
  `subscr_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `date_add` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `gender` enum('m','f') DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `secure_key` (`secure_key`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `pseudo` (`pseudo`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (34,'admin','admin','damien@thoorens.fr','0606060606','admin','$2y$10$jq124lXhBaObEPx4xjvb3OPiC8V97cdExmEXNYoLI7QemuC1miO7i','admin','be18973e68b14b51d2b3598a947c981ddfe9d16ac1038af1e5303f9bdb17a31e238884df4ea675ac1b556731622e63d45d58',1,'2020-08-04 09:56:22',NULL),(35,'user','user','user@user.fr','0123456789','user','$2y$10$Y/SW6mlSpnBuhiCXk8JkWuc8lUduoxfi0sv6c9kmL/HcyWRO6AmSW','user','d3d0510e15e9c1e48d9daac23468535b4ad99f5cb5d1d9321db128c471a5e7c0f9f548aa647d0f3ad907fd12bedb8a18e457',0,'2020-08-04 12:03:37',NULL),(37,'gre','gre','gegreg@rgeerg.gre','0123456666','gre','$2y$10$BCaOMsp9PXkUsSToUnnMLu6OTeqrO6JFW6cz5pG7c3qpMYokmonki','user','00f04b4fe15a349049a7a623d7e2bff73c52fc968ed7eced973fd933e3069aa243d5c87d1b8d591b2e8a6cc1d4f0e3875ad7',0,'2020-08-04 12:16:25',NULL),(38,'printer','printer','printer@example.com','0606060606','printer','$2y$10$54VUAm/Tl.BfTxzHhr1n4.QVEWX4.oss.ooYfM5s7MPrnOO6yWYkq','admprinter','c3413acb9d77ffe00934c608bcbc79fe057ff1e522b3b67736555f135107c9ee968647bf97b50542094e757e3a2752f39852',1,'2020-08-04 12:34:04',NULL),(39,'kgg','rrg','jgfg@ggfg.gg','1111111111','kll','$2y$10$5CfUoAVs/ASBSAJcb6pOiePHdwXw3VhA5oBgTR4NJwSOFHuo6t2RG','user','b5c02863275abd839236959e0e8abefda9b86517e9f16e93c5b80950bbc321caccb7d2cc43930085611a7968110ff19f763b',0,'2020-08-05 09:47:35',NULL),(40,'iugiuh','ouguiyg','ououh@iygi.khg','0606060606','&amp;aqw2ZSX','$2y$10$z7Vjigp0zlxO3dJHYD6X6u91QD1fohJxKnd8rMuIJoGbJrhR4yaTC','user','177b8f70f78002cc2ac0045e066de08e0723f4e8366c1e0fca352db5478b5734540453c7c0ee1a478c75439e30a27d537473',0,'2020-08-06 12:15:46',NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-08-13 16:07:40
