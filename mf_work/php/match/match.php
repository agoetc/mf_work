<?php
  session_start();
  require "../lib/sanitizing.php";
  date_default_timezone_set('Asia/Tokyo');

  if(isset($_POST['tag'])){
    $tag = hsc($_POST['tag']);
  }else{
    $tag = '';
  }
  $_SESSION['tag'] = $tag;
  if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
  }else {
	header('location: ../../');
    die();
  }
    try{
      $dsn = 'mysql:dbname=mf_test;host=127.0.0.1';
      $user = 'root';
      $password = '';
      $dbh = new PDO($dsn, $user, $password);

      //自分が既に登録されてあるか確認
      $sql = 'select user_id, tag from wait where user_id = ?';
        $stmt = $dbh->prepare($sql);
        $flag = $stmt->execute(array($user_id));
        if(!$flag){
          echo '接続に失敗しました';
          die();
        }
        $oldtag = $stmt->fetchColumn(1);
        if(empty($oldtag)){
          unset($_SESSION['oldtag']);
        }else{
          $_SESSION['oldtag'] = $oldtag;
          echo '既に登録されています。<br>登録タグ：'.$oldtag.'<br><a href="./match_die.php">取り消す</a><br><br>';
          die();
        }
      //相手のuser_idをもってくる
      $sql = 'SELECT min(wait_id) , user_id FROM wait WHERE tag = ? GROUP BY user_id';
        $stmt = $dbh->prepare($sql);
        $flag = $stmt->execute(array($tag));
        if (!$flag){
          echo 'データの取得に失敗しました';
          die();
        }
        $talk_par = $stmt->fetchColumn(1);
        $_SESSION['talk_par'] = $talk_par;
        if ($talk_par == NULL || $talk_par == $user_id){
          //相手がいないとき何もしない
        }else {
          if(empty($tag)){
          header('location: ./match_go.php');
          die();
          }
          //$talk_parとチャットを開始します';
          $sql = 'SELECT tag_times FROM tag_rank WHERE tag = ?';
          $stmt = $dbh->prepare($sql);
          $flag = $stmt->execute(array($tag));
          $row = $stmt->fetch();
          //登録されているならタグに+1をしてトーク画面へ飛ぶ
          if($row){
            $tag_times = $row["tag_times"] + 1;
            $sql = 'UPDATE tag_rank SET tag_times = ? WHERE tag = ?';
            $stmt = $dbh->prepare($sql);
            $flag = $stmt->execute(array($tag_times,$tag));
          	header('location: ./match_go.php');
          die();
        }
      }
        //タグがランキングにあるかチェック
      if($tag != ''){
        $sql = 'SELECT tag_times FROM tag_rank WHERE tag = ?';
        $stmt = $dbh->prepare($sql);
        $flag = $stmt->execute(array($tag));

        if (!$flag){
          echo 'データの取得に失敗しました';
          die();
        }
        $row = $stmt->fetch();

        if($row){
          //ある場合tag_timesを１増やす
          $tag_times = $row["tag_times"] + 1;
          $sql = 'UPDATE tag_rank SET tag_times = ? WHERE tag = ?';
          $stmt = $dbh->prepare($sql);
          $flag = $stmt->execute(array($tag_times,$tag));
          if (!$flag){
            echo 'データの更新に失敗しました';
            die();
          }
        }else{
          //データがないとき追加
          $sql = 'insert into tag_rank(tag,tag_ymd) values (?,?)';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1, $tag, PDO::PARAM_STR);
            $stmt->bindValue(2, date('Y-m-d'), PDO::PARAM_STR);
            $flag = $stmt->execute();
            if (!$flag){
              echo 'タグの登録に失敗<br>正しいタグを入力して下さい';
              echo '<META http-equiv="refresh" content="2; url=../../home/match_form.php">';
              die();
            }
        }
      }
      //user_idをインサート
      $sql = 'insert into wait(user_id,tag) values(?,?)';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
        $stmt->bindValue(2, $tag, PDO::PARAM_STR);
        $flag = $stmt->execute();
        if ($flag){
          echo 'ランダムマッチに登録しました';
          echo '<META http-equiv="refresh" content="1; url=../../home/match_form.php">';

        }else{
          if($talk_par == $user_id || $oldtag == ''){
            echo '既に登録されています。<br>登録タグ：'.$oldtag.'<br><a href="./match_die.php">取り消す</a><br><br>';
          }else{
            echo '登録に失敗しました';
            echo '<META http-equiv="refresh" content="1; url=../../home/match_form.php">';

          }
        }
      }catch (PDOException $e){
        print('Error:'.$e->getMessage());
   die();
}
