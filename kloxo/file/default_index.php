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


<!-- start: Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- end: Mobile Specific -->
	
	<!-- start: CSS -->
	<link id="bootstrap-style" href="/htmllib/rh14/css/bootstrap.min.css" rel="stylesheet">
	<link href="/htmllib/rh14/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link id="base-style" href="/htmllib/rh14/css/style.css" rel="stylesheet">
	<link id="base-style-responsive" href="/htmllib/rh14/css/style-responsive.css" rel="stylesheet">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>
	<!-- end: CSS -->



	</head>
<body>
<table class="header">
	<tr>
		<td valign="top" width="100%"><img class="logo" src="images/logo.png" height="75" alt="hosting-logo"></td>
		<td>
			<table class="content">
				<tr>
					<td><a href="http://lxcenter.org/" title="Go to LxCenter website"><img class="logo" src="images/lxcenter.png" alt="lxcenter-logo" width="120" height="35"></a></td>
				</tr>
				<tr>
					<td><a href="http://lxcenter.org/software/kloxo/" title="Go to Kloxo website"><img class="logo" src="images/kloxo.png" alt="kloxo-logo" width="120" height="27"></a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" bgcolor="#000000">&nbsp;</td>
	</tr>
</table>
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

