<?php
namespace app\admin\model;
use think\Db;
use app\common\model\Common;

class Shot extends Common{

    protected $name = 'shot';
    protected $priority_level_arr = [1=>'D',2=>'C',3=>'B',4=>'A'];   //镜头优先级
    protected $difficulty_arr = [1=>'D',2=>'C',3=>'B',4=>'A',5=>'S']; //镜头难度
    protected $time_arr = [1=>'白天',2=>'晚上'];   //时刻(1白天 2夜晚)
    protected $ambient_arr = [1=>'室外',2=>'室内'];    //环境(1外 2内)
    protected $task_status_arr = [1=>0,5=>20,10=>40,15=>60,20=>80,25=>100];    //用于任务状态计算进度百分比 status=>0%

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
     * 根据镜头状态与是否暂停状态获取列表数据
     * @param $page
     * @param $limit
     * @param string $status
     * @param string $is_assets 是否为等待资产 1是 2否
     * @param string $is_pause  是否暂停 1 非暂停 2暂停
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author zjs 2018/3/28
     */
    public function getList_byStatus($page,$limit,$status,$is_assets,$is_pause){
        $where = [];
        $where['status'] = $status;
        $where['is_assets'] = $is_assets;
        $where['is_pause'] = $is_pause;

        $dataCount = $this->where($where)->count('id');
        $list = $this->where($where)->order('plan_end_timestamp desc');
        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }
        $list = $list ->select();
        foreach($list as $key=>$value){
            $list[$key]['project_name'] = Project::get($value['project_id'])->project_byname;
            $list[$key]['shot_number'] = Db::name('field')->where('id',$value['field_id'])->value('name').$value['shot_number'];
            $list[$key]['priority_level'] = $this->priority_level_arr[$value['priority_level']];    //镜头优先级
            $list[$key]['difficulty'] = $this->difficulty_arr[$value['difficulty']];    //镜头难度
            $list[$key]['time'] = $this->time_arr[$value['time']];  //时刻
            $list[$key]['ambient'] = $this->ambient_arr[$value['ambient']]; //环境
            $list[$key]['surplus_days'] = floatval(sprintf("%.2f",($value['plan_end_timestamp']-time())/86400));   //剩余天数
            $list[$key]['create_timestamp'] = $value['create_time'];
            $list[$key]['create_time'] = date("Y-m-d H:i:s",$value['create_time']);
            //$list[$key]['tache_info'] = $this->rate_of_progress($value['id']);
        }
        $data['list'] = $list;
        $data['dataCount'] = $dataCount;
        return $data;
    }

    //添加镜头及任务
    public function addData($param,$uid,$group_id){
        //开启事务
        $this->startTrans();
        try{
            //资产ID 多项 字符串 以逗号分割(现在没有不加资产)
            //$param['asset_ids'] = implode(",",$param['asset_ids']);
            $param['shot_image'] = str_replace('\\','/',$param['shot_image']);
            $param['plan_start_timestamp'] = strtotime($param['plan_start_timestamp']);
            $param['plan_end_timestamp'] = strtotime($param['plan_end_timestamp']);
            $param['create_time'] = time();
            foreach($param['tache'] as $key=>$value){
                if(!empty($value)){
                    $tache_data[$key] = $value;
                }
            }
            //保存镜头表
            $result =  $this->allowField(true)->save($param);
            //获取自增ID的镜头对象
            $curr_shot_obj = $this->get($this->id);
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
                foreach($tache_data as $key=>$val){
                    foreach($val as $k=>$v){
                        $task_data['group_id'] = $group_id ;    //所属角色ID
                        $task_data['user_id'] = $uid;   //所属用户ID
                        $task_data['project_id'] = $curr_shot_obj->project_id;   //所属项目ID
                        $task_data['field_id'] = $curr_shot_obj->field_id;   //场号ID
                        $task_data['shot_id'] = $this->id;  //镜头ID
                        $task_data['tache_id'] = $key;  //环节ID
                        $task_data['tache_sort'] = Tache::get($key)->sort;  //环节排序
                        $task_data['studio_id'] = $v;   //工作室ID
                        $task_data['task_type'] = 1;    //镜头类型
                        $task_data['shot_image'] = $curr_shot_obj->shot_image;
                        $task_data['task_byname'] = $curr_shot_obj->shot_byname;//任务简称暂且为镜头的简称，任务模块中，可修改
                        $task_data['task_priority_level'] = $curr_shot_obj->priority_level;   //任务优先级
                        $task_data['difficulty'] = $curr_shot_obj->difficulty;    //任务难度
                        $task_data['plan_start_timestamp'] = $curr_shot_obj->plan_start_timestamp;  //计划开始时间
                        $task_data['plan_end_timestamp'] = $curr_shot_obj->plan_end_timestamp;    //计划结束时间
                        $task_data['task_status'] = 1;  //任务状态
                        $task_data['is_assets'] = 2; //是否为等待资产 1是 2否
                        $task_data['pid'] = 0;  //工作室顶级任务ID都为0
                        $task_data['create_time'] = time();//创建时间
                        Db::name('task')->insert($task_data);
                    }
                }
                $this->commit();
                return true;
            }
        }catch(\Exception $e){
            $this->rollback();
            $this->error = '添加失败';
            return false;
        }
    }

    //获取当前镜头各环节进度
    public function rate_of_progress($shot_id){
        $where = [];
        $where['shot_id'] = $shot_id;
        $where['pid'] = ['neq',0];
        $tache_arr = array_unique(Workbench::where('shot_id',$shot_id)->where('pid','!=',0)->field('tache_id')->order('tache_sort asc')->select());
        foreach($tache_arr as $key=>$value){
            //$studio_task_data[]
        }
        file_put_contents('aa.txt',var_export($tache_arr,true));
    }


}