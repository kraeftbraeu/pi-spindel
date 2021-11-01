<?php

include("backend/sec.inc");
$u = $_SERVER['PHP_AUTH_USER'];
$p = $_SERVER['PHP_AUTH_PW'];
if($u != $userToCompare || $p != $passToCompare) {
	header("HTTP/1.1 401 Unauthorized");
	exit;
}

$now = urldecode($_GET['time']);
$json = json_decode(base64_decode($_GET['data']));
if(!$json) {
    die("no data found");
}

// log in sqlite
$dbFile = "backend/ispindel";
$dbTable = "ispindel";
$dbQuery = "INSERT INTO $dbTable (id, token, angle, temperature, battery, gravity, interval, rssi, datetime) "
    ."VALUES ("
    .$json->ID.", "
    ."'".$json->token."', "
    .$json->angle.", "
    .$json->temperature.", "
    .$json->battery.", "
    .$json->gravity.", "
    .$json->interval.", "
    .$json->RSSI.", "
    ."'".$now."'"
    .")";
$db = new SQLite3($dbFile);
$db->exec($dbQuery);
?>
