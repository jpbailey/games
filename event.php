<html>
<head>
<title>Design a new event</title>
</head>

<body>

<?php

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

// echo $event;

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


// find the last index used in the database
// number the startups from that index + 1 to the end
// then start numbering the vcs from +1 to the end of that

// need to first update the event table with $event and $event_description
// as well as $num_startup and $num_vc and $start_date and $end_date
// need to escape the characters of the text fields before inserting them
// into the database.

$sql = "SELECT id FROM user ORDER BY id DESC LIMIT 1";
$user_query = $mysqli->query($sql);

echo "Creating event: ".$event."</br>\n";
$sql = "INSERT INTO event (active, name, num_startup, startup_budget,
	num_vc, vc_budget, start_date,
	end_date, description) VALUES (1, '".$event_name."', ".$num_startup.", ".
	$startup_budget.", ".$num_vc.", ".$vc_budget.", '".
	$start_date."', '".$end_date."', '".
	$event_description."')";
//echo $sql."<br>";
$mysqli->query($sql);


$user_data = $user_query->fetch_assoc();

$startup_array=array();

$counter = $user_data['id']+1;
while ($counter <= $user_data['id']+$num_startup) {
	array_push($startup_array, $counter);
	$new_name = "Startup ".$counter;
	$new_password = rand(1111,9999);
	echo "user: ".$new_user."; password: ".$new_password;
	echo "; link: http://digitalplatformgames.com/games/login.php?";
	echo "event=".$event."&user=".$new_user."<br>\n";
	$sql = "INSERT INTO user (name, password, vc,
		startup) VALUES ('".$new_name."', '".
		$new_password.", 0, 1)";
//	echo $sql."<br>";
	$mysqli->query($sql);
	$counter = $counter +1;
}

//print_r($startup_array);

$vc_array=array();

while ($counter <= $user_data['id']+$num_startup+$num_vc) {
	array_push($vc_array, $counter);
	$new_name = "Venture Capitalist ".$counter;
	$new_password = rand(1111,9999);
	echo "user: ".$new_user."; password: ".$new_password;
	echo "; link: http://digitalplatformgames.com/games/login.php?";
	echo "event=".$event."&user=".$new_user."<br>\n";
	$sql = "INSERT INTO user (name, password, vc,
		startup) VALUES ('".$new_name."', '".
		$new_password.", 1, 0)";
//	echo $sql."<br>";
	$mysqli->query($sql);
	$counter = $counter + 1;
}

//print_r($vc_array);

foreach ($vc_array as $vc) {
	foreach ($startup_array as $startup) {
		$sql = "INSERT INTO bid (event_id, vc_id, startup_id, price,
			investment, submitted, accepted, rejected, counter) VALUES (".$event_id.", ".
			$vc.", ".$startup.", 100, 500000, 0, 0, 0, 0)";
		$mysqli->query($sql);
	}
}

//go back to control panel
echo "<form action='controlpanel.php'>\n";
echo "<input type='submit' name='control' value='Return to Control Panel'>\n";
echo "<input type='hidden' name='user_id' value=$user_id\n";
echo "<input type='hidden' name='password' value=$password>\n";
echo "</form>";


//<table>
//<tr><th>parameter</th><th>value</th></tr>
//<tr><td>How many startups</td><td></td></tr>
//<tr><td>How many VCs</td><td></td></tr>
//<tr><td>Start date</td><td></td></tr>
//<tr><td>End date</td><td></td></tr>
//</table>

// this script will create an event parameters including poplulating
// the market and user tables
//
// it will ask the user for the following information an put it in the
// corresponding fields within the event table:
// - name
// - num_startup
// - num_vc
// - start_date
// - end_date
//
// it will then use the num_startup and num_vc to create the following
// in the user table:
// - name:  either vc## or st## by looking at the next available ##
// - nickname:  either Venture## or Startup##
// - password:  a 4-digit code that is randomly created
// - vc:  flagged as 1 if they are a venture capitalist; 0 otherwise
// - startup:  flagged as 1 if they are a startup; 0 otherwise
//
// finally, it will create  the following in the market table:
// - event_name:  with the event
// - user_name:  with the user; either vc## or st##

?>
</body>

</html>
