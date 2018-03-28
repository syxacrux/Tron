<?php
// +----------------------------------------------------------------------
// | Description: 组织架构
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\Access;
use app\common\controller\ApiCommon;

class Workbenches extends ApiCommon{

    public function index(){
        $workbench_model = model('Workbench');
        $uid = $this->uid;
        $group_id = Access::get($uid)->group_id;
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $workbench_model->getList($keywords, $page, $limit,$uid,$group_id);
        return resultArray(['data' => $data]);
    }

    public function read(){
        $workbench_model = model('Workbench');
        $param = $this->param;
        $data = $workbench_model->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $workbench_model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    public function save(){
        $workbench_model = model('Workbench');
        $param = $this->param;
        $data = $workbench_model->addData($param);
        if (!$data) {
            return resultArray(['error' => $workbench_model->getError()]);
        }
        return resultArray(['data' => '添加成功']);
    }

    public function update(){
        $workbench_model = model('Workbench');
        $param = $this->param;
        $data = $workbench_model->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $workbench_model->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }

    public function delete(){
        $workbench_model = model('Workbench');
        $param = $this->param;
        $data = $workbench_model->delDataById($param['id'], true);
        if (!$data) {
            return resultArray(['error' => $workbench_model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

    public function deletes(){
        $workbench_model = model('Workbench');
        $param = $this->param;
        $data = $workbench_model->delDatas($param['ids'], true);
        if (!$data) {
            return resultArray(['error' => $workbench_model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

    public function enables(){
        $workbench_model = model('Workbench');
        $param = $this->param;
        $data = $workbench_model->enableDatas($param['ids'], $param['status'], true);
        if (!$data) {
            return resultArray(['error' => $workbench_model->getError()]);
        }
        return resultArray(['data' => '操作成功']);
    }
}
