<html>
<head>
<title>Welcome to Digital Platform Games</title>
</head>

<?php
// this is the main login page
// we need to authenticate the user and know whether
// this person is an adminstrator, a venture capitalist,
// or a startup


// bring in the data passed by the url
$event_id=htmlspecialchars($_GET["event_id"]);
$user_id=htmlspecialchars($_GET["user_id"]);


// make and check database connection

$config = parse_ini_file('./private/config.ini');

$mysqli = new mysqli($config['servername'], $config['username'],
	$config['password'], $config['dbname']);

if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
        }
// if there is information about the user and event,
// retrieve it from the database

$sql="SELECT * FROM user WHERE id=".$user_id;
$user_query = $mysqli->query($sql);
$user_info = $user_query->fetch_assoc();

if ($user_info['name']!="") {
	$user_name = $user_info['name'];
} else {
	die("User not found.");
}

echo "<h1> Welcome " . $user_name;

if ($event_id=="") {
	echo ".<p>";
} else {
	$sql="SELECT * FROM event WHERE id=".$event_id;
	$event_query = $mysqli->query($sql);
	$event_info = $event_query->fetch_assoc();
	$event = $event_info['name'];
	echo " to event " . $event . "<p>";
}

echo "</h1>";

if ($user_info['vc']==1 & $user_info['startup']==0){
	echo "Your role in this game is a venture capitalist.<p>";
	$role="vc";
	if ($event=="") {
		die("event not specified; check URL");
		}
	} elseif ($user_info['vc']==0 & $user_info['startup']==1) {
	echo "Your role in this game is a startup.<p>";
	$role="startup";
	if ($event=="") {
		die("event not specified; check URL");
		}
	} elseif ($user_info['vc']==1 & $user_info['startup']==1) {
	echo "You are listed as both a venture capitalist and a ";
	echo "startup. Please contact the administrator to fix this.<p>";
	die();
	} else {
	$role="admin";
	}

// welcome name_name_of_user to event name_of_event
// fetch and display the description of the event

// would be good to make sure today's date is within the range
// of event dates; otherwise abort

if ($event_id != "") {
	$event_query = $mysqli->query("SELECT * FROM event WHERE id=$event_id;");
	$event_info = $event_query->fetch_assoc();

	echo "Description: ".$event_info['description']."<p>";
	echo "This event begins on: ".$event_info['start_date']." ";
	echo "and ends on ".$event_info['end_date'].".<p>";
	echo "There are ".$event_info['num_vc']." venture capitalists ";
	echo "in this simulation and ".$event_info['num_startup']." ";
	echo "startups.<p>";
}

echo "To get started, please enter the password provided to you:\n";
if ($role=="vc") {
	echo "<form action=vc.php method='post'>\n";
	} elseif ($role=="startup") {
	echo "<form action=startup.php method='post'>\n";
	} elseif ($role=="admin") {
	echo "<form action=controlpanel.php method='post'>\n";
	}
echo "<input type='hidden' name='user_id' value=$user_id>\n";
echo "<input type='hidden' name='event_id' value=$event_id>\n";
echo "password: <input type='password' name='password'>\n";
echo "<input type='submit'>";
echo "</form>";


?>
</body>

</html>
