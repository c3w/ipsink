  <HEAD>
    <LINK href="ipsink.css" rel="stylesheet" type="text/css">
  </HEAD>

<?php

include("nav.php");

include("db.inc.php");

if ($_REQUEST["submit"]) {
	// VALIDATE and INSERT into DB
	$query=sprintf("INSERT into networks.supernets ( network, description, is_supernet) VALUES ('%s', '%s', '%s')", $_REQUEST["cidr"], $_REQUEST["description"], $_REQUEST["is_supernet"]);
	$result=pg_exec($query) or die("query failed" . pg_last_error($dbconn)) ;
}

?>

<?php
include("list_supernets.php");
?>
