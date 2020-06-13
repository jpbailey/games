<head>
<title>Administrator</title>
<link rel="stylesheet" href="./styles.css">
</head>
<body>

<?php

// this is the admin page for a particular event
// you want to show all of the bid data even in draft
// form as well as the VC and startup dashboards

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
$sql = "SELECT * FROM bid WHERE event_name='".$event."' ORDER BY 'vc_name'";
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

require('./vc_dashboard.php');
require('./startup_dashboard.php');


?>

</html>
