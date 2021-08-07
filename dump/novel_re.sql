-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 07 2021 г., 14:47
-- Версия сервера: 10.3.22-MariaDB
-- Версия PHP: 7.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `novel_re`
--

-- --------------------------------------------------------

--
-- Структура таблицы `bookmarks`
--

CREATE TABLE `bookmarks` (
  `bookmark_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `novel_id` int(11) NOT NULL,
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `novel_id` int(11) NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` int(1) NOT NULL DEFAULT 1,
  `delete_marker` int(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `genres`
--

CREATE TABLE `genres` (
  `genre_id` int(11) NOT NULL,
  `genre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `genres`
--

INSERT INTO `genres` (`genre_id`, `genre`) VALUES
(6, 'Драма'),
(7, 'Комедия'),
(8, 'Научная фантастика'),
(9, 'Романтика'),
(10, 'Хоррор'),
(12, 'Детектив'),
(13, 'Триллер'),
(14, 'Мистика'),
(15, 'Повседневность'),
(16, 'Приключения'),
(17, 'Школа'),
(18, 'Фантастика'),
(19, 'Фентези'),
(20, 'Этти'),
(21, 'Хентай'),
(22, 'Экшн');

-- --------------------------------------------------------

--
-- Структура таблицы `images`
--

CREATE TABLE `images` (
  `image_id` int(11) NOT NULL,
  `foreign_id` int(11) NOT NULL,
  `usage` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path_to_image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `extension` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `affiliation` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `images`
--

INSERT INTO `images` (`image_id`, `foreign_id`, `usage`, `path_to_image`, `name`, `type`, `size`, `extension`, `affiliation`, `created_at`, `update_at`) VALUES
(14, 67, 'avatar', 'public/images/1_1628238375_2042326583.jpg', '1_1628238375_2042326583.jpg', 'image/jpeg', 588492, 'jpg', 'users', '2021-08-01 09:50:20', '2021-08-06 08:26:15'),
(15, 68, 'avatar', 'public/images/1_1627812064_396756137.jpg', '1_1627812064_396756137.jpg', 'image/jpeg', 603114, 'jpg', 'users', '2021-08-01 09:52:09', '2021-08-01 10:01:04'),
(16, 69, 'avatar', 'public/images/1_1628327151_435200554.jpg', '1_1628327151_435200554.jpg', 'image/jpeg', 319283, 'jpg', 'users', '2021-08-01 09:55:18', '2021-08-07 09:05:51'),
(45, 16, 'cover', 'public/images/1_1628067294_2049030889.png', '1_1628067294_2049030889.png', 'image/png', 382692, 'png', 'novels', '2021-08-04 08:54:54', '2021-08-04 08:54:54'),
(46, 16, 'screenshot', 'public/images/1_1628067294_1710122939.png', '1_1628067294_1710122939.png', 'image/png', 606361, 'png', 'novels', '2021-08-04 08:54:54', '2021-08-04 08:54:54'),
(47, 16, 'screenshot', 'public/images/1_1628067294_798351638.png', '1_1628067294_798351638.png', 'image/png', 533784, 'png', 'novels', '2021-08-04 08:54:54', '2021-08-04 08:54:54'),
(48, 16, 'screenshot', 'public/images/1_1628067294_943459641.png', '1_1628067294_943459641.png', 'image/png', 418514, 'png', 'novels', '2021-08-04 08:54:54', '2021-08-04 08:54:54'),
(53, 18, 'cover', 'public/images/1_1628155756_1844120166.png', '1_1628155756_1844120166.png', 'image/png', 354676, 'png', 'novels', '2021-08-05 09:29:16', '2021-08-05 09:29:16'),
(54, 18, 'screenshot', 'public/images/1_1628155756_944111367.jpg', '1_1628155756_944111367.jpg', 'image/jpeg', 307038, 'jpg', 'novels', '2021-08-05 09:29:16', '2021-08-05 09:29:16'),
(55, 18, 'screenshot', 'public/images/1_1628155757_1289909292.jpg', '1_1628155757_1289909292.jpg', 'image/jpeg', 508318, 'jpg', 'novels', '2021-08-05 09:29:17', '2021-08-05 09:29:17'),
(56, 18, 'screenshot', 'public/images/1_1628155757_290324343.jpg', '1_1628155757_290324343.jpg', 'image/jpeg', 303339, 'jpg', 'novels', '2021-08-05 09:29:17', '2021-08-05 09:29:17'),
(57, 19, 'cover', 'public/images/1_1628327053_1095329915.png', '1_1628327053_1095329915.png', 'image/png', 334835, 'png', 'novels', '2021-08-07 09:04:13', '2021-08-07 09:04:13'),
(58, 19, 'screenshot', 'public/images/1_1628327053_1190562085.jpg', '1_1628327053_1190562085.jpg', 'image/jpeg', 123290, 'jpg', 'novels', '2021-08-07 09:04:13', '2021-08-07 09:04:13'),
(59, 19, 'screenshot', 'public/images/1_1628327053_1611501010.jpg', '1_1628327053_1611501010.jpg', 'image/jpeg', 110147, 'jpg', 'novels', '2021-08-07 09:04:13', '2021-08-07 09:04:13'),
(60, 19, 'screenshot', 'public/images/1_1628327054_136476606.jpg', '1_1628327054_136476606.jpg', 'image/jpeg', 31917, 'jpg', 'novels', '2021-08-07 09:04:14', '2021-08-07 09:04:14');

-- --------------------------------------------------------

--
-- Структура таблицы `novels`
--

CREATE TABLE `novels` (
  `novel_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternative_title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year_release` int(4) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `developer` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `platforms` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age_rating` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `state` int(1) NOT NULL DEFAULT 0,
  `delete_marker` int(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `novels`
--

INSERT INTO `novels` (`novel_id`, `user_id`, `title`, `original_title`, `alternative_title`, `year_release`, `description`, `developer`, `type`, `duration`, `platforms`, `age_rating`, `country`, `language`, `views`, `state`, `delete_marker`, `created_at`, `updated_at`) VALUES
(16, 67, 'Судьба/Ночь Схватки', 'Fate/Stay Night', '', 2004, 'Ученик старшей школы, Эмия Широ, становится невольным участником так называемой «войны Святого Грааля», время от времени проходящей в японском городе Фуюки. Это борьба семерых магов за обладание легендарным артефактом — Святым Граалем, который исполнит любое желание победителя и, таким образом, изменит его судьбу. Каждый маг с момента своего начала участия в войне призывает слугу — одну из великих героических душ прошлого или будущего - сражающегося на стороне своего хозяина. Есть семь слуг, все они принадлежат к разным боевым классам: мечник (Сэйбер), лучник (Арчер), копейщик (Лансер), всадник (Райдер), берсеркер, убийца (Ассасин) и маг (Кастер). Битва ведется до тех пор, пока не останется одна пара мастера и слуги. Эмия Широ против собственной воли призывает мечницу — слугу, поставившую себе цель добраться до Грааля любым путём. Однако, он не желает участвовать в битве, так как в прошлой войне потерял всех своих родных. Эмии начинает помогать Тосака Рин — сильный маг, которая учится в той же школе, что и Широ. Но правила войны Святого Грааля указывают на то, что рано или поздно им предстоит сразиться между собой, а наиболее возможный исход для проигравшего — смерть.', 'Type-Moon', 'Новелла с выборами', 'Более 50 часов', 'Windows,Android', 'NC-17', 'Япония', 'Русский', 0, 1, 0, '2021-08-04 08:54:53', '2021-08-07 09:48:49'),
(18, 67, 'Ever 17 : Побег из Бесконечности', 'Ever 17 : Out of the Infinity', '', 2002, 'В подводном парке развлечений LeMU происходит катастрофа, в результате чего верхняя и частично нижние части комплекса оказываются затоплены. Посетителей эвакуируют, но несколько человек остаётся взаперти. Они ждут помощи, одновременно пытаясь спастись от затопления и разобраться в череде странностей, окружающих сам подводный комплекс и его разрушение. Им предстоит узнать многое друг о друге, о LeMU и о собственном прошлом.', 'KID', 'Новелла с выборами', '30-50 часов', 'Windows,Android', 'PG-13', 'Япония', 'Русский', 0, 1, 0, '2021-08-05 09:29:16', '2021-08-07 09:43:43'),
(19, 69, 'Когда плачут цикады: Ответы', 'Higurashi no Naku Koro ni: Kai', '', 2004, 'Действие происходит вокруг праздника Ватанагаси, где уже 4 года подряд на каждый праздник находят одного жестоко убитого человека, а второй пропадает безвести.\r\n\r\n1983 год, в деревню Хинамидзава переезжает Кэйити Маэбара. Он быстро входит в дружный коллектив школьного клуба. Но, внезапно, ему начинают открываться загадки, которыми насыщена не только сама деревня, но и, почти, каждый житель.', '07th Expansion', 'Новелла с выборами', 'Более 50 часов', 'Windows,Android', 'PG-13', 'Япония', 'Русский', 0, 1, 0, '2021-08-07 09:04:13', '2021-08-07 09:48:55');

-- --------------------------------------------------------

--
-- Структура таблицы `novels-genres`
--

CREATE TABLE `novels-genres` (
  `relation_id` int(11) NOT NULL,
  `novel_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `novels-genres`
--

INSERT INTO `novels-genres` (`relation_id`, `novel_id`, `genre_id`) VALUES
(16, 16, 6),
(17, 16, 7),
(18, 16, 9),
(19, 16, 22),
(20, 16, 14),
(21, 16, 15),
(22, 16, 16),
(23, 16, 21),
(28, 18, 6),
(29, 18, 8),
(30, 18, 9),
(31, 18, 22),
(32, 19, 6),
(33, 19, 7),
(34, 19, 10),
(35, 19, 12),
(36, 19, 14),
(37, 19, 15);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `login` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `online` int(1) NOT NULL DEFAULT 0,
  `state` int(1) NOT NULL DEFAULT 0,
  `delete_marker` int(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `login`, `password`, `remember_token`, `role`, `online`, `state`, `delete_marker`, `created_at`, `updated_at`) VALUES
(67, 'Администратор', '1@1', 'admin', '$1$qWItL5D2$c2OwL5StELkBT8MOlhMr90', 'tJCHPlXtvfDwzTw0aac2hFfbb5NkZPb6xgVYEDPHQCj4ngNVQJ', 'admin', 0, 0, 0, '2021-08-01 09:50:20', '2021-08-07 11:00:23'),
(68, 'Модератор', '2@2', 'moderator', '$1$1shDIjl3$BX535V7RAPBMfJbs2z5zz0', 'wBmeRWq8NaVaBNfToiM69MN6Xy5n8y78Pmihe1pyG06nIrmmx7', 'moderator', 0, 0, 0, '2021-08-01 09:52:09', '2021-08-06 13:58:55'),
(69, 'Пользователь', '3@3', 'user', '$1$JZ5rA3sY$IM0GveqVVve8GcvziHEty/', 'XIFGrcmgfi9pAUWlmddOdswtqABhQNJuaWnHoMULDgKtW7yIkK', 'user', 0, 0, 0, '2021-08-01 09:55:18', '2021-08-07 09:01:31');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`bookmark_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `novel_id` (`novel_id`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `novel_id` (`novel_id`);

--
-- Индексы таблицы `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genre_id`);

--
-- Индексы таблицы `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `foreign_id` (`foreign_id`);

--
-- Индексы таблицы `novels`
--
ALTER TABLE `novels`
  ADD PRIMARY KEY (`novel_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `novels-genres`
--
ALTER TABLE `novels-genres`
  ADD PRIMARY KEY (`relation_id`),
  ADD KEY `novel_id` (`novel_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `bookmark_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблицы `genres`
--
ALTER TABLE `genres`
  MODIFY `genre_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `images`
--
ALTER TABLE `images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT для таблицы `novels`
--
ALTER TABLE `novels`
  MODIFY `novel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `novels-genres`
--
ALTER TABLE `novels-genres`
  MODIFY `relation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_ibfk_1` FOREIGN KEY (`novel_id`) REFERENCES `novels` (`novel_id`),
  ADD CONSTRAINT `bookmarks_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Ограничения внешнего ключа таблицы `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`novel_id`) REFERENCES `novels` (`novel_id`);

--
-- Ограничения внешнего ключа таблицы `novels`
--
ALTER TABLE `novels`
  ADD CONSTRAINT `novels_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Ограничения внешнего ключа таблицы `novels-genres`
--
ALTER TABLE `novels-genres`
  ADD CONSTRAINT `novels-genres_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`genre_id`),
  ADD CONSTRAINT `novels-genres_ibfk_2` FOREIGN KEY (`novel_id`) REFERENCES `novels` (`novel_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
