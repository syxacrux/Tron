<?php

namespace app\admin\model;

use think\Db;
use app\common\model\Common;

class Approval extends Common
{
	protected $name = 'approvals';

	public function getList($keywords, $page, $limit){
		$where = [];
		if(!empty($keywords['project_id'])){
			$where['project_id'] = $keywords['project_id'];
		}
	}

}
