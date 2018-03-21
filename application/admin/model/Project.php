<?php
namespace app\admin\model;
use app\common\model\Common;
use redis\RedisPackage;

class Project extends Common{
    protected $name = "admin_project";
    protected $resolutic_arr = [1=>'1024*768',2=>'2560*1680'];  //分辨率
    protected $frame_rate_arr = [1=>'24fps',2=>'25fps']; //项目帧率
    protected $aspect_ratio_arr = [1=>'16:9',2=>'10:5',3=>'1:1'];   //遮幅比
    protected $status_arr = [0=>'未开始',1=>'等待中',2=>'制作中',3=>'暂停',4=>'完成']; //项目状态
    protected $movies_type_arr = [0=>'未选择',1=>'电影',2=>'电视剧'];

    //获取列表
    public function getList($keywords){
        $where = [];
        if (!empty($keywords['studio_ids'])) {   //工作室
            $studio_ids = implode(",",$keywords['studio_ids']);
            $where['studio_ids'] = ['in', $studio_ids];
        }
        if(!empty($keywords['status'])){ //项目状态
            $where['status'] = $keywords['status'];
        }
        $dataCount = $this->where($where)->count('id');
        $waitingCount = $this->where('status',1)->count('id');  //等待中
        $workingCount = $this->where('status',2)->count('id');  //制作中
        $suspendCount = $this->where('status',3)->count('id');  //暂停
        $finishCount = $this->where('status',4)->count('id');   //完成

        $big_time_where['plan_end_timestamp'] = ['>=',time()];   //大于的当前时间
        $small_time_where['plan_end_timestamp'] = ['<=',time()]; //小于当前时间
        $big_project_data = $this->where($where)->where($big_time_where)->select();
        $small_project_data = $this->where($where)->where($small_time_where)->select();
        //合成数据 当前时间与计划结束时间最小的，排前边
        $list = array_merge($big_project_data,$small_project_data);
        foreach($list as $key=>$value){
            $list[$key]['movies_type'] = $this->movies_type_arr[$value['movies_type']];  //影视类型
            $list[$key]['resolutic'] = $this->resolutic_arr[$value['resolutic']];   //分辨率
            $list[$key]['frame_rate'] = $this->frame_rate_arr[$value['frame_rate']];   //项目帧率
            $list[$key]['aspect_ratio'] = $this->aspect_ratio_arr[$value['aspect_ratio']]; //遮幅比
            $list[$key]['producer'] = !empty($value['producer']) ? : ''; //负责制片人
            $list[$key]['scene_producer'] = !empty($value['scene_producer']) ? User::getName_ById($value['scene_producer'],'realname',',') : '';//现场制片人
            $list[$key]['scene_director'] = !empty($value['scene_director']) ? User::getName_ById($value['scene_director'],'realname',','): '';//现场指导人
            $list[$key]['visual_effects_boss'] = !empty($value['visual_effects_boss']) ? User::getName_ById($value['visual_effects_boss'],'realname',',') : '';//视效总监
            $list[$key]['visual_effects_producer'] = !empty($value['visual_effects_producer']) ? User::getName_ById($value['visual_effects_producer'],'realname',',') : '';//视效总制片
            $list[$key]['second_company_producer'] = !empty($value['second_company_producer']) ? User::getName_ById($value['second_company_producer'],'realname',',') : '';//二级公司制片
            $list[$key]['inside_coordinate'] = !empty($value['inside_coordinate']) ? User::getName_ById($value['inside_coordinate'],'realname',',') : '';//内部协调制片
            $list[$key]['plan_start_time'] = date("Y-m-d H:i:s",$value['plan_start_timestamp']);   //计划开始时间
            $list[$key]['plan_end_time'] = date("Y-m-d H:i:s",$value['plan_end_timestamp']);   //计划结束时间
            $list[$key]['status_name'] = $this->status_arr[$value['status']];   //状态显示
            $list[$key]['create_time'] = date("Y-m-d H:i:s",$value['create_time']);
        }
        $data['list'] = $list;
        $data['dataCount'] = $dataCount;
        $data['nobeginCount'] = $nobeginCount;
        $data['waitingCount'] = $waitingCount;
        $data['workingCount'] = $workingCount;
        $data['suspendCount'] = $suspendCount;
        $data['finishCount'] = $finishCount;
        return $data;
    }

    /**
     * 新增数据
     * @param array $param
     * @return bool
     * @author zjs 2018/3/6
     */
    public function addData($param){
        try{
            $param['project_image'] = str_replace('\\','/',$param['project_image']);
            $param['plan_start_timestamp'] = strtotime($param['plan_start_timestamp']);
            $param['plan_end_timestamp'] = strtotime($param['plan_end_timestamp']);
            $param['create_time'] = time();
            $result =  $this->validate($this->name)->save($param);
            $str = 'Project '.$param['project_byname'];
            /*
            $cmd = "python /var/www/html/tronPipelineScript/createDirPath/parser.py $str ";
            system($cmd);*/
            if(false === $result){
                $this->error = $this->getError();
                return false;
            }else{
                //执行外部程序-开启队列
                /*
                $redis = new RedisPackage();
                $cmd = "python /var/www/html/tronPipelineScript/createDirPath/parser.py $str ";
                $redis::LPush("pyFile",$cmd);
                */
                return true;
            }
        }catch(\Exception $e){
            $this->error = '添加失败';
            return false;
        }
    }

    /**
     * 根据项目ID获取所有数据
     * @param string $id
     * @param string $uid
     * @param string $group_id
     * @return bool|static
     * @throws \think\exception\DbException
     * @author zjs 2018/3/16
     */
    public function getData_ById($id = ''){
        $data = $this->get($id);
        if (!$data){
            $this->error = '暂无此数据';
            return false;
        }
        return $data;
    }

    /**
     * 删除单个项目
     * @param $id
     * @return bool
     * @author zjs 2018/3/15
     */
    public function delProject($id){
        try{
            $this->where('id', $id)->delete();
            //删除所属项目的所有镜头
            //删除所属镜头的所有任务
            return true;
        }catch( \Exception $e){
            $this->error = '删除失败';
            return false;
        }
    }


}