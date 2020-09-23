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
  `addr_label` varchar(150) NOT NULL DEFAULT '',
  `addr_name` varchar(250) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `address2` varchar(255) NOT NULL DEFAULT '',
  `zip_code` varchar(10) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `id_country` tinyint(3) unsigned NOT NULL,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_address`),
  KEY `address_country` (`id_country`),
  KEY `address_user` (`id_user`),
  CONSTRAINT `address_country` FOREIGN KEY (`id_country`) REFERENCES `country` (`id_country`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `address_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address`
--

LOCK TABLES `address` WRITE;
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
INSERT INTO `address` VALUES (1,34,'Domicile','Damien Thoorens','5 rue dufyont','','14000','caen',1,0),(2,56,'domicil','damdam tototo','8 rue ducucu','5','45012','huito',1,1),(3,57,'domicile','nom&prenom de l\'adresse','8 rue du moulin','','55555','boujou',1,0),(4,38,'dgq','vzeff','gzeg','dfh','78000','srhrejh',1,0),(5,56,'travail','damdam toto','7 rue du rasoir','coucou','45000','bouchon',1,1),(6,59,'domicile','damdam toto','7 rue du rasoir','coucou','78000','bouchon',1,0),(7,56,'travail','damdam totoyo','7 rue du rasoir444','5','78030','bouchonmmm',1,1),(8,56,'cucu','fe ef','2 ruqsn','','45000','fze',1,1),(10,56,'travail','damdam toto','7 rue du rasoir','coucou','45000','bouchonmmm',1,0),(11,56,'domicile','damdam toto','8 rue ducucu','5','45012','huito',1,0);
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
  `code` char(2) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_country`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country`
--

LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
INSERT INTO `country` VALUES (1,'France','fr'),(2,'Belgique','be');
/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dossier_color`
--

DROP TABLE IF EXISTS `dossier_color`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dossier_color` (
  `id_dossier_color` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(32) NOT NULL DEFAULT '',
  `hex` varchar(6) NOT NULL DEFAULT '',
  `printable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `unprintable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `position` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_dossier_color`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dossier_color`
--

LOCK TABLES `dossier_color` WRITE;
/*!40000 ALTER TABLE `dossier_color` DISABLE KEYS */;
INSERT INTO `dossier_color` VALUES (1,'Ivoire','eceadf',1,1,14),(2,'Blanc','ffffff',0,1,18),(3,'Noir','000000',0,1,15),(4,'Gris foncé','525252',0,1,16),(5,'Gris clair','acacac',1,1,17),(6,'Marron','7a672c',0,1,12),(7,'Bleu clair','6e99e8',1,1,5),(8,'Bleu foncé','2b4ba6',1,1,4),(9,'Rouge','df1d1d',1,1,11),(10,'Jaune','ffff00',1,1,8),(11,'Bordeau','6d071a',0,1,1),(12,'Vert foncé','076d0e',0,1,6),(13,'Vert clair','33bc3d',0,1,7),(14,'Orange foncé','e1a400',1,0,10),(15,'Orange clair','f4c953',1,0,9),(16,'Violet','a346e4',1,0,2),(17,'Rose','e446de',1,0,3),(18,'Cuir 250g','74452b',0,1,13);
/*!40000 ALTER TABLE `dossier_color` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `key_value`
--

DROP TABLE IF EXISTS `key_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `key_value` (
  `sKey` varchar(150) NOT NULL DEFAULT '',
  `sValue` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`sKey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `key_value`
--

LOCK TABLES `key_value` WRITE;
/*!40000 ALTER TABLE `key_value` DISABLE KEYS */;
INSERT INTO `key_value` VALUES ('maxFeuillesMetal','100'),('maxFeuillesPlast','500'),('maxFeuillesThermo','350'),('prixFC','0.15'),('prixFT','0.20'),('tvaDossier','20'),('tvaMemoire','10'),('tvaPerso','20'),('tvaThese','10');
/*!40000 ALTER TABLE `key_value` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id_orders` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `date_add` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `id_user` smallint(5) unsigned NOT NULL,
  `id_address` smallint(5) unsigned NOT NULL,
  `nom_fichier` varchar(255) NOT NULL DEFAULT '',
  `nb_page` smallint(5) unsigned NOT NULL DEFAULT '0',
  `nb_page_nb` smallint(5) unsigned NOT NULL DEFAULT '0',
  `nb_page_c` smallint(5) unsigned NOT NULL DEFAULT '0',
  `doc_type` varchar(20) NOT NULL DEFAULT '',
  `couv_ft` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `couv_fc` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `couv_fc_type` varchar(16) NOT NULL DEFAULT '',
  `couv_fc_color` smallint(5) unsigned NOT NULL DEFAULT '0',
  `dos_ft` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dos_fc` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dos_fc_type` varchar(16) NOT NULL DEFAULT '',
  `dos_fc_color` smallint(5) unsigned NOT NULL DEFAULT '0',
  `reliure_type` varchar(20) NOT NULL DEFAULT '',
  `reliure_color` varchar(20) NOT NULL DEFAULT '',
  `quantity` smallint(5) unsigned NOT NULL DEFAULT '1',
  `rectoverso` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `tva` float(4,2) unsigned NOT NULL DEFAULT '0.00',
  `total` float(7,2) unsigned NOT NULL DEFAULT '0.00',
  `archive` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_orders`),
  KEY `id_user_orders` (`id_user`),
  KEY `id_user_address` (`id_address`),
  CONSTRAINT `id_user_address` FOREIGN KEY (`id_address`) REFERENCES `address` (`id_address`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `id_user_orders` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'2020-08-30 09:56:19',34,1,'9d7ae8a45b27e6cdaf7cd9a2be1302d3.pdf',34,31,3,'dossier',1,0,'',0,0,1,'unprintable',4,'thermo','Noire',1,0,1.79,8.93,1),(4,'2020-08-31 10:09:16',56,2,'62b3e3ac2f229d84750c44451a50c364.pdf',34,31,3,'memoire',1,1,'printable',7,0,1,'unprintable',6,'spiplast','Blanche',1,0,0.72,7.18,1),(5,'2020-08-31 17:06:31',56,2,'c3a0c212fe80bc7ec01819b467b0910f.pdf',34,31,3,'these',0,1,'printable',15,0,1,'printable',14,'thermo','Blanche',1,0,0.89,8.88,0),(6,'2020-08-31 17:11:46',56,2,'c3a0c212fe80bc7ec01819b467b0910f.pdf',34,31,3,'these',0,1,'printable',15,0,1,'printable',14,'thermo','Blanche',1,0,0.89,8.88,0),(7,'2020-08-31 17:16:39',56,2,'c3a0c212fe80bc7ec01819b467b0910f.pdf',34,31,3,'these',0,1,'printable',15,0,1,'printable',14,'thermo','Blanche',1,0,0.89,8.88,0),(8,'2020-08-31 17:18:18',56,2,'c3a0c212fe80bc7ec01819b467b0910f.pdf',34,31,3,'these',0,1,'printable',15,0,1,'printable',14,'thermo','Blanche',1,0,0.89,8.88,0),(9,'2020-08-31 19:19:35',56,2,'c6e69a269fcb33ba855156cd2e278361.pdf',34,31,3,'perso',1,1,'printable',5,1,1,'unprintable',3,'thermo','Noire',2,0,3.59,17.94,0),(10,'2020-08-31 22:02:41',57,3,'3baa0a858383ae325728326e77d3fe7d.pdf',34,31,3,'dossier',1,0,'',0,0,1,'unprintable',3,'spimetal','Blanche',1,0,1.77,8.83,0),(11,'2020-09-02 22:14:03',56,2,'9105277382bc11af32299c354ca06371.pdf',11,0,11,'these',0,1,'printable',9,0,1,'printable',9,'thermo','Blanche',1,0,1.20,12.00,0),(12,'2020-09-03 13:11:43',56,2,'2799d0fe4f6586c4d7597bd0f58fbaf5.pdf',34,31,3,'dossier',1,0,'',0,0,1,'unprintable',1,'thermo','Blanche',1,0,1.79,8.93,0),(13,'2020-09-03 13:50:38',38,4,'17686e130f43b05f18608ee884895b0a.pdf',34,31,3,'dossier',1,0,'',0,0,1,'unprintable',18,'spimetal','Noire',1,0,1.77,8.83,0),(14,'2020-09-03 13:58:00',38,4,'a7d5442c812e6959361fa6d9958c0d7d.pdf',165,26,139,'memoire',1,1,'printable',9,0,1,'unprintable',10,'spiplast','Noire',3,1,18.16,181.56,1),(15,'2020-09-03 14:09:50',56,5,'50f71c093c162647820857da49a8d32a.pdf',34,31,3,'these',0,1,'printable',8,1,1,'printable',7,'thermo','Noire',3,1,2.63,26.31,0),(16,'2020-09-05 11:13:07',59,6,'ac7a2a8226dbaaac9bb3978d525efc85.pdf',34,31,3,'perso',0,1,'unprintable',6,1,0,'',0,'spimetal','Noire',5,0,8.52,42.60,0),(17,'2020-09-07 18:48:54',34,1,'3b011424f75fa2811ebd21fb37da8720.pdf',16,16,0,'memoire',1,1,'printable',9,0,1,'unprintable',6,'spiplast','Blanche',2,1,0.78,7.76,1),(18,'2020-09-07 21:58:06',34,1,'cdad43ade5812bb6b54fc637cd37a1bf.pdf',16,16,0,'perso',0,0,'',0,0,0,'',0,'thermo','Blanche',1,0,1.06,5.28,0),(19,'2020-09-11 04:43:39',59,6,'5756def22c4d3aafae4de6aaa6c5d528.pdf',34,31,3,'perso',0,0,'',0,0,0,'',0,'spimetal','Blanche',1,0,1.70,8.48,0),(20,'2020-09-15 12:38:48',59,6,'9d758f80d1d95b44fe2d6dd9e7828dcb.pdf',34,31,3,'dossier',1,0,'',0,0,1,'unprintable',4,'spiplast','Blanche',2,0,2.69,13.44,0),(21,'2020-09-16 11:00:01',34,1,'b63ce005d131cf67a48063defc2342eb.pdf',11,0,11,'dossier',1,0,'',0,0,1,'unprintable',4,'thermo','Blanche',2,1,4.16,20.80,1),(22,'2020-09-16 15:02:10',56,8,'1f1af7a4ec778a5eba5ad6967bda4ea3.pdf',11,0,11,'perso',0,0,'',0,0,0,'',0,'thermo','Blanche',1,0,2.34,11.70,0),(23,'2020-09-16 15:38:02',56,7,'1f1af7a4ec778a5eba5ad6967bda4ea3.pdf',11,0,11,'perso',0,0,'',0,0,0,'',0,'thermo','Blanche',1,0,2.34,11.70,0),(24,'2020-09-17 17:32:23',56,10,'de4daf0fceca2a4f68975a005808e22a.pdf',34,31,3,'perso',0,0,'',0,0,0,'',0,'thermo','Blanche',1,0,1.72,8.58,0),(25,'2020-09-19 17:58:58',34,1,'eb7fb5258c907084ee83f50eb5bcedb0.pdf',34,31,3,'perso',0,0,'',0,0,0,'',0,'spimetal','Blanche',1,0,1.70,8.48,0),(26,'2020-09-21 13:02:30',34,1,'95b7c77fbfddc47c8c86bbb138f0b7ed.pdf',34,31,3,'perso',0,0,'',0,0,0,'',0,'thermo','Noire',1,0,1.72,8.58,0),(27,'2020-09-22 13:36:11',34,1,'023cca57b265388063412ab0e42adef5.pdf',34,31,3,'perso',0,0,'',0,0,0,'',0,'spimetal','Blanche',1,0,1.70,8.48,0);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paliers_couleur`
--

LOCK TABLES `paliers_couleur` WRITE;
/*!40000 ALTER TABLE `paliers_couleur` DISABLE KEYS */;
INSERT INTO `paliers_couleur` VALUES (1,15,0.7,7),(2,49,0.55,6),(3,99,0.5,5),(4,199,0.45,4),(5,499,0.4,3),(6,1000,0.25,2),(8,3000,0.15,1);
/*!40000 ALTER TABLE `paliers_couleur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paliers_nb`
--

DROP TABLE IF EXISTS `paliers_nb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paliers_nb` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `palier` smallint(5) unsigned NOT NULL,
  `prix` float NOT NULL,
  `position` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paliers_nb`
--

LOCK TABLES `paliers_nb` WRITE;
/*!40000 ALTER TABLE `paliers_nb` DISABLE KEYS */;
INSERT INTO `paliers_nb` VALUES (1,10,0.1,7),(2,49,0.08,6),(3,199,0.07,5),(4,499,0.06,4),(5,999,0.05,3),(6,3000,0.04,2),(7,5000,0.035,1);
/*!40000 ALTER TABLE `paliers_nb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paliers_spimetal`
--

DROP TABLE IF EXISTS `paliers_spimetal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paliers_spimetal` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `palier` smallint(5) unsigned NOT NULL,
  `prix` float NOT NULL,
  `position` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paliers_spimetal`
--

LOCK TABLES `paliers_spimetal` WRITE;
/*!40000 ALTER TABLE `paliers_spimetal` DISABLE KEYS */;
INSERT INTO `paliers_spimetal` VALUES (1,40,3.9,4),(2,60,4.4,3),(3,80,4.9,2),(4,100,5.4,1);
/*!40000 ALTER TABLE `paliers_spimetal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paliers_spiplast`
--

DROP TABLE IF EXISTS `paliers_spiplast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paliers_spiplast` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `palier` smallint(5) unsigned NOT NULL,
  `prix` float NOT NULL,
  `position` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paliers_spiplast`
--

LOCK TABLES `paliers_spiplast` WRITE;
/*!40000 ALTER TABLE `paliers_spiplast` DISABLE KEYS */;
INSERT INTO `paliers_spiplast` VALUES (1,45,2.1,6),(2,95,2.6,5),(3,145,3.1,4),(4,240,3.6,3),(5,375,4.6,2),(6,500,5.1,1);
/*!40000 ALTER TABLE `paliers_spiplast` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paliers_thermo`
--

DROP TABLE IF EXISTS `paliers_thermo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paliers_thermo` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `palier` smallint(5) unsigned NOT NULL,
  `prix` float NOT NULL,
  `position` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paliers_thermo`
--

LOCK TABLES `paliers_thermo` WRITE;
/*!40000 ALTER TABLE `paliers_thermo` DISABLE KEYS */;
INSERT INTO `paliers_thermo` VALUES (1,350,4,1);
/*!40000 ALTER TABLE `paliers_thermo` ENABLE KEYS */;
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
  `subscr_confirmed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `date_add` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `gender` enum('m','f') DEFAULT NULL,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `reset_pwd_expiration` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `secure_key` (`secure_key`),
  KEY `email` (`email`) USING BTREE,
  KEY `pseudo` (`pseudo`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (34,'admin','admin','damien@example.com','0606060606','admin','$2y$10$BEBEX5yYJFafr.ew6DF2HOH5ubPJ53Ho21QaqEV6XqxqSJ0IkEOly','admin','be18973e68b14b51d2b3598a947c981ddfe9d16ac1038af1e5303f9bdb17a31e238884df4ea675ac1b556731622e63d45d58',1,'2020-08-04 09:56:22',NULL,0,'0000-00-00 00:00:00'),(35,'user','user','user@user.fr','0123456789','user','$2y$10$Y/SW6mlSpnBuhiCXk8JkWuc8lUduoxfi0sv6c9kmL/HcyWRO6AmSW','user','d3d0510e15e9c1e48d9daac23468535b4ad99f5cb5d1d9321db128c471a5e7c0f9f548aa647d0f3ad907fd12bedb8a18e457',0,'2020-08-04 12:03:37',NULL,0,'0000-00-00 00:00:00'),(37,'gre','gre','gegreg@rgeerg.gre','0123456666','gre','$2y$10$BCaOMsp9PXkUsSToUnnMLu6OTeqrO6JFW6cz5pG7c3qpMYokmonki','user','00f04b4fe15a349049a7a623d7e2bff73c52fc968ed7eced973fd933e3069aa243d5c87d1b8d591b2e8a6cc1d4f0e3875ad7',0,'2020-08-04 12:16:25',NULL,0,'0000-00-00 00:00:00'),(38,'printer','printer','printer@example.com','0606060606','printer','$2y$10$54VUAm/Tl.BfTxzHhr1n4.QVEWX4.oss.ooYfM5s7MPrnOO6yWYkq','admprinter','c3413acb9d77ffe00934c608bcbc79fe057ff1e522b3b67736555f135107c9ee968647bf97b50542094e757e3a2752f39852',1,'2020-08-04 12:34:04',NULL,0,'0000-00-00 00:00:00'),(39,'kgg','rrg','test@example.com','1111111111','coucou','$2y$10$KJhOun1FLwisb78uYSLpY.45fWJ0AJqP9xU1bMc8aJtTawYJYu2XK','user','b5c02863275abd839236959e0e8abefda9b86517e9f16e93c5b80950bbc321caccb7d2cc43930085611a7968110ff19f763b',0,'2020-08-05 09:47:35',NULL,0,'0000-00-00 00:00:00'),(55,'Chris','Tophe','enmoijecrois@gmail.com','0123456789','christophe','$2y$10$RLUmoCsN1Hb4Qs0JZZ1tsOYbESPePV8N1JN/9I1dlI4x/QEL8wX1O','user','83ba0ffdaca6f939828c5c63018a9ef74a68fc6e5d6ac270d46c1e523a7bce778c41a94c6c494d71c47305ddc05cc0f2dd1a',1,'2020-08-18 19:38:58',NULL,0,'0000-00-00 00:00:00'),(56,'dam','tho','damien@thoorens.fr','0606060607','damien','$2y$10$n5ZxA8l.OvqdvYne31ppKe/6/M.jtJ4qh4V7rchs6xa.72MfulEVC','user','4de924aa086028dedeed19e34239d8151afc735d0b586d9e47b9de055f38ce1fe3291154b00ea666dcb85b9b00a806661784',1,'2020-08-27 11:24:10',NULL,0,'2020-09-16 18:22:33'),(57,'','','oih@hllj.mkj','','test','$2y$10$qZJ3fxhzYe1zxu9C5W7Cou.IhpC8.zHWPUTtSmZfM6dBQa3ZCPoFy','user','e141fbb2126e89abaff905851ac9b0c224eff7e600bb1bc376b0b21c98816fc710980a030950f7f362f80f24573d3b11ade1',0,'2020-08-31 22:00:07',NULL,1,'0000-00-00 00:00:00'),(58,'','','efz@gr.erg','','test2','$2y$10$9eSnAkuAjpw5Y1vzIn62pudS4tZGWfiN2XiHThSxlgNOEawDJ77r6','user','c592abe116512999e00360efdc277afc21f98222871dd496fbbf22a3de68b80259480f0c47056fd3cd96a5b81ec724f42f2f',1,'2020-09-01 10:15:31',NULL,0,'0000-00-00 00:00:00'),(59,'user1','name','HkDHyjgdhBD6651cu131@yopmail.com','0606060606','user1','$2y$10$9CskbbyoSQHSU16SmEJpSunjnTGe0.ntmYT.W3hAFQD.lkJ9id4Nq','user','64c115a195a68d2989d670c59c29fb38002eaf97e011de206efba8fcef91672fc4bdaaad7b93ce58c5c65efd5a50306ac97c',1,'2020-09-05 11:08:22',NULL,0,'0000-00-00 00:00:00'),(110,'','','achats@thoorens.fr','','test','$2y$10$Xqvp/BVGZiRveOBGAFwUd.r3OLquc3scs.hHk5Vzn3TZ51v76uTXy','user','4ee2500bef95c7ed41dc0cffb23f54fe46fe236dc63732213773965c426a4f215972e8fc8b4fec4ac550c47da3716ecede67',1,'2020-09-21 13:07:28',NULL,0,'2020-09-21 01:11:45');
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

-- Dump completed on 2020-09-22 15:24:04
