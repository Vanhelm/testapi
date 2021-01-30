-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 30 2021 г., 15:07
-- Версия сервера: 5.7.29
-- Версия PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `country`
--

-- --------------------------------------------------------

--
-- Структура таблицы `pay`
--

CREATE TABLE `pay` (
  `id` int(11) NOT NULL,
  `session` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lifetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pay` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `pay`
--

INSERT INTO `pay` (`id`, `session`, `lifetime`, `name`, `pay`, `status`) VALUES
(7, 'cbf541f21e2a76a5163edecaea3567f0', '2021-01-29 16:24:45', 'Лазерная указка', 1500, NULL),
(8, 'ecd475aa5cf372b1b6b40622c97f1443', '2021-01-29 16:25:49', 'Лазерная указка', 421, NULL),
(9, '8520a582f83ebfeedbb1f95bedd659d4', '2021-01-29 16:26:26', 'Vanhelm', 421, NULL),
(10, '44ed171303e760044abe7df00eee20b6', '2021-01-29 16:27:38', '123', 421, NULL),
(11, '1d7a746c7edc97f45b0cf55ec55bc790', '2021-01-29 16:27:55', 'Vanhelm', 421, NULL),
(12, 'e31208ab4d79b1842a53098037ac8a90', '2021-01-29 17:28:21', 'Максим', 143, NULL),
(13, '0ba5820fd46726f976584d257b6569f1', '2021-01-29 18:14:24', 'Лазерная указка', 123142, NULL),
(14, '55fe3c903cd7aeda5c5624fc3d45a27d', '2021-01-29 18:45:45', 'Vanhelm', 123, NULL),
(22, '434c4a5c7fc12ca322958674f1504346', '2021-01-29 17:29:23', 'Максим', 421, NULL),
(23, '5b350c8e719a9412cbaa435121fb9f09', '2021-01-29 17:50:28', 'Максим', 123, NULL),
(24, '5bef72581b798696afaee80be8ae334f', '2021-01-29 17:04:20', '31212', 14124, 1),
(25, 'b977fbfb7a8bb7fffaad9aec9462e049', '2021-01-30 06:27:48', 'Лазерная указка', 3000, NULL),
(26, '8e3956a8acdcda005a39f9424ef415fe', '2021-01-30 06:53:26', 'Vanhelm', 1500, NULL),
(27, '60d1a88829a381d003c63d327397e66d', '2021-01-30 07:07:02', 'Ластик', 1200, NULL),
(28, '3a5e592d19ee2f4ab86cb61171c16b04', '2021-01-30 07:16:04', '123', 421, NULL),
(29, '155f60c8bdf603f74410802d389df65f', '2021-01-30 08:53:12', 'Тестовый товар', 3200, 1),
(30, '5734c7c0e33128ed204ad6a7ffe490bd', '2021-01-30 09:08:48', 'Vanhelm', 412, NULL),
(31, 'c729985104a6e0a6618a8b3becb0eba2', '2021-01-30 09:10:00', 'Vanhelm', 421, NULL),
(32, '3a197b6e1f64780916c9ca7c647455bc', '2021-01-30 09:26:29', '123', 421, NULL),
(33, '32c0600c260647e3a78226dab1fd4951', '2021-01-30 09:41:35', 'Ручка', 10, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `session` bigint(20) DEFAULT NULL,
  `data` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `tokens`
--

INSERT INTO `tokens` (`id`, `token`, `session`, `data`) VALUES
(1, '7610735e0d3753deca180199b629913d', NULL, '2021-01-29 11:12:24');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `pay`
--
ALTER TABLE `pay`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `pay`
--
ALTER TABLE `pay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT для таблицы `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
