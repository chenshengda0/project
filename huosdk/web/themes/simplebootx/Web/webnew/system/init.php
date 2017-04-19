<?php
    /*
     * 系统初始化
     * 
     */

    ini_set("display_errors", false);
    @set_magic_quotes_runtime (0);
    define('MAGIC_QUOTES_GPC',get_magic_quotes_gpc()?True:False);
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

    if(!defined("ROOT_PATH")){
        define('ROOT_PATH', str_replace("system/","",str_replace('\\', '/', dirname(__FILE__))."/"));
    }

    require ROOT_PATH."system/pinyin/GetPingYing.php";
    
    
    filter_request($_GET);
    filter_request($_POST);
    
    date_default_timezone_set(sysconf("TIME_ZONE"));
    
    function get_http()
    {
    	return (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';
    }
    function get_domain()
    {
    	/* 协议 */
    	$protocol = get_http();
    
    	/* 域名或IP地址 */
    	if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
    	{
    		$host = $_SERVER['HTTP_X_FORWARDED_HOST'];
    	}
    	elseif (isset($_SERVER['HTTP_HOST']))
    	{
    		$host = $_SERVER['HTTP_HOST'];
    	}
    	else
    	{
    		/* 端口 */
    		if (isset($_SERVER['SERVER_PORT']))
    		{
    			$port = ':' . $_SERVER['SERVER_PORT'];
    
    			if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol))
    			{
    				$port = '';
    			}
    		}
    		else
    		{
    			$port = '';
    		}
    
    		if (isset($_SERVER['SERVER_NAME']))
    		{
    			$host = $_SERVER['SERVER_NAME'] . $port;
    		}
    		elseif (isset($_SERVER['SERVER_ADDR']))
    		{
    			$host = $_SERVER['SERVER_ADDR'] . $port;
    		}
    	}
    
    	return $protocol . $host;
    }
    function get_host()
    {
    
    
    	/* 域名或IP地址 */
    	if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
    	{
    		$host = $_SERVER['HTTP_X_FORWARDED_HOST'];
    	}
    	elseif (isset($_SERVER['HTTP_HOST']))
    	{
    		$host = $_SERVER['HTTP_HOST'];
    	}
    	else
    	{
    		if (isset($_SERVER['SERVER_NAME']))
    		{
    			$host = $_SERVER['SERVER_NAME'];
    		}
    		elseif (isset($_SERVER['SERVER_ADDR']))
    		{
    			$host = $_SERVER['SERVER_ADDR'];
    		}
    	}
    	return $host;
    }


    function sysconf($name){
    	 
    	if(isset($GLOBALS["sysconf"][$name])){
    		return $GLOBALS["sysconf"][$name];
    	}else{
    		return false;
    	}
    }


    function lang($name=""){
    	if(isset($GLOBALS["lang"][$name])){
    		return $GLOBALS["lang"][$name];
    	}else{
    		return $name;
    	}
    }
    

     global $sysconf,$action;
     $sysconf = require_once ROOT_PATH."system/config.php";
    
     $action = require_once ROOT_PATH."public/lang/admin_action.php";
    
    
    require ROOT_PATH.'system/utils/es_key.php';
    
    $es_key = new es_key();
    
    if(file_exists(ROOT_PATH."public/runtime/~core.php")){
        require_once ROOT_PATH."public/runtime/~core.php";
    }else{
        $str = file_get_contents(ROOT_PATH."license");
        
        $a = $es_key->decrypt($str, "xiucms");
        @file_put_contents(ROOT_PATH."public/runtime/~core.php", $a);
        
        require_once ROOT_PATH."public/runtime/~core.php";
    }

?>