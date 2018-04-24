<?php

namespace app\admin\model;

use think\Db;
use app\common\model\Common;

class Asset extends Common
{
	protected $name = 'asset';
	protected $priority_level_arr = [1 => 'D', 2 => 'C', 3 => 'B', 4 => 'A'];   //镜头优先级
	protected $difficulty_arr = [1 => 'D', 2 => 'C', 3 => 'B', 4 => 'A', 5 => 'S']; //镜头难度
	protected $task_status_arr = [1 => 0, 5 => 20, 10 => 40, 15 => 60, 20 => 80, 25 => 100];    //用于任务状态计算进度百分比 status=>0%
	//根据环节ID获取镜头页面进度条所用别名
	protected $tache_byname_arr = [3 => '美术部', 4 => '模型部', 5 => '贴图部', 6 => '绑定部', 7 => '跟踪部', 8 => '动画部', 9 => '数字绘景部', 10 => '特效部', 11 => '灯光部', 12 => '合成部'];

	//资产概括
	public function survey_list($keywords)
	{
		$where = [];
		if (!empty($keywords['project_id']) && empty($keywords['asset_name'])) {
			$where['project_id'] = $keywords['project_id'];
		}
		if (!empty($keywords['field_id']) && empty($keywords['asset_name'])) {
			$where['field_id'] = $keywords['field_id'];
		}
		if (!empty($keywords['asset_id']) && empty($keywords['asset_name'])) {
			$where['id'] = $keywords['asset_id'];
		}
		if (!empty($keywords['asset_content'])) {  //手动输入资产名称或简称
			$where['asset_byname|asset_name'] = trimall($keywords['asset_content']);
		}
		$in_production_count = $this->where($where)->where('status', 5)->count('id');  //制作中
		$waiting_assets_count = $this->where($where)->where('status', 1)->count('id');  //等待资产
		$feedback_count = $this->where($where)->where('status', 15)->count('id');  //反馈中
		$pause_count = $this->where($where)->where('is_pause', 2)->count('id');  //暂停
		$finish_count = $this->where($where)->where('status', 'in', [25, 30])->count('id');  //完成
		$data['in_production_count'] = $in_production_count;
		$data['waiting_assets_count'] = $waiting_assets_count;
		$data['feedback_count'] = $feedback_count;
		$data['pause_count'] = $pause_count;
		$data['finish_count'] = $finish_count;
		return $data;
	}

	//标准列表
	public function getList($keywords, $page, $limit)
	{
		$where = [];
		if (!empty($keywords['project_id']) && empty($keywords['asset_content'])) {
			$where['project_id'] = $keywords['project_id'];
		}
		if (!empty($keywords['field_id']) && empty($keywords['asset_content'])) {
			$where['field_id'] = $keywords['field_id'];
		}
		if (!empty($keywords['asset_id']) && empty($keywords['asset_content'])) {
			$where['id'] = $keywords['asset_id'];
		}
		if (!empty($keywords['priority_level']) && empty($keywords['asset_content'])) {  //优先级
			$where['priority_level'] = $keywords['priority_level'];
		}
		if (!empty($keywords['asset_content'])) {  //手动输入资产名称或简称
			$where['asset_byname|asset_name'] = trimall($keywords['asset_content']);
		}
		$dataCount = $this->where($where)->count('id');
		$list = $this->where($where);
		// 若有分页
		if ($page && $limit) {
			$list = $list->page($page, $limit);
		}
		$list = $list->select();
		for ($i = 0; $i < count($list); $i++) {
			$list[$i]['project_name'] = Project::get($list[$i]['project_id'])->project_byname;
			$list[$i]['type_name'] = Field::get($list[$i]['field_id'])->explain;
			$list[$i]['difficulty'] = $this->difficulty_arr[$list[$i]['difficulty']];  //难度
			$list[$i]['priority_level'] = $this->priority_level_arr[$list[$i]['priority_level']];  //优先级
			$list[$i]['tache_info'] = $this->rate_of_progress($list[$i]['id']);
			$list[$i]['plan_start_time'] = date('Y-m-d H:i:s', $list[$i]['plan_start_timestamp']);
			$list[$i]['plan_end_time'] = date('Y-m-d H:i:s', $list[$i]['plan_end_timestamp']);
			$list[$i]['actual_start_time'] = !empty($list[$i]['actual_start_timestamp']) ? date('Y-m-d H:i:s', $list[$i]['actual_start_timestamp']) : '';
			$list[$i]['actual_end_time'] = !empty($list[$i]['actual_end_timestamp']) ? date('Y-m-d H:i:s', $list[$i]['actual_end_timestamp']) : '';
		}
		$data['list'] = $list;
		$data['dataCount'] = $dataCount;
		return $data;
	}

	/**
	 * 根据状态与是否暂停状态获取列表数据
	 * @param $keywords
	 * @param $page
	 * @param $limit
	 * @param $status  int 状态
	 * @param $is_pause  int 是否暂停
	 * @return mixed
	 */
	public function getList_byStatus($keywords, $page, $limit, $status = '', $is_pause = '')
	{
		$where = [];
		if (!empty($keywords['project_id']) && empty($keywords['asset_content'])) {
			$where['project_id'] = $keywords['project_id'];
		}
		if (!empty($keywords['field_id']) && empty($keywords['asset_content'])) {
			$where['field_id'] = $keywords['field_id'];
		}
		if (!empty($keywords['asset_id']) && empty($keywords['asset_content'])) {
			$where['id'] = $keywords['asset_id'];
		}
		if (!empty($keywords['asset_content'])) {  //手动输入资产名称或资产简称
			$where['asset_byname|asset_name'] = $keywords['asset_content'];
		}
		if (!empty($status)) {
			$where['status'] = $status;
		}
		$where['is_pause'] = $is_pause;

		$dataCount = $this->where($where)->count('id');
		$list = $this->where($where)->order('plan_end_timestamp desc');
		// 若有分页
		if ($page && $limit) {
			$list = $list->page($page, $limit);
		}
		$list = $list->select();
		foreach ($list as $key => $value) {
			$list[$key]['project_name'] = Project::get($value['project_id'])->project_byname;
			$list[$key]['type_name'] = Field::get($value['field_id'])->explain;
			$list[$key]['priority_level'] = $this->priority_level_arr[$value['priority_level']];    //镜头优先级
			$list[$key]['difficulty'] = $this->difficulty_arr[$value['difficulty']];    //镜头难度
			$list[$key]['surplus_days'] = floatval(sprintf("%.2f", ($value['plan_end_timestamp'] - time()) / 86400));   //剩余天数
			$list[$key]['create_timestamp'] = $value['create_time'];
			$list[$key]['create_time'] = date("Y-m-d H:i:s", $value['create_time']);
			$list[$key]['tache_info'] = $this->rate_of_progress($value['id']);
		}
		$data['list'] = $list;
		$data['dataCount'] = $dataCount;
		return $data;
	}

	//添加资产及任务
	public function addData($param, $group_id)
	{
		$project_obj = Project::get($param['project_id']);
		//开启事务
		$this->startTrans();
		try {
			$param['asset_image'] = str_replace('\\', '/', $param['asset_image']);
			$param['plan_start_timestamp'] = strtotime($param['plan_start_timestamp']);
			$param['plan_end_timestamp'] = strtotime($param['plan_end_timestamp']);
			$param['create_time'] = time();
			foreach ($param['tache'] as $key => $value) {
				$tache_data[$key] = $value;
			}
			//保存资产表
			$result = $this->allowField(true)->save($param);
			$asset_id = $this->id;  //获取自增ID
			//获取自增ID的当前对象
			$curr_asset_obj = $this->get($this->id);
			if (false === $result) {
				$this->error = $this->getError();
				return false;
			} else {
				$project_byname = $project_obj->project_byname;
				$field_name = Field::get($param['field_id'])->name;
				//执行redis添加镜头所属目录 python
				$str = "'asset' '{$project_byname}' '{$field_name}' '{$param['asset_byname']}'";
				//exec_python($str);
				//根据环节分配任务给各大工作室
				foreach ($tache_data as $key => $val) {
					//根据环节内的工作室是否为空创建任务
					if (!empty($val)) {
						foreach ($val as $k => $v) {
							$task_data['group_id'] = $group_id;    //所属角色ID
							$task_data['user_id'] = 0;   //所属用户ID
							$task_data['project_id'] = $curr_asset_obj->project_id;  //所属项目ID
							$task_data['field_id'] = $curr_asset_obj->field_id;      //资产类型ID
							$task_data['asset_id'] = $asset_id;  //资产ID
							$task_data['tache_id'] = $key;  //环节ID
							$task_data['tache_sort'] = Tache::get($key)->sort;  //环节排序
							$task_data['studio_id'] = $v;   //工作室ID
							$task_data['task_type'] = 2;    //资产类型
							$task_data['task_image'] = $curr_asset_obj->asset_image;
							$task_data['task_byname'] = $curr_asset_obj->asset_byname;//任务简称暂且为资产的简称，任务模块中，可修改
							$task_data['task_priority_level'] = $curr_asset_obj->priority_level;   //任务优先级
							$task_data['difficulty'] = $curr_asset_obj->difficulty;    //任务难度
							$task_data['plan_start_timestamp'] = $curr_asset_obj->plan_start_timestamp;  //计划开始时间
							$task_data['plan_end_timestamp'] = $curr_asset_obj->plan_end_timestamp;    //计划结束时间
							$task_data['task_status'] = 1;  //任务状态
							$task_data['is_assets'] = 1; //是否为等待资产 1是 2否  资产任务被创建时 等待资产状态 可理解为等待制作
							$task_data['pid'] = 0;  //工作室顶级任务ID都为0
							$task_data['create_time'] = time();//创建时间
							$task_model = new Workbench();
							$task_model->data($task_data)->save();
						}
					} else {
						$task_data['group_id'] = $group_id;    //所属角色ID
						$task_data['user_id'] = 0;   //所属用户ID
						$task_data['project_id'] = $curr_asset_obj->project_id;  //所属项目ID
						$task_data['field_id'] = $curr_asset_obj->field_id;      //资产类型ID
						$task_data['asset_id'] = $asset_id;  //资产ID
						$task_data['tache_id'] = $key;  //环节ID
						$task_data['tache_sort'] = Tache::get($key)->sort;  //环节排序
						$task_data['studio_id'] = 0;   //工作室ID
						$task_data['task_type'] = 2;    //资产类型
						$task_data['task_image'] = $curr_asset_obj->asset_image;
						$task_data['task_byname'] = $curr_asset_obj->asset_byname;//任务简称暂且为资产的简称，任务模块中，可修改
						$task_data['task_priority_level'] = $curr_asset_obj->priority_level;   //任务优先级
						$task_data['difficulty'] = $curr_asset_obj->difficulty;    //任务难度
						$task_data['plan_start_timestamp'] = $curr_asset_obj->plan_start_timestamp;  //计划开始时间
						$task_data['plan_end_timestamp'] = $curr_asset_obj->plan_end_timestamp;    //计划结束时间
						$task_data['task_status'] = 1;  //任务状态
						$task_data['is_assets'] = 1; //是否为等待资产 1是 2否  资产任务被创建时 等待资产状态 可理解为等待制作
						$task_data['pid'] = 0;  //工作室顶级任务ID都为0
						$task_data['create_time'] = time();//创建时间
						$task_model = new Workbench();
						$task_model->data($task_data)->save();
					}
				}
				$this->commit();
				return true;
			}
		} catch (\Exception $e) {
			$this->rollback();
			$this->error = '添加失败';
			return false;
		}
	}

	//根据镜头ID进行编辑数据
	public function updateData_ById($param, $id)
	{
		$asset_obj = $this->get($id);
		if (!$asset_obj) {
			$this->error = '暂无此数据';
			return false;
		}
		//开启事务
		$this->startTrans();
		try {
			$param['plan_start_timestamp'] = strtotime($param['plan_start_timestamp']);
			$param['plan_end_timestamp'] = strtotime($param['plan_end_timestamp']);
			$param['create_time'] = time();
			foreach ($param['tache'] as $key => $value) {
				if (!empty($value)) {
					$tache_data[$key] = $value;
				}
			}
			$asset_model = new Asset();
			/**
			 * begin 环节内工作室不为空
			 * 执行新增操作
			 */
			$asset_model->allowField(true)->save($param, [$this->getPk() => $id]);//更新当前镜头行记录
			$project_byname = Project::get($param['project_id'])->project_byname;
			$field_name = Field::get($param['field_id'])->name;
			//执行redis添加资产所属目录 python
			$str = "'Asset' '{$project_byname}' '{$field_name}' '{$param['asset_byname']}'";
			//根据环节分配任务给各大工作室 新增操作
			foreach ($tache_data as $key => $val) {
				foreach ($val as $k => $v) {
					$task_data['group_id'] = 5;  //角色为工作室总监
					$task_data['user_id'] = 0;
					$task_data['project_id'] = $param['project_id'];   //所属项目ID
					$task_data['field_id'] = $param['field_id'];   //资产类型ID
					$task_data['asset_id'] = $id;  //资产ID
					$task_data['tache_id'] = $key;  //环节ID
					$task_data['tache_sort'] = Tache::get($key)->sort;  //环节排序
					$task_data['studio_id'] = $v;   //工作室ID
					$task_data['task_type'] = 2;    //资产类型
					$task_data['task_image'] = $asset_obj->asset_image;
					$task_data['task_byname'] = $asset_obj->asset_byname;//任务简称暂且为资产的简称，任务模块中，可修改
					$task_data['task_priority_level'] = $asset_obj->priority_level;   //任务优先级
					$task_data['difficulty'] = $asset_obj->difficulty;    //任务难度
					$task_data['plan_start_timestamp'] = $asset_obj->plan_start_timestamp;  //计划开始时间
					$task_data['plan_end_timestamp'] = $asset_obj->plan_end_timestamp;    //计划结束时间
					$task_data['task_status'] = 1;  //任务状态
					$task_data['is_assets'] = 1; //是否为等待资产 1是 2否
					$task_data['pid'] = 0;  //工作室顶级任务ID都为0
					$task_data['create_time'] = time();//创建时间
					$task_model = new Workbench();
					$task_model->data($task_data, true)->isUpdate(false)->save();
				}
			}
			$this->commit();
			return true;
			/**
			 * end 环节内工作室不为空
			 */
		} catch (\Exception $e) {
			$this->rollback();
			$this->error = '编辑失败';
			return false;
		}
	}

	//根据资产ID获取数据
	public function getData_ById($id)
	{
		$data = $this->get($id);
		$data->project_name = Project::get($data->project_id)->project_name;
		$data->type_name = Field::get($data->field_id)->explain;
		$data->difficulty_name = $this->difficulty_arr[$data->difficulty];
		$data->priority_level_name = $this->priority_level_arr[$data->priority_level];
		$data->tache_info = $this->get_studio_byTache($data->id);
		if (!$data) {
			$this->error = '暂无此数据';
			return false;
		}
		return $data;
	}

	//根据资产ID删除资产及子任务
	public function delData_ById($id)
	{
		//开启事务
		$this->startTrans();
		try {
			$asset_result = $this->where($this->getPk(), $id)->delete();
			if (false === $asset_result) {
				$this->error = '资产删除失败';
				return false;
			} else {
				//删除所属资产的所有任务
				$taskBy_assetDel_result = Workbench::destroy(['asset_id' => $id]);
				if (false === $taskBy_assetDel_result) {
					$this->error = '资产所属任务删除失败';
					$this->rollback();
					return false;
				} else {
					$this->commit();
					return true;
				}
				//python 脚本调用 未做
			}
		} catch (\Exception $e) {
			$this->rollback();
			return false;
		}
	}

	//根据资产ID获取所属环节下的工作室
	public function get_studio_byTache($asset_id)
	{
		$tache_ids_arr = array_unique(Workbench::where('asset_id', $asset_id)->column('tache_id'));
		foreach ($tache_ids_arr as $key => $value) {
			$data[$this->tache_byname_arr[$value]] = $this->get_studio_name(Workbench::where(['asset_id' => $asset_id, 'tache_id' => $value])->column('studio_id'));
		}
		return $data;
	}

	//根据工作室ID数组拼接成中文字符串
	public function get_studio_name($studio_id_arr)
	{
		foreach ($studio_id_arr as $key => $value) {
			$arr[$key]['id'] = $value;
			$arr[$key]['name'] = Studio::get($value)->name;
		}
		return $arr;
	}

	//获取当前资产各环节进度
	public function rate_of_progress($asset_id)
	{
		$tache_data = array_unique(Workbench::where(['pid' => 0, 'asset_id' => $asset_id])->column('tache_id'));  //获取所属资产的环节
		foreach ($tache_data as $key => $value) {
			//根据当前资产ID与环节ID查询是否有其任务
			$curr_task_data = Workbench::where(['asset_id' => $asset_id, 'tache_id' => $value])->find();
			$finish_degree[$key]['tache_id'] = $value;
			$finish_degree[$key]['tache_byname'] = $this->tache_byname_arr[$value];
			$finish_degree[$key]['finish_degree'] = !empty($curr_task_data) ? $this->get_finish_degree_by_task($asset_id, $value) : 0;
		}
		//根据环节ID获取所属任务
		return $finish_degree;
	}

	//根据任务获取完成度
	public function get_finish_degree_by_task($asset_id, $tache_id)
	{
		//获取当前环节下有几个工作室ID
		$studio_ids_arr = Workbench::where(['asset_id' => $asset_id, 'tache_id' => $tache_id])->column('studio_id');
		//多工作室
		if (count($studio_ids_arr) > 1) {
			foreach ($studio_ids_arr as $key => $value) {
				$studio_degree[] = $this->task_status_arr[Workbench::where(['pid' => 0, 'asset_id' => $asset_id, 'studio_id' => $value])->value('task_status')];
			}
			$curr_tache_degree = (array_sum($studio_degree) == 0) ? 0 : intval(array_sum($studio_degree) / count($studio_ids_arr));
		} else {//一个工作室 他的状态即是当前环节的进度
			$curr_tache_degree = $this->task_status_arr[Workbench::where(['pid' => 0, 'asset_id' => $asset_id, 'studio_id' => $studio_ids_arr[0]])->value('task_status')];    //获取这个任务的状态转化的进度值
		}
		return $curr_tache_degree;
	}

	//获取工作室列表 弹出视效、制片工作室
	public function getStudio_byAsset($param)
	{
		$studio_data = Studio::where('pid', 1)->select();
		unset($studio_data[0]); //弹出视效工作室
		unset($studio_data[1]); //制片工作室
		$studio_data = array_values($studio_data);
		if (!empty($param)) {
			$studio_id_temp = Workbench::where(['asset_id' => $param['asset_id'], 'tache_id' => $param['tache_id']])->column('studio_id');
			foreach ($studio_data as $key => $value) {
				foreach ($studio_id_temp as $k => $v) {
					if ($v == $value['id']) unset($studio_data[$key]);
				}
			}
			$data['list'] = array_values($studio_data);
		} else {
			$data['list'] = $studio_data;
		}
		return $data;
	}

	//根据镜头ID及环节ID删除所属环节的所有任务
	public function TacheDel_ByAssetId($asset_id, $tache_name)
	{
		$asset_obj = $this->get($asset_id);
		if (!$asset_obj) {
			$this->error = '暂无此数据';
			return false;
		}
		try {
			$tache_id = array_flip($this->tache_byname_arr)[$tache_name];
			$result = Workbench::destroy(['asset_id' => $asset_id, 'tache_id' => $tache_id]);
			if ($result === false) {
				$this->error = '删除失败';
				return false;
			} else {
				return true;
			}
		} catch (\Exception $e) {
			$this->error = '删除失败';
			return false;
		}
	}

	//根据资产ID与工作室ID删除对应的任务
	public function StudioDel_ByAssetId($asset_id, $tache_name, $studio_id)
	{
		$asset_obj = $this->get($asset_id);
		if (!$asset_obj) {
			$this->error = '暂无此数据';
			return false;
		}
		try {
			$tache_id = array_flip($this->tache_byname_arr)[$tache_name];
			$result = Workbench::destroy(['asset_id' => $asset_id, 'tache_id' => $tache_id, 'studio_id' => $studio_id]);
			if (false === $result) {
				$this->error = '删除失败';
				return false;
			} else {
				return true;
			}
		} catch (\Exception $e) {
			$this->error = '删除失败';
			return false;
		}
	}

	//根据项目ID及类型ID 获取资产类型表的数据
	public function get_asset_name($param)
	{
		$data = [];
		if (!empty($param['field_id'])) {
			$data = $this->where('field_id', $param['field_id'])->select();
		}
		return $data;
	}

	//校验所属项目、所属类型下的资产名称是否重复
	public function check_asset_byname($param)
	{
		$check_result = $this->where(['field_id' => $param['field_id'], 'asset_byname' => $param['asset_byname']])->value('id');
		if (!empty($check_result)) {
			$this->error = '所属类型中资产名称已重复';
			return false;
		} else {
			return true;
		}
	}
}