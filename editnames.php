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
    $sql = "SELECT name, password FROM user where id=".$row['startup_id'];
    $user_query=$mysqli->query($sql);
    $user_row=$user_query->fetch_assoc();
    echo $user_row['name'];
    echo "<br>\n";
}

echo "<h2>VCs</h2>\n";
$sql = "SELECT DISTINCT vc_id FROM bid WHERE event_id=".$event_id;
$vc_query = $mysqli->query($sql);

echo "<table id='gamedata'>";
echo "<tr><th>name</th><th>new name</th><th>URL</th><th>password</th><th>action</th></tr>\n";
while ($row=$vc_query->fetch_assoc()) {
    $sql = "SELECT * FROM user where id=".$row['vc_id'];
    $user_query=$mysqli->query($sql);
    $user_row=$user_query->fetch_assoc();
    echo "<tr>";
    echo "<td>".$user_row['name']."</td>";
    echo "<td><input type=text name='newname' value=".$user_row['name']."></td>";
    $vc_url = "http://digitalplatformgames.com/games/login.php?event_id=".$event_id."&user_id=".$user_row['id'];
    echo "<td><a href='".$vc_url."'>".$vc_url."</a></td>";
    echo "<td>".$user_row['password']."</td>";
    echo "<td>edit</td>";
    echo "</tr>";
}
echo "</table>\n";


?>

<form action='admin.php' method='POST'>
<input type='submit' name='action' value='Administratpr Panel'>

<?php
require ('./sendvars.php')
?>

<input type='hidden' name='role' value='vc'>
</form>

</body>

</html>

