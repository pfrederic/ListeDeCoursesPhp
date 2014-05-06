-- MySQL dump 10.13  Distrib 5.1.73, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: dbListeCoursesOrig
-- ------------------------------------------------------
-- Server version	5.1.73-1

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
-- Current Database: `dbListeCoursesOrig`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `dbListeCoursesOrig` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `dbListeCoursesOrig`;

--
-- Table structure for table `contenuListe`
--

DROP TABLE IF EXISTS `contenuListe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contenuListe` (
  `listeId` int(11) NOT NULL DEFAULT '0',
  `produitId` int(11) NOT NULL DEFAULT '0',
  `listeQte` int(11) DEFAULT NULL,
  `dansCaddy` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`listeId`,`produitId`),
  KEY `fk_produitId` (`produitId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contenuListe`
--

LOCK TABLES `contenuListe` WRITE;
/*!40000 ALTER TABLE `contenuListe` DISABLE KEYS */;
INSERT INTO `contenuListe` VALUES (0,1,12,0),(0,3,6,0),(0,6,9,0);
/*!40000 ALTER TABLE `contenuListe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `famille`
--

DROP TABLE IF EXISTS `famille`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `famille` (
  `familleId` int(11) NOT NULL,
  `familleLib` varchar(25) NOT NULL,
  `familleCode` int(11) NOT NULL,
  PRIMARY KEY (`familleId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `famille`
--

LOCK TABLES `famille` WRITE;
/*!40000 ALTER TABLE `famille` DISABLE KEYS */;
INSERT INTO `famille` VALUES (1,'Dupont',2586);
/*!40000 ALTER TABLE `famille` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `liste`
--

DROP TABLE IF EXISTS `liste`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `liste` (
  `listeId` int(11) NOT NULL DEFAULT '0',
  `familleId` int(11) NOT NULL,
  `enCours` tinyint(1) DEFAULT '1',
  KEY `produitId` (`familleId`),
  CONSTRAINT `fk_famille` FOREIGN KEY (`familleId`) REFERENCES `famille` (`familleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `liste`
--

LOCK TABLES `liste` WRITE;
/*!40000 ALTER TABLE `liste` DISABLE KEYS */;
INSERT INTO `liste` VALUES (0,1,1);
/*!40000 ALTER TABLE `liste` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `magasin`
--

DROP TABLE IF EXISTS `magasin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `magasin` (
  `magasinId` int(11) NOT NULL,
  `magasinLib` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`magasinId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `magasin`
--

LOCK TABLES `magasin` WRITE;
/*!40000 ALTER TABLE `magasin` DISABLE KEYS */;
INSERT INTO `magasin` VALUES (1,'Leclerc'),(2,'Auchan'),(3,'Carrefour');
/*!40000 ALTER TABLE `magasin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organisation`
--

DROP TABLE IF EXISTS `organisation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organisation` (
  `magasinId` int(11) NOT NULL DEFAULT '0',
  `rayonId` int(11) NOT NULL DEFAULT '0',
  `organisationOrdre` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`magasinId`,`rayonId`,`organisationOrdre`),
  KEY `rayonId` (`rayonId`),
  CONSTRAINT `organisation_ibfk_1` FOREIGN KEY (`magasinId`) REFERENCES `magasin` (`magasinId`),
  CONSTRAINT `organisation_ibfk_2` FOREIGN KEY (`rayonId`) REFERENCES `rayon` (`rayonId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organisation`
--

LOCK TABLES `organisation` WRITE;
/*!40000 ALTER TABLE `organisation` DISABLE KEYS */;
INSERT INTO `organisation` VALUES (1,1,1),(1,2,3),(1,3,2);
/*!40000 ALTER TABLE `organisation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produit`
--

DROP TABLE IF EXISTS `produit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produit` (
  `produitId` int(11) NOT NULL,
  `produitLib` varchar(50) DEFAULT NULL,
  `rayonId` int(11) DEFAULT NULL,
  PRIMARY KEY (`produitId`),
  KEY `fk_rayon` (`rayonId`),
  CONSTRAINT `fk_rayon` FOREIGN KEY (`rayonId`) REFERENCES `rayon` (`rayonId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produit`
--

LOCK TABLES `produit` WRITE;
/*!40000 ALTER TABLE `produit` DISABLE KEYS */;
INSERT INTO `produit` VALUES (1,'pilon de poulet',1),(2,'cote de boeuf',1),(3,'flanby',2),(4,'yaourt nature',2),(5,'feuille de chenes blonde',3),(6,'carote',3);
/*!40000 ALTER TABLE `produit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rayon`
--

DROP TABLE IF EXISTS `rayon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rayon` (
  `rayonId` int(11) NOT NULL,
  `rayonLib` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`rayonId`),
  UNIQUE KEY `rayonIdx` (`rayonLib`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rayon`
--

LOCK TABLES `rayon` WRITE;
/*!40000 ALTER TABLE `rayon` DISABLE KEYS */;
INSERT INTO `rayon` VALUES (7,'Jardinerie'),(3,'Légume'),(1,'viande'),(2,'yaourt');
/*!40000 ALTER TABLE `rayon` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-04-18  8:07:00
