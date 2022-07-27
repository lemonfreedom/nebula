/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE IF NOT EXISTS `nebula_metas` (
  `mid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `nebula_metas` (`mid`, `name`) VALUES
	(0, '默认分类');

CREATE TABLE IF NOT EXISTS `nebula_options` (
  `name` varchar(30) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `nebula_options` (`name`, `value`) VALUES
	('allowRegister', '1'),
	('description', '又一个博客网站诞生了'),
	('smtp', 'a:5:{s:4:"host";s:11:"smtp.qq.com";s:8:"username";s:13:"226582@qq.com";s:8:"password";s:16:"revpqsbyoyvucaig";s:4:"port";i:465;s:4:"name";s:6:"Nebula";}'),
	('title', 'Nebula');

CREATE TABLE IF NOT EXISTS `nebula_posts` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` tinyint(3) unsigned NOT NULL,
  `title` varchar(60) NOT NULL,
  `content` longtext NOT NULL,
  PRIMARY KEY (`pid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `nebula_posts` (`pid`, `mid`, `title`, `content`) VALUES
	(1, 1, '你哈', '<p>打发范德萨分</p>'),
	(2, 0, '中国', '<p>发士大夫</p>'),
	(3, 1, '这是图片测试', ''),
	(4, 0, '这是图片测试', '<p><img src="https://yaohuo.me/bbs/upload/1000/2022/07/27/27533_0858130img-56bc3f0feb54f3043ac066eb735e722d.jpg" alt="" width="100%"></p>');

CREATE TABLE IF NOT EXISTS `nebula_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `nickname` varchar(60) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

INSERT INTO `nebula_users` (`uid`, `role`, `nickname`, `username`, `password`, `email`, `token`) VALUES
	(1, 0, 'admin', '回不到以往', '322bqdbuk83ek2dq3e4rsiqlo9eeb2pbd00ec2058ca74eb4e4c7807dc5662a98', '226582@qq.com', 'nelyb4hcv8v66uai1duz4oix0pthdkl7'),
	(2, 1, 'test', 'test', '4nz6j6pbvsyzddi9vel04hsigeg91z51b592d52845f6dbc0712867c5ad2f39fc', '3855680@qq.com', '');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
