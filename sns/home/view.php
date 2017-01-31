<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ユーザー情報</title>
</head>
<body>
<?php
require "../php/lib/sanitizing.php";
require "../php/lib/connect_db.php";

$user_id = hsc($_GET['user_id']);

try
{	
	//mf_imgに接続
	$dbh = connect_mf_img();
	
	$sql = 'select icon_name from icon where user_id = ?';
	$stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $flag = $stmt->execute();
    
    $row = $stmt->fetch();
    $icon_name = $row['icon_name'];
	
	//mf_testに接続
	$dbh = connect_mf_test();
	
	//ユーザーの情報一覧を取得
	$sql = 'select user_name, email, self_intro from user_info where user_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $flag = $stmt->execute();
    
    //ユーザーの情報一覧を表示
    $user_items = $stmt->fetch();
    echo "<img border=1 src=../../uploader/icon/{$icon_name} width=128 height=128 alt=user_icon title=user_icon><br>";
    echo $user_id . "<br>";
    echo $user_items['user_name'] . "<br>";
    echo $user_items['email'] . "<br><br>";
    if (isset($user_items['self_intro'])){
    	echo "自己紹介:<br><p><b>";
    	echo $user_items['self_intro'] . "</b></p>";
    }
    
    //フレンド登録ボタン
    $friend_id = $user_id;
    echo "<form action=../php/friend_request.php method=post>";
    echo "<input type=hidden name=friend_id value=$friend_id>";
    echo "<input type=submit value=フレンド登録></form>";
}
catch(PDOException $e)
{
	print('Error:'.$e->getMessage());
	die();
}

$stmt = null;
$dbh = null;
?>
</body>
</html>