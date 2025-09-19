-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Gép: localhost:3306
-- Létrehozás ideje: 2025. Sze 19. 08:42
-- Kiszolgáló verziója: 10.11.14-MariaDB-cll-lve
-- PHP verzió: 8.4.11

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
-- Tábla szerkezet ehhez a táblához `api_kulcsok`
--

CREATE TABLE `api_kulcsok` (
  `kulcs_id` int(11) NOT NULL,
  `felhasznalo_id` int(11) NOT NULL,
  `api_kulcs` varchar(255) NOT NULL,
  `lejarat` timestamp NULL DEFAULT NULL,
  `letrehozva` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `biztonsagi_naplo`
--

CREATE TABLE `biztonsagi_naplo` (
  `biztonsagi_id` int(11) NOT NULL,
  `felhasznalo_id` int(11) DEFAULT NULL,
  `tabla_nev` varchar(100) DEFAULT NULL,
  `rekord_id` int(11) DEFAULT NULL,
  `muvelet` varchar(20) DEFAULT NULL,
  `regi_adat` longtext DEFAULT NULL CHECK (json_valid(`regi_adat`)),
  `uj_adat` longtext DEFAULT NULL CHECK (json_valid(`uj_adat`)),
  `letrehozva` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `ertekelesek`
--

CREATE TABLE `ertekelesek` (
  `ertekeles_id` int(11) NOT NULL,
  `munka_id` int(11) NOT NULL,
  `ertekelo_id` int(11) NOT NULL,
  `munkavallalo_id` int(11) NOT NULL,
  `pontszam` tinyint(4) NOT NULL CHECK (`pontszam` between 1 and 5),
  `megjegyzes` text DEFAULT NULL,
  `letrehozva` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `ertesitesek`
--

CREATE TABLE `ertesitesek` (
  `ertesites_id` int(11) NOT NULL,
  `felhasznalo_id` int(11) NOT NULL,
  `tipus_id` int(11) NOT NULL,
  `tartalom` text NOT NULL,
  `olvasva` tinyint(1) DEFAULT 0,
  `letrehozva` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `ertesites_tipusok`
--

CREATE TABLE `ertesites_tipusok` (
  `tipus_id` int(11) NOT NULL,
  `nev` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

--
-- A tábla adatainak kiíratása `ertesites_tipusok`
--

INSERT INTO `ertesites_tipusok` (`tipus_id`, `nev`) VALUES
(4, 'értékelés'),
(2, 'új jelentkezés'),
(1, 'új munka'),
(3, 'üzenet');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `fajlok`
--

CREATE TABLE `fajlok` (
  `fajl_id` int(11) NOT NULL,
  `felhasznalo_id` int(11) NOT NULL,
  `munka_id` int(11) DEFAULT NULL,
  `fajl_utvonal` varchar(255) NOT NULL,
  `fajl_tipus` varchar(50) NOT NULL,
  `feltoltve` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `felhasznalok`
--

CREATE TABLE `felhasznalok` (
  `felhasznalo_id` int(11) NOT NULL,
  `szerep` enum('munkáltató','munkavállaló','admin') DEFAULT 'munkavállaló',
  `nev` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `jelszo_hash` varchar(255) NOT NULL,
  `profil_kep` varchar(255) DEFAULT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `cim` text DEFAULT NULL,
  `letrehozva` timestamp NULL DEFAULT current_timestamp(),
  `modositva` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `utolso_belepes` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `felhasznalo_beallitasok`
--

CREATE TABLE `felhasznalo_beallitasok` (
  `beallitas_id` int(11) NOT NULL,
  `felhasznalo_id` int(11) NOT NULL,
  `kulcs` varchar(100) NOT NULL,
  `ertek` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `felhasznalo_naplo`
--

CREATE TABLE `felhasznalo_naplo` (
  `naplo_id` int(11) NOT NULL,
  `felhasznalo_id` int(11) DEFAULT NULL,
  `ip_cim` varchar(45) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `tevekenyseg` varchar(255) DEFAULT NULL,
  `letrehozva` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `felhasznalo_rangok`
--

CREATE TABLE `felhasznalo_rangok` (
  `rang_id` int(11) NOT NULL,
  `felhasznalo_id` int(11) NOT NULL,
  `szint` int(11) DEFAULT 1,
  `pontok` int(11) DEFAULT 0,
  `jelveny` varchar(100) DEFAULT NULL,
  `frissitve` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `hirlevel_feliratkozok`
--

CREATE TABLE `hirlevel_feliratkozok` (
  `feliratkozo_id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `igazolt` tinyint(1) DEFAULT 0,
  `feliratkozva` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `jelentesek`
--

CREATE TABLE `jelentesek` (
  `jelentés_id` int(11) NOT NULL,
  `bejelento_id` int(11) NOT NULL,
  `cel_felhasznalo_id` int(11) DEFAULT NULL,
  `munka_id` int(11) DEFAULT NULL,
  `uzenet` text NOT NULL,
  `allapot` varchar(20) DEFAULT 'nyitott',
  `letrehozva` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `jelentkezesek`
--

CREATE TABLE `jelentkezesek` (
  `jelentkezes_id` int(11) NOT NULL,
  `munka_id` int(11) NOT NULL,
  `munkavallalo_id` int(11) NOT NULL,
  `ajanlott_ar` decimal(10,2) DEFAULT NULL,
  `uzenet` text DEFAULT NULL,
  `allapot_id` int(11) NOT NULL DEFAULT 1,
  `letrehozva` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `jelentkezes_allapotok`
--

CREATE TABLE `jelentkezes_allapotok` (
  `allapot_id` int(11) NOT NULL,
  `nev` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

--
-- A tábla adatainak kiíratása `jelentkezes_allapotok`
--

INSERT INTO `jelentkezes_allapotok` (`allapot_id`, `nev`) VALUES
(2, 'elfogadva'),
(3, 'elutasítva'),
(1, 'függ?ben');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `kategoriak`
--

CREATE TABLE `kategoriak` (
  `kategoria_id` int(11) NOT NULL,
  `szulo_id` int(11) DEFAULT NULL,
  `nev` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `kedvencek`
--

CREATE TABLE `kedvencek` (
  `kedvenc_id` int(11) NOT NULL,
  `felhasznalo_id` int(11) NOT NULL,
  `munka_id` int(11) NOT NULL,
  `letrehozva` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `munkak`
--

CREATE TABLE `munkak` (
  `munka_id` int(11) NOT NULL,
  `munkaltato_id` int(11) NOT NULL,
  `kategoria_id` int(11) NOT NULL,
  `cim` varchar(150) NOT NULL,
  `leiras` text NOT NULL,
  `ar` decimal(10,2) DEFAULT NULL,
  `kezdet` date DEFAULT NULL,
  `vege` date DEFAULT NULL,
  `elerheto_napok` set('Hétf?','Kedd','Szerda','Csütörtök','Péntek','Szombat','Vasárnap') DEFAULT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `kep` varchar(255) DEFAULT NULL,
  `allapot` enum('nyitott','folyamatban','befejezve','lemondva') DEFAULT 'nyitott',
  `letrehozva` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `munka_allapotok`
--

CREATE TABLE `munka_allapotok` (
  `allapot_id` int(11) NOT NULL,
  `nev` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

--
-- A tábla adatainak kiíratása `munka_allapotok`
--

INSERT INTO `munka_allapotok` (`allapot_id`, `nev`) VALUES
(2, 'függ?ben'),
(3, 'lezárva'),
(1, 'nyitott'),
(4, 'törölve');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `munka_megtekintesek`
--

CREATE TABLE `munka_megtekintesek` (
  `megtekintes_id` int(11) NOT NULL,
  `munka_id` int(11) NOT NULL,
  `felhasznalo_id` int(11) DEFAULT NULL,
  `ip_cim` varchar(45) DEFAULT NULL,
  `megtekintve` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `szerepek`
--

CREATE TABLE `szerepek` (
  `szerep_id` int(11) NOT NULL,
  `nev` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

--
-- A tábla adatainak kiíratása `szerepek`
--

INSERT INTO `szerepek` (`szerep_id`, `nev`) VALUES
(1, 'admin'),
(2, 'munkáltató'),
(3, 'munkavállaló');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `uzenetek`
--

CREATE TABLE `uzenetek` (
  `uzenet_id` int(11) NOT NULL,
  `munka_id` int(11) NOT NULL,
  `kuldo_id` int(11) NOT NULL,
  `fogado_id` int(11) NOT NULL,
  `uzenet` text NOT NULL,
  `fajl_id` int(11) DEFAULT NULL,
  `letrehozva` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_hungarian_ci;

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `api_kulcsok`
--
ALTER TABLE `api_kulcsok`
  ADD PRIMARY KEY (`kulcs_id`),
  ADD UNIQUE KEY `api_kulcs` (`api_kulcs`),
  ADD KEY `felhasznalo_id` (`felhasznalo_id`);

--
-- A tábla indexei `biztonsagi_naplo`
--
ALTER TABLE `biztonsagi_naplo`
  ADD PRIMARY KEY (`biztonsagi_id`),
  ADD KEY `felhasznalo_id` (`felhasznalo_id`);

--
-- A tábla indexei `ertekelesek`
--
ALTER TABLE `ertekelesek`
  ADD PRIMARY KEY (`ertekeles_id`),
  ADD KEY `munka_id` (`munka_id`),
  ADD KEY `ertekelo_id` (`ertekelo_id`),
  ADD KEY `munkavallalo_id` (`munkavallalo_id`);

--
-- A tábla indexei `ertesitesek`
--
ALTER TABLE `ertesitesek`
  ADD PRIMARY KEY (`ertesites_id`),
  ADD KEY `felhasznalo_id` (`felhasznalo_id`),
  ADD KEY `tipus_id` (`tipus_id`);

--
-- A tábla indexei `ertesites_tipusok`
--
ALTER TABLE `ertesites_tipusok`
  ADD PRIMARY KEY (`tipus_id`),
  ADD UNIQUE KEY `nev` (`nev`);

--
-- A tábla indexei `fajlok`
--
ALTER TABLE `fajlok`
  ADD PRIMARY KEY (`fajl_id`),
  ADD KEY `felhasznalo_id` (`felhasznalo_id`),
  ADD KEY `munka_id` (`munka_id`);

--
-- A tábla indexei `felhasznalok`
--
ALTER TABLE `felhasznalok`
  ADD PRIMARY KEY (`felhasznalo_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- A tábla indexei `felhasznalo_beallitasok`
--
ALTER TABLE `felhasznalo_beallitasok`
  ADD PRIMARY KEY (`beallitas_id`),
  ADD KEY `felhasznalo_id` (`felhasznalo_id`);

--
-- A tábla indexei `felhasznalo_naplo`
--
ALTER TABLE `felhasznalo_naplo`
  ADD PRIMARY KEY (`naplo_id`),
  ADD KEY `felhasznalo_id` (`felhasznalo_id`);

--
-- A tábla indexei `felhasznalo_rangok`
--
ALTER TABLE `felhasznalo_rangok`
  ADD PRIMARY KEY (`rang_id`),
  ADD KEY `felhasznalo_id` (`felhasznalo_id`);

--
-- A tábla indexei `hirlevel_feliratkozok`
--
ALTER TABLE `hirlevel_feliratkozok`
  ADD PRIMARY KEY (`feliratkozo_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- A tábla indexei `jelentesek`
--
ALTER TABLE `jelentesek`
  ADD PRIMARY KEY (`jelentés_id`),
  ADD KEY `bejelento_id` (`bejelento_id`),
  ADD KEY `cel_felhasznalo_id` (`cel_felhasznalo_id`),
  ADD KEY `munka_id` (`munka_id`);

--
-- A tábla indexei `jelentkezesek`
--
ALTER TABLE `jelentkezesek`
  ADD PRIMARY KEY (`jelentkezes_id`),
  ADD KEY `munka_id` (`munka_id`),
  ADD KEY `munkavallalo_id` (`munkavallalo_id`),
  ADD KEY `allapot_id` (`allapot_id`);

--
-- A tábla indexei `jelentkezes_allapotok`
--
ALTER TABLE `jelentkezes_allapotok`
  ADD PRIMARY KEY (`allapot_id`),
  ADD UNIQUE KEY `nev` (`nev`);

--
-- A tábla indexei `kategoriak`
--
ALTER TABLE `kategoriak`
  ADD PRIMARY KEY (`kategoria_id`),
  ADD KEY `szulo_id` (`szulo_id`);

--
-- A tábla indexei `kedvencek`
--
ALTER TABLE `kedvencek`
  ADD PRIMARY KEY (`kedvenc_id`),
  ADD KEY `felhasznalo_id` (`felhasznalo_id`),
  ADD KEY `munka_id` (`munka_id`);

--
-- A tábla indexei `munkak`
--
ALTER TABLE `munkak`
  ADD PRIMARY KEY (`munka_id`),
  ADD KEY `munkaltato_id` (`munkaltato_id`),
  ADD KEY `kategoria_id` (`kategoria_id`);

--
-- A tábla indexei `munka_allapotok`
--
ALTER TABLE `munka_allapotok`
  ADD PRIMARY KEY (`allapot_id`),
  ADD UNIQUE KEY `nev` (`nev`);

--
-- A tábla indexei `munka_megtekintesek`
--
ALTER TABLE `munka_megtekintesek`
  ADD PRIMARY KEY (`megtekintes_id`),
  ADD KEY `munka_id` (`munka_id`),
  ADD KEY `felhasznalo_id` (`felhasznalo_id`);

--
-- A tábla indexei `szerepek`
--
ALTER TABLE `szerepek`
  ADD PRIMARY KEY (`szerep_id`),
  ADD UNIQUE KEY `nev` (`nev`);

--
-- A tábla indexei `uzenetek`
--
ALTER TABLE `uzenetek`
  ADD PRIMARY KEY (`uzenet_id`),
  ADD KEY `munka_id` (`munka_id`),
  ADD KEY `kuldo_id` (`kuldo_id`),
  ADD KEY `fogado_id` (`fogado_id`),
  ADD KEY `fk_uzenetek_fajl` (`fajl_id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `api_kulcsok`
--
ALTER TABLE `api_kulcsok`
  MODIFY `kulcs_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `biztonsagi_naplo`
--
ALTER TABLE `biztonsagi_naplo`
  MODIFY `biztonsagi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `ertekelesek`
--
ALTER TABLE `ertekelesek`
  MODIFY `ertekeles_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `ertesitesek`
--
ALTER TABLE `ertesitesek`
  MODIFY `ertesites_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `ertesites_tipusok`
--
ALTER TABLE `ertesites_tipusok`
  MODIFY `tipus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT a táblához `fajlok`
--
ALTER TABLE `fajlok`
  MODIFY `fajl_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `felhasznalok`
--
ALTER TABLE `felhasznalok`
  MODIFY `felhasznalo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `felhasznalo_beallitasok`
--
ALTER TABLE `felhasznalo_beallitasok`
  MODIFY `beallitas_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `felhasznalo_naplo`
--
ALTER TABLE `felhasznalo_naplo`
  MODIFY `naplo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `felhasznalo_rangok`
--
ALTER TABLE `felhasznalo_rangok`
  MODIFY `rang_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `hirlevel_feliratkozok`
--
ALTER TABLE `hirlevel_feliratkozok`
  MODIFY `feliratkozo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `jelentesek`
--
ALTER TABLE `jelentesek`
  MODIFY `jelentés_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `jelentkezesek`
--
ALTER TABLE `jelentkezesek`
  MODIFY `jelentkezes_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `jelentkezes_allapotok`
--
ALTER TABLE `jelentkezes_allapotok`
  MODIFY `allapot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT a táblához `kategoriak`
--
ALTER TABLE `kategoriak`
  MODIFY `kategoria_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `kedvencek`
--
ALTER TABLE `kedvencek`
  MODIFY `kedvenc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `munkak`
--
ALTER TABLE `munkak`
  MODIFY `munka_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `munka_allapotok`
--
ALTER TABLE `munka_allapotok`
  MODIFY `allapot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT a táblához `munka_megtekintesek`
--
ALTER TABLE `munka_megtekintesek`
  MODIFY `megtekintes_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `szerepek`
--
ALTER TABLE `szerepek`
  MODIFY `szerep_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT a táblához `uzenetek`
--
ALTER TABLE `uzenetek`
  MODIFY `uzenet_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `api_kulcsok`
--
ALTER TABLE `api_kulcsok`
  ADD CONSTRAINT `api_kulcsok_ibfk_1` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalok` (`felhasznalo_id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `biztonsagi_naplo`
--
ALTER TABLE `biztonsagi_naplo`
  ADD CONSTRAINT `biztonsagi_naplo_ibfk_1` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalok` (`felhasznalo_id`);

--
-- Megkötések a táblához `ertekelesek`
--
ALTER TABLE `ertekelesek`
  ADD CONSTRAINT `ertekelesek_ibfk_1` FOREIGN KEY (`munka_id`) REFERENCES `munkak` (`munka_id`),
  ADD CONSTRAINT `ertekelesek_ibfk_2` FOREIGN KEY (`ertekelo_id`) REFERENCES `felhasznalok` (`felhasznalo_id`),
  ADD CONSTRAINT `ertekelesek_ibfk_3` FOREIGN KEY (`munkavallalo_id`) REFERENCES `felhasznalok` (`felhasznalo_id`);

--
-- Megkötések a táblához `ertesitesek`
--
ALTER TABLE `ertesitesek`
  ADD CONSTRAINT `ertesitesek_ibfk_1` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalok` (`felhasznalo_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ertesitesek_ibfk_2` FOREIGN KEY (`tipus_id`) REFERENCES `ertesites_tipusok` (`tipus_id`);

--
-- Megkötések a táblához `fajlok`
--
ALTER TABLE `fajlok`
  ADD CONSTRAINT `fajlok_ibfk_1` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalok` (`felhasznalo_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fajlok_ibfk_2` FOREIGN KEY (`munka_id`) REFERENCES `munkak` (`munka_id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `felhasznalo_beallitasok`
--
ALTER TABLE `felhasznalo_beallitasok`
  ADD CONSTRAINT `felhasznalo_beallitasok_ibfk_1` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalok` (`felhasznalo_id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `felhasznalo_naplo`
--
ALTER TABLE `felhasznalo_naplo`
  ADD CONSTRAINT `felhasznalo_naplo_ibfk_1` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalok` (`felhasznalo_id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `felhasznalo_rangok`
--
ALTER TABLE `felhasznalo_rangok`
  ADD CONSTRAINT `felhasznalo_rangok_ibfk_1` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalok` (`felhasznalo_id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `jelentesek`
--
ALTER TABLE `jelentesek`
  ADD CONSTRAINT `jelentesek_ibfk_1` FOREIGN KEY (`bejelento_id`) REFERENCES `felhasznalok` (`felhasznalo_id`),
  ADD CONSTRAINT `jelentesek_ibfk_2` FOREIGN KEY (`cel_felhasznalo_id`) REFERENCES `felhasznalok` (`felhasznalo_id`),
  ADD CONSTRAINT `jelentesek_ibfk_3` FOREIGN KEY (`munka_id`) REFERENCES `munkak` (`munka_id`);

--
-- Megkötések a táblához `jelentkezesek`
--
ALTER TABLE `jelentkezesek`
  ADD CONSTRAINT `jelentkezesek_ibfk_1` FOREIGN KEY (`munka_id`) REFERENCES `munkak` (`munka_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jelentkezesek_ibfk_2` FOREIGN KEY (`munkavallalo_id`) REFERENCES `felhasznalok` (`felhasznalo_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jelentkezesek_ibfk_3` FOREIGN KEY (`allapot_id`) REFERENCES `jelentkezes_allapotok` (`allapot_id`);

--
-- Megkötések a táblához `kategoriak`
--
ALTER TABLE `kategoriak`
  ADD CONSTRAINT `kategoriak_ibfk_1` FOREIGN KEY (`szulo_id`) REFERENCES `kategoriak` (`kategoria_id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `kedvencek`
--
ALTER TABLE `kedvencek`
  ADD CONSTRAINT `kedvencek_ibfk_1` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalok` (`felhasznalo_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kedvencek_ibfk_2` FOREIGN KEY (`munka_id`) REFERENCES `munkak` (`munka_id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `munkak`
--
ALTER TABLE `munkak`
  ADD CONSTRAINT `munkak_ibfk_1` FOREIGN KEY (`munkaltato_id`) REFERENCES `felhasznalok` (`felhasznalo_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `munkak_ibfk_2` FOREIGN KEY (`kategoria_id`) REFERENCES `kategoriak` (`kategoria_id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `munka_megtekintesek`
--
ALTER TABLE `munka_megtekintesek`
  ADD CONSTRAINT `munka_megtekintesek_ibfk_1` FOREIGN KEY (`munka_id`) REFERENCES `munkak` (`munka_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `munka_megtekintesek_ibfk_2` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalok` (`felhasznalo_id`) ON DELETE SET NULL;

--
-- Megkötések a táblához `uzenetek`
--
ALTER TABLE `uzenetek`
  ADD CONSTRAINT `fk_uzenetek_fajl` FOREIGN KEY (`fajl_id`) REFERENCES `fajlok` (`fajl_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `uzenetek_ibfk_1` FOREIGN KEY (`munka_id`) REFERENCES `munkak` (`munka_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `uzenetek_ibfk_2` FOREIGN KEY (`kuldo_id`) REFERENCES `felhasznalok` (`felhasznalo_id`),
  ADD CONSTRAINT `uzenetek_ibfk_3` FOREIGN KEY (`fogado_id`) REFERENCES `felhasznalok` (`felhasznalo_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
