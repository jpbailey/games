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

echo "<h2>Startups</h2>\n";
$sql = "SELECT DISTINCT startup_id FROM bid WHERE event_id=".$event_id;
$startup_query = $mysqli->query($sql);

while ($row=$startup_query->fetch_assoc()) {
    $sql = "SELECT name FROM user where id=".$row['startup_id'];
    $user_query=$mysqli->query($sql);
    $user_row=$user_query->fetch_assoc();
    echo $user_row['name'];
    echo "<br>\n";
}

echo "<h2>VCs</h2>\n";
$sql = "SELECT DISTINCT vc_id FROM bid WHERE event_id=".$event_id;
$vc_query = $mysqli->query($sql);

while ($row=$vc_query->fetch_assoc()) {
    $sql = "SELECT name FROM user where id=".$row['vc_id'];
    $user_query=$mysqli->query($sql);
    $user_row=$user_query->fetch_assoc();
    echo $user_row['name'];
    echo "<br>\n";
}


?>

</body>

</html>

