-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Gép: localhost:3306
-- Létrehozás ideje: 2025. Dec 02. 10:54
-- Kiszolgáló verziója: 10.11.14-MariaDB-cll-lve
-- PHP verzió: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `villamme_villammelo`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `admin_tevekenyseg`
--

CREATE TABLE `admin_tevekenyseg` (
  `atid` int(11) NOT NULL,
  `admin_fid` int(11) NOT NULL,
  `cel_fid` int(11) DEFAULT NULL,
  `tevekenyseg` varchar(255) NOT NULL,
  `reszletek` text DEFAULT NULL,
  `idopont` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `admin_tevekenyseg`
--

INSERT INTO `admin_tevekenyseg` (`atid`, `admin_fid`, `cel_fid`, `tevekenyseg`, `reszletek`, `idopont`) VALUES
(1, 1, NULL, 'Felhasználó státusz módosítása: Kitiltott', 'Próbából', '2025-11-24 15:39:13'),
(2, 1, 4, 'Admin jog megadása', '', '2025-11-24 15:51:45'),
(3, 1, NULL, 'Felhasználó státusz módosítása: Aktiv', 'Ismét próba', '2025-11-24 15:52:34'),
(4, 1, NULL, 'Felhasználó státusz módosítása: Kitiltott', '', '2025-11-24 16:04:04'),
(5, 1, NULL, 'Felhasználó státusz módosítása: Aktiv', '', '2025-11-24 16:08:58'),
(6, 1, NULL, 'Tevékenység törlése', 'Tábla: email_ell, ID: 134', '2025-11-24 16:50:16'),
(7, 1, 4, 'Admin jog elvétele', '', '2025-11-24 17:07:51'),
(8, 1, NULL, 'Admin jog megadása', '', '2025-11-24 17:09:05'),
(9, 1, NULL, 'Admin jog elvétele', '', '2025-11-24 17:10:44'),
(10, 1, 1, 'Felhasználó adatainak módosítása', 'Mezők: fnev, email, knev, vnev, nem, szuletett, telefon, varos, reszletek', '2025-11-24 18:11:18'),
(11, 1, NULL, 'Felhasználó státusz módosítása: Fuggoben', '', '2025-11-24 19:23:40'),
(12, 1, NULL, 'Felhasználó státusz módosítása: Aktiv', '', '2025-11-24 19:33:02'),
(13, 1, NULL, 'Felhasználó státusz módosítása: Fuggoben', ' (Email megerősítés visszaállítva)', '2025-11-24 19:33:10'),
(14, 1, 127, 'Felhasználó törlése', 'Felhasználó: Hanzo (h4nzoart@gmail.com)', '2025-11-24 21:02:23'),
(15, 1, 128, 'Felhasználó státusz módosítása: Fuggoben', ' (Email megerősítés visszaállítva)', '2025-11-24 21:12:37'),
(16, 1, 128, 'Felhasználó státusz módosítása: Fuggoben', ' (Email megerősítés visszaállítva)', '2025-11-24 21:28:45'),
(17, 1, 128, 'Felhasználó státusz módosítása: Aktiv', '', '2025-11-24 21:32:59'),
(18, 1, 128, 'Felhasználó státusz módosítása: Fuggoben', ' (Email megerősítés visszaállítva)', '2025-11-25 14:08:20'),
(19, 1, 128, 'Felhasználó státusz módosítása: Fuggoben', ' (Email megerősítés visszaállítva)', '2025-11-25 14:13:14'),
(20, 1, 8, 'Felhasználó státusz módosítása: Fuggoben', ' (Email megerősítés visszaállítva)', '2025-11-25 14:15:28'),
(21, 1, 9, 'Felhasználó státusz módosítása: Fuggoben', ' (Email megerősítés visszaállítva)', '2025-11-25 14:15:34'),
(22, 1, 125, 'Felhasználó státusz módosítása: Fuggoben', ' (Email megerősítés visszaállítva)', '2025-11-25 14:15:46'),
(23, 1, 128, 'Felhasználó státusz módosítása: Kitiltott', 'Próbából kidobtam', '2025-11-25 15:12:08'),
(24, 1, 128, 'Felhasználó státusz módosítása: Aktiv', '', '2025-11-25 15:18:46'),
(25, 1, NULL, 'Tevékenység törlése', 'Tábla: email_ell, ID: 143', '2025-11-25 16:13:52'),
(26, 1, 128, 'Felhasználó státusz módosítása: Fuggoben', ' (Email megerősítés visszaállítva)', '2025-11-25 16:29:02'),
(27, 1, 128, 'Felhasználó státusz módosítása: Aktiv', '', '2025-11-25 16:29:20'),
(28, 1, 128, 'Felhasználó státusz módosítása: Fuggoben', ' (Email megerősítés visszaállítva)', '2025-11-25 16:29:23'),
(29, 1, 128, 'Felhasználó státusz módosítása: Kitiltott', '', '2025-11-25 16:29:40'),
(30, 1, 128, 'Felhasználó státusz módosítása: Aktiv', '', '2025-11-25 16:29:47'),
(31, 1, 128, 'Felhasználó adatainak módosítása', 'Mezők: fnev, email, knev, vnev, nem, szuletett, telefon, varos, reszletek', '2025-11-25 17:01:40'),
(32, 1, 128, 'Felhasználó adatainak módosítása', 'Módosított mezők:\n- telefon: \'\' → \'06307367201\'\n- varos: \'\' → \'Budapest\'\n', '2025-11-25 17:08:37'),
(33, 1, 128, 'Felhasználó adatainak módosítása', 'Módosított mezők:\n- telefon: \'06307367201\' → \'06307367200\'\n', '2025-11-25 17:08:54'),
(34, 1, NULL, 'Tevékenység törlése', 'Tábla: felhasznalo_tevekenyseg, ID: 639', '2025-11-25 17:14:38'),
(35, 1, NULL, 'Tevékenység törlése', 'Tábla: email_ell, ID: 132', '2025-11-25 17:14:52'),
(36, 1, NULL, 'Tevékenység törlése', 'Tábla: jelszo_visszaallitasok, ID: 4', '2025-11-25 17:14:57'),
(37, 1, NULL, 'Tevékenység törlése', 'Tábla: felhasznalo_tevekenyseg, ID: 638', '2025-11-25 17:15:10'),
(38, 1, 7, 'Admin jog megadása', '', '2025-11-25 17:17:32'),
(39, 1, 4, 'Admin jog megadása', '', '2025-11-25 17:17:36'),
(40, 1, 128, 'Felhasználó státusz módosítása: Kitiltott', 'Teszt', '2025-11-25 17:45:21'),
(41, 1, 128, 'Felhasználó státusz módosítása: Aktiv', '', '2025-11-25 17:46:36'),
(42, 1, 128, 'Felhasználó státusz módosítása: Kitiltott', 'Tesztből kitiltottam magam 1 napra', '2025-11-25 17:52:19'),
(43, 1, 4, 'Felhasználó státusz módosítása: Aktiv', 'Mert nem csinálsz semmit az oldalon!', '2025-11-25 18:19:40'),
(44, 1, 4, 'Felhasználó státusz módosítása: Aktiv', 'Mert nem csinálsz semmit az oldalon!', '2025-11-25 18:20:33'),
(45, 1, 4, 'Admin jog elvétele', '', '2025-11-25 18:20:52'),
(46, 1, 4, 'Felhasználó státusz módosítása: Kitiltott', 'Mert nem csinálsz semmit az oldalon!', '2025-11-25 18:21:07'),
(47, 1, NULL, 'Tevékenység törlése', 'Tábla: felhasznalo_tevekenyseg, ID: 643', '2025-11-25 18:54:54'),
(48, 1, 7, 'Admin jog elvétele', '', '2025-11-25 19:13:16'),
(49, 1, 7, 'Felhasználó státusz módosítása: Kitiltott', 'Kérkedek a WebAdminnal :P', '2025-11-25 19:14:02'),
(50, 1, 128, 'Felhasználó státusz módosítása: Aktiv', '', '2025-11-28 10:02:04'),
(51, 1, 7, 'Felhasználó státusz módosítása: Aktiv', '', '2025-11-28 10:02:13'),
(52, 1, 128, 'Felhasználó státusz módosítása: Kitiltott', '', '2025-11-28 11:35:01'),
(53, 1, 128, 'Felhasználó státusz módosítása: Kitiltott', '', '2025-11-28 11:43:34'),
(54, 1, 128, 'Felhasználó státusz módosítása: Kitiltott', '', '2025-11-28 11:47:48'),
(55, 1, 7, 'Felhasználó státusz módosítása: Kitiltott', '', '2025-11-28 11:50:01'),
(56, 1, 128, 'Felhasználó státusz módosítása: Aktiv', '', '2025-11-28 11:58:03'),
(57, 0, NULL, 'Kitiltás automatikus lejárta', 'Felhasználó státusza automatikusan visszaállítva aktívra', '2025-11-28 12:00:34'),
(58, 0, NULL, 'Kitiltás automatikus lejárta', 'Felhasználó státusza automatikusan visszaállítva aktívra', '2025-11-28 12:00:35'),
(59, 1, 128, 'Felhasználó státusz módosítása: Kitiltott', '', '2025-11-28 12:00:35'),
(60, 0, NULL, 'Kitiltás automatikus lejárta', 'Felhasználó státusza automatikusan visszaállítva aktívra', '2025-11-28 12:00:35'),
(61, 0, NULL, 'Kitiltás automatikus lejárta', 'Felhasználó státusza automatikusan visszaállítva aktívra', '2025-11-28 12:00:36'),
(62, 0, NULL, 'Kitiltás automatikus lejárta', 'Felhasználó státusza automatikusan visszaállítva aktívra', '2025-11-28 12:00:37'),
(63, 0, NULL, 'Kitiltás automatikus lejárta', 'Felhasználó státusza automatikusan visszaállítva aktívra', '2025-11-28 12:00:39'),
(64, 0, NULL, 'Kitiltás automatikus lejárta', 'Felhasználó státusza automatikusan visszaállítva aktívra', '2025-11-28 12:00:40'),
(65, 0, NULL, 'Kitiltás automatikus lejárta', 'Felhasználó státusza automatikusan visszaállítva aktívra', '2025-11-28 12:00:50'),
(66, 0, NULL, 'Kitiltás automatikus lejárta', 'Felhasználó státusza automatikusan visszaállítva aktívra', '2025-11-28 12:00:51'),
(67, 0, NULL, 'Kitiltás automatikus lejárta', 'Felhasználó státusza automatikusan visszaállítva aktívra', '2025-11-28 12:00:57'),
(68, 1, 128, 'Felhasználó státusz módosítása: Kitiltott', '', '2025-11-28 12:03:47'),
(69, 1, 128, 'Felhasználó státusz módosítása: Kitiltott', '', '2025-11-28 12:05:24'),
(70, 0, NULL, 'Kitiltások automatikus visszaállítása', '1 lejárt kitiltás automatikusan visszaállítva', '2025-11-28 12:06:05'),
(71, 1, 128, 'Felhasználó státusz módosítása: Kitiltott', '', '2025-11-28 12:11:40'),
(72, 1, 128, 'Felhasználó státusz módosítása: Kitiltott', '', '2025-11-28 12:15:08'),
(73, 0, NULL, 'Kitiltások automatikus visszaállítása', '1 lejárt kitiltás automatikusan visszaállítva: Hanzo (h4nzoart@gmail.com)', '2025-11-28 12:16:08'),
(74, 1, 128, 'Felhasználó státusz módosítása: Kitiltott', '', '2025-11-28 12:17:19'),
(75, 1, 128, 'Felhasználó státusz módosítása: Kitiltott', '', '2025-11-28 12:20:44'),
(76, 0, NULL, 'Kitiltások automatikus visszaállítása', '1 lejárt kitiltás automatikusan visszaállítva: Hanzo (h4nzoart@gmail.com)', '2025-11-28 12:21:01'),
(77, 1, 128, 'Felhasználó státusz módosítása: Kitiltott', '', '2025-11-28 12:24:57'),
(78, 1, 7, 'Felhasználó státusz módosítása: Kitiltott', '', '2025-12-01 09:46:53'),
(79, 1, 7, 'Felhasználó státusz módosítása: Kitiltott', 'Csak úgy', '2025-12-01 09:58:00'),
(80, 1, 7, 'Felhasználó státusz módosítása: Aktiv', 'szívesen', '2025-12-01 09:58:53'),
(81, 1, 7, 'Admin jog megadása', '', '2025-12-01 09:59:49'),
(82, 1, 1, 'Felhasználó adatainak módosítása', 'Módosított mezők:\n- nem: \'ferfi\' → \'no\'\n- reszletek: \'\' → \'Buzi vagyok\'\n', '2025-12-01 10:02:44'),
(83, 1, 1, 'Admin jog elvétele', '', '2025-12-01 10:03:08'),
(84, 1, 4, 'Felhasználó státusz módosítása: Aktiv', '', '2025-12-01 10:10:10'),
(85, 1, 4, 'Admin jog megadása', '', '2025-12-01 10:11:26');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `munka_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `torolve` tinyint(1) DEFAULT 0,
  `torles_datuma` timestamp NULL DEFAULT NULL,
  `torolte_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci;

--
-- A tábla adatainak kiíratása `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `munka_id`, `comment`, `rating`, `created_at`, `torolve`, `torles_datuma`, `torolte_user_id`) VALUES
(1, 7, 1, 'Teszt', 3, '2025-11-24 07:51:06', 1, '2025-11-24 08:00:40', NULL),
(2, 1, 1, 'Aha', 5, '2025-11-24 07:52:23', 1, '2025-11-24 08:12:30', NULL),
(3, 7, 1, 'Szia én', 3, '2025-11-24 08:01:45', 1, '2025-11-24 08:03:55', NULL),
(4, 7, 1, 'Teló teszt', 5, '2025-11-24 08:06:52', 1, '2025-11-24 08:09:58', NULL),
(5, 7, 1, 'az baj', 3, '2025-11-24 08:10:19', 1, '2025-11-24 08:10:35', NULL),
(6, 7, 1, 'Dani meleg', 5, '2025-11-24 08:13:00', 1, '2025-11-24 08:17:07', NULL),
(7, 7, 1, 'komment teszt #5', 4, '2025-11-24 08:17:38', 1, '2025-11-24 08:22:08', NULL),
(8, 7, 1, 'komment teszt #7', 4, '2025-11-24 08:22:23', 1, '2025-11-24 08:25:55', NULL),
(9, 7, 1, 'komment teszt #8', 4, '2025-11-24 08:26:05', 1, '2025-11-24 08:26:15', NULL),
(10, 7, 1, 'komment', 4, '2025-11-24 08:31:41', 1, '2025-11-24 08:35:33', NULL),
(11, 7, 1, 'komment teszt #10', 3, '2025-11-24 08:35:44', 1, '2025-11-24 12:29:08', NULL),
(12, 7, 1, 'Teó', 4, '2025-11-24 12:29:17', 1, '2025-11-24 12:29:27', NULL),
(13, 7, 1, '', 5, '2025-11-24 17:02:59', 1, '2025-11-24 17:03:07', NULL),
(14, 7, 1, 'komment teszt #12', 4, '2025-11-25 09:00:24', 1, '2025-11-25 09:01:44', NULL),
(15, 1, 1, 'Teszt, kíváncsi vagyok hogy csináltál-e valamit.', 5, '2025-11-25 15:34:03', 1, '2025-11-25 15:38:44', NULL),
(16, 1, 1, 'Jó szar', 5, '2025-12-01 11:21:23', 1, '2025-12-02 09:20:37', 7),
(17, 7, 1, '', 3, '2025-12-01 10:01:21', 1, '2025-12-01 10:01:24', NULL),
(18, 7, 1, '', 3, '2025-12-01 10:49:54', 1, '2025-12-01 10:50:11', NULL),
(19, 7, 1, 'Aha', 2, '2025-12-01 10:53:27', 1, '2025-12-01 10:53:48', NULL),
(20, 7, 1, '', 4, '2025-12-01 10:53:52', 1, '2025-12-01 10:54:10', NULL),
(21, 7, 1, 'Nem jött el', 1, '2025-12-01 10:54:31', 1, '2025-12-01 11:03:34', NULL),
(22, 124, 1, 'szar volt. meg ette a növényeimet', 1, '2025-12-01 11:55:31', 1, '2025-12-01 11:55:37', NULL),
(23, 124, 1, 'kihalt az összes virágom.', 1, '2025-12-01 11:55:59', 1, '2025-12-02 09:19:41', 7),
(24, 1, 1, 'Jó szar', 5, '2025-12-02 09:20:34', 1, '2025-12-02 09:20:39', 7),
(25, 1, 1, 'Jó szar', 5, '2025-12-02 09:20:46', 0, NULL, NULL),
(26, 128, 1, 'haha', 5, '2025-12-02 09:22:04', 0, NULL, NULL),
(27, 129, 1, 'szar', 2, '2025-12-02 09:27:03', 1, '2025-12-02 09:27:26', NULL),
(28, 129, 1, 'Nagyon szar', 1, '2025-12-02 09:48:27', 1, '2025-12-02 09:49:54', 7),
(29, 129, 1, 'Értékelem', 3, '2025-12-02 09:50:49', 1, '2025-12-02 09:50:55', 129),
(30, 129, 1, 'Most fogom kipróbálni', 3, '2025-12-02 09:51:11', 1, '2025-12-02 09:51:21', 129),
(31, 129, 1, 'Ajánlom', 4, '2025-12-02 09:51:21', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `email_ell`
--

CREATE TABLE `email_ell` (
  `emid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `lejarati_ido` timestamp NOT NULL,
  `ellenorzve` timestamp NULL DEFAULT NULL,
  `idopont` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci;

--
-- A tábla adatainak kiíratása `email_ell`
--

INSERT INTO `email_ell` (`emid`, `fid`, `token`, `lejarati_ido`, `ellenorzve`, `idopont`) VALUES
(3, 3, '904d8d940e27a8e4ed67f6b8caf427c85b2674bf820823fcfff426f18a1ca181', '2025-10-23 11:45:05', '2025-10-22 11:45:56', '2025-10-22 11:45:05'),
(7, 7, 'fd0eb5e8ff7195f9989bc81157a55b58426d8e86ea7e725da0488f4c72b0096e', '2025-11-04 09:54:33', '2025-11-03 09:54:45', '2025-11-03 09:54:33'),
(8, 8, '2d8fe1937952ac34ddc432b13a021dcac9eb5344a101b01c4ddde552b8da1a78', '2025-11-04 17:33:46', NULL, '2025-11-03 17:33:46'),
(10, 4, '33f98c6c9d20eb4998acf205a1c20036b264d266485e3e124077e569966b1f11', '2025-11-05 16:55:51', '2025-11-04 16:56:01', '2025-11-04 16:55:51'),
(11, 9, 'aa1a4099d4622cdd3cd257701e406c9229c8384f1726e60960d4ac80cfb6d9a6', '2025-11-09 17:39:06', NULL, '2025-11-08 17:39:06'),
(12, 2, 'cad65656dd5c76100eae7ede429ccee8d1dc91c4ed22c136df1f612f8bc07e09', '2025-11-11 12:13:33', '2025-11-10 12:13:47', '2025-11-10 12:13:33'),
(14, 6, 'a7cfe0bb9f090b2fc10069d0c4963a5fe4a1ed96bfbf77018ed76f41a91c7570', '2025-11-11 23:30:30', '2025-11-10 23:30:41', '2025-11-10 23:30:30'),
(15, 11, '4bac7fb4f06c0190d663496424ad3e3ae017dbb8515218afb602161ee7386582', '2025-11-12 09:17:34', NULL, '2025-11-11 09:17:34'),
(16, 12, '3804e12b109e951e4a04aaa6d5c760b4fd216eb149177ade11325074371689bc', '2025-11-12 09:17:36', NULL, '2025-11-11 09:17:36'),
(17, 13, '7fc02aa8c61dba547cbc0e5ef2a239787d4ffb7ee4e5e5678fdb45d0dd9a4483', '2025-11-12 09:17:38', NULL, '2025-11-11 09:17:38'),
(18, 14, 'd3758544859e8bbaeb522426a1a8e8fb4f1c272356645521421cbdcabf5e8888', '2025-11-12 09:17:40', NULL, '2025-11-11 09:17:40'),
(19, 15, 'f47343f8b5493b8b8201e60e592a1970861eeb7debf8ddaf429f68318fdf0e09', '2025-11-12 09:17:42', NULL, '2025-11-11 09:17:42'),
(20, 16, 'cf4ea9f6897a72cb0407de95d4e5af2750f51bd12a8e5cc81efe5f06806632a0', '2025-11-12 09:17:44', NULL, '2025-11-11 09:17:44'),
(21, 17, '1eadd1fca6a3209c92a8783ae1fc2a384a547f14af3878466cc6858b99fd990a', '2025-11-12 09:17:46', NULL, '2025-11-11 09:17:46'),
(22, 18, 'b33219d509290b61396a96fab1d7aa35164c2b055927a1595fac659af98c277c', '2025-11-12 09:17:47', NULL, '2025-11-11 09:17:47'),
(23, 19, 'f30723ff899d2d4f50d6dc014848d42b9117f2c789a3e2877be6ede9bfb91890', '2025-11-12 09:17:49', NULL, '2025-11-11 09:17:49'),
(24, 20, '1bc4aa6f3d55dab74a21fa3ce582500277725211e95d7d78d0cbb9ad4ca1a245', '2025-11-12 09:17:51', NULL, '2025-11-11 09:17:51'),
(25, 21, '2b784cfe906e72704669f44ea415bab738cfbffebbac602f0a12bd1d250f337a', '2025-11-12 09:22:50', NULL, '2025-11-11 09:22:50'),
(26, 22, '37651d10400f79a4cb2f4b2f1bfc96d77c126767c53ceb85911dd8fcb54798be', '2025-11-12 09:22:52', NULL, '2025-11-11 09:22:52'),
(27, 23, '04c8e3f78d9cb4de28debd6528a18951b8dcce891b466056fe7acc361c620c94', '2025-11-12 09:22:54', NULL, '2025-11-11 09:22:54'),
(28, 24, '4d9cbe0343e700c5fab3df9084ebb708e4df42bff42f68b723c5488e6a44e378', '2025-11-12 09:22:56', NULL, '2025-11-11 09:22:56'),
(29, 25, '2a466f6fc46ec56819cfa8ed29ebb5efa56b8a5ca24f27fed7878c29b09e1c88', '2025-11-12 09:22:58', NULL, '2025-11-11 09:22:58'),
(30, 26, 'cec0037b029d392b2c4c92bace02356e1629585cafa2d2022dc03a6b2386748c', '2025-11-12 09:58:12', NULL, '2025-11-11 09:58:12'),
(31, 27, '040c8def0409433cea4ab3c395ab56abb712021d4a5d7b3efd42612cf35d33af', '2025-11-12 10:00:23', NULL, '2025-11-11 10:00:23'),
(32, 28, 'b06121ee76e4a14ce620b1963ca08d2727870acfeabce6ea02fd4cd56ec053e6', '2025-11-12 10:31:31', NULL, '2025-11-11 10:31:31'),
(33, 29, '0d84c72933573e99c11d5983c86825432f922c28293a8bbdc0d07a25e1953369', '2025-11-12 10:40:10', NULL, '2025-11-11 10:40:10'),
(34, 30, 'fa29b0f233c5f88565d04a58826f6177fad4894115eb3c8881427766d972a595', '2025-11-12 11:57:19', '2025-11-11 11:59:58', '2025-11-11 11:57:19'),
(37, 31, '614d71375e167227ceb0a8ac521ef12dbf8a86ba8d2a1fc72aded5ea92498411', '2025-11-12 12:15:27', '2025-11-11 12:15:39', '2025-11-11 12:15:27'),
(38, 32, 'e230921e0ac28bfb736d40ecba65195a0e7229e6a78f293e2c0997704b302b63', '2025-11-12 12:16:22', '2025-11-11 12:16:52', '2025-11-11 12:16:22'),
(39, 33, 'a4cdc245ab7fd70ed354d0a88d031c4838166877f97bffc60afc3dfafe2ef2e4', '2025-11-12 12:31:54', NULL, '2025-11-11 12:31:54'),
(40, 34, '15e8633459b03586a9a878ed952bdb9be95c302a61760589c74e159e6883d9c0', '2025-11-12 12:33:59', NULL, '2025-11-11 12:33:59'),
(41, 35, 'ccaa712b718429b0a660a59b7589e4c18a21a839463a82f47f4cf767bce776e0', '2025-11-12 12:34:45', NULL, '2025-11-11 12:34:45'),
(42, 36, '6f2c9e57ce75a9f620af694ca43d62c197adbcf02d448ff15a7c4631ce25f927', '2025-11-12 12:38:33', NULL, '2025-11-11 12:38:33'),
(44, 38, 'a5fe38c540be5af67d1b64bb59a2685f268f0c5b6fab4bbd61ec0c0638638141', '2025-11-12 12:41:24', NULL, '2025-11-11 12:41:24'),
(45, 39, 'e85770037e1f065093ebf6d1b45a3950404e4662390bdb179b7efc78b4a85210', '2025-11-12 12:41:26', NULL, '2025-11-11 12:41:26'),
(46, 40, '2517651ca8b17f4bb0bef22589c567311705bab5d4135ac2a866e849e44e02cc', '2025-11-12 12:41:28', NULL, '2025-11-11 12:41:28'),
(47, 41, '7cb805095547fdb9b3aab598ccc52c631994b271371d978affc20ef969a732cb', '2025-11-12 12:41:30', NULL, '2025-11-11 12:41:30'),
(48, 42, 'd5cc47a3dfd3a3fefa3af917c3661332c72be4a4c0a816fa5dbc67d9ded8719c', '2025-11-12 12:41:31', NULL, '2025-11-11 12:41:31'),
(49, 43, '3c903a2a4cb7bb9014157fa4bc7ee94630a58d735293290472978dc8a1abec62', '2025-11-12 12:41:33', NULL, '2025-11-11 12:41:33'),
(50, 44, 'c298c6fbc334dd0a89a83872e15bfca64d4b5c987770746a51936c57173e9082', '2025-11-12 12:41:35', NULL, '2025-11-11 12:41:35'),
(51, 45, '19b16216eda1d7d51bda7941e005cc2a65b424c4155ccb57b9b5b0049654ba2b', '2025-11-12 12:41:37', NULL, '2025-11-11 12:41:37'),
(52, 46, '25313e38ade0d3625fb00ca20a08646770972239b57d5d2063944e2486ab22a7', '2025-11-12 12:41:39', NULL, '2025-11-11 12:41:39'),
(53, 47, '76868a0229c4d78e4d7830038ef0fbb13c2c885ccbd65620abed35645d92c92d', '2025-11-12 12:41:41', NULL, '2025-11-11 12:41:41'),
(54, 48, '5d14f3dd7a8f78fc51960991096282e655b009420695b024fc2f06f29692965d', '2025-11-12 12:41:43', NULL, '2025-11-11 12:41:43'),
(55, 49, 'add6d4bbb5617bb4bc409d6868a23dfe104917f3b2e22b06f7b847983e87c356', '2025-11-12 12:41:44', NULL, '2025-11-11 12:41:44'),
(56, 50, '10ca7bdb034546fe62d30e9720f8a6e2f9c5032f2838822ec72f0237c809615c', '2025-11-12 12:41:46', NULL, '2025-11-11 12:41:46'),
(57, 51, '4d89f6982a33a987f21307cff435bfbed15d9d089b1d6c5b64dac5c30d0801c0', '2025-11-12 12:41:48', NULL, '2025-11-11 12:41:48'),
(58, 52, 'aa167beef4b8076b7f9e9dc151eae4456c615e343e22b711b596872490cbd432', '2025-11-12 12:41:50', NULL, '2025-11-11 12:41:50'),
(59, 53, 'b3abfdf01dce5b6432a0dd1889bf921a1e7d3e7458b816262410a8b9f3568c76', '2025-11-12 12:41:52', NULL, '2025-11-11 12:41:52'),
(60, 54, '535ea2b0ab862044740f6800efcd95f1dd0a78b50683725eeb9bef2d8ba1fec6', '2025-11-12 12:41:54', NULL, '2025-11-11 12:41:54'),
(61, 55, 'f2e175720de6c4a67796fd85fa5f10bd0a7a8d4128c41f291a8721ab69c50f6f', '2025-11-12 12:41:56', NULL, '2025-11-11 12:41:56'),
(62, 56, '5898a42ced840988667de57540946963b10eeeae1a73c5a7c01ec398f1427427', '2025-11-12 12:41:58', NULL, '2025-11-11 12:41:58'),
(63, 57, 'ee0e209b736d041cbd2daf964cfa34b20ff01135c5f47d42d255d82094f0dcf8', '2025-11-12 12:41:59', NULL, '2025-11-11 12:41:59'),
(64, 58, '175029e3cda977f5dac2018527ea28db14e9dca416e623a2a076cf8dfc073bd3', '2025-11-12 12:42:01', NULL, '2025-11-11 12:42:01'),
(65, 59, '0da403b19f8d1825133c530ab772bb577221499dab494f12a9f9ea1d663795d6', '2025-11-12 12:42:03', NULL, '2025-11-11 12:42:03'),
(66, 60, '4e3d47950d38032878cca73516cca82daa36807220fce2ea808b024bd7fa55e1', '2025-11-12 12:42:05', NULL, '2025-11-11 12:42:05'),
(67, 61, '3570939f256c3203f93d4a7e6bc0984a7fd6507a8c9cb58fc84920bc908bee81', '2025-11-12 12:42:07', NULL, '2025-11-11 12:42:07'),
(68, 62, '03374c6a6c791ba7b640743f604a17b5641c1831ee5a4ff3f5ab19bcf526139c', '2025-11-12 12:42:09', NULL, '2025-11-11 12:42:09'),
(69, 63, '3b68a110c3aa21a48ec79317a0def2529d21ada5636ed53182baa4dca7a6679a', '2025-11-12 12:42:11', NULL, '2025-11-11 12:42:11'),
(70, 64, '3997c9e9aebaab91ecfa93d73c6a60ed8fdddbc94e6433c0a54029082e73ca76', '2025-11-12 12:42:13', NULL, '2025-11-11 12:42:13'),
(71, 65, '9c4719c8a98825d6f9136136a0ff237e31fe4949e403da8bf89b1b2d596d14da', '2025-11-12 12:42:14', NULL, '2025-11-11 12:42:14'),
(72, 66, '11b92dd3248d97434761a1e5905d3e3d044db8978d286d815d0f2b96ea98ebc6', '2025-11-12 12:42:16', NULL, '2025-11-11 12:42:16'),
(73, 67, 'f82c0504047279302cfbe3a0a23680d8a603c51d36036a548ecdb1ec1bb01ec1', '2025-11-12 12:42:18', NULL, '2025-11-11 12:42:18'),
(74, 68, '0ff80cd3e39111e1d356d5ae5d2143100c2da3fc7dde32963e0cb31166a59e67', '2025-11-12 12:42:20', NULL, '2025-11-11 12:42:20'),
(75, 69, '7521100ed7c5c6c2cd154e3c75d9d7fe26171f129faf2ef1d3cc7ccd3ad52716', '2025-11-12 12:42:22', NULL, '2025-11-11 12:42:22'),
(76, 70, '4385897a5d38c8117fd4381a6626b3c6f4fb0fdf864e86e40c255ef95804d25f', '2025-11-12 12:42:24', NULL, '2025-11-11 12:42:24'),
(77, 71, '791c72d9908b7ec17666f1eff60b6b8c9d41159b0b242d6a850f4bf28db876a3', '2025-11-12 12:42:26', NULL, '2025-11-11 12:42:26'),
(78, 72, '5d56866583a2a1fd5e98689bc91c61b60610b9b44c9841a1040e47798b5b4800', '2025-11-12 12:42:28', NULL, '2025-11-11 12:42:28'),
(79, 73, '1b5875357dea46fa78081d7acb67260f1a866123a657430cfa4021a4319d815c', '2025-11-12 12:42:30', NULL, '2025-11-11 12:42:30'),
(80, 74, '8cadb9a6683891c83e1cc0d3582359e51b0f2549c0155d2919bf3d3822e8eb61', '2025-11-12 12:42:32', NULL, '2025-11-11 12:42:32'),
(81, 75, '081acbb289bc27c7a777f666751efef2359434d806e6855e65fefa75401e10d8', '2025-11-12 12:42:33', NULL, '2025-11-11 12:42:33'),
(82, 76, '909c9dede16adc2dda9d89bf27f2a6346522c75edaac685b9775103192f365a9', '2025-11-12 12:42:35', NULL, '2025-11-11 12:42:35'),
(83, 77, '0fbbfd5a5d7a46f893209ba934e1902d9da98d5f0853255174cfd3ced7e89714', '2025-11-12 12:42:37', NULL, '2025-11-11 12:42:37'),
(84, 78, '27466c9afb6b1f99e9b01156d37e1eaa3e58b6c26ae20ee41335a8b7c4605364', '2025-11-12 12:42:39', NULL, '2025-11-11 12:42:39'),
(85, 79, 'be8ffb7b89f0601eaaf906647b3a4595f8d8ef428d39f8e337deddbb4637b13f', '2025-11-12 12:42:41', NULL, '2025-11-11 12:42:41'),
(86, 80, '28c2e4ebb2f0837541320db0fab1ad4c33212675a091aa83ec14a1bd093d0f97', '2025-11-12 12:42:43', NULL, '2025-11-11 12:42:43'),
(87, 81, '7178854a98762f7f1c9ae6d7f7ed7c6a85729cdaa75051e3605ce7737556e02e', '2025-11-12 12:42:45', NULL, '2025-11-11 12:42:45'),
(88, 82, '71177bc3b32dede6295078bf29e74c5e6d5b432e5e2917a8618083f878e51120', '2025-11-12 12:42:47', NULL, '2025-11-11 12:42:47'),
(89, 83, '33b72caa0cd9bb55c4b16c35dfcddcaf1d44eb4aa7bdb3d02af5879f266e6f68', '2025-11-12 12:42:48', NULL, '2025-11-11 12:42:48'),
(90, 84, '81c539eab1ab781839f08b49f2a0c5b9c45d3809584b56c4cbca33160aac597e', '2025-11-12 12:42:50', NULL, '2025-11-11 12:42:50'),
(91, 85, 'c144cb9aaae33ec78e4e889ee86815e10a2204ff86cb9ca00f556886f442083b', '2025-11-12 12:42:52', NULL, '2025-11-11 12:42:52'),
(92, 86, 'c01f1565b6e3d2fd2019b40614642f9f58215fb79fd3e5d180a6b93fc48b97b6', '2025-11-12 12:42:54', NULL, '2025-11-11 12:42:54'),
(93, 87, '881cb79e0e6a8043ef749828dae12cd62379b40f69abd7d19cb3fc8fa319e937', '2025-11-12 12:42:56', NULL, '2025-11-11 12:42:56'),
(94, 88, 'b67f72e63733ff162c04154bc36ba94328b649aef18e51153074b79b4e91fa62', '2025-11-12 12:42:58', NULL, '2025-11-11 12:42:58'),
(95, 89, '3b16caa533cd1f372e717772b92434eda6f33a571e84c0d4358938194dd644cb', '2025-11-12 12:43:00', NULL, '2025-11-11 12:43:00'),
(96, 90, 'c6e2fb0bf2529a84b6ef9ef1be0a48892773f62f8e1be633969cd111bfbeee45', '2025-11-12 12:43:01', NULL, '2025-11-11 12:43:01'),
(97, 91, 'a168544647980053818b40eee0db53fb76576f547f947921f28fbe95c7d6277c', '2025-11-12 12:43:03', NULL, '2025-11-11 12:43:03'),
(98, 92, 'a90d44ec762268da5a6bfe6779905c7afd79e709f1540ef39b4c6d6511b717ff', '2025-11-12 12:43:05', NULL, '2025-11-11 12:43:05'),
(99, 93, '2e9078002a8df24df31605ed4966192f007318b1b341c4c504abb79f56b18434', '2025-11-12 12:43:07', NULL, '2025-11-11 12:43:07'),
(100, 94, '41b346938009a8e4e7f12cda817666c90a2ac227a8b23ddbe758270c1e30a450', '2025-11-12 12:43:09', NULL, '2025-11-11 12:43:09'),
(101, 95, '04aa2c2cb26e5b38d29447d92e81847b59a2c12cc27d9db372ceec3b85092d24', '2025-11-12 12:43:11', NULL, '2025-11-11 12:43:11'),
(102, 96, '4e9152f2f5a1dd27e206183c706d84a3873f786597dfe3e1528a5df4d5507438', '2025-11-12 12:43:13', NULL, '2025-11-11 12:43:13'),
(103, 97, '4d4fc28a25e75c661582c4c14cc4587226bbae1a75472e846c9e9ba2cf1f0874', '2025-11-12 12:43:15', NULL, '2025-11-11 12:43:15'),
(104, 98, '3ea8dd2ccd7e3c2ab59b90b2e3c510884ccf6f1ef54a77ab24eff5c7c4862e29', '2025-11-12 12:43:16', NULL, '2025-11-11 12:43:16'),
(105, 99, '3118b3c807f14caede4c4f38c891426acd4c485129ccb0605707c1edcf80c3c2', '2025-11-12 12:43:18', NULL, '2025-11-11 12:43:18'),
(106, 100, 'e7f47049596578686c5a9700c1f3da49bcebd8c05de29d10f061a1f3e0324509', '2025-11-12 12:43:20', NULL, '2025-11-11 12:43:20'),
(107, 101, 'ac5a9c2150750954c90fca4c648dde31320568ea21d8daf9abdae4f8d77aff3b', '2025-11-12 12:43:22', NULL, '2025-11-11 12:43:22'),
(108, 102, '02f6892431d007d64684703f8ecc615f7d368ea382578e82cf55fedf0c6569d6', '2025-11-12 12:43:24', NULL, '2025-11-11 12:43:24'),
(109, 103, 'a945b2fba9e06232387d6875c934ee75f8be19a3dc9ac15f6ef2bc28defda74d', '2025-11-12 12:43:26', NULL, '2025-11-11 12:43:26'),
(110, 104, '376b75587e441340d12d2873f938c5176b54250d687b8ed841607ccf015315b5', '2025-11-12 12:43:28', NULL, '2025-11-11 12:43:28'),
(111, 105, 'f8bff8b140e6b7f2b373d297aaf77e72fa91e213265ba7240dc7dd44570e97c6', '2025-11-12 12:43:30', NULL, '2025-11-11 12:43:30'),
(112, 106, '2ae94e19dccf7a0d37d2ba6f7f371936adb24f915480f1d45d8176e1a07857b2', '2025-11-12 12:43:31', NULL, '2025-11-11 12:43:31'),
(113, 107, 'bdda5983e3efdf7fedde3cd7d4f10abb80efb376896399bfba3042b768b40b57', '2025-11-12 12:43:33', NULL, '2025-11-11 12:43:33'),
(114, 108, 'cd2eca5ef3a4a36622bfd0f641a487ed74a2877767cb2b054167f643d7ebc4fd', '2025-11-12 12:43:35', NULL, '2025-11-11 12:43:35'),
(115, 109, '69f65e41f572455b76eaa4642dcbe1a4c9207d4bf61af499065a3bd4830f6363', '2025-11-12 12:43:37', NULL, '2025-11-11 12:43:37'),
(116, 110, 'a6a7027af8a9fdb9bbeb0c7a21db91a1841240d68f2c8f54baaba206bf9a53f6', '2025-11-12 12:43:39', NULL, '2025-11-11 12:43:39'),
(117, 111, '32bc2f742064316c48d8d0dc62b61256ae2d3bf68355b352aafc59914a1d9295', '2025-11-12 12:43:41', NULL, '2025-11-11 12:43:41'),
(118, 112, 'ba15fe2dddf80b7845747c9427fb3cc5b41679b36c6e2b1683296735ad7ad7e1', '2025-11-12 12:43:43', NULL, '2025-11-11 12:43:43'),
(119, 113, '30ebc88093cd2690082f4271037b559bc3faad7eac9122234e2dbf55728bb4e0', '2025-11-12 12:43:44', NULL, '2025-11-11 12:43:44'),
(120, 114, '4d883c906adb0ecb1291b4e6e07f4a5538211094d26d3402d124dcaf9e40f280', '2025-11-12 12:43:45', NULL, '2025-11-11 12:43:45'),
(121, 115, '140deec6dff7f056ba992a731ecf46d8708d483e96667d9a1183ccff40db0904', '2025-11-12 12:43:46', NULL, '2025-11-11 12:43:46'),
(122, 116, '596d429317bbf408f786e5fc5d5b2720f3311c896580de948505c48a7de989bb', '2025-11-12 12:43:47', NULL, '2025-11-11 12:43:47'),
(123, 117, 'c2c6e8177e5b124f2c33ed56d55a3f555c3b5d1746c977de437de32264642a4f', '2025-11-12 12:43:47', NULL, '2025-11-11 12:43:47'),
(124, 118, '2d73b959676e12f492fb2744e084d527d5e5193ac236412f0a98f131d9d1a7dd', '2025-11-12 12:43:48', NULL, '2025-11-11 12:43:48'),
(125, 119, '176b7f66ad91e2caf1d6bc1e7aa395d3c1f1c7dbddb3ab0816a2294c99da1a59', '2025-11-12 12:43:49', NULL, '2025-11-11 12:43:49'),
(126, 120, 'ad2a5aef28c692fefd213353ba6b1baa7c858339f6e3666fb266012b58ba4d33', '2025-11-12 12:43:50', NULL, '2025-11-11 12:43:50'),
(127, 121, 'fbc18ae58bc2924d199395c3be8db505f0db4743009f4d308246d9529f2340b4', '2025-11-12 12:43:51', NULL, '2025-11-11 12:43:51'),
(128, 122, '5999360b2659a3e76f76b87cb30181e0035e9fd90214c65f3ce3d7c5fd8a223e', '2025-11-13 13:01:06', '2025-11-12 13:01:25', '2025-11-12 13:01:06'),
(129, 10, '10e28aebcf77c344d009a0a80f463915f36d7fa967c0700ea413103198a95483', '2025-11-13 13:40:19', '2025-11-12 13:40:39', '2025-11-12 13:40:19'),
(130, 123, 'ae3b36471ac7a15a88e798f20deb17bf7b0e57224be74fc3d0a2758e966048de', '2025-11-14 20:24:47', '2025-11-13 20:24:57', '2025-11-13 20:24:47'),
(131, 124, '5d4450858ffb86eb0e0f6bcb6798b667d653255ad13b04435b8c1d6c1ac20825', '2025-11-18 12:06:43', '2025-11-17 12:07:03', '2025-11-17 12:06:43'),
(133, 126, 'f11843c3f787518200e08845414d240b48e5b1920162f5831983cbdedf6ba66c', '2025-11-20 09:12:42', '2025-11-19 09:12:57', '2025-11-19 09:12:42'),
(139, 1, '14bee8f68b50decebd716ab325ee449a54845bb32b983a83226db5461a84e7a7', '2025-11-25 21:05:49', '2025-11-24 21:05:56', '2025-11-24 21:05:49'),
(144, 129, 'b5c0d2e367d5f49b2db98156f3027e37d193fe1c0d2b4d7d7c9c615b89ba480b', '2025-12-03 09:25:18', '2025-12-02 09:25:57', '2025-12-02 09:25:18');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `felhasznalok`
--

CREATE TABLE `felhasznalok` (
  `fid` int(11) NOT NULL,
  `szerep` enum('felhasznalo','admin') NOT NULL DEFAULT 'felhasznalo',
  `statusz` enum('Aktiv','Fuggoben','Kitiltott') NOT NULL DEFAULT 'Fuggoben',
  `nem` enum('ferfi','no','egyeb','nem_publikus') NOT NULL DEFAULT 'nem_publikus',
  `email` varchar(255) NOT NULL,
  `email_megerositve` tinyint(1) DEFAULT 0,
  `fnev` varchar(30) NOT NULL,
  `jelszo` varchar(255) NOT NULL,
  `knev` varchar(30) DEFAULT NULL,
  `vnev` varchar(30) DEFAULT NULL,
  `profilkep` varchar(255) DEFAULT NULL,
  `szuletett` date DEFAULT NULL,
  `telefon` varchar(30) DEFAULT NULL,
  `varmegye` varchar(50) DEFAULT NULL,
  `reszletek` text DEFAULT NULL,
  `regisztralt` timestamp NOT NULL DEFAULT current_timestamp(),
  `modositott` timestamp NOT NULL DEFAULT current_timestamp(),
  `belepett` timestamp NULL DEFAULT NULL,
  `statusz_ok` text DEFAULT NULL,
  `statusz_meddig` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `felhasznalok`
--

INSERT INTO `felhasznalok` (`fid`, `szerep`, `statusz`, `nem`, `email`, `email_megerositve`, `fnev`, `jelszo`, `knev`, `vnev`, `profilkep`, `szuletett`, `telefon`, `varmegye`, `reszletek`, `regisztralt`, `modositott`, `belepett`, `statusz_ok`, `statusz_meddig`) VALUES
(1, 'admin', 'Aktiv', 'ferfi', 'boros.daniel2003@gmail.com', 1, 'Ikelos', '$2y$10$f6HuNX0LxGyPuqUYZbmdo.M.fHnUj4RPcewYmg2wE6GS051IccBwu', 'Dániel', 'Boros', 'Ikelos_2025-11-19_20-54-29.png', '2005-11-20', '06307367201', 'Budapest', 'Dani vagyok 10.000.000 FT-ért dolgozok.\r\n- Naponta 5 rekesz sör (Önök állják).', '2025-10-21 21:35:14', '2025-12-01 12:10:28', '2025-12-02 09:22:12', NULL, NULL),
(4, 'admin', 'Aktiv', 'no', 'hencsey.dorina@gmail.com', 1, 'DoDo', '$2y$10$donaP.ouZCvUtFaZtYfLeeEdpbS/9Fv697U6rShphn5ogrsogS70C', 'Dorina', 'Hencsey', 'DoDo_2025-11-04_17-43-24.png', '2006-11-06', '+36308681314', '', 'Józsika lánya vagyok', '2025-10-22 21:58:22', '2025-12-01 12:21:19', '2025-12-02 09:14:29', '', '0000-00-00 00:00:00'),
(7, 'admin', 'Aktiv', 'ferfi', 'klebergergely@gmail.com', 1, 'Gernya', '$2y$10$DF0Vmth37v7gMaucVtbG0uvGjDRvOZ0n8YuqBV5VyoNFw6hP1qKlq', 'Gergely', 'Kléber', 'Gernya_2025-11-04_11-20-52.png', '2006-05-26', '06702264590', 'Budapest', NULL, '2025-11-03 09:54:33', '2025-12-01 09:59:49', '2025-12-02 09:52:15', 'szívesen', '0000-00-00 00:00:00'),
(8, 'felhasznalo', 'Fuggoben', 'nem_publikus', 'geri11@gmail.com', 0, 'geri11', '$2y$10$29Gb9LsG/MH94D4TPl3gneTIlzrDqhQhStp7dRNH59psGMqR2yM9y', NULL, NULL, 'default.png', NULL, NULL, NULL, NULL, '2025-11-03 17:33:46', '2025-11-25 14:15:28', NULL, '', '0000-00-00 00:00:00'),
(9, 'felhasznalo', 'Fuggoben', 'nem_publikus', 'jancsi@gmail.com', 0, 'jancsi', '$2y$10$AfsLVZXVvVW/s633bxpa3uikW.pE9lKjMlar7Z1GkaNDMn5ZYH9U6', NULL, NULL, 'default.png', NULL, NULL, NULL, NULL, '2025-11-08 17:39:06', '2025-11-25 14:15:34', NULL, '', '0000-00-00 00:00:00'),
(10, 'felhasznalo', 'Aktiv', 'nem_publikus', 'kleberposta@gmail.com', 1, 'KG Apa', '$2y$10$0VGwzsEdbVh5o33nxJYUnuRBCGD.riXwkt8fyAR2F4gMZOa8vP6me', NULL, NULL, 'default.png', NULL, NULL, NULL, NULL, '2025-11-10 12:37:40', '2025-11-10 12:37:40', '2025-11-12 13:40:49', NULL, NULL),
(124, 'felhasznalo', 'Aktiv', 'nem_publikus', 'kothenczmartin@gmail.com', 1, 'km25', '$2y$10$qSuKkMgwLBvmBDBtd/M.R.0uwmBcPwJX8v4QsR2.Ig3e7VRunJIga', NULL, NULL, 'default.png', NULL, NULL, NULL, NULL, '2025-11-17 12:06:43', '2025-11-17 12:06:43', '2025-12-01 12:19:57', NULL, NULL),
(125, 'felhasznalo', 'Fuggoben', 'nem_publikus', 'szalontai.sza@gmail.com', 0, 'mate', '$2y$10$5k6L6CdYcx9xltmL79TyS.EhsGb3P5DociVdUZ.ajxvLtrlC6wqeu', NULL, NULL, 'default.png', NULL, NULL, NULL, NULL, '2025-11-19 09:10:02', '2025-11-25 14:15:46', NULL, '', '0000-00-00 00:00:00'),
(126, 'felhasznalo', 'Aktiv', 'nem_publikus', 'kisgergely48@gmail.com', 1, 'Dani_egy_sexy_bestia', '$2y$10$9tl9XzLkw7MtwkAkuquL7.fvuscebsbG0s2t1tk098BV0pLVIKoiy', NULL, NULL, 'default.png', NULL, NULL, NULL, NULL, '2025-11-19 09:12:42', '2025-11-19 09:12:42', '2025-11-19 09:14:08', NULL, NULL),
(128, 'felhasznalo', 'Aktiv', 'ferfi', 'h4nzoart@gmail.com', 1, 'Hanzo', '$2y$10$JbCXfETTKb5.oqZ8Ahh/7Oxa/IuR94rH1GVp328jwkSO7DisIMFIq', 'Dani', 'Boros', 'default.png', '2005-11-20', '06307367200', 'Budapest', NULL, '2025-11-24 21:11:49', '2025-11-28 12:25:00', '2025-12-02 09:21:53', NULL, NULL),
(129, 'felhasznalo', 'Aktiv', 'nem_publikus', 'kleber.gergely@wm-iskola.hu', 1, 'Fawkes', '$2y$10$RLahcXfcQYWG2ne2jsIp.eXEONKXJvw0D770dzCcHp/dgra8purfW', NULL, NULL, 'default.png', NULL, NULL, NULL, NULL, '2025-12-02 09:25:18', '2025-12-02 09:25:18', '2025-12-02 09:50:32', NULL, NULL);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `felhasznalo_tevekenyseg`
--

CREATE TABLE `felhasznalo_tevekenyseg` (
  `ftid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `ip` varchar(60) NOT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `bongeszo` varchar(100) DEFAULT NULL,
  `tevekenyseg` varchar(255) NOT NULL,
  `sikeresseg` enum('sikeres','sikertelen') NOT NULL DEFAULT 'sikeres',
  `modositott_mezo` varchar(50) DEFAULT NULL,
  `regi_ertek` text DEFAULT NULL,
  `uj_ertek` text DEFAULT NULL,
  `kategoria` enum('bejelentkezes','profil','biztonsag','egyeb') DEFAULT 'egyeb',
  `prioritas` enum('alacsony','normal','magas') DEFAULT 'normal',
  `idopont` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `felhasznalo_tevekenyseg`
--

INSERT INTO `felhasznalo_tevekenyseg` (`ftid`, `fid`, `ip`, `session_id`, `bongeszo`, `tevekenyseg`, `sikeresseg`, `modositott_mezo`, `regi_ertek`, `uj_ertek`, `kategoria`, `prioritas`, `idopont`) VALUES
(1, 1, '89.134.20.10', '555c73g1vd137qjk48n8hlq1gj', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-21 21:35:16'),
(2, 1, '89.134.20.10', '555c73g1vd137qjk48n8hlq1gj', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-21 21:36:32'),
(3, 1, '89.134.20.10', '555c73g1vd137qjk48n8hlq1gj', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-21 21:39:24'),
(4, 1, '89.134.20.10', '555c73g1vd137qjk48n8hlq1gj', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-21 21:39:28'),
(5, 2, '195.199.251.129', '78cqu2mkaaeh282t7onjr4b90o', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-22 11:43:38'),
(6, 3, '195.199.251.129', '78cqu2mkaaeh282t7onjr4b90o', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-22 11:45:06'),
(7, 3, '195.199.251.129', '78cqu2mkaaeh282t7onjr4b90o', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-22 11:45:56'),
(8, 3, '195.199.251.129', '78cqu2mkaaeh282t7onjr4b90o', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-22 11:46:18'),
(9, 3, '195.199.251.129', '78cqu2mkaaeh282t7onjr4b90o', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-22 11:46:25'),
(10, 1, '89.134.20.10', 'r4ihllgq4u8m463m46es6nohr1', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-22 19:54:00'),
(11, 1, '89.134.20.10', 'r4ihllgq4u8m463m46es6nohr1', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-22 19:56:55'),
(12, 4, '188.6.166.253', '79vb414goqcr7knlir486o2qsn', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-22 21:58:23'),
(13, 4, '188.6.166.253', 'aa5hktaruoqe6h8fr035ba9jae', 'Safari', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-23 10:32:41'),
(14, 4, '188.6.166.253', 'aa5hktaruoqe6h8fr035ba9jae', 'Safari', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-23 10:35:34'),
(15, 5, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 16:29:23'),
(16, 5, '89.134.20.10', 'vkce7ovmeivfvnrblaa23b16ve', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 16:38:42'),
(17, 6, '89.134.20.10', 'vkce7ovmeivfvnrblaa23b16ve', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 16:51:58'),
(18, 6, '89.134.20.10', 'vkce7ovmeivfvnrblaa23b16ve', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 16:52:26'),
(19, 6, '89.134.20.10', 'vkce7ovmeivfvnrblaa23b16ve', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 16:54:20'),
(20, 6, '89.134.20.10', 'vkce7ovmeivfvnrblaa23b16ve', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 16:57:14'),
(21, 6, '89.134.20.10', 'vkce7ovmeivfvnrblaa23b16ve', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 16:57:53'),
(22, 6, '89.134.20.10', 'vkce7ovmeivfvnrblaa23b16ve', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 16:58:39'),
(23, 6, '89.134.20.10', 'vkce7ovmeivfvnrblaa23b16ve', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 17:09:22'),
(24, 6, '89.134.20.10', 'vkce7ovmeivfvnrblaa23b16ve', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 17:12:01'),
(25, 6, '89.134.20.10', 'vkce7ovmeivfvnrblaa23b16ve', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 17:14:42'),
(26, 6, '89.134.20.10', 'vkce7ovmeivfvnrblaa23b16ve', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 17:23:14'),
(27, 1, '89.134.20.10', 'vkce7ovmeivfvnrblaa23b16ve', 'Chrome', 'elfelejtett_jelszo', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 18:40:55'),
(28, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'jelszo_visszaallitas', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 18:41:37'),
(29, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 18:41:56'),
(30, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 18:41:58'),
(31, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'elfelejtett_jelszo', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 18:42:12'),
(32, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'elfelejtett_jelszo', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 18:43:05'),
(33, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'jelszo_visszaallitas', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 18:43:21'),
(34, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 18:43:26'),
(35, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 18:43:28'),
(36, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 18:43:31'),
(37, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 18:43:35'),
(38, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 18:44:22'),
(39, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 18:44:24'),
(40, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'elfelejtett_jelszo', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 18:47:37'),
(41, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'jelszo_visszaallitas', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 18:47:57'),
(42, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 18:58:29'),
(43, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 18:58:29'),
(44, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 18:58:51'),
(45, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 19:01:11'),
(46, 6, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 19:02:00'),
(47, 6, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 19:02:02'),
(48, 6, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 19:02:07'),
(49, 6, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 19:02:11'),
(50, 6, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 19:02:16'),
(51, 6, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 19:02:24'),
(52, 6, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 19:02:38'),
(53, 6, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 19:02:40'),
(54, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 19:02:58'),
(55, 6, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 19:05:05'),
(56, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 19:27:32'),
(57, 1, '89.134.20.10', '4o1bvc40plvmrdg33r12p9309j', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-26 19:29:36'),
(58, 1, '89.134.20.10', 'f4d6069dg462nf55gnfpst5oei', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-30 13:33:02'),
(59, 1, '89.134.20.10', 'f4d6069dg462nf55gnfpst5oei', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-10-30 13:34:31'),
(60, 4, '188.6.166.253', 'mufsf3dd6f0ojn9r8rb89heh60', 'Safari', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-01 18:14:29'),
(61, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-03 09:54:34'),
(62, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-03 09:54:45'),
(63, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-03 09:54:50'),
(64, 1, '195.199.251.129', 'c86t2c0v3guml3aq23b7bor8of', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-03 10:09:40'),
(65, 1, '195.199.251.129', 'c86t2c0v3guml3aq23b7bor8of', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-03 11:17:13'),
(66, 1, '195.199.251.129', 'c86t2c0v3guml3aq23b7bor8of', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-03 11:17:23'),
(67, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-03 12:08:16'),
(68, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-03 12:08:30'),
(69, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-03 12:08:34'),
(70, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-03 12:08:46'),
(71, 1, '195.199.251.129', 'c86t2c0v3guml3aq23b7bor8of', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-03 12:22:04'),
(72, 1, '195.199.251.129', 'c86t2c0v3guml3aq23b7bor8of', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-03 12:22:12'),
(73, 7, '31.46.170.254', 'm449n463n4h6stnd8ht7rj63hp', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-03 16:54:45'),
(74, 7, '31.46.170.254', 'm449n463n4h6stnd8ht7rj63hp', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-03 16:54:46'),
(75, 7, '31.46.170.254', 'm449n463n4h6stnd8ht7rj63hp', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-03 16:57:57'),
(76, 7, '31.46.170.254', 'm449n463n4h6stnd8ht7rj63hp', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-03 16:58:31'),
(77, 7, '31.46.170.254', 'lrp0bhd6g55hs66drkjkurpiu3', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-03 17:12:03'),
(78, 8, '91.82.4.194', 'duqmnelje1q4qf1o67unslpvf8', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-03 17:33:47'),
(79, 8, '91.82.4.194', 'duqmnelje1q4qf1o67unslpvf8', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-03 17:36:36'),
(80, 7, '195.199.251.129', 'rhtpr34qop5s4p1t5v6ic6r12d', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 07:56:40'),
(81, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 09:09:58'),
(82, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'fnev', 'Dani', 'Daniel', 'profil', 'normal', '2025-11-04 09:38:34'),
(83, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'knev', 'üres', 'Dániel', 'profil', 'normal', '2025-11-04 09:47:58'),
(84, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'vnev', 'üres', 'Boros', 'profil', 'normal', '2025-11-04 09:47:58'),
(85, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', '-0001-11-30', '2005-11-20', 'profil', 'normal', '2025-11-04 09:47:58'),
(86, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'telefon', 'üres', '06307367201', 'profil', 'normal', '2025-11-04 09:47:58'),
(87, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'varos', 'üres', 'Budapest', 'profil', 'normal', '2025-11-04 09:47:58'),
(88, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'profilkep', 'default.png', 'Daniel_2025-11-04_11-01-41.png', 'profil', 'normal', '2025-11-04 10:01:41'),
(89, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'nem', 'nem_publikus', 'ferfi', 'profil', 'normal', '2025-11-04 10:13:46'),
(90, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'profil_modositas', 'sikeres', 'jelszo', '***', '***', 'profil', 'normal', '2025-11-04 10:20:52'),
(91, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'profil_modositas', 'sikeres', 'profilkep', 'default.png', 'Gernya_2025-11-04_11-20-52.png', 'profil', 'normal', '2025-11-04 10:20:52'),
(92, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'profil_modositas', 'sikeres', 'nem', 'nem_publikus', 'ferfi', 'profil', 'normal', '2025-11-04 10:20:52'),
(93, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'profil_modositas', 'sikeres', 'knev', 'üres', 'Gergely', 'profil', 'normal', '2025-11-04 10:20:52'),
(94, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'profil_modositas', 'sikeres', 'vnev', 'üres', 'Kléber', 'profil', 'normal', '2025-11-04 10:20:52'),
(95, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', 'üres', '2006-05-26', 'profil', 'normal', '2025-11-04 10:20:52'),
(96, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'profil_modositas', 'sikeres', 'telefon', 'üres', '06702264590', 'profil', 'normal', '2025-11-04 10:20:52'),
(97, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'profil_modositas', 'sikeres', 'varos', 'üres', 'Budapest XXI.ker', 'profil', 'normal', '2025-11-04 10:20:52'),
(98, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 11:17:56'),
(99, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 11:18:07'),
(100, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'nem', 'ferfi', 'nem_publikus', 'profil', 'normal', '2025-11-04 11:19:33'),
(101, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'fnev', 'Daniel', 'Dani', 'profil', 'normal', '2025-11-04 11:19:33'),
(102, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'knev', 'Dániel', 'Dani', 'profil', 'normal', '2025-11-04 11:19:33'),
(103, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'vnev', 'Boros', 'Bors', 'profil', 'normal', '2025-11-04 11:19:33'),
(104, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', '2005-11-20', '2005-11-19', 'profil', 'normal', '2025-11-04 11:19:33'),
(105, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'telefon', '06307367201', '06307367200', 'profil', 'normal', '2025-11-04 11:19:33'),
(106, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'varos', 'Budapest', 'Budapes', 'profil', 'normal', '2025-11-04 11:19:33'),
(107, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'nem', 'nem_publikus', 'ferfi', 'profil', 'normal', '2025-11-04 11:20:43'),
(108, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'fnev', 'Dani', 'Ikelos', 'profil', 'normal', '2025-11-04 11:20:43'),
(109, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'knev', 'Dani', 'Dániel', 'profil', 'normal', '2025-11-04 11:20:43'),
(110, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'vnev', 'Bors', 'Boros', 'profil', 'normal', '2025-11-04 11:20:43'),
(111, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', '2005-11-19', '2005-11-20', 'profil', 'normal', '2025-11-04 11:20:43'),
(112, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'telefon', '06307367200', '06307367201', 'profil', 'normal', '2025-11-04 11:20:43'),
(113, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'varos', 'Budapes', 'Budapest', 'profil', 'normal', '2025-11-04 11:20:43'),
(114, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 11:20:49'),
(115, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 11:34:16'),
(116, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 11:39:13'),
(117, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 11:39:27'),
(118, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 11:39:30'),
(119, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 11:46:15'),
(120, 1, '84.225.107.134', 'tbfgqahe7eik8mkdmcstsl0s45', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 11:48:36'),
(121, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 12:15:32'),
(122, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 12:15:35'),
(123, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'varos', 'Budapest', 'Budapes', 'profil', 'normal', '2025-11-04 12:18:13'),
(124, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'profil_modositas', 'sikeres', 'varos', 'Budapes', 'Budapest', 'profil', 'normal', '2025-11-04 12:18:32'),
(125, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'profil_modositas', 'sikeres', 'jelszo', '***', '***', 'profil', 'normal', '2025-11-04 12:43:51'),
(126, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'profil_modositas', 'sikeres', 'fnev', 'Gernya', 'Ikszdé', 'profil', 'normal', '2025-11-04 12:43:51'),
(127, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'profil_modositas', 'sikeres', 'jelszo', '***', '***', 'profil', 'normal', '2025-11-04 12:44:25'),
(128, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'profil_modositas', 'sikeres', 'fnev', 'Ikszdé', 'Gernya', 'profil', 'normal', '2025-11-04 12:44:25'),
(129, 1, '84.225.107.134', 's8q6gpplcertda0shdso7pvfve', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 13:19:29'),
(130, 1, '195.199.251.129', 'bq2jucbecc23je0u776atl18v6', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 13:59:00'),
(131, 4, '188.6.166.253', '32ii23av3p24vjkm7687cusqa4', 'Safari', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 16:07:33'),
(132, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 16:10:05'),
(133, 1, '37.76.15.158', 'kq07qtg4n4geogsi5t76bss90f', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 16:26:46'),
(134, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'profilkep', 'default.png', 'DoDo_2025-11-04_17-43-24.png', 'profil', 'normal', '2025-11-04 16:43:24'),
(135, 1, '37.76.15.158', 'kq07qtg4n4geogsi5t76bss90f', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 16:50:47'),
(136, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'email', 'hencsey.dorina@gmail.com', 'henyododoka@gmail.com', 'profil', 'normal', '2025-11-04 16:54:28'),
(137, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', '-0001-11-30', 'üres', 'profil', 'normal', '2025-11-04 16:54:28'),
(138, 4, '188.6.166.253', '32ii23av3p24vjkm7687cusqa4', 'Safari', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 16:54:42'),
(139, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'email', 'henyododoka@gmail.com', 'hencsey.dorina@gmail.com', 'profil', 'normal', '2025-11-04 16:55:51'),
(140, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', '-0001-11-30', 'üres', 'profil', 'normal', '2025-11-04 16:55:51'),
(141, 4, '188.6.166.253', '32ii23av3p24vjkm7687cusqa4', 'Safari', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-04 16:56:01'),
(142, 7, '195.199.251.129', 'ks5q7451gv9f2jed3spdcvh725', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-07 09:57:23'),
(143, 7, '195.199.251.129', 'umj6tq6lc21adn0q0cf5ilc4cf', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-07 11:49:38'),
(144, 9, '82.131.141.97', 'tjimgi7rno98d0tdaabaai3ff7', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-08 17:39:07'),
(145, 7, '195.199.251.129', 't3452mid590fohis67atm1jqc6', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 09:59:13'),
(146, 1, '195.199.251.129', 'vc45ngjq3amiame1egbaus52uf', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 10:09:23'),
(147, 1, '195.199.251.129', 'vc45ngjq3amiame1egbaus52uf', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 10:10:21'),
(148, 1, '37.76.0.250', 'ighf3hab3lgughg5g9kn2n9o1o', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 10:17:20'),
(149, 1, '37.76.0.250', '83lqejludtm5laa2gaf813pur5', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 10:26:46'),
(150, 1, '195.199.251.129', 'vc45ngjq3amiame1egbaus52uf', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 10:30:18'),
(151, 2, '195.199.251.129', 'ebleb3a0sq2nq1aj24dq499a4l', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 12:13:34'),
(152, 2, '94.44.233.166', 'vsbrcn54s337pqqanb1i7p1b3m', 'Safari', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 12:13:47'),
(153, 2, '195.199.251.129', 'ebleb3a0sq2nq1aj24dq499a4l', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 12:13:58'),
(154, 7, '195.199.251.129', 'u6k86klgonoqrg6sdd6harpns6', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 12:16:46'),
(155, 2, '195.199.251.129', 'ebleb3a0sq2nq1aj24dq499a4l', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 12:17:01'),
(156, 10, '86.101.78.241', 'opa7eus5sorplfgl82qrsjlr14', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 12:37:41'),
(157, 10, '94.44.108.98', 'kr1tsv37hmcvmlaaihf0r5s2gi', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 12:37:58'),
(158, 10, '86.101.78.241', 'opa7eus5sorplfgl82qrsjlr14', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 12:38:35'),
(159, 1, '89.134.19.231', 'lbctpfn5qtoidji465ntso4kd2', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 14:39:33'),
(160, 1, '89.134.19.231', 'lbctpfn5qtoidji465ntso4kd2', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 14:40:02'),
(161, 1, '89.134.19.231', 'smda7n1dlr1hlba85ctfuoa84c', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 14:40:17'),
(162, 1, '89.134.19.231', 'smda7n1dlr1hlba85ctfuoa84c', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 15:06:32'),
(163, 1, '89.134.19.231', 'smda7n1dlr1hlba85ctfuoa84c', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 15:07:03'),
(164, 1, '89.134.19.231', 'smda7n1dlr1hlba85ctfuoa84c', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 15:12:34'),
(165, 1, '89.134.19.231', 'smda7n1dlr1hlba85ctfuoa84c', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 15:14:42'),
(166, 1, '89.134.19.231', 'smda7n1dlr1hlba85ctfuoa84c', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 15:25:57'),
(167, 1, '89.134.19.231', 'smda7n1dlr1hlba85ctfuoa84c', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 15:31:37'),
(168, 1, '89.134.19.231', 'smda7n1dlr1hlba85ctfuoa84c', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 15:32:12'),
(169, 1, '89.134.19.231', 'smda7n1dlr1hlba85ctfuoa84c', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 15:34:20'),
(170, 1, '89.134.19.231', 'smda7n1dlr1hlba85ctfuoa84c', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 15:37:14'),
(171, 1, '89.134.19.231', 'smda7n1dlr1hlba85ctfuoa84c', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 15:39:23'),
(172, 1, '89.134.19.231', 'smda7n1dlr1hlba85ctfuoa84c', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 15:57:10'),
(173, 1, '89.134.19.231', 'smda7n1dlr1hlba85ctfuoa84c', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 15:57:41'),
(174, 1, '89.134.19.231', 'q6a1pigcqcg4i4cr7uh8k8ttbd', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 16:10:21'),
(175, 1, '89.134.19.231', 'smda7n1dlr1hlba85ctfuoa84c', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 17:25:40'),
(176, 1, '89.134.19.231', 'uj1g71o3sqfhmnorn4u4a76u7j', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 18:06:33'),
(177, 1, '89.134.19.231', '4cd3fflvoef7jqt6ipv6fj8hkq', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 18:38:53'),
(178, 1, '89.134.19.231', '4cd3fflvoef7jqt6ipv6fj8hkq', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 18:39:08'),
(179, 1, '89.134.19.231', '4cd3fflvoef7jqt6ipv6fj8hkq', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 18:50:27'),
(180, 1, '89.134.19.231', 'cnj8srm3fksi7ck40isrogt271', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 18:50:53'),
(181, 1, '89.134.19.231', '4cd3fflvoef7jqt6ipv6fj8hkq', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 20:00:59'),
(182, 1, '89.134.19.231', '4cd3fflvoef7jqt6ipv6fj8hkq', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 20:03:58'),
(183, 1, '89.134.19.231', 'kimrmfeftgr6mce3euom5rrbdn', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 20:12:17'),
(184, 1, '89.134.19.231', 'djcp60sc8a64b12f850i4mc60q', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 20:14:09'),
(185, 1, '89.134.19.231', '3q65cjccco3lsr8avte5q1nr77', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 23:08:31'),
(186, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 23:11:19'),
(187, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'nem', 'nem_publikus', 'no', 'profil', 'normal', '2025-11-10 23:20:32'),
(188, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', '-0001-11-30', 'üres', 'profil', 'normal', '2025-11-10 23:20:32'),
(189, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'nem', 'no', 'nem_publikus', 'profil', 'normal', '2025-11-10 23:20:46'),
(190, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', '-0001-11-30', 'üres', 'profil', 'normal', '2025-11-10 23:20:46'),
(191, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'nem', 'nem_publikus', 'no', 'profil', 'normal', '2025-11-10 23:20:56'),
(192, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', '-0001-11-30', 'üres', 'profil', 'normal', '2025-11-10 23:20:56'),
(193, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'knev', 'üres', 'Dorina', 'profil', 'normal', '2025-11-10 23:21:07'),
(194, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', '-0001-11-30', 'üres', 'profil', 'normal', '2025-11-10 23:21:07'),
(195, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'vnev', 'üres', 'Hencsey', 'profil', 'normal', '2025-11-10 23:21:12'),
(196, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', '-0001-11-30', 'üres', 'profil', 'normal', '2025-11-10 23:21:12'),
(197, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', '-0001-11-30', 'üres', 'profil', 'normal', '2025-11-10 23:21:17'),
(198, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', '-0001-11-30', 'üres', 'profil', 'normal', '2025-11-10 23:21:26'),
(199, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', '-0001-11-30', 'üres', 'profil', 'normal', '2025-11-10 23:21:36'),
(200, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'telefon', 'üres', '+36308681314', 'profil', 'normal', '2025-11-10 23:21:36'),
(201, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', '-0001-11-30', '2006-11-06', 'profil', 'normal', '2025-11-10 23:21:46'),
(202, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', '2006-11-06', '0001-01-01', 'profil', 'normal', '2025-11-10 23:22:00'),
(203, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', '0001-01-01', '2006-11-06', 'profil', 'normal', '2025-11-10 23:22:26'),
(204, 4, '188.6.166.253', '4v8si7cj8dc5vvce71l9f7jneg', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 23:25:19'),
(205, 6, '89.134.19.231', 'g20aagsjnh4hcplj5q19dc5tle', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 23:30:30'),
(206, 6, '89.134.19.231', 'g20aagsjnh4hcplj5q19dc5tle', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 23:30:41'),
(207, 6, '89.134.19.231', 'g20aagsjnh4hcplj5q19dc5tle', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 23:30:58'),
(208, 1, '89.134.19.231', '3q65cjccco3lsr8avte5q1nr77', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 23:34:22'),
(209, 1, '89.134.19.231', '3q65cjccco3lsr8avte5q1nr77', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 23:34:57'),
(210, 6, '89.134.19.231', 'g20aagsjnh4hcplj5q19dc5tle', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 23:43:19'),
(211, 1, '89.134.19.231', 'g20aagsjnh4hcplj5q19dc5tle', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 23:43:28'),
(212, 4, '188.6.166.253', '9jgpmhktroucqgls2dj0ntlgrb', 'Safari', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 23:44:18'),
(213, 4, '188.6.166.253', '9jgpmhktroucqgls2dj0ntlgrb', 'Safari', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 23:48:15'),
(214, 4, '188.6.166.253', '9jgpmhktroucqgls2dj0ntlgrb', 'Safari', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-10 23:48:46'),
(215, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 08:54:52'),
(216, 11, '195.199.251.129', 'lmtq7i3vtk36s9qatafmfd65lk', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 09:17:35'),
(217, 12, '195.199.251.129', 'lmtq7i3vtk36s9qatafmfd65lk', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 09:17:37'),
(218, 13, '195.199.251.129', 'lmtq7i3vtk36s9qatafmfd65lk', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 09:17:39'),
(219, 14, '195.199.251.129', 'lmtq7i3vtk36s9qatafmfd65lk', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 09:17:41'),
(220, 15, '195.199.251.129', 'lmtq7i3vtk36s9qatafmfd65lk', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 09:17:43'),
(221, 16, '195.199.251.129', 'lmtq7i3vtk36s9qatafmfd65lk', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 09:17:44'),
(222, 17, '195.199.251.129', 'lmtq7i3vtk36s9qatafmfd65lk', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 09:17:46'),
(223, 18, '195.199.251.129', 'lmtq7i3vtk36s9qatafmfd65lk', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 09:17:48'),
(224, 19, '195.199.251.129', 'lmtq7i3vtk36s9qatafmfd65lk', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 09:17:50'),
(225, 20, '195.199.251.129', 'lmtq7i3vtk36s9qatafmfd65lk', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 09:17:52'),
(226, 21, '195.199.251.129', 'rbnkelk1k87iiqvanj61127cg5', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 09:22:51'),
(227, 22, '195.199.251.129', 'rbnkelk1k87iiqvanj61127cg5', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 09:22:53'),
(228, 23, '195.199.251.129', 'rbnkelk1k87iiqvanj61127cg5', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 09:22:55'),
(229, 24, '195.199.251.129', 'rbnkelk1k87iiqvanj61127cg5', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 09:22:57'),
(230, 25, '195.199.251.129', 'rbnkelk1k87iiqvanj61127cg5', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 09:22:59'),
(231, 1, '37.76.10.227', 'javibntjql27f31ek6240cukua', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 10:20:45'),
(232, 1, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 10:26:00'),
(233, 1, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 10:31:22'),
(234, 28, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 10:31:33'),
(235, 29, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 10:40:10'),
(236, 1, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 10:41:49'),
(237, 1, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 10:43:24'),
(238, 1, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 10:43:32'),
(239, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'profil_modositas', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 11:11:04'),
(240, 1, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 11:11:59'),
(241, 1, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 11:12:02'),
(242, 1, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 11:49:01'),
(243, 1, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 11:49:10'),
(244, 1, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 11:50:14'),
(245, 1, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 11:51:30'),
(246, 1, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 11:51:44'),
(247, 1, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 11:53:02'),
(248, 1, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 11:53:10'),
(249, 1, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 11:53:13'),
(250, 30, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 11:57:20'),
(251, 30, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 11:58:22'),
(252, 30, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 11:58:50'),
(253, 30, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'egyeb', 'normal', '2025-11-11 11:59:21'),
(254, 30, '37.76.10.227', 'u5o0ufouf4afia2id4gfdjfp0f', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 11:59:58'),
(255, 30, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 12:00:43'),
(256, 30, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'profil_modositas', 'sikeres', 'nem', 'nem_publikus', 'ferfi', 'profil', 'normal', '2025-11-11 12:03:16'),
(257, 30, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'profil_modositas', 'sikeres', 'knev', 'üres', 'Dániel', 'profil', 'normal', '2025-11-11 12:03:16'),
(258, 30, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'profil_modositas', 'sikeres', 'vnev', 'üres', 'Boros', 'profil', 'normal', '2025-11-11 12:03:16'),
(259, 30, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', 'üres', '2005-11-20', 'profil', 'normal', '2025-11-11 12:03:16'),
(260, 30, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'profil_modositas', 'sikeres', 'telefon', 'üres', '06307367201', 'profil', 'normal', '2025-11-11 12:03:16'),
(261, 30, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'profil_modositas', 'sikeres', 'varos', 'üres', 'Budapest', 'profil', 'normal', '2025-11-11 12:03:16'),
(262, 30, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 12:13:20'),
(263, 31, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:13:51'),
(264, 31, '37.76.10.227', 'u5o0ufouf4afia2id4gfdjfp0f', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:14:15'),
(265, 31, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:14:57'),
(266, 31, '37.76.10.227', 'u5o0ufouf4afia2id4gfdjfp0f', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:15:17'),
(267, 31, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:15:28'),
(268, 31, '37.76.10.227', 'u5o0ufouf4afia2id4gfdjfp0f', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:15:39'),
(269, 32, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:16:23'),
(270, 32, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 12:16:35'),
(271, 32, '37.76.10.227', 'u5o0ufouf4afia2id4gfdjfp0f', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:16:52'),
(272, 32, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 12:17:10'),
(273, 32, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'profil_modositas', 'sikeres', 'nem', 'nem_publikus', 'ferfi', 'profil', 'normal', '2025-11-11 12:18:57'),
(274, 32, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'profil_modositas', 'sikeres', 'knev', 'üres', 'Dániel', 'profil', 'normal', '2025-11-11 12:18:57'),
(275, 32, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'profil_modositas', 'sikeres', 'vnev', 'üres', 'Boros', 'profil', 'normal', '2025-11-11 12:18:57'),
(276, 32, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', 'üres', '2005-11-20', 'profil', 'normal', '2025-11-11 12:18:57'),
(277, 32, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'profil_modositas', 'sikeres', 'telefon', 'üres', '06307367201', 'profil', 'normal', '2025-11-11 12:18:57'),
(278, 32, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'profil_modositas', 'sikeres', 'varos', 'üres', 'Budapest', 'profil', 'normal', '2025-11-11 12:18:57'),
(279, 32, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 12:31:43'),
(280, 33, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:31:55'),
(281, 34, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:34:00'),
(282, 35, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:34:46'),
(283, 35, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 12:35:24'),
(284, 35, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 12:35:55'),
(285, 35, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 12:36:03'),
(286, 35, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 12:36:09'),
(287, 36, '195.199.251.129', 'afii58tnei7p5a33ktrnkbr5ql', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:38:35'),
(290, 38, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:41:24'),
(291, 39, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:41:26'),
(292, 40, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:41:28'),
(293, 41, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:41:30'),
(294, 42, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:41:32'),
(295, 43, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:41:34'),
(296, 44, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:41:36'),
(297, 45, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:41:38'),
(298, 46, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:41:40'),
(299, 47, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:41:41'),
(300, 48, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:41:43'),
(301, 49, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:41:45'),
(302, 50, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:41:47'),
(303, 51, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:41:49'),
(304, 52, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:41:51'),
(305, 53, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:41:53'),
(306, 54, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:41:55'),
(307, 55, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:41:56'),
(308, 56, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:41:58'),
(309, 57, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:00'),
(310, 58, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:02'),
(311, 59, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:04'),
(312, 60, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:06'),
(313, 61, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:08');
INSERT INTO `felhasznalo_tevekenyseg` (`ftid`, `fid`, `ip`, `session_id`, `bongeszo`, `tevekenyseg`, `sikeresseg`, `modositott_mezo`, `regi_ertek`, `uj_ertek`, `kategoria`, `prioritas`, `idopont`) VALUES
(314, 62, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:10'),
(315, 63, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:11'),
(316, 64, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:13'),
(317, 65, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:15'),
(318, 66, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:17'),
(319, 67, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:19'),
(320, 68, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:21'),
(321, 69, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:23'),
(322, 70, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:25'),
(323, 71, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:27'),
(324, 72, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:28'),
(325, 73, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:30'),
(326, 74, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:32'),
(327, 75, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:34'),
(328, 76, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:36'),
(329, 77, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:38'),
(330, 78, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:40'),
(331, 79, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:42'),
(332, 80, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:44'),
(333, 81, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:45'),
(334, 82, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:47'),
(335, 83, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:49'),
(336, 84, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:51'),
(337, 85, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:53'),
(338, 86, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:55'),
(339, 87, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:57'),
(340, 88, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:42:58'),
(341, 89, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:00'),
(342, 90, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:02'),
(343, 91, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:04'),
(344, 92, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:06'),
(345, 93, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:08'),
(347, 94, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:10'),
(348, 95, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:12'),
(349, 96, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:13'),
(350, 97, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:15'),
(351, 98, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:17'),
(352, 99, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:19'),
(353, 100, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:21'),
(354, 101, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:23'),
(355, 102, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:25'),
(356, 103, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:27'),
(357, 104, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:28'),
(358, 105, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:30'),
(359, 106, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:32'),
(360, 107, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:34'),
(361, 108, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:36'),
(362, 109, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:38'),
(363, 110, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:40'),
(364, 111, '195.199.251.129', 'ba975fkuqc3rp523o715looeqh', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:42'),
(365, 112, '195.199.251.129', '05muet3lpr54ja11o4ceq6vulk', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:44'),
(366, 113, '195.199.251.129', '05muet3lpr54ja11o4ceq6vulk', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:45'),
(367, 114, '195.199.251.129', '05muet3lpr54ja11o4ceq6vulk', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:46'),
(368, 115, '195.199.251.129', '05muet3lpr54ja11o4ceq6vulk', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:46'),
(369, 116, '195.199.251.129', '05muet3lpr54ja11o4ceq6vulk', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:47'),
(370, 117, '195.199.251.129', '05muet3lpr54ja11o4ceq6vulk', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:48'),
(371, 118, '195.199.251.129', '05muet3lpr54ja11o4ceq6vulk', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:49'),
(372, 119, '195.199.251.129', '05muet3lpr54ja11o4ceq6vulk', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:50'),
(373, 120, '195.199.251.129', '05muet3lpr54ja11o4ceq6vulk', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:51'),
(374, 121, '195.199.251.129', '05muet3lpr54ja11o4ceq6vulk', 'Egyéb', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-11 12:43:52'),
(400, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'profil_modositas', 'sikeres', 'jelszo', '***', '***', 'profil', 'normal', '2025-11-11 13:19:40'),
(401, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'profil_modositas', 'sikeres', 'fnev', 'Fawkes', 'Gernya', 'profil', 'normal', '2025-11-11 13:19:40'),
(413, 1, '89.134.19.231', 'p5h62407i8e5bugk6scdjlqthv', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 14:50:18'),
(414, 7, '145.236.244.237', 'elk6r1d77r6o2dcj97hv1pajct', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 15:25:09'),
(416, 1, '89.134.19.231', 'i1agvj9vfrniel0sji4lih65ja', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 16:04:45'),
(417, 1, '89.134.19.231', 'p5h62407i8e5bugk6scdjlqthv', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 16:28:21'),
(418, 1, '89.134.19.231', 'p5h62407i8e5bugk6scdjlqthv', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 16:28:32'),
(419, 1, '89.134.19.231', 'bvrr4b4h1kun589enjiulqb99m', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 20:11:22'),
(420, 1, '89.134.19.231', 'bvrr4b4h1kun589enjiulqb99m', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-11 20:14:39'),
(421, 122, '195.199.251.129', 'imnsp2ttae8a3cug3ds64nevfm', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-12 13:01:08'),
(422, 122, '195.199.251.129', 'imnsp2ttae8a3cug3ds64nevfm', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-12 13:01:25'),
(423, 122, '195.199.251.129', 'imnsp2ttae8a3cug3ds64nevfm', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-12 13:01:42'),
(424, 122, '195.199.251.129', 'imnsp2ttae8a3cug3ds64nevfm', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-12 13:01:48'),
(425, 1, '195.199.251.129', '2ic1gcaeene9efqan53edcor6n', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-12 13:07:28'),
(426, 1, '195.199.251.129', '2ic1gcaeene9efqan53edcor6n', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-12 13:08:17'),
(427, 1, '195.199.251.129', '2ic1gcaeene9efqan53edcor6n', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-12 13:11:45'),
(428, 1, '195.199.251.129', '2ic1gcaeene9efqan53edcor6n', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-12 13:11:53'),
(429, 1, '195.199.251.129', '2ic1gcaeene9efqan53edcor6n', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-12 13:25:07'),
(430, 10, '86.101.78.241', 'b99ddeem5j3v5vgtd3fvo2rs6m', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-12 13:40:06'),
(431, 10, '86.101.78.241', 'b99ddeem5j3v5vgtd3fvo2rs6m', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-12 13:40:19'),
(432, 10, '86.101.78.241', 'b99ddeem5j3v5vgtd3fvo2rs6m', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-12 13:40:24'),
(433, 10, '86.101.78.241', 'spoiv7upopt7er4pe7d7d601qn', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-12 13:40:39'),
(434, 10, '86.101.78.241', 'b99ddeem5j3v5vgtd3fvo2rs6m', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-12 13:40:49'),
(435, 4, '89.134.31.146', '0989ad0eanvlgpr8ml7jh8tqgk', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-12 17:39:37'),
(436, 4, '89.134.31.146', '0989ad0eanvlgpr8ml7jh8tqgk', 'Chrome', 'profil_modositas', 'sikeres', 'varos', 'üres', 'Budapest', 'profil', 'normal', '2025-11-12 17:40:07'),
(437, 7, '195.199.251.129', 'uh3p9pkf24mhgdsj5datubfbg2', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-13 07:09:50'),
(438, 7, '195.199.251.129', 'uh3p9pkf24mhgdsj5datubfbg2', 'Chrome', 'profil_modositas', 'sikeres', 'jelszo', '***', '***', 'profil', 'normal', '2025-11-13 07:12:55'),
(439, 7, '195.199.251.129', 'uh3p9pkf24mhgdsj5datubfbg2', 'Chrome', 'profil_modositas', 'sikeres', 'varos', 'Budapest XXI.ker', 'Budapest', 'profil', 'normal', '2025-11-13 07:12:55'),
(440, 7, '195.199.251.129', 'pb17boadfk3o1uhn0g2gutjch2', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-13 07:17:22'),
(441, 123, '84.225.115.127', 'dfmorcfk36bp9h1n526hv37ooo', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-13 20:24:49'),
(442, 123, '84.225.115.127', 'dfmorcfk36bp9h1n526hv37ooo', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-13 20:24:57'),
(443, 123, '84.225.115.127', 'dfmorcfk36bp9h1n526hv37ooo', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-13 20:25:28'),
(444, 123, '84.225.115.127', 'dfmorcfk36bp9h1n526hv37ooo', 'Chrome', 'profil_modositas', 'sikeres', 'varos', 'üres', 'Debrecen', 'profil', 'normal', '2025-11-13 20:28:50'),
(445, 7, '195.199.248.216', 'gjsemcusojfbi3j3ph9vg5avcl', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-14 09:19:32'),
(446, 1, '89.134.19.231', 'eu90i1c0h9s46pmuk8sm0brce4', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-14 16:07:48'),
(447, 1, '89.134.19.231', 'eu90i1c0h9s46pmuk8sm0brce4', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-14 16:13:06'),
(448, 1, '89.134.19.231', 'eu90i1c0h9s46pmuk8sm0brce4', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-14 16:16:10'),
(449, 1, '89.134.19.231', 'eu90i1c0h9s46pmuk8sm0brce4', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-14 16:16:32'),
(450, 7, '62.77.241.251', 'rig52lf8vkva07se27qt0ks8t8', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-16 17:43:07'),
(451, 1, '195.199.251.129', 'sup8co0t1b29705qe94hn4d6m1', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-17 09:52:39'),
(452, 4, '195.199.251.129', 'b6c7ku2imvngebf3g2jfm787j2', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-17 09:57:35'),
(453, 1, '195.199.251.129', 'sup8co0t1b29705qe94hn4d6m1', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-17 11:42:27'),
(454, 1, '195.199.251.129', 'sup8co0t1b29705qe94hn4d6m1', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-17 11:52:40'),
(455, 124, '195.199.251.129', 'mrboe5bdj6f1547ndf5gdh8gvg', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-17 12:06:44'),
(456, 124, '94.44.224.168', '9th67qjgnfb6jeebohahnndmc3', 'Safari', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-17 12:07:03'),
(457, 124, '195.199.251.129', 'mrboe5bdj6f1547ndf5gdh8gvg', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-17 12:07:31'),
(458, 4, '188.6.166.253', '7citnbjpcg97ficbu6elcgcja3', 'Safari', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-17 14:07:56'),
(459, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-17 18:15:45'),
(460, 4, '195.199.251.129', 'nu887mdk0eqhgrben49t8b5avh', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 09:05:53'),
(461, 1, '195.199.251.129', 'hfnqsstustmpknupjhtpdfl759', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 09:06:08'),
(462, 1, '195.199.251.129', 'hfnqsstustmpknupjhtpdfl759', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 09:09:42'),
(463, 125, '195.199.251.129', 'bs9t5tmpqc4np8hk77916i1mro', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-19 09:10:02'),
(464, 1, '195.199.251.129', 'hfnqsstustmpknupjhtpdfl759', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 09:11:27'),
(465, 126, '195.199.251.129', 'ktrt096jspbbte5phd36icvjru', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-19 09:12:42'),
(466, 126, '195.199.251.129', 'ktrt096jspbbte5phd36icvjru', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-19 09:12:57'),
(467, 126, '195.199.251.129', 'ktrt096jspbbte5phd36icvjru', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 09:14:08'),
(468, 1, '89.134.19.231', 'dqtkfl0nh573il59bdstiqs3jm', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 14:37:34'),
(469, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 14:57:31'),
(470, 1, '89.134.19.231', '4ldbfusfbs3l99h08c8enr4nak', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 15:09:54'),
(471, 7, '5.38.217.250', 'v54cdmjncoh2r6m9ngsok2vba4', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 15:23:33'),
(472, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'varos', 'Budapest', 'Békéscsaba', 'profil', 'normal', '2025-11-19 15:33:45'),
(473, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'varos', 'Békéscsaba', 'Budapest', 'profil', 'normal', '2025-11-19 15:35:11'),
(474, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'profilkep', 'Daniel_2025-11-04_11-01-41.png', 'Ikelos_2025-11-19_16-36-06.png', 'profil', 'normal', '2025-11-19 15:36:06'),
(475, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'profilkep', 'Ikelos_2025-11-19_16-36-06.png', 'Ikelos_2025-11-19_16-43-33.jpg', 'profil', 'normal', '2025-11-19 15:43:33'),
(476, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'profilkep', 'Ikelos_2025-11-19_16-43-33.jpg', 'Ikelos_2025-11-19_16-43-37.png', 'profil', 'normal', '2025-11-19 15:43:37'),
(477, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'profilkep', 'Ikelos_2025-11-19_16-43-37.png', 'Ikelos_2025-11-19_16-47-38.png', 'profil', 'normal', '2025-11-19 15:47:38'),
(478, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'profilkep', 'Ikelos_2025-11-19_16-47-38.png', 'Ikelos_2025-11-19_17-22-40.jpg', 'profil', 'normal', '2025-11-19 16:22:40'),
(479, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'profilkep', 'Ikelos_2025-11-19_17-22-40.jpg', 'Ikelos_2025-11-19_17-41-32.png', 'profil', 'normal', '2025-11-19 16:41:32'),
(480, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 16:42:22'),
(481, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 16:52:23'),
(482, 1, '89.134.19.231', 'd476hc5l6gehfn4a8ecb8u0bdt', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 16:53:01'),
(483, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'profilkep', 'Ikelos_2025-11-19_17-41-32.png', 'Ikelos_2025-11-19_17-53-26.jpg', 'profil', 'normal', '2025-11-19 16:53:26'),
(484, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'profilkep', 'Ikelos_2025-11-19_17-53-26.jpg', 'Ikelos_2025-11-19_17-58-21.png', 'profil', 'normal', '2025-11-19 16:58:21'),
(485, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'varos', 'Budapest', 'asd', 'profil', 'normal', '2025-11-19 17:02:51'),
(486, 1, '89.134.19.231', 'd476hc5l6gehfn4a8ecb8u0bdt', 'Chrome', 'profil_modositas', 'sikeres', 'varos', 'asd', 'Dsa', 'profil', 'normal', '2025-11-19 17:03:24'),
(487, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'varos', 'Dsa', 'Budapest', 'profil', 'normal', '2025-11-19 17:16:52'),
(488, 1, '89.134.19.231', 'd476hc5l6gehfn4a8ecb8u0bdt', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 17:29:57'),
(489, 1, '89.134.19.231', 'd476hc5l6gehfn4a8ecb8u0bdt', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 17:30:05'),
(490, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'profilkep', 'Ikelos_2025-11-19_17-58-21.png', 'Ikelos_2025-11-19_19-18-05.jpg', 'profil', 'normal', '2025-11-19 18:18:05'),
(491, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'profilkep', 'Ikelos_2025-11-19_19-18-05.jpg', 'Ikelos_2025-11-19_19-18-09.jpg', 'profil', 'normal', '2025-11-19 18:18:09'),
(492, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'profilkep', 'Ikelos_2025-11-19_19-18-09.jpg', 'Ikelos_2025-11-19_19-18-10.jpg', 'profil', 'normal', '2025-11-19 18:18:10'),
(493, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'profilkep', 'Ikelos_2025-11-19_19-18-10.jpg', 'Ikelos_2025-11-19_19-19-18.png', 'profil', 'normal', '2025-11-19 18:19:18'),
(494, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'profilkep', 'Ikelos_2025-11-19_19-19-18.png', 'Ikelos_2025-11-19_19-24-52.jpg', 'profil', 'normal', '2025-11-19 18:24:52'),
(495, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'profilkep', 'Ikelos_2025-11-19_19-24-52.jpg', 'Ikelos_2025-11-19_19-25-56.png', 'profil', 'normal', '2025-11-19 18:25:56'),
(496, 1, '89.134.19.231', 'd476hc5l6gehfn4a8ecb8u0bdt', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 18:29:36'),
(497, 1, '89.134.19.231', 'd476hc5l6gehfn4a8ecb8u0bdt', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 18:29:45'),
(498, 4, '188.6.166.253', 'aei70s9jbokh5aheum3qnqpfv6', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 18:52:34'),
(499, 1, '89.134.19.231', 'eg4dtrag1f9t46vqk5vv5gbkfk', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 18:58:24'),
(500, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'profilkep', 'Ikelos_2025-11-19_19-25-56.png', 'Ikelos_2025-11-19_20-45-01.png', 'profil', 'normal', '2025-11-19 19:45:01'),
(501, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'profilkep', 'Ikelos_2025-11-19_20-45-01.png', 'Ikelos_2025-11-19_20-54-29.png', 'profil', 'normal', '2025-11-19 19:54:29'),
(502, 1, '89.134.19.231', 'eg4dtrag1f9t46vqk5vv5gbkfk', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 19:58:56'),
(503, 1, '89.134.19.231', 'eg4dtrag1f9t46vqk5vv5gbkfk', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 19:59:04'),
(504, 1, '89.134.19.231', 'eg4dtrag1f9t46vqk5vv5gbkfk', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 20:04:54'),
(505, 1, '89.134.19.231', '5ceq1i7km6d6ubbsuap1mepdcg', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 20:08:53'),
(506, 1, '89.134.19.231', '5ceq1i7km6d6ubbsuap1mepdcg', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 20:09:22'),
(507, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 20:29:12'),
(508, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 20:29:19'),
(509, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 20:36:16'),
(510, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 20:36:22'),
(511, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', '', 'sikeres', 'admin_letrehozas', NULL, '4', 'egyeb', 'normal', '2025-11-19 20:36:37'),
(512, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', '', 'sikeres', 'felfuggesztes', NULL, '7', 'egyeb', 'normal', '2025-11-19 20:36:53'),
(513, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', '', 'sikeres', 'aktivitas', NULL, '7', 'egyeb', 'normal', '2025-11-19 20:37:09'),
(514, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', '', 'sikeres', 'torles', NULL, '37', 'egyeb', 'normal', '2025-11-19 20:37:48'),
(515, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', '', 'sikeres', 'torles', NULL, '37', 'egyeb', 'normal', '2025-11-19 20:37:56'),
(516, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', '', 'sikeres', 'torles', NULL, '37', 'egyeb', 'normal', '2025-11-19 20:37:59'),
(517, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', '', 'sikeres', 'torles', NULL, '37', 'egyeb', 'normal', '2025-11-19 20:38:20'),
(518, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', '', 'sikeres', 'admin_letrehozas', NULL, '37', 'egyeb', 'normal', '2025-11-19 20:39:14'),
(519, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 21:14:52'),
(523, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 21:16:51'),
(524, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 21:36:32'),
(527, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 21:37:09'),
(529, 1, '89.134.19.231', 'atc1rnksha9af1pgn1m0n590e4', 'Chrome', 'profil_modositas', 'sikeres', 'varos', 'Budapest', 'Debrecen', 'profil', 'normal', '2025-11-19 22:37:16'),
(530, 1, '89.134.19.231', '4evfm79andv8g143g45pdfnpho', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 22:45:32'),
(531, 1, '89.134.19.231', 'q5qk6upmksn131pg6ggj8n8ega', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 23:16:21'),
(532, 1, '89.134.19.231', 'q5qk6upmksn131pg6ggj8n8ega', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 23:17:43'),
(533, 1, '89.134.19.231', '4evfm79andv8g143g45pdfnpho', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-19 23:18:49'),
(534, 1, '195.199.251.129', '673qjn7fun2oumqu1mrgg38p8h', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-20 07:15:37'),
(535, 1, '195.199.251.129', '673qjn7fun2oumqu1mrgg38p8h', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-20 07:16:36'),
(536, 7, '195.199.251.129', 'ii6td97ilt42p6iheame600k2j', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-21 07:13:57'),
(537, 7, '195.199.251.129', 'as1s4i8l09vaahu8uqhdv3ahjb', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-21 07:44:47'),
(538, 1, '195.199.251.129', '6l5ngsdq90bv02nm84vg1t0afk', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-21 07:56:26'),
(539, 7, '195.199.251.129', 'as1s4i8l09vaahu8uqhdv3ahjb', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-21 07:57:00'),
(540, 7, '195.199.251.129', 'as1s4i8l09vaahu8uqhdv3ahjb', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-21 07:57:06'),
(541, 7, '195.199.251.129', 'po839tugrcdgilna6c7hacji1s', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-21 08:46:49'),
(542, 1, '195.199.251.129', 'sfavcip51v7iq84akoc3mo2sbm', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 07:31:58'),
(543, 7, '195.199.251.129', 'mtadvs5au0jgjg0vrvuej02po3', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 07:50:37'),
(544, 7, '195.199.248.177', 'gultf311jj4ajtsk1ifbv2svqj', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 08:06:31'),
(545, 1, '195.199.251.129', '7crta8qof9d7jg530ua2iogtfl', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 08:12:20'),
(546, 7, '5.38.220.46', 'cm9c6pgku2b3jnehunblpk5lfu', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 12:28:58'),
(547, 1, '89.134.19.231', 'laopq6vd9bd02nf4o1ev3hsoo9', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 15:18:11'),
(552, 7, '5.38.220.46', '1fh0nrk0lssil2a81vcdo3krqs', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 17:02:12'),
(570, 1, '89.134.19.231', 'laopq6vd9bd02nf4o1ev3hsoo9', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 20:31:20'),
(571, 1, '89.134.19.231', 'laopq6vd9bd02nf4o1ev3hsoo9', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 20:31:34'),
(575, 1, '89.134.19.231', 'laopq6vd9bd02nf4o1ev3hsoo9', 'Chrome', 'profil_modositas', 'sikeres', 'email', 'boros.daniel2003@gmail.com', 'h4nzoart@gmail.com', 'profil', 'normal', '2025-11-24 21:02:41'),
(576, 1, '89.134.19.231', 'laopq6vd9bd02nf4o1ev3hsoo9', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:03:41'),
(577, 1, '89.134.19.231', 'laopq6vd9bd02nf4o1ev3hsoo9', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:03:47'),
(578, 1, '89.134.19.231', 'laopq6vd9bd02nf4o1ev3hsoo9', 'Chrome', 'profil_modositas', 'sikeres', 'email', 'h4nzoart@gmail.com', 'boros.daniel2003@gmail.com', 'profil', 'normal', '2025-11-24 21:04:03'),
(579, 1, '89.134.19.231', 'laopq6vd9bd02nf4o1ev3hsoo9', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-24 21:04:33'),
(580, 1, '89.134.19.231', 'laopq6vd9bd02nf4o1ev3hsoo9', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:04:48'),
(581, 1, '89.134.19.231', 'laopq6vd9bd02nf4o1ev3hsoo9', 'Chrome', 'profil_modositas', 'sikeres', 'email', 'boros.daniel2003@gmail.com', 'h4nzoart@gmail.com', 'profil', 'normal', '2025-11-24 21:04:56'),
(582, 1, '89.134.19.231', 'laopq6vd9bd02nf4o1ev3hsoo9', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-24 21:05:31'),
(583, 1, '89.134.19.231', 'laopq6vd9bd02nf4o1ev3hsoo9', 'Chrome', 'profil_modositas', 'sikeres', 'email', 'h4nzoart@gmail.com', 'boros.daniel2003@gmail.com', 'profil', 'normal', '2025-11-24 21:05:49'),
(584, 1, '89.134.19.231', 'laopq6vd9bd02nf4o1ev3hsoo9', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-24 21:05:56'),
(585, 128, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-24 21:11:51'),
(586, 128, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-24 21:11:58'),
(587, 1, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:12:22'),
(588, 1, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:12:48'),
(589, 128, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:13:24'),
(590, 128, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:13:37'),
(591, 128, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:14:03'),
(592, 128, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:15:29'),
(593, 128, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:15:51'),
(594, 128, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:16:27'),
(595, 128, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:17:54'),
(596, 128, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:18:50'),
(597, 128, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-24 21:22:07'),
(598, 128, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:22:18'),
(599, 128, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-24 21:22:21'),
(600, 128, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-24 21:23:15'),
(601, 128, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:23:34'),
(602, 128, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:23:39'),
(603, 1, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:25:48'),
(604, 1, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:28:56'),
(605, 1, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:29:08'),
(606, 1, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:29:24'),
(607, 1, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:29:56'),
(608, 1, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:30:08'),
(609, 1, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:31:09'),
(610, 1, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:31:44'),
(611, 1, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:32:08'),
(612, 1, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:32:46'),
(613, 1, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:33:03'),
(614, 128, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:33:11'),
(615, 128, '89.134.19.231', 'ip75rkfc82kfkm5on3lh1p2lee', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-24 21:33:14'),
(616, 7, '195.199.251.129', 'nbepprkrf2hoj1p1eurai6urk2', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 08:58:51'),
(617, 9, '195.199.251.129', 'bt4dmvp4v5imaiprce1jetgvmt', 'Chrome', 'elfelejtett_jelszo', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-25 10:21:44'),
(618, 9, '195.199.251.129', 'bt4dmvp4v5imaiprce1jetgvmt', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 10:22:25'),
(619, 4, '195.199.251.129', 'o41k5df4hc680gdn9l451t9m66', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 11:02:54'),
(620, 128, '89.134.19.231', 'ad9di9qr8tuf40ug4s2pm9lvp9', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 14:07:26'),
(621, 128, '89.134.19.231', 'ad9di9qr8tuf40ug4s2pm9lvp9', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 14:07:30'),
(622, 1, '89.134.19.231', 'ad9di9qr8tuf40ug4s2pm9lvp9', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 14:07:34'),
(623, 128, '89.134.19.231', 's4fts0gj5k7jhd54so9it5pd4a', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 14:08:52'),
(624, 128, '89.134.19.231', 's4fts0gj5k7jhd54so9it5pd4a', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-25 14:08:55'),
(625, 128, '89.134.19.231', 's4fts0gj5k7jhd54so9it5pd4a', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-25 14:09:13'),
(626, 128, '89.134.19.231', 's4fts0gj5k7jhd54so9it5pd4a', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 14:09:25'),
(627, 128, '89.134.19.231', 's4fts0gj5k7jhd54so9it5pd4a', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 14:09:53'),
(628, 128, '89.134.19.231', 's4fts0gj5k7jhd54so9it5pd4a', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 14:10:00'),
(629, 128, '89.134.19.231', 's4fts0gj5k7jhd54so9it5pd4a', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 14:10:05'),
(630, 128, '89.134.19.231', 's4fts0gj5k7jhd54so9it5pd4a', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 14:13:30'),
(631, 128, '89.134.19.231', 's4fts0gj5k7jhd54so9it5pd4a', 'Chrome', 'uj_aktivacios_email', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-25 14:13:33'),
(632, 128, '89.134.19.231', 's4fts0gj5k7jhd54so9it5pd4a', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-11-25 14:13:51'),
(633, 128, '89.134.19.231', 's4fts0gj5k7jhd54so9it5pd4a', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 14:14:06'),
(634, 128, '89.134.19.231', 's4fts0gj5k7jhd54so9it5pd4a', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 14:14:19'),
(635, 7, '84.2.118.148', 'fl6sfai1mfmim9kg1dp8ons22r', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 15:17:45'),
(636, 128, '89.134.19.231', 'iun05rvhl1ajaue4fmhab6dcu4', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 15:19:40'),
(637, 128, '89.134.19.231', 'iun05rvhl1ajaue4fmhab6dcu4', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 15:20:09'),
(640, 1, '89.134.19.231', 'ad9di9qr8tuf40ug4s2pm9lvp9', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 17:41:56'),
(641, 1, '89.134.19.231', 'ad9di9qr8tuf40ug4s2pm9lvp9', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 17:42:16'),
(642, 128, '89.134.19.231', '8v9joepik5tqqrpp3h4rtercbi', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 17:45:44'),
(644, 128, '89.134.19.231', '8v9joepik5tqqrpp3h4rtercbi', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 17:46:47'),
(645, 128, '89.134.19.231', '8v9joepik5tqqrpp3h4rtercbi', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 17:47:02'),
(646, 1, '89.134.19.231', '8v9joepik5tqqrpp3h4rtercbi', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 17:47:13'),
(647, 1, '89.134.19.231', '8v9joepik5tqqrpp3h4rtercbi', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 18:17:40'),
(648, 128, '89.134.19.231', '8v9joepik5tqqrpp3h4rtercbi', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 18:18:02'),
(649, 1, '89.134.19.231', 'ad9di9qr8tuf40ug4s2pm9lvp9', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 19:16:10'),
(650, 1, '89.134.19.231', 'ad9di9qr8tuf40ug4s2pm9lvp9', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 19:16:19'),
(651, 1, '89.134.19.231', 'ad9di9qr8tuf40ug4s2pm9lvp9', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 19:16:41'),
(652, 1, '89.134.19.231', 'c7vqp0d5qkhr76gnhok0vc8qrr', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 19:45:52'),
(653, 1, '89.134.19.231', 'c7vqp0d5qkhr76gnhok0vc8qrr', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-25 19:47:14'),
(654, 1, '195.199.251.129', 'bg7agf2m6u7eud34rdn76oti60', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-26 12:38:44'),
(655, 1, '195.199.251.129', 'bg7agf2m6u7eud34rdn76oti60', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-26 12:40:59'),
(656, 1, '195.199.251.129', '5slb0jjvufl943s36oetm3rk7l', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-28 10:01:00'),
(657, 1, '195.199.251.129', '5slb0jjvufl943s36oetm3rk7l', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-28 10:01:23'),
(658, 128, '195.199.251.129', '5slb0jjvufl943s36oetm3rk7l', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-28 10:01:33'),
(659, 1, '195.199.251.129', '5slb0jjvufl943s36oetm3rk7l', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-28 10:01:49'),
(660, 1, '195.199.251.129', '5slb0jjvufl943s36oetm3rk7l', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-28 10:02:24'),
(661, 1, '195.199.251.129', '3ahaqaqd3k1aod03e1npvsfmnb', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-28 11:18:11'),
(662, 1, '195.199.251.129', '3ahaqaqd3k1aod03e1npvsfmnb', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-28 12:25:28'),
(663, 1, '89.134.20.131', '1enavvrm7r1u7bhetc48ah5pfv', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-28 16:39:01'),
(664, 1, '89.134.20.131', '1enavvrm7r1u7bhetc48ah5pfv', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-11-28 16:39:32'),
(665, 7, '195.199.251.129', 'o1quod42fbunqs202ikus1av5v', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 09:45:25'),
(666, 1, '195.199.251.129', 'k5r3aq1qqc70479oj8kgnt1tb9', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 09:46:28'),
(667, 1, '195.199.251.129', 'k5r3aq1qqc70479oj8kgnt1tb9', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 09:50:42'),
(668, 1, '195.199.251.129', '0ovo8q9t927isj8cphrjcjv1me', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 09:56:33'),
(669, 7, '195.199.251.129', 'mjlce6qondfnik8p3ljviprns1', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 09:58:27'),
(670, 7, '195.199.251.129', 'mjlce6qondfnik8p3ljviprns1', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 09:58:56'),
(671, 1, '84.225.114.23', 'm36lt5t1tq1o1o5gioefckqeg5', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:00:25'),
(672, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:03:32'),
(673, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:05:05'),
(674, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:05:09'),
(675, 4, '195.199.251.129', 'i85vgualdslqi9fb8hk5d6luj9', 'Chrome', 'bejelentkezes', 'sikertelen', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:09:18'),
(676, 4, '195.199.251.129', 'i85vgualdslqi9fb8hk5d6luj9', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:11:30'),
(677, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'profil_modositas', 'sikeres', 'reszletek', 'Buzi vagyok', 'Dani vagyok jól dolgozom!', 'profil', 'normal', '2025-12-01 10:24:10'),
(678, 1, '37.76.12.4', '9e9vca6so2esebb8tpj40k068u', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:30:59'),
(679, 1, '37.76.12.4', '9e9vca6so2esebb8tpj40k068u', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:32:06'),
(680, 1, '37.76.12.4', '9e9vca6so2esebb8tpj40k068u', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:32:17'),
(681, 1, '84.225.160.153', 'l6s287ne6debelqkbskn4qbv7t', 'Safari', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:33:15'),
(682, 7, '195.199.248.235', 'k1ckaam6t4ffrg5v5bmpdm002a', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:34:56'),
(683, 1, '37.76.12.4', '9e9vca6so2esebb8tpj40k068u', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:35:22'),
(684, 1, '37.76.12.4', '9e9vca6so2esebb8tpj40k068u', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:35:45'),
(685, 1, '37.76.12.4', '4mh35naq42esdm4em002hoqsuc', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:38:44');
INSERT INTO `felhasznalo_tevekenyseg` (`ftid`, `fid`, `ip`, `session_id`, `bongeszo`, `tevekenyseg`, `sikeresseg`, `modositott_mezo`, `regi_ertek`, `uj_ertek`, `kategoria`, `prioritas`, `idopont`) VALUES
(686, 1, '84.225.114.23', '4mh35naq42esdm4em002hoqsuc', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:43:21'),
(687, 1, '84.225.114.23', '4mh35naq42esdm4em002hoqsuc', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:43:29'),
(688, 1, '37.76.12.4', '4mh35naq42esdm4em002hoqsuc', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:44:10'),
(689, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:44:21'),
(690, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:44:30'),
(691, 1, '37.76.12.4', '2sc3c5pfv9qlp3j9fc9jsk4rt5', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:48:19'),
(692, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:59:52'),
(693, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 10:59:57'),
(694, 1, '37.76.12.4', '2sc3c5pfv9qlp3j9fc9jsk4rt5', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 11:00:08'),
(695, 1, '37.76.12.4', '2sc3c5pfv9qlp3j9fc9jsk4rt5', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 11:00:36'),
(696, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 11:16:05'),
(697, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 11:16:36'),
(698, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'profil_modositas', 'sikeres', 'reszletek', 'Dani vagyok jól dolgozom!', 'üres', 'profil', 'normal', '2025-12-01 11:46:38'),
(699, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'profil_modositas', 'sikeres', 'nem', 'no', 'ferfi', 'profil', 'normal', '2025-12-01 11:47:15'),
(700, 124, '195.199.251.129', 'a74t55ufnulqtcs9v36due6fu8', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 11:51:04'),
(701, 124, '195.199.251.129', 'a74t55ufnulqtcs9v36due6fu8', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 12:04:17'),
(702, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'profil_modositas', 'sikeres', 'nem', 'ferfi', 'no', 'profil', 'normal', '2025-12-01 12:04:43'),
(703, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'profil_modositas', 'sikeres', 'fnev', 'Ikelos', 'Ikelo$', 'profil', 'normal', '2025-12-01 12:04:43'),
(704, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'profil_modositas', 'sikeres', 'knev', 'Dániel', 'Dani', 'profil', 'normal', '2025-12-01 12:04:43'),
(705, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'profil_modositas', 'sikeres', 'vnev', 'Boros', 'Boro', 'profil', 'normal', '2025-12-01 12:04:43'),
(706, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', '2005-11-20', '2005-10-20', 'profil', 'normal', '2025-12-01 12:04:43'),
(707, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'profil_modositas', 'sikeres', 'telefon', '06307367201', '06307367200', 'profil', 'normal', '2025-12-01 12:04:43'),
(708, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'profil_modositas', 'sikeres', 'varmegye', 'Budapest', 'Bács-Kiskun', 'profil', 'normal', '2025-12-01 12:04:43'),
(709, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'profil_modositas', 'sikeres', 'nem', 'no', 'ferfi', 'profil', 'normal', '2025-12-01 12:05:21'),
(710, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'profil_modositas', 'sikeres', 'fnev', 'Ikelo$', 'Ikelos', 'profil', 'normal', '2025-12-01 12:05:21'),
(711, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'profil_modositas', 'sikeres', 'knev', 'Dani', 'Dániel', 'profil', 'normal', '2025-12-01 12:05:21'),
(712, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'profil_modositas', 'sikeres', 'vnev', 'Boro', 'Boros', 'profil', 'normal', '2025-12-01 12:05:21'),
(713, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'profil_modositas', 'sikeres', 'szuletett', '2005-10-20', '2005-11-20', 'profil', 'normal', '2025-12-01 12:05:21'),
(714, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'profil_modositas', 'sikeres', 'telefon', '06307367200', '06307367201', 'profil', 'normal', '2025-12-01 12:05:21'),
(715, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'profil_modositas', 'sikeres', 'varmegye', 'Bács-Kiskun', 'Budapest', 'profil', 'normal', '2025-12-01 12:05:21'),
(716, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'profil_modositas', 'sikeres', 'reszletek', 'üres', 'Dani vagyok 10.000.000 FT-ért dolgozok.', 'profil', 'normal', '2025-12-01 12:07:46'),
(717, 7, '195.199.248.224', 'utuolduplj357vo1u274k48u6i', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 12:09:13'),
(718, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'profil_modositas', 'sikeres', 'reszletek', 'Dani vagyok 10.000.000 FT-ért dolgozok.', 'Dani vagyok 10.000.000 FT-ért dolgozok.\r\n- Naponta 5 rekesz sör (Önök állják).', 'profil', 'normal', '2025-12-01 12:10:28'),
(719, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 12:15:29'),
(720, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 12:15:39'),
(721, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 12:15:51'),
(722, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 12:15:59'),
(723, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 12:17:39'),
(724, 7, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 12:18:26'),
(725, 7, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 12:18:56'),
(726, 1, '195.199.251.129', '8cjq6j3go341e9h0oj9nefeheb', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 12:19:11'),
(727, 124, '195.199.251.129', 'a74t55ufnulqtcs9v36due6fu8', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 12:19:57'),
(728, 4, '195.199.251.129', 'i85vgualdslqi9fb8hk5d6luj9', 'Chrome', 'profil_modositas', 'sikeres', 'varmegye', 'Budapest', 'üres', 'profil', 'normal', '2025-12-01 12:21:19'),
(729, 4, '195.199.251.129', 'i85vgualdslqi9fb8hk5d6luj9', 'Chrome', 'profil_modositas', 'sikeres', 'reszletek', 'üres', 'Józsika lánya vagyok', 'profil', 'normal', '2025-12-01 12:21:19'),
(730, 7, '5.38.218.119', 'uvgeffqqnv8citj2jgs9eta5nu', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-01 15:23:54'),
(731, 1, '195.199.251.129', 'smfunln6ordaq417gr8hfd8ipg', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 08:01:01'),
(732, 7, '195.199.251.129', '3hdcb7bqgn0tl4jrt92rve5p88', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 08:56:16'),
(733, 4, '195.199.251.129', 'm0muricr8had4pbee8nhs1s2vd', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 08:57:26'),
(734, 4, '195.199.251.129', 'm0muricr8had4pbee8nhs1s2vd', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:14:13'),
(735, 4, '195.199.251.129', 'm0muricr8had4pbee8nhs1s2vd', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:14:29'),
(736, 1, '195.199.251.129', 'r7tooqvf3d351lp3ns8cel4u19', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:20:18'),
(737, 1, '195.199.251.129', 'r7tooqvf3d351lp3ns8cel4u19', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:21:10'),
(738, 1, '195.199.251.129', 'r7tooqvf3d351lp3ns8cel4u19', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:21:20'),
(739, 1, '195.199.251.129', 'r7tooqvf3d351lp3ns8cel4u19', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:21:47'),
(740, 128, '195.199.251.129', 'r7tooqvf3d351lp3ns8cel4u19', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:21:53'),
(741, 128, '195.199.251.129', 'r7tooqvf3d351lp3ns8cel4u19', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:22:07'),
(742, 1, '195.199.251.129', 'r7tooqvf3d351lp3ns8cel4u19', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:22:12'),
(743, 7, '195.199.251.129', '3hdcb7bqgn0tl4jrt92rve5p88', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:24:46'),
(744, 129, '195.199.251.129', '3hdcb7bqgn0tl4jrt92rve5p88', 'Chrome', 'regisztracio', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-12-02 09:25:19'),
(745, 129, '195.199.251.129', '3hdcb7bqgn0tl4jrt92rve5p88', 'Chrome', 'email_megerosites', 'sikeres', NULL, NULL, NULL, 'biztonsag', 'normal', '2025-12-02 09:25:57'),
(746, 129, '195.199.251.129', '3hdcb7bqgn0tl4jrt92rve5p88', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:26:41'),
(747, 129, '195.199.251.129', '3hdcb7bqgn0tl4jrt92rve5p88', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:27:42'),
(748, 7, '195.199.251.129', '3hdcb7bqgn0tl4jrt92rve5p88', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:28:15'),
(749, 4, '195.199.251.129', 'm0muricr8had4pbee8nhs1s2vd', 'Chrome', 'munka_feltoltes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-12-02 09:33:41'),
(750, 4, '195.199.251.129', 'm0muricr8had4pbee8nhs1s2vd', 'Chrome', 'munka_feltoltes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-12-02 09:34:49'),
(751, 4, '195.199.251.129', 'm0muricr8had4pbee8nhs1s2vd', 'Chrome', 'munka_feltoltes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-12-02 09:39:11'),
(752, 4, '195.199.251.129', 'm0muricr8had4pbee8nhs1s2vd', 'Chrome', 'munka_feltoltes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-12-02 09:39:28'),
(753, 4, '195.199.251.129', 'm0muricr8had4pbee8nhs1s2vd', 'Chrome', 'munka_feltoltes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-12-02 09:40:29'),
(754, 4, '195.199.251.129', 'm0muricr8had4pbee8nhs1s2vd', 'Chrome', 'munka_feltoltes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-12-02 09:40:42'),
(755, 4, '195.199.251.129', 'm0muricr8had4pbee8nhs1s2vd', 'Chrome', 'munka_feltoltes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-12-02 09:40:55'),
(756, 4, '195.199.251.129', 'm0muricr8had4pbee8nhs1s2vd', 'Chrome', 'munka_feltoltes', 'sikeres', NULL, NULL, '2', 'egyeb', 'normal', '2025-12-02 09:42:14'),
(757, 4, '195.199.251.129', 'm0muricr8had4pbee8nhs1s2vd', 'Chrome', 'munka_feltoltes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-12-02 09:42:33'),
(758, 4, '195.199.251.129', 'm0muricr8had4pbee8nhs1s2vd', 'Chrome', 'munka_feltoltes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-12-02 09:43:45'),
(759, 7, '195.199.251.129', '3hdcb7bqgn0tl4jrt92rve5p88', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:48:01'),
(760, 129, '195.199.251.129', '3hdcb7bqgn0tl4jrt92rve5p88', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:48:11'),
(761, 129, '195.199.251.129', '3hdcb7bqgn0tl4jrt92rve5p88', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:49:06'),
(762, 7, '195.199.251.129', '3hdcb7bqgn0tl4jrt92rve5p88', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:49:42'),
(763, 7, '195.199.251.129', '3hdcb7bqgn0tl4jrt92rve5p88', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:50:09'),
(764, 129, '195.199.251.129', '3hdcb7bqgn0tl4jrt92rve5p88', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:50:32'),
(765, 129, '195.199.251.129', '3hdcb7bqgn0tl4jrt92rve5p88', 'Chrome', 'kijelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:51:56'),
(766, 7, '195.199.251.129', '3hdcb7bqgn0tl4jrt92rve5p88', 'Chrome', 'bejelentkezes', 'sikeres', NULL, NULL, NULL, 'bejelentkezes', 'normal', '2025-12-02 09:52:15'),
(767, 4, '195.199.251.129', 'm0muricr8had4pbee8nhs1s2vd', 'Chrome', 'munka_feltoltes', 'sikertelen', NULL, NULL, NULL, 'egyeb', 'normal', '2025-12-02 09:53:27');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `jelszo_visszaallitasok`
--

CREATE TABLE `jelszo_visszaallitasok` (
  `jvid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `felhasznalva` tinyint(4) DEFAULT 0,
  `lejarati_ido` timestamp NOT NULL,
  `idopont` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci;

--
-- A tábla adatainak kiíratása `jelszo_visszaallitasok`
--

INSERT INTO `jelszo_visszaallitasok` (`jvid`, `fid`, `token`, `felhasznalva`, `lejarati_ido`, `idopont`) VALUES
(5, 9, 'ce75d03884d0ef6105f4e7d5a3a912f842c351f784e92a3beba21858e1c604e6', 0, '2025-11-25 11:21:43', '2025-11-25 10:21:43');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `kategoriak`
--

CREATE TABLE `kategoriak` (
  `id` int(11) NOT NULL,
  `kategoria_nev` varchar(50) NOT NULL,
  `aktiv` tinyint(1) DEFAULT 1,
  `letrehozas_datuma` timestamp NULL DEFAULT current_timestamp(),
  `felhasznalo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci;

--
-- A tábla adatainak kiíratása `kategoriak`
--

INSERT INTO `kategoriak` (`id`, `kategoria_nev`, `aktiv`, `letrehozas_datuma`, `felhasznalo_id`) VALUES
(1, 'Locsolás', 1, '2025-11-17 10:12:36', NULL),
(2, 'Fűnyírás', 1, '2025-11-17 10:12:36', NULL),
(3, 'Festés', 1, '2025-11-17 10:12:36', NULL),
(4, 'Bútorösszeszerelés', 1, '2025-11-17 10:12:36', NULL),
(5, 'Kertészkedés', 1, '2025-11-17 10:12:36', NULL),
(6, 'Takartás', 1, '2025-11-17 10:12:36', NULL),
(7, 'Szállítás', 1, '2025-11-17 10:12:36', NULL),
(8, 'Állatgondozás', 1, '2025-11-17 10:12:36', NULL),
(9, 'Számítástechnika', 1, '2025-11-17 10:12:36', NULL),
(10, 'Egyéb', 1, '2025-11-17 10:12:36', NULL);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `munkak`
--

CREATE TABLE `munkak` (
  `id` int(11) NOT NULL,
  `felhasznalo_id` int(11) NOT NULL,
  `munka_nev` varchar(100) NOT NULL,
  `munka_leiras` text DEFAULT NULL,
  `ar` decimal(10,2) DEFAULT NULL,
  `telefonszam` varchar(30) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `datum_ido` datetime NOT NULL,
  `ertekeles` decimal(3,2) DEFAULT 0.00,
  `letrehozas_datuma` timestamp NULL DEFAULT current_timestamp(),
  `aktiv` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci;

--
-- A tábla adatainak kiíratása `munkak`
--

INSERT INTO `munkak` (`id`, `felhasznalo_id`, `munka_nev`, `munka_leiras`, `ar`, `telefonszam`, `email`, `datum_ido`, `ertekeles`, `letrehozas_datuma`, `aktiv`) VALUES
(1, 7, 'Professzionális locsolás', 'Pesten belül bárhol locsolok. 19 éves vagyok, megbízható munkát vállalok.', 100.00, NULL, NULL, '0000-00-00 00:00:00', 5.00, '2025-11-11 10:45:37', 1),
(2, 4, 'Kurva', 'gpijfgdosihpfmjhfődsgololololololp', 1000.00, '+36308681314', 'hencsey.dorina@gmail.com', '2025-12-03 11:38:00', 0.00, '2025-12-02 09:42:14', 1),
(3, 4, 'Kurva', 'hjxcolkjghghghghghdsnmlnlksdfksdafasdflé', 12000.00, '+36308681314', NULL, '2025-12-03 11:42:00', 0.00, '2025-12-02 09:42:33', 1),
(5, 4, 'Kurva', 'hjxcolkjghghghghghdsnmlnlksdfksdafasdflé', 12000.00, '+36308681314', NULL, '2025-12-03 11:42:00', 0.00, '2025-12-02 09:43:45', 1),
(6, 4, 'Kurva', 'hjxcolkjghghghghghdsnmlnlksdfksdafasdflé', 12000.00, '+36308681314', NULL, '2025-12-03 11:42:00', 0.00, '2025-12-02 09:53:27', 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `munkak_feltoltes`
--

CREATE TABLE `munkak_feltoltes` (
  `id` int(11) NOT NULL,
  `felhasznalo_id` int(11) NOT NULL,
  `munka_nev` varchar(255) NOT NULL,
  `munka_leiras` text NOT NULL,
  `ar` decimal(10,2) NOT NULL,
  `telefonszam` varchar(30) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `datum_ido` datetime NOT NULL,
  `aktiv` tinyint(1) DEFAULT 1,
  `ertekeles` int(11) DEFAULT 0,
  `letrehozas_datuma` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `munka_kategoriak`
--

CREATE TABLE `munka_kategoriak` (
  `id` int(11) NOT NULL,
  `felhasznalo_id` int(11) NOT NULL,
  `kategoria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci;

--
-- A tábla adatainak kiíratása `munka_kategoriak`
--

INSERT INTO `munka_kategoriak` (`id`, `felhasznalo_id`, `kategoria_id`) VALUES
(1, 7, 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `referencia_kepek`
--

CREATE TABLE `referencia_kepek` (
  `id` int(11) NOT NULL,
  `munka_id` int(11) NOT NULL,
  `kategoria_id` int(11) NOT NULL,
  `kep_url` varchar(255) NOT NULL,
  `kep_leiras` varchar(255) DEFAULT NULL,
  `sorrend` int(11) DEFAULT 0,
  `fo_kep` tinyint(1) DEFAULT 0,
  `feltoltes_datuma` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci;

--
-- A tábla adatainak kiíratása `referencia_kepek`
--

INSERT INTO `referencia_kepek` (`id`, `munka_id`, `kategoria_id`, `kep_url`, `kep_leiras`, `sorrend`, `fo_kep`, `feltoltes_datuma`) VALUES
(7, 7, 1, 'assets/images/Job_image/kulteri/elso.jpg\r\n', 'Locsolási referencia munka', 0, 1, '2025-11-17 10:26:44');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `torlesi_naplo`
--

CREATE TABLE `torlesi_naplo` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `torleste_user_id` int(11) NOT NULL,
  `torolt_user_id` int(11) NOT NULL,
  `torles_tipusa` enum('sajat','admin') DEFAULT 'sajat',
  `torles_datuma` timestamp NULL DEFAULT current_timestamp(),
  `megjegyzes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci;

--
-- A tábla adatainak kiíratása `torlesi_naplo`
--

INSERT INTO `torlesi_naplo` (`id`, `comment_id`, `torleste_user_id`, `torolt_user_id`, `torles_tipusa`, `torles_datuma`, `megjegyzes`) VALUES
(1, 23, 7, 124, 'admin', '2025-12-02 09:19:41', 'Admin törlés'),
(2, 16, 7, 1, 'admin', '2025-12-02 09:19:49', 'Admin törlés'),
(3, 16, 7, 1, 'admin', '2025-12-02 09:20:37', 'Admin törlés'),
(4, 24, 7, 1, 'admin', '2025-12-02 09:20:39', 'Admin törlés'),
(5, 28, 7, 129, '', '2025-12-02 09:49:54', 'Admin általi törlés'),
(6, 29, 129, 129, '', '2025-12-02 09:50:55', 'Felhasználó saját törlése'),
(7, 30, 129, 129, '', '2025-12-02 09:51:21', 'Új értékelés felülírta a régit');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `torolt_felhasznalok`
--

CREATE TABLE `torolt_felhasznalok` (
  `fid` int(11) NOT NULL,
  `szerep` enum('felhasznalo','admin') NOT NULL DEFAULT 'felhasznalo',
  `statusz` enum('Aktiv','Fuggoben','Kitiltott') NOT NULL DEFAULT 'Aktiv',
  `nem` enum('ferfi','no','egyeb','nem_publikus') NOT NULL DEFAULT 'nem_publikus',
  `email` varchar(255) NOT NULL,
  `fnev` varchar(30) NOT NULL,
  `jelszo` varchar(255) NOT NULL,
  `knev` varchar(30) DEFAULT NULL,
  `vnev` varchar(30) DEFAULT NULL,
  `profilkep` varchar(255) DEFAULT NULL,
  `szuletett` date DEFAULT NULL,
  `telefon` varchar(30) DEFAULT NULL,
  `varmegye` varchar(50) DEFAULT NULL,
  `reszletek` text DEFAULT NULL,
  `regisztralt` timestamp NOT NULL DEFAULT current_timestamp(),
  `modositott` timestamp NOT NULL DEFAULT current_timestamp(),
  `belepett` timestamp NULL DEFAULT NULL,
  `statusz_ok` text DEFAULT NULL,
  `statusz_meddig` timestamp NULL DEFAULT NULL,
  `torles_idopontja` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `torolt_felhasznalok`
--

INSERT INTO `torolt_felhasznalok` (`fid`, `szerep`, `statusz`, `nem`, `email`, `fnev`, `jelszo`, `knev`, `vnev`, `profilkep`, `szuletett`, `telefon`, `varmegye`, `reszletek`, `regisztralt`, `modositott`, `belepett`, `statusz_ok`, `statusz_meddig`, `torles_idopontja`) VALUES
(127, 'felhasznalo', 'Fuggoben', 'nem_publikus', 'h4nzoart@gmail.com', 'Hanzo', '$2y$10$/CFKAmMI2AoTAUQFmlLm3.cHZEGRsPkM14x./SyBVJw25YM8ev7SS', NULL, NULL, 'default.png', NULL, NULL, NULL, NULL, '2025-11-19 21:15:54', '2025-11-24 19:33:10', '2025-11-24 17:09:31', '', '0000-00-00 00:00:00', '2025-11-24 21:02:23');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `admin_tevekenyseg`
--
ALTER TABLE `admin_tevekenyseg`
  ADD PRIMARY KEY (`atid`);

--
-- A tábla indexei `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `munka_id` (`munka_id`),
  ADD KEY `torolte_user_id` (`torolte_user_id`);

--
-- A tábla indexei `email_ell`
--
ALTER TABLE `email_ell`
  ADD PRIMARY KEY (`emid`);

--
-- A tábla indexei `felhasznalok`
--
ALTER TABLE `felhasznalok`
  ADD PRIMARY KEY (`fid`);

--
-- A tábla indexei `felhasznalo_tevekenyseg`
--
ALTER TABLE `felhasznalo_tevekenyseg`
  ADD PRIMARY KEY (`ftid`);

--
-- A tábla indexei `jelszo_visszaallitasok`
--
ALTER TABLE `jelszo_visszaallitasok`
  ADD PRIMARY KEY (`jvid`);

--
-- A tábla indexei `kategoriak`
--
ALTER TABLE `kategoriak`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kategoria_nev` (`kategoria_nev`),
  ADD KEY `fk_kategoria_felhasznalo` (`felhasznalo_id`);

--
-- A tábla indexei `munkak`
--
ALTER TABLE `munkak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `felhasznalo_id` (`felhasznalo_id`);

--
-- A tábla indexei `munkak_feltoltes`
--
ALTER TABLE `munkak_feltoltes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_felhasznalo` (`felhasznalo_id`);

--
-- A tábla indexei `munka_kategoriak`
--
ALTER TABLE `munka_kategoriak`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_felhasznalo_kategoria` (`felhasznalo_id`,`kategoria_id`),
  ADD KEY `kategoria_id` (`kategoria_id`);

--
-- A tábla indexei `referencia_kepek`
--
ALTER TABLE `referencia_kepek`
  ADD PRIMARY KEY (`id`),
  ADD KEY `munka_id` (`munka_id`),
  ADD KEY `kategoria_id` (`kategoria_id`);

--
-- A tábla indexei `torlesi_naplo`
--
ALTER TABLE `torlesi_naplo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `torleste_user_id` (`torleste_user_id`),
  ADD KEY `torolt_user_id` (`torolt_user_id`);

--
-- A tábla indexei `torolt_felhasznalok`
--
ALTER TABLE `torolt_felhasznalok`
  ADD PRIMARY KEY (`fid`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `admin_tevekenyseg`
--
ALTER TABLE `admin_tevekenyseg`
  MODIFY `atid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT a táblához `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT a táblához `email_ell`
--
ALTER TABLE `email_ell`
  MODIFY `emid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT a táblához `felhasznalok`
--
ALTER TABLE `felhasznalok`
  MODIFY `fid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT a táblához `felhasznalo_tevekenyseg`
--
ALTER TABLE `felhasznalo_tevekenyseg`
  MODIFY `ftid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=768;

--
-- AUTO_INCREMENT a táblához `jelszo_visszaallitasok`
--
ALTER TABLE `jelszo_visszaallitasok`
  MODIFY `jvid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT a táblához `kategoriak`
--
ALTER TABLE `kategoriak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT a táblához `munkak`
--
ALTER TABLE `munkak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT a táblához `munkak_feltoltes`
--
ALTER TABLE `munkak_feltoltes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `munka_kategoriak`
--
ALTER TABLE `munka_kategoriak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT a táblához `referencia_kepek`
--
ALTER TABLE `referencia_kepek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT a táblához `torlesi_naplo`
--
ALTER TABLE `torlesi_naplo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `felhasznalok` (`fid`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`munka_id`) REFERENCES `munkak` (`id`),
  ADD CONSTRAINT `comments_ibfk_3` FOREIGN KEY (`torolte_user_id`) REFERENCES `felhasznalok` (`fid`);

--
-- Megkötések a táblához `kategoriak`
--
ALTER TABLE `kategoriak`
  ADD CONSTRAINT `fk_kategoria_felhasznalo` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalok` (`fid`) ON DELETE CASCADE;

--
-- Megkötések a táblához `munkak`
--
ALTER TABLE `munkak`
  ADD CONSTRAINT `munkak_ibfk_1` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalok` (`fid`) ON DELETE CASCADE;

--
-- Megkötések a táblához `munka_kategoriak`
--
ALTER TABLE `munka_kategoriak`
  ADD CONSTRAINT `munka_kategoriak_ibfk_1` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalok` (`fid`) ON DELETE CASCADE,
  ADD CONSTRAINT `munka_kategoriak_ibfk_2` FOREIGN KEY (`kategoria_id`) REFERENCES `kategoriak` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `referencia_kepek`
--
ALTER TABLE `referencia_kepek`
  ADD CONSTRAINT `referencia_kepek_ibfk_2` FOREIGN KEY (`kategoria_id`) REFERENCES `kategoriak` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `torlesi_naplo`
--
ALTER TABLE `torlesi_naplo`
  ADD CONSTRAINT `torlesi_naplo_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `torlesi_naplo_ibfk_2` FOREIGN KEY (`torleste_user_id`) REFERENCES `felhasznalok` (`fid`) ON DELETE CASCADE,
  ADD CONSTRAINT `torlesi_naplo_ibfk_3` FOREIGN KEY (`torolt_user_id`) REFERENCES `felhasznalok` (`fid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
