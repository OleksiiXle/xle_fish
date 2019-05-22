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
-- Структура таблицы `user_data`
--

CREATE TABLE IF NOT EXISTS `user_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT '' COMMENT 'Имя',
  `middle_name` varchar(50) DEFAULT '' COMMENT 'Отчество',
  `last_name` varchar(50) DEFAULT '' COMMENT 'Фамилия',
  `last_rout` varchar(250) DEFAULT NULL,
  `last_rout_time` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_user_data` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_data`
--

INSERT INTO `user_data` (`id`, `user_id`, `first_name`, `middle_name`, `last_name`, `last_rout`, `last_rout_time`, `created_at`, `updated_at`) VALUES
(35, 1, 'Главныйee', 'Системныйee', 'Администраторee', '/post/post/images/yuna.jpg', 1558455610, 1557071324, 1557079346),
(36, 2, 'Дефолтный', 'Первый', 'Пользователь', '/adminx/user/logout', 1557079562, 1557071325, 1557079555),
(37, 3, 'Тест', 'Гунп', 'Оргштат', '/adminx/user/logout', 1557079758, 1557079668, 1557079668);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `user_data`
--
ALTER TABLE `user_data`
  ADD CONSTRAINT `fk_user_user_data` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
