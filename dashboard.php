<?php

$sql = "SELECT DISTINCT vc_name FROM bid WHERE event_name='".$event."'";
$data = $mysqli->query($sql);

while ($row = $data->fetch_assoc()) {
        echo $row['vc_name']." has invested ";
        $sql = "SELECT SUM(investment) FROM bid WHERE vc_name='".
                $row['vc_name']."' AND accepted=1";
        $sql_query = $mysqli->query($sql);
        $row=$sql_query->fetch_assoc();
        if ($row['SUM(investment)'] != "") {
                echo "$".number_format($row['SUM(investment)'], 0)."<br>\n";
        } else {
                echo "$0.<br>\n";
        }
}

$sql = "SELECT DISTINCT startup_name FROM bid WHERE event_name='".$event."'";
$data = $mysqli->query($sql);

while ($row = $data->fetch_assoc()) {
        echo $row['startup_name']." has received ";
        $sql = "SELECT SUM(investment) FROM bid WHERE startup_name='".
                $row['startup_name']."' AND accepted=1";
        $sql_query = $mysqli->query($sql);
        $row=$sql_query->fetch_assoc();
        if ($row['SUM(investment)'] != "") {
                echo "$".number_format($row['SUM(investment)'], 0)."<br>\n";
        } else {
                echo "$0.<br>\n";
        }
}

?>

