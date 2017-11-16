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
	
	//フレンド登録解除
	$sql = 'delete from friend_list where user_id = ? and friend_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $stmt->bindValue(2, $friend_id, PDO::PARAM_STR);
    $flag = $stmt->execute();
    
    //フレンド通知を削除
	$sql = 'delete from friend_notification where from_id = ? and to_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $stmt->bindValue(2, $friend_id, PDO::PARAM_STR);
    $flag = $stmt->execute();
}
catch(PDOException $e)
{
	print('Error:'.$e->getMessage());
	die();
}

$stmt = null;
$dbh = null;