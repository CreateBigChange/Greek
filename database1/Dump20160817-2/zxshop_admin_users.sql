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
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `real_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码加密随机字符串',
  `is_super` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否超级管理员;1是超级管理员',
  `is_del` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否删除;1为已删除',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_agent` tinyint(1) DEFAULT '0' COMMENT '是否是代理商，1是，0否',
  `agent_level` int(11) DEFAULT '3' COMMENT '代理级别\n1, 省级\n2,市级\n3,区级',
  `agent_area` varchar(50) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '区域名称\n如湖南省-长沙市-岳麓区',
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'admin','管理员','wuhui920613@gmail.com','e18c0e6b2aed50642395f3e5ddb278a155f5b4cc','O3w4fupC',1,0,'Z0oXrhjd','2016-02-22 18:44:26','2016-02-22 18:44:26',0,3,''),(7,'test1','测试2','test2@gmail.com','c393ad842816f1943e4fdc78f1e6d56db3aa1708','U4AtUdD4',0,0,'U4AtUdD4','2016-03-18 09:41:01','2016-03-18 10:28:46',0,3,''),(8,'test','qqq','qqq','ad143094ce4eb0c1d649810267f9a3da60ab43b8','iYWhEm9V',0,1,NULL,'2016-07-14 02:43:43','2016-07-14 02:54:49',0,3,''),(9,'ssssssssss','sssssssss','sssssssssss','0c41d950340f2e69dab203c28d917ce23c24b1f6','dJ7rN4kJ',0,1,NULL,'2016-07-14 02:54:59','2016-07-14 03:35:49',0,3,''),(10,'11111111','1111111111111','111111111111','f0bac3d68d31610d479d14d15e50c63869076698','MaQI5x6l',0,1,NULL,'2016-07-14 03:35:56','2016-07-14 06:39:05',0,3,''),(11,'adminddddddddd','dddddddd','dddddd','46e7943858a7adf066df14e14ae7712da3d5f6ae','bU1lWnNQ',0,1,NULL,'2016-07-14 03:36:08','2016-07-14 06:39:06',0,3,''),(12,'ttt','tt','123123321321','a73218658cc6c1859e8b12d7b2a52a108602d15c','4W6K5gVp',0,1,NULL,'2016-07-14 06:23:56','2016-07-18 11:51:57',0,3,''),(13,'cccc','ccc','cccc','9cb8080710c60c15ee41e8bc2bce97fa091e5c27','wmYcSM4M',0,1,NULL,'2016-07-14 06:38:59','2016-07-18 11:51:55',0,3,''),(14,'test2','111','12123','7830a98947dffc267f50f325fdfd9e179cc6a40a','KAGiRSTj',0,1,NULL,'2016-07-20 10:13:24','2016-07-20 10:37:55',0,3,''),(15,'www','sad','asd','d6e3c6ff7cd67c1d593b6e0f6c95d2a84db9fc38','CN6XBiUa',0,1,NULL,'2016-07-20 10:20:22','2016-07-20 10:30:59',0,3,''),(16,'qqqq','312','123213','40f80477bc71f117ce7dd112fc7940ad934fe07c','4uCM4DnB',0,1,NULL,'2016-07-20 10:20:37','2016-07-20 10:30:57',0,3,''),(17,'eee','123123','123123','82d32623bd6efdeabf41fca000a7904793043ee5','o5FGjeZ6',0,1,NULL,'2016-07-20 10:29:25','2016-07-20 10:30:56',0,3,''),(22,'yxs','111','11213','f441d88afc9aabd981699669d7d6169cbc910026','04clhfI1',0,0,NULL,'2016-07-20 10:31:12','2016-07-20 10:31:12',0,3,''),(23,'加盟商测试','1234','4321','e60971428c708c62fbf0c53e4579b75a3d910283','FpMG383w',0,1,NULL,'2016-07-26 07:39:15','2016-07-26 07:39:36',0,0,''),(24,'jmscs','加盟商测试','jmsce@gmail.com','22db60cb82b1f000137e2354f265cc26136074ba','SiO7eAXO',0,0,NULL,'2016-07-26 07:40:15','2016-07-26 07:40:15',1,3,'湖南省-长沙市-岳麓区');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-08-18  8:50:37
