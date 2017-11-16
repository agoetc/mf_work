<?php
require "../lib/connect_db.php";
session_start();
$user_id = $_SESSION['user_id'];

try{
  $dbh = connect_mf_test();

  //自分が既に登録されてあるか確認
  $sql = 'select user_id from wait where user_id = ?';
    $stmt = $dbh->prepare($sql);
    $flag = $stmt->execute(array($user_id));
    if(!$flag){
      echo '接続に失敗しました';
      die();
    }
    $row = $stmt->fetch();

    if($row){
  $sql = 'DELETE FROM wait WHERE wait.user_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $flag = $stmt->execute();
    if ($flag){
      echo '取り消しに成功しました';
      echo '<META http-equiv="refresh" content="1; url=../../home/match_form.php">';
    }else{
      echo '取り消しに失敗しました';
    }
    if(isset($_SESSION['oldtag'])){
    $oldtag = $_SESSION['oldtag'];
    $sql = 'SELECT tag_times FROM tag_rank WHERE tag = ?';
    $stmt = $dbh->prepare($sql);
    $flag = $stmt->execute(array($oldtag));

    if (!$flag){
      echo 'データの取得に失敗しました';
      die();
    }
    $row = $stmt->fetch();
    if($row){
      $tag_times = $row["tag_times"] - 1;
      //データがあるとき更新
      $sql = 'UPDATE tag_rank SET tag_times = ? WHERE tag = ?';
      $stmt = $dbh->prepare($sql);
      $flag = $stmt->execute(array($tag_times,$oldtag));
      if (!$flag){
        echo 'データの更新に失敗しました';
        die();
      }
    }
  }
}else {
  echo '登録されていません';
}
  }catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
  }
