<?php

include $_SERVER['DOCUMENT_ROOT'] . '/constants.php';

try
{
	$pdo = new PDO("mysql:host=".HOST.";dbname=".DBNAME,USER,PWD);
	//$pdo = new PDO("mysql:host=localhost;dbname=orders",'root','admin');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->exec('SET NAMES "utf8"');
}
catch (PDOException $e)
{
	$output = 'Unable to connect to the database server, ' . $e->getMessage();
	include 'error.html.php';
	exit();
}

?>
