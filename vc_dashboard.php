<?php

//$sql = "SELECT DISTINCT vc_name FROM bid WHERE event_name='".$event."'";
//$data = $mysqli->query($sql);

//while ($row = $data->fetch_assoc()) {
//        echo $row['vc_name']." has invested ";
//        $sql = "SELECT SUM(investment) FROM bid WHERE vc_name='".
//                $row['vc_name']."' AND accepted=1";
//        $sql_query = $mysqli->query($sql);
//        $row=$sql_query->fetch_assoc();
//        if ($row['SUM(investment)'] != "") {
//                echo "$".number_format($row['SUM(investment)'], 0)."<br>\n";
//        } else {
//                echo "$0.<br>\n";
//        }
//}

$sql = "SELECT startup_budget FROM event WHERE name='".$event."'";
//echo $sql;
$sql_query = $mysqli->query($sql);
$startup_budget=0;
while ($row = $sql_query->fetch_assoc()) {
        $startup_budget = $row['startup_budget'];
}
//echo $startup_budget;

$sql = "SELECT DISTINCT startup_name FROM bid WHERE event_name='".$event."'";
$data = $mysqli->query($sql);

while ($row = $data->fetch_assoc()) {
	$sql = "SELECT nickname FROM user WHERE name='".$row['startup_name']."'";
	$sql_query = $mysqli->query($sql);
	$sql_data=$sql_query->fetch_assoc();
        echo $sql_data['nickname']." can still receive ";
        $sql = "SELECT SUM(investment) FROM bid WHERE startup_name='".
                $row['startup_name']."' AND accepted=1";
        $sql_query = $mysqli->query($sql);
        $row=$sql_query->fetch_assoc();
        if ($row['SUM(investment)'] != "") {
		$amt = $startup_budget - $row['SUM(investment)'];
		echo "$".number_format($amt,0)."<br>\n";
//                echo "$".number_format($row['SUM(investment)'], 0)."<br>\n";
        } else {
                echo "$".number_format($startup_budget,0)."<br>\n";
        }
}

?>

