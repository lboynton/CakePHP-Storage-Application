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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;


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
  `disabled` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `backup`.`users`
--
INSERT INTO `backup`.`users` (`id`,`username`,`password`,`email`,`created`,`real_name`,`admin`,`quota`,`last_login`,`disabled`) VALUES 
  (1,'lee','d3521f0f4841ff1a777252f1d0ed1671236ae505','lee@lboynton.com','2008-09-12 17:17:27','Lee Boynton',1,10485760,'2008-09-20 18:07:17',0);


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
