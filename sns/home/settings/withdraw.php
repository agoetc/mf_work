<?php
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
<title>退会</title>
</head>
<body>
<form action="../../php/change_settings.php" method="post">
<?php
echo "<input type=hidden name=settings_type value=withdraw>";
?>
<input type="submit" name="submit" value="退会">
</form>
</body>
</html>