-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.51b-community-nt


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
-- Definition of table `backups`
--

DROP TABLE IF EXISTS `backups`;
CREATE TABLE `backups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(75) NOT NULL,
  `size` int(11) NOT NULL,
  `data` mediumblob NOT NULL,
  `created` datetime default NULL,
  `user_id` int(11) NOT NULL,
  `hash` varchar(40) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `backups`
--

/*!40000 ALTER TABLE `backups` DISABLE KEYS */;
INSERT INTO `backups` (`id`,`name`,`size`,`data`,`created`,`user_id`,`hash`) VALUES 
 (1,'test.txt',6,0x68656C6C6F21,NULL,0,'5a8dd3ad0756a93ded72b823b19dd877');
/*!40000 ALTER TABLE `backups` ENABLE KEYS */;


--
-- Definition of table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(40) default NULL,
  `created` datetime default NULL,
  `real_name` varchar(45) default NULL,
  `admin` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`,`username`,`password`,`email`,`created`,`real_name`,`admin`) VALUES 
 (0,'lee','d3521f0f4841ff1a777252f1d0ed1671236ae505','lee@lboynton.com','2008-07-22 22:50:55','Lee Boynton',1),
 (1,'test','b71c2c45c6d44c21c4048be035f6c0118188f0fe','test@test.com','2008-07-28 10:09:05','',0),
 (2,'meh','2319165f114f19a1019bfb412f4bc3dc3e498215','mehhy@meh.meh','2008-07-31 22:16:08','Mehhy M. Meh the Mehhyist',0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
