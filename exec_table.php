<?php
$host = '192.168.100.87';
$local_host = 'localhost';
$user = 'root';
$pwd = '978866';
$local_pwd = 'root';
$database = 'test';
try {
	$pdo = new PDO("mysql:host=$host;dbname=$database", $user, $pwd);
} catch (PDOException $e) {
	echo 'Connection failed: ' . $e->getMessage();
}
$sql = "select * from test";
$pdo->query('set name utf8;');
$server_result = $pdo->query($sql)->fetchAll();

//更新本地数据表数据
try {
	$local_pdo = new PDO("mysql:host=$local_host;dbname=$database", $user, $local_pwd);
} catch (PDOException $e) {
	echo 'Connection failed: ' . $e->getMessage();
}

foreach($server_result as $key=>$value){
	$local_sql = "insert into test(`id`,`name`) values(:id,:name )";
	$sql_data = [
		"id"	=> $value['id'],
		"name" => $value['name']
	];
	$local_result = $local_pdo->prepare($local_sql)->execute($sql_data);
	if($local_result){
		echo $local_pdo->lastInsertId();
	}else{
		echo 123;
	}
}