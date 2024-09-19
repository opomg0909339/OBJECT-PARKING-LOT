-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2024-09-19 16:15:14
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `car`
--

-- --------------------------------------------------------

--
-- 資料表結構 `location`
--

CREATE TABLE `location` (
  `ID` int(11) NOT NULL,
  `PLACE` text NOT NULL,
  `RESERVE` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `location`
--

INSERT INTO `location` (`ID`, `PLACE`, `RESERVE`) VALUES
(1, '710台南市永康區正南一街157-2號', 0);

-- --------------------------------------------------------

--
-- 資料表結構 `reserve`
--

CREATE TABLE `reserve` (
  `ID` int(11) NOT NULL,
  `user` varchar(20) NOT NULL,
  `Count` int(10) NOT NULL DEFAULT 0,
  `stoptime` varchar(20) NOT NULL,
  `locationtime` varchar(20) NOT NULL,
  `LOCATION` varchar(30) NOT NULL,
  `RESERVECAR` varchar(10) NOT NULL,
  `pay` tinyint(1) NOT NULL DEFAULT 0,
  `stop` tinyint(1) NOT NULL DEFAULT 0,
  `same` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `reserve`
--

INSERT INTO `reserve` (`ID`, `user`, `Count`, `stoptime`, `locationtime`, `LOCATION`, `RESERVECAR`, `pay`, `stop`, `same`) VALUES
(1, '123', 6, '2023/10/13', '60', '710台南市永康區正南一街157-2號', '1051K7', 0, 0, 0);

-- --------------------------------------------------------

--
-- 資料表結構 `stop`
--

CREATE TABLE `stop` (
  `ID` int(11) NOT NULL,
  `DATE` varchar(20) NOT NULL,
  `LOCATION` varchar(20) NOT NULL,
  `STOPCAR` varchar(10) NOT NULL,
  `RESERVE` int(20) NOT NULL,
  `PLACE` varchar(30) DEFAULT '0',
  `StopTime` int(11) NOT NULL DEFAULT 0,
  `Time` int(11) NOT NULL DEFAULT 0,
  `Stoppay` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `stop`
--

INSERT INTO `stop` (`ID`, `DATE`, `LOCATION`, `STOPCAR`, `RESERVE`, `PLACE`, `StopTime`, `Time`, `Stoppay`) VALUES
(128, '2023-10-13 04:28', '710台南市永康區正南一街157-2號', '1051K7', 0, 'A車位', 5, 0, 0),
(4008, '2023-7-27 23:27', '2', 'AR6037', 0, 'B車位', 9, 0, 0),
(4014, '2023/10/13', '710台南市永康區正南一街157-2號', '1051K7', 0, 'A車位', 6, 0, 0),
(4015, '2023/10/13', '710台南市永康區正南一街157-2號', '1051K7', 0, 'A車位', 6, 0, 1);

-- --------------------------------------------------------

--
-- 資料表結構 `userinfor`
--

CREATE TABLE `userinfor` (
  `ID` int(11) NOT NULL,
  `account` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `card` varchar(20) NOT NULL,
  `car` varchar(20) NOT NULL,
  `money` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `userinfor`
--

INSERT INTO `userinfor` (`ID`, `account`, `password`, `card`, `car`, `money`) VALUES
(5, '123', '321', '741', '1051K7', 7980),
(6, '789', '741582963', '789', '1051K7', 0),
(7, '999', '741852963', '999', '999', 0),
(9, '741', '852', '123456789', 'AR6037', 0),
(10, '741', '852852', '123456789', '852', 0),
(11, '852', '852', 'A01', 'A02', 0),
(14, '851', '75553', '9969657', '75273', 0);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`ID`);

--
-- 資料表索引 `reserve`
--
ALTER TABLE `reserve`
  ADD PRIMARY KEY (`ID`);

--
-- 資料表索引 `stop`
--
ALTER TABLE `stop`
  ADD PRIMARY KEY (`ID`);

--
-- 資料表索引 `userinfor`
--
ALTER TABLE `userinfor`
  ADD PRIMARY KEY (`ID`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `location`
--
ALTER TABLE `location`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `reserve`
--
ALTER TABLE `reserve`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `stop`
--
ALTER TABLE `stop`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4016;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `userinfor`
--
ALTER TABLE `userinfor`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
