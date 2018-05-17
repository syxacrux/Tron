<?php
// +----------------------------------------------------------------------
// | Description: 接收 python参数 对外接口 无验证权限
// +----------------------------------------------------------------------
// | Author: zjs <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\Common;

class Pythons extends Common
{
	//更新审批表文件名称
	public function update_file_name()
	{
		$model = model('Approval');//审批表
		$param = $this->param;
		$data = $model->updateData_ById($param);
		return resultArray(['data' => $data]);
	}

}
