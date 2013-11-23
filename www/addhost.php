<?php

include("db.inc.php");

$query=sprintf("INSERT into networks.hosts (address, hostname) VALUES ('%s', '%s')", $_REQUEST["address"], $_REQUEST["hostname"]);
$result=pg_exec($query);

header("Location: $_SERVER[HTTP_REFERER]");

?>
