<?php
require "./lib/sanitizing.php";
require "./lib/connect_db.php";
session_start();

$user_id = hsc($_SESSION['user_id']);

$group_name = hsc($_POST['group_name']);

$members[] =$user_id;
$members[] = (!empty($_POST['member2'])) ? hsc($_POST['member2']) : NULL;
$members[] = (!empty($_POST['member3'])) ? hsc($_POST['member3']) : NULL;
$members[] = (!empty($_POST['member4'])) ? hsc($_POST['member4']) : NULL;
$members[] = (!empty($_POST['member5'])) ? hsc($_POST['member5']) : NULL;

try
{
	//mf_testに接続
	$dbh = connect_mf_test();
	
	echo '接続に成功しました<br>';
	
	
	//グループ作成
	$sql = 'insert into group_list (group_name, member1, member2, member3, member4, member5) values (?, ?, ?, ?, ?, ?)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $group_name, PDO::PARAM_STR);
    $stmt->bindValue(2, $members[0], PDO::PARAM_STR);
    $stmt->bindValue(3, $members[1], PDO::PARAM_STR);
    $stmt->bindValue(4, $members[2], PDO::PARAM_STR);
    $stmt->bindValue(5, $members[3], PDO::PARAM_STR);
    $stmt->bindValue(6, $members[4], PDO::PARAM_STR);
    
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
$stmt2 = null;
$dbh = null;