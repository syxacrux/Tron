<?php
namespace app\admin\model;
use app\common\model\Common;

class Tache extends Common{
    protected $name = "admin_tache";

    public function getDataList($keyword, $page, $limit){
        $where = [];
        if ($keyword) {
            $where['tache_name|explain'] = ['like', '%'.$keyword.'%'];
        }
        $dataCount = $this->where($where)->count('id');
        $list = $this->where($where)->order('sort asc');
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
            $param['sort'] = $sort = intval($param['sort']);
            $param['create_time'] = time();
            $check_sort = $this->where('sort',$sort)->find();
            if(!empty($check_sort)){
                $this->error = '序号重复';
                return false;
            }else{
                $result = $this->validate($this->name)->save($param);
                if(false === $result){
                    $this->error = $this->getError();
                    return false;
                }else{
                    return true;
                }
            }
        }catch(\Exception $e){
            $this->error = '添加失败';
            return false;
        }
    }

    /**
     * 更新
     * @param $data
     * @param $id
     * @return bool
     * @throws \think\exception\DbException
     * @author zjs 2018/3/19
     */
    public function updateData($data,$id){
        $checkData = $this->get($id);
        if (!$checkData) {
            $this->error = '暂无此数据';
            return false;
        }
        // 验证
        $validate = validate($this->name);
        if (!$validate->check($data)) {
            $this->error = $validate->getError();
            return false;
        }
        try{
            $checkSort = $this->where('id','<>',$id)->where('sort',$data['sort'])->value('sort');
            if($checkSort == $data['sort']){
                $this->error = '该序号已存在，请重新赋予序号!';
                return false;
            }else{
                $this->allowField(true)->save($data, [$this->getPk() => $id]);
                return true;
            }
        }catch(\Exception $e){
            $this->error = '编辑失败';
            return false;
        }
    }

    /**
     * 删除
     * @param $id
     * @return bool
     * @author zjs 2018/3/15
     */
    public function delTache($id){
        try{
            $this->where('id', $id)->delete();
            return true;
        }catch( \Exception $e){
            $this->error = '删除失败';
            return false;
        }
    }

    /**
     * 批量删除
     * @param $ids
     * @return bool
     * @author zjs 2018/3/15
     */
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
     * @param $ids string
     * @param $value string 要获取的字段名
     * @param $tag string 分割符
     * @return string
     * @author zjs 2018/3/15
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