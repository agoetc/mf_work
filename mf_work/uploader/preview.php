<?php
require "../mf_work/php/lib/sanitizing.php";
require "../mf_work/php/lib/connect_db.php";
session_start();
$user_id = hsc($_SESSION['user_id']);
$img_name = hsc($_GET['img_name']);
$_SESSION['img_name'] = $img_name;

$dbh = connect_mf_img();
$sql = 'select fav_id from img_fav where user_id= ? and img_name = ?';
	$stmt = $dbh->prepare($sql);
	$stmt->bindValue(1, $user_id, PDO::PARAM_STR);
  $stmt->bindValue(2, $img_name, PDO::PARAM_STR);
	$flag = $stmt->execute();
	if(!$flag){
		print_r($stmt->errorInfo());
		echo '接続に失敗しました';
	}
  $row = $stmt->fetch();
  if($row) {
    $fav_flag = TRUE;
  }
 ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../mf_work/css/uploader.css">
    <link rel="stylesheet" type="text/css" href="../mf_work/css/global_menu.css">
    <title></title>
  </head>
  <body>
    <header>
    <h1>アップローダー</h1>
    <nav>
    <ul class="global_menu">
    <li><a href="../mf_work/home">ホーム</a></li>
    <li><a href="../mf_work/home/friend.php">友達リスト</a></li>
    <li><a href="../mf_work/home/match_form.php">ランダムマッチ</a></li>
    <li><a href="../mf_work/home/settings">設定</a></li>
    <li><a href="../mf_work/php/sign_out.php">ログアウト</a></li>
    </ul>
    </nav>
    </header><br>
<?php if(!$fav_flag):?>
    <form action=fav.php method="get">
    <input type="submit" value="お気に入りに登録する">
<?php else : ?>
    <form action=fav_die.php method="get">
    <input type="submit" value="お気に入りを取り消す">
<?php endif;?>
    <br>
    <br>
    </form>
    <?php
      echo "<img src=./img/$img_name width=auto height=auto alt={$img_name}title=img_name>";
    ?>
  </body>
</html>
