<?php
require "./lib/sanitizing.php";

$dsn = 'mysql:dbname=mf_test;host=localhost';
$user = 'root';
$password = '';

$from_id = hsc($_POST['from_id']);
$to_id = hsc($_POST['to_id']);
$message = hsc($_POST['message']);
$message = str_rp("<br>", $message);

try
{
	$dbh = new PDO($dsn, $user, $password);
	
	echo '接続に成功しました<br>';
	
	$sql = 'insert into talk_data (from_id, to_id, message) values (?, ?, ?)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $from_id, PDO::PARAM_STR);
    $stmt->bindValue(2, $to_id, PDO::PARAM_STR);
    $stmt->bindValue(3, $message, PDO::PARAM_STR);
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