<html>
<head>
<title>VC Panel</title>
<link rel="stylesheet" href="./styles.css">
</head>

<?php

// this script will help the vc look at or enter in their bid
// information

// bring in the environment
require ('./environment.php');

// bring in the variables
require ('./variables.php');
// $status=htmlspecialchars($_POST['status']);

// make, check, and authenticate the database connection
require ('./database.php');

// welcome message
// welcome message
$sql = "SELECT name FROM user WHERE id=".$user_id;
$user_query=$mysqli->query($sql);
$user_row=$user_query->fetch_assoc();
$vc_name= $user_row['name'];
$sql = "SELECT name FROM event WHERE id=".$event_id;
$event_query=$mysqli->query($sql);
$event_row=$event_query->fetch_assoc();
$event_name=$event_row['name'];

echo "<h1>".$vc_name." participating in ".$event_name."<br></h1>\n";
echo "<p style='color:red'>Be sure to refresh this screen so you";
echo "have accurate data.</p>\n";

// find out how much the vc budget is and how much more money
// this vc has to spend
$sql = "SELECT vc_budget FROM event WHERE id=".$event_id;
$event_query = $mysqli->query($sql);
$event_row = $event_query->fetch_assoc();
$budget = $event_row['vc_budget'];

$sql = "SELECT SUM(investment) FROM bid WHERE vc_id=".
	$user_id." AND accepted=1";
$bid_query = $mysqli->query($sql);
$bid_row = $bid_query->fetch_assoc();
$spent = $bid_row['SUM(investment)'];
if ($spent=="") {
	$spent=0;
}

$remaining = $budget-$spent;

echo "Total amount to invest: $".number_format($budget, 0)."<br>\n";
echo "Current amount invested: $".number_format($spent, 0)."<br>\n";
echo  "Amount remaining to invest: $".number_format($remaining, 0).".<br>\n";

// this function will create one row within the table from the data
function createRow($row, $mysqli) {
	echo "<tr>\n";
	$str_arr = explode (",", $row);
	$sql = "SELECT name FROM user WHERE id=".$str_arr[1];
	$sql_query = $mysqli->query($sql);
	$sql_result = $sql_query->fetch_assoc();
	echo "<td>".$sql_result['name']."</td>\n";
	$id = $str_arr[0];
//	echo "<td>goes here right".$str_arr[1]."</td>\n";
	echo "<td>";
	echo '$' . number_format($str_arr[2], 0);
	echo "</td>\n";
	echo "<td>";
	echo '$' . number_format($str_arr[3], 0);
	echo "</td>\n";
	if ($str_arr[6]==1) { // rejected bid; nothing to do
		echo "<td>rejected</td>\n";
		echo "<td></td>\n";
	} elseif ($str_arr[5]==1) { //accepted bid; nothing to do
		echo "<td>accepted</td>\n";
		echo "<td></td>\n";
	} elseif ($str_arr[7]==1 ) {  //counteroffer
		echo "<td>counteroffer</td>\n";
		echo "<td><input type='submit' name='reject".$id."' value='reject'>\n";
		echo "<input type='submit' name='accept".$id."' value='accept'></td>\n";
	} elseif (($str_arr[4]==0) && ($str_arr[5]==0)){
		echo "<td>not yet submitted</td>\n";
		echo "<td><input type='submit' name='modify".$id."' value='edit'>\n";
		echo "<input type='submit' name='submit".$id."' value='submit'></td>\n";
	} elseif (($str_arr[4]==1) && ($str_arr[5]==0)) {
		echo "<td>submitted</td>\n";
		echo "<td><input type='submit' name='remove".$id."' value='withdraw'>\n";
	} else {
		echo "<td>accepted</td>\n";
		echo "<td></td>\n";
	}
	echo "</tr>\n";
	echo "\n";
}

// get all of the data for a specific user and event
$sql = "SELECT * FROM bid WHERE vc_id=".$user_id." AND event_id=".$event_id."ORDER BY startup_id";
$biddata_query=$mysqli->query($sql);

// put all of the data into an array
// if there are results, put them all into an array
// adding a 7th column here to take a look at any possible
// action to be taking on these bids

if ($biddata_query->num_rows > 0) {
	$ARRAY=array();
	while ($row=$biddata_query->fetch_assoc()){
		$array_row = $row['id'].",".$row['startup_id'].",".
			$row['price'].",".$row['investment'].",".
			$row['submitted'].",".$row['accepted'].",".
			$row['rejected'].",".$row['counter'].",0";
		$ARRAY[]= $array_row;
	}
} else {
	echo "no results";
	die();
}

?>

<form action='editbid.php' method='POST'>
<table id="gamedata">
<tr><th>Startup</th><th>Price</th><th>Investment</th>
<th>Status</th><th>Action</th></tr>

<?php
foreach($ARRAY as $row) {
	createRow($row, $mysqli);
}

echo "</table>\n";
require ('./sendvars.php');
echo "<input type='hidden' name='role' value='vc'>\n";
echo "<input type='hidden' name='remaining' value=".$remaining.">\n";
echo "</form>\n";

//new form where you can save the bid
// get all of the startups in a list
// move them over to savebid.php

echo "<h2>Make a new offer</h2>";

echo "<form action='editbid.php' method='POST'>\n";
echo "<table id='gamedata'>\n";
echo "<tr><th>parameter</th><th>value</th></tr>\n";
echo "<tr><td>Startup</td><td>\n";
$sql = "SELECT DISTINCT startup_id FROM bid WHERE event_id=".$event_id;
$startup_query = $mysqli->query($sql);

while ($row=$startup_query->fetch_assoc()) {
	echo "<input type='radio' id=".$row['startup_id']." ";
	echo "name='startup_id' value=".$row['startup_id'].">";
	echo "<label for='".$row['startup_id']."'";
	echo ">";
	$sql = "SELECT name FROM user WHERE id=".$row['startup_id'];
	$sql_query= $mysqli->query($sql);
	$sql_result=$sql_query->fetch_assoc();
	echo $sql_result['name'];
	echo "</label><br>";
}

echo "</td></tr>";
echo "<tr><td>price</td><td>";
echo "<input type=number name='price' value='0'>";
echo "</td></tr>";
echo "<tr><td>investment</td><td>";
echo "<input type=number name='investment' value='0'></td></tr>";
echo "</table>";

echo "<td><input type='submit' name='action' value='submit new bid'>\n";

require ('./sendvars.php');
echo "<input type='hidden' name='role' value='vc'>\n";
echo "<input type='hidden' name='remaining' value=".$remaining.">\n";
echo "</form>\n";

require('./vc_dashboard.php');



?>

</body>

</html>
