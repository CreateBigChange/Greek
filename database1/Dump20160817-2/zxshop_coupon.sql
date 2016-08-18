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
-- Table structure for table `coupon`
--

DROP TABLE IF EXISTS `coupon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `content` varchar(45) CHARACTER SET utf8 DEFAULT NULL COMMENT '内容',
  `type` tinyint(3) NOT NULL COMMENT '1 , 满减券',
  `effective_time` varchar(45) DEFAULT '1' COMMENT '有效时间,如果该值为1，这是永久有效',
  `value` double(4,2) NOT NULL,
  `prerequisite` int(10) unsigned DEFAULT NULL COMMENT '使用该卷的条件，如果该值为0，这没有条件',
  `store_id` int(10) unsigned DEFAULT '0',
  `total_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '优惠券的总数量',
  `in_num` int(11) DEFAULT '0' COMMENT '收回的优惠卷数量',
  `out_num` int(11) DEFAULT '0' COMMENT '已发出的优惠卷的数量',
  `stop_out` tinyint(1) DEFAULT '0' COMMENT '0在发放中\n1停止发放',
  `num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '剩余数量',
  `created_at` varchar(20) DEFAULT NULL,
  `updated_at` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1 COMMENT='优惠卷';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupon`
--

LOCK TABLES `coupon` WRITE;
/*!40000 ALTER TABLE `coupon` DISABLE KEYS */;
INSERT INTO `coupon` VALUES (1,'店铺专用券14','XXX    ',1,'12',12.00,12,21,100,0,0,1,100,'2016-07-15 17:02:25','2016-07-25 17:02:25'),(2,'店铺专用券',' 12',0,'1',5.90,50,21,100,0,0,1,100,'2016-07-15 17:04:57','2016-07-25 17:04:57'),(7,'优惠券3','baba  ',0,'12',99.99,1000,0,500,500,500,1,500,NULL,NULL),(16,'测试优惠券1',' 满1000送100  ',1,'',0.00,0,0,100,0,0,0,100,NULL,NULL),(22,'店铺专用券','减满4可用 ',1,'1',1.00,4,0,1,0,0,1,1,'2016-07-20 14:34:22','2016-07-20 14:34:22'),(23,'通用券','减满123可用',1,'12',12.00,123,0,100,0,0,0,100,'2016-07-20 16:27:45','2016-07-20 16:27:45'),(24,'通用券','',0,'',0.00,0,0,0,0,0,0,0,'2016-07-22 17:39:05','2016-07-22 17:39:05'),(25,'通用券','',0,'',0.00,0,0,0,0,0,0,0,'2016-07-22 17:39:13','2016-07-22 17:39:13'),(26,'通用券','',0,'',0.00,0,0,0,0,0,0,0,'2016-07-22 17:39:15','2016-07-22 17:39:15'),(27,'通用券','',0,'',0.00,0,0,0,0,0,1,0,'2016-07-22 17:39:17','2016-07-22 17:39:17'),(28,'通用券','',0,'',0.00,0,0,0,0,0,1,0,'2016-07-22 17:39:22','2016-07-22 17:39:22');
/*!40000 ALTER TABLE `coupon` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-08-18  8:50:31
