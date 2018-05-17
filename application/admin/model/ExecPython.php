<?php
namespace app\admin\model;

use app\common\model\Common;

class ExecPython extends Common{

	//根据任务类型 1镜头 2资产 分配制作人 执行python创建目录
	public function allot_artist($type,$task_id){
		$task_obj = Workbench::get($task_id);
		$tache_name = Tache::get($task_obj->tache_id)->tache_name;	//所属环节 prd
		$project_byname = Project::get($task_obj->project_id)->project_byname;	// 项目简称 FUY
		$field_num = Field::get($task_obj->field_id)->name;	//场号 006
		$shot_num = Shot::get($task_obj->shot_id)->shot_number; //镜头号 001
		$user_name = User::get($task_obj->user_id)->username;	//分配的制作人 用户名 zhaojs

		if($type == 1){	//镜头
			//环节
			switch ($tache_name){
				case 'prd':
					$param['windows_path'] = "\\$project_byname\\$field_num\\$shot_num\\Stuff\\$tache_name\\plates\\";
					$param['mac_path'] = "/$project_byname/$field_num/$shot_num/Stuff/$tache_name/plates/";
					$param['linux_path'] = "/$project_byname/$field_num/$shot_num/Stuff/$tache_name/plates/";
					$str="'ShotTask' '{$project_byname}' '{$field_num}' '{$shot_num}' '{$tache_name}' '{$user_name}'";
					break;
				case 'cmp':
					$file_name = strtolower($project_byname).$field_num.$shot_num.'_'.$tache_name.'_'.$user_name.'_'.'final_v0101';
					$data['windows_path'] =  "\\$project_byname\\$field_num\\$shot_num\\Stuff\\$tache_name\\publish\\$file_name";
					$data['mac_path'] = "/$project_byname/$field_num/$shot_num/Stuff/$ta/publish/$flname";
					$data['linux_path'] = "/$pjname/$fdname/$jingtou/Stuff/$arg/publish/$flname";
					$str="'ShotTask' '{$pjname}' '{$fdname}' '{$jingtou}' '{$arg}' '{$uname}'";
					break;
				case 'mmv':
					break;
				case 'art':
					break;
				default :
					break;
			}
		}else{	//资产

		}
		//更新当前任务的三套系统路径
		try{
			$task_model = new Workbench();
			$task_model->save($param,['id'=>$task_id]);
			return true;
		}catch(\Exception $e){
			$this->error = '分配制作人失败';
			return false;
		}

	}
}