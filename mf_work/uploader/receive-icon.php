<?php
session_start();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>receive-icon</title>
</head>
<body>
<p>
<?php
require "../mf_work/php/lib/connect_db.php";
//user_idもらってくる
$user_id = $_SESSION['user_id'];

//ファイルあるか確認
if (!is_uploaded_file($_FILES['upfile']['tmp_name'])) {
	echo 'ファイルが見つかりませんでした';
	die();
}

	//形式チェック
	$type = @exif_imagetype($_FILES['upfile']['tmp_name']);

	if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
		echo '未対応のファイルです';
		die();
	}
		$filename = $_FILES["upfile"]["name"];
		//拡張子取得
		$ext = substr($filename, strrpos($filename, '.'));
		try{
			//mf_imgに接続
			$dbh = connect_mf_img();
			$sql = 'insert into icon(user_id,icon_ext,icon_name) values(?,?,?)';
			$stmt = $dbh->prepare($sql);
			$stmt->bindValue(1, $user_id, PDO::PARAM_STR);
			$stmt->bindValue(2, $ext, PDO::PARAM_STR);
			$stmt->bindValue(3, $user_id.$ext, PDO::PARAM_STR);
			$flag = $stmt->execute();

			if ($flag) {
				echo 'データの追加に成功しました<br>';
			}else{
				$sql = 'select icon_name from icon where user_id = ?';
				$stmp = $dbh->prepare($sql);
				$stmp->bindParam(1, $user_id, PDO::PARAM_STR);
				$stmp->execute();

				$oldicon_name = $stmp->fetchColumn(0);

				if(unlink('./icon/'.$oldicon_name)){
				} else {
					echo '削除失敗';
				}

				$sql = 'update icon set icon_ext = ?, icon_name = ? where user_id = ?';
				$stmt = $dbh->prepare($sql);
				$stmt->bindValue(1, $ext, PDO::PARAM_STR);
				$stmt->bindValue(2, $user_id.$ext, PDO::PARAM_STR);
				$stmt->bindValue(3, $user_id, PDO::PARAM_STR);
				$flag = $stmt->execute();

				if ($flag) {
					echo 'データの更新に成功しました<br>';
				} else {
					echo 'データの更新に失敗しました<br>';
				}
			}
			//ファイルアップロード
			if (move_uploaded_file($_FILES["upfile"]["tmp_name"], "./icon/cushion".$filename)) {
				//ファイル権限変更
				chmod('./icon/cushion'.$filename, 0777);

				if(rename ('./icon/cushion'.$filename, './icon/'.$user_id.$ext)) {
					echo $filename . "をアップロードしました。";
				} else {
					echo 'ファイル名の変更に失敗しました';
				}
			} else {
				echo 'ファイルのアップロードに成功しました';
			}

			//データベースを閉じる
			$dbh = NULL;
		//mysql接続例外
		}catch (PDOException $e){
			print('Error:'.$e->getMessage());
			die();
		}


?>
</p>
</body>
</html>
