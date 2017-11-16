<?php
session_start();

//強制ログイン
$_SESSION['user_id'] = 'c_test';

header('location: ../home');
exit();