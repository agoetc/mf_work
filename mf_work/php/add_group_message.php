<?php
require "./lib/sanitizing.php";
require "./lib/url.php";
require "./lib/connect_db.php";

$from_id = hsc($_POST['from_id']);
$user_id = $from_id;
$group_id = hsc($_POST['group_id']);
$message = hsc($_POST['message']);
//URLの文字列をリンクにする
$message = url_to_anchor($message);
$message = str_rp("<br>", $message);

try
{
	//mf_testに接続
	$dbh = connect_mf_test();
	
	echo '接続に成功しました<br>';
	
	$sql = 'insert into group_talk_data (from_id, group_id, message) values (?, ?, ?)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $from_id, PDO::PARAM_STR);
    $stmt->bindValue(2, $group_id, PDO::PARAM_STR);
    $stmt->bindValue(3, $message, PDO::PARAM_STR);
    $flag = $stmt->execute();
    
    if ($flag){
    	echo 'データの追加に成功しました<br>';
    }else{
    	echo 'データの追加に失敗しました<br>';
    }
    
    $sql = 'select * from group_list where group_id = ?';
    $stmt = $dbh->prepare($sql);
    $flag = $stmt->execute(array($group_id));
    $row = $stmt->fetch();
    $members[] = $row['member1'];
    $members[] = $row['member2'];
    $members[] = $row['member3'];
    $members[] = $row['member4'];
    $members[] = $row['member5'];
    
	foreach($members as $member) {
		$sql = 'SELECT read_flg FROM group_notif WHERE user_id = ? AND group_id = ?';
		$stmt = $dbh->prepare($sql);
		$flag = $stmt->execute(array($member, $group_id));
		$row = $stmt->fetch();

		if($row){
			//データがあるとき更新
			$sql = 'UPDATE group_notif SET read_flg = "midoku" WHERE user_id = ? AND group_id = ?';
			$stmt = $dbh->prepare($sql);
			$flag = $stmt->execute(array($member, $group_id));

		}else{
			//データがないとき追加
			$sql = 'insert into group_notif(user_id, group_id, read_flg) values (?, ?, "midoku")';
			$stmt = $dbh->prepare($sql);
			$stmt->bindValue(1, $member, PDO::PARAM_STR);
			$stmt->bindValue(2, $group_id, PDO::PARAM_STR);
			$flag = $stmt->execute();
		}
	}

}
catch(PDOException $e)
{
	print('Error:'.$e->getMessage());
	die();
}

$stmt = null;
$dbh = null;