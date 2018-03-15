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

    //获取列表
    public function getDataList($keyword, $page, $limit,$uid){
        //差一个根据用户查看能看的项目 根据项目创建的工作室，与用户所属的工作室关联，可能需要两层遍历
        $group_id = Access::where('user_id',$uid)->value('group_id');
        $where = [];
        if (!empty($keyword['studio_ids'])) {   //工作室
            $studio_ids = implode(",",$keyword['studio_ids']);
            $where['studio_ids'] = ['in', $studio_ids];
        }
        if(!empty($keyword['status'])){ //项目状态
            $where['status'] = $keyword['status'];
        }
        $dataCount = $this->where($where)->count('id');
        $list = $this->where($where);
        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }
        $list = $list ->select();
        foreach($list as $key=>$value){
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
        return $data;
    }

    /**
     * 新增数据
     * @param array $param
     * @return bool
     * @author zjs 2018/3/6
     */
    public function addData($param){
        $aspect_ratio_arr = [1=>'16:9',2=>'10:5',3=>'1:1'];//遮幅比
        $resolutic_arr = [1=>'1024*768',2=>'2560*1680'];  //分辨率
        $frame_rate_arr = [1=>'24fps',2=>'25fps']; //项目帧率
        file_put_contents('aa.txt',var_export($param,true));
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
                /*$redis = new RedisPackage();
                $cmd = "python /var/www/html/tronPipelineScript/createDirPath/parser.py $str ";
                $redis::LPush("pyFile",$cmd);*/
                return true;
            }
        }catch(\Exception $e){
            $this->error = '添加失败';
            return false;
        }
    }

    /**
     * @param string $id
     * @return bool|static
     * @throws \think\exception\DbException
     * @author zjs 2018/3/15
     */
    public function getData_ById($id = ''){
        $data = $this->get($id);
        if (!$data){
            $this->error = '暂无此数据';
            return false;
        }
        return $data;
    }


}