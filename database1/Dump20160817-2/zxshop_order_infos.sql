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
-- Table structure for table `order_infos`
--

DROP TABLE IF EXISTS `order_infos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_infos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单信息id',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `goods_id` int(10) unsigned NOT NULL,
  `c_id` int(10) unsigned DEFAULT NULL COMMENT '分类ID',
  `b_id` int(10) unsigned DEFAULT NULL COMMENT '品牌ID',
  `c_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '分类名称',
  `b_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '品牌名称',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '商品名称',
  `img` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '商品图片',
  `out_price` double(8,2) unsigned NOT NULL COMMENT '销售单价',
  `give_points` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '赠送积分',
  `spec` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '规格',
  `num` int(10) unsigned NOT NULL COMMENT '购买数量',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_infos_goods_id_foreign` (`goods_id`),
  KEY `order_infos_c_id_foreign` (`c_id`),
  KEY `order_infos_b_id_foreign` (`b_id`),
  CONSTRAINT `order_infos_b_id_foreign` FOREIGN KEY (`b_id`) REFERENCES `goods_brand` (`id`),
  CONSTRAINT `order_infos_c_id_foreign` FOREIGN KEY (`c_id`) REFERENCES `goods_categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_infos`
--

LOCK TABLES `order_infos` WRITE;
/*!40000 ALTER TABLE `order_infos` DISABLE KEYS */;
INSERT INTO `order_infos` VALUES (1,1,26087,NULL,1101,NULL,'澳门永辉','睡觉咯','http://7xt4zt.com1.z0.glb.clouddn.com/1467809340694.jpg',1.00,0,'1',8,'2016-07-06 14:16:04','2016-07-06 14:16:04'),(2,2,25371,63,792,'进口水果','其他','泰国甜角','http://7xt4zt.com2.z0.glb.clouddn.com/1467837326.jpg',1.22,0,'1kg',1,'2016-07-07 00:15:04','2016-07-07 00:15:04'),(3,3,25371,63,792,'进口水果','其他','泰国甜角','http://7xt4zt.com2.z0.glb.clouddn.com/1467837326.jpg',1.22,0,'1kg',1,'2016-07-07 00:57:10','2016-07-07 00:57:10'),(4,4,25371,63,792,'进口水果','其他','泰国甜角','http://7xt4zt.com2.z0.glb.clouddn.com/1467837326.jpg',1.22,0,'1kg',1,'2016-07-07 00:58:01','2016-07-07 00:58:01'),(5,4,3361,63,792,'进口水果','其他','菠萝蜜','http://7xt4zt.com2.z0.glb.clouddn.com/1467843588.jpg',20.00,0,'1kg',2,'2016-07-07 00:58:01','2016-07-07 00:58:01'),(6,4,12834,63,792,'进口水果','其他','进口金果','http://7xt4zt.com2.z0.glb.clouddn.com/1467836730.jpg',12.00,0,'个',1,'2016-07-07 00:58:01','2016-07-07 00:58:01'),(7,5,25371,63,792,'进口水果','其他','泰国甜角','http://7xt4zt.com2.z0.glb.clouddn.com/1467837326.jpg',1.22,0,'1kg',1,'2016-07-07 00:59:38','2016-07-07 00:59:38'),(8,6,25371,63,792,'进口水果','其他','泰国甜角','http://7xt4zt.com2.z0.glb.clouddn.com/1467837326.jpg',1.22,0,'1kg',1,'2016-07-07 02:03:59','2016-07-07 02:03:59'),(9,7,8712,77,994,'代购','张新发','张新发槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467837137.jpg',10.00,0,'包',1,'2016-07-07 02:05:39','2016-07-07 02:05:39'),(10,8,8712,77,994,'代购','张新发','张新发槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467837137.jpg',10.00,0,'包',1,'2016-07-07 03:21:07','2016-07-07 03:21:07'),(11,8,10769,77,1113,'代购','九总','九总槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467836540.jpg',10.00,0,'10元装',1,'2016-07-07 03:21:07','2016-07-07 03:21:07'),(12,8,12827,77,1081,'代购','芙蓉王','芙蓉王硬盒蓝','http://7xt4zt.com2.z0.glb.clouddn.com/1467832710.jpg',35.00,0,'包',1,'2016-07-07 03:21:07','2016-07-07 03:21:07'),(13,9,8712,77,994,'代购','张新发','张新发槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467837137.jpg',10.00,0,'包',1,'2016-07-07 03:21:15','2016-07-07 03:21:15'),(14,9,10769,77,1113,'代购','九总','九总槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467836540.jpg',10.00,0,'10元装',1,'2016-07-07 03:21:15','2016-07-07 03:21:15'),(15,9,12827,77,1081,'代购','芙蓉王','芙蓉王硬盒蓝','http://7xt4zt.com2.z0.glb.clouddn.com/1467832710.jpg',35.00,0,'包',1,'2016-07-07 03:21:15','2016-07-07 03:21:15'),(16,10,8712,77,994,'代购','张新发','张新发槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467837137.jpg',10.00,0,'包',1,'2016-07-07 04:30:13','2016-07-07 04:30:13'),(17,10,10769,77,1113,'代购','九总','九总槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467836540.jpg',10.00,0,'10元装',1,'2016-07-07 04:30:13','2016-07-07 04:30:13'),(18,10,12827,77,1081,'代购','芙蓉王','芙蓉王硬盒蓝','http://7xt4zt.com2.z0.glb.clouddn.com/1467832710.jpg',35.00,0,'包',1,'2016-07-07 04:30:13','2016-07-07 04:30:13'),(19,11,8712,77,994,'代购','张新发','张新发槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467837137.jpg',10.00,0,'包',1,'2016-07-07 07:13:01','2016-07-07 07:13:01'),(20,12,25371,63,792,'进口水果','其他','泰国甜角','http://7xt4zt.com2.z0.glb.clouddn.com/1467837326.jpg',1.22,0,'1kg',1,'2016-07-07 07:26:49','2016-07-07 07:26:49'),(21,12,12834,63,792,'进口水果','其他','进口金果','http://7xt4zt.com2.z0.glb.clouddn.com/1467836730.jpg',12.00,0,'个',1,'2016-07-07 07:26:49','2016-07-07 07:26:49'),(22,12,5729,63,792,'进口水果','其他','菲律宾香蕉','http://7xt4zt.com2.z0.glb.clouddn.com/1467838630.jpg',5.00,0,'4支',1,'2016-07-07 07:26:49','2016-07-07 07:26:49'),(23,12,26481,63,792,'进口水果','其他','特级火龙果','http://7xt4zt.com2.z0.glb.clouddn.com/1467843158.jpg',5.50,0,'500g',1,'2016-07-07 07:26:49','2016-07-07 07:26:49'),(24,13,25371,63,792,'进口水果','其他','泰国甜角','http://7xt4zt.com2.z0.glb.clouddn.com/1467837326.jpg',1.22,0,'1kg',1,'2016-07-07 07:39:06','2016-07-07 07:39:06'),(25,13,12834,63,792,'进口水果','其他','进口金果','http://7xt4zt.com2.z0.glb.clouddn.com/1467836730.jpg',12.00,0,'个',1,'2016-07-07 07:39:06','2016-07-07 07:39:06'),(26,14,25371,63,792,'进口水果','其他','泰国甜角','http://7xt4zt.com2.z0.glb.clouddn.com/1467837326.jpg',1.22,0,'1kg',1,'2016-07-07 07:45:23','2016-07-07 07:45:23'),(27,14,12834,63,792,'进口水果','其他','进口金果','http://7xt4zt.com2.z0.glb.clouddn.com/1467836730.jpg',12.00,0,'个',1,'2016-07-07 07:45:23','2016-07-07 07:45:23'),(28,14,24631,63,792,'进口水果','其他','水仙芒','http://7xt4zt.com2.z0.glb.clouddn.com/1467839964.jpg',9.00,0,'500g',1,'2016-07-07 07:45:23','2016-07-07 07:45:23'),(29,14,18755,63,792,'进口水果','其他','奶椰','http://7xt4zt.com2.z0.glb.clouddn.com/1467843251.jpg',10.00,0,'个',1,'2016-07-07 07:45:23','2016-07-07 07:45:23'),(30,14,23639,63,792,'进口水果','其他','蛇果','http://7xt4zt.com2.z0.glb.clouddn.com/1467839990.jpeg',8.00,0,'斤',1,'2016-07-07 07:45:23','2016-07-07 07:45:23'),(31,15,25371,63,792,'进口水果','其他','泰国甜角','http://7xt4zt.com2.z0.glb.clouddn.com/1467837326.jpg',1.22,0,'1kg',1,'2016-07-07 07:47:41','2016-07-07 07:47:41'),(32,15,12834,63,792,'进口水果','其他','进口金果','http://7xt4zt.com2.z0.glb.clouddn.com/1467836730.jpg',12.00,0,'个',1,'2016-07-07 07:47:41','2016-07-07 07:47:41'),(33,15,24631,63,792,'进口水果','其他','水仙芒','http://7xt4zt.com2.z0.glb.clouddn.com/1467839964.jpg',9.00,0,'500g',1,'2016-07-07 07:47:41','2016-07-07 07:47:41'),(34,15,18755,63,792,'进口水果','其他','奶椰','http://7xt4zt.com2.z0.glb.clouddn.com/1467843251.jpg',10.00,0,'个',1,'2016-07-07 07:47:41','2016-07-07 07:47:41'),(35,15,23639,63,792,'进口水果','其他','蛇果','http://7xt4zt.com2.z0.glb.clouddn.com/1467839990.jpeg',8.00,0,'斤',1,'2016-07-07 07:47:41','2016-07-07 07:47:41'),(36,16,25371,63,792,'进口水果','其他','泰国甜角','http://7xt4zt.com2.z0.glb.clouddn.com/1467837326.jpg',1.22,0,'1kg',1,'2016-07-07 08:23:22','2016-07-07 08:23:22'),(37,16,12834,63,792,'进口水果','其他','进口金果','http://7xt4zt.com2.z0.glb.clouddn.com/1467836730.jpg',12.00,0,'个',1,'2016-07-07 08:23:22','2016-07-07 08:23:22'),(38,16,24631,63,792,'进口水果','其他','水仙芒','http://7xt4zt.com2.z0.glb.clouddn.com/1467839964.jpg',9.00,0,'500g',1,'2016-07-07 08:23:22','2016-07-07 08:23:22'),(39,16,18755,63,792,'进口水果','其他','奶椰','http://7xt4zt.com2.z0.glb.clouddn.com/1467843251.jpg',10.00,0,'个',1,'2016-07-07 08:23:22','2016-07-07 08:23:22'),(40,16,23639,63,792,'进口水果','其他','蛇果','http://7xt4zt.com2.z0.glb.clouddn.com/1467839990.jpeg',8.00,0,'斤',1,'2016-07-07 08:23:22','2016-07-07 08:23:22'),(41,17,25371,63,792,'进口水果','其他','泰国甜角','http://7xt4zt.com2.z0.glb.clouddn.com/1467837326.jpg',1.22,0,'1kg',1,'2016-07-07 08:34:10','2016-07-07 08:34:10'),(42,17,12834,63,792,'进口水果','其他','进口金果','http://7xt4zt.com2.z0.glb.clouddn.com/1467836730.jpg',12.00,0,'个',1,'2016-07-07 08:34:10','2016-07-07 08:34:10'),(43,17,24631,63,792,'进口水果','其他','水仙芒','http://7xt4zt.com2.z0.glb.clouddn.com/1467839964.jpg',9.00,0,'500g',1,'2016-07-07 08:34:10','2016-07-07 08:34:10'),(44,18,3361,63,792,'进口水果','其他','菠萝蜜','http://7xt4zt.com2.z0.glb.clouddn.com/1467843588.jpg',20.00,0,'1kg',5,'2016-07-08 02:57:59','2016-07-08 02:57:59'),(45,18,12834,63,792,'进口水果','其他','进口金果','http://7xt4zt.com2.z0.glb.clouddn.com/1467836730.jpg',12.00,0,'个',3,'2016-07-08 02:57:59','2016-07-08 02:57:59'),(46,18,13870,63,792,'进口水果','其他','进口无籽红提','http://7xt4zt.com2.z0.glb.clouddn.com/1467836247.jpg',35.00,0,'500g',3,'2016-07-08 02:57:59','2016-07-08 02:57:59'),(47,18,24631,63,792,'进口水果','其他','水仙芒','http://7xt4zt.com2.z0.glb.clouddn.com/1467839964.jpg',9.00,0,'500g',1,'2016-07-08 02:57:59','2016-07-08 02:57:59'),(48,19,3361,63,792,'进口水果','其他','菠萝蜜','http://7xt4zt.com2.z0.glb.clouddn.com/1467843588.jpg',20.00,0,'1kg',6,'2016-07-08 03:08:34','2016-07-08 03:08:34'),(49,19,13870,63,792,'进口水果','其他','进口无籽红提','http://7xt4zt.com2.z0.glb.clouddn.com/1467836247.jpg',35.00,0,'500g',2,'2016-07-08 03:08:34','2016-07-08 03:08:34'),(50,19,24631,63,792,'进口水果','其他','水仙芒','http://7xt4zt.com2.z0.glb.clouddn.com/1467839964.jpg',9.00,0,'500g',1,'2016-07-08 03:08:34','2016-07-08 03:08:34'),(51,20,8712,77,994,'代购','张新发','张新发槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467837137.jpg',10.00,0,'包',5,'2016-07-08 03:09:06','2016-07-08 03:09:06'),(52,20,10769,77,1113,'代购','九总','九总槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467836540.jpg',10.00,0,'10元装',5,'2016-07-08 03:09:06','2016-07-08 03:09:06'),(53,21,8712,77,994,'代购','张新发','张新发槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467837137.jpg',10.00,0,'包',5,'2016-07-08 03:09:13','2016-07-08 03:09:13'),(54,21,10769,77,1113,'代购','九总','九总槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467836540.jpg',10.00,0,'10元装',5,'2016-07-08 03:09:13','2016-07-08 03:09:13'),(55,22,8712,77,994,'代购','张新发','张新发槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467837137.jpg',10.00,0,'包',5,'2016-07-08 03:09:42','2016-07-08 03:09:42'),(56,22,10769,77,1113,'代购','九总','九总槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467836540.jpg',10.00,0,'10元装',4,'2016-07-08 03:09:42','2016-07-08 03:09:42'),(57,23,16649,32,809,'牛奶乳品','蒙牛','蒙牛优益C芦荟味','http://7xt4zt.com2.z0.glb.clouddn.com/youyicluhuiwei.jpg',6.50,0,'340ml',1,'2016-07-08 04:06:33','2016-07-08 04:06:33'),(58,23,2832,36,1096,'饼干糕点','好吃点','好吃点香脆核桃 饼干','http://7xt4zt.com2.z0.glb.clouddn.com/1467836842.jpg',5.50,0,'208g',2,'2016-07-08 04:06:33','2016-07-08 04:06:33'),(59,23,4890,36,770,'饼干糕点','真巧','真巧涂层草莓味','http://7xt4zt.com2.z0.glb.clouddn.com/14645234234.jpg',3.00,0,'90g',1,'2016-07-08 04:06:33','2016-07-08 04:06:33'),(60,23,16943,46,951,'啤酒洋酒','雪花','雪花纯生啤酒','http://7xt4zt.com2.z0.glb.clouddn.com/xuhuachunsheng.jpg',3.50,0,'580ml',2,'2016-07-08 04:06:33','2016-07-08 04:06:33'),(61,23,15473,32,802,'牛奶乳品','伊利','伊利纯牛奶','http://7xt4zt.com2.z0.glb.clouddn.com/yilichunniunai.jpg',3.50,0,'250ml',1,'2016-07-08 04:06:33','2016-07-08 04:06:33'),(62,23,19588,31,1486,'水/饮料','可口','可口可乐','http://7xt4zt.com2.z0.glb.clouddn.com/kekoukele.jpg',3.00,0,'600ml',2,'2016-07-08 04:06:33','2016-07-08 04:06:33'),(63,23,21646,31,800,'水/饮料','统一','统一绿茶','http://7xt4zt.com2.z0.glb.clouddn.com/tingyilvcha.jpg',3.00,0,'500ml',2,'2016-07-08 04:06:33','2016-07-08 04:06:33'),(64,23,15767,32,809,'牛奶乳品','蒙牛','特仑苏纯牛奶','http://7xt4zt.com2.z0.glb.clouddn.com/tls.jpg',5.50,0,'250ml',1,'2016-07-08 04:06:33','2016-07-08 04:06:33'),(65,23,23704,31,807,'水/饮料','农夫山泉','农夫山泉天然水','http://7xt4zt.com2.z0.glb.clouddn.com/nongfushanquanyoudiantian.jpg',2.00,0,'550ml',2,'2016-07-08 04:06:33','2016-07-08 04:06:33'),(66,23,14003,40,760,'方便速食','康师傅','康师傅卤香牛肉面','http://7xt4zt.com2.z0.glb.clouddn.com/ksflrm.jpg',5.00,0,'124g',3,'2016-07-08 04:06:33','2016-07-08 04:06:33'),(67,23,4596,36,770,'饼干糕点','真巧','真巧涂层巧克力味','http://7xt4zt.com2.z0.glb.clouddn.com/235454654645.jpg',4.00,0,'90g',1,'2016-07-08 04:06:33','2016-07-08 04:06:33'),(68,23,6654,36,768,'饼干糕点','卡夫','卡夫奶盐味太平梳打饼','http://7xt4zt.com2.z0.glb.clouddn.com/kafuyanwei.jpg',4.00,0,'100g',1,'2016-07-08 04:06:33','2016-07-08 04:06:33'),(69,24,11357,77,1115,'代购','和成天下','和成天下槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467832477.jpg',10.00,0,'10元装',1,'2016-07-08 07:39:36','2016-07-08 07:39:36'),(70,25,11357,77,1115,'代购','和成天下','和成天下槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467832477.jpg',10.00,0,'10元装',1,'2016-07-08 07:40:06','2016-07-08 07:40:06'),(71,26,11357,77,1115,'代购','和成天下','和成天下槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467832477.jpg',10.00,0,'10元装',1,'2016-07-08 07:50:07','2016-07-08 07:50:07'),(72,27,11357,77,1115,'代购','和成天下','和成天下槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467832477.jpg',10.00,0,'10元装',1,'2016-07-08 07:50:54','2016-07-08 07:50:54'),(73,28,8712,77,994,'代购','张新发','张新发槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467837137.jpg',10.00,0,'包',1,'2016-07-08 07:54:19','2016-07-08 07:54:19'),(74,29,8712,77,994,'代购','张新发','张新发槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467837137.jpg',10.00,0,'包',1,'2016-07-08 08:07:52','2016-07-08 08:07:52'),(75,30,8712,77,994,'代购','张新发','张新发槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467837137.jpg',10.00,0,'包',1,'2016-07-08 08:28:11','2016-07-08 08:28:11'),(76,31,8712,77,994,'代购','张新发','张新发槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467837137.jpg',10.00,0,'包',1,'2016-07-11 08:46:42','2016-07-11 08:46:42'),(77,32,8712,77,994,'代购','张新发','张新发槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467837137.jpg',10.00,0,'包',1,'2016-07-11 08:55:56','2016-07-11 08:55:56'),(78,33,22528,31,760,'水/饮料','康师傅','康师傅茉莉蜜茶','http://7xt4zt.com2.z0.glb.clouddn.com/1467839049.jpg',3.00,0,'500ml',1,'2016-07-11 09:02:36','2016-07-11 09:02:36'),(79,34,22528,31,760,'水/饮料','康师傅','康师傅茉莉蜜茶','http://7xt4zt.com2.z0.glb.clouddn.com/1467839049.jpg',3.00,0,'500ml',1,'2016-07-11 09:03:04','2016-07-11 09:03:04'),(80,35,22528,31,760,'水/饮料','康师傅','康师傅茉莉蜜茶','http://7xt4zt.com2.z0.glb.clouddn.com/1467839049.jpg',3.00,0,'500ml',1,'2016-07-11 09:05:38','2016-07-11 09:05:38'),(81,35,8712,77,994,'代购','张新发','张新发槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467837137.jpg',10.00,0,'包',1,'2016-07-11 09:05:38','2016-07-11 09:05:38'),(82,36,8712,77,994,'代购','张新发','张新发槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467837137.jpg',10.00,0,'包',1,'2016-07-11 09:17:19','2016-07-11 09:17:19'),(83,37,22528,31,760,'水/饮料','康师傅','康师傅茉莉蜜茶','http://7xt4zt.com2.z0.glb.clouddn.com/1467839049.jpg',3.00,0,'500ml',1,'2016-07-11 09:17:20','2016-07-11 09:17:20'),(84,38,16943,46,951,'啤酒洋酒','雪花','雪花纯生啤酒','http://7xt4zt.com2.z0.glb.clouddn.com/xuhuachunsheng.jpg',3.50,0,'580ml',1,'2016-07-11 09:24:26','2016-07-11 09:24:26'),(85,39,8712,77,994,'代购','张新发','张新发槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467837137.jpg',10.00,0,'包',1,'2016-07-11 09:25:40','2016-07-11 09:25:40'),(86,40,8712,77,994,'代购','张新发','张新发槟榔','http://7xt4zt.com2.z0.glb.clouddn.com/1467837137.jpg',10.00,0,'包',1,'2016-07-11 09:25:56','2016-07-11 09:25:56'),(87,41,22528,31,760,'水/饮料','康师傅','康师傅茉莉蜜茶','http://7xt4zt.com2.z0.glb.clouddn.com/1467839049.jpg',3.00,0,'500ml',1,'2016-07-11 09:38:17','2016-07-11 09:38:17'),(88,42,22528,31,760,'水/饮料','康师傅','康师傅茉莉蜜茶','http://7xt4zt.com2.z0.glb.clouddn.com/1467839049.jpg',3.00,0,'500ml',1,'2016-07-11 09:43:36','2016-07-11 09:43:36'),(89,43,22528,31,760,'水/饮料','康师傅','康师傅茉莉蜜茶','http://7xt4zt.com2.z0.glb.clouddn.com/1467839049.jpg',3.00,0,'500ml',1,'2016-07-11 09:44:20','2016-07-11 09:44:20'),(90,44,22528,31,760,'水/饮料','康师傅','康师傅茉莉蜜茶','http://7xt4zt.com2.z0.glb.clouddn.com/1467839049.jpg',3.00,0,'500ml',1,'2016-07-11 09:52:13','2016-07-11 09:52:13'),(91,45,22528,31,760,'水/饮料','康师傅','康师傅茉莉蜜茶','http://7xt4zt.com2.z0.glb.clouddn.com/1467839049.jpg',3.00,0,'500ml',1,'2016-07-11 09:53:19','2016-07-11 09:53:19'),(92,46,22528,31,760,'水/饮料','康师傅','康师傅茉莉蜜茶','http://7xt4zt.com2.z0.glb.clouddn.com/1467839049.jpg',3.00,0,'500ml',1,'2016-07-11 09:55:03','2016-07-11 09:55:03'),(93,47,25371,63,792,'进口水果','其他','泰国甜角','http://7xt4zt.com2.z0.glb.clouddn.com/1467837326.jpg',1.22,0,'1kg',1,'2016-07-12 01:23:07','2016-07-12 01:23:07'),(94,47,3361,63,792,'进口水果','其他','菠萝蜜','http://7xt4zt.com2.z0.glb.clouddn.com/1467843588.jpg',20.00,0,'1kg',1,'2016-07-12 01:23:07','2016-07-12 01:23:07'),(95,47,12834,63,792,'进口水果','其他','进口金果','http://7xt4zt.com2.z0.glb.clouddn.com/1467836730.jpg',12.00,0,'个',1,'2016-07-12 01:23:07','2016-07-12 01:23:07'),(96,47,24631,63,792,'进口水果','其他','水仙芒','http://7xt4zt.com2.z0.glb.clouddn.com/1467839964.jpg',9.00,0,'500g',1,'2016-07-12 01:23:07','2016-07-12 01:23:07'),(97,47,25149,63,792,'进口水果','其他','泰国金柚','http://7xt4zt.com2.z0.glb.clouddn.com/1467836663.jpg',15.00,0,'500g',1,'2016-07-12 01:23:07','2016-07-12 01:23:07');
/*!40000 ALTER TABLE `order_infos` ENABLE KEYS */;
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
