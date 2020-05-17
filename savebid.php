<html>
<head>
<title>Thanks for updating your bids.</title>
</head>

<body>
<?php

// save an edited bid to the database

// bring in the variables
require ('./variables.php');
$price=htmlspecialchars($_POST['price']);
$investment=htmlspecialchars($_POST['investment']);
$id=htmlspecialchars($_POST['id']);

// make, check, and authenticate databse connection
require './database.php';

// what kind of user are they?  vc or startup?

$sql = "SELECT * FROM user WHERE id=".$user_id;
$result = $mysqli->query($sql);
$row=$result->fetch_assoc();
if ($row['vc']==1) {
	$updates = "price=".$price.", investment=".$investment;
	$sql = "UPDATE bid SET ".$updates." WHERE id=".$id;
	$mysqli->query($sql);
	echo "<form action='vc.php' method='POST'>";
} else {
	$updates = "price=".$price.", investment=".$investment.", counter=1";
	$sql = "UPDATE bid SET ".$updates." WHERE id=".$id;
	$mysqli->query($sql);
	echo "<form action='startup.php' method='POST'>";
}

echo "<input type=submit name='review_bids' value='review bids'>";
echo "<input type='hidden' name='event' value=$event>";
echo "<input type='hidden' name='user' value=$user>";
echo "<input type='hidden' name='user_id' value=$user_id>";
echo "<input type='hidden' name='password' value=$password>";
echo "</form>\n";


?>
</body>


</html>
