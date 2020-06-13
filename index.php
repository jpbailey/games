<html>
<head>
<title>Games</title>
<link rel="stylesheet" href="./styles.css">
</head>

<body>
<h1>This is where the games happen!</h1>

<?php

// bring in environment variables
require ('./environment.php');

// at some point, it would be good to populate the games
// that are live with links for people to login

$config = parse_ini_file('./private/config.ini');

$mysqli = new mysqli($config['servername'], $config['username'],
        $config['password'], $config['dbname']);

if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
       }

$event_query=$mysqli->query("SELECT * FROM event WHERE 1;");

if ($event_query->num_rows > 0) {
        echo "<table id='gamedata'>";
        echo "<tr><th>name</th><th>description</th>";
	echo "<th>number of Startups</th><th>Number of VCs</th>";
	echo "<th>start date</th><th>end date</th><th>status</th></tr>";
        while ($row=$event_query->fetch_assoc()){
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['description'] . "</td>";
                echo "<td>" . $row['num_startup'] . "</td>";
                echo "<td>" . $row['num_vc'] . "</td>";
                echo "<td>" . $row['start_date'] . "</td>";
                echo "<td>" . $row['end_date'] . "</td>";
		if ($row['start_date']<=$date && $row['end_date']>=$date) {
			echo "<td>active</td>";
		} else {
			echo "<td>inactive</td>";
		}
                echo "</tr>";
        }
        echo "</table>";
        } else {
        echo "no results";
}


?>

</body>

</html>
