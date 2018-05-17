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
//$log_path = 'runtime/log/'.date("Ym");
//if(is_dir($log_path)){
//	$log_file_name = $log_path.DIRECTORY_SEPARATOR.'python_'.date('d').'.txt';
//	$res_content = 'time:'.date("Y-m-d H:i:s").'   '.'123';
//	file_put_contents("$log_file_name",$res_content.PHP_EOL,FILE_APPEND);
//}else{
//	echo 2;
//}
//file_put_contents('runtime/log/201805/aa.txt','123');

$a = 'Mys7431';
echo md5($a);
