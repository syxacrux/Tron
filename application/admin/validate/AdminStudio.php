<?php

namespace app\admin\validate;
use think\Validate;
/**
 * 设置模型
 */
class AdminStudio extends Validate{

    protected $rule = array(
        'name'      => 'require',
    );
    protected $message = array(
        'name.require'    		=> '工作室必须填写',
    );
}