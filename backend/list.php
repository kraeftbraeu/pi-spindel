<?php
$data = array();
foreach(scandir("backups") as $file) {
    if(endsWith($file, ".json")) {
        array_push($data, $file);
    }
}
header('Content-type: application/json');
echo json_encode($data);

function endsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    if( !$length ) {
        return true;
    }
    return substr( $haystack, -$length ) === $needle;
}
?>
