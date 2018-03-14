<?php
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
            echo "$res".PHP_EOL;
            system($res);
        }
    }
}
