<?php
//すでにログインしているならホーム画面に飛ばす
session_start();

if(isset($_SESSION['user_id']))
{
	header('location: ./home');
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>SNSの名前</title>
</head>
<body>
<h1>SNSの名前</h1><br>
SNSにログインする（<font color="red"><b>ユーザーID</b></font>を入力）<br><br>
<form action="./php/sign_in.php" method="post">
<input type="text" name="user_id">
<input type="submit" name="submit" value="ログイン">
</form><br><br>
<b>アカウントをお持ちでないですか？</b><br><br>
<a href="./sign_up.html">新規登録</a>
</body>
</html>