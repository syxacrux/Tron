<?php

namespace app\admin\validate;
use think\Validate;
/**
 * 设置模型
 */
class Studio extends Validate{

    protected $rule = array(
        'studio_name'      => 'require',
    );
    protected $message = array(
        'studio_name.require'    		=> '工作室必须填写',
    );
}