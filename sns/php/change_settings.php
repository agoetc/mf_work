<?php
require "./lib/sanitizing.php";
require "./lib/connect_db.php";
session_start();

$user_id = hsc($_SESSION['user_id']);

//アカウント設定の場合
if ($_POST['settings_type'] === "account") {
	$user_name = hsc($_POST['user_name']);
	$email = hsc($_POST['email']);
	$self_intro = hsc($_POST['self_intro']);
	$self_intro = str_rp("<br>", $self_intro);

	try
	{
		//mf_testに接続
		$dbh = connect_mf_test();
		
		echo '接続に成功しました<br><br>';
		
		//ユーザーの情報を編集
		$sql = 'update user_info set user_name = ?, email = ?, self_intro = ? where user_id = ?';
	    $stmt = $dbh->prepare($sql);
	    $stmt->bindValue(1, $user_name, PDO::PARAM_STR);
	    $stmt->bindValue(2, $email, PDO::PARAM_STR);
	    $stmt->bindValue(3, $self_intro, PDO::PARAM_STR);
	    $stmt->bindValue(4, $user_id, PDO::PARAM_STR);
	    $flag = $stmt->execute();
	}
	catch(PDOException $e)
	{
		print('Error:'.$e->getMessage());
		die();
	}
//退会処理の場合
} else if ($_POST['settings_type'] === "withdraw") {
	try
	{
		//mf_testに接続
		$dbh = connect_mf_test();
		
		echo '接続に成功しました<br><br>';
		
		//user_infoテーブルから削除
		$sql = 'delete from user_info where user_id = ?';
	    $stmt = $dbh->prepare($sql);
	    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
	    $flag = $stmt->execute();
	    
	    //friend_listテーブルから削除
	    $sql = 'delete from friend_list where user_id = ? or friend_id = ?';
	    $stmt = $dbh->prepare($sql);
	    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
	    $stmt->bindValue(2, $user_id, PDO::PARAM_STR);
	    $flag = $stmt->execute();
	    
	    echo '退会しました<br><br>';
	    
	    //セッションを破棄
	    $_SESSION = array(); 
		session_destroy();
		
		echo '<a href=../>トップページに戻る</a>';
	}
	catch(PDOException $e)
	{
		print('Error:'.$e->getMessage());
		die();
	}
}

//データベースの接続を閉じる
$stmt = null;
$dbh = null;