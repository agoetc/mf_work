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
<style>
body {
	text-align: center;
}
/* 角丸5px */
button {
    margin: 3px;
    width:110px;
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
<title>退会</title>
</head>
<body>
<h1>退会</h1>
<ul class="global_menu">
<li><a href="../../">ホーム</a></li>
<li><a href="../friend.php">コミュニティ</a></li>
<li><a href="../match_form.php">ランダムマッチ</a></li>
<li><a href="../settings/">設定</a></li>
<li><a href="../../php/sign_out.php">ログアウト</a></li>
</ul><br>
一度退会すると二度とログインできなくなります。<br>
本当に退会しますか？<br><br>
<form action="../../php/change_settings.php" method="post">
<?php
echo "<input type=hidden name=settings_type value=withdraw>";
?>
<center><button type="submit">退会</button></center>
</form><br>
<!-- フッター -->
<footer>
<p><small>&copy; 2017 codeDD</small></p>
</footer>
<!-- フッター終了 -->
</body>
</html>