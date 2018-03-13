<?php
namespace app\admin\controller;
use app\admin\model\Studio;
use app\common\controller\ApiCommon;

class Studios extends ApiCommon{
    //列表
    public function index(){
        $studio_model = new Studio();
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $studio_model->getDataList($keywords, $page, $limit);
        return resultArray(['data' => $data]);
    }

    //详情
    public function read(){
        $studio_model = model('Studio');
        $param = $this->param;
        $data = $studio_model->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $studio_model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    //新增
    public function save(){
        $studio_model = model('Studio');
        $param = $this->param;
        $data = $studio_model->addData($param);
        if (!$data) {
            return resultArray(['error' => $studio_model->getError()]);
        }
        return resultArray(['data' => '添加成功']);
    }

    //编辑
    public function update(){
        $studio_model = model('Studio');
        $param = $this->param;
        $data = $studio_model->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $studio_model->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }

    //删除
    public function delete(){
        $studio_model = model('Studio');
        $param = $this->param;

        $data = $studio_model->delStudio($param['id']);
        if (!$data) {
            return resultArray(['error' => $studio_model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

    //批量删除
    public function deletes(){
        $studio_model = model('Studio');
        $param = $this->param;
        $data = $studio_model->delDatas($param['ids'], true);
        if (!$data) {
            return resultArray(['error' => $studio_model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }
}