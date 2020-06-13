<?php

// this code is used at the bottom of the startup page to give them
// information about how much money the startups can receive

echo "<h2>Startup Dashboard</h2>";

$sql = "SELECT vc_budget FROM event WHERE id=".$event_id;
$sql_query = $mysqli->query($sql);
$vc_budget=0;
while ($row = $sql_query->fetch_assoc()) {
	$vc_budget = $row['vc_budget'];
}

$sql = "SELECT DISTINCT vc_id FROM bid WHERE event_id=".$event_id;
$data = $mysqli->query($sql);

while ($row = $data->fetch_assoc()) {
        print_r ($row);
	$sql = "SELECT name FROM user WHERE vc_id=".$row['vc_id'];
	$sql_query = $mysqli->query($sql);
	$sql_data=$sql_query->fetch_assoc();
	echo $sql_data['name']." remaining funds: ";
        $sql = "SELECT SUM(investment) FROM bid WHERE vc_id=".
                $row['vc_id']." AND accepted=1";
        $sql_query = $mysqli->query($sql);
        $row=$sql_query->fetch_assoc();
        if ($row['SUM(investment)'] != "") {
		echo "$".number_format($vc_budget - $row['SUM(investment)'],0)."<br>\n";
        } else {
                echo "$".number_format($vc_budget,0)."<br>\n";
        }
}

?>

