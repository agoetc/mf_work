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
<title></title>
</head>
<body>

</body>
</html>