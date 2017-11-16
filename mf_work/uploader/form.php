<?php
require "../php/lib/sanitizing.php";
require "../php/lib/connect_db.php";
session_start();
$user_id = hsc($_SESSION['user_id']);
try
{
	//mf_testに接続
  $dbh = connect_mf_img();

  if(!$i=$_GET["i"]){
  $sql = 'select img_name from imgs order by img_id desc LIMIT 10';
    $stmt = $dbh->prepare($sql);
    $flag = $stmt->execute();
    if(!$flag){
      print_r($stmt->errorInfo());
      echo '接続に失敗しました';
    }
  }else{
    $sql = 'select img_name from imgs order by img_id desc LIMIT '.$i.', 10';
      $stmt = $dbh->prepare($sql);
      $flag = $stmt->execute();
      if(!$flag){
        print_r($stmt->errorInfo());
        echo '接続に失敗しました';
      }
  }

  }catch(PDOException $e){
  print('Error:'.$e->getMessage());
  die();
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../mf_work/css/uploader.css">
<link rel="stylesheet" type="text/css" href="../mf_work/css/global_menu.css">
<title>アップローダー</title>
</head>
<body>
  <header>
  <h1>アップローダー</h1>
  <nav>
  <ul class="global_menu">
  <li><a href="./">ホーム</a></li>
  <li><a href="../mf_work/home/friend.php">友達リスト</a></li>
  <li><a href="../mf_work/home/match_form.php">ランダムマッチ</a></li>
  <li><a href="../mf_work/home/settings">設定</a></li>
  <li><a href="../mf_work/php/sign_out.php">ログアウト</a></li>
  </ul>
  </nav>
  </header><br>


<div class="img_form">
<form action="receive-img.php" method="post" enctype="multipart/form-data">
  画像：<br />
  <input type="file" name="upfile"/><br />
  <br />
  <input type="submit" value="アップロード" />
</form>

<form action="receive-icon.php" method="post" enctype="multipart/form-data">
  アイコン：<br />
  <input type="file" name="upfile"/><br />
  <br />
  <input type="submit" value="アップロード" />
</form>
</div>
  <br />

<?php
//最新１０件の場合
if(!isset($_GET["i"]) || $i == 0){
  echo '最新10件';
}else {
  echo "<a href=./form.php?i=".($i-10).">前の１０件</a>";
}
if(!isset($_GET["i"]))$i=0;
foreach($stmt as $row){
  $i++;
  $img_name = $row['img_name'];
  echo '<div class="img_fav">';
  echo "<a href=./preview.php?img_name={$img_name}><img border=2 src=./img/{$img_name} width=200px height=auto alt={$img_name}title=img_name></a></div><br>";
}
if($i % 10 ==0){
  echo "<a href=./form.php?i=".$i.">次の１０件</a>";
}else {
  echo 'これ以上データがありません';
}
 ?>
 <br>
 </form>
</body>
</html>
