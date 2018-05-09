<?php

namespace app\admin\validate;
use think\Validate;
/**
 * 设置模型
 */
class SystemParameter extends Validate{

	protected $rule = array(
		'category'      => 'require',
		'sort'			=> 'require',
	);
	protected $message = array(
		'category.require'    => '配置项名称必须填写',
		'sort.require'				=> '配置项排序必须填写',
	);
}