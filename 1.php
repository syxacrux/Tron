<?php
/**
 * Created by PhpStorm.
 * User: zhaojiusi
 * Date: 2018/4/11
 * Time: 19:58
 */


//$redis = new redis();
//$redis->connect('127.0.0.1', 6379);
//$redis->set('zjs',"webfsd");
//$result = $redis->get('site');
//var_dump($result);

$redis = new redis();
$redis->connect('127.0.0.1', 6379);
while(true){
	//sleep(1);
	if($redis->ping() != '+PONG'){	//检测是否连接成功
		$redis->connect('127.0.0.1', 6379);
	}
	if($redis->lLen("pyFile") != 0){
		$res = $redis->rpop('pyFile');
		if($res){
			//echo "$res".PHP_EOL;
			system($res);
		}
	}
}
