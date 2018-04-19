<?php
// +----------------------------------------------------------------------
// | Description: 业务类型管理 场号/集号/资产类型
// +----------------------------------------------------------------------
// | Author: Zjs <839804865@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\Access;
use app\common\controller\BaseCommon;

class Fields extends BaseCommon{

	//根据类型保存数据  1镜头 2资产
	public function save_field(){
		$field_model = model('Field');
		$param = $this->param;
		$data = $field_model->save_field_data($param);
		if(!$data){
			return resultArray(['error'=>$field_model->getError()]);
		}
		return resultArray(['data'=>$data]);
	}

}