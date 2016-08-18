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
-- Table structure for table `store_goods`
--

DROP TABLE IF EXISTS `store_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL,
  `goods_id` int(10) unsigned NOT NULL,
  `nav_id` int(10) unsigned DEFAULT NULL COMMENT '栏目ID',
  `c_id` int(10) unsigned DEFAULT '0' COMMENT '分类ID',
  `b_id` int(10) unsigned DEFAULT NULL COMMENT '品牌ID',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '商品名称',
  `img` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '商品图片',
  `in_price` double(8,2) unsigned DEFAULT NULL COMMENT '进价单价',
  `out_price` double(8,2) unsigned NOT NULL COMMENT '销售单价',
  `give_points` int(10) unsigned DEFAULT '0' COMMENT '赠送积分',
  `spec` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '规格',
  `desc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '描述',
  `stock` int(11) NOT NULL DEFAULT '0' COMMENT '库存',
  `is_open` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否上架',
  `is_checked` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `out_num` int(11) DEFAULT '0' COMMENT '0',
  PRIMARY KEY (`id`),
  KEY `store_goods_store_id_foreign` (`store_id`),
  KEY `store_goods_nav_id_foreign` (`nav_id`),
  CONSTRAINT `store_goods_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `store_infos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2742 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_goods`
--

LOCK TABLES `store_goods` WRITE;
/*!40000 ALTER TABLE `store_goods` DISABLE KEYS */;
INSERT INTO `store_goods` VALUES (1416,21,555,1,45,1298,'小红星二锅头56度','',NULL,4.50,0,'',NULL,0,0,0,0,'2016-07-06 18:40:54','2016-07-29 05:26:57',0),(1417,21,556,2,45,1044,'江是','http://7xt4zt.com2.z0.glb.clouddn.com/1467836568.jpg',NULL,15.00,0,'',NULL,0,1,1,0,'2016-07-06 18:40:54','2016-08-05 07:18:24',0),(1418,21,557,3,45,1247,'中国劲酒35°','http://7xt4zt.com2.z0.glb.clouddn.com/1467835173.jpg',NULL,10.00,0,'',NULL,0,0,0,0,'2016-07-06 18:40:55','2016-07-29 05:26:10',0),(1419,21,558,4,45,1420,'椰岛鹿龟酒','http://7xt4zt.com2.z0.glb.clouddn.com/1467837790.jpg',NULL,9.00,0,'',NULL,0,1,1,0,'2016-07-06 18:40:56','2016-07-29 05:34:37',0),(1420,21,559,5,36,760,'康师傅3+2草莓牛奶味','http://7xt4zt.com2.z0.glb.clouddn.com/1467839083.jpg',NULL,5.00,0,'',NULL,0,1,1,0,'2016-07-06 18:40:56','2016-07-29 10:10:31',0),(1421,21,560,6,36,768,'卡夫太平梳打饼香葱味','http://7xt4zt.com2.z0.glb.clouddn.com/1467837140.jpg',NULL,4.00,0,'',NULL,0,1,1,0,'2016-07-06 18:40:58','2016-07-29 05:31:05',0),(1422,21,561,6,36,752,'旺旺仙贝','http://7xt4zt.com2.z0.glb.clouddn.com/1467838065.jpg',NULL,4.00,0,'',NULL,0,1,1,0,'2016-07-06 18:40:58','2016-07-29 05:24:52',0),(1423,21,562,5,36,752,'旺旺雪饼','http://7xt4zt.com2.z0.glb.clouddn.com/1467837900.jpg',NULL,4.50,0,'',NULL,0,1,1,0,'2016-07-06 18:40:59','2016-07-29 04:25:54',0),(1424,21,563,4,36,762,'好丽友6枚蛋黄派奶油夹心','http://7xt4zt.com2.z0.glb.clouddn.com/1467837302.jpg',NULL,9.50,0,'',NULL,0,1,1,0,'2016-07-06 18:41:01','2016-07-29 04:25:52',0),(1425,21,564,3,36,1096,'好吃点香脆杏仁饼','',NULL,5.50,0,'',NULL,0,1,1,0,'2016-07-06 18:41:01','2016-07-29 04:26:56',0),(1426,21,565,2,36,1096,'好吃点香脆核桃 饼干','http://7xt4zt.com2.z0.glb.clouddn.com/1467831990.jpg',NULL,5.50,0,'',NULL,0,1,1,0,'2016-07-06 18:41:03','2016-07-29 04:25:18',0),(1427,21,566,1,36,844,'达利园瑞士卷橙汁味','',NULL,6.50,0,'',NULL,0,1,1,0,'2016-07-06 18:41:03','2016-07-29 04:26:40',0),(1428,21,567,1,36,844,'达利园瑞士卷草莓味','http://7xt4zt.com2.z0.glb.clouddn.com/1467840019.jpg',NULL,6.50,0,'',NULL,0,1,1,0,'2016-07-06 18:41:04','2016-07-29 04:30:06',0),(1429,21,568,2,36,1103,'爱尚非蛋糕(椰香味)','http://7xt4zt.com2.z0.glb.clouddn.com/1467835455.jpg',NULL,2.50,0,'',NULL,0,1,1,0,'2016-07-06 18:41:05','2016-07-29 04:24:49',0),(1430,21,569,3,36,1087,'嘉士利巧克力味威化饼','http://7xt4zt.com2.z0.glb.clouddn.com/1467834809.jpg',NULL,3.00,0,'',NULL,0,1,1,0,'2016-07-06 18:41:05','2016-07-29 04:24:57',0),(1431,21,570,4,36,799,'旺仔小馒头原味','http://7xt4zt.com2.z0.glb.clouddn.com/1467833842.jpg',NULL,8.00,0,'',NULL,0,1,1,0,'2016-07-06 18:41:07','2016-07-29 04:24:14',0),(1432,21,571,5,36,770,'真巧涂层巧克力味','http://7xt4zt.com2.z0.glb.clouddn.com/1467836585.jpg',NULL,4.00,0,'',NULL,0,1,1,0,'2016-07-06 18:41:08','2016-07-29 04:23:03',0),(1433,21,572,6,36,770,'真巧涂层草莓味','',NULL,3.00,0,'',NULL,0,1,1,0,'2016-07-06 18:41:08','2016-07-29 04:24:00',0),(1434,21,573,6,36,1087,'嘉士利夹心蓝莓味果酱','http://7xt4zt.com2.z0.glb.clouddn.com/1467836492.jpg',NULL,3.50,0,'',NULL,0,1,1,0,'2016-07-06 18:41:09','2016-07-29 04:21:43',0),(1435,21,574,5,36,1087,'嘉士利夹心凤梨味果酱','http://7xt4zt.com2.z0.glb.clouddn.com/1467832264.jpg',NULL,3.00,0,'',NULL,0,1,1,0,'2016-07-06 18:41:09','2016-07-29 04:21:59',0),(1436,21,575,4,36,752,'123132','http://7xt4zt.com2.z0.glb.clouddn.com/1467835749.jpg',NULL,3.00,0,'',NULL,0,0,0,0,'2016-07-06 18:41:10','2016-08-15 03:07:48',0),(1437,21,576,3,36,752,'米老头原味蛋黄煎饼/222212','',NULL,5.00,0,'',NULL,0,0,0,0,'2016-07-06 18:41:11','2016-08-15 03:07:20',0),(1438,21,577,2,36,752,'回头客铜锣烧12123','',NULL,5.00,0,'',NULL,0,0,0,0,'2016-07-06 18:41:12','2016-08-12 10:25:12',0),(1439,21,578,1,36,752,'卡夫奶盐味太平梳打饼/11233123','',NULL,4.00,0,'',NULL,0,0,0,0,'2016-07-06 18:41:12','2016-08-15 03:07:02',0),(2718,24,555,9,45,1298,'小红星二锅头56度','',NULL,4.50,0,'',NULL,0,0,0,0,'2016-08-10 01:13:27','2016-08-10 01:13:27',0),(2719,24,556,10,45,1044,'江是','http://7xt4zt.com2.z0.glb.clouddn.com/1467836568.jpg',NULL,15.00,0,'',NULL,0,1,1,0,'2016-08-10 01:13:27','2016-08-10 01:13:27',0),(2720,24,557,9,45,1247,'中国劲酒35°','http://7xt4zt.com2.z0.glb.clouddn.com/1467835173.jpg',NULL,10.00,0,'',NULL,0,0,0,0,'2016-08-10 01:13:28','2016-08-10 01:13:28',0),(2721,24,558,10,45,1420,'椰岛鹿龟酒','http://7xt4zt.com2.z0.glb.clouddn.com/1467837790.jpg',NULL,9.00,0,'',NULL,0,1,1,0,'2016-08-10 01:13:28','2016-08-10 01:13:28',0),(2722,24,559,9,36,760,'康师傅3+2草莓牛奶味','http://7xt4zt.com2.z0.glb.clouddn.com/1467839083.jpg',NULL,5.00,0,'',NULL,0,1,1,0,'2016-08-10 01:13:28','2016-08-10 01:13:28',0),(2723,24,560,10,36,768,'卡夫太平梳打饼香葱味','http://7xt4zt.com2.z0.glb.clouddn.com/1467837140.jpg',NULL,4.00,0,'',NULL,0,1,1,0,'2016-08-10 01:13:28','2016-08-10 01:13:28',0),(2724,24,561,10,36,752,'旺旺仙贝','http://7xt4zt.com2.z0.glb.clouddn.com/1467838065.jpg',NULL,4.00,0,'',NULL,0,1,1,0,'2016-08-10 01:13:28','2016-08-10 01:13:28',0),(2725,24,562,9,36,752,'旺旺雪饼','http://7xt4zt.com2.z0.glb.clouddn.com/1467837900.jpg',NULL,4.50,0,'',NULL,0,1,1,0,'2016-08-10 01:13:28','2016-08-10 01:13:28',0),(2726,24,563,10,36,762,'好丽友6枚蛋黄派奶油夹心','http://7xt4zt.com2.z0.glb.clouddn.com/1467837302.jpg',NULL,9.50,0,'',NULL,0,1,1,0,'2016-08-10 01:13:28','2016-08-10 01:13:28',0),(2727,24,564,9,36,1096,'好吃点香脆杏仁饼','',NULL,5.50,0,'',NULL,0,1,1,0,'2016-08-10 01:13:28','2016-08-10 01:13:28',0),(2728,24,565,10,36,1096,'好吃点香脆核桃 饼干','http://7xt4zt.com2.z0.glb.clouddn.com/1467831990.jpg',NULL,5.50,0,'',NULL,0,1,1,0,'2016-08-10 01:13:28','2016-08-10 01:13:28',0),(2729,24,566,9,36,844,'达利园瑞士卷橙汁味','',NULL,6.50,0,'',NULL,0,1,1,0,'2016-08-10 01:13:28','2016-08-10 01:13:28',0),(2730,24,567,9,36,844,'达利园瑞士卷草莓味','http://7xt4zt.com2.z0.glb.clouddn.com/1467840019.jpg',NULL,6.50,0,'',NULL,0,1,1,0,'2016-08-10 01:13:28','2016-08-10 01:13:28',0),(2731,24,568,10,36,1103,'爱尚非蛋糕(椰香味)','http://7xt4zt.com2.z0.glb.clouddn.com/1467835455.jpg',NULL,2.50,0,'',NULL,0,1,1,0,'2016-08-10 01:13:28','2016-08-10 01:13:28',0),(2732,24,569,9,36,1087,'嘉士利巧克力味威化饼','http://7xt4zt.com2.z0.glb.clouddn.com/1467834809.jpg',NULL,3.00,0,'',NULL,0,1,1,0,'2016-08-10 01:13:28','2016-08-10 01:13:28',0),(2733,24,570,10,36,799,'旺仔小馒头原味','http://7xt4zt.com2.z0.glb.clouddn.com/1467833842.jpg',NULL,8.00,0,'',NULL,0,1,1,0,'2016-08-10 01:13:28','2016-08-10 01:13:28',0),(2734,24,571,9,36,770,'真巧涂层巧克力味','http://7xt4zt.com2.z0.glb.clouddn.com/1467836585.jpg',NULL,4.00,0,'',NULL,0,1,1,0,'2016-08-10 01:13:28','2016-08-10 01:13:28',0),(2735,24,572,10,36,770,'真巧涂层草莓味','',NULL,3.00,0,'',NULL,0,1,1,0,'2016-08-10 01:13:28','2016-08-10 01:13:28',0),(2736,24,573,10,36,1087,'嘉士利夹心蓝莓味果酱','http://7xt4zt.com2.z0.glb.clouddn.com/1467836492.jpg',NULL,3.50,0,'',NULL,0,1,1,0,'2016-08-10 01:13:29','2016-08-10 01:13:29',0),(2737,24,574,9,36,1087,'嘉士利夹心凤梨味果酱','http://7xt4zt.com2.z0.glb.clouddn.com/1467832264.jpg',NULL,3.00,0,'',NULL,0,1,1,0,'2016-08-10 01:13:29','2016-08-10 01:13:29',0),(2738,24,575,10,36,1087,'嘉士利夹心草莓味果酱','http://7xt4zt.com2.z0.glb.clouddn.com/1467835749.jpg',NULL,3.00,0,'',NULL,0,1,1,0,'2016-08-10 01:13:29','2016-08-10 01:13:29',0),(2739,24,576,9,36,1350,'米老头原味蛋黄煎饼','',NULL,5.00,0,'',NULL,0,1,1,0,'2016-08-10 01:13:29','2016-08-10 01:13:29',0),(2740,24,577,10,36,1093,'回头客铜锣烧 红豆味','',NULL,5.00,0,'',NULL,0,1,1,0,'2016-08-10 01:13:29','2016-08-10 01:13:29',0),(2741,24,578,9,36,768,'卡夫奶盐味太平梳打饼','',NULL,4.00,0,'',NULL,0,1,1,0,'2016-08-10 01:13:29','2016-08-10 01:13:29',0);
/*!40000 ALTER TABLE `store_goods` ENABLE KEYS */;
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
