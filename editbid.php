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

if ($_POST['action']=='submit new bid') {
	$action="newbid";
} else {
	$bid_id = substr(key($_POST),6)+0;
	$action = substr(key($_POST),0,6);
}

$role=htmlspecialchars($_POST['role']);
$remaining=htmlspecialchars($_POST['remaining']);

// make, check, and authenticate database connection
require ('./database.php');

// use the database to bring in the values associated with the
// current bid information

if ($action!="newbid") {
	$sql = "SELECT * FROM bid WHERE id=$bid_id;";
	$bid_query = $mysqli->query($sql);
	$bid_row=$bid_query->fetch_assoc();
}

function capRoom($bid_id, $mysqli) {
	$sql = "SELECT vc_id, startup_id, event_id, investment FROM bid where id=".$bid_id;
	$query = $mysqli->query($sql);
	$row = $query->fetch_assoc();
	$vc_id = $row['vc_id'];
	$startup_id = $row['startup_id'];
	$event_id = $row['event_id'];
	$investment = $row['investment'];
	$sql = "SELECT SUM(investment) FROM bid WHERE vc_id = ".$vc_id." AND accepted=1";
	$query = $mysqli->query($sql);
	$row = $query->fetch_assoc();
	$vc_spent = $row['SUM(investment)'];
	$sql = "SELECT SUM(investment) FROM bid WHERE startup_id = ".$startup_id." AND accepted=1";
	$query = $mysqli->query($sql);
	$row = $query->fetch_assoc();
	$startup_spent = $row['SUM(investment)'];
	$sql = "SELECT vc_budget, startup_budget FROM event WHERE id = ".$event_id;
	$query = $mysqli->query($sql);
	$row = $query->fetch_assoc();
	$vc_budget = $row['vc_budget'];
	$startup_budget = $row['startup_budget'];
	if ($vc_budget>=($vc_spent+$investment) && $startup_budget>=($startup_spent+$investment)) {
		return "okay";
	} elseif ($vc_budget<($vc_spent+$investment)) {
		return "VC does not have enough funds";
	} else {
		return "Startup cannot accept an investment at this price";
	}
}
	
if ($action=="submit"){
	$sql = "SELECT investment FROM bid WHERE id=".$bid_id;
	$result=$mysqli->query($sql);
	$row=$result->fetch_assoc();
	if ($row['investment']<=$remaining) {
		echo "<h1>Successfully submitted this bid</h1>\n";
		$sql = "UPDATE bid SET submitted=1 WHERE id=".$bid_id;
		$mysqli->query($sql);
	} else {
		echo "Sorry, you cannot submit this bid since you do
			not have the budget.</br>";
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
	echo "<input type='hidden' name='id' value=$bid_id>";
	require ('./sendvars.php');
	echo "</form>\n";
} elseif ($action == "reject") {
	echo "<h1>Successfully rejecting this bid</h1>\n";
	$sql = "UPDATE bid SET rejected=1 WHERE id=".$bid_id;
	$mysqli->query($sql);
	echo "<form action='".$role.".php' method='POST'>";
	echo "<input type=submit name='review_bids' value='review bids'>";
	require ('./sendvars.php');
	echo "</form>\n";
} elseif ($action == "accept") {
	$message = capRoom($bid_id, $mysqli);
	if ($message == "okay") {
		echo "Accepted this bid.</br>";
		$sql = "UPDATE bid SET accepted=1 WHERE id=".$bid_id;
		$mysqli->query($sql);
	} else {
		echo $message;
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
	echo "<input type='hidden' name='id' value=$bid_id>";
	require ('./sendvars.php');
	echo "</form>\n";
} elseif ($action=="newbid") {
	if ($_POST['investment']<=$remaining && $_POST['startup_id']!="") {
		echo "Submitting this bid.<br>";
		$sql_part1 = "INSERT INTO bid (event_id, vc_id, startup_id, ";
		$sql_part2 = "price, investment, submitted, accepted, rejected, ";
		$sql_part3 = "counter) VALUES (".$event_id.", ".$user_id.", ";
		$sql_part4 = $_POST['startup_id'].", ".$_POST['price'].", ";
		$sql_part5 = $_POST['investment'].", 1, 0, 0, 0)";
		$sql=$sql_part1.$sql_part2.$sql_part3.$sql_part4.$sql_part5;
		$mysqli->query($sql);
	} elseif ($_POST['startup_id']=="") {
		echo "Cannot submit this bid because no startup was selected.</br>";
		echo "Please go back and be sure to select one of the startups.</br>";
	} else	{
		echo "Sorry, cannot submit this bid since you do
			no have the budget.</br>";
	}
	echo "<form action='".$role.".php' method='POST'>";
	echo "<input type=submit name='review_bids' value='review bids'>";
	require ('./sendvars.php');
	echo "</form>\n";

} else {
	echo "unknown action";
}

?>

</body>


</html>
