/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : wxopen_class

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-04-02 07:35:17
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for announcement
-- ----------------------------
DROP TABLE IF EXISTS `announcement`;
CREATE TABLE `announcement` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `pushTime` varchar(50) DEFAULT NULL,
  `content` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of announcement
-- ----------------------------

-- ----------------------------
-- Table structure for answer
-- ----------------------------
DROP TABLE IF EXISTS `answer`;
CREATE TABLE `answer` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `mId` int(10) DEFAULT NULL,
  `tAnswer` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of answer
-- ----------------------------

-- ----------------------------
-- Table structure for message
-- ----------------------------
DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `sName` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `cId` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of message
-- ----------------------------
INSERT INTO `message` VALUES ('1', '课上的好', '张三', '1');

-- ----------------------------
-- Table structure for scores
-- ----------------------------
DROP TABLE IF EXISTS `scores`;
CREATE TABLE `scores` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sName` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `sScore` int(10) DEFAULT NULL,
  `cId` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of scores
-- ----------------------------
INSERT INTO `scores` VALUES ('1', '张三', '80', '1');

-- ----------------------------
-- Table structure for signinfo
-- ----------------------------
DROP TABLE IF EXISTS `signinfo`;
CREATE TABLE `signinfo` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `status` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `cId` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of signinfo
-- ----------------------------
INSERT INTO `signinfo` VALUES ('1', 's1', '迟到', '1');

-- ----------------------------
-- Table structure for stuandclass
-- ----------------------------
DROP TABLE IF EXISTS `stuandclass`;
CREATE TABLE `stuandclass` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sId` int(11) DEFAULT NULL,
  `cId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of stuandclass
-- ----------------------------

-- ----------------------------
-- Table structure for tclass
-- ----------------------------
DROP TABLE IF EXISTS `tclass`;
CREATE TABLE `tclass` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `adress` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `stime` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `qcodeImage` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `tId` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tclass
-- ----------------------------
INSERT INTO `tclass` VALUES ('3', '物理', '苏宁广场', '周六10点', null, '2');
INSERT INTO `tclass` VALUES ('5', '高数课', '生化楼102', '周五14：30', null, '2');
INSERT INTO `tclass` VALUES ('6', '生物', '生化楼202', '周五13：30', null, '2');

-- ----------------------------
-- Table structure for upfile
-- ----------------------------
DROP TABLE IF EXISTS `upfile`;
CREATE TABLE `upfile` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `path` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `upTime` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of upfile
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uname` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `pword` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='type,1:teacher,0:student';

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'szhangsan', '123456', '0', '张三');
INSERT INTO `users` VALUES ('2', 'tli', '123456', '1', '李老师');
