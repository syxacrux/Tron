<?php
// +----------------------------------------------------------------------
// | Description: 基础框架路由配置文件
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honghaiweb.com>
// +----------------------------------------------------------------------

return [
	// 定义资源路由
	'__rest__'=>[
			'admin/upload'        =>		'admin/upload',
			'admin/rules'		   		=>		'admin/rules',
			'admin/systemdeploys'	=>		'admin/systemdeploys',
			'admin/groups'		   	=>		'admin/groups',
			'admin/users'		   		=>		'admin/users',
			'admin/menus'		   		=>		'admin/menus',
			'admin/studios'       =>		'admin/studios',
			'admin/taches'        =>		'admin/taches',
			'admin/projects'      =>		'admin/projects',
			'admin/shots'         =>		'admin/shots',
			'admin/workbenches'   =>		'admin/workbenches',
			'admin/assets'				=>		'admin/assets',
			'admin/references'		=>		'admin/references',
	],
	// [获取信息]
	'admin/infos/index'     =>      ['admin/infos/index',['method' => 'POST']],
	// 刷新token
	'admin/infos/refresh'   =>      ['admin/infos/refresh',['method' => 'POST']],
	// [基础]登录
	'admin/base/login'      =>      ['admin/base/login', ['method' => 'POST|GET']],
	// [基础] 记住登录
	'admin/base/relogin'		=>      ['admin/base/relogin', ['method' => 'POST']],
	// [基础] 修改密码
	'admin/base/setInfo'    =>      ['admin/base/setInfo', ['method' => 'POST']],
	// [基础] 退出登录
	'admin/base/logout'     =>      ['admin/base/logout', ['method' => 'POST']],
	// [基础] 获取配置
	'admin/base/getConfigs' =>      ['admin/base/getConfigs', ['method' => 'POST']],
	// [基础] 获取验证码
	'admin/base/getVerify'  =>      ['admin/base/getVerify', ['method' => 'GET']],
	// [基础] 上传图片
	'admin/upload'          =>      ['admin/upload/index', ['method' => 'POST']],
	// [基础] 上传缩略图
	'admin/upload_image'    =>      ['admin/upload/images_add', ['method' => 'POST']],
	// [基础] 生成base64格式的图片并上传到目录中
	'admin/image_base64'    =>      ['admin/upload/images_base64_add', ['method' => 'POST']],
	// [基础]	上传excel文件
	'admin/upload_excel'		=>			['admin/upload/excels_add',	['method' => 'POST']],
	// 保存系统配置
	'admin/systemConfigs'   =>      ['admin/systemConfigs/save', ['method' => 'POST']],
	// [规则] 批量删除
	'admin/rules/deletes'   =>      ['admin/rules/deletes', ['method' => 'POST']],
	// [规则] 批量启用/禁用
	'admin/rules/enables'   =>      ['admin/rules/enables', ['method' => 'POST']],
	// [用户组] 批量删除
	'admin/groups/deletes'  =>      ['admin/groups/deletes', ['method' => 'POST']],
	// [用户组] 批量启用/禁用
	'admin/groups/enables'  =>      ['admin/groups/enables', ['method' => 'POST']],
	// [用户] 批量删除
	'admin/users/deletes'   =>      ['admin/users/deletes', ['method' => 'POST']],
	// [用户] 批量启用/禁用
	'admin/users/enables'   =>      ['admin/users/enables', ['method' => 'POST']],
	// [菜单] 批量删除
	'admin/menus/deletes'   =>      ['admin/menus/deletes', ['method' => 'POST']],
	// [菜单] 批量启用/禁用
	'admin/menus/enables'   =>      ['admin/menus/enables', ['method' => 'POST']],
	// [工作室] 批量删除
	'admin/studios/deletes' =>      ['admin/studios/deletes', ['method' => 'POST']],
	// [工作室] 批量启用/禁用
	'admin/studios/enables' =>      ['admin/studios/enables', ['method' => 'POST']],
	// [环节管理] 批量删除
	'admin/taches/deletes'  =>      ['admin/taches/deletes', ['method' => 'POST']],
	// [角色管理] 批量删除
	'admin/roles/deletes'   =>      ['admin/roles/deletes', ['method' => 'POST']],
	// [角色管理] 批量启用/禁用
	'admin/roles/enables'   =>      ['admin/roles/enables', ['method' => 'POST']],
	// [项目] 获取登陆者是否属于当前项目 应用于编辑操作的权限
	'admin/check_auth'      =>      ['admin/projects/editProject_ByAuth', ['method'=> 'POST']],
	// [业务类型] 添加场号
	'admin/save_field'      =>      ['admin/fields/save_field',['method' => 'POST']],
	// [业务类型] 获取场/集/资产类型列表
	'admin/get_fields'      =>      ['admin/fields/get_field',['method' => 'GET']],
	// [镜头] 概况
	'shot/survey'						=>			['admin/shots/survey',['method' => 'GET']],
	// [镜头] 获取镜头号
	'admin/get_shot_num'		=>			['admin/shots/get_number',['method' => 'GET']],
	// [镜头] 校验镜头编号是否重复
	'shot/check_num'				=>			['admin/shots/check_shot_number',['method' => 'GET']],
	// [镜头] 制作中列表
	'shot/in_production'    =>      ['admin/shots/in_production_data',['method'=>'GET']],
	// [镜头] 反馈中列表
	'shot/feedback'         =>      ['admin/shots/feedback_data',['method'=>'GET']],
	// [镜头] 等待资产列表
	'shot/waiting_assets'   =>      ['admin/shots/waiting_assets_data',['method'=>'GET']],
	// [镜头] 暂停列表
	'shot/pause'            =>      ['admin/shots/pause_data',['method'=>'GET']],
	// [镜头] 完成列表
	'shot/finish'           =>      ['admin/shots/finish_data',['method'=>'GET']],
	// [镜头] 获取工作室列表
	'shot/get_studio'       =>      ['admin/shots/get_studio_list',['method'=>'GET']],
	// [镜头] 删除环节
	'shot/tache_del'				=>			['admin/shots/delete_tache',['method'=>'POST']],
	// [镜头] 删除工作室
	'shot/studio_del'				=>			['admin/shots/delete_studio',['method'=>'POST']],
	// [镜头] 获取模版
	'shot/template'					=>			['admin/shots/template',['method'=>'POST']],
	// [工作台] 标准列表
	'task/index_list'				=>			['admin/workbenches/index_list',['method' => 'GET']],
	// [工作台] 改变状态
	'task/change_status'    =>      ['admin/workbenches/change_status',['method' => 'POST']],
	// [工作台] 等待上游 资产列表
	'task/upper_assets'    	=>      ['admin/workbenches/wait_upper_assets',['method' => 'POST|GET']],
	// [工作台] 等待上游 上一个环节任务列表
	'task/upper_shots'    	=>      ['admin/workbenches/wait_upper_shots',['method' => 'POST|GET']],
	// [工作台] 完成列表
	'task/finish_task'    	=>      ['admin/workbenches/finish_list',['method' => 'GET']],
	// [工作台] 获取制作人列表
	'task/get_user'					=>			['admin/workbenches/get_user_list',['method'=>'GET']],
	// [工作台] 删除制作人
	'task/user_del'					=>			['admin/workbenches/delete_userid',['method'=>'POST']],
	// [资产库] 获取资产中文名称
	'asset/get_asset_name'	=>			['admin/assets/get_asset_name',['method' => 'GET']],
	// [资产库] 校验资产类型名称是否重复
	'asset/check_byname'		=>			['admin/assets/check_asset_byname',['method' => 'GET']],
	// [资产库] 制作中列表数据
	'asset/in_production'   =>      ['admin/assets/in_production_data',['method'=>'GET']],
	// [资产库] 反馈中列表数据
	'asset/feedback'        =>      ['admin/assets/feedback_data',['method'=>'GET']],
	// [资产库] 等待制作
	'asset/waiting_assets'  =>      ['admin/assets/waiting_assets_data',['method'=>'GET']],
	// [资产库] 资产暂停
	'asset/pause'           =>      ['admin/assets/pause_data',['method'=>'GET']],
	// [资产库] 资产完成
	'asset/finish'          =>      ['admin/assets/finish_data',['method'=>'GET']],
	// [资产库] 获取工作室列表
	'asset/get_studio'      =>      ['admin/assets/get_studio_list',['method'=>'GET']],
	// [资产库] 删除环节
	'asset/tache_del'				=>			['admin/assets/delete_tache',['method'=>'POST']],
	// [资产库] 删除工作室
	'asset/studio_del'			=>			['admin/assets/delete_studio',['method'=>'POST']],
	// [资产库] 标准列表
	'asset/index_list'			=>			['admin/assets/index_list',['method' => 'GET']],





	// MISS路由
	'__miss__'  => 'admin/base/miss',
];