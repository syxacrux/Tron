<?php
namespace app\admin\model;

use think\Db;
use app\common\model\Common;

class Reference extends Common{
	protected $name = 'reference';

	//根据条件获取列表
	public function getList($keywords, $page, $limit){

	}

	//新增数据 调用python脚本上传数据并存储数据
	public function addData($param,$uid){
		try{
			if($param['resource_type'] == 2 || $param['resource_type'] == 3){	//类型 镜头 或 资产 都以项目别名作为目录名称
				$directory_name = Project::get($param['project_id'])->project_byname;
			}else{	//类型 公共  以field_id的英文名称为目录名称
				$directory_name = Field::get($param['field_id'])->name;
			}
			//根据类型获取所属目录相对路径
			switch ($param['resource_type']){
				case 1:	//公共
					$path = DS.'Common'.DS.$directory_name;
					break;
				case 2:	//镜头
					$path = DS.$directory_name.'shots';
					break;
				case 3:	//资产
					$path = DS.$directory_name.'assets';
					break;
			}
			$param['file_name'] = $param['name'].time();//文件名称命令规则 填写的名字+当前时间戳
			$param['path'] = $path;
			$param['user_id'] = $uid;
			$param['create_year'] = date("Y");
			$param['create_time'] = time();
			$result = $this->allowField(true)->save($param);
			//调用python脚本 未写
			if($result === false){
				$this->error = $this->getError();
				return false;
			}else{
				return true;
			}
		}catch(\Exception $e){
			$this->error = '添加失败';
			return false;
		}
	}

	//根据主键获取相应信息
	public function getData_ById($id){
		$data = $this->get($id);
		if($data->resource_type == 1){	//公共
			$data->category = Field::get($data->field_id)->explain;
		}else{	//镜头 || 资产
			$data->project_name = Project::get($data->project_id)->project_name;
			$data->field_name = Field::get($data->field_id)->name;
		}
		$data->tache_info_name = Tache::get_tache_names($data->tache_info,'explain',',');
		$data->user_name = User::get($data->user_id)->realname;
		if (!$data) {
			$this->error = '暂无此数据';
			return false;
		}
		return $data;
	}

	//编辑基本信息
	public function updateData_ById($param,$id,$uid){
		$data = $this->get($id);
		if (!$data) {
			$this->error = '暂无此数据';
			return false;
		}
		try{
			$param['user_id'] = $uid;
			$param['create_year'] = date("Y");
			$param['create_time'] = time();
			$reference_model = new Reference();
			$reference_model->allowField(true)->save($param, [$this->getPk() => $id]);
			return true;
		}catch(\Exception $e){
			$this->error = '编辑失败';
			return false;
		}
	}




}