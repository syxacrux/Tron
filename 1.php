<?php
/**
 * Created by PhpStorm.
 * User: zhaojiusi
 * Date: 2018/4/11
 * Time: 19:58
 */
date_default_timezone_set('PRC'); //设置中国时区

//$redis = new redis();
//$redis->connect('127.0.0.1', 6379);
//$redis->set('zjs',"webfsd");
//$result = $redis->get('site');
//var_dump($result);

//$redis = new redis();
//$redis->connect('127.0.0.1', 6379);
//while(true){
//	//sleep(1);
//	if($redis->ping() != '+PONG'){	//检测是否连接成功
//		$redis->connect('127.0.0.1', 6379);
//	}
//	if($redis->lLen("pyFile") != 0){
//		$res = $redis->rpop('pyFile');
//		if($res){
//			//echo "$res".PHP_EOL;
//			system($res);
//		}
//	}
//}

//print_r(read_dir_queue('/Users/zhaojiusi/Code/tron/public/References/'));exit;

function recurDir($pathName)
{
	//将结果保存在result变量中
	$result = $temp = [];
	//判断传入的变量是否是目录
	if(!is_dir($pathName) || !is_readable($pathName)) {
		return null;
	}
	//取出目录中的文件和子目录名,使用scandir函数
	$allFiles = scandir($pathName);
	//遍历他们
	foreach($allFiles as $keys=>$fileName) {
		//判断是否是.和..因为这两个东西神马也不是。。。
		if(in_array($fileName, array('.', '..'))) {
			continue;
		}
		//路径加文件名
		$fullName = $pathName.'/'.$fileName;
		//如果是目录的话就继续遍历这个目录
		if(is_dir($fullName)) {
			//将这个目录中的文件信息存入到数组中
			$result[$keys][$fullName] = recurDir($fullName);
		}else {
			//如果是文件就先存入临时变量
			$temp[] = $fullName;
		}
	}
	//取出文件
	if($temp) {
		foreach($temp as $f) {
			$result[] = $f;
		}
	}
	$data = array_values($result);
	return $data;
}
//验证一下这个函数是否好用！
$tree = recurDir('/Users/zhaojiusi/Code/tron/public/References/');
//var_dump($tree)."<br/>";

