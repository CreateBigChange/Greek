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
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `img` varchar(255) NOT NULL COMMENT '图片连接',
  `redirect` varchar(255) NOT NULL COMMENT '跳转链接',
  `name` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_open` tinyint(4) DEFAULT '0' COMMENT '0为上线 1上线',
  `sort` int(10) unsigned DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES (1,'http://7xt4zt.com2.z0.glb.clouddn.com/1.jpg','http://wecaht.jisxu.com','qwe','0000-00-00 00:00:00','2016-07-13 02:27:17',1,2),(2,'http://7xt4zt.com2.z0.glb.clouddn.com/2.jpg','http://wecaht.jisxu.com','1','0000-00-00 00:00:00','0000-00-00 00:00:00',1,1),(3,'http://7xt4zt.com2.z0.glb.clouddn.com/1468460581.jpg','http://wecaht.jisxu.com','3','0000-00-00 00:00:00','2016-07-14 01:18:17',1,0),(4,'http://7xt4zt.com2.z0.glb.clouddn.com/1468329979.png','http://wecaht.jisxu.com','1','2011-11-11 03:11:11','0000-00-00 00:00:00',0,2),(39,'http://7xt4zt.com2.z0.glb.clouddn.com/1468407377.png','http://wecaht.jisxu.com','3','0000-00-00 00:00:00','2016-07-13 10:19:30',1,10),(40,'http://7xt4zt.com2.z0.glb.clouddn.com/1468379022.png','','','0000-00-00 00:00:00','0000-00-00 00:00:00',0,0),(41,'http://7xt4zt.com2.z0.glb.clouddn.com/1468382222.png','','','0000-00-00 00:00:00','0000-00-00 00:00:00',0,0),(42,'http://7xt4zt.com2.z0.glb.clouddn.com/1468379621.png','','','0000-00-00 00:00:00','0000-00-00 00:00:00',0,0),(43,'http://7xt4zt.com2.z0.glb.clouddn.com/1468390705.png','aaa','aaaa','2016-07-13 02:22:07','2016-07-29 09:06:21',1,7),(44,'http://7xt4zt.com2.z0.glb.clouddn.com/1468383589.png','','1212','2016-07-13 02:27:43','2016-07-13 02:28:08',1,1),(45,'http://7xt4zt.com2.z0.glb.clouddn.com/1469781570.jpg','111','111','2016-07-13 02:29:49','2016-08-17 07:24:41',1,9);
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-08-18  8:50:32
