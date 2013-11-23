  <HEAD>
    <LINK href="ipsink.css" rel="stylesheet" type="text/css">
  </HEAD>

<?php

include("db.inc.php");

if ($_REQUEST["submit"]=="insert") {
	// VALIDATE and INSERT into DB
	$query=sprintf("INSERT into networks.supernets ( network, description, is_supernet) VALUES ('%s', '%s', '%s')", $_REQUEST["cidr"], $_REQUEST["description"], $_REQUEST["is_supernet"]);
	$result=pg_exec($query) or die("query failed" . pg_last_error($dbconn)) ;
}

if ($_REQUEST["submit"]=="update") {
	$query=sprintf("UPDATE networks.supernets SET network='%s',description='%s',is_supernet='%s' WHERE network='%s'", $_POST["cidr"], $_REQUEST["description"], $_REQUEST["is_supernet"], $_REQUEST["cidr_orig"]);
$result=pg_exec($query) or die("query failed" . pg_last_error($dbconn)) ;
}

if ($_REQUEST["edit"]) {

$query=sprintf("SELECT network, description, is_supernet FROM networks.supernets WHERE network='%s'", urldecode($_REQUEST["edit"]));
$result=pg_exec($query);
$row = pg_fetch_row($result);
$cidr=$row[0];
$description=$row[1];
$is_supernet=$row[2];
}

?>
<form method=post action=/ipsink/>
<table border=1>
<th>cidr</th><th>description</th><th>!</th><tr>
<td><input type=hidden name=cidr_orig value='<?php print($cidr);?>'><input size=20 name=cidr value=<?php print($cidr);?>></td>
<td><input size=20 name=description value='<?php print($description);?>'><select name=is_supernet><option value='TRUE' <?php if ($is_supernet=='t'){echo "selected";}?>>supernet</option><option value='FALSE' <?php if ($is_supernet=='f'){echo "selected";}?>>subnet</option></td>
<td><input type=submit name=submit value='<?php if ($_REQUEST["edit"]){ echo "update";} else { echo "insert";}?>'></td>
</table>
</form>
