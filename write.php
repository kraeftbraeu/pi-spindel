<?php

$json = json_decode(file_get_contents('php://input'), true);
if(!$json) {
    die("no data found");
}
//file_put_contents("1-json.log", json_encode($json, JSON_PRETTY_PRINT));

$dbFile = "ispindel";
$dbTable = "ispindel";
$now = (new DateTime())->format('Y-m-d H:i:s');
$dbQuery = "INSERT INTO $dbTable (id, token, angle, temperature, battery, gravity, interval, rssi, datetime) "
    ."VALUES ("
    .$json['ID'].", "
    ."'".$json['token']."', "
    .$json['angle'].", "
    .$json['temperature'].", "
    .$json['battery'].", "
    .$json['gravity'].", "
    .$json['interval'].", "
    .$json['RSSI'].", "
    ."'".$now."'"
    .")";
//file_put_contents("1-query.log", $dbQuery);
$db = new SQLite3($dbFile);
$db->exec($dbQuery);

?>