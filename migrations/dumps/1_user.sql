-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Май 22 2019 г., 08:21
-- Версия сервера: 5.7.24-0ubuntu0.16.04.1
-- Версия PHP: 7.0.32-4+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `fish`
--

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_confirm_token` varchar(255) DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_username` (`username`),
  UNIQUE KEY `user_email` (`email`),
  UNIQUE KEY `email_confirm_token` (`email_confirm_token`),
  KEY `user_id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `email_confirm_token`, `status`, `created_at`, `updated_at`) VALUES
(1, 'defaultAdmin', 'nssbhI2AE_An0KvVniwetkysRSQIEgMg', '$2y$13$5OQeP85gWUlcX7mf1xQGpeOGD27E4.B/uRQxCBGXDWd02F/K2KvZO', NULL, 'admin@email.comee', NULL, 10, 1557071324, 1557079338),
(2, 'defaultUser', 'ceMN9qmYb1YP0lhS6GgwY8-H8ZhrEBUQ', '$2y$13$P4xveFIYdSceoYgmnwkSee2hv4zoQUIr8qmJDeAMMcp4b7fCFksa6', NULL, 'lokoko.xle@ukr.net', NULL, 10, 1557071325, 1557079618),
(3, 'oleksiiTest', 'o5uPq1EmPMJNvhNpZ4VvLyw9VBxy5DkL', '$2y$13$dfOW1eyh/Yllp4rbT6Xcn.FwVt7cWGBryZIHo2pYcgvt0gFXOCju.', NULL, 'whitesnake1969@ukr.net', NULL, 10, 1557079668, 1557079708);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
