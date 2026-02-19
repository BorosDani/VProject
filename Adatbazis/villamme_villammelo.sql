-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Gép: localhost:3306
-- Létrehozás ideje: 2026. Feb 14. 15:20
-- Kiszolgáló verziója: 10.11.15-MariaDB-cll-lve
-- PHP verzió: 8.4.17

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
-- Tábla szerkezet ehhez a táblához `jelentkezesek`
--

CREATE TABLE `jelentkezesek` (
  `id` int(11) NOT NULL,
  `munka_id` int(11) NOT NULL,
  `felhasznalo_id` int(11) NOT NULL,
  `uzenet` text CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci DEFAULT NULL,
  `statusz` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci DEFAULT 'jelentkezett',
  `idopont` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Tábla szerkezet ehhez a táblához `kapcsolat_uzenet`
--

CREATE TABLE `kapcsolat_uzenet` (
  `kuid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `megvalaszolt` tinyint(1) DEFAULT 0,
  `nev` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `kategoria` varchar(50) NOT NULL DEFAULT 'altalanos',
  `uzenet` text NOT NULL,
  `idopont` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;


--
-- Tábla szerkezet ehhez a táblához `megjegyzesek`
--

CREATE TABLE `megjegyzesek` (
  `meid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `megjegyzes` text NOT NULL,
  `ertekeles` int(11) NOT NULL CHECK (`ertekeles` between 1 and 5),
  `idopont` timestamp NULL DEFAULT current_timestamp(),
  `dolgozo_id` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci;


--
-- Tábla szerkezet ehhez a táblához `munkak`
--

CREATE TABLE `munkak` (
  `id` int(11) NOT NULL,
  `felhasznalo_id` int(11) NOT NULL,
  `munka_nev` varchar(255) NOT NULL,
  `munka_leiras` text NOT NULL,
  `ar` decimal(10,2) NOT NULL,
  `datum_ido` datetime NOT NULL,
  `telefonszam` varchar(30) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `statusz` enum('aktiv','befejezett','archivalt') DEFAULT 'aktiv',
  `aktiv` tinyint(1) DEFAULT 1,
  `letrehozas_datuma` timestamp NOT NULL DEFAULT current_timestamp(),
  `kiemelt` tinyint(1) NOT NULL DEFAULT 0,
  `fizetve` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;


--
-- Tábla szerkezet ehhez a táblához `referencia_kepek`
--

CREATE TABLE `referencia_kepek` (
  `id` int(11) NOT NULL,
  `munka_id` int(11) NOT NULL,
  `kep_url` varchar(255) NOT NULL,
  `sorrend` int(11) DEFAULT 0,
  `feltoltes_datuma` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

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
-- Tábla szerkezet ehhez a táblához `torolt_megjegyzesek`
--

CREATE TABLE `torolt_megjegyzesek` (
  `tid` int(11) NOT NULL,
  `meid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `dolgozo_id` int(11) NOT NULL,
  `megjegyzes` text DEFAULT NULL,
  `ertekeles` int(11) DEFAULT NULL,
  `idopont` datetime NOT NULL,
  `torles_tipusa` enum('sajat_torles','admin_torles','feluliras','sajat_ertekeles_torles','admin_ertekeles_torles') DEFAULT 'sajat_torles',
  `torleste_fid` int(11) NOT NULL,
  `torles_datuma` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Tábla szerkezet ehhez a táblához `torolt_munkak`
--

CREATE TABLE `torolt_munkak` (
  `id` int(11) NOT NULL,
  `felhasznalo_id` int(11) NOT NULL,
  `munka_nev` varchar(255) NOT NULL,
  `munka_leiras` text NOT NULL,
  `ar` decimal(10,2) NOT NULL,
  `datum_ido` datetime NOT NULL,
  `telefonszam` varchar(30) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `statusz` varchar(20) DEFAULT 'aktiv',
  `aktiv` tinyint(1) DEFAULT 1,
  `letrehozas_datuma` timestamp NOT NULL DEFAULT current_timestamp(),
  `torles_datuma` timestamp NOT NULL DEFAULT current_timestamp(),
  `torlo_felhasznalo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `admin_tevekenyseg`
--
ALTER TABLE `admin_tevekenyseg`
  ADD PRIMARY KEY (`atid`);

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
-- A tábla indexei `jelentkezesek`
--
ALTER TABLE `jelentkezesek`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `jelszo_visszaallitasok`
--
ALTER TABLE `jelszo_visszaallitasok`
  ADD PRIMARY KEY (`jvid`);

--
-- A tábla indexei `kapcsolat_uzenet`
--
ALTER TABLE `kapcsolat_uzenet`
  ADD PRIMARY KEY (`kuid`);

--
-- A tábla indexei `megjegyzesek`
--
ALTER TABLE `megjegyzesek`
  ADD PRIMARY KEY (`meid`);

--
-- A tábla indexei `munkak`
--
ALTER TABLE `munkak`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `referencia_kepek`
--
ALTER TABLE `referencia_kepek`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `torolt_felhasznalok`
--
ALTER TABLE `torolt_felhasznalok`
  ADD PRIMARY KEY (`fid`);

--
-- A tábla indexei `torolt_megjegyzesek`
--
ALTER TABLE `torolt_megjegyzesek`
  ADD PRIMARY KEY (`tid`);

--
-- A tábla indexei `torolt_munkak`
--
ALTER TABLE `torolt_munkak`
  ADD PRIMARY KEY (`id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `admin_tevekenyseg`
--
ALTER TABLE `admin_tevekenyseg`
  MODIFY `atid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT a táblához `email_ell`
--
ALTER TABLE `email_ell`
  MODIFY `emid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT a táblához `felhasznalok`
--
ALTER TABLE `felhasznalok`
  MODIFY `fid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT a táblához `felhasznalo_tevekenyseg`
--
ALTER TABLE `felhasznalo_tevekenyseg`
  MODIFY `ftid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1311;

--
-- AUTO_INCREMENT a táblához `jelentkezesek`
--
ALTER TABLE `jelentkezesek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT a táblához `jelszo_visszaallitasok`
--
ALTER TABLE `jelszo_visszaallitasok`
  MODIFY `jvid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT a táblához `kapcsolat_uzenet`
--
ALTER TABLE `kapcsolat_uzenet`
  MODIFY `kuid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT a táblához `megjegyzesek`
--
ALTER TABLE `megjegyzesek`
  MODIFY `meid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT a táblához `munkak`
--
ALTER TABLE `munkak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT a táblához `referencia_kepek`
--
ALTER TABLE `referencia_kepek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT a táblához `torolt_megjegyzesek`
--
ALTER TABLE `torolt_megjegyzesek`
  MODIFY `tid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT a táblához `torolt_munkak`
--
ALTER TABLE `torolt_munkak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
