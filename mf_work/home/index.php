<?php
require "../php/lib/sanitizing.php";
require "../php/lib/connect_db.php";
//まだログインしていないならトップページに飛ばす
session_start();

if(!isset($_SESSION['user_id']))
{
	header('location: ../');
	exit();
}

$user_id = hsc($_SESSION['user_id']);

try
{	
	//mf_imgに接続
	$dbh = connect_mf_img();
	
	$sql = 'select icon_name from icon where user_id = ?';
	$stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $flag = $stmt->execute();
    
    $row = $stmt->fetch();
    $icon_name = $row['icon_name'];
	
	//mf_testに接続
	$dbh = connect_mf_test();
	
	$sql = 'select user_name, email, self_intro from user_info where user_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $flag = $stmt->execute();
    
    $user_items = $stmt->fetch();
    //ユーザーアイコン
    $user_icon = "<img border=1 src=../../uploader/icon/{$icon_name} width=128 height=128 alt=user_icon title=user_icon>";
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
<link rel="stylesheet" type="text/css" href="../css/global_menu.css">
<link rel="stylesheet" type="text/css" href="../css/home.css">
<title>ホーム画面</title>
</head>
<body>
<!-- ヘッダー -->
<header>
<h1>Sarachi</h1>
<nav>
<ul class="global_menu">
<li><a href="./">ホーム</a></li>
<li><a href="./friend.php">コミュニティ</a></li>
<li><a href="./match_form.php">ランダムマッチ</a></li>
<li><a href="./settings/">設定</a></li>
<li><a href="../php/sign_out.php">ログアウト</a></li>
</ul>
</nav>
</header><br>
<!-- ヘッダー終了 -->

<!-- ユーザーアイコン -->
<div id="user_icon">
<?=$user_icon?>
</div>

<!-- ユーザーID -->
<div id="user_id">
<?=$user_id?>
</div>

<!-- ユーザーネーム -->
<div id="user_name">
<?=$user_items['user_name']?>
</div>

<!-- ユーザーメール -->
<div id="user_email">
<?=$user_items['email']?>
</div>

<!-- ユーザー自己紹介 -->
<?php if (isset($user_items['self_intro'])): ?>
<div id="self_intro">
<p>自己紹介</p>
<p><b><?=$user_items['self_intro']?></b></p>
</div>
<?php endif; ?>

<!-- フッター -->
<footer>
<p><small>&copy; 2017 codeDD</small></p>
</footer>
<!-- フッター終了 -->
</body>
</html>