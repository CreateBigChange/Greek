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
-- Table structure for table `store_withdraw_cash_log`
--

DROP TABLE IF EXISTS `store_withdraw_cash_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store_withdraw_cash_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `withdraw_cash_num` double(8,2) unsigned NOT NULL COMMENT '提现金额',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '状态:\n1  : 提现中\n2 : 审核中\n3 : 未通过\n0 : 完成',
  `reason` varchar(255) DEFAULT NULL COMMENT '没有通过的原因',
  `bank_card_num` varchar(45) NOT NULL COMMENT '银行卡号码',
  `bank_card_holder` varchar(45) NOT NULL COMMENT '持卡人',
  `bank_card_type` varchar(45) NOT NULL COMMENT '银行卡类型',
  `bank_name` varchar(45) NOT NULL COMMENT '银行名称',
  `bank_reserved_telephone` varchar(45) DEFAULT NULL COMMENT '银行预留电话',
  `pay_time` datetime NOT NULL,
  `can_withdraw_cash_num` double(8,2) DEFAULT '0.00' COMMENT '可提现',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_withdraw_cash_log`
--

LOCK TABLES `store_withdraw_cash_log` WRITE;
/*!40000 ALTER TABLE `store_withdraw_cash_log` DISABLE KEYS */;
INSERT INTO `store_withdraw_cash_log` VALUES (21,12,12,1000.00,'2016-07-06 18:40:54','2016-07-06 18:40:54',2,'213','431221144532444534543234','是多少','1','商店','123','0000-00-00 00:00:00',0.00),(23,21,12,111.00,'2016-07-06 18:40:54','2016-07-06 18:40:54',0,'搜索','431221144532444534543234','sad阿斯顿','1','撒旦','2212','2016-08-12 18:08:25',0.00),(25,12,12,12121.00,'2016-07-06 18:40:54','2016-07-06 18:40:54',2,NULL,'1231231232434543523452345234523123124234','我去饿啊实打实的','1','恶趣味','1321','0000-00-00 00:00:00',0.00),(26,21,12,111.00,'2016-07-06 18:40:54','2016-07-06 18:40:54',0,'搜索','431221144532444534543234','sad阿斯顿','1','撒旦','2212','2016-08-05 10:08:54',0.00),(27,21,12,111.00,'2016-07-06 18:40:54','2016-07-06 18:40:54',0,'搜索','431221144532444534543234','sad阿斯顿','1','撒旦','2212','2016-08-05 10:08:26',0.00),(28,21,12,111.00,'2016-07-06 18:40:54','2016-07-06 18:40:54',0,'搜索','431221144532444534543234','sad阿斯顿','1','撒旦','2212','2016-08-17 09:08:27',0.00),(29,21,12,111.00,'2016-07-06 18:40:54','2016-07-06 18:40:54',2,'搜索','431221144532444534543234','sad阿斯顿','1','撒旦','2212','2016-08-02 18:08:22',0.00),(30,21,12,111.00,'2016-07-06 18:40:54','2016-07-06 18:40:54',2,'搜索','431221144532444534543234','sad阿斯顿','1','撒旦','2212','2016-08-02 18:08:22',0.00),(31,21,12,111.00,'2016-07-06 18:40:54','2016-07-06 18:40:54',2,'搜索','431221144532444534543234','sad阿斯顿','1','撒旦','2212','2016-08-02 18:08:22',0.00),(32,21,12,111.00,'2016-07-06 18:40:54','2016-07-06 18:40:54',2,'搜索','431221144532444534543234','sad阿斯顿','1','撒旦','2212','2016-08-02 18:08:22',0.00),(33,21,12,111.00,'2016-07-06 18:40:54','2016-07-06 18:40:54',2,'搜索','431221144532444534543234','sad阿斯顿','1','撒旦','2212','2016-08-02 18:08:22',0.00),(34,21,12,111.00,'2016-07-06 18:40:54','2016-07-06 18:40:54',2,'搜索','431221144532444534543234','sad阿斯顿','1','撒旦','2212','2016-08-02 18:08:22',0.00),(35,21,12,111.00,'2016-07-06 18:40:54','2016-07-06 18:40:54',0,'搜索','431221144532444534543234','sad阿斯顿','1','撒旦','2212','2016-08-17 09:08:34',0.00);
/*!40000 ALTER TABLE `store_withdraw_cash_log` ENABLE KEYS */;
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
