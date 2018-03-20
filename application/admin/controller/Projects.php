<?php
namespace app\admin\controller;
use app\admin\model\Access;
use app\admin\model\Project;
use app\common\controller\ApiCommon;

class Projects extends ApiCommon{

    //首页
    public function index(){
        $project_model = new Project();
        $uid = $this->uid;
        $group_id = Access::get($uid)->group_id;
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? json_decode($param['keywords'],true): '';
        $data = $project_model->getList($keywords,$uid,$group_id);
        //$test = [0=>];
        $data['uid'] = $uid;
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

    //获取当前登陆人是否存在于所属项目ID的项目中
    public function get_uid_rule(){
        echo 123;
    }
}