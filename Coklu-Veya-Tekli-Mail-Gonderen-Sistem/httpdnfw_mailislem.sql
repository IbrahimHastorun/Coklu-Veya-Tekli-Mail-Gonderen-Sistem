-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 11 Eki 2022, 12:50:52
-- Sunucu sürümü: 8.0.30-cll-lve
-- PHP Sürümü: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `httpdnfw_mailislem`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kisiler`
--

CREATE TABLE `kisiler` (
  `id` int NOT NULL,
  `mailadres` varchar(70) CHARACTER SET utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
  `durum` int NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `mailicerik`
--

CREATE TABLE `mailicerik` (
  `id` int NOT NULL,
  `ekdosya` varchar(70) CHARACTER SET utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL DEFAULT '0',
  `konu` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
  `icerik` text CHARACTER SET utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
  `sure` int NOT NULL,
  `durum` int NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_turkish_ci;

-- --------------------------------------------------------
-- Tablo için indeksler `kisiler`
--
ALTER TABLE `kisiler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `mailicerik`
--
ALTER TABLE `mailicerik`
  ADD PRIMARY KEY (`id`);

--
--
-- Tablo için AUTO_INCREMENT değeri `kisiler`
--
ALTER TABLE `kisiler`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `mailicerik`
--
ALTER TABLE `mailicerik`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
