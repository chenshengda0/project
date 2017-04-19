<?php
/*
 * qjcms
 * 
 */

class es_cookie 
{
    // 判断Cookie是否存在
    static function is_set($name) {
        return isset($_COOKIE[$name]);
    }

    // 获取某个Cookie值
    static function get($name) {
        $value   = $_COOKIE[$name];
        return $value;
    }

    // 设置某个Cookie值
    static function set($name,$value,$expire='',$path='',$domain='') {   
    	$path = sysconf("COOKIE_PATH");
     	$domain = sysconf("DOMAIN_ROOT");
        $expire =   !empty($expire)?time()+$expire:0;
        setcookie($name, $value,$expire,$path,$domain);
    }

    // 删除某个Cookie值
    static function delete($name) {
        es_cookie::set($name,'',0);
    }

    // 清空Cookie值
    static function clear() {
        unset($_COOKIE);
    }
}
?>