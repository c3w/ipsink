<?php

$conn_str = "host=localhost port=5432 dbname=ipsink user=postgres password=postgres";
$dbconn = pg_connect($conn_str);

if (!$dbconn) {	 die ("connection to postgres failed" . pg_last_error($dbconn)); }

?>
