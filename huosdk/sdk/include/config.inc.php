<?php
/**
 * config.inc.php UTF-8
 * @date: 2015年10月15日下午5:41:35
 * SDK配置文件
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@1tsdk.com>
 * @version : 1.0
 */
if (!defined('IN_SYS')) {
    exit('Access Denied');
}

define('SITE_PATH', substr(dirname(__FILE__), 0, -12)."/");

if(file_exists(SITE_PATH."conf/db.php")){
    $dbconf = include SITE_PATH."conf/db.php";
}else{
    $dbconf = array();
}

/*
 * SDK接口数据库配置文件
 */
define('DB_TYPE', $dbconf['DB_TYPE']); // 数据库管理系统
define('DB_HOST', $dbconf['DB_HOST']); // 主机
define('DB_USER', $dbconf['DB_USER']); // 数据库用户
define('DB_PWD', $dbconf['DB_PWD']); // 数据库密码
define('DB_DATABASE', $dbconf['DB_NAME']); // 数据库名称
define('DB_PORT', $dbconf['DB_PORT']); // 数据库端口
define('DB_PREFIX', $dbconf['DB_PREFIX']); // 前缀

define('AUTHCODE', $dbconf['AUTHCODE']);

unset($dbconf);


