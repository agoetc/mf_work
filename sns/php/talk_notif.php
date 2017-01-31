<?php
require "./lib/sanitizing.php";
require "./lib/url.php";
require "./lib/connect_db.php";

$from_id = 'testes';
$to_id = 'testes4';
try
{
	//mf_testに接続
	$dbh = connect_mf_test();

	echo '接続に成功しました<br>';
	$sql = 'SELECT read_flg FROM notif WHERE from_id = ? AND to_id = ?';
		$stmt = $dbh->prepare($sql);
		$flag = $stmt->execute(array($from_id,$to_id));
		if (!$flag){
			echo 'データの取得に失敗しました';
			die();
		}
		// $row = $stmt->fetch();
		// $notif = $row['read_flg'];
		$notif = $stmt->fetchColumn(1);
		if(!is_null($notif)){
      $sql = 'UPDATE notif SET read_flg = 0 WHERE from_id = ? AND to_id = ?';
        $stmt = $dbh->prepare($sql);
        $flag = $stmt->execute(array($from_id,$to_id));
        if (!$flag){
          echo 'データの取得に失敗しました';
          die();
        }
			}else{
          $sql = 'insert into notif(from_id, to_id) values (?,?)';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1, $from_id, PDO::PARAM_STR);
            $stmt->bindValue(2, $to_id, PDO::PARAM_STR);
            $flag = $stmt->execute();
            if (!$flag){
              echo 'データの取得に失敗しました';
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
