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
<title>セキュリティ設定</title>
</head>
<body>
<h1>セキュリティ設定</h1>
<ul class="global_menu">
<li><a href="../../">ホーム</a></li>
<li><a href="../friend.php">コミュニティ</a></li>
<li><a href="../match_form.php">ランダムマッチ</a></li>
<li><a href="../settings/">設定</a></li>
<li><a href="../../php/sign_out.php">ログアウト</a></li>
</ul><br>
<form action="../../php/change_settings.php" method="post">
<input type=hidden name=settings_type value=security>
現在のパスワードを入力: <input type=password name=old_password><br><br>
新しいパスワードを入力: <input type=password name=new_password><br><br><br>
<center><button type="submit">変更</button></center><br><br>
</form>
<!-- フッター -->
<footer>
<p><small>&copy; 2017 codeDD</small></p>
</footer>
<!-- フッター終了 -->
</body>
</html>