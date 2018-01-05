/*
Navicat MySQL Data Transfer

Source Server         : 115.159.185.224_3306
Source Server Version : 50549
Source Host           : 115.159.185.224:3306
Source Database       : cuitcheck

Target Server Type    : MYSQL
Target Server Version : 50549
File Encoding         : 65001

Date: 2017-09-24 12:54:58
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `kh_answer`
-- ----------------------------
DROP TABLE IF EXISTS `kh_answer`;
CREATE TABLE `kh_answer` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `question_id` int(10) NOT NULL,
  `content` text NOT NULL,
  `is_true` int(1) NOT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_answer
-- ----------------------------
INSERT INTO `kh_answer` VALUES ('1', '17', 'test add 3<br>', '1', 'test', '2017-04-26 21:32:23', null, null, null, '1');
INSERT INTO `kh_answer` VALUES ('2', '19', '1', '0', 'test', '2017-04-26 21:42:46', null, null, null, '1');
INSERT INTO `kh_answer` VALUES ('3', '19', '2', '1', 'test', '2017-04-26 21:42:46', null, null, null, '1');
INSERT INTO `kh_answer` VALUES ('4', '19', '3', '0', 'test', '2017-04-26 21:42:46', null, null, null, '1');
INSERT INTO `kh_answer` VALUES ('5', '19', '4', '0', 'test', '2017-04-26 21:42:46', null, null, null, '1');
INSERT INTO `kh_answer` VALUES ('6', '20', 'test', '1', 'test', '2017-04-26 21:47:29', 'test', '2017-05-02 21:28:51', null, '1');
INSERT INTO `kh_answer` VALUES ('19', '49', 'test add 3', '1', 'test', '2017-05-29 11:30:33', null, null, null, '1');
INSERT INTO `kh_answer` VALUES ('20', '50', 'T', '1', 'test', '2017-05-29 11:30:33', null, null, null, '1');
INSERT INTO `kh_answer` VALUES ('21', '51', '6.500000', '0', 'test', '2017-05-29 11:30:33', null, null, null, '1');
INSERT INTO `kh_answer` VALUES ('22', '51', '6', '1', 'test', '2017-05-29 11:30:33', null, null, null, '1');
INSERT INTO `kh_answer` VALUES ('23', '51', '5.500000', '0', 'test', '2017-05-29 11:30:33', null, null, null, '1');
INSERT INTO `kh_answer` VALUES ('24', '51', '6.000000', '0', 'test', '2017-05-29 11:30:33', null, null, null, '1');
INSERT INTO `kh_answer` VALUES ('25', '52', '21312', '1', 'maqingwen', '2017-06-12 17:11:50', 'test', '2017-07-22 15:42:19', null, '1');
INSERT INTO `kh_answer` VALUES ('26', '52', '21321', '0', 'maqingwen', '2017-06-12 17:11:50', 'test', '2017-07-22 15:42:19', null, '1');
INSERT INTO `kh_answer` VALUES ('27', '52', '12312', '0', 'maqingwen', '2017-06-12 17:11:50', 'test', '2017-07-22 15:42:19', null, '1');
INSERT INTO `kh_answer` VALUES ('28', '52', '12312', '0', 'maqingwen', '2017-06-12 17:11:50', 'test', '2017-07-22 15:42:19', null, '1');
INSERT INTO `kh_answer` VALUES ('29', '53', '213', '1', 'test', '2017-06-13 13:19:31', null, null, null, '1');
INSERT INTO `kh_answer` VALUES ('30', '53', '12312', '0', 'test', '2017-06-13 13:19:31', null, null, null, '1');
INSERT INTO `kh_answer` VALUES ('31', '53', '21312', '0', 'test', '2017-06-13 13:19:31', null, null, null, '1');
INSERT INTO `kh_answer` VALUES ('32', '53', '21312', '0', 'test', '2017-06-13 13:19:31', null, null, null, '1');
INSERT INTO `kh_answer` VALUES ('33', '54', '问题-知识点表测试', '1', 'test', '2017-07-24 16:14:55', null, null, null, '1');
INSERT INTO `kh_answer` VALUES ('34', '55', '问题-知识点表测试', '1', 'test', '2017-07-24 16:18:45', null, null, null, '1');
INSERT INTO `kh_answer` VALUES ('35', '56', '<p>问题-知识点表测试</p>', '1', 'test', '2017-07-24 16:19:43', 'test', '2017-07-24 16:33:00', null, '1');
INSERT INTO `kh_answer` VALUES ('36', '57', 'a', '1', 'maqingwen', '2017-07-26 12:59:35', 'maqingwen', '2017-07-26 13:01:02', null, '1');
INSERT INTO `kh_answer` VALUES ('37', '57', '0', '0', 'maqingwen', '2017-07-26 12:59:35', 'maqingwen', '2017-07-26 13:01:02', null, '1');
INSERT INTO `kh_answer` VALUES ('38', '57', 'null', '0', 'maqingwen', '2017-07-26 12:59:35', 'maqingwen', '2017-07-26 13:01:02', null, '1');
INSERT INTO `kh_answer` VALUES ('39', '57', '报错', '0', 'maqingwen', '2017-07-26 12:59:35', 'maqingwen', '2017-07-26 13:01:02', null, '1');

-- ----------------------------
-- Table structure for `kh_auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `kh_auth_group`;
CREATE TABLE `kh_auth_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `rules` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_auth_group
-- ----------------------------
INSERT INTO `kh_auth_group` VALUES ('1', '超级管理员', '1', '1,2,3,4,17,18,19,20,24,5,6,27,8,9,15,16,28,10,21,22,11,12,13,14,23,25,26,29,30,31,38,32,33,34,35,36');
INSERT INTO `kh_auth_group` VALUES ('2', '系统管理员', '1', '1,2,8,9,15,16,23');
INSERT INTO `kh_auth_group` VALUES ('3', '学院管理员', '1', '1,2,3,4,17,18,19,20,24,5,6,27,10,21,22,23,25,26,29,30,31,38,32,33,34,35,36');
INSERT INTO `kh_auth_group` VALUES ('4', '老师', '1', '1,2,3,4,19,23,29,30,38,32,33,34,35,36');

-- ----------------------------
-- Table structure for `kh_auth_group_access`
-- ----------------------------
DROP TABLE IF EXISTS `kh_auth_group_access`;
CREATE TABLE `kh_auth_group_access` (
  `uid` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `group_id` (`group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='一个用户可在多个用户组 ';

-- ----------------------------
-- Records of kh_auth_group_access
-- ----------------------------
INSERT INTO `kh_auth_group_access` VALUES ('1', '1');
INSERT INTO `kh_auth_group_access` VALUES ('2', '1');
INSERT INTO `kh_auth_group_access` VALUES ('3', '1');
INSERT INTO `kh_auth_group_access` VALUES ('4', '3');
INSERT INTO `kh_auth_group_access` VALUES ('5', '3');
INSERT INTO `kh_auth_group_access` VALUES ('6', '4');
INSERT INTO `kh_auth_group_access` VALUES ('7', '4');

-- ----------------------------
-- Table structure for `kh_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `kh_auth_rule`;
CREATE TABLE `kh_auth_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` varchar(80) DEFAULT NULL,
  `title` varchar(20) NOT NULL,
  `status` int(1) NOT NULL,
  `type` int(1) NOT NULL DEFAULT '1',
  `condition` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='操作权限信息表-对应到菜单和每项操作';

-- ----------------------------
-- Records of kh_auth_rule
-- ----------------------------
INSERT INTO `kh_auth_rule` VALUES ('1', '0', '', '个人中心', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('2', '1', 'Home/PersonCenter/Updateinfo', '资料修改', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('3', '0', '', '学院管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('4', '3', 'Home/ClassMgr/classList', '行政班级管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('5', '0', null, '题库管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('6', '5', 'Home/TestDBPermissionMgr/testDBList', '题库授权', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('8', '0', '', '系统管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('9', '8', 'Home/System/log', '日志管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('10', '0', '', '章节知识点管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('11', '0', '', '用户管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('12', '11', 'Home/SysuserManage/sysUserList', '系统用户管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('13', '11', 'Home/SysuserGroup/sysGroupList', '用户组管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('14', '11', 'Home/SysuserRule/sysRuleList', '权限管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('15', '8', 'Home/InformMgr/informList', '通知公告', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('16', '8', 'Home/Dictionary/DictionaryList', '字典管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('17', '3', 'Home/GradeMgr/gradeList', '年级管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('18', '3', 'Home/MajorMgr/majorList', '专业管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('19', '3', 'Home/CourseclassMgr/courseclassList', '行课班级管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('20', '3', 'Home/StudentMgr/studentList', '学生管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('21', '10', 'Home/ChapterMgr/chapterList', '章节管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('22', '10', 'Home/KnowledgeMgr/knowledgeList', '知识点管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('23', '0', 'Home/index', '后台首页', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('24', '3', 'Home/CollegeMgr/collegeList', '学院管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('25', '0', null, '学科管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('26', '25', 'Home/CourseMgr/courseList', '学科管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('27', '5', 'Home/TestDatabaseMgr/testDatabaseList', '题库管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('28', '8', 'Home/System/menu', '菜单管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('29', '0', '', '试卷管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('30', '29', 'Home/ClassPapersCreateMgr/ClassPapersList', '组卷管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('31', '29', 'Home/TestTeacherPerMgr/testTeacherList', '出题授权', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('32', '0', '', '分析统计', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('33', '32', 'Home/ScoreAnalysis/testRecordList', '考试记录', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('34', '32', 'Home/ScoreAnalysis/paperScoreList', '成绩详情', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('35', '32', 'Home/ScoreAnalysis/classList', '学生统览', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('36', '32', 'Home/ScoreAnalysis/studentScore', '学生成绩检索', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('38', '29', 'Home/ClassPapersDetialMgr/showFinalTest', '分配正式考试', '1', '1', null);

-- ----------------------------
-- Table structure for `kh_chapter`
-- ----------------------------
DROP TABLE IF EXISTS `kh_chapter`;
CREATE TABLE `kh_chapter` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `sortnumber` int(10) NOT NULL,
  `course_id` int(10) NOT NULL,
  `comment` varchar(50) DEFAULT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_chapter
-- ----------------------------
INSERT INTO `kh_chapter` VALUES ('1', '课程简介', '1', '1', 'Java的发展史222', 'xujiaming', '2017-03-17 20:40:49', 'xujiaming', '2017-03-20 23:16:05', null, '1');
INSERT INTO `kh_chapter` VALUES ('2', '开发工具', '5', '1', '开发工具简介', 'xujiaming', '2017-03-17 20:46:27', 'xujiaming', '2017-03-20 17:23:18', null, '1');
INSERT INTO `kh_chapter` VALUES ('3', 'java', '10', '1', 'java基础', 'xujiaming', '2017-03-17 20:48:00', null, null, null, '1');
INSERT INTO `kh_chapter` VALUES ('4', '面向对象', '15', '1', '面向对象基础', 'xujiaming', '2017-03-17 20:49:36', null, null, null, '1');
INSERT INTO `kh_chapter` VALUES ('5', '课程简介', '0', '2', 'test', 'xujiaming', '2017-03-17 20:50:56', null, null, null, '1');
INSERT INTO `kh_chapter` VALUES ('6', '环境搭建', '5', '2', '环境搭建', 'xujiaming', '2017-03-17 20:52:10', null, null, null, '1');
INSERT INTO `kh_chapter` VALUES ('7', 'hello world', '10', '2', '第一个程序', 'xujiaming', '2017-03-17 20:53:55', null, null, null, '1');
INSERT INTO `kh_chapter` VALUES ('8', '四则运算', '15', '2', '基础四则运算', 'xujiaming', '2017-03-17 20:54:54', null, null, null, '1');
INSERT INTO `kh_chapter` VALUES ('9', '流程控制', '20', '2', 'if-else，swith，for，while', 'xujiaming', '2017-03-17 20:56:57', null, null, null, '1');
INSERT INTO `kh_chapter` VALUES ('11', '泛型', '17', '1', 'java泛型讲解', 'xujiaming', '2017-03-20 20:32:04', null, null, null, '1');

-- ----------------------------
-- Table structure for `kh_class`
-- ----------------------------
DROP TABLE IF EXISTS `kh_class`;
CREATE TABLE `kh_class` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `college_id` int(10) NOT NULL,
  `grade_id` int(10) NOT NULL,
  `comment` varchar(50) NOT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_class
-- ----------------------------
INSERT INTO `kh_class` VALUES ('2', '软工154', '2', '1', '', 'luochao', '2017-03-03 16:48:38', 'taolei', '2017-07-26 07:45:47', null, null, '1');
INSERT INTO `kh_class` VALUES ('5', '软工151', '2', '2', '', 'luochao', '2017-03-03 17:18:22', 'taolei', '2017-07-26 07:45:19', null, null, '1');
INSERT INTO `kh_class` VALUES ('6', '软工152', '2', '1', '', 'luochao', '2017-03-03 17:24:58', 'luochao', '2017-03-04 16:19:03', null, null, '1');
INSERT INTO `kh_class` VALUES ('7', '计科174', '2', '2', '', 'taolei', '2017-07-29 23:06:55', 'taolei', '2017-07-29 23:16:09', null, null, '1');

-- ----------------------------
-- Table structure for `kh_class_student`
-- ----------------------------
DROP TABLE IF EXISTS `kh_class_student`;
CREATE TABLE `kh_class_student` (
  `account` varchar(13) NOT NULL,
  `courseclass_id` int(10) NOT NULL,
  `comment` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_class_student
-- ----------------------------
INSERT INTO `kh_class_student` VALUES ('2015081114', '2', '');
INSERT INTO `kh_class_student` VALUES ('2015081115', '2', '');
INSERT INTO `kh_class_student` VALUES ('2015081116', '2', '');
INSERT INTO `kh_class_student` VALUES ('2015081118', '1', '');
INSERT INTO `kh_class_student` VALUES ('2015081117', '1', '');
INSERT INTO `kh_class_student` VALUES ('2015081119', '1', '');
INSERT INTO `kh_class_student` VALUES ('2015081118', '2', '');
INSERT INTO `kh_class_student` VALUES ('2015081117', '2', '');
INSERT INTO `kh_class_student` VALUES ('2015081119', '2', '');

-- ----------------------------
-- Table structure for `kh_college`
-- ----------------------------
DROP TABLE IF EXISTS `kh_college`;
CREATE TABLE `kh_college` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `leadername` varchar(30) NOT NULL,
  `comment` varchar(50) DEFAULT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1',
  `leaderphone` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_college
-- ----------------------------
INSERT INTO `kh_college` VALUES ('2', '软件工程学院', '万娟', '', 'maqingwen', '2017-02-25 16:36:21', 'maqingwen', '2017-04-09 16:06:16', null, '1', '15688888888');
INSERT INTO `kh_college` VALUES ('3', '计算机学院', '江泽明', '123', 'maqingwen', '2017-02-25 19:39:06', 'taolei', '2017-07-26 07:16:14', null, '1', '15688888888');

-- ----------------------------
-- Table structure for `kh_config`
-- ----------------------------
DROP TABLE IF EXISTS `kh_config`;
CREATE TABLE `kh_config` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `value` varchar(50) NOT NULL,
  `comment` varchar(50) DEFAULT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_config
-- ----------------------------

-- ----------------------------
-- Table structure for `kh_course`
-- ----------------------------
DROP TABLE IF EXISTS `kh_course`;
CREATE TABLE `kh_course` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_course
-- ----------------------------
INSERT INTO `kh_course` VALUES ('1', 'JAVA', 'xujiaming', '2017-03-17 20:37:48', 'taolei', '2017-03-27 20:29:43', '', '1');
INSERT INTO `kh_course` VALUES ('2', 'C语言', 'xujiaming', '2017-03-17 20:38:20', null, null, null, '1');
INSERT INTO `kh_course` VALUES ('3', 'C++', 'maqingwen', '2017-03-18 16:41:19', 'test', '2017-07-20 02:12:50', '123', '1');

-- ----------------------------
-- Table structure for `kh_courseclass`
-- ----------------------------
DROP TABLE IF EXISTS `kh_courseclass`;
CREATE TABLE `kh_courseclass` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `teacher_id` int(10) NOT NULL,
  `college_id` int(10) NOT NULL,
  `grade_id` int(10) NOT NULL,
  `start_time` varchar(30) NOT NULL,
  `end_time` varchar(30) NOT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_courseclass
-- ----------------------------
INSERT INTO `kh_courseclass` VALUES ('1', '软工153', '6', '2', '1', '2017-02-27', '2018-02-28', 'luochao', '2017-03-04 14:09:23', 'taolei', '2017-07-29 15:54:25', null, null, '1');
INSERT INTO `kh_courseclass` VALUES ('2', '软工152', '6', '2', '2', '2017-03-20', '2017-03-28', 'luochao', '2017-03-04 14:13:08', 'luochao', '2017-04-10 13:25:58', null, null, '1');
INSERT INTO `kh_courseclass` VALUES ('3', 'java测试二班', '7', '3', '1', '2018-02-06', '2018-03-06', 'luochao', '2017-03-22 21:39:43', 'test', '2017-07-20 03:47:29', null, null, '1');

-- ----------------------------
-- Table structure for `kh_dict`
-- ----------------------------
DROP TABLE IF EXISTS `kh_dict`;
CREATE TABLE `kh_dict` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(80) NOT NULL,
  `value` varchar(80) NOT NULL,
  `label` varchar(80) NOT NULL,
  `description` varchar(80) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_dict
-- ----------------------------
INSERT INTO `kh_dict` VALUES ('1', 'sysRole', '1', '超级管理员', '超级管理员', '0', 'xujiaming', '2017-02-27 20:06:17', '', '0000-00-00 00:00:00', '', '1');
INSERT INTO `kh_dict` VALUES ('2', 'sysRole', '2', '系统维护管理员', '系统维护管理员', '0', 'xujiaming', '2017-02-27 20:06:17', '', '0000-00-00 00:00:00', '', '1');
INSERT INTO `kh_dict` VALUES ('3', 'sysRole', '3', '学院管理员', '学院管理员', '0', 'xujiaming', '2017-02-27 20:06:17', '', '0000-00-00 00:00:00', '', '1');
INSERT INTO `kh_dict` VALUES ('4', 'sysRole', '4', '老师', '老师', '0', 'xujiaming', '2017-02-27 20:06:17', '', '0000-00-00 00:00:00', '', '1');
INSERT INTO `kh_dict` VALUES ('6', 'test', '1', '元老部', '测试所用', '0', 'xujiaming', '2017-02-28 20:08:07', null, '0000-00-00 00:00:00', '', '0');
INSERT INTO `kh_dict` VALUES ('7', 'test', 'test', 'test', 'test', '0', '', '0000-00-00 00:00:00', 'test', '2017-03-18 22:11:07', null, '0');
INSERT INTO `kh_dict` VALUES ('8', 'test2', 'test2', 'test2', 'test2', '0', 'test', '2017-03-18 22:11:49', 'test', '2017-03-30 14:58:20', null, '0');
INSERT INTO `kh_dict` VALUES ('12', '123', '123', '123', '123', '0', 'test', '2017-03-30 15:00:56', 'maqingwen', '2017-04-09 16:06:28', null, '0');
INSERT INTO `kh_dict` VALUES ('13', '11', '11', '11', '11', '0', 'maqingwen', '2017-04-09 16:07:32', null, null, null, '0');
INSERT INTO `kh_dict` VALUES ('14', 'testDBPermiss', '1', '仅可读', '仅可读', '0', 'maqingwen', '2017-05-18 14:27:51', null, null, null, '1');
INSERT INTO `kh_dict` VALUES ('15', 'testDBPermiss', '2', '可读可编辑', '可读可编辑', '0', 'maqingwen', '2017-05-18 14:28:21', null, null, null, '1');
INSERT INTO `kh_dict` VALUES ('16', 'testType', '1', '课堂测试', '普通的课堂测试考试', '0', 'maqingwen', '2017-06-25 14:06:13', null, null, null, '1');
INSERT INTO `kh_dict` VALUES ('17', 'testType', '2', '正式考试', '正式的考试类型，比如期末', '0', 'maqingwen', '2017-06-25 14:07:14', null, null, null, '1');
INSERT INTO `kh_dict` VALUES ('18', 'test', 'test', 'test', 'test', '0', 'taolei', '2017-07-26 08:22:10', null, null, null, '0');

-- ----------------------------
-- Table structure for `kh_grade`
-- ----------------------------
DROP TABLE IF EXISTS `kh_grade`;
CREATE TABLE `kh_grade` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `comment` varchar(50) NOT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_grade
-- ----------------------------
INSERT INTO `kh_grade` VALUES ('1', '2018级', '2333', 'maqingwen', '2017-02-28 16:49:21', 'maqingwen', '2017-03-08 15:28:57', null, '1');
INSERT INTO `kh_grade` VALUES ('2', '2017级', '', 'maqingwen', '2017-02-28 17:06:10', 'maqingwen', '2017-03-07 14:11:20', null, '1');

-- ----------------------------
-- Table structure for `kh_inform`
-- ----------------------------
DROP TABLE IF EXISTS `kh_inform`;
CREATE TABLE `kh_inform` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '通知id',
  `dept_id` int(1) NOT NULL COMMENT '学院id',
  `title` varchar(30) NOT NULL COMMENT '通知标题',
  `sendtype` int(1) NOT NULL COMMENT '发送对象1为学生2为老师3为全体',
  `content` text NOT NULL COMMENT '通知内容',
  `greatedate` datetime NOT NULL COMMENT '通知发布时间',
  `greateby` varchar(64) DEFAULT NULL COMMENT '通知发布人',
  `file_url` varchar(255) DEFAULT NULL COMMENT '附件地址',
  `file_name` varchar(255) DEFAULT NULL COMMENT '附件名称',
  `del_flag` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_inform
-- ----------------------------
INSERT INTO `kh_inform` VALUES ('15', '3', '测试通知', '2', '的的顶顶顶顶顶顶顶顶顶顶&lt;img src=&quot;/cuitcheck/Public/static/kindeditor/attached/image/20170408/20170408102956_41679.jpg&quot; alt=&quot;&quot; /&gt;', '2017-04-08 16:29:58', 'luochao', null, '', '1');
INSERT INTO `kh_inform` VALUES ('16', '3', '测试通知2', '1', '顶顶顶顶', '2017-04-08 00:00:00', 'luochao', null, '', '1');
INSERT INTO `kh_inform` VALUES ('17', '3', '测试通知3', '3', '的顶顶顶顶顶的', '2017-04-08 17:39:24', 'luochao', null, '', '1');
INSERT INTO `kh_inform` VALUES ('18', '2', '测试通知4', '1', '222222222222222', '2017-04-10 10:21:13', 'luochao', null, '', '1');
INSERT INTO `kh_inform` VALUES ('19', '2', '明天放假', '1', '&lt;p&gt;\n	&lt;span style=&quot;color:#FF9900;&quot;&gt;&lt;span style=&quot;color:#FF9900;&quot;&gt;&lt;strong&gt;因为科比夺冠&lt;/strong&gt;&lt;/span&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;\n&lt;p&gt;\n	&lt;span style=&quot;color:#FF9900;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;img src=&quot;/cuitcheck/Public/static/kindeditor/attached/image/20170410/20170410145857_75700.jpg&quot; alt=&quot;&quot; /&gt;&lt;br /&gt;\n&lt;/span&gt; \n&lt;/p&gt;', '2017-04-10 11:05:19', 'luochao', null, '', '1');
INSERT INTO `kh_inform` VALUES ('20', '3', '测试通知6', '1', '&lt;p&gt;\n	&lt;strong&gt;&lt;span style=&quot;background-color:#333333;&quot;&gt;&lt;span style=&quot;font-size:14px;&quot;&gt;哈哈哈&lt;/span&gt;&lt;span style=&quot;background-color:#FFFFFF;font-size:14px;&quot;&gt;哈哈哈&lt;/span&gt;&lt;span style=&quot;font-size:14px;&quot;&gt;&lt;/span&gt;&lt;/span&gt;&lt;/strong&gt;\n&lt;/p&gt;\n&lt;p&gt;\n	&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;img src=&quot;/cuitcheck/Public/static/kindeditor/attached/image/20170410/20170410151156_33216.jpg&quot; alt=&quot;&quot; /&gt;\n&lt;/p&gt;\n&lt;p&gt;\n	&lt;br /&gt;\n&lt;/p&gt;', '2017-04-10 00:00:00', 'luochao', null, '', '1');
INSERT INTO `kh_inform` VALUES ('21', '2', '测试', '1', '的顶顶顶顶顶的\n&lt;pre&gt;&lt;/pre&gt;', '2017-04-11 22:19:02', 'luochao', null, null, '1');
INSERT INTO `kh_inform` VALUES ('22', '3', '的', '1', '顶顶顶顶顶顶顶顶\n&lt;pre&gt;&lt;/pre&gt;', '2017-04-11 22:49:18', 'luochao', null, null, '1');
INSERT INTO `kh_inform` VALUES ('23', '3', 'ddd', '1', 'ddddddddd\n&lt;pre&gt;&lt;/pre&gt;', '2017-04-13 18:17:09', 'luochao', null, null, '1');
INSERT INTO `kh_inform` VALUES ('24', '3', 'wwwwwww', '1', 'wwwwwwwwww\n&lt;pre&gt;&lt;/pre&gt;', '2017-04-15 14:09:39', 'luochao', null, null, '1');
INSERT INTO `kh_inform` VALUES ('25', '3', 'sssss', '1', '&lt;strong&gt;&lt;/strong&gt;sssssssss\n&lt;pre&gt;&lt;/pre&gt;', '2017-04-15 14:12:09', 'luochao', '', '', '1');
INSERT INTO `kh_inform` VALUES ('26', '3', 'eeee', '2', 'eeeeeeee\n&lt;pre&gt;&lt;/pre&gt;', '2017-04-15 14:13:59', 'luochao', null, null, '1');
INSERT INTO `kh_inform` VALUES ('27', '3', 'hhhh', '2', 'hhhhhhhh\n&lt;pre&gt;&lt;/pre&gt;', '2017-04-15 14:18:28', 'luochao', null, null, '1');
INSERT INTO `kh_inform` VALUES ('28', '3', '5555', '1', '55555555\n&lt;pre&gt;&lt;/pre&gt;', '2017-04-15 14:24:27', 'luochao', null, null, '1');
INSERT INTO `kh_inform` VALUES ('29', '3', '6666666', '1', '66666666666\n&lt;pre&gt;&lt;/pre&gt;', '2017-04-15 14:25:20', 'luochao', './Uploads/fujian_file/2017-05-03/14938179304355.xls', '行课班级学生导入.xls', '1');
INSERT INTO `kh_inform` VALUES ('30', '2', '哈哈', '1', 'dddddddd\n&lt;pre&gt;&lt;/pre&gt;', '2017-04-15 20:24:58', 'luochao', './Uploads/fujian_file/2017-05-03/14938178589167.txt', '新建文本文档.txt', '1');
INSERT INTO `kh_inform` VALUES ('31', '3', 'sdasdas', '1', 'sdasdasdas\n&lt;pre&gt;&lt;/pre&gt;', '2017-05-02 16:48:31', 'luochao', './Uploads/fujian_file/2017-05-03/1493798876190.xls', '新建文本文档.txt', '1');
INSERT INTO `kh_inform` VALUES ('32', '3', 'sdasda', '1', 'sadasdasdasda\n&lt;pre&gt;&lt;/pre&gt;', '2017-05-03 16:00:49', 'luochao', './Uploads/fujian_file/2017-05-03/14937984469065.xls', '行课班级学生导入.xls', '1');
INSERT INTO `kh_inform` VALUES ('33', '3', 'weqweq', '1', '&lt;img src=&quot;/cuitcheck/Public/static/kindeditor/attached/image/20170616/20170616103207_50602.jpg&quot; alt=&quot;&quot; /&gt;&lt;img src=&quot;http://localhost/cuitcheck/Public/static/kindeditor/plugins/emoticons/images/9.gif&quot; border=&quot;0&quot; alt=&quot;&quot; /&gt;eqweqwe\n&lt;pre&gt;&lt;/pre&gt;', '2017-05-03 20:01:22', 'luochao', '', '', '0');

-- ----------------------------
-- Table structure for `kh_knowledge`
-- ----------------------------
DROP TABLE IF EXISTS `kh_knowledge`;
CREATE TABLE `kh_knowledge` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `chapter_id` int(10) NOT NULL,
  `comment` varchar(50) DEFAULT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_knowledge
-- ----------------------------
INSERT INTO `kh_knowledge` VALUES ('1', 'java发展历程', '1', 'test', 'xujiaming', '2017-03-23 21:52:46', 'xujiaming', '2017-03-24 20:44:27', null, '1');
INSERT INTO `kh_knowledge` VALUES ('2', 'eclipse使用', '2', 'test', 'xujiaming', '2017-03-23 21:56:56', null, null, null, '1');
INSERT INTO `kh_knowledge` VALUES ('3', '基础变量定义', '3', 'test', 'xujiaming', '2017-03-23 21:57:51', null, null, null, '1');
INSERT INTO `kh_knowledge` VALUES ('4', '控制语句', '3', 'test', 'xujiaming', '2017-03-23 21:59:01', null, null, null, '1');
INSERT INTO `kh_knowledge` VALUES ('5', '循环for语句', '3', 'test', 'xujiaming', '2017-03-23 21:59:54', null, null, null, '1');
INSERT INTO `kh_knowledge` VALUES ('6', 'C语言发展', '5', 'test', 'xujiaming', '2017-03-23 22:01:11', null, null, null, '1');
INSERT INTO `kh_knowledge` VALUES ('7', 'C环境配置', '6', 'test', 'xujiaming', '2017-03-23 22:02:11', null, null, null, '1');
INSERT INTO `kh_knowledge` VALUES ('8', '变量的使用', '7', 'test', 'xujiaming', '2017-03-23 22:03:52', null, null, null, '1');
INSERT INTO `kh_knowledge` VALUES ('9', 'C语言中的地址', '7', 'test', 'xujiaming', '2017-03-23 22:07:22', null, null, null, '1');
INSERT INTO `kh_knowledge` VALUES ('10', 'if-else的使用', '7', 'test', 'xujiaming', '2017-03-23 22:08:56', null, null, null, '1');
INSERT INTO `kh_knowledge` VALUES ('11', 'switch的使用', '7', 'test', 'xujiaming', '2017-03-23 22:09:55', null, null, null, '1');
INSERT INTO `kh_knowledge` VALUES ('13', 'java中的switch用法', '3', '单个添加测试', 'xujiaming', '2017-03-25 00:43:16', null, null, null, '1');
INSERT INTO `kh_knowledge` VALUES ('14', 'switch新特性 case为String', '3', '单个添加测试2', 'xujiaming', '2017-03-25 00:46:55', null, null, null, '1');

-- ----------------------------
-- Table structure for `kh_log`
-- ----------------------------
DROP TABLE IF EXISTS `kh_log`;
CREATE TABLE `kh_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_account` varchar(50) NOT NULL,
  `handlemethod` varchar(50) NOT NULL,
  `handle_location` text,
  `createip` varchar(15) NOT NULL,
  `create_date` datetime NOT NULL,
  `create_by` varchar(64) NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_log
-- ----------------------------
INSERT INTO `kh_log` VALUES ('40', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-07-29 17:00:37', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('41', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-07-29 17:47:06', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('44', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-07-29 17:57:26', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('45', 'maqingwen', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-07-29 22:47:21', 'maqingwen', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('46', 'maqingwen', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-07-29 22:52:02', 'maqingwen', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('47', 'maqingwen', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-07-29 23:00:46', 'maqingwen', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('48', 'maqingwen', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-07-29 23:05:31', 'maqingwen', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('49', 'maqingwen', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-07-29 23:12:08', 'maqingwen', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('50', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-07-29 23:14:22', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('51', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-07-29 23:19:57', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('52', 'maqingwen', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-07-29 23:28:49', 'maqingwen', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('53', 'maqingwen', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-07-29 23:46:34', 'maqingwen', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('54', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-07-30 09:18:28', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('55', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-07-30 09:38:53', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('56', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-07-30 09:39:19', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('57', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-07-30 09:43:41', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('58', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-07-30 09:49:39', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('59', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-07-30 09:50:12', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('60', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-07-30 09:56:00', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('61', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-08-02 17:01:14', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('62', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-08-05 00:18:54', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('63', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-08-27 10:37:46', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('64', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-15 14:21:39', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('65', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-15 14:24:04', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('66', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-15 16:45:53', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('67', 'test', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-15 16:53:01', 'test', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('68', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-15 16:53:52', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('69', 'laowang', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-15 16:54:22', 'laowang', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('70', 'laowang', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-15 16:58:33', 'laowang', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('71', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-15 17:06:28', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('72', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-17 21:39:43', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('73', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-17 21:40:32', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('74', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-17 21:58:06', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('75', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-19 19:35:19', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('76', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-19 19:38:49', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('77', 'laowang', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-19 19:52:14', 'laowang', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('78', 'laowang', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-19 19:53:00', 'laowang', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('79', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-19 19:54:00', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('80', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-19 20:04:04', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('81', 'laowang', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-19 20:09:55', 'laowang', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('82', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-19 20:11:24', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('83', 'laowang', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-19 20:34:32', 'laowang', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('84', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-19 20:42:06', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('85', 'laowang', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-19 21:22:40', 'laowang', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('86', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-19 21:23:24', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('87', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-19 22:24:14', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('88', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-22 13:13:54', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('89', 'laowang', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-22 14:18:51', 'laowang', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('90', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-09-22 14:20:47', 'taolei', null, null, null, '1');

-- ----------------------------
-- Table structure for `kh_major`
-- ----------------------------
DROP TABLE IF EXISTS `kh_major`;
CREATE TABLE `kh_major` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `comment` varchar(50) NOT NULL,
  `dept_id` int(11) NOT NULL COMMENT '上级学院的id',
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_major
-- ----------------------------
INSERT INTO `kh_major` VALUES ('1', '软件工程', '', '2', 'maqingwen', '2017-03-01 15:57:21', 'maqingwen', '2017-03-01 16:03:54', null, '1');
INSERT INTO `kh_major` VALUES ('2', '空间信息与数字技术应用', '2333', '2', 'maqingwen', '2017-03-01 16:07:56', null, null, null, '1');

-- ----------------------------
-- Table structure for `kh_menu`
-- ----------------------------
DROP TABLE IF EXISTS `kh_menu`;
CREATE TABLE `kh_menu` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `url` varchar(50) NOT NULL,
  `tree_code` int(1) NOT NULL,
  `parent_id` int(10) NOT NULL,
  `sort` int(10) NOT NULL,
  `icon` varchar(30) NOT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_menu
-- ----------------------------
INSERT INTO `kh_menu` VALUES ('1', '系统管理', '', '1', '0', '2', 'icon-shezhi', '', '0000-00-00 00:00:00', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('2', '日志管理', 'Home/System/log', '2', '1', '1', 'fa-calculator', '', '0000-00-00 00:00:00', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('3', '个人中心', '', '1', '0', '1', '&#xe612;', '', '0000-00-00 00:00:00', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('4', '资料修改', 'Home/PersonCenter/Updateinfo', '2', '3', '2', '&#xe642;', '', '0000-00-00 00:00:00', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('5', '一级菜单', '', '1', '0', '0', 'fa-bicycle', '', '0000-00-00 00:00:00', null, null, null, '0');
INSERT INTO `kh_menu` VALUES ('6', '二级菜单', '', '2', '5', '0', 'fa-rocket', '', '0000-00-00 00:00:00', null, null, null, '0');
INSERT INTO `kh_menu` VALUES ('7', '测试', 'Home/System/log', '1', '0', '0', 'fa-eye', '', '0000-00-00 00:00:00', null, null, null, '0');
INSERT INTO `kh_menu` VALUES ('8', '菜单管理', 'Home/System/menu', '2', '1', '2', 'icon-caidan', '', '0000-00-00 00:00:00', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('9', '用户管理', '', '1', '0', '1', 'icon-yonghuguanli', 'xujiaming', '2017-02-26 17:28:37', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('10', '系统用户管理', 'Home/SysuserManage/sysUserList', '2', '9', '0', 'icon-bianji', 'xujiaming', '2017-02-26 17:36:13', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('11', '学院管理', '', '1', '0', '2', 'icon-bianji', 'maqingwen', '2017-02-27 13:42:41', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('12', '学院管理', 'Home/CollegeMgr/collegeList', '2', '11', '0', 'icon-xueyuan', 'maqingwen', '2017-02-27 13:51:37', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('13', '年级管理', 'Home/GradeMgr/gradeList', '2', '11', '1', 'icon-iconnianji', 'maqingwen', '2017-02-27 13:52:23', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('14', '专业管理', 'Home/MajorMgr/majorList', '2', '11', '2', 'icon-ux13991945397084126', 'maqingwen', '2017-02-27 13:52:45', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('15', '行政班级管理', 'Home/ClassMgr/classList', '2', '11', '3', 'icon-icon57', 'maqingwen', '2017-02-27 13:53:07', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('16', '行课班级管理', 'Home/CourseclassMgr/courseclassList', '2', '11', '4', 'icon-icon57', 'maqingwen', '2017-02-27 13:53:34', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('17', '学科管理', '', '1', '0', '5', 'icon-shezhi', 'maqingwen', '2017-03-13 15:36:44', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('18', '学科管理', 'Home/CourseMgr/courseList', '2', '17', '1', 'icon-xuekefenlei', 'maqingwen', '2017-03-13 15:55:26', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('19', '章节知识点管理', '', '1', '0', '4', 'fa-archive', 'xujiaming', '2017-03-17 15:14:52', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('20', '章节管理', 'Home/ChapterMgr/chapterList', '2', '19', '0', 'fa-sitemap', 'xujiaming', '2017-03-17 15:29:46', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('21', '字典管理', 'Home/Dictionary/DictionaryList', '2', '1', '4', '&#xe61d;', '', '0000-00-00 00:00:00', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('22', '学生管理', 'Home/StudentMgr/studentList', '2', '11', '5', 'icon-icon57', 'liangxuanhao', '2017-03-11 10:10:10', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('23', '题库管理', '', '1', '0', '6', 'icon-tikuguanli', 'maqingwen', '2017-03-22 13:03:37', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('24', '知识点管理', 'Home/KnowledgeMgr/knowledgeList', '2', '19', '1', 'fa-file-text-o', 'xujiaming', '2017-03-23 15:40:30', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('25', '题库管理', 'Home/TestDatabaseMgr/testDatabaseList', '2', '23', '0', 'icon-tiku', 'maiqngwen', '2017-03-28 14:30:46', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('26', '通知公告', 'Home/InformMgr/informList', '2', '1', '1', '&#xe609;', '', '0000-00-00 00:00:00', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('27', '题库授权', 'Home/TestDBPermissionMgr/testDBList', '2', '23', '1', 'icon-shouquan', 'maqingwen', '2017-04-13 20:01:53', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('28', '试卷管理', '', '1', '0', '7', 'icon-kaoshi', 'maqingwen', '2017-06-10 14:24:55', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('29', '组卷管理', 'Home/ClassPapersCreateMgr/ClassPapersList', '2', '28', '1', 'icon-ceshi', 'maqingwen', '2017-06-10 14:26:02', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('30', '分配正式考试', 'Home/ClassPapersDetialMgr/showFinalTest', '2', '28', '2', 'icon-kao', 'maqingwen', '2017-06-10 14:27:20', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('31', '出题授权', 'Home/TestTeacherPerMgr/testTeacherList', '2', '28', '3', 'icon-shouquan', 'maqingwen', '2017-06-10 14:29:44', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('32', '分析统计', '', '1', '0', '8', '&#xe62c;', 'liangxuanhao', '2017-07-20 14:51:23', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('33', '考试记录', 'Home/ScoreAnalysis/testRecordList', '2', '32', '1', 'fa fa-history', 'liangxuanhao', '2017-07-20 15:03:47', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('34', '成绩详情', 'Home/ScoreAnalysis/paperScoreList', '2', '32', '2', 'fa fa-digg', 'liangxuanhao', '2017-07-20 15:06:16', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('35', '学生成绩检索', 'Home/ScoreAnalysis/studentScore', '2', '32', '4', 'fa fa-history', 'liangxuanhao', '2017-07-22 17:31:30', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('36', '学生统览', 'Home/ScoreAnalysis/classList', '2', '32', '3', 'fa fa-digg', 'liangxuanhao', '2017-07-26 14:59:39', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('37', '用户组管理', 'Home/SysuserGroup/sysGroupList', '2', '9', '1', 'icon-bianji', '', '0000-00-00 00:00:00', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('38', '权限管理', 'Home/SysuserRule/sysRuleList', '1', '9', '1', '&#xe628', '', '0000-00-00 00:00:00', null, null, null, '1');

-- ----------------------------
-- Table structure for `kh_paper_courserclass`
-- ----------------------------
DROP TABLE IF EXISTS `kh_paper_courserclass`;
CREATE TABLE `kh_paper_courserclass` (
  `testpaper_id` int(10) NOT NULL,
  `courserclass_id` int(10) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `comment` varchar(50) NOT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_paper_courserclass
-- ----------------------------
INSERT INTO `kh_paper_courserclass` VALUES ('1', '2', '2017-07-10 20:00:00', '2017-08-22 15:00:00', '11', '111', '2017-02-27 00:00:00', '111', '2017-02-27 00:00:00', null, '1');
INSERT INTO `kh_paper_courserclass` VALUES ('2', '2', '2017-02-27 00:00:00', '2017-02-27 00:00:00', '11', '111', '2017-02-27 00:00:00', '111', '2017-02-27 00:00:00', null, '1');
INSERT INTO `kh_paper_courserclass` VALUES ('3', '2', '2017-08-05 21:00:00', '2017-09-07 23:00:00', '11', '11', '2017-07-15 14:49:37', null, '2017-07-15 14:49:43', null, '1');
INSERT INTO `kh_paper_courserclass` VALUES ('5', '2', '2017-06-20 21:00:00', '2017-06-22 23:00:00', '数据库直接添加,用来测试', 'pjy', '0000-00-00 00:00:00', null, null, null, '1');
INSERT INTO `kh_paper_courserclass` VALUES ('3', '1', '2017-08-05 21:00:00', '2017-09-07 23:00:00', '', 'maqingwen', '2017-07-30 16:27:36', 'maqingwen', '2017-07-30 17:03:45', null, '0');
INSERT INTO `kh_paper_courserclass` VALUES ('3', '1', '2017-08-05 21:00:00', '2017-09-07 23:00:00', '', 'maqingwen', '2017-07-30 17:04:08', null, null, null, '1');
INSERT INTO `kh_paper_courserclass` VALUES ('5', '1', '2017-09-19 19:37:05', '2017-09-19 19:39:10', '', 'taolei', '2017-09-19 19:37:23', null, null, null, '0');
INSERT INTO `kh_paper_courserclass` VALUES ('5', '1', '2017-09-19 19:40:42', '2017-09-19 19:43:03', '', 'taolei', '2017-09-19 19:41:13', null, null, null, '0');

-- ----------------------------
-- Table structure for `kh_paper_question`
-- ----------------------------
DROP TABLE IF EXISTS `kh_paper_question`;
CREATE TABLE `kh_paper_question` (
  `testpaper_id` int(10) NOT NULL,
  `question_id` int(10) NOT NULL,
  `value` int(10) NOT NULL,
  `comment` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_paper_question
-- ----------------------------
INSERT INTO `kh_paper_question` VALUES ('1', '49', '1', '');
INSERT INTO `kh_paper_question` VALUES ('1', '50', '1', '');
INSERT INTO `kh_paper_question` VALUES ('1', '52', '2', '');
INSERT INTO `kh_paper_question` VALUES ('1', '51', '2', '');
INSERT INTO `kh_paper_question` VALUES ('1', '57', '2', '');
INSERT INTO `kh_paper_question` VALUES ('6', '53', '3', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('6', '56', '2', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('6', '55', '2', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('5', '52', '3', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('5', '19', '3', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('5', '1', '3', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('5', '51', '3', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('5', '50', '1', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('5', '17', '1', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('5', '12', '1', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('5', '5', '1', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('5', '4', '1', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('5', '6', '1', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('5', '20', '2', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('5', '49', '2', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('2', '53', '0', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('2', '55', '0', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('2', '56', '0', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('1', '53', '0', '');
INSERT INTO `kh_paper_question` VALUES ('8', '51', '3', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('8', '52', '3', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('8', '57', '3', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('8', '19', '3', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('8', '4', '2', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('8', '50', '2', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('8', '2', '2', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('8', '5', '2', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('8', '9', '1', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('8', '20', '1', '自动生成添加');

-- ----------------------------
-- Table structure for `kh_practice`
-- ----------------------------
DROP TABLE IF EXISTS `kh_practice`;
CREATE TABLE `kh_practice` (
  `id` int(10) NOT NULL,
  `account` int(10) NOT NULL,
  `questions` text NOT NULL,
  `answers` text NOT NULL,
  `creatime` varchar(30) NOT NULL,
  `difficulty` int(1) NOT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_practice
-- ----------------------------

-- ----------------------------
-- Table structure for `kh_question`
-- ----------------------------
DROP TABLE IF EXISTS `kh_question`;
CREATE TABLE `kh_question` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `testdb_id` int(10) NOT NULL,
  `content` text NOT NULL,
  `type` int(10) NOT NULL,
  `level` int(10) NOT NULL,
  `knowledge_ids` text NOT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_question
-- ----------------------------
INSERT INTO `kh_question` VALUES ('1', '2', 'test4 2323 &lt;br&gt;', '1', '1', '1,2,3', 'pjy', '0000-00-00 00:00:00', 'taolei', '2017-07-14 08:45:49', null, '0');
INSERT INTO `kh_question` VALUES ('2', '2', 'test2', '2', '2', '3,4', 'pjy', '0000-00-00 00:00:00', 'test', '2017-07-14 08:56:14', null, '0');
INSERT INTO `kh_question` VALUES ('3', '2', 'asdsa&amp;nbsp;', '2', '2', '2', '', '0000-00-00 00:00:00', null, null, null, '1');
INSERT INTO `kh_question` VALUES ('4', '2', 'asdsa&amp;nbsp;', '2', '2', '2', '', '0000-00-00 00:00:00', null, null, null, '1');
INSERT INTO `kh_question` VALUES ('5', '2', 'asdsa&amp;nbsp;', '2', '2', '2', '', '0000-00-00 00:00:00', null, null, null, '1');
INSERT INTO `kh_question` VALUES ('6', '2', 'test2&lt;img src=&quot;http://localhost/cuitcheck/Uploads/question_photo/20170425/14931244294939.jpg&quot; alt=&quot;undefined&quot;&gt;', '2', '3', '2,5', 'test', '2017-04-25 20:47:14', null, null, null, '1');
INSERT INTO `kh_question` VALUES ('9', '2', '大师的大师的撒大声萨达萨达啊实打实阿萨德圣诞树阿萨德萨达萨达阿萨德', '3', '3', '5', 'test', '2017-04-25 21:04:21', 'test', '2017-04-26 20:54:10', null, '0');
INSERT INTO `kh_question` VALUES ('12', '2', 'test answer add&lt;br&gt;', '2', '2', '3', 'test', '2017-04-26 21:08:55', null, null, null, '1');
INSERT INTO `kh_question` VALUES ('15', '2', 'test answer add2&lt;br&gt;', '2', '2', '3', 'test', '2017-04-26 21:20:51', null, null, null, '1');
INSERT INTO `kh_question` VALUES ('17', '2', 'test add 3&lt;br&gt;', '2', '2', '3,4', 'test', '2017-04-26 21:32:22', null, null, null, '1');
INSERT INTO `kh_question` VALUES ('19', '2', 'test add 4&lt;br&gt;', '1', '1', '2', 'test', '2017-04-26 21:42:46', 'test', '2017-05-02 21:11:47', null, '1');
INSERT INTO `kh_question` VALUES ('20', '2', 'test add 5&lt;br&gt;', '3', '1', '1,2,5', 'test', '2017-04-26 21:47:29', 'test', '2017-05-02 21:28:51', null, '1');
INSERT INTO `kh_question` VALUES ('49', '2', '设a,b,t 为整型变量,初值为a=7,b=9,执行完语句t=(a>b)?a:b后,t 的值是', '3', '2', '', 'test', '2017-05-29 11:30:32', null, null, '测试例题(添加时,请务必将其覆盖或删除!!!)', '1');
INSERT INTO `kh_question` VALUES ('50', '2', 'C语言程序总是从main()函数开始执行', '2', '1', '', 'test', '2017-05-29 11:30:33', null, null, '测试例题(添加时,请务必将其覆盖或删除!!!)', '1');
INSERT INTO `kh_question` VALUES ('51', '2', '设a和b均为double型变量，且a=5.5、b=2.5，则表达式(int)a+b/b的值是', '1', '3', '', 'test', '2017-05-29 11:30:33', null, null, '测试例题(添加时,请务必将其覆盖或删除!!!)', '1');
INSERT INTO `kh_question` VALUES ('52', '2', '&lt;p&gt;public void main(){&lt;/p&gt;&lt;p&gt;}', '1', '1', '9', 'maqingwen', '2017-06-12 17:11:49', 'test', '2017-07-22 15:42:19', null, '1');
INSERT INTO `kh_question` VALUES ('53', '1', '2131231', '1', '1', '1,2', 'test', '2017-06-13 13:19:31', null, null, null, '1');
INSERT INTO `kh_question` VALUES ('55', '1', '问题-知识点表测试', '2', '1', '', 'test', '2017-07-24 16:18:45', null, null, null, '1');
INSERT INTO `kh_question` VALUES ('56', '1', '&lt;p&gt;问题-知识点表测试&lt;/p&gt;', '2', '3', '2,4', 'test', '2017-07-24 16:19:43', 'test', '2017-07-24 16:33:00', null, '1');
INSERT INTO `kh_question` VALUES ('57', '2', '&lt;p&gt;以下代码输出的是（）&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;public void main{&lt;/p&gt;&lt;p&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; int a = b+c;&lt;/p&gt;&lt;p&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; System.out.println(a);&lt;/p&gt;&lt;p&gt;}&lt;/p&gt;', '1', '1', '', 'maqingwen', '2017-07-26 12:59:35', 'maqingwen', '2017-07-26 13:01:02', null, '1');

-- ----------------------------
-- Table structure for `kh_question_know`
-- ----------------------------
DROP TABLE IF EXISTS `kh_question_know`;
CREATE TABLE `kh_question_know` (
  `question_id` int(10) NOT NULL,
  `knowledge_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_question_know
-- ----------------------------
INSERT INTO `kh_question_know` VALUES ('1', '1');
INSERT INTO `kh_question_know` VALUES ('1', '2');
INSERT INTO `kh_question_know` VALUES ('1', '3');
INSERT INTO `kh_question_know` VALUES ('2', '3');
INSERT INTO `kh_question_know` VALUES ('2', '4');
INSERT INTO `kh_question_know` VALUES ('3', '2');
INSERT INTO `kh_question_know` VALUES ('4', '2');
INSERT INTO `kh_question_know` VALUES ('5', '2');
INSERT INTO `kh_question_know` VALUES ('6', '2');
INSERT INTO `kh_question_know` VALUES ('6', '5');
INSERT INTO `kh_question_know` VALUES ('9', '5');
INSERT INTO `kh_question_know` VALUES ('12', '3');
INSERT INTO `kh_question_know` VALUES ('15', '3');
INSERT INTO `kh_question_know` VALUES ('17', '3');
INSERT INTO `kh_question_know` VALUES ('17', '4');
INSERT INTO `kh_question_know` VALUES ('19', '2');
INSERT INTO `kh_question_know` VALUES ('20', '1');
INSERT INTO `kh_question_know` VALUES ('20', '2');
INSERT INTO `kh_question_know` VALUES ('20', '5');
INSERT INTO `kh_question_know` VALUES ('52', '9');
INSERT INTO `kh_question_know` VALUES ('53', '1');
INSERT INTO `kh_question_know` VALUES ('53', '2');
INSERT INTO `kh_question_know` VALUES ('55', '5');
INSERT INTO `kh_question_know` VALUES ('55', '6');
INSERT INTO `kh_question_know` VALUES ('56', '2');
INSERT INTO `kh_question_know` VALUES ('56', '4');
INSERT INTO `kh_question_know` VALUES ('57', '1');
INSERT INTO `kh_question_know` VALUES ('57', '4');

-- ----------------------------
-- Table structure for `kh_rank`
-- ----------------------------
DROP TABLE IF EXISTS `kh_rank`;
CREATE TABLE `kh_rank` (
  `account` int(10) NOT NULL,
  `courseclass_id` int(10) NOT NULL,
  `createtime` varchar(30) NOT NULL,
  `practice_id` int(10) NOT NULL,
  `getscore` int(10) NOT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_rank
-- ----------------------------

-- ----------------------------
-- Table structure for `kh_score`
-- ----------------------------
DROP TABLE IF EXISTS `kh_score`;
CREATE TABLE `kh_score` (
  `account` int(10) NOT NULL,
  `testpaper_id` int(10) NOT NULL,
  `score` int(10) NOT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_score
-- ----------------------------
INSERT INTO `kh_score` VALUES ('1', '1', '80', '', '0000-00-00 00:00:00', null, null, null, '1');
INSERT INTO `kh_score` VALUES ('2015081114', '2', '80', '111', '2017-03-04 14:09:23', '222', '2017-03-04 14:09:23', null, '1');
INSERT INTO `kh_score` VALUES ('2015081115', '1', '80', 'pjy', '0000-00-00 00:00:00', null, null, null, '1');
INSERT INTO `kh_score` VALUES ('2015081114', '3', '0', '', '0000-00-00 00:00:00', null, null, null, '1');

-- ----------------------------
-- Table structure for `kh_student`
-- ----------------------------
DROP TABLE IF EXISTS `kh_student`;
CREATE TABLE `kh_student` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `account` varchar(13) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(20) NOT NULL,
  `sex` int(1) NOT NULL,
  `class_id` int(10) NOT NULL,
  `grade_id` int(10) NOT NULL,
  `major_id` int(10) NOT NULL,
  `dept_id` int(10) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `email` varchar(25) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `status` int(1) NOT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_student
-- ----------------------------
INSERT INTO `kh_student` VALUES ('1', '2015081114', 'b296fdd2ac121c7ea8c47b6c68468534', '小罗', '0', '2', '2', '1', '2', '', '282196651@qq.com', '15688888888', '1', null, 'taolei', '2017-03-27 19:53:55', null, null, '', '1');
INSERT INTO `kh_student` VALUES ('2', '2015081115', 'b3360cc45c2819fc1ea9b0f16c15fdee', 'jack', '1', '2', '2', '1', '2', '', '', '15688888888', '1', null, 'taolei', '2017-03-27 19:55:50', null, null, '', '1');
INSERT INTO `kh_student` VALUES ('3', '2015081116', 'eaf5ea354d4f78254293806cd917a0b7', 'lucy', '0', '2', '2', '1', '2', '', '', '15688888888', '1', null, 'taolei', '2017-03-27 19:55:51', null, null, '', '1');
INSERT INTO `kh_student` VALUES ('4', '2015081117', 'bcf3b4fc692392373a121e463d60abd7', 'dsad3', '0', '2', '2', '1', '2', '', '', '15688888888', '1', null, 'taolei', '2017-03-27 19:55:51', null, null, '', '1');
INSERT INTO `kh_student` VALUES ('5', '2015081119', 'a65a8b56234fe11744058a502ce6dc4b', 'dsad', '0', '2', '2', '1', '2', '', '', '15688888888', '1', null, 'taolei', '2017-03-27 19:55:51', null, null, '', '1');
INSERT INTO `kh_student` VALUES ('6', '2015081118', '6296555496d46f34b4c4e47c60247e70', '2', '0', '2', '2', '1', '3', '', '', '15688888888', '1', null, 'taolei', '2017-03-27 19:55:51', null, null, '', '1');
INSERT INTO `kh_student` VALUES ('7', '20155454545', '9756a0967824b596a71af7a459c80cc3', '测试', '1', '2', '2', '1', '2', '', '', '', '1', null, 'taolei', '2017-04-05 21:32:07', null, null, '测试一下导入', '1');
INSERT INTO `kh_student` VALUES ('8', '2014081009', 'd93a5def7511da3d0f2d171d9c344e91', '肯德基', '1', '2', '2', '1', '2', null, null, '18780192254', '1', null, 'taolei', '2017-07-23 01:09:46', null, null, null, '1');

-- ----------------------------
-- Table structure for `kh_sysuser`
-- ----------------------------
DROP TABLE IF EXISTS `kh_sysuser`;
CREATE TABLE `kh_sysuser` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `account` varchar(16) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(20) NOT NULL,
  `sex` int(1) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `email` varchar(30) NOT NULL,
  `role` int(1) NOT NULL,
  `status` int(1) NOT NULL,
  `dept_id` int(10) NOT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_sysuser
-- ----------------------------
INSERT INTO `kh_sysuser` VALUES ('1', 'taolei', 'd93a5def7511da3d0f2d171d9c344e91', '陶磊', '1', '/cuitcheck/Uploads/UserPhoto/sysuser_1.jpeg', '15025693622', '492360041@qq.com', '1', '1', '2', '', '2017-02-25 15:38:04', 'maqingwen', '2017-06-13 13:13:56', '', '1');
INSERT INTO `kh_sysuser` VALUES ('2', 'xujiaming', 'd93a5def7511da3d0f2d171d9c344e91', '许加明', '1', '/cuitcheck/Uploads/UserPhoto/sysuser_2.jpeg', '18780192258', '2368272774@qq.com', '1', '1', '2', '', '2017-02-26 15:38:10', 'xujiaming', '2017-03-01 17:11:45', '', '1');
INSERT INTO `kh_sysuser` VALUES ('3', 'maqingwen', 'd93a5def7511da3d0f2d171d9c344e91', '马庆文', '1', '/cuitcheck/Uploads/UserPhoto/sysuser_2.jpeg', '18780192235', '122655555@qq.com', '3', '1', '2', '', '2017-02-27 15:38:16', 'xujiaming', '2017-03-13 17:20:38', '第三方的是非得失', '1');
INSERT INTO `kh_sysuser` VALUES ('4', 'luochao', 'd93a5def7511da3d0f2d171d9c344e91', '罗钞', '1', '/cuitcheck/Uploads/UserPhoto/sysuser_4.jpeg', '18080283060', '282196651@qq.com', '3', '1', '3', '', '2017-02-23 15:38:23', 'maqingwen', '2017-03-14 15:10:19', '', '1');
INSERT INTO `kh_sysuser` VALUES ('5', 'test', 'd93a5def7511da3d0f2d171d9c344e91', 'test', '1', '', '18780192214', '1878084884@qq.com', '3', '1', '3', 'taolei', '2017-03-14 15:09:10', 'luochao', '2017-03-14 15:11:31', '', '1');
INSERT INTO `kh_sysuser` VALUES ('6', 'laowang', 'd93a5def7511da3d0f2d171d9c344e91', '老王', '0', '', '15688888888', '123123121@qq.com', '4', '1', '2', 'maqingwen', '2017-06-14 20:51:14', 'xujiaming', '2017-06-20 13:58:42', '', '1');
INSERT INTO `kh_sysuser` VALUES ('7', 'jiaoshi2', 'd93a5def7511da3d0f2d171d9c344e91', '测试教师2', '1', '', '15688888888', '2313123213@qq.com', '4', '1', '3', 'maqingwen', '2017-06-21 13:16:10', 'maqingwen', '2017-06-21 13:17:16', '测试用，请勿修改', '1');

-- ----------------------------
-- Table structure for `kh_test_final`
-- ----------------------------
DROP TABLE IF EXISTS `kh_test_final`;
CREATE TABLE `kh_test_final` (
  `testpaper_id` int(10) NOT NULL,
  `student_id` int(10) NOT NULL,
  `question_id` int(10) NOT NULL,
  `answer_id` int(10) NOT NULL,
  `is_true` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_test_final
-- ----------------------------
INSERT INTO `kh_test_final` VALUES ('2', '1', '19', '4', '0');
INSERT INTO `kh_test_final` VALUES ('2', '1', '20', '0', '0');
INSERT INTO `kh_test_final` VALUES ('2', '1', '51', '23', '1');
INSERT INTO `kh_test_final` VALUES ('2', '1', '50', '0', '1');
INSERT INTO `kh_test_final` VALUES ('2', '1', '52', '27', '0');
INSERT INTO `kh_test_final` VALUES ('2', '1', '52', '27', '0');
INSERT INTO `kh_test_final` VALUES ('2', '1', '52', '27', '0');

-- ----------------------------
-- Table structure for `kh_test_teacher_permission`
-- ----------------------------
DROP TABLE IF EXISTS `kh_test_teacher_permission`;
CREATE TABLE `kh_test_teacher_permission` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(10) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_test_teacher_permission
-- ----------------------------
INSERT INTO `kh_test_teacher_permission` VALUES ('1', '6', '2017-06-14 20:52:19', '2017-08-02 20:52:19', 'maqingwen', '2017-06-14 20:52:19', 'xujiaming', '2017-07-29 23:16:07', null, '1');
INSERT INTO `kh_test_teacher_permission` VALUES ('10', '7', '2017-06-08 15:28:24', '2017-06-30 15:28:27', 'maqingwen', '2017-06-21 15:28:29', 'maqingwen', '2017-06-21 15:28:40', null, '1');
INSERT INTO `kh_test_teacher_permission` VALUES ('11', '7', '2017-07-14 09:55:41', '2017-09-14 09:55:49', 'luochao', '2017-07-14 09:56:08', null, null, null, '1');
INSERT INTO `kh_test_teacher_permission` VALUES ('13', '6', '2017-07-18 00:03:41', '2017-08-04 00:03:43', 'maqingwen', '2017-07-30 00:03:45', 'maqingwen', '2017-07-30 00:08:16', null, '1');
INSERT INTO `kh_test_teacher_permission` VALUES ('14', '6', '2017-08-19 20:07:20', '2017-09-30 20:07:29', 'taolei', '2017-09-19 20:07:33', null, null, null, '1');

-- ----------------------------
-- Table structure for `kh_testcache`
-- ----------------------------
DROP TABLE IF EXISTS `kh_testcache`;
CREATE TABLE `kh_testcache` (
  `testpaper_id` int(10) NOT NULL,
  `student_id` int(10) NOT NULL,
  `question_id` int(10) NOT NULL,
  `answer_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_testcache
-- ----------------------------

-- ----------------------------
-- Table structure for `kh_testdatabase`
-- ----------------------------
DROP TABLE IF EXISTS `kh_testdatabase`;
CREATE TABLE `kh_testdatabase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `course_id` int(10) NOT NULL,
  `college_id` int(10) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `comment` varchar(50) DEFAULT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_testdatabase
-- ----------------------------
INSERT INTO `kh_testdatabase` VALUES ('1', 'java基础1', '1', '2', '1', 'aaa1', 'maqingwen', '2017-03-29 17:05:17', 'luochao', '2017-07-14 09:49:43', null, '1');
INSERT INTO `kh_testdatabase` VALUES ('2', 'C++基础练习1', '3', '3', '1', '', 'maqingwen', '2017-04-05 15:50:10', 'test', '2017-06-13 14:50:04', null, '1');
INSERT INTO `kh_testdatabase` VALUES ('3', 'C语言高级练习', '2', '2', '1', '222333', 'maqingwen', '2017-04-11 19:15:04', null, null, null, '1');
INSERT INTO `kh_testdatabase` VALUES ('5', 'php', '1', '3', '1', '', 'test', '2017-06-13 15:15:02', 'taolei', '2017-07-18 17:53:53', null, '1');

-- ----------------------------
-- Table structure for `kh_testdatabase_permission`
-- ----------------------------
DROP TABLE IF EXISTS `kh_testdatabase_permission`;
CREATE TABLE `kh_testdatabase_permission` (
  `testdb_id` int(10) NOT NULL,
  `college_id` int(10) NOT NULL,
  `permiss_level` int(1) NOT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_testdatabase_permission
-- ----------------------------
INSERT INTO `kh_testdatabase_permission` VALUES ('1', '2', '2', 'maqingwen', '2017-04-13 10:45:22', 'maqingwen', '2017-06-01 12:28:15', null, '1');
INSERT INTO `kh_testdatabase_permission` VALUES ('2', '3', '2', 'maqingwen', '2017-04-13 10:45:52', null, null, null, '1');
INSERT INTO `kh_testdatabase_permission` VALUES ('3', '2', '2', 'maqingwen', '2017-04-13 10:46:09', null, null, null, '1');
INSERT INTO `kh_testdatabase_permission` VALUES ('1', '3', '2', 'maqingwen', '2017-06-01 15:40:47', 'xujiaming', '2017-07-26 23:23:10', null, '0');
INSERT INTO `kh_testdatabase_permission` VALUES ('5', '3', '2', 'test', '2017-06-13 15:15:02', null, null, null, '1');
INSERT INTO `kh_testdatabase_permission` VALUES ('5', '2', '1', 'test', '2017-07-19 12:21:35', 'xujiaming', '2017-07-26 23:11:12', null, '0');

-- ----------------------------
-- Table structure for `kh_testpaper`
-- ----------------------------
DROP TABLE IF EXISTS `kh_testpaper`;
CREATE TABLE `kh_testpaper` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `type_id` int(10) NOT NULL,
  `college_id` int(10) NOT NULL,
  `full_score` int(10) DEFAULT NULL,
  `pass_score` int(10) DEFAULT NULL,
  `is_use` int(1) NOT NULL DEFAULT '1',
  `create_date` datetime NOT NULL,
  `create_by` varchar(60) NOT NULL,
  `update_date` datetime DEFAULT NULL,
  `update_by` varchar(60) DEFAULT NULL,
  `comment` varchar(255) NOT NULL,
  `del_flag` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_testpaper
-- ----------------------------
INSERT INTO `kh_testpaper` VALUES ('1', 'java第一章随堂测试', '1', '2', '8', '60', '1', '2017-06-19 18:23:43', 'xujiaming', '2017-06-21 16:17:39', 'xujiaming', '666', '1');
INSERT INTO `kh_testpaper` VALUES ('2', 'java第二章随堂测试', '1', '2', '0', '0', '1', '0000-00-00 00:00:00', 'xujiaming', '2017-07-29 01:11:11', 'xujiaming', '自动生成更新基础分值', '1');
INSERT INTO `kh_testpaper` VALUES ('3', 'java第三章随堂测试', '2', '2', '0', '0', '1', '2017-06-20 17:09:10', 'xujiaming', '2017-07-30 00:02:50', 'xujiaming', '自动生成更新基础分值', '1');
INSERT INTO `kh_testpaper` VALUES ('5', '老师出试卷 test', '1', '2', '25', '15', '1', '2017-06-21 14:02:37', 'laowang', '2017-07-29 00:48:43', 'xujiaming', '自动生成更新基础分值', '1');
INSERT INTO `kh_testpaper` VALUES ('6', '学院管理医添加试卷 test', '1', '3', '8', '4', '1', '2017-06-21 14:06:21', 'test', '2017-07-29 00:43:42', 'xujiaming', '自动生成更新基础分值', '1');
INSERT INTO `kh_testpaper` VALUES ('8', '计算机学院老师添加正式考试试卷', '2', '3', '22', '13', '1', '2017-07-30 00:20:20', 'test', '2017-07-30 00:21:31', 'test', '自动生成更新基础分值', '1');
