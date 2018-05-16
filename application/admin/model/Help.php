<?php
namespace app\admin\model;

use think\Db;
use app\common\model\Common;

class Help extends Common{
	protected $name = 'helps';
	protected $category_arr = [1=>'[百科]',2=>'[提问]'];
	protected $system_category_arr = [0=>'未选择',1=>'mac',2=>'linux',3=>'windows'];

	//带分页的列表
	public function getHelpList($screen,$user_id,$page,$limit){
		$where = [];
		if($user_id != 1){
			$where['status'] = 1; //除超级管理员外，其他角色只能看到启用状态的数据
		}
		if(!empty($screen['category_id'])){
			$where['category_id'] = $screen['category_id'];
		}
		if(!empty($screen['system_category_id'])){
			$where['system_category_id'] = $screen['system_category_id'];
		}
		if(!empty($screen['keywords'])){	//关键词
			$where['keywords'] = ['like','%'.$screen['keywords']];
		}
		$dataCount = $this->where($where)->count('id');
		$list = $this->where($where);
		//若有分页
		if($page && $limit){
			$list = $list->page($page,$limit);
		}
		$list = $list->select();
		for($i = 0;$i < count($list); $i++){
			$list[$i]['type_name'] = $this->category_arr[$list[$i]['type']];
			$list[$i]['category_name'] = Parameter::get($list[$i]['category_id'])->category;
			$list[$i]['system_category_name'] = $this->system_category_arr[$list[$i]['system_category_id']];
			$list[$i]['degree_name'] = Parameter::get($list[$i]['degree'])->category;
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

	//问题回答
	public function addProblem_ById($param,$uid){
		try{
			$param['user_id'] = $uid;
			$param['create_time'] = time();
			$answer_model = new HelpAnswer();
			$result = $answer_model->save($param);
			if(false === $result){
				$this->error = $this->getError();
				return false;
			}else{
				return true;
			}
		}catch(\Exception $e){
			$this->error = '回复失败';
			return false;
		}
	}

	//所有问题的所有回复
	public function get_answer_list($help_id){
		$data = HelpAnswer::where('help_id',$help_id)->select();
		for($i=0;$i<count($data);$i++){
			$data[$i]['user_name'] = User::get($data[$i]['user_id'])->realname;
			$data[$i]['create_time'] = date('Y-m-d H:i:s',$data[$i]['create_time']);
		}
		return $data;
	}

	//根据主键获取行记录
	public function getData_ById($id){
		$help_obj = $this->get($id);
		if(!$help_obj){
			$this->error = '暂无数据';
			return false;
		}
		$help_obj->degree_name = Parameter::get($help_obj->degree)->category;
		$help_obj->user_name = User::get($help_obj->user_id)->realname;
		$help_obj->create_time = date('Y-m-d H:i:s',$help_obj->create_time);
		return $help_obj;
	}

	//根据单词查询相应问题列表
	public function getAskData($param,$page,$limit){
		$where = [];
		if(!empty($param['category_id'])){
			$where['category_id'] = $param['category_id'];
		}
		if(!empty($param['word'])){
			$where['title'] = ['like','%'.$param['word'].'%'];
		}
		$ask_data = $this->where($where);
		$dataCount = $this->where($where)->count('id');
		if($page && $limit){
			$ask_data = $ask_data->page($page,$limit);
		}else{
			$ask_data = $ask_data->order('id desc')->limit(5);
		}
		$ask_data = $ask_data->select();
		$data['list'] = $ask_data;
		$data['dataCount'] = $dataCount;
		return $data;
	}

	//删除回复
	public function answerDel_ById($id){
		try{
			$help_answer_obj = new HelpAnswer();
			$help_answer_obj->where('id',$id)->delete();
			return true;
		}catch(\Exception $e){
			$this->error = '删除失败';
			return false;
		}
	}

	//多选删除
	public function del_Datas($ids){
		if (empty($ids)) {
			$this->error = '删除失败';
			return false;
		}
		try{
			$ids = array_unique($ids);
			$this->where('id','in',$ids)->delete();
			return true;
		}catch(\Exception $e){
			$this->error = '删除失败';
			return false;
		}
	}

}