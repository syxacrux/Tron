<?php
namespace app\admin\model;

use think\Db;
use app\common\model\Common;

class Help extends Common{
	protected $name = 'helps';

	//带分页的列表
	public function getHelpList($screen,$page,$limit){
		$where = [];
		if(!empty($screen['category_id'])){
			$where['category_id'] = $screen['category_id'];
		}
		if(!empty($screen['keywords'])){	//关键词
			$where['keywords'] = ['like','%'.$screen['keywords']];
		}
		$where['status'] = 1; //默认显示启用
		$dataCount = $this->where($where)->count('id');
		$list = $this->where($where);
		//若有分页
		if($page && $limit){
			$list = $list->page($page,$limit);
		}
		$list = $list->select();
		for($i = 0;$i < count($list); $i++){
			$list[$i]['type_name'] = ($list[$i]['type'] == 1) ? '[百科]' : '[问题]';
			$list[$i]['category_name'] = Parameter::get($list[$i]['category_id'])->name;
			$list[$i]['degree_name'] = Parameter::get($list[$i]['degree'])->name;
			$list[$i]['user_name'] = User::get($list[$i]['user_id'])->realname;
			$list[$i]['create_time'] = date("Y-m-d H:i:s",$list[$i]['create_time']);
		}
		$data['list'] = $list;
		$data['dataCount'] = $dataCount;
		return $data;
	}

	//添加
	public function addData($param,$uid){
		try{
			$param['create_time'] = time();
			$param['user_id'] = $uid;
			$result = $this->save($param);
			if(false === $result){
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

	//编辑
	public function updateData_ById($param,$id,$uid){
		$help_obj = $this->get($id);
		if(!$help_obj){
			$this->error = '暂无此数据';
			return false;
		}
		try{
			$param['create_time'] = time();
			$param['user_id'] = $uid;
			$help_model = new Help();
			$help_model->save($param, [$this->getPk() => $id]);
			return true;
		}catch(\Exception $e){
			$this->error = '编辑失败';
			return false;
		}
	}



	//根据主键获取行记录
	public function getData_ById(){

	}




}