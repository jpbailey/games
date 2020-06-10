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
	$id = substr(key($_POST),6)+0;
	$action = substr(key($_POST),0,6);
}

$role=htmlspecialchars($_POST['role']);
$remaining=htmlspecialchars($_POST['remaining']);

// make, check, and authenticate database connection
require ('./database.php');

// use the database to bring in the values associated with the
// current bid information

if ($action!="newbid") {
	$sql = "SELECT * FROM bid WHERE id=$id;";
	$bid_query = $mysqli->query($sql);
	$bid_row=$bid_query->fetch_assoc();
}

function capRoom($id, $mysqli) {
	$sql = "SELECT vc_name, startup_name, event_name, price FROM bid where id=$id";
	$query = $mysqli->query($sql);
	$row = $query->fetch_assoc();
	$vc = $row['vc_name'];
	$startup = $row['startup_name'];
	$event = $row['event_name'];
	$price = $row['price'];
	$sql = "SELECT SUM(investment) FROM bid WHERE vc_name = '".$vc."' AND accepted=1";
	$query = $mysqli->query($sql);
	$row = $query->fetch_assoc();
	$vc_spent = $row['SUM(investment)'];
	$sql = "SELECT SUM(investment) FROM bid WHERE startup_name = '".$startup."' AND accepted=1";
	$query = $mysqli->query($sql);
	$row = $query->fetch_assoc();
	$startup_spent = $row['SUM(investment)'];
	$sql = "SELECT vc_budget, startup_budget FROM event WHERE name = '".$event."'";
	$query = $mysqli->query($sql);
	$row = $query->fetch_assoc();
	$vc_budget = $row['vc_budget'];
	$startup_budget = $row['startup_budget'];
	echo "VC: ".$vc."<br>";
	echo "Startup: ".$startup."<br>";
	echo "Event: ".$event."<br>";
	echo "VC Spent: ".$vc_spent."<br>";
	echo "Startup Spent: ".$startup_spent."<br>";
	echo "VC Budget: ".$vc_budget."<br>";
	echo "Startup Budget: ".$startup_budget."<br>";
	echo "Price: ".$price."<br>";
//	echo "</br>";
//	echo $bid_row['investment'];
//	use the event id from the bid row to figure out the budget for both sides
//	figure out how much each side has spent
//	then take a look at the bid information to see it it conforms
	if ($vc_budget>=($vc_spent+$price) && $startup_budget>=($startup_spent+price)) {
		return "okay";
	} else {
		return "denied";
	}
}
	
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
	$message = capRoom($id, $mysqli);
	echo $message;
//	if (capRoom($id, $mysqli)) {
//		echo "there is cap room";
//	} else {
//		echo "there isn't cap room";
//	}
	$sql = "SELECT startup_name, vc_name, investment FROM bid WHERE id=".$id;
	$result=$mysqli->query($sql);
	$row=$result->fetch_assoc();
	if ($row['investment']<=$remaining) {
		if ($role=="vc") {
			$sql = "SELECT startup_budget FROM event WHERE name = '".$event."'";
			$budget_query = $mysqli->query($sql);
			$budget_result = $budget_query->fetch_assoc();
			$sql = "SELECT SUM(investment) FROM bid WHERE startup_name = '".$row['startup_name']."' AND accepted=1";
			$sql_query = $mysqli->query($sql);
			$sql_result=$sql_query->fetch_assoc();
			$caproom = $budget_result['startup_budget']-$sql_result['SUM(investment)'];
			if ($row['investment'] <= $caproom) {
				echo "Accepting this bid.<br>";
				$sql = "UPDATE bid SET accepted=1 WHERE id=".$id;
				echo "not really updating this right now";
//				$mysqli->query($sql);
			} else {
				echo "The startup cannot accept this investment; it is too much";
			}
		} else {
			$sql = "SELECT vc_budget FROM event WHERE name = '".$event."'";
			$budget_query = $mysqli->query($sql);
			$budget_result = $budget_query->fetch_assoc();
			$sql = "SELECT SUM(investment) FROM bid WHERE vc_name = '".$row['vc_name']."' AND accepted=1";
			$sql_query = $mysqli->query($sql);
			$sql_result=$sql_query->fetch_assoc();
			$caproom = $budget_result['vc_budget']-$sql_result['SUM(investment)'];
			if ($row['investment'] <= $caproom) {
				echo "Accepting this bid.<br>";
				$sql = "UPDATE bid SET accepted=1 WHERE id=".$id;
				echo "not really accepting this bid right now";
//				$mysqli->query($sql);
			} else {
				echo "The vc does not have the funds for this investment; it is too much";
			}
		}
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
} elseif ($action=="newbid") {
	if ($_POST['investment']<=$remaining && $_POST['startup']!="") {
		echo "Submitting this bid.<br>";
		$sql_part1 = "INSERT INTO bid (event_name, vc_name, startup_name, ";
		$sql_part2 = "price, investment, submitted, accepted, rejected, ";
		$sql_part3 = "counter) VALUES ('".$event."', '".$user."', '";
		$sql_part4 = $_POST['startup']."', ".$_POST['price'].", ";
		$sql_part5 = $_POST['investment'].", 1, 0, 0, 0)";
		$sql=$sql_part1.$sql_part2.$sql_part3.$sql_part4.$sql_part5;
		$mysqli->query($sql);
	} elseif ($_POST['startup']=="") {
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
<form>



</form>


</body>


</html>
