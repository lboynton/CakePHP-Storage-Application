-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.51a-3ubuntu5.3


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
CREATE TABLE  `backup`.`backups` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `parent_id` int(11) default NULL,
  `lft` int(11) default NULL,
  `rght` int(11) default NULL,
  `name` varchar(255) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `size` int(10) unsigned default NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `backup`.`backups`
--
INSERT INTO `backup`.`backups` VALUES  (2,NULL,1,2,'lucic.jpg',1,'2008-09-15 20:47:53','2008-09-15 20:47:53',11790,'file'),
 (3,NULL,3,24,'Pictures',1,'2008-09-15 20:48:19','2008-09-16 21:40:18',NULL,'folder'),
 (4,3,4,5,'Dave_Mustaine2.jpg',1,'2008-09-15 20:48:37','2008-09-15 20:48:37',5456,'file'),
 (5,3,6,7,'dave_mustaine_for_president__1153238376.jpg',1,'2008-09-15 20:48:37','2008-09-15 20:48:37',42850,'file'),
 (6,3,8,9,'dave_photo!.jpg',1,'2008-09-15 20:48:37','2008-09-15 20:48:45',112959,'file'),
 (7,3,10,11,'db-ep3-poster.jpg',1,'2008-09-15 20:48:37','2008-09-15 20:48:37',86258,'file'),
 (8,3,12,13,'db-ep3-poster1.jpg',1,'2008-09-15 20:48:37','2008-09-15 20:48:37',90234,'file'),
 (9,3,14,15,'domo_p90.jpg',1,'2008-09-15 20:48:37','2008-09-15 20:48:37',24629,'file'),
 (10,3,16,17,'dos2.jpg',1,'2008-09-15 20:48:37','2008-09-15 20:48:37',42419,'file'),
 (11,3,18,19,'dowie0pd.gif',1,'2008-09-15 20:48:37','2008-09-15 20:48:37',29251,'file'),
 (12,3,20,21,'droids.jpg',1,'2008-09-15 20:48:37','2008-09-15 20:48:37',132599,'file'),
 (13,3,22,23,'DSCF2522.JPG',1,'2008-09-15 20:48:37','2008-09-15 20:48:37',189824,'file');
INSERT INTO `backup`.`backups` VALUES  (14,NULL,25,26,'194.jpg',1,'2008-09-16 17:49:23','2008-09-16 21:37:50',47234,'file'),
 (15,NULL,27,28,'_40122668_roonster203.jpg',1,'2008-09-16 17:50:00','2008-09-16 17:50:00',7543,'file'),
 (16,NULL,29,30,'Shite',1,'2008-09-16 21:41:22','2008-09-16 21:41:22',NULL,'folder'),
 (17,NULL,31,32,'Prince_Of_Persia .jpg',1,'2008-09-16 23:56:14','2008-09-16 23:56:14',78123,'file');

--
-- Definition of table `backup`.`users`
--
CREATE TABLE  `backup`.`users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(40) default NULL,
  `created` datetime default NULL,
  `real_name` varchar(45) default NULL,
  `admin` tinyint(1) NOT NULL default '0',
  `quota` bigint(20) unsigned default '5242880',
  `last_login` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `backup`.`users`
--
INSERT INTO `backup`.`users` VALUES  (1,'lee','d3521f0f4841ff1a777252f1d0ed1671236ae505','lee@lboynton.com','2008-09-12 17:17:27','Lee Boynton',1,10485760,'2008-09-16 23:38:48'),
 (2,'bob','d3521f0f4841ff1a777252f1d0ed1671236ae505','bob@bob.com','2008-09-16 16:03:20','Bob',0,1048576,'2008-09-16 21:42:08');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
