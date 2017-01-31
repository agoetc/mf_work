<?php
require "./lib/sanitizing.php";
require "./lib/connect_db.php";

$user_id = hsc($_POST['user_id']);
$user_name = hsc($_POST['user_name']);
$email = hsc($_POST['email']);

try
{
	//mf_testに接続
	$dbh = connect_mf_test();
	
	echo '接続に成功しました<br>';
	
	$sql = 'insert into user_info (user_id, user_name, email) values (?, ?, ?)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $stmt->bindValue(2, $user_name, PDO::PARAM_STR);
    $stmt->bindValue(3, $email, PDO::PARAM_STR);
    $flag = $stmt->execute();
    
    if ($flag){
		//mf_imgに接続
		$dbh = connect_mf_img();
		
		//デフォルトアイコンの拡張子
		$ext = '.PNG';
		
		$sql = 'insert into icon (user_id,icon_ext,icon_name) values(?, ?, ?)';
		$stmt = $dbh->prepare($sql);
		$stmt->bindValue(1, $user_id, PDO::PARAM_STR);
		$stmt->bindValue(2, $ext, PDO::PARAM_STR);
		$stmt->bindValue(3, $user_id.$ext, PDO::PARAM_STR);
		$flag = $stmt->execute();
		
		//デフォルトアイコンを適用
		copy('../../uploader/icon/default/default.PNG', '../../uploader/icon/'.$user_id.$ext);
    	
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