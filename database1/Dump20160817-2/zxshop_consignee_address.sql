-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: 192.168.0.249    Database: zxshop
-- ------------------------------------------------------
-- Server version	5.7.12

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
-- Table structure for table `consignee_address`
--

DROP TABLE IF EXISTS `consignee_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consignee_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `province` varchar(20) NOT NULL,
  `city` varchar(20) NOT NULL,
  `county` varchar(20) NOT NULL,
  `street` varchar(45) DEFAULT NULL COMMENT '街道',
  `address` varchar(45) NOT NULL,
  `consignee` varchar(45) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `longitude` float DEFAULT '0' COMMENT '经度',
  `latitude` float DEFAULT '0' COMMENT '纬度',
  `is_del` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `delivery_address_province_idx` (`province`),
  KEY `delivery_address_county_idx` (`city`,`county`,`province`),
  KEY `province_area_id_idx` (`user_id`,`province`,`city`),
  KEY `county_area_id_idx` (`county`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consignee_address`
--

LOCK TABLES `consignee_address` WRITE;
/*!40000 ALTER TABLE `consignee_address` DISABLE KEYS */;
INSERT INTO `consignee_address` VALUES (1,3,'湖南省','长沙市','岳麓区','银杉路','绿地中央广场5栋904','吴辉','18874130125',112.952,28.2276,0,'2016-07-07 04:30:43','2016-07-07 04:30:43'),(2,2,'湖南省','长沙市','岳麓区','银杉路','害虫','害虫','15173259280',112.951,28.2295,0,'2016-07-07 07:13:16','2016-07-07 07:13:16'),(3,5,'湖南省','长沙市','岳麓区','绿地中央广场(银杉路)','5栋904','卡卡','13728389359',112.952,28.2275,0,'2016-07-07 07:27:12','2016-07-07 07:27:12'),(4,9,'湖南省','长沙市','岳麓区','银杉路','绿地中央广场','杨先圣','18390231902',112.952,28.2277,0,'2016-07-08 03:07:30','2016-07-08 03:07:30'),(5,1,'湖南省','长沙市','岳麓区','绿地中央广场5栋603(安居乐园)','the ','Lou','15364098523',112.952,28.2274,0,'2016-07-11 08:56:41','2016-07-12 02:37:46');
/*!40000 ALTER TABLE `consignee_address` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-08-18  8:50:40
