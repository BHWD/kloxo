<?php
/*
 * This file is used by the /login/ page.
 * Refactoring is needed. Wrong place, wrong name
 */

$accountlist = array('client' => "Kloxo Account",'domain' => 'Domain Owner', 'mailaccount' => "Mail Account");
$progname = $sgbl->__var_program_name;

$ghtml->print_jscript_source("/htmllib/js/lxa.js");
if ($sgbl->is_this_slave()) { print("Slave Server\n"); exit; }

$logfo = db_get_value("general",  "admin", "login_pre");
$logfo = str_replace("<%programname%>", $sgbl->__var_program_name, $logfo);

if(!$cgi_forgotpwd ){
    $ghtml->print_message();


    if (if_demo()) {
        include_once "lib/demologins.php";
    } else {
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
<style type="text/css">
            body { background: url(/htmllib/rh14/img/bg-login.jpg) !important; }
        </style>


<div class="container-fluid-full">
        <div class="row-fluid">
                    
            <div class="row-fluid">
                <div class="login-box">
                    <div class="icons">
                        <a href="index.html"><i class="halflings-icon home"></i></a>
                        <a href="#"><i class="halflings-icon cog"></i></a>
                    </div>
                    <H2 lass="text-center"><?=$logfo?></H2>
                   <form  class="form-horizontal" name="loginform" action="/htmllib/phplib/" onsubmit="encode_url(loginform) ; return fieldcheck(this);" method="post">



<div class="input-prepend" title="Username">
                                <span class="add-on"><i class="halflings-icon user"></i></span>
                                <input class="input-large span10" name="frm_clientname" id="username" type="text" placeholder="type username"/>
                            </div>
<div class="clearfix"></div>

<div class="input-prepend" title="Password">
                                <span class="add-on"><i class="halflings-icon lock"></i></span>
                                <input class="input-large span10" name="frm_password" id="password" type="password" placeholder="type password"/>
                            </div>
<div class="clearfix"></div>
<input type="hidden" name="id" value="<?php echo mt_rand() ?>" />
<div class="clearfix"></div>
<div class="button-login">  
                                <button type="submit" name="login" class="btn btn-primary">Login</button>
                            </div>
                            <div class="clearfix"></div>
                    
                    </form>
                    <hr>
                    <h3>Forgot Password?</h3>
                    <p>
                        No problem, <a class="forgotpwd" href="javascript:document.forgotpassword.submit()">click here</a> to get a new password.
                    </p>    
<form name="forgotpassword" method="post" action="/login/">
                        <input type="hidden" name="frm_forgotpwd" value="1" />
                    </form>
                    <script> document.loginform.frm_clientname.focus(); </script>
                    
                <div class="clr"></div>
            </div>
                </div><!--/span-->
            </div><!--/row-->
            

    </div><!--/.fluid-container-->



        <div id="ctr" align="center">
            <div class="login">
                <div class="login-form">
                    <div align="center"><font size="5" color="red"><b> Login </b></font></div>
                    <br />
                    <form name="loginform" action="/htmllib/phplib/" onsubmit="encode_url(loginform) ; return fieldcheck(this);" method="post">
                        <div class="form-block">
                            <div class="inputlabel">Username</div>
                            <input name="frm_clientname" type="text" class="inputbox" size="30" />
                            <div class="inputlabel">Password</div>
                            <input name="frm_password" type="password" class="passbox" size="30" />
                            <br />
                            <input type="hidden" name="id" value="<?php echo mt_rand() ?>" />
                            <div align="left"><input type="submit" class="button" name="login" value="Login" /></div>
                        </div>
                    </form>
                </div>
                
        </div>
        <div id="break"></div> 

    <?php

    }


}
elseif ($cgi_forgotpwd == 1) {
    ?>

    <div id="ctr" align="center">
        <div class="login">
            <div class="login-form">
                <div align="center"><font name=Verdana size=5 color=red ><b> Forgot Password </b></font></div>
                <br />
                <form name="sendmail" action="/login/" method="post">
                    <div class="form-block">
                        <div class="inputlabel">Username</div>
                        <input name="frm_clientname" type="text" class="inputbox" size="30" />
                        <div class="inputlabel">Email Id</div>
                        <input name="frm_email" type="text" class="passbox" size="30" />
                        <br />
                        <div align="left"><input type="submit" class="button" name="forgot" value="Send" /></div>
                    </div>
                    <input type="hidden" name="frm_forgotpwd" value="2" />
                </form>
            </div>
            <div class="login-text">
                <div class="ctr"><img border=0 src="/img/login/icon1.gif" width="64" height="64" alt="security" /></div>
                <p>Welcome to <?php echo  $sgbl->__var_program_name; ?></p>
                <p>Use a valid username and email-id to get password.</p>
                <br />
                <a class=forgotpwd href="javascript:history.go(-1);"><font color="black"><u>Back to login</u></a>
            </div>

            <script> document.sendmail.frm_clientname.focus(); </script>

            <div class="clr"></div>
        </div>
    </div>
    <div id="break"></div>

<?php
} elseif ($cgi_forgotpwd==2) {

    $progname = $sgbl->__var_program_name;
    $cprogname = ucfirst($progname);

    $cgi_clientname = $ghtml->frm_clientname;
    $cgi_email = $ghtml->frm_email;


    htmllib::checkForScript($cgi_clientname);
    $classname = $ghtml->frm_class;

    if (!$classname) {
        $classname = getClassFromName($cgi_clientname);
    }

    if (!empty($cgi_clientname) && !empty($cgi_email)) {
        $tablename = $classname;
        $database = new Sqlite(null, $tablename);

        $data = $database->rawQuery("select contactemail from $tablename where nname = '$cgi_clientname';");

        if(empty($data)) {
            $ghtml->print_redirect("/login/?frm_emessage=nouser_email");
            //throw lxException('Contact email is not set on Kloxo, it could not be send to a empty address.');
        }
        elseif(!isset($data[0]['contactemail'])) {
            $ghtml->print_redirect("/login/?frm_emessage=nouser_email");
            //throw lxException('Contact email is not set on Kloxo, it could not be send to a empty address.');
        }
        else {
            $contact_email = $data[0]['contactemail'];

            if(empty($contact_email)) {
                //throw lxException('Contact email is not set on Kloxo, it could not be send to a empty address.');
                $ghtml->print_redirect("/login/?frm_emessage=nouser_email");
            }
        }

        if($cgi_email == $contact_email) {
            $rndstring =  randomString(8);
            $pass = crypt($rndstring);

            $database->rawQuery("update $tablename set password = '$pass' where nname = '$cgi_clientname'");

            $subject = "$cprogname Password Reset Request";

            $message = "\n\n\nYour password has been reset to the one below for your $cprogname login.\n" .
                "The Client IP address which requested the Reset: {$_SERVER['REMOTE_ADDR']}\n" .
                'Username: '. $cgi_clientname."\n" .
                'New Password: '. $rndstring;

            $from = NULL; //Setting NULL gets the program@hostname.com
            lx_mail($from, $contact_email, $subject, $message);

            $ghtml->print_redirect("/login/?frm_smessage=password_sent");

        } else {
            $ghtml->print_redirect("/login/?frm_emessage=nouser_email");
        }
    }
}
