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
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_del` tinyint(1) DEFAULT '0',
  `account` varchar(45) CHARACTER SET utf8 NOT NULL,
  `nick_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `true_name` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `sex` varchar(5) CHARACTER SET utf8 DEFAULT '男',
  `mobile` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `points` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `money` double(8,2) DEFAULT '0.00',
  `login_ip` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `login_old_ip` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `salt` varchar(60) CHARACTER SET utf8 NOT NULL COMMENT '密码加密随机字符串',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pay_password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '支付密码',
  `pay_salt` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `login_type` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '登录类型',
  `wx_pub_openid` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `wx_app_openid` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wx_unionid` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `qq_openid` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `qq_unionid` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` text CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,0,'15364010615','饼',NULL,'女','15364010615','http://7xt4zt.com2.z0.glb.clouddn.com/1468307970.jpeg','',0,0.00,'112.74.68.71',NULL,'7b9428fcb27552892498b2f4d64b817477b27da7','CsL5Fohx','2016-07-07 03:51:05','2016-08-01 05:30:49',NULL,NULL,'weixin','ovZmSs_597aDd09TZpoTPR5NQObI','oiFVUwmoUWyzImTgC088XrXmCkXQ','oejcrsydXxQSZbWrF1x2p21hdCH8',NULL,NULL,',rO3oxWqeY5s44qsb,HKuNIfvAAJMX5EnY,V2obyJhr5k0XHXAz,lAEAbLtLHiumiliq,b3n8qeMPJFjewcPR,wcDFRyFSHSgdW4CI,EDtDNauiUUG2hG1T,4GJFRDRZlLL4xtUu,4wCzjiy3s44x0b60,lhAERDwntfJcRw0c'),(2,0,'15173259280','Hello world！',NULL,'男','15173259280','http://wx.qlogo.cn/mmopen/86xug5S1lDSt8vc4EO1Iau8kTl8krEX44b1QqyIhIwyJ2XVt1fPBVSsvTibAxL8531NT8vibWW46UByHdej07CZo11e3Ps6uSY/0','',0,0.00,'112.74.68.71',NULL,'c933acf7fc657009b572066336c5c0ddc3c82694','dQ3O9fbW','2016-07-07 03:51:31','2016-07-07 07:12:41',NULL,NULL,'weixin','ovZmSs0i8G5SIb4WzJzI1gLSntr8',NULL,'oejcrs2B-m6KT41nf3lolXGwDX7Q',NULL,NULL,NULL),(3,0,'18874130125','吴辉',NULL,'男','18874130125','http://wx.qlogo.cn/mmopen/ibkKkoaQFco6ichbd6Lp5c5IUzwFWkWRjDsia8Hc0xb8sarAKox5P78myESAhg8mIFZfBnOc1jtOvn0UXgQccgTicA/0','',0,0.00,'112.74.68.71',NULL,'8459f4e184818b58cd485781012f2652b152b74b','BTEOCrbZ','2016-07-07 04:02:56','2016-07-07 04:30:03',NULL,NULL,'weixin','ovZmSs24EokHlOEyNT8Qn8a8EbZc','oiFVUwkvcGVfdSkwmc48B-6caz-Q','oejcrs9b5QBMD8Z19YJEyuF9x22I',NULL,NULL,',bz6sM7YrWfAlcwhQ,W8h4CsE7ohT2YEpK,EgSEHiBlzuwoU0OR,L7U44o6OXSThgVKB,ZKboB6o4JzxzQiZK'),(4,0,'','蓉Elsie',NULL,'女',NULL,'http://wx.qlogo.cn/mmopen/ibkKkoaQFco6gyXT9cBlyFTVAegfzyMnYvZeNk7AqUbceVCGkuzofQXPSmmF5pFGwuqiahHtaWCtI9j5eBhVhiavMj6T3XfYxGj/0','',0,0.00,'112.74.68.71',NULL,'','','2016-07-07 04:04:11','2016-07-07 04:04:11',NULL,NULL,'weixin','ovZmSs7D-Fi6g48JgLjS3Pbp9TXk',NULL,'oejcrs5IexGk2NkArw9iFG1VCNbY',NULL,NULL,NULL),(5,0,'13728389359','原地狂奔的骚年',NULL,'男','13728389359','http://wx.qlogo.cn/mmopen/RS6HVI6LDwT8SibZh9FK4A54unKWLlMrNsmVOk48T6m3JjwRAYOpicKMG1MHUOYgshuFu5NVRXF4xh1MsicicVRcWMF6VQQKc138/0','',0,0.00,'112.74.68.71',NULL,'b2db6704143a8bf359ac7b6609166a6d537902c9','IpTcEoR3','2016-07-07 04:05:19','2016-07-07 07:26:44',NULL,NULL,'weixin','ovZmSs-zRtnoW2AuSUJO-IsYtPww','oiFVUwlbCAe3p1IEyy98ltvTwBv4','oejcrsyQBfiqd8Pd5q686WKvtSkA',NULL,NULL,NULL),(6,0,'','剑',NULL,'男',NULL,'http://wx.qlogo.cn/mmopen/RS6HVI6LDwSg6HccI73PUFJgticaficBHsH7o6ruiaicUgeGFuibQicDicREI1PNcjNRM7libj7UbgNhHtIeDpHZvXlEYwFevNnFmHSR/0','',0,0.00,'112.74.68.71',NULL,'','','2016-07-07 04:42:14','2016-07-07 04:42:14',NULL,NULL,'weixin','ovZmSsw5HDYckBBzzaZzEVW7dPw8',NULL,'oejcrsx20WP21GeFMT9wuTZ524Cg',NULL,NULL,NULL),(7,0,'','清羽。',NULL,'男',NULL,'http://wx.qlogo.cn/mmopen/RS6HVI6LDwT8SibZh9FK4AwgwHFyGurfa6J8khCsB6j5lQB5ib0DZgCfOo4EEaR6ejAeQr6tPLzoTvIKVricttrfEZibVz2r2XEN/0','',0,0.00,'112.74.68.71',NULL,'','','2016-07-07 05:03:02','2016-07-07 05:03:02',NULL,NULL,'weixin','ovZmSs0xQY3EXWtBHbwd4ooikvhY',NULL,'oejcrs6I4XHhbNohgrVGfQ0joBkU',NULL,NULL,NULL),(8,0,'','L__@^O^玲',NULL,'女',NULL,'http://wx.qlogo.cn/mmopen/JATteHJvIXICdwYlomIYV9UuyIZlepvzqqic2vu7fHs97FUu85oR2qMW24xb2JAUiaWpaehuQeQLPy9BjCRk4HhA3vCAj3p9KT/0','',0,0.00,'113.246.166.190',NULL,'','','2016-07-07 06:15:25','2016-07-07 06:15:25',NULL,NULL,'weixin',NULL,'oiFVUwtqFvaDwcoBeuvsP5cENH7s','oejcrsztcD7TnAnRgqZujt67g0J0',NULL,NULL,NULL),(9,0,'18390231902','天涯蓝药师',NULL,'男','18390231902','http://wx.qlogo.cn/mmopen/86xug5S1lDQWWFFHDvEo4iaDqOUibias9HLnwSic6dicHFOKeI977AZFHiaKvZiaRbuq1niaEavULSgcaico9MMc72hnMQnUByyRFqWNN/0','',0,0.00,'112.74.68.71',NULL,'1cc02f689fc497d95ab561c2346f06d3777833ef','TpZ3avn6','2016-07-07 06:48:07','2016-07-07 06:50:03',NULL,NULL,'weixin','ovZmSs3IdruVeHCP5cBatf5Q4q6Y',NULL,'oejcrsx1HqZfL3XnAUT6fj_iqaA8',NULL,NULL,NULL),(10,0,'','Hayden',NULL,'男',NULL,'http://wx.qlogo.cn/mmopen/PiajxSqBRaEK1OCbDPhzj3eIO4wzH1MhRA4IcLYGkHMjeltTiaLcE6Yc4bAUmB90WWTrFRicaLPcHoSibDj1qSicI9g/0','',0,0.00,'112.74.68.71',NULL,'','','2016-07-07 07:09:51','2016-07-07 07:09:51',NULL,NULL,'weixin','ovZmSsxbx9llxSnKFunT2yVthMa8',NULL,'oejcrs62P75j8eBrZ6llRNIg6S2Y',NULL,NULL,NULL),(11,0,'','土石方掘金',NULL,'男',NULL,'http://wx.qlogo.cn/mmopen/RS6HVI6LDwTE4awJ3dq0tf85iar5ia3TicgyQSjKDnRsPSWNsvuC48ITqf7w7j38Ue5iav49VSgKyL3sLwRVRicIaOMwASJJqYeVic/0','',0,0.00,'112.74.68.71',NULL,'','','2016-07-07 09:19:48','2016-07-07 09:19:48',NULL,NULL,'weixin','ovZmSszM6OXVe7-rCzXcJlW5eQeg',NULL,'oejcrs2qDj8-O2CrlVo5-oF8MpCo',NULL,NULL,NULL),(12,0,'','杨木昜',NULL,'女',NULL,'http://wx.qlogo.cn/mmopen/FwceTV6iaDL5QV4zNCcYwqh5wEnTUsbticZo2wrpicraZ22uZ7E9vnXIWIWFAWq2bjY5XwLHxQAPNMPItD7IdQr9t4k7fibqTcWA/0','',0,0.00,'112.74.68.71',NULL,'','','2016-07-07 10:50:20','2016-07-07 10:50:20',NULL,NULL,'weixin','ovZmSs7iZzbLwgesejeIJ0xnj-Fk',NULL,'oejcrs0AcEBa1a9ZJOBcuMNU3ugg',NULL,NULL,NULL),(13,0,'','Y.x',NULL,'男',NULL,'http://wx.qlogo.cn/mmopen/RS6HVI6LDwT8SibZh9FK4A50wdH3ff11yFSsu7ERhmnIQicu8ul9WgMLxJ6O72T45ibq697gN9VXd4doWlTVXiaJpHDTuRxmIeoh/0','',0,0.00,'112.74.68.71',NULL,'','','2016-07-11 06:50:40','2016-07-11 06:50:40',NULL,NULL,'weixin','ovZmSs8izYY9VntxsoZcUR89TcWU',NULL,'oejcrs7Z5iQRrRvP5dUhrqQ247VY',NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-08-18  8:50:36
