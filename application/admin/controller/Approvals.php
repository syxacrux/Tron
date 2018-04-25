<?php
// +----------------------------------------------------------------------
// | Description: 镜头管理
// +----------------------------------------------------------------------
// | Author: Zjs <839804865@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\Access;
use app\common\controller\BaseCommon;

class Approvals extends BaseCommon
{

	//标准列表
	public function index()
	{
		$shot_model = model('Shot');
		$param = $this->param;
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'],true) : '';
		$page = !empty($param['page']) ? $param['page'] : '';
		$limit = !empty($param['limit']) ? $param['limit'] : '';
		$data = $shot_model->getList($keywords, $page, $limit);
		return resultArray(['data' => $data]);
	}

	//图片base64解析并生成图片存入服务器中
	public function images_base64_upload(){

	}

	//详情
	public function read()
	{
		$shot_model = model('Shot');
		$param = $this->param;
		$data = $shot_model->getData_ById($param['id']);
		if (!$data) {
			return resultArray(['error' => $shot_model->getError()]);
		}
		return resultArray(['data' => $data]);
	}

	//新增
	public function save()
	{
		$shot_model = model('Shot');
		$uid = $this->uid;
		if($uid == 1){	//超级管理员创建任务，则角色传0
			$group_id = 0;
		}else{
			$group_id = Access::where('user_id', $uid)->value('group_id'); //所属角色
		}
		$param = $this->param;
		$data = $shot_model->addData($param,$group_id);
		if (!$data) {
			return resultArray(['error' => $shot_model->getError()]);
		}
		return resultArray(['data' => '添加成功']);
	}

	//编辑
	public function update()
	{
		$shot_model = model('Shot');
		$param = $this->param;
		$data = $shot_model->updateData_ById($param, $param['id']);
		if (!$data) {
			return resultArray(['error' => $shot_model->getError()]);
		}
		return resultArray(['data' => '编辑成功']);
	}

	//删除
	public function delete()
	{
		$shot_model = model('Shot');
		$param = $this->param;
		$data = $shot_model->delData_ById($param['id'], true);//删除镜头还要删除目录 未做
		if (!$data) {
			return resultArray(['error' => $shot_model->getError()]);
		}
		return resultArray(['data' => '删除成功']);
	}

	public function deletes()
	{
		$shot_model = model('Shot');
		$param = $this->param;
		$data = $shot_model->delDatas($param['ids'], true);
		if (!$data) {
			return resultArray(['error' => $shot_model->getError()]);
		}
		return resultArray(['data' => '删除成功']);
	}

}
