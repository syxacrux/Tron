<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\model;
use think\Db;
use app\common\model\Common;

class Group extends Common 
{
    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     */
	protected $name = 'admin_group';

    /**
     * @param $keyword
     * @param $page
     * @param $limit
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author zjs 2018/3/16
     */
	public function getDataList($keyword, $page, $limit){
        $where = [];
        if ($keyword) {
            $where['title|remark'] = ['like', '%'.$keyword.'%'];
        }
        $dataCount = $this->where($where)->count('id');
        $list = $this->where($where);
        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }
        $list = $list->select();
        $data['list'] = $list;
        $data['dataCount'] = $dataCount;
        return $data;
	}

    /**
     * 根据用户ID获取角色的值
     * @param $user_id int 用户主键
     * @return mixed
     * @author zjs 2018/3/16
     */
	public static function getGroupData($user_id,$value){
        $group_id = Access::where('user_id',$user_id)->value('group_id');
        $group_data = self::where('id',$group_id)->value($value);
        return $group_data;
    }
}