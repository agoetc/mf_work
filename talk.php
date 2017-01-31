<?php
require "../php/lib/sanitizing.php";
session_start();
$from_id = hsc($_SESSION['user_id']);
$to_id = hsc($_GET['to_id']);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>トークページ</title>
<link rel="stylesheet" type="text/css" href="../css/textarea.css">
<!--
<script src="http://code.jquery.com/jquery-2.0.0.min.js"></script>
-->
<script src="../js/jquery-3.1.1.min.js"></script>
<script src="../js/resize.js"></script>
<script src="../js/talk.js"></script>
</head>
<body>
　<textarea id="message" onkeyup="resize();"></textarea>
<?php
echo "<input type=hidden id=from_id value=$from_id>";
echo "<input type=hidden id=to_id value=$to_id>";
?>
<button id="submit">発言</button><hr>
<div id="main"></div>
</body>
</html>