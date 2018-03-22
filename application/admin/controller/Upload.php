<?php
// +----------------------------------------------------------------------
// | Description: 图片上传
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;
use think\Request;
use think\Controller;

class Upload extends Controller{
    public function index(){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        $file = request()->file('file');
        if (!$file) {
        	return resultArray(['error' => '请上传文件']);
        }
        
        $info = $file->validate(['ext'=>'jpg,png,gif'])->move(ROOT_PATH . DS . 'uploads');
        if ($info) {
            return resultArray(['data' =>  'uploads'. DS .$info->getSaveName()]);
        }
        
        return resultArray(['error' =>  $file->getError()]);
    }

    /**
     * 缩略图上传图片
     * 移动到框架应用根目录/uploads/Projects/images 目录下
     * 用于模块 项目、镜头缩略图上传
     * @return array
     * @author zjs 2018/3/22
     */
    public function images_add(){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        $file = request()->file('file');
        if (!$file) {
            return resultArray(['error' => '请上传文件']);
        }
        $images_dir = ROOT_PATH.DS.'uploads'.DS.'Projects'.DS.'images';
        $host_image_path = 'uploads'. DS .'Projects'.DS.'images';
        //移动文件
        $info = $file->validate(['ext'=>'jpg,png,gif'])->move($images_dir);
        if ($info) {
            return resultArray(['data' =>$host_image_path.DS.$info->getSaveName()]);
        }
        return resultArray(['error'=>$file->getError()]);
    }

}
 