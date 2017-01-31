<?php
function connect_mf_test() {
$dsn = 'mysql:dbname=mf_test;host=127.0.0.1';
$user = 'root';
$password = '';

return new PDO($dsn, $user, $password);
}

function connect_mf_img() {
$dsn = 'mysql:dbname=mf_img;host=127.0.0.1';
$user = 'root';
$password = '';

return new PDO($dsn, $user, $password);
}