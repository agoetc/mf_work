<?php
require "../mf_work/php/lib/sanitizing.php";
require "../mf_work/php/lib/connect_db.php";
session_start();
$user_id = hsc($_SESSION['user_id']);

if(!$img_name = hsc($_GET['img_name'])){
  $img_name = hsc($_SESSION['img_name']);
}
try
{
  $dbh = connect_mf_img();

  $sql = 'DELETE FROM img_fav WHERE user_id = ? AND img_name = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $stmt->bindValue(2, $img_name, PDO::PARAM_STR);
    $flag = $stmt->execute();
  if (!$flag){
    echo 'お気に入りの取り消しに失敗しました';
    die();
  }
  echo "お気に入りを取り消しました";


}catch(PDOException $e){
print('Error:'.$e->getMessage());
die();
}
