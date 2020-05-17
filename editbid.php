<html>
<head>
<title>Thanks for updating your bids.</title>
<link rel="stylesheet" href="./styles.css">
</head>

<body>
<?php
// take a look at the bids that are coming in, read the correct
// new values, update the database, give them the option of returning
// to the previous submitbid.php page

// set the environment
require ('./environment.php');

// bring in variables
require ('./variables.php');
$id = substr(key($_POST),6)+0;
$action = substr(key($_POST),0,6);
$role=htmlspecialchars($_POST['role']);
$remaining=htmlspecialchars($_POST['remaining']);

// make, check, and authenticate database connection
require ('./database.php');

// use the database to bring in the values associated with the
// current bid information

$sql = "SELECT * FROM bid WHERE id=$id;";
$bid_query = $mysqli->query($sql);
$bid_row=$bid_query->fetch_assoc();

if ($action=="submit"){
	$sql = "SELECT investment FROM bid WHERE id=".$id;
	$result=$mysqli->query($sql);
	$row=$result->fetch_assoc();
//	echo "is ".$row['investment']." less than or equal to ".
//		$remaining."?";
	if ($row['investment']<=$remaining) {
		echo "<h1>Successfully submitted this bid</h1>\n";
		$sql = "UPDATE bid SET submitted=1 WHERE id=".$id;
		$mysqli->query($sql);
	} else {
		echo "Sorry, cannot submit this bid since you do
			no have the budget.</br>";
	}
	echo "<form action='".$role.".php' method='POST'>";
	echo "<input type=submit name='review_bids' value='review bids'>";
	require ('./sendvars.php');
	echo "</form>\n";
} elseif ($action=="remove") {
	echo "<h1>Successfully withdrew this bid</h1>\n";
	$updates = "price=".$bid_row['price'].", investment=".
		$bid_row['investment'].", submitted = 0";
	$sql = "UPDATE bid SET ".$updates." WHERE id=".$bid_row['id'];
	$mysqli->query($sql);
	echo "<form action='".$role.".php' method='POST'>";
	echo "<input type=submit name='review_bids' value='review bids'>";
	require ('./sendvars.php');
	echo "</form>\n";
} elseif ($action=="modify") {
	echo "Modifying bid";
	echo "<form action='savebid.php' method='POST'>";
	echo "<table id='gamedata'>";
	echo "<tr><th>parameter</th><th>current value</th><th>new value</th></tr>\n";
	echo "<tr><td>price</td><td>".$bid_row['price']."</td><td>";
	echo "<input type=number name='price' value=".$bid_row['price'].">";
	echo "</td></tr>";
	echo "<tr><td>investment</td><td>".$bid_row['investment']."</td>";
	echo "<td><input type=number name='investment' value=".$bid_row['investment']."></td></tr>";
	echo "</table>";
	echo "<input type=submit name='save' value='save'>";
	echo "<input type='hidden' name='id' value=$id>";
	require ('./sendvars.php');
	echo "</form>\n";
} elseif ($action == "reject") {
	echo "<h1>Successfully rejecting this bid</h1>\n";
	$sql = "UPDATE bid SET rejected=1 WHERE id=".$id;
	$mysqli->query($sql);
	echo "<form action='".$role.".php' method='POST'>";
	echo "<input type=submit name='review_bids' value='review bids'>";
	require ('./sendvars.php');
	echo "</form>\n";
} elseif ($action == "accept") {
	$sql = "SELECT investment FROM bid WHERE id=".$id;
	$result=$mysqli->query($sql);
	$row=$result->fetch_assoc();
	if ($row['investment']<=$remaining) {
		echo "Accepting this bid.<br>";
		$sql = "UPDATE bid SET accepted=1 WHERE id=".$id;
		$mysqli->query($sql);
	} else {
		echo "Sorry, cannot accept this bid since you do
			no have the budget.</br>";
	}
	echo "<form action='".$role.".php' method='POST'>";
	echo "<input type=submit name='review_bids' value='review bids'>";
	require ('./sendvars.php');
	echo "</form>\n";
} elseif ($action=="counte") {
	echo "<h2>Make a counteroffer</h2>\n";
	echo "<form action='savebid.php' method='POST'>";
	echo "<table id='gamedata'>";
	echo "<tr><th>parameter</th><th>current value</th><th>new value</th></tr>\n";
	echo "<tr><td>price</td><td>".$bid_row['price']."</td><td>";
	echo "<input type=number name='price' value=".$bid_row['price'].">";
	echo "</td></tr>";
	echo "<tr><td>investment</td><td>".$bid_row['investment']."</td>";
	echo "<td><input type=number name='investment' value=".$bid_row['investment']."></td></tr>";
	echo "</table>";
	echo "<input type=submit name='save' value='save'>";
	echo "<input type='hidden' name='id' value=$id>";
	require ('./sendvars.php');
	echo "</form>\n";
}

?>
<form>



</form>


</body>


</html>
