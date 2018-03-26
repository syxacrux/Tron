<?php
namespace app\admin\model;
use think\Db;
use app\common\model\Common;

class Shot extends Common{

    protected $name = 'shot';
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

    public function addData($param){
        try{
            $param['asset_ids'] = implode(",",$param['asset_ids']);    //资产ID 多项 字符串 以逗号分割
            $param['shot_image'] = str_replace('\\','/',$param['shot_image']);
            $param['plan_start_timestamp'] = strtotime($param['plan_start_timestamp']);
            $param['plan_end_timestamp'] = strtotime($param['plan_end_timestamp']);
            $param['actual_start_timestamp'] = strtotime($param['actual_start_timestamp']);
            $param['actual_end_timestamp'] = strtotime($param['actual_end_timestamp']);
            $param['create_time'] = time();
            //保存镜头表
            $result =  $this->validate($this->name)->save($param);
            if(false === $result){
                $this->error = $this->getError();
                return false;
            }else{
                $project_byname = Project::get($param['project_id'])->project_byname;
                $field_name = Db::name('field')->where('id',$param['field_id'])->value('name');
                //执行redis添加镜头所属目录 python
                $str = "'Shot' '{$project_byname}' '{$field_name}' '{$param['shot_name']}'";
                //exec_python($str);
                //根据环节分配任务给各大工作室

                return true;
            }
        }catch(\Exception $e){
            $this->error = '添加失败';
            return false;
        }
    }

    /**
     * 添加场号/集号
     * @param $param
     * @return bool
     * @author zjs 2018/3/21
     */
    public function addFieldData($param){
        file_put_contents('aa.txt',var_export(123,true));
        try{

            $where = [];
            $where['project_id'] = $param['project_id'];
            $where['name'] = $param['name'];
            $check_name = Db::name('field')->where($where)->find();
            if(!empty($check_name)){
                $this->error = "名称重复，请重新填写";
                return false;
            }else{
                file_put_contents('aa.txt',var_export($param,true));
                Db::name('field')->insert($param);
            }
        }catch(\Exception $e){
            $this->error = '添加失败1';
            return false;
        }
    }


}