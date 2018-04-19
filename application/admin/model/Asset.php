<?php
namespace app\admin\model;

use think\Db;
use app\common\model\Common;

class Asset extends Common{
	protected $name = 'asset';
	protected $priority_level_arr = [1 => 'D', 2 => 'C', 3 => 'B', 4 => 'A'];   //镜头优先级
	protected $difficulty_arr = [1 => 'D', 2 => 'C', 3 => 'B', 4 => 'A', 5 => 'S']; //镜头难度
	protected $task_status_arr = [1 => 0, 5 => 20, 10 => 40, 15 => 60, 20 => 80, 25 => 100];    //用于任务状态计算进度百分比 status=>0%

	//资产概括
	public function survey_list($keywords){
		$where = [];
		if(!empty($keywords['project_id']) && empty($keywords['asset_name'])){
			$where['project_id'] = $keywords['project_id'];
		}
		if(!empty($keywords['field_id']) && empty($keywords['asset_name'])){
			$where['field_id'] = $keywords['field_id'];
		}
		if(!empty($keywords['asset_id']) && empty($keywords['asset_name'])){
			$where['id'] = $keywords['asset_id'];
		}
		if(!empty($keywords['asset_content'])){	//手动输入资产名称或简称
			$where['asset_byname|asset_name'] = trimall($keywords['asset_content']);
		}
		$in_production_count = $this->where($where)->where('status',5)->count('id');	//制作中
		$feedback_count = $this->where($where)->where('status',15)->count('id');	//反馈中
		$pause_count = $this->where($where)->where('is_pause',2)->count('id');	//暂停
		$finish_count = $this->where($where)->where('status','in',[25,30])->count('id');	//完成
		$data['in_production_count'] = $in_production_count;
		$data['feedback_count'] = $feedback_count;
		$data['pause_count'] = $pause_count;
		$data['finish_count'] = $finish_count;
		return $data;
	}

	//标准列表
	public function getList(){

	}

	//根据镜头状态与是否暂停状态获取列表数据
	public function getList_byStatus(){

	}


}