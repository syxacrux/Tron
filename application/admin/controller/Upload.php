<?php
// +----------------------------------------------------------------------
// | Description: 图片上传
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use think\Controller;

class Upload extends Controller
{
	public function index()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: POST');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		$file = request()->file('file');
		if (!$file) {
			return resultArray(['error' => '请上传文件']);
		}

		$info = $file->validate(['ext' => 'jpg,png,gif'])->move(ROOT_PATH . DS . 'uploads');
		if ($info) {
			return resultArray(['data' => 'uploads' . DS . $info->getSaveName()]);
		}

		return resultArray(['error' => $file->getError()]);
	}

	/**
	 * 缩略图上传图片
	 * 移动到框架应用根目录/uploads/Projects/images 目录下
	 * 用于模块 项目、镜头缩略图上传
	 * @return array
	 * @author zjs 2018/3/22
	 */
	public function images_add()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: POST');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		$file = request()->file('file');
		if (!$file) {
			return resultArray(['error' => '请上传文件']);
		}
		$images_dir = ROOT_PATH . DS . 'uploads' . DS . 'Projects' . DS . 'images';
		$host_image_path = 'uploads' . DS . 'Projects' . DS . 'images';
		//移动文件
		$info = $file->validate(['ext' => 'jpg,png,gif'])->move($images_dir);
		if ($info) {
			return resultArray(['data' => $host_image_path . DS . $info->getSaveName()]);
		}
		return resultArray(['error' => $file->getError()]);
	}

	public function images_base64_add(){
		$image_str = request()->post('images');
		$imageName = time().'.png';
		if(strstr($image_str,',')){
			$image = explode(',',$image_str);
			$image = $image[1];
		}
		$path = ROOT_PATH.DS.'uploads'.DS.'Projects'.DS.'images'.DS.date('Ymd',time());
		if(!is_dir($path)){
			mkdir($path,0777,true);
		}
		$imageSrc = $path.DS.$imageName;	//图片名字
		$r = file_put_contents($imageSrc,base64_decode($image));
		if(!$r){
			return resultArray(['error'=>'图片生成失败']);
		}else{
			return resultArray(['data'=>1,'msg'=>'图片生成成功']);
		}

	}

	/**
	 * excel文件上传
	 * 移动到所属项目根目录 /uploads/Projects/excels/import 目录下
	 * @return array
	 * @author zjs 2018/04/25
	 */
	public function excels_add()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: POST');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		$file = request()->file('file');
		if (!$file) {
			return resultArray(['error' => '请上传Excel文件']);
		}
		$excels_dir = ROOT_PATH . DS . 'uploads' . DS . 'Projects' . DS . 'excels' . DS . 'import';
		$host_excels_path = 'uploads' . DS . 'Projects' . DS . 'excels' . DS . 'import';
		//移动文件
		$info = $file->validate(['ext' => 'xls,xlsx,xltx'])->move($excels_dir);
		if ($info) {
			return resultArray(['data' => $host_excels_path . DS . $info->getSaveName()]);
		}
		return resultArray(['error' => $file->getError()]);
	}


}
 