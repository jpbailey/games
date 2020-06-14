<html>
<head>
<title>Design a new event</title>
</head>

<body>

<?php

// this script will populate the database with information
// needed to run the game.  It makes an entry into the event
// table, adds all of the users to the user table, and then
// makes some placeholder bids in the bid table base on each
// vc and startup pairing

// set the environment variables
require ('./environment.php');

// bring in the variables from the previous page
require ('./variables.php');
$event_name = htmlspecialchars($_POST['event_name']);
$num_startup = htmlspecialchars($_POST['num_startup']);
$startup_budget = htmlspecialchars($_POST['startup_budget']);
$num_vc = htmlspecialchars($_POST['num_vc']);
$vc_budget = htmlspecialchars($_POST['vc_budget']);
$start_date = htmlspecialchars($_POST['start_date']);
$end_date =  htmlspecialchars($_POST['end_date']);
$event_description = htmlspecialchars($_POST['event_description']);

// connect, check, and authenticate a database connection
require ('./database.php');

//make sure the event name isn't already there
$sql = "SELECT name FROM event WHERE 1";
$sql_query = $mysqli->query($sql);

$flag = 0;
while ($row=$sql_query->fetch_assoc()){
	if ($row['name']==$event_name) {
		$flag=1;
	}
}

if ($flag==1) {
	echo "An event with this name already exists.  Please";
	echo " try again.<p>";
	die();
}

// create the event in the event table based on the event
// parametera and get the event_id from the database
echo "Creating event: ".$event_name."</br>\n";
$sql = "INSERT INTO event (active, name, num_startup, startup_budget,
	num_vc, vc_budget, start_date,
	end_date, description) VALUES (1, '".$event_name."', ".$num_startup.", ".
	$startup_budget.", ".$num_vc.", ".$vc_budget.", '".
	$start_date."', '".$end_date."', '".
	$event_description."')";
$mysqli->query($sql);

$event_id = ($mysqli->insert_id);

// find the last index used in the database from the user table
// number the startups from that index + 1 to the end
// then start numbering the vcs from +1 to the end of that

$sql = "SELECT id FROM user ORDER BY id DESC LIMIT 1";
$user_query = $mysqli->query($sql);
$user_data = $user_query->fetch_assoc();
$counter = $user_data['id']+1;

$startup_array=array();
while ($counter <= $user_data['id']+$num_startup) {
	array_push($startup_array, $counter);
	$new_name = "Startup ".$counter;
	$new_password = rand(1111,9999);
	echo "user: ".$new_name."; password: ".$new_password;
	echo "; link: http://digitalplatformgames.com/games/login.php?";
	echo "event_id=".$event_id."&user_id=".$counter."<br>\n";
	$sql = "INSERT INTO user (name, password, vc,
		startup) VALUES ('".$new_name."', ".
		$new_password.", 0, 1)";
	$mysqli->query($sql);
	$counter = $counter +1;
}


$vc_array=array();

while ($counter <= $user_data['id']+$num_startup+$num_vc) {
	array_push($vc_array, $counter);
	$new_name = "Venture Capitalist ".$counter;
	$new_password = rand(1111,9999);
	echo "user: ".$new_name."; password: ".$new_password;
	echo "; link: http://digitalplatformgames.com/games/login.php?";
	echo "event_id=".$event_id."&user_id=".$counter."<br>\n";
	$sql = "INSERT INTO user (name, password, vc,
		startup) VALUES ('".$new_name."', ".
		$new_password.", 1, 0)";
	$mysqli->query($sql);
	$counter = $counter + 1;
}

// now that we have two arrays -- one for the VCs and one for
// the startups -- we need to create entries into the bid table
// based on each pairing

foreach ($vc_array as $vc) {
	foreach ($startup_array as $startup) {
		$sql = "INSERT INTO bid (event_id, vc_id, startup_id, price,
			investment, submitted, accepted, rejected, counter) VALUES (".$event_id.", ".
			$vc.", ".$startup.", 100, 500000, 0, 0, 0, 0)";
		$mysqli->query($sql);
	}
}

//go back to control panel
echo "<form action=controlpanel.php method='post'>\n";
echo "<input type='submit' name='control' value='Return to Control Panel'>\n";
require ('./sendvars.php');
echo "</form>";

?>
</body>

</html>
