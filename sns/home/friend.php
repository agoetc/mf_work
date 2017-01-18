<?php
require "../php/lib/sanitizing.php";
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
<title>友達リスト</title>
</head>
<body>
<h1>友達リスト</h1><br>
友達リクエストをする（<font color="red"><b>ユーザーID</b></font>を入力）<br><br>
<form action="../php/friend_request.php" method="post">
<input type="text" name="friend_id">
<input type="submit" name="submit" value="送信"><br><br>
</form>
<?php
$user_id = hsc($_SESSION['user_id']);

$dsn = 'mysql:dbname=mf_test;host=localhost';
$user = 'root';
$password = '';

try
{
	$dbh = new PDO($dsn, $user, $password);
	
	echo '接続に成功しました<br><br>';
	
	$sql = 'select friend_id from friend_list where user_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $flag = $stmt->execute();
    
    foreach($stmt as $row){
    	$friend_id = $row['friend_id'];
    	echo "<a href=./view.php?user_id=$friend_id>" . $friend_id . "</a>";
    	echo ' ';
    	$sql2 = 'select user_name from user_info where user_id = ?';
	    $stmt2 = $dbh->prepare($sql2);
	    $stmt2->bindValue(1, $friend_id, PDO::PARAM_STR);
	    $flag2 = $stmt2->execute();
	    $row2 = $stmt2->fetch();
	    echo "<a href=./talk.php?to_id=$friend_id>" . $row2['user_name'] . "&nbsp;と会話をする</a>";
	    echo '<br>';
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