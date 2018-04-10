/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50635
 Source Host           : localhost:8889
 Source Schema         : new_tron

 Target Server Type    : MySQL
 Target Server Version : 50635
 File Encoding         : 65001

 Date: 10/04/2018 16:56:42
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for oa_admin_access
-- ----------------------------
DROP TABLE IF EXISTS `oa_admin_access`;
CREATE TABLE `oa_admin_access` (
  `user_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_admin_access
-- ----------------------------
BEGIN;
INSERT INTO `oa_admin_access` VALUES (2, 1);
INSERT INTO `oa_admin_access` VALUES (4, 2);
INSERT INTO `oa_admin_access` VALUES (5, 5);
INSERT INTO `oa_admin_access` VALUES (6, 3);
INSERT INTO `oa_admin_access` VALUES (8, 7);
INSERT INTO `oa_admin_access` VALUES (9, 4);
INSERT INTO `oa_admin_access` VALUES (10, 1);
COMMIT;

-- ----------------------------
-- Table structure for oa_admin_group
-- ----------------------------
DROP TABLE IF EXISTS `oa_admin_group`;
CREATE TABLE `oa_admin_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL COMMENT '角色名称',
  `remark` varchar(100) DEFAULT NULL COMMENT '角色备注',
  `rules` varchar(4000) DEFAULT NULL COMMENT '权限节点数据 所含菜单权限 字符串 以逗号分割',
  `sort` tinyint(3) DEFAULT NULL COMMENT '角色级别',
  `pid` int(11) DEFAULT NULL COMMENT '父级所属角色',
  `status` tinyint(3) DEFAULT '1' COMMENT '1启用 2停用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_admin_group
-- ----------------------------
BEGIN;
INSERT INTO `oa_admin_group` VALUES (1, 'SuperViser', '监管人', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,40,41,42,43,44,45,46,52,53,54,55,56,47,48,49,51,57,58,66,67,68,73,69,73,80,77,78,79,82,85,59,60,61,62,63,70,71,72,74,75,76,81,83,84,86,87,88,50', NULL, 0, 1);
INSERT INTO `oa_admin_group` VALUES (2, 'VFX', '视效总监', '1,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,46,36,37,38,39,40,41,42,43,44,45,47,48,49,50,51', 1, 0, 1);
INSERT INTO `oa_admin_group` VALUES (3, 'VRD', '视效总制片', '1,47,48,49,50,51,41,42,43,44,45,36,37,38,39,40,32,33,34,46', NULL, 0, 1);
INSERT INTO `oa_admin_group` VALUES (4, 'PRD', '制片', '1,47,48,49,50,51,41,42,43,44,45,36,37,38,39,40,32,33,34,46,52,53,54,55,56,57,58,59,60,61,62,63,64,65', NULL, 0, 1);
INSERT INTO `oa_admin_group` VALUES (5, 'teamLeader', '工作室总监', '1,32,33,34,46,36,37,39,40,41,42,43,44,45,47,48', 2, 0, 1);
INSERT INTO `oa_admin_group` VALUES (6, 'groupLeader', '组长', '1,47,48,41,42,43,44,45,36,37,38,39,40,32,33,34,46,28,29,30,20,21,22', 3, 0, 1);
INSERT INTO `oa_admin_group` VALUES (7, 'artist', '制作人', '1,47,48,41,42,36,37,32,33,34,46,28,29,30,20,21', 4, 6, 1);
COMMIT;

-- ----------------------------
-- Table structure for oa_admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `oa_admin_menu`;
CREATE TABLE `oa_admin_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `pid` int(11) unsigned DEFAULT '0' COMMENT '上级菜单ID',
  `title` varchar(32) DEFAULT '' COMMENT '菜单名称',
  `url` varchar(127) DEFAULT '' COMMENT '链接地址',
  `icon` varchar(64) DEFAULT '' COMMENT '图标',
  `menu_type` tinyint(4) DEFAULT NULL COMMENT '菜单类型',
  `sort` tinyint(4) unsigned DEFAULT '0' COMMENT '排序（同级有效）',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `rule_id` int(11) DEFAULT NULL COMMENT '权限id',
  `module` varchar(50) DEFAULT NULL,
  `menu` varchar(50) DEFAULT NULL COMMENT '三级菜单吗',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

-- ----------------------------
-- Records of oa_admin_menu
-- ----------------------------
BEGIN;
INSERT INTO `oa_admin_menu` VALUES (1, 0, '管理', '', '', 1, 0, 1, 32, 'Admin', '');
INSERT INTO `oa_admin_menu` VALUES (2, 1, '系统配置', '', '', 1, 0, 1, 33, 'Admin', '');
INSERT INTO `oa_admin_menu` VALUES (3, 2, '菜单管理', '/admin/menu/list', '', 1, 0, 1, 11, 'Admin', 'menu');
INSERT INTO `oa_admin_menu` VALUES (4, 2, '系统参数', '/admin/config/add', '', 1, 0, 1, 19, 'Admin', 'systemConfig');
INSERT INTO `oa_admin_menu` VALUES (5, 2, '权限规则', '/admin/rule/list', '', 1, 0, 1, 3, 'Admin', 'rule');
INSERT INTO `oa_admin_menu` VALUES (6, 1, '账户管理', '', '', 1, 0, 1, 34, 'Admin', '');
INSERT INTO `oa_admin_menu` VALUES (7, 6, '工作室管理', '/admin/studios/list', '', 1, 1, 1, 37, 'Admin', 'studios');
INSERT INTO `oa_admin_menu` VALUES (8, 6, '环节管理', '/admin/taches/list', '', 1, 2, 1, 42, 'Admin', 'studios');
INSERT INTO `oa_admin_menu` VALUES (9, 6, '角色管理', '/admin/groups/list', '', 1, 3, 1, 21, 'Admin', 'groups');
INSERT INTO `oa_admin_menu` VALUES (10, 6, '成员列表', '/admin/users/list', '', 1, 4, 1, 29, 'Admin', 'users');
INSERT INTO `oa_admin_menu` VALUES (11, 1, '项目管理', '', '', 1, 0, 1, 46, 'Admin', '');
INSERT INTO `oa_admin_menu` VALUES (12, 11, '项目列表', '/admin/projects/list', '', 1, 1, 1, 48, 'Admin', 'projects');
INSERT INTO `oa_admin_menu` VALUES (13, 1, '工作台管理', '', '', 1, 0, 1, 52, 'Admin', '');
INSERT INTO `oa_admin_menu` VALUES (14, 13, '工作台', '/admin/workbenches/list', '', 1, 1, 1, 58, 'Admin', 'workbenches');
INSERT INTO `oa_admin_menu` VALUES (15, 1, '镜头管理', '', '', 1, 0, 1, 53, 'Admin', '');
INSERT INTO `oa_admin_menu` VALUES (16, 15, '镜头列表', '/admin/shots/list', '', 1, 1, 1, 60, 'Admin', 'shots');
COMMIT;

-- ----------------------------
-- Table structure for oa_admin_project
-- ----------------------------
DROP TABLE IF EXISTS `oa_admin_project`;
CREATE TABLE `oa_admin_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `studio_ids` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '所属工作室 可为多个 以逗号分割',
  `project_image` varchar(1000) COLLATE utf8_bin NOT NULL COMMENT '项目缩略图',
  `project_name` varchar(500) COLLATE utf8_bin NOT NULL COMMENT '项目名称',
  `project_byname` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '项目简称',
  `movies_type` tinyint(3) DEFAULT '0' COMMENT '影视类型 0未选择 1电影 2电视剧',
  `project_explain` varchar(3000) COLLATE utf8_bin DEFAULT NULL COMMENT '项目说明',
  `duration` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '0' COMMENT '总时长(计算得来 所属项目下所有镜头的帧长之和除以60)',
  `resolutic` tinyint(3) DEFAULT '1' COMMENT '分辨率',
  `frame_rate` tinyint(3) DEFAULT '1' COMMENT '项目帧率',
  `aspect_ratio` tinyint(3) DEFAULT '1' COMMENT '遮幅比',
  `frame_sum` int(11) DEFAULT '0' COMMENT '总帧长(计算得来 所属项目下所有镜头的帧长求和)',
  `handle_frame` varchar(50) COLLATE utf8_bin DEFAULT '' COMMENT '手柄帧',
  `screenings_count` int(6) DEFAULT '0' COMMENT '场次数量',
  `lens_count` int(6) DEFAULT '0' COMMENT '镜头数量',
  `task_count` int(11) DEFAULT '0' COMMENT '任务数量',
  `producer` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '负责制片人(保存多个人，以逗号分割)',
  `scene_producer` varchar(100) COLLATE utf8_bin DEFAULT '' COMMENT '现场制片人(保存多个人，以逗号分割)',
  `scene_director` varchar(100) COLLATE utf8_bin DEFAULT '' COMMENT '现场指导人(保存多个人，以逗号分割)',
  `visual_effects_boss` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '视效总监(保存多个人，以逗号分割)',
  `visual_effects_producer` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '视效总制片(保存多个人，以逗号分割)',
  `second_company_producer` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '二级公司制片(保存多个人，以逗号分割)',
  `inside_coordinate` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '内部协调制片(保存多个人，以逗号分割)',
  `plan_start_timestamp` int(11) DEFAULT NULL COMMENT '计划开始时间',
  `plan_end_timestamp` int(11) DEFAULT NULL COMMENT '计划结束时间',
  `status` tinyint(3) DEFAULT '0' COMMENT '项目完成状态 1 等待中 2制作中 3暂停 4完成',
  `uid` int(11) NOT NULL COMMENT '创建人主键ID user表',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='项目表';

-- ----------------------------
-- Records of oa_admin_project
-- ----------------------------
BEGIN;
INSERT INTO `oa_admin_project` VALUES (1, '2,3', 'uploads/Projects/images/20180321/6a7a5b472463faf75df69abf7b80ea6d.jpg', '扶摇', 'FUY', 2, '', '0', 1, 1, 1, 0, '+1,+1', 0, 0, 0, '5', '5,6', '4,5,10', '5,6', '5,6', '5,6', '4', 1521648000, 1525017600, 1, 1, 1521602422);
INSERT INTO `oa_admin_project` VALUES (2, '2,3', 'uploads/Projects/images/20180410/66c50ccf593263bbfca20124cc370e70.jpg', '大主宰', 'DZZ', 2, '', '0', 1, 1, 1, 0, '+1,+1', 0, 0, 0, '5', '5,6', '4,5,10', '5,6', '5,6', '5,6', '4', 1521648000, 1525017600, 2, 1, 1523345748);
INSERT INTO `oa_admin_project` VALUES (3, '2,3', 'uploads/Projects/images/20180321/cc25c2dabdf2c23bc36a808a30d4dca0.jpg', '七剑', 'QJ', 2, '', '0', 1, 1, 1, 0, '+1,+1', 0, 0, 0, '5', '5,6', '5,6', '5,6', '5,6', '5,6', '4', 1520870400, 1524672000, 1, 1, 1521601009);
COMMIT;

-- ----------------------------
-- Table structure for oa_admin_rule
-- ----------------------------
DROP TABLE IF EXISTS `oa_admin_rule`;
CREATE TABLE `oa_admin_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT '' COMMENT '名称',
  `name` varchar(100) DEFAULT '' COMMENT '定义',
  `level` tinyint(5) DEFAULT NULL COMMENT '级别。1模块,2控制器,3操作',
  `pid` int(11) DEFAULT '0' COMMENT '父id，默认0',
  `status` tinyint(3) DEFAULT '1' COMMENT '状态，1启用，0禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_admin_rule
-- ----------------------------
BEGIN;
INSERT INTO `oa_admin_rule` VALUES (1, '系统基础功能', 'admin', 1, 0, 1);
INSERT INTO `oa_admin_rule` VALUES (2, '权限规则', 'rules', 2, 1, 1);
INSERT INTO `oa_admin_rule` VALUES (3, '规则列表', 'index', 3, 2, 1);
INSERT INTO `oa_admin_rule` VALUES (4, '权限详情', 'read', 3, 2, 1);
INSERT INTO `oa_admin_rule` VALUES (5, '编辑权限', 'update', 3, 2, 1);
INSERT INTO `oa_admin_rule` VALUES (6, '删除权限', 'delete', 3, 2, 1);
INSERT INTO `oa_admin_rule` VALUES (7, '添加权限', 'save', 3, 2, 1);
INSERT INTO `oa_admin_rule` VALUES (8, '批量删除权限', 'deletes', 3, 2, 1);
INSERT INTO `oa_admin_rule` VALUES (9, '批量启用/禁用权限', 'enables', 3, 2, 1);
INSERT INTO `oa_admin_rule` VALUES (10, '菜单管理', 'menus', 2, 1, 1);
INSERT INTO `oa_admin_rule` VALUES (11, '菜单列表', 'index', 3, 10, 1);
INSERT INTO `oa_admin_rule` VALUES (12, '添加菜单', 'save', 3, 10, 1);
INSERT INTO `oa_admin_rule` VALUES (13, '菜单详情', 'read', 3, 10, 1);
INSERT INTO `oa_admin_rule` VALUES (14, '编辑菜单', 'update', 3, 10, 1);
INSERT INTO `oa_admin_rule` VALUES (15, '删除菜单', 'delete', 3, 10, 1);
INSERT INTO `oa_admin_rule` VALUES (16, '批量删除菜单', 'deletes', 3, 10, 1);
INSERT INTO `oa_admin_rule` VALUES (17, '批量启用/禁用菜单', 'enables', 3, 10, 1);
INSERT INTO `oa_admin_rule` VALUES (18, '系统管理', 'systemConfigs', 2, 1, 1);
INSERT INTO `oa_admin_rule` VALUES (19, '修改系统配置', 'save', 3, 18, 1);
INSERT INTO `oa_admin_rule` VALUES (20, '角色管理', 'groups', 2, 1, 1);
INSERT INTO `oa_admin_rule` VALUES (21, '角色列表', 'index', 3, 20, 1);
INSERT INTO `oa_admin_rule` VALUES (22, '角色详情', 'read', 3, 20, 1);
INSERT INTO `oa_admin_rule` VALUES (23, '编辑角色', 'update', 3, 20, 1);
INSERT INTO `oa_admin_rule` VALUES (24, '删除角色', 'delete', 3, 20, 1);
INSERT INTO `oa_admin_rule` VALUES (25, '添加角色', 'save', 3, 20, 1);
INSERT INTO `oa_admin_rule` VALUES (26, '批量删除角色', 'deletes', 3, 20, 1);
INSERT INTO `oa_admin_rule` VALUES (27, '批量启用/禁用角色', 'enables', 3, 20, 1);
INSERT INTO `oa_admin_rule` VALUES (28, '成员管理', 'users', 2, 1, 1);
INSERT INTO `oa_admin_rule` VALUES (29, '成员列表', 'index', 3, 28, 1);
INSERT INTO `oa_admin_rule` VALUES (30, '成员详情', 'read', 3, 28, 1);
INSERT INTO `oa_admin_rule` VALUES (31, '删除成员', 'delete', 3, 28, 1);
INSERT INTO `oa_admin_rule` VALUES (32, '管理菜单', 'Adminstrative', 2, 1, 1);
INSERT INTO `oa_admin_rule` VALUES (33, '系统管理二级菜单', 'systemConfig', 1, 32, 1);
INSERT INTO `oa_admin_rule` VALUES (34, '账户管理二级菜单', 'personnel', 3, 32, 1);
INSERT INTO `oa_admin_rule` VALUES (36, '工作室管理', 'studios', 2, 1, 1);
INSERT INTO `oa_admin_rule` VALUES (37, '工作室列表', 'index', 3, 36, 1);
INSERT INTO `oa_admin_rule` VALUES (38, '添加工作室', 'save', 3, 36, 1);
INSERT INTO `oa_admin_rule` VALUES (39, '编辑工作室', 'update', 3, 36, 1);
INSERT INTO `oa_admin_rule` VALUES (40, '删除工作室', 'delete', 3, 36, 1);
INSERT INTO `oa_admin_rule` VALUES (41, '环节管理', 'taches', 2, 1, 1);
INSERT INTO `oa_admin_rule` VALUES (42, '环节列表', 'index', 3, 41, 1);
INSERT INTO `oa_admin_rule` VALUES (43, '添加环节', 'save', 3, 41, 1);
INSERT INTO `oa_admin_rule` VALUES (44, '编辑环节', 'update', 3, 41, 1);
INSERT INTO `oa_admin_rule` VALUES (45, '删除环节', 'delete', 3, 41, 1);
INSERT INTO `oa_admin_rule` VALUES (46, '项目管理二级菜单', 'project', 3, 32, 1);
INSERT INTO `oa_admin_rule` VALUES (47, '项目管理', 'projects', 2, 1, 1);
INSERT INTO `oa_admin_rule` VALUES (48, '项目列表', 'index', 3, 47, 1);
INSERT INTO `oa_admin_rule` VALUES (49, '添加项目', 'save', 3, 47, 1);
INSERT INTO `oa_admin_rule` VALUES (50, '编辑项目', 'update', 3, 47, 1);
INSERT INTO `oa_admin_rule` VALUES (51, '删除项目', 'delete', 3, 47, 1);
INSERT INTO `oa_admin_rule` VALUES (52, '工作台二级菜单', 'workbench', 3, 32, 1);
INSERT INTO `oa_admin_rule` VALUES (53, '镜头管理二级菜单', 'shot', 3, 32, 1);
INSERT INTO `oa_admin_rule` VALUES (54, '库管理菜单', 'library', 3, 32, 1);
INSERT INTO `oa_admin_rule` VALUES (55, '审批管理二级菜单', 'approval', 3, 32, 1);
INSERT INTO `oa_admin_rule` VALUES (56, '时间计划管理二级菜单', 'timeplan', 3, 32, 1);
INSERT INTO `oa_admin_rule` VALUES (57, '工作台管理', 'workbenches', 2, 1, 1);
INSERT INTO `oa_admin_rule` VALUES (58, '工作台列表', 'index', 3, 57, 1);
INSERT INTO `oa_admin_rule` VALUES (59, '镜头管理', 'shots', 2, 1, 1);
INSERT INTO `oa_admin_rule` VALUES (60, '镜头列表', 'index', 3, 59, 1);
INSERT INTO `oa_admin_rule` VALUES (61, '添加镜头', 'save', 3, 59, 1);
INSERT INTO `oa_admin_rule` VALUES (62, '编辑镜头', 'update', 3, 59, 1);
INSERT INTO `oa_admin_rule` VALUES (63, '删除镜头', 'delete', 3, 59, 1);
INSERT INTO `oa_admin_rule` VALUES (66, '添加任务', 'save', 3, 57, 1);
INSERT INTO `oa_admin_rule` VALUES (67, '编辑任务', 'update', 3, 57, 1);
INSERT INTO `oa_admin_rule` VALUES (68, '删除任务', 'delete', 3, 57, 1);
INSERT INTO `oa_admin_rule` VALUES (69, '工作台标准列表', 'index_list', 3, 57, 1);
INSERT INTO `oa_admin_rule` VALUES (70, '删除环节', 'delete_tache', 3, 59, 1);
INSERT INTO `oa_admin_rule` VALUES (71, '删除工作室', 'delete_studio', 3, 59, 1);
INSERT INTO `oa_admin_rule` VALUES (72, '批量导入镜头', 'shot_import', 3, 59, 1);
INSERT INTO `oa_admin_rule` VALUES (73, '删除所属任务制作人', 'delete_userId', 3, 57, 1);
INSERT INTO `oa_admin_rule` VALUES (74, '镜头制作中的列表', 'in_production_data', 3, 59, 1);
INSERT INTO `oa_admin_rule` VALUES (75, '镜头暂停列表', 'pause_data', 3, 59, 1);
INSERT INTO `oa_admin_rule` VALUES (76, '镜头完成状态列表', 'finish_data', 3, 59, 1);
INSERT INTO `oa_admin_rule` VALUES (77, '等待上游资产列表', 'wait_upper_assets', 3, 57, 1);
INSERT INTO `oa_admin_rule` VALUES (78, '等待上游镜头列表', 'wait_upper_shots', 3, 57, 1);
INSERT INTO `oa_admin_rule` VALUES (79, '工作台完成列表', 'finish_list', 3, 57, 1);
INSERT INTO `oa_admin_rule` VALUES (80, '工作台标准列表', 'index_list', 3, 57, 1);
INSERT INTO `oa_admin_rule` VALUES (81, '反馈中列表', 'feedback_data', 3, 59, 1);
INSERT INTO `oa_admin_rule` VALUES (82, '工作台详情', 'read', 3, 57, 1);
INSERT INTO `oa_admin_rule` VALUES (83, '镜头详情', 'read', 3, 59, 1);
INSERT INTO `oa_admin_rule` VALUES (84, '获取工作室列表', 'get_studio_list', 3, 59, 1);
INSERT INTO `oa_admin_rule` VALUES (85, '获取用户列表', 'get_user_list', 3, 57, 1);
INSERT INTO `oa_admin_rule` VALUES (86, '添加场号', 'save_field', 3, 59, 1);
INSERT INTO `oa_admin_rule` VALUES (87, '编辑成员', 'update', 3, 28, 1);
INSERT INTO `oa_admin_rule` VALUES (88, '获取当前用户权限', 'editProject_ByAuth', 3, 47, 1);
INSERT INTO `oa_admin_rule` VALUES (89, '项目详情', 'read', 3, 47, 1);
COMMIT;

-- ----------------------------
-- Table structure for oa_admin_studio
-- ----------------------------
DROP TABLE IF EXISTS `oa_admin_studio`;
CREATE TABLE `oa_admin_studio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '' COMMENT '工作室名称',
  `explain` varchar(255) DEFAULT '' COMMENT '工作室备注',
  `pid` int(11) DEFAULT '0' COMMENT '所属父级主键ID',
  `status` tinyint(3) DEFAULT '1' COMMENT '是否启用 1启用 0禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_admin_studio
-- ----------------------------
BEGIN;
INSERT INTO `oa_admin_studio` VALUES (1, '北京聚光绘影科技有限公司', '', 0, 1);
INSERT INTO `oa_admin_studio` VALUES (2, '视效工作室', '金旭', 1, 1);
INSERT INTO `oa_admin_studio` VALUES (3, '制片工作室', '周艳春', 1, 1);
INSERT INTO `oa_admin_studio` VALUES (4, '研发工作室', '梁辰雨', 1, 1);
INSERT INTO `oa_admin_studio` VALUES (5, 'CGI工作室', '韩意', 1, 1);
INSERT INTO `oa_admin_studio` VALUES (6, '资产工作室', '段振伟', 1, 1);
INSERT INTO `oa_admin_studio` VALUES (7, '美术工作室', '马佳', 1, 1);
INSERT INTO `oa_admin_studio` VALUES (8, '动画工作室', '董云', 1, 1);
INSERT INTO `oa_admin_studio` VALUES (9, 'DI工作室', '赵智宇', 1, 1);
COMMIT;

-- ----------------------------
-- Table structure for oa_admin_tache
-- ----------------------------
DROP TABLE IF EXISTS `oa_admin_tache`;
CREATE TABLE `oa_admin_tache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tache_name` varchar(225) NOT NULL COMMENT '环节名称',
  `explain` varchar(255) DEFAULT NULL COMMENT '说明',
  `sort` tinyint(3) DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='环节表';

-- ----------------------------
-- Records of oa_admin_tache
-- ----------------------------
BEGIN;
INSERT INTO `oa_admin_tache` VALUES (1, 'VFX', '视效总监部', 1);
INSERT INTO `oa_admin_tache` VALUES (2, 'PRD', '制片部', 2);
INSERT INTO `oa_admin_tache` VALUES (3, 'ART', '美术部', 3);
INSERT INTO `oa_admin_tache` VALUES (4, 'MOD', '模型部', 4);
INSERT INTO `oa_admin_tache` VALUES (5, 'TEX', '贴图部', 5);
INSERT INTO `oa_admin_tache` VALUES (6, 'RIG', '绑定部', 6);
INSERT INTO `oa_admin_tache` VALUES (7, 'MMV', '跟踪部', 7);
INSERT INTO `oa_admin_tache` VALUES (8, 'ANI', '动画部', 8);
INSERT INTO `oa_admin_tache` VALUES (9, 'DMT', '数字绘景部', 9);
INSERT INTO `oa_admin_tache` VALUES (10, 'EFX', '特效部', 10);
INSERT INTO `oa_admin_tache` VALUES (11, 'LGT', '灯光部', 11);
INSERT INTO `oa_admin_tache` VALUES (12, 'CMP', '合成部', 12);
COMMIT;

-- ----------------------------
-- Table structure for oa_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `oa_admin_user`;
CREATE TABLE `oa_admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `username` varchar(100) DEFAULT NULL COMMENT '管理后台账号',
  `password` varchar(100) DEFAULT NULL COMMENT '管理后台密码',
  `realname` varchar(100) DEFAULT NULL COMMENT '真实姓名',
  `remark` varchar(100) DEFAULT NULL COMMENT '用户备注',
  `studio_id` int(11) DEFAULT NULL COMMENT '所属工作室',
  `tache_ids` varchar(255) DEFAULT NULL COMMENT '环节ID',
  `status` tinyint(3) DEFAULT NULL COMMENT '状态,1启用0禁用',
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_admin_user
-- ----------------------------
BEGIN;
INSERT INTO `oa_admin_user` VALUES (1, 'admin', 'd93a5def7511da3d0f2d171d9c344e91', '超级管理员', '', 0, NULL, 1, NULL);
INSERT INTO `oa_admin_user` VALUES (4, 'zhaott', 'd93a5def7511da3d0f2d171d9c344e91', '赵涛涛', '', 8, '5', 1, 1520938880);
INSERT INTO `oa_admin_user` VALUES (5, 'luoxin', 'd93a5def7511da3d0f2d171d9c344e91', '罗新', '', 8, '8', 1, 1521014461);
INSERT INTO `oa_admin_user` VALUES (6, 'lijz', 'd93a5def7511da3d0f2d171d9c344e91', '李锦智', '', 8, '8', 1, 1521019302);
INSERT INTO `oa_admin_user` VALUES (8, 'wangcy', 'd93a5def7511da3d0f2d171d9c344e91', '王春雨', '', 6, '6', 1, 1521170493);
INSERT INTO `oa_admin_user` VALUES (9, 'liwb', 'd93a5def7511da3d0f2d171d9c344e91', '李文斌', '', 6, '6', 1, 1521172074);
INSERT INTO `oa_admin_user` VALUES (10, 'liangcy', 'd93a5def7511da3d0f2d171d9c344e91', '梁辰雨', '', 7, '3,5', 1, 1521172198);
COMMIT;

-- ----------------------------
-- Table structure for oa_field
-- ----------------------------
DROP TABLE IF EXISTS `oa_field`;
CREATE TABLE `oa_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL COMMENT '所属项目ID',
  `name` varchar(255) DEFAULT NULL COMMENT '场号/集号',
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='=场/集表';

-- ----------------------------
-- Records of oa_field
-- ----------------------------
BEGIN;
INSERT INTO `oa_field` VALUES (1, 1, '123');
INSERT INTO `oa_field` VALUES (4, 1, '002');
COMMIT;

-- ----------------------------
-- Table structure for oa_shot
-- ----------------------------
DROP TABLE IF EXISTS `oa_shot`;
CREATE TABLE `oa_shot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL COMMENT '所属项目ID',
  `field_id` int(11) NOT NULL COMMENT '场号ID',
  `asset_ids` varchar(255) DEFAULT NULL COMMENT '资产ID(多个以逗号分割)',
  `shot_image` varchar(1000) NOT NULL COMMENT '镜头缩略图地址',
  `shot_number` varchar(20) NOT NULL COMMENT '镜头编号',
  `shot_byname` varchar(20) NOT NULL COMMENT '镜头简称',
  `shot_name` varchar(255) NOT NULL COMMENT '镜头名称',
  `shot_explain` varchar(1000) DEFAULT '' COMMENT '镜头备注',
  `clip_frame_length` int(8) DEFAULT '0' COMMENT '剪辑帧长',
  `frame_range` varchar(50) DEFAULT '' COMMENT '帧数范围 两值以逗号分割',
  `priority_level` tinyint(3) DEFAULT '1' COMMENT '镜头优先级 1D 2C 3B 4A',
  `difficulty` tinyint(3) DEFAULT '1' COMMENT '镜头难度1D 2C 3B 4A 5S',
  `handle_frame` varchar(50) DEFAULT '' COMMENT '手柄帧',
  `material_frame_length` int(11) DEFAULT NULL COMMENT '素材帧长',
  `change_speed_info` varchar(20) DEFAULT '' COMMENT '变速信息',
  `time` tinyint(3) NOT NULL DEFAULT '1' COMMENT '时刻(1白天 2夜晚)',
  `ambient` tinyint(3) NOT NULL DEFAULT '1' COMMENT '环境(1外 2内)',
  `material_number` varchar(50) DEFAULT '' COMMENT '素材号(一个镜头一个素材号)',
  `second_company` varchar(100) DEFAULT '' COMMENT '二级公司(相当于其他工作室ID )',
  `make_demand` varchar(1000) DEFAULT '' COMMENT '制作要求',
  `status` tinyint(3) DEFAULT '1' COMMENT '状态 1等待制作 5制作中 10等待审核 15反馈中 20内部审核通过 25完成 30客户通过',
  `is_assets` tinyint(3) DEFAULT '2' COMMENT '是否为等待资产 1是 2否',
  `is_pause` tinyint(3) NOT NULL DEFAULT '1' COMMENT '是否暂停 1 非暂停 2暂停',
  `plan_start_timestamp` int(11) DEFAULT '0' COMMENT '计划开始时间',
  `plan_end_timestamp` int(11) DEFAULT '0' COMMENT '计划结束时间',
  `actual_start_timestamp` int(11) DEFAULT NULL COMMENT '实际开始时间',
  `actual_end_timestamp` int(11) DEFAULT NULL COMMENT '实际结束时间',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `field_id` (`field_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='镜头表';

-- ----------------------------
-- Records of oa_shot
-- ----------------------------
BEGIN;
INSERT INTO `oa_shot` VALUES (11, 1, 1, '', 'uploads/Projects/images/20180409/cc0a09b50fcd51d3cde9d2c5c8991749.jpg', '001', 'damao', '大猫', '', 0, '101,134', 1, 1, '+1,+1', 0, '', 1, 1, '', '', '我的要求', 5, 2, 1, 1522252800, 1523376000, NULL, NULL, 1523269588);
INSERT INTO `oa_shot` VALUES (12, 1, 1, '', 'uploads/Projects/images/20180329/e72d3f1d0202928e900fea7b8903331e.jpg', '002', 'damao', '大猫', '', 0, '', 1, 1, '', 0, '', 1, 2, '', '', '', 1, 2, 1, 1522252800, 1523289600, NULL, NULL, 1522322100);
INSERT INTO `oa_shot` VALUES (13, 1, 1, '', 'uploads/Projects/images/20180402/be558f6c157c077b2ba703f86337ccb5.jpg', '002', 'FUY', '扶摇', '', 0, '101,135', 1, 1, '+1,+1', 0, '', 1, 1, '', '', '', 1, 2, 1, 1522598400, 1525795200, NULL, NULL, 1522657105);
COMMIT;

-- ----------------------------
-- Table structure for oa_system_config
-- ----------------------------
DROP TABLE IF EXISTS `oa_system_config`;
CREATE TABLE `oa_system_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(50) DEFAULT '',
  `value` varchar(100) DEFAULT '' COMMENT '配置值',
  `group` tinyint(4) unsigned DEFAULT '0' COMMENT '配置分组',
  `need_auth` tinyint(4) DEFAULT '1' COMMENT '1需要登录后才能获取，0不需要登录即可获取',
  PRIMARY KEY (`id`),
  UNIQUE KEY `参数名` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='【配置】系统配置表';

-- ----------------------------
-- Records of oa_system_config
-- ----------------------------
BEGIN;
INSERT INTO `oa_system_config` VALUES (1, 'SYSTEM_NAME', 'Tron后台', 0, 1);
INSERT INTO `oa_system_config` VALUES (2, 'SYSTEM_LOGO', 'uploads\\20180315\\be6c8909d3abe5c604a3b2ac2af3e2fc.jpg', 0, 1);
INSERT INTO `oa_system_config` VALUES (3, 'LOGIN_SESSION_VALID', '20000', 0, 1);
INSERT INTO `oa_system_config` VALUES (4, 'IDENTIFYING_CODE', '0', 0, 1);
COMMIT;

-- ----------------------------
-- Table structure for oa_task
-- ----------------------------
DROP TABLE IF EXISTS `oa_task`;
CREATE TABLE `oa_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT NULL COMMENT '角色ID',
  `user_id` int(11) DEFAULT NULL COMMENT '所属用户',
  `project_id` int(11) NOT NULL COMMENT '所属项目ID',
  `field_id` int(11) NOT NULL COMMENT '场号/集号ID',
  `shot_id` int(11) DEFAULT NULL COMMENT '镜头ID 根据任务类型存值',
  `assets_id` int(11) DEFAULT NULL COMMENT '资产ID 根据任务类型存值',
  `tache_id` int(11) NOT NULL COMMENT '环节ID',
  `tache_sort` tinyint(3) DEFAULT NULL COMMENT '环节序号',
  `studio_id` int(11) NOT NULL COMMENT '工作室ID',
  `task_type` tinyint(3) DEFAULT NULL COMMENT '任务类型 1镜头 2资产',
  `task_image` varchar(255) DEFAULT NULL COMMENT '镜头缩略图',
  `task_byname` varchar(50) NOT NULL COMMENT '任务简称',
  `make_demand` varchar(1000) DEFAULT '' COMMENT '制作要求',
  `task_priority_level` tinyint(3) DEFAULT '1' COMMENT '任务优先级 1D 2C 3B 4A',
  `difficulty` tinyint(3) DEFAULT '1' COMMENT '任务难度1D 2C 3B 4A 5S',
  `second_company` varchar(100) DEFAULT '' COMMENT '二级公司(相当于其他工作室ID )',
  `task_is_status_time` int(11) DEFAULT NULL COMMENT '任务在此状态时间 分',
  `task_allocated_time` int(11) DEFAULT NULL COMMENT '任务分配时间 小时',
  `plan_start_timestamp` int(11) DEFAULT '0' COMMENT '计划开始时间',
  `plan_end_timestamp` int(11) DEFAULT '0' COMMENT '计划结束时间',
  `actually_start_timestamp` int(11) DEFAULT '0' COMMENT '任务实际开始时间',
  `actually_end_timestamp` int(11) DEFAULT '0' COMMENT '任务实际结时时间',
  `finish_degree` int(3) DEFAULT '0' COMMENT '完成度',
  `task_status` tinyint(3) DEFAULT '1' COMMENT '任务状态 1等待制作 5制作中 10等待审核 15反馈中 20内部审核通过 25提交发布 30完成(客户通过)',
  `is_assets` tinyint(3) DEFAULT '2' COMMENT '是否为等待资产 1是 2否',
  `is_pause` tinyint(3) NOT NULL DEFAULT '1' COMMENT '是否暂停 1 非暂停 2暂停',
  `camera_model` varchar(100) DEFAULT '' COMMENT '相机型号',
  `camera_catch` varchar(100) DEFAULT '' COMMENT '相机捕捉',
  `camera_motion` tinyint(3) DEFAULT '1' COMMENT '相机运动 1匀速',
  `camera_height` int(6) DEFAULT NULL COMMENT '相机高度',
  `camera_focus` varchar(100) DEFAULT '' COMMENT '相机焦距',
  `focus_distance` varchar(50) DEFAULT '' COMMENT '对焦距离',
  `depth_of_field` varchar(50) DEFAULT '' COMMENT '景深',
  `pid` int(11) DEFAULT NULL COMMENT '所属任务主键',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '改变状态时更新时间',
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `field_id` (`field_id`),
  KEY `shot_id` (`shot_id`),
  KEY `assets_id` (`assets_id`),
  KEY `tache_id` (`tache_id`),
  KEY `studio_id` (`studio_id`),
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COMMENT='任务表';

-- ----------------------------
-- Records of oa_task
-- ----------------------------
BEGIN;
INSERT INTO `oa_task` VALUES (22, 1, 0, 1, 1, 11, NULL, 7, 7, 7, 1, 'uploads/Projects/images/20180329/9b466489a34add762c7e781a35882c8d.jpg', 'damao', '', 1, 1, '', NULL, NULL, 1522252800, 1523376000, 0, 0, 0, 1, 2, 1, '', '', 1, NULL, '', '', '', 0, 1522312119, 0);
INSERT INTO `oa_task` VALUES (24, 1, 10, 1, 1, 11, 0, 5, 5, 8, 1, 'uploads/Projects/images/20180409/45b0d621435b0ad6b113dfe20ea006d4.jpg', 'damao', '这是要求', 2, 1, '', NULL, NULL, 1522252800, 1523289600, 0, 0, 0, 5, 2, 1, '', '', 1, NULL, '', '', '', 0, 1522322100, 0);
INSERT INTO `oa_task` VALUES (25, 1, 0, 1, 1, 12, NULL, 7, 7, 5, 1, 'uploads/Projects/images/20180329/e72d3f1d0202928e900fea7b8903331e.jpg', 'damao', '', 1, 1, '', NULL, NULL, 1522252800, 1523289600, 0, 0, 0, 1, 2, 1, '', '', 1, NULL, '', '', '', 0, 1522322100, 0);
INSERT INTO `oa_task` VALUES (26, 1, 0, 1, 1, 13, NULL, 3, 3, 4, 1, 'uploads/Projects/images/20180402/be558f6c157c077b2ba703f86337ccb5.jpg', 'FUY', '', 1, 1, '', NULL, NULL, 1522598400, 1525795200, 0, 0, 0, 1, 2, 1, '', '', 1, NULL, '', '', '', 0, 1522657105, 0);
INSERT INTO `oa_task` VALUES (27, 1, 0, 1, 1, 13, NULL, 3, 3, 5, 1, 'uploads/Projects/images/20180402/be558f6c157c077b2ba703f86337ccb5.jpg', 'FUY', '', 1, 1, '', NULL, NULL, 1522598400, 1525795200, 0, 0, 0, 1, 2, 1, '', '', 1, NULL, '', '', '', 0, 1522657105, 0);
INSERT INTO `oa_task` VALUES (28, 1, 0, 1, 1, 13, NULL, 5, 5, 7, 1, 'uploads/Projects/images/20180402/be558f6c157c077b2ba703f86337ccb5.jpg', 'FUY', '', 1, 1, '', NULL, NULL, 1522598400, 1525795200, 0, 0, 0, 1, 2, 1, '', '', 1, NULL, '', '', '', 0, 1522657105, 0);
INSERT INTO `oa_task` VALUES (29, 1, 10, 1, 1, 13, 0, 5, 5, 6, 1, 'uploads/Projects/images/20180402/be558f6c157c077b2ba703f86337ccb5.jpg', 'FUY', '', 1, 1, '', NULL, NULL, 1522598400, 1525795200, 0, 0, 0, 25, 2, 1, '', '', 1, NULL, '', '', '', 0, 1522657105, 0);
INSERT INTO `oa_task` VALUES (32, 1, 4, 1, 1, 11, 0, 7, 7, 7, 1, 'uploads/Projects/images/20180409/f45c54a3c09dface207240a35e9b809f.jpg', 'damao', '', 1, 1, '', NULL, NULL, 1522252800, 1523376000, 0, 0, 0, 1, 2, 1, '', '', 1, NULL, '', '', '', 22, 1523280483, 1523280483);
INSERT INTO `oa_task` VALUES (35, 1, 10, 1, 1, 11, 0, 7, 7, 7, 1, 'uploads/Projects/images/20180329/9b466489a34add762c7e781a35882c8d.jpg', 'damao', '', 1, 1, '', NULL, NULL, 1522252800, 1523376000, 0, 0, 0, 1, 2, 1, '', '', 1, NULL, '', '', '', 22, 1523340556, 1523340556);
INSERT INTO `oa_task` VALUES (36, 1, 9, 1, 1, 11, 0, 7, 7, 7, 1, 'uploads/Projects/images/20180329/9b466489a34add762c7e781a35882c8d.jpg', 'damao', '', 1, 1, '', NULL, NULL, 1522252800, 1523376000, 0, 0, 0, 1, 2, 1, '', '', 1, NULL, '', '', '', 22, 1523340556, 1523340556);
INSERT INTO `oa_task` VALUES (37, 1, 10, 1, 1, 12, 0, 7, 7, 5, 1, 'uploads/Projects/images/20180329/e72d3f1d0202928e900fea7b8903331e.jpg', 'damao', '', 1, 1, '', NULL, NULL, 1522252800, 1523289600, 0, 0, 0, 1, 2, 1, '', '', 1, NULL, '', '', '', 25, 1523340584, 1523340584);
COMMIT;

-- ----------------------------
-- Table structure for oa_task_state_record
-- ----------------------------
DROP TABLE IF EXISTS `oa_task_state_record`;
CREATE TABLE `oa_task_state_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) DEFAULT NULL COMMENT '任务表ID',
  `task_status` tinyint(3) DEFAULT NULL COMMENT ' 任务表状态',
  `user_id` int(11) DEFAULT NULL COMMENT '操作人ID',
  `create_timestamp` int(11) DEFAULT NULL COMMENT '创建时间戳',
  `create_time` varchar(255) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='任务状态记录表';

-- ----------------------------
-- Records of oa_task_state_record
-- ----------------------------
BEGIN;
INSERT INTO `oa_task_state_record` VALUES (1, 22, 5, 1, 1522664893, '2018-04-02 18:28:13');
INSERT INTO `oa_task_state_record` VALUES (2, 23, 1, 1, 1522830334, '2018-04-04 16:25:34');
INSERT INTO `oa_task_state_record` VALUES (3, 24, 15, 1, 1522834437, '2018-04-04 17:33:57');
INSERT INTO `oa_task_state_record` VALUES (4, 24, 1, 1, 1522834440, '2018-04-04 17:34:00');
INSERT INTO `oa_task_state_record` VALUES (5, 24, 15, 1, 1523163704, '2018-04-08 13:01:44');
INSERT INTO `oa_task_state_record` VALUES (6, 24, 1, 1, 1523163706, '2018-04-08 13:01:46');
INSERT INTO `oa_task_state_record` VALUES (7, 25, 15, 1, 1523163714, '2018-04-08 13:01:54');
INSERT INTO `oa_task_state_record` VALUES (8, 25, 1, 1, 1523163723, '2018-04-08 13:02:03');
INSERT INTO `oa_task_state_record` VALUES (9, 25, 15, 1, 1523163769, '2018-04-08 13:02:49');
INSERT INTO `oa_task_state_record` VALUES (10, 25, 1, 1, 1523166791, '2018-04-08 13:53:11');
INSERT INTO `oa_task_state_record` VALUES (11, 24, 15, 1, 1523166820, '2018-04-08 13:53:40');
INSERT INTO `oa_task_state_record` VALUES (12, 24, 5, 1, 1523167103, '2018-04-08 13:58:23');
INSERT INTO `oa_task_state_record` VALUES (13, 22, 1, 1, 1523167132, '2018-04-08 13:58:52');
INSERT INTO `oa_task_state_record` VALUES (14, 30, 1, 4, 1523279970, '2018-04-09 21:19:30');
INSERT INTO `oa_task_state_record` VALUES (15, 31, 1, 5, 1523279970, '2018-04-09 21:19:30');
INSERT INTO `oa_task_state_record` VALUES (16, 32, 1, 4, 1523280483, '2018-04-09 21:28:03');
INSERT INTO `oa_task_state_record` VALUES (17, 33, 1, 5, 1523280483, '2018-04-09 21:28:03');
INSERT INTO `oa_task_state_record` VALUES (18, 34, 1, 5, 1523280906, '2018-04-09 21:35:06');
INSERT INTO `oa_task_state_record` VALUES (19, 35, 1, 10, 1523340556, '2018-04-10 14:09:16');
INSERT INTO `oa_task_state_record` VALUES (20, 36, 1, 9, 1523340556, '2018-04-10 14:09:16');
INSERT INTO `oa_task_state_record` VALUES (21, 37, 1, 10, 1523340584, '2018-04-10 14:09:44');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
