<?php

include("nav.php");

include("db.inc.php");


if ($_REQUEST["network"]) {

	include("listhosts.php");

?><table><th>address</th><th>hostname</th><tr><td><?php
	$query=sprintf("SELECT networks.nextips_for('%s');", urldecode($_REQUEST["network"]));
	$result=pg_exec($query) or die ("barf");
	?><form action=addhost.php><td><select name=address><?php
	while ( $row = pg_fetch_row($result) ) {
		printf("<option value='%s'>%s</option>", $row[0], $row[0]);
	}
	printf("<input type=hidden value='%s'><input size=40 name='hostname'></td><td><input type=submit value='add'>", $_REQUEST["network"]);
	?></select></td></form></td></table><?php
}

?>
