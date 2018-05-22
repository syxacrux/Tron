<?php

namespace app\admin\model;

use think\Db;
use app\common\model\Common;
use redis\RedisPackage;


class Shot extends Common
{

	protected $name = 'shot';
	protected $priority_level_arr = [1 => 'D', 2 => 'C', 3 => 'B', 4 => 'A'];   //镜头优先级
	protected $difficulty_arr = [1 => 'D', 2 => 'C', 3 => 'B', 4 => 'A', 5 => 'S']; //镜头难度
	protected $time_arr = [1 => '白天', 2 => '晚上'];   //时刻(1白天 2夜晚)
	protected $ambient_arr = [1 => '室外', 2 => '室内'];    //环境(1外 2内)
	protected $task_status_arr = [1 => 0, 5 => 20, 10 => 40, 15 => 60, 20 => 80, 25 => 100];    //用于任务状态计算进度百分比 status=>0%
	//根据环节ID获取镜头页面进度条所用别名
	protected $tache_byname_arr = [14 => '美术部', 18 => '模型部', 21 => '贴图部', 16 => '绑定部', 19 => '跟踪部', 22 => '动画部', 20 => '数字绘景部', 23 => '特效部', 24 => '灯光部', 25 => '合成部'];

	//镜头概况
	public function survey_list()
	{
		$where = [];
		if (!empty($keywords['project_id']) && empty($keywords['shot_number'])) {
			$where['project_id'] = $keywords['project_id'];
		}
		if (!empty($keywords['field_id']) && empty($keywords['shot_number'])) {
			$where['field_id'] = $keywords['field_id'];
		}
		if (!empty($keywords['shot_id']) && empty($keywords['shot_number'])) {
			$where['id'] = $keywords['shot_id'];
		}
		if (!empty($keywords['shot_number'])) {  //手动输入镜头编号
			$shot_number_len = strlen($keywords['shot_number']);
			//后期可对3 镜头号长度进行配置
			if ($shot_number_len == 3) {  //前端  对三位进行判断 必填场号
				$where['shot_number'] = $keywords['shot_number'];
			}
			//后期可对场号长度进行配置  场号+镜头号  暂定为6
			if ($shot_number_len == 6) {
				$shot_number = substr($keywords['shot_number'], 3, 3);
				$where['shot_number'] = $shot_number;
			}
		}
		$in_production_count = $this->where($where)->where('status', 5)->count('id');  //制作中
		$feedback_count = $this->where($where)->where('status', 15)->count('id');  //反馈中
		$pause_count = $this->where($where)->where('is_pause', 2)->count('id');  //暂停
		$waiting_assets_count = $this->where($where)->where('is_assets', 1)->count('id');  //等待资产
		$finish_count = $this->where($where)->where('status', 'in', [25, 30])->count('id');  //客户通过 和 完成
		$data['in_production_count'] = $in_production_count;
		$data['feedback_count'] = $feedback_count;
		$data['pause_count'] = $pause_count;
		$data['waiting_assets_count'] = $waiting_assets_count;
		$data['finish_count'] = $finish_count;
		return $data;
	}

	/**
	 * 镜头列表
	 * @param $keywords
	 * @param $page
	 * @param $limit
	 * @return mixed
	 */
	public function getList($keywords, $page, $limit)
	{
		$where = [];
		if (!empty($keywords['project_id']) && empty($keywords['shot_number'])) {
			$where['project_id'] = $keywords['project_id'];
		}
		if (!empty($keywords['field_id']) && empty($keywords['shot_number'])) {
			$where['field_id'] = $keywords['field_id'];
		}
		if (!empty($keywords['shot_id']) && empty($keywords['shot_number'])) {
			$where['id'] = $keywords['shot_id'];
		}
		if (!empty($keywords['shot_number'])) {  //手动输入镜头编号
			$shot_number_len = strlen($keywords['shot_number']);
			//后期可对3 镜头号长度进行配置
			if ($shot_number_len == 3) {  //前端  对三位进行判断 必填场号
				$where['shot_number'] = $keywords['shot_number'];
			}
			//后期可对场号长度进行配置  场号+镜头号  暂定为6
			if ($shot_number_len == 6) {
				$shot_number = substr($keywords['shot_number'], 3, 3);
				$where['shot_number'] = $shot_number;
			}
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
	public function getList_byStatus($keywords, $page, $limit, $status = '', $is_assets = '', $is_pause = '')
	{
		$where = [];
		if (!empty($keywords['project_id']) && empty($keywords['shot_number'])) {
			$where['project_id'] = $keywords['project_id'];
		}
		if (!empty($keywords['field_id']) && empty($keywords['shot_number'])) {
			$where['field_id'] = $keywords['field_id'];
		}
		if (!empty($keywords['shot_id']) && empty($keywords['shot_number'])) {
			$where['id'] = $keywords['shot_id'];
		}
		if (!empty($keywords['shot_number'])) {  //手动输入镜头编号
			$shot_number_len = strlen($keywords['shot_number']);
			//后期可对3 镜头号长度进行配置
			if ($shot_number_len == 3) {  //前端  对三位进行判断 必填场号
				$where['shot_number'] = $keywords['shot_number'];
			}
			//后期可对场号长度进行配置  场号+镜头号  暂定为6
			if ($shot_number_len == 6) {
				$shot_number = substr($keywords['shot_number'], 3, 3);
				$where['shot_number'] = $shot_number;
			}
		}
		if (!empty($status)) {
			$where['status'] = $status;
		}
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
			$list[$key]['shot_number'] = Field::get($value['field_id'])->name . $value['shot_number'];
			$list[$key]['priority_level'] = $this->priority_level_arr[$value['priority_level']];    //镜头优先级
			$list[$key]['difficulty'] = $this->difficulty_arr[$value['difficulty']];    //镜头难度
			$list[$key]['time'] = $this->time_arr[$value['time']];  //时刻
			$list[$key]['ambient'] = $this->ambient_arr[$value['ambient']]; //环境
			$list[$key]['surplus_days'] = floatval(sprintf("%.2f", ($value['plan_end_timestamp'] - time()) / 86400));   //剩余天数
			$list[$key]['create_timestamp'] = $value['create_time'];
			$list[$key]['create_time'] = date("Y-m-d H:i:s", $value['create_time']);
			$list[$key]['tache_info'] = $this->rate_of_progress($value['id']);//各环节进度计算显示
			$list[$key]['asset_info'] = !empty($value['asset_ids']) ? $this->get_assets_name($value['asset_ids']) : [];
		}
		$data['list'] = $list;
		$data['dataCount'] = $dataCount;
		return $data;
	}

	//添加镜头及任务
	public function addData($param, $group_id)
	{
		$project_obj = Project::get($param['project_id']);
		//开启事务
		$this->startTrans();
		try {
			$is_assets = !empty($param['asset_ids']) ? $this->check_assets_status($param['asset_ids']) : 2;  // return 1/2
			$param['shot_image'] = str_replace('\\', '/', $param['shot_image']);
			$param['plan_start_timestamp'] = strtotime($param['plan_start_timestamp']);
			$param['plan_end_timestamp'] = strtotime($param['plan_end_timestamp']);
			$param['create_time'] = time();
			//接收的环节下的工作室可以为空 ，将数据存储到 $tache_data 中
			foreach ($param['tache'] as $key => $value) {
				$tache_data[$key] = $value;
			}
			//保存镜头表
			$result = $this->allowField(true)->save($param);
			$shot_id = $this->id;
			//获取自增ID的镜头对象
			$curr_shot_obj = $this->get($this->id);
			if (false === $result) {
				$this->error = $this->getError();
				return false;
			} else {
				//镜头表新增成功 更新所属项目表镜头数量 lens_count +1
				$lens_count['lens_count'] = $this->where('project_id', $param['project_id'])->count('id');
				$project_model = new Project();
				$project_model->where('id', $param['project_id'])->update($lens_count);
				$project_byname = $project_obj->project_byname;
				$field_name = Field::get($param['field_id'])->name;

				//执行redis添加镜头所属目录 python
				$str = "'Shot' '{$project_byname}' '{$field_name}' '{$param['shot_name']}'";
				/*
				$redis = new RedisPackage();
				$cmd = "python /usr/local/httpd/htdocs/tron/tronPipelineScript/createDirPath/parser.py $str ";
				$redis::LPush("pyFile",$cmd);
				*/

				//根据环节分配任务给各大工作室
				foreach ($tache_data as $key => $val) {
					//根据环节内的工作室是否为空创建任务
					if (!empty($val)) {
						foreach ($val as $k => $v) {
							$task_data['group_id'] = $group_id;    //所属角色ID
							$task_data['user_id'] = 0;   //所属用户ID
							$task_data['project_id'] = $curr_shot_obj->project_id;   //所属项目ID
							$task_data['field_id'] = $curr_shot_obj->field_id;   //场号ID
							$task_data['resource_id'] = $shot_id;  //镜头ID
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
							$task_data['is_assets'] = $is_assets; //是否为等待资产 1是 2否
							$task_data['pid'] = 0;  //工作室顶级任务ID都为0
							$task_data['create_time'] = time();//创建时间
							$task_model = new Workbench();
							$task_model->data($task_data)->save();
						}
					} else {
						$task_data['group_id'] = $group_id;    //所属角色ID
						$task_data['user_id'] = 0;   //所属用户ID
						$task_data['project_id'] = $curr_shot_obj->project_id;   //所属项目ID
						$task_data['field_id'] = $curr_shot_obj->field_id;   //场号ID
						$task_data['resource_id'] = $shot_id;  //镜头ID
						$task_data['tache_id'] = $key;  //环节ID
						$task_data['tache_sort'] = Tache::get($key)->sort;  //环节排序
						$task_data['studio_id'] = 0;   //工作室ID
						$task_data['task_type'] = 1;    //镜头类型
						$task_data['task_image'] = $curr_shot_obj->shot_image;
						$task_data['task_byname'] = $curr_shot_obj->shot_byname;//任务简称暂且为镜头的简称，任务模块中，可修改
						$task_data['task_priority_level'] = $curr_shot_obj->priority_level;   //任务优先级
						$task_data['difficulty'] = $curr_shot_obj->difficulty;    //任务难度
						$task_data['plan_start_timestamp'] = $curr_shot_obj->plan_start_timestamp;  //计划开始时间
						$task_data['plan_end_timestamp'] = $curr_shot_obj->plan_end_timestamp;    //计划结束时间
						$task_data['task_status'] = 1;  //任务状态
						$task_data['is_assets'] = $is_assets; //是否为等待资产 1是 2否
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
			$param['asset_ids'] = !empty($param['asset_ids']) ? implode(',', $param['asset_ids']) : '';
			$param['plan_start_timestamp'] = strtotime($param['plan_start_timestamp']);
			$param['plan_end_timestamp'] = strtotime($param['plan_end_timestamp']);
			$param['create_time'] = time();
			//检测所属镜头ID的资产是否包含未完成的任务
			$is_assets = !empty($param['asset_ids']) ? $this->check_assets_status($param['asset_ids']) : 2;  // return 1/2

			$shot_model = new Shot();
			//更新当前镜头行记录
			$shot_model->allowField(true)->save($param, [$this->getPk() => $id]);

			/* 修改基本信息 或者 为环节分配所属工作室的任务 */
			if (!empty($param['tache'])) {
				/**
				 * begin 处理环节内的数据
				 * $tache_has_data array 环节内包含工作室
				 * $tache_empty_data array 环节内不包含工作室时 环节ID校验所属镜头包含的环节，过滤已存在的返回
				 * 执行为环节创建任务的操作
				 */
				$tache_empty_data = [];
				$tache_has_data = [];
				foreach ($param['tache'] as $key => $value) {
					if (!empty($value)) {
						$tache_has_data[$key] = $value;  //存在工作室
					} else {
						$tache_empty_data[] = $key;  //不存在工作室
					}
				}
				//获取当前镜头下的所有环节
				$tache_by_task = array_unique(Workbench::where('resource_id', $id)->column('tache_id'));

				//弹出相同的环节
				if (!empty($tache_empty_data)) {
					foreach ($tache_by_task as $key => $value) {
						foreach ($tache_empty_data as $k => $v) {
							if ($v == $value) {
								unset($tache_empty_data[$k]);
							} else {
								break;
							}
						}
					}
				}

				//环节内有工作室 给相应工作室总监分配任务
				if (!empty($tache_has_data)) {
					foreach ($tache_has_data as $key => $value) {
						foreach ($value as $k => $v) {
							$task_data['group_id'] = 5;
							$task_data['user_id'] = 0;
							$task_data['project_id'] = $param['project_id'];   //所属项目ID
							$task_data['field_id'] = $param['field_id'];   //场号ID
							$task_data['resource_id'] = $id;  //镜头ID
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
							$task_data['is_assets'] = $is_assets; //是否为等待资产 1是 2否
							$task_data['pid'] = 0;  //工作室顶级任务ID都为0
							$task_data['create_time'] = time();//创建时间
							$task_model = new Workbench();
							$task_model->data($task_data, true)->isUpdate(false)->save();
						}
						unset($task_data);
					}
				}

				//新增环节 但无工作室
				if (!empty($tache_empty_data)) {
					foreach ($tache_empty_data as $key => $value) {
						$task_data['group_id'] = 0;
						$task_data['user_id'] = 0;
						$task_data['project_id'] = $param['project_id'];   //所属项目ID
						$task_data['field_id'] = $param['field_id'];   //场号ID
						$task_data['resource_id'] = $id;  //镜头ID
						$task_data['tache_id'] = $value;  //环节ID
						$task_data['tache_sort'] = Tache::get($value)->sort;  //环节排序
						$task_data['studio_id'] = 0;   //工作室ID
						$task_data['task_type'] = 1;    //镜头类型
						$task_data['task_image'] = $shot_obj->shot_image;
						$task_data['task_byname'] = $shot_obj->shot_byname;//任务简称暂且为镜头的简称，任务模块中，可修改
						$task_data['task_priority_level'] = $shot_obj->priority_level;   //任务优先级
						$task_data['difficulty'] = $shot_obj->difficulty;    //任务难度
						$task_data['plan_start_timestamp'] = $shot_obj->plan_start_timestamp;  //计划开始时间
						$task_data['plan_end_timestamp'] = $shot_obj->plan_end_timestamp;    //计划结束时间
						$task_data['task_status'] = 1;  //任务状态
						$task_data['is_assets'] = $is_assets; //是否为等待资产 1是 2否
						$task_data['pid'] = 0;  //工作室顶级任务ID都为0
						$task_data['create_time'] = time();//创建时间
						$task_model = new Workbench();
						$task_model->data($task_data, true)->isUpdate(false)->save();
					}
					unset($task_data);
				}
				//更新任务数据 过滤未分配工作室的任务
				$task_count['task_count'] = Workbench::where('project_id', $param['project_id'])->where('studio_id','neq',0)->count('id');
				$project_obj = new Project();
				$project_obj->where('id', $param['project_id'])->update($task_count);
				//添加
				$this->commit();
				return true;
			} else {  //更新镜头数据
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

	//检测所属镜头下的资产
	public function check_assets_status($asset_ids_str)
	{
		$asset_ids = explode(',', $asset_ids_str);
		foreach ($asset_ids as $key => $value) {
			if (Asset::get($value)->status < 20) {  //通过 非等待资产状态
				$check_data[] = 1;
			}
		}
		if (!empty($check_data)) {
			return 1;
		} else {
			return 2;
		}
	}

	//根据镜头ID获取数据
	public function getData_ById($id)
	{
		$data = $this->get($id);
		$data->project_name = Project::get($data->project_id)->project_name;
		$data->field_name = Field::get($data->field_id)->name;
		$data->time_name = $this->time_arr[$data->time];
		$data->ambient_name = $this->ambient_arr[$data->ambient];
		$data->difficulty_name = $this->difficulty_arr[$data->difficulty];
		$data->priority_level_name = $this->priority_level_arr[$data->priority_level];
		$data->tache_info = $this->get_studio_byTache($data->id);
		$data->asset_info = !empty($data->asset_ids) ? $this->get_assets_name($data->asset_ids)['asset_arr'] : [];
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
		$tache_ids_arr = array_unique(Workbench::where('resource_id', $shot_id)->column('tache_id'));
		foreach ($tache_ids_arr as $key => $value) {
			$data[$this->tache_byname_arr[$value]] = $this->get_studio_name(Workbench::where(['resource_id' => $shot_id, 'tache_id' => $value])->column('studio_id'));
		}
		return $data;
	}

	//根据工作室ID数组拼接成中文字符串
	public function get_studio_name($studio_id_arr)
	{
		$studio_ids_arr = array_unique($studio_id_arr);
		foreach ($studio_ids_arr as $key => $value) {
			if ($value != 0) {
				$arr[$key]['id'] = $value;
				$arr[$key]['name'] = Studio::get($value)->name;
			} else {
				$arr = [];
			}
		}
		$data = array_values($arr);
		return $data;
	}

	//根据资产ID string 获取名称
	public function get_assets_name($asset_ids_str)
	{
		$asset_ids_arr = explode(',', $asset_ids_str);
		$asset_arr = [];
		foreach ($asset_ids_arr as $key => $value) {
			$asset_arr[$key]['type'] = Field::get(Asset::get($value)->field_id)->explain;
			$asset_arr[$key]['name'] = $asset_names[] = Asset::get($value)->asset_name;
		}
		$data['asset_names'] = implode(',', $asset_names);
		$data['asset_arr'] = $asset_arr;
		return $data;
	}

	//获取当前镜头各环节进度
	public function rate_of_progress($shot_id)
	{
		$tache_data = array_unique(Workbench::where(['pid' => 0, 'resource_id' => $shot_id])->column('tache_id'));  //获取所属镜头的环节
		foreach ($tache_data as $key => $value) {
			//根据当前镜头ID与环节ID查询是否有其任务
			$curr_task_data = Workbench::where(['resource_id' => $shot_id, 'tache_id' => $value])->find();
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
		$studio_ids_arr = Workbench::where(['resource_id' => $shot_id, 'tache_id' => $tache_id])->column('studio_id');
		//多工作室
		if (count($studio_ids_arr) > 1) {
			foreach ($studio_ids_arr as $key => $value) {
				$studio_degree[] = $this->task_status_arr[Workbench::where(['pid' => 0, 'resource_id' => $shot_id, 'studio_id' => $value])->value('task_status')];
			}
			$curr_tache_degree = (array_sum($studio_degree) == 0) ? 0 : intval(array_sum($studio_degree) / count($studio_ids_arr));
		} else {//一个工作室 他的状态即是当前环节的进度
			$curr_tache_degree = $this->task_status_arr[Workbench::where(['pid' => 0, 'resource_id' => $shot_id, 'studio_id' => $studio_ids_arr[0]])->value('task_status')];    //获取这个任务的状态转化的进度值
		}
		return $curr_tache_degree;
	}

	//获取工作室列表 弹出视效、制片工作室
	public function getStudio_byShot($param)
	{
		//弹出了视效工作室
		$studio_ids_arr = array_unique(User::where('company_id',1)->where('studio_id','in',[2,5,6,7,8,9])->column('studio_id'));	//未来加外包公司 以所属项目 所属镜头的公司ID获取工作室

		$studio_data = Studio::where('id','in',$studio_ids_arr)->select();
		if (!empty($param)) {
			$studio_id_temp = Workbench::where(['resource_id' => $param['shot_id'], 'tache_id' => $param['tache_id']])->column('studio_id');
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
			$result = Workbench::destroy(['resource_id' => $shot_id, 'tache_id' => $tache_id]);
			if ($result === false) {
				$this->error = '删除失败';
				return false;
			} else {
				//更新所属项目的任务数量 过滤未分配工作室的任务
				$taskCount['task_count'] = Workbench::where('id',$shot_obj->project_id)->where('studio_id','neq',0)->count('id');
				$project_obj = new Project();
				$project_obj->where('id',$shot_obj->project_id)->update($taskCount);
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
			$result = Workbench::destroy(['resource_id' => $shot_id, 'tache_id' => $tache_id, 'studio_id' => $studio_id]);
			if (false === $result) {
				$this->error = '删除失败';
				return false;
			} else {
				//更新所属项目任务数量 过滤未分配工作室的任务
				$taskCount['task_count'] = Workbench::where('resource_id',$shot_obj->id)->where('studio_id','neq',0)->count('id');
				$project_obj = new Project();
				$project_obj->where('id',$shot_obj->project_id)->udpate($taskCount);
				return true;
			}
		} catch (\Exception $e) {
			$this->error = '删除失败';
			return false;
		}
	}

	//根据项目ID及场号ID 获取镜头ID 及镜头编号
	public function get_shot_number($param)
	{
		$data = [];
		if (!empty($param['field_id'])) {
			$data = $this->where('field_id', $param['field_id'])->select();
		}
		return $data;
	}

	//校验所属项目、所属场号下的镜头编号是否重复
	public function check_shot_num($param)
	{
		$check_result = $this->where(['field_id' => $param['field_id'], 'shot_number' => $param['shot_number']])->value('id');
		if (!empty($check_result)) {
			$this->error = '镜头编号已重复';
			return false;
		} else {
			return true;
		}
	}

	//获取模板
	public function shot_template()
	{
		$file_name = '123.xls';
		$files = "uploads/Projects/excels/" . $file_name;
		if (is_file($files)) {
			return $files;
		} else {
			$PHPExcel = new \PHPExcel();
			$PHPSheet = $PHPExcel->getActiveSheet();
			$PHPSheet->setTitle("镜头模板"); //给当前活动sheet设置名称
			$PHPSheet->setCellValue("A1", "姓名1")->setCellValue("B1", "分数");//表格数据
			$PHPSheet->setCellValue("A2", "张三")->setCellValue("B2", "2121");//表格数据
			$objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
			header('Content-Disposition: attachment;filename=".$file_name."');
			header('Content-Type: application/vnd.ms-excel; charset=utf-8');
			header("Content-Disposition: attachment;filename=$file_name");
			header('Cache-Control: max-age=0');
			$objWriter->save($files); //文件通过浏览器下载
			return $files;
		}
	}

	public function shot_import($param){
		
	}

}