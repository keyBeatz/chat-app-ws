-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Počítač: localhost
-- Vytvořeno: Stř 29. lis 2017, 18:52
-- Verze serveru: 5.7.20
-- Verze PHP: 7.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `chat_app`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `conversation`
--

CREATE TABLE `conversation` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `conversation`
--

INSERT INTO `conversation` (`id`, `date_created`, `date_updated`) VALUES
(3, '2017-11-29 15:19:45', '2017-11-29 15:19:45'),
(4, '2017-11-29 15:29:12', '2017-11-29 15:29:12'),
(5, '2017-11-29 15:29:17', '2017-11-29 15:29:17'),
(6, '2017-11-29 15:29:19', '2017-11-29 15:29:19'),
(7, '2017-11-29 15:29:20', '2017-11-29 15:29:20'),
(8, '2017-11-29 15:29:22', '2017-11-29 15:29:22');

-- --------------------------------------------------------

--
-- Struktura tabulky `conversation_member`
--

CREATE TABLE `conversation_member` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `unread` tinyint(4) DEFAULT '1',
  `deleted` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `conversation_member`
--

INSERT INTO `conversation_member` (`id`, `user_id`, `conversation_id`, `status`, `unread`, `deleted`) VALUES
(5, 7, 3, 'active', 1, 0),
(6, 8, 3, 'active', 1, 0),
(9, 7, 5, 'active', 1, 0),
(10, 10, 5, 'active', 1, 0),
(11, 7, 6, 'active', 1, 0),
(12, 11, 6, 'active', 1, 0),
(13, 7, 7, 'active', 1, 0),
(14, 13, 7, 'active', 1, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `message`
--

CREATE TABLE `message` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `date_sent` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_edited` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `message`
--

INSERT INTO `message` (`id`, `conversation_id`, `user_id`, `text`, `date_sent`, `date_edited`) VALUES
(9, 3, 7, 'Ahoj', '2017-11-29 15:57:56', '2017-11-29 15:57:56'),
(10, 5, 7, 'asdasd', '2017-11-29 15:57:56', '2017-11-29 15:57:56'),
(11, 6, 7, 'asddsa', '2017-11-29 15:57:56', '2017-11-29 15:57:56'),
(12, 7, 7, 'hdgfhf', '2017-11-29 15:57:56', '2017-11-29 15:57:56'),
(13, 7, 13, 'ahoj ahoj ahoj', '2017-11-29 15:57:56', '2017-11-29 15:57:56'),
(14, 7, 13, 'ahoj ahoj ahoj 2', '2017-11-29 15:57:56', '2017-11-29 15:57:56'),
(15, 7, 13, 'funguje to?', '2017-11-29 15:57:56', '2017-11-29 15:57:56'),
(16, 7, 7, 'fghfgh', '2017-11-29 19:07:49', '2017-11-29 19:07:49'),
(17, 7, 7, 'ahoj', '2017-11-29 19:15:11', '2017-11-29 19:15:11'),
(18, 7, 7, 'testing', '2017-11-29 19:15:26', '2017-11-29 19:15:26'),
(19, 7, 7, 'fsdf', '2017-11-29 19:24:00', '2017-11-29 19:24:00'),
(20, 7, 7, 'test', '2017-11-29 19:26:27', '2017-11-29 19:26:27'),
(21, 7, 7, 'gdfg', '2017-11-29 19:27:26', '2017-11-29 19:27:26'),
(22, 6, 7, 'fsdfsf', '2017-11-29 19:27:37', '2017-11-29 19:27:37'),
(23, 3, 8, 'zdarec :DD', '2017-11-29 19:30:10', '2017-11-29 19:30:10'),
(24, 3, 7, 'jak to de', '2017-11-29 19:31:08', '2017-11-29 19:31:08'),
(25, 3, 8, 'supr supr', '2017-11-29 19:31:14', '2017-11-29 19:31:14'),
(26, 7, 7, 'ahoj', '2017-11-29 19:42:01', '2017-11-29 19:42:01'),
(27, 7, 7, 'fds', '2017-11-29 19:44:09', '2017-11-29 19:44:09'),
(28, 7, 7, 'Lorem ipsum dolor sit amet', '2017-11-29 19:46:16', '2017-11-29 19:46:16'),
(29, 3, 8, 'čus', '2017-11-29 19:46:23', '2017-11-29 19:46:23'),
(30, 3, 7, 'funguje (y)', '2017-11-29 19:46:28', '2017-11-29 19:46:28');

-- --------------------------------------------------------

--
-- Struktura tabulky `user`
--

CREATE TABLE `user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `login` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `user`
--

INSERT INTO `user` (`id`, `login`) VALUES
(7, 'user1'),
(8, 'user2'),
(9, 'user3'),
(10, 'user4'),
(11, 'user5'),
(12, 'user6'),
(13, 'user7');

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `conversation`
--
ALTER TABLE `conversation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_date_created` (`date_created`),
  ADD KEY `conversation_date_updated` (`date_updated`);

--
-- Klíče pro tabulku `conversation_member`
--
ALTER TABLE `conversation_member`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_conversation_member_conversation1_idx` (`conversation_id`),
  ADD KEY `fk_conversation_member_user1_idx` (`user_id`);

--
-- Klíče pro tabulku `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_message_conversation1_idx` (`conversation_id`),
  ADD KEY `message_date_send` (`date_sent`),
  ADD KEY `message_date_edited` (`date_edited`),
  ADD KEY `fk_message_user1_idx` (`user_id`);

--
-- Klíče pro tabulku `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login_UNIQUE` (`login`),
  ADD KEY `user_login` (`login`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `conversation`
--
ALTER TABLE `conversation`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pro tabulku `conversation_member`
--
ALTER TABLE `conversation_member`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pro tabulku `message`
--
ALTER TABLE `message`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pro tabulku `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `conversation_member`
--
ALTER TABLE `conversation_member`
  ADD CONSTRAINT `fk_conversation_member_conversation1` FOREIGN KEY (`conversation_id`) REFERENCES `conversation` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_conversation_member_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `fk_message_conversation1` FOREIGN KEY (`conversation_id`) REFERENCES `conversation` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_message_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
