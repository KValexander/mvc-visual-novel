-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 30 2021 г., 10:35
-- Версия сервера: 10.3.29-MariaDB
-- Версия PHP: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `vn_user`
--
CREATE DATABASE IF NOT EXISTS `vn_user` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `vn_user`;

-- --------------------------------------------------------

--
-- Структура таблицы `bookmarks`
--

DROP TABLE IF EXISTS `bookmarks`;
CREATE TABLE `bookmarks` (
  `bookmark_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `novel_id` int(11) NOT NULL,
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `novel_id` int(11) NOT NULL,
  `content` varchar(5000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `discussions`
--

DROP TABLE IF EXISTS `discussions`;
CREATE TABLE `discussions` (
  `discussion_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `novel_id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` varchar(5000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `friends`
--

DROP TABLE IF EXISTS `friends`;
CREATE TABLE `friends` (
  `friend_id` int(11) NOT NULL,
  `user_one` int(11) NOT NULL,
  `user_two` int(11) NOT NULL,
  `state` int(1) NOT NULL DEFAULT 2,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `grades`
--

DROP TABLE IF EXISTS `grades`;
CREATE TABLE `grades` (
  `grade_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `grade` int(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `user_one` int(11) NOT NULL,
  `user_two` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` varchar(5000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `received` int(1) NOT NULL DEFAULT 0,
  `state` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `news_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` varchar(5000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` int(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `ratings`
--

DROP TABLE IF EXISTS `ratings`;
CREATE TABLE `ratings` (
  `rating_id` int(11) NOT NULL,
  `novel_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `replies`
--

DROP TABLE IF EXISTS `replies`;
CREATE TABLE `replies` (
  `reply_id` int(11) NOT NULL,
  `discussion_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` varchar(5000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `login` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` int(1) NOT NULL DEFAULT 3,
  `avatar` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `online` int(1) NOT NULL DEFAULT 0,
  `state` int(1) NOT NULL DEFAULT 2,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Индексы таблицы `discussions`
--
ALTER TABLE `discussions`
  ADD PRIMARY KEY (`discussion_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `novel_id` (`novel_id`);

--
-- Индексы таблицы `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`friend_id`),
  ADD KEY `user_one` (`user_one`),
  ADD KEY `user_two` (`user_two`);

--
-- Индексы таблицы `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`grade_id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `user_one` (`user_one`),
  ADD KEY `user_two` (`user_two`);

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`news_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `novel_id` (`novel_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `discussion_id` (`discussion_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `role_id` (`role_id`);

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
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `discussions`
--
ALTER TABLE `discussions`
  MODIFY `discussion_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `friends`
--
ALTER TABLE `friends`
  MODIFY `friend_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `grades`
--
ALTER TABLE `grades`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `replies`
--
ALTER TABLE `replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
