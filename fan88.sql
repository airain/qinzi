-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 05 月 07 日 14:52
-- 服务器版本: 5.1.51
-- PHP 版本: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `fan88`
--

-- --------------------------------------------------------

--
-- 表的结构 `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `introduce` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parentid` int(11) NOT NULL DEFAULT '0',
  `type` smallint(2) NOT NULL DEFAULT '1',
  `elite` smallint(2) DEFAULT '0',
  `state` smallint(6) DEFAULT '0',
  `sort_number` int(8) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `category`
--

INSERT INTO `category` (`id`, `name`, `path`, `introduce`, `parentid`, `type`, `elite`, `state`, `sort_number`) VALUES
(1, '商家类型', '0,1', '分类介绍', 0, 1, 0, 0, 0),
(2, '大酒店', '0,1,2', '分类介绍', 1, 1, 0, 0, 0),
(3, '小餐馆', '0,1,3', '分类介绍', 1, 1, 0, 0, 0),
(4, '路边摊', '0,1,4', '分类介绍', 1, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `manager`
--

CREATE TABLE IF NOT EXISTS `manager` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `user_pwd` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `lastlogin_time` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `manager`
--

INSERT INTO `manager` (`user_id`, `user_name`, `user_pwd`, `lastlogin_time`) VALUES
(1, 'skyings', 'e10adc3949ba59abbe56e057f20f883e', 1320250130),
(2, 'lixiaobing', 'e10adc3949ba59abbe56e057f20f883e', 1336316185);

-- --------------------------------------------------------

--
-- 表的结构 `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `shop_id` int(12) NOT NULL,
  `name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `price` float NOT NULL,
  `addtime` int(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `menu`
--


-- --------------------------------------------------------

--
-- 表的结构 `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `shop_id` int(12) NOT NULL,
  `menu_id` int(12) NOT NULL,
  `order_time` int(12) NOT NULL,
  `order_cnt` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `order`
--


-- --------------------------------------------------------

--
-- 表的结构 `shop`
--

CREATE TABLE IF NOT EXISTS `shop` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `addtime` int(12) NOT NULL,
  `desc` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `shop`
--


-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `ip` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `user`
--


-- --------------------------------------------------------

--
-- 表的结构 `user_order`
--

CREATE TABLE IF NOT EXISTS `user_order` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `order_time` int(12) NOT NULL,
  `order_id` int(12) NOT NULL,
  `menu_id` int(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `user_order`
--

