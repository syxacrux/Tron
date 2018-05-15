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
		$uid = $this->uid;
		$screen = !empty($param['screen']) ? json_decode($param['screen'],true): '';
		$page = !empty($param['page']) ? $param['page']: '';
		$limit = !empty($param['limit']) ? $param['limit']: '';
		$data = $help_model->getHelpList($screen,$uid, $page, $limit);
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

	//发起文章
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

	//发起问题
	public function save_problem(){
		$help_model = model('Help');
		$uid = $this->uid;
		$param = $this->param;
		$data = $help_model->addProblem_ById($param,$uid);
		if(!$data){
			return resultArray(['error' => $help_model->getError()]);
		}else{
			return resultArray(['data'=>'回复成功']);
		}
	}

	//所属问题回复列表
	public function answer_list(){
		$help_model = model('Help');
		$param = $this->param;
		$data = $help_model->get_answer_list($param['help_id']);
		return resultArray(['data'=>$data]);
	}

	//编辑文章
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

	//根据单词模糊匹配相应记录
	public function new_ask_push(){
		$help_model = model('Help');
		$param = $this->param;
		$data = $help_model->getAskData($param);
		return resultArray(['data'=>$data]);
	}

	//删除
	public function delete(){
		$help_model = model('Help');
		$param = $this->param;
		$data = $help_model->delDataById($param['id'], true);
		if (!$data) {
			return resultArray(['error' => $help_model->getError()]);
		}
		return resultArray(['data' => '删除成功']);
	}

	//删除回复
	public function delete_answer(){
		$help_model = model('Help');
		$param = $this->param;
		$data = $help_model->answerDel_ById($param['id']);
		if(!$data){
			return resultArray(['error'=>$help_model->getError()]);
		}else{
			return resultArray(['data'=>'删除回复成功']);
		}
	}

	//根据多ID删除
	public function deletes_by_ids(){
		$help_model = model('Help');
		$param = $this->param;
		$data = $help_model->del_Datas($param['ids']);
		if (!$data) {
			return resultArray(['error' => $help_model->getError()]);
		}
		return resultArray(['data' => '删除成功']);
	}

	//批量启用 | 禁用 文章
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
