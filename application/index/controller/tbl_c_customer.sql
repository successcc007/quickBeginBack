-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2017-11-16 14:39:15
-- 服务器版本： 5.7.14-log
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jishi`
--

-- --------------------------------------------------------

--
-- 表的结构 `tbl_c_customer`
--

CREATE TABLE `tbl_c_customer` (
  `i_id` int(11) NOT NULL,
  `s_phone` varchar(20) NOT NULL,
  `s_user_name` varchar(20) NOT NULL,
  `i_version` int(11) NOT NULL,
  `i_delete` int(1) NOT NULL,
  `s_password` varchar(50) NOT NULL,
  `s_sex` tinyint(1) NOT NULL DEFAULT '0',
  `s_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '登录状态',
  `s_create_time` int(11) NOT NULL COMMENT '注册时间',
  `s_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 技师 0 客户'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `tbl_c_customer`
--

INSERT INTO `tbl_c_customer` (`i_id`, `s_phone`, `s_user_name`, `i_version`, `i_delete`, `s_password`, `s_sex`, `s_status`, `s_create_time`, `s_type`) VALUES
(101, '4253061818', 'tom189', 1, 0, '', 0, 0, 0, 0),
(201, '4168891765', 'loveyou888', 1, 0, '', 0, 0, 0, 0),
(301, '13958130201', 'admin', 0, 0, 'a205f270af603217fdf655472e596cf0', 0, 0, 1509617951, 0),
(401, '13958130204', 'test', 0, 0, '1cf64187017d04ee566b99e03720c9a3', 0, 1, 1509675881, 0),
(501, '13958130908', '', 0, 0, '101', 0, 0, 1510843023, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_c_customer`
--
ALTER TABLE `tbl_c_customer`
  ADD PRIMARY KEY (`i_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
