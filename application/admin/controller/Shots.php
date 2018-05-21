<?php
// +----------------------------------------------------------------------
// | Description: 镜头管理
// +----------------------------------------------------------------------
// | Author: Zjs <839804865@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\Access;
use app\common\controller\BaseCommon;

class Shots extends BaseCommon
{
	//概况
	public function survey(){
		$shot_model = model('Shot');
		$param = $this->param;
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'],true) : '';
		$data = $shot_model->survey_list($keywords);
		return resultArray(['data'=>$data]);
	}

	//标准列表
	public function index()
	{
		$shot_model = model('Shot');
		$param = $this->param;
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'],true) : '';
		$page = !empty($param['page']) ? $param['page'] : '';
		$limit = !empty($param['limit']) ? $param['limit'] : '';
		$data = $shot_model->getList($keywords, $page, $limit);
		return resultArray(['data' => $data]);
	}

	//接口 - 镜头看板 - 制作中
	public function in_production_data()
	{
		$shot_model = model('Shot');
		$param = $this->param;
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'],true) : '';
		$page = !empty($param['page']) ? $param['page'] : '';
		$limit = !empty($param['limit']) ? $param['limit'] : '';
		$data = $shot_model->getList_byStatus($keywords,$page, $limit, 5, 2, 1);
		return resultArray(['data' => $data]);
	}

	//接口 - 镜头看板 - 反馈中
	public function feedback_data()
	{
		$shot_model = model('Shot');
		$param = $this->param;
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'],true) : '';
		$page = !empty($param['page']) ? $param['page'] : '';
		$limit = !empty($param['limit']) ? $param['limit'] : '';
		$data = $shot_model->getList_byStatus($keywords,$page, $limit, 15, 2, 1);
		return resultArray(['data' => $data]);
	}

	//接口 - 镜头看板 - 等待资产
	public function waiting_assets_data()
	{
		$shot_model = model('Shot');
		$param = $this->param;
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'],true) : '';
		$page = !empty($param['page']) ? $param['page'] : '';
		$limit = !empty($param['limit']) ? $param['limit'] : '';
		$data = $shot_model->getList_byStatus($keywords,$page, $limit, 1, 1, 1);
		return resultArray(['data' => $data]);
	}

	//接口 - 镜头看板 - 等待制作
	public function waiting_shots_data(){
		$shot_model = mode('Shot');
		$param = $this->param;
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'],true) : '';
		$page = !empty($param['page']) ? $param['page'] : '';
		$limit = !empty($param['limit']) ? $param['limit'] : '';
		$data = $shot_model->getList_byStatus($keywords,$page,$limit,1,2,1);
		return resultArray(['data'=>$data]);
	}

	//接口 - 镜头看板 - 暂停
	public function pause_data()
	{
		$shot_model = model('Shot');
		$param = $this->param;
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'],true) : '';
		$page = !empty($param['page']) ? $param['page'] : '';
		$limit = !empty($param['limit']) ? $param['limit'] : '';
		$data = $shot_model->getList_byStatus($keywords,$page, $limit, '', 2, 2);
		return resultArray(['data' => $data]);
	}

	//接口 - 镜头看板 - 完成
	public function finish_data()
	{
		$shot_model = model('Shot');
		$param = $this->param;
		$keywords = !empty($param['keywords']) ? json_decode($param['keywords'],true) : '';
		$page = !empty($param['page']) ? $param['page'] : '';
		$limit = !empty($param['limit']) ? $param['limit'] : '';
		$data = $shot_model->getList_byStatus($keywords,$page, $limit, 25, 1, 1);
		return resultArray(['data' => $data]);
	}

	//详情
	public function read()
	{
		$shot_model = model('Shot');
		$param = $this->param;
		$data = $shot_model->getData_ById($param['id']);
		if (!$data) {
			return resultArray(['error' => $shot_model->getError()]);
		}
		return resultArray(['data' => $data]);
	}

	//新增
	public function save()
	{
		$shot_model = model('Shot');
		$uid = $this->uid;
		if($uid == 1){	//超级管理员创建任务，则角色传0
			$group_id = 0;
		}else{
			$group_id = Access::where('user_id', $uid)->value('group_id'); //所属角色
		}
		$param = $this->param;
		$data = $shot_model->addData($param,$group_id);
		if (!$data) {
			return resultArray(['error' => $shot_model->getError()]);
		}
		return resultArray(['data' => '添加成功']);
	}

	//编辑
	public function update()
	{
		$shot_model = model('Shot');
		$param = $this->param;
		$data = $shot_model->updateData_ById($param, $param['id']);
		if (!$data) {
			return resultArray(['error' => $shot_model->getError()]);
		}
		return resultArray(['data' => '编辑成功']);
	}

	//删除
	public function delete()
	{
		$shot_model = model('Shot');
		$param = $this->param;
		$data = $shot_model->delData_ById($param['id'], true);//删除镜头还要删除目录 未做
		if (!$data) {
			return resultArray(['error' => $shot_model->getError()]);
		}
		return resultArray(['data' => '删除成功']);
	}

	public function deletes()
	{
		$shot_model = model('Shot');
		$param = $this->param;
		$data = $shot_model->delDatas($param['ids'], true);
		if (!$data) {
			return resultArray(['error' => $shot_model->getError()]);
		}
		return resultArray(['data' => '删除成功']);
	}

	public function enables()
	{
		$shot_model = model('Shot');
		$param = $this->param;
		$data = $shot_model->enableDatas($param['ids'], $param['status'], true);
		if (!$data) {
			return resultArray(['error' => $shot_model->getError()]);
		}
		return resultArray(['data' => '操作成功']);
	}

	//接口 - 根据镜头ID及环节获取工作室列表
	public function get_studio_list()
	{
		$shot_model = model('Shot');
		$param = $this->param;
		$data = $shot_model->getStudio_byShot($param);
		return resultArray(['data' => $data]);
	}

	//镜头 - 删除环节(所属镜头下的环节)
	public function delete_tache()
	{
		$shot_model = model('Shot');
		$param = $this->param;
		$shot_id = !empty($param['id']) ? $param['id'] : '';
		$tache_name = !empty($param['tache_name']) ? $param['tache_name'] : '';
		$data = $shot_model->TacheDel_ByShotId($shot_id, $tache_name);
		return resultArray(['data' => $data]);
	}

	//镜头 - 删除工作室(所属镜头下的工作室)
	public function delete_studio()
	{
		$shot_model = model('Shot');
		$param = $this->param;
		$shot_id = !empty($param['id']) ? $param['id'] : '';
		$studio_id = !empty($param['studio_id']) ? $param['studio_id'] : '';
		$tache_name = !empty($param['tache_name']) ? $param['tache_name'] : '';
		$data = $shot_model->StudioDel_ByShotId($shot_id, $tache_name, $studio_id);
		return resultArray(['data' => $data]);
	}

	//添加场号
	public function save_field()
	{
		$shot_model = model('Shot');
		$param = $this->param;
		$data = $shot_model->field_add($param);
		return resultArray(['data' => $data]);
	}


	//筛选 - 获取镜头号
	public function get_number(){
		$shot_model = model('Shot');
		$param = $this->param;
		$data = $shot_model->get_shot_number($param);
		return resultArray(['data'=>$data]);
	}

	//接口 - 检测所属项目、所属场号下的镜头编号是否重复
	public function check_shot_number(){
		$shot_model = model('Shot');
		$param = $this->param;
		$data = $shot_model->check_shot_num($param);
		if(!$data){
		    return resultArray(['error'=>'镜头编号已重复']);
        }
		return resultArray(['data'=>$data]);
	}

	//获取模版
	public function import(){
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: POST');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header("content-type:text/html;charset=utf-8");
		//上传excel文件
		$file = request()->file('excel_file');
		//移到/public/uploads/excel/下
		$info = $file->move(ROOT_PATH.'public'.DS.'uploads'.DS.'excel');
		//上传文件成功
		if ($info) {
			//引入PHPExcel类
			vendor('PHPExcel.PHPExcel.Reader.Excel5');
			//获取上传后的文件名
			$fileName = $info->getSaveName();
			//文件路径
			$filePath = 'public/uploads/excel/'.$fileName;
			//实例化PHPExcel类
			$PHPReader = new \PHPExcel_Reader_Excel5();
			//读取excel文件
			$objPHPExcel = $PHPReader->load($filePath);
			//读取excel文件中的第一个工作表
			$sheet = $objPHPExcel->getSheet(0);
			$allRow = $sheet->getHighestRow();  //取得总行数
			//$allColumn = $sheet->getHighestColumn();  //取得总列数
			//从第二行开始插入，第一行是列名
			for ($j=2; $j <= $allRow; $j++) {
				$data['name'] = $objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue();
				$data['tel'] = $objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue();
				$data['addr'] = $objPHPExcel->getActiveSheet()->getCell("C".$j)->getValue();
				$last_id = Db::table('users')->insertGetId($data);//保存数据，并返回主键id
				if ($last_id) {
					echo "第".$j."行导入成功，users表第:".$last_id."条！<br/>";
				}else{
					echo "第".$j."行导入失败！<br/>";
				}
			}
		}else{
			echo "上传文件失败！";
		}
	}


}
