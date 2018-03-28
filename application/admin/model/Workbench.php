<?php
namespace app\admin\model;
use think\Db;
use app\common\model\Common;

class Workbench extends Common{

    protected $table = 'oa_task';
    protected $name = 'task';
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
     * @author zjs 2018/3/26
     */
    public function getList($keyword, $page, $limit,$uid,$group_id){
        $where = [];
        $user_obj = User::get($uid);
        //任务列表 暂定为 $keyword['list_type'] = 1;
        //等待上游列表 $keyword['list_type'] = 2;
        //完成列表 $keyword['list_type'] = 3;
        switch ($keyword['list_type']){
            case 1:
                /**
                 * 项目ID为数组转成字符串，以逗号分割
                 * 一、除工作室角色外的角色，获取当前用户包含的项目ID 可能为多个值
                 * 1.四大状态
                 * 二、工作室角色内的所有角色，根据当前用户所属工作室，获取包含的项目ID
                 * 1.四大状态
                 * 项目ID为数组转成字符串，以逗号分割
                 */
                if($group_id == 1 || $group_id == 2|| $group_id == 3 || $group_id == 4){
                    $project_where['producer|scene_producer|scene_director|visual_effects_boss|visual_effects_producer|inside_coordinate'] = ['like','%'.$uid.'%'];
                    $project_ids_data = Project::where($project_where)->field('id')->select();
                    foreach($project_ids_data as $key=>$value){
                        $project_id_arr[] = $value['id'];
                    }
                    $project_ids = implode(",",$project_id_arr);
                    $where['project_id'] = ['in',$project_ids];
                }elseif($group_id == 5 || $group_id == 6 || $group_id == 7){//工作室内角色 暂时为5，6，7
                    $where['studio_id'] = $user_obj->studio_id;
                }
                //加入条件查询
                if(!empty($keyword['project_id'])){
                    $where['project_id'] = $keyword['project_id'];
                }
                if(!empty($keyword['field_id'])){
                    $where['field_id'] = $keyword['field_id'];
                }
                $dataCount = $this->where($where)->count('id'); //全部数量
                //制作中 in_production
                $in_production_list = $this->where($where)->where('status',5);
                //反馈中 feedback
                $feedback_list = $this->where($where)->where('status',15);
                //提交发布 submit
                $submit_list = $this->where($where)->where('status',25);
                //等待制作 wait_production
                $wait_production_list = $this->where($where)->where('status',1);
                // 若有分页
                if($page && $limit){
                    //暂定为总页数为40 /每列显示10条数据 $limit 10
                    $every_limit = intval($limit)/4;
                    $in_production_list = $in_production_list->page($page,$every_limit);
                    $feedback_list = $feedback_list->page($page,$every_limit);
                    $submit_list = $submit_list->page($page,$limit);
                    $wait_production_list = $wait_production_list->page($page,$every_limit);
                    $list_data = array_merge($in_production_list,$feedback_list,$submit_list,$wait_production_list);
                }else{
                    $list_data = $this->where($where)->select();
                }
                break;
            case 2:
                break;
            case 3:
                break;
        }

        $data['list'] = $list_data;
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