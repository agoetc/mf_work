<?php
require "./lib/sanitizing.php";
require "./lib/url.php";
require "./lib/connect_db.php";

$from_id = hsc($_POST['from_id']);
$group_id = hsc($_POST['group_id']);
$message = hsc($_POST['message']);
//URLの文字列をリンクにする
$message = url_to_anchor($message);
$message = str_rp("<br>", $message);

try
{
	//mf_testに接続
	$dbh = connect_mf_test();
	
	echo '接続に成功しました<br>';
	
	$sql = 'insert into group_talk_data (from_id, group_id, message) values (?, ?, ?)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $from_id, PDO::PARAM_STR);
    $stmt->bindValue(2, $group_id, PDO::PARAM_STR);
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