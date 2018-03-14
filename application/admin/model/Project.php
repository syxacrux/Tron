<?php
namespace app\admin\model;
use app\common\model\Common;

class Project extends Common{
    protected $name = "admin_project";

    //获取列表
    public function getDataList($keyword, $page, $limit){
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
        $header = Request::instance();
        file_put_contents('aa.txt',var_export($header,true));
        try{
            $param['plan_start_timestamp'] = strtotime($param['plan_start_timestamp']);
            $param['plan_end_timestamp'] = strtotime($param['plan_end_timestamp']);
            $param['uid'] = $param[''];
            $param['create_time'] = $param[''];
            $result =  $this->validate($this->name)->save($param);
            if(false === $result){
                $this->error = $this->getError();
                return false;
            }else{
                $str = 'Project '.$param['project_byname'];
                //执行外部程序-开启队列 //common.php
                exec_python_file($str);
                return true;
            }
        }catch(\Exception $e){
            $this->error = '添加失败';
            return false;
        }
    }


}