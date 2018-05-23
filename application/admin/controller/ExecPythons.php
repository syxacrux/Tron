<?php
// +----------------------------------------------------------------------
// | Description: 测试 对外接口 无验证权限
// +----------------------------------------------------------------------
// | Author: zjs <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\Common;

class ExecPythons extends Common
{
	//测试
	public function get_test()
	{
		$model = model('Test');
		$param = $this->param;
		$data = $model->getTestList($param);
		return resultArray(['data' => $data]);
	}

	//更新任务 回调python dailies
	public function update_task_byDailies(){
		$callback_model = model('ExecPython');
		$param = $this->param;
		$data = $callback_model->callback_dailies($param);
		return resultArray(['data'=>$data]);
	}

}
