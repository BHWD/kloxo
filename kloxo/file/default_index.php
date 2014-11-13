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
	<!-- start: JavaScript-->

		<script src="/htmllib/rh14/js/jquery-1.9.1.min.js"></script>
	<script src="/htmllib/rh14/js/jquery-migrate-1.0.0.min.js"></script>
	
		<script src="/htmllib/rh14/js/jquery-ui-1.10.0.custom.min.js"></script>
	
		<script src="/htmllib/rh14/js/jquery.ui.touch-punch.js"></script>
	
		<script src="/htmllib/rh14/js/modernizr.js"></script>
	
		<script src="/htmllib/rh14/js/bootstrap.min.js"></script>
	
		<script src="/htmllib/rh14/js/jquery.cookie.js"></script>
	
		<script src='/htmllib/rh14/js/fullcalendar.min.js'></script>
	
		<script src='/htmllib/rh14/js/jquery.dataTables.min.js'></script>

		<script src="/htmllib/rh14/js/excanvas.js"></script>
	<script src="/htmllib/rh14/js/jquery.flot.js"></script>
	<script src="/htmllib/rh14/js/jquery.flot.pie.js"></script>
	<script src="/htmllib/rh14/js/jquery.flot.stack.js"></script>
	<script src="/htmllib/rh14/js/jquery.flot.resize.min.js"></script>
	
		<script src="/htmllib/rh14/js/jquery.chosen.min.js"></script>
	
		<script src="/htmllib/rh14/js/jquery.uniform.min.js"></script>
		
		<script src="/htmllib/rh14/js/jquery.cleditor.min.js"></script>
	
		<script src="/htmllib/rh14/js/jquery.noty.js"></script>
	
		<script src="/htmllib/rh14/js/jquery.elfinder.min.js"></script>
	
		<script src="/htmllib/rh14/js/jquery.raty.min.js"></script>
	
		<script src="/htmllib/rh14/js/jquery.iphone.toggle.js"></script>
	
		<script src="/htmllib/rh14/js/jquery.uploadify-3.1.min.js"></script>
	
		<script src="/htmllib/rh14/js/jquery.gritter.min.js"></script>
	
		<script src="/htmllib/rh14/js/jquery.imagesloaded.js"></script>
	
		<script src="/htmllib/rh14/js/jquery.masonry.min.js"></script>
	
		<script src="/htmllib/rh14/js/jquery.knob.modified.js"></script>
	
		<script src="/htmllib/rh14/js/jquery.sparkline.min.js"></script>
	
		<script src="/htmllib/rh14/js/counter.js"></script>
	
		<script src="/htmllib/rh14/js/retina.js"></script>

		<script src="/htmllib/rh14/js/custom.js"></script>
	<!-- end: JavaScript-->
</body>

</html>

<?php
	}
	else {
		// use user-define index.php -- no override when kloxo update
		include_once "./custom-index.php";
	}
?>

