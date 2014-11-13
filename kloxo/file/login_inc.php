<script language="javascript" src="/htmllib/js/login.js"></script>
<script language="javascript" src="/htmllib/js/preop.js"></script>

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
<?php 

chdir("..");
include_once "htmllib/lib/displayinclude.php";

init_language();
$cgi_clientname = $ghtml->frm_clientname; 
$cgi_class = $ghtml->frm_class; 
$cgi_password = $ghtml->frm_password;
$cgi_forgotpwd = $ghtml->frm_forgotpwd; 
$cgi_email = $ghtml->frm_email;

$cgi_classname = 'client';
if ($cgi_class) {
	$cgi_classname = $cgi_classname;
}
ob_start();
include_once "htmllib/lib/indexcontent.php";


