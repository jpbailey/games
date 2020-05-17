<html>

<head>
<title>just a test</title>
</head>

<body>
Trying to just process an update and staying on the same page.

<form action="" method="POST">
<input type="text" name="id"/><br>
<input type="text" name="name"/><br>
<input type="submit" name="update" value="update data"/>
</form>

<?php

$config = parse_ini_file('./private/config.ini');

$mysqli = new mysqli($config['servername'], $config['username'],
        $config['password'], $config['dbname']);

if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
        }


if ($status_row['password']!=$password) {
	die("login failure");
}

if(isset($_POST['update'])) {
	$id = $_POST['id'];
	$user = $_POST['name'];
//	$sql = "INSERT testing SET user='$id', name='$user'";
	$sql = "UPDATE testing SET user='$id', name='$user' WHERE id=3";
	$update_query=$mysqli->query($sql);
	echo $id;
	echo "<br>";
	echo $user;
}
?>

</body>

</html>
