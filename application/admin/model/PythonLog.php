<?php

namespace app\admin\model;
use app\common\model\Common;

class PythonLog extends Common{

	public function getTestList($param){
		$data['id'] = $param['id'];
		$data['count'] = $param['count'];
		return $data;
	}

}