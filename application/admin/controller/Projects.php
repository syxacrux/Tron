<?php
namespace app\admin\controller;
use app\admin\model\Project;
use app\common\controller\ApiCommon;

class Projects extends ApiCommon{

    //首页
    public function index(){
        $project_model = new Project();
        $uid = $this->uid;
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $data = $project_model->getDataList($keywords,$uid);
        return resultArray(['data' => $data]);
    }

    //详情
    public function read(){
        $project_model = new Project();
        $param = $this->param;
        $data = $project_model->getData_ById($param['id']);
        if (!$data) {
            return resultArray(['error' => $project_model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    //新增
    public function save(){
        $project_model = new Project();
        $param = $this->param;
        $param['uid'] = $this->uid;
        $data = $project_model->addData($param);
        if (!$data) {
            return resultArray(['error' => $project_model->getError()]);
        }
        return resultArray(['data' => '添加成功']);
    }

    //编辑
    public function update(){
        $project_model = new Project();
        $param = $this->param;
        $data = $project_model->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $project_model->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }

    //删除
    public function delete(){
        $project_model = new Project();
        $param = $this->param;
        $data = $project_model->delProject($param['id']);
        if (!$data) {
            return resultArray(['error' => $project_model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

}