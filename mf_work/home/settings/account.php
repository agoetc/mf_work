<?php
require "../../php/lib/sanitizing.php";
require "../../php/lib/connect_db.php";
//まだログインしていないならトップページに飛ばす
session_start();

if(!isset($_SESSION['user_id']))
{
	header('location: ../../');
	exit();
}

$user_id = hsc($_SESSION['user_id']);

try
{
	//mf_testに接続
	$dbh = connect_mf_test();
	
	$sql = 'select user_name, email, self_intro from user_info where user_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $flag = $stmt->execute();
    
    $user_items = $stmt->fetch();
    $user_name = $user_items['user_name'];
    $email = $user_items['email'];
    if (isset($user_items['self_intro'])){
		$self_intro = $user_items['self_intro'];
		//テキストエリア内で改行させるために<br>を数値文字参照に変換する
		$self_intro = str_replace("<br>", "&#13;&#10;", $self_intro);
	}
}
catch(PDOException $e)
{
	print('Error:'.$e->getMessage());
	die();
}

$stmt = null;
$dbh = null;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body {
	text-align: center;
}
/* 角丸5px */
button {
    margin: 3px;
    width:140px;
    font-size:14px;
    font-weight:bold;
    text-decoration:none;
    display:block;
    text-align:center;
    padding:8px 0 10px;
    color:#fff;
    background-color:#49a9d4;
    border-radius:5px;
}
</style>
<link rel="stylesheet" type="text/css" href="../../css/global_menu.css">
<title>アカウント設定</title>
</head>
<body>
<h1>アカウント設定</h1>
<ul class="global_menu">
<li><a href="../../">ホーム</a></li>
<li><a href="../friend.php">コミュニティ</a></li>
<li><a href="../match_form.php">ランダムマッチ</a></li>
<li><a href="../settings/">設定</a></li>
<li><a href="../../php/sign_out.php">ログアウト</a></li>
</ul><br>
<form action="../../php/change_settings.php" method="post">
<input type=hidden name=settings_type value=account>
ユーザー名を入力: <input type=text name=user_name value=<?=$user_name?>><br><br>
メールアドレスを入力: <input type=text name=email value=<?=$email?>><br><br>
自己紹介欄: <br><br><textarea name=self_intro id=self_intro rows=18 cols=67><?php if(isset($self_intro)):?><?=$self_intro?><?php endif;?></textarea><br><br>
<center><button type="submit">変更</button></center>
</form><br>
<!-- フッター -->
<footer>
<p><small>&copy; 2017 codeDD</small></p>
</footer>
<!-- フッター終了 -->
</body>
</html>