-- MySQL dump 10.13  Distrib 5.5.35, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: vpngui
-- ------------------------------------------------------
-- Server version	5.5.35-0+wheezy1

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
-- Table structure for table `ping`
--

DROP TABLE IF EXISTS `ping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ping` (
  `key` int(50) NOT NULL AUTO_INCREMENT,
  `sites` varchar(50) COLLATE utf8_bin NOT NULL,
  `status` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ping`
--

LOCK TABLES `ping` WRITE;
/*!40000 ALTER TABLE `ping` DISABLE KEYS */;
/*!40000 ALTER TABLE `ping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_conf`
--

DROP TABLE IF EXISTS `site_conf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_conf` (
  `key` int(11) NOT NULL AUTO_INCREMENT,
  `siteconf` varchar(50) COLLATE utf8_bin NOT NULL,
  `vpnname` varchar(50) COLLATE utf8_bin NOT NULL,
  `left` varchar(50) COLLATE utf8_bin NOT NULL,
  `leftid` varchar(50) COLLATE utf8_bin NOT NULL,
  `leftsubnet` varchar(50) COLLATE utf8_bin NOT NULL,
  `right` varchar(50) COLLATE utf8_bin NOT NULL,
  `rightid` varchar(50) COLLATE utf8_bin NOT NULL,
  `secretkey` varchar(50) COLLATE utf8_bin NOT NULL,
  `rightsubnet` varchar(50) COLLATE utf8_bin NOT NULL,
  `templateid` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_conf`
--

LOCK TABLES `site_conf` WRITE;
/*!40000 ALTER TABLE `site_conf` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_conf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statusvpn`
--

DROP TABLE IF EXISTS `statusvpn`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statusvpn` (
  `key` int(50) NOT NULL AUTO_INCREMENT,
  `qtyvpn` int(50) NOT NULL,
  `vpn` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statusvpn`
--

LOCK TABLES `statusvpn` WRITE;
/*!40000 ALTER TABLE `statusvpn` DISABLE KEYS */;
/*!40000 ALTER TABLE `statusvpn` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tchat`
--

DROP TABLE IF EXISTS `tchat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tchat` (
  `key` int(255) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pseudo` text COLLATE utf8_bin NOT NULL,
  `message` text COLLATE utf8_bin NOT NULL,
  `user_level` int(50) NOT NULL,
  PRIMARY KEY (`key`,`pseudo`(5))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tchat`
--

LOCK TABLES `tchat` WRITE;
/*!40000 ALTER TABLE `tchat` DISABLE KEYS */;
/*!40000 ALTER TABLE `tchat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `template`
--

DROP TABLE IF EXISTS `template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `template` (
  `key` int(11) NOT NULL AUTO_INCREMENT,
  `templatetype` varchar(50) COLLATE utf8_bin NOT NULL,
  `template_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `left` varchar(50) COLLATE utf8_bin NOT NULL,
  `leftid` varchar(50) COLLATE utf8_bin NOT NULL,
  `leftsubnet` varchar(50) COLLATE utf8_bin NOT NULL,
  `right` varchar(50) COLLATE utf8_bin NOT NULL,
  `rightid` varchar(50) COLLATE utf8_bin NOT NULL,
  `rightsubnet` varchar(50) COLLATE utf8_bin NOT NULL,
  `secretkey` varchar(50) COLLATE utf8_bin NOT NULL,
  `auth` varchar(50) COLLATE utf8_bin NOT NULL,
  `ike` varchar(50) COLLATE utf8_bin NOT NULL,
  `authby` varchar(50) COLLATE utf8_bin NOT NULL,
  `auto` varchar(50) COLLATE utf8_bin NOT NULL,
  `compress` varchar(50) COLLATE utf8_bin NOT NULL,
  `pfs` varchar(50) COLLATE utf8_bin NOT NULL,
  `type` varchar(50) COLLATE utf8_bin NOT NULL,
  `keylife` varchar(50) COLLATE utf8_bin NOT NULL,
  `rekey` varchar(50) COLLATE utf8_bin NOT NULL,
  `esp` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`key`),
  UNIQUE KEY `template_name` (`template_name`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `template`
--

LOCK TABLES `template` WRITE;
/*!40000 ALTER TABLE `template` DISABLE KEYS */;
INSERT INTO `template` VALUES (4,'template','Clear template','','','','','','','','','','','','','','','','',''),(2,'action','Add new template','','','','','','','','','','','','','','','','',''),(3,'action','Delete a template','','','','','','','','','','','','','','','','',''),(1,'action','Clear','','','','','','','','','','','','','','','','','');
/*!40000 ALTER TABLE `template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `full_name` tinytext COLLATE latin1_general_ci NOT NULL,
  `user_name` varchar(200) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `user_level` tinyint(4) NOT NULL DEFAULT '1',
  `pwd` varchar(220) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `approuved` tinyint(1) NOT NULL,
  `allow_pwd_change` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `key` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `idx_search` (`full_name`,`user_name`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin',5,'4f95b2aa337fc897b252bc83f169b088c66cc2978371ba79aa0307a7a2601d7b',1,1,'3pVyo25gJMPbaETn3JaRM3rBsIOnZ0YhCachllkVpTcwqNsS7PHzH1g8JsNJ7pF4a','b2c4b74ade0f57e6838f8dcb11912674100ec50147ad3c35d0c8b3ac542f53d2');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vpnlog`
--

DROP TABLE IF EXISTS `vpnlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vpnlog` (
  `key` int(11) NOT NULL AUTO_INCREMENT,
  `day` int(50) NOT NULL,
  `week` int(50) NOT NULL,
  `month` int(50) NOT NULL,
  `year` int(50) NOT NULL,
  `hour` varchar(50) COLLATE utf8_bin NOT NULL,
  `type` varchar(50) COLLATE utf8_bin NOT NULL,
  `user` varchar(50) COLLATE utf8_bin NOT NULL,
  `user_level` int(50) NOT NULL,
  `sitename` varchar(50) COLLATE utf8_bin NOT NULL,
  `IP` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vpnlog`
--

LOCK TABLES `vpnlog` WRITE;
/*!40000 ALTER TABLE `vpnlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `vpnlog` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-04-21  0:14:23
