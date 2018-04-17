<?php
// +----------------------------------------------------------------------
// | Description: 参考库管理
// +----------------------------------------------------------------------
// | Author: Zjs <839804865@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\Access;
use app\common\controller\BaseCommon;

class References extends BaseCommon{

	//首页
	public function index(){
		$reference_model = model('reference');
		$uid = $this->uid;
		$group_id = Access::where('user_id', $uid)->value('group_id'); //所属角色
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'],true) : '';
		$page = !empty($param['page']) ? $param['page'] : '';
		$limit = !empty($param['limit']) ? $param['limit'] : '';
		$data = $reference_model->getList($keywords, $page, $limit,$uid,$group_id);
		return resultArray(['data' => $data]);
	}


}