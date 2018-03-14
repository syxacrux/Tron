<?php
namespace app\admin\model;
use app\common\model\Common;

class Studio extends Common{

    protected $name = 'admin_studio';

    /**
     * 获取列表
     * @param $keyword
     * @param $page
     * @param $limit
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author zjs 2018/3/13
     */
    public function getDataList($keyword, $page, $limit){
        $where = [];
        if ($keyword) {
            $where['name'] = ['like', '%'.$keyword.'%'];
        }
        $dataCount = $this->where($where)->count('id');
        $list = $this->where($where);
        // 若有分页
        if ($page && $limit) {
            $list = $list->page($page, $limit);
        }
        $list = $list ->select();
        $data['list'] = $list;
        $data['dataCount'] = $dataCount;
        return $data;
    }

    /**
     * 新增数据
     * @param $param
     * @return bool
     * @author zjs 2018/3/13
     */
    public function addData($param){
        try{
            $param['studio_name'] = trimall($param['studio_name']);
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

    /**
     * 删除
     * @param $id
     * @return bool
     * @author zjs 2018/3/13
     */
    public function delStudio($id){
        //根据工作写给ID查询用户关联表内是否存在所属工作室的用户
        $userCount_byStudio = Access::where('studio_ids','like','%'.$id.'%')->count();
        if($userCount_byStudio != 0){
            $this->error = '请先编辑或删除所属成员的工作室';
            return false;
        }else{
            try{
                $this->where('id', $id)->delete();
                return true;
            }catch( \Exception $e){
                $this->error = '删除失败';
                return false;
            }
        }
    }

    /**
     * 多工作室ID字符串转换相对应的工作室名称，以逗号分割
     * @param $ids
     * @param $tag
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author zjs 2018/3/13
     */
    public function get_studio_names($ids,$tag){
        $studio_ids = explode(',',$ids);
        foreach($studio_ids as $key=>$value){
            $res[] = $this->field('studio_name')->where('id',$value)->find()->studio_name;
        }
        $studio_names = implode($tag,$res);
        return $studio_names;
    }
}