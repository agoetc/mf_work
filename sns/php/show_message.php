<?php
require "./lib/sanitizing.php";
require "./lib/connect_db.php";
session_start();

$from_id = hsc($_SESSION['user_id']);
$to_id = hsc($_GET['to_id']);

try
{
	//mf_testに接続
	$dbh = connect_mf_test();
		//データがあるとき更新
		$sql = 'UPDATE notif SET read_flg = "kidoku" WHERE from_id = ? AND to_id = ?';
		$stmt = $dbh->prepare($sql);
		$flag = $stmt->execute(array($to_id,$from_id));

		if (!$flag){
			echo 'データの更新に失敗しました';
			die();
		}

	$sql = "select * from talk_data where (from_id='$from_id' and to_id='$to_id') or (from_id='$to_id' and to_id='$from_id') order by num desc";
    $stmt = $dbh->prepare($sql);
    $flag = $stmt->execute();
    foreach($stmt as $row){
    	$json[] = $row;
    }

    if(isset($json)){
    	echo json_encode($json);
    }
		$sql = 'SELECT read_flg FROM notif WHERE from_id = ? AND to_id = ?';
		$stmt = $dbh->prepare($sql);
		$flag = $stmt->execute(array($from_id,$to_id));

		if (!$flag){
			echo 'データの取得に失敗しました';
			die();
		}

}
catch(PDOException $e)
{
	print('Error:'.$e->getMessage());
	die();
}

$stmt = null;
$dbh = null;
