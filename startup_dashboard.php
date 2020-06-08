<?php

$sql = "SELECT vc_budget FROM event WHERE name='".$event."'";
//echo $sql;
$sql_query = $mysqli->query($sql);
$vc_budget=0;
while ($row = $sql_query->fetch_assoc()) {
	$vc_budget = $row['vc_budget'];
}

$sql = "SELECT DISTINCT vc_name FROM bid WHERE event_name='".$event."'";
$data = $mysqli->query($sql);

while ($row = $data->fetch_assoc()) {
	$sql = "SELECT nickname FROM user WHERE name='".$row['vc_name']."'";
	$sql_query = $mysqli->query($sql);
	$sql_data=$sql_query->fetch_assoc();
	echo $sql_data['nickname']." remaining funds: ";
        $sql = "SELECT SUM(investment) FROM bid WHERE vc_name='".
                $row['vc_name']."' AND accepted=1";
        $sql_query = $mysqli->query($sql);
        $row=$sql_query->fetch_assoc();
        if ($row['SUM(investment)'] != "") {
		echo "$".number_format($vc_budget - $row['SUM(investment)'],0)."<br>\n";
//                echo "$".number_format($row['SUM(investment)'], 0)."<br>\n";
        } else {
                echo "$".number_format($vc_budget,0)."<br>\n";
        }
}


//$sql = "SELECT DISTINCT startup_name FROM bid WHERE event_name='".$event."'";
//$data = $mysqli->query($sql);

//while ($row = $data->fetch_assoc()) {
//        echo $row['startup_name']." has received ";
//        $sql = "SELECT SUM(investment) FROM bid WHERE startup_name='".
//                $row['startup_name']."' AND accepted=1";
//        $sql_query = $mysqli->query($sql);
//        $row=$sql_query->fetch_assoc();
//        if ($row['SUM(investment)'] != "") {
//                echo "$".number_format($row['SUM(investment)'], 0)."<br>\n";
//        } else {
//                echo "$0.<br>\n";
//        }
//}

?>

