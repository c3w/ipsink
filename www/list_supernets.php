  <HEAD>
    <LINK href="ipsink.css" rel="stylesheet" type="text/css">
  </HEAD>

<?php

include("db.inc.php");

if ($_REQUEST["delete"]) {
	$query=sprintf("DELETE from networks.supernets WHERE network='%s'", urldecode($_REQUEST["delete"]));
	$result=pg_exec($query);
}

$query = "SELECT network, description, is_supernet FROM networks.supernets ORDER by network";
$result = pg_exec($query);
?><table><th>cidr</th><th>description</th><th>type</th><th>actions</th><tr><?php
while ($row = pg_fetch_row($result)) {
	if ($row[2]=='t'){ $type="supernet"; }
	if ($row[2]=='f'){ $type="subnet"; }
	printf("<td>%s</td><td>%s</td><td>%s</td><td>[ <a href=?delete=%s>delete</a> ] [ <a href=?edit=%s>edit</a> ]", $row[0], $row[1], $type, urlencode($row[0]), urlencode($row[0]));
	if ($row[2] == 'f') {
		printf("[ <a href=getip.php?network=%s>get IP</a> ]</td><tr>", urlencode($row[0]));
	} else { printf("<tr>"); }
	printf("<br>");
}

?>
