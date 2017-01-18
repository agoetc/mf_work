<?php
require "./lib/sanitizing.php";
session_start();

$dsn = 'mysql:dbname=mf_test;host=localhost';
$user = 'root';
$password = '';

$from_id = hsc($_SESSION['user_id']);
$to_id = hsc($_GET['to_id']);

try
{
	$dbh = new PDO($dsn, $user, $password);
	
	$sql = "select * from talk_data where (from_id='$from_id' and to_id='$to_id') or (from_id='$to_id' and to_id='$from_id') order by num desc";
    $stmt = $dbh->prepare($sql);
    $flag = $stmt->execute();
    foreach($stmt as $row){
    	$json[] = $row;
    }
    
    if(isset($json)){
    	echo json_encode($json);
    }
}
catch(PDOException $e)
{
	print('Error:'.$e->getMessage());
	die();
}

$stmt = null;
$dbh = null;