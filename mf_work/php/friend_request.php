<?php
require "./lib/sanitizing.php";
require "./lib/connect_db.php";
session_start();

$user_id = hsc($_SESSION['user_id']);
$friend_id = hsc($_POST['friend_id']);

try
{
	//mf_testに接続
	$dbh = connect_mf_test();
	
	//フレンド登録
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
    
    //フレンド登録した相手に通知
    $sql2 = 'insert into friend_notification (from_id, to_id) values (?, ?)';
    $stmt2 = $dbh->prepare($sql2);
    $stmt2->bindValue(1, $user_id, PDO::PARAM_STR);
    $stmt2->bindValue(2, $friend_id, PDO::PARAM_STR);
    $flag = $stmt2->execute();
    
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
$stmt2 = null;
$dbh = null;