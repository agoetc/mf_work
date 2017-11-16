<?php
//すでにログインしているならホーム画面に飛ばす
session_start();

if(isset($_SESSION['user_id']))
{
	header('location: ./home/');
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Sarachi</title>
</head>
<body>
<center>
<h1>Sarachi</h1><br>
<form action="./php/sign_in.php" method="post">
<input type="text" name="user_id" placeholder="ユーザーIDを入力"><br><br>
<input type="password" name="password" placeholder="パスワードを入力"><br><br>
<input type="submit" name="submit" value="ログイン">
</form><br><br>
<b>アカウントをお持ちでないですか？</b><br><br>
<a href="./sign_up.html">新規登録</a>
</center>
</body>
</html>