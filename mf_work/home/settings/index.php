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
<link rel="stylesheet" type="text/css" href="../../css/global_menu.css">
<link rel="stylesheet" type="text/css" href="../../css/settings.css">
<title>設定</title>
</head>
<body>
<!-- ヘッダー -->
<header>
<h1>設定画面</h1>
<nav>
<ul class="global_menu">
<li><a href="../../">ホーム</a></li>
<li><a href="../friend.php">コミュニティ</a></li>
<li><a href="../match_form.php">ランダムマッチ</a></li>
<li><a href="./">設定</a></li>
<li><a href="../../php/sign_out.php">ログアウト</a></li>
</ul>
</nav>
</header>
<!-- ヘッダー終了 -->

<ul class="settings_menu" style="list-style:none;">
<li><a href="./account.php">アカウント設定（登録情報の変更）</a></li>
<li><a href="../../../uploader/form.php">アップローダー（アイコンの変更）</a></li>
<li><a href="./security.php">セキュリティ設定</a></li>
<li><a href="./withdraw.php">退会</a></li>
</ul>
<!-- フッター -->
<footer>
<p><small>&copy; 2017 codeDD</small></p>
</footer>
<!-- フッター終了 -->
</body>
</html>