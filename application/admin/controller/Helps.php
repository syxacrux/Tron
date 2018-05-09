<?php
// +----------------------------------------------------------------------
// | Description: 组织架构
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use redis\RedisPackage;
use app\admin\model\Access;
use app\common\controller\BaseCommon;

class Helps extends BaseCommon{

	public function index(){
		$help_model = model('Help');
		$param = $this->param;
		$screen = !empty($param['screen']) ? json_decode($param['screen'],true): '';
		$page = !empty($param['page']) ? $param['page']: '';
		$limit = !empty($param['limit']) ? $param['limit']: '';
		$data = $help_model->getHelpList($screen, $page, $limit);
		return resultArray(['data' => $data]);
	}

	public function read(){
		$help_model = model('Help');
		$param = $this->param;
		$data = $help_model->getData_ById($param['id']);
		if (!$data) {
			return resultArray(['error' => $help_model->getError()]);
		}
		return resultArray(['data' => $data]);
	}

	public function save(){
		$help_model = model('Help');
		$uid = $this->uid;
		$param = $this->param;
		$data = $help_model->addData($param,$uid);
		if (!$data) {
			return resultArray(['error' => $help_model->getError()]);
		}
		return resultArray(['data' => '添加成功']);
	}

	public function update(){
		$help_model = model('Help');
		$uid = $this->uid;
		$param = $this->param;
		$data = $help_model->updateData_ById($param, $param['id'],$uid);
		if (!$data) {
			return resultArray(['error' => $help_model->getError()]);
		}
		return resultArray(['data' => '编辑成功']);
	}

	public function delete(){
		$help_model = model('Help');
		$param = $this->param;
		$data = $help_model->delDataById($param['id'], true);
		if (!$data) {
			return resultArray(['error' => $help_model->getError()]);
		}
		return resultArray(['data' => '删除成功']);
	}

	public function deletes(){
		$help_model = model('Studio');
		$param = $this->param;
		$data = $help_model->delDatas($param['ids'], true);
		if (!$data) {
			return resultArray(['error' => $help_model->getError()]);
		}
		return resultArray(['data' => '删除成功']);
	}

	public function enables(){
		$help_model = model('Help');
		$param = $this->param;
		$data = $help_model->enableDatas($param['ids'], $param['status'], true);
		if (!$data) {
			return resultArray(['error' => $help_model->getError()]);
		}
		return resultArray(['data' => '操作成功']);
	}
}
