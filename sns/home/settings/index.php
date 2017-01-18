<?php
//まだログインしていないならトップページに飛ばす
session_start();

if(!isset($_SESSION['user_id']))
{
	header('location: ../../');
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>設定</title>
</head>
<body>
<h1>設定画面</h1><br>
<a href="./account.php">アカウント設定</a><br><br>
<a href="./security.php">セキュリティ設定</a><br><br>
<a href="./notification.php">通知設定</a><br><br>
<a href="./withdraw.php">退会</a><br><br>
</body>
</html>