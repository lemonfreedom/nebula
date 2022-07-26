SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `nebula_options` (
  `name` varchar(30) NOT NULL,
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `nebula_options` (`name`, `value`) VALUES
('allowRegister', '1'),
('description', '又一个博客网站诞生了'),
('smtp', 'a:5:{s:4:\"host\";s:11:\"smtp.qq.com\";s:8:\"username\";s:13:\"226582@qq.com\";s:8:\"password\";s:16:\"revpqsbyoyvucaig\";s:4:\"port\";i:465;s:4:\"name\";s:6:\"Nebula\";}'),
('title', 'Nebula');

CREATE TABLE `nebula_users` (
  `uid` int UNSIGNED NOT NULL,
  `role` tinyint UNSIGNED NOT NULL,
  `nickname` varchar(60) DEFAULT '',
  `username` varchar(60) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `nebula_users` (`uid`, `role`, `nickname`, `username`, `password`, `email`, `token`) VALUES
(1, 0, '', 'admin', '75ev40h18izmtugnumgpjzv182iwvtcta787bc109365a390e2a6448c0dc4df04', '226582@qq.com', 'k5g802k6iiv5ogyj763940i6mezg1nnj'),
(2, 0, '', 'admin2', 'a3jb6y1m9cwwnwx6j5cciz7bkkzlzi8g50f5be79f11669fabd1485a415ed84ce', '3855680@qq.com', '');


ALTER TABLE `nebula_options`
  ADD PRIMARY KEY (`name`);

ALTER TABLE `nebula_users`
  ADD PRIMARY KEY (`uid`);


ALTER TABLE `nebula_users`
  MODIFY `uid` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
