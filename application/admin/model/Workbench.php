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
			$project_ids_arr = Project::where($project_where)->column('id');
			if (!empty($project_ids_data)) {
				$project_ids = implode(",", $project_ids_arr);
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
		if (!empty($keywords['project_id'])) {
			$where['project_id'] = $keywords['project_id'];
		}
		if (!empty($keywords['field_id'])) {
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
			$list[$i]['shot_number'] = ($list[$i]['task_type'] == 1) ? Shot::get($list[$i]['shot_id'])->shot_number : Asset::get($list[$i]['asset_id'])->asset_name;
			$list[$i]['difficulty'] = $this->difficulty_arr[$list[$i]['difficulty']];
			$list[$i]['task_priority_level'] = $this->task_priority_level_arr[$list[$i]['task_priority_level']];
			$list[$i]['status_cn'] = $this->status_arr[$list[$i]['task_status']];
			$list[$i]['plan_start_time'] = date("Y-m-d H:i:s", $list[$i]['plan_start_timestamp']);
			$list[$i]['plan_end_time'] = date("Y-m-d H:i:s", $list[$i]['plan_end_timestamp']);
			$list[$i]['actually_start_time'] = ($list[$i]['actually_start_timestamp'] != 0) ? date("Y-m-d H:i:s", $list[$i]['actually_start_timestamp']) : '';
			$list[$i]['actually_end_time'] = ($list[$i]['actually_end_timestamp'] != 0) ? date("Y-m-d H:i:s", $list[$i]['actually_end_timestamp']) : '';
			$list[$i]['user_name'] = ($list[$i]['user_id'] != 0) ? User::get($list[$i]['user_id'])->realname : $this->getUserName_ById($list[$i]['id']);;
		}
		$data['list'] = $list;
		$data['dataCount'] = $dataCount;
		return $data;
	}

	//获取当前任务的制作人 拼成字符串 顶级任务 user_di=0 查询任务是否已分配人
	public function getUserName_ById($task_id)
	{
		$user_id_arr = $this->where('pid', $task_id)->column('user_id');
		$data = "任务未分配制作人";
		if (!empty($user_id_arr)) {
			foreach ($user_id_arr as $key => $value) {
				$res[] = User::where('id', $value)->value('realname');
			}
			$data = implode(',', $res);
		}
		return $data;
	}

	/**
	 * 获取列表
	 * @param $keywords
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
	public function getTaskList($keywords, $page, $limit, $uid, $group_id)
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
			$project_ids_arr = Project::where($project_where)->column('id');
			if (!empty($project_ids_arr)) {
				$project_ids = implode(",", $project_ids_arr);
				$where['project_id'] = ['in', $project_ids];
				$where['studio_id'] = 0;
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
		if (!empty($keywords['project_id'])) {
			$where['project_id'] = $keywords['project_id'];
		}
		if (!empty($keywords['field_id'])) {
			$where['field_id'] = $keywords['field_id'];
		}
		if (!empty($keywords['shot_id'])) {
			$where['shot_id'] = $keywords['shot_id'];
		}
		if (!empty($keywords['shot_number'])) {
			$shot_id = Shot::where('shot_number', substr($keywords['shot_number'], 3, 3))->value('id');
			if (!$shot_id) {
				$data['list'] = [];
				$data['dataCount'] = 0;
				return $data;
			}
			$shot_number_len = strlen($keywords['shot_number']);
			//后期可对3 镜头号长度进行配置
			if ($shot_number_len == 3) {
				$where['shot_id'] = $shot_id;
			}
			//后期可对场号长度进行配置  场号+镜头号  暂定为6
			if ($shot_number_len == 6) {
				$field_id = Db::name('field')->where('name', substr($keywords['shot_number'], 1, 3))->value('id');
				//场号不匹配，则数据直接返回空
				if (!$field_id) {
					$data['list'] = [];
					$data['dataCount'] = 0;
					return $data;
				}
				$where['field_id'] = $field_id;
				$where['shot_id'] = $shot_id;
			}
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
			$list_data[$key]['shot_number'] = ($value['task_type'] == 1) ? Field::get($value['field_id'])->name . Shot::get($value['shot_id'])->shot_number : Asset::get($value['asset_id'])->asset_name;
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
	public function getUpperAssets($keywords, $page, $limit, $uid, $group_id)
	{

	}

	//工作台 - 等待上游 镜头
	public function getUpperShots($keywords, $page, $limit, $uid)
	{
		$where = [];
		$foreach_where = [];
		//加入条件查询
		if (!empty($keywords['project_id'])) {
			$where['project_id'] = $keywords['project_id'];
		}
		if (!empty($keywords['field_id'])) {
			$where['field_id'] = $keywords['field_id'];
		}
		if (!empty($keywords['shot_id'])) {
			$where['shot_id'] = $keywords['shot_id'];
		}
		//根据当前用户获取所属的所有镜头ID 去重
		$where['user_id'] = $uid;
		$shot_ids_arr = array_unique($this->where($where)->column('shot_id'));
		if (!empty($shot_ids_arr)) {
			//对每个镜头进行分页处理 ，似乎无法进行在循环外进行分页 需要优化
			foreach ($shot_ids_arr as $key => $shot_id) {
				//每个镜头
				$min_tache_sort = min($this->where(['shot_id' => $shot_id, 'user_id' => $uid])->column('tache_sort'));
				if ($min_tache_sort == 1) {
					$list_data = [];
					$dataCount[] = 0;
				} else {  //2
					$range_tache_sort = $min_tache_sort - 1;
					$first_tache_sort = 1;
					if ($first_tache_sort == $range_tache_sort) {  //if 2 2-1 = 1
						$foreach_where['shot_id'] = $shot_id;
						$foreach_where['tache_sort'] = 1;
						$dataCount[] = $this->where($foreach_where)->count('id');
						$list_data[] = $this->where($foreach_where)->page($page, $limit)->select();
					} else {
						$foreach_where['shot_id'] = $shot_id;
						$foreach_where['tache_sort'] = ['between', [1, $range_tache_sort]];
						$dataCount[] = $this->where($foreach_where)->count('id');
						$list_data[] = $this->where($foreach_where)->page($page, $limit)->select();
					}
				}
			}
			foreach ($list_data as $key => $value) {
				if (empty($value)) {
					unset($list_data[$key]);
				}
			}
			if (!empty($list_data)) {
				$list = array_values($list_data)[0];
				$dataCount = array_sum($dataCount);
				foreach ($list as $key => $value) {
					$list[$key]['project_name'] = Project::get($value['project_id'])->project_byname;
					$list[$key]['shot_number'] = Db::name('field')->where('id', $value['field_id'])->value('name') . Shot::get($value['shot_id'])->shot_number;
					$list[$key]['task_priority_level'] = $this->task_priority_level_arr[$value['task_priority_level']];    //任务优先级
					$list[$key]['difficulty'] = $this->difficulty_arr[$value['difficulty']];   //任务难度
					$list[$key]['surplus_days'] = floatval(sprintf("%.2f", ($value['plan_end_timestamp'] - time()) / 86400)) . "天";   //剩余天数
					$list[$key]['task_allot_days'] = (!empty($value['actually_start_timestamp']) || !empty($value['actually_end_timestamp'])) ? floatval(sprintf("%.2f", ($value['actually_end_timestamp'] - $value['actually_start_timestamp']) / 86400)) . "天" : '0天';//任务分配时间
					$list[$key]['create_timestamp'] = $value['create_time'];
					$list[$key]['create_time'] = date("Y-m-d H:i:s", $value['create_time']);
				}
			} else {
				$list = [];
				$dataCount = 0;
			}
		} else {
			$list = [];
			$dataCount = 0;
		}

		$data['list'] = $list;
		$data['dataCount'] = $dataCount;
		return $data;
	}

	//工作台 - 任务完成 列表
	public function getFinishTask($keywords, $page, $limit, $uid, $group_id)
	{
		$where = [];
		//加入条件查询
		if (!empty($keywords['project_id']) && empty($keywords['shot_number'])) {
			$where['project_id'] = $keywords['project_id'];
		}
		if (!empty($keywords['field_id']) && empty($keywords['shot_number'])) {
			$where['field_id'] = $keywords['field_id'];
		}
		if (!empty($keywords['shot_id']) && empty($keywords['shot_number'])) {
			$where['shot_id'] = $keywords['shot_id'];
		}
		if (!empty($keywords['shot_number'])) {
			$shot_number_len = strlen($keywords['shot_number']);
			if ($shot_number_len == 3) {
				$shot_number = substr($keywords['shot_number'], 1, 3);
			}
			if ($shot_number_len == 6) {
				$shot_number = substr($keywords['shot_number'], 3, 3);
			}
			$shot_id = Shot::where('shot_number', $shot_number)->value('id');
			if (!$shot_id) {
				$data['list'] = [];
				$data['dataCount'] = 0;
				return $data;
			}

			//后期可对3 镜头号长度进行配置
			if ($shot_number_len == 3) {
				$where['field_id'] = $keywords['field_id'];
				$where['shot_id'] = $shot_id;
			}
			//后期可对场号长度进行配置  场号+镜头号  暂定为6
			if ($shot_number_len == 6) {
				$field_id = Db::name('field')->where('name', substr($keywords['shot_number'], 1, 3))->value('id');
				//场号不匹配，则数据直接返回空
				if (!$field_id) {
					$data['list'] = [];
					$data['dataCount'] = 0;
					return $data;
				}
				$where['field_id'] = $field_id;
				$where['shot_id'] = $shot_id;
			}
		}

		if (!empty($keywords['user_id']) || ($group_id == 7)) {  //制作人只能看到自己完成的任务
			$where['user_id'] = $uid;
		}

		$where['task_status'] = ['in', '25,30'];  //提交发布 完成
		$dataCount = $this->where($where)->count('id'); //全部数量
		$list = $this->where($where);
		//若有分页
		if ($page && $limit) {
			$list = $list->page($page, $limit);
		}
		$list = $list->select();
		foreach ($list as $key => $value) {
			$list[$key]['project_name'] = Project::get($value['project_id'])->project_byname;
			$list[$key]['shot_number'] = Db::name('field')->where('id', $value['field_id'])->value('name') . Shot::get($value['shot_id'])->shot_number;
			$list[$key]['task_priority_level'] = $this->task_priority_level_arr[$value['task_priority_level']];    //任务优先级
			$list[$key]['difficulty'] = $this->difficulty_arr[$value['difficulty']];   //任务难度
			$list[$key]['surplus_days'] = floatval(sprintf("%.2f", ($value['plan_end_timestamp'] - time()) / 86400)) . "天";   //剩余天数
			$list[$key]['task_allot_days'] = (!empty($value['actually_start_timestamp']) || !empty($value['actually_end_timestamp'])) ? floatval(sprintf("%.2f", ($value['actually_end_timestamp'] - $value['actually_start_timestamp']) / 86400)) . "天" : '0天';//任务分配时间
			$list[$key]['create_timestamp'] = $value['create_time'];
			$list[$key]['create_time'] = date("Y-m-d H:i:s", $value['create_time']);
		}
		$data['list'] = $list;
		$data['dataCount'] = $dataCount;
		return $data;
	}

	//新增 此方法暂时未用
	public function addData($param)
	{
		try {
			$param['asset_ids'] = implode(",", $param['asset_ids']);    //资产ID 多项 字符串 以逗号分割
			$param['task_image'] = str_replace('\\', '/', $param['task_image']);
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
	public function change_task_status($task_id, $data, $uid, $group_id)
	{
		$task_data['task_status'] = $this->status_cn_arr[$data['status']];
		$curr_task_data = $this->get($task_id);
		if (!$curr_task_data) {
			$this->error = '暂无此数据';
			return false;
		}
		try {
			//获取所属任务当前状态值
			$curr_task_status = $this->get($task_id)->task_status;
			//查询所属镜头的状态值
			$shot_id = $this->get($task_id)->shot_id;
			$shot_status = Shot::get($shot_id)->status;
			//判断角色权限
			if ($group_id == 7) {  //制作人
				if (($curr_task_status == 1) && ($task_data['task_status'] == 5)) {  //等待制作改变状态为制作中，可进行更新，其他行为，均没有权限操作
					//更改状态时将当前时间加入实际开始时间
					$task_data['actually_start_timestamp'] = time();
					$this->save($task_data, [$this->getPk() => $task_id]);
				} else {
					$this->error = '您没有权限操作';
					return false;
				}
			} else {  //除制作人角色外，其他角色没有把当前任务 未制作——>制作中 的权限
				//区分管理员
				if ($uid != 1) {
					if (($curr_task_status == 1) && ($task_data['task_status'] == 5)) {
						$this->error = '您没有权限操作,只有当前制作人可操作';
						return false;
					}
				} else {
					$this->save($task_data, [$this->getPk() => $task_id]);
					//同时更新所属镜头ID 将状态改为制作中
					if ($shot_status == 1) {  //所属镜头状态 为等待制作时 任务改为制作中时 同时更新镜头表状态
						$shot = Shot::get($shot_id);
						$shot->status = 5;
						$shot->actual_start_timestamp = time();
						$shot->save();
					}
				}
			}
			//记录状态更新记录
			$task_status_record['user_id'] = $uid;
			$task_status_record['task_id'] = $task_id;
			$task_status_record['task_status'] = $task_data['task_status'];
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
		if (!$task_obj) {
			$this->error = '暂无此数据';
			return false;
		}
		$project_obj = Project::get($task_obj->project_id);
		$shot_obj = Shot::get($task_obj->shot_id);
		$user_ids_arr = $this->where('pid', $task_id)->column('user_id');
		//组合当前任务所属制作人数据
		if ($task_obj->user_id == 0) {
			if (!empty($user_ids_arr)) {
				foreach ($user_ids_arr as $key => $value) {
					$user_ids_data[$key]['user_id'] = $value;
					$user_ids_data[$key]['realname'] = User::get($value)->realname;
				}
			} else {
				$user_ids_data = [];
			}
		} else {
			$user_ids_data[0]['user_id'] = $task_obj->user_id;
			$user_ids_data[0]['realname'] = User::get($task_obj->user_id)->realname;
		}

		$task_obj->project_name = $project_obj->project_name;
		$task_obj->project_byname = $project_obj->project_byname;
		$task_obj->field_number = Db::name('field')->where('id', $task_obj->field_id)->value('name');
		$task_obj->shot_number = $shot_obj->shot_number;
		$task_obj->shot_byname = $shot_obj->shot_byname;
		$task_obj->shot_name = $shot_obj->shot_name;
		$task_obj->difficulty_name = $this->difficulty_arr[$task_obj->difficulty];
		$task_obj->task_priority_level_name = $this->task_priority_level_arr[$task_obj->task_priority_level];
		$task_obj->plan_start_time = date("Y-m-d H:i:s", $task_obj->plan_start_timestamp);
		$task_obj->plan_end_time = date("Y-m-d H:i:s", $task_obj->plan_end_timestamp);
		$task_obj->actually_start_time = date("Y-m-d H:i:s", $task_obj->actually_start_timestamp);
		$task_obj->actually_end_time = date("Y-m-d H:i:s", $task_obj->actually_end_timestamp);
		$task_obj->user_data = $user_ids_data;
		return $task_obj;
	}

	//根据主键编辑任务 并分配制作人
	public function updateData_ById($data, $id)
	{
		$task_obj = $this->get($id);
		if (empty($task_obj)) {
			$this->error = '暂无此数据';
			return false;
		}
		//开启事务
		$this->startTrans();
		try {
			//$data['user_id'] 以逗号分割的字符串转为数组
			$user_ids_arr = explode(',', $data['user_id']);
			if (!empty($data['user_id'])) {
				//根据制作人分配任务 默认为新增操作
				foreach ($user_ids_arr as $key => $value) {
					$task_data['group_id'] = $task_obj->group_id;
					$task_data['user_id'] = $value;
					$task_data['project_id'] = $task_obj->project_id;
					$task_data['field_id'] = $task_obj->field_id;
					$task_data['shot_id'] = $task_obj->shot_id;
					$task_data['assets_id'] = !empty($task_obj->assets_id) ? $task_obj->assets_id : 0;
					$task_data['tache_id'] = $task_obj->tache_id;
					$task_data['tache_sort'] = $task_obj->tache_sort;
					$task_data['studio_id'] = $task_obj->studio_id;
					$task_data['task_type'] = $task_obj->task_type;
					$task_data['task_image'] = $data['task_image'];
					$task_data['task_byname'] = !empty($data['task_byname']) ? $data['task_byname'] : '';
					$task_data['make_demand'] = !empty($data['make_demand']) ? $data['make_demand'] : '';
					$task_data['task_priority_level'] = $data['task_priority_level'];
					$task_data['difficulty'] = $data['difficulty'];
					$task_data['second_company'] = $data['second_company'];
					$task_data['plan_start_timestamp'] = strtotime($data['plan_start_time']);
					$task_data['plan_end_timestamp'] = strtotime($data['plan_end_time']);
					$task_data['task_status'] = $task_obj->task_status;
					$task_data['is_assets'] = $task_obj->is_assets;
					$task_data['is_pause'] = $task_obj->is_pause;
					$task_data['camera_motion'] = 1;  //相机运动
					$task_data['pid'] = $id;  //父任务ID
					$task_data['create_time'] = time();
					$task_data['update_time'] = time();
					$this->data($task_data, true)->isUpdate(false)->save();
					//为制作人创建目录  python

					//根据自增任务ID添加任务记录表记录
					$task_record_data['task_id'] = $this->id;
					$task_record_data['task_status'] = 1;
					$task_record_data['user_id'] = $value;
					$task_record_data['create_timestamp'] = time();
					$task_record_data['create_time'] = date('Y-m-d H:i:s');
					Db::name('task_state_record')->insert($task_record_data);
				}
				$this->commit();
				return true;
			} else {  //更新
				$task_data['task_image'] = $data['task_image'];
				$task_data['task_priority_level'] = $data['task_priority_level'];
				$task_data['difficulty'] = $data['difficulty'];
				$task_data['plan_start_timestamp'] = strtotime($data['plan_start_time']);
				$task_data['plan_end_timestamp'] = strtotime($data['plan_end_time']);
				$task_data['make_demand'] = !empty($data['make_demand']) ? $data['make_demand'] : '';
				$this->allowField(true)->save($task_data, [$this->getPk() => $id]);
				$this->commit();
				return true;
			}
		} catch (\Exception $e) {
			$this->rollback();
			$this->error = '编辑失败';
			return false;
		}
	}

	//删除所属任务的制作人 同时调用python删除相应的目录
	public function TaskDel_ById($task_id, $user_id)
	{
		$task_obj = $this->get($task_id);
		if (!$task_obj) {
			$this->error = '暂无此数据';
			return false;
		}
		//开启事务
		$this->startTrans();
		try {
			$result = $this->destroy(['pid' => $task_id, 'user_id' => $user_id]);
			if ($result === false) {
				$this->error = '删除失败';
				$this->rollback();
				return false;
			} else {
				$this->commit();
				return true;
			}
		} catch (\Exception $e) {
			$this->error = '删除失败';
			$this->rollback();
			return false;
		}
	}

	//根据所属任务ID弹出已存在的制作人列表
	public function getUser_byTask($task_id, $uid)
	{
		$tache_ids_arr = explode(',', User::get($uid)->tache_ids);
		if (($uid != 1) || ($task_id != '')) {
			foreach ($tache_ids_arr as $key => $value) {
				$user_ids_arr[] = User::where('tache_ids', 'like', '%' . $value . '%')->column('id');
			}
			$user_arr_byTache = array_unique($user_ids_arr)[0];
			//获取所属任务的制作人 用户ID
			$curr_task_userId_arr = $this->where('pid', $task_id)->column('user_id');
			foreach ($user_arr_byTache as $key => $value) {
				foreach ($curr_task_userId_arr as $k => $v) {
					if ($v == $value) unset($user_arr_byTache[$key]);
				}
			}
			$user_arr = array_values($user_arr_byTache);
		} else {  //获取所有用户
			$user_arr = User::column('id');
			unset($user_arr[0]);//弹出超级管理员自己
		}
		foreach ($user_arr as $key => $value) {
			$user_data[$key]['id'] = $value;
			$user_data[$key]['real_name'] = User::get($value)->realname;
		}
		$data['list'] = $user_data;
		return $data;
	}

}