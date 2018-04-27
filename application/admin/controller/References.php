<?php
// +----------------------------------------------------------------------
// | Description: 参考库管理
// +----------------------------------------------------------------------
// | Author: Zjs <839804865@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\Access;
use app\common\controller\BaseCommon;

class References extends BaseCommon
{

	//首页
	public function index()
	{
		$reference_model = model('reference');
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'], true) : '';
		$page = !empty($param['page']) ? $param['page'] : '';
		$limit = !empty($param['limit']) ? $param['limit'] : '';
		$data = $reference_model->getList($keywords, $page, $limit);
		return resultArray(['data' => $data]);
	}

	//新增
	public function save(){
		$reference_model = model('reference');
		$uid = $this->uid;
		$param = $this->param;
		$data = $reference_model->addData($param,$uid);
		if (!$data) {
			return resultArray(['error' => $reference_model->getError()]);
		}
		return resultArray(['data' => '添加成功']);
	}

	//详情
	public function read(){
		$reference_model = model('reference');
		$param = $this->param;
		$data = $reference_model->getData_ById($param['id']);
		if (!$data) {
			return resultArray(['error' => $reference_model->getError()]);
		}
		return resultArray(['data' => $data]);
	}

	//编辑
	public function update()
	{
		$reference_model = model('reference');
		$param = $this->param;
		$uid = $this->uid;
		$data = $reference_model->updateData_ById($param, $param['id'],$uid);
		if (!$data) {
			return resultArray(['error' => $reference_model->getError()]);
		}
		return resultArray(['data' => '编辑成功']);
	}

	//删除
	public function delete(){
		$reference_model = model('reference');
		$param = $this->param;
		$data = $reference_model->delData_ById($param['id'], true);//删除镜头还要删除目录 未做
		if (!$data) {
			return resultArray(['error' => $reference_model->getError()]);
		}
		return resultArray(['data' => '删除成功']);
	}

	//批量删除
	public function deletes(){
		$reference_model = model('reference');
	}

	//下载
	public function download(){
		$reference_model = model('reference');
	}

}