<?php
namespace app\admin\model;
use app\common\model\Common;

class Parameter extends Common{

	protected $name = 'system_parameter';

	/**
	 * 获取列表
	 * @param $keywords
	 * @param $page
	 * @param $limit
	 * @return mixed
	 * @throws \think\db\exception\DataNotFoundException
	 * @throws \think\db\exception\ModelNotFoundException
	 * @throws \think\exception\DbException
	 * @author zjs 2018/3/16
	 */
	public function getList($keywords, $page, $limit){
		$where = [];
		//配置项名称
		if (!empty($keywords['name'])) {
			$where['name'] = ['like', '%'.$keywords['name'].'%'];
		}
		//所属父级
		if(!empty($keywords['pid'])){
			$where['pid'] = $keywords['pid'];
		}
		$dataCount = $this->where($where)->count('id');
		$list = $this->where($where);
		// 若有分页
		if ($page && $limit) {
			$list = $list->page($page, $limit);
		}
		$list = $list ->select();
		foreach($list as $key=>$value){
			$list[$key]['pname'] = ($value['pid'] != 0 ) ? $this->get($value['pid'])->category : '顶级分类';
		}
		$data['list'] = $list;
		$data['dataCount'] = $dataCount;
		return $data;
	}

	//详情
	public function getData_ById($id){
		$parameter_obj = $this->get($id);
		if(!$parameter_obj){
			$this->error = '暂无此数据';
			return false;
		}
		$parameter_obj->pname = ($parameter_obj->pid == 0) ? '无' : $this->get($parameter_obj->pid)->category;
		return $parameter_obj;
	}

}