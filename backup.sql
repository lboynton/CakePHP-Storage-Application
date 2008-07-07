-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.51a


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema `backup`
--

CREATE DATABASE IF NOT EXISTS `backup`;
USE `backup`;

--
-- Definition of table `backup`.`backups`
--

DROP TABLE IF EXISTS `backup`.`backups`;
CREATE TABLE  `backup`.`backups` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `data` mediumblob NOT NULL,
  `name` varchar(75) NOT NULL,
  `type` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `md5sum` varchar(32) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `backup`.`backups`
--

/*!40000 ALTER TABLE `backups` DISABLE KEYS */;
LOCK TABLES `backups` WRITE;
INSERT INTO `backup`.`backups` VALUES  (5,1,0x746573740A,'test','application/octet-stream',5,'2008-07-07 14:47:07','2008-07-07 14:47:07','d8e8fca2dc0f896fd7cb4cb0031ba249');
UNLOCK TABLES;
/*!40000 ALTER TABLE `backups` ENABLE KEYS */;


--
-- Definition of table `backup`.`users`
--

DROP TABLE IF EXISTS `backup`.`users`;
CREATE TABLE  `backup`.`users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(40) default NULL,
  `created` datetime default NULL,
  `realName` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `backup`.`users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
LOCK TABLES `users` WRITE;
INSERT INTO `backup`.`users` VALUES  (1,'newuser','098f6bcd4621d373cade4e832627b4f6','user@domain.com','2008-06-30 20:25:15',''),
 (9,'testing','ae2b1fca515949e5d54fb22b8ed95575','testing@testing.com','2008-07-07 12:54:53','Mr Test'),
 (10,'asdf','e10adc3949ba59abbe56e057f20f883e','asdf@asdf.com','2008-07-07 13:40:14','asdf'),
 (11,'abcdef','e80b5017098950fc58aad83c8c14978e','a@a.com','2008-07-07 13:42:03','abcdef'),
 (12,'aaaaaa','0b4e7a0e5fe84ad35fb5f95b9ceeac79','a@a.com','2008-07-07 13:42:29','aaaaaa'),
 (13,'bbbbbb','875f26fdb1cecf20ceb4ca028263dec6','a@a.com','2008-07-07 13:42:48','bbbbbb'),
 (14,'cccccc','c1f68ec06b490b3ecb4066b1b13a9ee9','c@c.com','2008-07-07 13:44:53','cccccc'),
 (15,'eeeeee','cd87cd5ef753a06ee79fc75dc7cfe66c','eeeeee@eeeeee.com','2008-07-07 13:46:37','eeeeee'),
 (16,'pppppp','e882b72bccfc2ad578c27b0d9b472a14','p@p.com','2008-07-07 14:01:30','pppppp'),
 (17,'1','e10adc3949ba59abbe56e057f20f883e','1@1.com','2008-07-07 14:17:55','123456'),
 (18,'kkkkkk','c08ac56ae1145566f2ce54cbbea35fa3','k@k.com','2008-07-07 14:18:24','kkkkkk');
UNLOCK TABLES;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
