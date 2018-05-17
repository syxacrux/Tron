<?php
namespace app\admin\model;

use redis\RedisPackage;
use app\common\model\Common;

class Field extends Common{
	protected $name = 'field';

	//添加场号/集号/资产类型
	public function save_field_data($param){
		$project_id = $param['project_id'];
		$field_name = trimall($param['name']);
		$param['explain'] = !empty($param['explain']) ? trimall($param['explain']) : '';
		$project_obj = Project::get($project_id);
		if(!$project_obj){
			$this->error = '暂无数据';
			return false;
		}
		try{
			$where = [];
			$where['project_id'] = $project_id;
			$where['name'] = $field_name;
			$where['type'] = $param['type'];
			$check_name = $this->where($where)->find();
			$str = "'Seq' '{$project_obj->project_byname}' '{$field_name}'";
			switch ($param['type']){
				case 1:	//镜头
					if(!empty($check_name) || !is_null($check_name)){
						$this->error = '所属项目下场号重复';
						return false;
					}else{
						$this->save($param);
						//执行外部程序-开启队列
						/*
							$redis = new RedisPackage();
							$cmd = "python /usr/local/httpd/htdocs/tron/tronPipelineScript/createDirPath/parser.py $str ";
							$redis::LPush("pyFile",$cmd);
						*/
						return true;
					}
					break;
				case 2:	//资产
					if(!empty($check_name) || !is_null($check_name)){
						$this->error = '所属项目下资产类型重复';
						return false;
					}else{
						$this->save($param);
						//执行外部程序-开启队列
						/*
						$redis = new RedisPackage();
						$cmd = "python /usr/local/httpd/htdocs/tron/tronPipelineScript/createDirPath/parser.py $str ";
						$redis::LPush("pyFile",$cmd);
						*/
						return true;
					}
					break;
			}
		}catch(\Exception $e){
			$this->error = '添加失败';
		}
	}

	//根据项目ID获取所属场|资产类型的数据
	public function get_field_data($param){
		$data = [];
		if(!empty($param['project_id'])){
			$where['project_id'] = $param['project_id'];
			$where['type'] = $param['type'];
			$data = $this->where($where)->select();
		}
		return $data;
	}
}