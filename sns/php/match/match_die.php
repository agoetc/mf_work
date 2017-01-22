<?php
session_start();
$user_id = $_SESSION['user_id'];

try{
  $dsn = 'mysql:dbname=mf_test;host=127.0.0.1';
  $user = 'root';
  $password = '';
  $dbh = new PDO($dsn, $user, $password);

  $sql = 'DELETE FROM wait WHERE wait.user_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $flag = $stmt->execute();
    if ($flag){
      echo '取り消しに成功しました';
    }else{
      echo '取り消しに失敗しました';
    }
  }catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
  }
