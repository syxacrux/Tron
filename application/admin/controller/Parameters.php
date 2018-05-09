<?php
// +----------------------------------------------------------------------
// | Description: 组织架构
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\common\controller\BaseCommon;

class Parameters extends BaseCommon{

	public function index(){
		$parameter_model = model('Parameter');
		$param = $this->param;
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'],true): '';
		$page = !empty($param['page']) ? $param['page']: '';
		$limit = !empty($param['limit']) ? $param['limit']: '';
		$data = $parameter_model->getList($keywords, $page, $limit);
		return resultArray(['data' => $data]);
	}

	public function read(){
		$parameter_model = model('Parameter');
		$param = $this->param;
		$data = $parameter_model->getData_ById($param['id']);
		if (!$data) {
			return resultArray(['error' => $parameter_model->getError()]);
		}
		return resultArray(['data' => $data]);
	}

	public function save(){
		$parameter_model = model('Parameter');
		$param = $this->param;
		$data = $parameter_model->createData($param);
		if (!$data) {
			return resultArray(['error' => $parameter_model->getError()]);
		}
		return resultArray(['data' => '添加成功']);
	}

	public function update(){
		$parameter_model = model('Parameter');
		$param = $this->param;
		$data = $parameter_model->updateDataById($param, $param['id']);
		if (!$data) {
			return resultArray(['error' => $parameter_model->getError()]);
		}
		return resultArray(['data' => '编辑成功']);
	}

	public function delete(){
		$parameter_model = model('Parameter');
		$param = $this->param;
		$data = $parameter_model->delDataById($param['id'], true);
		if (!$data) {
			return resultArray(['error' => $parameter_model->getError()]);
		}
		return resultArray(['data' => '删除成功']);
	}

	public function deletes(){
		$parameter_model = model('Parameter');
		$param = $this->param;
		$data = $parameter_model->delDatas($param['ids'], true);
		if (!$data) {
			return resultArray(['error' => $parameter_model->getError()]);
		}
		return resultArray(['data' => '删除成功']);
	}

}
