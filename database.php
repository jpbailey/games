<?php
$config = parse_ini_file('./private/config.ini');

$mysqli = new mysqli($config['servername'], $config['username'],
	$config['password'], $config['dbname']);

if ($mysqli->connect_error) {
	die("Connection failed: " . $mysqli->connect_error);
}

$sql="SELECT password FROM user WHERE id=".$user_id;
$status_query=$mysqli->query($sql);
$status_row=$status_query->fetch_assoc();

if ($status_row['password']!=$password) {
	die("login failure");
}
?>

