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

$edit_id=htmlspecialchars($_POST['edit_id']);
$newname=htmlspecialchars($_POST['newname']);

echo $edit_id;
echo $newname;

//echo $edit_id;

// make, check, and authenticate database connection
require ('./database.php');

// use the database to bring in the values associated with the
// current bid information

//$sql = "SELECT name FROM user WHERE id=".$edit_id;
//$user_query = $mysqli->query($sql);
//$user_row=$user_query->fetch_assoc();
//echo "current name:  ";
//echo $user_row['name'];
//echo "<p>\n";


echo "<form action='admin.php' method='POST'>";
echo "<input type='submit' name='action' value='back to admin panel'>";
require ('./sendvars.php');
echo "</form>";

?>

</body>


</html>