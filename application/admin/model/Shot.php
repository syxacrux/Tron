<?php
namespace app\admin\model;
use think\Db;
use app\common\model\Common;

class Shot extends Common{

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
     * 添加场号/集号
     * @param $param
     * @return bool
     * @author zjs 2018/3/21
     */
    public function addFieldData($param){
        try{
            $where = [];
            $where['project_id'] = $param['project_id'];
            $where['name'] = $param['name'];
            $check_name = Db::name('field')->where($where)->find();
            if(!empty($check_name)){
                $this->error = "名称重复，请重新填写";
                return false;
            }else{
                Db::name('field')->insert($param);
            }
        }catch(\Exception $e){
            $this->error = '添加失败';
            return false;
        }
    }


}