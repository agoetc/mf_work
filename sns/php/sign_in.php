<?php
require "./lib/sanitizing.php";
//セッションにユーザーIDを格納してホーム画面に飛ばす
session_start();

$user_id = hsc($_POST['user_id']);

$_SESSION['user_id'] = $user_id;

header('location: ../home');
exit();