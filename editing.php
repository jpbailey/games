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

//$edit_id=htmlspecialchars($_POST['edit_id']);

echo $edit_id;

// make, check, and authenticate database connection
require ('./database.php');

// use the database to bring in the values associated with the
// current bid information

$sql = "SELECT name FROM user WHERE id=".edit_id;
echo $sql;

//$user_query = $mysqli->query($sql);
//$user_row=$user_query->fetch_assoc();
//echo $user_row['name'];

echo "did it pass along the variables?";

?>

</body>


</html>
