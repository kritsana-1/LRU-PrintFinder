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

CREATE TABLE `User` (
  `User_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Role` enum('admin','user') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`User_ID`),
  UNIQUE KEY `uq_user_username` (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `User` (`Username`, `Password`, `Name`, `Role`) VALUES
('admin', '$2b$12$Kj3PuOEv7t5bnLiindMFHO.ardotsAhpJRFlDANo2kzD8UVS1Ur/6', 'ผู้ดูแลระบบ', 'admin'),
('user1', '$2b$12$Kj3PuOEv7t5bnLiindMFHO.ardotsAhpJRFlDANo2kzD8UVS1Ur/6', 'ผู้ใช้งานตัวอย่าง', 'user');

--
-- Database: `lru_printfinder`
--

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `ClosingHours` varchar(100) NOT NULL COMMENT 'เวลาปิด',
  `StoreID` int(255) NOT NULL,
  `StoreName` varchar(255) NOT NULL COMMENT 'ชื่อร้านปริ้น',
  `OpeningDays` varchar(100) NOT NULL COMMENT 'วันเปิดบริการ',
  `OpeningHours` varchar(100) NOT NULL COMMENT 'เวลาเปิดบริการ',
  `LocationHint` varchar(255) NOT NULL COMMENT 'บริเวณที่ตั้งร้าน',
  `PhoneNumber` varchar(10) NOT NULL COMMENT 'เบอร์โทร',
  `MoreContact` varchar(255) NOT NULL COMMENT 'ข้อมูลติดต่อเพิ่มเติม',
  `Service` varchar(255) NOT NULL COMMENT 'บริการ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`ClosingHours`, `StoreID`, `StoreName`, `OpeningDays`, `OpeningHours`, `LocationHint`, `PhoneNumber`, `MoreContact`, `Service`) VALUES
('ปิด 19:00', 1, 'ร้าน บุ๊คก๊อบปี๊', 'เปิดบริการ ทุกวัน', 'เปิด 8:00', 'อยู่บริเวณข้างมอที่อยู่ใกล้ๆกับเซเว่น', '0850047467', 'Line id : 0850047467 Fb : บุ๊คก๊อบปี๊', 'ถ่ายเอกสาร,ปริ้นงาน,เข้าเล่ม'),
('ปิด 18:00', 2, 'ร้าน เอฟ เอ โฟโต้ก๊อปปี้', 'เปิดบริการ จันทร์ถึงเสาร์', 'เปิด 8:00', 'อยู่ข้างๆร้านจุ๋ยมือถือตรงซอยเพชรเจริญซอย 1', '0870002497', 'Line id : 0924147952', 'ถ่ายเอกสาร,ป้ายไวนิล'),
('ปิด จันทร์- ศุกร์ 19:30, เสาร์ 17:00, อาทิตย์ 19:30', 3, 'ร้านเอ็ม เค ก๊อปปี้', 'เปิดบริการทุกวัน', 'เปิด จันทร์- ศุกร์ 7:30, เสาร์ 10:00, อาทิตย์ 9:30', 'ข้างมอประตู 2 ทางยิมใหม่', '042033311', 'Line id: mkcopy1234', 'ขายอุปกรณ์เครื่องเขียน,ปริ้นเอกสาร,ถ่ายเอกสาร,พิมพ์ใบปลิวโบรชัวร์'),
('ปิด -', 4, 'ร้านเฮงก็อปปี้', 'เปิดบริการทุกวัน', 'เปิด 24 ชั่วโมง', 'อยู่ข้างมราชภัฏเลย เลยหอพักหญิงวิชชากร', '0969615266', 'Line id : hengcopy', 'ปริ้น,ถ่ายเอกสาร,เข้าเล่ม ,portfolio ,ตัดต่อ,ถ่ายรูป'),
('ปิด จันทร์ - ศุกร์ 18:00, เสาร์ 18:00', 5, 'ร้าน บ้านคอมเมืองเลย', 'เปิดบริการ จันทร์ถึงเสาร์', 'เปิด จันทร์ - ศุกร์ 8:00, เสาร์ 9:00', 'ตรงข้ามตลาดข้างมอ', '0818929844', 'Line id: bancomloei', 'เอกสาร,ปริ้นงาน,เข้าเล่ม');

-- --------------------------------------------------------

--
-- Table structure for table `storeaddress`
--

CREATE TABLE `storeaddress` (
  `AddressID` int(255) NOT NULL,
  `HouseNumber` varchar(50) NOT NULL COMMENT 'บ้านเลขที่',
  `Subdistrict` varchar(50) NOT NULL COMMENT 'ตำบล',
  `District` varchar(50) NOT NULL COMMENT 'อำเภอ',
  `Province` varchar(50) NOT NULL COMMENT 'จังหวัด',
  `PostalCode` varchar(5) NOT NULL COMMENT 'รหัสไปรษณีย์',
  `StoreID` int(11) NOT NULL COMMENT 'ลำดับร้าน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `storeaddress`
--

INSERT INTO `storeaddress` (`AddressID`, `HouseNumber`, `Subdistrict`, `District`, `Province`, `PostalCode`, `StoreID`) VALUES
(1, '410 หมู่ 11 ', 'ตำบลเมือง', 'อำเภอเมืองเลย', 'จังหวัดเลย', 42000, '1'),
(2, '204 หมู่ 8', 'ตำบลเมือง', 'อำเภอเมืองเลย', 'จังหวัดเลย', 42000, '2'),
(3, '111', 'ตำบลเมือง', 'อำเภอเมืองเลย', 'จังหวัดเลย', 42000, '3'),
(4, '559/2 หมู่ 11', 'ตำบลเมือง', 'อำเภอเมืองเลย', 'จังหวัดเลย', 42000, '4'),
(5, '25 หมู่9', 'ตำบลเมือง', 'อำเภอเมืองเลย', 'จังหวัดเลย', 42000, '5');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`StoreID`);

--
-- Indexes for table `storeaddress`
--
ALTER TABLE `storeaddress`
  ADD PRIMARY KEY (`AddressID`),
  ADD KEY `StoreID` (`StoreID`),
  ADD CONSTRAINT `fk_storeaddress_store` FOREIGN KEY (`StoreID`) REFERENCES `store` (`StoreID`) ON UPDATE CASCADE ON DELETE CASCADE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `StoreID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `storeaddress`
--
ALTER TABLE `storeaddress`
  MODIFY `AddressID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
