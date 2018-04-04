<?php

namespace app\admin\validate;

use think\Validate;

/**
 * 设置模型
 */
class Shot extends Validate
{

	protected $rule = array(
		'shot_image' => 'require',
		'shot_number' => 'require',
		'shot_byname' => 'require',
		'shot_name' => 'require',
	);
	protected $message = array(
		'shot_image.require' => '镜头缩略图必须上传',
		'shot_number.require' => '镜头编号必须填写',
		'shot_byname.require' => '镜头简称必须填写',
		'shot_name.require' => '镜头名称必须填写',
	);
}