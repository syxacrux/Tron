<?php

namespace app\admin\model;

use think\Db;
use app\common\model\Common;

class Shot extends Common
{

	protected $name = 'shot';
	protected $priority_level_arr = [1 => 'D', 2 => 'C', 3 => 'B', 4 => 'A'];   //镜头优先级
	protected $difficulty_arr = [1 => 'D', 2 => 'C', 3 => 'B', 4 => 'A', 5 => 'S']; //镜头难度
	protected $time_arr = [1 => '白天', 2 => '晚上'];   //时刻(1白天 2夜晚)
	protected $ambient_arr = [1 => '室外', 2 => '室内'];    //环境(1外 2内)
	protected $task_status_arr = [1 => 0, 5 => 20, 10 => 40, 15 => 60, 20 => 80, 25 => 100];    //用于任务状态计算进度百分比 status=>0%
	//根据环节ID获取镜头页面进度条所用别名
	protected $tache_byname_arr = [3 => '美术部', 4 => '模型部', 5 => '贴图部', 6 => '绑定部', 7 => '跟踪部', 8 => '动画部', 9 => '数字绘景部', 10 => '特效部', 11 => '灯光部', 12 => '合成部'];

	/**
	 * 镜头首页概括
	 * @param $keywords
	 * @param $page
	 * @param $limit
	 * @return mixed
	 */
	public function getList($keywords, $page, $limit)
	{
		$where = [];
		$dataCount = $this->where($where)->count('id');
		$list = $this->where($where);
		// 若有分页
		if ($page && $limit) {
			$list = $list->page($page, $limit);
		}
		$list = $list->select();
		for ($i = 0; $i < count($list); $i++) {
			$list[$i]['project_name'] = Project::get($list[$i]['project_id'])->project_byname;
			$list[$i]['field_number'] = Db::name('field')->where('id', $list[$i]['field_id'])->value('name');
			$list[$i]['difficulty'] = $this->difficulty_arr[$list[$i]['difficulty']];
			$list[$i]['priority_level'] = $this->priority_level_arr[$list[$i]['priority_level']];
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
	 * 根据镜头状态与是否暂停状态获取列表数据
	 * @param $keywords
	 * @param $page
	 * @param $limit
	 * @param string $status
	 * @param string $is_assets 是否为等待资产 1是 2否
	 * @param string $is_pause 是否暂停 1 非暂停 2暂停
	 * @return mixed
	 * @throws \think\db\exception\DataNotFoundException
	 * @throws \think\db\exception\ModelNotFoundException
	 * @throws \think\exception\DbException
	 * @author zjs 2018/3/28
	 */
	public function getList_byStatus($keywords,$page, $limit, $status, $is_assets, $is_pause)
	{
		$where = [];
		if(!empty($keywords['project_id']) && empty($keywords['shot_number'])){
			$where['project_id'] = $keywords['project_id'];
		}
		if(!empty($keywords['field_id']) && empty($keywords['shot_number'])){
			$where['field_id'] = $keywords['field_id'];
		}
		if(!empty($keywords['shot_id']) && empty($keywords['shot_number'])){
			$where['id'] = $keywords['shot_id'];
		}
		if(!empty($keywords['shot_number'])){	//手动输入镜头编号
			$shot_number_len = strlen($keywords['shot_number']);
			//后期可对3 镜头号长度进行配置
			if($shot_number_len == 3){	//前端  对三位进行判断 必填场号
				$where['shot_number'] = $keywords['shot_number'];
			}
			//后期可对场号长度进行配置  场号+镜头号  暂定为6
			if($shot_number_len == 6){
				$shot_number = substr($keywords['shot_number'],3,3);
				$where['shot_number'] = $shot_number;
			}
		}
		$where['status'] = $status;
		$where['is_assets'] = $is_assets;
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
			$list[$key]['shot_number'] = Db::name('field')->where('id', $value['field_id'])->value('name') . $value['shot_number'];
			$list[$key]['priority_level'] = $this->priority_level_arr[$value['priority_level']];    //镜头优先级
			$list[$key]['difficulty'] = $this->difficulty_arr[$value['difficulty']];    //镜头难度
			$list[$key]['time'] = $this->time_arr[$value['time']];  //时刻
			$list[$key]['ambient'] = $this->ambient_arr[$value['ambient']]; //环境
			$list[$key]['surplus_days'] = floatval(sprintf("%.2f", ($value['plan_end_timestamp'] - time()) / 86400));   //剩余天数
			$list[$key]['create_timestamp'] = $value['create_time'];
			$list[$key]['create_time'] = date("Y-m-d H:i:s", $value['create_time']);
			$list[$key]['tache_info'] = $this->rate_of_progress($value['id']);
		}
		$data['list'] = $list;
		$data['dataCount'] = $dataCount;
		return $data;
	}

	//添加镜头及任务
	public function addData($param, $uid, $group_id)
	{
		//开启事务
		$this->startTrans();
		try {
			//资产ID 多项 字符串 以逗号分割(现在没有不加资产)
			//$param['asset_ids'] = implode(",",$param['asset_ids']);
			$param['shot_image'] = str_replace('\\', '/', $param['shot_image']);
			$param['plan_start_timestamp'] = strtotime($param['plan_start_timestamp']);
			$param['plan_end_timestamp'] = strtotime($param['plan_end_timestamp']);
			$param['create_time'] = time();
			foreach ($param['tache'] as $key => $value) {
				if (!empty($value)) {
					$tache_data[$key] = $value;
				}
			}
			//保存镜头表
			$result = $this->allowField(true)->save($param);
			//获取自增ID的镜头对象
			$curr_shot_obj = $this->get($this->id);
			if (false === $result) {
				$this->error = $this->getError();
				return false;
			} else {
				$project_byname = Project::get($param['project_id'])->project_byname;
				$field_name = Db::name('field')->where('id', $param['field_id'])->value('name');
				//执行redis添加镜头所属目录 python
				$str = "'Shot' '{$project_byname}' '{$field_name}' '{$param['shot_name']}'";
				//exec_python($str);
				//根据环节分配任务给各大工作室
				foreach ($tache_data as $key => $val) {
					foreach ($val as $k => $v) {
						$task_data['group_id'] = $group_id;    //所属角色ID
						$task_data['user_id'] = 0;   //所属用户ID
						$task_data['project_id'] = $curr_shot_obj->project_id;   //所属项目ID
						$task_data['field_id'] = $curr_shot_obj->field_id;   //场号ID
						$task_data['shot_id'] = $this->id;  //镜头ID
						$task_data['tache_id'] = $key;  //环节ID
						$task_data['tache_sort'] = Tache::get($key)->sort;  //环节排序
						$task_data['studio_id'] = $v;   //工作室ID
						$task_data['task_type'] = 1;    //镜头类型
						$task_data['task_image'] = $curr_shot_obj->shot_image;
						$task_data['task_byname'] = $curr_shot_obj->shot_byname;//任务简称暂且为镜头的简称，任务模块中，可修改
						$task_data['task_priority_level'] = $curr_shot_obj->priority_level;   //任务优先级
						$task_data['difficulty'] = $curr_shot_obj->difficulty;    //任务难度
						$task_data['plan_start_timestamp'] = $curr_shot_obj->plan_start_timestamp;  //计划开始时间
						$task_data['plan_end_timestamp'] = $curr_shot_obj->plan_end_timestamp;    //计划结束时间
						$task_data['task_status'] = 1;  //任务状态
						$task_data['is_assets'] = 2; //是否为等待资产 1是 2否
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
		$shot_obj = $this->get($id);
		if (!$shot_obj) {
			$this->error = '暂无此数据';
			return false;
		}
		//开启事务
		$this->startTrans();
		try {
			//资产ID 多项 字符串 以逗号分割(现在没有不加资产)
			$param['plan_start_timestamp'] = strtotime($param['plan_start_timestamp']);
			$param['plan_end_timestamp'] = strtotime($param['plan_end_timestamp']);
			$param['create_time'] = time();
			foreach ($param['tache'] as $key => $value) {
				if (!empty($value)) {
					$tache_data[$key] = $value;
				}
			}
			$shot_model = new Shot();
			if (!empty($tache_data)) {  //环节不为空  执行更新本镜头数据及添加相应环节的任务
				//更新当前镜头行记录
				$shot_model->allowField(true)->save($param, [$this->getPk() => $id]);
				$project_byname = Project::get($param['project_id'])->project_byname;
				$field_name = Db::name('field')->where('id', $param['field_id'])->value('name');
				//执行redis添加镜头所属目录 python
				$str = "'Shot' '{$project_byname}' '{$field_name}' '{$param['shot_name']}'";
				//exec_python($str);
				//根据环节分配任务给各大工作室
				foreach ($tache_data as $key => $val) {
					foreach ($val as $k => $v) {
						$task_data['group_id'] = 5;  //角色为工作室总监
						$task_data['user_id'] = 0;
						$task_data['project_id'] = $param['project_id'];   //所属项目ID
						$task_data['field_id'] = $param['field_id'];   //场号ID
						$task_data['shot_id'] = $id;  //镜头ID
						$task_data['tache_id'] = $key;  //环节ID
						$task_data['tache_sort'] = Tache::get($key)->sort;  //环节排序
						$task_data['studio_id'] = $v;   //工作室ID
						$task_data['task_type'] = 1;    //镜头类型
						$task_data['task_image'] = $shot_obj->shot_image;
						$task_data['task_byname'] = $shot_obj->shot_byname;//任务简称暂且为镜头的简称，任务模块中，可修改
						$task_data['task_priority_level'] = $shot_obj->priority_level;   //任务优先级
						$task_data['difficulty'] = $shot_obj->difficulty;    //任务难度
						$task_data['plan_start_timestamp'] = $shot_obj->plan_start_timestamp;  //计划开始时间
						$task_data['plan_end_timestamp'] = $shot_obj->plan_end_timestamp;    //计划结束时间
						$task_data['task_status'] = 1;  //任务状态
						$task_data['is_assets'] = 2; //是否为等待资产 1是 2否
						$task_data['pid'] = 0;  //工作室顶级任务ID都为0
						$task_data['create_time'] = time();//创建时间
						$task_model = new Workbench();
						$task_model->data($task_data, true)->isUpdate(false)->save();
					}
				}
				$this->commit();
				return true;
			} else {  //更新本镜头数据
				$shot_model->allowField(true)->save($param, [$this->getPk() => $id]);
				$this->commit();
				return true;
			}
		} catch (\Exception $e) {
			$this->rollback();
			$this->error = '编辑失败';
			return false;
		}
	}

	//根据镜头ID获取数据
	public function getData_ById($id)
	{
		$data = $this->get($id);
		$data->project_name = Project::get($data->project_id)->project_name;
		$data->field_name = Db::name('field')->where('id', $data->field_id)->value('name');
		$data->time_name = $this->time_arr[$data->time];
		$data->ambient_name = $this->ambient_arr[$data->ambient];
		$data->difficulty_name = $this->difficulty_arr[$data->difficulty];
		$data->priority_level_name = $this->priority_level_arr[$data->priority_level];
		$data->tache_info = $this->get_studio_byTache($data->id);
		if (!$data) {
			$this->error = '暂无此数据';
			return false;
		}
		return $data;
	}

	//根据镜头ID删除镜头及子任务
	public function delData_ById($id)
	{
		//开启事务
		$this->startTrans();
		try {
			$shot_result = $this->where($this->getPk(), $id)->delete();
			if (false === $shot_result) {
				$this->error = '镜头删除失败';
				return false;
			} else {
				//删除所属镜头的所有任务
				$taskBy_shotDel_result = Workbench::destroy(['shot_id' => $id]);
				if (false === $taskBy_shotDel_result) {
					$this->error = '镜头所属任务删除失败';
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

	//根据镜头ID获取所属环节下的工作室
	public function get_studio_byTache($shot_id)
	{
		$tache_ids_arr = array_unique(Workbench::where('shot_id', $shot_id)->column('tache_id'));
		foreach ($tache_ids_arr as $key => $value) {
			$data[$this->tache_byname_arr[$value]] = $this->get_studio_name(Workbench::where(['shot_id' => $shot_id, 'tache_id' => $value])->column('studio_id'));
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

	//获取当前镜头各环节进度
	public function rate_of_progress($shot_id)
	{
		$tache_data = array_unique(Workbench::where(['pid'=>0,'shot_id'=>$shot_id])->column('tache_id'));  //获取所属镜头的环节
		foreach ($tache_data as $key => $value) {
			//根据当前镜头ID与环节ID查询是否有其任务
			$curr_task_data = Workbench::where(['shot_id' => $shot_id, 'tache_id' => $value])->find();
			$finish_degree[$key]['tache_id'] = $value;
			$finish_degree[$key]['tache_byname'] = $this->tache_byname_arr[$value];
			$finish_degree[$key]['finish_degree'] = !empty($curr_task_data) ? $this->get_finish_degree_by_task($shot_id, $value) : '';
		}
		//根据环节ID获取所属任务
		return $finish_degree;
	}

	//根据任务获取完成度
	public function get_finish_degree_by_task($shot_id, $tache_id)
	{
		//获取当前环节下有几个工作室ID
		$studio_ids_arr = Workbench::where(['shot_id' => $shot_id, 'tache_id' => $tache_id])->column('studio_id');
		//多工作室
		if (count($studio_ids_arr) > 1) {
			foreach ($studio_ids_arr as $key => $value) {
				$studio_degree[] = $this->task_status_arr[Workbench::where(['pid' => 0, 'shot_id' => $shot_id, 'studio_id' => $value])->value('task_status')];
			}
			$curr_tache_degree = (array_sum($studio_degree) == 0) ? 0 : intval(array_sum($studio_degree) / count($studio_ids_arr));
		} else {//一个工作室 他的状态即是当前环节的进度
			$curr_tache_degree = $this->task_status_arr[Workbench::where(['pid' => 0, 'shot_id' => $shot_id, 'studio_id' => $studio_ids_arr[0]])->value('task_status')];    //获取这个任务的状态转化的进度值
		}
		return $curr_tache_degree;
	}

	//获取工作室列表 弹出视效、制片工作室
	public function getStudio_byShot($param)
	{
		$studio_data = Studio::where('pid', 1)->select();
		unset($studio_data[0]); //弹出视效工作室
		unset($studio_data[1]); //制片工作室
		$studio_data = array_values($studio_data);
		if (!empty($param)) {
			$studio_id_temp = Workbench::where(['shot_id' => $param['shot_id'], 'tache_id' => $param['tache_id']])->column('studio_id');
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
	public function TacheDel_ByShotId($shot_id, $tache_name)
	{
		$shot_obj = $this->get($shot_id);
		if (!$shot_obj) {
			$this->error = '暂无此数据';
			return false;
		}
		try {
			$tache_id = array_flip($this->tache_byname_arr)[$tache_name];
			$result = Workbench::destroy(['shot_id' => $shot_id, 'tache_id' => $tache_id]);
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

	//根据镜头ID与工作室ID删除对应的任务
	public function StudioDel_ByShotId($shot_id, $tache_name, $studio_id)
	{
		$shot_obj = $this->get($shot_id);
		if (!$shot_obj) {
			$this->error = '暂无此数据';
			return false;
		}
		try {
			$tache_id = array_flip($this->tache_byname_arr)[$tache_name];
			$result = Workbench::destroy(['shot_id' => $shot_id, 'tache_id' => $tache_id, 'studio_id' => $studio_id]);
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

	//添加场号
	public function field_add($param){
		$project_id = $param['project_id'];
		$name = trimall($param['name']);
		$project_obj = Project::get($project_id);
		if(!$project_obj){
			$this->error = '暂无数据';
			return false;
		}
		try{
			$where = [];
			$where['project_id'] = $project_id;
			$where['name'] = $name;
			$check_name = Db::name('field')->where($where)->find();
			if(!empty($check_name) || !is_null($check_name)){
				$this->error = '所属项目下场号重复';
				return false;
			}else{
				Db::name('field')->insert($param);
				return true;
			}
		}catch(\Exception $e){
			$this->error = '添加失败';
		}
	}

	//根据项目ID获取所属场的数据
	public function get_field_data($param){
		$data = [];
		if(!empty($param['project_id'])){
			$data = Db::name('field')->where('project_id', $param['project_id'])->select();
		}
		return $data;
	}

	//根据项目ID及场号ID 获取镜头ID 及镜头编号
	public function get_shot_number($param){
		$data = [];
		if(!empty($param['field_id'])){
			$data = $this->where('field_id',$param['field_id'])->select();
		}
		return $data;
	}

	//校验所属项目、所属场号下的镜头编号是否重复
	public function check_shot_num($param){
		$check_result = $this->where(['field_id'=>$param['field'],'shot_number'=>$param['shot_number']])->value('id');
		if(!empty($check_result)){
			$this->error = '镜头编号已重复';
			return false;
		}else{
			return true;
		}
	}
}