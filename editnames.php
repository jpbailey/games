<head>
<title>Edit/View Participants</title>
<link rel="stylesheet" href="./styles.css">
</head>
<body>

<?php


// bring in the variables from the previous page
require ('./variables.php');

// make, check, and authenticate database connection
require './database.php';


$sql = "SELECT DISTINCT startup_id FROM bid WHERE event_id=".$event_id;
$startup_query = $mysqli->query($sql);

while ($row=$startup_query->fetch_assoc()) {
    echo $row['startup_id'];
}


//$sql = "SELECT DISTINCT vc_id FROM bid WHERE event_id=".$event_id;


?>

</body>

</html>

