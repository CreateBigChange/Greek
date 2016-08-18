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
-- Table structure for table `push`
--

DROP TABLE IF EXISTS `push`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `push` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application` varchar(45) DEFAULT NULL COMMENT '应用名称，推送给哪个应用',
  `content` varchar(45) DEFAULT NULL COMMENT '推送内容',
  `title` varchar(45) DEFAULT NULL COMMENT '标题',
  `platform` varchar(45) DEFAULT NULL COMMENT '平台，iOS还是android还是all',
  `tag` varchar(45) DEFAULT '' COMMENT '按标签推送',
  `alias` varchar(45) DEFAULT '' COMMENT '按别名推送',
  `sound` varchar(45) DEFAULT NULL COMMENT '铃声',
  `type` varchar(45) DEFAULT NULL COMMENT '类型，是新订单的推送还是退单的推送还是其他',
  `is_push` tinyint(1) DEFAULT '0' COMMENT '是否推送，默认0未推送',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='推送数据';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `push`
--

LOCK TABLES `push` WRITE;
/*!40000 ALTER TABLE `push` DISABLE KEYS */;
INSERT INTO `push` VALUES (1,'急所需商户端','急所需有新订单啦,请及时处理','急所需新订单','all','','21','default','new',1,'2016-08-04 10:00:59','2016-08-04 10:53:22'),(2,'急所需商户端','急所需有新订单啦,请及时处理','急所需新订单','all','','21','default','new',1,'2016-08-04 10:01:20','2016-08-04 10:53:23'),(3,'急所需商户端','急所需有新订单啦,请及时处理','急所需新订单','all','','21','default','new',1,'2016-08-04 10:01:25','2016-08-04 10:53:23');
/*!40000 ALTER TABLE `push` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-08-18  8:50:41
