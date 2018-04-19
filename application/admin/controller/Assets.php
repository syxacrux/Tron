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

	//首页
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


}