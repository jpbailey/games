<html>
<head>
<title>Welcome to Digital Platform Games</title>
</head>

<?php

// this script will help the vc look at or enter in their bid
// information

// bring in environment variables
require ('./environment.php');

// bring in variables from previous screen
require ('./variables.php');

// First, make and check the database connection
require './database.php';

function createRow($row) {
	echo "<tr>\n";
	$str_arr = explode (",", $row);
	echo "<td>".$str_arr[1]."</td>\n";
	$id = $str_arr[0];
	if (($str_arr[4]==0) && ($str_arr[5]==0)){
		echo "<td><input type='text' name='price".$id.
			"' value='".$str_arr[2]."'></td>\n";
		echo "<td><input type='text' name='investment".$id.
			"' value='".$str_arr[3]."'></td>\n";
		echo "<td><input type='radio' name='action".$id."' value='do_nothing' checked><label>do nothing</label>\n";
		echo "<input type='radio' name='action".$id."' value='save'><label>save</label>\n";
		echo "<input type='radio' name='action".$id."' value='submit'><label>submit</label></td>\n";
		echo "</tr>\n";
	} else {
		echo "<td>".$str_arr[2]."</td>\n";
		echo "<td>".$str_arr[3]."</td>\n";
		echo "<td>submitted</td>\n";
	}
	echo "</tr>\n";
	echo "\n";
}

echo "Welcome ".$nickname." to event ".$event.".<br>\n";

// put the existing data into an array

$sql = "SELECT * FROM bid WHERE vc_name='$user' AND event_name='$event'";
$biddata_query=$mysqli->query($sql);

// if there are results, put them all into an array
// adding a 7th column here to take a look at any possible
// action to be taking on these bids

if ($biddata_query->num_rows > 0) {
	$ARRAY=array();
	while ($row=$biddata_query->fetch_assoc()){
		$array_row = $row['id'].",".$row['startup_name'].",".
			$row['price'].",".$row['investment'].",".
			$row['submitted'].",".$row['accepted'].
				",0";
		$ARRAY[]= $array_row;
	}
} else {
	echo "no results";
	die();
}

// create an input table using the data found in the array

//$_SESSION["formid"] = md5(rand(0,10000000));
?>

<form action='' method='POST'>
<table>
<tr><th>Startup</th><th>Price</th><th>Investment</th>
<th>status</th></tr>

<?php
foreach($ARRAY as $row) {
	createRow($row);
}

echo "</table>";
echo "<input type='hidden' name='event' value=$event>";
echo "<input type='hidden' name='user' value=$user>";
echo "<input type='hidden' name='user_id' value=$user_id>";
echo "<input type='hidden' name='password' value=$password>";
echo "<input type='hidden' name='status' value='submitted'>";
echo "<input type='submit' name='update' value='update data'>";
echo "</form>\n";

//	return $array_local;
//}

// when the update data button is pressed, we need to go through
// the values in the array and update the database in case any
// of the rows have changed

function writeRecord($row, $action, $mysqli){
	$str_arr = explode (",", $row);
	$price_name = "price".$str_arr[0];
	$investment_name = "investment".$str_arr[0];
	if ($action ==  "save") {
		$updates = "price=".$_POST[$price_name].
			", investment=".$_POST[$investment_name];
	} elseif ($action == "submit") {
		$updates = "price=".$_POST[$price_name].
		", investment=".$_POST[$investment_name].
		", submitted=1";
	} else {
		$updates ="";
	}
	$sql = "UPDATE bid SET ".$updates." WHERE id=".$str_arr[0];
	echo $sql;
	if ($updates !="") {
		$mysqli->query($sql);
	}
}

// print_r($ARRAY);

if(isset($_POST['update'])) {
	echo "updated on: ";
	$date = new DateTime();
	echo date('m/d/Y H:i:s', $date->getTimestamp());
	echo "</br>";
	foreach($ARRAY as $row) {
		$str_arr = explode (",", $row);
		if (($str_arr[4]==0) && ($str_arr[5]==0)){
			$varname = "action".$str_arr[0];
			writeRecord($row, $_POST[$varname], $mysqli);
		}
	}
	header('Location:  submitbid.php');
}

?>

</body>

</html>
