<?php


class webmisc_b extends Lxaclass {
static $__desc_execcgi = array("f", "", "enable_cgi_in_documentroot");
static $__desc_dirindex = array("f", "", "enable_directory_index");
static $__desc_disable_openbasedir = array("f", "", "disable_openbasedir");
}

class aspnetconf_b extends lxaclass {
}


class phpconfig_b extends Lxaclass {
	static $__desc_fcgi_num = array("", "",  "number_of_fcgi_process");
	static $__desc_exec_type = array("", "",  "exec_type");

}


class webindexdir_a extends Lxaclass {
static $__desc = array("", "",  "Indexed Directory");
static $__desc_nname = array("n", "",  "Location");

static function createListAlist($parent, $class)
{
	$alist[] = "a=list&c=$class";
	$alist[] = "a=addform&c=$class";
	return $alist;
}

static function addform($parent, $class, $typetd = null)
{
	$vlist['nname'] = array('L', "/www/");
	$res['variable'] = $vlist;
	$res['action'] =  'add';
	return $res;
}
}

class Redirect_a extends LxaClass {

static $__desc = array("", "",  "redirect");
static $__desc_nname = array("n", "",  "virtual_location", "a=show");
static $__desc_httporssl = array("n", "",  "http_or_ssl");
static $__desc_ttype = array("e", "",  "type");
static $__desc_ttype_v_local = array("e", "",  "local_redirection");
static $__desc_ttype_v_remote = array("e", "",  "remote_redirection");
static $__desc_redirect = array("n", "",  "redirected_location");

static function createListAlist($parent, $class)
{
	$alist[] = "a=list&c=$class";
	$alist['__v_dialog_alocal'] = "a=addform&dta[var]=ttype&dta[val]=local&c=$class";
	$alist['__v_dialog_aremote'] = "a=addform&&dta[var]=ttype&dta[val]=remote&c=$class";
	return $alist;

}

function updateform($subaction, $param)
{
	$vlist['nname'] = array('M', null);
	$vlist['redirected_location'] = null;
	return $vlist;
}

static function createListAddForm($parent, $class)
{
	return false;
}

function getSpecialParentClass()
{
	return 'domain';
}

static function createListNlist($parent, $view)
{
	$nlist['httporssl'] = '5%';
	$nlist['ttype'] = '5%';
	$nlist['nname'] = '100%';
	$nlist['redirect'] = '40%';
	return $nlist;

}

// The virtual domain redirect is handled differently from the forward domain 'redirect permanent'. the virtual domain ones are never edited, but rather listed and deleted, while the forward one is directly edited. So for the virtual domain ones, the 'http://' is automatically added and stored in the db itself, while for forward domain redirect_domain variable, the 'http://' is added is only added at the time of synctosystem. The 'http//' is essential, since if it is not present, apache will refuse to start at all. Dangerous.

static function add($parent, $class, $param)
{
	$ttype = $param['ttype'];
	$redirect = $param['redirect'];

	if ($ttype == 'remote') {
		if (!csb($redirect, "http")) {
			$redirect = "http://" . $redirect;
		}
	}

	$param['redirect'] = $redirect;
	return $param;
}

static function checkForPort($port, $httporssl)
{
	if ($port === '80' && $httporssl === 'https') { return false ;}
	if ($port === '443' && $httporssl === 'http') { return false; }
	return true;
}

static function addform($parent, $class, $typetd = null)
{

	if ($typetd['val'] === 'remote') {
		//$httporssl = array('var' => 'httporssl', 'val' => array('s', array('http', 'https', 'both')));
		$vlist['httporssl'] = array('s', array('both', 'http', 'https'));
	}

	$vlist['nname'] = array('m', array('pretext' => "$parent->nname/"));
	if ($typetd['val'] === 'local') {
		$vlist['redirect'] = array('L', "/");
	} else {
		$vlist['redirect'] = array('m', null);
	}
	$ret['action'] = 'add';
	$ret['variable'] = $vlist;
	return $ret;

}

}

class SubWeb_a  extends LxaClass {

static $__desc = array("", "",  "simple_sub_domain");
static $__desc_nname = array("", "",  "sub_domain_name", "__stub");
static $__desc_redirect_url = array("", "",  "redirect");
static $__desc_directory = array("", "",  "redirect");

function getStubUrl($name)
{
	return "a=show&l[class]=ffile&l[nname]=/subdomains/$this->nname";
}

function postAdd()
{
	$web = $this->getParentO();
	$domain = $web->getParentO();
	$dns = $domain->getObject('dns');
	$dns->addRec("cn", $this->nname, "__base__");
	try {
		$dns->was();
	} catch (exception $e) {
		throw new lxException("subdomain_not_added_due_to_dns_conflict", 'nname', $this->nname);
	}
	validate_domain_name("{$this->nname}.$web->nname");
}

static function perPage()
{
	return '50000';
}

static function createListAlist($parent, $class)
{
	$alist[] = "a=list&c=$class";
	//$alist[] = "n=web&a=addform&c=$class";
	return $alist;

}

static function createListAddForm($parent, $class)
{
	return false;

}

static function addform($parent, $class, $typetd = null)
{
	$vlist['nname'] = array('m', array('posttext' => ".$parent->nname"));
	$ret['variable'] = $vlist;
	$ret['action'] = 'add';
	return $ret;
}

}


class Customerror_b extends lxaClass {
   	static $__desc_url_400 = array("", "",  "_400_(bad_request)"); 
	static $__desc_url_401 = array("", "",  "401_(authorization_required)");
	static $__desc_url_403 = array("", "",  "403_(forbidden)");
	static $__desc_url_404 = array("", "",  "404_(not_found)");
	static $__desc_url_500 = array("", "",  "500_(internal_server_error)");
}

class Server_Alias_a extends Lxaclass {

	static $__desc = array("", "",  "server_alias");
	static $__desc_nname = array("", "",  "server_alias");

function postAdd()
{
	$web = $this->getParentO();
	$domain = $web->getParentO();
	$dns = $domain->getObject('dns');

	if (isset($dns->dns_record_a['a___base__'])) {
		$ip = $dns->dns_record_a['a___base__']->param;
		$dns->addRec("a", $this->nname, $ip);
	} else {
		$dns->addRec("cn", $this->nname, "__base__");
	}

	$this->setUpdateSubaction('subdomain');

	try {
		$dns->was();
	} catch (exception $e) {
		throw new lxException("alis_not_added_due_to_dns_conflict", 'nname', $this->nname);
	}
}

static function createListAddForm($parent, $class)
{
	return true;

}

static function perPage()
{
	return '50000';
}
static function addform($parent, $class, $typetd = null)
{
	$vlist['nname'] = array('m', array('posttext' => ".$parent->nname"));
	$ret['variable'] = $vlist;
	$ret['action'] = 'add';
	return $ret;
}

static function createListAlist($parent, $class)
{
	$alist[] = "a=list&c=$class";
	return $alist;

}

}

class Web extends Lxdb {

// Core
static $__desc = array("S", "",  "web");

//Mysql
//static $__desc_ddate = array("", "",  "date");
//static $__desc_nname	 = array("", "",  "[%s]_name", URL_SHOW); 
//static $__desc_subdomain_name= array("", "",  "sub_domain");
static $__desc_ttype = array("", "",  "");
static $__desc_nname = array("", "",  "domain_name");
static $__desc_username = array("", "",  "user_name");
static $__desc_text_extra_tag = array("t", "",  "extra_tags");
static $__desc_customerror_b = array("", "",  "the_db_list");
static $__desc_redirect_domain= array("", "",  "redirection_domain");
//static $__desc_iisid = array("", "",  "iis_site_id");
static $__desc_syncserver = array("sd", "",  "web_server");
static $__desc_ipaddress= array("s", "",  "ip_address");
//static $__desc_cron_mailto = array("", "",  "mail_to");
static $__desc_status  = array("e", "",  "s");
static $__desc_status_v_on  = array("", "",  "enabled"); 
static $__desc_status_v_off  = array("", "",  "disabled"); 
static $__desc_stats_username  =  array("", "",  "statistics_page_user");
static $__desc_stats_password  =  array("", "",  "statistics_page_password");
static $__desc_remove_processed_stats  =  array("f", "",  "remove_processed_logs");
static $__desc_lighty_pretty_app_f  =  array("", "",  "application");
static $__desc_indexfile_list  =  array("", "",  "index_file_order");
static $__desc_lighty_pretty_path_f  =  array("n", "",  "installed_path");
static $__desc_hotlink_flag = array("f", "",  "enable_hotlink_protection");
static $__desc_text_hotlink_allowed = array("", "",  "allowed_domains_(one_per_line)");
static $__desc_hotlink_redirect = array("", "",  "redirect_to_(img)");
static $__desc_fcgi_children =  array("f", "",  "use_php_fcgi_children");
static $__desc_text_blockip =  array("t", "",  "block_ip");
static $__desc_docroot =  array("", "",  "document_root");
static $__desc_email =  array("", "",  "email");
static $__desc_selist =  array("", "",  "search_engine_list");
static $__desc_force_www_redirect = array("f", "", "force_redirect_domain.com_to_www.domain.com");


static $__desc_ssl_flag = array("q", "",  "enable_ssl_(only_on_linux)");
static $__desc_awstats_flag = array("q", "",  "enable_awstats");
static $__desc_dotnet_flag = array("q", "",  "enable_asp.net_(windows_only)");
static $__desc_frontpage_flag = array("q", "",  "enable_frontpage");
static $__desc_cron_manage_flag = array("q", "",  "allow_scheduler_management");
static $__desc_installapp_flag = array("q", "",  "enable_installapp");
static $__desc_text_lighty_rewrite = array("t", "",  "lighttp_rewrite_rule");
//static $__desc_subweb_a_num = array("q", "",  "number_of_subdomains");
static $__desc_cron_minute_flag = array("q", "",  "allow_minute_management_for_cron");
static $__desc_cgi_flag =  array("q", "",  "enable_cgi");
static $__desc_php_flag =  array("q", "",  "enable_php");
//static $__desc_php_manage_flag =  array("q", "",  "enable_php_management");
static $__desc_phpfcgi_flag  	 = array("q", "",  "a");
static $__desc_phpfcgiprocess_num  	 = array("hq", "a",  "");
static $__desc_rubyfcgiprocess_num  	 = array("q", "",  "");
static $__desc_ftpuser_num  	 = array("q", "a",  "");
static $__desc_rubyrails_num  	 = array("q", "a",  "");
//static $__desc_inc_flag =  array("q", "",  "enable_server_side_includes");
static $__desc_phpunsafe_flag = array("q", "",  "can_enable_php_unsafe_mode");
static $__desc_disk_usage= array("D", "",  "quota");
static $__desc_subweb_a = array("q", "",  "subdomain");
static $__desc_redirect_a = array("", "",  "redirect");
static $__desc_server_alias_a = array("", "",  "");
//static $__desc_uuser_o = array('Rvqdtb', '', '', '');
//static $__desc_aspnet_o = array('db', '', '', '');
static $__desc_ffile_o = array('', '', '', '');
static $__desc_dirprotect_l = array('db', '', '', '');
static $__desc_ftpuser_l = array("Rqdtb", "",  "");
static $__desc_installappsnapshot_l = array("d", "",  "");
static $__desc_component_l = array("", "",  "");
static $__desc_rubyrails_l = array("qdb", "",  "");
static $__desc_odbc_l = array("db", "",  "");
static $__desc_davuser_l = array("db", "",  "");
static $__desc_phpini_o = array("db", "",  "");
static $__desc_cron_l = array("db", "",  "");
static $__desc_installapp_l = array("db", "",  "");
static $__desc_allinstallapp_l = array("", "",  "");
static $__desc_ftpsession_l = array("v", "",  "");

static $__acdesc_update_permalink = array("", "",  "permalink");
static $__acdesc_update_sesubmit = array("", "",  "search_engine");
static $__acdesc_update_blockip = array("", "",  "block_ip");
static $__acdesc_update_dirindex = array("", "",  "index_manager");
static $__acdesc_update_hotlink_protection = array("", "",  "hotlink_protection");
static $__acdesc_update_extra_tag = array("", "",  "add_extra_tags");
static $__acdesc_update_phpinfo = array("", "",  "phpinfo");
static $__acdesc_update_docroot = array("", "",  "document_root");
static $__acdesc_update_run_stats = array("", "",  "run_stats");
static $__acdesc_update_lighty_rewrite = array("", "",  "lighttpd_rewrite_rule");
static $__acdesc_update_cron_mailto = array("", "",  "cron_mail");
static $__acdesc_update_custom_error = array("", "",  "error_handlers");
static $__acdesc_update_fcgi_config = array("", "",  "fcgi_configuration");
static $__acdesc_update_ssl_config_m = array("", "",  "ssl_config");
static $__acdesc_update_ssl_create = array("", "",  "create_certificate");
static $__acdesc_update_ssl_upload = array("", "",  "upload_certificate");
static $__acdesc_update_frontpage_admin = array("", "",  "frontpage_admin");
static $__acdesc_update_ipaddress = array("", "",  "ipaddress");
static $__acdesc_update_enable_ssl_flag = array("", "",  "enable_ssl");
static $__acdesc_update_aspnet_parameters = array("", "",  "configure_asp.net");
static $__acdesc_update_enable_dotnet_flag = array("", "",  "enable/disable_asp.net");
static $__acdesc_update_enable_frontpage_flag = array("", "",  "enable/disable_frontpage");
static $__acdesc_update_redirect_domain =  array("","",  "redirect_url"); 
static $__acdesc_update_statsconfig =  array("","",  "stats_configuration"); 
static $__acdesc_update_access_log =  array("","",  "access_log"); 
static $__acdesc_update_php_log =  array("","",  "PHP_log"); 
static $__acdesc_update_error_log =  array("","",  "error_log"); 
static $__acdesc_update_show_stats = array("", "",  "show_stats");
static $__acdesc_update_stats_protect =  array("","",  "stats_page_protection"); 
static $__acdesc_update_configure_misc =  array("","",  "misc_config"); 
static $__acdesc_update_phpconfig =  array("","",  "configure_php"); 
static $__acdesc_show =  array("","",  "web"); 
static $__acdesc_graph_webtraffic	 = array("", "",  "web_traffic");
static $__acdesc_update_image_manager =   array("", "",  "image_manager"); 
static $__desc_logo_manage_flag =  array("q", "",  "can_change_logo");

function createExtraVariables()
{
	global $gbl, $sgbl, $login, $ghtml; 

	$gen = $login->getObject('general')->generalmisc_b;
	$port = $login->getObject('general')->portconfig_b;

	$webstatsprog = $gen->webstatisticsprogram;
	if (!$webstatsprog) { $webstatsprog = "awstats"; }
	$this->__var_statsprog = $webstatsprog;



	$ol = array("index.php", "index.html", "index.shtml", "index.htm", "default.htm",  "Default.aspx", "Default.asp", "index.pl");
	$dirin = $login->getObject('genlist')->dirindexlist_a;
	$list = get_namelist_from_objectlist($dirin);
	$this->__var_index_list = lx_array_merge(array($list, $ol));

	$this->__var_sslport = $port->sslport;
	if (!$this->__var_sslport) $this->__var_sslport = "7777";
	$this->__var_nonsslport = $port->nonsslport;
	if (!$this->__var_nonsslport) $this->__var_nonsslport = "7778";

	if (!$this->docroot) { $this->docroot = $this->nname; }
	if (!$this->corelocation) { $this->corelocation = "__path_customer_root"; }

	$this->__var_extrabasedir = $gen->extrabasedir;
	$this->__var_dirprotect = $this->getList("dirprotect");




	if (!$this->isDeleted()) {
		if ($this->getParentO()) {
			$parent = $this->getParentO()->getParentO();
		}
		if ($parent) {
			$this->__var_disable_url = $parent->disable_url;
		}
	}

	//$dvlist = $this->getList('davuser');

	$dvlist = null;
	foreach((array) $dvlist as $v) {
		$ndvlist[$v->directory][] = null;
	}
	//$this->__var_davuser = $ndvlist;
	$this->__var_davuser = null;

	if (!$this->customer_name) {
		if ($this->getRealClientParentO()) {
			$this->customer_name = $this->getRealClientParentO()->getPathFromName();
		}
	}

	$this->__var_railspp = $this->getList('rubyrails');


	$mydb = new Sqlite($this->__masterserver, 'ipaddress');
	$syncserver = $this->syncserver? $this->syncserver: 'localhost';
	$condition = 'syncserver = :syncserver';
	$params = array(':syncserver' => $syncserver);
	$this->__var_ipssllist = $mydb->getRowsWhere($condition, $params, array('ipaddr', 'nname'));


	$this->__var_addonlist = $this->getTrueParentO()->getList('addondomain');


	if (!isset($this->__var_sysuserpassword)) {
		$this->__var_sysuserpassword['realpass'] = $this->getParentO()->realpass;
	}

	$dipdb = new Sqlite(null, "domainipaddress");
	$domainip = $dipdb->getRowsWhere($condition, $params, array('domain', 'ipaddr'));
	$this->__var_domainipaddress = get_namelist_from_arraylist($domainip, 'ipaddr', 'domain');

	$ipdb = new Sqlite($this->__masterserver, 'ipaddress');
	$iplist = $ipdb->getRowsWhere($condition, $params, array('ipaddr'));
	$this->__var_ipaddress = $iplist;
	$mydb = new Sqlite($this->__masterserver, "web");
	
	 
	/* This is to ensure that the excess domainipaddress entries are filtered out.
	$siplist = get_namelist_from_arraylist($iplist, 'ipaddr');
	foreach($this->__var_domainipaddress as $k) {
		if (!isset($siplist[$k])) {
			unset($this->__var_domainipaddress[$k]);
		}
	}
*/






	if ($this->dbaction === 'update' && $this->subaction !== 'full_update' && $this->subaction !== 'fixipdomain') {
		return ;
	}

	if ($this->dbaction === 'add') {
		$this->__var_parent_contactemail = $this->getTrueParentO()->getTrueParentO()->contactemail;
		$this->__var_clientname = $this->getTrueParentO()->getTrueParentO()->nname;
	}

	$this->__var_vdomain_list = $mydb->getRowsWhere($condition, $params, array('nname', 'ipaddress'));
	/*
	$string = "ttype='forward' AND syncserver = '$syncserver'" ;
	$this->__var_fdomain_list = $mydb->getRowsWhere($string, array('nname'));
	*/
}



function getQuotaNeedVar()
{
	return array("nname" => $this->nname, "customer_name" => $this->getRealClientParentO()->getPathFromName());
}


function isRealQuotaVariable($k)
{
	$list['disk_usage'] = 'a';
	return isset($list[$k]);
}

function runStats()
{
	log_log("run_stats", "Running stats");
	$list[$this->nname] = $this;
	webtraffic::run_awstats($this->__var_statsprog, $list);
}

function getQuotadisk_usage() 
{
	global $gbl, $sgbl, $login, $ghtml; 
	if (isset($sgbl->__var_diskusage[$this->nname])) {
		return $sgbl->__var_diskusage[$this->nname] ;
	} else {
		return $this->used->disk_usage;
	}
}


function inheritSynserverFromParent() { return false; }
function extraBackup() { return false; }
function extraRestore() { return true; }

function getFfileFromVirtualList($name)
{
	$name = coreFfile::getRealpath($name);
	$htroot = $this->getFullDocRoot();
	$confroot = "__path_httpd_root/$this->nname/";
	if ($name === '__lx_error_log') {
		$root = "$confroot/stats/";
		$name = "{$this->nname}-error_log";
		$readonly = 'on';
		$showheader = false;
		$numlines = '20';
		$extraid = "__lx_error_log";

		if ($this->__driverappclass === 'lighttpd') {
			rl_exec_get(null, $this->syncserver, array("web__lighttpd", 'fixErrorLog'), array($this->nname));
		}

	} else if ($name === '__lx_access_log') {
		$root = "$confroot/stats/";
		$name = "{$this->nname}-custom_log";
		$readonly = 'on';
		$showheader = false;
		$numlines = '20';
		$extraid = "__lx_access_log";
	} else if ($name === '__lx_php_log') {
		$root = "/home/$this->customer_name/__processed_stats/";
		$name = "{$this->nname}.phplog";
		$readonly = 'on';
		$showheader = false;
		$numlines = '20';
		$extraid = "__lx_php_log";
	} else {
		$root = $htroot;
		$readonly = 'off';
		$showheader = true;
		$name = '/' . $name;
		$numlines = null;
		$extraid = null;
	}

	$ffile= new Ffile($this->__masterserver, $this->syncserver, $root, $name, $this->username);
	$ffile->__parent_o = $this;
	$ffile->get();
	$ffile->readonly = $readonly;
	$ffile->__flag_showheader = $showheader;
	$ffile->numlines = $numlines;
	$ffile->__var_extraid = $extraid;
	return $ffile;
}

function isRealChild($c)
{
	if ($this->ttype === 'virtual') {
		return true;
	}

	return false;

}

function updateRun_stats($param)
{
	global $gbl, $sgbl, $login, $ghtml; 
	$param['noval'] = 'a';
	return $param;
}


function getMultiUpload($var)
{
	if ($var === 'ssl_config_m') {
		return array("enable_ssl_flag");
	}
	return $var;

}

static function findTotalUsage($driver, $list)
{
	foreach($list as $k => $d) {
		$tlist[$k] = self::web_getdisk_usage($d['customer_name'], $d['nname']);
	}
	return $tlist;
}

static function web_getdisk_usage($customer_name, $domainname)
{
	global $gbl, $sgbl, $login, $ghtml; 

	return;

	//$path[] = "__path_customer_root/$customer_name/$domainname";
	$path[] = "__path_customer_root/$customer_name/__processed_stats/$domainname";
	$path[] = "__path_program_home/domain/$domainname/__backup/";
	//$path[] = "__path_httpd_root/$domainname";

	$t = 0;
	foreach($path as $p) {
		$t += lxfile_dirsize($p);
	}
	return $t;
}

function deleteDir()
{
	global $gbl, $sgbl, $login, $ghtml; 
	
	if (!$this->customer_name) { return; }
	if (!$this->nname) { return; }

	recursively_remove("$sgbl->__path_customer_root/{$this->customer_name}/__processed_stats/{$this->nname}");
	recursively_remove("$sgbl->__path_program_home/domain/{$this->nname}");
	recursively_remove("$sgbl->__path_httpd_root/{$this->nname}");
	recursively_remove("$sgbl->__path_kloxo_httpd_root/awstats/dirdata/{$this->nname}");
	lxfile_rm("__path_real_etc_root/awstats/awstats.{$this->nname}.conf");
	lxfile_rm_rec("/var/lib/webalizer/{$this->nname}");
	lxfile_rm("/etc/webalizer/webalizer.{$this->nname}.conf");

}

function webChangeOwner()
{

	if (!lxfile_exists("{$this->getFullDocRoot()}")) {
		lxfile_cp_rec("__path_customer_root/$this->__var_oldcustomer_name/$this->docroot", "{$this->getFullDocRoot()}");
	}
	lxfile_unix_chown_rec("{$this->getFullDocRoot()}", "$this->username:$this->username");

	lunlink("__path_httpd_root/$this->nname/httpdocs");

}

function getFullDocRoot()
{
	if (!$this->docroot) { $this->docroot = $this->nname; }
	$path = "__path_customer_root/$this->customer_name/$this->docroot";
	$path = expand_real_root($path);
	return $path;
}

function getParentFullDocRoot()
{
	if (!$this->docroot) {
		$parent = $this->nname;
	} else {
		$parent = $this->docroot;
		$pos = strpos($parent, '/');
		if ($pos > 0) {
			$parent = substr($parent, 0, $pos);
		}
	}
	$path = "__path_customer_root/$this->customer_name/$parent";
	$path = expand_real_root($path);
	return $path;
}

function getCustomerRoot()
{
	$path = "__path_customer_root/$this->customer_name";
	$path = expand_real_root($path);
	return $path;
}

function getDirprotectFromVirtualList($name)
{
	$list = 'dirprotect_l';
	$this->initListIfUndef('dirprotect');

	if (isset($this->{$list}[$name])) {
		return $this->{$list}[$name];
	}

	$dirp = new dirprotect($this->__masterserver, $this->__readserver, $name);
	$dirp->status = 'nonexistant';
	$dirp->__parent_o = $this;
	return $dirp;
}


function do_backup()
{
	global $gbl, $sgbl, $login, $ghtml; 
	$name = $this->nname; $fullpath = "$sgbl->__path_customer_root/{$this->customer_name}/$name/";
	lxfile_mkdir($fullpath);
	$list = lscandir_without_dot_or_underscore($fullpath);
	return array($fullpath, $list);
}

function do_restore($docd)
{
	global $gbl, $sgbl, $login, $ghtml; 
	$name = $this->nname; $fullpath = "$sgbl->__path_customer_root/{$this->customer_name}/$name/";
	lxfile_mkdir($fullpath);
	lxshell_unzip_with_throw($fullpath, $docd);
}



function makeDnsChanges($newserver)
{
	$ip = getOneIPForServer($newserver);
	$dns = $this->getParentO()->getObject('dns');

	$dns->dns_record_a['a___base__']->param = $ip;
	$dns->setUpdateSubaction('subdomain');
	$dns->was();
	$var = "webpserver";
	$domain = $this->getParentO();
	$domain->$var = $newserver;
	$domain->setUpdateSubaction();
	$domain->write();
}


function createPhpInfo()
{
	$domname = $this->nname;
	if (!lxfile_exists("__path_customer_root/{$this->username}/kloxoscript")) {
		lxfile_mkdir("__path_customer_root/{$this->username}/kloxoscript/");
		lxfile_cp("../file/script/phpinfo.phps", "__path_customer_root/{$this->username}/kloxoscript/phpinfo.php");
		lxfile_unix_chown_rec("__path_customer_root/$this->username/kloxoscript", "{$this->username}:{$this->username}");
	}
}



function createDir()
{	

	global $gbl, $sgbl, $login, $ghtml; 

	if (!$this->customer_name) {
		log_log("critical", "Lack customername for web: {$this->nname}");
		return;
	}

	
	$web_home = $sgbl->__path_httpd_root ;
	$base_root = $sgbl->__path_httpd_root;
	$v_dir 		= "$web_home/{$this->nname}/conf";
	$log_path 	= "$web_home/{$this->nname}/stats";
	$cgi_path   = "{$this->getFullDocRoot()}/cgi-bin/";
	$log_path1 	= "$log_path/";
	$cust_log 	= "$log_path1/{$this->nname}-custom_log"; 
	$err_log 	= "$log_path1/{$this->nname}-error_log";
	$awstat_conf 	= "$sgbl->__path_real_etc_root/awstats/";
	$awstat_dirdata 	= "$sgbl->__path_kloxo_httpd_root/awstats/";
	$user_home = $this->getFullDocRoot();

	if (!lxfile_exists("{$this->getCustomerRoot()}/public_html")) {
		lxfile_symlink($this->nname, "{$this->getCustomerRoot()}/public_html");
	}

	$domname = $this->nname;
    
 
	/*
	print("+++++++++++++++++++++++++++++++++++++++++++++++++++++");

	print("This is the Conf file Path  $v_dir 		=" );
    print("This is the LogPath  	$log_path 	= ");
    print("This is the  LogPath $log_path1 	="); 
    print("This is the Custom  LogPath $cust_log 	=" );
    print("This is the Error LoG Path  $err_log 	= ");
	print("$stat_conf 	= ");
	print("This is THE User Home $user_home = ");	

	print("+++++++++++++++++++++++++++++++++++++++++++++++++++");

*/

	// Protection for webstats.

	$new_user_dir = false;
	lxfile_mkdir($user_home);
	if ((count(lscandir_without_dot($user_home)) == 0) && isset($this->__var_skelfile) && $this->__var_skelfile) {
		$this->getAndUnzipSkeleton($this->__var_skelmachine, $this->__var_skelfile, "$user_home/");
		$new_user_dir = true;
	}
	lxfile_mkdir("$web_home/$domname/webstats");

	$wsstring = "Stats not yet generated\n";

	lfile_put_contents("$web_home/$domname/webstats/index.html", $wsstring);

	lxfile_mkdir($cgi_path);


	lxfile_mkdir($user_home);


	// Sort of hack.. Changes the domain.com/domain.com to domain.com/httpdocs. Which is easier to remember. Slowly we need to change all the code from dom/dom to dom/httpdocs.. but for now, just create a symlink.

	lxfile_generic_chmod("$web_home/{$this->nname}", "0755");
	lxfile_mkdir("$user_home/");


	lxfile_generic_chmod($user_home, "0755");



	lxfile_mkdir($v_dir);
	lxfile_mkdir($log_path);
	//lxfile_mkdir($log_path1);
	lxfile_mkdir("__path_apache_path/kloxo");
	lxfile_touch("__path_apache_path/kloxo/virtualhost.conf");

	$parent_doc_root = $this->getParentFullDocRoot();
	if ($user_home != $parent_doc_root) {
		lxfile_generic_chown_rec($parent_doc_root, "{$this->username}:{$this->username}");
	} else {
		lxfile_generic_chown_rec($user_home, "{$this->username}:{$this->username}");
	}
	if ($new_user_dir) {
		lxfile_generic_chmod_rec($user_home, "755");
	}
	lxfile_generic_chown($user_home, "{$this->username}:apache");
	lxfile_generic_chown("__path_customer_root/$this->customer_name", "{$this->username}:apache");
	lxfile_generic_chmod("__path_customer_root/$this->customer_name", "750");
	lxfile_generic_chown($log_path1, "apache:apache");
	lxfile_generic_chmod($log_path1, "770");
	lxfile_generic_chown("$web_home/{$this->nname}", "{$this->username}:apache");

	if (!lxfile_exists("$web_home/{$this->nname}/httpdocs")) {
		//lxfile_mkdir("$sgbl->__path_customer_root/$this->customer_name/domain/$this->nname");
		//lxfile_symlink("{$this->getFullDocRoot()}", "$sgbl->__path_customer_root/$this->customer_name/domain/$this->nname/www");
		lxfile_symlink("{$this->getFullDocRoot()}", "$web_home/$this->nname/httpdocs");
		//lxfile_symlink("$web_home/{$this->nname}/httpdocs", "$web_home/{$this->nname}/{$this->nname}");
	}

	$this->createstatsConf($this->nname, $this->stats_username, $this->stats_password);
/*	print("This is the User Home : $user_home \n");                                                   
	print("This is the certificate Pah : $sgbl->__path_ssl_root/certificate/\n");            
	print("This is the Private Key Pah: $sgbl->__path_ssl_root/privatekey/\n");             
	print("This is the Domain Name :$web_home/{$this->nname}\n");                              
	
	print( "This is teh User Httpdocs  :$user_home/www/");                                         
    print("GO to the User Dir (chmod 775");                                                    
	print("Chown To The :{$this->username}:{$this->username}, $user_home\n");  
	print("This is the Vdir :  $v_dir\n");                                                         
	print("Creating log path :$log_path\n");                                                            
	print("Creating Dir:$log_path1\n");                                                           
	print("Touching :$sgbl->__path_apache_path/kloxo\n");                           
	print("Touching Virtual hOPs$sgbl->__path_apache_path/kloxo/virtualhost.conf\n");                  
	print("$err_log\n");                                                             
	print("Install ALL : $install_all\n");
	print("chown  :{$this->username} , $web_home/{$this->nname}\n");
	exit;
*/

	dprint("end\n");

}

static function createstatsConf($domname, $stats_name, $stats_password)
{
    $inp = "__path_program_root/file/webalizer.model.conf";
	$outp = "__path_real_etc_root/webalizer/webalizer.$domname.conf";
	self::docreatestatsConf($inp, $outp, $domname, $stats_name, $stats_password);
	lxfile_mkdir("/var/lib/webalizer/$domname");
	lxfile_mkdir("__path_httpd_root/$domname/webstats/webalizer/");

    $inp = "__path_program_root/file/awstats.model.conf";
    $outp = "__path_real_etc_root/awstats/awstats.$domname.conf";
	self::docreatestatsConf($inp, $outp, $domname, $stats_name, $stats_password);
	//lxfile_cp("__path_real_etc_root/awstats/awstats.$domname.conf", "__path_real_etc_root/awstats/awstats.www.$domname.conf");
	lxfile_mkdir("/home/kloxo/httpd/awstats/dirdata/$domname");

}


static function docreatestatsConf($inp, $outp, $domain, $stats_name, $stats_password)
{
	global $gbl, $sgbl, $login, $ghtml; 
    $filecontents = lfile($inp);

	foreach($filecontents as &$f) {

		if (preg_match("/_lx_domain_name_/", $f)) {
			$f = preg_replace("/_lx_domain_name_/", $domain, $f);
		}

		if (preg_match("/_lx__path_httpd_root/", $f)) {
			$f = preg_replace("/_lx__path_httpd_root/", $sgbl->__path_httpd_root, $f);
		}

		$regexdom = str_replace('.', '\.', $domain);
		$regexdom .= "$";

		if (preg_match("/_lxregex_domain_name_/", $f)) {
			$f = preg_replace("/_lxregex_domain_name_/", $regexdom, $f);
		}

		if (preg_match("/_lx_authentic_user/", $f)) {
			$f = preg_replace("/_lx_authentic_user/", $stats_name, $f);
		}
		if (preg_match("/_lx_dns_lookup_/", $f)) {
			$f = preg_replace("/_lx_dns_lookup_/", "1", $f);
		}

		$st_pro = "0";
		if ($stats_password) { $st_pro = "1"; }

		if (preg_match("/_lx_stats_protect/", $f)) {
			$f = preg_replace("/_lx_stats_protect/", $st_pro, $f);
		}
	}

	$filecontents = implode("", $filecontents);
	lxfile_mkdir(dirname($outp));
	lfile_put_contents($outp, $filecontents);
	lxfile_generic_chmod($outp, "0744");
 }

function getShowInfo()
{
	//return "Primary Ftp User: $this->ftpusername; Subdomains: {$this->used->subweb_a_num}";
}

function hasFileResource() { return true; }
function createShowClist($subaction)
{
	global $gbl, $sgbl, $login, $ghtml; 
	$clist = null;
	if ($this->ttype === 'virtual') {
	}
	return $clist;
}


static function add($parent, $class, $param)
{
	return $param;
}

function updatePhpInfo($param)
{
	global $gbl, $sgbl, $login, $ghtml; 
	$ar['ip_address'] = $gbl->c_session->ip_address;
	$ar['session'] = $gbl->c_session->tsessionid;
	rl_exec_get(null, $this->syncserver, array("web", "createSession"), array($ar));
	$servar = base64_encode(serialize($ar));
	$gbl->__this_window_url = "http://$this->nname/__kloxo/phpinfo.php?session=$servar";
	return null;
}

static function createSession($ar)
{
	$tsess['name'] = $ar['session'];
	$tsess['ip_address'] = $ar['ip_address'];
	lfile_put_serialize("/home/kloxo/httpd/script/sess_{$tsess['name']}", $tsess);
}


function createShowPropertyList(&$alist) 
{ 

	$alist['property'][] = 'a=show';
	//$alist['property'][] = "goback=1&o=mmail&a=list&c=mailaccount";
	//$alist['property'][] = 'goback=1&a=show&sa=config';

}

static function removeOtherDriver($driverapp)
{
	global $gbl, $sgbl, $login, $ghtml; 
	if ($driverapp === 'apache') {
		@ exec("rpm -e --nodeps lighttpd 2>/dev/null");
		lunlink("/etc/init.d/lighttpd");
	} else if ($driverapp === 'lighttpd') {
		@ exec("rpm -e --nodeps httpd 2>/dev/null");
	}
}

static function switchProgramPre($old, $new)
{
	if ($new === 'apache') {
		$ret = lxshell_return("yum", "-y", "install", "httpd", "mod_ssl");
		if ($ret) { throw new lxexception('install_httpd_failed', 'parent'); }
		lxshell_return("service",  "lighttpd", "stop");
		lxshell_return("rpm", "-e", "--nodeps", "lighttpd");
		lunlink("/etc/init.d/lighttpd");
		lxshell_return("chkconfig", "httpd", "on");
	} else {
		$ret = lxshell_return("yum", "-y", "install", "lighttpd", "lighttpd-fastcgi");
		if ($ret) { throw new lxexception('install_lighttpd_failed', 'parent'); }
		lxfile_unix_chmod("/etc/init.d/lighttpd", "0755");
		lxshell_return("service", "httpd", "stop");
		lxshell_return("rpm", "-e", "--nodeps", "httpd");
		lxshell_return("chkconfig", "lighttpd", "on");
	}


	if ($new === 'apache') {
		addLineIfNotExistInside("/etc/httpd/conf/httpd.conf", "Include /etc/httpd/conf/kloxo/kloxo.conf", "");
		lxshell_return("__path_php_path", "../bin/misc/installsuphp.php");
		//lxshell_return("__path_php_path", "../bin/fix/fixfrontpage.php");
	} else {
		lxfile_mkdir("/etc/lighttpd/");
		lxfile_mkdir("/etc/lighttpd/conf/kloxo");
		lxfile_cp("../file/lighttpd/lighttpd.conf", "/etc/lighttpd/lighttpd.conf");
		lxfile_cp("../file/lighttpd/conf/kloxo/kloxo.conf", "/etc/lighttpd/conf/kloxo/kloxo.conf");
		lxfile_cp("../file/lighttpd/conf/kloxo/webmail.conf", "/etc/lighttpd/conf/kloxo/webmail.conf");
		lxfile_cp("../file/lighttpd/etc_init.d", "/etc/init.d/lighttpd");
		lxfile_unix_chmod("/etc/init.d/lighttpd", "0755");
		lxfile_mkdir("/home/kloxo/httpd/lighttpd");
		lxfile_unix_chown("/home/kloxo/httpd/lighttpd", "apache");
	}
}

static function switchProgramPost($old, $new)
{
	createRestartFile($new);
}



function createShowAlist(&$alist, $subaction = null)
{
	global $gbl, $sgbl, $login, $ghtml; 

	return $alist;
}


static function get_full_alist()
{

	$alist['__title_class_web'] = '__title_class_web';

	//$alist[] = "a=list&c=webindexdir_a";
	
	$alist[] = "a=list&c=dirprotect";
	$alist[] = "a=show&l[class]=ffile&l[nname]=/";
	$alist[] = "a=list&c=ftpuser";
	$alist[] = 'a=list&c=ftpsession';



	//$this->getSwitchServerUrl($alist);

	//$alist[] = "a=updateForm&sa=ipaddress";

	$alist['__title_script'] = 'script';
	$alist[] = create_simpleObject(array('url' => "http://nname/__kloxo/phpinfo.php", 'purl' => 'a=updateform&sa=phpinfo', 'target' => "target='_blank'")); 

	$alist[] = "a=show&o=phpini";
	$alist[] = "a=updateform&sa=lighty_rewrite";
	$alist[] = "a=list&c=component";

	$alist[] = "a=updateform&sa=permalink";

	$alist[] = "a=show&k[class]=allinstallapp&k[nname]=installapp";

	/*
	$alist['action'][] = "a=update&sa=backup";
	$alist['action'][] = "a=updateform&sa=restore";
	*/
	/*
	if ($this->priv->isOn('frontpage_flag')) {
		$alist[] = create_simpleObject(array( 'url' => "http://$this->nname:8080", 'purl' => 'a=update&sa=frontpage_admin&l[class]=web&l[nname]=$this->nname', 'target' => "target='_blank'")); 
	}
*/

	return $alist;

}

function createGraphList()
{
	$alist[] = "a=graph&sa=webtraffic";
	return $alist;
}

function isDomainVirtual()
{
	return ($this->ttype === 'virtual');
}


function preSync()
{
	//Syncing uuser before everything else. If uuser is not there, everythign else will get fucked up...
	if ($this->isDomainVirtual() && ($this->dbaction === 'add' || $this->dbaction === 'syncadd')) {
		//$this->getObject('uuser')->was();
	}
}

function isQuotaVariableSpecific($var)
{
	global $gbl, $sgbl, $login, $ghtml; 
	if ($var === 'frontpage_flag') {
		$v = db_get_value("pserver", $this->syncserver, "osversion");
		if (csa($v, " 5")) { return false; }
		$driverapp = $gbl->getSyncClass(null, $this->syncserver, 'web');
		if ($driverapp === 'lighttpd') { return false; }
	}
	return true;

}


function updatepermalink($param)
{

	$name = $param['lighty_pretty_app_f'];
	$path = $param['lighty_pretty_path_f'];

	$list = lfile_trim("../file/prettyurl/$name");
	$list[0] = trimSpaces($list[0]);
	list($t, $type, $typen) = explode(" ", $list[0]);

	array_shift($list);
	if ($type === '404' || $typen === '404') {
		$this->customerror_b->url_404 = str_replace("<%lxpath%>", $path, $list[0]);
		array_shift($list);
	}

	if ($type === 'rewrite' || $typen === 'rewrite') {
		$string = implode("\n", $list);
		$this->text_lighty_rewrite = str_replace("<%lxpath%>", $path, $string);
	}

	return $param;

}


function updateCron_mailto($param)
{

	$cronlist = $this->getList('cron');
	if ($cronlist) {
		$cron = arrayGetFirstObject($cronlist);
		$cron->setUpdateSubaction('update');
		$cron->syncToSystem();
	}

	return $param;
}

function updateExtra_Tag($param)
{
	if_not_admin_complain_and_exit();
	if (isset($param['extra_tag_file'])) {
		$param['text_extra_tag'] = lfile_get_contents($param['extra_tag_file']);
	}
	return $param;
}

function updateDirindex($param)
{
	$param['indexfile_list'] = lxclass::fixListVariable($param['indexfile_list']);
	return $param;
}


function isWebVirtual()
{
	return ($this->ttype === 'virtual');
}



function updateSesubmit($param)
{
	global $gbl, $sgbl, $login, $ghtml; 
	callInBackground("se_submit", array($login->contactemail, $this->nname, $param['email']));
	throw new lxException("se_submit_running_background", '', $this->nname);
}

function updateform($subaction, $param)
{
	global $gbl, $sgbl, $login, $ghtml; 
	
	$driverapp = $gbl->getSyncClass(null, $this->__readserver, 'web');

	switch($subaction) {


		case "run_stats":
			$vlist['confirm_f'] = array('M', "");
			$vlist['__v_updateall_button'] = array();
			return $vlist;


		case "sesubmit":
			include "sesubmit/engines.php";
			$selist = array_keys($enginelist);
			$selist = implode("\n", $selist);
			$selist = "\n$selist";

			$vlist['nname'] = array('M', $this->nname);
			$vlist['email'] = null;
			$vlist['selist'] = array('M', $selist);
			return $vlist;

		case "docroot":
			$vlist['docroot'] = null;
			return $vlist;

		case "blockip":
			$vlist['text_blockip'] = null;
			$vlist['__v_updateall_button'] = array();
			return $vlist;


		case "fcgi_config":
			$vlist['fcgi_children'] = null;
			$vlist['__v_updateall_button'] = array();
			return $vlist;

		case "statsconfig":
			$vlist['remove_processed_stats'] = null;
			$vlist['__v_updateall_button'] = array();
			return $vlist;

		case "hotlink_protection":
			$vlist['hotlink_flag'] = null;
			$vlist['text_hotlink_allowed'] = array("t", null);
			$vlist['hotlink_redirect'] = array("L", "/");
			$vlist['__v_updateall_button'] = array();
			return $vlist;

		case "permalink":
			$list = lscandir_without_dot_or_underscore("../file/prettyurl/");
			$vlist['lighty_pretty_app_f'] = array('s', $list);
			$vlist['lighty_pretty_path_f'] = null;
			return $vlist;

		case "lighty_rewrite":
			$vlist['text_lighty_rewrite'] = null;
			$vlist['__v_updateall_button'] = array();
			return $vlist;
		
		case "stats_protect":
			if ($this->stats_username === $this->nname) {
				$vlist['stats_username'] = array('M', $this->stats_username);
			} else {
				$vlist['stats_username'] = null;
			}
			$vlist['stats_password'] = null;
			$vlist['__v_updateall_button'] = array();
			return $vlist;


		case "cron_mailto":
			$vlist['cron_mailto'] = null;
			return $vlist;

		case "configure_misc":
			$vlist['force_www_redirect'] = null;
			if ($driverapp === 'apache') {
				$vlist['webmisc_b-execcgi'] = null;
				if ($login->isAdmin()) {
					$vlist['webmisc_b-disable_openbasedir'] = null;
				}
			}
		
			$vlist['__v_updateall_button'] = array();
			return $vlist;

		case "dirindex":
			$vlist['webmisc_b-dirindex'] = null;
			if (!$this->indexfile_list) {
				//$this->indexfile_list = get_web_index_list();
			}

			$ol = array("index.php", "index.html", "index.shtml", "index.htm", "default.htm",  "Default.aspx", "Default.asp", "index.pl");
			$dirin = $login->getObject('genlist')->dirindexlist_a;
			$list = get_namelist_from_objectlist($dirin);
			$index = lx_array_merge(array($list, $ol));
			$vlist['indexfile_list'] = array('U', $index);
			$vlist['__v_updateall_button'] = array();
			return $vlist;

		case "extra_tag":
			$vlist['text_extra_tag'] = null;
			return $vlist;

		case "custom_error":
			if ($driverapp !== 'lighttpd') {
				$vlist['customerror_b_s_url_400'] = array("L", "/");
				$vlist['customerror_b_s_url_401'] = array("L", "/");
				$vlist['customerror_b_s_url_403'] = array("L", "/");
				$vlist['customerror_b_s_url_500'] = array("L", "/");
			}
			$vlist['customerror_b_s_url_404'] = array("L", "/");
			$vlist['__v_updateall_button'] = array();

			return $vlist;

		case "ssl_upload":
			$vlist['ssl_key_file_f'] = null;
			$vlist['ssl_crt_file_f'] = null;
			return $vlist;

		case "ipaddress":
			if ($this->getParentO()->isLogin()) {
				$vlist['ipaddress'] = array('M', $this->ipaddress);
				return $vlist;
			}

				//Just parent is domain.. The client is above that...
			$parent = $this->getParentO()->getParentO();
			$iplist = $parent->getIpaddress(array($this->syncserver));
			if (!$iplist) {
				//dprintr($parent->__parent_o);
				$iplist = getAllIpaddress();
				
			}
			$vlist['ipaddress'] = array('s', $iplist);
			return $vlist;
	}

	return parent::updateform($subaction, $param);

}

static function getSelectList($parent, $var)
{

	global $gbl, $sgbl, $login, $ghtml; 

	switch($var) {

		case "ipaddress":
			{
				$iplist = $parent->getIpaddress(array($param['web_s_syncserver']));
				if (!$iplist) {
					//dprintr($parent->__parent_o);
					throw new lxException("no_ip_pool_in_parent", 'ipaddresslist');
				}
				return lx_array_keys($iplist);
			}

	}
}

function doStatsPageProtection()
{
	$filename = $this->getStatsProtectFileName();
	$dir = dirname($filename);
	$owner = "{$this->username}:apache";

	$password = crypt($this->stats_password);
	$content = "{$this->stats_username}:$password\n";

	lxuser_mkdir($owner, $dir);
	lxfile_generic_chmod($dir, '750');
	lxuser_put_contents($owner, $filename,  $content);
	lxfile_generic_chmod($filename, '750');
}

function getStatsProtectFileName()
{
	global $sgbl; 
	$dir = "{$sgbl->__path_httpd_root}/{$this->nname}/__dirprotect";
	$filename = "$dir/__stats";
	return $filename;
}

function getAndUnzipSkeleton($ip, $filepass, $dir)
{
	$oldir = getcwd();
	// File may be a variable path.
	dprintr($filepass);
	$file = $filepass['file'];
	// The thing is this needs to be executed even on secondary master and then the primary master would be down. So if we cannot connect back, we just continue. Skeleton is not an important thing.
	try {
		getFromFileserv($ip, $filepass, "$dir/$file");
	} catch (exception $e) {
		return;
	}
	lxfile_generic_chown("$dir/$file", $this->username);
	lxshell_unzip($this->username, $dir, "$dir/$file");
	lunlink("$dir/$file");

	$this->replaceVariables("$dir/index.html");
}

// Please note that this function is executed in the backend and thus the parent is not available.
function replaceVariables($filename)
{
	$cont = lfile_get_contents($filename);
	$cont = str_replace("<%domainname%>", $this->nname, $cont);
	$cont = str_replace("<%contactemail%>", $this->__var_parent_contactemail, $cont);
	$cont = str_replace("<%clientname%>", $this->__var_clientname, $cont);
	lxuser_put_contents($this->username, $filename, $cont);
}


static function initThisList($parent, $class)
{
	if ($parent->get__table() != 'sslipaddress') {
		dprint("Someting wrong..");
		exit;
	}
	return null;

}




}
