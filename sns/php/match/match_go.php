<?php
session_start();
$from_id = $_SESSION['user_id'];
$to_id = $_SESSION['talk_par'];

try{
  $dsn = 'mysql:dbname=mf_test;host=127.0.0.1';
  $user = 'root';
  $password = '';
  $dbh = new PDO($dsn, $user, $password);

  $sql = 'DELETE FROM wait WHERE wait.user_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $from_id, PDO::PARAM_STR);
    $flag = $stmt->execute();
    if (!$flag){
      echo 'データの削除に失敗しました';
      die();
    }
    $sql = 'DELETE FROM wait WHERE wait.user_id = ?';
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(1, $to_id, PDO::PARAM_STR);
      $flag = $stmt->execute();
      if (!$flag){
        echo 'データの削除に失敗しました';
        die();
      }

    header('location: ../../home/talk.php?to_id='.$to_id);
    exit();

  }catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}
