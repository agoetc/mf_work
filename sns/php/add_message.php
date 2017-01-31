<?php
require "./lib/sanitizing.php";
require "./lib/url.php";
require "./lib/connect_db.php";

$from_id = hsc($_POST['from_id']);
$to_id = hsc($_POST['to_id']);
$message = hsc($_POST['message']);
//URLの文字列をリンクにする
$message = url_to_anchor($message);
$message = str_rp("<br>", $message);

try
{
	//mf_testに接続
	$dbh = connect_mf_test();

	echo '接続に成功しました<br>';

	$sql = 'insert into talk_data (from_id, to_id, message) values (?, ?, ?)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $from_id, PDO::PARAM_STR);
    $stmt->bindValue(2, $to_id, PDO::PARAM_STR);
    $stmt->bindValue(3, $message, PDO::PARAM_STR);
    $flag = $stmt->execute();

    if ($flag){
    	echo 'データの追加に成功しました<br>';
    }else{
    	echo 'データの追加に失敗しました<br>';
    }
		$sql = 'SELECT read_flg FROM notif WHERE from_id = ? AND to_id = ?';
		$stmt = $dbh->prepare($sql);
		$flag = $stmt->execute(array($from_id,$to_id));

		if (!$flag){
			echo 'データの取得に失敗しました';
			die();
		}
		$row = $stmt->fetch();

		if($row){
			//データがあるとき更新
			$sql = 'UPDATE notif SET read_flg = "midoku" WHERE from_id = ? AND to_id = ?';
			$stmt = $dbh->prepare($sql);
			$flag = $stmt->execute(array($from_id,$to_id));

			if (!$flag){
				echo 'データの更新に失敗しました';
				die();
			}
		}else{
			//データがないとき追加
			$sql = 'insert into notif(from_id, to_id, read_flg) values (?, ?, "midoku")';
			$stmt = $dbh->prepare($sql);
			$stmt->bindValue(1, $from_id, PDO::PARAM_STR);
			$stmt->bindValue(2, $to_id, PDO::PARAM_STR);
			$flag = $stmt->execute();
			if (!$flag){
				echo 'データの追加に失敗しました';
				die();
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
