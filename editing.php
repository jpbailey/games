<html>
<head>
<title>Editing Team Name</title>
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
	$bid_id = substr(key($_POST),6)+0;
	$action = substr(key($_POST),0,6);
}

$role=htmlspecialchars($_POST['role']);
$remaining=htmlspecialchars($_POST['remaining']);

// make, check, and authenticate database connection
require ('./database.php');

// use the database to bring in the values associated with the
// current bid information



?>

</body>


</html>
