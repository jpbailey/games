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

//echo $edit_id;

// make, check, and authenticate database connection
require ('./database.php');

// use the database to bring in the values associated with the
// current bid information

$sql = "SELECT name FROM user WHERE id=".$edit_id;
$user_query = $mysqli->query($sql);
$user_row=$user_query->fetch_assoc();
echo "current name:  ";
echo $user_row['name'];
echo "<p>\n";

//echo "This functionality needs to be added still";

echo "new name:";

echo "<form action='newname.php' method='POST'>";
echo "<input type='text' name='newname' value='".$user_row['name']."'>";
echo "<br>";
echo "<input type='submit' name='action' value='submit name change'>";
echo "<input type='hidden' name='edit_id' value=".$edit_id.">";
require ('./sendvars.php');
echo "</form>";

?>

</body>


</html>
