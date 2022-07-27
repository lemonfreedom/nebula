SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `nebula_metas` (
  `mid` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

INSERT INTO `nebula_metas` (`mid`, `name`) VALUES
(0, '默认分类');

CREATE TABLE `nebula_options` (
  `name` varchar(30) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `nebula_options` (`name`, `value`) VALUES
('allowRegister', '1'),
('description', '又一个博客网站诞生了'),
('smtp', 'a:5:{s:4:\"host\";s:11:\"smtp.qq.com\";s:8:\"username\";s:13:\"226582@qq.com\";s:8:\"password\";s:16:\"revpqsbyoyvucaig\";s:4:\"port\";i:465;s:4:\"name\";s:6:\"Nebula\";}'),
('title', 'Nebula');

CREATE TABLE `nebula_posts` (
  `pid` int UNSIGNED NOT NULL,
  `mid` tinyint UNSIGNED NOT NULL,
  `title` varchar(60) NOT NULL,
  `content` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `nebula_users` (
  `uid` int UNSIGNED NOT NULL,
  `role` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `nickname` varchar(60) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(32) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `nebula_users` (`uid`, `role`, `nickname`, `username`, `password`, `email`, `token`) VALUES
(1, 1, 'admin', 'admin', 'ciybkc88f05meqaulgc1mzlwg9nohkewff9346ae29a24f9f05c0953839cd5933', '226582@qq.com', 'xaf5f7ys9oihiaorpvr2zc4z0kmipacp');


ALTER TABLE `nebula_metas`
  ADD PRIMARY KEY (`mid`);

ALTER TABLE `nebula_options`
  ADD PRIMARY KEY (`name`);

ALTER TABLE `nebula_posts`
  ADD PRIMARY KEY (`pid`) USING BTREE;

ALTER TABLE `nebula_users`
  ADD PRIMARY KEY (`uid`);


ALTER TABLE `nebula_posts`
  MODIFY `pid` int UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `nebula_users`
  MODIFY `uid` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
