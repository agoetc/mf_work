<?php
require "./lib/sanitizing.php";
require "./lib/connect_db.php";
//セッションにユーザーIDを格納してホーム画面に飛ばす
session_start();

$user_id = hsc($_POST['user_id']);
$password = hsc($_POST['password']);

//mf_testに接続
$dbh = connect_mf_test();

$sql = 'select salt, encrypted_password from user_info where user_id = ?';
$stmt = $dbh->prepare($sql);
$stmt->bindValue(1, $user_id, PDO::PARAM_STR);
$flag = $stmt->execute();
$row = $stmt->fetch();
$salt = $row['salt'];
$encrypted_password = crypt($password, $salt);

if ($row['encrypted_password'] == $encrypted_password) {
	$_SESSION['user_id'] = $user_id;
	header('location: ../home/');
	exit();
} else {
	echo 'パスワードが違います';
}