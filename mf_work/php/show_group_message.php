<?php
require "./lib/sanitizing.php";
require "./lib/connect_db.php";
session_start();

$from_id = hsc($_SESSION['user_id']);
$group_id = hsc($_GET['group_id']);

try
{
	//mf_testに接続
	$dbh = connect_mf_test();
	
	//データがあるとき更新
	$sql = 'UPDATE group_notif SET read_flg = "kidoku" WHERE user_id = ? AND group_id = ?';
	$stmt = $dbh->prepare($sql);
	$flag = $stmt->execute(array($from_id, $group_id));
	
	$sql = "select * from group_talk_data where group_id='$group_id' order by num desc";
    $stmt = $dbh->prepare($sql);
    $flag = $stmt->execute();
    foreach($stmt as $row){
    	$json[] = $row;
    }
    
    if(isset($json)){
    	echo json_encode($json);
    }
}
catch(PDOException $e)
{
	print('Error:'.$e->getMessage());
	die();
}

$stmt = null;
$dbh = null;