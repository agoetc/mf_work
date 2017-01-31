<?php
require "../php/lib/sanitizing.php";
require "../php/lib/connect_db.php";
//まだログインしていないならトップページに飛ばす
session_start();

if(!isset($_SESSION['user_id']))
{
	header('location: ../');
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>検索結果</title>
</head>
<body>
<?php
$user_id = hsc($_SESSION['user_id']);

try
{	
	//mf_testに接続
	$dbh = connect_mf_test();
	
	//検索対象の文字がユーザー情報のいずれかに含まれているなら表示する
	$search_key = '%' . hsc($_GET['search_key']) . '%';
	
	$sql = 'select user_id, user_name, email, self_intro from user_info where (user_id like ?) or (user_name like ?) or (email like ?) or (self_intro like ?)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $search_key, PDO::PARAM_STR);
    $stmt->bindValue(2, $search_key, PDO::PARAM_STR);
    $stmt->bindValue(3, $search_key, PDO::PARAM_STR);
    $stmt->bindValue(4, $search_key, PDO::PARAM_STR);
    $flag = $stmt->execute();

	//mf_imgに接続
	$dbh = connect_mf_img();
    
    foreach($stmt as $user_items){
		$sql = 'select icon_name from icon where user_id = ?';
		$stmt2 = $dbh->prepare($sql);
		$stmt2->bindValue(1, $user_items['user_id'], PDO::PARAM_STR);
		$flag = $stmt2->execute();

		$row = $stmt2->fetch();
		$icon_name = $row['icon_name'];
		
    	echo "<img border=1 src=../../uploader/icon/{$icon_name} width=128 height=128 alt=user_icon title=user_icon><br>";
	    echo $user_items['user_id'] . "<br>";
	    echo $user_items['user_name'] . "<br>";
	    echo $user_items['email'] . "<br><br>";
	    if (isset($user_items['self_intro'])){
	    	echo "自己紹介:<br><p><b>";
	    	echo $user_items['self_intro'] . "</b></p>";
	    }
	    $friend_id = $user_items['user_id'];
	    echo "<form action=../php/friend_request.php method=post>";
	    echo "<input type=hidden name=friend_id value=$friend_id>";
	    echo "<input type=submit value=フレンド登録></form>";
	    echo '<hr>';
    }

}
catch(PDOException $e)
{
	print('Error:'.$e->getMessage());
	die();
}

$stmt = null;
$stmt2 = null;
$dbh = null;
?>
</body>
</html>