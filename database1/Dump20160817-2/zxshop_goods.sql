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
-- Table structure for table `goods`
--

DROP TABLE IF EXISTS `goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `c_id` int(10) unsigned NOT NULL COMMENT '分类ID',
  `b_id` int(10) unsigned NOT NULL COMMENT '品牌ID',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '商品名称',
  `img` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '商品图片',
  `in_price` double(8,2) unsigned DEFAULT NULL COMMENT '进价单价',
  `out_price` double(8,2) unsigned NOT NULL COMMENT '销售单价',
  `give_points` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '赠送积分',
  `spec` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '规格',
  `desc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '描述',
  `stock` int(11) NOT NULL DEFAULT '0' COMMENT '库存',
  `is_open` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否上架',
  `is_checked` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `goods_c_id_foreign` (`c_id`),
  KEY `goods_b_id_foreign` (`b_id`),
  CONSTRAINT `goods_b_id_foreign` FOREIGN KEY (`b_id`) REFERENCES `goods_brand` (`id`),
  CONSTRAINT `goods_c_id_foreign` FOREIGN KEY (`c_id`) REFERENCES `goods_categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=601 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `goods`
--

LOCK TABLES `goods` WRITE;
/*!40000 ALTER TABLE `goods` DISABLE KEYS */;
INSERT INTO `goods` VALUES (555,45,1298,'小红星二锅头56度','',0.00,4.50,0,'','',0,1,0,0,'2016-07-06 18:40:54','2016-07-06 18:40:54'),(556,45,1044,'江小白酒','http://7xt4zt.com2.z0.glb.clouddn.com/1467836568.jpg',0.00,15.00,0,'','',0,1,0,0,'2016-07-06 18:40:54','2016-07-06 18:40:54'),(557,45,1247,'中国劲酒35°','http://7xt4zt.com2.z0.glb.clouddn.com/1467835173.jpg',0.00,10.00,0,'','',0,1,0,0,'2016-07-06 18:40:55','2016-07-06 18:40:55'),(558,45,1420,'椰岛鹿龟酒','http://7xt4zt.com2.z0.glb.clouddn.com/1467837790.jpg',0.00,9.00,0,'','',0,1,0,0,'2016-07-06 18:40:56','2016-07-06 18:40:56'),(559,36,760,'康师傅3+2草莓牛奶味','http://7xt4zt.com2.z0.glb.clouddn.com/1467839083.jpg',0.00,5.00,0,'','',0,1,0,0,'2016-07-06 18:40:56','2016-07-06 18:40:56'),(560,36,768,'卡夫太平梳打饼香葱味','http://7xt4zt.com2.z0.glb.clouddn.com/1467837140.jpg',0.00,4.00,0,'','',0,1,0,0,'2016-07-06 18:40:58','2016-07-06 18:40:58'),(561,36,752,'旺旺仙贝','http://7xt4zt.com2.z0.glb.clouddn.com/1467838065.jpg',0.00,4.00,0,'','',0,1,0,0,'2016-07-06 18:40:58','2016-07-06 18:40:58'),(562,36,752,'旺旺雪饼','http://7xt4zt.com2.z0.glb.clouddn.com/1467837900.jpg',0.00,4.50,0,'','',0,1,0,0,'2016-07-06 18:40:59','2016-07-06 18:40:59'),(563,36,762,'好丽友6枚蛋黄派奶油夹心','http://7xt4zt.com2.z0.glb.clouddn.com/1467837302.jpg',0.00,9.50,0,'','',0,1,0,0,'2016-07-06 18:41:01','2016-07-06 18:41:01'),(564,36,1096,'好吃点香脆杏仁饼','',0.00,5.50,0,'','',0,1,0,0,'2016-07-06 18:41:01','2016-07-06 18:41:01'),(565,36,1096,'好吃点香脆核桃 饼干','http://7xt4zt.com2.z0.glb.clouddn.com/1467831990.jpg',0.00,5.50,0,'','',0,1,0,0,'2016-07-06 18:41:03','2016-07-06 18:41:03'),(566,36,844,'达利园瑞士卷橙汁味','',0.00,6.50,0,'','',0,1,0,0,'2016-07-06 18:41:03','2016-07-06 18:41:03'),(567,36,844,'达利园瑞士卷草莓味','http://7xt4zt.com2.z0.glb.clouddn.com/1467840019.jpg',0.00,6.50,0,'','',0,1,0,0,'2016-07-06 18:41:04','2016-07-06 18:41:04'),(568,36,1103,'爱尚非蛋糕(椰香味)','http://7xt4zt.com2.z0.glb.clouddn.com/1467835455.jpg',0.00,2.50,0,'','',0,1,0,0,'2016-07-06 18:41:05','2016-07-06 18:41:05'),(569,36,1087,'嘉士利巧克力味威化饼','http://7xt4zt.com2.z0.glb.clouddn.com/1467834809.jpg',0.00,3.00,0,'','',0,1,0,0,'2016-07-06 18:41:05','2016-07-06 18:41:05'),(570,36,799,'旺仔小馒头原味','http://7xt4zt.com2.z0.glb.clouddn.com/1467833842.jpg',0.00,8.00,0,'','',0,1,0,0,'2016-07-06 18:41:07','2016-07-06 18:41:07'),(571,36,770,'真巧涂层巧克力味','http://7xt4zt.com2.z0.glb.clouddn.com/1467836585.jpg',0.00,4.00,0,'','',0,1,0,0,'2016-07-06 18:41:08','2016-07-06 18:41:08'),(572,36,770,'真巧涂层草莓味','',0.00,3.00,0,'','',0,1,0,0,'2016-07-06 18:41:08','2016-07-06 18:41:08'),(573,36,1087,'嘉士利夹心蓝莓味果酱','http://7xt4zt.com2.z0.glb.clouddn.com/1467836492.jpg',0.00,3.50,0,'','',0,1,0,0,'2016-07-06 18:41:09','2016-07-06 18:41:09'),(574,36,1087,'嘉士利夹心凤梨味果酱','http://7xt4zt.com2.z0.glb.clouddn.com/1467832264.jpg',0.00,3.00,0,'','',0,1,0,0,'2016-07-06 18:41:09','2016-07-06 18:41:09'),(575,36,1087,'嘉士利夹心草莓味果酱','http://7xt4zt.com2.z0.glb.clouddn.com/1467835749.jpg',0.00,3.00,0,'','',0,1,0,1,'2016-07-06 18:41:10','2016-08-05 07:13:23'),(576,36,1350,'米老头原味蛋黄煎饼','http://7xt4zt.com2.z0.glb.clouddn.com/1469699981.jpg',0.00,5.00,0,'','',0,1,0,0,'2016-07-06 18:41:11','2016-07-28 07:44:37'),(577,36,1093,'回头客铜锣烧 红豆味','http://7xt4zt.com2.z0.glb.clouddn.com/1469693896.jpg',0.00,5.00,0,'','',0,1,0,0,'2016-07-06 18:41:12','2016-07-28 07:44:09'),(578,36,768,'卡夫奶盐味太平梳打饼','http://7xt4zt.com2.z0.glb.clouddn.com/1469699328.jpg',0.00,4.00,0,'','',0,1,0,0,'2016-07-06 18:41:12','2016-07-28 07:44:18'),(579,36,1178,'福娃糙米卷（牛奶味）','http://7xt4zt.com2.z0.glb.clouddn.com/1467833481.jpg',0.00,5.00,0,'','',0,1,0,0,'2016-07-06 18:41:13','2016-07-06 18:41:13'),(580,36,760,'康师傅3+2果香蓝莓','http://7xt4zt.com2.z0.glb.clouddn.com/1467832966.jpg',0.00,5.00,0,'','',0,1,0,0,'2016-07-06 18:41:15','2016-07-06 18:41:15'),(585,45,1044,'姜小白1111','http://7xt4zt.com2.z0.glb.clouddn.com/1467836568.jpg',0.00,15.00,0,'','0',1,0,0,0,'2016-07-06 18:40:54','2016-07-06 18:40:54'),(586,45,1044,'姜小白1111','http://7xt4zt.com2.z0.glb.clouddn.com/1467836568.jpg',0.00,15.00,0,'','0',1,0,0,0,'2016-07-06 18:40:54','2016-07-06 18:40:54'),(587,45,1044,'姜小白1111','http://7xt4zt.com2.z0.glb.clouddn.com/1467836568.jpg',0.00,15.00,0,'','0',1,0,0,0,'2016-07-06 18:40:54','2016-07-06 18:40:54'),(588,45,1044,'姜小白1111','http://7xt4zt.com2.z0.glb.clouddn.com/1467836568.jpg',0.00,15.00,0,'','0',1,0,0,0,'2016-07-06 18:40:54','2016-07-06 18:40:54'),(589,45,1044,'姜小白1111','http://7xt4zt.com2.z0.glb.clouddn.com/1467836568.jpg',0.00,15.00,0,'','0',1,0,0,0,'2016-07-06 18:40:54','2016-07-06 18:40:54'),(590,45,1044,'姜小白1111','http://7xt4zt.com2.z0.glb.clouddn.com/1467836568.jpg',0.00,15.00,0,'1','0',1,0,0,0,'2016-07-06 18:40:54','2016-07-06 18:40:54'),(591,45,1044,'姜小白1111','http://7xt4zt.com2.z0.glb.clouddn.com/1467836568.jpg',0.00,15.00,0,'2','0',1,0,0,0,'2016-07-06 18:40:54','2016-07-06 18:40:54'),(592,45,1044,'姜小白1111','http://7xt4zt.com2.z0.glb.clouddn.com/1467836568.jpg',0.00,15.00,0,'3','0',1,0,0,0,'2016-07-06 18:40:54','2016-07-06 18:40:54'),(593,45,1044,'姜小白1111','http://7xt4zt.com2.z0.glb.clouddn.com/1467836568.jpg',0.00,15.00,0,'4','0',1,0,0,0,'2016-07-06 18:40:54','2016-07-06 18:40:54'),(594,45,1044,'姜小白1111','http://7xt4zt.com2.z0.glb.clouddn.com/1467836568.jpg',0.00,15.00,0,'5','0',1,0,0,0,'2016-07-06 18:40:54','2016-07-06 18:40:54'),(595,45,1044,'姜小白1111','http://7xt4zt.com2.z0.glb.clouddn.com/1467836568.jpg',0.00,15.00,0,'1','0',1,0,0,0,'2016-07-06 18:40:54','2016-07-06 18:40:54'),(596,45,1044,'姜小白1111','http://7xt4zt.com2.z0.glb.clouddn.com/1467836568.jpg',0.00,15.00,0,'2','0',1,0,0,0,'2016-07-06 18:40:54','2016-07-06 18:40:54'),(597,45,1044,'姜小白1111','http://7xt4zt.com2.z0.glb.clouddn.com/1467836568.jpg',0.00,15.00,0,'3','0',1,0,0,0,'2016-07-06 18:40:54','2016-07-06 18:40:54'),(598,45,1044,'姜小白1111','http://7xt4zt.com2.z0.glb.clouddn.com/1467836568.jpg',0.00,15.00,0,'4','0',1,0,0,0,'2016-07-06 18:40:54','2016-07-06 18:40:54'),(599,45,1044,'姜小白1111','http://7xt4zt.com2.z0.glb.clouddn.com/1467836568.jpg',0.00,15.00,0,'5','0',1,0,0,0,'2016-07-06 18:40:54','2016-07-06 18:40:54'),(600,36,910,'金富士番茄味棒饼','http://7xt4zt.com2.z0.glb.clouddn.com/1467837194.jpg',0.00,4.50,0,'','',0,1,0,0,'2016-07-06 18:41:19','2016-08-15 01:53:10'),(700,36,1086,'金富士蔬菜味棒饼','http://7xt4zt.com2.z0.glb.clouddn.com/1467833899.jpg',0.00,4.50,0,'','',0,1,0,0,'2016-07-06 18:41:17','2016-07-06 18:41:17'),(800,36,1100,'三辉提拉米苏','http://7xt4zt.com2.z0.glb.clouddn.com/1469693522.jpg',0.00,3.00,0,'','',0,1,0,0,'2016-07-06 18:41:17','2016-07-28 07:44:01'),(900,36,760,'康师傅3+2清新柠檬味','http://7xt4zt.com2.z0.glb.clouddn.com/1467837121.jpg',0.00,5.00,0,'','',0,1,0,0,'2016-07-06 18:41:16','2016-07-06 18:41:16');
/*!40000 ALTER TABLE `goods` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-08-18  8:50:39
