<?php
require "../../php/lib/sanitizing.php";
//まだログインしていないならトップページに飛ばす
session_start();

if(!isset($_SESSION['user_id']))
{
	header('location: ../../');
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>アカウント設定</title>
</head>
<body>
<form action="../../php/change_settings.php" method="post">
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
    $user_name = $user_items['user_name'];
    $email = $user_items['email'];
    if (isset($user_items['self_intro'])){
		$self_intro = $user_items['self_intro'];
		$self_intro = str_replace("<br>", "&#13;", $self_intro);
	}
}
catch(PDOException $e)
{
	print('Error:'.$e->getMessage());
	die();
}

echo "<input type=hidden name=settings_type value=account>";
echo "ユーザー名を入力: <input type=text name=user_name value=$user_name><br><br>";
echo "メールアドレスを入力: <input type=text name=email value=$email><br><br>";
echo "自己紹介欄: <br><br><textarea name=self_intro id=self_intro rows=18 cols=67>";
if (isset($self_intro)){
	echo $self_intro;
}
echo "</textarea><br><br>";

$stmt = null;
$dbh = null;
?>
<input type="submit" name="submit" value="変更">
</form>
</body>
</html>