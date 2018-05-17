<?php
namespace app\admin\model;

use app\common\model\Common;
use redis\RedisPackage;

class ExecPython extends Common{

	//根据任务类型 1镜头 2资产 分配制作人 执行python创建目录
	public function allot_artist($type,$task_id){
		$task_obj = Workbench::get($task_id);
		$task_byname = $task_obj->task_byname;	//所属任务简称 guangxian
		$tache_name = Tache::get($task_obj->tache_id)->tache_name;	//所属环节 prd
		$project_byname = Project::get($task_obj->project_id)->project_byname;	// 项目简称 FUY
		$field_num = Field::get($task_obj->field_id)->name;	//场号 006
		$shot_num = Shot::get($task_obj->shot_id)->shot_number; //镜头号 001
		$user_name = User::get($task_obj->user_id)->username;	//分配的制作人 用户名 zhaojs

		if($type == 1){	//镜头
			//环节
			switch ($tache_name){
				case 'cmp':
					$file_name = strtolower($project_byname).$field_num.$shot_num.'_'.$tache_name.'_'.$user_name.'_'.'final_v0101';
					$param['windows_path'] =  "\\$project_byname\\$field_num\\$shot_num\\Stuff\\$tache_name\\publish\\$file_name";
					$param['mac_path'] = "/$project_byname/$field_num/$shot_num/Stuff/$tache_name/publish/$file_name";
					$param['linux_path'] = "/$project_byname/$field_num/$shot_num/Stuff/$tache_name/publish/$file_name";
					$str="'ShotTask' '{$project_byname}' '{$field_num}' '{$shot_num}' '{$tache_name}' '{$user_name}'";
					break;
				case 'mmv':
					$file_name = strtolower($project_byname).$field_num.$shot_num.'_'.$tache_name.'_'.$user_name.'_'.'matchmove';
					$param['windowspath'] =  "\\$project_byname\\$field_num\\$shot_num\\Stuff\\$tache_name\\publish\\$file_name";
					$param['macospath'] = "/$project_byname/$field_num/$shot_num/Stuff/$tache_name/publish/$file_name";
					$param['linuxpath'] = "/$project_byname/$field_num/$shot_num/Stuff/$tache_name/publish/$file_name";
					$str="'ShotTask' '{$project_byname}' '{$field_num}' '{$shot_num}' '{$tache_name}' '{$user_name}'";
					break;
				case 'art':
					$file_name = strtolower($project_byname).'_'.$tache_name.'_'.$user_name.'_'.$task_byname.'_master';
					$param['windowspath'] =  "\\$project_byname\\Stuff\\$tache_name\\publish\\$file_name";
					$param['macospath'] = "/$project_byname/Stuff/$tache_name/publish/$file_name";
					$param['linuxpath'] = "/$project_byname/Stuff/$tache_name/publish/$file_name";
					$str="'ShotTask' '{$project_byname}' '{$tache_name}' '{$user_name}' ";
					break;
				default :
					$file_name = strtolower($project_byname).$field_num.$shot_num.'_'.$tache_name.'_'.$user_name.'_'.$task_byname.'_master';
					$param['windowspath'] =  "\\$project_byname\\$field_num\\$shot_num\\Stuff\\$tache_name\\publish\\$file_name";
					$param['macospath'] = "/$project_byname/$field_num/$shot_num/Stuff/$tache_name/publish/$file_name";
					$param['linuxpath'] = "/$project_byname/$field_num/$shot_num/Stuff/$tache_name/publish/$file_name";
					$str="'ShotTask' '{$project_byname}' '{$field_num}' '{$shot_num}' '{$tache_name}' '{$user_name}'";
					break;
			}
		}else{	//资产
			switch ($tache_name){	//环节
				case 'cmp':
					$file_name = strtolower($project_byname).'_'.$tache_name.'_'.$user_name.'_'.'final_v0101';
					$param['windowspath'] =  "\\$project_byname\\Stuff\\$tache_name\\publish\\$file_name";
					$param['macospath'] = "/$project_byname/Stuff/$tache_name/publish/$file_name";
					$param['linuxpath'] = "/$project_byname/Stuff/$tache_name/publish/$file_name";
					$str="'AssetTask' '{$project_byname}' '{$tache_name}' '{$user_name}' ";
					break;
				case 'mmv':
					$file_name = strtolower($project_byname).'_'.$tache_name.'_'.$user_name.'_'.'matchmove';
					$param['windowspath'] =  "\\$project_byname\\Stuff\\$tache_name\\publish\\$file_name";
					$param['macospath'] = "/$project_byname/Stuff/$tache_name/publish/$file_name";
					$param['linuxpath'] = "/$project_byname/Stuff/$tache_name/publish/$file_name";
					$str="'AssetTask' '{$project_byname}' '{$tache_name}' '{$user_name}' ";
					break;
				case 'art':
					$file_name = strtolower($project_byname).'_'.$tache_name.'_'.$user_name.'_'.$task_byname.'_master';
					$param['windowspath'] =  "\\$project_byname\\Stuff\\$tache_name\\publish\\$file_name";
					$param['macospath'] = "/$project_byname/Stuff/$tache_name/publish/$file_name";
					$param['linuxpath'] = "/$project_byname/Stuff/$tache_name/publish/$file_name";
					$str="'AssetTask' '{$project_byname}' '{$tache_name}' '{$user_name}' ";
					break;
				default :
					$file_name = strtolower($project_byname).'_'.$tache_name.'_'.$user_name.'_'.$task_byname.'_master';
					$param['windowspath'] =  "\\$project_byname\\Stuff\\$tache_name\\publish\\$file_name";
					$param['macospath'] = "/$project_byname/Stuff/$tache_name/publish/$file_name";
					$param['linuxpath'] = "/$project_byname/Stuff/$tache_name/publish/$file_name";
					$str="'AssetTask' '{$project_byname}' '{$tache_name}' '{$user_name}' ";
					break;

			}
		}
		//更新当前任务的三套系统路径
		try{
			$task_model = new Workbench();
			$task_model->save($param,['id'=>$task_id]);
			$cmd = $str." '".$file_name."'";
			//执行外部程序-开启队列
			/*
			$redis = new RedisPackage();
			$cmd = "python /usr/local/httpd/htdocs/tron/tronPipelineScript/createDirPath/parser.py $str ";
			$redis::LPush("pyFile",$cmd);
			*/
			return true;
		}catch(\Exception $e){
			$this->error = '分配制作人失败';
			return false;
		}

	}
}