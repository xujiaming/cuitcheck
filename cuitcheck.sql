/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50716
Source Host           : localhost:3306
Source Database       : cuitcheck

Target Server Type    : MYSQL
Target Server Version : 50716
File Encoding         : 65001

Date: 2017-11-05 23:11:36
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for kh_answer
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_answer
-- ----------------------------
INSERT INTO `kh_answer` VALUES ('1', '1', '正确', '1', 'taolei', '2017-11-04 15:34:44', null, null, null, '1');
INSERT INTO `kh_answer` VALUES ('16', '20', '1', '1', 'taolei', '2017-11-04 15:52:46', 'taolei', '2017-11-04 21:46:28', null, '1');
INSERT INTO `kh_answer` VALUES ('17', '21', 'T', '1', 'taolei', '2017-11-04 15:52:46', null, null, null, '1');
INSERT INTO `kh_answer` VALUES ('18', '23', '1', '1', 'taolei', '2017-11-04 15:52:54', null, null, null, '1');
INSERT INTO `kh_answer` VALUES ('19', '24', 'C语言程序总是从main()函数开始执行', '1', 'taolei', '2017-11-04 15:52:54', 'taolei', '2017-11-04 16:01:07', null, '1');
INSERT INTO `kh_answer` VALUES ('20', '25', '0:6.500000(END)0:6(END)3:5.500000(END)0:6.000000(END)', '1', 'taolei', '2017-11-04 15:52:54', 'taolei', '2017-11-04 16:01:20', null, '1');

-- ----------------------------
-- Table structure for kh_auth_group
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
INSERT INTO `kh_auth_group` VALUES ('1', '超级管理员', '1', '1,2,3,4,17,18,19,20,24,5,6,27,8,9,15,16,28,11,12,13,14,23,25,21,22,26,39,29,30,31,38,32,33,34,35,36');
INSERT INTO `kh_auth_group` VALUES ('2', '系统管理员', '1', '1,2,8,9,15,16,23');
INSERT INTO `kh_auth_group` VALUES ('3', '学院管理员', '1', '1,2,3,4,17,18,19,20,24,5,6,27,23,25,21,22,26,39,29,30,31,38,32,33,34,36');
INSERT INTO `kh_auth_group` VALUES ('4', '老师', '1', '1,2,3,19,23,29,30,38,32,33,34,35,36');

-- ----------------------------
-- Table structure for kh_auth_group_access
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
INSERT INTO `kh_auth_group_access` VALUES ('8', '2');
INSERT INTO `kh_auth_group_access` VALUES ('9', '3');
INSERT INTO `kh_auth_group_access` VALUES ('10', '3');
INSERT INTO `kh_auth_group_access` VALUES ('11', '3');
INSERT INTO `kh_auth_group_access` VALUES ('12', '4');
INSERT INTO `kh_auth_group_access` VALUES ('13', '4');
INSERT INTO `kh_auth_group_access` VALUES ('14', '3');

-- ----------------------------
-- Table structure for kh_auth_rule
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
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COMMENT='操作权限信息表-对应到菜单和每项操作';

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
INSERT INTO `kh_auth_rule` VALUES ('21', '25', 'Home/ChapterMgr/chapterList', '章节管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('22', '25', 'Home/KnowledgeMgr/knowledgeList', '知识点管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('23', '0', 'Home/index', '后台首页', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('24', '3', 'Home/CollegeMgr/collegeList', '学院管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('25', '0', null, '课程学科管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('26', '25', 'Home/CourseMgr/courseList', '学科管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('27', '5', 'Home/TestDatabaseMgr/testDatabaseList', '题库管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('28', '8', 'Home/System/menu', '菜单管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('29', '0', '', '试卷管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('30', '29', 'Home/ClassPapersCreateMgr/ClassPapersList', '组卷管理', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('31', '29', 'Home/TestTeacherPerMgr/testTeacherList', '出题授权', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('32', '0', '', '分析统计', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('33', '32', 'Home/ScoreAnalysis/testRecordList', '考试记录', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('34', '32', 'Home/ScoreAnalysis/scoreSearch', '成绩检索', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('35', '32', 'Home/ScoreAnalysis/classList', '成绩详情', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('36', '32', 'Home/ScoreAnalysis/studentScore', '学生能力考核', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('38', '29', 'Home/ClassPapersDetialMgr/showFinalTest', '分配正式考试', '1', '1', null);
INSERT INTO `kh_auth_rule` VALUES ('39', '25', 'Home/LessionMgr/lessionList', '课程管理', '1', '1', null);

-- ----------------------------
-- Table structure for kh_chapter
-- ----------------------------
DROP TABLE IF EXISTS `kh_chapter`;
CREATE TABLE `kh_chapter` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `sortnumber` int(10) NOT NULL,
  `lession_id` int(10) NOT NULL COMMENT '课程id',
  `comment` varchar(50) DEFAULT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_chapter
-- ----------------------------
INSERT INTO `kh_chapter` VALUES ('1', '序言', '1', '1', '第一章', 'taolei', '2017-11-04 15:21:52', null, null, null, '1');
INSERT INTO `kh_chapter` VALUES ('2', '基本概念', '2', '1', '12', 'taolei', '2017-11-04 15:23:42', 'taolei', '2017-11-04 22:03:40', null, '1');
INSERT INTO `kh_chapter` VALUES ('3', '序言', '1', '2', '10', 'taolei', '2017-11-04 15:24:23', null, null, null, '1');
INSERT INTO `kh_chapter` VALUES ('4', '基本概念', '3', '2', '33', 'taolei', '2017-11-04 15:24:58', null, null, null, '0');
INSERT INTO `kh_chapter` VALUES ('5', '基本概念', '3', '2', '11', 'taolei', '2017-11-04 15:26:04', null, null, null, '1');
INSERT INTO `kh_chapter` VALUES ('6', '函数', '4', '1', '', 'taolei', '2017-11-04 22:14:18', null, null, null, '1');
INSERT INTO `kh_chapter` VALUES ('7', '指针', '3', '1', '123', 'taolei', '2017-11-04 22:14:35', 'taolei', '2017-11-04 22:14:51', null, '1');

-- ----------------------------
-- Table structure for kh_class
-- ----------------------------
DROP TABLE IF EXISTS `kh_class`;
CREATE TABLE `kh_class` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `college_id` int(10) NOT NULL,
  `grade_id` int(10) NOT NULL,
  `comment` varchar(50) DEFAULT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_class
-- ----------------------------
INSERT INTO `kh_class` VALUES ('1', '软工153', '4', '1', null, 'taolei', '2017-11-04 15:04:38', null, null, null, null, '1');
INSERT INTO `kh_class` VALUES ('2', '软工154', '4', '1', null, 'taolei', '2017-11-04 15:05:02', null, null, null, null, '1');
INSERT INTO `kh_class` VALUES ('3', '软工161', '4', '2', null, 'taolei', '2017-11-04 15:05:16', null, null, null, null, '1');
INSERT INTO `kh_class` VALUES ('4', '软工151', '4', '1', null, 'taolei', '2017-11-04 15:05:32', null, null, null, null, '1');
INSERT INTO `kh_class` VALUES ('5', '计科152', '5', '1', null, 'taolei', '2017-11-04 15:12:58', null, null, null, null, '1');

-- ----------------------------
-- Table structure for kh_class_student
-- ----------------------------
DROP TABLE IF EXISTS `kh_class_student`;
CREATE TABLE `kh_class_student` (
  `account` varchar(13) NOT NULL,
  `courseclass_id` int(10) NOT NULL,
  `comment` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_class_student
-- ----------------------------
INSERT INTO `kh_class_student` VALUES ('2015081149', '1', null);
INSERT INTO `kh_class_student` VALUES ('2015081150', '1', null);
INSERT INTO `kh_class_student` VALUES ('2015081151', '1', null);
INSERT INTO `kh_class_student` VALUES ('2015081152', '1', null);
INSERT INTO `kh_class_student` VALUES ('2015081153', '1', null);

-- ----------------------------
-- Table structure for kh_college
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_college
-- ----------------------------
INSERT INTO `kh_college` VALUES ('4', '软件工程', '郭本俊', '', 'taolei', '2017-11-04 14:22:23', null, null, null, '1', '13563568535');
INSERT INTO `kh_college` VALUES ('5', '计算机学院', '梁老师', '', 'taolei', '2017-11-04 14:47:40', null, null, null, '1', '18382315084');

-- ----------------------------
-- Table structure for kh_config
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
-- Table structure for kh_course
-- ----------------------------
DROP TABLE IF EXISTS `kh_course`;
CREATE TABLE `kh_course` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `college_id` int(10) NOT NULL COMMENT '学院id',
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_course
-- ----------------------------
INSERT INTO `kh_course` VALUES ('1', 'C语言', '4', 'taolei', '2017-11-04 15:16:25', null, null, 'C类', '1');
INSERT INTO `kh_course` VALUES ('2', 'JAVA', '4', 'taolei', '2017-11-04 15:16:41', null, null, 'java类', '1');
INSERT INTO `kh_course` VALUES ('3', 'C语言', '5', 'taolei', '2017-11-04 15:16:55', null, null, 'C类', '1');
INSERT INTO `kh_course` VALUES ('4', 'C++', '4', 'taolei', '2017-11-04 15:17:16', 'taolei', '2017-11-04 15:17:26', '', '0');
INSERT INTO `kh_course` VALUES ('5', 'C++', '4', 'taolei', '2017-11-04 15:17:35', null, null, '', '1');

-- ----------------------------
-- Table structure for kh_courseclass
-- ----------------------------
DROP TABLE IF EXISTS `kh_courseclass`;
CREATE TABLE `kh_courseclass` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `teacher_id` int(10) NOT NULL,
  `college_id` int(10) NOT NULL,
  `lession_id` int(10) NOT NULL COMMENT '课程id',
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_courseclass
-- ----------------------------
INSERT INTO `kh_courseclass` VALUES ('1', 'c语言软工154', '12', '4', '1', '1', '2017-11-04', '2017-12-04', 'taolei', '2017-11-04 16:04:14', null, null, null, null, '1');

-- ----------------------------
-- Table structure for kh_dict
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
-- Table structure for kh_grade
-- ----------------------------
DROP TABLE IF EXISTS `kh_grade`;
CREATE TABLE `kh_grade` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `comment` varchar(50) DEFAULT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_grade
-- ----------------------------
INSERT INTO `kh_grade` VALUES ('1', '2015级', '2015级的1', 'taolei', '2017-11-04 15:01:23', 'taolei', '2017-11-04 15:01:44', null, '1');
INSERT INTO `kh_grade` VALUES ('2', '2016级', '', 'taolei', '2017-11-04 15:01:31', null, null, null, '1');
INSERT INTO `kh_grade` VALUES ('3', '2017级', '', 'taolei', '2017-11-04 15:01:38', null, null, null, '1');

-- ----------------------------
-- Table structure for kh_inform
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
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_inform
-- ----------------------------
INSERT INTO `kh_inform` VALUES ('34', '0', '关于开展2017年消防安全月活动的通知', '2', '&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	各单位：\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	在全国119消防日即将来临之际，为切实提升全校师生消防安全意识和素质，强化消防安全“四个能力”建设，进一步加强学校消防安全工作，落实消防安全责任制，为师生营造一个平安和谐的校园环境。学校将开展119消防安全月活动，现将有关事宜通知如下：\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	一、活动主题\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	一年一个“119”&amp;nbsp; 天天都是安全日\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	二、活动内容\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	（一）主题教育宣传周活动\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	1.活动时间：11月9至17日\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	2.活动地点：\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	航空港校区：汇海路、校大门 龙泉校区：九龙柱广场\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	3.活动内容：\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	校园LED屏幕滚动播放消防安全宣传片。\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	（二）消防安全培训\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	1.活动时间：2017年11月10日\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	2.活动地点：航空港校区学术报告厅\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	3.培训内容：\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	火灾形成的隐患、如何逃生及安全疏散、如何使用灭火器及扑灭初期火灾。\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	（三）消防实景演练\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	1.演练地点：第二田径运动场\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	2.演练时间：2017年11月17日\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	3.演练内容：\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	义务消防队员紧急集合、安全应急高空绳索下滑、水带及水枪的使用方法演示、灭火器的使用方法演示；\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	4、组织同学实际操作正确使用灭火器。\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	（四）消防安全检查\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	1.各单位自查\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	请各单位于2017年11月2至10日严格按照《成都信息工程大学安全检查制度》组织自查，并如实填写《成都信息工程大学安全检查登记表》（见附件1），并将电子档于11月12日前报送至保卫处邮箱&lt;a href=&quot;mailto:bwc@cuit.edu.cn&quot;&gt;bwc@cuit.edu.cn&lt;/a&gt;，纸质档加盖单位公章后报送学校保卫处（航空港校区行政楼102室、龙泉校区行政楼112室）。\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	2.学校专项检查\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	2017年11月13至14日双流区消防大队、龙泉驿区消防大队带队，对学校重点、要害部位和场所进行专项检查，并如实全校通报检查情况，对须整改的安全隐患将发《整改通知书》。\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	3.检查内容\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	按照教学单位、行政部门、后勤服务公司分类检查，详见附件2。\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	&amp;nbsp;\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;\n &amp;nbsp;安全工作领导小组办公室\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;\n 2017年11月2日\n&lt;/p&gt;\n&lt;pre&gt;&lt;/pre&gt;', '2017-11-04 14:50:03', 'taolei', '', '', '1');
INSERT INTO `kh_inform` VALUES ('35', '0', '111', '1', '&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	各研究生培养学院：\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	为充分发挥我校优秀研究生的模范带头作用，促进研究生全面发展，不断提高研究生培养质量，根据《成都信息工程大学研究生奖励条例》的规定，现将2016-2017学年研究生评优工作通知如下：\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	一、评选范围和对象\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	具有成都信息工程大学学籍，在校学习满一年以上的全日制硕士研究生。\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	二、评选类别和比例\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	（一）优秀研究生，评选比例为不超过各培养单位适评研究生人数的5%，限三年级研究生申请。\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	（二）学习优秀奖，评选比例为不超过各培养单位适评研究生人数的10%，限二年级研究生申请。\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	（三）优秀研究生干部，评选比例为不超过各培养单位适评研究生人数的5%，限二、三年级研究生申请。\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	（四）科研论文奖，根据实际发表情况评定。\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	三、评选条件\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	(一)优秀研究生\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	1、具有良好的学术道德和团结合作精神；\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	2、通过全国大学生英语六级考试；学位课程考试平均成绩在80分以上且单科成绩不低于70分；学位课程平均成绩排名在本专业年级中居前30%；\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	3、已有以第一作者身份在本学科中文核心及以上级别期刊正式发表的学术论文，或有以主研身份参加的科研项目通过鉴定。\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	（二）学习优秀奖\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	1、学习成绩优异，通过全国大学生英语六级考试；\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	2、学位课程考试平均成绩在85分以上且单科成绩不低于75分，非学位课程考查均合格，且无补考；\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	3、已修学位课程平均成绩排名在本专业年级中居前30%。\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	（三）优秀研究生干部\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	1、在适评学年内担任学生干部职务，且任期满一年；\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	2、积极履行所承担的工作职责，积极协助校、学院党团组织做好学生的思想政治工作及校园稳定工作，工作踏实负责；\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	3、师生公认表率作用好，有一定的群众基础和号召力，工作有成效；\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	4、学习态度端正，成绩良好，原则上学位课程考试平均成绩在75分以上且单科成绩在70分以上，非学位课程考查成绩均合格，且无补考。\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	（四）科研论文奖\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	参评论文必须是本人在研究生学习阶段以第一作者身份（或第二作者，导师第一），以成都信息工程大学的名义公开发表的与所学专业相关的论文原件；论文录用通知等不能证明最终结果的材料不能作为参评依据。其他具体评奖要求请参见《成都信息工程大学研究生手册》（2016年版）。\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	四、评选注意事项\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	（一）凡在本学年中有下列情况之一者，不能参加评选：\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	1、受到各种纪律处分者；\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	2、课程考试成绩不及格或者重修者；\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	3、违反公民道德规范，造成恶劣影响者。\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	（二）评选原则、程序和时间\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	评选工作要坚持“公正合理、实事求是、保证质量、宁缺毋滥”的原则。具体程序和时间安排为：\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	1、即日起至2017年11月15日：各学院初评并公示；\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	2、学校研究生评优办公室严格按照研究生评优评定条件对学院推荐名单进行综合审查，在此基础上，提交学校研究生评优委员会审议。学校研究生评优委员会通过组织讨论、审议，提出学校当年评优学生建议名单，报学校研究生评优领导小组集体研究审定，确定最终名单，在全校进行不少于五个工作日的公示。\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	（三）凡参评科研论文奖，不得拿以前参评过的论文进行申报，参评者均需提供学术刊物原件，复审完毕后均予以归还。\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	（四）凡参评SCI、EI级别的论文需附检索报告，论文录用通知等不能证明最终结果的材料不能作为参评依据。\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	五、报送材料\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	1、“优秀研究生”、“学习优秀奖”、“优秀研究生干部”、“科研论文奖”各奖项申报审批表一式两份（纸质档），各奖项相关证明材料（复印件）;\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	2、2016-2017学年成都信息工程大学研究生评优汇总表（纸质档和电子档，邮箱：yjsglk@cuit.edu.cn）;\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	&amp;nbsp;\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;\n 学生工作部\n&lt;/p&gt;\n&lt;p style=&quot;text-indent:2em;&quot;&gt;\n	&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;2017年11月1日\n&lt;/p&gt;\n&lt;pre&gt;&lt;/pre&gt;', '2017-11-04 14:51:47', 'taolei', '', '', '1');

-- ----------------------------
-- Table structure for kh_knowledge
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_knowledge
-- ----------------------------
INSERT INTO `kh_knowledge` VALUES ('1', '简介序言', '1', '简介序言1', 'taolei', '2017-11-04 15:26:57', 'taolei', '2017-11-04 15:27:04', null, '1');
INSERT INTO `kh_knowledge` VALUES ('2', '变量类型', '2', '001', 'taolei', '2017-11-04 21:46:06', null, null, null, '1');
INSERT INTO `kh_knowledge` VALUES ('3', 'int，string', '2', '', 'taolei', '2017-11-04 21:58:25', null, null, null, '1');

-- ----------------------------
-- Table structure for kh_lession
-- ----------------------------
DROP TABLE IF EXISTS `kh_lession`;
CREATE TABLE `kh_lession` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `course_id` int(10) NOT NULL COMMENT '关联学科id',
  `college_id` int(10) NOT NULL COMMENT '学院id',
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `del_flag` int(1) DEFAULT '1',
  `remarks` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_lession
-- ----------------------------
INSERT INTO `kh_lession` VALUES ('1', 'C程序设计基础', '1', '4', 'taolei', '2017-11-04 15:18:44', 'taolei', '2017-11-04 15:22:28', '1', '');
INSERT INTO `kh_lession` VALUES ('2', 'java程序设计', '2', '4', 'taolei', '2017-11-04 15:19:18', null, null, '1', '');
INSERT INTO `kh_lession` VALUES ('3', 'java高级程序设计', '2', '4', 'taolei', '2017-11-04 15:19:51', null, null, '1', '');

-- ----------------------------
-- Table structure for kh_log
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
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_log
-- ----------------------------
INSERT INTO `kh_log` VALUES ('159', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-11-04 14:19:52', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('160', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-11-04 15:55:22', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('161', 'testjisunji', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-11-04 15:56:37', 'testjisunji', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('162', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-11-04 15:57:28', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('163', 'testjisunji', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-11-04 15:58:02', 'testjisunji', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('164', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-11-04 15:58:49', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('165', 'laowang', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-11-04 16:02:51', 'laowang', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('166', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-11-04 16:03:26', 'taolei', null, null, null, '1');
INSERT INTO `kh_log` VALUES ('167', 'taolei', '/cuitcheck/Home/Login/check_login', null, '0.0.0.0', '2017-11-04 20:17:57', 'taolei', null, null, null, '1');

-- ----------------------------
-- Table structure for kh_major
-- ----------------------------
DROP TABLE IF EXISTS `kh_major`;
CREATE TABLE `kh_major` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `comment` varchar(50) DEFAULT NULL,
  `dept_id` int(11) NOT NULL COMMENT '上级学院的id',
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_major
-- ----------------------------
INSERT INTO `kh_major` VALUES ('3', '软件工程', '', '4', 'taolei', '2017-11-04 14:56:08', 'taolei', '2017-11-04 22:19:12', null, '1');
INSERT INTO `kh_major` VALUES ('4', '空间信息技术', '', '4', 'taolei', '2017-11-04 15:00:38', 'taolei', '2017-11-04 15:17:53', null, '0');
INSERT INTO `kh_major` VALUES ('5', '计算机科学与技术', '', '5', 'taolei', '2017-11-04 15:11:25', null, null, null, '1');
INSERT INTO `kh_major` VALUES ('6', '空间信息技术', '', '4', 'taolei', '2017-11-04 15:18:04', null, null, null, '1');

-- ----------------------------
-- Table structure for kh_menu
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
INSERT INTO `kh_menu` VALUES ('17', '课程学科管理', '', '1', '0', '5', 'icon-shezhi', 'maqingwen', '2017-03-13 15:36:44', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('18', '学科管理', 'Home/CourseMgr/courseList', '2', '17', '0', 'icon-xuekefenlei', 'maqingwen', '2017-03-13 15:55:26', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('19', '课程管理', 'Home/LessionMgr/lessionList', '2', '17', '1', 'icon-bianji', '', '0000-00-00 00:00:00', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('20', '章节管理', 'Home/ChapterMgr/chapterList', '2', '17', '2', 'fa-sitemap', 'xujiaming', '2017-03-17 15:29:46', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('21', '字典管理', 'Home/Dictionary/DictionaryList', '2', '1', '4', '&#xe61d;', '', '0000-00-00 00:00:00', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('22', '学生管理', 'Home/StudentMgr/studentList', '2', '11', '5', 'icon-icon57', 'liangxuanhao', '2017-03-11 10:10:10', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('23', '题库管理', '', '1', '0', '6', 'icon-tikuguanli', 'maqingwen', '2017-03-22 13:03:37', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('24', '知识点管理', 'Home/KnowledgeMgr/knowledgeList', '2', '17', '3', 'fa-file-text-o', 'xujiaming', '2017-03-23 15:40:30', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('25', '题库管理', 'Home/TestDatabaseMgr/testDatabaseList', '2', '23', '0', 'icon-tiku', 'maiqngwen', '2017-03-28 14:30:46', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('26', '通知公告', 'Home/InformMgr/informList', '2', '1', '1', '&#xe609;', '', '0000-00-00 00:00:00', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('27', '题库授权', 'Home/TestDBPermissionMgr/testDBList', '2', '23', '1', 'icon-shouquan', 'maqingwen', '2017-04-13 20:01:53', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('28', '试卷管理', '', '1', '0', '7', 'icon-kaoshi', 'maqingwen', '2017-06-10 14:24:55', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('29', '组卷管理', 'Home/ClassPapersCreateMgr/ClassPapersList', '2', '28', '1', 'icon-ceshi', 'maqingwen', '2017-06-10 14:26:02', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('30', '分配正式考试', 'Home/ClassPapersDetialMgr/showFinalTest', '2', '28', '2', 'icon-kao', 'maqingwen', '2017-06-10 14:27:20', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('31', '出题授权', 'Home/TestTeacherPerMgr/testTeacherList', '2', '28', '3', 'icon-shouquan', 'maqingwen', '2017-06-10 14:29:44', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('32', '分析统计', '', '1', '0', '8', '&#xe62c;', 'liangxuanhao', '2017-07-20 14:51:23', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('33', '考试记录', 'Home/ScoreAnalysis/testRecordList', '2', '32', '1', 'fa fa-history', 'liangxuanhao', '2017-07-20 15:03:47', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('34', '成绩检索', 'Home/ScoreAnalysis/scoreSearch', '2', '32', '2', 'fa fa-digg', 'liangxuanhao', '2017-07-20 15:06:16', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('35', '学生能力考核', 'Home/ScoreAnalysis/studentScore', '2', '32', '4', 'fa fa-history', 'liangxuanhao', '2017-07-22 17:31:30', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('36', '成绩详情', 'Home/ScoreAnalysis/classList', '2', '32', '3', 'fa fa-digg', 'liangxuanhao', '2017-07-26 14:59:39', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('37', '用户组管理', 'Home/SysuserGroup/sysGroupList', '2', '9', '1', 'icon-bianji', '', '0000-00-00 00:00:00', null, null, null, '1');
INSERT INTO `kh_menu` VALUES ('38', '权限管理', 'Home/SysuserRule/sysRuleList', '1', '9', '1', '&#xe628', '', '0000-00-00 00:00:00', null, null, null, '1');

-- ----------------------------
-- Table structure for kh_paper_courserclass
-- ----------------------------
DROP TABLE IF EXISTS `kh_paper_courserclass`;
CREATE TABLE `kh_paper_courserclass` (
  `testpaper_id` int(10) NOT NULL,
  `courserclass_id` int(10) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `comment` varchar(50) DEFAULT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_paper_courserclass
-- ----------------------------
INSERT INTO `kh_paper_courserclass` VALUES ('1', '1', '2017-11-04 16:05:53', '2017-11-04 16:24:00', null, 'taolei', '2017-11-04 16:06:00', null, null, null, '1');

-- ----------------------------
-- Table structure for kh_paper_question
-- ----------------------------
DROP TABLE IF EXISTS `kh_paper_question`;
CREATE TABLE `kh_paper_question` (
  `testpaper_id` int(10) NOT NULL,
  `question_id` int(10) NOT NULL,
  `value` int(10) NOT NULL,
  `comment` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_paper_question
-- ----------------------------
INSERT INTO `kh_paper_question` VALUES ('1', '1', '10', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('1', '24', '10', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('1', '25', '15', '自动生成添加');
INSERT INTO `kh_paper_question` VALUES ('1', '20', '15', '自动生成添加');

-- ----------------------------
-- Table structure for kh_practice
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
-- Table structure for kh_question
-- ----------------------------
DROP TABLE IF EXISTS `kh_question`;
CREATE TABLE `kh_question` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `testdb_id` int(10) NOT NULL,
  `content` text NOT NULL,
  `type` int(10) NOT NULL,
  `level` int(10) NOT NULL,
  `knowledge_ids` text,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_question
-- ----------------------------
INSERT INTO `kh_question` VALUES ('1', '1', '正确', '2', '1', null, 'taolei', '2017-11-04 15:34:44', null, null, null, '1');
INSERT INTO `kh_question` VALUES ('20', '1', '设a,b,t 为整型变量,初值为a=7,b=9,执行完语句t=(a&amp;gt;b)?a:b后,t 的值是', '3', '2', null, 'taolei', '2017-11-04 15:52:46', 'taolei', '2017-11-04 21:46:28', null, '1');
INSERT INTO `kh_question` VALUES ('21', '1', 'C语言程序总是从main()函数开始执行', '2', '1', null, 'taolei', '2017-11-04 15:52:46', 'taolei', '2017-11-04 16:00:40', '测试例题(添加时,请务必将其覆盖或删除!!!)', '0');
INSERT INTO `kh_question` VALUES ('22', '1', '设a和b均为double型变量，且a=5.5、b=2.5，则表达式(int)a+b/b的值是', '3', '3', null, 'taolei', '2017-11-04 15:52:46', 'taolei', '2017-11-04 16:00:58', null, '0');
INSERT INTO `kh_question` VALUES ('23', '1', '设a,b,t 为整型变量,初值为a=7,b=9,执行完语句t=(a>b)?a:b后,t 的值是', '3', '2', null, 'taolei', '2017-11-04 15:52:54', 'taolei', '2017-11-04 16:01:01', '测试例题(添加时,请务必将其覆盖或删除!!!)', '0');
INSERT INTO `kh_question` VALUES ('24', '1', 'C语言程序总是从main()函数开始执行', '2', '1', null, 'taolei', '2017-11-04 15:52:54', 'taolei', '2017-11-04 16:01:07', '测试例题(添加时,请务必将其覆盖或删除!!!)', '1');
INSERT INTO `kh_question` VALUES ('25', '1', '设a和b均为double型变量，且a=5.5、b=2.5，则表达式(int)a+b/b的值是', '3', '3', null, 'taolei', '2017-11-04 15:52:54', 'taolei', '2017-11-04 16:01:20', '测试例题(添加时,请务必将其覆盖或删除!!!)', '1');

-- ----------------------------
-- Table structure for kh_question_know
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
INSERT INTO `kh_question_know` VALUES ('14', '1');
INSERT INTO `kh_question_know` VALUES ('24', '1');
INSERT INTO `kh_question_know` VALUES ('25', '1');
INSERT INTO `kh_question_know` VALUES ('20', '1');
INSERT INTO `kh_question_know` VALUES ('20', '2');

-- ----------------------------
-- Table structure for kh_rank
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
-- Table structure for kh_score
-- ----------------------------
DROP TABLE IF EXISTS `kh_score`;
CREATE TABLE `kh_score` (
  `account` int(10) NOT NULL,
  `testpaper_id` int(10) NOT NULL,
  `score` int(10) NOT NULL,
  `create_by` varchar(64) DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_score
-- ----------------------------
INSERT INTO `kh_score` VALUES ('2015081149', '1', '0', null, '2017-11-04 20:26:04', null, null, null, '1');
INSERT INTO `kh_score` VALUES ('2015081150', '1', '15', null, '2017-11-04 20:26:08', null, null, null, '1');
INSERT INTO `kh_score` VALUES ('2015081151', '1', '15', null, '2017-11-04 20:26:12', null, null, null, '1');
INSERT INTO `kh_score` VALUES ('2015081152', '1', '0', null, '2017-11-04 20:26:14', null, null, null, '1');

-- ----------------------------
-- Table structure for kh_student
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_student
-- ----------------------------
INSERT INTO `kh_student` VALUES ('1', '2015081149', '6aa471cb63531e429bfcd8b336585075', '刘干事', '0', '2', '1', '3', '4', null, null, '', '1', null, 'taolei', '2017-11-04 15:06:37', 'taolei', '2017-11-04 15:06:50', '', '1');
INSERT INTO `kh_student` VALUES ('2', '2015081150', 'f0e881aff253c77005898b546e7e6fee', '梁轩豪', '1', '2', '1', '3', '4', null, '', '', '1', null, 'taolei', '2017-11-04 15:13:37', null, null, '', '1');
INSERT INTO `kh_student` VALUES ('3', '2015081151', '851786edb34c608b1993a70a5a31ba99', '合轩豪', '1', '2', '1', '3', '4', null, '', '', '1', null, 'taolei', '2017-11-04 15:13:37', null, null, '', '1');
INSERT INTO `kh_student` VALUES ('4', '2015081152', '363bfe1853b38c9d5ef3371c1f2a8541', '安义文', '1', '2', '1', '3', '4', null, '', '', '1', null, 'taolei', '2017-11-04 15:13:37', null, null, '', '1');
INSERT INTO `kh_student` VALUES ('5', '2015081153', '42dffca50ac1a38d5b3e06d2cdfaa292', ' 葡京姚', '1', '2', '1', '3', '4', null, '', '', '1', null, 'taolei', '2017-11-04 15:13:37', null, null, '', '1');
INSERT INTO `kh_student` VALUES ('6', '2015081154', 'edfae7c6b95a6493191163edf284116f', '梁轩豪', '1', '2', '1', '3', '4', null, '', '', '1', null, 'taolei', '2017-11-04 15:13:37', null, null, '', '1');
INSERT INTO `kh_student` VALUES ('7', '2015081155', '555d9d0fd318b9cf85516f1824ec483d', '梁轩豪', '1', '1', '1', '3', '4', null, '', '', '1', null, 'taolei', '2017-11-04 15:13:37', null, null, '', '1');
INSERT INTO `kh_student` VALUES ('8', '2015081156', '25bfd862eb433663f6967591c68c2b00', '梁轩豪', '1', '2', '1', '3', '4', null, '', '', '1', null, 'taolei', '2017-11-04 15:13:37', null, null, '', '1');
INSERT INTO `kh_student` VALUES ('9', '2015081157', 'd5ff6beef5ff065c326c75725992ca33', '梁轩豪', '1', '2', '1', '3', '4', null, '', '', '1', null, 'taolei', '2017-11-04 15:13:37', null, null, '', '1');
INSERT INTO `kh_student` VALUES ('10', '2015081158', 'fe36c3c47d84b3db1610df2af39d0a15', '梁轩豪', '1', '2', '1', '3', '4', null, '', '', '1', null, 'taolei', '2017-11-04 15:13:37', null, null, '', '1');
INSERT INTO `kh_student` VALUES ('11', '2015081159', 'd1d0fd1b65bcfd425859a66b973214fe', '梁轩豪', '1', '2', '1', '3', '4', null, '', '', '1', null, 'taolei', '2017-11-04 15:13:37', null, null, '', '1');
INSERT INTO `kh_student` VALUES ('12', '2015081160', 'b306fd84484a25547df3617d8c23c22c', '梁轩豪', '1', '5', '1', '5', '5', null, '', '', '1', null, 'taolei', '2017-11-04 15:13:37', 'taolei', '2017-11-04 15:14:19', '', '1');
INSERT INTO `kh_student` VALUES ('13', '2015081161', '7995679504a3343a13e48eed3eff91c0', '梁轩豪', '1', '5', '1', '5', '5', null, '', '', '1', null, 'taolei', '2017-11-04 15:13:37', null, null, '', '1');
INSERT INTO `kh_student` VALUES ('14', '2015081170', '65921b66e86fcaa6675579c8f1191ddc', 'zzy', '1', '2', '1', '3', '4', null, '', '', '1', null, 'taolei', '2017-11-04 22:55:50', null, null, '', '1');

-- ----------------------------
-- Table structure for kh_sysuser
-- ----------------------------
DROP TABLE IF EXISTS `kh_sysuser`;
CREATE TABLE `kh_sysuser` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `account` varchar(16) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(20) NOT NULL,
  `sex` int(1) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `role` int(1) NOT NULL,
  `status` int(1) NOT NULL,
  `dept_id` char(10) DEFAULT NULL,
  `create_by` varchar(64) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_by` varchar(64) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `del_flag` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_sysuser
-- ----------------------------
INSERT INTO `kh_sysuser` VALUES ('1', 'taolei', 'd93a5def7511da3d0f2d171d9c344e91', '陶磊', '1', '/cuitcheck/Uploads/UserPhoto/sysuser_1.jpeg', '15025693622', '492360041@qq.com', '1', '1', '', '', '2017-02-25 15:38:04', 'taolei', '2017-11-04 14:20:06', '', '1');
INSERT INTO `kh_sysuser` VALUES ('8', 'test1', 'd93a5def7511da3d0f2d171d9c344e91', 'manange', '0', null, '18382315084', '13854564354@qq.com', '2', '1', '4', 'taolei', '2017-11-04 14:27:18', 'taolei', '2017-11-04 14:27:37', '', '1');
INSERT INTO `kh_sysuser` VALUES ('11', 'test3', 'd93a5def7511da3d0f2d171d9c344e91', 'Admin2', '1', null, '18382315084', '', '3', '1', '4', 'taolei', '2017-11-04 14:43:23', null, null, '', '1');
INSERT INTO `kh_sysuser` VALUES ('12', 'laowang', 'd93a5def7511da3d0f2d171d9c344e91', '王老师', '1', null, '', '', '4', '1', '4', 'taolei', '2017-11-04 14:48:11', null, null, '', '1');
INSERT INTO `kh_sysuser` VALUES ('13', 'laozhang', 'd93a5def7511da3d0f2d171d9c344e91', '王老师', '0', null, '', '', '4', '1', '5', 'taolei', '2017-11-04 14:48:11', null, null, '', '1');
INSERT INTO `kh_sysuser` VALUES ('14', 'testjisunji', 'd93a5def7511da3d0f2d171d9c344e91', '计算机学院管理员', '0', null, '15252525282', '1335637386@qq.com', '3', '1', '5', 'taolei', '2017-11-04 15:56:04', null, null, '', '1');

-- ----------------------------
-- Table structure for kh_testcache
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
-- Table structure for kh_testdatabase
-- ----------------------------
DROP TABLE IF EXISTS `kh_testdatabase`;
CREATE TABLE `kh_testdatabase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `lession_id` int(10) NOT NULL COMMENT '课程id',
  `type_id` int(10) NOT NULL COMMENT '考试类型id',
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_testdatabase
-- ----------------------------
INSERT INTO `kh_testdatabase` VALUES ('1', 'c语言基本题库', '1', '1', '4', '0', '', 'taolei', '2017-11-04 15:29:37', null, null, null, '1');
INSERT INTO `kh_testdatabase` VALUES ('2', 'java程序设计基本题库', '2', '1', '4', '1', '', 'taolei', '2017-11-04 15:31:18', null, null, null, '1');

-- ----------------------------
-- Table structure for kh_testdatabase_permission
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
INSERT INTO `kh_testdatabase_permission` VALUES ('1', '4', '2', 'taolei', '2017-11-04 15:29:37', null, null, null, '1');
INSERT INTO `kh_testdatabase_permission` VALUES ('2', '4', '2', 'taolei', '2017-11-04 15:31:18', null, null, null, '1');
INSERT INTO `kh_testdatabase_permission` VALUES ('2', '5', '1', 'taolei', '2017-11-04 15:57:45', null, null, null, '1');

-- ----------------------------
-- Table structure for kh_testpaper
-- ----------------------------
DROP TABLE IF EXISTS `kh_testpaper`;
CREATE TABLE `kh_testpaper` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `type_id` int(10) NOT NULL,
  `college_id` int(10) NOT NULL,
  `lession_id` int(10) NOT NULL COMMENT '课程id',
  `full_score` int(10) DEFAULT NULL,
  `pass_score` int(10) DEFAULT NULL,
  `is_use` int(1) NOT NULL DEFAULT '1',
  `create_date` datetime NOT NULL,
  `create_by` varchar(60) NOT NULL,
  `update_date` datetime DEFAULT NULL,
  `update_by` varchar(60) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `del_flag` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_testpaper
-- ----------------------------
INSERT INTO `kh_testpaper` VALUES ('1', 'c语言测试试卷', '1', '4', '1', '50', '30', '1', '2017-11-04 15:59:15', 'taolei', '2017-11-04 16:01:48', 'taolei', '自动生成更新基础分值', '1');

-- ----------------------------
-- Table structure for kh_testtype
-- ----------------------------
DROP TABLE IF EXISTS `kh_testtype`;
CREATE TABLE `kh_testtype` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
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
-- Records of kh_testtype
-- ----------------------------

-- ----------------------------
-- Table structure for kh_test_final
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
INSERT INTO `kh_test_final` VALUES ('1', '1', '1', '0', '0');
INSERT INTO `kh_test_final` VALUES ('1', '1', '20', '16', '1');
INSERT INTO `kh_test_final` VALUES ('1', '1', '24', '0', '0');
INSERT INTO `kh_test_final` VALUES ('1', '1', '25', '0', '0');
INSERT INTO `kh_test_final` VALUES ('1', '2', '1', '0', '0');
INSERT INTO `kh_test_final` VALUES ('1', '2', '20', '16', '1');
INSERT INTO `kh_test_final` VALUES ('1', '2', '24', '0', '0');
INSERT INTO `kh_test_final` VALUES ('1', '2', '25', '0', '0');
INSERT INTO `kh_test_final` VALUES ('1', '3', '1', '0', '0');
INSERT INTO `kh_test_final` VALUES ('1', '3', '20', '16', '1');
INSERT INTO `kh_test_final` VALUES ('1', '3', '24', '0', '0');
INSERT INTO `kh_test_final` VALUES ('1', '3', '25', '0', '0');
INSERT INTO `kh_test_final` VALUES ('1', '4', '1', '0', '0');
INSERT INTO `kh_test_final` VALUES ('1', '4', '20', '0', '0');
INSERT INTO `kh_test_final` VALUES ('1', '4', '24', '0', '0');

-- ----------------------------
-- Table structure for kh_test_teacher_permission
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kh_test_teacher_permission
-- ----------------------------
INSERT INTO `kh_test_teacher_permission` VALUES ('1', '12', '2017-11-04 16:02:28', '2017-11-05 16:02:31', 'taolei', '2017-11-04 16:02:35', null, null, null, '1');
SET FOREIGN_KEY_CHECKS=1;
