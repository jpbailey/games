<?php

// this code is used at the bottom of the VC page to give them
// information about how much money the startups can receive

echo "<h2>VC Dashboard</h2>";

$sql = "SELECT startup_budget FROM event WHERE id=".$event_id;
$sql_query = $mysqli->query($sql);
$startup_budget=0;
while ($row = $sql_query->fetch_assoc()) {
        $startup_budget = $row['startup_budget'];
}

$sql = "SELECT DISTINCT startup_name FROM bid WHERE id=".$event_id;
$data = $mysqli->query($sql);

while ($row = $data->fetch_assoc()) {
	$sql = "SELECT nickname FROM user WHERE id=".$row['startup_id'];
	$sql_query = $mysqli->query($sql);
	$sql_data=$sql_query->fetch_assoc();
        echo $sql_data['nickname']." can still receive ";
        $sql = "SELECT SUM(investment) FROM bid WHERE startup_id=".
                $row['startup_id']." AND accepted=1";
        $sql_query = $mysqli->query($sql);
        $row=$sql_query->fetch_assoc();
        if ($row['SUM(investment)'] != "") {
		$amt = $startup_budget - $row['SUM(investment)'];
		echo "$".number_format($amt,0)."<br>\n";
        } else {
                echo "$".number_format($startup_budget,0)."<br>\n";
        }
}

?>

