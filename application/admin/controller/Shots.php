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
        $shot_model = model('Shot');
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $shot_model->getList($keywords, $page, $limit);
        return resultArray(['data' => $data]);
    }

    public function index_detail(){
        $shot_model = model('Shot');
        $uid = $this->uid;
        $group_id = Access::get($uid)->group_id; //所属角色
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $shot_model->getList($keywords, $page, $limit,$uid,$group_id);
        return resultArray(['data' => $data]);
    }

    public function read(){
        $shot_model = model('Shot');
        $param = $this->param;
        $data = $shot_model->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $shot_model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    public function save(){
        $shot_model = model('Shot');
        $param = $this->param;
        $data = $shot_model->addData($param);
        if (!$data) {
            return resultArray(['error' => $shot_model->getError()]);
        }
        return resultArray(['data' => '添加成功']);
    }

    public function update(){
        $shot_model = model('Shot');
        $param = $this->param;
        $data = $shot_model->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $shot_model->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }

    public function delete(){
        $shot_model = model('Shot');
        $param = $this->param;
        $data = $shot_model->delDataById($param['id'], true);
        if (!$data) {
            return resultArray(['error' => $shot_model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

    public function deletes(){
        $shot_model = model('Shot');
        $param = $this->param;
        $data = $shot_model->delDatas($param['ids'], true);
        if (!$data) {
            return resultArray(['error' => $shot_model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

    public function enables(){
        $shot_model = model('Shot');
        $param = $this->param;
        $data = $shot_model->enableDatas($param['ids'], $param['status'], true);
        if (!$data) {
            return resultArray(['error' => $shot_model->getError()]);
        }
        return resultArray(['data' => '操作成功']);
    }
}
