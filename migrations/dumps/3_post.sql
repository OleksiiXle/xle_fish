-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Май 22 2019 г., 08:22
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
-- Структура таблицы `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT 'Владелец',
  `target` int(11) DEFAULT '0' COMMENT 'Цель',
  `type` int(11) DEFAULT '0' COMMENT 'Тип',
  `name` varchar(250) DEFAULT NULL COMMENT 'Название',
  `content` blob COMMENT 'Содержимое',
  `created_at` int(11) NOT NULL COMMENT 'Создано',
  `updated_at` int(11) NOT NULL COMMENT 'Изменено',
  PRIMARY KEY (`id`),
  KEY `fk_user_post` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `post`
--

INSERT INTO `post` (`id`, `user_id`, `target`, `type`, `name`, `content`, `created_at`, `updated_at`) VALUES
(9, 1, 0, 1, 'Весенняя ловля щуки на спиннинг – сезонные особенности', 0x3c703ed0a1d0b5d0b7d0bed0bd20d0bbd0bed0b2d0bbd0b820d189d183d0bad0b820d0bdd0b0d187d0b8d0bdd0b0d0b5d182d181d18f20d0b220d0bdd0b0d187d0b0d0bbd0b520d0bcd0b0d180d182d0b02c20d187d0b0d181d182d0be20d0b820d182d0bed0b3d0b4d0b02c20d0bad0bed0b3d0b4d0b020d0bdd0b020d0b2d0bed0b4d0b520d0b5d189d19120d0bed181d182d0b0d191d182d181d18f20d0bbd191d0b42e20d09220d18dd182d0be20d0b2d180d0b5d0bcd18f20d0bdd0b0d187d0b8d0bdd0b0d0b5d182d181d18f20d182d0b0d0ba20d0bdd0b0d0b7d18bd0b2d0b0d0b5d0bcd18bd0b93c7374726f6e673e266e6273703bd0b6d0bed1803c2f7374726f6e673e2c20d0bad0bed0b3d0b4d0b020d189d183d0bad0b020d0bdd0b0d187d0b8d0bdd0b0d0b5d18220d0b0d0bad182d0b8d0b2d0bdd0be20d0b1d180d0b0d182d18c20d0bbd18ed0b1d183d18e20d0bfd180d0b8d0bcd0b0d0bdd0bad1832c20d181d182d180d0b5d0bcd18fd181d18c20d0b2d0bed0b7d0bdd0b0d0b3d180d0b0d0b4d0b8d182d18c20d181d0b5d0b1d18f20d0b7d0b020d0b4d0bed0bbd0b3d183d18e20d0b3d0bed0bbd0bed0b4d0bdd183d18e20d0b7d0b8d0bcd1832e20d098d0bcd0b5d0bdd0bdd0be20d0b220d18dd182d0be20d0b2d180d0b5d0bcd18f20d0b820d0bdd0b0d187d0b8d0bdd0b0d0b5d182d181d18f3c7374726f6e673e266e6273703bd0b7d0bed0bbd0bed182d0bed0b520d0b2d180d0b5d0bcd18f3c2f7374726f6e673e266e6273703bd0bbd0bed0b2d0bbd0b820d0bdd0b020d181d0bfd0b8d0bdd0bdd0b8d0bdd0b326726171756f3b2e3c2f703e0d0a0d0a3c68323ed09bd183d187d188d0b8d0b520d181d0bfd0bed181d0bed0b1d18b20d0bbd0bed0b2d0bbd0b820d189d183d0ba3c2f68323e0d0a0d0a3c703ed09ed0bfd182d0b8d0bcd0b0d0bbd18cd0bdd0bed0b520d0b2d180d0b5d0bcd18f20d0b4d0bbd18f20d185d0bed180d0bed188d0b5d0b3d0be20d0bad0bbd191d0b2d0b020d189d183d0bad0b820d0bfd180d0b8d185d0bed0b4d0b8d182d181d18f20d0bdd0b020d183d182d180d0b5d0bdd0bdd0b8d0b52028d18120d181d0b5d0bcd0b820d0b4d0be20d0b4d0b5d181d18fd182d0b82920d0b820d0b2d0b5d187d0b5d180d0bdd0b8d0b520d187d0b0d181d18b2028d0bfd0bed181d0bbd0b52031362920d0b220d182d0b5d18520d0bcd0b5d181d182d0b0d1852c20d0b3d0b4d0b520d0b5d181d182d18c20d0bfd18bd188d0bdd0b0d18f20d0b2d0bed0b4d0bdd0b0d18f20d180d0b0d181d182d0b8d182d0b5d0bbd18cd0bdd0bed181d182d18c2e20d098d0bcd0b5d0bdd0bdd0be20d182d0b0d0bc20d0bfd180d18fd187d183d182d181d18f20d189d183d0bad0b82c20d0bed181d0bbd0b0d0b1d0bbd0b5d0bdd0bdd18bd0b520d0bdd0b5d180d0b5d181d182d0bed0bc2c20d0b220d0bed0b6d0b8d0b4d0b0d0bdd0b8d0b820d0b4d0bed0b1d18bd187d0b82e3c2f703e0d0a0d0a3c703ed0a1d0bbd0b5d0b4d183d0b5d182266e6273703b3c7374726f6e673ed180d0b0d0b7d0bbd0b8d187d0b0d182d18c20d0bed0b7d191d180d0bdd18bd18520d0b820d180d0b5d187d0bdd18bd18520d189d183d0ba3c2f7374726f6e673e2c20d0bfd0bed181d0bbd0b5d0b4d0bdd0b8d0b520d0b3d0bed180d0b0d0b7d0b4d0be20d18dd0bdd0b5d180d0b3d0b8d187d0bdd0b5d0b520d181d0b2d0bed0b8d18520d0b1d0bed0bbd0b5d0b520d0bcd0b5d0b4d0bbd0b8d182d0b5d0bbd18cd0bdd18bd18520d181d0b5d181d182d0b5d1802c20d0bfd180d0b8d0b2d18bd0bad188d0b8d18520d0ba20d0bed182d181d183d182d181d182d0b2d0b8d18e20d182d0b5d187d0b5d0bdd0b8d18f2e20d09fd0bed18dd182d0bed0bcd18320d0bdd0b020d180d0b5d0bad0b520d0bfd180d0bed0b2d0bed0b4d0bad0b020d181d0bfd0b8d0bdd0bdd0b8d0bdd0b3d0bed0bc20d0b4d0bed0bbd0b6d0bdd0b020d0b1d18bd182d18c20d0bdd0b0d0bcd0bdd0bed0b3d0be20d0b1d0bed0bbd0b5d0b520d0b8d0bdd182d0b5d0bdd181d0b8d0b2d0bdd0bed0b92c20d187d0b5d0bc20d0b220d181d182d0bed18fd187d0b5d0b920d0b2d0bed0b4d0b52e3c2f703e0d0a, 1557942143, 1557942225),
(10, 1, 0, 1, 'Секреты успеха: как поймать форель на спиннинг', 0x3c703ed09bd18ed0b1d0b8d182d0b5d0bbd0b5d0b920d182d0b0d0bad0bed0b920d180d18bd0b1d0b0d0bbd0bad0b8266e6273703bd181d182d0b0d0bdd0bed0b2d0b8d182d181d18f20d0b2d181d0b520d0b1d0bed0bbd18cd188d0b520d0b820d0b1d0bed0bbd18cd188d0b52c20d0bed182d182d0bed0b3d0be20d0bdd0b5d0bcd0b0d0bbd0bed0b2d0b0d0b6d0bdd0be20d181d0b1d0b5d180d0b5d187d18c20d18dd182d18320d0bfd180d0b5d0bad180d0b0d181d0bdd183d18e20d180d18bd0b1d1832e20d09dd0b5d181d0bcd0bed182d180d18f20d0bdd0b020d182d0be2c20d187d182d0be20d180d18bd0b1d0b020d0b2d0b5d0b7d0b4d0b5d181d183d189d0b0d18f3a20d0bed0bdd0b020d0bed0b1d0b8d182d0b0d0b5d18220d0b220d180d0b5d0bad0b0d1852c20d0bed0b7d0b5d180d0b0d1852c20d180d183d187d18cd18fd18520d0b820d0bfd180d183d0b4d0b0d185266e6273703b28d0b2266e6273703bd0bfd0bed181d0bbd0b5d0b4d0bdd0b8d18520d0bed181d0bed0b1d18c266e6273703bd180d0b0d0b7d0b2d0bed0b4d18fd18220d181d0bfd0b5d186d0b8d0b0d0bbd18cd0bdd0be20266d646173683b20d0bfd0bbd0b0d182d0bdd0b0d18f20d180d18bd0b1d0b0d0bbd0bad0b0292c20d0bdd183d0b6d0bdd0be266e6273703b3c7374726f6e673ed181d0bed0b1d0bbd18ed0b4d0b0d182d18c20d0bfd180d0b0d0b2d0b8d0bbd0b020d0bbd0bed0b2d0bbd0b83c2f7374726f6e673e2e266e6273703bd09a20d0bfd180d0b8d0bcd0b5d180d1832c20d0bdd0b520d0b2d18bd0bbd0b0d0b2d0bbd0b8d0b2d0b0d182d18c20d184d0bed180d0b5d0bbd18c2c20d0bdd0b520d0b4d0bed181d182d0b8d0b3d188d183d18e20d0b7d180d0b5d0bbd0bed181d182d0b82e20d095d189d0b520d0b2d0b0d0b6d0bdd0be20d181d0bed0b1d0bbd18ed0b4d0b0d182d18c20d187d0b8d181d182d0bed182d18320d0bdd0b020d181d0b0d0bcd0b8d18520d0b2d0bed0b4d0bed0b5d0bcd0b0d1852e3c2f703e0d0a0d0a3c703ed09bd0bed0b2d0bbd18f20d184d0bed180d0b5d0bbd0b820d0bdd0b020d181d0bfd0b8d0bdd0bdd0b8d0bdd0b320d0b1d18bd0b2d0b0d0b5d18220d0bdd0b0d0b8d0b1d0bed0bbd0b5d0b520d180d0b5d0b7d183d0bbd18cd182d0b0d182d0b8d0b2d0bdd0b020d18120d0bad0bed0bbd0b5d0b1d0bbd18ed189d0b8d0bcd0b8d181d18f20d0b820d0b2d180d0b0d189d0b0d18ed189d0b8d0bcd0b8d181d18f20d0b1d0bbd0b5d181d0bdd0b0d0bcd0b82c20d0b020d182d0b0d0bad0b6d0b520d0bcd0b5d0bbd0bad0b8d0bcd0b820d0b2d0bed0b1d0bbd0b5d180d0b0d0bcd0b82e20d09ad0bed0bbd0b5d0b1d0bbd18ed189d0b8d0b5d181d18f20d0b1d0bbd0b5d181d0bdd18b20d0bbd183d187d188d0b520d0b1d180d0b0d182d18c20d0b2d18bd182d18fd0bdd183d182d0bed0b920d184d0bed180d0bcd18b2c20d0bad0bed182d0bed180d18bd0b520d0bfd180d0b820d0b4d0b2d0b8d0b6d0b5d0bdd0b8d0b820d0b4d0b5d0bbd0b0d18ed18220d180d0b5d0b7d0bad0b8d0b520d180d18bd0b2d0bad0b820d0b220d181d182d0bed180d0bed0bdd18b2e3c2f703e0d0a0d0a3c703ed0a7d182d0be20d0bad0b0d181d0b0d0b5d182d181d18f20d0b2d0b5d180d182d183d188d0b5d0ba2c20d182d0be20d0bed0bdd0b820d0b4d0bed0bbd0b6d0bdd18b20d0b1d18bd182d18c20d0bdd0b5d0b1d0bed0bbd18cd188d0b8d18520d180d0b0d0b7d0bcd0b5d180d0bed0b220d0b820d0bed0b1d0bbd0b0d0b4d0b0d182d18c20d185d0bed180d0bed188d0b5d0b92c20d181d182d0b0d0b1d0b8d0bbd18cd0bdd0bed0b920d0b8d0b3d180d0bed0b92c20d0bad0b0d0ba20d0bfd180d0b820d0b4d0b2d0b8d0b6d0b5d0bdd0b8d0b820d0bfd0be20d182d0b5d187d0b5d0bdd0b8d18e2c20d182d0b0d0ba20d0b820d0bfd180d0bed182d0b8d0b220d182d0b5d187d0b5d0bdd0b8d18f2e20d09fd0bed18dd182d0bed0bcd18320d0bdd0b0d0b8d0bbd183d187d188d0b8d0bc20d0b2d0b0d180d0b8d0b0d0bdd182d0bed0bc20d0b1d183d0b4d183d18220d0bfd180d0b8d0bcd0b0d0bdd0bad0b820d18120d188d0b8d180d0bed0bad0b8d0bc20d0bbd0b5d0bfd0b5d181d182d0bad0bed0bc2e3c2f703e0d0a0d0a3c703ed09dd0b5d0bad0bed182d0bed180d18bd0b520d180d18bd0b1d0bed0bbd0bed0b2d18b20d0b4d0bbd18f20d183d0b2d0b5d0bbd0b8d187d0b5d0bdd0b8d18f20d187d0b8d181d0bbd0b020d0bfd0bed0bad0bbd0b5d0b2d0bed0ba20d18dd0bad181d0bfd0b5d180d0b8d0bcd0b5d0bdd182d0b8d180d183d18ed18220d18120d0b4d0bed0b1d0b0d0b2d0bbd0b5d0bdd0b8d0b5d0bc20d0ba20d0b1d0bbd0b5d181d0bdd0b520d0bdd0b5d0b1d0bed0bbd18cd188d0b8d18520d182d0b2d0b8d181d182d0b5d180d0bed0b220d0b820d0bcd183d188d0b5d0ba2c20d0bad0bed182d0bed180d18bd0bcd0b820d0bed0b1d18bd187d0bdd0be20d0bcd0b0d181d0bad0b8d180d183d18ed18220d0bad180d18ed187d0bad0b82e3c2f703e0d0a, 1557989138, 1557989583),
(11, 1, 0, 1, '000000', 0x3c703e6466646667646667646620646667206466266e6273703b3c2f703e0d0a, 1558346895, 1558346895);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_user_post` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
