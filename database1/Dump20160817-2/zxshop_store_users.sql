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
-- Table structure for table `store_users`
--

DROP TABLE IF EXISTS `store_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL,
  `account` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `real_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `salt` varchar(60) CHARACTER SET utf8 DEFAULT NULL COMMENT '密码加密随机字符串',
  `tel` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `store_users_account_unique` (`account`),
  KEY `store_users_store_id_foreign` (`store_id`),
  CONSTRAINT `store_users_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `store_infos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=275 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_users`
--

LOCK TABLES `store_users` WRITE;
/*!40000 ALTER TABLE `store_users` DISABLE KEYS */;
INSERT INTO `store_users` VALUES (12,21,'18874130125','吴','c9332272da586c9a37da47048e389a66ce0d9188','TMtkEguU','18874130125',0,'8cc7777bfc51d10e1cd48b1a141768e5cdbf38d2','2016-06-02 18:51:17','2016-07-08 00:47:27'),(14,24,'18401586654','吴辉','dd648b8139db448716234c903e39439bcee8feac','WsCDqf0b','18401586654',0,'fb1a8d75fe4f89d1e68b8dc0e0040fd479502812','2016-06-22 03:17:36','2016-06-22 06:45:55'),(15,25,'18975873110','呱顶呱水果店',NULL,NULL,'18975873110',0,NULL,'2016-07-01 03:12:39','2016-07-01 03:12:39'),(16,26,'18975177946','喜洋洋连锁超市',NULL,NULL,'18975177946',0,NULL,'2016-07-01 03:12:39','2016-07-01 03:12:39'),(17,27,'18938660919','深圳市宝安区西乡佳豪百货店',NULL,NULL,'18938660919',0,NULL,'2016-07-01 03:12:39','2016-07-01 03:12:39'),(18,28,'18923711100','深圳市南山区宏图便利店',NULL,NULL,'18923711100',0,NULL,'2016-07-01 03:12:39','2016-07-01 03:12:39'),(19,29,'18902461370','深圳市龙华新区龙华范珍旭水果批发行',NULL,NULL,'18902461370',0,NULL,'2016-07-01 03:12:39','2016-07-01 03:12:39'),(20,30,'18873685681','鲜果乐园水果商店',NULL,NULL,'18873685681',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(21,31,'18837303867','新乡旭晟超市',NULL,NULL,'18837303867',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(22,32,'18837269705','林州振宏超市',NULL,NULL,'18837269705',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(23,33,'18820285296','深圳市龙华新区龙华共和市场湘华粮油批发商行',NULL,NULL,'18820285296',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(24,34,'18774896805','新高桥是明店',NULL,NULL,'18774896805',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(25,35,'18774058289','爱在零食坊',NULL,NULL,'18774058289',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(26,36,'18773267868','长沙市岳麓区羲梁生鲜店',NULL,NULL,'18773267868',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(27,37,'18739219585','鹤壁市浚县宏基超市（安阳市）',NULL,NULL,'18739219585',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(28,38,'18692637905','株洲荷塘区优果零食铺',NULL,NULL,'18692637905',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(29,39,'18692601295','株洲市荷塘区誉兴水果店',NULL,NULL,'18692601295',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(30,40,'18692395595','乐尔乐特价超市',NULL,NULL,'18692395595',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(31,41,'18684777033','快乐惠裕昌炒货店（映江苑店）',NULL,NULL,'18684777033',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(32,42,'18684738052','雨花区融科东南海佳宜连锁超市',NULL,NULL,'18684738052',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(33,43,'18683208430','深圳市龙华新区民治南源新村果然鲜水果店',NULL,NULL,'18683208430',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(34,44,'18682468876','深圳市福田区新百果园商店',NULL,NULL,'18682468876',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(35,45,'18682293267','深圳市福田区加州阳光水果店',NULL,NULL,'18682293267',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(36,46,'18679441850','农家果园锦绣店',NULL,NULL,'18679441850',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(37,47,'18674853766','久发水果超市',NULL,NULL,'18674853766',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(38,48,'18674459982','万誉生活超市',NULL,NULL,'18674459982',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(39,49,'18673266903','锦旭水果',NULL,NULL,'18673266903',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(40,50,'18670398555','乐活水果',NULL,NULL,'18670398555',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(41,51,'18670378563','株洲芦淞区家庭果园',NULL,NULL,'18670378563',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(42,52,'18664946026','深圳市龙岗区益友通便利店',NULL,NULL,'18664946026',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(43,53,'18639223433','鹤壁浚县卫贤镇祥龙超市（安阳区）',NULL,NULL,'18639223433',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(44,54,'18627531216','株洲荷塘区宏辉便利店',NULL,NULL,'18627531216',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(45,55,'18625763328','鹤壁淇滨区阳光家天下丹鼎超市（安阳区）',NULL,NULL,'18625763328',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(46,56,'18617129407','深圳市龙华新区龙华超越百货商行',NULL,NULL,'18617129407',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(47,57,'18613980403','家利多水果超市',NULL,NULL,'18613980403',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(48,58,'18603010132','自由自在港货店',NULL,NULL,'18603010132',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(49,59,'18574380589','天天鲜水果',NULL,NULL,'18574380589',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(50,60,'18573187393','株洲芦淞区果穗果香水果店',NULL,NULL,'18573187393',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(51,61,'18571913410','便利佳超市',NULL,NULL,'18571913410',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(52,62,'18539210250','鹤壁淇滨区黎阳商贸城丹鼎超市（安阳市）',NULL,NULL,'18539210250',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(53,63,'18537322622','获嘉丹鼎超市',NULL,NULL,'18537322622',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(54,64,'18530521563','汤阴县宜沟镇丹鼎超市（安阳市）',NULL,NULL,'18530521563',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(55,65,'18507486652','岳麓区咸嘉新村尚果水果速递',NULL,NULL,'18507486652',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(56,66,'18437312304','延津县马庄乡马庄大街宇琳百货',NULL,NULL,'18437312304',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(57,67,'18373201700','驿家连锁超市',NULL,NULL,'18373201700',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(58,68,'18318053530','深圳市龙岗区坂田显芳水果店',NULL,NULL,'18318053530',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(59,69,'18274972288','星沙物流园爱尚水果',NULL,NULL,'18274972288',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(60,70,'18274852884','琪琪超市',NULL,NULL,'18274852884',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(61,71,'18261019496','鹤壁市浚县三环路丹鼎宜家超市（安阳市）',NULL,NULL,'18261019496',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(62,72,'18243154525','深圳市龙华新区兆强粮油干货批发贸易商行',NULL,NULL,'18243154525',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(63,73,'18239264939','浚县小河镇大碾村万客来超市（安阳）',NULL,NULL,'18239264939',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(64,74,'18239256060','鹤壁市淇滨区德源原浆酒店（安阳）',NULL,NULL,'18239256060',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(65,75,'18182100216','豪兴烟酒商行',NULL,NULL,'18182100216',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(66,76,'18171824077','深圳市龙华新区龙聪宇恒乐连锁便利店',NULL,NULL,'18171824077',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(67,77,'18165769272','深圳市龙华新区大浪每天惠彩虹之家百货店',NULL,NULL,'18165769272',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(68,78,'18163799738','e家果园（贯江苑店）',NULL,NULL,'18163799738',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(69,79,'18163737809','纤果屋水果店',NULL,NULL,'18163737809',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(70,80,'18122400556','深圳市宝安区沙井兴大兴生活超市',NULL,NULL,'18122400556',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(71,81,'18075650575','长沙市天心区爱家乐便利店','1fc212bd30243b4bd53c5abda9a91d16c3786a85','ox1ArgLB','18075650575',0,'6b7fdecc2acf9d496e45ec4d5c2cba825eb8a1da','2016-07-01 03:12:40','2016-07-12 05:09:10'),(72,82,'18033403169','深圳市龙华新区龙华信一顺百货商行',NULL,NULL,'18033403169',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(73,83,'18008411348','爱爱超市',NULL,NULL,'18008411348',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(74,84,'17875053125','深圳市龙华新区龙华秀秀便利店',NULL,NULL,'17875053125',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(75,85,'17839161919','林州龙安南路红茂粮油超',NULL,NULL,'17839161919',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(76,86,'17737803140','汤阴老城区荣城百货（安阳区）',NULL,NULL,'17737803140',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(77,87,'17708482103','岳麓区后湖批发超市',NULL,NULL,'17708482103',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(78,88,'17703924657','鹤壁市浚县大郭庄丹鼎超市（安阳市）',NULL,NULL,'17703924657',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(79,89,'17608406205','株洲芦淞区天然果园',NULL,NULL,'17608406205',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(80,90,'17050265858','善品客连锁超市',NULL,NULL,'17050265858',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(81,91,'15993009691','获嘉县太山乡佳联超市',NULL,NULL,'15993009691',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(82,92,'15939279566','淇县西岗镇西岗瑞达超市 （安阳）',NULL,NULL,'15939279566',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(83,93,'15937298628','林州龙山区丹鼎超市',NULL,NULL,'15937298628',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(84,94,'15937221730','滑县南张固村百合易购（安阳区）',NULL,NULL,'15937221730',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(85,95,'15920088268','深圳市龙岗区越水鲜水果店',NULL,NULL,'15920088268',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(86,96,'15915437925','深圳市龙岗区百里汇时宜便利店',NULL,NULL,'15915437925',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(87,97,'15899870793','深圳市龙华新区茜茜水果店',NULL,NULL,'15899870793',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(88,98,'15893889488','新乡市延津县位邱乡朱寨村鸿泰超市',NULL,NULL,'15893889488',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(89,99,'15890069456','上街区丹鼎颐家超市（郑州市）',NULL,NULL,'15890069456',0,NULL,'2016-07-01 03:12:40','2016-07-01 03:12:40'),(90,100,'15889544421','深圳市南山区果然鲜水果店',NULL,NULL,'15889544421',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(91,101,'15874805937','众旺水果连锁超市（映江店）',NULL,NULL,'15874805937',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(92,102,'15874090337','金泽水果店',NULL,NULL,'15874090337',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(93,103,'15873210097','湘潭市岳塘区昌霖超市',NULL,NULL,'15873210097',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(94,104,'15862691185','尚优鲜果',NULL,NULL,'15862691185',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(95,105,'15839254911','鹤壁浚县伾山街丹鼎e家超市（安阳市）',NULL,NULL,'15839254911',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(96,106,'15839239217','合家乐百货超市（安阳区）',NULL,NULL,'15839239217',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(97,107,'15839230444','浚县小河镇益农信息社（安阳区）',NULL,NULL,'15839230444',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(98,108,'15838013297','新密市米村镇双汇超市（郑州市）',NULL,NULL,'15838013297',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(99,109,'15837398086','滑县金运购物广场（安阳市）',NULL,NULL,'15837398086',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(100,110,'15818654985','深圳市南山区亿果园水果店',NULL,NULL,'15818654985',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(101,111,'15818524656','深圳市宝安区沙井天大福商店',NULL,NULL,'15818524656',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(102,112,'15700787887','呱顶呱水果店（1号果农）',NULL,NULL,'15700787887',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(103,113,'15639209234','浚县麦多购物中心',NULL,NULL,'15639209234',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(104,114,'15637340888','新乡向阳路天宝小区丹鼎超市',NULL,NULL,'15637340888',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(105,115,'15616015218','株洲荷塘区滔滔水果店',NULL,NULL,'15616015218',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(106,116,'15607315018','众旺水果炒货连锁（星江苑）',NULL,NULL,'15607315018',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(107,117,'15580950853','株洲荷塘区金都西饼屋',NULL,NULL,'15580950853',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(108,118,'15560053473','深圳市龙岗区花果园水果店',NULL,NULL,'15560053473',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(109,119,'15539283088','鹤壁淇县高村镇古城金友超市（安阳区）',NULL,NULL,'15539283088',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(110,120,'15539280200','鹤壁淇县北阳乡志伟超市（安阳区）',NULL,NULL,'15539280200',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(111,121,'15539269566','浚县科技路中段家天下购物广场',NULL,NULL,'15539269566',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(112,122,'15539257018','鹤壁淇滨区黄河路成人用品店（安阳区）',NULL,NULL,'15539257018',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(113,123,'15518826288','滑县好万家生活超市（安阳市）',NULL,NULL,'15518826288',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(114,124,'15514964705','深圳市龙岗区鲜果惠水果超市',NULL,NULL,'15514964705',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(115,125,'15387524399','新佳宜小俊食品店',NULL,NULL,'15387524399',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(116,126,'15364030128','新德雅炒货店（绿地店）','e7b86af39d13f81e93bff4d6344f2a58f74529d7','mgYCY07I','15364030128',0,'8621a0121aa326fe1919dafe7bad2dd067bc913c','2016-07-01 03:12:41','2016-07-11 12:50:37'),(117,127,'15273136933','果滋味水果店',NULL,NULL,'15273136933',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(118,128,'15239239309','便民超市（安阳区）',NULL,NULL,'15239239309',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(119,129,'15237227788','汤阴县人民路中段欧特福超市（安阳区）',NULL,NULL,'15237227788',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(120,130,'15211218697','传奇果业',NULL,NULL,'15211218697',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(121,131,'15211019118','金一隆超市',NULL,NULL,'15211019118',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(122,132,'15173220117','湘潭市雨湖区晨曦超市',NULL,NULL,'15173220117',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(123,133,'15139250118','淇县杨利超市（安阳区）',NULL,NULL,'15139250118',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(124,134,'15139231235','鹤壁浚县卫贤镇诚信四邻便利店（安阳区）',NULL,NULL,'15139231235',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(125,135,'15139203379','鹤壁淇县西岗镇三忠超市（安阳）',NULL,NULL,'15139203379',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(126,136,'15137266277','林州桂林镇大店村向阳超市',NULL,NULL,'15137266277',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(127,137,'15129887123','今之鲜果铺',NULL,NULL,'15129887123',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(128,138,'15116316800','农夫鲜果',NULL,NULL,'15116316800',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(129,139,'15116235207','株洲荷塘区芙蓉兴盛便利店',NULL,NULL,'15116235207',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(130,140,'15115669670','鲜果坊水果便利商行',NULL,NULL,'15115669670',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(131,141,'15111084440','岳麓区绝味鸭脖新城小区店',NULL,NULL,'15111084440',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(132,142,'15111061055','长沙市开福区红旺水果炒货连锁',NULL,NULL,'15111061055',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(133,143,'15084999043','520果园',NULL,NULL,'15084999043',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(134,144,'15080758257','湘潭市岳塘区佳品屋超市',NULL,NULL,'15080758257',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(135,145,'15039233913','鹤壁淇县万和百货超市（安阳市）',NULL,NULL,'15039233913',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(136,146,'15038087278','新密大隗镇丹鼎超市（郑州市）',NULL,NULL,'15038087278',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(137,147,'15018534119','深圳市龙华新区七二七天优品食品便利店',NULL,NULL,'15018534119',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(138,148,'15013723035','深圳市龙华新区大浪美佳园便利店',NULL,NULL,'15013723035',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(139,149,'13973187937','好祥来水果炒货店',NULL,NULL,'13973187937',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(140,150,'13939279660','鹤壁浚县白寺乡白寺集证硕超市（安阳市）',NULL,NULL,'13939279660',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(141,151,'13939263611','鹤壁市淇县淇冯超市（安阳市）',NULL,NULL,'13939263611',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(142,152,'13939215887','鹤壁市浚县丽珍超市（安阳市）',NULL,NULL,'13939215887',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(143,153,'13938714267','新乡凤泉区丹鼎颐家',NULL,NULL,'13938714267',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(144,154,'13926599726','深圳市福田区百家惠商店',NULL,NULL,'13926599726',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(145,155,'13902467619','深圳市福田区福湘日杂商店',NULL,NULL,'13902467619',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(146,156,'13875886062','鲜果屋',NULL,NULL,'13875886062',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(147,157,'13875851766','快乐惠（刚毅食品商店）',NULL,NULL,'13875851766',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(148,158,'13873178835','芙蓉兴盛佑江店',NULL,NULL,'13873178835',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(149,159,'13849239322','鹤壁淇县海亮超市（安阳市）',NULL,NULL,'13849239322',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(150,160,'13849228730','鹤壁浚县王庄镇丹鼎e家（安阳市）',NULL,NULL,'13849228730',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(151,161,'13838075812','新密大隗镇百全超市（郑州市）',NULL,NULL,'13838075812',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(152,162,'13823339635','深圳市龙华新区民治佳果园商店',NULL,NULL,'13823339635',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(153,163,'13802587969','深圳市福田区百里客便利店',NULL,NULL,'13802587969',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(154,164,'13787178330','奇恩生活超市',NULL,NULL,'13787178330',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(155,165,'13787096051','佳佳一品水果连锁超市',NULL,NULL,'13787096051',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(156,166,'13786167501','株洲荷塘区飞鸿水果店',NULL,NULL,'13786167501',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(157,167,'13760378777','深圳市宝安区龙华佳乐社商店',NULL,NULL,'13760378777',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(158,168,'13760147436','深圳市龙华新区民治好吉祥便利店',NULL,NULL,'13760147436',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(159,169,'13751138860','深圳市龙岗区鑫群畅商行',NULL,NULL,'13751138860',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(160,170,'13751106737','深圳市宝安区西乡惠乐多超市',NULL,NULL,'13751106737',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(161,171,'13735631588','深圳市龙岗区横岗雄波益友便利店',NULL,NULL,'13735631588',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(162,172,'13728822704','深圳市罗湖区好街坊果园水果店',NULL,NULL,'13728822704',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(163,173,'13715278973','深圳市宝安区西乡佳旺水果店',NULL,NULL,'13715278973',0,NULL,'2016-07-01 03:12:41','2016-07-01 03:12:41'),(164,174,'13715050663','深圳市龙岗区锐达盛商行',NULL,NULL,'13715050663',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(165,175,'13714977345','深圳市龙岗区坂田果然鲜水果店',NULL,NULL,'13714977345',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(166,176,'13714945178','深圳市龙华新区民治街道澳门新村欢乐新鲜水果园',NULL,NULL,'13714945178',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(167,177,'13714097045','深圳市龙岗区坂田爱惠康果品店',NULL,NULL,'13714097045',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(168,178,'13713799318','深圳市龙华新区壹家福生鲜店',NULL,NULL,'13713799318',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(169,179,'13713655591','深圳市龙岗区雄旺天商行',NULL,NULL,'13713655591',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(170,180,'13699786683','深圳市龙华新区民治樟坑家乐通便利店',NULL,NULL,'13699786683',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(171,181,'13693921586','淇县朝歌镇后张近村小雪超市（安阳市）',NULL,NULL,'13693921586',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(172,182,'13691862877','深圳市南山区新塘水果园',NULL,NULL,'13691862877',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(173,183,'13691647491','深圳市南山区岑晨鲜果店',NULL,NULL,'13691647491',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(174,184,'13691602949','果然鲜水果连锁上塘店','7e8ac05673b0f5c8c294e536a4bd468b82dc7b86','TpUCwbw2','13691602949',0,'a0860eb12dc3a4cfc38996cdc3fb4bc96b863bf0','2016-07-01 03:12:42','2016-07-08 13:54:57'),(175,185,'13686457981','深圳市福田区果果乐水果店',NULL,NULL,'13686457981',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(176,186,'13682455001','深圳市龙华新区龙华嘉旺发日用百货批发商行',NULL,NULL,'13682455001',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(177,187,'13662592393','深圳市宝安区福永绿之园水果超市',NULL,NULL,'13662592393',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(178,188,'13631505778','深圳市龙华新区民治四海水果商行',NULL,NULL,'13631505778',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(179,189,'13617329549','湘潭县欣宜家超市',NULL,NULL,'13617329549',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(180,190,'13603935569','新乡振中路丹鼎超市',NULL,NULL,'13603935569',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(181,191,'13603928421','鹤壁淇滨区益农信息社（安阳区）',NULL,NULL,'13603928421',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(182,192,'13603926093','鹤壁市淇县惠捷便利店（安阳市）',NULL,NULL,'13603926093',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(183,193,'13598691247','辉县市华艺郡府东门',NULL,NULL,'13598691247',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(184,194,'13590415278','深圳市龙华新区工业东路油松阿里巴巴便利店',NULL,NULL,'13590415278',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(185,195,'13590244155','深圳市龙华新区果盈园水果店',NULL,NULL,'13590244155',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(186,196,'13590161558','深圳市龙岗区紫麟轩商行',NULL,NULL,'13590161558',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(187,197,'13574141088','家家乐水果',NULL,NULL,'13574141088',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(188,198,'13569889407','原阳县蒋庄乡智宾干菜店',NULL,NULL,'13569889407',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(189,199,'13569001136','商会大厦','31d4264d0e14885e920feab0c460fb3bb3ecf0b3','xSYjTSf7','13569001136',0,'ce417a135be87cccbe5ddfe74cf67f45697c172c','2016-07-01 03:12:42','2016-07-12 10:04:57'),(190,200,'13560763938','深圳市龙岗区同心水果店',NULL,NULL,'13560763938',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(191,201,'13554701801','深圳市龙岗区横岗莉益友便利店',NULL,NULL,'13554701801',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(192,202,'13548764741','大不同水果连锁',NULL,NULL,'13548764741',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(193,203,'13548685985','豪盛烟酒商行',NULL,NULL,'13548685985',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(194,204,'13548638403','优选优果（银盆岭店）','384639b786eb783a971ae087b2bc2faf48f4cfa2','mpPeOqOo','13548638403',0,'114a0fe03a72e38b23bbefc349de413786985333','2016-07-01 03:12:42','2016-07-08 06:08:43'),(195,205,'13543258158','深圳市宝安区西乡兴旺隆百货店',NULL,NULL,'13543258158',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(196,206,'13534185469','深圳市龙福源商贸有限公司',NULL,NULL,'13534185469',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(197,207,'13530453173','绿色果园',NULL,NULL,'13530453173',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(198,208,'13528666635','果然鲜365花园店',NULL,NULL,'13528666635',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(199,209,'13526838786','新密市米村镇佳佳超市（郑州市）','59481ee9ae5d1cc0d5183d6a600481b4b8eb1c93','g6pQdnbU','13526838786',0,'6d527c19af5a16bd80d5dae8bc534e76d32aa6a9','2016-07-01 03:12:42','2016-07-11 02:57:32'),(200,210,'13510904348','深圳市龙华新区龙华宜购商行',NULL,NULL,'13510904348',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(201,211,'13510555677','深圳市宝安区福永罗雪松水果超市',NULL,NULL,'13510555677',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(202,212,'13510188477','深圳市龙岗区春暖芬富商行',NULL,NULL,'13510188477',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(203,213,'13503923905','鹤壁大新区杨科超市（安阳区）',NULL,NULL,'13503923905',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(204,214,'13503734098','辉县黄水乡龙门村丹鼎店',NULL,NULL,'13503734098',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(205,215,'13487588386','晶晶粮油',NULL,NULL,'13487588386',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(206,216,'13480956703','深圳市南山区孙记绿色水果店',NULL,NULL,'13480956703',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(207,217,'13467628036','鲜果零食店',NULL,NULL,'13467628036',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(208,218,'13462322553','新乡市文化桥头易社通便利店',NULL,NULL,'13462322553',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(209,219,'13461996861','鹤壁浚县新镇小蒋村永奇超市（安阳市）',NULL,NULL,'13461996861',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(210,220,'13430996504','深圳市宝安区松岗快购便利店',NULL,NULL,'13430996504',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(211,221,'13430691599','深圳市福田区刘莎莎士多店',NULL,NULL,'13430691599',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(212,222,'13425122086','深圳市罗湖区鲜果营地水果店',NULL,NULL,'13425122086',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(213,223,'13422887205','深圳市龙华新区龙华鸿福居便利店',NULL,NULL,'13422887205',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(214,224,'13420950872','深圳市龙岗区大自然果品超市',NULL,NULL,'13420950872',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(215,225,'13418907498','深圳市罗湖区优乐民生鲜蔬果店',NULL,NULL,'13418907498',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(216,226,'13410801860','深圳市龙岗区许峰果香源水果店',NULL,NULL,'13410801860',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(217,227,'13410438533','深圳市南山区西丽镇庆操果然鲜水果店',NULL,NULL,'13410438533',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(218,228,'13409231555','获嘉亢村佳联超市',NULL,NULL,'13409231555',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(219,229,'13387362610','沁园商行',NULL,NULL,'13387362610',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(220,230,'13323616613','淇县南门里惠民综合超市（安阳市）',NULL,NULL,'13323616613',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(221,231,'13316925891','阿里福门便利店',NULL,NULL,'13316925891',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(222,232,'13303929581','鹤壁淇县高村镇文华超市（安阳市）',NULL,NULL,'13303929581',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(223,233,'13262116177','原阳魏店村丹鼎超市',NULL,NULL,'13262116177',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(224,234,'13244781933','深圳市龙岗区富康便利店','23c6db6f1bd7054cf2ea02c9228afc6f707896fd','hAaW36md','13244781933',0,'241e69f46fff8c8f6c62dd1a57234dc97d44faab','2016-07-01 03:12:42','2016-07-08 04:35:05'),(225,235,'13212628582','株洲荷塘区星美便利店',NULL,NULL,'13212628582',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(226,236,'13207317767','众旺水果炒货连锁咏江店',NULL,NULL,'13207317767',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(227,237,'13203165552','礼林综合批发超市',NULL,NULL,'13203165552',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(228,238,'13193521048','汤阴县信合路中段鲜多多超市（安阳区）',NULL,NULL,'13193521048',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(229,239,'15387516569','岳麓区共和世家美滋滋零食坊',NULL,NULL,'15387516569',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(230,240,'13140596166','原阳丹鼎超市',NULL,NULL,'13140596166',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(231,241,'13135318113','天心区郑韩便利店（邮电学院）',NULL,NULL,'13135318113',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(232,242,'13088809877','深圳市宝安区观澜威发便利店',NULL,NULL,'13088809877',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(233,243,'13080500077','星沙泉塘城市果园',NULL,NULL,'13080500077',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(234,244,'13066979730','深圳市龙岗区布吉镇绿源水果商行',NULL,NULL,'13066979730',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(235,245,'13048923100','深圳市龙岗区汉财便利店','d97fe05169c0edf16c11ef7944029caebd7a3b5b','VFnZsInd','13048923100',0,'da4b7e01346ec223d826cf394523c3bd10e0bddd','2016-07-01 03:12:42','2016-07-09 02:34:43'),(236,246,'13033889558','鹤壁市淇滨区鑫惠生活超市（安阳区）',NULL,NULL,'13033889558',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(237,247,'13033885362','鹤壁市淇县东街批发部（安阳市）',NULL,NULL,'13033885362',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(238,248,'13033875828','鹤壁浚县屯子镇张庄村生活超市（安阳市）',NULL,NULL,'13033875828',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(239,249,'13017688891','新密幸福家园生活超市（郑州市）',NULL,NULL,'13017688891',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(240,250,'13008881662','深圳市宛深投资有限公司',NULL,NULL,'13008881662',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(241,251,'13007599339','林州金海盛购物广场',NULL,NULL,'13007599339',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(242,252,'13005483018','深圳市龙岗区布吉惠生活便利店',NULL,NULL,'13005483018',0,NULL,'2016-07-01 03:12:42','2016-07-01 03:12:42'),(243,253,'15364091209','急所需便利店（奥林匹克花园）','6c445609e33ad739981d9e432b12469295b4999b','BnHnLahu','15364091209',0,NULL,'2016-07-12 02:00:23','2016-07-12 02:00:23'),(244,254,'15874043591','张','bc789bfa618afda482e1db7184001244d8137489','gRNqroJH','15874043591',0,NULL,'2016-07-12 02:07:59','2016-07-12 02:07:59'),(245,255,'15560269330','宝龙国际社区永盛生活超市','0d6b96da6ea081a2a8eb1acb1d10516e3f26382b','XMbJuvUG','15560269330',0,NULL,'2016-07-12 02:10:26','2016-07-12 02:10:26'),(246,256,'18508430917','叶','a80d343d278ae0c3ca640d7331705cd0af15ab8d','03ITSiO2','18508430917',0,NULL,'2016-07-12 02:12:51','2016-07-12 02:12:51'),(247,257,'13049460322','郭','4189be4b81f23637083716e092384bdffaaab1b7','2FTYcgwf','13049460322',0,NULL,'2016-07-12 06:39:25','2016-07-12 06:39:25'),(248,258,'13049414244','罗','5a3bb05880e18c41c97959de8549a05e38767ad9','5u22jTHr','13049414244',0,'b437c786ed3faf65ec19add92d1346b1a691c7b9','2016-07-12 06:44:03','2016-07-13 07:21:46'),(250,261,'13148863015','李','ebdb144dc36ed3899889c0449044093694fc2d62','ssqT4qc9','13148863015',0,'b437c786ed3faf65ec19add92d1346b1a691c7b9','2016-07-12 06:55:45','2016-07-13 07:34:53'),(251,262,'13058165244','马','d2c46cea12a298436839b186ef5807d91c515710','JvmoxI7Y','13058165244',0,'b437c786ed3faf65ec19add92d1346b1a691c7b9','2016-07-12 07:00:36','2016-07-13 07:38:56'),(252,263,'13265424044','饶','10b5bdc45ced36bb544be1d1d2ad19c621d79ef4','0OdrDPwl','13265424044',0,NULL,'2016-07-12 07:03:52','2016-07-12 07:03:52'),(253,264,'13048989411','吴','4d523fbda698652170095ad5ea048f4679922bda','y5uetVz8','13048989411',0,NULL,'2016-07-12 07:06:12','2016-07-12 07:06:12'),(254,265,'13048946233','陈宝贵','3aff9dc60b484e48a49e16d68826bc279490c0d0','7czNoO7F','13048946233',0,NULL,'2016-07-12 07:08:51','2016-07-12 07:08:51'),(255,266,'13048974322','魏','a68f4bc2fb6461d1e21201fcfa2291da4cfe8b96','NjavXZjc','13048974322',0,NULL,'2016-07-12 07:11:28','2016-07-12 07:11:28'),(256,267,'13244792756','李','79093458f76505b8cf09bc380a4c4553f00c31fc','sDsbSjRF','13244792756',0,NULL,'2016-07-12 07:13:51','2016-07-12 07:13:51'),(257,268,'13265474644','池','b25ae2297a7d26b082e46843fd67581146bba1c9','6u4irIDu','13265474644',0,NULL,'2016-07-12 07:19:59','2016-07-12 07:19:59'),(258,269,'13129537400','林','583f80e102253df14a1bd62afa287943b3336293','h3tPuSoK','13129537400',0,NULL,'2016-07-12 07:25:04','2016-07-12 07:25:04'),(259,270,'13149943872','李','78d23f0a83a9ed65b21ce095c96d91c7a4d41e06','X5EESX6m','13149943872',0,NULL,'2016-07-12 07:27:30','2016-07-12 07:27:30'),(260,271,'13148861343','姜','23aed936be15df7827f5c1206b2592b04c094664','GbTvtdpQ','13148861343',0,NULL,'2016-07-12 07:30:07','2016-07-12 07:30:07'),(261,272,'13129520411','张','9d03b835b5f8ea63e8073101101053814353399b','NuMefwy8','13129520411',0,NULL,'2016-07-12 07:31:36','2016-07-12 07:31:36'),(262,273,'13048973844','王林','215d770b26a478febdb3a1c5667bc34225052d81','y9QOczCo','13048973844',0,'b437c786ed3faf65ec19add92d1346b1a691c7b9','2016-07-12 07:32:28','2016-07-13 07:51:18'),(263,274,'13265429517','贾','d5abbe98ce6bbc8ff233b927d893df3d4529f79c','QLmzTQMr','13265429517',0,NULL,'2016-07-12 07:36:54','2016-07-12 07:36:54'),(264,275,'13049418011','刘艳珊','0a0e14d51a0f8a8ee1f1ca180b5027b9445ede59','Jf2T30Bb','13049418011',0,NULL,'2016-07-12 07:38:53','2016-07-12 07:38:53'),(265,276,'13143415225','肖','f5c948d9439915d773872aee2fb5bab34a94a615','35x6ssnl','13143415225',0,NULL,'2016-07-12 07:40:36','2016-07-12 07:40:36'),(266,277,'13128955044','杨','f6db4e6be88ca10c942eee4d3f9c663840e85dd4','o6egAf4u','13128955044',0,'b437c786ed3faf65ec19add92d1346b1a691c7b9','2016-07-12 07:44:10','2016-07-13 07:49:07'),(267,278,'13265549611','赵','5de7bbd2dabc0a589c0a16dae08772db180a9cd6','SS8xHsa2','13265549611',0,NULL,'2016-07-12 07:48:00','2016-07-12 07:48:00'),(268,279,'13243778960','周','f7cf1d28b9b4a9005f52206f4abddfa4091c0f81','wZcoBKMV','13243778960',0,'b437c786ed3faf65ec19add92d1346b1a691c7b9','2016-07-12 07:50:41','2016-07-13 07:41:48'),(269,280,'13244741677','王','873fa24473defd813974d288a310d84cd71b4c83','9Ws86WKr','13244741677',0,NULL,'2016-07-12 07:53:49','2016-07-12 07:53:49'),(270,281,'13129580344','杨','4378f320761d5d6b6a180beb482936d1f1560513','DmROmOUk','13129580344',0,NULL,'2016-07-12 07:57:37','2016-07-12 07:57:37'),(271,282,'13244774537','张','1799032df20c6e40f4fb00fac47413e310336649','DQJ5xYE7','13244774537',0,NULL,'2016-07-12 08:04:35','2016-07-12 08:04:35'),(272,283,'13128983411','胡','7c8b630f5578cd14743344f5028743b736f52d72','nCLcKkYR','13128983411',0,NULL,'2016-07-12 08:13:51','2016-07-12 08:13:51'),(273,284,'13058153144','吴','a0b6be2b9bcbaee5c65da0e3d64de5cd276c28f0','VRDR8y2i','13058153144',0,NULL,'2016-07-12 08:15:54','2016-07-12 08:15:54'),(274,285,'13244737677','刘','d0e44f1729f472cebbc6db01da9949c242247ee5','2EXLJMv9','13244737677',0,NULL,'2016-07-12 08:18:23','2016-07-12 08:18:23');
/*!40000 ALTER TABLE `store_users` ENABLE KEYS */;
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