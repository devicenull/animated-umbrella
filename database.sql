-- MariaDB dump 10.19  Distrib 10.5.12-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: trunkrecorder
-- ------------------------------------------------------
-- Server version	10.5.12-MariaDB-0+deb11u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `radio_call`
--

DROP TABLE IF EXISTS `radio_call`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `radio_call` (
  `CALLID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `absolute_path` varchar(255) NOT NULL DEFAULT '',
  `size_kb` int(10) unsigned NOT NULL DEFAULT 0,
  `TGID` int(10) unsigned NOT NULL DEFAULT 0,
  `call_date` datetime DEFAULT NULL,
  `frequency` int(10) unsigned NOT NULL DEFAULT 0,
  `SYSTEMID` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`CALLID`),
  UNIQUE KEY `abspath` (`absolute_path`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `radio_system`
--

DROP TABLE IF EXISTS `radio_system`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `radio_system` (
  `SYSTEMID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `system_name` varchar(64) NOT NULL DEFAULT '',
  `last_talkgroup_update` datetime DEFAULT NULL,
  `talkgroup_path` varchar(255) DEFAULT '',
  PRIMARY KEY (`SYSTEMID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `talk_group`
--

DROP TABLE IF EXISTS `talk_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `talk_group` (
  `our_talkgroupid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SYSTEMID` int(10) unsigned NOT NULL DEFAULT 0,
  `TGID` int(10) unsigned NOT NULL DEFAULT 0,
  `alpha_tag` varchar(64) NOT NULL DEFAULT '',
  `mode` varchar(8) NOT NULL DEFAULT '',
  `description` varchar(64) NOT NULL DEFAULT '',
  `category` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`our_talkgroupid`),
  UNIQUE KEY `systgid` (`SYSTEMID`,`TGID`)
) ENGINE=InnoDB AUTO_INCREMENT=646 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-12-28 19:21:38
