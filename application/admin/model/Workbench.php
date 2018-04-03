<?php

namespace app\admin\model;

use think\Db;
use app\common\model\Common;

class Workbench extends Common
{

	protected $table = 'oa_task';
	protected $name = 'task';

	protected $task_priority_level_arr = [1 => 'D', 2 => 'C', 3 => 'B', 4 => 'A'];   //任务优先级
	protected $difficulty_arr = [1 => 'D', 2 => 'C', 3 => 'B', 4 => 'A', 5 => 'S']; //任务难度
	protected $task_status_degree_arr = [1 => 0, 5 => 20, 10 => 40, 15 => 60, 20 => 80, 25 => 100];    //用于任务状态计算进度百分比 status=>0%
	//根据环节ID获取镜头页面进度条所用别名
	protected $tache_byname_arr = [3 => '美术', 4 => '模型', 5 => '贴图', 6 => '绑定', 7 => '跟踪', 8 => '动画', 9 => '数绘', 10 => '特效', 11 => '灯光', 12 => '合成'];
	protected $status_cn_arr = ['等待制作' => 1, '制作中' => 5, '等待审核' => 10, '反馈中' => 15, '审核通过' => 20, '提交发布' => 25, '完成' => 30];
	protected $status_arr = [1 => '等待制作', 5 => '制作中', 10 => '等待审核', 15 => '反馈中', 20 => '审核通过', 25 => '提交发布', 30 => '完成'];

	public function project(){
		return $this->hasOne('Project','project_id')->field('project_name,project_byname');
	}

	public function shot(){
		return $this->hasOne('Shot','shot_id')->field('shot_number,shot_byname,shot_name');
	}

	public function getList($keywords, $page, $limit, $uid, $group_id)
	{
		$where = [];
		$user_obj = User::get($uid);
		/**
		 * 项目ID为数组转成字符串，以逗号分割
		 * 一、除工作室角色外的角色，获取当前用户包含的项目ID 可能为多个值
		 * 1.四大状态
		 * 二、工作室角色内的所有角色，根据当前用户所属工作室，获取包含的项目ID
		 * 1.四大状态
		 * 项目ID为数组转成字符串，以逗号分割
		 */
		if ($group_id == 1 || $group_id == 2 || $group_id == 3 || $group_id == 4) {
			$project_where['producer|scene_producer|scene_director|visual_effects_boss|visual_effects_producer|inside_coordinate'] = ['like', '%' . $uid . '%'];
			$project_ids_data = Project::where($project_where)->field('id')->select();
			if (!empty($project_ids_data)) {
				foreach ($project_ids_data as $key => $value) {
					$project_id_arr[] = $value['id'];
				}
				$project_ids = implode(",", $project_id_arr);
				$where['project_id'] = ['in', $project_ids];
			} else {  //超级管理员 uid =1
				$where = [];
			}
		} elseif ($group_id == 5 || $group_id == 6) {//工作室内角色 暂时为5 工作室总监，6组长
			$where['studio_id'] = $user_obj->studio_id;
		} elseif ($group_id == 7) {//工作室内角色  7制作人
			$where['studio_id'] = $user_obj->studio_id;
			$where['user_id'] = $uid;
		} else { // uid 为超级管理员
			$where = [];
		}
		//加入条件查询
		if (!empty($keyword['project_id'])) {
			$where['project_id'] = $keywords['project_id'];
		}
		if (!empty($keyword['field_id'])) {
			$where['field_id'] = $keywords['field_id'];
		}
		$dataCount = $this->where($where)->count('id'); //全部数量
		$list = $this->where($where);
		//若有分页
		if ($page && $limit) {
			$list = $list->page($page, $limit);
		}
		$list = $list->select();
		for ($i = 0; $i < count($list); $i++) {
			$list[$i]['project_name'] = Project::get($list[$i]['project_id'])->project_name;
			$list[$i]['field_number'] = Db::name('field')->where('id', $list[$i]['field_id'])->value('name');
			$list[$i]['shot_number'] = Shot::get($list[$i]['shot_id'])->shot_number;
			$list[$i]['difficulty'] = $this->difficulty_arr[$list[$i]['difficulty']];
			$list[$i]['task_priority_level'] = $this->task_priority_level_arr[$list[$i]['task_priority_level']];
			$list[$i]['status_cn'] = $this->status_arr[$list[$i]['task_status']];
			$list[$i]['plan_start_time'] = date("Y-m-d H:i:s", $list[$i]['plan_start_timestamp']);
			$list[$i]['plan_end_time'] = date("Y-m-d H:i:s", $list[$i]['plan_end_timestamp']);
			$list[$i]['actually_start_time'] = ($list[$i]['actually_start_timestamp'] !=0 ) ? date("Y-m-d H:i:s", $list[$i]['actually_start_timestamp']): '';
			$list[$i]['actually_end_time'] = ($list[$i]['actually_end_timestamp'] != 0) ? date("Y-m-d H:i:s", $list[$i]['actually_end_timestamp']) : '';
		}
		$data['list'] = $list;
		$data['dataCount'] = $dataCount;
		return $data;
	}

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
	public function getTaskList($keyword, $page, $limit, $uid, $group_id)
	{
		$where = [];
		$user_obj = User::get($uid);
		//任务列表
		/**
		 * 项目ID为数组转成字符串，以逗号分割
		 * 一、除工作室角色外的角色，获取当前用户包含的项目ID 可能为多个值
		 * 1.四大状态
		 * 二、工作室角色内的所有角色，根据当前用户所属工作室，获取包含的项目ID
		 * 1.四大状态
		 * 项目ID为数组转成字符串，以逗号分割
		 */
		if ($group_id == 1 || $group_id == 2 || $group_id == 3 || $group_id == 4) {
			$project_where['producer|scene_producer|scene_director|visual_effects_boss|visual_effects_producer|inside_coordinate'] = ['like', '%' . $uid . '%'];
			$project_ids_data = Project::where($project_where)->field('id')->select();
			if (!empty($project_ids_data)) {
				foreach ($project_ids_data as $key => $value) {
					$project_id_arr[] = $value['id'];
				}
				$project_ids = implode(",", $project_id_arr);
				$where['project_id'] = ['in', $project_ids];
			} else {  //超级管理员 uid =1
				$where = [];
			}

		} elseif ($group_id == 5 || $group_id == 6) {//工作室内角色 暂时为5 工作室总监，6组长
			$where['studio_id'] = $user_obj->studio_id;
		} elseif ($group_id == 7) {//工作室内角色  7制作人
			$where['studio_id'] = $user_obj->studio_id;
			$where['user_id'] = $uid;
		} else { // uid 为超级管理员
			$where = [];
		}
		//加入条件查询
		if (!empty($keyword['project_id'])) {
			$where['project_id'] = $keyword['project_id'];
		}
		if (!empty($keyword['field_id'])) {
			$where['field_id'] = $keyword['field_id'];
		}
		$dataCount = $this->where($where)->count('id'); //全部数量

		// 若有分页
		if ($page && $limit) {
			//暂定为总页数为40 /每列显示10条数据 $limit 10
			$every_limit = intval($limit) / 4;
			$in_production_list = $this->where($where)->where('task_status', 5)->page($page, $every_limit)->select(); //制作中 in_production
			$feedback_list = $this->where($where)->where('task_status', 'in', '10,15')->page($page, $every_limit)->select();   //反馈中 feedback  等待审核 反馈中
			$submit_list = $this->where($where)->where('task_status', 25)->page($page, $every_limit)->select();  //提交发布 submit
			$wait_production_list = $this->where($where)->where('task_status', 1)->page(1, 10)->select();  //等待制作 wait_production
			$list_data = array_merge($in_production_list, $feedback_list, $submit_list, $wait_production_list);
		} else {
			$in_production_list = $this->where($where)->where('task_status', 5)->select();
			$feedback_list = $this->where($where)->where('task_status', 'in', '10,15')->select();
			$submit_list = $this->where($where)->where('task_status', 25)->select();
			$wait_production_list = $this->where($where)->where('task_status', 1)->select();
			$list_data = array_unique(array_merge($in_production_list, $feedback_list, $submit_list, $wait_production_list));
		}
		//重组数组
		foreach ($list_data as $key => $value) {
			$list_data[$key]['project_name'] = Project::get($value['project_id'])->project_byname;
			$list_data[$key]['shot_number'] = Db::name('field')->where('id', $value['field_id'])->value('name') . Shot::get($value['shot_id'])->shot_number;
			$list_data[$key]['task_priority_level'] = $this->task_priority_level_arr[$value['task_priority_level']];    //任务优先级
			$list_data[$key]['difficulty'] = $this->difficulty_arr[$value['difficulty']];   //任务难度
			$list_data[$key]['surplus_days'] = floatval(sprintf("%.2f", ($value['plan_end_timestamp'] - time()) / 86400)) . "天";   //剩余天数
			$list_data[$key]['task_allot_days'] = (!empty($value['actually_start_timestamp']) || !empty($value['actually_end_timestamp'])) ? floatval(sprintf("%.2f", ($value['actually_end_timestamp'] - $value['actually_start_timestamp']) / 86400)) . "天" : '0天';//任务分配时间
			$list_data[$key]['create_timestamp'] = $value['create_time'];
			$list_data[$key]['create_time'] = !empty($value['update_time']) ? '读任务状态记录表的最新时间' : date("Y-m-d H:i:s", $value['create_time']);
			$list_data[$key]['task_finish_degree'] = $this->rate_of_progress($value['task_status'], $value['tache_id']);//任务完成度
		}
		$data['list'] = $list_data;
		$data['dataCount'] = $dataCount;
		return $data;
	}

	//工作台 - 等待上游 资产
	public function getUpperAssets($keyword, $page, $limit, $uid, $group_id)
	{

	}

	//工作台 - 等待上游 镜头

	//工作台 - 任务完成 列表
	public function getFinishTask($keywords, $page, $limit, $uid)
	{
		$where = [];
		//加入条件查询
		if (!empty($keyword['project_id'])) {
			$where['project_id'] = $keywords['project_id'];
		}
		if (!empty($keyword['field_id'])) {
			$where['field_id'] = $keywords['field_id'];
		}
		$where['user_id'] = $uid;
		$where['task_status'] = ['in', '25,30'];  //提交发布 完成
		$dataCount = $this->where($where)->count('id'); //全部数量
		$list = $this->where($where);
		//若有分页
		if ($page && $limit) {
			$list = $list->page($page, $limit);
		}
		$list = $list->select();
		foreach ($list as $key => $value) {
			$list[$key]['create_time'] = date("Y-m-d H:i:s", $value['create_time']);
		}
		$data['list'] = $list;
		$data['dataCount'] = $dataCount;

	}

	//新增
	public function addData($param)
	{
		try {
			$param['asset_ids'] = implode(",", $param['asset_ids']);    //资产ID 多项 字符串 以逗号分割
			$param['shot_image'] = str_replace('\\', '/', $param['shot_image']);
			$param['plan_start_timestamp'] = strtotime($param['plan_start_timestamp']);
			$param['plan_end_timestamp'] = strtotime($param['plan_end_timestamp']);
			$param['actual_start_timestamp'] = strtotime($param['actual_start_timestamp']);
			$param['actual_end_timestamp'] = strtotime($param['actual_end_timestamp']);
			$param['create_time'] = time();
			$result = $this->validate($this->name)->save($param);
			if (false === $result) {
				$this->error = $this->getError();
				return false;
			} else {
				$project_byname = Project::get($param['project_id'])->project_byname;
				$field_name = Db::name('field')->where('id', $param['field_id'])->value('name');
				//执行redis添加镜头所属目录 python
				$str = "'Shot' '{$project_byname}' '{$field_name}' '{$param['shot_name']}'";
				//exec_python($str);
				return true;
			}
		} catch (\Exception $e) {
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
	public function addFieldData($param)
	{
		try {
			$where = [];
			$where['project_id'] = $param['project_id'];
			$where['name'] = $param['name'];
			$check_name = Db::name('field')->where($where)->find();
			if (!empty($check_name)) {
				$this->error = "名称重复，请重新填写";
				return false;
			} else {
				Db::name('field')->insert($param);
			}
		} catch (\Exception $e) {
			$this->error = '添加失败';
			return false;
		}
	}

	//根据任务状态组合完成进度数据
	public function rate_of_progress($status, $tache_id)
	{
		$data['tache_id'] = $tache_id;
		$data['tache_byname'] = $this->tache_byname_arr[$tache_id];
		$data['finish_degree'] = $this->task_status_degree_arr[$status];
		return $data;
	}

	//任务状态改变并记录
	public function change_task_status($task_id, $status, $uid)
	{
		$task_status['task_status'] = $this->status_cn_arr[$status];
		$curr_task_data = $this->get($task_id);
		if (!$curr_task_data) {
			$this->error = '暂无此数据';
			return false;
		}
		try {
			$this->save($task_status, [$this->getPk() => $task_id]);
			//记录状态更新记录
			$task_status_record['user_id'] = $uid;
			$task_status_record['task_id'] = $task_id;
			$task_status_record['task_status'] = $task_status['task_status'];
			$task_status_record['create_timestamp'] = time();
			$task_status_record['create_time'] = date("Y-m-d H:i:s");
			Db::name('task_state_record')->insert($task_status_record);
			return true;
		} catch (\Exception $e) {
			$this->error = '更新状态失败';
			return false;
		}
	}

	//根据主键获取数据
	public function getData_ById($task_id)
	{
		$task_obj = $this->get($task_id);
		if(!$task_obj){
			$this->error = '暂无此数据';
			return false;
		}
		//$project_obj = Project::get($task_obj->project_id);
		$task_obj->project_name = $this->project->project_name;
		$task_obj->project_byname = $this->project->project_byname;
		$task_obj->field_number = Db::name('field')->where('id',$task_obj->field_id)->value('name');
		$task_obj->shot_number = $this->shot->shot_number;
		$task_obj->shot_byname = $this->shot->shot_byname;
		$task_obj->shot_name = $this->shot->shot_name;
		$task_obj->difficulty_name = $this->difficulty_arr[$task_obj->difficulty];
		$task_obj->task_priority_level_name = $this->task_priority_level_arr[$task_obj->task_priority_level];
		return $task_obj;
	}


}