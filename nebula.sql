/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE IF NOT EXISTS `nebula_options` (
  `name` varchar(30) NOT NULL,
  `value` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `nebula_options` (`name`, `value`) VALUES
	('allowRegister', '1'),
	('description', '又一个博客网站诞生了'),
	('smtp', 'a:5:{s:4:"host";s:11:"smtp.qq.com";s:8:"username";s:13:"226582@qq.com";s:8:"password";s:16:"revpqsbyoyvucaig";s:4:"port";i:465;s:4:"name";s:6:"Nebula";}'),
	('title', 'Nebula');

CREATE TABLE IF NOT EXISTS `nebula_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(60) DEFAULT '',
  `username` varchar(60) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(32) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

INSERT INTO `nebula_users` (`uid`, `nickname`, `username`, `password`, `email`, `token`) VALUES
	(1, '', 'admin', 'm3xyauttakiz4nqhp1zkxci3zd4vhgfg0afb255303bbcc23a7c6df84e5b924e6', '226582@qq.com', ''),
	(2, '', 'admin2', 'a3jb6y1m9cwwnwx6j5cciz7bkkzlzi8g50f5be79f11669fabd1485a415ed84ce', '3855680@qq.com', '');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
