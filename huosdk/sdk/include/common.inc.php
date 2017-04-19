<?php
	header("Content-Type: text/html; charset=utf-8");
	date_default_timezone_set("PRC");
	define('IN_SYS',TRUE);
	
	define('SYS_ROOT',substr(dirname(__FILE__), 0, -7));
	define('CLASS_PATH','include/class/');
	define('IN_SYS',TRUE);
	require_once SYS_ROOT.'include/config.inc.php';
	
	require_once(SYS_ROOT.CLASS_PATH.'Switchlog.class.php');
	require_once(SYS_ROOT.CLASS_PATH.'Db.class.php');
	require_once(SYS_ROOT.CLASS_PATH.'Library.class.php');
	require_once(SYS_ROOT.CLASS_PATH.'Cache.class.php');
	require_once(SYS_ROOT.CLASS_PATH.'Param.class.php');
	
	session_start();
    ini_set('session.gc_maxlifetime',21600); //设置session有效期
	$db = new DB();
	$huosdk_blogflag = true;
?>