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
            $res[] = $this->field('name')->where('id',$value)->find()->data['name'];
        }
        $studio_names = implode($tag,$res);
        return $studio_names;
    }
}