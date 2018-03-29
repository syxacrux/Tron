<?php
// +----------------------------------------------------------------------
// | Description: 基础类，无需验证权限。
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\Access;
use com\verify\HonrayVerify;
use app\common\controller\Common;
use think\Db;
use think\Request;

class Base extends Common
{
    public function login()
    {   
        $userModel = model('User');
        $param = $this->param;
        $username = $param['username'];
        $password = $param['password'];
        $verifyCode = !empty($param['verifyCode'])? $param['verifyCode']: '';
        $isRemember = !empty($param['isRemember'])? $param['isRemember']: '';
        $data = $userModel->login($username, $password, $verifyCode, $isRemember);
        if (!$data) {
            return resultArray(['error' => $userModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    public function relogin()
    {   
        $userModel = model('User');
        $param = $this->param;
        $data = decrypt($param['rememberKey']);
        $username = $data['username'];
        $password = $data['password'];

        $data = $userModel->login($username, $password, '', true, true);
        if (!$data) {
            return resultArray(['error' => $userModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }    

    public function logout()
    {
        $param = $this->param;
        cache('Auth_'.$param['authkey'], null);
        return resultArray(['data'=>'退出成功']);
    }

    public function getConfigs()
    {
        $systemConfig = cache('DB_CONFIG_DATA'); 
        if (!$systemConfig) {
            //获取所有系统配置
            $systemConfig = model('admin/SystemConfig')->getDataList();
            cache('DB_CONFIG_DATA', null);
            cache('DB_CONFIG_DATA', $systemConfig, 36000); //缓存配置
        }
        return resultArray(['data' => $systemConfig]);
    }

    public function getVerify()
    {
        $captcha = new HonrayVerify(config('captcha'));
        return $captcha->entry();
    }

    public function setInfo(){
        $userModel = model('User');
        $param = $this->param;
        $old_pwd = $param['old_pwd'];
        $new_pwd = $param['new_pwd'];
        $auth_key = $param['auth_key'];
        $data = $userModel->setInfo($auth_key, $old_pwd, $new_pwd);
        if (!$data) {
            return resultArray(['error' => $userModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    /**
     * 根据当前登陆人获取模块内容的一行数据是否存在这个人
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @return array 200/400
     * @author zjs 2018/3/16
     */
    public function getAuth_byUid(){
        $param = $this->param;
        $table = $param['table'];//数据表名
        $uid = $param['uid']; //当前登陆人
        if($uid == 1){  //过滤admin
            return ['code'=>200,'error'=>''];
        }else{
            $group_id = Access::where('user_id',$uid)->where()->value('group_id');
            if(!$group_id){
                return ['code' => '400','error'=>'没有当前用户'];
            }elseif($group_id == 1 || $group_id == 2 || $group_id==3){
                return ['code'=>200,'error'=>''];
            }
            $table_id = $param['table_id'];//查询数据表名主键
            switch ($table) {
                case 'project':
                    $table_model = model('Project')->where('id',$table_id)->where('scene_producer|scene_director|second_company_producer|inside_coordinate','like','%'.$uid.'%');
                    break;
            }
            $check_uid = $table_model->find();
            if(!empty($check_uid)){
                return ['code'  => 200,'error'=>''];
            }else{
                return ['code'  => 400,'error'=>'没有权限'];
            }
        }

    }

    /**
     * 根据项目ID获取场/集数据
     * @author zjs 2018/3/21
     */
    public function getField_ByPid(){
        $param = $this->param;
        $data = Db::name('field')->where('project_id',$param['project_id'])->select();
        if (!$data) {
            return ['code'  => 200,'data'=>[]];
        }
        return ['code'=>200,'data' => $data];
    }

    /**
     * 保存场号
     * @return array
     */
    public function save_field(){
        $param = $this->param;
        $where = [];
        $where['project_id'] = $param['project_id'];
        $where['name'] = $param['name'];
        $check_name = Db::name('field')->where($where)->find();
        if(!empty($check_name) || !is_null($check_name)){
            return ['code'=>400,'error'=>'所属项目下场号重复'];
        }else{
            Db::name('field')->insert($param);
            return ['code'=>200,'error'=>''];
        }
    }

    // miss 路由：处理没有匹配到的路由规则
    public function miss(){
        if (Request::instance()->isOptions()) {
            return ;
        } else {
            echo 'vuethink接口';
        }
    }
}
 