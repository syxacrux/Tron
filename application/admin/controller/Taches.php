<?php
namespace app\admin\controller;
use app\common\controller\BaseCommon;
use app\admin\model\Tache;

class Taches extends BaseCommon{
    //首页
    public function index(){
        $tache_model = new Tache();
        $uid = $this->uid;
        $param = $this->param;
        $keywords = !empty($param['keywords']) ? $param['keywords']: '';
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $tache_model->getDataList($keywords, $page, $limit,$uid);
        return resultArray(['data' => $data]);
    }

    public function read(){
        $tache_model = new Tache();
        $param = $this->param;
        $data = $tache_model->getDataById($param['id']);
        if (!$data) {
            return resultArray(['error' => $tache_model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    //新增
    public function save(){
        $tache_model = new Tache();
        $param = $this->param;
        $data = $tache_model->addData($param);
        if (!$data) {
            return resultArray(['error' => $tache_model->getError()]);
        }
        return resultArray(['data' => '添加成功']);
    }

    //编辑
    public function update(){
        $tache_model = new Tache();
        $param = $this->param;
        $data = $tache_model->updateDataById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $tache_model->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }

    //删除
    public function delete(){
        $tache_model = new Tache();
        $param = $this->param;
        $data = $tache_model->delTache($param['id']);
        if (!$data) {
            return resultArray(['error' => $tache_model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }

    //批量删除
    public function deletes(){
        $tache_model = new Tache();
        $param = $this->param;
        $data = $tache_model->delTaches($param['ids']);
        if (!$data) {
            return resultArray(['error' => $tache_model->getError()]);
        }
        return resultArray(['data' => '删除成功']);
    }
}