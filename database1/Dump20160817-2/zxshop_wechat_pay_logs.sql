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
-- Table structure for table `wechat_pay_logs`
--

DROP TABLE IF EXISTS `wechat_pay_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wechat_pay_logs` (
  `trade_type` varchar(10) DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  `detail` text,
  `openid` varchar(45) DEFAULT NULL,
  `out_trade_no` varchar(48) DEFAULT NULL,
  `total_fee` int(11) DEFAULT NULL,
  `spbill_create_ip` varchar(16) DEFAULT NULL,
  `fee_type` int(11) DEFAULT NULL,
  `timeStamp` varchar(16) DEFAULT NULL,
  `nonceStr` varchar(16) DEFAULT NULL,
  `package` varchar(56) DEFAULT NULL,
  `signType` varchar(12) DEFAULT NULL,
  `paySign` varchar(45) DEFAULT NULL,
  `appid` varchar(45) DEFAULT NULL,
  `bank_type` varchar(45) DEFAULT NULL,
  `cash_fee` varchar(3) DEFAULT NULL,
  `mch_id` varchar(15) DEFAULT NULL,
  `result_code` varchar(7) DEFAULT NULL,
  `return_code` varchar(7) DEFAULT NULL,
  `sign` varchar(45) DEFAULT NULL,
  `time_end` varchar(15) DEFAULT NULL,
  `transaction_id` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wechat_pay_logs`
--

LOCK TABLES `wechat_pay_logs` WRITE;
/*!40000 ALTER TABLE `wechat_pay_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `wechat_pay_logs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-08-18  8:50:33
