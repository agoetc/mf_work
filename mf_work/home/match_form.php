<?php
require "../php/lib/sanitizing.php";
require "../php/lib/connect_db.php";
date_default_timezone_set('Asia/Tokyo');

//まだログインしていないならトップページに飛ばす
session_start();
if(!isset($_SESSION['user_id'])){
	header('location: ../');
	exit();
}
$user_id = hsc($_SESSION['user_id']);
try{
	//mf_testに接続
	$dbh = connect_mf_test();

	$sql = 'DELETE FROM tag_rank WHERE tag_times % 2=0 AND tag_ymd<'.date('Ymd');
		$stmt = $dbh->prepare($sql);
		$flag = $stmt->execute();
		if(!$flag){
print_r($stmt->errorInfo());
			die();
		}
	$sql = 'select tag,tag_times from tag_rank where tag_times != 0 ORDER BY tag_times DESC';
    $stmt = $dbh->prepare($sql);
    $flag = $stmt->execute();
    // $tag_rank = $stmt->fetch();
    if(!$flag){
      die();
    }
  }catch(PDOException $e){
	print('Error:'.$e->getMessage());
	die();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../css/global_menu.css">
    <link rel="stylesheet" type="text/css" href="../css/match.css">
    <title>ランダムマッチフォーム</title>
  </head>
  <body>
    <h1>ランダムマッチフォーム</h1>
    <nav>
    <ul class="global_menu">
    <li><a href="./">ホーム</a></li>
    <li><a href="./friend.php">コミュニティ</a></li>
    <li><a href="./match_form.php">ランダムマッチ</a></li>
    <li><a href="./settings/">設定</a></li>
    <li><a href="../php/sign_out.php">ログアウト</a></li>
    </ul>
    </nav>
    <br>
    <meta charset="UTF-8">
    <form action="../php/match/match.php" method="post">
    <input type="text" name="tag">
    <input type="submit" name="submit" value="タグを入力">
    </form><br><br>
    <?php
        $i=1;
        foreach($stmt as $row){
          $tag = $row['tag'];
          $tag_times = $row['tag_times'];
          $wait = $tag_times % 2;
          echo '<div class="tag_rank">';
          echo $i++.'　'.$tag.'<br></div>';

          echo '<div class="match_info">';
          echo 'match　'.floor($tag_times/2);
          if($wait){
          echo '　wait</div>';
        }else{
          echo '</div>';
        }

          if($i == 11){
            break;
          }
        }
    ?>
<br>
<!-- フッター -->
<footer>
<p><small>&copy; 2017 codeDD</small></p>
</footer>
<!-- フッター終了 -->
  </body>
</html>
