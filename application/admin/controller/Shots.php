<?php
// +----------------------------------------------------------------------
// | Description: 组织架构
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\model\Access;
use app\common\controller\BaseCommon;

class Shots extends BaseCommon{

    public function index(){
        $studio_model = model('Shot');
        $uid = $this->uid;
        $group_id = Access::get($uid)->group_id; //所属角色
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $studio_model->getList($keywords, $page, $limit,$uid,$group_id);
        return resultArray(['data' => $data]);
    }

    public function index_detail(){
        $studio_model = model('Shot');
        $uid = $this->uid;
        $group_id = Access::get($uid)->group_id; //所属角色
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $studio_model->getList($keywords, $page, $limit,$uid,$group_id);
        return resultArray(['data' => $data]);
    }

    public function read(){
        $studio_model = model('Shot');
        $param = $this->param;
        $data = $studio_model->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $studio_model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    public function save(){
        $studio_model = model('Shot');
        $param = $this->param;
        $data = $studio_model->createData($param);
        if (!$data) {
            return resultArray(['error' => $studio_model->getError()]);
        }
        return resultArray(['data' => '添加成功']);
    }

    public function update(){
        $studio_model = model('Shot');
        $param = $this->param;
        $data = $studio_model->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $studio_model->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }

    public function delete(){
        $studio_model = model('Shot');
        $param = $this->param;
        $data = $studio_model->delDataById($param['id'], true);
        if (!$data) {
            return resultArray(['error' => $studio_model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

    public function deletes(){
        $studio_model = model('Shot');
        $param = $this->param;
        $data = $studio_model->delDatas($param['ids'], true);
        if (!$data) {
            return resultArray(['error' => $studio_model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

    public function enables(){
        $studio_model = model('Shot');
        $param = $this->param;
        $data = $studio_model->enableDatas($param['ids'], $param['status'], true);
        if (!$data) {
            return resultArray(['error' => $studio_model->getError()]);
        }
        return resultArray(['data' => '操作成功']);
    }
}
