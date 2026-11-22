-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 22, 2026 at 11:52 AM
-- Server version: 26.10.0
-- PHP Version: 8.6.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `medifind`
--

-- --------------------------------------------------------

--
-- Table structure for table `01_user_users`
--

CREATE TABLE `01_user_users` (
  `User_ID` int NOT NULL,
  `Username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `First_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Middle_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Last_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Gender` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Birthdate` date DEFAULT NULL,
  `Email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Phone` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `Address_ID` int DEFAULT NULL,
  `Role_ID` int DEFAULT NULL,
  `User_Status_ID` int DEFAULT NULL,
  `Profilepic_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Password_hash` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `DateCreated` datetime DEFAULT CURRENT_TIMESTAMP,
  `DateApproved` datetime(6) DEFAULT NULL,
  `reset_code` int DEFAULT NULL,
  `IsDeleted` tinyint(1) NOT NULL DEFAULT '0',
  `DeletedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `01_user_users`
--

INSERT INTO `01_user_users` (`User_ID`, `Username`, `First_name`, `Middle_name`, `Last_name`, `Gender`, `Birthdate`, `Email`, `Phone`, `Address_ID`, `Role_ID`, `User_Status_ID`, `Profilepic_url`, `Password_hash`, `DateCreated`, `DateApproved`, `reset_code`, `IsDeleted`, `DeletedAt`) VALUES
(1, 'System', 'System', NULL, 'Admin', NULL, NULL, 'System@gmail.com', '09000000001', 1, 4, 1, '/pic/admin.jpg', '$2y$10$cGqJ/j9imqfPdhlsifovrO6mig7fOxGRxHl6NXTAIpvstUXCl191a', '2026-04-16 17:38:27', NULL, 832221, 0, NULL),
(2, 'Maria', 'Maria', NULL, 'Santos', NULL, NULL, 'maria@email.com', '09171234561', 2, 1, 1, '/pic/u2.jpg', 'hashed_pass_2', '2026-04-16 17:38:27', NULL, 0, 0, NULL),
(3, 'Jose', 'Jose', NULL, 'Reyes', NULL, NULL, 'jose@email.com', '09171234562', 3, 2, 1, '/pic/u3.jpg', 'hashed_pass_3', '2026-04-16 17:38:27', NULL, 0, 0, NULL),
(4, 'Ana', 'Ana', NULL, 'Cruz', NULL, NULL, 'ana@email.com', '09171234563', 4, 3, 1, '/pic/u4.jpg', 'hashed_pass_4', '2026-04-16 17:38:27', NULL, 0, 0, NULL),
(5, 'Juan', 'Juan', NULL, 'Luna', NULL, NULL, 'juan@email.com', '09171234564', 5, 1, 1, '/pic/u5.jpg', 'hashed_pass_5', '2026-04-16 17:38:27', NULL, 0, 0, NULL),
(6, 'Elena', 'Elena', NULL, 'Torres', NULL, NULL, 'elena@email.com', '09171234565', 1, 1, 1, '/pic/u6.jpg', 'hashed_pass_6', '2026-04-16 17:38:27', NULL, 0, 0, NULL),
(7, 'Pedro', 'Pedro', NULL, 'Penduko', NULL, NULL, 'pedro@email.com', '09171234566', 2, 1, 1, '/pic/u7.jpg', 'hashed_pass_7', '2026-04-16 17:38:27', NULL, 0, 0, NULL),
(8, 'Rosa', 'Rosa', NULL, 'Villanueva', NULL, NULL, 'rosa@email.com', '09171234567', 3, 3, 1, '/pic/u8.jpg', 'hashed_pass_8', '2026-04-16 17:38:27', NULL, 0, 0, NULL),
(9, 'Antonio', 'Antonio', NULL, 'Luna', NULL, NULL, 'antonio@email.com', '09171234568', 4, 1, 1, '/pic/u9.jpg', 'hashed_pass_9', '2026-04-16 17:38:27', NULL, 0, 0, NULL),
(10, 'Sofia', 'Sofia', NULL, 'Loren', NULL, NULL, 'sofia@email.com', '09171234569', 5, 1, 1, '/pic/u10.jpg', 'hashed_pass_10', '2026-04-16 17:38:27', NULL, 0, 0, NULL),
(11, 'Ramon', 'Ramon', NULL, 'Magsaysay', NULL, NULL, 'ramon@email.com', '09171234570', 1, 2, 1, '/pic/u11.jpg', 'hashed_pass_11', '2026-04-16 17:38:27', NULL, 0, 0, NULL),
(12, 'qwerty', 'Alquien', '123', 'Capuyan', NULL, NULL, 'TEST@gmail.com', '123123', 6, 1, 1, '/pic/default.jpg', '$2y$10$xtqiUqKXCQrSDKdKifylqO4uL5zeT4BXtK4pPSHCs9.jBTECaXTii', '2026-04-27 21:25:02', NULL, 0, 0, NULL),
(13, 'padmin@gmail.com', 'Alquien', '123', 'Capuyan', NULL, NULL, 'padmin@gmail.com', '2401117072@stud', 7, 2, 1, '/pic/default.jpg', '$2y$10$RGaP4WUAkJh7Ah1xwYRYAex2LVZ7H3ccGbVSU36.unZps0.tq.egy', '2026-04-27 22:25:14', NULL, 0, 0, NULL),
(14, 'Doe@gmail.com', 'Doe', 'Doe', 'Doe', NULL, NULL, 'Doe@gmail.com', 'Doe1231', 8, 1, 1, '/pic/default.jpg', '$2y$10$h8vYyXndO6zU7kpri6b/S.tuoge6BI8oSg/o6Dyxqm.7QmYbyqWWq', '2026-04-27 23:39:02', NULL, NULL, 0, NULL),
(15, 'Farmy', 'Farmy', 'Farmy', 'Farmy', NULL, NULL, 'Farmy@gmail.com', '343', 9, 2, 1, '/pic/default.jpg', '$2y$10$HmRgEe8c1a2z1RHJjNuZle8zUgcHibrJKYTxskijisu1aQ3G/vcT2', '2026-04-27 23:41:50', NULL, NULL, 0, NULL),
(16, 'testhash', 'testhash', 'testhash', 'testhash', NULL, NULL, 'testhash@gmail.com', 'testhash', 10, 2, 1, '/pic/default.jpg', '$2y$10$z07YXBrjcL6La4pWPI2Xc.PYN.Ixm3So4NwCkdrEMufN38vjdD3XO', '2026-04-27 23:55:17', NULL, NULL, 0, NULL),
(17, '222@gmail.com', 'Alquien', '123', 'Capuyan', NULL, NULL, '222@gmail.com', '1123', 11, 1, 1, '/pic/default.jpg', '$2y$10$ZJhO6d/e7uNOL.Dv2FPcgO/09RrWQ8.KZpfvbLqZ5JVvi7XBnP6EW', '2026-04-28 00:43:54', NULL, NULL, 0, NULL),
(18, '2222@gmail.com', '222@gmail.com', '222@gmail.com', '222@gmail.com', NULL, NULL, '2222@gmail.com', '222@gmail.com', 12, 1, 1, '/pic/default.jpg', '$2y$10$2woolMCc3AP.ZSPommLlJOxgZ7ATGWXsgG5AlBj37BrJFMtHAUEfi', '2026-04-28 00:44:37', NULL, NULL, 0, NULL),
(19, '22222@gmail.com', '2222@gmail.com', '2222@gmail.com', '2222@gmail.com', NULL, NULL, '22222@gmail.com', '2222@gmail.com', 13, 1, 1, '/pic/default.jpg', '$2y$10$YNBN4mXJT80JScLpQJbJkuAhOaz87JtVLgxdzGIp2Oqnj2G3DT8Wm', '2026-04-28 00:46:37', NULL, NULL, 0, NULL),
(20, 'dowid@gmail.com', 'dowid', 'dowid', 'Capuyan', NULL, NULL, 'dowid@gmail.com', 'dowid', 14, 1, 1, '/pic/default.jpg', '$2y$10$Wki9t8MiN4iGbJAviBUy3ObJ0lFBh471qJfEz.JWr6TsDODInvDC2', '2026-04-28 00:55:50', NULL, NULL, 0, NULL),
(21, 'dowid2@gmail.com', 'dowid@gmail.com', 'dowid@gmail.com', 'dowid@gmail.com', NULL, NULL, 'dowid2@gmail.com', 'dowid@gmail.com', 15, 1, 1, '/pic/default.jpg', '$2y$10$sPglpZj1C9zFeOIFTGdgw.82/NsV8f5Fu1VONXdPZ6LqBIKHrooJK', '2026-04-28 00:57:18', NULL, NULL, 0, NULL),
(22, '1234@gmail.com', 'Alquien', '123', 'Capuyan', NULL, NULL, '1234@gmail.com', '123', 16, 1, 1, '/pic/default.jpg', '$2y$10$UQiBOw/uBcCMhkzGtPhKTOggGMU9Gv1ZgPcm2nlo/SZYj4OPgh3hO', '2026-04-28 00:58:02', NULL, NULL, 0, NULL),
(23, 'dowed', 'dowed', 'dowed', 'dowed', NULL, NULL, 'dowed@gmail.com', 'dowed', 17, 2, 1, '/pic/default.jpg', '$2y$10$6RylX0G0PAaj5nIgd9V7i.vaChY3VobNStb4da.17.0vUll5YX3fy', '2026-04-28 01:30:36', NULL, NULL, 0, NULL),
(24, 'AdminAdmin@gmail.com', 'AdminAdmin', 'AdminAdmin', 'AdminAdmin', NULL, NULL, 'AdminAdmin@gmail.com', 'AdminAdmin', 18, 4, 1, '/pic/default.jpg', '$2y$10$gzbqMwmUWvNI1mHzalD6k.iLBrcEnSGBy5Wb6aq0fCUKVg0Q0366W', '2026-04-28 01:32:40', NULL, NULL, 0, NULL),
(25, 'admin', 'Alquien', 'Engaling', 'Capuyan', NULL, NULL, '2401117072@student.buksu.edu.ph', '09931419491', 19, 4, 1, '/pic/default.jpg', '$2y$10$CL2E1aM2TBb1EAsJjHIucO.SSgqCihUgQgemniOUIWjhegC2vcBFy', '2026-04-28 06:51:01', NULL, NULL, 0, NULL),
(26, 'yael', 'Yael', 'Sabio', 'Capuyan', NULL, NULL, 'yael123@gmail.com', '0934835', 20, 2, 1, '/pic/default.jpg', '$2y$10$ZQKDSV.zKfuXb7fLEUBCAeRVpJ0jhWTNL8B/zgaZMjBRoPx3ikxUi', '2026-04-28 07:20:30', NULL, NULL, 0, NULL),
(27, 'zeruuan', 'Hazel', 'Monteroyo', 'Carillo', NULL, NULL, 'hazel@gmail.com', '0932123', 21, 1, 1, '/pic/default.jpg', '$2y$10$xCBwtI2UTf2ds.d6VU1ieej8kgOcmcaaHZVRquVWd7ONaYKPXwhr.', '2026-04-28 07:28:25', NULL, NULL, 0, NULL),
(28, 'dayandayan', 'Dayan', '', 'Balansag', NULL, NULL, 'dayan@gmail.com', '2342424', 22, 2, 1, '/pic/default.jpg', '$2y$10$eCKf9N0e/1OoDfauZlBeMuSIiTMvvV8D29J0fTcJ7jRs8nnBYYANi', '2026-04-28 07:34:47', NULL, NULL, 0, NULL),
(30, 'Alquien123s@gmail.com', 'Alquien', 'sd', 'Capuyan', NULL, NULL, 'Alquien123s@gmail.com', '123123123', 25, 2, 1, NULL, '$2y$10$J0q6QGloqyh8rbeWSRW5cONFsBahlwvLwFB1gq4eaakWoX39F3mSS', '2026-04-28 08:09:44', NULL, NULL, 0, NULL),
(31, 'Pharmacy@gmail.com', 'Pharmacy', 'Pharmacy', 'Pharmacy', NULL, NULL, 'Pharmacy@gmail.com', 'Pharmacy', 26, 2, 1, NULL, '$2y$10$KBiRDhahiAFmXnIpCSha1uIypp1C0hVuj7o7WqORH4VOT7AgIOQ5u', '2026-04-28 08:11:09', NULL, NULL, 0, NULL),
(33, 'Pharmacy2@gmail.com', 'Pharmacy2@gmail.com', 'Pharmacy2@gmail.com', 'Pharmacy2@gmail.com', NULL, NULL, 'Pharmacy2@gmail.com', 'Pharmacy2@gmail', 28, 2, 1, NULL, '$2y$10$/7mOYyvwgzYeFrIH.s9xKO4.IvZXavqSb4u3dB4FuWnbSo6tBHAyy', '2026-04-28 08:22:07', NULL, NULL, 0, NULL),
(35, 'Princess', 'Dayan', NULL, 'Balansag', NULL, NULL, 'dayanbalansag@gmail.com', '09123', 30, 2, 1, NULL, '$2y$10$kzeD7inDKdWyJ6Fujg9P8.SbMyB3vjptWUFSaCSgjd9z6.krkginm', '2026-04-28 09:44:17', NULL, NULL, 0, NULL),
(36, 'yael@gmail.com', 'yael', 'yael', 'yael', NULL, NULL, 'yael@gmail.com', 'yael', 31, 1, 1, NULL, '$2y$10$JndPFWwPbpcxIwqvTKZNruBY8FTuqkUJEW3wmXPk6P/055dV161ly', '2026-04-28 09:59:25', NULL, NULL, 0, NULL),
(37, 'Cedric@gmail.oom', 'Cedric', NULL, 'Cagas', NULL, NULL, 'Cedric@gmail.oom', 'Cedric@gmail.oo', 32, 2, 1, NULL, '$2y$10$78hXUBSyxOB6qcUBZdlijuujxYNA8gwopYZ6p7uwcYqQFuoa7WjSa', '2026-04-28 10:01:51', NULL, NULL, 0, NULL),
(38, 'Alkwen', 'Alquien', 'Engaling', 'Capuyan', NULL, NULL, 'alquiencapuyan101@gmail.com', '092434', 33, 1, 1, NULL, '$2y$10$rmwiuyp7IRXX52Gz7M3IK.0ct8jLh122.3gDmgxdz2SAqeCJmPaIS', '2026-04-28 10:27:33', NULL, NULL, 0, NULL),
(39, 'testpharmacist', 'Test', '', 'Pharmacist', 'F', '2000-01-01', 'testpharmacist@gmail.com', '0', NULL, 2, 1, '/pic/default.jpg', '$2y$10$hdM5hyrKHdv9reU7IdJViu0CCbcYW6Sn0iTsLJV2i81xnLjB04dQO', '2026-10-19 20:32:14', NULL, NULL, 0, NULL),
(40, 'hzlsangster', 'Hazel', NULL, 'Carillo', 'F', '2006-09-14', 'hazelannmcarillo@gmail.com', '09763041287', 43, 1, 1, NULL, '$2y$10$Oqqdsm9n.RN7nfYZdfhoN.tCCJrFybnSpmRu8gIGjO3cU7F14W.XK', '2026-11-22 15:27:41', NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `02_user_roles`
--

CREATE TABLE `02_user_roles` (
  `Role_ID` int NOT NULL,
  `Role_Name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `02_user_roles`
--

INSERT INTO `02_user_roles` (`Role_ID`, `Role_Name`) VALUES
(1, 'Patient/Client'),
(2, 'Pharmacist'),
(3, 'Pharmacy Owner'),
(4, 'System Admin');

-- --------------------------------------------------------

--
-- Table structure for table `03_user_status`
--

CREATE TABLE `03_user_status` (
  `Status_ID` int NOT NULL,
  `Status_Name` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `03_user_status`
--

INSERT INTO `03_user_status` (`Status_ID`, `Status_Name`) VALUES
(1, 'Active'),
(2, 'Inactive'),
(3, 'Suspended');

-- --------------------------------------------------------

--
-- Table structure for table `04_user_approval`
--

CREATE TABLE `04_user_approval` (
  `Approval_ID` int NOT NULL,
  `Approval_Status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `04_user_approval`
--

INSERT INTO `04_user_approval` (`Approval_ID`, `Approval_Status`) VALUES
(1, 'Pending'),
(2, 'Approved'),
(3, 'Rejected'),
(4, 'Not Requested'),
(5, 'Suspended');

-- --------------------------------------------------------

--
-- Table structure for table `05_address_user_address`
--

CREATE TABLE `05_address_user_address` (
  `Address_ID` int NOT NULL,
  `User_ID` int DEFAULT NULL,
  `Province_ID` int DEFAULT NULL,
  `City_ID` int DEFAULT NULL,
  `Barangay_ID` int DEFAULT NULL,
  `Street` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `05_address_user_address`
--

INSERT INTO `05_address_user_address` (`Address_ID`, `User_ID`, `Province_ID`, `City_ID`, `Barangay_ID`, `Street`) VALUES
(1, 1, 1, 1, 2, 'Purok 2B'),
(2, 2, 1, 1, 1, 'Fortich Street'),
(3, 3, 1, 1, 3, 'Capitol Drive'),
(4, 4, 1, 1, 4, 'National Highway'),
(5, 5, 1, 1, 5, 'Dahl St.'),
(6, 12, 1, 1, 3, 'd3'),
(7, 13, 1, 1, 10, 'Purok2b'),
(8, 14, 1, 1, 4, '2'),
(9, 15, 1, 1, 1, 'Purok2b'),
(10, 16, 1, 1, 10, 'd3'),
(11, 17, 1, 1, 10, 'd3'),
(12, 18, 1, 1, 1, 'Purok2b'),
(13, 19, 1, 1, 1, 'Purok2b'),
(14, 20, 1, 1, 4, 'd3'),
(15, 21, 1, 1, 4, '123'),
(16, 22, 1, 1, 4, 'Purok2b'),
(17, 23, 1, 1, 4, '32343'),
(18, 24, 1, 1, 7, '32343'),
(19, 25, 1, 1, 2, 'Ofelia Village'),
(20, 26, 1, 1, 7, '1232'),
(21, 27, 1, 1, 1, 'Purok2b'),
(22, 28, 1, 1, 2, '32343'),
(23, NULL, 1, 1, 7, '32343'),
(25, 30, 1, 1, 7, '2'),
(26, 31, 1, 1, 4, 'Pharmacy'),
(28, 33, 1, 1, 7, 'Pharmacy'),
(30, 35, 1, 1, 7, 'Purok2b'),
(31, 36, 1, 1, 7, 'd3'),
(32, 37, 1, 1, 4, 'd3'),
(33, 38, 1, 1, 7, 'Purok 2B'),
(42, 39, NULL, NULL, NULL, ''),
(43, 40, 1, 1, 7, 'Purok 6-A L.F. Dayyo Subdivision');

-- --------------------------------------------------------

--
-- Table structure for table `06_address_province`
--

CREATE TABLE `06_address_province` (
  `Province_ID` int NOT NULL,
  `Province_Name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `06_address_province`
--

INSERT INTO `06_address_province` (`Province_ID`, `Province_Name`) VALUES
(1, 'Bukidnon');

-- --------------------------------------------------------

--
-- Table structure for table `07_address_city`
--

CREATE TABLE `07_address_city` (
  `City_ID` int NOT NULL,
  `Province_ID` int DEFAULT NULL,
  `City_Name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `07_address_city`
--

INSERT INTO `07_address_city` (`City_ID`, `Province_ID`, `City_Name`) VALUES
(1, 1, 'Malaybalay City');

-- --------------------------------------------------------

--
-- Table structure for table `08_address_barangay`
--

CREATE TABLE `08_address_barangay` (
  `Barangay_ID` int NOT NULL,
  `City_ID` int DEFAULT NULL,
  `Barangay_Name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `08_address_barangay`
--

INSERT INTO `08_address_barangay` (`Barangay_ID`, `City_ID`, `Barangay_Name`) VALUES
(1, 1, 'Poblacion'),
(2, 1, 'Casisang'),
(3, 1, 'Sumpong'),
(4, 1, 'Aglayan'),
(5, 1, 'Dalwangan'),
(6, 1, 'San Jose'),
(7, 1, 'Bangcud'),
(8, 1, 'Impalambong'),
(9, 1, 'Kalasungay'),
(10, 1, 'Patpat');

-- --------------------------------------------------------

--
-- Table structure for table `09_pharmacies`
--

CREATE TABLE `09_pharmacies` (
  `Pharmacy_ID` int NOT NULL,
  `Pharmacy_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Owner_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Address_ID` int DEFAULT NULL,
  `Phone` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `User_ID` int DEFAULT NULL,
  `Approval_ID` int DEFAULT NULL,
  `Logo_URL` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Pic_URL` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DateCreated` datetime DEFAULT CURRENT_TIMESTAMP,
  `Date_Approved` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `09_pharmacies`
--

INSERT INTO `09_pharmacies` (`Pharmacy_ID`, `Pharmacy_name`, `Owner_name`, `Address_ID`, `Phone`, `User_ID`, `Approval_ID`, `Logo_URL`, `Pic_URL`, `DateCreated`, `Date_Approved`) VALUES
(1, 'Rose Pharmacy', 'Maria Rose Dela Vega', 1, '08822001001', 3, 2, '', '../08_Uploads/Pharmacy_Image/Generika.png', '2026-04-16 17:38:28', NULL),
(2, 'Rose Pharmacy Sayre', 'Maria Rose Dela Vega', 2, '08822001002', 3, 2, NULL, NULL, '2026-04-16 17:38:28', NULL),
(3, 'Mercury Drug', 'Carlos Tan Jr.', 3, '08822002001', 11, 2, NULL, NULL, '2026-04-16 17:38:28', NULL),
(4, '', NULL, NULL, '', 30, 1, NULL, NULL, '2026-04-28 08:09:44', NULL),
(5, '', NULL, NULL, '', 31, 1, NULL, NULL, '2026-04-28 08:11:09', NULL),
(6, '', NULL, 28, '', 33, 1, NULL, NULL, '2026-04-28 08:22:07', NULL),
(7, '', NULL, 30, '', 35, 1, NULL, NULL, '2026-04-28 09:44:17', NULL),
(8, '', NULL, 32, '', 37, 1, NULL, NULL, '2026-04-28 10:01:51', NULL),
(9, 'Test Pharmacy', 'Test Pharmacist', NULL, '09000000002', 39, 2, NULL, NULL, '2026-10-19 20:35:27', '2026-10-19 20:35:27');

-- --------------------------------------------------------

--
-- Table structure for table `12_meds_medicines`
--

CREATE TABLE `12_meds_medicines` (
  `Medicine_ID` int NOT NULL,
  `Gen_ID` int DEFAULT NULL,
  `Brand_ID` int DEFAULT NULL,
  `DosageForm_ID` int DEFAULT NULL,
  `Dosage_ID` int DEFAULT NULL,
  `Package_Size` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Pieces_Per_Box` int NOT NULL DEFAULT '1',
  `Is_Essential` tinyint(1) NOT NULL DEFAULT '0',
  `DosageVal_ID` int DEFAULT NULL,
  `Unit_ID` int DEFAULT NULL,
  `Category_ID` int DEFAULT NULL,
  `Manufacturer_ID` int DEFAULT NULL,
  `Require_rx_ID` int DEFAULT NULL,
  `IsDeleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `12_meds_medicines`
--

INSERT INTO `12_meds_medicines` (`Medicine_ID`, `Gen_ID`, `Brand_ID`, `DosageForm_ID`, `Dosage_ID`, `Package_Size`, `Pieces_Per_Box`, `Is_Essential`, `DosageVal_ID`, `Unit_ID`, `Category_ID`, `Manufacturer_ID`, `Require_rx_ID`, `IsDeleted`) VALUES
(1, 1, 1, 1, 1, NULL, 100, 1, 1, 1, 1, 2, 1, 0),
(2, 2, 2, 2, 1, NULL, 100, 1, 1, 1, 2, 1, 2, 0),
(3, 1, 1, 1, 2, NULL, 100, 1, 2, 1, 1, 1, 2, 0),
(4, 3, 3, 2, 23, NULL, 100, 1, 3, 1, 3, 4, 2, 0),
(5, 4, 4, 2, 4, NULL, 30, 1, 4, 1, 5, 5, 1, 0),
(6, 5, 5, 2, 1, NULL, 100, 1, 1, 1, 6, 1, 2, 0),
(7, 6, 6, 1, 1, NULL, 100, 1, 1, 1, 6, 1, 2, 0),
(8, 7, 7, 2, 1, NULL, 100, 1, 1, 1, 3, 4, 1, 0),
(9, 8, 8, 2, 6, NULL, 100, 1, 5, 1, 5, 4, 1, 0),
(10, 9, 9, 2, 1, NULL, 100, 1, 1, 1, 7, 6, 1, 0),
(11, 10, 10, 2, 6, NULL, 100, 1, 5, 1, 8, 1, 2, 0),
(12, 11, 11, 2, 24, NULL, 100, 0, 6, 1, 9, 7, 2, 0),
(13, 12, 12, 1, 1, NULL, 100, 0, 1, 1, 3, 1, 2, 0),
(14, 13, 13, 2, 1, NULL, 100, 0, 1, 1, 6, 1, 2, 0),
(15, 14, 14, 2, 25, NULL, 100, 0, 7, 1, 10, 1, 2, 0),
(16, 15, 15, 3, 19, '60 mL', 1, 1, 8, 5, 3, 8, 2, 0),
(17, 16, 16, 3, 20, '120 mL', 1, 1, 9, 5, 11, 1, 2, 0),
(18, 17, 17, 2, 26, NULL, 100, 1, 10, 1, 6, 3, 1, 0),
(19, 18, 18, 1, 5, NULL, 100, 1, 11, 1, 10, 9, 1, 0),
(20, 19, 19, 1, 1, NULL, 100, 1, 1, 1, 4, 1, 1, 0),
(21, 20, 20, 2, 3, NULL, 100, 1, 9, 1, 11, 10, 2, 0),
(22, 21, 21, 2, 1, NULL, 100, 1, 1, 1, 11, 1, 2, 0),
(23, 22, 22, 2, 27, NULL, 100, 1, 12, 1, 5, 11, 1, 0),
(24, 23, 23, 2, 1, NULL, 100, 1, 1, 1, 4, 4, 1, 0),
(25, 24, 24, 2, 6, NULL, 100, 0, 5, 1, 10, 11, 2, 0),
(26, 25, 25, 2, 6, NULL, 100, 0, 5, 1, 10, 11, 2, 0),
(27, 26, 26, 2, 7, NULL, 100, 0, 13, 1, 10, 11, 2, 0),
(28, 27, 27, 2, 28, NULL, 100, 1, 14, 1, 3, 12, 2, 0),
(29, 28, 28, 3, 21, '100 mL', 1, 1, 15, 5, 6, 13, 2, 0),
(30, 29, 29, 2, 1, NULL, 20, 1, 1, 1, 4, 14, 1, 0),
(31, 30, 30, 2, 5, NULL, 100, 1, 11, 1, 5, 15, 1, 0),
(32, 31, 31, 2, 6, NULL, 100, 1, 5, 1, 8, 14, 2, 0),
(33, 32, 32, 10, 22, '120 mL', 1, 1, 16, 8, 12, 16, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `13_meds_generic_name`
--

CREATE TABLE `13_meds_generic_name` (
  `Gen_ID` int NOT NULL,
  `Generic_Name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `13_meds_generic_name`
--

INSERT INTO `13_meds_generic_name` (`Gen_ID`, `Generic_Name`) VALUES
(1, 'Amoxicillin'),
(2, 'Paracetamol'),
(3, 'Ibuprofen'),
(4, 'Losartan Potassium'),
(5, 'Phenylephrine + Paracetamol'),
(6, 'Carbocisteine'),
(7, 'Mefenamic Acid'),
(8, 'Amlodipine Besylate'),
(9, 'Metformin Hydrochloride'),
(10, 'Cetirizine Dihydrochloride'),
(11, 'Vitex Negundo (Lagundi)'),
(12, 'Ibuprofen + Paracetamol'),
(13, 'Phenylephrine + Chlorphenamine + Paracetamol'),
(14, 'Aluminum + Magnesium Hydroxide + Simethicone'),
(15, 'Paracetamol Pediatric Oral Solution'),
(16, 'Ascorbic Acid + Zinc'),
(17, 'Salbutamol Sulfate'),
(18, 'Omeprazole'),
(19, 'Cefalexin Monohydrate'),
(20, 'Vitamin B-Complex (B1, B6, B12)'),
(21, 'Multivitamins + Iron'),
(22, 'Clopidogrel'),
(23, 'Azithromycin'),
(24, 'Dicycloverine + Paracetamol'),
(25, 'Hyoscine N-Butylbromide'),
(26, 'Bisacodyl'),
(27, 'Naproxen Sodium'),
(28, 'Dextromethorphan + Guaifenesin'),
(29, 'Ciprofloxacin'),
(30, 'Simvastatin'),
(31, 'Loratadine'),
(32, 'Povidone-Iodine 1% Solution');

-- --------------------------------------------------------

--
-- Table structure for table `14_meds_brand_name`
--

CREATE TABLE `14_meds_brand_name` (
  `Brand_ID` int NOT NULL,
  `Brand_Name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `14_meds_brand_name`
--

INSERT INTO `14_meds_brand_name` (`Brand_ID`, `Brand_Name`) VALUES
(1, 'Amoxil'),
(2, 'Biogesic'),
(3, 'Advil'),
(4, 'Cozaar'),
(5, 'Neozep Forte'),
(6, 'Solmux'),
(7, 'Ponstan'),
(8, 'Norvasc'),
(9, 'Glucophage'),
(10, 'Alnix'),
(11, 'Ascof'),
(12, 'Alaxan FR'),
(13, 'Bioflu'),
(14, 'Kremil-S'),
(15, 'Tempra'),
(16, 'Ceelin Plus'),
(17, 'Ventolin'),
(18, 'Omez'),
(19, 'Ceporex'),
(20, 'Neurobion'),
(21, 'Enervon-C'),
(22, 'Plavix'),
(23, 'Zithromax'),
(24, 'Buscopan Venus'),
(25, 'Buscopan'),
(26, 'Dulcolax'),
(27, 'Flanax'),
(28, 'Robitussin DM'),
(29, 'Ciprobay'),
(30, 'Zocor'),
(31, 'Claritin'),
(32, 'Betadine / Bactidol');

-- --------------------------------------------------------

--
-- Table structure for table `15_meds_dosage_form`
--

CREATE TABLE `15_meds_dosage_form` (
  `DosageForm_ID` int NOT NULL,
  `Dosage_Form` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `15_meds_dosage_form`
--

INSERT INTO `15_meds_dosage_form` (`DosageForm_ID`, `Dosage_Form`) VALUES
(1, 'Capsule'),
(2, 'Tablet'),
(3, 'Syrup'),
(4, 'Suspension'),
(5, 'Injection'),
(6, 'Drops'),
(7, 'Ointment'),
(8, 'Cream'),
(9, 'Inhaler'),
(10, 'Solution');

-- --------------------------------------------------------

--
-- Table structure for table `16_meds_dosages`
--

CREATE TABLE `16_meds_dosages` (
  `Dosage_ID` int NOT NULL,
  `Dosage_Strength` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `16_meds_dosages`
--

INSERT INTO `16_meds_dosages` (`Dosage_ID`, `Dosage_Strength`) VALUES
(22, '1 %'),
(17, '1%'),
(6, '10 mg'),
(3, '100 mg'),
(14, '100 mg / mL'),
(20, '100 mg/5mL'),
(8, '120 mg / 5 mL'),
(19, '120 mg/5mL'),
(9, '125 mg / 5 mL'),
(16, '15 mg / 0.6 mL'),
(21, '15 mg/5mL'),
(12, '156.25 mg / 5 mL'),
(25, '178 mg'),
(26, '2 mg'),
(15, '2.5 mg / mL'),
(18, '2%'),
(5, '20 mg'),
(23, '200 mg'),
(11, '200 mg / 5 mL'),
(13, '228.5 mg / 5 mL'),
(2, '250 mg'),
(10, '250 mg / 5 mL'),
(24, '300 mg'),
(7, '5 mg'),
(4, '50 mg'),
(1, '500 mg'),
(28, '550 mg'),
(27, '75 mg');

-- --------------------------------------------------------

--
-- Table structure for table `16_meds_dosage_value`
--

CREATE TABLE `16_meds_dosage_value` (
  `DosageVal_ID` int NOT NULL,
  `Dosage_Value` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `16_meds_dosage_value`
--

INSERT INTO `16_meds_dosage_value` (`DosageVal_ID`, `Dosage_Value`) VALUES
(1, '500'),
(2, '250'),
(3, '200'),
(4, '50'),
(5, '10'),
(6, '300'),
(7, '178'),
(8, '120'),
(9, '100'),
(10, '2'),
(11, '20'),
(12, '75'),
(13, '5'),
(14, '550'),
(15, '15'),
(16, '1');

-- --------------------------------------------------------

--
-- Table structure for table `17_meds_dosage_unit`
--

CREATE TABLE `17_meds_dosage_unit` (
  `Unit_ID` int NOT NULL,
  `Unit_Name` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `17_meds_dosage_unit`
--

INSERT INTO `17_meds_dosage_unit` (`Unit_ID`, `Unit_Name`) VALUES
(1, 'mg'),
(2, 'g'),
(3, 'mcg'),
(4, 'mL'),
(5, 'mg/5mL'),
(6, 'mg/mL'),
(7, 'mcg/mL'),
(8, '%'),
(9, 'IU'),
(10, 'drops');

-- --------------------------------------------------------

--
-- Table structure for table `18_meds_categories`
--

CREATE TABLE `18_meds_categories` (
  `Category_ID` int NOT NULL,
  `Category_Name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `18_meds_categories`
--

INSERT INTO `18_meds_categories` (`Category_ID`, `Category_Name`) VALUES
(1, 'Antibiotic'),
(2, 'Analgesic'),
(3, 'Analgesics & Antipyretics'),
(4, 'Antibiotics'),
(5, 'Cardiovascular'),
(6, 'Cold & Respiratory'),
(7, 'Endocrine & Metabolic'),
(8, 'Antihistamines'),
(9, 'Herbal & Respiratory'),
(10, 'Gastrointestinal'),
(11, 'Vitamins & Minerals'),
(12, 'Antiseptics & Topical');

-- --------------------------------------------------------

--
-- Table structure for table `19_meds_manufacturer`
--

CREATE TABLE `19_meds_manufacturer` (
  `Manufacturer_ID` int NOT NULL,
  `Manufacturer_Name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `19_meds_manufacturer`
--

INSERT INTO `19_meds_manufacturer` (`Manufacturer_ID`, `Manufacturer_Name`) VALUES
(1, 'Unilab'),
(2, 'GSK'),
(3, 'GlaxoSmithKline'),
(4, 'Pfizer'),
(5, 'Organon Philippines'),
(6, 'Merck'),
(7, 'Pascual Laboratories'),
(8, 'Taisho Pharmaceuticals'),
(9, 'Torrent Pharma'),
(10, 'P&G Health'),
(11, 'Sanofi'),
(12, 'Bayer Philippines'),
(13, 'Haleon'),
(14, 'Bayer'),
(15, 'Organon'),
(16, 'Mundipharma');

-- --------------------------------------------------------

--
-- Table structure for table `20_meds_requires_rx`
--

CREATE TABLE `20_meds_requires_rx` (
  `Require_rx_ID` int NOT NULL,
  `Requirement_Status` varchar(10) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `20_meds_requires_rx`
--

INSERT INTO `20_meds_requires_rx` (`Require_rx_ID`, `Requirement_Status`) VALUES
(1, 'Yes'),
(2, 'No');

-- --------------------------------------------------------

--
-- Table structure for table `21_inventory`
--

CREATE TABLE `21_inventory` (
  `Inventory_ID` int NOT NULL,
  `Pharmacy_ID` int DEFAULT NULL,
  `Medicine_ID` int DEFAULT NULL,
  `Batch_Number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Quantity` int NOT NULL DEFAULT '0',
  `Low_Stock_Threshold` int NOT NULL DEFAULT '10',
  `Price_Unit_ID` int DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `Expiry_date` date DEFAULT NULL,
  `Last_updated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `IsDeleted` tinyint(1) NOT NULL DEFAULT '0',
  `DeletedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `21_inventory`
--

INSERT INTO `21_inventory` (`Inventory_ID`, `Pharmacy_ID`, `Medicine_ID`, `Batch_Number`, `Quantity`, `Low_Stock_Threshold`, `Price_Unit_ID`, `Price`, `Expiry_date`, `Last_updated`, `IsDeleted`, `DeletedAt`) VALUES
(1, 1, 1, 'LOT-2026A', 100, 10, 1, 15.00, '2025-12-31', '2026-11-22 14:35:42', 0, NULL),
(2, 2, 1, 'LOT-2026A', 50, 10, 1, 15.50, '2025-12-31', '2026-11-22 14:35:42', 0, NULL),
(3, 3, 2, 'LOT-2026A', 500, 10, 1, 5.00, '2026-06-30', '2026-11-22 14:35:42', 0, NULL),
(4, 9, 3, 'LOT-2026A', 50, 10, 1, 1.00, '2026-11-24', '2026-11-22 14:35:42', 0, NULL),
(5, 9, 1, 'LOT-2026-005', 120, 10, NULL, 8.50, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(6, 9, 3, 'LOT-2026-006', 8, 10, NULL, 14.00, '2026-12-08', '2026-11-22 16:51:12', 0, NULL),
(7, 9, 2, 'LOT-2026-007', 45, 10, NULL, 12.50, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(8, 9, 2, 'LOT-2026-008', 150, 10, NULL, 8.50, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(9, 9, 1, 'LOT-2026-009', 8, 10, NULL, 14.00, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(10, 9, 4, 'LOT-2026-010', 85, 10, NULL, 9.50, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(11, 9, 5, 'LOT-2026-011', 5, 10, NULL, 18.50, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(12, 9, 6, 'LOT-2026-012', 95, 10, NULL, 9.00, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(13, 9, 7, 'LOT-2026-013', 45, 10, NULL, 13.50, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(14, 9, 8, 'LOT-2026-014', 0, 10, NULL, 22.00, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(15, 9, 9, 'LOT-2026-015', 60, 10, NULL, 19.50, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(16, 9, 10, 'LOT-2026-016', 110, 10, NULL, 11.00, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(17, 9, 11, 'LOT-2026-017', 6, 10, NULL, 16.00, '2026-12-10', '2026-11-22 16:51:12', 0, NULL),
(18, 9, 12, 'LOT-2026-018', 75, 10, NULL, 10.00, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(19, 9, 13, 'LOT-2026-019', 90, 10, NULL, 11.50, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(20, 9, 14, 'LOT-2026-020', 105, 10, NULL, 9.50, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(21, 9, 15, 'LOT-2026-021', 65, 10, NULL, 8.00, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(22, 9, 16, 'LOT-2026-022', 22, 10, NULL, 125.00, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(23, 9, 17, 'LOT-2026-023', 4, 10, NULL, 145.00, '2026-12-14', '2026-11-22 16:51:12', 0, NULL),
(24, 9, 18, 'LOT-2026-024', 0, 10, NULL, 6.50, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(25, 9, 19, 'LOT-2026-025', 55, 10, NULL, 28.00, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(26, 9, 20, 'LOT-2026-026', 7, 10, NULL, 21.00, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(27, 9, 21, 'LOT-2026-027', 130, 10, NULL, 24.00, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(28, 9, 22, 'LOT-2026-028', 140, 10, NULL, 9.00, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(29, 9, 23, 'LOT-2026-029', 35, 10, NULL, 42.00, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(30, 9, 24, 'LOT-2026-030', 18, 10, NULL, 75.00, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(31, 9, 25, 'LOT-2026-031', 60, 10, NULL, 19.50, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(32, 9, 26, 'LOT-2026-032', 80, 10, NULL, 17.00, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(33, 9, 27, 'LOT-2026-033', 50, 10, NULL, 12.00, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(34, 9, 28, 'LOT-2026-034', 70, 10, NULL, 16.50, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(35, 9, 29, 'LOT-2026-035', 15, 10, NULL, 165.00, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(36, 9, 30, 'LOT-2026-036', 20, 10, NULL, 48.00, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(37, 9, 31, 'LOT-2026-037', 40, 10, NULL, 26.00, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(38, 9, 32, 'LOT-2026-038', 30, 10, NULL, 28.50, '2027-11-22', '2026-11-22 16:51:12', 0, NULL),
(39, 9, 33, 'LOT-2026-039', 25, 10, NULL, 135.00, '2027-11-22', '2026-11-22 16:51:12', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `22_inventory_unit_price`
--

CREATE TABLE `22_inventory_unit_price` (
  `Price_Unit_ID` int NOT NULL,
  `Unit` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `22_inventory_unit_price`
--

INSERT INTO `22_inventory_unit_price` (`Price_Unit_ID`, `Unit`) VALUES
(1, 'pcs'),
(2, 'box');

-- --------------------------------------------------------

--
-- Table structure for table `23_pharmacy_images`
--

CREATE TABLE `23_pharmacy_images` (
  `Image_ID` int NOT NULL,
  `Pharmacy_ID` int DEFAULT NULL,
  `Image_Path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Description` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Date_Uploaded` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `24_medicine_images`
--

CREATE TABLE `24_medicine_images` (
  `Image_ID` int NOT NULL,
  `Medicine_ID` int DEFAULT NULL,
  `Image_Path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Is_Primary` tinyint(1) DEFAULT '0',
  `Date_Uploaded` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `24_medicine_images`
--

INSERT INTO `24_medicine_images` (`Image_ID`, `Medicine_ID`, `Image_Path`, `Is_Primary`, `Date_Uploaded`) VALUES
(1, 2, '../07_Assets/images/medicines/Paracetamol.png', 1, '2026-11-22 15:31:48'),
(2, 1, '../07_Assets/images/medicines/Amoxicilin.png', 1, '2026-11-22 15:31:48'),
(3, 4, '../07_Assets/images/medicines/Ibuprofen.png', 1, '2026-11-22 15:31:48'),
(4, 5, '../07_Assets/images/medicines/Cozaar.png', 1, '2026-11-22 15:31:48'),
(5, 6, '../07_Assets/images/medicines/Paracetamol.png', 1, '2026-11-22 15:31:48'),
(6, 7, '../07_Assets/images/medicines/Solmux.jpg', 1, '2026-11-22 15:31:48'),
(7, 8, '../07_Assets/images/medicines/Advil.png', 1, '2026-11-22 15:31:48'),
(8, 9, '../07_Assets/images/medicines/Cozaar.png', 1, '2026-11-22 15:31:48'),
(9, 10, '../07_Assets/images/medicines/Glucophage.jpg', 1, '2026-11-22 15:31:48'),
(10, 11, '../07_Assets/images/medicines/Allerta.png', 1, '2026-11-22 15:31:48'),
(11, 12, '../07_Assets/images/medicines/Solmux.jpg', 1, '2026-11-22 15:31:48'),
(12, 13, '../07_Assets/images/medicines/Advil.png', 1, '2026-11-22 15:31:48'),
(13, 14, '../07_Assets/images/medicines/Paracetamol.png', 1, '2026-11-22 15:31:48'),
(14, 15, '../07_Assets/images/medicines/Glucophage.jpg', 1, '2026-11-22 15:31:49'),
(15, 16, '../07_Assets/images/medicines/Paracetamol.png', 1, '2026-11-22 15:31:49'),
(16, 17, '../07_Assets/images/medicines/Enervon-C.jpg', 1, '2026-11-22 15:31:49'),
(17, 18, '../07_Assets/images/medicines/Solmux.jpg', 1, '2026-11-22 15:31:49'),
(18, 19, '../07_Assets/images/medicines/Glucophage.jpg', 1, '2026-11-22 15:31:49'),
(19, 20, '../07_Assets/images/medicines/Amoxicilin.png', 1, '2026-11-22 15:31:49'),
(20, 21, '../07_Assets/images/medicines/Neurobion.jpg', 1, '2026-11-22 15:31:49'),
(21, 22, '../07_Assets/images/medicines/Enervon-C.jpg', 1, '2026-11-22 15:31:49'),
(22, 23, '../07_Assets/images/medicines/Cozaar.png', 1, '2026-11-22 15:31:49'),
(23, 24, '../07_Assets/images/medicines/Amoxicilin.png', 1, '2026-11-22 15:31:49'),
(24, 25, '../07_Assets/images/medicines/Paracetamol.png', 1, '2026-11-22 15:31:49'),
(25, 26, '../07_Assets/images/medicines/Glucophage.jpg', 1, '2026-11-22 15:31:49'),
(26, 27, '../07_Assets/images/medicines/Glucophage.jpg', 1, '2026-11-22 15:31:49'),
(27, 28, '../07_Assets/images/medicines/Advil.png', 1, '2026-11-22 15:31:49'),
(28, 29, '../07_Assets/images/medicines/Solmux.jpg', 1, '2026-11-22 15:31:49'),
(29, 30, '../07_Assets/images/medicines/Amoxicilin.png', 1, '2026-11-22 15:31:49'),
(30, 31, '../07_Assets/images/medicines/Cozaar.png', 1, '2026-11-22 15:31:49'),
(31, 32, '../07_Assets/images/medicines/Allerta.png', 1, '2026-11-22 15:31:49'),
(32, 33, '../07_Assets/images/logo.png', 1, '2026-11-22 15:31:49');

-- --------------------------------------------------------

--
-- Table structure for table `25_user_prescriptions`
--

CREATE TABLE `25_user_prescriptions` (
  `Prescription_ID` int NOT NULL,
  `User_ID` int DEFAULT NULL,
  `Image_Path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Notes` text COLLATE utf8mb4_general_ci,
  `Date_Uploaded` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `26_pos_sales`
--

CREATE TABLE `26_pos_sales` (
  `Sale_ID` int NOT NULL,
  `Receipt_No` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Pharmacy_ID` int NOT NULL,
  `User_ID` int NOT NULL,
  `Customer_Name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT 'Walk-in Customer',
  `Subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Discount_Type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'None',
  `Discount_Amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Grand_Total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Cash_Tendered` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Change_Amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Payment_Method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Cash',
  `Sale_Date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `26_pos_sales`
--

INSERT INTO `26_pos_sales` (`Sale_ID`, `Receipt_No`, `Pharmacy_ID`, `User_ID`, `Customer_Name`, `Subtotal`, `Discount_Type`, `Discount_Amount`, `Grand_Total`, `Cash_Tendered`, `Change_Amount`, `Payment_Method`, `Sale_Date`) VALUES
(1, 'MF-20261121-S0001', 9, 39, 'Juan Dela Cruz', 4.00, 'None', 0.00, 4.00, 50.00, 46.00, 'Cash', '2026-11-21 23:31:38'),
(2, 'MF-20261122-S0002', 9, 39, 'Maria Santos (Senior)', 76.50, 'Senior/PWD', 15.30, 61.20, 100.00, 38.80, 'Cash', '2026-11-22 00:31:38'),
(3, 'MF-20261122-S0003', 9, 39, 'Elena Ramos', 84.00, 'None', 0.00, 84.00, 100.00, 16.00, 'Cash', '2026-11-22 06:31:38'),
(4, 'MF-20261122-S0004', 9, 39, 'Roberto Tan', 87.50, 'None', 0.00, 87.50, 100.00, 12.50, 'Cash', '2026-11-22 00:31:38'),
(5, 'MF-20261122-S0005', 9, 39, 'Angelo Bautista (PWD)', 2.00, 'Senior/PWD', 0.40, 1.60, 50.00, 48.40, 'Cash', '2026-11-22 06:31:38'),
(6, 'MF-20261117-S0006', 9, 39, 'Walk-in Customer', 85.00, 'None', 0.00, 85.00, 100.00, 15.00, 'Cash', '2026-11-17 23:31:38'),
(7, 'MF-20261119-S0007', 9, 39, 'Teresa Gomez', 28.00, 'None', 0.00, 28.00, 50.00, 22.00, 'Cash', '2026-11-19 06:31:38'),
(8, 'MF-20261116-S0008', 9, 39, 'Ricardo Lim', 125.00, 'None', 0.00, 125.00, 150.00, 25.00, 'Cash', '2026-11-16 07:31:38'),
(9, 'MF-20261116-S0009', 9, 39, 'Carmen Diaz', 7.00, 'None', 0.00, 7.00, 50.00, 43.00, 'Cash', '2026-11-16 00:31:38'),
(10, 'MF-20261113-S0010', 9, 39, 'Juan Dela Cruz', 76.50, 'None', 0.00, 76.50, 100.00, 23.50, 'Cash', '2026-11-13 01:31:38'),
(11, 'MF-20261103-S0011', 9, 39, 'Maria Santos (Senior)', 84.00, 'Senior/PWD', 16.80, 67.20, 100.00, 32.80, 'Cash', '2026-11-03 01:31:38'),
(12, 'MF-20261111-S0012', 9, 39, 'Elena Ramos', 25.00, 'None', 0.00, 25.00, 50.00, 25.00, 'Cash', '2026-11-11 01:31:38');

-- --------------------------------------------------------

--
-- Table structure for table `27_pos_sale_items`
--

CREATE TABLE `27_pos_sale_items` (
  `Item_ID` int NOT NULL,
  `Sale_ID` int NOT NULL,
  `Inventory_ID` int NOT NULL,
  `Medicine_ID` int NOT NULL,
  `Quantity` int NOT NULL,
  `Unit_Price` decimal(10,2) NOT NULL,
  `Total_Price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `27_pos_sale_items`
--

INSERT INTO `27_pos_sale_items` (`Item_ID`, `Sale_ID`, `Inventory_ID`, `Medicine_ID`, `Quantity`, `Unit_Price`, `Total_Price`) VALUES
(1, 1, 4, 3, 4, 1.00, 4.00),
(2, 2, 5, 1, 9, 8.50, 76.50),
(3, 3, 6, 3, 6, 14.00, 84.00),
(4, 4, 7, 2, 7, 12.50, 87.50),
(5, 5, 4, 3, 2, 1.00, 2.00),
(6, 6, 5, 1, 10, 8.50, 85.00),
(7, 7, 6, 3, 2, 14.00, 28.00),
(8, 8, 7, 2, 10, 12.50, 125.00),
(9, 9, 4, 3, 7, 1.00, 7.00),
(10, 10, 5, 1, 9, 8.50, 76.50),
(11, 11, 6, 3, 6, 14.00, 84.00),
(12, 12, 7, 2, 2, 12.50, 25.00);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_01_users`
-- (See below for the actual view)
--
CREATE TABLE `view_01_users` (
`Age` bigint
,`Birthdate` date
,`DateApproved` datetime(6)
,`DateCreated` datetime
,`Email` varchar(255)
,`First_name` varchar(100)
,`Full_Name` varchar(201)
,`Gender` varchar(20)
,`Last_name` varchar(100)
,`Middle_name` varchar(100)
,`Password_hash` varchar(300)
,`Phone` varchar(15)
,`Profilepic_url` varchar(255)
,`Role` varchar(50)
,`Role_ID` int
,`User_ID` int
,`Username` varchar(50)
,`UserStatus` varchar(20)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_02_address`
-- (See below for the actual view)
--
CREATE TABLE `view_02_address` (
`Address_ID` int
,`Barangay_Name` varchar(255)
,`City_Name` varchar(255)
,`Full_Address` text
,`Province_Name` varchar(255)
,`Street` varchar(255)
,`User_ID` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_03_user_address`
-- (See below for the actual view)
--
CREATE TABLE `view_03_user_address` (
`Barangay_Name` varchar(255)
,`City_Name` varchar(255)
,`Email` varchar(255)
,`Full_Address` text
,`Full_Name` varchar(201)
,`Phone` varchar(15)
,`Province_Name` varchar(255)
,`Role` varchar(50)
,`Street` varchar(255)
,`User_ID` int
,`UserStatus` varchar(20)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_04_pharmacy`
-- (See below for the actual view)
--
CREATE TABLE `view_04_pharmacy` (
`Approval_Status` varchar(20)
,`Barangay_Name` varchar(255)
,`City_Name` varchar(255)
,`Date_Approved` datetime
,`DateCreated` datetime
,`Full_Address` text
,`Logo_URL` varchar(255)
,`Owner_Email` varchar(255)
,`Owner_name` varchar(100)
,`Pharmacy_ID` int
,`Pharmacy_name` varchar(100)
,`Phone` varchar(15)
,`Pic_URL` varchar(255)
,`Province_Name` varchar(255)
,`Street` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_05_medicine_master`
-- (See below for the actual view)
--
CREATE TABLE `view_05_medicine_master` (
`Brand_Name` varchar(255)
,`Category_Name` varchar(100)
,`Dosage_Form` varchar(100)
,`Dosage_Strength` varchar(50)
,`Generic_Name` varchar(255)
,`Is_Essential` tinyint(1)
,`IsDeleted` tinyint(1)
,`Manufacturer_Name` varchar(255)
,`Medicine_ID` int
,`Package_Size` varchar(50)
,`Pieces_Per_Box` int
,`Require_rx_ID` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_06_inventory_stocks`
-- (See below for the actual view)
--
CREATE TABLE `view_06_inventory_stocks` (
`Availability_Status` varchar(12)
,`Batch_Number` varchar(50)
,`Brand_Name` varchar(255)
,`Category_Name` varchar(100)
,`Dosage` varchar(50)
,`Dosage_Form` varchar(100)
,`Expiry_date` date
,`Generic_Name` varchar(255)
,`Inventory_ID` int
,`Is_Essential` tinyint(1)
,`IsDeleted` tinyint(1)
,`Last_updated` datetime
,`Low_Stock_Threshold` int
,`Manufacturer_Name` varchar(255)
,`Medicine_ID` int
,`Package_Size` varchar(50)
,`Pharmacy_ID` int
,`Pharmacy_name` varchar(100)
,`Pieces_Per_Box` int
,`Price` decimal(10,2)
,`Price_Per` varchar(20)
,`Quantity` int
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `01_user_users`
--
ALTER TABLE `01_user_users`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `uq_email` (`Email`),
  ADD UNIQUE KEY `uq_phone` (`Phone`),
  ADD KEY `fk_user_address` (`Address_ID`),
  ADD KEY `fk_user_role` (`Role_ID`),
  ADD KEY `fk_user_status` (`User_Status_ID`);

--
-- Indexes for table `02_user_roles`
--
ALTER TABLE `02_user_roles`
  ADD PRIMARY KEY (`Role_ID`);

--
-- Indexes for table `03_user_status`
--
ALTER TABLE `03_user_status`
  ADD PRIMARY KEY (`Status_ID`);

--
-- Indexes for table `04_user_approval`
--
ALTER TABLE `04_user_approval`
  ADD PRIMARY KEY (`Approval_ID`);

--
-- Indexes for table `05_address_user_address`
--
ALTER TABLE `05_address_user_address`
  ADD PRIMARY KEY (`Address_ID`),
  ADD KEY `fk_addr_user` (`User_ID`),
  ADD KEY `fk_addr_province` (`Province_ID`),
  ADD KEY `fk_addr_city` (`City_ID`),
  ADD KEY `fk_addr_brgy` (`Barangay_ID`);

--
-- Indexes for table `06_address_province`
--
ALTER TABLE `06_address_province`
  ADD PRIMARY KEY (`Province_ID`);

--
-- Indexes for table `07_address_city`
--
ALTER TABLE `07_address_city`
  ADD PRIMARY KEY (`City_ID`),
  ADD KEY `fk_city_prov` (`Province_ID`);

--
-- Indexes for table `08_address_barangay`
--
ALTER TABLE `08_address_barangay`
  ADD PRIMARY KEY (`Barangay_ID`),
  ADD KEY `fk_brgy_city` (`City_ID`);

--
-- Indexes for table `09_pharmacies`
--
ALTER TABLE `09_pharmacies`
  ADD PRIMARY KEY (`Pharmacy_ID`),
  ADD KEY `fk_pharmacy_address` (`Address_ID`),
  ADD KEY `fk_pharmacy_user` (`User_ID`),
  ADD KEY `fk_pharmacy_approval` (`Approval_ID`);

--
-- Indexes for table `12_meds_medicines`
--
ALTER TABLE `12_meds_medicines`
  ADD PRIMARY KEY (`Medicine_ID`),
  ADD KEY `fk_med_generic` (`Gen_ID`),
  ADD KEY `fk_med_brand` (`Brand_ID`),
  ADD KEY `fk_med_form` (`DosageForm_ID`),
  ADD KEY `fk_med_value` (`DosageVal_ID`),
  ADD KEY `fk_med_unit` (`Unit_ID`),
  ADD KEY `fk_med_category` (`Category_ID`),
  ADD KEY `fk_med_manufacturer` (`Manufacturer_ID`),
  ADD KEY `fk_med_rx` (`Require_rx_ID`),
  ADD KEY `fk_meds_dosage` (`Dosage_ID`);

--
-- Indexes for table `13_meds_generic_name`
--
ALTER TABLE `13_meds_generic_name`
  ADD PRIMARY KEY (`Gen_ID`);

--
-- Indexes for table `14_meds_brand_name`
--
ALTER TABLE `14_meds_brand_name`
  ADD PRIMARY KEY (`Brand_ID`);

--
-- Indexes for table `15_meds_dosage_form`
--
ALTER TABLE `15_meds_dosage_form`
  ADD PRIMARY KEY (`DosageForm_ID`);

--
-- Indexes for table `16_meds_dosages`
--
ALTER TABLE `16_meds_dosages`
  ADD PRIMARY KEY (`Dosage_ID`),
  ADD UNIQUE KEY `Dosage_Strength` (`Dosage_Strength`);

--
-- Indexes for table `16_meds_dosage_value`
--
ALTER TABLE `16_meds_dosage_value`
  ADD PRIMARY KEY (`DosageVal_ID`);

--
-- Indexes for table `17_meds_dosage_unit`
--
ALTER TABLE `17_meds_dosage_unit`
  ADD PRIMARY KEY (`Unit_ID`);

--
-- Indexes for table `18_meds_categories`
--
ALTER TABLE `18_meds_categories`
  ADD PRIMARY KEY (`Category_ID`);

--
-- Indexes for table `19_meds_manufacturer`
--
ALTER TABLE `19_meds_manufacturer`
  ADD PRIMARY KEY (`Manufacturer_ID`);

--
-- Indexes for table `20_meds_requires_rx`
--
ALTER TABLE `20_meds_requires_rx`
  ADD PRIMARY KEY (`Require_rx_ID`);

--
-- Indexes for table `21_inventory`
--
ALTER TABLE `21_inventory`
  ADD PRIMARY KEY (`Inventory_ID`),
  ADD KEY `fk_inv_pharmacy` (`Pharmacy_ID`),
  ADD KEY `fk_inv_med` (`Medicine_ID`),
  ADD KEY `fk_inv_price_unit` (`Price_Unit_ID`);

--
-- Indexes for table `22_inventory_unit_price`
--
ALTER TABLE `22_inventory_unit_price`
  ADD PRIMARY KEY (`Price_Unit_ID`);

--
-- Indexes for table `23_pharmacy_images`
--
ALTER TABLE `23_pharmacy_images`
  ADD PRIMARY KEY (`Image_ID`),
  ADD KEY `fk_img_pharmacy` (`Pharmacy_ID`);

--
-- Indexes for table `24_medicine_images`
--
ALTER TABLE `24_medicine_images`
  ADD PRIMARY KEY (`Image_ID`),
  ADD KEY `fk_img_med` (`Medicine_ID`);

--
-- Indexes for table `25_user_prescriptions`
--
ALTER TABLE `25_user_prescriptions`
  ADD PRIMARY KEY (`Prescription_ID`),
  ADD KEY `fk_rx_user` (`User_ID`);

--
-- Indexes for table `26_pos_sales`
--
ALTER TABLE `26_pos_sales`
  ADD PRIMARY KEY (`Sale_ID`),
  ADD UNIQUE KEY `Receipt_No` (`Receipt_No`),
  ADD KEY `Pharmacy_ID` (`Pharmacy_ID`),
  ADD KEY `Sale_Date` (`Sale_Date`);

--
-- Indexes for table `27_pos_sale_items`
--
ALTER TABLE `27_pos_sale_items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `Sale_ID` (`Sale_ID`),
  ADD KEY `Inventory_ID` (`Inventory_ID`),
  ADD KEY `Medicine_ID` (`Medicine_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `01_user_users`
--
ALTER TABLE `01_user_users`
  MODIFY `User_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `02_user_roles`
--
ALTER TABLE `02_user_roles`
  MODIFY `Role_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `03_user_status`
--
ALTER TABLE `03_user_status`
  MODIFY `Status_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `04_user_approval`
--
ALTER TABLE `04_user_approval`
  MODIFY `Approval_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `05_address_user_address`
--
ALTER TABLE `05_address_user_address`
  MODIFY `Address_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `06_address_province`
--
ALTER TABLE `06_address_province`
  MODIFY `Province_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `07_address_city`
--
ALTER TABLE `07_address_city`
  MODIFY `City_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `08_address_barangay`
--
ALTER TABLE `08_address_barangay`
  MODIFY `Barangay_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `09_pharmacies`
--
ALTER TABLE `09_pharmacies`
  MODIFY `Pharmacy_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `12_meds_medicines`
--
ALTER TABLE `12_meds_medicines`
  MODIFY `Medicine_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `13_meds_generic_name`
--
ALTER TABLE `13_meds_generic_name`
  MODIFY `Gen_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `14_meds_brand_name`
--
ALTER TABLE `14_meds_brand_name`
  MODIFY `Brand_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `15_meds_dosage_form`
--
ALTER TABLE `15_meds_dosage_form`
  MODIFY `DosageForm_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `16_meds_dosages`
--
ALTER TABLE `16_meds_dosages`
  MODIFY `Dosage_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `16_meds_dosage_value`
--
ALTER TABLE `16_meds_dosage_value`
  MODIFY `DosageVal_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `17_meds_dosage_unit`
--
ALTER TABLE `17_meds_dosage_unit`
  MODIFY `Unit_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `18_meds_categories`
--
ALTER TABLE `18_meds_categories`
  MODIFY `Category_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `19_meds_manufacturer`
--
ALTER TABLE `19_meds_manufacturer`
  MODIFY `Manufacturer_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `20_meds_requires_rx`
--
ALTER TABLE `20_meds_requires_rx`
  MODIFY `Require_rx_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `21_inventory`
--
ALTER TABLE `21_inventory`
  MODIFY `Inventory_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `22_inventory_unit_price`
--
ALTER TABLE `22_inventory_unit_price`
  MODIFY `Price_Unit_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `23_pharmacy_images`
--
ALTER TABLE `23_pharmacy_images`
  MODIFY `Image_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `24_medicine_images`
--
ALTER TABLE `24_medicine_images`
  MODIFY `Image_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `25_user_prescriptions`
--
ALTER TABLE `25_user_prescriptions`
  MODIFY `Prescription_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `26_pos_sales`
--
ALTER TABLE `26_pos_sales`
  MODIFY `Sale_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `27_pos_sale_items`
--
ALTER TABLE `27_pos_sale_items`
  MODIFY `Item_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

-- --------------------------------------------------------

--
-- Structure for view `view_01_users`
--
DROP TABLE IF EXISTS `view_01_users`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_01_users`  AS SELECT `u`.`User_ID` AS `User_ID`, `u`.`Role_ID` AS `Role_ID`, `u`.`Username` AS `Username`, `u`.`First_name` AS `First_name`, `u`.`Middle_name` AS `Middle_name`, `u`.`Last_name` AS `Last_name`, concat(`u`.`First_name`,' ',`u`.`Last_name`) AS `Full_Name`, `u`.`Gender` AS `Gender`, `u`.`Birthdate` AS `Birthdate`, timestampdiff(YEAR,`u`.`Birthdate`,curdate()) AS `Age`, `u`.`Email` AS `Email`, `u`.`Phone` AS `Phone`, `r`.`Role_Name` AS `Role`, `s`.`Status_Name` AS `UserStatus`, `u`.`Profilepic_url` AS `Profilepic_url`, `u`.`Password_hash` AS `Password_hash`, `u`.`DateCreated` AS `DateCreated`, `u`.`DateApproved` AS `DateApproved` FROM ((`01_user_users` `u` left join `02_user_roles` `r` on((`u`.`Role_ID` = `r`.`Role_ID`))) left join `03_user_status` `s` on((`u`.`User_Status_ID` = `s`.`Status_ID`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view_02_address`
--
DROP TABLE IF EXISTS `view_02_address`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_02_address`  AS SELECT `a`.`Address_ID` AS `Address_ID`, `a`.`User_ID` AS `User_ID`, `p`.`Province_Name` AS `Province_Name`, `c`.`City_Name` AS `City_Name`, `b`.`Barangay_Name` AS `Barangay_Name`, `a`.`Street` AS `Street`, concat_ws(', ',nullif(`a`.`Street`,''),nullif(`b`.`Barangay_Name`,''),nullif(`c`.`City_Name`,''),nullif(`p`.`Province_Name`,'')) AS `Full_Address` FROM (((`05_address_user_address` `a` left join `06_address_province` `p` on((`a`.`Province_ID` = `p`.`Province_ID`))) left join `07_address_city` `c` on((`a`.`City_ID` = `c`.`City_ID`))) left join `08_address_barangay` `b` on((`a`.`Barangay_ID` = `b`.`Barangay_ID`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view_03_user_address`
--
DROP TABLE IF EXISTS `view_03_user_address`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_03_user_address`  AS SELECT `u`.`User_ID` AS `User_ID`, concat(`u`.`First_name`,' ',`u`.`Last_name`) AS `Full_Name`, `u`.`Email` AS `Email`, `u`.`Phone` AS `Phone`, `r`.`Role_Name` AS `Role`, `s`.`Status_Name` AS `UserStatus`, `a`.`Street` AS `Street`, `b`.`Barangay_Name` AS `Barangay_Name`, `c`.`City_Name` AS `City_Name`, `p`.`Province_Name` AS `Province_Name`, concat_ws(', ',nullif(`a`.`Street`,''),nullif(`b`.`Barangay_Name`,''),nullif(`c`.`City_Name`,''),nullif(`p`.`Province_Name`,'')) AS `Full_Address` FROM ((((((`01_user_users` `u` left join `02_user_roles` `r` on((`u`.`Role_ID` = `r`.`Role_ID`))) left join `03_user_status` `s` on((`u`.`User_Status_ID` = `s`.`Status_ID`))) left join `05_address_user_address` `a` on((`u`.`Address_ID` = `a`.`Address_ID`))) left join `06_address_province` `p` on((`a`.`Province_ID` = `p`.`Province_ID`))) left join `07_address_city` `c` on((`a`.`City_ID` = `c`.`City_ID`))) left join `08_address_barangay` `b` on((`a`.`Barangay_ID` = `b`.`Barangay_ID`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view_04_pharmacy`
--
DROP TABLE IF EXISTS `view_04_pharmacy`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_04_pharmacy`  AS SELECT `ph`.`Pharmacy_ID` AS `Pharmacy_ID`, `ph`.`Pharmacy_name` AS `Pharmacy_name`, `ph`.`Owner_name` AS `Owner_name`, `ph`.`Phone` AS `Phone`, `u`.`Email` AS `Owner_Email`, `addr`.`Street` AS `Street`, `addr`.`Barangay_Name` AS `Barangay_Name`, `addr`.`City_Name` AS `City_Name`, `addr`.`Province_Name` AS `Province_Name`, `addr`.`Full_Address` AS `Full_Address`, `ph`.`Logo_URL` AS `Logo_URL`, `ph`.`Pic_URL` AS `Pic_URL`, `app`.`Approval_Status` AS `Approval_Status`, `ph`.`DateCreated` AS `DateCreated`, `ph`.`Date_Approved` AS `Date_Approved` FROM (((`09_pharmacies` `ph` left join `01_user_users` `u` on((`ph`.`User_ID` = `u`.`User_ID`))) left join `view_02_address` `addr` on((`ph`.`Address_ID` = `addr`.`Address_ID`))) left join `04_user_approval` `app` on((`ph`.`Approval_ID` = `app`.`Approval_ID`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view_05_medicine_master`
--
DROP TABLE IF EXISTS `view_05_medicine_master`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_05_medicine_master`  AS SELECT `m`.`Medicine_ID` AS `Medicine_ID`, `g`.`Generic_Name` AS `Generic_Name`, `b`.`Brand_Name` AS `Brand_Name`, `cat`.`Category_Name` AS `Category_Name`, `df`.`Dosage_Form` AS `Dosage_Form`, `d`.`Dosage_Strength` AS `Dosage_Strength`, `m`.`Package_Size` AS `Package_Size`, `m`.`Pieces_Per_Box` AS `Pieces_Per_Box`, `m`.`Is_Essential` AS `Is_Essential`, `man`.`Manufacturer_Name` AS `Manufacturer_Name`, `rx`.`Require_rx_ID` AS `Require_rx_ID`, `m`.`IsDeleted` AS `IsDeleted` FROM (((((((`12_meds_medicines` `m` left join `13_meds_generic_name` `g` on((`m`.`Gen_ID` = `g`.`Gen_ID`))) left join `14_meds_brand_name` `b` on((`m`.`Brand_ID` = `b`.`Brand_ID`))) left join `15_meds_dosage_form` `df` on((`m`.`DosageForm_ID` = `df`.`DosageForm_ID`))) left join `16_meds_dosages` `d` on((`m`.`Dosage_ID` = `d`.`Dosage_ID`))) left join `18_meds_categories` `cat` on((`m`.`Category_ID` = `cat`.`Category_ID`))) left join `19_meds_manufacturer` `man` on((`m`.`Manufacturer_ID` = `man`.`Manufacturer_ID`))) left join `20_meds_requires_rx` `rx` on((`m`.`Require_rx_ID` = `rx`.`Require_rx_ID`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view_06_inventory_stocks`
--
DROP TABLE IF EXISTS `view_06_inventory_stocks`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_06_inventory_stocks`  AS SELECT `i`.`Inventory_ID` AS `Inventory_ID`, `i`.`Pharmacy_ID` AS `Pharmacy_ID`, `p`.`Pharmacy_name` AS `Pharmacy_name`, `i`.`Medicine_ID` AS `Medicine_ID`, `i`.`Batch_Number` AS `Batch_Number`, `g`.`Generic_Name` AS `Generic_Name`, `b`.`Brand_Name` AS `Brand_Name`, `cat`.`Category_Name` AS `Category_Name`, `df`.`Dosage_Form` AS `Dosage_Form`, `d`.`Dosage_Strength` AS `Dosage`, `m`.`Package_Size` AS `Package_Size`, `m`.`Pieces_Per_Box` AS `Pieces_Per_Box`, `m`.`Is_Essential` AS `Is_Essential`, `man`.`Manufacturer_Name` AS `Manufacturer_Name`, `i`.`Quantity` AS `Quantity`, `i`.`Low_Stock_Threshold` AS `Low_Stock_Threshold`, `i`.`Price` AS `Price`, `u`.`Unit` AS `Price_Per`, `i`.`Expiry_date` AS `Expiry_date`, `i`.`Last_updated` AS `Last_updated`, (case when ((`i`.`Expiry_date` is not null) and (`i`.`Expiry_date` < curdate())) then 'Expired' when (`i`.`Quantity` = 0) then 'Out of Stock' when (`i`.`Quantity` <= `i`.`Low_Stock_Threshold`) then 'Low Stock' else 'Available' end) AS `Availability_Status`, `i`.`IsDeleted` AS `IsDeleted` FROM (((((((((`21_inventory` `i` join `09_pharmacies` `p` on((`i`.`Pharmacy_ID` = `p`.`Pharmacy_ID`))) join `12_meds_medicines` `m` on((`i`.`Medicine_ID` = `m`.`Medicine_ID`))) left join `13_meds_generic_name` `g` on((`m`.`Gen_ID` = `g`.`Gen_ID`))) left join `14_meds_brand_name` `b` on((`m`.`Brand_ID` = `b`.`Brand_ID`))) left join `15_meds_dosage_form` `df` on((`m`.`DosageForm_ID` = `df`.`DosageForm_ID`))) left join `16_meds_dosages` `d` on((`m`.`Dosage_ID` = `d`.`Dosage_ID`))) left join `18_meds_categories` `cat` on((`m`.`Category_ID` = `cat`.`Category_ID`))) left join `19_meds_manufacturer` `man` on((`m`.`Manufacturer_ID` = `man`.`Manufacturer_ID`))) left join `22_inventory_unit_price` `u` on((`i`.`Price_Unit_ID` = `u`.`Price_Unit_ID`))) WHERE (`i`.`IsDeleted` = 0) ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `01_user_users`
--
ALTER TABLE `01_user_users`
  ADD CONSTRAINT `fk_user_address` FOREIGN KEY (`Address_ID`) REFERENCES `05_address_user_address` (`Address_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`Role_ID`) REFERENCES `02_user_roles` (`Role_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_status` FOREIGN KEY (`User_Status_ID`) REFERENCES `03_user_status` (`Status_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `05_address_user_address`
--
ALTER TABLE `05_address_user_address`
  ADD CONSTRAINT `fk_addr_brgy` FOREIGN KEY (`Barangay_ID`) REFERENCES `08_address_barangay` (`Barangay_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_addr_city` FOREIGN KEY (`City_ID`) REFERENCES `07_address_city` (`City_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_addr_province` FOREIGN KEY (`Province_ID`) REFERENCES `06_address_province` (`Province_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_addr_user` FOREIGN KEY (`User_ID`) REFERENCES `01_user_users` (`User_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `07_address_city`
--
ALTER TABLE `07_address_city`
  ADD CONSTRAINT `fk_city_prov` FOREIGN KEY (`Province_ID`) REFERENCES `06_address_province` (`Province_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `08_address_barangay`
--
ALTER TABLE `08_address_barangay`
  ADD CONSTRAINT `fk_brgy_city` FOREIGN KEY (`City_ID`) REFERENCES `07_address_city` (`City_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `09_pharmacies`
--
ALTER TABLE `09_pharmacies`
  ADD CONSTRAINT `fk_pharmacy_address` FOREIGN KEY (`Address_ID`) REFERENCES `05_address_user_address` (`Address_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pharmacy_approval` FOREIGN KEY (`Approval_ID`) REFERENCES `04_user_approval` (`Approval_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pharmacy_user` FOREIGN KEY (`User_ID`) REFERENCES `01_user_users` (`User_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `12_meds_medicines`
--
ALTER TABLE `12_meds_medicines`
  ADD CONSTRAINT `fk_med_brand` FOREIGN KEY (`Brand_ID`) REFERENCES `14_meds_brand_name` (`Brand_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_med_category` FOREIGN KEY (`Category_ID`) REFERENCES `18_meds_categories` (`Category_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_med_form` FOREIGN KEY (`DosageForm_ID`) REFERENCES `15_meds_dosage_form` (`DosageForm_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_med_generic` FOREIGN KEY (`Gen_ID`) REFERENCES `13_meds_generic_name` (`Gen_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_med_manufacturer` FOREIGN KEY (`Manufacturer_ID`) REFERENCES `19_meds_manufacturer` (`Manufacturer_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_med_rx` FOREIGN KEY (`Require_rx_ID`) REFERENCES `20_meds_requires_rx` (`Require_rx_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_med_unit` FOREIGN KEY (`Unit_ID`) REFERENCES `17_meds_dosage_unit` (`Unit_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_med_value` FOREIGN KEY (`DosageVal_ID`) REFERENCES `16_meds_dosage_value` (`DosageVal_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_meds_dosage` FOREIGN KEY (`Dosage_ID`) REFERENCES `16_meds_dosages` (`Dosage_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `21_inventory`
--
ALTER TABLE `21_inventory`
  ADD CONSTRAINT `fk_inv_med` FOREIGN KEY (`Medicine_ID`) REFERENCES `12_meds_medicines` (`Medicine_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inv_pharmacy` FOREIGN KEY (`Pharmacy_ID`) REFERENCES `09_pharmacies` (`Pharmacy_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inv_price_unit` FOREIGN KEY (`Price_Unit_ID`) REFERENCES `22_inventory_unit_price` (`Price_Unit_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `23_pharmacy_images`
--
ALTER TABLE `23_pharmacy_images`
  ADD CONSTRAINT `fk_img_pharmacy` FOREIGN KEY (`Pharmacy_ID`) REFERENCES `09_pharmacies` (`Pharmacy_ID`) ON DELETE CASCADE;

--
-- Constraints for table `24_medicine_images`
--
ALTER TABLE `24_medicine_images`
  ADD CONSTRAINT `fk_img_med` FOREIGN KEY (`Medicine_ID`) REFERENCES `12_meds_medicines` (`Medicine_ID`) ON DELETE CASCADE;

--
-- Constraints for table `25_user_prescriptions`
--
ALTER TABLE `25_user_prescriptions`
  ADD CONSTRAINT `fk_rx_user` FOREIGN KEY (`User_ID`) REFERENCES `01_user_users` (`User_ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
