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
$bid_id=htmlspecialchars($_POST['id']);

// make, check, and authenticate databse connection
require './database.php';

// what kind of user are they?  vc or startup?

$sql = "SELECT * FROM user WHERE id=".$user_id;
$result = $mysqli->query($sql);
$row=$result->fetch_assoc();
if ($row['vc']==1) {
	$updates = "price=".$price.", investment=".$investment;
	$sql = "UPDATE bid SET ".$updates." WHERE id=".$bid_id;
	$mysqli->query($sql);
	echo "<form action='vc.php' method='POST'>";
} else {
	$updates = "price=".$price.", investment=".$investment.", counter=1";
	$sql = "UPDATE bid SET ".$updates." WHERE id=".$bid_id;
	$mysqli->query($sql);
	echo "<form action='startup.php' method='POST'>";
}

echo "<input type=submit name='review_bids' value='review bids'>";
require ('./sendvars.php');
echo "</form>\n";


?>
</body>


</html>
