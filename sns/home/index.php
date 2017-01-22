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
<title>ホーム画面</title>
</head>
<body>
<h1>ホーム画面</h1><br>
<a href="./settings">設定</a><br><br>
<a href="./friend.php">友達リスト</a><br><br>
<a href="../php/match/match_form.html">ランダムマッチ</a><br><br>
<a href="../php/sign_out.php">ログアウト</a><br><br>
<?php
$user_id = hsc($_SESSION['user_id']);

$dsn = 'mysql:dbname=mf_test;host=localhost';
$user = 'root';
$password = '';

try
{
	$dbh = new PDO($dsn, $user, $password);
	
	echo '接続に成功しました<br><br>';
	
	$sql = 'select user_name, email, self_intro from user_info where user_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $flag = $stmt->execute();
    
    $user_items = $stmt->fetch();
    echo $user_id . "<br>";
    echo $user_items['user_name'] . "<br>";
    echo $user_items['email'] . "<br><br>";
    if (isset($user_items['self_intro'])){
    	echo "自己紹介:<br><p><b>";
    	echo $user_items['self_intro'] . "</b></p>";
    }

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
