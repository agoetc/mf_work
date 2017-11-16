<?php
//新規登録で送られてきたデータ形式が正しいかチェックする
function check_sign_up_data($user_id, $user_name, $email, $password) {
	if(empty($user_id) || empty($user_name) || empty($email) || empty($password)) {
		echo '未入力項目があります<br>';
		exit();
	} else if(!ctype_alnum($user_id) || mb_strlen($user_id) > 15 || mb_strlen($user_name) > 30 || !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/^[!-~]+$/", $password)) {
		if(!ctype_alnum($user_id)) echo 'ユーザーIDは半角英数字で入力してください。<br>';
		if(mb_strlen($user_id) > 15) echo 'ユーザーIDが長すぎます。（15文字以内で入力してください）<br>';
		if(mb_strlen($user_name) > 30) echo 'ユーザー名が長すぎます。（30文字以内で入力してください）<br>';
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) echo 'メールアドレスが適切な形式ではありません。<br>';
		if(!preg_match("/^[!-~]+$/", $password)) echo 'パスワードは英数字と記号の範囲で構成してください。<br>';
		exit();
	}
}

//アカウント設定で送られてきたデータ形式が正しいかチェックする
function check_account_settings_data($user_name, $email, $self_intro) {
	if(empty($user_name) || empty($email)) {
		echo 'ユーザー名もしくはメールアドレスが未入力です。';
		exit();
	} else if(mb_strlen($user_name) > 30 || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($self_intro) > 200) {
		if(mb_strlen($user_name) > 30) echo 'ユーザー名が長すぎます。（30文字以内で入力してください）<br>';
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) echo 'メールアドレスが適切な形式ではありません。<br>';
		if(mb_strlen($self_intro) > 200) echo '自己紹介が長すぎます。（200文字以内で入力してください。）<br>';
		exit();
	}
}

//新しいパスワードのデータ形式が正しいかチェックする
function check_new_password_data($new_password) {
	if(empty($new_password)) {
		echo '新しいパスワードが入力されていません。<br>';
		exit();
	} else if(!preg_match("/^[!-~]+$/", $new_password)) {
		echo 'パスワードは英数字と記号の範囲で構成してください。<br>';
		exit();
	}
}

//グループ作成のデータ形式が正しいかチェックする
function check_group_data($group_name, $members) {
	if(empty($group_name)) {
		echo 'グループ名が未入力です。';
		exit();
	}
	for($i = 1; $i < 5; $i++) {
		if($members[0] == $members[$i]) {
			echo '自分を招待するユーザーに加えることはできません。';
			exit();
		}
	}
	for($i = 1; $i < 5; $i++) {
		if(!empty($members[$i])) {
			if(!user_exist($members[$i])) {
				echo '存在しないユーザーをグループに加えることはできません。';
				exit();
			}
		}
	}
}

//ユーザーが存在するかチェックする
function user_exist($user_id) {
	$dbh = connect_mf_test();
	$sql = 'select * from user_info where user_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $flag = $stmt->execute();
    if (!$stmt->fetch()) {
    	return false;
    }
    return true;
}