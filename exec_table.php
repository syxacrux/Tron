<?php
$host = '192.168.100.69';
$local_host = 'localhost';
$user = 'root';
$pwd = 'M3o1i9d8';
$local_pwd = 'root';
$database = 'tron';
$local_database = 'new_tron';
/**
 * @param $host	string 正式平台 sql地址
 * @param $database	string 正式平台 数据库名称
 * @param $user string 正式平台 数据库登陆名
 * @param $pwd string 正式平台 数据库密码
 * @param $local_host string
 * @param $local_pwd string
 * @param $local_database string
 */
function import_user($host,$database,$user,$pwd,$local_host,$local_pwd,$local_database){
	try {
		$pdo = new PDO("mysql:host=$host;dbname=$database", $user, $pwd);
	} catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
	}
	$sql = "select * from userdata";
	$pdo->query('set name utf8;');
	$server_result = $pdo->query($sql)->fetchAll();

//新增本地数据表数据
	try {
		$local_pdo = new PDO("mysql:host=$local_host;dbname=$local_database", $user, $local_pwd);
	} catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
	}

	foreach($server_result as $key=>$value){
		$local_sql = "insert into oa_admin_user(`id`,`username`,`password`,`realname`,`status`) values(:id,:username,:password,:realname,:status)";
		$sql_data = [
			"id"			=> $value['uid'],
			"username" => $value['uname'],
			"password" => $value['password'],
			"realname" => $value['name'],
			"status"	=>1
		];
		$local_result = $local_pdo->prepare($local_sql)->execute($sql_data);
		if($local_result){
			echo $local_pdo->lastInsertId().PHP_EOL;
		}else{
			echo '404'.PHP_EOL;
		}
	}
}

//echo import_user($host,$database,$user,$pwd,$local_host,$local_pwd,$local_database);
//导入环节表
function import_tache($host,$database,$user,$pwd,$local_host,$local_pwd,$local_database){
	try {
		$pdo = new PDO("mysql:host=$host;dbname=$database", $user, $pwd);
	} catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
	}
	$sql = "select * from usergroup";
	$pdo->query('set name utf8;');
	$server_result = $pdo->query($sql)->fetchAll();

//新增本地数据表数据
	try {
		$local_pdo = new PDO("mysql:host=$local_host;dbname=$local_database", $user, $local_pwd);
	} catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
	}

	foreach($server_result as $key=>$value){
		$local_sql = "insert into oa_admin_tache(`id`,`tache_name`,`explain`) values(:id,:tache_name,:explain)";
		$sql_data = [
			"id"			=> $value['gid'],
			"tache_name" => $value['gname'],
			"explain" => $value['fullname'].'部'
		];
		$local_result = $local_pdo->prepare($local_sql)->execute($sql_data);
		if($local_result){
			echo $local_pdo->lastInsertId().PHP_EOL;
		}else{
			echo '404'.PHP_EOL;
		}
	}
}
//echo import_tache($host,$database,$user,$pwd,$local_host,$local_pwd,$local_database);

//导入用户所属环节数据
function import_tache_user($host,$database,$user,$pwd,$local_host,$local_pwd,$local_database){
	try {
		$pdo = new PDO("mysql:host=$host;dbname=$database", $user, $pwd);
	} catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
	}
	$sql = "select * from userlink";
	$pdo->query('set name utf8;');
	$server_result = $pdo->query($sql)->fetchAll();

//新增本地数据表数据
	try {
		$local_pdo = new PDO("mysql:host=$local_host;dbname=$local_database", $user, $local_pwd);
	} catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
	}

	foreach($server_result as $key=>$value){
		$local_sql = "update oa_admin_user set `tache_ids`= :tache_ids where id=:id";
		$sql_data = [
			"id"			=> $value['uid'],
			"tache_ids" => $value['gid']
		];
		$local_result = $local_pdo->prepare($local_sql)->execute($sql_data);
		if($local_result){
			echo $local_pdo->lastInsertId().PHP_EOL;
		}else{
			echo '404'.PHP_EOL;
		}
	}
}

//echo import_tache_user($host,$database,$user,$pwd,$local_host,$local_pwd,$local_database);

//写入成员角色权限
function import_user_role($host,$database,$user,$pwd,$local_host,$local_pwd,$local_database){
	try {
		$pdo = new PDO("mysql:host=$host;dbname=$database", $user, $pwd);
	} catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
	}
	$sql = "select * from userlink";
	$pdo->query('set name utf8;');
	$server_result = $pdo->query($sql)->fetchAll();

//新增本地数据表数据
	try {
		$local_pdo = new PDO("mysql:host=$local_host;dbname=$local_database", $user, $local_pwd);
	} catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
	}

	foreach($server_result as $key=>$value){
		$local_sql = "insert into oa_admin_access(`user_id`,`group_id`) values(:user_id,:group_id)";
		switch ($value['rid']){
			case 7:
				$sql_data = [
					"user_id"			=> $value['uid'],
					"group_id" => 1
				];
				break;
			case 8:
				$sql_data = [
					"user_id"			=> $value['uid'],
					"group_id" => 4
				];
				break;
			case 9:
				$sql_data = [
					"user_id"			=> $value['uid'],
					"group_id" => 6
				];
				break;
			case 10:
				$sql_data = [
					"user_id"			=> $value['uid'],
					"group_id" => 7
				];
				break;
		}

		$local_result = $local_pdo->prepare($local_sql)->execute($sql_data);
		if($local_result){
			echo $local_pdo->lastInsertId().PHP_EOL;
		}else{
			echo '404'.PHP_EOL;
		}
	}
}

//echo import_user_role($host,$database,$user,$pwd,$local_host,$local_pwd,$local_database);






