<?php
//セッションを破棄してトップページに飛ばす
session_start();

$_SESSION = array(); 
session_destroy();

header('location: ../');
exit();