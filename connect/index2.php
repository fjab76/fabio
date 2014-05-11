<?php
//connect to MySQL
$db = mysql_connect('localhost', 'root', 'admin') or 
    die ('Unable to connect. Check your connection parameters.');

//create the main database if it doesn't already exist
$query = 'SET NAMES "utf8"';
mysql_query($query, $db) or die(mysql_error($db));

$output = 'Database connection established.';
include 'output.html.php';