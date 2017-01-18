<?php
require "./lib/sanitizing.php";
$user_id = hsc($_POST['user_id']);
$user_name = hsc($_POST['user_name']);
$email = hsc($_POST['email']);

$dsn = 'mysql:dbname=mf_test;host=localhost';
$user = 'root';
$password = '';

try
{
	$dbh = new PDO($dsn, $user, $password);
	
	echo '接続に成功しました<br>';
	
	$sql = 'insert into user_info (user_id, user_name, email) values (?, ?, ?)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $stmt->bindValue(2, $user_name, PDO::PARAM_STR);
    $stmt->bindValue(3, $email, PDO::PARAM_STR);
    $flag = $stmt->execute();
    
    if ($flag){
    	echo 'データの追加に成功しました<br>';
    }else{
    	echo 'データの追加に失敗しました<br>';
    }

}
catch(PDOException $e)
{
	print('Error:'.$e->getMessage());
	die();
}

$stmt = null;
$dbh = null;