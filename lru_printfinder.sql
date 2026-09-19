-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2026 at 12:44 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS `lru_printfinder`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `lru_printfinder`;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE IF NOT EXISTS `User` (
  `User_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Role` enum('admin','user') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`User_ID`),
  UNIQUE KEY `uq_user_username` (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `User` (`Username`, `Password`, `Name`, `Role`) VALUES
('admin', '$2b$12$Kj3PuOEv7t5bnLiindMFHO.ardotsAhpJRFlDANo2kzD8UVS1Ur/6', 'ผู้ดูแลระบบ', 'admin'),
('user1', '$2b$12$Kj3PuOEv7t5bnLiindMFHO.ardotsAhpJRFlDANo2kzD8UVS1Ur/6', 'ผู้ใช้งานตัวอย่าง', 'user');

--
-- Database: `lru_printfinder`
--

-- --------------------------------------------------------

--
-- Table structure for table `Store`
--

CREATE TABLE IF NOT EXISTS `Store` (
  `Store_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Store_Name` varchar(150) NOT NULL,
  `Description` text DEFAULT NULL,
  `Address` text NOT NULL,
  `Latitude` decimal(10,8) NOT NULL,
  `Longitude` decimal(11,8) NOT NULL,
  PRIMARY KEY (`Store_ID`),
  UNIQUE KEY `uq_store_name` (`Store_Name`),
  CONSTRAINT `chk_store_latitude` CHECK (`Latitude` BETWEEN -90 AND 90),
  CONSTRAINT `chk_store_longitude` CHECK (`Longitude` BETWEEN -180 AND 180)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
INSERT IGNORE INTO `Store` (`Store_Name`, `Description`, `Address`, `Latitude`, `Longitude`) VALUES
('ร้านบุ๊คก๊อบปี๊', 'บริการถ่ายเอกสาร ปริ้นงาน และเข้าเล่ม', 'ใกล้มหาวิทยาลัยราชภัฏเลย ข้างร้านสะดวกซื้อ', 17.48512000, 101.72231000),
('ร้านเอ็ม เค ก๊อปปี้', 'ปริ้นเอกสาร ถ่ายเอกสาร และพิมพ์งานด่วน', 'บริเวณประตู 2 มหาวิทยาลัยราชภัฏเลย', 17.48745000, 101.72418000),
('ร้านบ้านคอมเมืองเลย', 'ปริ้นงาน เข้าเล่ม และบริการงานเอกสาร', 'ตรงข้ามตลาดใกล้มหาวิทยาลัยราชภัฏเลย', 17.48396000, 101.72087000);

-- จบข้อมูลร้านค้าในฐานข้อมูล lru_printfinder

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
