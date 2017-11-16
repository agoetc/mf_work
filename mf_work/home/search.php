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
	//mf_testに接続
	$dbh = connect_mf_test();
	
	//検索対象の文字がユーザー情報のいずれかに含まれているなら表示する
	$search_key = '%' . hsc($_GET['search_key']) . '%';
	
	$sql = 'select user_id, user_name, email, self_intro from user_info where (user_id like ?) or (user_name like ?) or (email like ?) or (self_intro like ?)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $search_key, PDO::PARAM_STR);
    $stmt->bindValue(2, $search_key, PDO::PARAM_STR);
    $stmt->bindValue(3, $search_key, PDO::PARAM_STR);
    $stmt->bindValue(4, $search_key, PDO::PARAM_STR);
    $flag = $stmt->execute();

	//mf_imgに接続
	$dbh = connect_mf_img();
	
	//mf_testに接続
	$dbh2 = connect_mf_test();
    
	$user_icon_items = array();
    $user_id_items = array();
    $user_name_items = array();
    $user_email_items = array();
    $user_self_intro_items = array();
    $user_friend_flag_items = array();
    
    foreach($stmt as $user_items){
		$sql = 'select icon_name from icon where user_id = ?';
		$stmt2 = $dbh->prepare($sql);
		$stmt2->bindValue(1, $user_items['user_id'], PDO::PARAM_STR);
		$flag = $stmt2->execute();

		$row = $stmt2->fetch();
		$icon_name = $row['icon_name'];
		
    	$user_icon_items[] = "<img border=1 src=../../uploader/icon/{$icon_name} width=128 height=128 alt=user_icon title=user_icon>";
	    $user_id_items[] = $user_items['user_id'];
	    $user_name_items[] = $user_items['user_name'];
	    $user_email_items[] = $user_items['email'];
		$user_self_intro_items[] = $user_items['self_intro'];
	    
		//フレンド登録ボタン用
		$friend_id = hsc($user_items['user_id']);
		$user_id = hsc($_SESSION['user_id']);
		$sql = 'select * from friend_list where user_id = ? and friend_id = ?';

		$stmt2 = $dbh2->prepare($sql);
		$stmt2->bindValue(1, $user_id, PDO::PARAM_STR);
		$stmt2->bindValue(2, $friend_id, PDO::PARAM_STR);
		$flag = $stmt2->execute();
		$row = $stmt2->fetch();

		//すでにフレンド登録しているユーザーの場合
		if($row) {
			$user_friend_flag_items[] = TRUE;
		} else {
			$user_friend_flag_items[] = FALSE;
		}
    }

}
catch(PDOException $e)
{
	print('Error:'.$e->getMessage());
	die();
}

$stmt = null;
$stmt2 = null;
$dbh = null;
$dbh2 = null;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body {
	text-align: center;
}
.self_intro {
    display: inline-block;
    text-align: left;
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
</style>
<link rel="stylesheet" type="text/css" href="../css/global_menu.css">
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
<title>検索結果</title>
</head>
<body>
<h1>検索結果</h1>
<ul class="global_menu">
<li><a href="./">ホーム</a></li>
<li><a href="./friend.php">コミュニティ</a></li>
<li><a href="./match_form.php">ランダムマッチ</a></li>
<li><a href="./settings/">設定</a></li>
<li><a href="../php/sign_out.php">ログアウト</a></li>
</ul><br>
<form action="./search.php" method="get">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="search_key">
<input type="submit" value="検索"><br><br>
</form><br>
<?php
$my_flag = FALSE;
?>
<?php for($i = 0; $i < count($user_id_items); $i++):?>

	<?php
	if ($user_id == $user_id_items[$i]) {
		$my_flag = TRUE;
	}
	?>

	<!-- 自分以外のユーザーを表示 -->
	<?php if($user_id != $user_id_items[$i]):?>

		<!-- ユーザーアイコン -->
		<a href=./view.php?user_id=<?=$user_id_items[$i]?>><?=$user_icon_items[$i]?></a><br>

		<!-- ユーザーID -->
		<a href=./view.php?user_id=<?=$user_id_items[$i]?>><?=$user_id_items[$i]?></a><br>

		<!-- ユーザー名 -->
		<?=$user_name_items[$i]?><br>

		<!-- ユーザーメール -->
		<?=$user_email_items[$i]?><br>

		<!-- 自己紹介があるときだけ -->
		<?php if(isset($user_self_intro_items[$i])):?>
			<!-- ユーザー自己紹介 -->
			<p class="self_intro">自己紹介:<br><b><?=$user_self_intro_items[$i]?></b></p>
		<?php endif;?>

		<!-- フレンドではないユーザーの時だけフレンド登録ボタンを表示 -->
		<center>
		<?php if(!$user_friend_flag_items[$i]):?>
		<button id=<?=$user_id_items[$i]?>_touroku onclick=touroku("<?=$user_id_items[$i]?>")>フレンド登録</button>
		<button id=<?=$user_id_items[$i]?>_kaijo onclick=kaijo("<?=$user_id_items[$i]?>") style="background-color:red;display:none">フレンド解除</button>
		<?php else:?>
		<button id=<?=$user_id_items[$i]?>_kaijo onclick=kaijo("<?=$user_id_items[$i]?>") style="background-color:red">フレンド解除</button>
		<button id=<?=$user_id_items[$i]?>_touroku onclick=touroku("<?=$user_id_items[$i]?>") style="display:none">フレンド登録</button>
		<?php endif;?>
		</center>

		<hr>
		
	<?php endif;?>

<?php endfor;?>

<?php
if (count($user_id_items) == 0 || (count($user_id_items) == 1 && $my_flag)) {
	echo '検索結果なし';
}
?>
<!-- フッター -->
<footer>
<p><small>&copy; 2017 codeDD</small></p>
</footer>
<!-- フッター終了 -->
</body>
</html>