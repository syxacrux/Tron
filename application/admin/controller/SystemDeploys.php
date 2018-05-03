<?php
// +----------------------------------------------------------------------
// | Description: 组织架构
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\Access;
use app\common\controller\BaseCommon;

class SystemDeploys extends BaseCommon{

	public function index(){
		$deploy_model = model('SystemDeploy');
		$param = $this->param;
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'],true): '';
		$page = !empty($param['page']) ? $param['page']: '';
		$limit = !empty($param['limit']) ? $param['limit']: '';
		$data = $deploy_model->getList($keywords, $page, $limit);
		return resultArray(['data' => $data]);
	}

	public function read(){
		$deploy_model = model('SystemDeploy');
		$param = $this->param;
		$data = $deploy_model->getData_ById($param['id']);
		if (!$data) {
			return resultArray(['error' => $deploy_model->getError()]);
		}
		return resultArray(['data' => $data]);
	}

	public function save(){
		$deploy_model = model('SystemDeploy');
		$param = $this->param;
		$data = $deploy_model->createData($param);
		if (!$data) {
			return resultArray(['error' => $deploy_model->getError()]);
		}
		return resultArray(['data' => '添加成功']);
	}

	public function update(){
		$deploy_model = model('SystemDeploy');
		$param = $this->param;
		$data = $deploy_model->updateDataById($param, $param['id']);
		if (!$data) {
			return resultArray(['error' => $deploy_model->getError()]);
		}
		return resultArray(['data' => '编辑成功']);
	}

	public function delete(){
		$deploy_model = model('SystemDeploy');
		$param = $this->param;
		$data = $deploy_model->delDataById($param['id'], true);
		if (!$data) {
			return resultArray(['error' => $deploy_model->getError()]);
		}
		return resultArray(['data' => '删除成功']);
	}

	public function deletes(){
		$deploy_model = model('SystemDeploy');
		$param = $this->param;
		$data = $deploy_model->delDatas($param['ids'], true);
		if (!$data) {
			return resultArray(['error' => $deploy_model->getError()]);
		}
		return resultArray(['data' => '删除成功']);
	}

}
