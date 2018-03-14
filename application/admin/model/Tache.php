<?php
namespace app\admin\model;
use app\common\model\Common;

class Tache extends Common{
    protected $name = "admin_tache";

    public function getDataList($keyword, $page, $limit,$uid){
        $where = [];
        if ($keyword) {
            $where['tache_name'] = ['like', '%'.$keyword.'%'];
        }
        $dataCount = $this->where($where)->count('id');
        $list = $this->where($where);
        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }
        $list = $list ->select();
        foreach($list as $key=>$value){
            $list[$key]['create_time'] = date("Y-m-d H:i:s",$value['create_time']);
        }
        $data['list'] = $list;
        $data['dataCount'] = $dataCount;
        return $data;
    }

    public function addData($param){
        try{
            $param['tache_name'] = trimall($param['tache_name']);
            $param['explain'] = trimall($param['explain']);
            $param['create_time'] = time();
            $result =  $this->validate($this->name)->save($param);
            if(false === $result){
                $this->error = $this->getError();
                return false;
            }else{
                return true;
            }
        }catch(\Exception $e){
            $this->error = '添加失败';
            return false;
        }
    }

    public function delTache($id){
        try{
            $this->where('id', $id)->delete();
            return true;
        }catch( \Exception $e){
            $this->error = '删除失败';
            return false;
        }
    }

    public function delTaches($ids){
        //数组转字符串用,分割
        $ids_str = implode(',',$ids);
        try{
            $this->where('id','in',$ids_str)->delete();
            return true;
        }catch(\Exception $e){
            $this->error = '删除失败';
            return false;
        }
    }

    /**
     * 多环节ID字符串转换相对应的环节名称，以逗号分割
     * @param $ids  string 多环节ID
     * @param $tag  string 分割符号
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author zjs 2018/3/13
     */
    public static function get_tache_names($ids,$value,$tag){
        if(!empty($ids)){
            $tache_ids = explode(',',$ids);
            foreach($tache_ids as $key=>$val){
                $res[] = self::where('id',$val)->value($value);
            }
            $data = implode($tag,$res);
        }else{
            $data = '';
        }
        return $data;
    }
}