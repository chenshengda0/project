<?php 
	if(!defined("ADMIN_ROOT")){
		exit("no promission");
	}
	
    define('APP_NAME', 'Administrator');
    define('APP_PATH', './Administrator/');
    define('APP_DEBUG', true);//开启调试模式TRUE 关闭调试模式false
    
	require "./system/system_init.php";
    
    
    
?>