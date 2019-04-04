/*
Navicat MySQL Data Transfer

Source Server         : 47.52.141.36
Source Server Version : 50557
Source Host           : 47.52.141.36:3306
Source Database       : test_kaoqing

Target Server Type    : MYSQL
Target Server Version : 50557
File Encoding         : 65001

Date: 2019-03-30 10:28:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pg_auth_role
-- ----------------------------
DROP TABLE IF EXISTS `pg_auth_role`;
CREATE TABLE `pg_auth_role` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `remark` varchar(64) DEFAULT NULL COMMENT '备注说明',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` varchar(2048) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='角色数据表';

-- ----------------------------
-- Records of pg_auth_role
-- ----------------------------
INSERT INTO `pg_auth_role` VALUES ('1', '系统管理员', '对整个系统的用户、数据及权限进行分析', '1', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100');
INSERT INTO `pg_auth_role` VALUES ('2', '游客', '开发给多数据用户查看，可做为演示或者测试使用，当保留角色使用', '1', '');
INSERT INTO `pg_auth_role` VALUES ('3', '管理员', '拥有大部分数据管理的权限，对一些企业信息进行相关的配置，大多数是新开发的管理给此类型的用户使用', '1', '177,178,187,179,180,181,182,183,184,185,186,188,189,190');
INSERT INTO `pg_auth_role` VALUES ('4', '普通用户', '只有分配给自己使用的一小部分权限', '1', '');
INSERT INTO `pg_auth_role` VALUES ('5', '老师', '', '1', '177,178,187,179,180,181,182,183,184,185,186,188,189,190');

-- ----------------------------
-- Table structure for pg_auth_role_user
-- ----------------------------
DROP TABLE IF EXISTS `pg_auth_role_user`;
CREATE TABLE `pg_auth_role_user` (
  `uid` mediumint(8) unsigned NOT NULL,
  `role_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`role_id`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `group_id` (`role_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色用户对应表';

-- ----------------------------
-- Records of pg_auth_role_user
-- ----------------------------
INSERT INTO `pg_auth_role_user` VALUES ('64', '5');

-- ----------------------------
-- Table structure for pg_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `pg_auth_rule`;
CREATE TABLE `pg_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL COMMENT '上级',
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `menu_type` varchar(20) NOT NULL COMMENT '菜单显示方式',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  `level` int(11) NOT NULL COMMENT '层级',
  `list_sort` varchar(15) NOT NULL COMMENT '排序每级3位，如101',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=191 DEFAULT CHARSET=utf8 COMMENT='权限规则';

-- ----------------------------
-- Records of pg_auth_rule
-- ----------------------------
INSERT INTO `pg_auth_rule` VALUES ('1', '0', 'Pangu', '系统管理', '1', 'menu_left', '1', '', '1', '101');
INSERT INTO `pg_auth_rule` VALUES ('2', '1', 'Pangu/Role/index', '角色管理', '1', 'menu_left', '1', '', '2', '101101');
INSERT INTO `pg_auth_rule` VALUES ('3', '2', 'Pangu/Role/add', '添加角色', '1', 'button', '1', '', '3', '101101101');
INSERT INTO `pg_auth_rule` VALUES ('4', '2', 'Pangu/Role/edit', '修改角色', '1', 'button', '1', '', '3', '101101102');
INSERT INTO `pg_auth_rule` VALUES ('5', '2', 'Pangu/Role/delete', '删除角色', '1', 'button', '1', '', '3', '101101103');
INSERT INTO `pg_auth_rule` VALUES ('6', '1', 'Pangu/User/index', '用户管理', '1', 'menu_left', '1', '', '2', '101102');
INSERT INTO `pg_auth_rule` VALUES ('7', '6', 'Pangu/User/add', '添加用户', '1', 'button', '1', '', '3', '101102102');
INSERT INTO `pg_auth_rule` VALUES ('8', '6', 'Pangu/User/edit', '修改用户', '1', 'button', '1', '', '3', '101102103');
INSERT INTO `pg_auth_rule` VALUES ('9', '6', 'Pangu/User/delete', '删除用户', '1', 'button', '1', '', '3', '101102103');
INSERT INTO `pg_auth_rule` VALUES ('10', '1', 'Pangu/Group/index', '分组管理', '1', 'menu_left', '-1', '', '2', '101103');
INSERT INTO `pg_auth_rule` VALUES ('11', '10', 'Pangu/Group/add', '添加分组', '1', 'button', '1', '', '3', '101103101');
INSERT INTO `pg_auth_rule` VALUES ('12', '10', 'Pangu/Group/edit', '修改分组', '1', 'button', '1', '', '3', '101103103');
INSERT INTO `pg_auth_rule` VALUES ('13', '10', 'Pangu/Group/delete', '删除分组', '1', 'button', '1', '', '3', '101103103');
INSERT INTO `pg_auth_rule` VALUES ('14', '1', 'Pangu/Rule/index', '规则管理', '1', 'menu_left', '1', '', '2', '101104');
INSERT INTO `pg_auth_rule` VALUES ('15', '14', 'Pangu/Rule/add', '添加规则', '1', 'button', '1', '', '3', '101104101');
INSERT INTO `pg_auth_rule` VALUES ('16', '14', 'Pangu/Rule/edit', '修改规则', '1', 'button', '1', '', '3', '101104102');
INSERT INTO `pg_auth_rule` VALUES ('17', '14', 'Pangu/Rule/delete', '删除规则', '1', 'button', '1', '', '3', '101104103');
INSERT INTO `pg_auth_rule` VALUES ('18', '1', 'Pangu/Model/index', '模型管理', '1', 'menu_left', '1', '', '2', '101105');
INSERT INTO `pg_auth_rule` VALUES ('19', '18', 'Pangu/Model/add', '添加模型', '1', 'button', '1', '', '3', '101105101');
INSERT INTO `pg_auth_rule` VALUES ('20', '18', 'Pangu/Model/edit', '修改模型', '1', 'button', '1', '', '3', '101105102');
INSERT INTO `pg_auth_rule` VALUES ('21', '18', 'Pangu/Model/delete', '删除模型', '1', 'button', '1', '', '3', '101105104');
INSERT INTO `pg_auth_rule` VALUES ('22', '1', 'pangu/app/lists', '预留菜单不可删除', '1', 'button', '-1', '', '2', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('23', '1', 'pangu/app/create', '预留菜单不可删除', '1', 'button', '-1', '', '3', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('24', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('25', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('26', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('27', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('28', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('29', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('30', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('31', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('32', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('33', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('34', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('35', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('36', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('37', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('38', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('39', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('40', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('41', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('42', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('43', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('44', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('45', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('46', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('47', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('48', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('49', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('50', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('51', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('52', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('53', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('54', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('55', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('56', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('57', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('58', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('59', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('60', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('61', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('62', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('63', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('64', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('65', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('66', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('67', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('68', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('69', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('70', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('71', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('72', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('73', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('74', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('75', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('76', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('77', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('78', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('79', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('80', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('81', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('82', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('83', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('84', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('85', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('86', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('87', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('88', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('89', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('90', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('91', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('92', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('93', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('94', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('95', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('96', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('97', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('98', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('99', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('100', '1', 'Pangu', '预留菜单不可删除', '1', 'button', '-1', '', '0', '99999999');
INSERT INTO `pg_auth_rule` VALUES ('177', '0', 'kaoqing/AdminKaoqing/index', '考勤管理', '1', 'menu_left', '1', '', '1', '110');
INSERT INTO `pg_auth_rule` VALUES ('178', '177', 'kaoqing/AdminKaoqing/lists', '考勤列表', '1', 'menu_left', '1', '', '2', '110101');
INSERT INTO `pg_auth_rule` VALUES ('179', '177', 'kaoqing/AdminStudent/lists', '学生管理', '1', 'menu_left', '1', '', '2', '110102');
INSERT INTO `pg_auth_rule` VALUES ('180', '179', 'kaoqing/AdminStudent/add', '添加学生信息', '1', 'menu_left', '1', '', '3', '110102101');
INSERT INTO `pg_auth_rule` VALUES ('181', '179', 'kaoqing/AdminStudent/edit', '修改学生信息', '1', 'menu_left', '1', '', '3', '110102102');
INSERT INTO `pg_auth_rule` VALUES ('182', '179', 'kaoqing/AdminStudent/delete', '删除学生信息', '1', 'menu_left', '1', '', '3', '110102103');
INSERT INTO `pg_auth_rule` VALUES ('183', '177', 'kaoqing/AdminGrade/lists', '班级管理', '1', 'menu_left', '1', '', '2', '110103');
INSERT INTO `pg_auth_rule` VALUES ('184', '183', 'kaoqing/AdminGrade/add', '添加班级信息', '1', '', '1', '', '3', '110103101');
INSERT INTO `pg_auth_rule` VALUES ('185', '183', 'kaoqing/AdminGrade/edit', '修改班级信息', '1', '', '1', '', '3', '110103102');
INSERT INTO `pg_auth_rule` VALUES ('186', '183', 'kaoqing/AdminGrade/delete', '删除班级信息', '1', '', '1', '', '3', '110103103');
INSERT INTO `pg_auth_rule` VALUES ('187', '178', 'kaoqing/AdminKaoqing/add', '添加考勤信息', '1', '', '1', '', '3', '110101101');
INSERT INTO `pg_auth_rule` VALUES ('188', '177', 'kaoqing/AdminCompany/lists', '企业管理', '1', 'menu_left', '1', '', '2', '110104');
INSERT INTO `pg_auth_rule` VALUES ('189', '188', 'kaoqing/AdminCompany/add', '添加企业', '1', '', '1', '', '3', '110104101');
INSERT INTO `pg_auth_rule` VALUES ('190', '188', 'kaoqing/AdminCompany/edit', '修改企业', '1', '', '1', '', '3', '110104102');

-- ----------------------------
-- Table structure for pg_grade
-- ----------------------------
DROP TABLE IF EXISTS `pg_grade`;
CREATE TABLE `pg_grade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL COMMENT '班级名称',
  `class_num` varchar(32) NOT NULL COMMENT '班级编号',
  `year` int(4) NOT NULL COMMENT '所属年度',
  `remark` varchar(255) NOT NULL COMMENT '备注',
  `create_user_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='班级表';

-- ----------------------------
-- Records of pg_grade
-- ----------------------------
INSERT INTO `pg_grade` VALUES ('1', '019213班', '029213', '2002', '', '2', '1553076414', '1');
INSERT INTO `pg_grade` VALUES ('2', '移动应用班', '', '2015', '', '2', '1553076464', '1');
INSERT INTO `pg_grade` VALUES ('3', '电子商务班', '', '2015', '', '2', '1553076487', '1');

-- ----------------------------
-- Table structure for pg_internship_company
-- ----------------------------
DROP TABLE IF EXISTS `pg_internship_company`;
CREATE TABLE `pg_internship_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) NOT NULL COMMENT '企业名称',
  `address` varchar(255) NOT NULL COMMENT '企业地址',
  `linkman` varchar(32) NOT NULL COMMENT '联系人',
  `mobile` varchar(32) NOT NULL COMMENT '手机号',
  `remark` varchar(256) NOT NULL,
  `create_user_id` int(11) NOT NULL,
  `create_user_name` varchar(32) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` tinyint(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pg_internship_company
-- ----------------------------
INSERT INTO `pg_internship_company` VALUES ('1', '长安企业1', '很远的地方', '', '', '', '0', '', '0', '1');
INSERT INTO `pg_internship_company` VALUES ('2', '比亚迪', '很近的地方', '', '', '', '0', '', '0', '1');

-- ----------------------------
-- Table structure for pg_kaoqing
-- ----------------------------
DROP TABLE IF EXISTS `pg_kaoqing`;
CREATE TABLE `pg_kaoqing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `student_name` varchar(16) NOT NULL COMMENT '学生名称',
  `grade_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL COMMENT '实习企业',
  `lng` varchar(16) NOT NULL,
  `lat` varchar(16) NOT NULL,
  `address` varchar(64) NOT NULL COMMENT '省市县',
  `photo_date` int(10) DEFAULT NULL COMMENT '照相时间',
  `kaoqing_date` char(8) NOT NULL COMMENT '考勤日期',
  `photo` varchar(128) NOT NULL COMMENT '照片',
  `create_time` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pg_kaoqing
-- ----------------------------
INSERT INTO `pg_kaoqing` VALUES ('1', '1', '张三', '1', '1', '', '', '广东省清远市清新区清和大产业园', '15345654', '20190301', 'http://www.wscrm.net/uploads/20190311/7b176ed9b24964d93692f87f2bd85ce2.png', '1553165490', '1');
INSERT INTO `pg_kaoqing` VALUES ('2', '1', '张三', '1', '1', '', '', '广东省清远市清新区清和大产业园', '153456541', '20190302', 'http://www.wscrm.net/uploads/20190311/7b176ed9b24964d93692f87f2bd85ce2.png', '1553165490', '1');
INSERT INTO `pg_kaoqing` VALUES ('3', '1', '张三', '1', '1', '', '', '广东省清远市清新区清和大产业园', '153456541', '20190304', 'http://www.wscrm.net/uploads/20190311/7b176ed9b24964d93692f87f2bd85ce2.png', '1553165490', '1');
INSERT INTO `pg_kaoqing` VALUES ('4', '1', '张三', '1', '1', '', '', '广东省清远市清新区清和大产业园', '153456541', '20190305', 'http://www.wscrm.net/uploads/20190311/7b176ed9b24964d93692f87f2bd85ce2.png', '1553165490', '1');
INSERT INTO `pg_kaoqing` VALUES ('5', '1', '张三', '1', '1', '', '', '广东省清远市清新区清和大产业园', '153456541', '20190306', 'http://www.wscrm.net/uploads/20190311/7b176ed9b24964d93692f87f2bd85ce2.png', '1553165490', '1');
INSERT INTO `pg_kaoqing` VALUES ('6', '1', '张三', '1', '1', '0', '0', '', '1553701907', '20190327', '/uploads/20190327/93a551bf26fb785dcb0971cd31618a1b.jpg', '1553701907', '1');
INSERT INTO `pg_kaoqing` VALUES ('7', '1', '张三', '1', '1', '0', '0', '', '1553771309', '20190328', '/uploads/20190328/b67647f8322b80060100f898f93284b7.jpg', '1553771309', '1');

-- ----------------------------
-- Table structure for pg_model
-- ----------------------------
DROP TABLE IF EXISTS `pg_model`;
CREATE TABLE `pg_model` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL COMMENT '模型名称',
  `table_name` varchar(32) NOT NULL COMMENT '数据表名',
  `file_url` varchar(128) NOT NULL COMMENT '导入的文件名',
  `add_time` int(11) NOT NULL,
  `add_user_id` int(11) NOT NULL,
  `add_user_name` varchar(64) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='数据模型';

-- ----------------------------
-- Records of pg_model
-- ----------------------------

-- ----------------------------
-- Table structure for pg_model_field
-- ----------------------------
DROP TABLE IF EXISTS `pg_model_field`;
CREATE TABLE `pg_model_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model_id` int(11) NOT NULL,
  `model_name` varchar(64) NOT NULL,
  `title` varchar(64) NOT NULL,
  `field_name` varchar(64) NOT NULL,
  `data_type` varchar(12) NOT NULL COMMENT '字段类型，与MYDSQL对应',
  `form_type` varchar(12) NOT NULL COMMENT '表单类型',
  `form_data` varchar(256) NOT NULL COMMENT '表单内容',
  `is_search` int(11) NOT NULL DEFAULT '1',
  `is_list` int(11) NOT NULL DEFAULT '1',
  `is_add` int(11) NOT NULL DEFAULT '1',
  `is_edit` int(11) NOT NULL DEFAULT '1',
  `is_content` int(11) NOT NULL DEFAULT '1',
  `list_sort` int(11) NOT NULL,
  `add_time` int(11) NOT NULL,
  `add_user_id` int(11) NOT NULL,
  `add_user_name` varchar(64) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='数据模型字段';

-- ----------------------------
-- Records of pg_model_field
-- ----------------------------

-- ----------------------------
-- Table structure for pg_session
-- ----------------------------
DROP TABLE IF EXISTS `pg_session`;
CREATE TABLE `pg_session` (
  `session_id` varchar(255) NOT NULL,
  `session_expire` int(11) NOT NULL,
  `session_data` blob,
  UNIQUE KEY `session_id` (`session_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pg_session
-- ----------------------------

-- ----------------------------
-- Table structure for pg_student
-- ----------------------------
DROP TABLE IF EXISTS `pg_student`;
CREATE TABLE `pg_student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grade_id` varchar(32) NOT NULL COMMENT '班级名称',
  `company_id` int(11) NOT NULL COMMENT '对应实习企业ID',
  `student_name` varchar(16) NOT NULL COMMENT '学生名称',
  `mobile` varchar(11) NOT NULL COMMENT '手机号',
  `password` varbinary(32) NOT NULL COMMENT '密码',
  `code` varchar(12) NOT NULL COMMENT '学生编号',
  `year` tinyint(4) NOT NULL COMMENT '哪一届',
  `start_time` date NOT NULL COMMENT '实习开始时间',
  `end_time` date NOT NULL COMMENT '实习结束时间',
  `remark` varchar(255) NOT NULL COMMENT '备注',
  `create_user_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='学生表';

-- ----------------------------
-- Records of pg_student
-- ----------------------------
INSERT INTO `pg_student` VALUES ('1', '1', '1', '张三', '13680033351', 0x6531306164633339343962613539616262653536653035376632306638383365, '02921304', '127', '0000-00-00', '0000-00-00', '', '2', '1553076544', '1');
INSERT INTO `pg_student` VALUES ('2', '2', '1', '李四', '13680033352', 0x6531306164633339343962613539616262653536653035376632306638383365, '02921305', '127', '0000-00-00', '0000-00-00', '', '2', '1553078713', '1');
INSERT INTO `pg_student` VALUES ('3', '1', '1', '小黄', '13680033353', 0x6531306164633339343962613539616262653536653035376632306638383365, '02921306', '127', '0000-00-00', '0000-00-00', '', '1', '1553349958', '-1');
INSERT INTO `pg_student` VALUES ('4', '2', '2', '王五', '13680033354', 0x6531306164633339343962613539616262653536653035376632306638383365, '02921307', '0', '0000-00-00', '0000-00-00', '', '1', '1553350109', '1');
INSERT INTO `pg_student` VALUES ('5', '3', '2', '小明', '1333333333', '', '1111111', '0', '2019-03-29', '2019-03-31', '', '1', '1553702195', '1');
INSERT INTO `pg_student` VALUES ('6', '2', '2', '小黄', '1333333333', '', '012222', '127', '0000-00-00', '0000-00-00', '备注', '1', '1553703394', '1');
INSERT INTO `pg_student` VALUES ('7', '2', '2', '小黄', '1333333333', '', '02921304', '127', '2019-03-06', '2019-03-12', '', '1', '1553703700', '1');

-- ----------------------------
-- Table structure for pg_sys_log
-- ----------------------------
DROP TABLE IF EXISTS `pg_sys_log`;
CREATE TABLE `pg_sys_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app` varchar(64) DEFAULT NULL COMMENT '应用',
  `controller` varchar(64) DEFAULT NULL COMMENT '控制器',
  `action` varchar(64) DEFAULT NULL COMMENT '操作',
  `content` varchar(1024) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统日志表，任何的日志都会写到这里';

-- ----------------------------
-- Records of pg_sys_log
-- ----------------------------

-- ----------------------------
-- Table structure for pg_user
-- ----------------------------
DROP TABLE IF EXISTS `pg_user`;
CREATE TABLE `pg_user` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `roles` varchar(128) NOT NULL COMMENT '用户角色，用，分隔',
  `account` varchar(64) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `bind_account` varchar(50) NOT NULL,
  `last_login_time` int(11) unsigned DEFAULT '0',
  `last_login_ip` varchar(40) DEFAULT NULL,
  `login_count` mediumint(8) unsigned DEFAULT '0',
  `verify` varchar(32) DEFAULT NULL,
  `mobile` varchar(11) NOT NULL COMMENT '手机号',
  `email` varchar(50) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `sign_img` varchar(128) NOT NULL COMMENT '用户签名图片信息',
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pg_user
-- ----------------------------
INSERT INTO `pg_user` VALUES ('1', '1', 'pangu', '超级管理员', '21232f297a57a5a743894a0e4a801fc3', '', '1423036079', '120.84.11.117', '0', '', '', '', '盘古开天辟地的创始人', '', '0', '0', '1');

-- ----------------------------
-- Table structure for pg_weixin_config
-- ----------------------------
DROP TABLE IF EXISTS `pg_weixin_config`;
CREATE TABLE `pg_weixin_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` int(10) DEFAULT NULL COMMENT '用户ID',
  `public_name` varchar(50) DEFAULT NULL COMMENT '公众号名称',
  `public_id` varchar(100) DEFAULT NULL COMMENT '公众号原始id',
  `headface_url` varchar(255) DEFAULT NULL COMMENT '公众号头像',
  `area` varchar(50) DEFAULT NULL COMMENT '地区',
  `addon_config` text COMMENT '插件配置',
  `addon_status` text COMMENT '插件状态',
  `token` varchar(100) DEFAULT NULL COMMENT 'Token',
  `type` char(10) DEFAULT NULL COMMENT '类型',
  `appid` varchar(255) DEFAULT NULL COMMENT 'AppID',
  `secret` varchar(255) DEFAULT NULL COMMENT 'AppSecret',
  `encodingaeskey` varchar(255) DEFAULT NULL COMMENT 'EncodingAESKey',
  `tips_url` varchar(255) DEFAULT NULL COMMENT '提示关注公众号的文章地址',
  `is_bind` tinyint(2) DEFAULT '0' COMMENT '是否为微信开放平台绑定账号',
  `check_file` int(10) DEFAULT NULL COMMENT '域名授权验证文件',
  `app_type` tinyint(2) DEFAULT '0' COMMENT '类型',
  `mch_id` varchar(32) DEFAULT NULL COMMENT '商户号',
  `partner_key` varchar(100) DEFAULT NULL COMMENT 'API密钥',
  `cert_pem` int(10) unsigned DEFAULT NULL COMMENT '微信支付证书cert',
  `key_pem` int(10) unsigned DEFAULT NULL COMMENT '微信支付证书key',
  `authorizer_refresh_token` varchar(255) DEFAULT NULL COMMENT '一键绑定刷新令牌',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pg_weixin_config
-- ----------------------------
INSERT INTO `pg_weixin_config` VALUES ('1', '2', '云裂变服务号', 'gh_bcd6a31ff8fd', null, null, null, null, 's7BmNqJaG3ehatKPrFox', '2', 'wx36f32ff01aeaa79a', '5d90da724c03f4773310c1e0baa700ab', 'hjgrfnXolLyamYsTOPvopldBhoALXWBfgIwlWuAifZi', null, '0', null, '0', '1488837052', '01234567899876543210abcdefghijxy', null, null, null);

-- ----------------------------
-- Table structure for pg_weixin_user
-- ----------------------------
DROP TABLE IF EXISTS `pg_weixin_user`;
CREATE TABLE `pg_weixin_user` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `public_id` char(32) NOT NULL COMMENT '公众号原始ID',
  `user_id` int(11) NOT NULL COMMENT '对应用户表的ID，具体哪个作为用户表，后期定义',
  `openid` varchar(32) NOT NULL,
  `nickname` varchar(32) NOT NULL DEFAULT '',
  `sex` tinyint(1) NOT NULL,
  `province` varchar(24) NOT NULL,
  `city` varchar(24) NOT NULL,
  `country` varchar(24) NOT NULL,
  `headimgurl` varchar(256) NOT NULL,
  `privilege` varchar(64) NOT NULL,
  `unionid` varchar(64) NOT NULL,
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间内',
  `update_time` int(11) unsigned NOT NULL COMMENT '修改时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_user_id` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '操作人员id',
  `create_user_name` varchar(32) NOT NULL DEFAULT '0' COMMENT '操作人员',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='微信用户数据表';


