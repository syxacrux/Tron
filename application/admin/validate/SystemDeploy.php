<?php

namespace app\admin\validate;
use think\Validate;
/**
 * 设置模型
 */
class SystemDeploy extends Validate{

	protected $rule = array(
		'name'      => 'require',
		'sort'			=> 'require',
	);
	protected $message = array(
		'name.require'    		=> '配置项名称必须填写',
		'sort.require'				=> '配置项排序必须填写',
	);
}