<?php
// +----------------------------------------------------------------------
// | Description: 资产库管理
// +----------------------------------------------------------------------
// | Author: Zjs <839804865@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\Access;
use app\common\controller\BaseCommon;

class Assets extends BaseCommon
{
	public function survey(){
		$shot_model = model('Shot');
		$param = $this->param;
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'],true) : '';
		$data = $shot_model->survey_list($keywords);
		return resultArray(['data'=>$data]);
	}

	//标准列表
	public function index()
	{
		$asset_model = model('asset');
		$uid = $this->uid;
		$group_id = Access::where('user_id', $uid)->value('group_id'); //所属角色
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'], true) : '';
		$page = !empty($param['page']) ? $param['page'] : '';
		$limit = !empty($param['limit']) ? $param['limit'] : '';
		$data = $asset_model->getList($keywords, $page, $limit, $uid, $group_id);
		return resultArray(['data' => $data]);
	}

	//接口 - 镜头看板 - 制作中
	public function in_production_data()
	{
		$asset_model = model('asset');
		$param = $this->param;
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'],true) : '';
		$page = !empty($param['page']) ? $param['page'] : '';
		$limit = !empty($param['limit']) ? $param['limit'] : '';
		$data = $asset_model->getList_byStatus($keywords,$page, $limit, 5, 2, 1);
		return resultArray(['data' => $data]);
	}

	//接口 - 镜头看板 - 反馈中
	public function feedback_data()
	{
		$asset_model = model('asset');
		$param = $this->param;
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'],true) : '';
		$page = !empty($param['page']) ? $param['page'] : '';
		$limit = !empty($param['limit']) ? $param['limit'] : '';
		$data = $asset_model->getList_byStatus($keywords,$page, $limit, 15, 2, 1);
		return resultArray(['data' => $data]);
	}

	//接口 - 镜头看板 - 等待资产
	public function waiting_assets_data()
	{
		$asset_model = model('asset');
		$param = $this->param;
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'],true) : '';
		$page = !empty($param['page']) ? $param['page'] : '';
		$limit = !empty($param['limit']) ? $param['limit'] : '';
		$data = $asset_model->getList_byStatus($keywords,$page, $limit, 1, 1, 1);
		return resultArray(['data' => $data]);
	}

	//接口 - 镜头看板 - 暂停
	public function pause_data()
	{
		$asset_model = model('asset');
		$param = $this->param;
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'],true) : '';
		$page = !empty($param['page']) ? $param['page'] : '';
		$limit = !empty($param['limit']) ? $param['limit'] : '';
		$data = $asset_model->getList_byStatus($keywords,$page, $limit, 5, 2, 2);
		return resultArray(['data' => $data]);
	}

	//接口 - 镜头看板 - 完成
	public function finish_data()
	{
		$asset_model = model('asset');
		$param = $this->param;
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'],true) : '';
		$page = !empty($param['page']) ? $param['page'] : '';
		$limit = !empty($param['limit']) ? $param['limit'] : '';
		$data = $asset_model->getList_byStatus($keywords,$page, $limit, 25, 1, 1);
		return resultArray(['data' => $data]);
	}

	//详情
	public function read()
	{
		$asset_model = model('asset');
		$param = $this->param;
		$data = $asset_model->getData_ById($param['id']);
		if (!$data) {
			return resultArray(['error' => $asset_model->getError()]);
		}
		return resultArray(['data' => $data]);
	}

	//新增
	public function save()
	{
		$asset_model = model('asset');
		$uid = $this->uid;
		$group_id = Access::where('user_id', $uid)->value('group_id'); //所属角色
		$param = $this->param;
		$data = $asset_model->addData($param,$group_id);
		if (!$data) {
			return resultArray(['error' => $asset_model->getError()]);
		}
		return resultArray(['data' => '添加成功']);
	}

	//编辑
	public function update()
	{
		$asset_model = model('asset');
		$param = $this->param;
		$data = $asset_model->updateData_ById($param, $param['id']);
		if (!$data) {
			return resultArray(['error' => $asset_model->getError()]);
		}
		return resultArray(['data' => '编辑成功']);
	}

	//删除
	public function delete()
	{
		$asset_model = model('asset');
		$param = $this->param;
		$data = $asset_model->delData_ById($param['id'], true);//删除镜头还要删除目录 未做
		if (!$data) {
			return resultArray(['error' => $asset_model->getError()]);
		}
		return resultArray(['data' => '删除成功']);
	}

	public function deletes()
	{
		$asset_model = model('asset');
		$param = $this->param;
		$data = $asset_model->delDatas($param['ids'], true);
		if (!$data) {
			return resultArray(['error' => $asset_model->getError()]);
		}
		return resultArray(['data' => '删除成功']);
	}

	//接口 - 根据镜头ID及环节获取工作室列表
	public function get_studio_list()
	{
		$asset_model = model('asset');
		$param = $this->param;
		$data = $asset_model->getStudio_byShot($param);
		return resultArray(['data' => $data]);
	}

	//镜头 - 删除环节(所属镜头下的环节)
	public function delete_tache()
	{
		$asset_model = model('asset');
		$param = $this->param;
		$shot_id = !empty($param['id']) ? $param['id'] : '';
		$tache_name = !empty($param['tache_name']) ? $param['tache_name'] : '';
		$data = $asset_model->TacheDel_ByShotId($shot_id, $tache_name);
		return resultArray(['data' => $data]);
	}

	//镜头 - 删除工作室(所属镜头下的工作室)
	public function delete_studio()
	{
		$asset_model = model('asset');
		$param = $this->param;
		$shot_id = !empty($param['id']) ? $param['id'] : '';
		$studio_id = !empty($param['studio_id']) ? $param['studio_id'] : '';
		$tache_name = !empty($param['tache_name']) ? $param['tache_name'] : '';
		$data = $asset_model->StudioDel_ByShotId($shot_id, $tache_name, $studio_id);
		return resultArray(['data' => $data]);
	}

	//添加场号
	public function save_field()
	{
		$asset_model = model('asset');
		$param = $this->param;
		$data = $asset_model->field_add($param);
		return resultArray(['data' => $data]);
	}

	//筛选 - 获取场号
	public function get_field(){
		$asset_model = model('asset');
		$param = $this->param;
		$data = $asset_model->get_field_data($param);
		return resultArray(['data'=>$data]);
	}

	//筛选 - 获取镜头号
	public function get_number(){
		$asset_model = model('asset');
		$param = $this->param;
		$data = $asset_model->get_shot_number($param);
		return resultArray(['data'=>$data]);
	}

	//接口 - 检测所属项目、所属场号下的镜头编号是否重复
	public function check_shot_number(){
		$asset_model = model('asset');
		$param = $this->param;
		$data = $asset_model->check_shot_num($param);
		if(!$data){
			return resultArray(['error'=>'镜头编号已重复']);
		}
		return resultArray(['data'=>$data]);
	}

}