<?php

namespace app\admin\validate;
use think\Validate;
/**
 * 设置模型
 */
class Tache extends Validate{

    protected $rule = array(
        'tache_name'      => 'require',
    );
    protected $message = array(
        'tache_name.require'    		=> '环节必须填写',
    );
}