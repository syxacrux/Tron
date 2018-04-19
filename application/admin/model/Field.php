<?php
namespace app\admin\model;

use think\Db;
use app\common\model\Common;

class Field extends Common{
	protected $name = 'field';

	//添加场号/集号/资产类型
	public function save_field_data($param){
		$project_id = $param['project_id'];
		$name = trimall($param['name']);
		$param['explain'] = !empty($param['explain']) ? trimall($param['explain']) : '';
		$project_obj = Project::get($project_id);
		if(!$project_obj){
			$this->error = '暂无数据';
			return false;
		}
		try{
			$where = [];
			$where['project_id'] = $project_id;
			$where['name'] = $name;
			$where['type'] = $param['type'];
			$check_name = $this->where($where)->find();
			switch ($param['type']){
				case 1:
					if(!empty($check_name) || !is_null($check_name)){
						$this->error = '所属项目下场号重复';
						return false;
					}else{
						$this->save($param);
						return true;
					}
					break;
				case 2:
					if(!empty($check_name) || !is_null($check_name)){
						$this->error = '所属项目下资产类型重复';
						return false;
					}else{
						$this->save($param);
						return true;
					}
					break;
			}
		}catch(\Exception $e){
			$this->error = '添加失败';
		}
	}
}