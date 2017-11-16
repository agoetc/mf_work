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

	//自分がフレンド登録をしているユーザーの一覧を取得
	$sql = 'select friend_id from friend_list where user_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $flag = $stmt->execute();

    //フレンドのIDとユーザー名を表示
    $friend_id_items = array();
    $friend_name_items = array();
    foreach($stmt as $row){
    	$friend_id = $row['friend_id'];
    	//フレンドIDの一覧を格納
    	$friend_id_items[] = "<a href=./view.php?user_id=$friend_id>" . $friend_id . "</a>&nbsp;";
    	$sql = 'select user_name from user_info where user_id = ?';
	    $stmt = $dbh->prepare($sql);
	    $stmt->bindValue(1, $friend_id, PDO::PARAM_STR);
	    $flag = $stmt->execute();
	    $row = $stmt->fetch();
	    //フレンドの名前の一覧を格納
	    $friend_name_items[] = "<a href=./talk.php?to_id=$friend_id>" . $row['user_name'] . "&nbsp;と会話をする</a>";
    }

    //誰かが自分をフレンド登録したら通知する
    $friend_notif_items = array();
    $sql = 'select from_id from friend_notification where to_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $flag = $stmt->execute();

    foreach($stmt as $row){
    	$from_id = $row['from_id'];
    	$friend_notif_items[] = "<a href=./view.php?user_id=$from_id>" . $from_id . "</a>があなたをフレンドに登録しました！";
    }

    //自分が参加しているグループ一覧を表示
    $group_name_items = array();
    $group_member_items = array();
    $sql = 'select * from group_list where (member1 = ?) or (member2 = ?) or (member3 = ?) or (member4 = ?) or (member5 = ?)';
    $stmt = $dbh->prepare($sql);
    for($i = 1; $i <= 5; $i++){
    	$stmt->bindValue($i, $user_id, PDO::PARAM_STR);
    }
    $flag = $stmt->execute();

    $group_id_items = array();
    foreach($stmt as $key =>$row){
    	$members[] = $row["member1"];
		$members[] = $row["member2"];
    	$members[] = $row["member3"];
    	$members[] = $row["member4"];
    	$members[] = $row["member5"];

    	$group_id_items[] = $row['group_id'];

    	$group_id = $row['group_id'];
    	//グループの名前の一覧を格納
    	$group_name_items[] = "<a href=./group_talk.php?group_id=$group_id>" . $row['group_name'] . '</a>';
    	for($i = 0; $i < 5; $i++){
    		if(!is_null($members[$i]))  {
    			//それぞれのグループメンバーの一覧を格納
	    		$group_member_items[$key][$i] = "<a href=./view.php?user_id=$members[$i]>" . $members[$i] . '</a>';
    		} else {
				$group_member_items[$key][$i] = NULL;
			}
    	}

    	//配列の初期化
    	$members = array();
    }

    //トークの通知を表示
    $talk_notif_items = array();
	$sql = 'SELECT from_id FROM notif WHERE to_id = ? AND read_flg = "midoku"';
	$stmt = $dbh->prepare($sql);
	$flag = $stmt->execute(array($user_id));

	foreach($stmt as $row){
		$from_id = $row['from_id'];
		$talk_notif_items[] = "<a href=./talk.php?to_id=$from_id>" . $from_id . "</a>からメッセージが届きました";
	}

	//グループのトーク通知を表示
	$group_talk_notif_items = array();
	$sql = 'select group_id from group_notif where user_id = ? and group_id = ? and read_flg = "midoku"';
	$stmt = $dbh->prepare($sql);
	for($i = 0; $i < count($group_id_items); $i++) {
		$flag = $stmt->execute(array($user_id, $group_id_items[$i]));
		$row = $stmt->fetch();
		if ($row) {
			$group_id = $row['group_id'];
			$sql = 'select group_name from group_list where group_id = ?';
			$stmt2 = $dbh->prepare($sql);
			$flag = $stmt2->execute(array($group_id));
			$row2 = $stmt2->fetch();
			$group_name = $row2['group_name'];
			$group_talk_notif_items[] = "<a href=./group_talk.php?group_id=$group_id>" . $group_name . "</a>に新着メッセージがあります";
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
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="../css/global_menu.css">
<link rel="stylesheet" type="text/css" href="../css/friend.css">
<title>コミュニティ</title>
</head>
<body>
<header>
<h1>コミュニティ</h1>
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
<b>ユーザー検索</b><br><br>
<form action="./search.php" method="get">
<input type="text" name="search_key">
<input type="submit" value="検索"><br><br>
</form>

<div class= "comu">
<div class="friend">

<h3>フレンド通知</h3>
	<?php for($i = 0; $i < count($friend_notif_items); $i++):?>

		<!-- フレンド通知 -->
		<?=$friend_notif_items[$i]?><br>

	<?php endfor;?>

	<!-- フレンド通知がないとき -->
	<?php if(empty($friend_notif_items)):?>
		通知なし<br>
	<?php endif;?>

<h3>フレンド一覧</h3>
<?php for($i = 0; $i < count($friend_id_items); $i++):?>

	<!-- フレンドID -->
	<?=$friend_id_items[$i]?>

	<!-- フレンドネーム -->
	<?=$friend_name_items[$i]?><br>

<?php endfor;?>

<!-- フレンドがいないとき -->
<?php if(empty($friend_id_items)):?>
	フレンドがいません<br>
<?php endif;?>

</div>

<div class="talk">
<h3>トーク通知</h3>
<?php for($i = 0; $i < count($talk_notif_items); $i++):?>

	<!-- トーク通知 -->
	<?=$talk_notif_items[$i]?><br>

<?php endfor;?>

<!-- トーク通知がないとき -->
<?php if(empty($talk_notif_items)):?>
	通知なし<br>
<?php endif;?>
</div>

<div class="group">

<h3>グループトーク通知</h3>
<?php for($i = 0; $i < count($group_talk_notif_items); $i++):?>

	<!-- グループトーク通知 -->
	<?=$group_talk_notif_items[$i]?><br>

<?php endfor;?>
<!-- グループトーク通知がないとき -->
<?php if(empty($group_talk_notif_items)):?>
	通知なし<br>
<?php endif;?>

<h3>グループ一覧</h3>
<?php for($i = 0; $i < count($group_name_items); $i++):?>

	<!-- グループ名 -->
	<?=$group_name_items[$i]?>

	(&nbsp;<?php for($j = 0; $j < count($group_member_items[$i]); $j++):?>

		<!-- グループメンバー -->
		<?php if(!is_null($group_member_items[$i][$j])):?>
			<?=$group_member_items[$i][$j]?>&nbsp;
		<?php endif;?>

	<?php endfor;?>)<br>

<?php endfor;?>

<!-- グループがないとき -->
<?php if(empty($group_name_items)):?>
	グループがありません<br>
<?php endif;?>

<br><a href="./create_group.html">グループを作成する</a><br>
</div>
</div><br>

<!-- フッター -->
<footer>
<p><small>&copy; 2017 codeDD</small></p>
</footer>
<!-- フッター終了 -->
</body>
</html>
