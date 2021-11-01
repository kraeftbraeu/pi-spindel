<?php

$doit = $_GET['doit'];
if(!$doit) {
    die("no data found");
}
$dbFile = "ispindel";
$dbTable = "ispindel";
$db = new SQLite3($dbFile);
$where = "date(julianday(datetime)) < date(julianday(date('now')) - 14)";

// backup
$backup = "backups/".date("Y-m-d").".json";
$data = array();
$results = $db->query("SELECT * FROM ".$dbTable." WHERE ".$where." ORDER BY datetime");
if(!$results) {
    ?>database not available<?php
    exit;
}
while($row = $results->fetchArray(SQLITE3_ASSOC)) {
    array_push($data, $row);
}

echo count($data)." datasets to backup...";

file_put_contents($backup, json_encode($data));

$db->exec("DELETE FROM ".$dbTable." WHERE ".$where);

?>
<h1>Done</h1>
<p>Removed everything older than 2 weeks</p>
<p><a href="<?php echo $backup; ?>">Backup</a></p>
