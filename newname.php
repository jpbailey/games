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

// make, check, and authenticate database connection
require ('./database.php');

// use the database to bring in the values associated with the
// current bid information

$sql = "SELECT name FROM user WHERE id=".$edit_id;
$user_query = $mysqli->query($sql);
$user_row=$user_query->fetch_assoc();
echo "current name is: ".$user_row['name']."<p>";
echo "new name is: ".$newname."<p>";

// check to make sure new name is sql compliant
// no sql injections here!

//$sql = "UPDATE user SET name='".$newname."' WHERE id=".$edit_id;
$stmt = $mysqli->prepare( "UPDATE user SET name= ? WHERE id= ?");
$stmt->bind_param("si", $newname, $edit_id);
$stmt->execute();
$stmt->close();

//$stmt = $mysqli->prepare("UPDATE myTable SET name = ? WHERE id = ?");
//$stmt->bind_param("si", $_POST['name'], $_SESSION['id']);

//$stmt = $conn->prepare("INSERT INTO MyGuests (firstname, lastname, email) VALUES (?, ?, ?)");
//$stmt->bind_param("sss", $firstname, $lastname, $email);

//echo $clean_sql;
//$mysqli->query($sql);

echo "<form action='editnames.php' method='POST'>";
echo "<input type='submit' name='action' value='back to edit names'>";
require ('./sendvars.php');
echo "</form>";

?>

</body>


</html>