<?php
namespace app\admin\model;
use app\common\model\Common;

class Shot extends Common{

    protected $name = 'admin_shot';

    /**
     * 获取列表
     * @param $keyword
     * @param $page
     * @param $limit
     * @param $uid
     * @param $group_id int 所属角色
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author zjs 2018/3/16
     */
    public function getList($keyword, $page, $limit,$uid,$group_id){
        $where = [];
        //区分工作室总监只能查看所属工作室
        if($group_id == 5){
            $where['id'] = User::get($uid)->studio_id;
        }
        if ($keyword) {
            $where['name'] = ['like', '%'.$keyword.'%'];
        }
        $dataCount = $this->where($where)->count('id');
        $list = $this->where($where);
        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }
        $list = $list ->select();
        $data['list'] = $list;
        $data['dataCount'] = $dataCount;
        return $data;
    }


    /**
     * 多工作室ID字符串转换相对应的工作室名称，以逗号分割
     * @param $ids
     * @param $tag
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author zjs 2018/3/13
     */
    public static function get_studio_names($ids,$value,$tag){
        if(!empty($ids)){
            $studio_ids = explode(',',$ids);
            foreach($studio_ids as $key=>$val){
                $res[] = self::where('id',$val)->value($value);
            }
            $data = implode($tag,$res);
        }else{
            $data = '';
        }
        return $data;
    }

}