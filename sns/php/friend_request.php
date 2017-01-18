<?php
require "./lib/sanitizing.php";
session_start();

$user_id = hsc($_SESSION['user_id']);
$friend_id = hsc($_POST['friend_id']);

$dsn = 'mysql:dbname=mf_test;host=localhost';
$user = 'root';
$password = '';

try
{
	$dbh = new PDO($dsn, $user, $password);
	
	echo '接続に成功しました<br>';
	
	$sql = 'insert into friend_list (user_id, friend_id) values (?, ?)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $stmt->bindValue(2, $friend_id, PDO::PARAM_STR);
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