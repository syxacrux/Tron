<?php
date_default_timezone_set('PRC'); //设置中国时区

$redis=new Redis();
$redis->connect('127.0.0.1', 6379);
while(true){
    //sleep(1);
    if($redis->ping() != '+PONG'){
        $redis->connect('127.0.0.1', 6379);
    }
    if($redis->lLen("pyFile") !='0'){
        $res=$redis->rpop('pyFile');
        if($res){
        	//执行python
					system($res);
					//记录python 执行日志
					$log_path = 'runtime'.DIRECTORY_SEPARATORlog.date("Ym");
					$log_file_name = $log_path.DIRECTORY_SEPARATOR.'python_'.date('d').'.txt';
					$res_content = 'time:'.date("Y-m-d H:i:s").'   '.$res;
					if(is_dir($log_path)){
						file_put_contents("$log_file_name",$res_content.PHP_EOL,FILE_APPEND);
					}else{
						@mkdir($log_path,0777);//创建log目录
						file_put_contents("$log_file_name",$res_content.PHP_EOL,FILE_APPEND);
					}
        }
    }
}
