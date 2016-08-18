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
-- Table structure for table `store_nav`
--

DROP TABLE IF EXISTS `store_nav`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store_nav` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '栏目id',
  `store_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '栏目名称',
  `sort` int(11) DEFAULT '1',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已经删除;0未删除, 1已删除',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_nav`
--

LOCK TABLES `store_nav` WRITE;
/*!40000 ALTER TABLE `store_nav` DISABLE KEYS */;
INSERT INTO `store_nav` VALUES (1,21,'白酒',1,0,'2016-07-24 22:46:33','2016-07-24 22:46:33'),(2,21,'饼干糕点',2,0,'2016-07-24 22:46:33','2016-07-24 22:46:33'),(3,21,'白酒',1,0,'2016-07-24 22:47:47','2016-07-24 22:47:47'),(4,21,'饼干糕点',2,0,'2016-07-24 22:47:47','2016-07-24 22:47:47'),(5,22,'白酒',1,0,'2016-08-09 10:46:20','2016-08-09 10:46:20'),(6,22,'饼干糕点',1,0,'2016-08-09 10:54:04','2016-08-09 10:54:04'),(9,24,'白酒',1,0,'2016-08-10 01:13:27','2016-08-10 01:13:27'),(10,24,'饼干糕点',1,0,'2016-08-10 01:13:27','2016-08-10 01:13:27');
/*!40000 ALTER TABLE `store_nav` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-08-18  8:50:38
