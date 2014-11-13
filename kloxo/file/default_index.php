<?php
	// use default index.php
	if (!file_exists("./custom-index.php")) {
?>

<?php
	if (file_exists("./custom-inc.php")) {
		// use user-define inc.php -- no override when kloxo update
		$incfile = "./custom-inc.php";
	}
	else {
		// use default inc.php
		$incfile = "./inc.php";
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Kloxo Control Panel</title>
			<meta http-equiv="Content-Language" content="en-us">
			<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
			<link href="/htmllib/css/skin/default/missing-common.css" rel="stylesheet" type="text/css">
			<link href="/htmllib/lib/admin_login.css" rel="stylesheet" type="text/css">
	</head>
<body>

<table class="content">
	<tr>
		<td width="50">&nbsp;</td><td valign="top"><?php include_once $incfile; ?></td>
		<td width="280" valign="center"><img src="images/disableskeletonbg.gif"></td>
	</tr>
</table>

</body>

</html>

<?php
	}
	else {
		// use user-define index.php -- no override when kloxo update
		include_once "./custom-index.php";
	}
?>

