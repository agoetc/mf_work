<?php
require "./lib/sanitizing.php";
require "./lib/connect_db.php";
require "./lib/encryption.php";
require "./lib/check_data.php";
session_start();

$user_id = hsc($_SESSION['user_id']);

//アカウント設定の場合
if ($_POST['settings_type'] === "account") {
	$user_name = hsc($_POST['user_name']);
	$email = hsc($_POST['email']);
	$self_intro = hsc($_POST['self_intro']);
	$self_intro = str_rp("<br>", $self_intro);

	//データ形式が正しいかチェック
	check_account_settings_data($user_name, $email, $self_intro);

	try
	{
		//mf_testに接続
		$dbh = connect_mf_test();

		echo 'アカウント設定を更新しました<br><br>';

		//ユーザーの情報を編集
		$sql = 'update user_info set user_name = ?, email = ?, self_intro = ? where user_id = ?';
	    $stmt = $dbh->prepare($sql);
	    $stmt->bindValue(1, $user_name, PDO::PARAM_STR);
	    $stmt->bindValue(2, $email, PDO::PARAM_STR);
	    $stmt->bindValue(3, $self_intro, PDO::PARAM_STR);
	    $stmt->bindValue(4, $user_id, PDO::PARAM_STR);
	    $flag = $stmt->execute();

		echo '<META http-equiv="refresh" content="1; url=../home/">';

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
//セキュリティ設定の場合
} else if ($_POST['settings_type'] === "security") {
	$old_password = hsc($_POST['old_password']);
	$new_password = hsc($_POST['new_password']);

	//新しいパスワードのデータ形式が正しいかチェック
	check_new_password_data($new_password);

	try
	{
		//mf_testに接続
		$dbh = connect_mf_test();

		$sql = 'select salt, encrypted_password from user_info where user_id = ?';
		$stmt = $dbh->prepare($sql);
		$stmt->bindValue(1, $user_id, PDO::PARAM_STR);
		$flag = $stmt->execute();
		$row = $stmt->fetch();
		$salt = $row['salt'];
		$encrypted_password = crypt($old_password, $salt);

		if ($row['encrypted_password'] == $encrypted_password) {
			$new_salt = create_salt();
			$new_encrypted_password = crypt($new_password, $new_salt);

			//ユーザーのパスワードを編集
			$sql = 'update user_info set salt = ?, encrypted_password = ? where user_id = ?';
		    $stmt = $dbh->prepare($sql);
		    $stmt->bindValue(1, $new_salt, PDO::PARAM_INT);
		    $stmt->bindValue(2, $new_encrypted_password, PDO::PARAM_STR);
		    $stmt->bindValue(3, $user_id, PDO::PARAM_STR);
		    $flag = $stmt->execute();

		    echo 'パスワードを変更しました';
				echo '<META http-equiv="refresh" content="1; url=../home/">';

		} else {
			echo 'パスワードが違います';
			echo '<META http-equiv="refresh" content="1; url=../home/settings/security.php">';
		}
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
