<?php
/**
 * 入口文件
 * 
 */
if (ini_get('magic_quotes_gpc')) {
	function stripslashesRecursive(array $array){
		foreach ($array as $k => $v) {
			if (is_string($v)){
				$array[$k] = stripslashes($v);
			} else if (is_array($v)){
				$array[$k] = stripslashesRecursive($v);
			}
		}
		return $array;
	}
	$_GET = stripslashesRecursive($_GET);
	$_POST = stripslashesRecursive($_POST);
}
//开启调试模式
define("APP_DEBUG", FALSE);
//网站当前路径
define('SITE_PATH', dirname(__FILE__)."/");

//项目路径，不可更改
define('APP_PATH', SITE_PATH . 'admin/app/');
//项目相对路径，不可更改
define('SPAPP_PATH',   SITE_PATH.'thinkphp/');
//
define('SPAPP',   './admin/app/');
//项目资源目录，不可更改
define('SPSTATIC',   SITE_PATH.'public/');
//定义缓存存放路径
define("RUNTIME_PATH", SITE_PATH . "admin/runtime/");
//静态缓存目录
define("HTML_PATH", SITE_PATH . "admin/runtime/Html/");

define("THINKCMF_CORE_TAGLIBS", 'cx,Common\Lib\Taglib\TagLibSpadmin,Common\Lib\Taglib\TagLibHome');

if(file_exists(SITE_PATH."conf/domain.inc.php")){
    include SITE_PATH."conf/domain.inc.php";
}else{
    exit;
}

ini_set('session.name', 'HUOSDK_ADMINID');

//载入框架核心文件
require SPAPP_PATH.'Core/ThinkPHP.php';

