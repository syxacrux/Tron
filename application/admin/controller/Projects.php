<?php

namespace app\admin\controller;

use app\admin\model\Access;
use app\admin\model\Project;
use app\common\controller\BaseCommon;

class Projects extends BaseCommon
{

	//首页
	public function index()
	{
		$project_model = new Project();
		$uid = $this->uid;
		$group_id = Access::where('user_id',$uid)->value('group_id');
		$param = $this->param;
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'], true) : '';
		$data = $project_model->getList($keywords, $uid, $group_id);
		$data['uid'] = $uid;
		return resultArray(['data' => $data]);
	}

	//详情
	public function read()
	{
		$project_model = new Project();
		$param = $this->param;
		$data = $project_model->getData_ById($param['id']);
		if (!$data) {
			return resultArray(['error' => $project_model->getError()]);
		}
		return resultArray(['data' => $data]);
	}

	//新增
	public function save()
	{
		$project_model = new Project();
		$param = $this->param;
		$param['uid'] = $this->uid;
		$data = $project_model->addData($param);
		if (!$data) {
			return resultArray(['error' => $project_model->getError()]);
		}
		return resultArray(['data' => '添加成功']);
	}

	//编辑
	public function update()
	{
		$project_model = new Project();
		$param = $this->param;
		$param['project_image'] = str_replace('\\', '/', $param['project_image']);
		$param['plan_start_timestamp'] = strtotime($param['plan_start_timestamp']);
		$param['plan_end_timestamp'] = strtotime($param['plan_end_timestamp']);
		$param['create_time'] = time();
		$data = $project_model->updateDataById($param, $param['id']);
		if (!$data) {
			return resultArray(['error' => $project_model->getError()]);
		}
		return resultArray(['data' => '编辑成功']);
	}

	//删除
	public function delete()
	{
		$project_model = new Project();
		$param = $this->param;
		$data = $project_model->delProject($param['id']);
		if (!$data) {
			return resultArray(['error' => $project_model->getError()]);
		}
		return resultArray(['data' => '删除成功']);
	}

	//检测当前登陆人是否有编辑当前项目的权限
	public function editProject_ByAuth(){
		$project_model = model('Project');
		$param = $this->param;
		$uid = $this->uid;
		$group_id = Access::where('user_id',$uid)->value('group_id');
		$data = $project_model->getAuth_byUid($param,$uid,$group_id);
		return resultArray(['data'=>$data]);
	}

}