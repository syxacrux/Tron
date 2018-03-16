<?php
// +----------------------------------------------------------------------
// | Description: 用户角色关联
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------
namespace app\admin\model;
use think\Db;
use app\common\model\Common;

class Access extends Common{
    protected $name = 'admin_access';

    /**
     * 根据用户ID获取角色ID
     * @param $uid
     * @return mixed
     * @author zjs 2018/3/16
     */
    public static function getData_ById($uid){
        $data = self::where('user_id',$uid)->value('group_id');
        return $data;
    }
}