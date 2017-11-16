<?php
require "../php/lib/sanitizing.php";
require "../php/lib/connect_db.php";
require "../php/lib/check_data.php";
session_start();

if($_GET['user_id'] == $_SESSION['user_id']) {
	header('location: ./');
	exit();
}

$user_id = hsc($_GET['user_id']);

if (!user_exist($user_id)) {
	echo 'ユーザーが存在しません。';
	exit();
}

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
	
	//ユーザーの情報一覧を取得
	$sql = 'select user_name, email, self_intro from user_info where user_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $flag = $stmt->execute();
    
    $user_items = $stmt->fetch();
    
    //ユーザーアイコン用
    $user_icon = "<img border=1 src=../../uploader/icon/{$icon_name} width=128 height=128 alt=user_icon title=user_icon><br>";
    
    //フレンド登録ボタン用
    $friend_id = $user_id;
    $user_id = hsc($_SESSION['user_id']);
    $sql = 'select * from friend_list where user_id = ? and friend_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $stmt->bindValue(2, $friend_id, PDO::PARAM_STR);
    $flag = $stmt->execute();
    $row = $stmt->fetch();
    
    $friend_flag = FALSE;
    //すでにフレンド登録しているユーザーの場合
    if($row) {
    	$friend_flag = TRUE;
    }
    
    //フレンド通知を削除
	$sql = 'delete from friend_notification where from_id = ? and to_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $friend_id, PDO::PARAM_STR);
    $stmt->bindValue(2, $user_id, PDO::PARAM_STR);
    $flag = $stmt->execute();
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
<style>
body {
	text-align: center;
}
/* 角丸5px */
button {
    margin: 3px;
    width:140px;
    font-size:12px;
    font-weight:bold;
    text-decoration:none;
    display:block;
    text-align:center;
    padding:8px 0 10px;
    color:#fff;
    background-color:#49a9d4;
    border-radius:5px;
}
#self_intro {
    display: inline-block;
    text-align: left;
}
</style>
<script src="../js/jquery-3.1.1.min.js"></script>
<script>
function touroku(user_id){
	var data = {
		friend_id: user_id
	};

	$.post("../php/friend_request.php", data);
	document.getElementById(user_id + "_touroku").style.display = "none";
	document.getElementById(user_id + "_kaijo").style.display = "block";
	document.getElementById(user_id + "_kaijo").style.backgroundColor = "red";
}

function kaijo(user_id){
	var data = {
		friend_id: user_id
	};

	$.post("../php/friend_delete.php", data);
	document.getElementById(user_id + "_kaijo").style.display = "none";
	document.getElementById(user_id + "_touroku").style.display = "block";
}
</script>
<title>ユーザー情報</title>
</head>
<body>
<h1>ユーザー情報</h1>
<ul class="global_menu">
<li><a href="./">ホーム</a></li>
<li><a href="./friend.php">コミュニティ</a></li>
<li><a href="./match_form.php">ランダムマッチ</a></li>
<li><a href="./settings/">設定</a></li>
<li><a href="../php/sign_out.php">ログアウト</a></li>
</ul><br>

<!-- ユーザーアイコン -->
<div id="user_icon">
<?=$user_icon?>
</div>

<!-- ユーザーID -->
<div id="user_id">
<?=hsc($_GET['user_id'])?>
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
<p>自己紹介:</p>
<p><b><?=$user_items['self_intro']?></b></p>
</div>
<?php endif; ?>

<!-- フレンド登録しているかどうかで処理を分ける -->
<center>
<?php if(!$friend_flag):?>
<button id=<?=$friend_id?>_touroku onclick=touroku("<?=$friend_id?>")>フレンド登録</button>
<button id=<?=$friend_id?>_kaijo onclick=kaijo("<?=$friend_id?>") style="background-color:red;display:none">フレンド解除</button>
<?php else:?>
<button id=<?=$friend_id?>_kaijo onclick=kaijo("<?=$friend_id?>") style="background-color:red">フレンド解除</button>
<button id=<?=$friend_id?>_touroku onclick=touroku("<?=$friend_id?>") style="display:none">フレンド登録</button>
<?php endif;?>
</center><br>

<!-- フッター -->
<footer>
<p><small>&copy; 2017 codeDD</small></p>
</footer>
<!-- フッター終了 -->
</body>
</html>