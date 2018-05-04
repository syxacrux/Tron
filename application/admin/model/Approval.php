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
		if(!empty($keywords['type'])){
			$where['resource_type'] = $keywords['type'];
		}
		if(!empty($keywords['field_id'])){	//场号 | 资产类型 查询相关类型的所有任务ID
			$task_ids_arr = Workbench::where('field_id',$keywords['field_id'])->column('id');
			$where['task_id'] = ['in',$task_ids_arr];
		}
		if(!empty($keywords['resource_name'])){	//镜头简称 | 资产名称  类型不能为空
			switch ($keywords['type']){
				case 1:	//镜头

					break;
				case 2:	//资产
					break;
			}
			//$task_ids_arr =
		}
	}

}
