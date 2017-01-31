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
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>友達リスト</title>
</head>
<body>
<h1>友達リスト</h1><br>
<b>ユーザー検索</b><br><br>
<form action="./search.php" method="get">
<input type="text" name="search_key">
<input type="submit" value="検索"><br><br>
</form>
<?php
$user_id = hsc($_SESSION['user_id']);

try
{
	//mf_testに接続
	$dbh = connect_mf_test();
	
	echo '接続に成功しました<br><br>';
	
	//自分がフレンド登録をしているユーザーの一覧を取得
	$sql = 'select friend_id from friend_list where user_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $flag = $stmt->execute();
    
    //フレンドのIDとユーザー名を表示
    foreach($stmt as $row){
    	$friend_id = $row['friend_id'];
    	echo "<a href=./view.php?user_id=$friend_id>" . $friend_id . "</a>";
    	echo ' ';
    	$sql = 'select user_name from user_info where user_id = ?';
	    $stmt = $dbh->prepare($sql);
	    $stmt->bindValue(1, $friend_id, PDO::PARAM_STR);
	    $flag = $stmt->execute();
	    $row = $stmt->fetch();
	    echo "<a href=./talk.php?to_id=$friend_id>" . $row['user_name'] . "&nbsp;と会話をする</a>";
	    echo '<br>';
    }
    
    //誰かが自分をフレンド登録したら通知する
    echo '<br>========通知========<br><br>';
    $sql = 'select from_id from friend_notification where to_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $flag = $stmt->execute();
    
    foreach($stmt as $row){
    	$from_id = $row['from_id'];
    	echo "<a href=./view.php?user_id=$from_id>" . $from_id . "</a>があなたをフレンドに登録しました！<br>";
    }
    
    //自分が参加しているグループ一覧を表示
    echo '<br>=======グループ一覧=======<br><br>';
    $sql = 'select * from group_list where (member1 = ?) or (member2 = ?) or (member3 = ?) or (member4 = ?) or (member5 = ?)';
    $stmt = $dbh->prepare($sql);
    for($i = 1; $i <= 5; $i++){
    	$stmt->bindValue($i, $user_id, PDO::PARAM_STR);
    }
    $flag = $stmt->execute();
    
    foreach($stmt as $row){
    	for($i = 1; $i <= 5; $i++){
    		$members[] = $row["member$i"];
    	}
    	$group_id = $row['group_id'];
    	echo "<a href=./group_talk.php?group_id=$group_id>" . $row['group_name'] . '</a>( ';
    	for($i = 0; $i < 5; $i++){
    		if(!empty($members[$i])) echo "<a href=./view.php?user_id=$members[$i]>" . $members[$i] . '</a> ';
    	}
    	echo ')<br>';
    	
    	//配列の初期化
    	$members = array();
    }
    
    echo '<br><a href="./create_group.html">グループを作成する</a><br>';
}
catch(PDOException $e)
{
	print('Error:'.$e->getMessage());
	die();
}

$stmt = null;
$dbh = null;
?>
</body>
</html>