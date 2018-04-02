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

    //接口 - 镜头看板 - 制作中
    public function in_production_data(){
        $shot_model = model('Shot');
        $param = $this->param;
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $shot_model->getList_byStatus($page,$limit,5,2,1);
        return resultArray(['data' => $data]);
    }

    //接口 - 镜头看板 - 反馈中
    public function feedback_data(){
        $shot_model = model('Shot');
        $param = $this->param;
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $shot_model->getList_byStatus($page,$limit,15,2,1);
        return resultArray(['data' => $data]);
    }

    //接口 - 镜头看板 - 等待资产
    public function waiting_assets_data(){
        $shot_model = model('Shot');
        $param = $this->param;
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $shot_model->getList_byStatus($page,$limit,1,1,1);
        return resultArray(['data' => $data]);
    }

    //接口 - 镜头看板 - 暂停
    public function pause_data(){
        $shot_model = model('Shot');
        $param = $this->param;
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $shot_model->getList_byStatus($page,$limit,5,2,2);
        return resultArray(['data' => $data]);
    }

    //接口 - 镜头看板 - 完成
    public function finish_data(){
        $shot_model = model('Shot');
        $param = $this->param;
        $page = !empty($param['page']) ? $param['page']: '';
        $limit = !empty($param['limit']) ? $param['limit']: '';
        $data = $shot_model->getList_byStatus($page,$limit,25,1,1);
        return resultArray(['data' => $data]);
    }

    public function read(){
        $shot_model = model('Shot');
        $param = $this->param;
        $data = $shot_model->getData_ById($param['id']);
        if (!$data) {
            return resultArray(['error' => $shot_model->getError()]);
        }
        return resultArray(['data' => $data]);
    }

    public function save(){
        $shot_model = model('Shot');
        $uid = $this->uid;
        $group_id = Access::get($uid)->group_id; //所属角色
        $param = $this->param;
        $data = $shot_model->addData($param,$uid,$group_id);
        if (!$data) {
            return resultArray(['error' => $shot_model->getError()]);
        }
        return resultArray(['data' => '添加成功']);
    }

    //编辑
    public function update(){
        $shot_model = model('Shot');
        $param = $this->param;
        $data = $shot_model->updateData_ById($param, $param['id']);
        if (!$data) {
            return resultArray(['error' => $shot_model->getError()]);
        }
        return resultArray(['data' => '编辑成功']);
    }

    //删除
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

    //接口 - 根据镜头ID及环节获取工作室列表
    public function get_studio_list(){
        $shot_model = model('Shot');
        $param = $this->param;
        $data = $shot_model->getStudio_byShot($param);
        return resultArray(['data' => $data]);
    }

}
