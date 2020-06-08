<html>
<head>
<title>Bid data</title>
<link rel="stylesheet" href="./styles.css">
</head>
<body>
<?php

// this code will allow the startup to see all of the bids
// and allow them to accept them

// bring in environment
require ('./environment.php');

// bring in variables
require ('./variables.php');

// make, check, and authneticate database connection
require ('./database.php');

// welcome message
$sql = "SELECT nickname FROM user WHERE id=".$user_id;
$user_query=$mysqli->query($sql);
$user_row=$user_query->fetch_assoc();
$nickname= $user_row['nickname'];

echo "<h1>".$nickname." participating in ".$event."<br></h1>\n";
echo "<p style='color:red'>Be sure to refresh this screen so you";
echo "have accurate data.</p>\n";

// take a look at the remaining budget for this startup
$sql = "SELECT startup_budget FROM event WHERE name='".$event."'";
$event_query = $mysqli->query($sql);
$event_row = $event_query->fetch_assoc();
$budget = $event_row['startup_budget'];

$sql = "SELECT SUM(investment) FROM bid WHERE startup_name='".
        $user."' AND accepted=1";
$bid_query = $mysqli->query($sql);
$bid_row = $bid_query->fetch_assoc();
$spent = $bid_row['SUM(investment)'];
if ($spent=="") {
        $spent=0;
}
$remaining = $budget-$spent;

echo "Maxiumum investment amount: $".number_format($budget, 0)."<br>\n";
echo "Current investment received: $".number_format($spent, 0)."<br>\n";
echo "Amount available to accept: $".number_format($remaining, 0)."<br>\n";

// this is the place where the startups can go to take
// a look at the bids as they are coming in
// need to edit this to allow a starup to accept a bid if
// they are not over their budget amount

$sql = "SELECT * FROM bid WHERE event_name='".$event."' AND startup_name='".$user."'";
$data = $mysqli->query($sql);

//echo "Here are the bids.</br>\n";

//only show submitted bids
// allow them to accept the bids

function makeAcceptedRow($row, $nickname, $vc) {
                echo "<tr>\n";
                echo "<td>" . $vc . "</td>\n";
                echo "<td>" . $nickname . "</td>\n";
                echo "<td>";
                echo '$' . number_format($row['price'], 0);
                echo "</td>\n";
                echo "<td>";
                echo '$' . number_format($row['investment'], 0);
                echo "</td>\n";
		echo "<td>accepted</td>\n";
		echo "<td></td>\n";
                echo "</tr>\n";
}

function makeRejectedRow($row, $nickname, $vc) {
                echo "<tr>\n";
                echo "<td>" . $vc . "</td>\n";
                echo "<td>" . $nickname . "</td>\n";
                echo "<td>";
                echo '$' . number_format($row['price'], 0);
                echo "</td>\n";
                echo "<td>";
                echo '$' . number_format($row['investment'], 0);
                echo "</td>\n";
		echo "<td>rejected</td>\n";
		echo "<td></td>\n";
                echo "</tr>\n";
}

function makeCounterRow($row, $nickname, $vc) {
                echo "<tr>\n";
                echo "<td>" . $vc . "</td>\n";
                echo "<td>" . $nickname . "</td>\n";
                echo "<td>";
                echo '$' . number_format($row['price'], 0);
                echo "</td>\n";
                echo "<td>";
                echo '$' . number_format($row['investment'], 0);
                echo "</td>\n";
		echo "<td>counteroffer made</td>\n";
		echo "<td></td>\n";
// may want to add the functionality of withdrawing a counteroffer
//		echo "<td>withdraw counteroffer</td>\n";
                echo "</tr>\n";
}

function makeSubmittedRow($row, $nickname, $vc) {
                echo "<tr>\n";
                echo "<td>" . $vc . "</td>\n";
                echo "<td>" . $nickname . "</td>\n";
                echo "<td>";
                echo '$' . number_format($row['price'], 0);
                echo "</td>\n";
                echo "<td>";
                echo '$' . number_format($row['investment'], 0);
                echo "</td>\n";
		echo "<td>submitted</td>";
		echo "<td><input type='submit' name='accept".$row['id'].
			"' value='accept'><input type='submit'
			name='reject".$row['id']."' value=
			'reject'><input type='submit' 
			name='counte".$row['id']."' value =
			'counter offer'></td>\n";
                echo "</tr>\n";
}

function makeDraftRow($row, $nickname, $vc) {
                echo "<tr>\n";
                echo "<td>" . $vc . "</td>\n";
                echo "<td>" . $nickname . "</td>\n";
                echo "<td>TBD</td>\n";
                echo "<td>TBD</td>\n";
                echo "<td>draft</td>\n";
		echo "<td></td>\n";
                echo "</tr>\n";
}

if ($data->num_rows > 0) {
	echo "<form action='editbid.php' method='POST'>\n";
	echo "<table id='gamedata'>\n";
        echo "<tr><th>VC</th><th>Startup</th><th>Price</th>\n";
        echo "<th>Investment</th><th>Status</th><th>Action</th></tr>\n";
        while ($row=$data->fetch_assoc()){
		$sql= "SELECT nickname FROM user WHERE name='".$row['vc_name']."'";
		$vc_query=$mysqli->query($sql);
		$vc_result=$vc_query->fetch_assoc();
		$vc_name = $vc_result['nickname'];
//		echo $vc_name;
		if ($row['accepted']==1) {
			makeAcceptedRow($row, $nickname, $vc_name);
		} elseif ($row['rejected']==1) {
			makeRejectedRow($row, $nickname, $vc_name);
		} elseif ($row['counter']==1) {
			makeCounterRow($row, $nickname, $vc_name);
		} elseif ($row['submitted']==1) {
			makeSubmittedRow($row, $nickname, $vc_name);
		} else {
			makeDraftRow($row, $nickname, $vc_name);
		}
        }
	echo "</table>\n";
	echo "<input type='hidden' name='role' value='startup'>";
	echo "<input type='hidden' name='remaining' value=".$remaining.">";
	require ('./sendvars.php');
	echo "</form>\n";
} else {
        echo "no results<p>";
}

require('./startup_dashboard.php');

?>

</body>
</html>

