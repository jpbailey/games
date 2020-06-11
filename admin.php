<head>
<title>Get Data</title>
<link rel="stylesheet" href="./styles.css">
</head>
<body>

<?php
// here you want to get all of the bid data that has been
// entered into the system.  the assumption is that all of the vcs
// have entered in the data correctly and you are just looking at 
// the data to figure out what bids translate into executed transactions
//
// you need to pull from the bid table all rows where event_name is
// the event that you are interested in looking at.  this includes:
//
// additionally, you will need to pull from the tiebraker table where
// the event_name is the event that you are interested in looking at
// the following information:
// - vc_name
// - selected_startup:  the name of the startup that they are willing
//   to go higher on in price
// - new_bid:  next higher bid for the selected startup
// - floor_price:  the minimum price they will pay for any unclaimed
//   investment opportunities
//
// the information should be presented such that all bids
// are ranked from highest price to lowest price

// bring in the variables from the previous page

$event=htmlspecialchars($_POST['event']);
$user=htmlspecialchars($_POST['user']);
$nickname=htmlspecialchars($_POST['nickname']);
$password=htmlspecialchars($_POST['password']);

// make, check, and authenticate database connection
require './database.php';

// get the bid data for the event

$sql = "SELECT * FROM event WHERE name='$event';";
$event_query = $mysqli->query($sql);

$event_row = $event_query->fetch_assoc();

echo "<h1>Data from event: ".$event_row['name']."</h1>\n";
echo "<p style='color:red'>Be sure to refresh this screen so you";
echo "have accurate data.</p>\n";
//echo "There should be ".$event_row['num_vc']." VCs.<p>";
//echo "There should be ".$event_row['num_startup']." startups.<p>";
//echo "There should be ".$event_row['num_vc']*$event_row['num_startup']." bids.<p>";

$sql = "SELECT * FROM bid WHERE event_name='$event';";
$data = $mysqli->query($sql);

$submitted_bids = 0;
if ($data->num_rows > 0) {
	while ($row=$data->fetch_assoc()){
		if ($row['submitted'] == 1) {
			$submitted_bids = $submitted_bids + 1;
		}
	}
//	echo "There are ".$submitted_bids." submitted bids.<p>";
} else {
	echo "must be an error because there are no bids<p>";
}

// $sql = "SELECT * FROM bid WHERE event_name='$event' ORDER BY submitted, bid DESC;";
$sql = "SELECT * FROM bid WHERE event_name='".$event."'";
$data = $mysqli->query($sql);

if ($data->num_rows > 0) {
	echo "<table id='gamedata'>";
	echo "<tr><th>VC</th><th>Startup</th><th>Price</th>";
	echo "<th>Investment</th><th>Status</th></tr>";
	while ($row=$data->fetch_assoc()){
        	echo "<tr>";
		$sql = "SELECT nickname FROM user WHERE name='".$row['vc_name']."'";
		$sql_query = $mysqli->query($sql);
		$sql_result = $sql_query->fetch_assoc();
		echo "<td>" . $sql_result['nickname'] . "</td>";
		$sql = "SELECT nickname FROM user WHERE name='".$row['startup_name']."'";
		$sql_query = $mysqli->query($sql);
		$sql_result = $sql_query->fetch_assoc();
		echo "<td>" . $sql_result['nickname'] . "</td>";
		echo "<td>";
		echo '$' . number_format($row['price'], 0);
		echo "</td>";
		echo "<td>";
		echo '$' . number_format($row['investment'], 0);
		echo "</td>";
		if ($row['accepted'] == 1) {
			echo "<td>accepted</td>\n";
		} elseif ($row['rejected'] == 1) {
			echo "<td>rejected</td>\n";
		} elseif ($row['counteroffer'] == 1) {
			echo "<td>counteroffer</td>\n";
		} elseif ($row['submitted'] == 1) {
			echo "<td>submitted</td>\n";
		} else {
			echo "<td>no bid</td>\n";
		}
		echo "</tr>";
	}
	echo "</table>";
	} else {
	echo "no results<p>";
}

require('./dashboard.php');


?>

</html>
