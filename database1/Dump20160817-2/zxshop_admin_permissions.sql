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
-- Table structure for table `admin_permissions`
--

DROP TABLE IF EXISTS `admin_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '菜单父ID',
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图标class',
  `name` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `is_menu` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否作为菜单显示,[1|0]',
  `sort` tinyint(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `level` tinyint(1) DEFAULT '0' COMMENT '层级',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_permissions`
--

LOCK TABLES `admin_permissions` WRITE;
/*!40000 ALTER TABLE `admin_permissions` DISABLE KEYS */;
INSERT INTO `admin_permissions` VALUES (1,0,'icon-group','权限','',1,100,0,NULL,NULL),(2,1,NULL,'权限管理','/alpha/permissions',1,1,1,NULL,NULL),(3,1,NULL,'角色管理','/alpha/roles',1,2,1,NULL,NULL),(4,1,NULL,'用户管理','/alpha/admin/users',1,3,1,NULL,NULL),(5,2,NULL,'添加权限','/alpha/permission/add',0,0,2,NULL,'2016-07-20 11:15:26'),(6,0,'icon-dashboard','首页','/alpha/index',1,1,0,NULL,NULL),(7,0,'icon-book','商品库','',1,3,0,NULL,NULL),(8,7,NULL,'商品管理','/alpha/goods',1,0,1,NULL,NULL),(9,8,NULL,'添加商品','/alpha/goods/add,/alpha/goods/brand/{cid},/alpha//goods/category/pid/{pid}',0,0,2,NULL,'2016-05-05 22:28:27'),(10,8,NULL,'修改商品','/alpha/goods/update,/alpha/goods/category/level/{pid},/alpha/goods/info/{id},/alpha/goods/category/pid/{pid},/alpha/goods/brand/{cid}',0,0,2,NULL,'2016-05-05 22:30:14'),(13,8,NULL,'删除商品','/alpha/goods/del/{id}',0,0,2,NULL,NULL),(14,0,'icon-sitemap','店铺','',1,4,0,'2016-05-05 19:53:59','2016-05-05 19:53:59'),(15,14,NULL,'店铺管理','/alpha/stores/infos',1,0,1,'2016-05-05 19:54:32','2016-05-06 00:19:17'),(22,3,NULL,'查看角色权限','/alpha/permission/role/relation/{rid}',0,0,2,'2016-05-05 22:54:52','2016-05-05 22:54:52'),(24,3,NULL,'添加角色','/alpha/role/add',0,0,2,'2016-05-05 22:57:57','2016-05-05 22:57:57'),(25,3,NULL,'修改角色','/alpah/role/del/{id},/alpha/role/info/{id},/alpha/role/update,/alpha/permission/role/delete,/alpha/permission/role/add',0,0,2,'2016-05-05 22:58:29','2016-05-05 23:00:19'),(26,15,NULL,'修改店铺','/alpha/stores/update,/alpha/stores/info/{id},/alpha/stores/categories,/alpha/areas,/alpha/upload/qiniu',0,0,2,'2016-05-06 00:24:07','2016-05-06 00:25:47'),(27,15,NULL,'添加店铺','/alpha/stores/add,/alpha/stores/categories,/alpha/areas,/alpha/upload/qiniu,/alpha/upload/qiniu',0,0,2,'2016-05-06 00:25:37','2016-05-06 00:25:37'),(28,14,NULL,'店员管理','/alpha/store/user',1,2,1,'2016-05-08 19:22:33','2016-05-08 19:22:33'),(29,14,NULL,'分类管理','/alpha/stores/categories/list',1,6,1,'2016-05-08 22:35:29','2016-05-08 22:35:29'),(30,29,NULL,'添加店铺分类','/alpha/stores/category/add',0,0,2,'2016-05-08 23:36:43','2016-05-08 23:36:43'),(31,47,NULL,'修改店铺','/alpha/stores/category/update,',0,0,2,'2016-05-08 23:42:25','2016-07-28 10:11:33'),(32,7,NULL,'分类管理','/alpha/goods/category/list',1,1,1,'2016-05-09 01:23:10','2016-05-09 01:23:10'),(33,32,NULL,'修改分类','/alpha/goods/category/info/{id},/alpha/goods/category/update',0,1,2,'2016-05-09 16:47:47','2016-05-09 16:47:47'),(34,32,NULL,'修改品牌','/alpha/goods/brand/update,/alpha/goods/brand/info/{id}',0,0,2,'2016-05-09 20:02:28','2016-05-09 20:02:28'),(35,32,NULL,'添加品类','/alpha/goods/category/add,/alpha/goods/brand/add',0,0,2,'2016-05-10 17:05:39','2016-05-10 17:05:39'),(36,32,NULL,'删除品类','/alpha/goods/category/del/{id},/alpha/goods/brand/del/{id}',0,0,2,'2016-05-10 18:15:40','2016-05-10 18:15:40'),(37,0,'icon-shopping-cart','订单','',1,2,0,'2016-05-18 18:59:32','2016-05-18 18:59:32'),(38,37,NULL,'所有订单','/alpha/order/list',1,0,1,'2016-05-18 22:11:07','2016-05-18 22:11:07'),(39,37,NULL,'未配送订单','/alpha/order/notdelivery',1,3,1,'2016-05-19 01:18:53','2016-05-19 01:18:53'),(40,37,NULL,'已配送订单','/alpha/order/delivery',1,2,1,'2016-05-19 01:20:16','2016-05-19 01:20:16'),(41,37,NULL,'意外订单','/alpha/order/accident',1,1,1,'2016-05-19 01:21:06','2016-05-19 01:21:06'),(42,37,NULL,'处理订单','/alpha/order/change/status/{id}',0,0,1,'2016-05-19 17:04:18','2016-05-19 17:04:18'),(43,0,' icon-male','用户','',1,5,0,'2016-05-23 23:06:39','2016-05-23 23:06:39'),(44,43,NULL,'用户列表','/alpha/user/list',1,1,1,'2016-05-23 23:07:20','2016-05-23 23:07:20'),(45,0,'icon-jpy','财务','',1,6,0,'2016-06-28 00:51:01','2016-06-28 00:51:01'),(46,45,NULL,'提现申请','/alpha/finance/cash',1,0,1,'2016-06-28 00:51:26','2016-06-28 00:51:26'),(47,14,NULL,'商品管理','/alpha/store/goods/{storeId},/alpha/store/goods/info/{goodsId}',0,0,1,'2016-07-06 12:28:24','2016-07-29 03:47:04'),(48,47,NULL,'修改商品','/alpha/store/goods/update',0,0,2,'2016-07-06 12:28:48','2016-07-28 10:10:49'),(50,0,'icon-jpy','活动','',1,7,0,'2016-07-12 07:13:28','2016-07-12 07:13:28'),(57,2,NULL,'删除权限','/alpha/permission/del/{id}',1,1,2,'2016-07-13 08:12:11','2016-07-13 08:12:11'),(58,2,NULL,'修改权限','/alpha/permission/update,/alpha/permission/info/{id},/alpha/permission/level/{level}',1,0,2,'2016-07-13 08:13:54','2016-07-20 10:50:25'),(79,3,NULL,'角色删除','/alpha/role/del/{id}',0,0,2,'2016-07-14 02:41:06','2016-07-14 02:41:06'),(80,4,NULL,'添加用户','/alpha/admin/user/add',0,3,2,'2016-07-14 02:43:04','2016-07-14 02:43:04'),(81,4,NULL,'修改用户设置','/alpha/admin/user/update,/alpha/roles/user/ajax/{userId},/alpha/admin/user/info/{id},/alpha/roles/user/ajax/{userId},/alpha/admin/user/info/{id}',0,3,2,'2016-07-14 02:44:36','2016-07-14 02:44:36'),(82,4,NULL,'删除用户','/alpha/admin/user/del/{id}',0,3,2,'2016-07-14 02:54:22','2016-07-14 02:54:22'),(88,4,NULL,'获取角色','/alpha/roles/ajax',0,3,2,'2016-07-14 06:38:10','2016-07-14 06:38:10'),(89,37,NULL,'配送中订单','/alpha/order/dispatching',1,4,1,'2016-07-14 08:14:35','2016-07-14 09:21:11'),(90,50,NULL,'优惠券','/alpha/Activity/coupon',1,1,1,'2016-07-18 03:16:36','2016-07-22 10:08:46'),(91,90,NULL,'优惠券关闭','/alpha/Activity/couponClose/{id}',1,1,2,'2016-07-18 06:21:50','2016-07-26 07:27:44'),(92,90,NULL,'优惠券增加','/alpha/Activity/couponAdd',1,1,2,'2016-07-18 07:15:23','2016-07-18 07:15:23'),(94,50,NULL,'轮播图管理','/alpha/Activity/bannerVersion',1,1,1,'2016-07-21 06:56:25','2016-07-21 07:00:14'),(95,94,NULL,'轮播图存储','/alpha/Activity/save',0,1,2,'2016-07-21 06:59:38','2016-07-21 06:59:38'),(96,46,NULL,'审核通过','/alpha/finance/withdrawAgree/{id}',0,1,2,'2016-07-26 03:40:30','2016-07-26 04:13:22'),(97,46,NULL,'审核拒绝','/alpha/finance/withdrawReject',0,1,2,'2016-07-26 03:55:50','2016-07-26 05:41:48'),(98,90,NULL,'优惠券的开启','/alpha/Activity/couponOpen/{id}',0,1,2,'2016-07-26 07:28:21','2016-07-26 07:28:21'),(99,45,NULL,'审核通过的提现','/alpha/finance/checked',1,1,1,'2016-07-26 08:22:57','2016-07-26 08:22:57'),(100,99,NULL,'完成提现','/alpha/finance/finish_withdraw',0,1,2,'2016-07-26 09:17:01','2016-07-26 09:17:01'),(101,14,NULL,'店铺商品审核','/alpha/store/goods/by/nocheck',1,1,1,'2016-07-27 03:02:44','2016-07-27 03:06:25'),(102,101,NULL,'通过审核','/alpha/store/goods/check/{goodsId}',0,0,2,'2016-07-27 03:17:43','2016-07-27 03:17:43'),(103,37,NULL,'已结算订单','/alpha/order/balance',1,5,1,'2016-07-27 04:47:02','2016-08-02 10:15:41'),(104,103,NULL,'导出Excel','/alpha/order/balance/getOrderExport',0,1,2,'2016-07-27 06:40:45','2016-07-27 06:40:45'),(105,103,NULL,'订单导入','/alpha/order/import',0,1,2,'2016-07-27 10:45:57','2016-07-27 10:45:57'),(107,47,NULL,'审核修改商品','/alpha/store/goods/by/update',0,1,2,'2016-07-29 07:08:05','2016-07-29 07:08:05'),(110,8,NULL,'商品导出','/alpha/goods/excleExport',0,1,2,'2016-08-02 02:23:07','2016-08-02 02:23:07'),(111,8,NULL,'商品导入','/alpha/goods/import',0,0,2,'2016-08-02 02:27:06','2016-08-02 02:27:06'),(112,37,NULL,'已完成订单','/alpha/order/completd',1,1,1,'2016-08-02 03:03:37','2016-08-02 10:15:28'),(113,47,NULL,'商品导入','/alpha/store/goods/import',0,1,2,'2016-08-02 04:36:49','2016-08-02 04:36:49'),(114,45,NULL,'提现完成','/alpha/finance/cashFinishList',1,2,1,'2016-08-02 09:20:58','2016-08-02 09:20:58'),(115,47,NULL,'银行卡修改','/alpha/stores/bankinfo',0,6,2,'2016-08-03 03:10:11','2016-08-03 03:10:11'),(116,1,NULL,'修改密码','/alpha/role/password',1,5,1,'2016-08-03 09:29:44','2016-08-03 09:35:33'),(118,0,'glyphicon glyphicon-sound-dolby','加盟商','/alpha/franchisee/list',1,6,0,'2016-08-08 03:29:44','2016-08-08 03:36:09'),(119,118,NULL,'已联系','/alpha/franchisee/contactList',1,0,1,'2016-08-08 08:23:32','2016-08-10 01:59:00'),(120,118,NULL,'修改信息','/alpha/franchisee/update',0,1,1,'2016-08-09 09:36:15','2016-08-09 09:36:15'),(121,118,NULL,'未联系','/alpha/franchisee/uncontactList',1,2,1,'2016-08-10 02:00:27','2016-08-10 02:00:27'),(122,116,NULL,'密码验证','/alpha/role/verifyPassword',0,0,2,'2016-08-10 02:57:58','2016-08-10 02:57:58'),(124,116,NULL,'修改密码','/alpha/role/passwordChange',0,1,2,'2016-08-10 07:37:35','2016-08-10 07:37:35'),(125,28,NULL,'店员信息更新','/alpha/store/updateStoreInfo',0,1,2,'2016-08-15 02:46:55','2016-08-15 02:46:55');
/*!40000 ALTER TABLE `admin_permissions` ENABLE KEYS */;
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
