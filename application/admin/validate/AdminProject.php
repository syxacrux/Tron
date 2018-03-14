<?php

namespace app\admin\validate;
use think\Validate;
/**
 * 设置模型
 */
class AdminProject extends Validate{

    protected $rule = array(
        'project_image'      => 'require',
        'project_name'       => 'require',
    );
    protected $message = array(
        'project_image.require'    		=> '项目缩略图必须上传',
        'project_name.require'          => '项目名称必须填写',

    );
}