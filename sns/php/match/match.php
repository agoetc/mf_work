<?php

  session_start();
  if(isset($_POST['tag'])){
    $tag = $_POST['tag'];
    $_SESSION['tag'] = $tag;
  }else{
    $tag = '';
  }

  if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
  }else {
    echo '不正なアクセスです';
    die();
  }

    try{
      $dsn = 'mysql:dbname=mf_test;host=127.0.0.1';
      $user = 'root';
      $password = '';
      $dbh = new PDO($dsn, $user, $password);
      //相手のuser_idをもってくる

      $sql = 'SELECT min(wait_id) , user_id FROM wait WHERE tag = ? GROUP BY user_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
        $flag = $stmt->execute(array($tag));
        if (!$flag){
          echo 'データの取得に失敗しました';
          die();
        }
        $talk_par = $stmt->fetchColumn(1);
        $_SESSION['talk_par'] = $talk_par;

        if ($talk_par == NULL || $talk_par == $user_id){
          //相手がいるとき
        }else {
          //$talk_parとチャットを開始します';
          include 'match_go.php';
          die();
        }
      //user_idをインサート
      $sql = 'insert into wait(user_id,tag) values(?,?)';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
        $stmt->bindValue(2, $tag, PDO::PARAM_STR);
        $flag = $stmt->execute();
        if ($flag){
          echo 'ランダムマッチに登録しました';
        }else{
          if($talk_par == $user_id){
            echo '既に登録されています。<a href="./match_die.php">取り消す</a><br><br>';
          }else{
            echo '登録に失敗しました';
          }
        }
      }catch (PDOException $e){
        print('Error:'.$e->getMessage());
   die();
}
