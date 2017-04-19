/*
Navicat MySQL Data Transfer

Source Server         : localhost-wamp
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : syc

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2016-10-11 14:41:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `qj_actions`
-- ----------------------------
DROP TABLE IF EXISTS `qj_actions`;
CREATE TABLE `qj_actions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `func` varchar(255) NOT NULL,
  `orderid` int(10) NOT NULL,
  `moduleid` int(10) NOT NULL,
  `is_del` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `moduleid` (`moduleid`)
) ENGINE=MyISAM AUTO_INCREMENT=237 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_actions
-- ----------------------------
INSERT INTO `qj_actions` VALUES ('1', 'ADMIN_LIST', 'index', '1', '4', '0');
INSERT INTO `qj_actions` VALUES ('84', 'ADMIN_GROUP_TRASH', 'index', '0', '48', '0');
INSERT INTO `qj_actions` VALUES ('3', 'GROUP_LIST', 'index', '1', '6', '0');
INSERT INTO `qj_actions` VALUES ('4', 'GROUP_EDIT', 'edit', '3', '6', '0');
INSERT INTO `qj_actions` VALUES ('5', 'GROUP_DEL', 'delete', '4', '6', '0');
INSERT INTO `qj_actions` VALUES ('6', 'GROUP_ADD', 'add', '2', '6', '0');
INSERT INTO `qj_actions` VALUES ('7', 'ADMIN_ADD', 'add', '2', '4', '0');
INSERT INTO `qj_actions` VALUES ('8', 'TRASH_LIST', 'index', '1', '7', '0');
INSERT INTO `qj_actions` VALUES ('9', 'DEL_COMPLATE', 'del_complate', '0', '7', '0');
INSERT INTO `qj_actions` VALUES ('10', 'RESTORE', 'del_restore', '0', '7', '0');
INSERT INTO `qj_actions` VALUES ('11', 'MENU_LIST', 'index', '1', '10', '0');
INSERT INTO `qj_actions` VALUES ('12', 'MENU_ADD', 'add', '2', '10', '0');
INSERT INTO `qj_actions` VALUES ('13', 'MENU_EDIT', 'edit', '3', '10', '0');
INSERT INTO `qj_actions` VALUES ('14', 'MENU_DEL', 'delete', '4', '10', '0');
INSERT INTO `qj_actions` VALUES ('15', 'MODULE_LIST', 'index', '1', '11', '0');
INSERT INTO `qj_actions` VALUES ('16', 'MODULE_ADD', 'add', '2', '11', '0');
INSERT INTO `qj_actions` VALUES ('17', 'MODULE_EDIT', 'edit', '3', '11', '0');
INSERT INTO `qj_actions` VALUES ('18', 'MODULE_DELETE', 'delete', '4', '11', '0');
INSERT INTO `qj_actions` VALUES ('19', 'ACTION_LIST', 'index', '1', '13', '0');
INSERT INTO `qj_actions` VALUES ('20', 'ACTION_ADD', 'add', '2', '13', '0');
INSERT INTO `qj_actions` VALUES ('21', 'ACTION_EDIT', 'edit', '3', '13', '0');
INSERT INTO `qj_actions` VALUES ('22', 'ACTION_DELETE', 'delete', '4', '13', '0');
INSERT INTO `qj_actions` VALUES ('90', 'SAVE', 'save_conf', '0', '51', '0');
INSERT INTO `qj_actions` VALUES ('89', 'EMAIL_CONF', 'index', '0', '51', '0');
INSERT INTO `qj_actions` VALUES ('88', 'CONNECT_TEST', 'system_upload_example', '0', '50', '0');
INSERT INTO `qj_actions` VALUES ('87', 'SAVE', 'system_upload', '0', '50', '0');
INSERT INTO `qj_actions` VALUES ('97', 'SYSTEM_CONFIG', 'index', '0', '50', '0');
INSERT INTO `qj_actions` VALUES ('82', 'ADMIN_EDIT', 'edit', '2', '4', '0');
INSERT INTO `qj_actions` VALUES ('83', 'ADMIN_DEL', 'delete', '2', '4', '0');
INSERT INTO `qj_actions` VALUES ('86', 'DEL_COMPLATE', 'del_complate', '2', '48', '0');
INSERT INTO `qj_actions` VALUES ('85', 'RESTORE', 'del_restore', '1', '48', '0');
INSERT INTO `qj_actions` VALUES ('91', 'EMAIL_TEST', 'public_test_email', '0', '51', '0');
INSERT INTO `qj_actions` VALUES ('92', 'SMS_LIST', 'index', '0', '52', '0');
INSERT INTO `qj_actions` VALUES ('93', 'SMS_INSTALL', 'install', '0', '52', '0');
INSERT INTO `qj_actions` VALUES ('94', 'SMS_EDIT', 'edit', '0', '52', '0');
INSERT INTO `qj_actions` VALUES ('95', 'SMS_UNINSTALL', 'uninstall', '0', '52', '0');
INSERT INTO `qj_actions` VALUES ('96', 'SMS_EFFECT', 'set_effect', '0', '52', '0');
INSERT INTO `qj_actions` VALUES ('181', 'SET_SHOW', 'change_show', '5', '10', '0');
INSERT INTO `qj_actions` VALUES ('182', 'SET_SHOW', 'change_show', '5', '11', '0');
INSERT INTO `qj_actions` VALUES ('183', 'SITE_CONF1', 'index', '0', '84', '0');
INSERT INTO `qj_actions` VALUES ('184', 'ADD_SITE', 'add', '0', '84', '0');
INSERT INTO `qj_actions` VALUES ('185', 'DEL_SITE', 'delete', '0', '84', '0');
INSERT INTO `qj_actions` VALUES ('186', 'NAVS_LIST', 'index', '0', '86', '0');
INSERT INTO `qj_actions` VALUES ('187', 'NAVS_ADD', 'add', '0', '86', '0');
INSERT INTO `qj_actions` VALUES ('188', 'NAVS_EDIT', 'edit', '0', '86', '0');
INSERT INTO `qj_actions` VALUES ('189', 'NAVS_DEL', 'delete', '0', '86', '0');
INSERT INTO `qj_actions` VALUES ('190', 'SET_SHOW', 'change_show', '0', '86', '0');
INSERT INTO `qj_actions` VALUES ('191', 'CATEGORY_LIST', 'index', '0', '87', '0');
INSERT INTO `qj_actions` VALUES ('192', 'CATEGORY_ADD', 'add', '0', '87', '0');
INSERT INTO `qj_actions` VALUES ('193', 'CATEGORY_EDIT', 'edit', '0', '87', '0');
INSERT INTO `qj_actions` VALUES ('194', 'CATEGORY_ORDER', 'listorder', '0', '87', '0');
INSERT INTO `qj_actions` VALUES ('195', 'CATEGORY_DEL', 'delete', '0', '87', '0');
INSERT INTO `qj_actions` VALUES ('196', 'CATEGORY_TRASH', 'trashindex', '0', '87', '0');
INSERT INTO `qj_actions` VALUES ('197', 'CATEGORY_RESTORE', 'trashrestore', '0', '87', '0');
INSERT INTO `qj_actions` VALUES ('198', 'CATEGORY_DEL_COM', 'trashdelete', '0', '87', '0');
INSERT INTO `qj_actions` VALUES ('199', 'NEWS_LIST', 'index', '0', '89', '0');
INSERT INTO `qj_actions` VALUES ('200', 'NEWS_ADD', 'add', '0', '89', '0');
INSERT INTO `qj_actions` VALUES ('201', 'NEWS_EDIT', 'edit', '0', '89', '0');
INSERT INTO `qj_actions` VALUES ('202', 'NEWS_DEL', 'delete', '0', '89', '0');
INSERT INTO `qj_actions` VALUES ('203', 'NEWS_TRASH', 'trashstore_index', '0', '89', '0');
INSERT INTO `qj_actions` VALUES ('204', 'NEWS_RESTORE', 'trashstore_del_restore', '0', '89', '0');
INSERT INTO `qj_actions` VALUES ('205', 'NEWS_DEL_COM', 'trashstore_del_complate', '0', '89', '0');
INSERT INTO `qj_actions` VALUES ('206', 'PRO_CATEGORY_LIST', 'index', '0', '91', '0');
INSERT INTO `qj_actions` VALUES ('207', 'PRO_CATEGORY_ADD', 'add', '0', '91', '0');
INSERT INTO `qj_actions` VALUES ('208', 'PRO_CATEGORY_EDIT', 'edit', '0', '91', '0');
INSERT INTO `qj_actions` VALUES ('209', 'PRO_CATEGORY_ORDER', 'listorder', '0', '91', '0');
INSERT INTO `qj_actions` VALUES ('210', 'PRO_CATEGORY_DEL', 'delete', '0', '91', '0');
INSERT INTO `qj_actions` VALUES ('211', 'PRO_CATEGORY_TRASH', 'trashindex', '0', '91', '0');
INSERT INTO `qj_actions` VALUES ('212', 'PRO_CATEGORY_RESTORE', 'trashrestore', '0', '91', '0');
INSERT INTO `qj_actions` VALUES ('213', 'PRO_CATEGORY_DEL_COM', 'trashdelete', '0', '91', '0');
INSERT INTO `qj_actions` VALUES ('214', 'PRODUCT_LIST', 'index', '0', '92', '0');
INSERT INTO `qj_actions` VALUES ('215', 'PRODUCT_ADD', 'add', '0', '92', '0');
INSERT INTO `qj_actions` VALUES ('216', 'PRODUCT_EDIT', 'edit', '0', '92', '0');
INSERT INTO `qj_actions` VALUES ('217', 'PRODUCT_DEL', 'delete', '0', '92', '0');
INSERT INTO `qj_actions` VALUES ('218', 'PRODUCT_TRASH', 'trashstore_index', '0', '92', '0');
INSERT INTO `qj_actions` VALUES ('219', 'PRODUCT_RESTORE', 'trashstore_del_restore', '0', '92', '0');
INSERT INTO `qj_actions` VALUES ('220', 'PRODUCT_DEL_COM', 'trashstore_del_complate', '0', '92', '0');
INSERT INTO `qj_actions` VALUES ('221', 'PAGE_LIST', 'index', '0', '93', '0');
INSERT INTO `qj_actions` VALUES ('222', 'PAGE_ADD', 'add', '0', '93', '0');
INSERT INTO `qj_actions` VALUES ('223', 'PAGE_EDIT', 'edit', '0', '93', '0');
INSERT INTO `qj_actions` VALUES ('224', 'PAGE_DEL', 'delete', '0', '93', '0');
INSERT INTO `qj_actions` VALUES ('225', 'LOG_LIST', 'index', '0', '94', '0');
INSERT INTO `qj_actions` VALUES ('226', 'SILDE_LIST', 'index', '0', '95', '0');
INSERT INTO `qj_actions` VALUES ('227', 'SILDE_ADD', 'add', '0', '95', '0');
INSERT INTO `qj_actions` VALUES ('228', 'SILDE_EDIT', 'edit', '0', '95', '0');
INSERT INTO `qj_actions` VALUES ('229', 'SILDE_DEL', 'delete', '0', '95', '0');
INSERT INTO `qj_actions` VALUES ('230', 'LINK_LIST', 'index', '0', '96', '0');
INSERT INTO `qj_actions` VALUES ('231', 'LINK_ADD', 'add', '0', '96', '0');
INSERT INTO `qj_actions` VALUES ('232', 'LINK_EDIT', 'edit', '0', '96', '0');
INSERT INTO `qj_actions` VALUES ('233', 'LINK_DEL', 'delete', '0', '96', '0');
INSERT INTO `qj_actions` VALUES ('234', 'ORDER_LISTS', 'index', '0', '97', '0');
INSERT INTO `qj_actions` VALUES ('235', 'ORDER_EDIT', 'edit', '0', '10', '0');
INSERT INTO `qj_actions` VALUES ('236', 'DEL', 'delete', '0', '97', '0');

-- ----------------------------
-- Table structure for `qj_admin`
-- ----------------------------
DROP TABLE IF EXISTS `qj_admin`;
CREATE TABLE `qj_admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL COMMENT '用户名',
  `password` varchar(50) NOT NULL COMMENT '密码',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `login_time` int(10) NOT NULL COMMENT '最后登录时间',
  `login_ip` varchar(50) NOT NULL COMMENT '最后登录ip',
  `groupid` int(10) NOT NULL COMMENT '管理员分组',
  `is_del` tinyint(1) NOT NULL,
  `salt` varchar(10) NOT NULL COMMENT '加密参数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_admin
-- ----------------------------
INSERT INTO `qj_admin` VALUES ('1', 'admin', 'f83a5845da856805a3aaac6ecc77525c', '0', '1475109581', '127.0.0.1', '1', '0', 'vgacxw');
INSERT INTO `qj_admin` VALUES ('2', '编辑', 'da828692d9656311c6048c5b3ff34a1d', '1420422725', '1457688106', '127.0.0.1', '2', '1', 'fuqjyk');
INSERT INTO `qj_admin` VALUES ('3', '编辑2', '0a7bda9f26f14d1f7d1becc1dc5adb15', '1420422737', '1430967769', '127.0.0.1', '1', '1', 'njklvf');
INSERT INTO `qj_admin` VALUES ('8', 'xiucms', '042107af12b16e40c113cdb62a6f22dd', '1460163439', '0', '', '1', '0', 'ypxqrp');
INSERT INTO `qj_admin` VALUES ('9', 'tina', 'a04f26171b3002530e713ba9766c033f', '1463559403', '1475023623', '192.168.1.132', '1', '0', 'urrvlv');
INSERT INTO `qj_admin` VALUES ('10', 'ivan', '5b228e93646887c43590a383df9124c9', '1466735030', '1466835714', '192.168.1.113', '1', '0', 'xjicso');

-- ----------------------------
-- Table structure for `qj_admin_log`
-- ----------------------------
DROP TABLE IF EXISTS `qj_admin_log`;
CREATE TABLE `qj_admin_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `action` varchar(255) NOT NULL,
  `create_time` int(10) NOT NULL,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1062 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_admin_log
-- ----------------------------
INSERT INTO `qj_admin_log` VALUES ('589', '0', '管理员登录:tina', '1463562047', '192.168.1.107');
INSERT INTO `qj_admin_log` VALUES ('590', '1', ':添加:page_news_num1', '1463562175', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('591', '1', ':添加:tel', '1463562206', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('592', '1', '修改成功:tel', '1463562324', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('593', '9', '修改成功:tel', '1463562381', '192.168.1.107');
INSERT INTO `qj_admin_log` VALUES ('594', '9', '导航:修改:首页', '1463562445', '192.168.1.107');
INSERT INTO `qj_admin_log` VALUES ('595', '1', '导航:修改:首页', '1463562483', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('596', '9', '修改成功:page_news_num1', '1463562507', '192.168.1.107');
INSERT INTO `qj_admin_log` VALUES ('597', '9', '网站设置:设置成功:', '1463562661', '192.168.1.107');
INSERT INTO `qj_admin_log` VALUES ('598', '0', '管理员登录:admin', '1463562689', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('599', '9', '导航:添加:网站首页', '1463562891', '192.168.1.107');
INSERT INTO `qj_admin_log` VALUES ('600', '9', '导航:添加:联系我们', '1463562922', '192.168.1.107');
INSERT INTO `qj_admin_log` VALUES ('601', '0', '管理员登录:admin', '1463618235', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('602', '0', '管理员登录:admin', '1463619566', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('603', '0', '管理员:admin', '1463622647', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('604', '1', '::39', '1463624009', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('605', '1', '::40', '1463624014', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('606', '1', '::41', '1463624017', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('607', '1', '::42', '1463624020', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('608', '1', '::43', '1463624025', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('609', '1', '::45', '1463624031', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('610', '1', '::46', '1463624034', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('611', '1', '::44', '1463624039', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('612', '1', '::index_about', '1463624068', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('613', '1', '修改成功:index_about', '1463624106', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('614', '1', '::insex_shenqi', '1463624209', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('615', '1', '修改成功:insex_shenqi', '1463624218', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('616', '1', '::48', '1463624991', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('617', '1', '::index_shenqi', '1463624997', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('618', '1', '修改成功:index_shenqi', '1463625003', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('619', '1', '::精品游戏攻略', '1463625191', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('620', '1', '::游戏动态', '1463625231', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('621', '1', '::精彩视频', '1463625249', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('622', '1', '::游戏下载', '1463625445', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('623', '1', '::公会新闻', '1463625828', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('624', '1', '::公会介绍', '1463625890', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('625', '1', '::公会游戏', '1463625935', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('626', '1', '::公会军团', '1463625956', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('627', '1', '::公会新闻', '1463626003', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('628', '1', '::公会游戏', '1463626011', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('629', '1', '::公会军团', '1463626019', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('630', '1', '::游戏攻略', '1463626036', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('631', '1', '::公会介绍', '1463626045', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('632', '1', '::游戏下载专区', '1463626065', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('633', '1', '::公会论坛', '1463626103', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('634', '1', '::公会新闻', '1463626111', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('635', '1', '::公会游戏', '1463626129', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('636', '1', '::公会军团', '1463626135', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('637', '1', '::游戏攻略', '1463626143', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('638', '1', '::公会介绍', '1463626149', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('639', '1', '::公会论坛', '1463626154', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('640', '1', '::游戏下载专区', '1463626160', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('641', '1', '::发号中心', '1463626345', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('642', '1', '::发号中心', '1463626362', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('643', '1', '::在线留言', '1463626481', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('644', '1', '::在线留言', '1463626523', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('645', '1', '::index_luentan', '1463626903', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('646', '1', '修改成功:index_luentan', '1463626911', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('647', '1', '修改成功:index_luentan', '1463626936', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('648', '1', '::耀星公会', '1463627703', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('649', '1', '::公会介绍', '1463627711', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('650', '1', '::入会必读', '1463627739', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('651', '1', '::公会章程', '1463627764', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('652', '1', '::联络方式', '1463627789', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('653', '1', '::友情链接', '1463627817', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('654', '1', '::游戏下载', '1463634146', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('655', '1', '::友情链接', '1463634163', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('656', '1', '::联络方式', '1463634171', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('657', '1', '::公会章程', '1463634175', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('658', '1', '::入会必读', '1463634179', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('659', '1', '::耀星公会', '1463634183', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('660', '1', '::公会介绍', '1463634186', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('661', '1', '::友情链接', '1463634227', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('662', '1', '::link_id', '1463635241', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('663', '1', '修改成功:link_id', '1463635246', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('664', '1', '::right_img', '1463638632', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('665', '1', '::right_img_url', '1463638653', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('666', '1', '修改成功:right_img', '1463638949', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('667', '1', '::CARD_ZHONG', '1463649345', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('668', '1', '::CARD_TYPE', '1463649394', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('669', '1', '::GAME_CARD', '1463649432', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('670', '1', '::', '1463649469', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('671', '0', '管理员:admin', '1463704155', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('672', '0', '管理员:admin', '1463704614', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('673', '1', '::最萌公会赛现金派送', '1463705410', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('674', '1', '::最萌公会赛现金派送', '1463705457', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('675', '1', '::最萌公会赛现金派送', '1463705488', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('676', '1', '::公会赛', '1463705508', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('677', '1', '::星际战甲多玩特权礼包独家特权', '1463705578', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('678', '1', '::《守望先锋》全英雄特点属性图文解析攻略', '1463705752', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('679', '1', '::《守望先锋》全英雄特点属性图文解析攻略', '1463705806', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('680', '1', '::《守望先锋》全英雄特点属性图文解析攻略', '1463705845', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('681', '1', '::1', '1463706272', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('682', '1', '::实际地图面积巨大 《最终幻想15》部分地图曝光', '1463707470', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('683', '1', '::实际地图面积巨大 《最终幻想15》部分地图曝光', '1463707511', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('684', '1', '::实际地图面积巨大 《最终幻想15》部分地图曝光', '1463707541', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('685', '1', '::发号中心', '1463707869', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('686', '1', '::公会介绍', '1463708450', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('687', '1', '::入会必读', '1463708476', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('688', '1', '::公会章程', '1463708512', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('689', '1', '::联络方式', '1463708533', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('690', '1', '::【操哥】我的世界工业2-生存实录', '1463710328', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('691', '1', '::【操哥】我的世界工业2-生存实录', '1463710450', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('692', '1', '::【操哥】我的世界工业2-生存实录', '1463710567', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('693', '1', '::【操哥】我的世界工业2-生存实录', '1463710831', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('694', '1', '::【操哥】我的世界工业2-生存实录', '1463710947', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('695', '1', '::【操哥】我的世界工业2-生存实录', '1463710993', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('696', '1', '::【操哥】我的世界工业2-生存实录', '1463711024', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('697', '1', '::【操哥】我的世界工业2-生存实录', '1463711055', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('698', '1', '::【操哥】我的世界工业2-生存实录', '1463711092', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('699', '1', '::【操哥】我的世界工业2-生存实录', '1463711138', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('700', '1', '::《守望先锋》全英雄特点属性图文解析攻略', '1463711660', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('701', '1', '::实际地图面积巨大 《最终幻想15》部分地图曝光', '1463711677', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('702', '1', '::【操哥】我的世界工业2-生存实录', '1463711691', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('703', '1', '::【操哥】我的世界工业2-生存实录', '1463711714', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('704', '0', '管理员:admin', '1463711900', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('705', '1', ':设置成功:', '1463714527', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('706', '1', '::logo1', '1463714632', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('707', '1', '修改成功:logo1', '1463714638', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('708', '1', '::luentan_url', '1463721137', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('709', '1', '修改成功:luentan_url', '1463721155', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('710', '0', '管理员:admin', '1463722498', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('711', '1', '修改成功:index_shenqi', '1463722577', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('712', '1', '::D10', '1463723963', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('713', '0', '管理员:admin', '1463724066', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('714', '1', '修改成功:index_about', '1463724139', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('715', '1', ':设置成功:', '1463724978', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('716', '1', '::星际战甲多玩特权礼包独家特权', '1463725305', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('717', '1', '::', '1463725447', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('718', '1', '::', '1463725524', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('719', '1', '::', '1463725536', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('720', '1', '::星际战甲多玩特权礼包独家特权', '1463725607', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('721', '1', '::星际战甲多玩特权礼包独家特权', '1463725628', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('722', '1', '::星际战甲多玩特权礼包独家特权', '1463725649', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('723', '1', '::星际战甲多玩特权礼包独家特权', '1463725672', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('724', '1', '::index_silde1', '1463725680', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('725', '1', '::星际战甲多玩特权礼包独家特权', '1463725690', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('726', '1', '::index_silde2', '1463725735', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('727', '1', '::D10', '1463725759', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('728', '1', '::index_silde1_url', '1463725763', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('729', '1', '::index_silde2_url', '1463725801', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('730', '1', '::D10', '1463725801', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('731', '1', '::D10', '1463725957', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('732', '1', '::D10', '1463725992', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('733', '1', '::D10', '1463726004', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('734', '1', '::D10', '1463726096', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('735', '1', '::D10', '1463726182', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('736', '1', '::D10', '1463726208', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('737', '0', '管理员:admin', '1463729858', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('738', '1', '修改成功:luentan_url', '1463730665', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('739', '0', '管理员:admin', '1463730765', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('740', '1', '::公会论坛', '1463730793', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('741', '0', '管理员:admin', '1463731022', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('742', '1', '::公会论坛', '1463731101', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('743', '1', '::login_url', '1463731625', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('744', '1', '修改成功:login_url', '1463731630', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('745', '1', '::register_url', '1463731653', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('746', '1', '修改成功:register_url', '1463731677', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('747', '0', '管理员:admin', '1463798530', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('748', '1', '::tina', '1463798543', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('749', '1', '::    公会介绍', '1463798579', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('750', '1', '::    入会必读', '1463798597', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('751', '1', '::    联络方式', '1463798617', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('752', '1', '::游戏动态', '1463798657', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('753', '1', '::入会必读', '1463798676', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('754', '1', '::联络方式', '1463798683', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('755', '1', '::公会介绍', '1463798707', '192.168.1.109');
INSERT INTO `qj_admin_log` VALUES ('756', '0', '管理员:admin', '1465285552', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('757', '0', '管理员:admin', '1465795705', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('758', '0', '管理员:admin', '1466581884', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('759', '1', '::6', '1466581941', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('760', '1', '::在线留言', '1466582875', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('761', '1', '::在线留言', '1466582884', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('762', '1', '::zxgg_id', '1466582982', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('763', '1', '修改成功:zxgg_id', '1466582994', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('764', '1', '修改成功:index_silde1', '1466583063', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('765', '1', '修改成功:index_silde2', '1466583074', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('766', '1', '::upload_id', '1466583132', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('767', '0', '管理员:admin', '1466646550', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('768', '1', '::D10', '1466653905', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('769', '1', '::表单列表', '1466658198', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('770', '0', '管理员:admin', '1466728558', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('771', '1', '修改成功:upload_id', '1466728588', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('772', '1', '::cyfc_id', '1466728709', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('773', '1', '::girl_id', '1466728768', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('774', '1', '::49', '1466729530', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('775', '1', '::50', '1466729537', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('776', '1', '修改成功:right_img', '1466729579', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('777', '0', '管理员:admin', '1466734914', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('778', '1', '::ivan', '1466735030', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('779', '1', ':设置成功:', '1466736152', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('780', '1', '::在线留言', '1466737265', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('781', '1', '::热血仙境', '1466737694', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('782', '1', '::热血', '1466738016', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('783', '1', '::热血仙境2', '1466738037', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('784', '1', '::3', '1466745521', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('785', '1', '::关于唯恋', '1466746936', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('786', '1', '::唯恋手游公会', '1466746957', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('787', '1', '::入驻游戏', '1466747853', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('788', '1', '::入驻游戏', '1466748196', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('789', '1', '::关于唯恋', '1466748261', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('790', '1', '::关于唯恋', '1466748271', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('791', '1', '::游戏动态', '1466748294', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('792', '1', '::入驻游戏', '1466748307', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('793', '1', '::关于唯恋', '1466748327', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('794', '1', '::游戏动态', '1466748358', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('795', '1', '::游戏动态', '1466748593', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('796', '1', '::游戏动态', '1466748613', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('797', '1', '::公会新闻', '1466748731', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('798', '1', '::啊实打实', '1466748890', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('799', '1', '::8', '1466748894', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('800', '1', '::成员风采', '1466748919', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('801', '1', '::ShowGirl', '1466749475', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('802', '1', '::加入唯恋', '1466749623', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('803', '1', '::3', '1466749743', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('804', '1', '::成员风采', '1466750009', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('805', '1', '::发号中心', '1466750092', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('806', '1', '::GAME_MANAGER', '1466750119', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('807', '1', '::游戏管理', '1466750126', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('808', '1', '::ShowGirl', '1466750147', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('809', '1', '::DOWNLOAD_MANAGER', '1466750172', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('810', '1', '::游戏管理', '1466750190', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('811', '1', '::成员风采', '1466750207', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('812', '1', '::ShowGirl', '1466750222', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('813', '1', '::成员风采', '1466750230', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('814', '1', '::', '1466750701', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('815', '1', '::加入唯恋', '1466750925', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('816', '1', '::', '1466750943', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('817', '1', '::', '1466751017', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('818', '1', '::', '1466751117', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('819', '1', '::', '1466751175', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('820', '1', '::', '1466751210', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('821', '1', '::', '1466751313', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('822', '1', '::苏澜学院的一枚小玉虚', '1466751690', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('823', '1', '::苏澜学院的一枚小玉虚', '1466751707', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('824', '1', '::唯恋小小圣堂', '1466751939', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('825', '1', '::唯恋的一枚小玉虚', '1466751951', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('826', '1', '::4', '1466752103', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('827', '1', '::云垂大陆的萌厨娘，甜到发腻，才艺值到报表的AliceLiddel', '1466752343', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('828', '1', '::云垂大陆的萌厨娘，甜到发腻，才艺值到报表的AliceLiddel', '1466752371', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('829', '1', '::羞涩哒伊娃小盆友', '1466752622', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('830', '1', '::唯恋小小圣堂', '1466752692', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('831', '1', '::羞涩哒伊娃小盆友', '1466752714', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('832', '1', '::冷艳小白菜', '1466752834', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('833', '1', '::浮生若梦', '1466753031', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('834', '1', '::入驻游戏', '1466753492', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('835', '1', '::download_title', '1466755545', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('836', '1', '::download_keywords', '1466755586', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('837', '1', '::download_description', '1466755604', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('838', '1', '::东京食尸鬼', '1466756055', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('839', '1', '修改成功:download_title', '1466757169', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('840', '1', '修改成功:download_keywords', '1466757172', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('841', '1', '修改成功:download_description', '1466757176', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('842', '1', '::11', '1466757484', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('843', '1', '::', '1466757665', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('844', '1', '::', '1466757798', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('845', '1', '::', '1466757830', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('846', '1', '::108', '1466757996', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('847', '1', '::', '1466759315', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('848', '1', '::倩女幽魂新手卡', '1466759546', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('849', '1', '修改成功:cyfc_id', '1466759668', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('850', '1', '修改成功:girl_id', '1466759677', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('851', '1', '::唯恋小小圣堂', '1466759748', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('852', '0', '管理员:ivan', '1466835714', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('853', '10', '::', '1466835916', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('854', '0', '管理员:admin', '1466835969', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('855', '10', '::放开那三国新手卡', '1466836400', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('856', '10', '::', '1466836643', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('857', '10', '::谁是大英雄新手卡', '1466836695', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('858', '10', '::', '1466838983', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('859', '10', '::9', '1466842060', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('860', '10', '::友情链接', '1466842155', '192.168.1.113');
INSERT INTO `qj_admin_log` VALUES ('861', '0', '管理员:admin', '1466990873', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('862', '1', '::', '1466991133', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('863', '0', '管理员:admin', '1467784680', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('864', '0', '管理员:admin', '1467784694', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('865', '1', '修改成功:luentan_url', '1467784707', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('866', '1', '::公会论坛', '1467784730', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('867', '0', '管理员:admin', '1467796607', '192.168.1.117');
INSERT INTO `qj_admin_log` VALUES ('868', '1', '::11', '1467797278', '192.168.1.117');
INSERT INTO `qj_admin_log` VALUES ('869', '1', '::22', '1467797359', '192.168.1.117');
INSERT INTO `qj_admin_log` VALUES ('870', '0', '管理员:admin', '1468548195', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('871', '0', '管理员:admin', '1470799204', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('872', '0', '管理员:admin', '1470875471', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('873', '0', '管理员:admin', '1470892257', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('874', '1', '::ACCOUNT_CONF', '1470892329', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('875', '1', '::QQ互联', '1470893698', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('876', '1', '::QQ互联', '1470893826', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('877', '1', '::QQ互联', '1470893994', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('878', '1', '::109', '1470896249', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('879', '1', '::7', '1470897768', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('880', '1', '::8', '1470897777', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('881', '0', '管理员:admin', '1470972102', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('882', '1', '::QQ互联', '1470972137', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('883', '0', '管理员:admin', '1471055288', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('884', '1', '::index_gid', '1471064641', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('885', '1', '::index_cid', '1471064664', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('886', '1', '::gonggao_id', '1471064698', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('887', '1', '::yxgl_id', '1471064719', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('888', '1', '::游戏攻略', '1471064741', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('889', '1', '::活动公告', '1471064763', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('890', '1', '::3', '1471064767', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('891', '1', '::5', '1471064770', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('892', '1', '::6', '1471064773', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('893', '1', '::7', '1471064775', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('894', '1', '::9', '1471064777', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('895', '1', '::10', '1471064780', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('896', '1', '::3,4,5,6,7,8,9,10', '1471064787', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('897', '1', '修改成功:gonggao_id', '1471064797', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('898', '1', '修改成功:yxgl_id', '1471064800', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('899', '1', '::tj_gids', '1471065071', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('900', '1', '::实际地图面积巨大 《最终幻想15》部分地图曝光', '1471065973', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('901', '1', '::实际地图面积巨大 《最终幻想15》部分地图曝光', '1471065987', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('902', '1', '::《守望先锋》全英雄特点属性图文解析攻略', '1471066022', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('903', '1', '修改成功:tj_gids', '1471066387', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('904', '0', '管理员:admin', '1471231934', '192.168.1.112');
INSERT INTO `qj_admin_log` VALUES ('905', '1', ':设置成功:', '1471231970', '192.168.1.112');
INSERT INTO `qj_admin_log` VALUES ('906', '0', '管理员:admin', '1471232474', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('907', '1', '::实际地图面积巨大 《最终幻想15》部分地图曝光', '1471232575', '192.168.1.112');
INSERT INTO `qj_admin_log` VALUES ('908', '1', '::倩女活动公告2', '1471232657', '192.168.1.112');
INSERT INTO `qj_admin_log` VALUES ('909', '1', '::倩女活动公告1', '1471232677', '192.168.1.112');
INSERT INTO `qj_admin_log` VALUES ('910', '1', '::三国活动公告1', '1471232900', '192.168.1.112');
INSERT INTO `qj_admin_log` VALUES ('911', '1', '::29,28,27,26,25,24,23,22,21,20,19,18,17,16,15,14,13,12,11,10', '1471232910', '192.168.1.112');
INSERT INTO `qj_admin_log` VALUES ('912', '1', '::3,2,1', '1471232924', '192.168.1.112');
INSERT INTO `qj_admin_log` VALUES ('913', '1', '::', '1471238312', '192.168.1.112');
INSERT INTO `qj_admin_log` VALUES ('914', '1', '::', '1471238640', '192.168.1.112');
INSERT INTO `qj_admin_log` VALUES ('915', '1', '::', '1471238781', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('916', '1', '::', '1471238821', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('917', '1', '::', '1471238872', '192.168.1.112');
INSERT INTO `qj_admin_log` VALUES ('918', '1', '::', '1471240008', '192.168.1.112');
INSERT INTO `qj_admin_log` VALUES ('919', '1', '::', '1471240036', '192.168.1.112');
INSERT INTO `qj_admin_log` VALUES ('920', '0', '管理员:admin', '1471240882', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('921', '1', '::游戏攻略', '1471243291', '192.168.1.112');
INSERT INTO `qj_admin_log` VALUES ('922', '1', '::倩女幽魂游戏攻略1', '1471243308', '192.168.1.112');
INSERT INTO `qj_admin_log` VALUES ('923', '1', '::', '1471243406', '192.168.1.112');
INSERT INTO `qj_admin_log` VALUES ('924', '1', '::倩女幽魂豪华礼包', '1471243438', '192.168.1.112');
INSERT INTO `qj_admin_log` VALUES ('925', '1', '::游戏公告', '1471243741', '192.168.1.112');
INSERT INTO `qj_admin_log` VALUES ('926', '0', '管理员:admin', '1471941099', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('927', '0', '管理员:admin', '1474425516', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('928', '1', '::110', '1474425537', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('929', '1', '::漫道短信接口[推荐]', '1474425554', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('930', '1', '::CATE_MANAGER', '1474426930', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('931', '1', '::CATEGORY_MANAGE', '1474426962', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('932', '1', '::栏目管理', '1474426970', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('933', '1', '::CONTENT_MANAGE', '1474427067', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('934', '1', '::文章列表', '1474427098', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('935', '1', '::游戏管理', '1474427115', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('936', '1', '::游戏卡管理', '1474427160', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('937', '1', '::游戏中心', '1474427181', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('938', '1', '::游戏管理', '1474427207', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('939', '1', '::GAME_TYPE', '1474429346', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('940', '1', '::游戏类型', '1474429423', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('941', '1', '::GAME_PLAT', '1474434461', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('942', '1', '::Network', '1474434566', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('943', '1', '::KAIFU_MANAGE', '1474437046', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('944', '1', '::网络平台', '1474439006', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('945', '1', '::在线留言', '1474449332', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('946', '1', '::11', '1474449339', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('947', '1', '::7', '1474449343', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('948', '1', '::6', '1474449346', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('949', '1', '::222', '1474449377', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('950', '1', '::1111', '1474449675', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('951', '1', '::222', '1474449684', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('952', '1', '::222', '1474449817', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('953', '1', '::9,8,7,6,5,4', '1474450689', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('954', '1', '::29,28,27,26,25,24,23,22,21,20,19,18,17,16,15,14,13,12,11,10', '1474450696', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('955', '1', '::9,8,7,6,5,4,3,2,1', '1474450700', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('956', '1', '::1', '1474450705', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('957', '1', '::2', '1474450708', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('958', '1', '::11', '1474450710', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('959', '1', '::1,2,11', '1474450778', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('960', '1', '文章分类:删除:1', '1474451634', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('961', '1', '文章分类:删除:2', '1474451637', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('962', '1', '文章分类:删除:11', '1474451640', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('963', '1', '文章分类:彻底删除:1,2,11', '1474451648', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('964', '1', '文章分类:添加:游戏大全', '1474451669', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('965', '0', '管理员登录:admin', '1474503957', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('966', '1', '文章分类:修改:游戏大全', '1474504483', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('967', '0', '管理员登录:admin', '1474505552', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('968', '1', '网站设置:设置成功:', '1474510085', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('969', '1', '导航:删除:5', '1474510363', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('970', '1', '导航:删除:9', '1474510366', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('971', '1', '导航:删除:10', '1474510369', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('972', '1', '导航:删除:11', '1474510371', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('973', '1', '导航:删除:12', '1474510375', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('974', '1', '导航:删除:2', '1474510379', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('975', '1', '导航:删除:4', '1474510383', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('976', '1', '导航:删除:13', '1474510387', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('977', '1', '导航:修改:游戏大全', '1474510413', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('978', '1', '文章分类:添加:游戏资讯', '1474510625', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('979', '1', '文章分类:添加:游戏功略', '1474510676', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('980', '1', '文章:添加:六龙争霸3D原石怎么获取', '1474510802', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('981', '1', '文章:添加:全民超神偷心魅魔怎么提升战力', '1474510905', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('982', '1', '文章:添加:全民枪战争霸币怎么获取', '1474511020', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('983', '1', '文章:添加:英雄战迹赵云怎么样 赵云天赋怎么加点', '1474511125', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('984', '1', '文章:添加:梦幻西游手游龙宫带什么特技好 龙宫怎么加点', '1474511227', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('985', '0', '管理员登录:admin', '1474511253', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('986', '1', '文章:添加:全民突击战甲怎么搭配', '1474511321', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('987', '1', '文章:添加:火柴人联盟武器大师怎么样？好用吗？', '1474511458', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('988', '1', '文章:添加:问道手游兰若寺副本通关攻略', '1474511547', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('989', '1', '文章:添加:全民超神寒冬之心宝石怎么搭配', '1474511626', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('990', '1', '文章:添加:天天酷跑玫瑰利刃值得入手吗？好不好？', '1474512387', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('991', '1', '文章:添加:火影忍者手游凯怎么无限连招', '1474512877', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('992', '1', '文章:添加:天天酷跑审判女王新宝物礼包值得买吗', '1474512960', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('993', '1', '导航:添加:游戏资讯', '1474512999', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('994', '1', '导航:添加:游戏功略', '1474513009', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('995', '1', '文章分类:修改:游戏功略', '1474515012', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('996', '1', '文章分类:修改:游戏资讯', '1474515017', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('997', '1', '文章分类:修改:游戏大全', '1474515032', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('998', '1', '单页面:修改:关于手游村', '1474515054', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('999', '0', '管理员登录:admin', '1474521520', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1000', '1', '模块:修改:PUSH_TYPE', '1474523875', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1001', '1', '模块:添加:PUSH_MANAGE', '1474527142', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1002', '0', '管理员登录:admin', '1474592965', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1003', '0', '管理员登录:admin', '1474596933', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1004', '0', '管理员登录:admin', '1474696880', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1005', '1', '文章分类:添加:新游推荐', '1474700906', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1006', '1', '文章分类:添加:游戏开服', '1474701009', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1007', '1', '文章分类:修改:游戏开服', '1474701016', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1008', '1', '文章分类:添加:开服', '1474701036', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1009', '1', '文章分类:修改:开服', '1474701043', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1010', '1', '文章分类:添加:开测', '1474701067', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1011', '1', '文章分类:添加:游戏礼包', '1474701205', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1012', '1', '导航:添加:开服开测', '1474704927', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1013', '1', '导航:修改:开服开测', '1474704942', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1014', '1', '导航:添加:游戏礼包', '1474708430', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1015', '0', '管理员登录:admin', '1474851500', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1016', '1', '管理员:修改:tina', '1474851515', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1017', '0', '管理员登录:admin', '1474856446', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1018', '1', '单页面:添加:手游排行', '1474856538', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1019', '1', '导航:添加:手游排行', '1474856551', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1020', '0', '管理员登录:tina', '1474861848', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1021', '1', '文章分类:删除:17', '1474876279', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1022', '1', '文章分类:删除:18', '1474876283', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1023', '1', '文章分类:彻底删除:17,18', '1474876288', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1024', '9', '文章分类:添加:热门视频', '1474878415', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1025', '9', '文章:添加:《封印者》新时装“黑暗光辉”展示', '1474878889', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1026', '0', '管理员登录:tina', '1474937369', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1027', '0', '管理员登录:admin', '1474937482', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1028', '9', '单页面:修改:加入手游村', '1474937498', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1029', '9', '单页面:修改:联络方式', '1474937517', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1030', '9', '单页面:修改:入会必读', '1474937548', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1031', '9', '单页面:删除:3', '1474937553', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1032', '9', '单页面:修改:关于手游村', '1474937577', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1033', '9', '单页面:删除:2', '1474937580', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1034', '9', '导航:添加:关于我们', '1474937611', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1035', '9', '导航:修改:关于我们', '1474937628', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1036', '9', '导航:添加:联系我们', '1474937658', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1037', '9', '导航:添加:关于我们', '1474937682', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1038', '9', '导航:添加:联系我们', '1474937704', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1039', '9', '导航:修改:关于我们', '1474937711', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1040', '9', '导航:添加:加入我们', '1474937745', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1041', '9', ':添加:逗逗猫', '1474942337', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1042', '9', ':添加:摩登农场', '1474959346', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1043', '9', ':添加:独步天下', '1474960593', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1044', '1', ':添加:mlx', '1474963338', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1045', '1', ':添加:swhd', '1474963368', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1046', '1', ':添加:wxfl', '1474963396', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1047', '9', '文章:添加:《封印者》新时装“黑暗光辉”展示', '1474964805', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1048', '9', '文章:添加:《封印者》新时装“黑暗光辉”展示', '1474964830', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1049', '9', '文章:修改:《封印者》新时装“黑暗光辉”展示', '1474964887', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1050', '9', '文章:修改:《封印者》新时装“黑暗光辉”展示', '1474964892', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1051', '9', '文章:修改:《封印者》新时装“黑暗光辉”展示', '1474964903', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1052', '9', ':修改:', '1474965180', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1053', '9', '文章:修改:火柴人联盟武器大师怎么样？好用吗？', '1474966320', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1054', '0', '管理员登录:tina', '1475023623', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1055', '0', '管理员登录:admin', '1475026560', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1056', '9', '文章:添加:《封印者》新时装“黑暗光辉”展示', '1475028209', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1057', '9', '文章:添加:《封印者》新时装“黑暗光辉”展示', '1475028261', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1058', '1', '模块:添加:ADVTYPE', '1475028753', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1059', '1', '模块:添加:ADV', '1475028782', '127.0.0.1');
INSERT INTO `qj_admin_log` VALUES ('1060', '9', ':添加:亚瑟神剑', '1475043301', '192.168.1.132');
INSERT INTO `qj_admin_log` VALUES ('1061', '0', '管理员登录:admin', '1475109581', '127.0.0.1');

-- ----------------------------
-- Table structure for `qj_adv`
-- ----------------------------
DROP TABLE IF EXISTS `qj_adv`;
CREATE TABLE `qj_adv` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` int(10) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `img` varchar(255) NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_adv
-- ----------------------------
INSERT INTO `qj_adv` VALUES ('1', '1', '', '', '/App/Tpl/Common/images/7.jpg', '0');
INSERT INTO `qj_adv` VALUES ('2', '2', '', '', '/App/Tpl/Common/images/9.jpg', '0');
INSERT INTO `qj_adv` VALUES ('3', '3', '', '', '/App/Tpl/Common/images/10.jpg', '0');
INSERT INTO `qj_adv` VALUES ('4', '4', '', '', '/App/Tpl/Common/images/11.jpg', '0');
INSERT INTO `qj_adv` VALUES ('5', '5', '', '', '/App/Tpl/Common/images/18.jpg', '0');
INSERT INTO `qj_adv` VALUES ('6', '5', '', '', '/App/Tpl/Common/images/18.jpg', '0');

-- ----------------------------
-- Table structure for `qj_advtype`
-- ----------------------------
DROP TABLE IF EXISTS `qj_advtype`;
CREATE TABLE `qj_advtype` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `width` smallint(5) NOT NULL,
  `height` smallint(5) NOT NULL,
  `temp` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_advtype
-- ----------------------------
INSERT INTO `qj_advtype` VALUES ('1', '首页广告位(上)', '1000', '100', null);
INSERT INTO `qj_advtype` VALUES ('2', '首页轮播图右侧', '365', '170', null);
INSERT INTO `qj_advtype` VALUES ('3', '首页广告位(下)', '1000', '100', null);
INSERT INTO `qj_advtype` VALUES ('4', '首页广告位(底)', '1000', '100', null);
INSERT INTO `qj_advtype` VALUES ('5', '内页右侧', '310', '170', '<ul><a href=\"$url\" target=\"_blank\"><img src=\"$img\" alt=\"$title\"></a></ul>');

-- ----------------------------
-- Table structure for `qj_card`
-- ----------------------------
DROP TABLE IF EXISTS `qj_card`;
CREATE TABLE `qj_card` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `card_id` int(10) NOT NULL,
  `card_idno` varchar(255) NOT NULL,
  `userid` int(10) NOT NULL COMMENT '领取会员id',
  `username` varchar(255) NOT NULL COMMENT '领取会员名称',
  `collection_ip` varchar(255) NOT NULL,
  `collection_time` int(10) NOT NULL COMMENT '领取时间',
  `is_del` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `card_id` (`card_id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8 COMMENT='卡号表';

-- ----------------------------
-- Records of qj_card
-- ----------------------------
INSERT INTO `qj_card` VALUES ('25', '2', '23213213123', '1', 'admin', '', '1463715619', '0');
INSERT INTO `qj_card` VALUES ('26', '2', '321312324', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('27', '2', '23435345', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('28', '2', '5465465464', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('29', '2', '4564567575', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('30', '2', '345342342', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('31', '2', '5465475675', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('32', '2', '213123546', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('33', '2', '546756785342', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('34', '2', '234364575', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('35', '2', 'ahjvas234234', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('36', '2', '234345sdas', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('37', '2', '3423sdfsfre', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('38', '2', '234gre234vdf', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('39', '2', '3423dsf353ds', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('40', '2', '23423dfsfsr35', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('41', '2', '3242342tfdsf3', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('42', '2', 'ewdasd32423', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('43', '2', '423423sdsfdgdf', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('44', '2', '234243sdfdgfd', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('45', '2', 'dsfdfgd33453', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('46', '3', 'aaaaaaaaaaa', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('47', '4', 'kdjogiqewkl', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('48', '5', 'dsfheiwthbnf dx', '1', 'admin', '', '1466652065', '0');
INSERT INTO `qj_card` VALUES ('49', '6', 'dtgadshfoawdfe', '1', 'admin', '', '1466651731', '0');
INSERT INTO `qj_card` VALUES ('50', '7', 'dfgb w4y', '1', 'admin', '', '1466651724', '0');
INSERT INTO `qj_card` VALUES ('51', '8', 'dsgtbe56787', '1', 'admin', '', '1466651616', '1');
INSERT INTO `qj_card` VALUES ('52', '8', 'asdasdasdasdas', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('53', '8', 'asdasdasdas', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('54', '8', 'SDASDASDA', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('55', '8', 'DSFDSFDSFD', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('56', '8', 'DFGFHGFHGFH', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('57', '8', 'FDGDFGDFGDF', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('58', '8', 'GFHGFHGFH', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('59', '8', 'GFHGFHGJASDA', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('60', '8', 'ASDSREWRERTRE', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('61', '8', 'RETEYRXVCVXCBGF', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('62', '8', 'DSFSDFDFHRTYSDFSD', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('63', '8', 'DSFDGFBXCVSDFE', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('64', '8', 'SDFDSFDSFDSFDG', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('65', '9', 'DSFSDFDSFDS', '1', 'admin', '', '1466756482', '0');
INSERT INTO `qj_card` VALUES ('66', '9', 'DSFDSFDSFDSF', '3', 'ivan', '', '1466757305', '0');
INSERT INTO `qj_card` VALUES ('67', '9', 'DGFDFGFHGF', '0', '', '127.0.0.1', '1471249706', '0');
INSERT INTO `qj_card` VALUES ('68', '9', 'DSFDSFDSFDS', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('69', '10', 'ghrtgdsfgaes', '1', '', '127.0.0.1', '1471076258', '0');
INSERT INTO `qj_card` VALUES ('70', '10', 'asdgtrhrtyh54', '3', '', '192.168.1.112', '1471230725', '0');
INSERT INTO `qj_card` VALUES ('71', '10', 'fgfhyrthyrtre', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('72', '10', 'srttyyjuyjufg', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('73', '11', 'fhththrt', '1', '', '127.0.0.1', '1471232973', '0');
INSERT INTO `qj_card` VALUES ('74', '11', 'kirytgh', '0', '', '127.0.0.1', '1471249691', '0');
INSERT INTO `qj_card` VALUES ('75', '11', 'rthrytb', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('76', '11', 'fgjtyuie', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('77', '11', 'yutuiirew', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('78', '11', 'rture6u5', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('79', '12', '1111111', '1', '', '127.0.0.1', '1471232915', '0');
INSERT INTO `qj_card` VALUES ('80', '13', 'sdgherh', '5', 'a111', '', '1474941046', '0');
INSERT INTO `qj_card` VALUES ('81', '13', 'wrtgerh', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('82', '13', 'eryrtyhu', '0', '', '', '0', '0');
INSERT INTO `qj_card` VALUES ('83', '14', 'ip890.8p.890【。', '0', '', '', '0', '0');

-- ----------------------------
-- Table structure for `qj_card_type`
-- ----------------------------
DROP TABLE IF EXISTS `qj_card_type`;
CREATE TABLE `qj_card_type` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `orderid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='卡号类型';

-- ----------------------------
-- Records of qj_card_type
-- ----------------------------
INSERT INTO `qj_card_type` VALUES ('1', '新手卡', '0');
INSERT INTO `qj_card_type` VALUES ('2', '豪华礼包', '0');

-- ----------------------------
-- Table structure for `qj_category`
-- ----------------------------
DROP TABLE IF EXISTS `qj_category`;
CREATE TABLE `qj_category` (
  `catid` int(10) NOT NULL AUTO_INCREMENT COMMENT '类别id',
  `catname` varchar(255) NOT NULL COMMENT '类别名称',
  `en_name` varchar(255) NOT NULL COMMENT '英文名称',
  `catdir` varchar(255) NOT NULL COMMENT '类别目录',
  `title` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `tablename` varchar(255) NOT NULL,
  `parentid` int(10) NOT NULL COMMENT '上级类别',
  `par_urls` varchar(255) NOT NULL COMMENT '上级目录地址',
  `hits` int(10) NOT NULL COMMENT '查看次数',
  `orderid` int(10) NOT NULL COMMENT '排序',
  `list_template` varchar(255) DEFAULT NULL,
  `show_template` varchar(255) DEFAULT NULL,
  `wap_list_template` varchar(255) DEFAULT NULL,
  `wap_show_template` varchar(255) DEFAULT NULL,
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `is_del` tinyint(1) NOT NULL COMMENT '是否删除',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_category
-- ----------------------------
INSERT INTO `qj_category` VALUES ('12', '游戏大全', '', 'youxi', '游戏大全-手游村', '游戏大全', '游戏大全', 'game', '0', '', '0', '0', 'index', 'show', 'index', 'show', '1474451669', '0');
INSERT INTO `qj_category` VALUES ('13', '游戏资讯', 'youxizixun', 'youxizixun', '游戏资讯-手游村', '游戏资讯', '游戏资讯', 'news', '0', '', '132', '0', 'index', 'show', 'index', 'show', '1474510625', '0');
INSERT INTO `qj_category` VALUES ('14', '游戏功略', 'youxigonglue', 'youxigonglue', '游戏功略-手游村', '游戏功略', '游戏功略', 'news', '0', '', '107', '0', 'index', 'show', 'index', 'show', '1474510676', '0');
INSERT INTO `qj_category` VALUES ('15', '新游推荐', '', 'tuijian', '', '', '', 'page', '0', '', '0', '0', 'index', 'show', 'index', 'show', '1474700906', '0');
INSERT INTO `qj_category` VALUES ('16', '游戏开服', '', 'kaifu', '', '', '', 'kaifu', '0', '', '0', '0', 'index', 'show', 'index', 'show', '1474701008', '0');
INSERT INTO `qj_category` VALUES ('19', '游戏礼包', '', 'libao', '', '', '', 'gamecard', '0', '', '0', '0', 'index', 'show', 'index', 'show', '1474701205', '0');
INSERT INTO `qj_category` VALUES ('20', '热门视频', 'shipin', 'shipin', '热门视频', '热门视频', '热门视频', 'news', '0', '', '2', '0', 'index', 'show', 'index', 'show', '1474878415', '0');

-- ----------------------------
-- Table structure for `qj_download`
-- ----------------------------
DROP TABLE IF EXISTS `qj_download`;
CREATE TABLE `qj_download` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `game_id` int(10) NOT NULL,
  `day_num` int(10) NOT NULL,
  `week_num` int(10) NOT NULL,
  `month_num` varchar(255) NOT NULL,
  `year_num` int(10) NOT NULL,
  `num` varchar(100) NOT NULL,
  `updatetime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `game_id` (`game_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_download
-- ----------------------------
INSERT INTO `qj_download` VALUES ('1', '18', '3', '3', '3', '3', '3', '1474880442');
INSERT INTO `qj_download` VALUES ('2', '15', '2', '3', '3', '3', '3', '1474966344');
INSERT INTO `qj_download` VALUES ('3', '9', '2', '2', '2', '2', '2', '1474878346');
INSERT INTO `qj_download` VALUES ('4', '7', '1', '3', '3', '3', '3', '1475044263');
INSERT INTO `qj_download` VALUES ('5', '13', '1', '1', '1', '1', '1', '1474870963');
INSERT INTO `qj_download` VALUES ('6', '8', '2', '2', '2', '2', '2', '1474878338');
INSERT INTO `qj_download` VALUES ('7', '10', '1', '7', '7', '7', '7', '1475053603');
INSERT INTO `qj_download` VALUES ('8', '17', '1', '1', '1', '1', '1', '1474937130');
INSERT INTO `qj_download` VALUES ('9', '16', '1', '1', '1', '1', '1', '1474963072');
INSERT INTO `qj_download` VALUES ('10', '12', '1', '1', '1', '1', '1', '1474963074');

-- ----------------------------
-- Table structure for `qj_email_conf`
-- ----------------------------
DROP TABLE IF EXISTS `qj_email_conf`;
CREATE TABLE `qj_email_conf` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '邮箱配置id',
  `mail_server` varchar(255) DEFAULT NULL COMMENT '邮件服务器',
  `mail_port` int(10) NOT NULL COMMENT '发送邮件端口',
  `mail_from` varchar(255) DEFAULT NULL COMMENT '发件人地址',
  `mail_user` varchar(255) DEFAULT NULL COMMENT '验证用户名',
  `mail_password` varchar(255) DEFAULT NULL COMMENT '验证密码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_email_conf
-- ----------------------------

-- ----------------------------
-- Table structure for `qj_game`
-- ----------------------------
DROP TABLE IF EXISTS `qj_game`;
CREATE TABLE `qj_game` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `catid` int(10) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `banner` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8 NOT NULL,
  `typeid` int(10) NOT NULL,
  `network` int(10) NOT NULL,
  `keywords` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `content` text CHARACTER SET utf8,
  `download` varchar(255) DEFAULT NULL,
  `banben` varchar(255) DEFAULT NULL,
  `size` int(10) DEFAULT NULL,
  `down_num` int(10) NOT NULL,
  `clicks` int(10) NOT NULL,
  `orderid` int(10) NOT NULL,
  `add_time` int(10) NOT NULL,
  `update_time` int(10) NOT NULL,
  `is_del` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of qj_game
-- ----------------------------
INSERT INTO `qj_game` VALUES ('2', '12', '倩女幽魂', '/public/uploadfile/image/20160815/20160815141748_96448.png', '/public/uploadfile/image/20160624/20160624170528_50517.png', '1', '1', '', '', '倩女幽魂手游内容', '', '', '0', '0', '0', '0', '0', '1474700806', '0');
INSERT INTO `qj_game` VALUES ('3', '12', '放开那三国', '', '/public/uploadfile/image/20160625/20160625142258_40319.png', '8', '1', '', '', '', '', '', '0', '0', '0', '0', '0', '1474700832', '0');
INSERT INTO `qj_game` VALUES ('4', '12', '谁是大英雄', '/public/uploadfile/image/20160625/20160625142401_34612.png', '/public/uploadfile/image/20160625/20160625143544_12936.png', '8', '1', '', '', '的撒打算打算打算按时打算打算打', '', '', '0', '0', '0', '0', '0', '1474700837', '0');
INSERT INTO `qj_game` VALUES ('5', '12', '刀塔传奇', '', '/public/uploadfile/image/20160625/20160625151143_39267.png', '2', '1', '', '', '', '', '', '0', '0', '0', '0', '0', '1474700841', '0');
INSERT INTO `qj_game` VALUES ('6', '12', '逗逗猫', '', '/public/uploadfile/image/20160926/20160926114535_22768.png', '3', '1', '逗逗猫', '好玩休闲游戏推荐，《逗逗猫》是一款休闲dots类游戏，滑动相邻的同色猫咪进行消除，防止猫球溢出的同时干扰对手的网络对战游戏。', '<span style=\"color:#666666;font-family:微软雅黑;font-size:14px;line-height:26px;background-color:#FFFFFF;\"><span style=\"color:#666666;font-family:微软雅黑;font-size:14px;line-height:26px;background-color:#FFFFFF;\">好玩休闲游戏推荐，《逗逗猫》是一款休闲dots类游戏，滑动相邻的同色猫咪进行消除，防止猫球溢出的同时干扰对手的网络对战游戏。 游戏风格： 游戏糅合了传统的绘画风格和现代比较潮流的卡通Q版风格，在场景绘制上使得玩家有耳目一新的感觉。浩瀚的宇宙中，猫星人之间展开激烈对抗，变色龙美杜莎齐上阵，在狭小的空间里撑到最后的就是强者！ 游戏特色： 随时随地和好友一起玩，史上最萌的消除游戏。在游戏《逗逗猫》中，用户使用微博账号登陆，能够同步游戏中部分数据，包括昵称、好友等。玩家可以发微博并@好友分享自己的战绩。</span></span>', 'http://n.qd.shouji.360tpcdn.com/nqapk/lm_111961/140429/0ebe12e8e86239111bc6aece9aa9493d/com.LeTu.Main_20100.apk', '2.01.00', '17', '0', '0', '0', '1474860597', '1474861538', '0');
INSERT INTO `qj_game` VALUES ('7', '12', '极品双修', null, '/public/uploadfile/image/20160926/20160926114503_91342.png', '6', '2', '', '极致战斗体验，让你爽到不能呼吸！多样仙剑技能系统，让你每次战斗都有不一样的快感！更有美丽仙女、霸气飞行坐骑相伴，让你战斗路途上永不寂寞！', '<span style=\"color:#666666;font-family:微软雅黑;font-size:14px;line-height:26px;background-color:#FFFFFF;\">极致战斗体验，让你爽到不能呼吸！多样仙剑技能系统，让你每次战斗都有不一样的快感！更有美丽仙女、霸气飞行坐骑相伴，让你战斗路途上永不寂寞！在游戏中，你还能遇见心仪的另一半，共闯夫妻副本，夺取极品装备！真人语音，聆听你的伙伴！定义头像，交友游戏两不误！</span>', 'http://downali.game.uc.cn/wm/8/8/jpsx-huc_3835816_16015734f1f1.apk', '1.0.0', '151', '3', '0', '0', '1474861505', '1474861505', '0');
INSERT INTO `qj_game` VALUES ('8', '12', '胭脂', null, '/public/uploadfile/image/20160926/20160926114745_98558.png', '1', '2', '胭脂', '《胭脂》手游是根据同名谍战剧《胭脂》改编的2016巨制民国风RPG手游作品，游戏遵循原作剧本，精细还原了电视剧中的时代背景、人物角色与故事剧情。该剧由著名导演徐纪周指导，更由新生代影视小花旦赵丽颖与王牌男神陆毅领衔主演，在拍摄初期就已广受关注，播出后更受到大批量观众的火热追捧。', '<span style=\"color:#666666;font-family:微软雅黑;font-size:14px;line-height:26px;background-color:#FFFFFF;\">《胭脂》手游是根据同名谍战剧《胭脂》改编的2016巨制民国风RPG手游作品，游戏遵循原作剧本，精细还原了电视剧中的时代背景、人物角色与故事剧情。该剧由著名导演徐纪周指导，更由新生代影视小花旦赵丽颖与王牌男神陆毅领衔主演，在拍摄初期就已广受关注，播出后更受到大批量观众的火热追捧。作为原剧的唯一正版授权手游，《胭脂》手游邀请到了全体原班演员人马与主创人员联手入驻，更通过唯美的2D画面与丰富有趣的游戏系</span>', 'http://downali.game.uc.cn/wm/7/7/Rouge-UC_V1.0.1_201609231749_8989447_19205414393a.apk', '1.0.1', '101', '2', '0', '0', '1474861724', '1474861724', '0');
INSERT INTO `qj_game` VALUES ('9', '12', '军团荣耀', null, '/public/uploadfile/image/20160926/20160926115123_83536.png', '2', '2', '军团荣耀', '首款4v4对战类Moba游戏，84个兵种10个英雄的相互搭配玩法，让玩家在绚丽的技能特效中享受视觉盛宴独创的怪物寻路方式，3D模型在同频下可以达到200个，市面主流机都能流畅的玩耍竞技模式推广，赛区竞技，丰厚奖励等你来拿！', '<span style=\"color:#666666;font-family:微软雅黑;font-size:14px;line-height:26px;background-color:#FFFFFF;\">首款4v4对战类Moba游戏，84个兵种10个英雄的相互搭配玩法，让玩家在绚丽的技能特效中享受视觉盛宴独创的怪物寻路方式，3D模型在同频下可以达到200个，市面主流机都能流畅的玩耍竞技模式推广，赛区竞技，丰厚奖励等你来拿！</span>', 'http://downali.game.uc.cn/wm/0/16/legiontd_v1091_8988656_185817552526.apk', '1.0.91', '127', '2', '0', '0', '1474861926', '1474861926', '0');
INSERT INTO `qj_game` VALUES ('10', '12', '王者天下', null, '/public/uploadfile/image/20160926/20160926115258_23326.png', '2', '2', '王者天下', '年度策略型手游大作《王者天下》2016焕新登场，带你开辟成王之路！为策略游戏用户量身打造的诚意之作，攻城略地，招贤纳士，称王称霸，坐拥江山美人！', '<span style=\"color:#666666;font-family:微软雅黑;font-size:14px;line-height:26px;background-color:#FFFFFF;\">年度策略型手游大作《王者天下》2016焕新登场，带你开辟成王之路！为策略游戏用户量身打造的诚意之作，攻城略地，招贤纳士，称王称霸，坐拥江山美人！ 五大必玩理由 一、实时国战争夺城池 二、纯正策略布阵艺术 三、满满福利登陆就送 四、战国名将酷炫大招 五、经典战役创意主线 战火已经点燃，成王之路就在脚下！进入《王者天下》，梦回春秋楚汉，打造属于你的一片千秋伟业！</span>', 'http://downali.game.uc.cn/wm/15/31/com.legu.king.uc.0920_8904127_12484153062a.apk', '1.0', '108', '7', '0', '0', '1474862020', '1474862020', '0');
INSERT INTO `qj_game` VALUES ('11', '12', '史诗奥德赛', null, '/public/uploadfile/image/20160926/20160926131757_12510.png', '2', '2', '史诗奥德赛', '全新魔幻3D策略手游《史诗：奥德赛》震撼来袭！ ☆欧美魔幻风的设计，众多耳熟能详的魔幻英雄纷至沓来，总能找到合你口味的菜。 ☆畅快淋漓的打斗，酷炫华丽的技能特效，让你肾上腺素极速分泌。', '<span style=\"color:#666666;font-family:微软雅黑;font-size:14px;line-height:26px;background-color:#FFFFFF;\">全新魔幻3D策略手游《史诗：奥德赛》震撼来袭！ ☆欧美魔幻风的设计，众多耳熟能详的魔幻英雄纷至沓来，总能找到合你口味的菜。 ☆畅快淋漓的打斗，酷炫华丽的技能特效，让你肾上腺素极速分泌。 ☆以北欧神话为原型，再现史诗级大战！ ☆丰富的战前策略配置，近千种英雄与兵种的搭配组合！ ☆巧用三位上阵英雄的技能搭配，利用风骚的走位技巧，挑选合理的时机，窥准破绽，集火破敌！</span>', 'http://downali.game.uc.cn/wm/2/2/aodesai_encrypt_signed_ucv3_8927234_14192622160a.apk', '1.0', '225', '0', '0', '0', '1474867078', '1474867078', '0');
INSERT INTO `qj_game` VALUES ('12', '12', '金牌德州扑克', null, '/public/uploadfile/image/20160926/20160926132024_44725.png', '2', '1', '金牌德州扑克', '真人美女德州扑克，赛事丰富，天天德州约战；同城交友，切磋牌技。受欢迎程度远高于梭哈、炸金花、斗地主等热门棋牌游戏！ 3D高清画质，打造真实赛场 高端用户，涵盖投资、金融、IT等 强大比赛系统，尽享竞技交流 熟人约局，自建私人房', '<span style=\"color:#666666;font-family:微软雅黑;font-size:14px;line-height:26px;background-color:#FFFFFF;\">真人美女德州扑克，赛事丰富，天天德州约战；同城交友，切磋牌技。受欢迎程度远高于梭哈、炸金花、斗地主等热门棋牌游戏！ 3D高清画质，打造真实赛场 高端用户，涵盖投资、金融、IT等 强大比赛系统，尽享竞技交流 熟人约局，自建私人房</span>', 'http://downali.game.uc.cn/wm/13/13/GoldenPoker_UC_1_9_6_8919469_1935584a78eb.apk', '1.9.6', '45', '1', '0', '0', '1474867263', '1474867263', '0');
INSERT INTO `qj_game` VALUES ('13', '12', '进击的海盗', null, '/public/uploadfile/image/20160926/20160926132347_36312.png', '4', '2', '进击的海盗', '全革新航海题材手游《进击的海盗》惊耀问世，浓郁的中世纪航海风、明烈的海盗主题特色，勾勒出一个栩栩如生的海盗纪元！大海图激战漫游、资源岛占领、港口争夺、商船劫掠，真王者制霸海洋。', '<span style=\"color:#666666;font-family:微软雅黑;font-size:14px;line-height:26px;background-color:#FFFFFF;\">全革新航海题材手游《进击的海盗》惊耀问世，浓郁的中世纪航海风、明烈的海盗主题特色，勾勒出一个栩栩如生的海盗纪元！大海图激战漫游、资源岛占领、港口争夺、商船劫掠，真王者制霸海洋。回合制策略战斗，震撼轰击特性，华丽战斗打击。甲板、火炮、船艏改造，战船外观提升，个性船长招募，打造专属豪华舰队。附属领地SLG玩法，丰富养成资源。组建联盟，军团夺宝，多人交互，热血畅快的游戏体验！绚烂启航，即刻进击为王吧！</span>', 'http://downali.game.uc.cn/wm/12/28/jjdhd_8311324_101523f91aa6.apk', '1.0.1', '153', '1', '0', '0', '1474867429', '1474867429', '0');
INSERT INTO `qj_game` VALUES ('14', '12', '摩登农场', null, '/public/uploadfile/image/20160926/20160926132919_15067.png', '3', '1', '', '摩登农场是国内首款45度视角的农场益智战斗类游戏；多样化的地图，各式各样的技能羊给你带来非同一般的乐趣，为了保护你的羊群赶紧行动吧！摩登农场震撼登陆！', '<span style=\"color:#666666;font-family:微软雅黑;font-size:14px;line-height:26px;background-color:#FFFFFF;\">摩登农场是国内首款45度视角的农场益智战斗类游戏；多样化的地图，各式各样的技能羊给你带来非同一般的乐趣，为了保护你的羊群赶紧行动吧！摩登农场震撼登陆！</span>', 'http://cdn.4g.play.cn/f/o/cpkg/wm/000/002/986/e4692168h2d90707/5097036_20011615.apk', '1.0', '27', '0', '0', '0', '1474867761', '1474867761', '0');
INSERT INTO `qj_game` VALUES ('15', '12', '全民削水果', null, '/public/uploadfile/image/20160926/20160926133116_82976.png', '3', '1', '全民削水果', '全民削水果是一款极易操作的休闲游戏，五彩缤纷的水果世界等你挑战！水果忍者的全新升级，秉承经典玩法的基础上完善装备和道具体系，狂热新元素鲜果盛宴，瞬间让你战力爆棚，更有神奇刺激的梦幻新场景等你解锁！疯狂切割！就是现在！赶紧叫上小伙伴一起享受水果新热潮吧！', '<span style=\"color:#666666;font-family:微软雅黑;font-size:14px;line-height:26px;background-color:#FFFFFF;\">全民削水果是一款极易操作的休闲游戏，五彩缤纷的水果世界等你挑战！水果忍者的全新升级，秉承经典玩法的基础上完善装备和道具体系，狂热新元素鲜果盛宴，瞬间让你战力爆棚，更有神奇刺激的梦幻新场景等你解锁！疯狂切割！就是现在！赶紧叫上小伙伴一起享受水果新热潮吧！</span>', 'http://cdn.4g.play.cn/f/o/cpkg/wm/000/002/986/2dfbc841h2d90830/5099142_20011615.apk', '1.0', '7', '3', '0', '0', '1474867877', '1474867877', '0');
INSERT INTO `qj_game` VALUES ('16', '12', '钓鱼时代', null, '/public/uploadfile/image/20160926/20160926133302_11713.png', '3', '1', '钓鱼时代', '《钓鱼时代》是一款巅峰级3D写实风格钓鱼手游大作，简洁明媚的画风、优美欢快的背景音乐、紧凑的音效、丰富的鱼类图鉴、多元的钓具、逼真的钓鱼感，游戏效果和体验让人惊叹！', '<span style=\"color:#666666;font-family:微软雅黑;font-size:14px;line-height:26px;background-color:#FFFFFF;\">《钓鱼时代》是一款巅峰级3D写实风格钓鱼手游大作，简洁明媚的画风、优美欢快的背景音乐、紧凑的音效、丰富的鱼类图鉴、多元的钓具、逼真的钓鱼感，游戏效果和体验让人惊叹！在核心玩法方面，争取把钓鱼乐趣做到极致，力求做到最简洁又不失耐玩性，玩家只需要通过一个按键，就可以在指尖上真正享受钓鱼的乐趣，完全停不下来。钓鱼，养鱼，出售，升级装备，收集图鉴，开拓钓场等养成系统仿佛让玩家完美的置身于的现实钓鱼生涯中！</span>', 'http://cdn.4g.play.cn/f/o/cpkg/wm/000/002/986/f0666dfeh2d907a6/5092865_20011615.apk', '1.0', '82', '1', '0', '0', '1474867983', '1474867983', '0');
INSERT INTO `qj_game` VALUES ('17', '12', '武神天下', null, '/public/uploadfile/image/20160926/20160926133453_10596.png', '8', '2', '武神天下', '领酷炫武将，结交三国武神，平定诸强之乱！ Q萌清新的角色，精致的场景，革新的策略回合制玩法，挂机赚钱自由交易，男神萌妹轻松交友，约你来玩！', '<span style=\"color:#666666;font-family:微软雅黑;font-size:14px;line-height:26px;background-color:#FFFFFF;\">领酷炫武将，结交三国武神，平定诸强之乱！ Q萌清新的角色，精致的场景，革新的策略回合制玩法，挂机赚钱自由交易，男神萌妹轻松交友，约你来玩！</span>', 'http://downali.game.uc.cn/wm/13/29/201609021wushensanguozhi0902_5_ucw_5963581_183245dc64b3.apk', '1.0.0.2', '163', '1', '0', '0', '1474868124', '1474868124', '0');
INSERT INTO `qj_game` VALUES ('18', '12', '帝之崛起', null, '/public/uploadfile/image/20160926/20160926133838_86000.png', '8', '2', '帝之崛起', '《帝之崛起》是一款以中国帝王为主要题材的3D角色扮演类手游巨作！玩家扮演炎黄传人的角色，透过募集历代帝王、名将及绝世美女来组成强大的远征军队，面对蚩尤的魔界军团勇敢抗战。', '<span style=\"color:#666666;font-family:微软雅黑;font-size:14px;line-height:26px;background-color:#FFFFFF;\">《帝之崛起》是一款以中国帝王为主要题材的3D角色扮演类手游巨作！玩家扮演炎黄传人的角色，透过募集历代帝王、名将及绝世美女来组成强大的远征军队，面对蚩尤的魔界军团勇敢抗战。在漫长的征途中，玩家经过不断征战的磨练以及适时培养壮大自己的队伍，必能击败其他挑战者，一统中华河山！快来拯救苍生、一统中华河山！</span>', 'http://downali.game.uc.cn/wm/9/9/PurpleSouls_ninegame_1.0.0_3542569_1750367a773f.apk', '1.0.0', '162', '3', '0', '0', '1474868319', '1474868319', '0');
INSERT INTO `qj_game` VALUES ('19', '12', '独步天下', null, '/public/uploadfile/image/20160927/20160927151355_50141.jpg', '1', '2', '独步天下', '《独步天下》是2013年91wan旗下倾力打造的一款RPG网页游戏巨作。将古典侠义精神与现代武侠故事相结合，新颖的职业技能体系，华丽的战斗特效，层出不穷的知名侠客，高潮迭起的剧情副本，将引领您进入一个丰富有趣的武侠世界！搜掠天下秘籍，执掌天下侠客，尽在您手！', '<span style=\"color:#636363;font-family:微软雅黑;font-size:14px;line-height:24px;\">《独步天下》是2013年91wan旗下倾力打造的一款RPG网页游戏巨作。将古典侠义精神与现代武侠故事相结合，新颖的职业技能体系，华丽的战斗特效，层出不穷的知名侠客，高潮迭起的剧情副本，将引领您进入一个丰富有趣的武侠世界！搜掠天下秘籍，执掌天下侠客，尽在您手！</span>', '', '', '0', '0', '0', '0', '1474960441', '1474960441', '0');
INSERT INTO `qj_game` VALUES ('20', '12', '天书世界', null, '/public/uploadfile/image/20160928/20160928095000_68916.jpg', '1', '2', '天书世界', '《天书世界》是一款由哥们网历时2年，全程自主研发的新三国诚意之作。游戏主打万人同屏抢地盘，独创“抢山寨占商铺、三国同花顺、逐鹿天下”等融合了ARPG和SLG的上百种多元化创新玩法。同时，游戏首创在线时长收益模式，为玩家打造了一个策略与血性交织，时间兑换金钱的全新三国！', '<span style=\"color:#636363;font-family:微软雅黑;font-size:14px;line-height:24px;\">《天书世界》是一款由哥们网历时2年，全程自主研发的新三国诚意之作。游戏主打万人同屏抢地盘，独创“抢山寨占商铺、三国同花顺、逐鹿天下”等融合了ARPG和SLG的上百种多元化创新玩法。同时，游戏首创在线时长收益模式，为玩家打造了一个策略与血性交织，时间兑换金钱的全新三国！</span>', '', '', '0', '0', '0', '0', '1475027471', '1475027471', '0');
INSERT INTO `qj_game` VALUES ('21', '12', '亚瑟神剑', null, '/public/uploadfile/image/20160928/20160928105523_48784.jpg', '1', '2', '亚瑟神剑', '《亚瑟神剑》YY游戏独家首发的一个以西方魔幻为题材的大型多人竞技即时制角色扮演类网页游戏。游戏融入轻操作立体化战斗，玩家可把握大招释放顺序，巧妙搭配实现打断、秒杀等战斗技巧。爽快的连击战斗，原画级的唯美场景，感人至深的精彩剧情，使玩家能够轻易融入到美妙奇幻的世界，寻找本真的快乐。', '<span style=\"color:#636363;font-family:微软雅黑;font-size:14px;line-height:24px;\">《亚瑟神剑》YY游戏独家首发的一个以西方魔幻为题材的大型多人竞技即时制角色扮演类网页游戏。游戏融入轻操作立体化战斗，玩家可把握大招释放顺序，巧妙搭配实现打断、秒杀等战斗技巧。爽快的连击战斗，原画级的唯美场景，感人至深的精彩剧情，使玩家能够轻易融入到美妙奇幻的世界，寻找本真的快乐。</span>', '', '', '0', '0', '0', '0', '1475031324', '1475031324', '0');
INSERT INTO `qj_game` VALUES ('22', '12', '将星诀', null, '/public/uploadfile/image/20160928/20160928105902_23186.jpg', '2', '2', '将星诀', '将星诀', '将星诀', '', '', '0', '0', '0', '0', '1475031544', '1475031544', '0');
INSERT INTO `qj_game` VALUES ('23', '12', '小小精灵', null, '/public/uploadfile/image/20160928/20160928110352_36196.jpg', '1', '2', '小小精灵', '小小精灵是嗨皮窝网络研发的一款以口袋妖怪为主题萌系RPG网页游戏。最好玩的宠物游戏，600多种宠物等你来捕捉。游戏中不仅有各种可爱强力的口袋妖怪们，还融入了各种全新要素，如全世界玩家的对抗、充满先进比赛的竞技场、独特且有趣的剧情挑战等等，重温宠物小精灵和口袋妖怪的童年回忆。', '<span style=\"color:#636363;font-family:微软雅黑;font-size:14px;line-height:24px;\"><span style=\"color:#636363;font-family:微软雅黑;font-size:14px;line-height:24px;\">小小精灵是嗨皮窝网络研发的一款以口袋妖怪为主题萌系RPG网页游戏。最好玩的宠物游戏，600多种宠物等你来捕捉。游戏中不仅有各种可爱强力的口袋妖怪们，还融入了各种全新要素，如全世界玩家的对抗、充满先进比赛的竞技场、独特且有趣的剧情挑战等等，重温宠物小精灵和口袋妖怪的童年回忆。</span></span>', '', '', '0', '0', '0', '0', '1475031861', '1475031861', '0');

-- ----------------------------
-- Table structure for `qj_gameplat`
-- ----------------------------
DROP TABLE IF EXISTS `qj_gameplat`;
CREATE TABLE `qj_gameplat` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `sort` smallint(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_gameplat
-- ----------------------------
INSERT INTO `qj_gameplat` VALUES ('1', '安卓', '0');
INSERT INTO `qj_gameplat` VALUES ('2', '电脑', '0');

-- ----------------------------
-- Table structure for `qj_gametype`
-- ----------------------------
DROP TABLE IF EXISTS `qj_gametype`;
CREATE TABLE `qj_gametype` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `sort` smallint(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_gametype
-- ----------------------------
INSERT INTO `qj_gametype` VALUES ('1', '角色扮演', '1');
INSERT INTO `qj_gametype` VALUES ('2', '塔防策略', '0');
INSERT INTO `qj_gametype` VALUES ('3', '益智休闲', '0');
INSERT INTO `qj_gametype` VALUES ('4', '模拟经营', '0');
INSERT INTO `qj_gametype` VALUES ('5', '格斗射击', '0');
INSERT INTO `qj_gametype` VALUES ('6', '动作冒险', '0');
INSERT INTO `qj_gametype` VALUES ('7', '体育竞速', '0');
INSERT INTO `qj_gametype` VALUES ('8', '回合对战', '0');
INSERT INTO `qj_gametype` VALUES ('9', '其它', '2');
INSERT INTO `qj_gametype` VALUES ('10', '棋牌', '0');

-- ----------------------------
-- Table structure for `qj_game_card`
-- ----------------------------
DROP TABLE IF EXISTS `qj_game_card`;
CREATE TABLE `qj_game_card` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `catid` int(10) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL COMMENT '缩略图',
  `game_id` int(10) NOT NULL,
  `type` int(10) NOT NULL,
  `content` text,
  `num` int(10) NOT NULL,
  `make_num` int(10) NOT NULL COMMENT '领取数量',
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `seo_description` varchar(255) DEFAULT NULL,
  `start_time` int(10) DEFAULT NULL,
  `end_time` int(10) DEFAULT NULL,
  `add_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='游戏卡号';

-- ----------------------------
-- Records of qj_game_card
-- ----------------------------
INSERT INTO `qj_game_card` VALUES ('9', '0', '摩登农场', '/public/uploadfile/image/20160624/20160624164103_17635.jpg', '14', '1', '第三方士大夫的广泛的官方回复', '4', '3', '', '', '', '0', '0', '1466756055');
INSERT INTO `qj_game_card` VALUES ('10', '0', '倩女幽魂新手卡', '/public/uploadfile/image/20160624/20160624170528_50517.png', '2', '1', '倩女幽魂游戏礼包内容<span>倩女幽魂游戏礼包内容</span><span>倩女幽魂游戏礼包内容</span><span>倩女幽魂游戏礼包内容</span><span>倩女幽魂游戏礼包内容</span>', '4', '3', '', '', '', '0', '0', '1466759546');
INSERT INTO `qj_game_card` VALUES ('11', '0', '放开那三国新手卡', '/public/uploadfile/image/20160625/20160625142258_40319.png', '3', '1', '', '6', '3', '', '', '', null, '0', '1466836400');
INSERT INTO `qj_game_card` VALUES ('12', '0', '谁是大英雄新手卡', '/public/uploadfile/image/20160625/20160625143544_12936.png', '4', '1', 'sdasdasdas', '1', '1', '', '', '', '0', '0', '1466836694');
INSERT INTO `qj_game_card` VALUES ('13', '0', '倩女幽魂豪华礼包', '', '2', '2', '', '3', '1', '', '', '', null, '0', '1471243438');
INSERT INTO `qj_game_card` VALUES ('14', '0', '逗逗猫', '', '6', '2', '枯干杨成武山东人分公司的风格上的丁枯顶起 闲得发慌', '1', '0', '', '', '', null, '0', '1474942337');
INSERT INTO `qj_game_card` VALUES ('15', '0', '摩登农场', '', '14', '1', '<ul class=\"content\" style=\"font-size:16px;color:#6A6A6A;font-family:微软雅黑, Arial, Helvetica, sans-serif;background-color:#FFFFFF;\">\r\n	<span style=\"color:#666666;font-family:微软雅黑;font-size:14px;line-height:26px;\">摩登农场是国内首款45度视角的农场益智战斗类游戏；多样化的地图，各式各样的技能羊给你带来非同一般的乐趣，为了保护你的羊群赶紧行动吧！摩登农场震撼登陆！</span>\r\n</ul>', '0', '0', '摩登农场', '摩登农场', '摩登农场', null, '0', '1474959346');
INSERT INTO `qj_game_card` VALUES ('16', '0', '独步天下', '', '19', '2', '<span style=\"color:#7A7A7A;font-family:微软雅黑;font-size:16px;line-height:27px;background-color:#FFFFFF;\">兽魂石3；征银令*5；1朵玫瑰；小喇叭</span>', '0', '0', '独步天下', '独步天下', '独步天下', null, '0', '1474960593');
INSERT INTO `qj_game_card` VALUES ('17', '0', '亚瑟神剑', '', '21', '1', '', '0', '0', '亚瑟神剑', '亚瑟神剑', '亚瑟神剑', null, '0', '1475043301');

-- ----------------------------
-- Table structure for `qj_group`
-- ----------------------------
DROP TABLE IF EXISTS `qj_group`;
CREATE TABLE `qj_group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_del` tinyint(1) NOT NULL,
  `orderid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_group
-- ----------------------------
INSERT INTO `qj_group` VALUES ('1', 'MANAGER', '0', '1');
INSERT INTO `qj_group` VALUES ('2', 'EDITOR', '0', '1');

-- ----------------------------
-- Table structure for `qj_group_access`
-- ----------------------------
DROP TABLE IF EXISTS `qj_group_access`;
CREATE TABLE `qj_group_access` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `groupid` int(10) NOT NULL,
  `actionid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3588 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_group_access
-- ----------------------------
INSERT INTO `qj_group_access` VALUES ('3555', '1', '197');
INSERT INTO `qj_group_access` VALUES ('3554', '1', '196');
INSERT INTO `qj_group_access` VALUES ('3553', '1', '195');
INSERT INTO `qj_group_access` VALUES ('3552', '1', '194');
INSERT INTO `qj_group_access` VALUES ('3551', '1', '193');
INSERT INTO `qj_group_access` VALUES ('3550', '1', '192');
INSERT INTO `qj_group_access` VALUES ('3549', '1', '191');
INSERT INTO `qj_group_access` VALUES ('3548', '1', '190');
INSERT INTO `qj_group_access` VALUES ('3547', '1', '189');
INSERT INTO `qj_group_access` VALUES ('3546', '1', '188');
INSERT INTO `qj_group_access` VALUES ('3545', '1', '187');
INSERT INTO `qj_group_access` VALUES ('3544', '1', '186');
INSERT INTO `qj_group_access` VALUES ('3543', '1', '22');
INSERT INTO `qj_group_access` VALUES ('3542', '1', '21');
INSERT INTO `qj_group_access` VALUES ('3541', '1', '20');
INSERT INTO `qj_group_access` VALUES ('3540', '1', '19');
INSERT INTO `qj_group_access` VALUES ('3539', '1', '85');
INSERT INTO `qj_group_access` VALUES ('3538', '1', '86');
INSERT INTO `qj_group_access` VALUES ('3537', '1', '84');
INSERT INTO `qj_group_access` VALUES ('3536', '1', '182');
INSERT INTO `qj_group_access` VALUES ('3535', '1', '18');
INSERT INTO `qj_group_access` VALUES ('3534', '1', '17');
INSERT INTO `qj_group_access` VALUES ('3533', '1', '16');
INSERT INTO `qj_group_access` VALUES ('3532', '1', '15');
INSERT INTO `qj_group_access` VALUES ('3531', '1', '10');
INSERT INTO `qj_group_access` VALUES ('3530', '1', '9');
INSERT INTO `qj_group_access` VALUES ('3529', '1', '8');
INSERT INTO `qj_group_access` VALUES ('3528', '1', '6');
INSERT INTO `qj_group_access` VALUES ('3527', '1', '5');
INSERT INTO `qj_group_access` VALUES ('3526', '1', '4');
INSERT INTO `qj_group_access` VALUES ('3525', '1', '3');
INSERT INTO `qj_group_access` VALUES ('3524', '1', '96');
INSERT INTO `qj_group_access` VALUES ('3523', '1', '95');
INSERT INTO `qj_group_access` VALUES ('3522', '1', '94');
INSERT INTO `qj_group_access` VALUES ('3521', '1', '93');
INSERT INTO `qj_group_access` VALUES ('3520', '1', '92');
INSERT INTO `qj_group_access` VALUES ('3519', '1', '91');
INSERT INTO `qj_group_access` VALUES ('3167', '5', '12');
INSERT INTO `qj_group_access` VALUES ('3168', '5', '13');
INSERT INTO `qj_group_access` VALUES ('3169', '5', '1');
INSERT INTO `qj_group_access` VALUES ('3170', '5', '7');
INSERT INTO `qj_group_access` VALUES ('3171', '5', '82');
INSERT INTO `qj_group_access` VALUES ('3172', '5', '83');
INSERT INTO `qj_group_access` VALUES ('3518', '1', '89');
INSERT INTO `qj_group_access` VALUES ('3517', '1', '90');
INSERT INTO `qj_group_access` VALUES ('3516', '1', '97');
INSERT INTO `qj_group_access` VALUES ('3515', '1', '87');
INSERT INTO `qj_group_access` VALUES ('3514', '1', '88');
INSERT INTO `qj_group_access` VALUES ('3513', '1', '181');
INSERT INTO `qj_group_access` VALUES ('3512', '1', '14');
INSERT INTO `qj_group_access` VALUES ('3511', '1', '13');
INSERT INTO `qj_group_access` VALUES ('3510', '1', '12');
INSERT INTO `qj_group_access` VALUES ('3509', '1', '11');
INSERT INTO `qj_group_access` VALUES ('3508', '1', '83');
INSERT INTO `qj_group_access` VALUES ('3507', '1', '82');
INSERT INTO `qj_group_access` VALUES ('3506', '1', '7');
INSERT INTO `qj_group_access` VALUES ('3505', '1', '1');
INSERT INTO `qj_group_access` VALUES ('3504', '1', '185');
INSERT INTO `qj_group_access` VALUES ('3503', '1', '184');
INSERT INTO `qj_group_access` VALUES ('3502', '1', '183');
INSERT INTO `qj_group_access` VALUES ('3556', '1', '198');
INSERT INTO `qj_group_access` VALUES ('3557', '1', '199');
INSERT INTO `qj_group_access` VALUES ('3558', '1', '200');
INSERT INTO `qj_group_access` VALUES ('3559', '1', '201');
INSERT INTO `qj_group_access` VALUES ('3560', '1', '202');
INSERT INTO `qj_group_access` VALUES ('3561', '1', '203');
INSERT INTO `qj_group_access` VALUES ('3562', '1', '204');
INSERT INTO `qj_group_access` VALUES ('3563', '1', '205');
INSERT INTO `qj_group_access` VALUES ('3564', '1', '206');
INSERT INTO `qj_group_access` VALUES ('3565', '1', '207');
INSERT INTO `qj_group_access` VALUES ('3566', '1', '208');
INSERT INTO `qj_group_access` VALUES ('3567', '1', '209');
INSERT INTO `qj_group_access` VALUES ('3568', '1', '210');
INSERT INTO `qj_group_access` VALUES ('3569', '1', '211');
INSERT INTO `qj_group_access` VALUES ('3570', '1', '212');
INSERT INTO `qj_group_access` VALUES ('3571', '1', '213');
INSERT INTO `qj_group_access` VALUES ('3572', '1', '214');
INSERT INTO `qj_group_access` VALUES ('3573', '1', '215');
INSERT INTO `qj_group_access` VALUES ('3574', '1', '216');
INSERT INTO `qj_group_access` VALUES ('3575', '1', '217');
INSERT INTO `qj_group_access` VALUES ('3576', '1', '218');
INSERT INTO `qj_group_access` VALUES ('3577', '1', '219');
INSERT INTO `qj_group_access` VALUES ('3578', '1', '220');
INSERT INTO `qj_group_access` VALUES ('3579', '1', '221');
INSERT INTO `qj_group_access` VALUES ('3580', '1', '222');
INSERT INTO `qj_group_access` VALUES ('3581', '1', '223');
INSERT INTO `qj_group_access` VALUES ('3582', '1', '224');
INSERT INTO `qj_group_access` VALUES ('3583', '1', '225');
INSERT INTO `qj_group_access` VALUES ('3584', '1', '226');
INSERT INTO `qj_group_access` VALUES ('3585', '1', '227');
INSERT INTO `qj_group_access` VALUES ('3586', '1', '228');
INSERT INTO `qj_group_access` VALUES ('3587', '1', '229');

-- ----------------------------
-- Table structure for `qj_kaifu`
-- ----------------------------
DROP TABLE IF EXISTS `qj_kaifu`;
CREATE TABLE `qj_kaifu` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `game_id` int(10) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `url` varchar(255) NOT NULL,
  `open_time` int(10) NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_kaifu
-- ----------------------------
INSERT INTO `qj_kaifu` VALUES ('1', '测试', '18', '0', 'http://downali.game.uc.cn/wm/9/9/PurpleSouls_ninegame_1.0.0_3542569_1750367a773f.apk', '1123200', '0');
INSERT INTO `qj_kaifu` VALUES ('2', '双线365服', '19', '0', 'http://www.kaifu.com/gamewebsite-44966.html', '0', '0');
INSERT INTO `qj_kaifu` VALUES ('3', '双线256区', '20', '1', 'http://www.kaifu.com/gamewebsite-61867.html', '1475024400', '1475030191');
INSERT INTO `qj_kaifu` VALUES ('4', '双线3服', '21', '0', 'http://www.kaifu.com/gamewebsite-80292.html', '1475042400', '1475031369');
INSERT INTO `qj_kaifu` VALUES ('5', '全部区服', '22', '0', 'http://www.quegame.com/new_home.php?site=0', '1473314400', '1475031632');
INSERT INTO `qj_kaifu` VALUES ('6', '双线32服', '23', '0', 'http://xxjl.73994.com/', '1475028000', '1475031922');

-- ----------------------------
-- Table structure for `qj_menus`
-- ----------------------------
DROP TABLE IF EXISTS `qj_menus`;
CREATE TABLE `qj_menus` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `orderid` int(10) NOT NULL,
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  `is_del` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_menus
-- ----------------------------
INSERT INTO `qj_menus` VALUES ('1', 'ADMIN_MANAGE', '1', '1', '0');
INSERT INTO `qj_menus` VALUES ('2', 'MENU_CONF', '2', '1', '0');
INSERT INTO `qj_menus` VALUES ('12', 'SYSTEM_CONF', '3', '1', '0');
INSERT INTO `qj_menus` VALUES ('15', 'TEST', '4', '1', '1');
INSERT INTO `qj_menus` VALUES ('16', 'WEB_MANAGER', '4', '1', '0');
INSERT INTO `qj_menus` VALUES ('17', 'CONTENT_MANAGER', '5', '1', '0');
INSERT INTO `qj_menus` VALUES ('18', 'CUSTOM_MANAGE', '6', '1', '0');
INSERT INTO `qj_menus` VALUES ('19', 'FORM_MANAGE', '7', '1', '0');

-- ----------------------------
-- Table structure for `qj_modules`
-- ----------------------------
DROP TABLE IF EXISTS `qj_modules`;
CREATE TABLE `qj_modules` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `orderid` int(10) NOT NULL,
  `menuid` int(10) NOT NULL,
  `pid` int(10) NOT NULL,
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  `is_del` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=118 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_modules
-- ----------------------------
INSERT INTO `qj_modules` VALUES ('1', 'ADMIN_MANAGE', '', '1', '1', '0', '1', '0');
INSERT INTO `qj_modules` VALUES ('3', 'ADMIN_GROUP', '', '2', '1', '0', '1', '0');
INSERT INTO `qj_modules` VALUES ('4', 'ADMIN_MANAGE', 'Admin', '1', '1', '1', '1', '0');
INSERT INTO `qj_modules` VALUES ('48', 'GROUP_TRASH', 'GroupTrash', '3', '1', '3', '1', '0');
INSERT INTO `qj_modules` VALUES ('6', 'ADMIN_GROUP', 'Group', '2', '1', '3', '1', '0');
INSERT INTO `qj_modules` VALUES ('7', 'ADMIN_TRASH', 'AdminTrash', '2', '1', '1', '1', '0');
INSERT INTO `qj_modules` VALUES ('9', 'MENU_MANAGE', '', '3', '2', '0', '1', '0');
INSERT INTO `qj_modules` VALUES ('10', 'MENU_MANAGE', 'Menu', '1', '2', '9', '1', '0');
INSERT INTO `qj_modules` VALUES ('11', 'MODULE_MANAGE', 'Module', '2', '2', '9', '1', '0');
INSERT INTO `qj_modules` VALUES ('13', 'ACTION_MANAGE', 'Action', '3', '2', '9', '1', '0');
INSERT INTO `qj_modules` VALUES ('49', 'SYSTEM_CONF', '', '9', '12', '0', '1', '0');
INSERT INTO `qj_modules` VALUES ('50', 'SYSTEM_UPLOAD', 'System', '1', '12', '49', '1', '0');
INSERT INTO `qj_modules` VALUES ('51', 'EMAIL_API', 'Email', '1', '12', '49', '1', '0');
INSERT INTO `qj_modules` VALUES ('52', 'SMS_API', 'Sms', '1', '12', '49', '1', '0');
INSERT INTO `qj_modules` VALUES ('83', 'TEST', '', '4', '2', '0', '1', '1');
INSERT INTO `qj_modules` VALUES ('84', 'SITE_CONF', 'Site', '0', '16', '85', '1', '0');
INSERT INTO `qj_modules` VALUES ('85', 'WEB_SYSTEM', '', '0', '16', '0', '1', '0');
INSERT INTO `qj_modules` VALUES ('86', 'NAV_MANAGER', 'Navs', '0', '16', '85', '1', '0');
INSERT INTO `qj_modules` VALUES ('87', 'CATEGORY_MANAGE', 'Category', '0', '17', '88', '1', '0');
INSERT INTO `qj_modules` VALUES ('88', 'CATE_MANAGER', '', '0', '17', '0', '1', '0');
INSERT INTO `qj_modules` VALUES ('89', 'NEWS_LIST', 'News', '0', '17', '98', '1', '0');
INSERT INTO `qj_modules` VALUES ('90', 'PRODUCT_MANAGER', '', '0', '17', '0', '1', '1');
INSERT INTO `qj_modules` VALUES ('91', 'PRODUCT_CATEGORY', 'ProductsCategory', '0', '17', '90', '1', '1');
INSERT INTO `qj_modules` VALUES ('92', 'PRODUCTS', 'Products', '0', '17', '90', '1', '1');
INSERT INTO `qj_modules` VALUES ('93', 'PAGE_LIST', 'Page', '0', '17', '98', '1', '0');
INSERT INTO `qj_modules` VALUES ('94', 'ADMIN_LOG', 'AdminLog', '20', '16', '85', '1', '0');
INSERT INTO `qj_modules` VALUES ('95', 'SILDE_MANAGER', 'Silde', '0', '16', '85', '1', '0');
INSERT INTO `qj_modules` VALUES ('96', 'LINK_MANAGE', 'Link', '0', '16', '85', '1', '0');
INSERT INTO `qj_modules` VALUES ('97', 'ORDER_LIST', 'Order', '0', '19', '99', '1', '0');
INSERT INTO `qj_modules` VALUES ('98', 'CONTENT_MANAGE', '', '0', '17', '0', '1', '0');
INSERT INTO `qj_modules` VALUES ('99', 'FORM_MANAGE', '', '0', '19', '0', '1', '0');
INSERT INTO `qj_modules` VALUES ('100', 'CUSTOM_MANAGE', '', '0', '18', '0', '1', '0');
INSERT INTO `qj_modules` VALUES ('101', 'CUSTOM_LIST', 'Custom', '0', '18', '100', '1', '0');
INSERT INTO `qj_modules` VALUES ('102', 'PICTURE_MANAGE', '', '4', '17', '0', '1', '1');
INSERT INTO `qj_modules` VALUES ('103', 'PICTURECATEGORY', 'PictureCategory', '0', '17', '102', '1', '1');
INSERT INTO `qj_modules` VALUES ('104', 'PICTURE_LIST', 'Picture', '0', '17', '102', '1', '1');
INSERT INTO `qj_modules` VALUES ('105', 'CARD_ZHONG', '', '3', '17', '0', '1', '0');
INSERT INTO `qj_modules` VALUES ('106', 'CARD_TYPE', 'CardType', '0', '17', '105', '1', '0');
INSERT INTO `qj_modules` VALUES ('107', 'GAME_CARD', 'GameCard', '0', '17', '98', '1', '0');
INSERT INTO `qj_modules` VALUES ('108', 'GAME_MANAGER', 'Game', '0', '17', '98', '1', '0');
INSERT INTO `qj_modules` VALUES ('109', 'DOWNLOAD_MANAGER', 'Download', '-1', '17', '105', '1', '1');
INSERT INTO `qj_modules` VALUES ('110', 'ACCOUNT_CONF', 'Account', '4', '12', '49', '1', '1');
INSERT INTO `qj_modules` VALUES ('111', 'GAME_TYPE', 'GameType', '3', '17', '105', '1', '0');
INSERT INTO `qj_modules` VALUES ('112', 'PUSH_TYPE', 'PushType', '4', '17', '105', '1', '0');
INSERT INTO `qj_modules` VALUES ('113', 'Network', 'Network', '5', '17', '105', '1', '0');
INSERT INTO `qj_modules` VALUES ('114', 'KAIFU_MANAGE', 'KaiFu', '6', '17', '98', '1', '0');
INSERT INTO `qj_modules` VALUES ('115', 'PUSH_MANAGE', 'Push', '6', '17', '98', '1', '0');
INSERT INTO `qj_modules` VALUES ('116', 'ADVTYPE', 'Advtype', '6', '17', '105', '1', '0');
INSERT INTO `qj_modules` VALUES ('117', 'ADV', 'Adv', '6', '17', '98', '1', '0');

-- ----------------------------
-- Table structure for `qj_navs`
-- ----------------------------
DROP TABLE IF EXISTS `qj_navs`;
CREATE TABLE `qj_navs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '名称',
  `module` varchar(255) DEFAULT NULL,
  `guide` varchar(255) NOT NULL COMMENT '链接',
  `type` tinyint(1) NOT NULL COMMENT '类型（0 顶部导航，1底部导航）',
  `is_blank` tinyint(1) NOT NULL COMMENT '是否新页面打开',
  `is_show` tinyint(1) NOT NULL COMMENT '是否显示',
  `orderid` int(10) NOT NULL COMMENT '排序（数值小的在前面）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_navs
-- ----------------------------
INSERT INTO `qj_navs` VALUES ('1', '游戏大全', 'game', '0', '0', '0', '1', '0');
INSERT INTO `qj_navs` VALUES ('14', '游戏资讯', 'news', '13', '0', '0', '1', '5');
INSERT INTO `qj_navs` VALUES ('15', '游戏功略', 'news', '14', '0', '0', '1', '6');
INSERT INTO `qj_navs` VALUES ('16', '开服开测', 'kaifu', '0', '0', '0', '1', '4');
INSERT INTO `qj_navs` VALUES ('17', '游戏礼包', 'gamecard', '0', '0', '0', '1', '0');
INSERT INTO `qj_navs` VALUES ('18', '手游排行', 'page', '7', '0', '0', '1', '0');
INSERT INTO `qj_navs` VALUES ('19', '关于我们', 'page', '1', '0', '0', '1', '8');
INSERT INTO `qj_navs` VALUES ('20', '联系我们', 'page', '5', '0', '0', '1', '8');
INSERT INTO `qj_navs` VALUES ('21', '关于我们', 'page', '1', '1', '1', '1', '0');
INSERT INTO `qj_navs` VALUES ('22', '联系我们', 'page', '5', '1', '1', '1', '0');
INSERT INTO `qj_navs` VALUES ('23', '加入我们', 'page', '4', '1', '1', '1', '0');

-- ----------------------------
-- Table structure for `qj_network`
-- ----------------------------
DROP TABLE IF EXISTS `qj_network`;
CREATE TABLE `qj_network` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `sort` smallint(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_network
-- ----------------------------
INSERT INTO `qj_network` VALUES ('1', '单机', '0');
INSERT INTO `qj_network` VALUES ('2', '网游', '0');
INSERT INTO `qj_network` VALUES ('3', '破解版', '0');

-- ----------------------------
-- Table structure for `qj_news`
-- ----------------------------
DROP TABLE IF EXISTS `qj_news`;
CREATE TABLE `qj_news` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `catid` int(10) NOT NULL COMMENT '类型id',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `thumb` varchar(255) DEFAULT NULL COMMENT '缩略图',
  `tags` varchar(255) DEFAULT NULL COMMENT '标签',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键词',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `seo_title` varchar(255) DEFAULT NULL COMMENT 'seo标题',
  `seo_keywords` varchar(255) DEFAULT NULL COMMENT 'seo关键词',
  `seo_description` varchar(255) DEFAULT NULL COMMENT 'seo描述',
  `content` text COMMENT '内容',
  `game_id` int(10) NOT NULL,
  `clicks` int(11) NOT NULL COMMENT '点击数',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `is_show` tinyint(1) NOT NULL COMMENT '是否显示',
  `is_del` tinyint(1) NOT NULL COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_news
-- ----------------------------
INSERT INTO `qj_news` VALUES ('30', '13', '六龙争霸3D原石怎么获取', '/public/uploadfile/image/20160922/20160922102000_13062.jpg', '', '六龙争霸 六龙争霸3D', '在手游六龙争霸3D中，原石可以用来装备进阶，是很重要的材料哦，那么六龙争霸3D原石怎么获取?下面就让小编来为大家详细的介绍下原石如何获取。', '六龙争霸3D原石怎么获取', '六龙争霸 六龙争霸3D', '在手游六龙争霸3D中，原石可以用来装备进阶，是很重要的材料哦，那么六龙争霸3D原石怎么获取?下面就让小编来为大家详细的介绍下原石如何获取。', '<p>\r\n	　　在手游六龙争霸3D中，原石可以用来装备进阶，是很重要的材料哦，那么六龙争霸3D原石怎么获取?下面就让小编来为大家详细的介绍下原石如何获取。\r\n</p>\r\n<p>\r\n	　　原石获得途径\r\n</p>\r\n<p>\r\n	　　1、在游戏中有一个皇陵密室的玩法，在这里小伙伴们可以打到大量的原石，今天要勤打副本哦。\r\n</p>\r\n<p>\r\n	　　2、抽，就是通过钱庄抽，有一定的概率可以抽到，小编不建议大家在这方面抽太多，因为获得的概率不高，所以通过抽获得不划算。\r\n</p>\r\n<p>\r\n	　　3、购买，就是可以通过游戏玩家之间来进行购买，不过这个貌似卖的人会比较少要培养装备大家都需要用到原石。\r\n</p>\r\n<p>\r\n	　　以上就是关于六龙争霸3D原石怎么获取的攻略了，想要获取原石的玩家们可以通过上面介绍的那些途径去获取哦，想要了解更多关于《六龙争霸3D》的攻略就请关注说玩网。\r\n</p>', '0', '12', '1474510802', '1', '0');
INSERT INTO `qj_news` VALUES ('31', '13', '全民超神偷心魅魔怎么提升战力', '/public/uploadfile/image/20160922/20160922102118_10829.png', '', '偷心魅魔 偷心魅魔提升战力', '在手游全民超神里，偷心魅魔是一个有输出有控制的法师英雄，是一个很值得大家去培养的英雄，那么全民超神偷心魅魔怎么提升战力?下面就由小编来为大家详细的介绍下吧。', '全民超神偷心魅魔怎么提升战力', '偷心魅魔 偷心魅魔提升战力', '在手游全民超神里，偷心魅魔是一个有输出有控制的法师英雄，是一个很值得大家去培养的英雄，那么全民超神偷心魅魔怎么提升战力?下面就由小编来为大家详细的介绍下吧。', '<p>\r\n	　　在手游全民超神里，偷心魅魔是一个有输出有控制的法师英雄，是一个很值得大家去培养的英雄，那么全民超神偷心魅魔怎么提升战力?下面就由小编来为大家详细的介绍下吧。\r\n</p>\r\n<p style=\"text-align:center;\">\r\n	<img src=\"/public/uploadfile/image/20160922/20160922102142_60089.png\" alt=\"\" />\r\n</p>\r\n<p>\r\n	　　首先是英雄获得途径，偷心魅魔可以每天在竞技商店通过竞技币购买，只要每天坚持打对战，坚持购买，用不了几天就能收集到。\r\n</p>\r\n<p>\r\n	　　影响英雄战力的几个方面，分别是英雄等级，英雄阶级以及英雄星等。等级和星等都是越高战力越强，而阶级由低到高分别是白色，绿色，绿1，蓝色，蓝1，蓝2，紫色及最高的紫1。\r\n</p>\r\n<p>\r\n	　　等级方面，只要参加闯关模式，英雄就可以获得经验升级，或者使用经验药可以直接升级。经验药分为三种不同等级，分别为经验药水，经验奶酪及经验汉堡，不同的经验药提升的经验也不同，分别为60，300及1500。不过效果都是一样的。\r\n</p>\r\n<p>\r\n	　　阶级方面，英雄进阶需要卷轴，魅魔属于法师，需要不同等级的湛蓝卷轴。升阶卷轴可以通过刷困难闯关关卡随机获得，只要小伙伴们自己努力，升阶到紫1不是梦。\r\n</p>\r\n<p>\r\n	　　最后一个就是英雄的星等，魅魔初始为2星，需要通过灵魂石不断升星。每个星等之间升级需要5次，2星生3星每次需要10个灵魂石，总共需要50个灵魂石;3星升4星每次需要25个灵魂石，总计需要125个灵魂石;4星升5星每次则是需要50个灵魂石，总计需要250个灵魂石。只要小伙伴们每天坚持打对战，换取灵魂石，升级到5星魅魔不是梦。\r\n</p>\r\n<p>\r\n	　　以上就是关于全民超神偷心魅魔怎么提升战力的攻略，喜欢玩偷心魅魔这个英雄的玩家们一定要注意提升偷心魅魔的战力哦。\r\n</p>', '0', '1', '1474510905', '1', '0');
INSERT INTO `qj_news` VALUES ('32', '13', '全民枪战争霸币怎么获取', '/public/uploadfile/image/20160922/20160922102210_58493.jpg', '', '手游全民枪战 枪战争霸币', '在手游全民枪战中,争霸币既可以换取强化点，也能够换取风骑碎片哦，那么全民枪战争霸币怎么获取呢？下面就跟小编一起来看看吧。', '全民枪战争霸币怎么获取', '手游全民枪战 枪战争霸币', '在手游全民枪战中,争霸币既可以换取强化点，也能够换取风骑碎片哦，那么全民枪战争霸币怎么获取呢？下面就跟小编一起来看看吧。', '<p>\r\n	　　在手游全民枪战中,争霸币既可以换取强化点，也能够换取风骑碎片哦，那么全民枪战争霸币怎么获取呢?下面就跟小编一起来看看吧。\r\n</p>\r\n<p style=\"text-align:center;\">\r\n	<img src=\"/public/uploadfile/image/20160922/20160922102338_56095.jpg\" alt=\"\" />\r\n</p>\r\n<p>\r\n	　　战队争霸只要获得胜利的一方就可以获得争霸币，那么玩家攒够一定的争霸币之后，就可以直接在战队争霸系统中使用争霸币兑换一些限量版的武器或是强化点，争霸币中的物品每天24:00会进行刷新一次，找不到你喜欢的东西就多积攒一些分数，因为在争霸币兑换中还有一些永久的武器哦。\r\n</p>\r\n<p>\r\n	　　以上就是关于全民枪战争霸币怎么获取的攻略了，争霸币的用途可是有很多的哦，大家一定要多多获取争霸币哦。\r\n</p>', '0', '1', '1474511020', '1', '0');
INSERT INTO `qj_news` VALUES ('33', '13', '英雄战迹赵云怎么样 赵云天赋怎么加点', '/public/uploadfile/image/20160922/20160922102424_30964.jpg', '', '英雄战迹 英雄战迹赵云', '在竞技手游英雄战迹中，赵云是一个战士突进型英雄，那么英雄战迹赵云怎么样?赵云厉不厉害呢?赵云天赋怎么加点呢?下面就由小编来为大家一一介绍下吧。', '英雄战迹赵云怎么样 赵云天赋怎么加点', '英雄战迹 英雄战迹赵云', '在竞技手游英雄战迹中，赵云是一个战士突进型英雄，那么英雄战迹赵云怎么样?赵云厉不厉害呢?赵云天赋怎么加点呢?下面就由小编来为大家一一介绍下吧。', '<p>\r\n	　　在竞技手游英雄战迹中，赵云是一个战士突进型英雄，那么英雄战迹赵云怎么样?赵云厉不厉害呢?赵云天赋怎么加点呢?下面就由小编来为大家一一介绍下吧。\r\n</p>\r\n<p style=\"text-align:center;\">\r\n	<img src=\"/public/uploadfile/image/20160922/20160922102523_41014.jpg\" alt=\"\" />\r\n</p>\r\n<p>\r\n	　　【赵云技能】\r\n</p>\r\n<p>\r\n	　　龙鸣——被动：每损失一定最大生命值获得额外免伤效果\r\n</p>\r\n<p>\r\n	　　天翔之龙——赵云跃向空中，向地面目标发动雷霆一击，对范围内敌人造成理伤害并将其短暂击飞\r\n</p>\r\n<p>\r\n	　　破云之龙——赵云快速刺出龙枪，对前方范围内敌人造成高额物理伤害\r\n</p>\r\n<p>\r\n	　　惊雷之龙——赵云执枪冲锋陷阵，对前方直线路径敌人造成物理伤害\r\n</p>\r\n<p>\r\n	　　【赵云天赋】\r\n</p>\r\n<p>\r\n	　　天赋加点是：战斗体质(每5秒回复最大生命2%)、高原血统(受到伤害回复最大生命3%)、狂野血脉(攻速提高20%、20%造成1.5倍伤害)、龙王神力(生命值、输出、免伤均提高10%)。\r\n</p>\r\n<p>\r\n	　　该天赋的加点中战斗体质和高原血统可以弥补赵云血量不高，战场上如被敌人集火攻击，由于受伤较重无法持续输出的短板，这两个天赋让赵云的生存能力大大提高;狂野血脉让赵云的攻速和暴击率提高，可配合赵云的暴击攻速流符文对敌方造成高额伤害;龙王神力可让赵云全属性均得以提高，让他的生存能力和输出更强。\r\n</p>\r\n<p>\r\n	　　【总结】\r\n</p>\r\n<p>\r\n	　　赵云的身板来说还是可以的，并且有切入战斗的技能，在支援或是先手都有不错的效果。并且，被动也为赵云提供了抗压的资本。配合高爆发的刺客，能够打出成吨的伤害。\r\n</p>\r\n<p>\r\n	　　以上就是关于英雄战迹赵云怎么样?赵云天赋怎么加点的攻略了，天赋加点果断要加暴击和攻速，赵云这个英雄还是很好很厉害的，是很值得大家去玩的哦。\r\n</p>', '0', '3', '1474511125', '1', '0');
INSERT INTO `qj_news` VALUES ('34', '13', '梦幻西游手游龙宫带什么特技好 龙宫怎么加点', '/public/uploadfile/image/20160922/20160922102550_29121.jpg', '', '梦幻西游 梦幻西游手游 梦幻西游龙宫带', '在梦幻西游手游中，龙宫拥有强大的群体输出，那么梦幻西游手游龙宫带什么特技好?龙宫怎么加点呢?下面就由小编来为大家详细的介绍下。', '梦幻西游手游龙宫带什么特技好 龙宫怎么加点', '梦幻西游 梦幻西游手游 梦幻西游龙宫带', '在梦幻西游手游中，龙宫拥有强大的群体输出，那么梦幻西游手游龙宫带什么特技好?龙宫怎么加点呢?下面就由小编来为大家详细的介绍下。', '<p>\r\n	　　在梦幻西游手游中，龙宫拥有强大的群体输出，那么梦幻西游手游龙宫带什么特技好?龙宫怎么加点呢?下面就由小编来为大家详细的介绍下。\r\n</p>\r\n<p style=\"text-align:center;\">\r\n	<img src=\"/public/uploadfile/image/20160922/20160922102704_14660.jpg\" alt=\"\" />\r\n</p>\r\n<p>\r\n	　　【特技选择】\r\n</p>\r\n<p>\r\n	　　想要追求高输出，你就不得不放弃特技，而系统默认的龙宫特技也是一片空白。但如果你非要给自己安排一个特技，那么不妨在衣服上放一个慈航普度特技——通常龙宫的低速不适合携带特技，但这一特技让龙宫使用能够保证其他团队成员的出手机会。往往要比其他成员使用更有机会翻盘。\r\n</p>\r\n<p>\r\n	　　【加点建议】\r\n</p>\r\n<p>\r\n	　　龙宫默认的加点方案已经非常适合目前主流的加点方案。但你还是要注意以下几点：\r\n</p>\r\n<p>\r\n	　　调整你的宠物加点，提升其生存能力，并能配合你的速度。\r\n</p>\r\n<p>\r\n	　　利用阵法的位置来进一步降低你的速度，确保自己是全场最低速单位，甚至如果有必要，可以考虑脱鞋子。\r\n</p>\r\n<p>\r\n	　　以上就是关于梦幻西游手游龙宫带什么特技好的攻略了，龙宫带慈航普度这个特技还是很不错的哦，大家可以去试试哦。\r\n</p>', '0', '2', '1474511227', '1', '0');
INSERT INTO `qj_news` VALUES ('35', '13', '全民突击战甲怎么搭配', '/public/uploadfile/image/20160922/20160922102728_10101.jpg', '', '全民突击 全民突击战甲', '在全民突击里，战甲可以提升我们的生存能力，不同的玩法所需要的战甲也是不同，小编就在这里为大家详细的介绍下全民突击战甲怎么搭配?大家可一定要来看看哦。', '全民突击战甲怎么搭配', '全民突击 全民突击战甲', '在全民突击里，战甲可以提升我们的生存能力，不同的玩法所需要的战甲也是不同，小编就在这里为大家详细的介绍下全民突击战甲怎么搭配?大家可一定要来看看哦。', '<p>\r\n	　　在全民突击里，战甲可以提升我们的生存能力，不同的玩法所需要的战甲也是不同，小编就在这里为大家详细的介绍下全民突击战甲怎么搭配?大家可一定要来看看哦。\r\n</p>\r\n<p style=\"text-align:center;\">\r\n	<img src=\"/public/uploadfile/image/20160922/20160922102839_10359.jpg\" alt=\"\" />\r\n</p>\r\n<p>\r\n	　　【道具流】\r\n</p>\r\n<p>\r\n	　　战甲推荐：火箭狂徒+爆破斥候装甲\r\n</p>\r\n<p>\r\n	　　为什么会这么选择呢?因为搭配上这套战甲的话，从某些程度上来讲，根本就不用和敌方进行正面交锋，只需要用道具就可以消耗对面的血量，然后用油桶和机枪去收拾那些残血的敌人!\r\n</p>\r\n<p>\r\n	　　【炮狙流】\r\n</p>\r\n<p>\r\n	　　战甲推荐：手雷狂徒头盔+防暴装甲\r\n</p>\r\n<p>\r\n	　　本身狙击枪的威力就比较大，在跑动的时候命中的个1-2枪，再利用手雷和RPG等道具就可以收拾剩下的敌人了，这个道具可以帮助我们减少反复开镜和收镜的时间，提前的锁定目标，所以说防暴装甲是很适合的!\r\n</p>\r\n<p>\r\n	　　【AK47点射流】\r\n</p>\r\n<p>\r\n	　　战甲推荐：防狙击枪头盔+防弹装甲\r\n</p>\r\n<p>\r\n	　　使用AK47的玩家需要的是灵活的步伐，最大的威胁就是炮狙党了，所以说我们最好是选择可以防御狙击枪的战甲，当然这是面对路人的情况，依照局势来选择就对了!\r\n</p>\r\n<p>\r\n	　　【雷明登870流】\r\n</p>\r\n<p>\r\n	　　战甲推荐：防突击步枪头盔 + 防弹装甲\r\n</p>\r\n<p>\r\n	　　870流与跑酷点射流是天敌，870开枪速度慢，突击步枪前几枪射速和进驻度刚好克制它。反过来870高伤害短时间能给灵活的兔子致命一击，无须像狙击枪开镜瞄准。两者可为针尖对麦芒，因此选择防御突击步枪战甲。\r\n</p>\r\n<p>\r\n	　　以上就是关于全民突击战甲怎么搭配的攻略了，不同的流派的战甲搭配是不一样的哦，各位小伙伴们一定要根据自身的情况去选择相对应的战甲搭配哦。\r\n</p>', '0', '5', '1474511321', '1', '0');
INSERT INTO `qj_news` VALUES ('36', '14', '火柴人联盟武器大师怎么样？好用吗？', '/public/uploadfile/image/20160927/20160927165154_10310.jpg', '', '火柴人联盟 火柴人联盟武器大师', '火柴人联盟武器大师怎么样?好用吗?对于武器大家这个角色有些玩家还不是很了解，小编在这里就跟大家详细的分析下，希望可以帮助到各位玩家们。', '火柴人联盟武器大师怎么样？好用吗？', '火柴人联盟 火柴人联盟武器大师', '火柴人联盟武器大师怎么样?好用吗?对于武器大家这个角色有些玩家还不是很了解，小编在这里就跟大家详细的分析下，希望可以帮助到各位玩家们。', '<p>\r\n	　　火柴人联盟武器大师怎么样?好用吗?对于武器大家这个角色有些玩家还不是很了解，小编在这里就跟大家详细的分析下，希望可以帮助到各位玩家们。\r\n</p>\r\n<p style=\"text-align:center;\">\r\n	<img src=\"/public/uploadfile/image/20160922/20160922103056_79914.png\" alt=\"\" /> \r\n</p>\r\n<p>\r\n	　　武器大师技能\r\n</p>\r\n<p>\r\n	　　跳斩：纵身跳起手持灯柱猛砸地面，震飞敌人\r\n</p>\r\n<p>\r\n	　　无情连打：扔出灯柱以回旋镖形式击向敌人\r\n</p>\r\n<p>\r\n	　　反击风暴：快速旋转手中灯柱，击碎周围敌人\r\n</p>\r\n<p>\r\n	　　宗师之威：手持灯柱，快速且猛烈多次砸向地面\r\n</p>\r\n<p>\r\n	　　从武器大师的技能来看，他的招式幅度大而且上海高，在加上骇人的绿光给人一种精神上的压迫感，而且他的四个技能都是暴利输出型的哦，在放“宗师之威”技能的时候会顷刻让周围的敌人造成致命的伤害呢，那么武器大师的连招是什么呢?一起来了解下吧。\r\n</p>\r\n<p>\r\n	　　武器大师连招是什么?\r\n</p>\r\n<p>\r\n	　　武器大师对近身的敌人的毁灭型的，但是对远程的敌人就会有些吃力，但是武器大师的机动性高能和多类的英雄配合都能打得相当的给力，所以玩家们在挑选英雄上，可以让武器大师挑选寒冰等远程的英雄上线，这样就能互补双方的缺点哦，玩家们，你们觉得呢?\r\n</p>\r\n<p>\r\n	　　以上就是关于火柴人联盟武器大师怎么样的攻略分析了， 这个英雄在应对近战的时候是非常给力的，但是因为手比较短，所以对付远程兵会有点吃力。\r\n</p>', '0', '0', '1474511458', '1', '0');
INSERT INTO `qj_news` VALUES ('37', '14', '问道手游兰若寺副本通关攻略', '/public/uploadfile/image/20160922/20160922103152_67656.png', '', '兰若寺 兰若寺通关攻略', '问道手游兰若寺副本通关攻略，兰若寺这个副本还是有一定的难度的，想要通关也是需要一定的技巧的，下面就跟小编一起来了解下这个副本怎么过吧。', '问道手游兰若寺副本通关攻略', '兰若寺 兰若寺通关攻略', '问道手游兰若寺副本通关攻略，兰若寺这个副本还是有一定的难度的，想要通关也是需要一定的技巧的，下面就跟小编一起来了解下这个副本怎么过吧。', '<p>\r\n	　　问道手游兰若寺副本通关攻略，兰若寺这个副本还是有一定的难度的，想要通关也是需要一定的技巧的，下面就跟小编一起来了解下这个副本怎么过吧。\r\n</p>\r\n<p style=\"text-align:center;\">\r\n	<img src=\"/public/uploadfile/image/20160922/20160922103219_32703.png\" alt=\"\" />\r\n</p>\r\n<p>\r\n	　　在游戏中，道友们在和姥姥战斗时候，玩家在第一场战斗中就都是很快就可以过关了，但是在第二场还是有一定的难度，第二场战斗怪物中也是会进行反击的，这时候就是很需要一个水上防御的，但是玩家进行到了第三场中，就会因为怪物就会进行反击了，这时候玩家就需要直接带上法宝宝了，但是如果玩家在队伍中是有水的话，就必须要让水首先困住了其他的一个小怪了，接着就是要用人力就去破了。\r\n</p>\r\n<p>\r\n	　　等玩家在进入到了兰若寺中了，但是这时候就需要NPC兑换的，接着就要直接去后山就找到这小怪了，最后把小怪打了，玩家在打开了地图中，就可以在地图中有一个叫小苗的，然后宁采臣就在里面，这时候就可以兑换了，然后就找到了赤霞真人，就会提示就可以杀姥姥了。\r\n</p>\r\n<p>\r\n	　　玩家在和姥姥战斗时候，接着就是要去杀黑山老妖了，等到把黑山老妖杀死直呼，就需要去找宁采臣对话，就直接又可以去找赤霞真人，宁采臣兑换万之后，另外一个人立刻和赤霞真人兑换然后传出副本。\r\n</p>\r\n<p>\r\n	　　以上就是关于问道手游兰若寺副本通关攻略了，看完上面的攻略后，相信大家也知道怎么通关这个副本了。\r\n</p>', '0', '0', '1474511547', '1', '0');
INSERT INTO `qj_news` VALUES ('38', '14', '全民超神寒冬之心宝石怎么搭配', '/public/uploadfile/image/20160922/20160922103312_47131.jpg', '', '全民超神 全民超神寒冬之心', '寒冬之心是一个法术坦克型英雄，那么全民超神寒冬之心宝石怎么搭配呢?下面小编就跟大家详细的分析下寒冬之心如何搭配宝石，希望可以帮助到大家。', '全民超神寒冬之心宝石怎么搭配', '全民超神 全民超神寒冬之心', '寒冬之心是一个法术坦克型英雄，那么全民超神寒冬之心宝石怎么搭配呢?下面小编就跟大家详细的分析下寒冬之心如何搭配宝石，希望可以帮助到大家。', '<p>\r\n	　　寒冬之心是一个法术坦克型英雄，那么全民超神寒冬之心宝石怎么搭配呢?下面小编就跟大家详细的分析下寒冬之心如何搭配宝石，希望可以帮助到大家。\r\n</p>\r\n<p style=\"text-align:center;\">\r\n	<img src=\"/public/uploadfile/image/20160922/20160922103344_53846.jpg\" alt=\"\" />\r\n</p>\r\n<p>\r\n	　　在游戏中英雄宝石搭配也不是你想怎么搭就怎么搭的，就像英雄出装一样，宝石的搭配也是跟英雄的定位密不可分的;所以在对英雄进行宝石搭配之前一定需要先了解英雄的特点;下面琵琶网小编就来给大家说说寒冬之心宝石搭配，希望能够帮助到大家。\r\n</p>\r\n<p>\r\n	　　首先作为一名肉盾英雄在战斗的时候都是走在队伍前面的，这样的英雄是很需要强大的生存能力的;所以在宝石搭配上需要选择增加自身血量抗性能力的;可以在前排站得更久，为后面的输出英雄提供更稳定的输出环境;所以宝石方面就推荐生命宝石 \r\n，护甲宝石 及法抗宝石这些可以达到提升生存能力效果的宝石。\r\n</p>\r\n<p>\r\n	　　小伙伴们莫要忘记寒冬之心不仅是个能抗的英雄，同时也是一个可以进行法术输出的英雄;他的技能大都是法术加成伤害的;所以在宝石方面也需要可以增加法术输出伤害的宝石，推荐宝石为法强宝石，法术穿透宝石以及法术吸血宝石。\r\n</p>\r\n<p>\r\n	　　以上就是关于全民超神寒冬之心符文怎么搭配的攻略分析了，因为寒冬之心是一个法坦，所以在符文的选择上，小编推荐大家除了选择增加血量、物抗等这些外，还要适当的搭配下法强、法穿等这些宝石。\r\n</p>', '0', '2', '1474511626', '1', '0');
INSERT INTO `qj_news` VALUES ('39', '14', '天天酷跑玫瑰利刃值得入手吗？好不好？', '/public/uploadfile/image/20160922/20160922103418_14202.png', '', '天天酷跑 天天酷跑玫瑰利刃', '', '天天酷跑玫瑰利刃值得入手吗？好不好？', '天天酷跑 天天酷跑玫瑰利刃', '', '<p>\r\n	　　玫瑰利刃是属于吸血鬼伯爵的S级宝物哦，那么天天酷跑玫瑰利刃值得入手吗?好不好?下面小编级跟大家分析下玫瑰利刃这个宝物的属性。\r\n</p>\r\n<p style=\"text-align:center;\">\r\n	<img src=\"/public/uploadfile/image/20160922/20160922104625_59254.jpg\" alt=\"\" />\r\n</p>\r\n<p>\r\n	　　【玫瑰利刃】\r\n</p>\r\n<p>\r\n	　　基础属性：表现分增加11%;飞行得分增加11%\r\n</p>\r\n<p>\r\n	　　满级属性：伯爵夫人送给伯爵的护身符，让伯爵在技能结束后有一段无敌时间，并且表现分提升。\r\n</p>\r\n<p>\r\n	　　看这款宝物的名称就与伯爵夫人有关，吸血伯爵在游戏中几乎是一款无法超越的角色，毕竟他不仅能够免费复活一次，技能加分又高，同时又自带减少技能cd的能力，可惜没有完美的坐骑适配，而且现在经典模式爆分也容易多了，不然酷跑世界可就是吸血伯爵的天下了。这次这款宝物的推出，加强了吸血伯爵的保命能力，让他在技能结束后无敌并且提升表现分，简直逆天了。纵观天天酷跑现有的S级宝物，哪一款能有这样保命又加分的双重能力。再加上吸血伯爵自身条件本来就很优越，建议各位必定要入手这款宝物。\r\n</p>\r\n<p>\r\n	　　这款宝物现在通过降妖秘境产出，这次降妖秘境的难度也大幅降低了，算是给大家的福利吧，要加油击败牛魔王，争取拿下玫瑰利刃。\r\n</p>\r\n<p>\r\n	　　意义上就是关于天天酷跑玫瑰利刃值得入手吗?好不好的攻略分析了，玫瑰利刃这个宝物对于吸血鬼伯爵的加强可是很大的，有吸血鬼伯爵这个角色的玩家们可以去入手。\r\n</p>', '0', '0', '1474512387', '1', '0');
INSERT INTO `qj_news` VALUES ('40', '14', '火影忍者手游凯怎么无限连招', '/public/uploadfile/image/20160922/20160922105435_33815.jpg', '', '火影忍者 火影忍者手游凯', '火影忍者手游凯怎么无限连招?凯是一个A级英雄，而且其伤害也是很足的，关于凯的连招技巧，小编就在这里跟大家详细的分析下。', '火影忍者手游凯怎么无限连招', '火影忍者 火影忍者手游凯', '火影忍者手游凯怎么无限连招?凯是一个A级英雄，而且其伤害也是很足的，关于凯的连招技巧，小编就在这里跟大家详细的分析下。', '<p>\r\n	　　火影忍者手游凯怎么无限连招?凯是一个A级英雄，而且其伤害也是很足的，关于凯的连招技巧，小编就在这里跟大家详细的分析下。\r\n</p>\r\n<p style=\"text-align:center;\">\r\n	<img src=\"/public/uploadfile/image/20160922/20160922105427_72215.jpg\" alt=\"\" />\r\n</p>\r\n<p>\r\n	　　凯连招技巧\r\n</p>\r\n<p>\r\n	　　先说凯的走位技巧，位于敌人侧上方或侧下方为最佳，这样对手只能为了摆脱你而直线走位，因为一旦不慎和你走到一个X轴的时候你的飞踢就有很大威胁性了，相对的，自己跑到角落里的对手也算是瓮中之鳖，用老方法接近，看对手的走位状况，走到一条直线就飞踢，距离较近就旋风腿踢起。一个走位优秀老道的凯即使不放技能都给人很大的压迫感，这一点和小李很像。\r\n</p>\r\n<p>\r\n	　　然后是技能分析，飞踢不多说，但是需要一定预判，万一提前量打的太多不小心飞到人家面前而没击中对手则成了笑话。而且飞踢很好打一些空中技能，例如鹿丸的爆炸符，看到对手有放爆炸符的迹象时立刻飞踢是一个好针对。当你飞踢命中率极高的时候给对手的压力是很大的，必须被动走位。\r\n</p>\r\n<p>\r\n	　　旋风腿这个技能以前小编有些小看他了，现在看来配合走位是相当恐怖的，在墙边被打到就注定会被无限连，即使不是也可以旋风腿接飞踢强制打到墙边。而且两套平a之后还可以接一套旋风腿+飞踢的combo，伤害真的很吓人。\r\n</p>\r\n<p>\r\n	　　最后是大招，凯的大常年被放空大多是被保护害的，事实上在对手处于站立状态下释放为最佳，可以打满伤害甚至可以接技能，而且封走位封起身极佳，对手即使密卷或通灵兽抗住第一下也会吃后续的伤害。\r\n</p>\r\n<p>\r\n	　　以上就是关于火影忍者手游凯怎么无限连招的攻略分析了，凯这个忍者的刷图能力还是很不错的，学会连招后，刷图速度那是很快的。\r\n</p>', '0', '1', '1474512877', '1', '0');
INSERT INTO `qj_news` VALUES ('41', '14', '天天酷跑审判女王新宝物礼包值得买吗', '/public/uploadfile/image/20160922/20160922105519_95610.jpg', '', '天天酷跑 天天酷跑审判女王', '关于天天酷跑审判女王新宝物礼包值得买吗是近期有些玩家在问小编的问题，小编在在这里就跟大家详细的分析下审判女王新宝物礼包有没有必要购买。', '天天酷跑审判女王新宝物礼包值得买吗', '天天酷跑 天天酷跑审判女王', '关于天天酷跑审判女王新宝物礼包值得买吗是近期有些玩家在问小编的问题，小编在在这里就跟大家详细的分析下审判女王新宝物礼包有没有必要购买。', '<p>\r\n	　　关于天天酷跑审判女王新宝物礼包值得买吗是近期有些玩家在问小编的问题，小编在在这里就跟大家详细的分析下审判女王新宝物礼包有没有必要购买。\r\n</p>\r\n<p style=\"text-align:center;\">\r\n	<img src=\"/public/uploadfile/image/20160922/20160922105559_19964.jpg\" alt=\"\" />\r\n</p>\r\n<p>\r\n	　　审判女王曾经是一款120级的滑翔角色，最有特点的技能就是每局额外复活一次，之后使用钻石复活有折扣。后来天天酷跑推出了她的S级宝物死神镰刀， \r\n让审判女王拥有了冲刺技能，同时还有A级宝物审判契约的帮助，审判女王多了三跳的能力，成为了不折不扣的120级三跳滑翔角色。\r\n</p>\r\n<p>\r\n	　　【死神镰刀】\r\n</p>\r\n<p>\r\n	　　基础属性：表现分增加11%，飞行得分增加11%;\r\n</p>\r\n<p>\r\n	　　满级属性：审判女王可获得死神冲刺技能，冲刺期间表现分大幅增加。\r\n</p>\r\n<p>\r\n	　　【审判契约】\r\n</p>\r\n<p>\r\n	　　基础属性：飞行得分增加10%\r\n</p>\r\n<p>\r\n	　　满级属性：审判女王获得三跳滑翔能力，在破坏障碍物时得分额外增加。\r\n</p>\r\n<p>\r\n	　　参考这两款宝物的属性，只要装备了这两款宝物，审判女王就相当于迷你版的吸血伯爵，除了属性方面和130级角色有点差别之外，其余部分差别不大，也算是弥补了错过吸血伯爵的玩家一点遗憾。\r\n</p>\r\n<p>\r\n	　　现在官方推出的审判女王新宝物礼包中，已经不含死神镰刀，取而代之的是新宝物审判勋章。\r\n</p>\r\n<p>\r\n	　　【审判勋章】\r\n</p>\r\n<p>\r\n	　　基础属性：表现分增加11%;飞行得分增加11%\r\n</p>\r\n<p>\r\n	　　满级属性：审判女王在正常奔跑时有一定概率将金币变为审判符文\r\n</p>\r\n<p>\r\n	　　这款宝物和死神镰刀相比弱多了，冲刺技能绝对实用，而变金币为高分收集物这种技能实在太普遍，很多坐骑也有这种技能，所以这款宝物并没有为审判女王带来太惊艳的加强效果。目前这个礼包售价68RMB，虽然不算贵，而且也包含了20000个魔法药水，但是这款宝物不太实用，还是等待以后官方推出更好的宝物吧。\r\n</p>\r\n<p>\r\n	　　关于天天酷跑审判女王新宝物礼包值得买吗小编就介绍到这里了，这个礼包小编并不是很推荐大家去买。\r\n</p>', '0', '7', '1474512960', '1', '0');
INSERT INTO `qj_news` VALUES ('42', '20', '《封印者》新时装“黑暗光辉”展示', '/public/uploadfile/image/20160926/20160926163421_74794.png', '', '封印者', '《封印者》新时装“黑暗光辉”展示', '《封印者》新时装“黑暗光辉”展示', '封印者', '《封印者》新时装“黑暗光辉”展示', '<embed src=\"http://f.v.17173cdn.com/player_f2/MzE1NDMyMDk.swf\" type=\"application/x-shockwave-flash\" width=\"550\" height=\"400\" quality=\"high\" />', '0', '5', '1474878889', '1', '0');
INSERT INTO `qj_news` VALUES ('43', '20', '《封印者》新时装“黑暗光辉”展示', '/public/uploadfile/image/20160926/20160926163421_74794.png', '', '《封印者》新时装“黑暗光辉”展示', '《封印者》新时装“黑暗光辉”展示', '《封印者》新时装“黑暗光辉”展示', '《封印者》新时装“黑暗光辉”展示', '《封印者》新时装“黑暗光辉”展示', '<embed src=\"http://f.v.17173cdn.com/player_f2/MzE1NDMyMDk.swf\" type=\"application/x-shockwave-flash\" width=\"550\" height=\"400\" quality=\"high\" />', '0', '1', '1474964805', '1', '0');
INSERT INTO `qj_news` VALUES ('44', '20', '《封印者》新时装“黑暗光辉”展示', '/public/uploadfile/image/20160926/20160926163421_74794.png', '', '封印者', '《封印者》新时装“黑暗光辉”展示', '《封印者》新时装“黑暗光辉”展示', '《封印者》新时装“黑暗光辉”展示', '《封印者》新时装“黑暗光辉”展示', '<embed src=\"http://f.v.17173cdn.com/player_f2/MzE1NDMyMDk.swf\" type=\"application/x-shockwave-flash\" width=\"550\" height=\"400\" quality=\"high\" />', '0', '1', '1474964830', '1', '0');
INSERT INTO `qj_news` VALUES ('45', '20', '《封印者》新时装“黑暗光辉”展示', '/public/uploadfile/image/20160926/20160926163421_74794.png', '', '封印者', '《封印者》新时装“黑暗光辉”展示', '《封印者》新时装“黑暗光辉”展示', '封印者', '《封印者》新时装“黑暗光辉”展示', '<embed src=\"http://f.v.17173cdn.com/player_f2/MzE1NDMyMDk.swf\" type=\"application/x-shockwave-flash\" width=\"550\" height=\"400\" quality=\"high\" />', '0', '0', '1475028209', '1', '0');
INSERT INTO `qj_news` VALUES ('46', '20', '《封印者》新时装“黑暗光辉”展示', '/public/uploadfile/image/20160926/20160926163421_74794.png', '', '封印者', '《封印者》新时装“黑暗光辉”展示', '《封印者》新时装“黑暗光辉”展示', '封印者', '《封印者》新时装“黑暗光辉”展示', '<embed src=\"http://f.v.17173cdn.com/player_f2/MzE1NDMyMDk.swf\" type=\"application/x-shockwave-flash\" width=\"550\" height=\"400\" quality=\"high\" />', '0', '1', '1475028261', '1', '0');

-- ----------------------------
-- Table structure for `qj_order`
-- ----------------------------
DROP TABLE IF EXISTS `qj_order`;
CREATE TABLE `qj_order` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL COMMENT '类型',
  `name` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `qq` varchar(255) DEFAULT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `add_time` int(10) NOT NULL,
  `is_del` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_order
-- ----------------------------

-- ----------------------------
-- Table structure for `qj_page`
-- ----------------------------
DROP TABLE IF EXISTS `qj_page`;
CREATE TABLE `qj_page` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `catdir` varchar(30) NOT NULL DEFAULT '' COMMENT '别名',
  `parentid` smallint(5) NOT NULL DEFAULT '0' COMMENT '父类id',
  `page_name` varchar(150) NOT NULL DEFAULT '' COMMENT '页面名称',
  `en_name` varchar(255) DEFAULT NULL COMMENT '英文名称',
  `banner` varchar(255) DEFAULT NULL COMMENT '头部横幅',
  `content` text NOT NULL COMMENT '内容',
  `title` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `orderid` int(11) DEFAULT NULL,
  `template` varchar(255) NOT NULL COMMENT '模板',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_page
-- ----------------------------
INSERT INTO `qj_page` VALUES ('1', 'about', '0', '关于手游村', 'about', '', '<span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span><span>我是公会介绍</span>', '', '公会介绍', '公会介绍', '1463625890', '0', 'index');
INSERT INTO `qj_page` VALUES ('4', 'jrsyc', '1', '加入手游村', 'jrsyc', '', '<p>\r\n	加入手游村加入手游村加入手游村加入手游村加入手游村加入手游村加入手游村加入手游村加入手游村\r\n</p>', '', '手游村', '加入手游村', '1463627764', '0', 'index');
INSERT INTO `qj_page` VALUES ('5', 'lxfs', '1', '联络方式', 'lxfs', '', '<p>\r\n	联络方式联络方式联络方式\r\n</p>\r\n<p>\r\n	联络方式联络方式\r\n</p>\r\n<p>\r\n	联络方式联络方式\r\n</p>\r\n<p>\r\n	联络方式联络方式联络方式\r\n</p>\r\n<p>\r\n	联络方式联络方式联络方式\r\n</p>\r\n<p>\r\n	联络方式联络方式联络方式\r\n</p>\r\n<p>\r\n	联络方式\r\n</p>\r\n<p>\r\n	联络方式\r\n</p>\r\n<p>\r\n	联络方式\r\n</p>\r\n<p>\r\n	联络方式\r\n</p>\r\n<p>\r\n	联络方式\r\n</p>\r\n<p>\r\n	联络方式\r\n</p>\r\n<p>\r\n	<br />\r\n</p>', '', '联络方式', '联络方式', '1463627789', '0', 'index');
INSERT INTO `qj_page` VALUES ('6', 'link', '6', '友情链接', 'link', '', '<a class=\"ke-insertfile\" href=\"http://baidu.com\" target=\"_blank\">百度</a>', '', '', '', '1463627817', null, 'link');
INSERT INTO `qj_page` VALUES ('7', 'paihang', '0', '手游排行', 'paihang', '', '', null, '', '', '1474856538', null, 'paihang');

-- ----------------------------
-- Table structure for `qj_push`
-- ----------------------------
DROP TABLE IF EXISTS `qj_push`;
CREATE TABLE `qj_push` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `typeid` int(10) NOT NULL,
  `itemid` int(10) NOT NULL,
  `sort` smallint(3) NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_push
-- ----------------------------
INSERT INTO `qj_push` VALUES ('1', '1', '3', '0', '0');
INSERT INTO `qj_push` VALUES ('2', '3', '14', '0', '1474872852');
INSERT INTO `qj_push` VALUES ('3', '3', '7', '0', '1474872926');
INSERT INTO `qj_push` VALUES ('4', '2', '6', '0', '1474872947');
INSERT INTO `qj_push` VALUES ('5', '2', '12', '0', '1474872960');
INSERT INTO `qj_push` VALUES ('6', '2', '16', '0', '1474872976');
INSERT INTO `qj_push` VALUES ('7', '1', '12', '0', '1474872988');
INSERT INTO `qj_push` VALUES ('8', '2', '15', '0', '1474872989');
INSERT INTO `qj_push` VALUES ('9', '3', '18', '0', '1474873005');
INSERT INTO `qj_push` VALUES ('10', '3', '13', '0', '1474873021');
INSERT INTO `qj_push` VALUES ('11', '4', '10', '0', '1474873044');
INSERT INTO `qj_push` VALUES ('12', '5', '8', '0', '1474873063');
INSERT INTO `qj_push` VALUES ('13', '4', '9', '0', '1474873095');
INSERT INTO `qj_push` VALUES ('14', '5', '7', '0', '1474873111');
INSERT INTO `qj_push` VALUES ('15', '5', '5', '0', '1474873139');
INSERT INTO `qj_push` VALUES ('16', '5', '18', '0', '1474873161');
INSERT INTO `qj_push` VALUES ('17', '5', '9', '0', '1474873362');
INSERT INTO `qj_push` VALUES ('18', '5', '11', '0', '1474873393');
INSERT INTO `qj_push` VALUES ('19', '5', '17', '0', '1474873420');
INSERT INTO `qj_push` VALUES ('20', '1', '17', '0', '1474873458');
INSERT INTO `qj_push` VALUES ('21', '1', '15', '0', '1474873471');
INSERT INTO `qj_push` VALUES ('22', '1', '13', '0', '1474873483');
INSERT INTO `qj_push` VALUES ('23', '1', '11', '0', '1474873495');
INSERT INTO `qj_push` VALUES ('24', '1', '9', '0', '1474873509');
INSERT INTO `qj_push` VALUES ('25', '1', '7', '0', '1474873519');
INSERT INTO `qj_push` VALUES ('26', '1', '5', '0', '1474873530');
INSERT INTO `qj_push` VALUES ('27', '2', '5', '0', '1474873555');
INSERT INTO `qj_push` VALUES ('28', '2', '9', '0', '1474873571');
INSERT INTO `qj_push` VALUES ('29', '2', '8', '0', '1474873582');
INSERT INTO `qj_push` VALUES ('30', '2', '4', '0', '1474873594');
INSERT INTO `qj_push` VALUES ('31', '2', '13', '0', '1474873609');
INSERT INTO `qj_push` VALUES ('32', '3', '15', '0', '1474873628');
INSERT INTO `qj_push` VALUES ('33', '3', '16', '0', '1474873639');
INSERT INTO `qj_push` VALUES ('34', '3', '12', '0', '1474873674');
INSERT INTO `qj_push` VALUES ('35', '3', '2', '0', '1474873688');
INSERT INTO `qj_push` VALUES ('36', '3', '3', '0', '1474873700');
INSERT INTO `qj_push` VALUES ('37', '4', '8', '0', '1474873717');
INSERT INTO `qj_push` VALUES ('38', '4', '11', '0', '1474873728');
INSERT INTO `qj_push` VALUES ('39', '4', '13', '0', '1474873740');
INSERT INTO `qj_push` VALUES ('40', '4', '14', '0', '1474873751');
INSERT INTO `qj_push` VALUES ('41', '4', '17', '0', '1474873762');
INSERT INTO `qj_push` VALUES ('42', '4', '18', '0', '1474873775');
INSERT INTO `qj_push` VALUES ('43', '4', '5', '0', '1474873795');
INSERT INTO `qj_push` VALUES ('44', '5', '10', '0', '1474873872');
INSERT INTO `qj_push` VALUES ('45', '5', '13', '0', '1474873889');
INSERT INTO `qj_push` VALUES ('46', '6', '11', '0', '1474955545');
INSERT INTO `qj_push` VALUES ('47', '6', '10', '0', '1474955545');
INSERT INTO `qj_push` VALUES ('48', '6', '5', '0', '1474955545');
INSERT INTO `qj_push` VALUES ('49', '6', '4', '0', '1474955545');
INSERT INTO `qj_push` VALUES ('50', '6', '3', '0', '1474955545');
INSERT INTO `qj_push` VALUES ('51', '7', '42', '0', '1474962389');
INSERT INTO `qj_push` VALUES ('52', '7', '44', '0', '1474967824');
INSERT INTO `qj_push` VALUES ('53', '7', '43', '0', '1474967824');
INSERT INTO `qj_push` VALUES ('54', '7', '46', '0', '1475028318');
INSERT INTO `qj_push` VALUES ('55', '7', '45', '0', '1475028318');
INSERT INTO `qj_push` VALUES ('56', '6', '20', '0', '1475028350');
INSERT INTO `qj_push` VALUES ('57', '6', '8', '0', '1475029002');
INSERT INTO `qj_push` VALUES ('58', '6', '7', '0', '1475029002');

-- ----------------------------
-- Table structure for `qj_pushtype`
-- ----------------------------
DROP TABLE IF EXISTS `qj_pushtype`;
CREATE TABLE `qj_pushtype` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `sort` smallint(3) NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_pushtype
-- ----------------------------
INSERT INTO `qj_pushtype` VALUES ('1', '首页轮播图下游戏', 'game', '0', '0');
INSERT INTO `qj_pushtype` VALUES ('2', '首页好评', 'game', '0', '0');
INSERT INTO `qj_pushtype` VALUES ('3', '首页热门', 'game', '0', '0');
INSERT INTO `qj_pushtype` VALUES ('4', '首页推荐', 'game', '0', '0');
INSERT INTO `qj_pushtype` VALUES ('5', '首页精品推荐', 'game', '0', '0');
INSERT INTO `qj_pushtype` VALUES ('6', '内页推荐', 'game', '0', '0');
INSERT INTO `qj_pushtype` VALUES ('7', '首页热门视频', 'news', '0', '0');

-- ----------------------------
-- Table structure for `qj_silde`
-- ----------------------------
DROP TABLE IF EXISTS `qj_silde`;
CREATE TABLE `qj_silde` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '名称',
  `link` varchar(255) NOT NULL COMMENT '链接',
  `img` varchar(255) NOT NULL COMMENT '图片',
  `type` int(10) NOT NULL COMMENT '类型',
  `orderid` int(10) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='幻灯片';

-- ----------------------------
-- Records of qj_silde
-- ----------------------------
INSERT INTO `qj_silde` VALUES ('4', '在线留言', '', '/App/Tpl/Common/images/banner.jpg', '1', '0');
INSERT INTO `qj_silde` VALUES ('5', '11', '', '/App/Tpl/Common/images/banner.jpg', '1', '0');
INSERT INTO `qj_silde` VALUES ('8', '222', '', '/App/Tpl/Common/images/banner.jpg', '1', '0');
INSERT INTO `qj_silde` VALUES ('9', '1111', '', '/App/Tpl/Common/images/8.jpg', '2', '0');
INSERT INTO `qj_silde` VALUES ('10', '222', '', '/App/Tpl/Common/images/8.jpg', '2', '0');

-- ----------------------------
-- Table structure for `qj_site_conf`
-- ----------------------------
DROP TABLE IF EXISTS `qj_site_conf`;
CREATE TABLE `qj_site_conf` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '显示名称',
  `name` varchar(255) NOT NULL COMMENT '标签',
  `value` text NOT NULL,
  `tip` varchar(255) DEFAULT NULL,
  `type` tinyint(1) NOT NULL COMMENT '类型 1 单行输入框 2 多行输入框 3图片 4 html',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否可以删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_site_conf
-- ----------------------------
INSERT INTO `qj_site_conf` VALUES ('1', '网站名', 'site_name', '手游村', '', '1', '0');
INSERT INTO `qj_site_conf` VALUES ('6', '网站logo', 'logo', '/App/Tpl/Common/images/logo.png', null, '3', '0');
INSERT INTO `qj_site_conf` VALUES ('13', '底部内容和统计代码', 'cnzz', '©2010-2016 www.youxi.com 手游村 版权所有 粤ICP备16041791号', null, '2', '0');
INSERT INTO `qj_site_conf` VALUES ('15', 'ICP备案证书号', 'icp', '16041791', null, '1', '0');
INSERT INTO `qj_site_conf` VALUES ('26', '是否开启伪静态', 'url_rewrite', '1', '0 关闭 1 开启', '1', '0');
INSERT INTO `qj_site_conf` VALUES ('36', '网站标题', 'title', '手游村', null, '1', '0');
INSERT INTO `qj_site_conf` VALUES ('37', '网站关键词', 'keywords', '手游村', null, '2', '0');
INSERT INTO `qj_site_conf` VALUES ('38', '网站描述', 'description', '手游村', null, '2', '0');
INSERT INTO `qj_site_conf` VALUES ('80', '模拟下载', 'mlx', '', '首页模拟下载地址', '1', '1');
INSERT INTO `qj_site_conf` VALUES ('81', '试玩活动', 'swhd', '', '试玩活动地址', '1', '1');
INSERT INTO `qj_site_conf` VALUES ('82', '微信福利', 'wxfl', '', '微信福利地址', '1', '1');

-- ----------------------------
-- Table structure for `qj_sms_conf`
-- ----------------------------
DROP TABLE IF EXISTS `qj_sms_conf`;
CREATE TABLE `qj_sms_conf` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(255) NOT NULL COMMENT '接口名称',
  `description` varchar(255) NOT NULL COMMENT '接口描述',
  `class_name` varchar(255) NOT NULL COMMENT '接口类名',
  `server_url` varchar(255) NOT NULL COMMENT '短信发送接口地址',
  `username` varchar(255) NOT NULL COMMENT '接口用户名',
  `password` varchar(255) NOT NULL COMMENT '接口密码',
  `is_effect` tinyint(1) NOT NULL COMMENT '是否启用',
  `userid` int(10) NOT NULL COMMENT '企业id ',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_sms_conf
-- ----------------------------

-- ----------------------------
-- Table structure for `qj_system_upload_conf`
-- ----------------------------
DROP TABLE IF EXISTS `qj_system_upload_conf`;
CREATE TABLE `qj_system_upload_conf` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ftp配置id',
  `upload_url` varchar(50) DEFAULT NULL COMMENT 'ftp地址',
  `upload_name` varchar(50) DEFAULT NULL COMMENT 'ftp账号',
  `upload_pwd` varchar(50) DEFAULT NULL COMMENT 'ftp密码',
  `upload_site` varchar(50) DEFAULT NULL COMMENT 'ftp目录',
  `upload_showsite` varchar(50) DEFAULT NULL COMMENT 'ftp访问路径',
  `upload_size` int(11) DEFAULT '0' COMMENT '上传文件大小(单位:M)',
  `upload_port` int(11) DEFAULT '21' COMMENT 'ftp端口',
  `upload_is_open` tinyint(2) NOT NULL COMMENT '是否开启远程附件',
  `upload_pasv` tinyint(2) NOT NULL COMMENT '是否开启被动模式',
  `upload_img_url` varchar(500) DEFAULT NULL COMMENT '水印图片地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_system_upload_conf
-- ----------------------------
INSERT INTO `qj_system_upload_conf` VALUES ('1', '', '', '', '/public/uploadfile/', '', '0', '21', '0', '1', '');

-- ----------------------------
-- Table structure for `qj_user`
-- ----------------------------
DROP TABLE IF EXISTS `qj_user`;
CREATE TABLE `qj_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `mobile` int(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `login_time` int(10) NOT NULL,
  `login_ip` varchar(255) NOT NULL,
  `emailpassed` tinyint(1) NOT NULL,
  `verify` varchar(20) DEFAULT NULL,
  `verify_time` int(10) DEFAULT NULL,
  `create_time` int(10) NOT NULL,
  `salt` varchar(10) DEFAULT NULL,
  `is_del` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qj_user
-- ----------------------------
INSERT INTO `qj_user` VALUES ('5', 'a111', '2e5cb95a64f7c2b31eec03268360f1f4', '', '0', '373845395@qq.com', '', '1475043922', '127.0.0.1', '1', '', '0', '1475043846', 'ogkfvg', '0');
INSERT INTO `qj_user` VALUES ('6', 'tina', 'cfcbb8609eadde63ab66403ce020aaf4', '', '0', '', '', '1474871020', '192.168.1.132', '0', null, null, '1474871020', 'xguhfb', '0');
INSERT INTO `qj_user` VALUES ('8', 'aaaa', 'a0b8904710449ab40eb05298e0dec775', '', '0', '2101590442@qq.com', '', '1475044311', '127.0.0.1', '1', '', '0', '1475044236', 'amqoiw', '0');
