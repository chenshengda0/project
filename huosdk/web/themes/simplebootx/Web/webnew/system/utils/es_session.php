<?php
/*
 * qjcms
 * 
 */

//引入数据库的系统配置及定义配置函数
require_once ROOT_PATH."system/utils/es_cookie.php";


class es_session 
{	
	static function id()
	{		
		return session_id();
	}
	static function start()
	{
		session_set_cookie_params(0,sysconf("COOKIE_PATH"),sysconf("DOMAIN_ROOT"));
		@session_start();
	}
	
    // 判断session是否存在
    static function is_set($name) {    	
        return isset($_SESSION[sysconf("AUTH_KEY").$name]);
    }

    // 获取某个session值
    static function get($name) {

        $value   = $_SESSION[sysconf("AUTH_KEY").$name];
        return $value;
    }

    // 设置某个session值
    static function set($name,$value) {   

        $_SESSION[sysconf("AUTH_KEY").$name]  =   $value;
    }

    // 删除某个session值
    static function delete($name) {

        unset($_SESSION[sysconf("AUTH_KEY").$name]);
    }

    // 清空session
    static function clear() {

        session_destroy();
    }
    
    //关闭session的读写
    static function close()
    {

    	@session_write_close();
    }
    
    static function  is_expired()
    {

        if (isset($_SESSION[sysconf("AUTH_KEY")."expire"]) && $_SESSION[sysconf("AUTH_KEY")."expire"] < time()) {
            return true;
        } else {        	
        	$_SESSION[sysconf("AUTH_KEY")."expire"] = time()+(intval(sysconf("EXPIRED_TIME"))*60);
            return false;
        }
    }
}
?>