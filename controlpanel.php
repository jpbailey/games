<html>
<head>
<title>Admin Control Panel</title>
</head>

<body>

<?php

// this script will allow an administrator to either
// create a game by directing them to the event.php script
// or to look at the data from the venture capitalists' bids

// get environment variables
require ('./environment.php');
$date = date('Y-m-d');

// bring in the variables from the previous page
require ('./variables.php');

// make, check, and authenticate database connection
require './database.php';

// give them two options; either start a new event or get the data
// from an event

$sql = "SELECT * FROM event WHERE 1 ORDER BY start_date;";
$event_query = $mysqli->query($sql);

echo "<h2>Existing Events</h2>";
while ($row=$event_query->fetch_assoc()){
	if ($row['active'] == 1) {
	        $fullname = $row['name']." - started on: ".$row['start_date'];
		echo "<form action=admin.php method='post'>";
		echo "<input type='hidden' name='event_id' value=".$row['id'].">";
		echo "<input type='hidden' name='user_id' value=$user_id>";
		echo "<input type='hidden' name='password' value=$password>";
		echo "<input type='submit' value='".$fullname."'>";
		echo "</form>";
	}
}

echo "<h2>Design a New Event.</h2>";
echo "<form action=event.php method='post'>";
echo "<table id='gamedata'>";
echo "<tr><td>What is the event name?</td><td>";
echo "<input type='text' name='event'></td></tr>";
echo "<tr><td>Please write a brief description of the event:</td><td>";
echo "<input type='text' name='event_description' size=40></td></tr>";
echo "<tr><td>How many startups?</td><td>";
echo "<input type='number' name='num_startup'></td></tr>";
echo "<tr><td>What is the max total investment for a startup?</td><td>";
echo "<input type='number' name='startup_budget' value=2500000></td></tr>";
echo "<tr><td>How many VCs?</td><td>";
echo "<input type='number' name='num_vc'></td></tr>";
echo "<tr><td>How much does each VC have to invest?</td><td>";
echo "<input type='number' name='vc_budget' value=2000000></td></tr>";
echo "<tr><td>Start date?</td><td>";
echo "<input type='date' name='start_date'></td></tr>";
echo "<tr><td>End date?</td><td>";
echo "<input type='date' name='end_date'></td></tr>";
echo "</table>";
echo "<input type='hidden' name='user' value=$user>";
echo "<input type='hidden' name='password' value=$password>";
echo "<input type='hidden' name='user_id' value=$user_id>";
echo "<input type='submit' value='Create New Event'>";
echo "</form>";


?>
</body>
</html>

