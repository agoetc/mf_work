<?php
require "./lib/sanitizing.php";
require "./lib/connect_db.php";
require "./lib/encryption.php";
require "./lib/check_data.php";

$user_id = (isset($_POST['user_id'])) ? hsc($_POST['user_id']) : exit();
$user_name = (isset($_POST['user_name'])) ? hsc($_POST['user_name']) : exit();
$email = (isset($_POST['email'])) ? hsc($_POST['email']) : exit();
$password = (isset($_POST['password'])) ? hsc($_POST['password']) : exit();

//入力チェック
check_sign_up_data($user_id, $user_name, $email, $password);

//暗号化に必要な文字列を作成
$salt = create_salt();
//暗号化
$encrypted_password = crypt($password, $salt);

try
{
	//mf_testに接続
	$dbh = connect_mf_test();

	$sql = 'insert into user_info (user_id, user_name, email, salt, encrypted_password) values (?, ?, ?, ?, ?)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $stmt->bindValue(2, $user_name, PDO::PARAM_STR);
    $stmt->bindValue(3, $email, PDO::PARAM_STR);
    $stmt->bindValue(4, $salt, PDO::PARAM_INT);
    $stmt->bindValue(5, $encrypted_password, PDO::PARAM_STR);
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

    	echo 'アカウントを作成しました<br>';
		echo '<META http-equiv="refresh" content="1; url=../">';
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
