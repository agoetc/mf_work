<?php
require "../php/lib/sanitizing.php";
session_start();
$from_id = hsc($_SESSION['user_id']);
$group_id = hsc($_GET['group_id']);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>グループトークページ</title>
<link rel="stylesheet" type="text/css" href="../css/textarea.css">
<script src="../js/jquery-3.1.1.min.js"></script>
<script src="../js/resize.js"></script>
<script src="../js/group_talk.js"></script>
</head>
<body>
　<textarea id="message" onkeyup="resize();"></textarea>
<?php
echo "<input type=hidden id=from_id value=$from_id>";
echo "<input type=hidden id=group_id value=$group_id>";
?>
<button id="submit">発言</button><hr>
<div id="main"></div>
</body>
</html>