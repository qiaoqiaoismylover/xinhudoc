/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50610
Source Host           : localhost:3306
Source Database       : rockdoc

Target Server Type    : MYSQL
Target Server Version : 50610
File Encoding         : 65001

Date: 2019-08-30 20:16:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `rockdoc_admin`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_admin`;
CREATE TABLE `rockdoc_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '姓名',
  `user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `email` varchar(255) NOT NULL COMMENT '邮箱',
  `bootstyle` smallint(6) NOT NULL DEFAULT '0' COMMENT '后台样式',
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_admin
-- ----------------------------
INSERT INTO `rockdoc_admin` VALUES ('1', '管理员', 'admin', 'admin@rockoa.com', '22', '$2y$10$MbcVIpNuwuXoUG80/ypYd.MWvfQFqFx7GWhWWXKZblA8SpC6m8F6i', 'LWAe4sJqLxyrPNGgIapIBMZ098KJ5Bq0KZM3G2RfFn9JPnaadNBhy9iLGm6t', '2019-05-17 19:01:56', '2019-05-30 13:10:06');

-- ----------------------------
-- Table structure for `rockdoc_authory`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_authory`;
CREATE TABLE `rockdoc_authory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '对应单位ID',
  `agenhid` int(11) NOT NULL DEFAULT '0' COMMENT '对应应用agenh.id',
  `objectid` varchar(500) NOT NULL DEFAULT '' COMMENT '针对对象',
  `objectname` varchar(500) NOT NULL DEFAULT '' COMMENT '针对对象姓名',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态0停用,1启用',
  `atype` tinyint(4) NOT NULL DEFAULT '0' COMMENT '授权类型',
  `receid` varchar(500) NOT NULL DEFAULT '' COMMENT '对应人员数据,u人员,g组,d部门,all所有',
  `recename` varchar(500) NOT NULL DEFAULT '' COMMENT '对应人员数据',
  `wherestr` varchar(500) NOT NULL DEFAULT '' COMMENT '对应条件',
  `explain` varchar(500) NOT NULL DEFAULT '' COMMENT '说明',
  PRIMARY KEY (`id`),
  KEY `authory_cid_index` (`cid`),
  KEY `authory_agenhid_index` (`agenhid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_authory
-- ----------------------------

-- ----------------------------
-- Table structure for `rockdoc_chargems`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_chargems`;
CREATE TABLE `rockdoc_chargems` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '类型',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '对应id',
  `optdt` datetime DEFAULT NULL COMMENT '添加时间',
  `updatedt` datetime DEFAULT NULL COMMENT '更新时间',
  `key` varchar(200) NOT NULL DEFAULT '' COMMENT 'key',
  `modeid` int(11) NOT NULL DEFAULT '0' COMMENT '模块id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_chargems
-- ----------------------------
INSERT INTO `rockdoc_chargems` VALUES ('1', '0', '36', '2019-06-02 19:31:05', '2019-06-24 21:45:50', '', '36');

-- ----------------------------
-- Table structure for `rockdoc_company`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_company`;
CREATE TABLE `rockdoc_company` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '单位名称',
  `num` varchar(10) NOT NULL COMMENT '单位编号',
  `shortname` varchar(20) NOT NULL COMMENT '单位简称',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '单位logo',
  `tel` varchar(50) NOT NULL DEFAULT '' COMMENT '电话',
  `contacts` varchar(20) NOT NULL DEFAULT '' COMMENT '联系人',
  `flaskm` int(11) NOT NULL DEFAULT '100' COMMENT '用户容量',
  `flasks` int(11) NOT NULL DEFAULT '0' COMMENT '已添加用户',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '创建用户ID',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态0停用,1启用',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_num_unique` (`num`),
  KEY `company_uid_index` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_company
-- ----------------------------
INSERT INTO `rockdoc_company` VALUES ('1', '信呼开发团队', 'svmhqt', '信呼团队', '/images/wjj.png', '0592-1234567', '磐石', '100', '4', '3', '1', '2019-05-17 19:29:14', '2019-06-08 18:28:17');
INSERT INTO `rockdoc_company` VALUES ('2', '信呼工作室', 'cowqir', '信呼工作室', '', '', '', '100', '1', '1', '1', '2019-05-24 23:57:49', '2019-05-24 23:57:50');

-- ----------------------------
-- Table structure for `rockdoc_dept`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_dept`;
CREATE TABLE `rockdoc_dept` (
  `id` int(11) NOT NULL DEFAULT '0' COMMENT '部门Id',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级ID顶级',
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '对应单位ID',
  `name` varchar(50) NOT NULL COMMENT '名称',
  `num` varchar(30) NOT NULL DEFAULT '' COMMENT '编号',
  `headman` varchar(50) NOT NULL DEFAULT '' COMMENT '负责人',
  `headid` varchar(50) NOT NULL DEFAULT '' COMMENT '负责人ID',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序号越大越靠前',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态0停用,1启用',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  UNIQUE KEY `dept_cid_id_unique` (`cid`,`id`),
  KEY `dept_cid_index` (`cid`),
  KEY `dept_pid_index` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_dept
-- ----------------------------
INSERT INTO `rockdoc_dept` VALUES ('1', '0', '1', '信呼开发团队', '', '', '', '0', '1', '2019-05-17 19:29:14');
INSERT INTO `rockdoc_dept` VALUES ('2', '1', '1', '技术部', '', '陈先生', '1', '0', '1', '2019-05-17 20:09:50');
INSERT INTO `rockdoc_dept` VALUES ('1', '0', '2', '信呼工作室', '', '', '', '0', '1', '2019-05-24 23:57:49');

-- ----------------------------
-- Table structure for `rockdoc_doctpl`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_doctpl`;
CREATE TABLE `rockdoc_doctpl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '单位company.id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '平台用户users.id',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '平台单位用户usera.id',
  `filename` varchar(100) NOT NULL DEFAULT '' COMMENT '模版名称',
  `filenum` varchar(50) NOT NULL DEFAULT '' COMMENT '文件编号关联文件系统上',
  `fileext` varchar(20) NOT NULL DEFAULT '' COMMENT '类型docx,xlsx,pptx',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optname` varchar(20) NOT NULL DEFAULT '' COMMENT '操作人',
  `shateid` varchar(200) NOT NULL DEFAULT '' COMMENT '共享给Id',
  `shatename` varchar(200) NOT NULL DEFAULT '' COMMENT '共享给',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序号',
  `status` tinyint(4) DEFAULT '1' COMMENT '是否启用',
  PRIMARY KEY (`id`),
  KEY `doctpl_cid_index` (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_doctpl
-- ----------------------------
INSERT INTO `rockdoc_doctpl` VALUES ('1', '1', '1', '1', '业绩报表模版.xlsx', 'qvehndf5', 'xlsx', '2019-05-28 10:04:19', '陈先生', 'd1', '信呼开发团队', '0', '1');

-- ----------------------------
-- Table structure for `rockdoc_docxie`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_docxie`;
CREATE TABLE `rockdoc_docxie` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '单位company.id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '平台用户users.id',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '平台单位用户usera.id',
  `filename` varchar(100) NOT NULL DEFAULT '' COMMENT '文档名称',
  `filenum` varchar(50) NOT NULL DEFAULT '' COMMENT '文件编号关联文件系统上',
  `fenlei` varchar(50) NOT NULL DEFAULT '' COMMENT '分类',
  `fileext` varchar(20) NOT NULL DEFAULT '' COMMENT '类型docx,xlsx,pptx',
  `adddt` datetime DEFAULT NULL COMMENT '添加时间',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optname` varchar(20) NOT NULL DEFAULT '' COMMENT '操作人',
  `xienameid` varchar(500) NOT NULL DEFAULT '' COMMENT '协作人ID',
  `xiename` varchar(500) NOT NULL DEFAULT '' COMMENT '协作人',
  `recename` varchar(500) NOT NULL DEFAULT '' COMMENT '可查看人',
  `receid` varchar(500) NOT NULL DEFAULT '' COMMENT '可查看人ID',
  `explian` varchar(500) NOT NULL DEFAULT '' COMMENT '说明',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态0停用,1启用',
  `editname` varchar(20) NOT NULL DEFAULT '' COMMENT '最后修改人',
  `editnaid` int(11) NOT NULL DEFAULT '0' COMMENT '最后修改人id',
  PRIMARY KEY (`id`),
  KEY `docxie_cid_index` (`cid`),
  KEY `docxie_aid_index` (`aid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_docxie
-- ----------------------------
INSERT INTO `rockdoc_docxie` VALUES ('1', '1', '1', '1', '信呼开发团队介绍.pptx', 'i4a5kdhy', '介绍', 'pptx', '2019-05-28 09:58:20', '2019-05-28 09:58:20', '陈先生', 'u1', '陈先生', '信呼开发团队', 'd1', '', '1', '', '0');

-- ----------------------------
-- Table structure for `rockdoc_file`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_file`;
CREATE TABLE `rockdoc_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '对应单位ID',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '创建用户ID',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '创建单位下用户ID',
  `filenum` varchar(50) NOT NULL DEFAULT '' COMMENT '文件编号关联文件系统上',
  `filename` varchar(200) NOT NULL DEFAULT '' COMMENT '文件名',
  `fileext` varchar(30) NOT NULL DEFAULT '' COMMENT '文件扩展名',
  `filepath` varchar(200) NOT NULL DEFAULT '' COMMENT '文件路径',
  `thumbpath` varchar(200) NOT NULL DEFAULT '' COMMENT '缩略图路径',
  `pdfpath` varchar(200) NOT NULL DEFAULT '' COMMENT '转为pdf路径',
  `filesizecn` varchar(20) NOT NULL DEFAULT '' COMMENT '文件大小',
  `filesize` int(11) NOT NULL DEFAULT '0' COMMENT '文件大小',
  `downci` int(11) NOT NULL DEFAULT '0' COMMENT '下载次数',
  `optdt` datetime DEFAULT NULL COMMENT '添加时间',
  `lastdt` datetime DEFAULT NULL COMMENT '最后下载',
  `mtable` varchar(50) NOT NULL DEFAULT '' COMMENT '对应表',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '对应主表的id',
  `isxg` tinyint(4) NOT NULL DEFAULT '0' COMMENT '相关文件',
  `isdel` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否删除了',
  PRIMARY KEY (`id`),
  KEY `file_cid_index` (`cid`),
  KEY `file_uid_index` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_file
-- ----------------------------

-- ----------------------------
-- Table structure for `rockdoc_fileda`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_fileda`;
CREATE TABLE `rockdoc_fileda` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '单位company.id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '平台用户users.id',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '平台单位用户usera.id',
  `filenum` varchar(50) NOT NULL DEFAULT '' COMMENT '文件编号',
  `filename` varchar(200) NOT NULL DEFAULT '' COMMENT '文件名',
  `fileext` varchar(30) NOT NULL DEFAULT '' COMMENT '文件扩展名',
  `filetype` varchar(200) NOT NULL DEFAULT '' COMMENT '文件类型',
  `filepath` varchar(200) NOT NULL DEFAULT '' COMMENT '文件路径',
  `thumbpath` varchar(200) NOT NULL DEFAULT '' COMMENT '缩略图路径',
  `pdfpath` varchar(200) NOT NULL DEFAULT '' COMMENT '转为pdf路径',
  `filesizecn` varchar(20) NOT NULL DEFAULT '' COMMENT '文件大小',
  `filesize` int(11) NOT NULL DEFAULT '0' COMMENT '文件大小',
  `oid` int(11) NOT NULL DEFAULT '0' COMMENT '关联旧ID',
  `downci` int(11) NOT NULL DEFAULT '0' COMMENT '下载次数',
  `adddt` datetime NOT NULL COMMENT '添加时间',
  `optdt` datetime NOT NULL COMMENT '操作时间',
  `optname` varchar(20) NOT NULL DEFAULT '' COMMENT '上传者',
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT 'ip',
  `web` varchar(50) NOT NULL DEFAULT '' COMMENT '浏览器',
  `remark` varchar(200) NOT NULL DEFAULT '' COMMENT '备注',
  `table` varchar(50) NOT NULL DEFAULT '' COMMENT '对应表',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '对应table表中id',
  `isdel` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `deldt` datetime DEFAULT NULL COMMENT '删除时间',
  `outuid` int(11) NOT NULL DEFAULT '0' COMMENT '外部对应用户Id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fileda_filenum_unique` (`filenum`),
  KEY `fileda_aid_index` (`aid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_fileda
-- ----------------------------
INSERT INTO `rockdoc_fileda` VALUES ('1', '1', '1', '1', '917rppqr', '快速了解信呼文件管理平台.docx', 'docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'http://www.rockoa.com/upload/upgfile/xinhudocuse.docx', '', '', '11.10 KB', '11370', '0', '14', '2019-05-26 15:54:01', '2019-05-26 15:54:01', '', '127.0.0.1', 'Chrome', '', '', '0', '0', null, '0');
INSERT INTO `rockdoc_fileda` VALUES ('2', '1', '1', '1', 'i4a5kdhy', '信呼开发团队介绍.pptx', 'pptx', '', 'http://www.rockoa.com/upload/upgfile/xinhuppt.pptx', '', '', '28.95 KB', '29642', '0', '0', '2019-05-28 09:58:20', '2019-05-28 09:58:20', '', '127.0.0.1', 'Chrome', '', '', '0', '0', null, '0');
INSERT INTO `rockdoc_fileda` VALUES ('3', '1', '1', '1', 'qvehndf5', '业绩报表模版.xlsx', 'xlsx', '', 'http://www.rockoa.com/upload/upgfile/xinhuyeji.xlsx', '', '', '9.65 KB', '9879', '0', '0', '2019-05-28 10:04:19', '2019-05-28 10:04:19', '', '127.0.0.1', 'Chrome', '', '', '0', '0', null, '0');

-- ----------------------------
-- Table structure for `rockdoc_group`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_group`;
CREATE TABLE `rockdoc_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '对应单位ID',
  `name` varchar(255) NOT NULL COMMENT '组名',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序号越大越靠前',
  PRIMARY KEY (`id`),
  KEY `group_cid_index` (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_group
-- ----------------------------
INSERT INTO `rockdoc_group` VALUES ('1', '2', '我们的组', '0');

-- ----------------------------
-- Table structure for `rockdoc_log`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_log`;
CREATE TABLE `rockdoc_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '单位company.id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '平台用户users.id',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '平台单位用户usera.id',
  `ltype` varchar(50) NOT NULL DEFAULT '' COMMENT '类型',
  `optname` varchar(50) NOT NULL DEFAULT '' COMMENT '操作人',
  `remark` varchar(500) NOT NULL DEFAULT '' COMMENT '操作人',
  `optdt` datetime DEFAULT NULL COMMENT '添加时间',
  `ip` varchar(30) NOT NULL DEFAULT '' COMMENT 'IP',
  `web` varchar(30) NOT NULL DEFAULT '' COMMENT '浏览器',
  `url` varchar(500) NOT NULL DEFAULT '' COMMENT '相关地址',
  `level` tinyint(4) NOT NULL DEFAULT '0' COMMENT '日志级别0普通,1提示,2错误',
  PRIMARY KEY (`id`),
  KEY `log_cid_index` (`cid`),
  KEY `log_ltype_index` (`ltype`),
  KEY `log_level_index` (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_log
-- ----------------------------

-- ----------------------------
-- Table structure for `rockdoc_migrations`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_migrations`;
CREATE TABLE `rockdoc_migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_migrations
-- ----------------------------
INSERT INTO `rockdoc_migrations` VALUES ('1', '2017_12_15_000000_create_admin_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('2', '2017_12_15_000000_create_chargems_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('3', '2017_12_15_000000_create_company_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('4', '2017_12_15_000000_create_dept_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('5', '2017_12_15_000000_create_file_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('6', '2017_12_15_000000_create_imchat_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('7', '2017_12_15_000000_create_imgroup_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('8', '2017_12_15_000000_create_imgroupuser_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('9', '2017_12_15_000000_create_immess_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('10', '2017_12_15_000000_create_immesszt_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('11', '2017_12_15_000000_create_log_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('12', '2017_12_15_000000_create_password_resets_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('13', '2017_12_15_000000_create_task_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('14', '2017_12_15_000000_create_token_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('15', '2017_12_15_000000_create_usera_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('16', '2017_12_15_000000_create_users_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('17', '2017_12_21_000000_create_flowbill_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('18', '2017_12_21_000000_create_flowchao_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('19', '2017_12_21_000000_create_flowchecks_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('20', '2017_12_21_000000_create_flowcourse_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('21', '2017_12_21_000000_create_flowcourss_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('22', '2017_12_21_000000_create_flowlog_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('23', '2017_12_21_000000_create_flowmenu_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('24', '2017_12_21_000000_create_flowread_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('25', '2017_12_21_000000_create_remind_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('26', '2018_02_15_000000_create_group_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('27', '2018_02_15_000000_create_sjoin_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('28', '2018_03_15_000000_create_authory_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('29', '2018_04_15_000000_create_option_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('30', '2018_04_15_000000_create_rockqueue_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('31', '2018_04_15_000000_create_todo_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('32', '2018_05_15_000000_create_basefileda_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('33', '2018_05_15_000000_create_basesms_table', '1');
INSERT INTO `rockdoc_migrations` VALUES ('35', '2018_05_15_000000_create_smsrecord_table', '2');
INSERT INTO `rockdoc_migrations` VALUES ('36', '2019_05_20_000000_create_agent_worc_table', '2');
INSERT INTO `rockdoc_migrations` VALUES ('37', '2019_05_20_000000_create_agent_word_table', '2');
INSERT INTO `rockdoc_migrations` VALUES ('38', '2019_05_20_000000_create_agent_doctpl_table', '3');
INSERT INTO `rockdoc_migrations` VALUES ('39', '2019_05_20_000000_create_agent_docxie_table', '4');
INSERT INTO `rockdoc_migrations` VALUES ('41', '2018_05_15_000000_create_fileda_table', '5');

-- ----------------------------
-- Table structure for `rockdoc_option`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_option`;
CREATE TABLE `rockdoc_option` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '对应单位ID',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '对应上级id',
  `num` varchar(50) NOT NULL DEFAULT '' COMMENT '选项编号',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `value` varchar(500) NOT NULL DEFAULT '' COMMENT '对应值',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序号',
  `explain` varchar(500) NOT NULL DEFAULT '' COMMENT '说明',
  `optdt` datetime DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `option_cid_index` (`cid`),
  KEY `option_num_index` (`num`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_option
-- ----------------------------
INSERT INTO `rockdoc_option` VALUES ('1', '1', '0', 'xinhuoaurl', '', 'http://127.0.0.1/app/xinhu/', '0', '关联信呼OA地址', '2019-06-01 21:32:26');
INSERT INTO `rockdoc_option` VALUES ('2', '1', '0', 'xinhuoakey', '', 'rockxinhukey', '0', '信呼OA的openkey', '2019-06-01 21:32:26');
INSERT INTO `rockdoc_option` VALUES ('3', '0', '0', 'systemnum', '', '047bf7383bdcbfbf61f0dbc6b46a0b03', '0', '', '2019-06-02 18:55:58');

-- ----------------------------
-- Table structure for `rockdoc_rockqueue`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_rockqueue`;
CREATE TABLE `rockdoc_rockqueue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '对应单位ID',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '单位用户id',
  `atype` varchar(20) NOT NULL DEFAULT '' COMMENT '类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '运行地址',
  `params` varchar(4000) NOT NULL DEFAULT '' COMMENT '运行参数',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态@0等运行,1成功,2失败',
  `rundt` datetime DEFAULT NULL COMMENT '需运行时间',
  `optdt` datetime DEFAULT NULL COMMENT '添加时间',
  `runcont` varchar(4000) NOT NULL DEFAULT '' COMMENT '运行结果',
  `lastdt` datetime DEFAULT NULL COMMENT '运行时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_rockqueue
-- ----------------------------

-- ----------------------------
-- Table structure for `rockdoc_sjoin`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_sjoin`;
CREATE TABLE `rockdoc_sjoin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '对应单位ID',
  `type` varchar(10) NOT NULL COMMENT '类型',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '对应主id',
  `sid` int(11) NOT NULL DEFAULT '0' COMMENT '对应子id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sjoin_type_mid_sid_unique` (`type`,`mid`,`sid`),
  KEY `sjoin_cid_index` (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_sjoin
-- ----------------------------
INSERT INTO `rockdoc_sjoin` VALUES ('1', '2', 'gu', '1', '3');

-- ----------------------------
-- Table structure for `rockdoc_smsrecord`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_smsrecord`;
CREATE TABLE `rockdoc_smsrecord` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(2000) NOT NULL DEFAULT '' COMMENT '手机号',
  `cont` varchar(200) NOT NULL DEFAULT '' COMMENT '短信内容',
  `code` varchar(20) DEFAULT NULL COMMENT '验证码',
  `optdt` datetime NOT NULL COMMENT '添加时间',
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT 'ip',
  `device` varchar(100) DEFAULT NULL COMMENT '来源渠道',
  `web` varchar(30) DEFAULT NULL COMMENT '浏览器类型',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否使用',
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '对应单位Id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_smsrecord
-- ----------------------------

-- ----------------------------
-- Table structure for `rockdoc_task`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_task`;
CREATE TABLE `rockdoc_task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '单位company.id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `fenlei` varchar(50) NOT NULL DEFAULT '' COMMENT '分类',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '运行地址',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '运行类型d每天,h,m等',
  `time` varchar(100) NOT NULL DEFAULT '' COMMENT '运行时间',
  `ratecont` varchar(100) NOT NULL DEFAULT '' COMMENT '运行说明',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序号越大越靠后',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态0停用,1启用',
  `state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '运行结果',
  `lastdt` datetime DEFAULT NULL COMMENT '最后运行时间',
  `lastcont` varchar(500) NOT NULL DEFAULT '' COMMENT '最后运行返回内容',
  `explain` varchar(100) NOT NULL DEFAULT '' COMMENT '说明',
  PRIMARY KEY (`id`),
  KEY `task_cid_index` (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_task
-- ----------------------------
INSERT INTO `rockdoc_task` VALUES ('1', '0', '每5分钟运行', '系统', 'system,minute', 'i5', '00', '每5分钟运行一次', '0', '1', '1', '2019-08-30 20:15:00', 'success', '');
INSERT INTO `rockdoc_task` VALUES ('2', '0', '单位用户更新', '系统', 'system,useraup', 'd', '00:10:00', '每天', '0', '1', '1', '2019-08-20 00:10:01', 'success', '');
INSERT INTO `rockdoc_task` VALUES ('3', '0', '数据库备份', '系统', 'sysbeifen', 'd', '05:00:00', '删除过期回收站和数据库备份', '0', '1', '1', '2019-08-15 05:00:01', 'success', '');
INSERT INTO `rockdoc_task` VALUES ('4', '0', '每天早上运行', '系统', 'sysday', 'd', '08:00:00', '每天运行', '0', '1', '1', '2019-08-15 08:00:00', 'success', '');

-- ----------------------------
-- Table structure for `rockdoc_todo`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_todo`;
CREATE TABLE `rockdoc_todo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '对应单位ID',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '单位下用户ID',
  `typename` varchar(20) NOT NULL DEFAULT '' COMMENT '类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '提醒类型标题',
  `mess` varchar(500) NOT NULL DEFAULT '' COMMENT '信息内容',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态@0|未读,1|已读',
  `mtable` varchar(50) NOT NULL DEFAULT '' COMMENT '对应表',
  `agenhnum` varchar(50) NOT NULL DEFAULT '' COMMENT '对应应用编号',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '对应主表的id',
  `optdt` datetime DEFAULT NULL COMMENT '添加时间',
  `tododt` datetime DEFAULT NULL COMMENT '提醒时间',
  `readdt` datetime DEFAULT NULL COMMENT '已读时间',
  `optname` varchar(20) NOT NULL DEFAULT '' COMMENT '发送人',
  PRIMARY KEY (`id`),
  KEY `todo_cid_index` (`cid`),
  KEY `todo_aid_index` (`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_todo
-- ----------------------------

-- ----------------------------
-- Table structure for `rockdoc_token`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_token`;
CREATE TABLE `rockdoc_token` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户Id',
  `token` varchar(10) NOT NULL COMMENT 'token',
  `useragent` varchar(50) NOT NULL COMMENT '绑定信息',
  `cfrom` varchar(20) NOT NULL COMMENT '来源',
  `ip` varchar(30) NOT NULL DEFAULT '' COMMENT 'ip',
  `web` varchar(30) NOT NULL DEFAULT '' COMMENT '浏览器',
  `device` varchar(50) NOT NULL DEFAULT '' COMMENT '驱动',
  `online` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态0离线,1在线',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_token_unique` (`token`),
  KEY `token_uid_index` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_token
-- ----------------------------

-- ----------------------------
-- Table structure for `rockdoc_usera`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_usera`;
CREATE TABLE `rockdoc_usera` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '对应单位ID',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '关联平台用户ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `user` varchar(30) NOT NULL DEFAULT '' COMMENT '用户名',
  `position` varchar(50) NOT NULL DEFAULT '' COMMENT '职位',
  `mobile` varchar(50) NOT NULL DEFAULT '' COMMENT '手机号',
  `mobilecode` varchar(10) NOT NULL DEFAULT '' COMMENT '手机号区号，默认+86',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '单位分配的邮箱',
  `deptid` int(11) NOT NULL DEFAULT '0' COMMENT '所在部门ID',
  `deptname` varchar(30) NOT NULL DEFAULT '' COMMENT '部门名称',
  `deptids` varchar(50) NOT NULL DEFAULT '' COMMENT '多部门ID,多个,分开',
  `deptallname` varchar(255) NOT NULL DEFAULT '' COMMENT '部门全名',
  `deptpath` varchar(200) NOT NULL DEFAULT '' COMMENT '部门路径,如1,2,3',
  `superid` varchar(30) NOT NULL DEFAULT '' COMMENT '上级主管Id',
  `superman` varchar(50) NOT NULL DEFAULT '' COMMENT '上级主管姓名,多个,分开',
  `superpath` varchar(200) NOT NULL DEFAULT '' COMMENT '上级主管全部人',
  `grouppath` varchar(200) NOT NULL DEFAULT '' COMMENT '组Id',
  `tel` varchar(50) NOT NULL DEFAULT '' COMMENT '办公电话',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序号越大越靠前',
  `gender` tinyint(4) NOT NULL DEFAULT '1' COMMENT '性别0未知,1男,2女',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态0待激活,1已激活,2停用',
  `istxl` tinyint(4) NOT NULL DEFAULT '1' COMMENT '通讯录显示',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '用户级别0普通用户,1管理员',
  `pingyin` varchar(50) NOT NULL DEFAULT '' COMMENT '名字拼音',
  `joindt` datetime DEFAULT NULL COMMENT '激活时间',
  `createdt` datetime DEFAULT NULL COMMENT '添加时间',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `usera_cid_index` (`cid`),
  KEY `usera_uid_index` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_usera
-- ----------------------------
INSERT INTO `rockdoc_usera` VALUES ('1', '1', '1', '陈先生', 'chenxiansheng', '管理员', '15800000000', '', 'admin@rockoa.com', '2', '技术部', '', '信呼开发团队/技术部', '1,2', '', '', '', '', '', '0', '1', '1', '1', '0', 'chenxiansheng', '2019-05-17 19:29:14', '2019-05-17 19:29:14', '2019-06-08 18:37:07');
INSERT INTO `rockdoc_usera` VALUES ('2', '1', '2', '王老五', 'wanglaowu', '程序员', '15800000001', '', '', '2', '技术部', '', '信呼开发团队/技术部', '1,2', '', '', '', '', '', '0', '1', '1', '1', '2', 'wanglaowu', '2019-05-18 13:14:50', '2019-05-18 13:14:45', '2019-06-08 18:21:09');
INSERT INTO `rockdoc_usera` VALUES ('3', '2', '1', '陈先生', 'chenxiansheng', '创建人', '15800000000', '', '', '1', '信呼工作室', '', '信呼工作室', '1', '', '', '', '1', '', '0', '1', '1', '1', '2', 'chenxiansheng', '2019-05-24 23:57:49', '2019-05-24 23:57:49', '2019-06-08 18:31:24');
INSERT INTO `rockdoc_usera` VALUES ('4', '1', '3', '张山', 'zhangshan', '程序员', '15800000002', '', '', '2', '技术部', '', '信呼开发团队/技术部', '1,2', '', '', '', '', '', '0', '1', '1', '1', '2', 'zhangshan', '2019-05-29 19:12:37', '2019-05-29 19:11:33', '2019-05-29 19:11:33');
INSERT INTO `rockdoc_usera` VALUES ('5', '1', '0', '貂蝉', 'diaochan', '人事经理', '15800000003', '', '', '2', '技术部', '', '信呼开发团队/技术部', '1,2', '', '', '', '', '', '0', '2', '0', '1', '0', 'chan', null, '2019-06-08 18:22:59', '2019-06-08 18:23:05');

-- ----------------------------
-- Table structure for `rockdoc_users`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_users`;
CREATE TABLE `rockdoc_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '姓名',
  `userid` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名/帐号',
  `nameen` varchar(50) NOT NULL DEFAULT '' COMMENT '英文名',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `mobile` varchar(50) NOT NULL DEFAULT '' COMMENT '手机号',
  `mobilecode` varchar(10) NOT NULL DEFAULT '' COMMENT '手机号区号，默认+86',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '个人邮箱',
  `password` varchar(255) NOT NULL COMMENT '登录密码',
  `face` varchar(200) NOT NULL DEFAULT '' COMMENT '头像',
  `bootstyle` smallint(6) NOT NULL DEFAULT '0' COMMENT '后台样式地址',
  `flaskm` int(11) NOT NULL DEFAULT '0' COMMENT '可创建单位数',
  `flasks` int(11) NOT NULL DEFAULT '0' COMMENT '已创建单位数',
  `devcid` int(11) NOT NULL DEFAULT '0' COMMENT '默认单位ID',
  `remember_token` varchar(100) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态0停用,1启用',
  `online` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'REIM在线状态',
  `onlinedt` datetime DEFAULT NULL COMMENT '最后在线时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_mobile_mobilecode_unique` (`mobile`,`mobilecode`),
  KEY `users_userid_index` (`userid`),
  KEY `users_email_index` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_users
-- ----------------------------
INSERT INTO `rockdoc_users` VALUES ('1', '陈先生', '', '', '雨中磐石', '15800000000', '', '', '$2y$10$rHxsi19O7RGWfWM7m0bnhOp7i5l724v.SWhjW/ZXRXarRKF4O7QdW', '/images/face.jpg', '6', '5', '2', '1', 'hX1TAnb2L1X8hFiMDalsjqspsksadY7V24wuU4BKiB4kHeasSCiQEHTqZnta', '1', '0', null, '2019-05-17 19:29:14', '2019-06-24 12:04:39');
INSERT INTO `rockdoc_users` VALUES ('2', '王老五', '', '', '王老五', '15800000001', '', '', '$2y$10$NIFmHB/.rh8cuWYL1e7O/uR2ueHbpHbhNN6sUQsdFipocZhMoFnVu', '/images/user1.png', '26', '0', '0', '0', 'SSJnBargHsr8qMjecHP5QQBnWmbX1omOcCTkDV9mN3mkLJKkqMZHtA0dFpWv', '1', '0', null, '2019-05-17 23:18:31', '2019-06-09 19:09:39');
INSERT INTO `rockdoc_users` VALUES ('3', '张山', 'zhangsan', '', '张山', '15800000002', '', '', '$2y$10$1vuJi6O3vLfq8uO1qPeS6.H7IRMWblYuHaQElfLCzPPottsc8bOs2', '/images/folder.png', '0', '0', '0', '0', '7JSudrNbHDGFdbA7t1XeTbxeWJgnwMLj2r9nJ6anOW1TswDVq0KN4j5XWlIa', '1', '0', null, '2019-05-29 19:12:29', '2019-05-29 19:12:29');

-- ----------------------------
-- Table structure for `rockdoc_worc`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_worc`;
CREATE TABLE `rockdoc_worc` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '单位company.id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '平台用户users.id',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '平台单位用户usera.id',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否提交',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '分区名称',
  `uptype` varchar(100) NOT NULL DEFAULT '' COMMENT '上传类型',
  `receid` varchar(200) NOT NULL DEFAULT '' COMMENT '可查看人员ID',
  `recename` varchar(200) NOT NULL DEFAULT '' COMMENT '可查看人员',
  `guanid` varchar(200) NOT NULL DEFAULT '' COMMENT '管理人员Id',
  `guanname` varchar(200) NOT NULL DEFAULT '' COMMENT '管理人员',
  `upuserid` varchar(200) NOT NULL DEFAULT '' COMMENT '可上传人员ID',
  `upuser` varchar(200) NOT NULL DEFAULT '' COMMENT '可上传人员',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optname` varchar(20) NOT NULL DEFAULT '' COMMENT '操作人',
  `optid` int(11) NOT NULL DEFAULT '0' COMMENT '操作人id',
  `size` bigint(20) NOT NULL DEFAULT '0' COMMENT '分配大小0不限制单位字节',
  `sizeu` bigint(20) NOT NULL DEFAULT '0' COMMENT '已使用大小字节',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序号',
  PRIMARY KEY (`id`),
  KEY `worc_cid_index` (`cid`),
  KEY `worc_aid_index` (`aid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_worc
-- ----------------------------
INSERT INTO `rockdoc_worc` VALUES ('1', '1', '1', '1', '1', '0', '公共文件', '', 'd1', '信呼开发团队', 'u1', '陈先生', 'd2', '技术部', '2019-05-19 18:36:14', '陈先生', '1', '1073741824', '13776012', '0');
INSERT INTO `rockdoc_worc` VALUES ('2', '1', '1', '1', '1', '0', '技术开发文档', '', 'd1', '信呼开发团队', 'd2', '技术部', 'd2', '技术部', '2019-05-20 16:00:03', '陈先生', '1', '1073741824', '10910', '1');
INSERT INTO `rockdoc_worc` VALUES ('5', '1', '2', '2', '1', '0', '老五分区', '', 'u2', '王老五', 'u2', '王老五', 'u2', '王老五', '2019-05-24 20:52:13', '王老五', '2', '1073741824', '0', '0');

-- ----------------------------
-- Table structure for `rockdoc_word`
-- ----------------------------
DROP TABLE IF EXISTS `rockdoc_word`;
CREATE TABLE `rockdoc_word` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '单位company.id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '平台用户users.id',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '平台单位用户usera.id',
  `fqid` int(11) NOT NULL DEFAULT '0' COMMENT '分区worc.id',
  `folderid` int(11) NOT NULL DEFAULT '0' COMMENT '文件夹word.id',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '类型0文件,1文件夹',
  `filename` varchar(100) NOT NULL DEFAULT '' COMMENT '文件名',
  `filenum` varchar(50) NOT NULL DEFAULT '' COMMENT '文件编号关联文件系统上',
  `fileext` varchar(30) NOT NULL DEFAULT '' COMMENT '文件扩展名',
  `thumbpath` varchar(200) NOT NULL DEFAULT '' COMMENT '图片缩略图',
  `filesizecn` varchar(20) NOT NULL DEFAULT '' COMMENT '文件大小',
  `filesize` int(11) NOT NULL DEFAULT '0' COMMENT '文件大小',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optname` varchar(20) NOT NULL DEFAULT '' COMMENT '操作人',
  `shateid` varchar(200) NOT NULL DEFAULT '' COMMENT '共享给Id',
  `shatename` varchar(200) NOT NULL DEFAULT '' COMMENT '共享给',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序号',
  `downci` int(11) NOT NULL DEFAULT '0' COMMENT '下载/查看次数',
  `isdel` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否删除了',
  `stype` tinyint(4) NOT NULL DEFAULT '0' COMMENT '分类',
  `shateren` varchar(50) NOT NULL DEFAULT '' COMMENT '共享人',
  `shaterenid` int(11) NOT NULL DEFAULT '0' COMMENT '共享人ID',
  `editname` varchar(20) NOT NULL DEFAULT '' COMMENT '最后修改人',
  `editnaid` int(11) NOT NULL DEFAULT '0' COMMENT '最后修改人id',
  PRIMARY KEY (`id`),
  KEY `word_cid_index` (`cid`),
  KEY `word_fqid_index` (`fqid`),
  KEY `word_folderid_index` (`folderid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rockdoc_word
-- ----------------------------
INSERT INTO `rockdoc_word` VALUES ('1', '1', '1', '1', '1', '0', '1', '规则制度', '', '', '', '', '0', '2019-05-26 15:56:00', '陈先生', '', '', '0', '0', '0', '0', '', '0', '', '0');
INSERT INTO `rockdoc_word` VALUES ('2', '1', '1', '1', '2', '0', '0', '快速了解信呼文件管理平台.docx', '917rppqr', 'docx', '', '10.65 KB', '10910', '2019-05-26 15:56:33', '陈先生', '', '', '0', '0', '0', '0', '', '0', '', '0');
