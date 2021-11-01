<?php
$dbFile = "ispindel";
$dbTable = "ispindel";

$token = $_GET["token"];
if(!$token) {
    ?>no token found - valid tokens are:<ul><?php
    $dbQuery = "SELECT DISTINCT token FROM ".$dbTable;
    $db = new SQLite3($dbFile);
    $results = $db->query($dbQuery);
    while($row = $results->fetchArray(SQLITE3_ASSOC)) {
        ?><li><?php echo $row["token"] ?></li><?php
    }
    ?></ul><?php
    exit;
}
$fields = $_GET["fields"];
if(!$fields) {
    $fields = "angle, temperature, battery, gravity, interval, rssi";
}

$data = array();
$dbQuery = "SELECT ".$fields.", datetime FROM ".$dbTable." WHERE token ='".$token."' ORDER BY datetime";
$db = new SQLite3($dbFile);
$results = $db->query($dbQuery);
if(!$results) {
    ?>database not available<?php
    exit;
}
while($row = $results->fetchArray(SQLITE3_ASSOC)) {
    array_push($data, $row);
}

header('Content-type: application/json');
echo json_encode($data);
?>
