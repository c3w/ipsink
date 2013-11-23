
<?php

include("db.inc.php");

if ($_REQUEST["delete"]) {
	$query=sprintf("DELETE from networks.hosts WHERE address='%s'", $_REQUEST["delete"]);
	$res=pg_exec($query);
	header("Location: $_SERVER[HTTP_REFERER]");
}

$query=sprintf("
SELECT x.hostaddr, x.netaddr, x.description, x.hostname FROM (
  SELECT
      s.address AS hostaddr,
      s.hostname AS hostname,
      n.network AS netaddr,
      n.description AS description,
      n.network AS network,
      row_number() OVER (
          PARTITION BY s.address
          ORDER BY masklen(n.network) DESC
      ) AS row
  FROM networks.hosts s
  LEFT JOIN networks.supernets n
  ON s.address << n.network
) x          
WHERE x.row = 1
");
//", urldecode($_REQUEST["network"]));

$result=pg_exec($query);
?><table border=1><th>address</th><th>subnet</th><th>desc</th><th>hostname</th><th>!</th><tr><?php
while ($row = pg_fetch_row($result)) {
	if ($row[1]==urldecode($_REQUEST["network"])) {
	printf("<td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>[ <a href=?delete=%s&network=%s>delete</a> ]</td><tr>", $row[0], $row[1], $row[2], $row[3], $row[0], $_REQUEST["network"]);
}
}

?>
