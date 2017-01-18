<?php
require "./lib/sanitizing.php";
session_start();

$user_id = hsc($_SESSION['user_id']);

$dsn = 'mysql:dbname=mf_test;host=localhost';
$user = 'root';
$password = '';

if ($_POST['settings_type'] === "account") {
	$user_name = hsc($_POST['user_name']);
	$email = hsc($_POST['email']);
	$self_intro = hsc($_POST['self_intro']);
	$self_intro = str_rp("<br>", $self_intro);

	try
	{
		$dbh = new PDO($dsn, $user, $password);
		
		echo '接続に成功しました<br><br>';
		
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

	$stmt = null;
	$dbh = null;
} else if ($_POST['settings_type'] === "withdraw") {
	try
	{
		$dbh = new PDO($dsn, $user, $password);
		
		echo '接続に成功しました<br><br>';
		
		$sql = 'delete from user_info where user_id = ?';
	    $stmt = $dbh->prepare($sql);
	    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
	    $flag = $stmt->execute();
	    
	    $sql2 = 'delete from friend_list where user_id = ? or friend_id = ?';
	    $stmt2 = $dbh->prepare($sql2);
	    $stmt2->bindValue(1, $user_id, PDO::PARAM_STR);
	    $stmt2->bindValue(2, $user_id, PDO::PARAM_STR);
	    $flag = $stmt2->execute();
	    
	    echo '退会しました<br><br>';
	    
	    $_SESSION = array(); 
		session_destroy();
		
		echo '<a href=../>トップページに戻る</a>';
	}
	catch(PDOException $e)
	{
		print('Error:'.$e->getMessage());
		die();
	}

	$stmt = null;
	$stmt2 = null;
	$dbh = null;
}