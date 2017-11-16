<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>receive-img</title>
</head>
<body>
<p>
<?php
require "../mf_work/php/lib/connect_db.php";
date_default_timezone_set('Asia/Tokyo');
$img_d = date('y-m-d');

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

			$sql = 'insert into imgs(img_d) values(?)';
			$stmt = $dbh->prepare($sql);
			$stmt->bindValue(1, $img_d, PDO::PARAM_STR);
			$flag = $stmt->execute();

			if ($flag) {
				echo 'データの追加に成功しました<br>';
			} else {
				echo 'データの追加に失敗しました<br>';
			}

			//最新の主キーを取得
			$id = $dbh->lastInsertId();

			//ファイルアップロード
			if (!move_uploaded_file($_FILES["upfile"]["tmp_name"], "./img/cushion".$filename)){
				echo 'アップロードに失敗しました';
				die();
			}
				//ファイル権限変更
				chmod('./img/cushion'.$filename, 0644);
				//ファイル名をlastInsertIdに変更、クッションページから移動
			if (!rename ('./img/cushion'.$filename, './img/'.$id.$ext)) {
				echo 'アップロードに失敗しました';
				die();
			}
			$sql = 'update imgs set img_name = ? where img_id = ?';
			$stmt = $dbh->prepare($sql);
			$stmt->bindValue(1, $id.$ext, PDO::PARAM_STR);
			$stmt->bindValue(2, $id, PDO::PARAM_STR);
			$flag = $stmt->execute();
			if (!$flag) {
				echo 'データの追加に失敗しました<br>';
			}
					echo "<br><a href=./img/{$id}{$ext}>画像</a>";


			//データベースを閉じる
			$dbh = NULL;
		}catch (PDOException $e){
			print('Error:'.$e->getMessage());
			die();
		}

		echo "<br><a href=./fav.php?img_name={$id}{$ext}>お気に入りに登録する</a><br>";

?>
</p>

</body>
</html>
