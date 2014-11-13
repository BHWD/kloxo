<?php 

include_once "htmllib/lib/displayinclude.php";

function domainshow()
{   
	global $gbl, $sgbl, $login, $ghtml; 

	if ($login->isAdmin()) {
		$doctype = "admin";
		$domainclass = "all_domaina";
	} else  {
		$doctype = "client";
		$domainclass = "domaina";
	}

	$url = "a=show";
	$url = $ghtml->getFullUrl($url);

	if (lxfile_exists("lbin/header_vendor.php")) {
		$file = "/lbin/header_vendor.php";
	} else {
		$file = "/lbin/header.php";
	}

	$sp = $login->getSpecialObject('sp_specialplay');

	if ($sp->isOn('lpanel_scrollbar')) {
		$lpscroll = 'auto';
	} else {
		$lpscroll = 'no';
	}

	if ($gbl->isOn('show_help')) {
		$scrollstring = 'scrolling=no';
		$width = $sgbl->__var_lpanelwidth;
	} else {
		$scrollstring = "scrolling=$lpscroll";
		$width = $sgbl->__var_lpanelwidth;
	}

    $title = get_title(true);
?>
<html>
<head>
<title><?php echo $title ?></title>
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<?php

	if ($login->getSpecialObject('sp_specialplay')->isOn('simple_skin')) {
		print("<FRAMESET frameborder=\"0\" rows=\"96%,*\"  border=\"0\">\n");
		print("\t<FRAME name=\"mainframe\" src=\"$url\">\n");
		print("\t<FRAME name=\"bottomframe\" src=\"/htmllib/lbin/bottom.php\">\n");
		return;
	}

    $headerheight = 29;

	if ($login->isDefaultSkin()) {
		$headerheight = 0;
	} else  {
		if ($login->getSpecialObject('sp_specialplay')->isOn('show_thin_header')) {
			$headerheight = 29;
		}
    }

	print("<FRAMESET frameborder=\"0\" rows=\"$headerheight,*\" border=\"0\">\n");
	print("\t<FRAME name=\"topframe\" src=\"$file\" scrolling=\"no\">\n");

	if (!$sp->isOn('split_frame')) { 
		print("<FRAMESET frameborder=\"0\" cols=\"$width,*\" border=\"0\">\n");
		print("\t<FRAME name=\"leftframe\" src=\"/htmllib/lbin/lpanel.php?lpanel_type=tree\" $scrollstring border=\"0\">\n");
	}

	if ($sp->isOn('split_frame')) {
		print("\t<FRAMESET frameborder=\"0\" cols=\"50%,*\" border=\"0\">\n");
	}
	print("<FRAMESET frameborder=\"0\" rows=\"96%,*\" border=\"0\">\n");
	print("\t<FRAME name=\"mainframe\" src=\"$url\">\n");
	print("\t<FRAME name=\"bottomframe\" src=\"/htmllib/lbin/bottom.php\">\n");

	if ($sp->isOn('split_frame')) {
		print("\t<FRAME name=\"rightframe\" src=\"$url\">\n");
	}
    print("</FRAMESET>\n");
	print("</FRAMESET>\n");
	print("</FRAMESET>\n");

?>

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
</body>
</html>
<?php
}

function main_main()
{
	global $gbl, $login, $ghtml; 

   	initProgram();

	domainshow();
}

redirect_to_https();
main_main();

