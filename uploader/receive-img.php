<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>receive-img</title>
</head>
<body>
<p><?php

date_default_timezone_set('Asia/Tokyo');
$img_d = date('y-m-d');

if (is_uploaded_file($_FILES['upfile']['tmp_name'])) {
  //形式チェック
  $type = @exif_imagetype($_FILES['upfile']['tmp_name']);
  if (in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {

    $filename = $_FILES["upfile"]["name"];
    //拡張子取得
    $ext = substr($filename, strrpos($filename, '.'));
    try{

    $dsn = 'mysql:dbname=mf_img;host=127.0.0.1';
    $user = 'user';
    $password = 'aaa';
    $dbh = new PDO($dsn, $user, $password);
    //画像追加
    $sql = 'insert into imgs(img_d) values(?)';
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(1, $img_d, PDO::PARAM_STR);
      $flag = $stmt->execute();
      if ($flag){
        echo 'データの追加に成功しました<br>';
      }else{
        echo 'データの追加に失敗しました<br>';
      }
    //最新の主キーを取得
    $id = $dbh->lastInsertId();
    $id = $id + 1;
    //ファイルアップロード
    if (move_uploaded_file($_FILES["upfile"]["tmp_name"], "./img/cushion".$filename)){
      //ファイル権限変更
      chmod('./img/cushion'.$filename, 0644);
      //ファイル名をlastInsertIdに変更、クッションページから移動
      if(rename ('./img/cushion'.$filename, './img/'.$id.$ext)){
      echo 'http://localhost/uploader/img/'.$id;
          }else{
            echo 'アップロードエラー';
          }
          }else {
            echo 'アップロードに失敗しました';
          }
          $dbh = NULL;
        }catch (PDOException $e){
          print('Error:'.$e->getMessage());
          die();
        }
      }else {
        echo '未対応のファイルです';
      }
}else{
  echo 'ファイルが見つかりませんでした';
}

?>
</p>
</body>
</html>
